<?php
function add_campaign_controller()
{
    global $cloaker;

    if ($_POST)
    {
        foreach($_POST as $key => $value)
        {
            $values[$key] = mysql_real_escape_string($value);
        }

        if ($cloaker->insertCampaign($values)) {
            header('Location: '.ADMIN_URL);
            exit;
        }else{
            $viewData['errors'][] = 'Campaign could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
        }
    }

    $to_options = function ($entities){
        $options = array();
        foreach ($entities as $entity) {
            $options[$entity->id] = $entity->name;
        }
        return $options;
    };

    // Traffic source options
    //$options = $to_options(TrafficSource::getByUserId($_SESSION['user_id']));
    //$viewData['traffic_source_options'] = $options;

    // Affiliate campaign options
    $options = $to_options(AffiliateNetwork::getByUserId($_SESSION['user_id']));
    $viewData['network_options'] = $options;

    $viewData['current_page'] = 'add_campaign';
    View('add_campaign', $viewData);
    exit;
}


function manage_campaign_controller()
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
        if (!empty($_POST)) // the update form has been submitted
        {

            foreach($_POST as $key => $value)
            {
                $values[$key] = mysql_real_escape_string($value);
            }
            if (!$cloaker->updateCampaign($values))
            {
                $viewData['errors'][] = 'Campaign could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
            }
            else
            {
                $viewData = $cloaker->getCampaignDetails($campaignID);
                $viewData['success'][] = 'Campaign was updated successfully!';
            }
        }

        $to_options = function ($entities){
            $options = array();
            foreach ($entities as $entity) {
                $options[$entity->id] = $entity->name;
            }
            return $options;
        };

        // Traffic source options
        //$options = $to_options(TrafficSource::getByUserId($_SESSION['user_id']));
        //$viewData['traffic_source_options'] = $options;

        // Affiliate campaign options
        $options = $to_options(AffiliateNetwork::getByUserId($_SESSION['user_id']));
        $viewData['network_options'] = $options;

        $viewData['destinations'] = $cloaker->getDestinations($campaignID);
        $viewData['current_page'] = 'manage';
        View('manage', $viewData);
        exit;
    }
}


function delete_campaign_controller()
{
    global $cloaker;

    if (!$cloaker->deleteCampaign(mysql_real_escape_string($_GET['id'])))
    {
        Flash::set('Campaign could not be added, because the following '.
                   'MySQL Error occurred: <br> <br>'.mysql_error());
    }
    header('Location: '.ADMIN_URL);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
