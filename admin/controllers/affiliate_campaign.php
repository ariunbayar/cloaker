<?php

function affiliate_campaign_controller()
{
    global $cloaker;

    $viewData['affiliate_campaigns'] = $cloaker->getAffiliateCampaigns();
    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_campaign';
    $viewData['id'] = $viewData['name'] = $viewData['affiliate_network_id'] = null;
    View('affiliate_campaign', $viewData);
    exit;
}

function edit_affiliate_campaign_controller()
{
    global $cloaker;
    list($viewData['id'],$viewData['name'],$viewData['affiliate_network_id'])
        = $cloaker->getAffiliateCampaign(mysql_real_escape_string($_GET['id']));

    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['affiliate_campaigns'] = $cloaker->getAffiliateCampaigns();
    $viewData['current_page'] = 'affiliate_campaign';
    View('affiliate_campaign', $viewData);
    exit;
}

function save_affiliate_campaign_controller()
{
    global $cloaker;

    if (!$cloaker->saveAffiliateCampaign(mysql_real_escape_string($_POST['name']),
            $_POST['affiliate_network_id'], $_POST['id']))
    {
        $viewData['errors'][] = 'Affiliate campaign could not be added, because '.
            'the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL.'/affiliate_campaign/');
    exit;
}

function delete_affiliate_campaign_controller()
{
    global $cloaker;
    if (!$cloaker->deleteAffiliateCampaign(mysql_real_escape_string($_GET['id'])))
    {
        Flash::set('Affiliate campaign could not be deleted, because'.
            ' the following MySQL Error occurred: <br> <br>'.mysql_error());
    }

    header('Location: '.ADMIN_URL.'/affiliate_campaign/');
    exit;
}

//affiliate network controllers

function affiliate_network_controller()
{
    global $cloaker;

    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_network';
    View('affiliate_campaign', $viewData);
    exit;
}

function edit_affiliate_network_controller()
{
    global $cloaker;
    list($viewData['id'],$viewData['name']) = $cloaker->getAffiliateNetwork(mysql_real_escape_string($_GET['id']));

    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_network';
    View('affiliate_campaign', $viewData);
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

    header('Location: '.ADMIN_URL.'/affiliate_campaign/');
    exit;
}

function delete_affiliate_network_controller()
{
    global $cloaker;

    if (!$cloaker->deleteAffiliateCampaignsByNetId(mysql_real_escape_string($_GET['id'])))
    {
        Flash::set('Affiliate campaign could not be deleted, because'.
            ' the following MySQL Error occurred: <br> <br>'.mysql_error());
    }

    if (!$cloaker->deleteAffiliateNetwork(mysql_real_escape_string($_GET['id'])))
    {
        $viewData['errors'][] = 'Affiliate network could not be deleted, because '. 
            'the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    header('Location: '.ADMIN_URL.'/affiliate_campaign/');
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
