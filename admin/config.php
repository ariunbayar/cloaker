<?php
// Default config for production environment
$config = array(
    'DB_HOST' => '127.0.0.1',
    'DB_USER' => 'root',
    'DB_PASSWORD' => 'apmsetup',
    'DB_NAME' => 'cloaker',
    'ADMIN_URL' => 'http://local.cloaker.test/admin/',
);

$dev_environments = array(

    // local config for Ariunbayar
    'cloaker.dev' => array(
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

// Defines runtime contstants for the environment
foreach ($config as $param => $value) {
    define($param, $value);
}
define('STATIC_URL', ADMIN_URL.'static/');  // full URL to static files (css, js etc.)
?>
