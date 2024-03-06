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

// should require the composer autoloader on first
require __DIR__ . '/vendor/autoload.php';

use PhpCsFixer\Finder;
use Webify\Tools\Fixer\Fixer;

$finder = Finder::create()
	->in(__DIR__)
	->exclude([
		__DIR__ . '/vendor',
	])

	->ignoreDotFiles(false)
	->name('*.php')
;

return (new Fixer($finder))->getConfig()->setUsingCache(false);
