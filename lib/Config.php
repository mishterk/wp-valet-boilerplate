<?php

namespace WPValetBoilerplate;

class Config {

	public static $config = [];

	public static function set_config_by_path( $filepath ) {
		if ( file_exists( $filepath ) ) {
			self::set_config( include $filepath );
		}
	}

	public static function set_config( array $config ) {
		self::$config = $config;
	}

	public static function get( $key, $default = null ) {
		return self::resolve_by_dot_notation( self::$config, $key, $default );
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
	private static function resolve_by_dot_notation( array $array, $path, $default = null ) {
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