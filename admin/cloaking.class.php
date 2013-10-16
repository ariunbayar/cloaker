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
	protected $ip;		 // IP Address the request is coming from
	protected $ref;		 // HTTP Referer associated with the request
	protected $hostname; // A hostname that the IP resolves to.
	protected $ipkey;	 // API key for the IPinfoDB.com Geolocation API
	protected $unqid;	 // ID of the current Session (as generated by PHP)
	protected $page;	 // The URI which was given in order to access the campaign
	protected $url;		 // Full URL to the IPinfoDB.com Geolocation API
	protected $country;  // Country that the request is coming from
	protected $region;	 // Region/State that the request is coming from
	protected $city;	 // City that the request is coming from
	
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
	 *
	 * @return Array
	 */
	function getCampaignDetails($id = 0)
	{
		if ($id == 0)
		{
			$query = mysql_query("SELECT id, name, ct_dt, md_dt, cloak_status FROM campaigns WHERE owner_id = '$_SESSION[user_id]' ORDER BY id ASC");
			$data = array();
			while ($row = mysql_fetch_assoc($query))
			{
				$data[] = $row;
			}
			return $data;
		}
		else
		{
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
	 * insertCampaign()
	 *
	 * Inserts a new campaign record into the database.
	 *
	 * @param Array $values An array containing the values that need to be inserted
	 * 
	 * @return Boolean TRUE upon success, FALSE upon failure
	 */
	function insertCampaign($values)
	{
		$sql = "INSERT INTO campaigns (name,owner_id,ct_dt,md_dt,shortcode,cloak_status,ref_status,googleurl,ad_status,deniedip_status,denyiprange_status,visit_count,visitcount_status,rdns,rdns_status,geolocation,geoloc_status,geoloc_mismatch_status,ua_strings,ua_status)
				VALUES ('$values[name]','$_SESSION[user_id]',NOW(),NOW(),MD5(NOW()),'on','$values[ref_status]','$values[googleurl]','$values[ad_status]','$values[deniedip_status]','$values[denyiprange_status]','$values[visit_count]','$values[visitcount_status]','$values[rdns]','$values[rdns_status]','$values[geolocation]','$values[geoloc_status]','$values[geoloc_mismatch_status]','$values[ua_strings]','$values[ua_status]')";
		$query = mysql_query($sql);
		$campaignID = mysql_insert_id();
		if (!empty($campaignID))
		{
			mysql_query("INSERT INTO destinations (campaign_ID, url) VALUES ('$campaignID','$values[cloaked_url]')");
			$destinationID = mysql_insert_id();
			mysql_query("UPDATE campaigns SET cloaked_url = '$destinationID' WHERE id = '$campaignID'");
			mysql_query("INSERT INTO destinations (campaign_ID, url) VALUES ('$campaignID','$values[cloaking_url]')");
			$destinationID = mysql_insert_id();
			mysql_query("UPDATE campaigns SET cloaking_url = '$destinationID' WHERE id = '$campaignID'");
			if (!empty($values['iplist']))
			{
				$iplist = explode(PHP_EOL,$values['iplist']);
				foreach($iplist as $ip)
				{
					mysql_query("INSERT INTO denied_ips (campaign_id,ip,ct) VALUES ('$campaignID','$ip',NOW())");
				}
			}
			if (!empty($values['iprange']))
			{
				$iprange = explode(PHP_EOL,$values['iprange']);
				foreach($iprange as $ip)
				{
					mysql_query("INSERT INTO denied_ip_ranges (campaign_id,iprange,ct) VALUES ('$campaignID','$ip',NOW())");
				}
			}
		}
		return $query;
	}
	
	/**
	 * updateCampaign()
	 *
	 * Updates a campaign record already present in the database.
	 *
	 * @param Array $values An array containing the values that need to be updated
	 * 
	 * @return Boolean TRUE upon success, FALSE upon failure
	 */
	function updateCampaign($values)
	{
		$sql = "UPDATE campaigns SET name = '$values[name]', md_dt = NOW(), cloak_status = '$values[cloak_status]', cloaking_url = '$values[cloaking_url]', cloaked_url = '$values[cloaked_url]', ref_status = '$values[ref_status]', googleurl = '$values[googleurl]', ad_status = '$values[ad_status]',
				deniedip_status = '$values[deniedip_status]', denyiprange_status = '$values[denyiprange_status]', visit_count = '$values[visit_count]', visitcount_status = '$values[visitcount_status]', rdns = '$values[rdns]', rdns_status = '$values[rdns_status]', geolocation = '$values[geolocation]', geoloc_status = '$values[geoloc_status]', geoloc_mismatch_status = '$values[geoloc_mismatch_status]', ua_strings = '$values[ua_strings]', ua_status = '$values[ua_status]'
				WHERE id = '$values[id]'";
		$query = mysql_query($sql);
		mysql_query("DELETE FROM denied_ips WHERE campaign_id = '$values[id]'");
		if (!empty($values['iplist']))
		{
			$iplist = explode(PHP_EOL,$values['iplist']);
			foreach($iplist as $ip)
			{
				mysql_query("INSERT INTO denied_ips (campaign_id,ip,ct) VALUES ('$values[id]','$ip',NOW())");
			}
		}
		mysql_query("DELETE FROM denied_ip_ranges WHERE campaign_id = '$values[id]'");
		if (!empty($values['iprange']))
		{
			$iprange = explode(PHP_EOL,$values['iprange']);
			foreach($iprange as $ip)
			{
				mysql_query("INSERT INTO denied_ip_ranges (campaign_id,iprange,ct) VALUES ('$values[id]','$ip',NOW())");
			}
		}
		return $query;
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
			mysql_query("DELETE FROM iptracker WHERE campaign_id = '$id'");
		}
		return $query;
	}
	
	/**
	 * getIdByShortcode($shortcode)
	 *
	 * Returns the campaign ID corresponding to the provided shortcode or Boolean FALSE if the shortcode doesn't exist.
	 *
	 * @return Mixed
	 */
	function getIdByShortcode($shortcode)
	{
		$query = mysql_query("SELECT id FROM campaigns WHERE shortcode = '$shortcode'");
		if (mysql_num_rows($query) == 0)
		{
			return false;
		}
		else
		{
			list($id) = mysql_fetch_row($query);
			return $id;
		}
	}
	
	/**
	 * changeShortcode()
	 *
	 * Changes the shortcode for a campaign
	 *
	 * @param int $id The id of the campaign to change the shortcode for
	 * @return Boolean
	 */
	function changeShortcode($id)
	{
		$shortcode = md5(time());
		$query = mysql_query("UPDATE campaigns SET shortcode = '$shortcode' WHERE id = '$id'");
		return $query;
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
	
	/**
	 * getStatistics()
	 *
	 * Returns Statistical details for a Campaign
	 *
	 * @param int $id    The ID of the Campaign
	 * @param int $page  Which page to start from?
	 * @param int $limit How many records to show per page?
	 *
	 * @return Array An array containing the statistics
	 */
	function getStatistics($id, $page = 1, $limit = 50)
	{
		$offset = $page * $limit - $limit;
		$query = mysql_query("SELECT * FROM iptracker WHERE campaign_id = '$id' ORDER BY id DESC LIMIT $offset,$limit");
		$data = array();
		while($row = mysql_fetch_assoc($query))
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
	 * @param int $id    The ID of the Campaign
	 * @param int $limit How many records are shown per page?
	 * @return int
	 */ 
	function countStatistics($id, $limit = 50)
	{
		$query = mysql_query("SELECT COUNT(id) FROM iptracker WHERE campaign_id = '$id'");
		list($rows) = mysql_fetch_row($query);
		return ceil($rows / $limit);
	}
	
	/**
	 * getVariables()
	 *
	 * Retrieves campaign information and initializes all the necessary Cloaker variables as long as a valid
	 * campaign ID has been provided.
	 *
	 * @param int $campaignID The ID of the Campaign to load information for.
	 *
	 * @return Mixed An Array with all the campaign details (as present in the DB) or Boolean FALSE if a campaign doesn't exist.
	 */
	function getVariables($campaignID)
	{
		$campaignQuery = mysql_query("SELECT * FROM campaigns WHERE id = '$campaignID'");
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
		$query = mysql_query("SELECT * FROM `iptracker` WHERE `campaign_id` = '".$campaignID."' AND `session_id` = '".$this->unqid."' AND `ip` = '".$this->ip."'");
		$count = mysql_num_rows($query);
		
		if ($count == 0) // if the current Session has not yet been captured by the Cloaker's IP Tracking Module -> insert it
		{		
			mysql_query("INSERT INTO `iptracker`(`campaign_id`,`ip`,`session_id`,`referral_url`,`host`,`country`,`region`,`city`,`page_views`,`cloak`,`access_time`,`ct_dt`)
						 VALUES('".$campaignID."','".$this->ip."','".$this->unqid."','".$this->ref."','".$this->hostname."','".$this->country."','".$this->region."','".$this->city."','1','no','0 minute(s)',now())");		
		}
		else // if the current Session has already been captured by the Cloaker's IP Tracking Module -> update info associated with it
		{
			$row = mysql_fetch_assoc($query);
			$pageViews = $row['page_views']+1;
			$createDate = $row['ct_dt'];
			$this->cloak = $row['cloak'];
			$to_time=strtotime($createDate);
			$accessTime = round(abs($to_time - $access_time) / 60,2)." minute(s)";
			mysql_query("UPDATE `iptracker` SET `country` = '".$this->country."',`region`='".$this->region."',`city`='".$this->city."',`access_time`='$accessTime',`page_views`='$pageViews' WHERE `campaign_id` = '".$campaignID."' AND `ip` = '".$this->ip."' AND `session_id` = '".$this->unqid."'");	
		}	
		$query = mysql_query("SELECT COUNT(id) FROM `iptracker` WHERE `ip` = '".$this->ip."'");
		list($ipCount) = mysql_fetch_row($query);
		$this->ipCount = $ipCount;
		$end = microtime(true);
		$duration = $end - $start;		
		$this->durValue['ipQry'] = $duration; // how much time did the Cloaker spend on IP Tracking?
		// IP Tracking: End
		
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
			$getParams = explode(',',$campaignDetails['googleurl']);
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
			$rdns = explode(',',$campaignDetails['rdns']);	
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
			$geoLocation = array_map('strtolower', explode(',',$campaignDetails['geolocation']));
			if ((in_array(trim(strtolower($this->country)), $geoLocation)) || (in_array(trim(strtolower($this->region)), $geoLocation)) || (in_array(trim(strtolower($this->city)), $geoLocation)))
			{
				$this->reasonForCloak('Geo Location Matched');
				return true;
			}
		}
		if ($campaignDetails['geoloc_mismatch_status'] == 'on') // Cloak based on Geolocation Mismatch is ON?
		{
			$geoLocation = array_map('strtolower', explode(',',$campaignDetails['geolocation']));
			if ((!in_array(trim(strtolower($this->country)), $geoLocation)) || (!in_array(trim(strtolower($this->region)), $geoLocation)) || (!in_array(trim(strtolower($this->city)), $geoLocation)))
			{
				$this->reasonForCloak('Geo Location Mismatch');
				return true;
			}
		}
		if ($campaignDetails['deniedip_status'] == 'on') // Cloak based on IP is ON?
		{
			$query = mysql_query("SELECT ip FROM denied_ips WHERE campaign_id = '$campaignDetails[id]'");
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
	function getDestinationUrl($id)
	{
		$query = mysql_query("SELECT url FROM destinations WHERE id='".$id."'");
		list($destination_url) = mysql_fetch_row($query);
		return $destination_url;
	}
}
?>
