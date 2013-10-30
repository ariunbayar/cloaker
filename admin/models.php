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
