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
list($viewData['total_pages'], $viewData['statistics']) =
    $cloaker->getStatistics($filters, $viewData['page'], 5); // TODO change limit to default
$query_string = http_build_query($filters);
$viewData['url_format'] = ADMIN_URL.'statistics/'.$_GET['id'].'/%s/?'.$query_string;
$viewData['filters'] = &$filters;

// TODO charts


View('statistics', $viewData);
?>
