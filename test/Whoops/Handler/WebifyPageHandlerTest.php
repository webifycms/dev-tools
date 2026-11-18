<?php

/**
 * 	The file is part of the "webifycms/dev-tools", WebifyCMS development tools.
 *
 * 	@see https://webifycms.com/tools/development-tools
 *
 * 	@license Copyright (c) 2022 WebifyCMS
 * 	@license https://webifycms.com/extension/tools/license
 * 	@author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Tools\Test\Whoops\Handler;

use PHPUnit\Framework\Attributes\{CoversClass, Small, Test};
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use Webify\Tools\Whoops\Handler\WebifyPageHandler;
use Whoops\Run;

/**
 * Unit tests for the WebifyCMS development error page.
 *
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(WebifyPageHandler::class)]
#[Small]
final class WebifyPageHandlerTest extends TestCase
{
	/**
	 * The layout must carry the logo, the new design, the error and its tooling.
	 */
	#[Test]
	public function itRendersTheBrandedErrorPage(): void
	{
		$page = $this->render(new RuntimeException('Boom from the test'));

		self::assertStringContainsString('<title>WebifyCMS — Development Error</title>', $page);
		self::assertStringContainsString('data:image/png;base64,', $page);
		self::assertStringContainsString('class="topbar"', $page);
		self::assertStringContainsString('class="workspace"', $page);
		self::assertStringContainsString('--w-brand: #4f46e5', $page);
		self::assertStringContainsString('RuntimeException', $page);
		self::assertStringContainsString('Boom from the test', $page);
		self::assertStringContainsString('Stack trace', $page);
		self::assertStringContainsString('webifycms/dev-tools', $page);
		self::assertStringContainsString('filp/whoops', $page);
	}

	/**
	 * An application can remove the brand and keep the page neutral.
	 */
	#[Test]
	public function itHidesTheLogo(): void
	{
		$page    = $this->render(new RuntimeException('Boom'), new WebifyPageHandler(logo: ''));
		$logoTag = implode('', ['<img class="topbar__', 'logo"']);

		self::assertStringNotContainsString($this->embeddedLogoPrefix(), $page);
		self::assertStringNotContainsString($logoTag, $page);
		self::assertStringContainsString('class="topbar"', $page);
	}

	/**
	 * A custom logo and its description must reach the rendered image.
	 */
	#[Test]
	public function itUsesACustomLogo(): void
	{
		$handler = new WebifyPageHandler(logo: 'https://example.test/logo.png', logoAlt: 'Acme');
		$page    = $this->render(new RuntimeException('Boom'), $handler);

		self::assertStringContainsString('src="https://example.test/logo.png"', $page);
		self::assertStringContainsString('alt="Acme"', $page);
		self::assertStringNotContainsString($this->embeddedLogoPrefix(), $page);
	}

	/**
	 * An earlier error must stay visible, as it often explains the current one.
	 */
	#[Test]
	public function itShowsEarlierErrors(): void
	{
		$error  = new RuntimeException('The first problem');
		$page   = $this->render(new RuntimeException('The final problem', 0, $error));

		self::assertStringContainsString('Earlier errors', $page);
		self::assertStringContainsString('The first problem', $page);
		self::assertStringContainsString('The final problem', $page);
	}

	/**
	 * Request values such as tokens must never be printed on the error page.
	 */
	#[Test]
	public function itHidesSecretsFromTheRequest(): void
	{
		$secret               = implode('', ['super-', 'secret-value']);
		$previous             = $_SERVER['api_token'] ?? null;
		$_SERVER['api_token'] = $secret;

		try {
			$page = $this->render(new RuntimeException('Boom'));
		} finally {
			if (null === $previous) {
				unset($_SERVER['api_token']);
			} else {
				$_SERVER['api_token'] = $previous;
			}
		}

		self::assertStringNotContainsString($secret, $page);
		self::assertStringContainsString('[redacted]', $page);
	}

	private function embeddedLogoPrefix(): string
	{
		return implode('', ['data:image/png;', 'base64,']);
	}

	private function render(Throwable $error, ?WebifyPageHandler $handler = null): string
	{
		$run = new Run();
		$run->pushHandler($handler ?? new WebifyPageHandler());
		$run->writeToOutput(false);
		$run->allowQuit(false);

		$page = $run->handleException($error);
		self::assertIsString($page);

		return $page;
	}
}
