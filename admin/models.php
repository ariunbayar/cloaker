<?php
class Model
{
    protected $_values = array();

    public function __construct()
    {
        foreach ($this->_fields as $field) {
            if (!array_key_exists($field, $this->_values)){
                $this->_values[$field] = null;
            }
        }
    }

    public function __get($name)
    {
        if (in_array($name, $this->_fields)){
            return $this->_values[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->_fields)){
            $this->_values[$name] = $value;
        }
    }

    public function save()
    {
        $is_editing = is_numeric($this->id);

        if ($is_editing){
            $fields = '';
            // Example:
            // $this->_values= array('name' => 'bold', 'created_at' => '2013-09-11 00:00:00');
            foreach ($this->_values as $field => $value){
                $fields .= sprintf($field."='%s', ", $value);
                $value = mysql_escape_string($value);
            }
            $fields = rtrim($fields, ", ");
            // Example:
            // $fields = "name='bold', created_at='2013-09-11 00:00:00'";

            $id = mysql_real_escape_string($this->id);
            $query = "UPDATE %s SET %s WHERE id=$id";
            $sql = sprintf($query, $this::$_table, $fields);
        } else {
            $field_names = '';
            $field_values = '';
            // Example:
            // $this->_values= array('name' => 'bold', 'created_at' => NULL);
            foreach ($this->_values as $field => $value) {
                if ($field == 'created_date'){
                    $value = date("Y-m-d H:i:s");
                }
                $field_names .= $field.', ';
                $value = mysql_escape_string($value);
                $field_values .= sprintf("'%s', ", $value);
            }
            $field_values = rtrim($field_values, ", ");
            $field_names = rtrim($field_names, ", ");
            // Example:
            // $field_names = "name, created_at";
            // $field_values = "'bold', '2013-09-11 00:00:00'";
            $query = "INSERT INTO %s (%s) VALUES (%s)";
            $sql = sprintf($query, $this::$_table, $field_names, $field_values);
        }
        $rs = mysql_query($sql);
        return $rs;
    }

    /**
     * @param int Id of the entity to get
     * @return mixed Instance of the object or null
     */
    static public function getById($id)
    {
        $class = get_called_class();
        $id = mysql_real_escape_string($id);

        $query = "SELECT * FROM %s WHERE id = '%s' LIMIT 1";
        $sql = sprintf($query, $class::$_table, $id);
        $rs = mysql_query($sql);

        $entity = mysql_fetch_object($rs, get_called_class());
        return $entity;
    }

    static public function hydrate($sql)
    {
        $class = get_called_class();
        $rs = mysql_query($sql);

        $entities = array();
        while ($obj = mysql_fetch_object($rs, $class))
        {
            $entities[] = $obj;
        }
        return $entities;
    }
}


class TrafficSource
{
    public $id;
    public $name;
    public $user_id;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int User id to look up
     * @return array Array of objects
     */
    static public function getByUserId($user_id)
    {
        $user_id = mysql_real_escape_string($user_id);

        $query = "SELECT * FROM traffic_source WHERE user_id = '%s' ORDER BY id ASC";
        $sql = sprintf($query, $user_id);
        $rs = mysql_query($sql);

        $entities = array();
        while ($obj = mysql_fetch_object($rs, get_class()))
        {
            $entities[] = $obj;
        }

        return $entities;
    }

    static public function getById($id)
    {
        $id = mysql_real_escape_string($id);

        $query = "SELECT * FROM traffic_source WHERE id = '%s'";
        $sql = sprintf($query, $id);
        $rs = mysql_query($sql);

        $obj = mysql_fetch_object($rs, get_class());

        return $obj;
    }

    /**
	 * Deletes a traffic source by ID
	 * @return Boolean TRUE upon success, FALSE upon failure
	 */
	static public function deleteById($id)
	{
        $id = mysql_real_escape_string($id);
		$query = "DELETE FROM traffic_source WHERE id = '%s'";
        $sql = sprintf($query, $id);
        $result = mysql_query($sql);
		return $result;
	}

    /**
     * Save a traffic source by ID
     */
    public function save()
	{
        $name = mysql_real_escape_string($this->getName());
        $user_id = mysql_real_escape_string($this->getUserId());
        $id = mysql_real_escape_string($this->getId());

        if ($id == null){
            $query = "INSERT INTO `traffic_source` (`id`, `name`, `user_id`) VALUES (null, '%s', '%s')";
            $sql = sprintf($query, $name, $user_id);

        } else {
            $query = "UPDATE `traffic_source` SET `name` = '%s' WHERE `id` = '%s'";
            $sql = sprintf($query, $name, $id);
        }
		$result = mysql_query($sql);
		return $result;
	}
}


class Tracker extends Model
{
    static public $_table = 'tracker';
    protected $_fields = array(
        'id',
        'campaign_id',
        'traffic_source_id',
        'shortcode',
    );

    /**
     * @param int Campaign id to look up
     * @return array Array of objects
     */
    static public function getByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);

        $query = "SELECT * FROM %s WHERE campaign_id = '%s'";
        $sql = sprintf($query, self::$_table, $campaign_id);
        return self::hydrate($sql);
    }

    /**
     * Changes the shortcode for a generated link
     *
     * @return Boolean Indicates if it succeeded
     */
    public function changeShortcode()
    {
        $this->shortcode = substr(md5(time()), 0, 7);
        return $this->save();
    }
}


class Network extends Model
{
    static public $_table = 'network';
    protected $_fields = array(
        'id',
        'name',
        'user_id',
    );

    /**
     * @param int User id to look up
     * @return array Array of objects
     */
    static public function getByUserId($user_id)
    {
        $user_id = mysql_real_escape_string($user_id);

        $query = "SELECT * FROM %s WHERE user_id = '%s' ORDER BY id ASC";
        $sql = sprintf($query, self::$_table, $user_id);
        return self::hydrate($sql);
    }
}
?>
