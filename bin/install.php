<?php

/**
 * This script will remove all work and the database. It will basically leave you with the boilerplate and the
 * valetbp-config.php file.
 */

$config = require dirname( __DIR__ ) . '/valetbp-config.php';

if ( $config['valet']['secure'] ) {
	echo "Running 'valet secure' (this will take a few seconds)" . PHP_EOL;
	exec( 'valet secure' );
}

echo "Installing Composer dependencies" . PHP_EOL;
exec( 'composer install --prefer-dist', $output );

echo "Creating the database" . PHP_EOL;
$db_user = $config['db']['user'];
$db_name = $config['db']['name'];
$db_pass = $config['db']['password'] ? " -p $db_pass" : '';
exec( "mysql -u {$db_user}{$db_pass} -e 'create database `{$db_name}`'" );

echo "Downloading and installing WordPress" . PHP_EOL;
$url   = $config['urls']['dev']['protocol'] . '//' . $config['urls']['dev']['host'];
$title = $config['site']['title'];
$user  = $config['auth']['username'];
$pass  = $config['auth']['password'];
$email = $config['auth']['email'];
exec( 'wp core download' );
exec( "wp core install --url='$url' --title='$title' --admin_user='$user' --admin_password='$pass' --admin_email='$email' --skip-email" );

echo "Installing and activating the WP-CLI login helper plugin" . PHP_EOL;
exec( 'wp login install --activate --yes' );

echo "Logging you into the site" . PHP_EOL;
exec( 'wp valetbp login' );

echo 'DONE: Installation complete' . PHP_EOL;