<?php

$config = array(
	// {{{ Environments

	'environments' => array(
		'local'      => 'local.holidayapi.com',
		'production' => 'holidayapi.com',
	),

	// }}}
	// {{{ PHP Options

	'php' => array(
		'local' => array(
			'date.timezone'          => 'Universal',
			'display_errors'         => true,
			'error_reporting'        => -1,
			'session.gc_maxlifetime' => 86400,
		),
		'production' => array(
			'date.timezone'          => 'Universal',
			'display_errors'         => false,
			'error_reporting'        => -1,
			'session.gc_maxlifetime' => 86400,
		),
	),

	// }}}
	// {{{ PICKLES Stuff

	'pickles' => array(
		'disabled'        => false,
		'session'         => 'files',
		'template'        => 'index',
		'module'          => 'home',
		//'404'             => 'error/404',
		'cache'           => 'memcached',
		'profiler'        => array(
			'local'      => false,
			'production' => false,
		),
		'logging'        => array(
			'local'      => true,
			'production' => false,
		),
		'minify' => array(
			'local'      => true,
			'production' => false,
		),
	),

	// }}}
	// {{{ Datasources

	'datasources' => array(
		'local' => array(
			'memcached' => array(
				'type'      => 'memcache',
				'hostname'  => 'localhost',
				'port'      => 11211,
				'namespace' => 'holidayapi',
			),
		),
		'production' => array(
			'memcached' => array(
				'type'      => 'memcache',
				'hostname'  => 'localhost',
				'port'      => 11211,
				'namespace' => 'holidayapi',
			),
		),
	),

	// }}}
	// {{{ Security Options

	'security' => array(
		'login'  => 'login',
		'model'  => 'User',
		'column' => 'role',
		'levels' => array(
			 0 => 'ANONYMOUS',
			10 => 'USER',
			20 => 'ADMIN',
		),
	),

	// }}}
);

?>
