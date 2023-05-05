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

use Webify\Tools\Rector;

// Initialize
return (new Rector())->initialize([
    __DIR__.'/src',
    __DIR__.'/test',
]);
