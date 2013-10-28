<?php
function affiliate_network_controller()
{
    global $cloaker;

    if (!empty($_POST))
    {
        switch($_POST['action'])
        {
            case 'add':
                if (!$cloaker->addAffiliateNetwork(mysql_real_escape_string($_POST['name'])))
                {
                    $viewData['errors'][] = 'Affiliate network could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                break;
            case 'edit':
                list($viewData['id'],$viewData['name']) = $cloaker->getAffiliateNetwork(mysql_real_escape_string($_POST['id']));
                break;
            case 'update':
                if
                    (!$cloaker->updateAffiliateNetwork(mysql_real_escape_string($_POST['id']),
                        mysql_real_escape_string($_POST['name'])))
                {
                    $viewData['errors'][] = 'Affiliate network could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                else
                {
                    $viewData['success'][] = 'Affiliate network has been updated successfully';
                }
                break;
        }
    }
    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_network';
    View('affiliate_network', $viewData);
    exit;
}

function delete_affiliate_network_controller()
{
    global $cloaker;

    if (!$cloaker->deleteAffiliateNetwork(mysql_real_escape_string($_GET['id'])))
    {
        $viewData['errors'][] = 'Affiliate network could not be deleted, because the following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    $viewData['affiliate_networks'] = $cloaker->getAffiliateNetworks();
    $viewData['current_page'] = 'affiliate_network';
    View('affiliate_network', $viewData);
    exit;
}

?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
