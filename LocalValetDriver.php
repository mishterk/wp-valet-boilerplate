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
	 * Using the constructor
	 */
	public function __construct() {
		self::addDevelopmentFlashToDocument();
	}

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
	 * @param string $string
	 * @param string $startsWith
	 *
	 * @return bool
	 */
	private static function stringStartsWith( $string, $startsWith ) {
		return strpos( $string, $startsWith ) === 0;
	}

	/**
	 * Using PHP's shutdown function, we append a small HTML flash at the end of the document to indicate we are in
     * our development environment
	 */
	private static function addDevelopmentFlashToDocument() {
		register_shutdown_function( function () {
			?>
            <div style="background:green; font-weight: bold; padding: 0.25em 0.25em; line-height:1; position: fixed; z-index: 99999999999999; top:0; left: 0; color: white; font-size:9px;">
                DEVELOPMENT
            </div>
			<?php
		} );
	}

}