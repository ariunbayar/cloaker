<?php

function traffic_source_controller()
{
    global $cloaker;

    $viewData['traffic_sources'] = $cloaker->getTrafficSources();
    $viewData['current_page'] = 'traffic_source';
    View('traffic_source', $viewData);
    exit;
}

function edit_traffic_source_controller()
{
    global $cloaker;

    list($viewData['id'],$viewData['name']) = $cloaker->getTrafficSource(mysql_real_escape_string($_GET['id']));
    $viewData['traffic_sources'] = $cloaker->getTrafficSources();
    $viewData['current_page'] = 'traffic_source';
    View('traffic_source', $viewData);
    exit;
}

function save_traffic_source_controller()
{
    global $cloaker;

    if (!$cloaker->saveTrafficSource(mysql_real_escape_string($_POST['name']), $_POST['id']))
    {
        $viewData['errors'][] = 'Traffic source could not be save, '.
            'because the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL.'/traffic_source/');
    exit;
}


function delete_traffic_source_controller()
{
    global $cloaker;

    if (!$cloaker->deleteTrafficSource(mysql_real_escape_string($_GET['id'])))
    {
        Flash::set('Traffic source could not be deleted, because the '.
            'following MySQL Error occurred: <br> <br>'.mysql_error());
    }
    
    header('Location: '.ADMIN_URL.'/traffic_source/');
    exit;
}
?>

<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
