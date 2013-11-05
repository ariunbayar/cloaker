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
require dirname(__FILE__).'/models.php';

// include controller functions
require dirname(__FILE__).'/controllers/campaign.php';
require dirname(__FILE__).'/controllers/dashboard.php';
require dirname(__FILE__).'/controllers/destination.php';
require dirname(__FILE__).'/controllers/global_ip.php';
require dirname(__FILE__).'/controllers/login.php';
require dirname(__FILE__).'/controllers/statistics.php';
require dirname(__FILE__).'/controllers/traffic_source.php';
require dirname(__FILE__).'/controllers/network.php';
require dirname(__FILE__).'/controllers/generate_links.php';
require dirname(__FILE__).'/controllers/migration.php';
require dirname(__FILE__).'/controllers/offer.php';

$cloaker = new Cloaker();

if (isset($_SESSION['logged_in']))
{
    $action = (isset($_GET['action']) ? $_GET['action'] : '');
    switch($action)
    {
        case 'add_campaign':
            add_campaign_controller();
            break;

        case 'save_traffic_source':
            save_traffic_source_controller();
            break;

        case 'delete_campaign':
            delete_campaign_controller();
            break;

        case 'delete_traffic_source':
            delete_traffic_source_controller();
            break;

        case 'delete_network':
            delete_network_controller();
            break;

        case 'manage':
            manage_campaign_controller();
            break;

        case 'edit_traffic_source':
            edit_traffic_source_controller();
            break;

        case 'destinations':
            manage_destination_controller();
            break;

        case 'traffic_source':
            traffic_source_controller();
            break;

        case 'network':
            network_controller();
            break;

        case 'save_network':
            save_network_controller();
            break;

        case 'edit_network':
            edit_network_controller();
            break;

        case 'statistics':
            statistics_controller();
            break;

        case 'generate_links':
            generate_links();
            break;

        case 'regenerate_url':
            regenerateUrl();
            break;

        case 'delete_tracker':
            deleteTracker();
            break;

        case 'giplist' :
            global_ip_controller();
            break;

        case 'migration_deploy' :
            migration_deploy_controller();
            break;

        case 'offer':
            offer_controller();
            break;

        case 'edit_offer':
            edit_offer_controller();
            break;

        case 'delete_offer':
            delete_offer_controller();
            break;

        case 'save_offer':
            save_offer_controller();
            break;

        case 'logout':
            logout_controller();
            break;

        default:
            dashboard_controller();
    }
}else if ($_GET['action'] == 'migration_deploy') {
    migration_deploy_controller();
}else{
    login_controller();
}

?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
