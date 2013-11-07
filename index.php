<?php
/**
 * Cloaker Front Controller
 *
 * Handles all incoming requests, checks if a cloak is necessary and if it is -
 * cloaks the requested URL and displays a good-looking fake landing page instead.
 *
 * @author Marin Bezhanov
 * @since February 2013
 */
session_start();

include('admin/config.php');
include('admin/cloaking.class.php');
include('admin/models.php');


function _get_shortcode()
{
    if (isset($_GET['shortcode'])){  // regular ad click
        return array(false, $_GET['shortcode']);
    }else if (isset($_GET['sc'])){  // landing page js include
        return array(true, $_GET['sc']);
    }
    return array(false, null);
}

function _redirect_to_offer($cloaker, $campaign, $offer, $subid)
{
    if ($campaign->cloak_status == 'on') // If cloaking is enabled for the current Campaign...
    {
        if (!$cloaker->shouldCloak($campaign))
        {
            header('Location: '.$cloaker->getDestinationUrl($offer->cloaked_url, $subid));
            exit;
        }
        else // if cloaking is enabled and a reason for cloaking is detected -> display fake landing page
        {
            header('Location: '.$cloaker->getDestinationUrl($offer->cloaking_url, $subid));
            exit;
        }
    }
    else // if cloaking is disabled -> display fake landing page
    {
        header('Location: '.$cloaker->getDestinationUrl($offer->cloaking_url, $subid));
        exit;
    }
}


$cloaker = new Cloaker();
list($is_viewing_landing_page, $shortcode) = _get_shortcode();
if ($shortcode && !$cloaker->checkGiplist())
{
    $offer_id = null;
    if (strpos($shortcode, '-')){
        list($shortcode, $offer_id) = explode('-', $shortcode);
    }
    $tracker = Tracker::getByShortcode($shortcode);
    if ($tracker) // shortcode exists and successfully resolves a tracking setup
    {
        // track this hit/click
        list($subid, $campaign) = $cloaker->getVariables($tracker);

        if ($tracker->is_landing_page){
            $offer = Offer::getById($offer_id);
        }else{
            $offer = array_pop($tracker->getOffers());
        }

        if ($is_viewing_landing_page){
            // assuming this is the javascript page
            exit;
        }else{
            _redirect_to_offer($cloaker, $campaign, $offer, $subid);
        }
    }
}
?>
