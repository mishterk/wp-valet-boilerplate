<?php

namespace WPValetBoilerplate;

use WP_CLI;

class WPCLICommand extends \WP_CLI_Command {

	/**
	 * Runs a full DB sync and local config to get the development installation up to date and ready for work
	 */
	public function sync() {
		$this->pull_db();
		$this->post_sync();
	}

	/**
	 * Runs all post-sync tasks. Useful where a manual DB pull has been
	 *
	 * @subcommand post-sync
	 */
	public function post_sync() {
		$this->register_acf();
		$this->install_plugins();
		$this->toggle_plugins();
		WP_CLI::runcommand( 'rewrite flush' );
		$this->login();
		$this->test_smtp();
	}

	/**
	 * Pulls the latest from production via the WP Migrate DB Pro plugin with CLI addon
	 *
	 * @subcommand pull-db
	 */
	public function pull_db() {
		$plugins_needed_for_sync = [ 'wp-migrate-db-pro' ];
		WP_CLI::runcommand( 'plugin activate ' . implode( ' ', $plugins_needed_for_sync ) );

		$url = Config::get( 'urls.prod.protocol' ) . '//' . Config::get( 'urls.prod.host' );
		$key = Config::get( 'wpmdbpro.remote_key' );

		$command = "migratedb pull $url '$key'";

		if ( $replacements = Config::get( 'wpmdbpro.strings_to_replace' ) ) {
			$command .= ' --find="' . implode( ',', array_keys( $replacements ) ) . '"';
			$command .= ' --replace="' . implode( ',', array_values( $replacements ) ) . '"';
		}

		if ( Config::get( 'wpmdbpro.exclude_spam' ) ) {
			$command .= ' --exclude-spam';
		}

		if ( $tables = Config::get( 'wpmdbpro.tables_to_migrate' ) ) {
			$command .= ' --include-tables=' . implode( ',', $tables );
		}

		WP_CLI::log( 'Running command: ' . $command );
		WP_CLI::runcommand( $command );
	}

	/**
	 * Inserts the ACF license key from valetbp-config.php into the database and registers with ACF.
	 *
	 * @subcommand register-acf
	 */
	public function register_acf() {
		if ( $key = Config::get( 'acf.key' ) ) {
			WP_CLI::runcommand( "eval 'function_exists(\"acf_pro_update_license\") and acf_pro_update_license(\"'$key'\");'" );
			WP_CLI::success( 'ACF Pro license key stored and activated' );
		}
	}

	/**
	 * Installs plugins as defined in valetbp-config.php
	 *
	 * @subcommand install-plugins
	 */
	public function install_plugins() {
		$plugins_to_install = Config::get( 'sync.plugins.activate', [] );

		foreach ( $plugins_to_install as $plugin ) {
			if ( is_array( $plugin ) ) {
				if ( ! $plugin['src'] ) {
					continue;
				}
				$plugin = $plugin['src'];
			}
			WP_CLI::runcommand( "plugin install $plugin" );
		}
	}

	/**
	 * De/Activates plugins as defined in valetbp-config.php
	 *
	 * @subcommand toggle-plugins
	 */
	public function toggle_plugins() {
		if ( $plugins_to_activate = Config::get( 'sync.plugins.activate' ) ) {
			$plugins_to_activate = implode( ' ', $this->get_plugin_slugs( $plugins_to_activate ) );
			WP_CLI::runcommand( "plugin activate $plugins_to_activate" );
		}

		WP_CLI::runcommand( 'login install --activate --yes' );

		if ( $plugins_to_deactivate = Config::get( 'sync.plugins.deactivate' ) ) {
			$plugins_to_deactivate = implode( ' ', $this->get_plugin_slugs( $plugins_to_deactivate ) );
			WP_CLI::runcommand( "plugin deactivate $plugins_to_deactivate" );
		}
	}

	/**
	 * Fires a test email message using wp_mail and opens up Mailhog in a browser window.
	 *
	 * @subcommand test-smtp
	 */
	public function test_smtp() {
		if ( $test = Config::get( 'smtp.test' ) ) {
			WP_CLI::runcommand( "eval 'wp_mail( \"{$test['to']}\", \"{$test['subject']}\", \"{$test['message']}\" );'" );
			exec( "open '{$test['mailhog_url']}'" );
		}
	}

	/**
	 * Logs the auth user into the site and opens the site in a new browser tab. Auth user is defined in
	 * valetbp-config.php.
	 *
	 * @see https://aaemnnost.tv/wp-cli-commands/login/
	 */
	public function login() {
		WP_CLI::log( 'Attempting to log you in...' );
		$username = Config::get( 'auth.username' );
		WP_CLI::runcommand( "login create $username --launch" );
	}

	/**
	 * Creates an admin user using the credentials listed in the config.
	 *
	 * @subcommand create-admin-user
	 */
	public function create_admin_user() {
		WP_CLI::runcommand( sprintf( "user create %s %s --role=administrator --user_pass=%s", Config::get( 'auth.username' ), Config::get( 'auth.email' ), Config::get( 'auth.password' ), ) );
	}

	/**
	 * Gets an array of just the plugin slugs for de/activation
	 *
	 * @param array $plugins_array
	 *
	 * @return array
	 */
	private function get_plugin_slugs( array $plugins_array ) {
		$plugins_array = array_map( [ $this, 'format_plugin' ], $plugins_array );
		$plugins_array = wp_list_pluck( $plugins_array, 'slug' );

		return $plugins_array;
	}

	/**
	 * Expands plugin array item to an expected array format containing the 'slug' and 'src' keys.
	 *
	 * @param array|string $plugin
	 *
	 * @return array
	 */
	private function format_plugin( $plugin ) {
		if ( is_string( $plugin ) ) {
			$slug           = $plugin;
			$plugin         = [];
			$plugin['slug'] = $plugin['src'] = $slug;
		}

		return $plugin;
	}

}
