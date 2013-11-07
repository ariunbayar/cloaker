<?php
/*
 * HOOK
 */
if ($_GET['code'] == 'tqYeSEujmPvwaYQp'){
    $filename = dirname(__FILE__).'/deploy_requests';
    $value = date("Y-m-d H:i:s\n");
    file_put_contents($filename, $value, FILE_APPEND);
    echo "deployment requested!";
}
?>
