<?php

function affiliate_network_controller()
{
    global $cloaker;

    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_network';
    View('affiliate_network', $viewData);
    exit;
}

function edit_affiliate_network_controller()
{
    global $cloaker;
    list($viewData['id'],$viewData['name']) = $cloaker->getAffiliateNetwork(mysql_real_escape_string($_GET['id']));

    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_network';
    View('affiliate_network', $viewData);
    exit;
}

function save_affiliate_network_controller()
{
    global $cloaker;
    if (!$cloaker->saveAffiliateNetwork(mysql_real_escape_string($_POST['name']), $_POST['id']))
    {
        $viewData['errors'][] = 'Affiliate network could not be added, because '.
            'the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL.'/affiliate_network/');
    exit;
}

function delete_affiliate_network_controller()
{
    global $cloaker;

    if (!$cloaker->deleteAffiliateNetwork(mysql_real_escape_string($_GET['id'])))
    {
        $viewData['errors'][] = 'Affiliate network could not be deleted, because '. 
            'the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL.'/affiliate_network/');
    exit;
}

?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
