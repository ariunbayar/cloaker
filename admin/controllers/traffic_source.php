<?php

function traffic_source_controller()
{
    $viewData['traffic_sources'] = TrafficSource::getByUserId($_SESSION['user_id']);

    $viewData['current_page'] = 'traffic_source';
    View('traffic_source', $viewData);
    exit;
}

function edit_traffic_source_controller()
{
    global $cloaker;

    list($viewData['id'],$viewData['name']) = $cloaker->getTrafficSource(mysql_real_escape_string($_GET['id']));

    $viewData['traffic_sources'] = TrafficSource::getByUserId($_SESSION['user_id']);
    $viewData['current_page'] = 'traffic_source';
    View('traffic_source', $viewData);
    exit;
}

function save_traffic_source_controller()
{
    $traffic_source = new TrafficSource;
    $traffic_source->setName($_POST['name']);
    $traffic_source->setUserId($_SESSION['user_id']);
    $traffic_source->setId($_POST['id']);
    $traffic_source->save();

    header('Location: '.ADMIN_URL.'/traffic_source/');
    exit;
}


function delete_traffic_source_controller()
{
    if (!TrafficSource::deleteById($_GET['id']))
    {
        Flash::set('Traffic source could not be deleted, because the '.
            'following MySQL Error occurred: <br> <br>'.mysql_error());
    };

    header('Location: '.ADMIN_URL.'/traffic_source/');
    exit;
}
?>

<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
