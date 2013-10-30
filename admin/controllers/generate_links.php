<?php
function generateShortCode()
{
    return substr(md5(time()), 0, 7);
}


function generate_links()
{
    global $cloaker;

    if (empty($_GET['id']))
    {
        header('Location: '.ADMIN_URL);
        exit;
    }

    $campaign_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    if ($_POST) {
        $tracker = new Tracker();
        $tracker->campaign_id = $campaign_id;
        $tracker->traffic_source_id = $_POST['traffic_source_id'];
        $tracker->shortcode = generateShortCode();
        $tracker->is_landing_page = (int)$_POST['landing_page'];
        $tracker->save();
        header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
        exit;
    }


    $viewData = $cloaker->getCampaignDetails($campaign_id);

    $options = to_select_options(TrafficSource::getByUserId($user_id));
    $viewData['traffic_source_options'] = $options;

    $viewData['network'] = Network::getById($viewData['network_id']);
    $viewData['trackers'] = Tracker::getByCampaignId($campaign_id);

    $viewData['current_page'] = 'generate_links';
    View('generate_links', $viewData);
    exit;
}


function regenerateUrl()
{
    // XXX looks like the id parameter is NOT always campaign id
    $tracker = Tracker::getById($_GET['id']);
    $tracker->shortcode = generateShortCode();
    if (!$tracker->save()) {
        Flash::set('Campaign URL could not be updated, because the '.
                   'following MySQL Error occurred: <br> <br>'.mysql_error());
    }

    header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
    exit;
}


function deleteTracker()
{
    $tracker = Tracker::getById($_GET['id']);
    if (!Tracker::deleteById($tracker->id)) {
        Flash::set('Campaign URL could not be updated, because the '.
                   'following MySQL Error occurred: <br> <br>'.mysql_error());
    }

    header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
