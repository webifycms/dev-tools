<?php

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
	// 'no_blank_lines_before_namespace' => true,
];
