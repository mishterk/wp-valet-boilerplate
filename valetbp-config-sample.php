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

	'urls'     => [
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
	'install'  => [
		'themes'  => [
			// the first theme listed will be activate on installation
			//'twentynineteen',
		],
		'plugins' => [
			'query-monitor',
			'wp-smtp-config',
		],
	],

	/**
	 * Defines plugins to install and activate along with plugins to deactivate during the `wp valetdb sync` command.
	 * If a string is provided, it is treated as the WordPress.org slug. If any other src is needed here, use an array
	 * with the 'slug' and 'src' keys.
	 *
	 * If 'src' => FALSE, the plugin will be skipped from installation. This is useful if the wp plugin install command
	 * results in an error where a plugin isn't available via WordPress.org.
	 */
	'sync'     => [
		'plugins' => [
			'activate'   => [
				'query-monitor',
				'wp-smtp-config',
				'log-emails',
				[ 'slug' => 'wp-migrate-db-pro', 'src' => false ],
				[ 'slug' => 'wp-migrate-db-pro-cli', 'src' => false ],
			],
			'deactivate' => [],
		],
	],

	/**
	 * WP Migrate DB Pro
	 *
	 * This is being installed using Composer and will need a composer API key in order for that to happen.
	 * @see https://deliciousbrains.com/wp-migrate-db-pro/doc/installing-via-composer/
	 */
	'wpmdbpro' => [
		//'license_key'        => '', // todo - support this
		'composer_api_key'   => '',
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
	 * Configured for brew-installed MailHog running at http://localhost:8025/. These settings are set up as constants
	 * in wp-config.php for use by the wp-smtp-config plugin.
	 *
	 * @see https://wordpress.org/plugins/wp-smtp-config/
	 * @see https://pascalbaljetmedia.com/en/blog/setup-mailhog-with-laravel-valet
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