<?php

declare(strict_types=1);

namespace OneCMS\Sniffer;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

/**
 * The PHP Standard fixer class.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
final class Fixer
{
	private Finder $finder;

	private array $rules;

	private Config $config;

	/**
	 * The class constructor.
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
	public function getConfig(): Config
	{
		return $this->config;
	}

	/**
	 * Merge the given rules with the default rules.
	 */
	private function mergeRules(array $rules): array
	{
		$defaultRulesSet = require __DIR__ . '/rules.php';

		return array_merge($defaultRulesSet, $rules);
	}
}
