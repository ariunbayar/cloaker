<?php
function generate_links()
{
    global $cloaker;

    if (empty($_GET['id']))
    {
        header('Location: '.ADMIN_URL);
        exit;
    }

    $campaign_id = $_GET['id'];

    $viewData = $cloaker->getCampaignDetails($campaign_id);
    $viewData['current_page'] = 'generate_links';
    $viewData['trackers'] = Tracker::getByCampaignId($campaign_id);
    View('generate_links', $viewData);
    exit;
}


function regenerateUrl()
{
    // XXX looks like the id parameter is NOT always campaign id
    $tracker = Tracker::getById($_GET['id']);
    if (!$tracker->changeShortcode()) {
        Flash::set('Campaign URL could not be updated, because the '.
                   'following MySQL Error occurred: <br> <br>'.mysql_error());
    }

    header("Location: ".ADMIN_URL."/generate_links/{$tracker->campaign_id}/");
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
