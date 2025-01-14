<?php
function _get_migration_files()
{
    $migrations = array();
    $files = scandir(MIGRATION_DIR);
    foreach ($files as $file) {
        if (preg_match('/.sql$/', $file)){
            $migrations[] = $file;
        }
    }
    return $migrations;
}


function _run_migration()
{
    $prefix = 'tmp';

    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while ($row = mysql_fetch_row($result)) {
        $tables[] = $row[0];
    }

    // backup tables
    foreach ($tables as $table) {
        $tmp_table = $prefix.'_'.$table;
        $sql = "CREATE TABLE $tmp_table LIKE $table";
        mysql_query($sql);
        $sql = "INSERT INTO $tmp_table SELECT * FROM $table";
        mysql_query($sql);
    }

    $has_error = false;

    foreach (_get_migration_files() as $file) {
        $migration = Migration::getByFileName($file);
        $file_name = ($migration ? $migration->file_name : null);

        if ($file_name != $file) {
            $sql_content = file_get_contents(MIGRATION_DIR.$file);
            // run each query separately
            $queries = explode(";", $sql_content);
            foreach ($queries as $query) {
                $query = trim($query);
                if ($query){
                    if (!mysql_query($query)) {
                        $has_error = true;
                        $error =  $file."\n"."Warning: This SQL has error! Please run manual.".
                            "\n".mysql_error()."\n".$query."\n";
                        Flash::set('Error', $error);
                        break;
                    }
                }
            }
            $migration = new Migration();
            $migration->file_name = $file;
            $migration->save();
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
        Flash::set('Success', 'Migration has run successfully!<br/>'
                   .'TODO: Show this message as success');
    }
}


function migration_deploy_controller()
{
    // must supply correct password when running from automated script
    if (!(isset($_GET['password']) && $_GET['password'] == MIGRATE_PASSWORD)){
        // only superadmin has access to this page
        if ($_SESSION['user_level'] != 'superadmin') {
            header('Location: '.ADMIN_URL.'dashboard/');
            exit;
        }
    }

    if (isset($_GET['execute']) && $_GET['execute'] == 'yes'){
        _run_migration();
        header('Location: '.ADMIN_URL.'/migration_deploy/');
        exit;
    }

    $data = array(
        'current_page' => 'migration_deploy',
        'migration_files' => _get_migration_files(),
        'migrations' => Migration::getAll(),
    );
    View('migration_deploy', $data);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
