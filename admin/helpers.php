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
 * Globals
 */
class G{
    static public $values = array();

    static public function get($key, $is_array=false)
    {
        $rval = ($is_array ? array() : null);

        if (isset(self::$values[$key])){
            $rval = self::$values[$key];
        }
        return $rval;
    }

    static public function set($key, $value, $is_array=false)
    {
        if ($is_array){
            if (!isset(self::$values[$key]))
                self::$values[$key] = array();
            self::$values[$key][] = $value;
        }else{
            self::$values[$key] = $value;
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
