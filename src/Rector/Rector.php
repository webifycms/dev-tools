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

namespace Webify\Tools\Rector;

use Rector\Config\RectorConfig;
use Rector\Configuration\RectorConfigBuilder;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

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
	public function initialize(array $paths): RectorConfigBuilder
	{
		return RectorConfig::configure()
			->withPaths($paths)
			->withSets(
				[
					SetList::PHP_81,
				]
			)
			->withRules(
				[
					TypedPropertyFromStrictConstructorRector::class,
				]
			)
			->withPreparedSets(
				deadCode: true,
				codeQuality: true
			)
		;
	}
}
