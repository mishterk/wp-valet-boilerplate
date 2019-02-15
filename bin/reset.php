<?php

$config = require dirname( __DIR__ ) . '/valetbp-config.php';

echo "Dropping the database" . PHP_EOL;
$db_user = $config['db']['user'];
$db_name = $config['db']['name'];
$db_pass = $config['db']['password'] ? " -p $db_pass" : '';
exec( "mysql -u {$db_user}{$db_pass} -e 'drop database `{$db_name}`'" );

echo "Deleting the WordPress core" . PHP_EOL;
exec( 'rm -rf wp' );

echo "Deleting the wp-content directory" . PHP_EOL;
exec( 'rm -rf wp-content' );

echo "Deleting Composer's vendor directory and lock file" . PHP_EOL;
exec( 'rm -rf vendor' );
exec( 'rm -f composer.lock' );

echo 'DONE: Installation was reset' . PHP_EOL;