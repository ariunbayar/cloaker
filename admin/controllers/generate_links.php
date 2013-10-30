<?php
function generate_links()
{
    global $cloaker;

    if (empty($_GET['id']))
    {
        header('Location: '.ADMIN_URL);
        exit;
    }

    $viewData = $cloaker->getCampaignDetails($_GET['id']);
    $viewData['current_page'] = 'generate_links';
    View('generate_links', $viewData);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
