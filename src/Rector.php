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

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;

/**
 * Rector library initializer.
 */
final class Rector
{
	/**
	 * The class constructor.
	 *
	 * @param array<string> $paths the paths rector should looking into the files
	 */
	public function __construct(public readonly array $paths)
	{
	}

	/**
	 * Initialize the rector.
	 */
	public function initialize(): \Closure
	{
		$paths = $this->paths;

		return static function (RectorConfig $rectorConfig) use ($paths): void {
			// sets the paths
			$rectorConfig->paths($paths);
			// defined sets of rules
			$rectorConfig->sets([
				SetList::PHP_81,
			]);
			// register a single rule
			$rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
			$rectorConfig->rule(TypedPropertyRector::class);
		};
	}
}
