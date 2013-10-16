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

$cloaker = new Cloaker();

if (!empty($_GET['shortcode']) && !$cloaker->checkGiplist())
{
    $shortcode = mysql_real_escape_string($_GET['shortcode']);
	$campaignID = $cloaker->getIdByShortcode($shortcode);
	if ($campaignID !== false) // shortcode exists and successfully resolves to a campaign ID
	{
		$campaignDetails = $cloaker->getVariables($campaignID);
		if ($campaignDetails['cloak_status'] == 'on') // If cloaking is enabled for the current Campaign...
		{
			if ((!$cloaker->shouldCloak($campaignDetails)) && ($campaignDetails['cloak_status']=="on")) // if cloaking is enabled, but no reason for cloaking is detected -> redirect to the cloaked URL
			{
				header('Location: '.$cloaker->getDestinationUrl($campaignDetails['cloaked_url']));
				exit;
			}
			else // if cloaking is enabled and a reason for cloaking is detected -> display fake landing page 
			{
				header('Location: '.$cloaker->getDestinationUrl($campaignDetails['cloaking_url']));
				exit;
			}
		}
		else // if cloaking is disabled -> display fake landing page
		{	
			header('Location: '.$cloaker->getDestinationUrl($campaignDetails['cloaking_url']));
			exit;
		}		
	}
	else
	{
		exit;
	}

}
else
{
	exit;
}
?>
