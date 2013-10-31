<?php

function network_controller()
{
    $viewData['networks'] = Network::getByUserId($_SESSION['user_id']);

    $viewData['current_page'] = 'network';
    View('network', $viewData);
    exit;
}

function edit_network_controller()
{
    $viewData['network'] = Network::getById($_GET['id']);
    $viewData['networks'] = Network::getByUserId($_SESSION['user_id']);

    $viewData['current_page'] = 'network';
    View('network', $viewData);
    exit;
}

function save_network_controller()
{
    $network = new Network;
    $network->name = $_POST['name'];
    $network->user_id = $_SESSION['user_id'];
    $network->id = $_POST['id'];
    $network->save();


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
