<?php

return [

	'site' => [
		'title' => 'example title',
	],

	'auth' => [
		'username' => 'admin',
		'email'    => 'user@example.com',
		'password' => 'password',
	],

	'db' => [
		'name'         => 'example_db_name',
		'user'         => 'root',
		'password'     => '',
		'host'         => 'localhost',
		'charset'      => 'utf8',
		'collate'      => '',
		'table_prefix' => 'wp_',
	],

	'urls' => [
		'prod' => [
			'protocol' => 'https:',
			'host'     => 'example.com',
		],
		'dev'  => [
			'protocol' => 'https:',
			'host'     => 'example.com.test',
		],
	],

	'plugins' => [
		'install'    => [ 'query-monitor', 'wp-smtp-config' ],
		'activate'   => [ 'query-monitor', 'wp-smtp-config', 'wp-migrate-db-pro-cli' ],
		'deactivate' => [],
	],

	'wpmdbpro' => [
		'remote_key'         => '',
		'strings_to_replace' => [
			'//example.com' => '//example.com.test',
			'/remote/path'  => '/Users/system_user_name/Sites/valet/example.com',
		],
		'tables_to_migrate'  => [],
		'exclude_spam'       => true,
	],

	'acf'   => [
		'key' => '',
	],

	/**
	 * todo: remote image loading via valet local driver
	 */
	'valet' => [
		'local_driver' => [
			'load_images_remotely' => true
		],
		'secure'       => true,
	],

	/**
	 * Configured for brew-installed MailHog.
	 * @see http://localhost:8025/
	 */
	'smtp'  => [
		'host'       => '0.0.0.0',
		'port'       => 1025,
		'encryption' => null,
		'user'       => 'testuser',
		'password'   => 'testpwd',
		'from'       => 'Local Dev <email@localdev.com>',
		'replyto'    => 'Local Dev <email@localdev.com>',
	],

	/**
	 * Define log file paths
	 */
	'logs'  => [
		'dev'   => dirname( __FILE__ ) . '/logs/dev.log',
		'debug' => dirname( __FILE__ ) . '/logs/debug.log',
	],

	'google_maps' => [
		'api_key' => ''
	],


];