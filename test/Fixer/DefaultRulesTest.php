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

use PHPUnit\Framework\Attributes\{CoversClass, Small, Test};
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionNamedType;
use Webify\Tools\Fixer\DefaultRules;

/**
 * Unit test for the DefaultRules class.
 *
 * Ensures the RULES constant is a properly structured array containing
 * all the required rule definitions for PHP-CS-Fixer.
 *
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DefaultRules::class)]
#[Small]
final class DefaultRulesTest extends TestCase
{
	/**
	 * Test that the RULES constant is an array and is not empty.
	 *
	 * The constant must be a non-empty array of PHP-CS-Fixer rules.
	 */
	#[Test]
	public function itHasNonEmptyRulesArray(): void
	{
		$rules = DefaultRules::RULES;

		self::assertIsArray($rules);
		self::assertNotEmpty($rules);
	}

	/**
	 * Test that the RULES constant includes all required rule sets.
	 *
	 * Verifies the presence of essential rule sets like @PSR12, @PhpCsFixer,
	 * and @PHP8x4Migration.
	 */
	#[Test]
	public function itIncludesRequiredRuleSets(): void
	{
		$rules = DefaultRules::RULES;

		self::assertArrayHasKey('@PSR12', $rules);
		self::assertTrue($rules['@PSR12']);

		self::assertArrayHasKey('@PhpCsFixer', $rules);
		self::assertTrue($rules['@PhpCsFixer']);

		self::assertArrayHasKey('@PHP8x4Migration', $rules);
		self::assertTrue($rules['@PHP8x4Migration']);
	}

	/**
	 * Test that the default rules require strict type declarations.
	 */
	#[Test]
	public function itHasDeclareStrictTypesEnabled(): void
	{
		$rules = DefaultRules::RULES;

		self::assertArrayHasKey('declare_strict_types', $rules);
		self::assertTrue($rules['declare_strict_types']);
	}

	/**
	 * Test that the DefaultRules class is final.
	 */
	#[Test]
	public function itIsFinal(): void
	{
		$reflection = new ReflectionClass(DefaultRules::class);

		self::assertTrue($reflection->isFinal());
	}

	/**
	 * Test that the RULES constant is a typed array.
	 */
	#[Test]
	public function itHasTypedRulesConstant(): void
	{
		$reflection      = new ReflectionClassConstant(DefaultRules::class, 'RULES');
		$reflectionType  = $reflection->getType();

		self::assertNotNull($reflectionType);
		self::assertInstanceOf(ReflectionNamedType::class, $reflectionType);
		self::assertSame('array', $reflectionType->getName());
	}

	/**
	 * Test that the yoda_style rule is properly configured.
	 */
	#[Test]
	public function itHasYodaStyleEnabled(): void
	{
		$rules = DefaultRules::RULES;

		self::assertArrayHasKey('yoda_style', $rules);
		self::assertIsArray($rules['yoda_style']);
		self::assertTrue($rules['yoda_style']['equal']);
		self::assertTrue($rules['yoda_style']['identical']);
	}
}
