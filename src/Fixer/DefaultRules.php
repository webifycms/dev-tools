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

namespace Webify\Tools\Fixer;

/**
 * PHP CS Fixer default rules class.
 */
final class DefaultRules
{
	/**
	 * The default rules.
	 */
	public const array RULES = [
		'@PSR12'                  	      	  => true,
		'@PhpCsFixer'                  	 	  => true,
		'@PHP8x4Migration'              	   => true,
		// overrides that inherits from @PhpCsFixer rule
		'binary_operator_spaces'       	 	  => [
			'operators' => [
				'=>' => 'align_by_scope',
				'='  => 'align',
				'|'  => 'single_space',
			],
		],
		'concat_space'                      => ['spacing' => 'one'],
		'yoda_style'                        => [
			'equal'                => true,
			'identical'            => true,
			'less_and_greater'     => true,
			'always_move_variable' => true,
		],
		'single_import_per_statement'       => false,
		'global_namespace_import'           => [
			'import_classes'   => true,
			'import_constants' => false,
			'import_functions' => true,
		],
		'no_superfluous_phpdoc_tags'        => true,
		// other overrides
		'group_import'                      => [
			'group_types' => ['classy', 'functions', 'constants'],
		],
		// risky rule allowed
		'declare_strict_types'              => true,
		'echo_tag_syntax'                   => [
			'format'                         => 'short',
			'shorten_simple_statements_only' => false,
		],
		'phpdoc_to_comment'                 => false,
	];
}
