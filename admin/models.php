<?php
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
        // TODO remove Cloaker->getTrafficSources

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

class Network
{
    public $id;
    public $name;
    public $user_id;

    /**
     * @param int User id to look up
     * @return array Array of objects
     */
    static public function getByUserId($user_id)
    {
        $user_id = mysql_real_escape_string($user_id);

        $query = "SELECT * FROM network WHERE user_id = '%s' ORDER BY id ASC";
        $sql = sprintf($query, $user_id);
        $rs = mysql_query($sql);

        $entities = array();
        while ($obj = mysql_fetch_object($rs, get_class()))
        {
            $entities[] = $obj;
        }
        return $entities;

    }
}
?>
