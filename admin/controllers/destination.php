<?php
function manage_destination_controller()
{
    global $cloaker;

    if (empty($_GET['id']))
    {
        header('Location: '.ADMIN_URL);
        exit;
    }
    else
    {
        $campaignID = mysql_real_escape_string($_GET['id']);
        $viewData = $cloaker->getCampaignDetails($campaignID);
        if (!empty($_POST))
        {
            switch($_POST['action'])
            {
                case 'add':
                    if (!$cloaker->addDestination($campaignID, mysql_real_escape_string($_POST['url']), mysql_real_escape_string($_POST['notes'])))
                    {
                        $viewData['errors'][] = 'Destination could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                    }
                    break;
                case 'delete':
                    if (!$cloaker->deleteDestination(mysql_real_escape_string($_POST['id'])))
                    {
                        $viewData['errors'][] = 'Destination could not be deleted, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                    }
                    break;
                case 'edit':
                    list($viewData['url'],$viewData['notes']) = $cloaker->getDestinationDetails(mysql_real_escape_string($_POST['id']));
                    break;
                case 'update':
                    if (!$cloaker->updateDestination(mysql_real_escape_string($_POST['id']), mysql_real_escape_string($_POST['url']), mysql_real_escape_string($_POST['notes'])))
                    {
                        $viewData['errors'][] = 'Destination could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                    }
                    else
                    {
                        $viewData['success'][] = 'Destination has been updated successfully';
                    }
                    break;
            }
        }
        $viewData['destinations'] = $cloaker->getDestinations($campaignID);
        View('destinations', $viewData);
        exit;
    }
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
