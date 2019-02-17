<?php

return [

	'site' => [
		'title'  => 'example title',
		'secure' => true,
	],

	/**
	 * This user data is used during site creation and is also used as part of the automatic login process.
	 */
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

	'urls'    => [
		'prod' => [
			'protocol' => 'https:',
			'host'     => 'example.com',
		],
		'dev'  => [
			'protocol' => 'https:',
			'host'     => 'example.com.test',
		],
	],

	/**
	 * Defines plugins to install and activate as part of the initial install process.
	 */
	'install' => [
		// the first theme listed will be activate on installation
		'themes'  => [
			//'twentynineteen',
		],
		'plugins' => [
			'query-monitor',
			'wp-smtp-config',
		],
	],

	/**
	 * Defines plugins to install and activate along with plugins to deactivate during the `wp valetdb sync` command.
	 */
	'sync'    => [
		'plugins' => [
			'activate'   => [ 'query-monitor', 'wp-smtp-config', 'wp-migrate-db-pro', 'wp-migrate-db-pro-cli' ],
			'deactivate' => [],
		],
	],

	'wpmdbpro' => [
		'license_key'        => '', // todo - support this
		'remote_key'         => '',
		'strings_to_replace' => [
			'//example.com' => '//example.com.test',
			'/remote/path'  => '/Users/system_user_name/Sites/valet/example.com',
		],
		'tables_to_migrate'  => [],
		'exclude_spam'       => true,
	],

	'acf'            => [
		'key' => '',
	],

	/**
	 * Attempt to load uploads from remote domain when the file doesn't exist locally.
	 * Set to FALSE to disable.
	 */
	'remote_uploads' => [
		'uri_base' => '/wp-content/uploads/'
	],

	/**
	 * Configured for brew-installed MailHog.
	 * @see http://localhost:8025/
	 */
	'smtp'           => [
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
	'logs'           => [
		'dev'   => dirname( __FILE__ ) . '/logs/dev.log',
		'debug' => dirname( __FILE__ ) . '/logs/debug.log',
	],

	'google_maps' => [
		'api_key' => ''
	],


];