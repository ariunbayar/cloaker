<?php

$admins = array("admin@example.com");
$subject = "Deployment log: ".date('Y-m-d H:i:s');
$file_name = dirname(__FILE__).'/deployment.log';

echo $admins.$subject.$file_name;

$content = file_get_contents($file_name);

foreach ($admins as $admin) {
    mail($admin, $subject, $content);
}

?>

