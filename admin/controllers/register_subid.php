<?php
function register_subid_controller()
{
    global $cloaker;

    if ($_POST){
        $subidlist = $_POST['subidlist'];
        if ($cloaker->saveSubid($subidlist))
        {
            Flash::set('Subid updated successfully');
            header("Location: ".ADMIN_URL."register_subid/");
            exit;
        }else{
            $viewData['errors'][] = 'Subid list could not be saved, because the following MySQL Error occurred: <br> <br>'.mysql_error();
        }
    }

    $viewData['current_page'] = 'register_subid';
    View('register_subid', $viewData);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
