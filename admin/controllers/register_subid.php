<?php
function register_subid_controller()
{
    global $cloaker;

    if ($_POST) {
        $subidlist = $_POST['subidlist'];
        if (!empty($subidlist)) {
            $result = $cloaker->saveSubId($subidlist);
            if ($result['correct_sub_id_count']) {
                $viewData['success'] = $result['correct_sub_id_count'].' Sub ID successfully updated ';
            } else {
                $viewData['errors'][] = 'Sub ID list could not be saved, because the following MySQL Error occurred: <br> <br>'.mysql_error();
            }
            if ($result['wrong_sub_id_count']) {
                $sub_id_error = $result['wrong_sub_id_count']." Wrong Sub ID. Please check Sub IDs.";
                $wrong_sub_id = array();
                foreach ($result['wrong_sub_id'] as $sub_id) {
                    $wrong_sub_id[] = $sub_id;
                }
                $viewData['errors'][] = $sub_id_error;
                $viewData['wrong_sub_ids'] = $wrong_sub_id;
            }
        } else {
            $viewData['errors'][] = 'Please insert Sub ID';
        }
    } else {
        $viewData['wrong_sub_ids'] = array();
    }

    $viewData['current_page'] = 'register_subid';
    View('register_subid', $viewData);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
