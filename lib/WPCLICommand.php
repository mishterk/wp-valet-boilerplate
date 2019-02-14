<?php

namespace WPValetBoilerplate;

use WP_CLI;

class WPCLICommand extends \WP_CLI_Command {

	/**
	 * Runs a full sync to get development inline with prod and ready for development work
	 */
	public function sync_with_prod() {
		$this->pull_prod_db();
		$this->update_acf_license_key();
		$this->install_plugins();
		$this->configure_dev_plugins();
		WP_CLI::runcommand( 'rewrite flush' );
		$this->log_me_in();
	}

	/**
	 * Pulls the latest from production via the WP Migrate DB Pro plugin with CLI addon
	 */
	public function pull_prod_db() {
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
	 * Ensures our ACF key is in the DB
	 */
	public function update_acf_license_key() {
		if ( $key = Config::get( 'acf.key' ) ) {
			WP_CLI::runcommand( "eval 'function_exists(\"acf_pro_update_license\") and acf_pro_update_license(\"'$key'\");'" );
			WP_CLI::success( 'ACF Pro license key stored and activated' );
		}
	}

	/**
	 * Installs plugins as per config
	 */
	public function install_plugins() {
		if ( $plugins_to_install = Config::get( 'plugins.install' ) ) {
			$plugins_to_install = implode( ' ', $plugins_to_install );
			WP_CLI::runcommand( "plugin install $plugins_to_install" );
		}
	}

	/**
	 * De/Activates plugins as per config
	 */
	public function configure_dev_plugins() {
		if ( $plugins_to_activate = Config::get( 'plugins.activate' ) ) {
			$plugins_to_activate = implode( ' ', $plugins_to_activate );
			WP_CLI::runcommand( "plugin activate $plugins_to_activate" );
		}

		if ( $plugins_to_deactivate = Config::get( 'plugins.deactivate' ) ) {
			$plugins_to_deactivate = implode( ' ', $plugins_to_deactivate );
			WP_CLI::runcommand( "plugin deactivate $plugins_to_deactivate" );
		}
	}

	/**
	 * @see https://aaemnnost.tv/wp-cli-commands/login/
	 */
	public function log_me_in() {
		WP_CLI::log( 'Attempting to log you in...' );
		$username = Config::get( 'auth.username' );
		WP_CLI::runcommand( "login create $username --launch" );
	}

}