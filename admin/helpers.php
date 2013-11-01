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


function select_tag($name, $options, $selected = '', $empty_value = false,
                    $attrs = array())
{
    $attributes = '';
    foreach ($attrs as $key => $value) {
        $attributes .= sprintf('%s="%s" ', $key, $value);
    }
    ob_start();
    ?>
    <select name="<?php echo $name ?>" id="<?php echo $name ?>"
            <?php echo $attributes ?>>
        <?php if ($empty_value) {?>
            <option value=''><?php echo $empty_value ?></option>
        <?php } ?>
        <?php foreach ($options as $key => $value){ ?>
            <?php if (is_array($value)) {?>
                <?php list($label, $_options) = $value ?>
                <optgroup label="<?php echo $label ?>">
                    <?php foreach ($_options as $_key => $_value){ ?>
                        <?php $is_selected = (!is_null($selected) && $_key == $selected) ?>
                        <option value="<?php echo htmlspecialchars($_key) ?>" <?php if ($is_selected) echo 'selected="selected"' ?>>
                            <?php echo htmlspecialchars($_value)?>
                        </option>
                    <?php } ?>
                </optgroup>
            <?php }else{ ?>
                <?php $is_selected = (!is_null($selected) && $key == $selected) ?>
                <option value="<?php echo htmlspecialchars($key) ?>" <?php if ($is_selected) echo 'selected="selected"' ?>>
                    <?php echo htmlspecialchars($value)?>
                </option>
            <?php } ?>
        <?php } ?>
    </select>
    <?php
    $html = ob_get_clean();
    return $html;
}


function anchor_tag($url, $text=null, $new_window=false)
{
    $url = htmlspecialchars($url);
    $text = ($text ? htmlspecialchars($text) : $url);

    if ($new_window) {
        $format = '<a href="%s" target="_blank">%s</a>';
    }else{
        $format = '<a href="%s">%s</a>';
    }

    $html = sprintf($format, $url, $text);
    return $html;
}


function to_select_options($entities, $key_field='id', $value_field='name')
{
    $options = array();
    foreach ($entities as $entity) {
        $options[$entity->{$key_field}] = $entity->{$value_field};
    }
    return $options;
}


function get_entity_or_redirect($model_class, $id)
{
    if (is_numeric($id)) {
        $entity = $model_class::getById($id);
        if ($entity instanceof $model_class){
            return $entity;
        }
    }

    header('Location: '.ADMIN_URL);
    exit;
}
?>
