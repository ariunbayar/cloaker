<?php
require dirname(__FILE__).'/admin/config.php';
require dirname(__FILE__).'/admin/models.php';

$link = mysql_connect(
    $config['DB_HOST'],
    $config['DB_USER'],
    ''
);

mysql_select_db($config['DB_NAME'], $link);

$dir_path = dirname(__FILE__)."/migration/";
$files = scandir($dir_path);
foreach ($files as $file) {
    if (preg_match('/.sql$/', $file)){
        $migration = Migration::getByFileName($file);
        $file_name = ($migration) ? $migration->file_name : null;
        if (!($file_name == $file)){
            echo $file."\n";
            $sql_content = file_get_contents($dir_path.$file);
            // run each query separately
            $queries = explode(";", $sql_content);
            foreach ($queries as $query) {
                $query = trim($query);
                if ($query){
                    if (!mysql_query($query)) {
                        echo "\n"."Warning: This SQL has error! Please run manual."."\n";
                        echo mysql_error()."\n";
                        echo $query."\n";
                        exit();
                    }
                }
            }
            $migration = new Migration();
            $migration->file_name = $file;
            $migration->save();
        }
    }
}
mysql_close($link);
?>
