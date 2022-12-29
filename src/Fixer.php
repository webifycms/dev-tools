<?php
/**
 * The file is part of the "getonecms/dev-tools", OneCMS development tools.
 *
 * @see https://getonecms.com/tools/development-tools
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/tools/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Tools;

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Finder;

/**
 * The PHP Standard fixer class.
 */
final class Fixer
{
	/**
	 * The rules.
	 *
	 * @var array<string>
	 */
	private readonly array $rules;

	/**
	 * The config object.
	 */
	private readonly ConfigInterface $config;

	/**
	 * The class constructor.
	 *
	 * @param array<string> $rules
	 */
	public function __construct(
		public readonly Finder $finder,
		array $rules = []
	) {
		$this->rules  = $this->mergeRules($rules);
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
		$defaultRulesSet = require \dirname(__DIR__) . '/.cs-default-rules.php';

		return array_merge($defaultRulesSet, $rules);
	}
}
