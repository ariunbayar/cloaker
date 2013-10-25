<?php
/**
 * Main Administration Controller
 *
 * Controls the entire admin area of the Cloaker
 *
 * @author Marin Bezhanov
 * @since February 2013
 */
session_start();

require dirname(__FILE__).'/config.php';
require dirname(__FILE__).'/cloaking.class.php';
require dirname(__FILE__).'/helpers.php';

// include controller functions
require dirname(__FILE__).'/controllers/campaign.php';
require dirname(__FILE__).'/controllers/dashboard.php';
require dirname(__FILE__).'/controllers/destination.php';
require dirname(__FILE__).'/controllers/global_ip.php';
require dirname(__FILE__).'/controllers/login.php';
require dirname(__FILE__).'/controllers/statistics.php';
require dirname(__FILE__).'/controllers/traffic_source.php';

$cloaker = new Cloaker();

if (isset($_SESSION['logged_in']))
{
    switch($_GET['action'])
    {
        case 'add_campaign':
            add_campaign_controller();
            break;

        case 'delete_campaign':
            delete_campaign_controller();
            break;

        case 'manage':
            manage_campaign_controller();
            break;

        case 'destinations':
            manage_destination_controller();
            break;

        case 'traffic_source':
            traffic_source_controller();
            break;

        case 'deleteTrafficSource':
            delete_traffic_source_controller();
            break;

        case 'statistics':
            statistics_controller();
            break;

        case 'giplist' :
            global_ip_controller();
            break;

        case 'logout':
            logout_controller();

        default:
            dashboard_controller();
    }
} else {
    login_controller();
}

?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
