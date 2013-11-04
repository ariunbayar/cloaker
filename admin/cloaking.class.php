<?php
/**
 * PHP Cloaker - An URL Cloaking Class
 *
 * A heavily modified version of an Indian URL Cloaking script. Some of the awful database,
 * function and variable naming conventions have unfortunately been retained, in order to
 * speed up the modification process (since time was critical)
 *
 * @author Marin Bezhanov
 * @since February 2013
 */
class Cloaker
{
    protected $ip;         // IP Address the request is coming from
    protected $ref;         // HTTP Referer associated with the request
    protected $hostname; // A hostname that the IP resolves to.
    protected $ipkey;     // API key for the IPinfoDB.com Geolocation API
    protected $unqid;     // ID of the current Session (as generated by PHP)
    protected $page;     // The URI which was given in order to access the campaign
    protected $url;         // Full URL to the IPinfoDB.com Geolocation API
    protected $country;  // Country that the request is coming from
    protected $region;     // Region/State that the request is coming from
    protected $city;     // City that the request is coming from
    
    protected $ipCount,$cloak; // These seem to be unused?
    
    /**
     * __construct()
     *
     * Cloaking class constructor. Initializes all the necessary variables for the class to function properly.
     *
     * @return void
     */
    function __construct()
    {
        // Initialize variables
        $this->ip = $_SERVER['REMOTE_ADDR'];    
        $this->ref = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
        $this->hostname = gethostbyaddr($this->ip);
        $this->ipkey = '334a8ea9e2e47a210049c24b4c6d734c3d6578f3b525d1e997bb1a7a52977a7e';
        $this->unqid = session_id();
        $this->page = $_SERVER['REQUEST_URI'];
        $this->url = "http://api.ipinfodb.com/v3/ip-city/?key=".$this->ipkey."&ip=".$this->ip."&format=raw";
        
        // Connect to database
        $connection = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
        mysql_select_db(DB_NAME, $connection);
    }
    
    /**
     * getCampaignDetails()
     *
     * Lists information about the campaigns currently present in the system. The function is used for
     * displaying all the details on the Dashboard/Campaigns screen and/or for listing campaign specific
     * details on the Manage screen.
     * TODO use Campaign::getById() instead
     *
     * @return Array
     */
    function getCampaignDetails($id = 0)
    {
        if ($id == 0)
        {
            // get all the campaigns
            $query = mysql_query("SELECT id, name, ct_dt, md_dt, cloak_status FROM campaigns WHERE owner_id = '{$_SESSION['user_id']}' ORDER BY id ASC");
            $data = array();
            while ($row = mysql_fetch_assoc($query))
            {
                $data[$row['id']] = $row;
                $data[$row['id']]['page_views'] = array(0, 0);
            }

            return $data;
        }
        else
        {
            $id = mysql_real_escape_string($id);
            $query = mysql_query("SELECT ip FROM denied_ips WHERE campaign_id = '$id'");
            $iplist = array();
            while(list($ip) = mysql_fetch_row($query))
            {
                $iplist[] = $ip;
            }
            $query = mysql_query("SELECT iprange FROM denied_ip_ranges WHERE campaign_id = '$id'");
            $iprange = array();
            while(list($ip) = mysql_fetch_row($query))
            {
                $iprange[] = $ip;
            }
            $query = mysql_query("SELECT * FROM campaigns WHERE id = '$id'");
            return array_merge(mysql_fetch_assoc($query), array('iplist' => $iplist), array('iprange' => $iprange));
        }
    }

    /**
     * updateNumPageViewsFor()
     *
     * Updates number of page views cloaked and non-cloaked for current filter.
     * This will update the passed campaigns details. See getCampaignDetails()
     *
     * @param array $campaigns Array of campaigns as reference
     * @param array $filters Array of filter values
     */
    function updateNumPageViewsFor(&$campaigns, $values)
    {
        $filters = array("c.owner_id = ".$_SESSION['user_id']);
        $this->_add_filter('date_from', "t.ct_dt >= '%s 00:00:00'", $filters, $values);
        $this->_add_filter('date_to', "t.ct_dt <= '%s 23:59:59'", $filters, $values);
        $filter_str = implode(' AND ', $filters);
        $sql = "
            SELECT
                t.campaign_id,
                SUM(IF(t.cloak='yes', t.page_views, 0)) as cloaked_page_views,
                SUM(IF(t.cloak='no', t.page_views, 0)) as non_cloaked_page_views
            FROM iptracker as t
            LEFT JOIN campaigns as c ON c.id = t.campaign_id
            WHERE $filter_str
            GROUP BY t.campaign_id
        ";
        $resultset = mysql_query($sql);
        while ($row = mysql_fetch_assoc($resultset))
        {
            $campaigns[$row['campaign_id']]['page_views'] = array(
                $row['cloaked_page_views'],
                $row['non_cloaked_page_views'],
            );
        }
    }

    /**
    * checkGiplist()
    *
    * @param
    * @return if IP is included in Global denied IPs, return true
    * not included, return false
    */
    function checkGiplist()
    {
        if (in_array($this->ip, $this->getGipList())) {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
    * getGipList()
    *
    * Returns all global denied IPs
    *
    * @param
    * @return Array
    */
    function getGipList()
    {
        $query = mysql_query('SELECT ip FROM denied_gips');
        $giplist = array();
        while( list($gip) = mysql_fetch_row($query)){
            $giplist[] = $gip;
        }
        return $giplist;
    }

    /**
    * saveGiplist($giplist)
    *
    * Save Global denied IPs
    *
    * @param mixed $giplist
    * @return Number of saved Global IPs
    */
    function saveGiplist($giplist)
    {
        mysql_query("TRUNCATE TABLE denied_gips");
        $i = 0;
        if (!empty($giplist))
        {
            $giparray = explode(PHP_EOL, $giplist);
            foreach($giparray as $gip)
            {
                if ($gip != null && $gip != '')
                {
                    mysql_query("INSERT INTO denied_gips (ip, ct) VALUES ('$gip', NOW())");
                    $i++;
                }
            }
        }
        return $i;
    }

    /**
     * getDestinations()
     *
     * Returns all destinations corresponding to a campaign ID
     *
     * @param int $id The ID of the campaign.
     * @return Array
     */
    function getDestinations($id)
    {
        $query = mysql_query("SELECT id, url, notes FROM destinations WHERE campaign_id = '$id'");
        $data = array();
        while($row = mysql_fetch_assoc($query))
        {
            $data[] = $row;
        }
        return $data;
    }
    
    /**
     * deleteCampaign()
     *
     * Deletes a campaign by ID
     *
     * @return Boolean TRUE upon success, FALSE upon failure
     */
    function deleteCampaign($id)
    {
        $query = mysql_query("DELETE FROM campaigns WHERE id = '$id' AND owner_id = '$_SESSION[user_id]'");
        if (mysql_affected_rows($query) == 1)
        {
            mysql_query("DELETE FROM denied_ips WHERE campaign_id = '$id'");
            mysql_query("DELETE FROM denied_ip_ranges WHERE campaign_id = '$id'");
            mysql_query("DELETE FROM destinations WHERE campaign_id = '$id'");
            $this->deleteTrackerRecordsFor($id);
        }
        return $query;
    }

    /**
     * deleteTrackerRecordsFor()
     *
     * Deletes all tracker records for a campaign.
     * So that data can start all over
     *
     * @param int Campaign id
     */
    function deleteTrackerRecordsFor($campaign_id)
    {
        $campaign_id = mysql_real_escape_string($campaign_id);
        mysql_query("DELETE FROM iptracker WHERE campaign_id = '$campaign_id'");
    }
    
    /**
     * deleteDestination()
     *
     * Deletes a destination by ID
     *
     * @param int $id The ID of the destination as specified in the "destinations" MySQL table
     * @return Boolean
     */
    function deleteDestination($id)
    {
        $query = mysql_query("DELETE FROM destinations WHERE id = '$id'");
        return $query;
    }
    
    /**
     * addDestination()
     *
     * Adds a new destination into the database
     *
     * @param int    $id    The ID of the Campaign this destination should be added to
     * @param String $url   The URL of the Destination
     * @param String $notes Any additional notes regarding this Destination
     * @return Boolean
     */
    function addDestination($id, $url, $notes)
    {
        $query = mysql_query("INSERT INTO destinations (campaign_id,url,notes) VALUES ('$id','$url','$notes')");
        return $query;
    }
    
    /**
     * getDestinationDetails()
     *
     * Returns the URL and Notes corresponding to a Destination ID
     *
     * @param int $id The ID of the Destination
     * @return Array An Array containing the URL and Notes
     */
    function getDestinationDetails($id)
    {
        $query = mysql_query("SELECT url,notes FROM destinations WHERE id = '$id'");
        return mysql_fetch_row($query);
    }
    
    /**
     * updateDestination()
     *
     * Updates a Destination by ID
     *
     * @param int    $id    The ID of the Destination
     * @param String $url   The URL of the Destination
     * @param String $notes Any additional notes regarding this Destination
     * @return Boolean
     */
    function updateDestination($id, $url, $notes)
    {
        $query = mysql_query("UPDATE destinations SET url = '$url', notes = '$notes' WHERE id = '$id'");
        return $query;
    }

    function _add_filter($field, $format, &$filter_array, $values)
    {
        if (isset($values[$field]) && $values[$field]){
            $filter_array[] = sprintf($format, mysql_real_escape_string($values[$field]));
        }
    }

    /**
     * buildFilters()
     *
     * Prepares part of the SQL used in WHERE clause according to the given
     * filter parameters for iptracker
     *
     * @param array $values Filter by these values
     * @param int $num_days_to_normalize_date_range
     * @return string Used in WHERE clause of SQL
     */
    function buildFilters($values, $num_days_to_normalize_date_range = null)
    {
        $filter_array = array();
        $this->_add_filter('id', "campaign_id = '%s'", $filter_array, $values);
        $this->_add_filter('ip', "ip LIKE '%%%s%%'", $filter_array, $values);
        $this->_add_filter('referer', "referral_url LIKE '%%%s%%'", $filter_array, $values);
        $this->_add_filter('host', "host LIKE '%%%s%%'", $filter_array, $values);
        $this->_add_filter('country', "country LIKE '%%%s%%'", $filter_array, $values);
        $this->_add_filter('region', "region LIKE '%%%s%%'", $filter_array, $values);
        $this->_add_filter('city', "city LIKE '%%%s%%'", $filter_array, $values);
        $this->_add_filter('cloak', "cloak = '%s'", $filter_array, $values);
        $this->_add_filter('cloak_reason', "reasonforcloak = '%s'", $filter_array, $values);
        $this->_add_filter('access_date_to', "ct_dt <= '%s 23:59:59'", $filter_full_date_covered, $values);

        if ($num_days_to_normalize_date_range === null){
            $this->_add_filter('access_date_from', "ct_dt >= '%s 00:00:00'", $filter_array, $values);
            return implode(' AND ', $filter_array);
        }else{
            // Normalize date range to limited days
            $filter_full_date_covered = $filter_array;
            $this->_add_filter('access_date_from', "ct_dt >= '%s 00:00:00'", $filter_full_date_covered, $values);
            $filter_str = implode(' AND ', $filter_full_date_covered);
            $max_date_sql = "SELECT MAX(ct_dt) FROM iptracker WHERE $filter_str";
            $min_date_sql = "SELECT MIN(ct_dt) FROM iptracker WHERE $filter_str";
            list($max_date) = mysql_fetch_row(mysql_query($max_date_sql));
            list($min_date) = mysql_fetch_row(mysql_query($min_date_sql));
            $one_day = 24 * 60 * 60;
            $num_days_covering = (strtotime($max_date) - strtotime($min_date)) / $one_day;
            $max_days_reached = $num_days_covering > $num_days_to_normalize_date_range;
            if ($max_days_reached) {
                $values['access_date_from'] = date('Y-m-d', strtotime($max_date) - 60 * $one_day);
                $this->_add_filter('access_date_from', "ct_dt >= '%s 00:00:00'", $filter_array, $values);
            }
            return array(implode(' AND ', $filter_array), $max_days_reached);
        }
    }

    /**
     * getStatistics()
     *
     * Returns Statistical details for a Campaign
     *
     * @param array $values Filter by these values
     * @param int $page     Which page to start from?
     * @param int $limit    How many records to show per page?
     *
     * @return Array An array containing the statistics
     */
    function getStatistics($values = array(), $page = 1, $limit = 50)
    {
        $filter_str = $this->buildFilters($values);
        $offset = $page * $limit - $limit;
        $query = "
            SELECT * FROM iptracker
            WHERE $filter_str
            ORDER BY id DESC
            LIMIT $offset, $limit
        ";
        $resultset = mysql_query($query);
        $data = array();
        while($row = mysql_fetch_assoc($resultset))
        {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * countStatistics()
     *
     * Counts how many statistical records are present for a given Campaign
     *
     * @param array $values Filter values to count by
     * @param int $limit How many records are shown per page?
     * @return int
     */
    function countStatistics($values, $limit = 50)
    {
        $filter_str = $this->buildFilters($values);
        $count_query = "SELECT COUNT(id) FROM iptracker WHERE $filter_str";
        list($num_records) = mysql_fetch_row(mysql_query($count_query));
        return ceil($num_records / $limit);
    }

    /**
     * getTotalPageViews()
     *
     * Get cloaked and non-cloaked page view count from iptracker for chart
     *
     * @param array $values Filter values to count by
     * @param int $max_days Limit the data to given number of days
     * @return array Cloaked and Non-cloaked page views per date
     */
    function getTotalPageViews($values, $max_days = 60)
    {
        list($filter_str, $max_days_reached) = $this->buildFilters($values, $max_days);
        $sql = "
            SELECT
                DATE(ct_dt) as access_date,
                SUM(IF(cloak='yes', page_views, 0)) as num_cloaked,
                SUM(IF(cloak='no', page_views, 0)) as num_non_cloaked
            FROM iptracker
            WHERE $filter_str
            GROUP BY access_date
            ORDER BY access_date ASC
        ";
        $one_day = 24 * 60 * 60;
        $resultset = mysql_query($sql);
        $last_date = 0;
        $data = array('cloaked' => array(0), 'non_cloaked' => array(0));
        while ($row = mysql_fetch_row($resultset))
        {
            list($access_date, $num_cloaked, $num_non_cloaked) = $row;
            if (!$last_date){
                list($year, $month, $day) = explode('-', date('Y-m-d', strtotime($access_date) - $one_day));
                $data['start_date'] = array((int)$year, (int)$month, (int)$day);
            }

            while ($last_date && $access_date > date('Y-m-d', $last_date + $one_day)){
                $last_date += $one_day;
                $data['cloaked'][] = 0;
                $data['non_cloaked'][] = 0;
            }

            $last_date = strtotime($access_date);
            $data['cloaked'][] = (int)$num_cloaked;
            $data['non_cloaked'][] = (int)$num_non_cloaked;
        }

        return array($data, $max_days_reached);
    }

    /**
     * getPageViewsByGeolocation()
     *
     * @param string $geolocation_str Geolocation string for the campaign
     * @param array $values Filter values to count by
     * @param int $max_days Limit the data to given number of days
     * @return array Page views by geolocation
     */
    function getPageViewsByGeolocation($geolocation_str, $values, $max_days = 60)
    {
        $data = array();
        $geolocations = explode(',', $geolocation_str);
        $geolocation_select_sql = '';
        foreach ($geolocations as $i => $geolocation) {
            // TODO exclusion sql
            $data[$geolocation] = array(0);
            $val = mysql_real_escape_string($geolocation);
            $geolocation_select_sql .= ($i ? ',' : '');
            $sql_like = "country LIKE '%$val%' OR region LIKE '%$val%' OR city LIKE '%$val%'";
            $geolocation_select_sql .= "SUM(IF($sql_like, page_views, 0))";
        }
        if (count($data) == 0){  // No geolocation is specified
            return array($data, false);
        }

        list($filter_str, $max_days_reached) = $this->buildFilters($values, $max_days);
        $sql = "
            SELECT
                $geolocation_select_sql,
                DATE(ct_dt) as access_date
            FROM iptracker
            WHERE $filter_str
            GROUP BY access_date
            ORDER BY access_date ASC
        ";
        $one_day = 24 * 60 * 60;
        $resultset = mysql_query($sql);
        $last_date = 0;
        while ($row = mysql_fetch_row($resultset))
        {
            $access_date = array_pop($row);
            if (!$last_date){
                list($year, $month, $day) = explode('-', date('Y-m-d', strtotime($access_date) - $one_day));
                $data['start_date'] = array((int)$year, (int)$month, (int)$day);
            }

            while ($last_date && $access_date > date('Y-m-d', $last_date + $one_day)){
                $last_date += $one_day;
                foreach ($geolocations as $i => $geolocation) {
                    $data[$geolocation][] = 0;
                }
            }

            $last_date = strtotime($access_date);
            foreach ($geolocations as $i => $geolocation) {
                $data[$geolocation][] = (int)$row[$i];
            }
        }

        return array($data, $max_days_reached);
    }

    
    /**
     * getVariables()
     *
     * Retrieves campaign information and initializes all the necessary Cloaker variables as long as a valid
     * campaign ID has been provided.
     *
     * @param Tracker $tracker Tracker instance indicating the setup
     *
     * @return Mixed An Array with all the campaign details (as present in the DB) or Boolean FALSE if a campaign doesn't exist.
     */
    function getVariables($tracker)
    {
        $campaignQuery = mysql_query("SELECT * FROM campaigns WHERE id='{$tracker->campaign_id}'");
        if (mysql_num_rows($campaignQuery) == 0)
        {
            return false;
        }
        $campaignDetails = mysql_fetch_assoc($campaignQuery);
        
        // Geolocate IP: Start
        
        $start = microtime(true);
        $context = stream_context_create(array( // a stream_context we would use along with the file_get_contents() operation
            'http' => array(
            'timeout' => 2 // file_get_contents() timeout in seconds
            )
        ));
        $ipApiUrl = "http://www.ipaddressapi.com/l/4e6c100ddeef1338ae6f91af2f61413aa2ffad38de58?h=".$this->ip; // URL to the Geolocation API provided by ipaddressapi.com
        $apiResponse = @file_get_contents($ipApiUrl, 0, $context); // store the API response into a variable
        
        // A typical ipaddressapi.com API response contains:
        //
        // - Host Name or IP Address queried,
        // - Resolved IP Address (if a Host Name was queried),
        // - Country Code (ISO-3166-1 alpha-2)
        // - Country Name
        // - Region Code (ISO-3166-2 for US and CA, FIPS 10-4 for other countries)
        // - Region Name
        // - City
        // - Postcode
        // - Latitude
        // - Longitude
        // - ISP
        // - Organization
        //
        // e.g. "192.0.43.10","192.0.43.10","US","United States","CA","California","Marina Del Rey","","33.980300","-118.451700","ICANN","ICANN"
        
        if (!empty($apiResponse)) // if a response was successfully received from the ipaddressapi.com API...
        {
            $responseDetails = explode(',',str_replace('"','',$apiResponse));
            $this->city = $responseDetails[6];
            $this->region = $responseDetails[5];
            $this->country = $responseDetails[3];
            $api_domain = "ipaddressapi.com";
        }
        else // no response was received from the ipaddressapi.com API (either a timeout occurred or the IP could not be resolved)
        {
            // what we basically do here is send a request to an alternative API, provided by IPinfoDB.com
            $apiResponse = @file_get_contents($this->url, 0, $context);
            
            // An IPinfoDB.com API response contains the following information:
            //
            // - Status Code
            // - Status Message
            // - IP Address
            // - Country Code
            // - Country Name
            // - Region Name
            // - City Name
            // - ZIP Code
            // - Latitude
            // - Longitude
            // - Time Zone
            //
            // e.g. OK;;192.0.43.10;US;UNITED STATES;CALIFORNIA;LOS ANGELES;90094;34.0522;-118.244;-08:00
            
            if (!empty($apiResponse)) // response was successfully received from the IPinfoDB.com API...
            {
                $responseDetails = explode(';',$apiResponse);
                $this->city = $responseDetails[6];
                $this->region = $responseDetails[5];
                $this->country = $responseDetails[4];
                $api_domain = "api.ipinfodb.com";
            }
        }
        $end = microtime(true);
        $duration = $end - $start;
        $this->durValue['ipAddreddApi'] = $duration; // how much time it took the Cloaker to geolocate the IP?
        // Geolocate IP: Finished    
        
        // IP Tracking: Start
        $start = microtime(true);
        $access_time = time();            
        $sql = "SELECT * FROM `iptracker` WHERE `campaign_id` =
            '{$tracker->campaign_id}' AND `session_id` = '".$this->unqid."'
            AND `ip` = '".$this->ip."'";
        $query = mysql_query($sql);
        $count = mysql_num_rows($query);
        
        if ($count == 0) // if the current Session has not yet been captured by the Cloaker's IP Tracking Module -> insert it
        {
            mysql_query("INSERT INTO `iptracker`(`campaign_id`,`ip`,`session_id`,`referral_url`,`host`,`country`,`region`,`city`,`page_views`,`cloak`,`access_time`,`ct_dt`, `traffic_source_id`)
                         VALUES('{$tracker->campaign_id}','".$this->ip."','".$this->unqid."','".$this->ref."','".$this->hostname."','".$this->country."','".$this->region."','".$this->city."','1','no','0 minute(s)',now(), {$tracker->traffic_source_id})");
        }
        else // if the current Session has already been captured by the Cloaker's IP Tracking Module -> update info associated with it
        {
            $row = mysql_fetch_assoc($query);
            $pageViews = $row['page_views']+1;
            $createDate = $row['ct_dt'];
            $this->cloak = $row['cloak'];
            $to_time=strtotime($createDate);
            $accessTime = round(abs($to_time - $access_time) / 60,2)." minute(s)";
            mysql_query("UPDATE `iptracker` SET `country` =
                '".$this->country."',`region`='".$this->region."',`city`='".$this->city."',`access_time`='$accessTime',`page_views`='$pageViews' WHERE `campaign_id` = '{$tracker->campaign_id}' AND `ip` = '".$this->ip."' AND `session_id` = '".$this->unqid."'");    
        }
        $query = mysql_query("SELECT COUNT(id) FROM `iptracker` WHERE `ip` = '".$this->ip."'");
        list($ipCount) = mysql_fetch_row($query);
        $this->ipCount = $ipCount;
        $end = microtime(true);
        $duration = $end - $start;        
        $this->durValue['ipQry'] = $duration; // how much time did the Cloaker spend on IP Tracking?
        // IP Tracking: End

        // return ip tracking id as a subid
        $row = mysql_fetch_assoc(mysql_query($sql));
        $campaignDetails['subid'] = $row['id'];
        
        return $campaignDetails;
    }
    
    /**
     * shouldCloak()
     *
     * Determines whether a user should see a fake landing page or the cloaked URL, based on a number of cloaking rule checks,
     * including:
     *
     * - Referral Check
     * - Reverse DNS Check
     * - Geolocation Check
     * - Denied IP Check
     * - Denied IP Range Check
     * - Recurring Visits Count Check
     * - Browser History Check
     *
     * @param Array $campaignDetails An array containing all the information stored about a campaign in the "campaigns" MySQL table.
     * @return Boolean
     */
    function shouldCloak($campaignDetails)
    {
        if ($campaignDetails['ref_status'] == 'on') // Cloak based on non-empty referral url is ON?
        {
            if (!empty($this->ref)) // Referreal URL is not empty?
            {
                $this->reasonForCloak('Referral URL Not Empty');
                return true;
            }
        }
        if ($campaignDetails['ad_status'] == 'on') // Cloak based on certain GET variables present in the HTTP Request?
        {
            $getParams = explode(',', $campaignDetails['googleurl']);
            foreach($getParams as $key)
            {
                if (array_key_exists($key, $_GET))
                {
                    $this->reasonForCloak('GET Parameter Match');
                    return true;
                }
            }
        }
        if ($campaignDetails['rdns_status'] == 'on') // Cloak based on Reverse DNS is ON?
        {
            $hostname = $this->hostname;
            $rdns = explode(',', $campaignDetails['rdns']);
            for($i=0;$i<count($rdns);$i++)
            {
                if (strpos(trim($hostname),$rdns[$i]) !== false)
                {
                    $this->match = 1;
                    $this->reasonForCloak('Reverse DNS Matched');
                    return true;
                }
            }
        }
        if ($campaignDetails['geoloc_status'] == 'on') // Cloak based on Geolocation Match is ON?
        {
            $geoLocation = array_map('strtolower', explode(',', $campaignDetails['geolocation']));
            if ((in_array(trim(strtolower($this->country)), $geoLocation)) || (in_array(trim(strtolower($this->region)), $geoLocation)) || (in_array(trim(strtolower($this->city)), $geoLocation)))
            {
                $this->reasonForCloak('Geo Location Matched');
                return true;
            }
        }
        if ($campaignDetails['geoloc_mismatch_status'] == 'on') // Cloak based on Geolocation Mismatch is ON?
        {
            $geoLocation = array_map('strtolower', explode(',', $campaignDetails['geolocation']));
            if ((!in_array(trim(strtolower($this->country)), $geoLocation)) || (!in_array(trim(strtolower($this->region)), $geoLocation)) || (!in_array(trim(strtolower($this->city)), $geoLocation)))
            {
                $this->reasonForCloak('Geo Location Mismatch');
                return true;
            }
        }
        if ($campaignDetails['deniedip_status'] == 'on') // Cloak based on IP is ON?
        {
            $query = mysql_query("SELECT ip FROM denied_ips WHERE campaign_id = '{$campaignDetails['id']}'");
            while (list($ip) = mysql_fetch_row($query))
            {
                if ($this->ip == $ip)
                {
                    $this->reasonForCloak('Denied IP Matched');
                    return true;
                }
            }
        }
        if ($campaignDetails['denyiprange_status'] == 'on') // Cloak based on IP Range is ON?
        {
            $query = mysql_query("SELECT iprange FROM denied_ip_ranges WHERE campaign_id = '$campaignDetails[id]'");
            list($a,$b,$c,$d) = explode(".",$this->ip); // split IP into its A-, B-, C-, and D-blocks
            while (list($ip) = mysql_fetch_row($query))
            {
                $ipBlocks = explode('.',$ip);
                if (($a == $ipBlocks[0]) && (empty($ipBlocks[1])))
                {
                    $this->reasonForCloak('Denied IP Range Matched');
                    return true;                    
                }
                else if (($a == $ipBlocks[0]) && ($b == $ipBlocks[1]) && (empty($ipBlocks[2])))
                {
                    $this->reasonForCloak('Denied IP Range Matched');
                    return true;                    
                }
                else if (($a == $ipBlocks[0]) && ($b == $ipBlocks[1]) && ($c == $ipBlocks[2]) && (empty($ipBlocks[3])))
                {
                    $this->reasonForCloak('Denied IP Range Matched');
                    return true;                    
                }
                else if ($this->ip == $ip)
                {
                    $this->reasonForCloak('Denied IP Matched');
                    return true;
                }
            }
        }
        if ($campaignDetails['visitcount_status'] == 'on') // Cloak based on number of recurring visits?
        {
            $query = mysql_query("SELECT page_views FROM `iptracker` WHERE `ip` = '".$this->ip."' AND `session_id` = '".$this->unqid."'");
            list($visits) = mysql_fetch_row($query);
            if ($visits > $campaignDetails['visit_count'])
            {
                $this->reasonForCloak('Visit Threshold Exceeded');
                return true;
            }
        }
        if ($campaignDetails['ua_status'] == 'on') // Cloak based on User Agent strings?
        {
            $uaStrings = explode(',',$campaignDetails['ua_strings']);
            foreach($uaStrings as $uaString)
            {
                if (strpos($_SERVER['HTTP_USER_AGENT'],$uaString) !== false)
                {
                    $this->reasonForCloak('Unallowed User Agent');
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * reasonForCloak()
     *
     * Updates the "iptracker" table with the reason why an incoming request has been cloaked.
     *
     * @param String $reasonMessage A message indicating the exact reason for cloaking.
     * @return void
     */
    private function reasonForCloak($reasonMessage)
    {
        mysql_query("UPDATE `iptracker` SET `cloak` = 'yes', `reasonforcloak` = '".$reasonMessage."' WHERE `ip` = '".$this->ip."' AND `session_id` = '".$this->unqid."'");    
    }

    /**
     * getDestinationUrl()
     *
     * Returns the Destination URL (aka Cloaked URL) corresponding to a given Campaign
     *
     * @param int $id The URL ID as recorded in the "destinations" table
     * @return String
     */
    function getDestinationUrl($id, $subid)
    {
        $query = mysql_query("SELECT url FROM destinations WHERE id='".$id."'");
        list($destination_url) = mysql_fetch_row($query);
        $destination_url = str_replace('[[subid]]', $subid, $destination_url);
        return $destination_url;
    }
}
?>
