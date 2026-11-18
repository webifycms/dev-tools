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

namespace Webify\Tools\Whoops;

use Throwable;
use Webify\Tools\Whoops\Handler\WebifyPageHandler;
use Whoops\Handler\{HandlerInterface, PlainTextHandler};
use Whoops\Run;

/**
 * Shows development errors with Whoops.
 *
 * register() installs one handler for the whole script, so uncaught exceptions
 * and PHP errors open the branded error page instead of PHP's plain output.
 * handle() shows the page for a single error without ending the script.
 */
final readonly class Whoops
{
	private Run $run;

	/**
	 * @param null|HandlerInterface $handler a replacement for the default handler
	 * @param null|string           $logo    an image source, or an empty string to hide it
	 * @param null|string           $logoAlt text for readers who cannot see the logo
	 */
	public function __construct(
		?HandlerInterface $handler = null,
		?string $logo = null,
		?string $logoAlt = null,
	) {
		$this->run = new Run();

		$this->run->pushHandler($handler ?? $this->defaultHandler($logo, $logoAlt));
	}

	/**
	 * Makes this instance the handler for all later PHP errors and exceptions.
	 */
	public function register(): void
	{
		$this->run->register();
	}

	/**
	 * Shows the error page for one caught error.
	 *
	 * Whoops ends the script after showing the page unless the runner has been
	 * set with allowQuit(false) through getRun().
	 */
	public function handle(Throwable $error): void
	{
		$this->run->handleException($error);
	}

	/**
	 * Returns the underlying Whoops runner for extra settings and handlers.
	 */
	public function getRun(): Run
	{
		return $this->run;
	}

	/**
	 * Chooses how the error is shown in the current environment.
	 *
	 * A terminal cannot read an HTML page, so command-line tools get plain text
	 * instead of a page made almost entirely of markup.
	 */
	private function defaultHandler(?string $logo, ?string $logoAlt): HandlerInterface
	{
		return PHP_SAPI === 'cli'
			? new PlainTextHandler()
			: new WebifyPageHandler($logo, $logoAlt);
	}
}
