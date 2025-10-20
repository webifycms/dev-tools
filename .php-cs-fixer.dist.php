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

// should require the composer autoloader on first
require __DIR__ . '/vendor/autoload.php';

use PhpCsFixer\Finder;
use Webify\Tools\Fixer\Fixer;

$finder = Finder::create()
	->in(__DIR__)
	->exclude(
		[
			__DIR__ . '/vendor',
		]
	)
	->ignoreDotFiles(false)
	->name('*.php')
;
$header = <<<'HEADER'
		The file is part of the "webifycms/dev-tools", WebifyCMS development tools.

		@see https://webifycms.com/tools/development-tools

		@license Copyright (c) 2022 WebifyCMS
		@license https://webifycms.com/extension/tools/license
		@author Mohammed Shifreen <mshifreen@gmail.com>
	HEADER;
$rules = [
	'header_comment' => [
		'header'       => $header,
		'location'     => 'after_open',
		'comment_type' => 'PHPDoc',
		'separate'     => 'top',
	],
];

return new Fixer($finder, $rules)->getConfig()->setUsingCache(false);
