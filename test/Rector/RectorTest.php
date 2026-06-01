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

namespace Webify\Tools\Test\Rector;

use Override;
use PHPUnit\Framework\Attributes\{CoversClass, Small, Test};
use PHPUnit\Framework\TestCase;
use Rector\Configuration\RectorConfigBuilder;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use ReflectionClass;
use ReflectionProperty;
use Webify\Tools\Rector\Rector;

/**
 * Unit test for the Rector class.
 *
 * Verifies the Rector initialization returns a properly configured
 * RectorConfigBuilder instance with paths and rules applied.
 *
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(Rector::class)]
#[Small]
final class RectorTest extends TestCase
{
	/**
	 * The Rector instance used across tests.
	 */
	private Rector $rector;

	/**
	 * Sets up the test environment.
	 */
	#[Override]
	protected function setUp(): void
	{
		$this->rector = new Rector();
	}

	/**
	 * Test that initialize() returns a RectorConfigBuilder instance.
	 */
	#[Test]
	public function itReturnsRectorConfigBuilder(): void
	{
		$builder = $this->rector->initialize(['src']);

		self::assertInstanceOf(RectorConfigBuilder::class, $builder);
	}

	/**
	 * Tests that initialize() accept and process multiple paths.
	 */
	#[Test]
	public function itAcceptsMultiplePaths(): void
	{
		$paths   = ['src', 'test'];
		$builder = $this->rector->initialize($paths);

		self::assertInstanceOf(RectorConfigBuilder::class, $builder);
	}

	/**
	 * Test that initialize() works with an empty paths array.
	 *
	 * The method should not throw when given an empty list of paths.
	 */
	#[Test]
	public function itHandlesEmptyPaths(): void
	{
		$builder = $this->rector->initialize([]);

		self::assertInstanceOf(RectorConfigBuilder::class, $builder);
	}

	/**
	 * Tests that initialize() apply the configured rules via reflection.
	 *
	 * Verifies that the TypedPropertyFromStrictConstructorRector rule is included
	 * in the internal rules property of the builder.
	 */
	#[Test]
	public function itAppliesConfiguredRules(): void
	{
		$builder   = $this->rector->initialize(['src']);
		$ref       = new ReflectionProperty($builder, 'rules');
		$rules     = $ref->getValue($builder);
		$ruleClass = TypedPropertyFromStrictConstructorRector::class;

		self::assertContains($ruleClass, $rules);
	}

	/**
	 * Test that the Rector class is final.
	 */
	#[Test]
	public function itIsFinal(): void
	{
		$reflection = new ReflectionClass(Rector::class);

		self::assertTrue($reflection->isFinal());
	}
}
