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

namespace Webify\Tools\Test\Whoops;

use PHPUnit\Framework\Attributes\{CoversClass, Small, Test};
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;
use Throwable;
use Webify\Tools\Whoops\Handler\WebifyPageHandler;
use Webify\Tools\Whoops\Whoops;
use Whoops\Handler\{CallbackHandler, Handler, PlainTextHandler};
use Whoops\Run;

/**
 * Unit tests for the Whoops wrapper.
 *
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(Whoops::class)]
#[Small]
final class WhoopsTest extends TestCase
{
	/**
	 * The wrapper must own a runner that can be configured by the caller.
	 */
	#[Test]
	public function itProvidesARunnerWithOneHandler(): void
	{
		$run      = new Whoops()->getRun();
		$handlers = $run->getHandlers();

		self::assertInstanceOf(Run::class, $run);
		self::assertIsArray($handlers);
		self::assertCount(1, $handlers);
	}

	/**
	 * A web request gets the branded page, while a terminal gets readable text.
	 */
	#[Test]
	public function itChoosesTheHandlerForTheCurrentEnvironment(): void
	{
		$handler = new Whoops()->getRun()->popHandler();

		if (PHP_SAPI === 'cli') {
			self::assertInstanceOf(PlainTextHandler::class, $handler);

			return;
		}

		self::assertInstanceOf(WebifyPageHandler::class, $handler);
	}

	/**
	 * The error must reach the handler supplied by the caller.
	 */
	#[Test]
	public function itShowsAnErrorWithTheGivenHandler(): void
	{
		$caught  = null;
		$handler = new CallbackHandler(
			static function (Throwable $error) use (&$caught): int {
				$caught = $error;

				return Handler::DONE;
			}
		);

		new Whoops($handler)->handle(new RuntimeException('Boom'));

		self::assertInstanceOf(RuntimeException::class, $caught);
		self::assertSame('Boom', $caught->getMessage());
	}

	/**
	 * The wrapper must not be changed after it has been handed to a caller.
	 */
	#[Test]
	public function itIsFinalReadonly(): void
	{
		$reflection = new ReflectionClass(Whoops::class);

		self::assertTrue($reflection->isFinal());
		self::assertTrue($reflection->isReadOnly());
	}
}
