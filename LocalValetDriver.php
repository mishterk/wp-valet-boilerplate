<?php

use WPValetBoilerplate\Config;
use WPValetBoilerplate\Debug;

require_once __DIR__ . '/vendor/autoload.php';
Config::set_config_by_path( __DIR__ . '/valetbp-config.php' );
Debug::$logfile = Config::get( 'logs.dev' );

/**
 * Class LocalValetDriver
 */
class LocalValetDriver extends WordPressValetDriver {

	/**
	 * @var bool
	 */
	private static $try_remote = false;

	/**
	 * @param string $sitePath
	 * @param string $siteName
	 * @param string $uri
	 *
	 * @return bool|false|string
	 */
	public function isStaticFile( $sitePath, $siteName, $uri ) {
		$local_file_exists = parent::isStaticFile( $sitePath, $siteName, $uri );
		$remote_load       = Config::get( 'remote_uploads' );

		if ( $local_file_exists or ! $remote_load ) {
			return $local_file_exists;
		}

		if ( strpos( $uri, $remote_load['uri_base'] ) === 0 ) {
			self::$try_remote = true;
			$remote_host      = Config::get( 'urls.prod.protocol' ) . '//' . Config::get( 'urls.prod.host' );

			return rtrim( $remote_host, '/' ) . $uri;
		}

		return false;
	}

	/**
	 * @param string $staticFilePath
	 * @param string $sitePath
	 * @param string $siteName
	 * @param string $uri
	 */
	public function serveStaticFile( $staticFilePath, $sitePath, $siteName, $uri ) {
		if ( self::$try_remote ) {
			header( "Location: $staticFilePath" );
		} else {
			parent::serveStaticFile( $staticFilePath, $sitePath, $siteName, $uri );
		}
	}

	/**
	 * @param string $sitePath
	 * @param string $siteName
	 * @param string $uri
	 *
	 * @return string
	 */
	public function frontControllerPath( $sitePath, $siteName, $uri ) {
		return parent::frontControllerPath( "$sitePath/wp", $siteName, $uri );
	}

}