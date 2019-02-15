<?php

namespace WPValetBoilerplate;

use WP_CLI;

class WPCLICommand extends \WP_CLI_Command {

	/**
	 * Runs a full DB sync and local config to get the development installation up to date and ready for work
	 */
	public function sync() {
		$this->pull_db();
		$this->register_acf();
		$this->install_plugins();
		$this->toggle_plugins();
		WP_CLI::runcommand( 'rewrite flush' );
		$this->login();
	}

	/**
	 * Pulls the latest from production via the WP Migrate DB Pro plugin with CLI addon
	 *
	 * @subcommand pull-db
	 */
	public function pull_db() {
		$url = Config::get( 'urls.prod.protocol' ) . '//' . Config::get( 'urls.prod.host' );
		$key = Config::get( 'wpmdbpro.remote_key' );

		$command = "migratedb pull $url $key";

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
		if ( $plugins_to_install = Config::get( 'sync.plugins.install' ) ) {
			$plugins_to_install = implode( ' ', $plugins_to_install );
			WP_CLI::runcommand( "plugin install $plugins_to_install" );
		}
	}

	/**
	 * De/Activates plugins as defined in valetbp-config.php
	 *
	 * @subcommand toggle-plugins
	 */
	public function toggle_plugins() {
		if ( $plugins_to_activate = Config::get( 'sync.plugins.activate' ) ) {
			$plugins_to_activate = implode( ' ', $plugins_to_activate );
			WP_CLI::runcommand( "plugin activate $plugins_to_activate" );
		}

		if ( $plugins_to_deactivate = Config::get( 'sync.plugins.deactivate' ) ) {
			$plugins_to_deactivate = implode( ' ', $plugins_to_deactivate );
			WP_CLI::runcommand( "plugin deactivate $plugins_to_deactivate" );
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

}