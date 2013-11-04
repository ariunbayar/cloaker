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

$cloaker = new Cloaker();
if (!empty($_GET['shortcode']) && !$cloaker->checkGiplist())
{
    $shortcode = $_GET['shortcode'];
    $offer_id = null;
    if (strpos($shortcode, '-')){
        list($shortcode, $offer_id) = explode('-', $shortcode);
    }
    $tracker = Tracker::getByShortcode($shortcode);
    if ($tracker) // shortcode exists and successfully resolves a tracking setup
    {
        if ($tracker->is_landing_page){
            $offer = Offer::getById($offer_id);
        }else{
            $offer = array_pop($tracker->getOffers());
        }
        $campaignDetails = $cloaker->getVariables($tracker);
        $subid = $campaignDetails['subid'];
        if ($campaignDetails['cloak_status'] == 'on') // If cloaking is enabled for the current Campaign...
        {
            if (!$cloaker->shouldCloak($campaignDetails))  // TODO check
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
}
?>
