<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

use WPValetBoilerplate\Config;
use WPValetBoilerplate\Debug;

require_once __DIR__ . '/vendor/autoload.php';

Config::set_config_by_path( __DIR__ . '/valetbp-config.php' );
Debug::$logfile = Config::get( 'logs.dev' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	try {
		WP_CLI::add_command( 'valetbp', '\WPValetBoilerplate\WPCLICommand' );
	} catch ( \Exception $e ) {
	}
	define( 'WP_DEBUG', false );
	define( 'WP_DEBUG_DISPLAY', false );
	$_SERVER['HTTP_HOST'] = Config::get( 'urls.dev.host' );
}

// ** MySQL settings - You can get this info from your web host ** //
define( 'DB_NAME', Config::get( 'db.name', '' ) );
define( 'DB_USER', Config::get( 'db.user', 'root' ) );
define( 'DB_PASSWORD', Config::get( 'db.password', '' ) );
define( 'DB_HOST', Config::get( 'db.host', 'localhost' ) );
define( 'DB_CHARSET', Config::get( 'db.charset', 'utf8' ) );
define( 'DB_COLLATE', Config::get( 'db.collate', '' ) );

/**#@+*/
define( 'AUTH_KEY', '' );
define( 'SECURE_AUTH_KEY', '' );
define( 'LOGGED_IN_KEY', '' );
define( 'NONCE_KEY', '' );
define( 'AUTH_SALT', '' );
define( 'SECURE_AUTH_SALT', '' );
define( 'LOGGED_IN_SALT', '' );
define( 'NONCE_SALT', '' );
/**#@-*/

$table_prefix = Config::get( 'db.table_prefix' );

$home_url = Config::get( 'urls.dev.protocol' ) . '//' . Config::get( 'urls.dev.host' );
define( 'WP_HOME', $home_url );
define( 'WP_SITEURL', "$home_url/wp" );
defined( 'WP_CONTENT_DIR' ) or define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
defined( 'WP_CONTENT_URL' ) or define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content' );

defined( 'WP_DEBUG' ) or define( 'WP_DEBUG', true );
defined( 'WP_DEBUG_DISPLAY' ) or define( 'WP_DEBUG_DISPLAY', false );
defined( 'WP_DEBUG_LOG' ) or define( 'WP_DEBUG_LOG', false ); // setting a custom log location
ini_set( 'log_errors', 1 );
ini_set( 'error_log', Config::get( 'logs.debug' ) );
defined( 'SCRIPT_DEBUG' ) or define( 'SCRIPT_DEBUG', true );
defined( 'JETPACK_DEV_DEBUG' ) or define( 'JETPACK_DEV_DEBUG', true );

if ( ! defined( 'PHPUNIT_RUNNING' ) ) {
	// Loopback connections can suck, disable if you don't need cron
	define( 'DISABLE_WP_CRON', false );
}

define( 'AUTOMATIC_UPDATER_DISABLED', true );

if ( Config::get( 'smtp' ) ) {
	define( 'WP_SMTP_HOST', Config::get( 'smtp.host' ) );
	define( 'WP_SMTP_PORT', Config::get( 'smtp.port' ) );
	define( 'WP_SMTP_ENCRYPTION', Config::get( 'smtp.encryption' ) );
	define( 'WP_SMTP_USER', Config::get( 'smtp.user' ) );
	define( 'WP_SMTP_PASSWORD', Config::get( 'smtp.password' ) );
	define( 'WP_SMTP_FROM', Config::get( 'smtp.from' ) );
	define( 'WP_SMTP_REPLYTO', Config::get( 'smtp.replyto' ) );
}

// todo - consider these for config file
// Disable external calls
//define('WP_HTTP_BLOCK_EXTERNAL', true);
// whitelist some domains from external call block
//define('WP_ACCESSIBLE_HOSTS', 'site1.com, site2.com');

define( 'WP_ALLOW_REPAIR', true );
define( 'WP_POST_REVISIONS', false );

if ( Config::get( 'google_maps.api_key' ) ) {
	define( 'GOOGLE_MAP_API_KEY', Config::get( 'google_maps.api_key' ) );
}

//define( 'ACFCDT_JSON_DIR', '/vagrant/db-table-defs' );


//// override wp_mail
//function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
//	\PDK\Debug::log( get_defined_vars() );
//}


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
