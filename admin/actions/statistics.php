<?php
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
    'referer',
    'host',
    'country',
    'region',
    'city',
    'cloak',
    'cloak_reason',
    'access_date_from',
    'access_date_to',
);
$filters = array();
foreach ($allowed_filters as $field) {
    if (isset($_GET[$field])){
        $filters[$field] = $_GET[$field];
    }
}

$viewData = $cloaker->getCampaignDetails(mysql_real_escape_string($_GET['id']));
$viewData['page'] = (empty($_GET['page'])) ? 1 : (int)$_GET['page'];
$viewData['total_pages'] = $cloaker->countStatistics($filters);
$viewData['statistics'] = $cloaker->getStatistics($filters, $viewData['page']);

$viewData['errors'] = array();

// Chart data for cloaked/non-cloaked page views
list($viewData['total_page_views'], $max_days_reached) =
    $cloaker->getTotalPageViews($filters, 60);
if ($max_days_reached){
    $viewData['errors'][] =
        'Regular and Cloaked views chart is limited to '.
        '60 days to avoid degraded performance.';
}

// Chart data for page views by geolocation
list($viewData['page_views_by_geolocation'], $max_days_reached) =
    $cloaker->getPageViewsByGeolocation($viewData['geolocation'], $filters, 60);
if ($max_days_reached){
    $viewData['errors'][] =
        'Page views by Geolocation chart is limited to '.
        '60 days to avoid degraded performance.';
}

$query_string = http_build_query($filters);
$viewData['url_format'] = ADMIN_URL.'statistics/'.$_GET['id'].'/%s/?'.$query_string;
$viewData['filters'] = &$filters;


View('statistics', $viewData);
?>
