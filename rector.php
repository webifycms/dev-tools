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

use OneCMS\Tools\Rector;

// Initialize
return (new Rector([
    __DIR__ . '/src',
    __DIR__ . '/test'
]))->initialize();