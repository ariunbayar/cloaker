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


class Tracker
{
    public $id;
    public $campaign_id;
    public $traffic_source_id;
    public $shortcode;

    /**
     * @param int Campaign id to look up
     * @return array Array of objects
     */
    static public function getByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);

        $query = "SELECT * FROM tracker WHERE campaign_id = '%s'";
        $sql = sprintf($query, $campaign_id);
        $rs = mysql_query($sql);

        $entities = array();
        while ($obj = mysql_fetch_object($rs, get_class()))
        {
            $entities[] = $obj;
        }
        return $entities;
    }

    /**
     * @param int Id of the entity to get
     * @return Tracker
     */
    static public function getById($id)
    {
        $id = mysql_real_escape_string($id);

        $query = "SELECT * FROM tracker WHERE id = '%s' LIMIT 1";
        $sql = sprintf($query, $id);
        $rs = mysql_query($sql);

        $entity = mysql_fetch_object($rs, get_class());
        return $entity;
    }

    /**
     * Changes the shortcode for a generated link
     *
     * @return Boolean Indicates if it succeeded
     */
    public function changeShortcode()
    {
        $shortcode = substr(md5(time()), 0, 7);
        $id = mysql_real_escape_string($this->id);
        $query = "UPDATE tracker SET shortcode='%s' WHERE id = '%s'";
        $rs = mysql_query(sprintf($query, $shortcode, $id));
        return $rs;
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
