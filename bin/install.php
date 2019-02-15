<?php

$config = require dirname( __DIR__ ) . '/valetbp-config.php';

if ( $config['valet']['secure'] ) {
	echo "Running 'valet secure'…";
	exec( 'valet secure' );
}

// install composer dependencies
exec( 'composer install --prefer-dist', $output );

// create the DB
$db_user = $config['db']['user'];
$db_name = $config['db']['name'];
$db_pass = $config['db']['password'] ? " -p $db_pass" : '';
exec( "mysql -u {$db_user}{$db_pass} -e 'create database `{$db_name}`'" );

// install wordpress
$url   = $config['urls']['dev']['protocol'] . '//' . $config['urls']['dev']['host'];
$title = $config['site']['title'];
$user  = $config['auth']['username'];
$pass  = $config['auth']['password'];
$email = $config['auth']['email'];
exec( 'wp core download' );
exec( "wp core install --url='$url' --title='$title' --admin_user='$user' --admin_password='$pass' --admin_email='$email' --skip-email" );

// install and activate the wp cli login helper plugin
exec( 'wp login install --activate --yes' );

// log me into the new site
exec( 'wp valetbp log_me_in' );

echo 'INSTALLATION COMPLETE';