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

namespace Webify\Tools\Rector;

use Rector\Config\RectorConfig;
use Rector\Configuration\RectorConfigBuilder;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

/**
 * Rector library initializer.
 *
 * Provides a convenient entry point for configuring Rector within WebifyCMS
 * projects. Sets up paths, registered rules (such as typed property inference
 * from strict constructors), and prepared sets for dead code removal, code
 * quality improvements, and coding style adjustments.
 */
final class Rector
{
	/**
	 * Initialize and configure the Rector configuration builder.
	 *
	 * Applies the given source paths, registers the
	 * TypedPropertyFromStrictConstructorRector rule, and enables the dead-code,
	 * code-quality, and coding-style prepared sets.
	 *
	 * @param array<string> $paths an array of directory or file paths to analyse
	 *
	 * @return RectorConfigBuilder The configured Rector builder ready to be returned from a rector.php config file.
	 */
	public function initialize(array $paths): RectorConfigBuilder
	{
		return RectorConfig::configure()
			->withPaths($paths)
			->withRules(
				[
					TypedPropertyFromStrictConstructorRector::class,
				]
			)
			->withPreparedSets(
				deadCode: true,
				codeQuality: true,
				codingStyle: true
			)
		;
	}
}
