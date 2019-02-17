<?php

use WPValetBoilerplate\Config;
use WPValetBoilerplate\Debug;

require_once __DIR__ . '/vendor/autoload.php';
Config::setConfigFile( __DIR__ . '/valetbp-config.php' );
Debug::$logfile = Config::get( 'logs.dev' );

/**
 * Class LocalValetDriver
 */
class LocalValetDriver extends WordPressValetDriver {

	/**
	 * @var bool
	 */
	private static $tryRemoteFallback = false;

	/**
	 * @param string $sitePath
	 * @param string $siteName
	 * @param string $uri
	 *
	 * @return bool|false|string
	 */
	public function isStaticFile( $sitePath, $siteName, $uri ) {
		$localFileFound = parent::isStaticFile( $sitePath, $siteName, $uri );
		$remoteFallback = Config::get( 'remote_uploads' );

		if ( $localFileFound or ! $remoteFallback ) {
			return $localFileFound;
		}

		if ( self::stringStartsWith( $uri, $remoteFallback['uri_base'] ) ) {
			self::$tryRemoteFallback = true;
			$remoteHost              = Config::get( 'urls.prod.protocol' ) . '//' . Config::get( 'urls.prod.host' );

			return rtrim( $remoteHost, '/' ) . $uri;
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
		if ( self::$tryRemoteFallback ) {
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
	 * @param string $startsWith
	 *
	 * @return bool
	 */
	private static function stringStartsWith( $string, $startsWith ) {
		return strpos( $string, $startsWith ) === 0;
	}

}