<?php

function network_controller($data = array())
{
    $networks = Network::getByCampaignId($_GET['id']);
    $campaign = get_entity_or_redirect('Campaign', $_GET['id']);

    $viewData = array_merge($data, array(
        'id' => $_GET['id'],  // TODO is a fallback to old templating
        'campaign' => $campaign,
        'networks' => $networks,
        'current_page' => 'network',
    ));
    View('network', $viewData);
    exit;
}


function edit_network_controller()
{
    $data['network'] = Network::getById($_GET['network_id']);
    network_controller($data);
}


function save_network_controller()
{
    $network = new Network;
    $network->id = $_POST['network_id'];
    $network->name = $_POST['name'];
    $network->campaign_id = $_GET['id'];
    $network->save();


    header('Location: '.ADMIN_URL."/network/{$_GET['id']}/");
    exit;
}


function delete_network_controller()
{
    if (!Network::deleteById($_GET['network_id']))
    {
        $viewData['errors'][] = 'Network could not be deleted, because '. 
            'the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL."/network/{$_GET['id']}/");
    exit;
}

?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
