<?php

/**
 * The file is part of the "webifycms/dev-tools", WebifyCMS development tools.
 *
 * @see https://webifycms.com/tools/development-tools
 *
 * @license Copyright (c) 2022 WebifyCMS
 * @license https://webifycms.com/extension/tools/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Tools\Fixer;

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
	 * @var array<string, array<string, mixed>|bool>
	 */
	private readonly array $rules;

	/**
	 * The config object.
	 */
	private readonly ConfigInterface $config;

	/**
	 * The class constructor.
	 *
	 * @param array<string, array<string, mixed>|bool> $rules
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
	 * @param array<string, array<string, mixed>|bool> $rules
	 *
	 * @return array<string, array<string, mixed>|bool>
	 */
	private function mergeRules(array $rules): array
	{
		return array_merge(PHPCsFixerDefaultRules::RULES, $rules);
	}
}
