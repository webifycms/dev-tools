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

namespace Webify\Tools\Test\Fixer;

use Override;
use PhpCsFixer\{ConfigInterface, Finder};
use PHPUnit\Framework\Attributes\{CoversClass, DataProvider, Small, Test};
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Webify\Tools\Fixer\Fixer;

/**
 * Unit test for the Fixer class.
 *
 * Verifies the configuration object creation, rule merging,
 * and default settings for indentation, line endings, and risky mode.
 *
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(Fixer::class)]
#[Small]
final class FixerTest extends TestCase
{
	/**
	 * The test finder instance used across tests.
	 */
	private Finder $finder;

	/**
	 * Sets up the test environment.
	 *
	 * Creates a Finder instance scoped to the project's source directory.
	 */
	#[Override]
	protected function setUp(): void
	{
		$this->finder = Finder::create()->in(__DIR__ . '/../../src');
	}

	/**
	 * Test that the Fixer constructor creates a valid ConfigInterface instance.
	 *
	 * Ensures getConfig() returns an object implementing ConfigInterface.
	 */
	#[Test]
	public function itReturnsValidConfig(): void
	{
		$fixer  = new Fixer($this->finder);
		$config = $fixer->getConfig();

		self::assertInstanceOf(ConfigInterface::class, $config);
	}

	/**
	 * Test the default configuration values.
	 *
	 * Verifies that indentation uses tabs, line ending is "\n",
	 * and risky mode is enabled.
	 */
	#[Test]
	public function itHasDefaultConfiguration(): void
	{
		$fixer  = new Fixer($this->finder);
		$config = $fixer->getConfig();

		self::assertSame("\t", $config->getIndent());
		self::assertSame("\n", $config->getLineEnding());
		self::assertTrue($config->getRiskyAllowed());
	}

	/**
	 * Test that custom rules are merged with default rules.
	 *
	 * Provides a custom rule override and verifies that the merged
	 * rules contain both default and custom entries.
	 *
	 * @param array<string, array<string, string>|bool> $customRules
	 */
	#[Test]
	#[DataProvider('provideCustomRules')]
	public function itMergesCustomRulesWithDefaults(array $customRules, string $expectedKey): void
	{
		$fixer  = new Fixer($this->finder, $customRules);
		$config = $fixer->getConfig();
		$rules  = $config->getRules();

		self::assertArrayHasKey($expectedKey, $rules);
		self::assertSame($customRules[$expectedKey], $rules[$expectedKey]);
	}

	/**
	 * Data provider for custom rule merging tests.
	 *
	 * @return array<string, array{0: array<string, array<string, string>|bool>, 1: string}>
	 */
	public static function provideCustomRules(): array
	{
		return [
			'overriding concat_space'        => [
				['concat_space' => ['spacing' => 'one']],
				'concat_space',
			],
			'disabling declare_strict_types' => [
				['declare_strict_types' => false],
				'declare_strict_types',
			],
		];
	}

	/**
	 * Test that the config applies default rules when no custom rules are given.
	 *
	 * Verifies that a default rule like '@PSR12' is present in the config.
	 */
	#[Test]
	public function itAppliesDefaultRules(): void
	{
		$fixer  = new Fixer($this->finder);
		$config = $fixer->getConfig();
		$rules  = $config->getRules();

		self::assertArrayHasKey('@PSR12', $rules);
		self::assertTrue($rules['@PSR12']);
	}

	/**
	 * Test that the finder is correctly set on the config.
	 *
	 * The config must retain the Finder instance passed to the constructor.
	 */
	#[Test]
	public function itSetsFinderOnConfig(): void
	{
		$fixer  = new Fixer($this->finder);
		$config = $fixer->getConfig();

		self::assertSame($this->finder, $config->getFinder());
	}

	/**
	 * Test that the Fixer is final and readonly.
	 */
	#[Test]
	public function itIsFinalReadonly(): void
	{
		$reflection = new ReflectionClass(Fixer::class);

		self::assertTrue($reflection->isFinal());
		self::assertTrue($reflection->isReadOnly());
	}
}
