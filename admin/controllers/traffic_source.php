<?php
function traffic_source_controller()
{
    global $cloaker;

    //$viewData = $cloaker->getTrafficSource();
    if (!empty($_POST))
    {
        switch($_POST['action'])
        {
            case 'add':
                if (!$cloaker->addTrafficSource(mysql_real_escape_string($_POST['name'])))
                {
                    $viewData['errors'][] = 'Traffic source could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                break;
            case 'delete':
                if (!$cloaker->deleteTrafficSource(mysql_real_escape_string($_POST['id'])))
                {
                    $viewData['errors'][] = 'Traffic source could not be deleted, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                break;
            case 'edit':
                list($viewData['id'],$viewData['name']) = $cloaker->getTrafficSource(mysql_real_escape_string($_POST['id']));
                break;
            case 'update':
                if
                    (!$cloaker->updateTrafficSource(mysql_real_escape_string($_POST['id']),
                        mysql_real_escape_string($_POST['name'])))
                {
                    $viewData['errors'][] = 'Traffic source could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                else
                {
                    $viewData['success'][] = 'Traffic source has been updated successfully';
                }
                break;
        }
    }
    $viewData['traffic_sources'] = $cloaker->getTrafficSources();
    
    View('traffic_source', $viewData);
    exit;
}


function delete_traffic_source_controller()
{
    global $cloaker;

    if (!$cloaker->deleteTrafficSource(mysql_real_escape_string($_GET['id'])))
    {
        $viewData['errors'][] = 'Traffic source could not be deleted, because the following MySQL Error occurred: <br> <br>'.mysql_error();
    }
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
