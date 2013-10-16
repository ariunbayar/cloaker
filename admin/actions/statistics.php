<?php
if (empty($_GET['id']))
{
    header('Location: '.ADMIN_URL);
    exit;
}
else
{
    $viewData = $cloaker->getCampaignDetails(mysql_real_escape_string($_GET['id']));
    $viewData['page'] = (empty($_GET['page'])) ? 1 : (int)$_GET['page'];
    $viewData['statistics'] = $cloaker->getStatistics(mysql_real_escape_string($_GET['id']), $viewData['page']);
    $viewData['total_pages'] = $cloaker->countStatistics(mysql_real_escape_string($_GET['id']));
    View('statistics', $viewData);
    exit;
}
?>
