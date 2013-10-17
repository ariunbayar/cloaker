<?php
if (empty($_GET['id']))
{
    header('Location: '.ADMIN_URL);
    exit;
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
$query_string = http_build_query($filters);
$viewData['url_format'] = ADMIN_URL.'statistics/'.$_GET['id'].'/%s/?'.$query_string;
$viewData['filters'] = &$filters;

//$viewData['daily_page_views'] = $cloaker->getDailyPageViews();


View('statistics', $viewData);
?>
