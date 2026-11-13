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

use Webify\Tools\Rector\Rector;

// Initialize
return new Rector()
	->initialize([dirname(__DIR__) . '/src', dirname(__DIR__) . '/test'])
	->withPhpSets(php84: true)
;
