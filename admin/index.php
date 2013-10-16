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

include 'config.php';
include 'cloaking.class.php';

$cloaker = new Cloaker();

if (isset($_SESSION['logged_in']))
{
	if (!empty($_GET['action']))
	{
		switch($_GET['action'])
		{
			case 'create':
				if (empty($_POST))
				{
					header('Location: '.ADMIN_URL);
					exit;
				}
				else
				{
					foreach($_POST as $key => $value)
					{
						$values[$key] = mysql_real_escape_string($value);
					}
					if (!$cloaker->insertCampaign($values))
					{
						$viewData['errors'][] = 'Campaign could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
					}
				}
				break;
			case 'manage':
				if (empty($_GET['id']))
				{
					header('Location: '.ADMIN_URL);
					exit;
				}
				else
				{
					$campaignID = mysql_real_escape_string($_GET['id']);
					$viewData = $cloaker->getCampaignDetails($campaignID);
					if (!empty($_POST)) // the update form has been submitted
					{
						if (isset($_POST['change'])) // a "change shortcode" request has been sent
						{
							if (!$cloaker->changeShortcode($_POST['id']))
							{
								$viewData['errors'][] = 'Campaign URL could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
							}
							else
							{
								$viewData = $cloaker->getCampaignDetails($campaignID);
							}
						}
						else // a regular update request has been sent
						{
							foreach($_POST as $key => $value)
							{
								$values[$key] = mysql_real_escape_string($value);
							}
							if (!$cloaker->updateCampaign($values))
							{
								$viewData['errors'][] = 'Campaign could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
							}
							else
							{
								$viewData = $cloaker->getCampaignDetails($campaignID);
								$viewData['success'][] = 'Campaign was updated successfully!';
							}
						}
					}
					$viewData['destinations'] = $cloaker->getDestinations($campaignID);
					View('manage', $viewData);
					exit;
				}
			case 'destinations':
				if (empty($_GET['id']))
				{
					header('Location: '.ADMIN_URL);
					exit;
				}
				else
				{
					$campaignID = mysql_real_escape_string($_GET['id']);
					$viewData = $cloaker->getCampaignDetails($campaignID);
					if (!empty($_POST))
					{
						switch($_POST['action'])
						{
							case 'add':
								if (!$cloaker->addDestination($campaignID, mysql_real_escape_string($_POST['url']), mysql_real_escape_string($_POST['notes'])))
								{
									$viewData['errors'][] = 'Destination could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
								}
								break;
							case 'delete':
								if (!$cloaker->deleteDestination(mysql_real_escape_string($_POST['id'])))
								{
									$viewData['errors'][] = 'Destination could not be deleted, because the following MySQL Error occurred: <br> <br>'.mysql_error();
								}
								break;
							case 'edit':
								list($viewData['url'],$viewData['notes']) = $cloaker->getDestinationDetails(mysql_real_escape_string($_POST['id']));
								break;
							case 'update':
								if (!$cloaker->updateDestination(mysql_real_escape_string($_POST['id']), mysql_real_escape_string($_POST['url']), mysql_real_escape_string($_POST['notes'])))
								{
									$viewData['errors'][] = 'Destination could not be updated, because the following MySQL Error occurred: <br> <br>'.mysql_error();
								}
								else
								{
									$viewData['success'][] = 'Destination has been updated successfully';
								}
								break;
						}
					}
					$viewData['destinations'] = $cloaker->getDestinations($campaignID);
					View('destinations', $viewData);
					exit;
				}
			case 'statistics':
                require dirname(__FILE__).'/actions/statistics.php';
                break;
			case 'delete':
				if (!$cloaker->deleteCampaign(mysql_real_escape_string($_GET['id'])))
				{
					$viewData['errors'][] = 'Campaign could not be added, because the following MySQL Error occurred: <br> <br>'.mysql_error();
				}
				break;
            case 'giplist' :
                $giplist = $_POST['giplist'];
                if (!$cloaker->saveGiplist($giplist))
                {
                    $viewData['errors'][] = 'Global denied ip list could not be saved, because the following MySQL Error occurred: <br> <br>'.mysql_error();
                }
                
                break;
			case 'logout':
				unset($_SESSION['logged_in']);
				header('Location: '.ADMIN_URL);
				exit;
		}
	}
    // date range filter values
    $today = date('Y-m-d');
    $viewData['filters'] = array(
        'date_from' => (isset($_GET['date_from']) ? $_GET['date_from'] : $today),
        'date_to' => (isset($_GET['date_to']) ? $_GET['date_to'] : $today),
    );
	$viewData['campaigns'] = $cloaker->getCampaignDetails();
    $cloaker->updateNumPageViewsFor($viewData['campaigns'], $viewData['filters']);
    if($_SESSION['user_level'] == 'superadmin') {
        $viewData['giplist'] = $cloaker->getGipList();
        View('dashboard_superadmin', $viewData);
    } else {
        View('dashboard', $viewData);
    }
    
}
else
{
	if (isset($_POST['login']))
	{
		$loginQuery = mysql_query("SELECT id, user_level FROM users WHERE username = '".mysql_real_escape_string($_POST['username'])."' AND password = '".md5($_POST['password'])."'");
		if (mysql_num_rows($loginQuery) == 1)
		{
			$user = mysql_fetch_array($loginQuery);
			$_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
			$_SESSION['user_level'] = $user['user_level'];
			header('Location: '.ADMIN_URL);
			exit;
		}
	}
	View('login');
}

/**
 * View() - Loads a view
 *
 * @param String $name Name of the view file
 * @param Array  $data An optional array with view data.
 * @return void
 */
function View($name, $data = '')
{
	include dirname(__FILE__).'/views/'.$name.'.php';
}
?>
