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

namespace Webify\Tools\Fixer;

use PhpCsFixer\{Config, ConfigInterface, Finder};

/**
 * The PHP Standard fixer class.
 *
 * Provides a convenient wrapper around PHP-CS-Fixer configuration, applying
 * a set of default rules that follow PSR-12, @PhpCsFixer, and PHP 8.x migration
 * standards. Custom rules can be merged with the defaults on construction.
 */
final readonly class Fixer
{
	/**
	 * The merged set of rules applied to the configuration.
	 *
	 * Combines the default rules from DefaultRules::RULES with any user-provided
	 * overrides. Each key is a rule name, and the value is either a boolean
	 * (enable/disable) or an associative array of rule-specific options.
	 *
	 * @var array<string, array<string, mixed>|bool>
	 */
	private array $rules;

	/**
	 * The underlying PHP-CS-Fixer configuration instance.
	 *
	 * Configured during construction with merged rules, the provided Finder,
	 * tab indentation, Unix line endings, and risky mode enabled.
	 */
	private ConfigInterface $config;

	/**
	 * The class constructor.
	 *
	 * @param Finder                                   $finder the file finder instance used to locate PHP files
	 * @param array<string, array<string, mixed>|bool> $rules  optional custom rules to merge with defaults
	 */
	public function __construct(
		public Finder $finder,
		array $rules = []
	) {
		$this->rules  = $this->mergeRules($rules);
		$this->config = new Config()
			->setRules($this->rules)
			->setFinder($this->finder)
			->setIndent("\t")
			->setLineEnding("\n")
			->setRiskyAllowed(true)
		;
	}

	/**
	 * Returns the fully configured PHP-CS-Fixer configuration.
	 *
	 * @return ConfigInterface the prepared configuration with rules, finder, and settings
	 */
	public function getConfig(): ConfigInterface
	{
		return $this->config;
	}

	/**
	 * Merges the given custom rules with the default rules.
	 *
	 * User-provided rules override the defaults where keys match, and any
	 * defaults not overridden are preserved.
	 *
	 * @param array<string, array<string, mixed>|bool> $rules custom rules to merge
	 *
	 * @return array<string, array<string, mixed>|bool> the merged rules array
	 */
	private function mergeRules(array $rules): array
	{
		return array_merge(DefaultRules::RULES, $rules);
	}
}
