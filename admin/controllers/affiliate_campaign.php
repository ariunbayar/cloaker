<?php
function affiliate_campaign_controller()
{
    global $cloaker;

    if (!empty($_POST))
    {
        switch($_POST['action'])
        {
            case 'add':
                if
                    (!$cloaker->addAffiliateCampaign(mysql_real_escape_string($_POST['name']), mysql_real_escape_string($_POST['affiliate_network_id'])))
                {
                    $viewData['errors'][] = 'Affiliate campaign could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                break;
            case 'delete':
                if (!$cloaker->deleteAffiliateCampaign(mysql_real_escape_string($_POST['id'])))
                {
                    $viewData['errors'][] = 'Affiliate campaign could not be deleted, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                break;
            case 'edit':
                list($viewData['id'],$viewData['name'],$viewData['affiliate_network_id']) = $cloaker->getAffiliateCampaign(mysql_real_escape_string($_POST['id']));
                break;
            case 'update':
                if
                    (!$cloaker->updateAffiliateCampaign(mysql_real_escape_string($_POST['id']),
                        mysql_real_escape_string($_POST['name']),
                        mysql_real_escape_string($_POST['affiliate_network_id'])))
                {
                    $viewData['errors'][] = 'Affiliate campaign could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                else
                {
                    $viewData['success'][] = 'Affiliate campaign has been updated successfully';
                }
                break;
        }
    }
    $viewData['affiliate_campaigns'] = $cloaker->getAffiliateCampaigns();
    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_campaign';
    View('affiliate_campaign', $viewData);
    exit;
}

?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
