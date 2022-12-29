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
	 * Initialize the rector.
	 *
	 * @param array<string> $paths
	 */
	public function initialize(array $paths): \Closure
	{
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
