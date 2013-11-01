<?php
require dirname(__FILE__).'/admin/config.php';
require dirname(__FILE__).'/admin/models.php';
var_dump(dirname(__FILE__).'/admin/models.php'); die();

$link = mysql_connect(
    $config['DB_HOST'],
    $config['DB_USER'],
    ''
);

?>
