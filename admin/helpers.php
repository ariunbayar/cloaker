<?php
/**
 * Flash messages
 */
class Flash
{
    public function set($message)
    {
        $_SESSION['flash_message'] = $message;
    }

    public function get()
    {
        if (isset($_SESSION['flash_message'])){
            return $_SESSION['flash_message'];
        }else{
            return '';
        }
    }
}


/**
 * View() - Loads a view
 *
 * @param String $name Name of the view file
 * @param Array  $data An optional array with view data.
 * @return void
 */
function View($name, $data = '')
{
    include dirname(__FILE__).'/views/'.$name.'.php';
}

?>
