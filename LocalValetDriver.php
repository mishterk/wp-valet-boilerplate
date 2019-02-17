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
		$local_file_found = parent::isStaticFile( $sitePath, $siteName, $uri );
		$remote_fallback  = Config::get( 'remote_uploads' );

		if ( $local_file_found or ! $remote_fallback ) {
			return $local_file_found;
		}

		if ( self::stringStartsWith( $uri, $remote_fallback['uri_base'] ) ) {
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

	/**
	 * @param string $string
	 * @param string $starts_with
	 *
	 * @return bool
	 */
	private static function stringStartsWith( $string, $starts_with ) {
		return strpos( $string, $starts_with ) === 0;
	}

}