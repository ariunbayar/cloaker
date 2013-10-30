<?php

function network_controller()
{
    global $cloaker;

    $viewData['networks'] = $cloaker->getNetworks();
    $viewData['current_page'] = 'network';
    View('network', $viewData);
    exit;
}

function edit_network_controller()
{
    global $cloaker;
    list($viewData['id'],$viewData['name']) = $cloaker->getNetwork(mysql_real_escape_string($_GET['id']));

    $viewData['networks'] = $cloaker->getNetworks();
    $viewData['current_page'] = 'network';
    View('network', $viewData);
    exit;
}

function save_network_controller()
{
    global $cloaker;
    if (!$cloaker->saveNetwork(mysql_real_escape_string($_POST['name']), $_POST['id']))
    {
        $viewData['errors'][] = 'Network could not be added, because '.
            'the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL.'/network/');
    exit;
}

function delete_network_controller()
{
    if (!Network::deleteById($_GET['id']))
    {
        $viewData['errors'][] = 'Network could not be deleted, because '. 
            'the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL.'/network/');
    exit;
}

?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
