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
        throw new Exception('No such field "'.$name.'" for '.get_called_class());
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
                if ($field == 'created_at'){
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

        if (!$is_editing){
            $this->id = mysql_insert_id();
        }
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

        $entity = mysql_fetch_object($rs, $class);
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

    /**
     * Deletes an entity by ID
     * @return Boolean TRUE upon success, FALSE upon failure
     */
    static public function deleteById($id)
    {
        $class = get_called_class();
        $id = mysql_real_escape_string($id);
        $query = "DELETE FROM %s WHERE id = '%s'";
        $sql = sprintf($query, $class::$_table, $id);
        $result = mysql_query($sql);
        return $result;
    }

    static public function getAll()
    {
        $class = get_called_class();

        $query = "SELECT * FROM %s ORDER BY id ASC";
        $sql = sprintf($query, $class::$_table);
        return self::hydrate($sql);
    }
}


class Tracker extends Model
{
    static public $_table = 'tracker';
    protected $_fields = array(
        'id',
        'campaign_id',
        'traffic_source_id',
        'network_id',
        'shortcode',
        'is_landing_page',
        'landing_page_url',
        'created_at',
        'cpc',
    );

    /**
     * @param int Campaign id to look up
     * @return array Array of objects
     */
    static public function getByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);

        $query = "SELECT * FROM %s WHERE campaign_id='%s'
                  ORDER BY created_at DESC";
        $sql = sprintf($query, self::$_table, $campaign_id);
        return self::hydrate($sql);
    }

    static public function getLandingPageOptionsByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);

        $query = "SELECT * FROM %s WHERE campaign_id='%s' AND is_landing_page = 1
                  ORDER BY created_at DESC";
        $sql = sprintf($query, self::$_table, $campaign_id);
        $entities = self::hydrate($sql);
        return to_select_options($entities, 'id', 'landing_page_url');
    }

    /**
     * @param int Shortcode to look up
     * @return Tracker
     */
    static public function getByShortcode($shortcode)
    {
        $shortcode = mysql_real_escape_string($shortcode);

        $query = "SELECT * FROM %s WHERE shortcode='%s' LIMIT 1";
        $sql = sprintf($query, self::$_table, $shortcode);
        $objs = self::hydrate($sql);
        if ($objs){
            return $objs[0];
        }
        return null;
    }

    public function getNetwork()
    {
        return Network::getById($this->network_id);
    }

    public function getTrafficSource()
    {
        return TrafficSource::getById($this->traffic_source_id);
    }

    public function getOffers()
    {
        return TrackerOffer::getOffersByTrackerId($this->id);
    }

    public function getAdURL()
    {
        if ($this->is_landing_page){
            $url = $this->landing_page_url;
            $join_str = '?';
            if (strpos($url, '?')){
                $join_str = (substr($url, -1, 1) == '?' ? '' : '&');
            }
            $url .= $join_str.'sc='.$this->shortcode;
        }else{
            $url = substr(ADMIN_URL, 0, -6).$this->shortcode.'/';
        }
        return $url;
    }

    public function getCampaign()
    {
        return Campaign::getById($this->campaign_id);
    }
}


class Network extends Model
{
    static public $_table = 'network';
    protected $_fields = array(
        'id',
        'name',
        'campaign_id',
    );

    static public function getByCampaignId($user_id)
    {
        $user_id = mysql_real_escape_string($user_id);

        $query = "SELECT * FROM %s WHERE campaign_id = '%s' ORDER BY id ASC";
        $sql = sprintf($query, self::$_table, $user_id);
        return self::hydrate($sql);
    }
}


class TrafficSource extends Model
{
    static public $_table = 'traffic_source';
    protected $_fields = array(
        'id',
        'name',
        'campaign_id',
    );

    /**
     * @param int Campaign id to look up
     * @return array Array of objects
     */
    static public function getByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);
        $query = "SELECT * FROM %s WHERE campaign_id = '%s' ORDER BY id ASC";
        $sql = sprintf($query, self::$_table, $campaign_id);
        return self::hydrate($sql);
    }
}


class Migration extends Model
{
    static public $_table = 'migration';
    protected $_fields = array(
        'id',
        'file_name',
    );

    static public function getByFileName($file_name)
    {
        $file_name = mysql_real_escape_string($file_name);
        $query = "SELECT * FROM %s WHERE file_name = '%s' LIMIT 1";
        $sql = sprintf($query, self::$_table, $file_name);
        $rs = mysql_query($sql);

        $entity = mysql_fetch_object($rs);
        return $entity;
    }
}


class Campaign extends Model
{
    static public $_table = 'campaigns';
    protected $_fields = array(
        'id',
        'owner_id',
        'name',
        'ct_dt',
        'md_dt',
        'cloak_status',
        'ref_status',
        'googleurl',
        'ad_status',
        'deniedip_status',
        'denyiprange_status',
        'visit_count',
        'visitcount_status',
        'rdns',
        'rdns_status',
        'geolocation',
        'geoloc_status',
        'geoloc_mismatch_status',
        'ua_strings',
        'ua_status'
    );

    static public function getByUserId($user_id)
    {
        $user_id = mysql_real_escape_string($user_id);

        $query = "SELECT * FROM %s WHERE owner_id = '%s' ORDER BY id ASC";
        $sql = sprintf($query, self::$_table, $user_id);
        return self::hydrate($sql);
    }

    public function getDeniedIpDisplay()
    {
        $iplist = array();
        foreach (DeniedIP::getByCampaignId($this->id) as $denied_ip){
            $iplist[] = $denied_ip->ip;
        }
        return implode(PHP_EOL, $iplist);
    }

    public function getDeniedIpRangeDisplay()
    {
        $ip_ranges = array();
        foreach (DeniedIPRange::getByCampaignId($this->id) as $denied_ip_range){
            $ip_ranges[] = $denied_ip_range->iprange;
        }
        return implode(PHP_EOL, $ip_ranges);
    }
}


class Offer extends Model
{
    static public $_table = 'offer';
    protected $_fields = array(
        'id',
        'network_id',
        'name',
        'cloaked_url',
        'cloaking_url',
        'payout',
        'campaign_id',
    );

    /**
     * @param int Campaign id to look up
     * @return array Array of Offer instances
     */
    static public function getByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);
        $query = "SELECT * FROM %s WHERE campaign_id = '%s' ORDER BY id ASC";
        $sql = sprintf($query, self::$_table, $campaign_id);
        return self::hydrate($sql);
    }

    public function getNetwork()
    {
        return Network::getById($this->network_id);
    }

    public function getCloakedUrl()
    {
        return Destination::getById($this->cloaked_url);
    }

    public function getCloakingUrl()
    {
        return Destination::getById($this->cloaking_url);
    }
}


class Destination extends Model
{
    static public $_table = 'destinations';
    protected $_fields = array(
        'id',
        'url',
        'notes',
    );
}


class TrackerOffer extends Model
{
    static public $_table = 'tracker_offer';
    protected $_fields = array(
        'id',
        'tracker_id',
        'offer_id',
    );

    static public function getOffersByTrackerId($tracker_id)
    {
        $tracker_id = mysql_real_escape_string($tracker_id);
        $query = "
            SELECT t2.* FROM %s t1
            LEFT JOIN %s t2 ON t1.offer_id=t2.id
            WHERE t1.tracker_id = '%s' ORDER BY id ASC
        ";
        $sql = sprintf($query, self::$_table, Offer::$_table, $tracker_id);
        return Offer::hydrate($sql);
    }

    static public function deleteByTrackerId($tracker_id)
    {
        $tracker_id = mysql_real_escape_string($tracker_id);
        $query = "DELETE FROM %s WHERE tracker_id = '%s'";
        $sql = sprintf($query, self::$_table, $tracker_id);
        $result = mysql_query($sql);
        return $result;
    }
}


class DeniedIP extends Model
{
    static public $_table = 'denied_ips';
    protected $_fields = array(
        'id',
        'campaign_id',
        'ip',
        'ct',
    );

    static public function getByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);

        $query = "SELECT * FROM %s WHERE campaign_id='%s' ORDER BY id";
        $sql = sprintf($query, self::$_table, $campaign_id);
        return self::hydrate($sql);
    }

    static public function deleteByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);
        $query = "DELETE FROM %s WHERE campaign_id = '%s'";
        $sql = sprintf($query, self::$_table, $campaign_id);
        $result = mysql_query($sql);
        return $result;
    }
}

class DeniedIPRange extends Model
{
    static public $_table = 'denied_ip_ranges';
    protected $_fields = array(
        'id',
        'campaign_id',
        'iprange',
        'ct',
    );

    static public function getByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);

        $query = "SELECT * FROM %s WHERE campaign_id='%s' ORDER BY id";
        $sql = sprintf($query, self::$_table, $campaign_id);
        return self::hydrate($sql);
    }

    static public function deleteByCampaignId($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);
        $query = "DELETE FROM %s WHERE campaign_id = '%s'";
        $sql = sprintf($query, self::$_table, $campaign_id);
        $result = mysql_query($sql);
        return $result;
    }
}
?>
