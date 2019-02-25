<?php

$config = require dirname( __DIR__ ) . '/valetbp-config.php';

$verbose        = false;
$exec_and_print = function ( $command ) use ( $verbose ) {
	exec( $command, $output );
	if ( $verbose ) {
		foreach ( $output as $line ) {
			echo '  - ' . $line . PHP_EOL;
		}
	}
};

if ( $config['site']['secure'] ) {
	echo "Running 'valet secure' (this will take a few seconds)" . PHP_EOL;
	$exec_and_print( 'valet secure' );
}

echo "Replacing {COMPOSER_API_KEY} in composer.json with actual key from config" . PHP_EOL;
$composer_file_path    = dirname( __DIR__ ) . '/composer.json';
$composer_file_content = file_get_contents( $composer_file_path );
$composer_file_content = str_replace( '{COMPOSER_API_KEY}', $config['wpmdbpro']['composer_api_key'], $composer_file_content );
file_put_contents( $composer_file_path, $composer_file_content );

echo "Installing Composer dependencies" . PHP_EOL;
$exec_and_print( 'composer install --prefer-dist' );

echo "Creating the database" . PHP_EOL;
$db_user = $config['db']['user'];
$db_name = $config['db']['name'];
$db_pass = $config['db']['password'] ? " -p $db_pass" : '';
$exec_and_print( "mysql -u {$db_user}{$db_pass} -e 'create database `{$db_name}`'" );

echo "Downloading and installing WordPress" . PHP_EOL;
$url   = $config['urls']['dev']['protocol'] . '//' . $config['urls']['dev']['host'];
$title = $config['site']['title'];
$user  = $config['auth']['username'];
$pass  = $config['auth']['password'];
$email = $config['auth']['email'];
$exec_and_print( 'wp core download' );
$exec_and_print( "wp core install --url='$url' --title='$title' --admin_user='$user' --admin_password='$pass' --admin_email='$email' --skip-email" );

if ( $config['install']['themes'] ) {
	echo "Downloading and installing initial themes" . PHP_EOL;
	$themes       = implode( ' ', $config['install']['themes'] );
	$active_theme = $config['install']['themes'][0];
	$exec_and_print( "wp theme install $themes" );
	$exec_and_print( "wp theme activate $active_theme" );
}

if ( $config['install']['plugins'] ) {
	echo "Downloading and installing initial plugins" . PHP_EOL;
	$plugins = implode( ' ', $config['install']['plugins'] );
	$exec_and_print( "wp plugin install $plugins --activate" );
}

echo "Installing and activating the WP-CLI login helper plugin" . PHP_EOL;
$exec_and_print( 'wp login install --activate --yes' );

echo "Logging you into the site" . PHP_EOL;
$exec_and_print( 'wp valetbp login' );

echo 'DONE: Installation complete' . PHP_EOL;