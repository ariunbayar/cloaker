<?php
function statistics_controller()
{
    global $cloaker;

    if (empty($_GET['id']))
    {
        header('Location: '.ADMIN_URL);
        exit;
    }

    // Did user requested to cleanup all the tracker records?
    if (isset($_GET['cleanup']) && $_GET['cleanup'] == 'yes'){
        $cloaker->deleteTrackerRecordsFor($_GET['id']);
        header('Location: '.ADMIN_URL.'statistics/'.$_GET['id'].'/');
        exit();
    }

    $allowed_filters = array(
        'id',
        'ip',
        'converted',
        'referer',
        'host',
        'country',
        'region',
        'city',
        'cloak',
        'cloak_reason',
        'access_date_from',
        'access_date_to',
        'traffic_source_id',
        'network_id',
        'offer_id',
        'tracker_id_for_lp',
    );
    $filters = array();
    foreach ($allowed_filters as $field) {
        if (isset($_GET[$field])){
            $filters[$field] = $_GET[$field];
        } else {
            $filters[$field] = "";
        }
    }
    $campaign_id = mysql_real_escape_string($_GET['id']);
    $viewData = $cloaker->getCampaignDetails($campaign_id);

    $options = to_select_options(TrafficSource::getByCampaignId($campaign_id));
    $viewData['traffic_sources'] = $options;
    $options = to_select_options(Network::getByCampaignId($campaign_id));
    $viewData['networks'] = $options;
    $viewData['offers'] = Offer::getByCampaignId($campaign_id);
    $viewData['tracker_options'] = Tracker::getLandingPageOptionsByCampaignId($campaign_id);

    $per_page = 50;

    $viewData['page'] = (empty($_GET['page'])) ? 1 : (int)$_GET['page'];
    $viewData['total_pages'] = $cloaker->countStatistics($filters, $per_page);
    $viewData['statistics'] = $cloaker->getStatistics($filters, $viewData['page'], $per_page);

    $viewData['errors'] = array();

    // Chart data for cloaked/non-cloaked page views
    list($viewData['total_page_views'], $max_days_reached) =
        $cloaker->getTotalPageViews($filters, 60);
    if ($max_days_reached){
        $viewData['warning'][] =
            'Regular and Cloaked views chart is limited to '.
            '60 days to avoid degraded performance.';
    }

    // Chart data for page views by geolocation
    list($viewData['page_views_by_geolocation'], $max_days_reached) =
        $cloaker->getPageViewsByGeolocation($viewData['geolocation'], $filters, 60);
    if ($max_days_reached){
        $viewData['warning'][] =
            'Page views by Geolocation chart is limited to '.
            '60 days to avoid degraded performance.';
    }

    $query_string = http_build_query($filters);
    $viewData['url_format'] = ADMIN_URL.'statistics/'.$_GET['id'].'/%s/?'.$query_string;
    $viewData['filters'] = &$filters;
    $viewData['current_page'] = 'statistics';

    View('statistics', $viewData);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
