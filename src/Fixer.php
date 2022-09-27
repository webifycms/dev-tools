<?php

/**
 * The file is part of the "getonecms/dev-tools", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/tools
 *
 * @copyright Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/tools/license
 * @author  Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Tools;

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Finder;

/**
 * The PHP Standard fixer class.
 *
 * @author Mohammed Shifreen
 */
final class Fixer
{
	private Finder $finder;

	/**
	 * The rules.
	 *
	 * @var array<string>
	 */
	private array $rules;

	private ConfigInterface $config;

	/**
	 * The class constructor.
	 *
	 * @param array<string> $rules
	 */
	public function __construct(
		Finder $finder,
		array $rules = []
	) {
		$this->rules  = $this->mergeRules($rules);
		$this->finder = $finder;
		$this->config = (new Config())
			->setRules($this->rules)
			->setFinder($this->finder)
			->setIndent("\t")
			->setLineEnding("\n")
			->setRiskyAllowed(true)
		;
	}

	/**
	 * Returns fixer configuration.
	 */
	public function getConfig(): ConfigInterface
	{
		return $this->config;
	}

	/**
	 * Merge the given rules with the default rules.
	 *
	 * @param array<string> $rules
	 *
	 * @return array<string>
	 */
	private function mergeRules(array $rules): array
	{
		$defaultRulesSet = require __DIR__ . '/cs_fixer_rules.php';

		return array_merge($defaultRulesSet, $rules);
	}
}
