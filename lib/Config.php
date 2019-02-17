<?php

namespace WPValetBoilerplate;

class Config {

	public static $config = [];

	public static function setConfigFile( $filepath ) {
		if ( file_exists( $filepath ) ) {
			self::setConfig( include $filepath );
		}
	}

	public static function setConfig( array $config ) {
		self::$config = $config;
	}

	public static function get( $key, $default = null ) {
		return self::resolveByDotNotation( self::$config, $key, $default );
	}

	/**
	 * Resolves the value of a multi-dimensional array using dot notation.
	 *
	 * i.e; resolve(['a' => ['b' => 1]], 'a.b') => 1
	 *
	 * @param array $array
	 * @param $path
	 * @param null $default
	 *
	 * @return array|mixed|null
	 */
	private static function resolveByDotNotation( array $array, $path, $default = null ) {
		$current = $array;
		$p       = strtok( $path, '.' );

		while ( $p !== false ) {
			if ( ! isset( $current[ $p ] ) ) {
				return $default;
			}
			$current = $current[ $p ];
			$p       = strtok( '.' );
		}

		return $current;
	}

}