<?php

$admins = array("admin@example.com");

$subject = "Deployment log: ".date('Y-m-d H:i');
$file_name = dirname(__FILE__).'/../deployment.log';

$content = file_get_contents($file_name);

foreach ($admins as $admin) {
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "To: ".$admin."\n";
    $headers .= "From: <server@cloaker.com>\n";

    @mail($admin, $subject, $content, $headers);
}
?>

