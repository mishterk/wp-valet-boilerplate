<?php

$wp_entry_file = dirname( __FILE__ ) . '/wp/index.php';

if ( file_exists( $wp_entry_file ) ) {
	require( $wp_entry_file );

} else {
	die( 'WordPress not yet installed in the /wp directory.' );

}