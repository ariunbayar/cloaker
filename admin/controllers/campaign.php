<?php
function add_campaign_controller()
{
    if ($_POST)
    {
        $values = $_POST;
        $errors = array();

        // Save the campaign
        $campaign = new Campaign;
        $campaign->name = $values['name'];
        $campaign->owner_id = $_SESSION['user_id'];
        $campaign->ct_dt = date('Y-m-d H:i:s');
        $campaign->md_dt = date('Y-m-d H:i:s');
        $campaign->cloak_status = 'on';
        $campaign->ref_status = $values['ref_status'];
        $campaign->googleurl = $values['googleurl'];
        $campaign->ad_status = $values['ad_status'];
        $campaign->deniedip_status = $values['deniedip_status'];
        $campaign->denyiprange_status = $values['denyiprange_status'];
        $campaign->visit_count = $values['visit_count'];
        $campaign->visitcount_status = $values['visitcount_status'];
        $campaign->rdns = $values['rdns'];
        $campaign->rdns_status = $values['rdns_status'];
        $campaign->geolocation = $values['geolocation'];
        $campaign->geoloc_status = $values['geoloc_status'];
        $campaign->geoloc_mismatch_status = $values['geoloc_mismatch_status'];
        $campaign->ua_strings = $values['ua_strings'];
        $campaign->ua_status = $values['ua_status'];
        if (!$campaign->save()) $errors[] = mysql_error();

        // Save campaign offer as "Default offer"
        $cloaked_dest = new Destination;
        $cloaked_dest->url = $values['cloaked_url'];
        if (!$cloaked_dest->save()) $errors[] = mysql_error();
        $cloaking_dest = new Destination;
        $cloaking_dest->url = $values['cloaking_url'];
        if (!$cloaking_dest->save()) $errors[] = mysql_error();
        $offer = new Offer;
        $offer->name = 'Default offer';
        $offer->cloaked_url = $cloaked_dest->id;
        $offer->cloaking_url = $cloaking_dest->id;
        $offer->payout = 0;
        $offer->campaign_id = $campaign->id;
        if (!$offer->save()) $errors[] = mysql_error();

        // Save denied ip addresses
        if ($values['iplist']) {
            $iplist = explode(PHP_EOL, $values['iplist']);
            foreach ($iplist as $ip) {
                $denied_ip = new DeniedIP;
                $denied_ip->campaign_id = $campaign->id;
                $denied_ip->ip = $ip;
                $denied_ip->ct = date('Y-m-d H:i:s');
                if (!$denied_ip->save()) $errors[] = mysql_error();
            }
        }

        // Save denied ip ranges
        if ($values['iprange']) {
            $ip_ranges = explode(PHP_EOL, $values['iprange']);
            foreach($ip_ranges as $ip_range) {
                $denied_ip_range = new DeniedIPRange;
                $denied_ip_range->campaign_id = $campaign->id;
                $denied_ip_range->iprange = $ip_range;
                $denied_ip_range->ct = date('Y-m-d H:i:s');
                if (!$denied_ip_range->save()) $errors[] = mysql_error();
            }
        }

        // Generate link
        $tracker = new Tracker();
        $tracker->campaign_id = $campaign->id;
        $tracker->shortcode = generateShortCode();
        $tracker->is_landing_page = 0;
        if (!$tracker->save()) $errors[] = mysql_error();
        $tracker_offer = new TrackerOffer;
        $tracker_offer->tracker_id = $tracker->id;
        $tracker_offer->offer_id = $offer->id;
        if (!$tracker_offer->save()) $errors[] = mysql_error();


        if ($errors){
            Flash::set('Error', 'Campaign could not be added, because the following '.
                       'MySQL Error occurred: <br> <br>'.
                       implode('<br/>', $errors));
        }

        header('Location: '.ADMIN_URL);
        exit;
    }

    $viewData['current_page'] = 'add_campaign';
    View('add_campaign', $viewData);
    exit;
}


function manage_campaign_controller()
{
    $campaign = get_entity_or_redirect('Campaign', $_GET['id']);
    if ($_POST)
    {
        $values = $_POST;
        $errors = array();

        // Save the campaign
        $campaign->name = $values['name'];
        $campaign->cloak_status = $values['cloak_status'];
        $campaign->ref_status = $values['ref_status'];
        $campaign->googleurl = $values['googleurl'];
        $campaign->ad_status = $values['ad_status'];
        $campaign->deniedip_status = $values['deniedip_status'];
        $campaign->denyiprange_status = $values['denyiprange_status'];
        $campaign->visit_count = $values['visit_count'];
        $campaign->visitcount_status = $values['visitcount_status'];
        $campaign->rdns = $values['rdns'];
        $campaign->rdns_status = $values['rdns_status'];
        $campaign->geolocation = $values['geolocation'];
        $campaign->geoloc_status = $values['geoloc_status'];
        $campaign->geoloc_mismatch_status = $values['geoloc_mismatch_status'];
        $campaign->ua_strings = $values['ua_strings'];
        $campaign->ua_status = $values['ua_status'];
        $campaign->md_dt = date('Y-m-d H:i:s');
        if (!$campaign->save()) $errors[] = mysql_error();

        // Save denied ip addresses
        if ($values['iplist']) {
            DeniedIP::deleteByCampaignId($campaign->id);
            $iplist = explode(PHP_EOL, $values['iplist']);
            foreach ($iplist as $ip) {
                if (strlen(trim($ip)) == 0) continue;
                $denied_ip = new DeniedIP;
                $denied_ip->campaign_id = $campaign->id;
                $denied_ip->ip = $ip;
                $denied_ip->ct = date('Y-m-d H:i:s');
                if (!$denied_ip->save()) $errors[] = mysql_error();
            }
        }

        // Save denied ip ranges
        if ($values['iprange']) {
            DeniedIPRange::deleteByCampaignId($campaign->id);
            $ip_ranges = explode(PHP_EOL, $values['iprange']);
            foreach($ip_ranges as $ip_range) {
                if (strlen(trim($ip_range)) == 0) continue;
                $denied_ip_range = new DeniedIPRange;
                $denied_ip_range->campaign_id = $campaign->id;
                $denied_ip_range->iprange = $ip_range;
                $denied_ip_range->ct = date('Y-m-d H:i:s');
                if (!$denied_ip_range->save()) $errors[] = mysql_error();
            }
        }

        if ($errors){
            Flash::set('Error', 'Campaign could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error());
        }else{
            Flash::set('Success', 'Campaign was updated successfully!'.
                       '<br/>TODO Change this to success message, not error');
        }

        header('Location: '.ADMIN_URL.'manage/'.$campaign->id.'/');
        exit;
    }

    $data = array(
        'id' => $_GET['id'],  // TODO is a fallback to old templating
        'campaign' => $campaign,
        'current_page' => 'manage',
    );
    View('manage', $data);
    exit;
}


function delete_campaign_controller()
{
    global $cloaker;

    if (!$cloaker->deleteCampaign(mysql_real_escape_string($_GET['id'])))
    {
        Flash::set('Error', 'Campaign could not be added, because the following '.
                   'MySQL Error occurred: <br> <br>'.mysql_error());
    }
    header('Location: '.ADMIN_URL);
    exit;
}
?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
