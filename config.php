<?php

$config = [
	// {{{ Environments

	'environments' => array(
		'local'      => 'local.holidayapi.com',
		'production' => 'holidayapi.com',
	),

	// }}}
	// {{{ PHP Options

	'php' => [
		'local' => [
			'date.timezone'          => 'Universal',
			'display_errors'         => true,
			'error_reporting'        => -1,
			'session.gc_maxlifetime' => 86400,
		],
		'production' => [
			'date.timezone'          => 'Universal',
			'display_errors'         => false,
			'error_reporting'        => -1,
			'session.gc_maxlifetime' => 86400,
		],
	],

	// }}}
	// {{{ PICKLES Stuff

	'pickles' => [
		'disabled'        => false,
		'session'         => 'files',
		'template'        => 'index',
		'module'          => 'home',
		//'404'             => 'error/404',
		'cache'           => 'memcached',
		'profiler'        => [
			'local'      => false,
			'production' => false,
		],
		'logging'        => [
			'local'      => true,
			'production' => false,
		],
		'minify' => [
			'local'      => true,
			'production' => false,
		],
	],

	// }}}
	// {{{ Datasources

	'datasources' => [
		'local' => [
			'memcached' => [
				'type'      => 'memcache',
				'hostname'  => 'localhost',
				'port'      => 11211,
				'namespace' => 'holidayapi',
			],
		],
		'production' => [
			'memcached' => [
				'type'      => 'memcache',
				'hostname'  => 'localhost',
				'port'      => 11211,
				'namespace' => 'holidayapi',
			],
		],
	],

	// }}}
	// {{{ Security Options

	'security' => [
		'login'  => 'login',
		'model'  => 'User',
		'column' => 'role',
		'levels' => [
			 0 => 'ANONYMOUS',
			10 => 'USER',
			20 => 'ADMIN',
		],
	],

	// }}}
];

?>
