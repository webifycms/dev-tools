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

return [
    '@PSR12'                  	      	=> true,
    '@PSR12:risky'                 	 	=> true,
    '@PHP80Migration:risky'        	 	=> true,
    '@PHP81Migration'              	 	=> true,
    '@PhpCsFixer'                  	 	=> true,
    '@PhpCsFixer:risky'            	 	=> true,
    'binary_operator_spaces'       	 	=> [ // overrides that inherits from @PhpCsFixer rule
        'operators' => [
            '=>' => 'align',
            '='  => 'align',
            '|'  => 'no_space',
        ],
    ],
    'concat_space' => ['spacing' => 'one'], // overrides that inherits from @PhpCsFixer rule
    'yoda_style'   => [
        'equal'                => true,
        'identical'            => true,
        'less_and_greater'     => true,
        'always_move_variable' => true,
    ],
    'php_unit_test_case_static_method_calls' => [ // overrides that inherits from @PhpCsFixer:risky rule
        'call_type' => 'this',
    ],
];
