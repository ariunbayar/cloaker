<?php
function dashboard_controller()
{
    global $cloaker;

    // date range filter values
    $today = date('Y-m-d');
    $viewData['filters'] = array(
        'date_from' => (isset($_GET['date_from']) ? $_GET['date_from'] : $today),
        'date_to' => (isset($_GET['date_to']) ? $_GET['date_to'] : $today),
    );
    $viewData['campaigns'] = $cloaker->getCampaignDetails();
    $cloaker->updateNumPageViewsFor($viewData['campaigns'], $viewData['filters']);

    $viewData['current_page'] = 'dashboard';
    View('dashboard', $viewData);
    exit;
}
?>
