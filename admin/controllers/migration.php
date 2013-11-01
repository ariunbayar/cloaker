<?php
function migration_deploy_controller()
{
    // only superadmin has access to this page
    if ($_SESSION['user_level'] != 'superadmin') {
        header('Location: '.ADMIN_URL.'dashboard/');
        exit;
    }

    $prefix = 'tmp';

    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while ($row = mysql_fetch_row($result)) {
        $tables[] = $row[0];
    }

    // backup tables
    foreach ($tables as $table) {
        continue;
        $tmp_table = $prefix.'_'.$table;
        $sql = "CREATE TABLE $tmp_table LIKE $table";
        mysql_query($sql);
        $sql = "INSERT INTO $tmp_table SELECT * FROM $table";
        mysql_query($sql);
    }

    $has_error = false;

    $dir_path = dirname(__FILE__)."/../../migration/";
    $files = scandir($dir_path);
    foreach ($files as $file) {
        if (preg_match('/.sql$/', $file)){
            $migration = Migration::getByFileName($file);
            $file_name = ($migration) ? $migration->file_name : null;
            if (!($file_name == $file)) {
                //echo $file."\n";
                $sql_content = file_get_contents($dir_path.$file);
                // run each query separately
                $queries = explode(";", $sql_content);
                foreach ($queries as $query) {
                    $query = trim($query);
                    if ($query){
                        if (!mysql_query($query)) {
                            $has_error = true;
                            $error =  $file."\n"."Warning: This SQL has error! Please run manual.".
                                "\n".mysql_error()."\n".$query."\n";
                            Flash::set($error);
                        }
                    }
                }
                $migration = new Migration();
                $migration->file_name = $file;
                $migration->save();
            }
        }
    }
    
    if ($has_error) {  // Restore old tables
        foreach ($tables as $table){
            $tmp_table = $prefix.'_'.$table;
            $sql = "DROP TABLE $table";
            mysql_query($sql);
            $sql = "RENAME TABLE $tmp_table TO $table";
            mysql_query($sql);
        }
    } else {  // Remove temporary tables
        foreach ($tables as $table){
            $tmp_table = $prefix.'_'.$table;
            $sql = "DROP TABLE $tmp_table";
            mysql_query($sql);
        }
        $viewData['success'] = 'Migration run successfully!';
    }

    $viewData['current_page'] = 'migration_deploy';
    View('migration_deploy', $viewData);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
