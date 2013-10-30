<?php
class TrafficSource
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


class AffiliateNetwork
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
        // TODO remove Cloaker->getAffiliateNetworks

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
