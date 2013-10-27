<?php
function global_ip_controller()
{
    global $cloaker;

    // only superadmin has access to this page
    if ($_SESSION['user_level'] != 'superadmin') {
        header('Location: '.ADMIN_URL.'giplist/');
        exit;
    }

    if ($_POST){
        $giplist = $_POST['giplist'];
        if ($cloaker->saveGiplist($giplist))
        {
            header('Location: '.ADMIN_URL.'giplist/');
            exit;
        }else{
            $viewData['errors'][] = 'Global denied ip list could not be saved, because the following MySQL Error occurred: <br> <br>'.mysql_error();
        }
    }

    $viewData['giplist'] = $cloaker->getGipList();
    $viewData['current_page'] = 'global_ip';
    View('global_ip', $viewData);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
