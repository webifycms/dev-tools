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
 *
 * Provides a single source of truth for the default PHP-CS-Fixer rules used
 * across WebifyCMS packages. The RULES constant combines PSR-12,
 *
 * @PhpCsFixer, and PHP 8.4 migration standards with opinionated overrides
 * for operator spacing, imports, and strict type enforcement.
 */
final class DefaultRules
{
	/**
	 * The default set of PHP-CS-Fixer rules.
	 *
	 * This array defines the baseline coding standards for all WebifyCMS
	 * projects. Each key is a PHP-CS-Fixer rule name, and its value is either
	 * a boolean to enable/disable the rule or an associative array of options.
	 *
	 * @var array<string, array<string, mixed>|bool>
	 */
	public const array RULES = [
		'@PSR12'                        => true,
		'@PhpCsFixer'                   => true,
		'@PHP8x4Migration'              => true,

		// overrides that inherit from @PhpCsFixer rule
		'binary_operator_spaces'        => [
			'operators' => [
				'=>' => 'align_by_scope',
				'='  => 'align',
				'|'  => 'single_space',
			],
		],
		'concat_space'                  => ['spacing' => 'one'],
		'yoda_style'                    => [
			'equal'                => true,
			'identical'            => true,
			'less_and_greater'     => true,
			'always_move_variable' => true,
		],
		'single_import_per_statement'   => false,
		'global_namespace_import'       => [
			'import_classes'   => true,
			'import_constants' => false,
			'import_functions' => true,
		],
		'no_superfluous_phpdoc_tags'    => true,

		// The formatter forces a single space after each of these words. The
		// list is its own default without `extends` and `implements`, so a class
		// with more parents or interfaces than fit on one line can put them on
		// the lines below. Nothing else about the rule changes.
		'single_space_around_construct' => [
			'constructs_followed_by_a_single_space' => [
				'abstract',
				'as',
				'attribute',
				'break',
				'case',
				'catch',
				'class',
				'clone',
				'comment',
				'const',
				'const_import',
				'continue',
				'do',
				'echo',
				'else',
				'elseif',
				'enum',
				'final',
				'finally',
				'for',
				'foreach',
				'function',
				'function_import',
				'global',
				'goto',
				'if',
				'include',
				'include_once',
				'instanceof',
				'insteadof',
				'interface',
				'match',
				'named_argument',
				'namespace',
				'new',
				'open_tag_with_echo',
				'php_doc',
				'php_open',
				'print',
				'private',
				'private_set',
				'protected',
				'protected_set',
				'public',
				'public_set',
				'readonly',
				'require',
				'require_once',
				'return',
				'static',
				'switch',
				'throw',
				'trait',
				'try',
				'type_colon',
				'use',
				'use_lambda',
				'use_trait',
				'var',
				'while',
				'yield',
				'yield_from',
			],
		],

		// other overrides
		'group_import'                  => [
			'group_types' => ['classy', 'functions', 'constants'],
		],
		// A class name, parent and interfaces that do not fit on one line must
		// stay on separate lines; without this the formatter joins them again
		// and the line runs past the 120-column limit.
		'class_definition'              => [
			'single_line'             => false,
			'single_item_single_line' => false,
		],

		// risky rule allowed
		'declare_strict_types'          => true,
		'echo_tag_syntax'               => [
			'format'                         => 'short',
			'shorten_simple_statements_only' => false,
		],
		'phpdoc_to_comment'             => false,
	];
}
