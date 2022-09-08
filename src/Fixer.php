<?php

declare(strict_types=1);

namespace OneCMS\Sniffer;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

/**
 * The PHP Standard fixer class.
 *
 * @package getonecms/sniffer
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class Fixer
{
	/**
	 * @var Finder
	 */
	private Finder $finder;
	/**
	 * @var array
	 */
	private array $rules;
	/**
	 * @var Config
	 */
	private Config $config;

	/**
	 * The class constructor.
	 *
	 * @param Finder $finder
	 * @param array  $rules
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
			->setRiskyAllowed(true);
	}

	/**
	 * Merge the given rules with the default rules.
	 *
	 * @param  array $rules
	 * @return array
	 */
	private function mergeRules(array $rules): array
	{
		$defaultRulesSet = require __DIR__ . '/rules.php';

		return array_merge($defaultRulesSet, $rules);
	}

	/**
	 * Returns fixer configuration.
	 *
	 * @return Config
	 */
	public function getConfig(): Config
	{
		return $this->config;
	}
}
