<?php
function global_ip_controller()
{
    global $cloaker;

    $giplist = $_POST['giplist'];
    if (!$cloaker->saveGiplist($giplist))
    {
        $viewData['errors'][] = 'Global denied ip list could not be saved, because the following MySQL Error occurred: <br> <br>'.mysql_error();
    }
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
