<?php
/**
 * Flash messages
 */
class Flash
{
    static public function set($message)
    {
        $_SESSION['flash_message'] = $message;
    }

    static public function exists()
    {
        return isset($_SESSION['flash_message']);
    }

    static public function get()
    {
        if (self::exists()){
            $msg = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $msg;
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