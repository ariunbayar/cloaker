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
    $viewData['traffics'] = $cloaker->getTrafficSources();
    $cloaker->updateNumPageViewsFor($viewData['campaigns'], $viewData['filters']);
    if($_SESSION['user_level'] == 'superadmin') {
        $viewData['giplist'] = $cloaker->getGipList();
    }

    $viewData['current_page'] = 'dashboard';
    View('dashboard', $viewData);
}
?>
