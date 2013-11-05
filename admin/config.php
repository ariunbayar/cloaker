<?php
// Default config for production environment
$config = array(
    'DB_HOST' => '127.0.0.1',
    'DB_USER' => 'cloaker',
    'DB_PASSWORD' => 't2cUdqbD84sn1iHR3P5kLZA2T76T571L',
    'DB_NAME' => 'cloaker',
    'ADMIN_URL' => 'http://cloaker.charlesjasonbush.com/admin/',
    'MIGRATE_PASSWORD' => 'b56zssTf4KvyQVsY',
);

$dev_environments = array(

    // local config for Ariunbayar
    'cloaker.dev' => array(
        'DB_USER' => 'root',
        'DB_PASSWORD' => '',
        'ADMIN_URL' => 'http://cloaker.dev/admin/',
    ),

);

$current_domain = $_SERVER['HTTP_HOST'];
if (array_key_exists($current_domain, $dev_environments)){
    $config = array_merge($config, $dev_environments[$current_domain]);
    // show errors in development environment
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Defines runtime constants for the environment
foreach ($config as $param => $value) {
    define($param, $value);
}
define('STATIC_URL', ADMIN_URL.'static/');  // full URL to static files (css, js etc.)
?>
