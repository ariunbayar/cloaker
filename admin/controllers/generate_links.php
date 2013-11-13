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
        if ($tracker->is_landing_page) {
            $tracker->landing_page_url = $_POST['landing_page_url'];
        }
        $tracker->network_id = $_POST['network_id'];
        $tracker->cpc = $_POST['cost_per_click'];
        $tracker->save();

        foreach ($_POST['offer_ids'] as $offer_id) {
            $tracker_offer = new TrackerOffer;
            $tracker_offer->tracker_id = $tracker->id;
            $tracker_offer->offer_id = $offer_id;
            $tracker_offer->save();
        }

        header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
        exit;
    }

    $viewData = $cloaker->getCampaignDetails($campaign_id);

    $options = to_select_options(TrafficSource::getByCampaignId($campaign_id));
    $viewData['traffic_source_options'] = $options;
    $options = to_select_options(Network::getByCampaignId($campaign_id));
    $viewData['network_options'] = $options;

    $viewData['offers'] = Offer::getByCampaignId($campaign_id);

    $viewData['trackers'] = Tracker::getByCampaignId($campaign_id);

    $viewData['current_page'] = 'generate_links';
    View('generate_links', $viewData);
    exit;
}


function edit_tracker_controller()
{
    global $cloaker;

    if ($_POST) {
        $tracker = Tracker::getById($_GET['id']);
        $tracker->traffic_source_id = $_POST['traffic_source_id'];
        $tracker->is_landing_page = (int)$_POST['landing_page'];
        if ($tracker->is_landing_page) {
            $tracker->landing_page_url = $_POST['landing_page_url'];
        }
        $tracker->network_id = $_POST['network_id'];
        $tracker->cpc = $_POST['cost_per_click'];
        $tracker->save();

        if (!TrackerOffer::deleteByTrackerId($tracker->id)) {
            $errors[] = 'Generated URL could not be delete, because the '.
                        'following MySQL Error occurred: <br> <br>'.mysql_error();
        }
        foreach ($_POST['offer_ids'] as $offer_id) {
            $tracker_offer = new TrackerOffer;
            $tracker_offer->tracker_id = $tracker->id;
            $tracker_offer->offer_id = $offer_id;
            $tracker_offer->save();
        }
        header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
        exit;
    }

    $viewData['tracker'] = Tracker::getById($_GET['id']);

    $campaign_id = $viewData['tracker']->campaign_id;
    $viewData = $cloaker->getCampaignDetails($viewData['tracker']->campaign_id);

    $viewData['tracker'] = Tracker::getById($_GET['id']);

    $options = to_select_options(TrafficSource::getByCampaignId($campaign_id));
    $viewData['traffic_source_options'] = $options;
    $options = to_select_options(Network::getByCampaignId($campaign_id));
    $viewData['network_options'] = $options;

    $viewData['offers'] = Offer::getByCampaignId($campaign_id);

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
        Flash::set('Error', 'Campaign URL could not be updated, because the '.
                   'following MySQL Error occurred: <br> <br>'.mysql_error());
    }

    header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
    exit;
}


function deleteTracker()
{
    $tracker = Tracker::getById($_GET['id']);
    $errors = array();

    if (!TrackerOffer::deleteByTrackerId($tracker->id)) {
        $errors[] = 'Generated URL could not be delete, because the '.
                    'following MySQL Error occurred: <br> <br>'.mysql_error();
    }
    if (!Tracker::deleteById($tracker->id)) {
        $errors[] = 'Generated URL could not be delete, because the '.
                    'following MySQL Error occurred: <br> <br>'.mysql_error();
    }
    if ($errors) {
        Flash::set('Error', implode('<br/>', $errors));
    }

    header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
    exit;
}
?>
