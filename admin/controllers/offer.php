<?php
function offer_controller($data = array())
{
    global $cloaker;

    $campaign = get_entity_or_redirect('Campaign', $_GET['id']);
    $offers = Offer::getByCampaignId($campaign->id);
    $network_options = to_select_options(Network::getByCampaignId($campaign->id));

    // Add/edit offer
    if (isset($_GET['offer_id']) && $_GET['offer_id']){
        $offer = get_entity_or_redirect('Offer', $_GET['offer_id']);
    }else{
        $offer = new Offer;
    }

    $viewData = array_merge($data, array(
        'id' => $_GET['id'],  // TODO is a fallback to old templating
        'campaign' => $campaign,
        'offers' => $offers,
        'network_options' => $network_options,
        'offer' => $offer,
        'current_page' => 'offer',
        'edit_url' => ADMIN_URL."edit_offer/{$campaign->id}/?offer_id=%s",
        'save_url' => ADMIN_URL."save_offer/{$campaign->id}/?offer_id=%s",
        'delete_url' => ADMIN_URL."delete_offer/{$campaign->id}/?offer_id=%s",
    ));
    View('offer', $viewData);
    exit;
}


function edit_offer_controller()
{
    $data = array('offer' => Offer::getById($_GET['offer_id']));
    offer_controller($data);
}


function save_offer_controller()
{
    $campaign_id = $_GET['id'];

    if (is_numeric($_POST['offer_id'])){
        $offer = get_entity_or_redirect('Offer', $_GET['offer_id']);
        $cloaked_url = $offer->getCloakedUrl();
        $cloaking_url = $offer->getCloakingUrl();
    }else{
        $offer = new Offer;
        $cloaked_url = new Destination;
        $cloaking_url = new Destination;
    }

    $cloaked_url->url = $_POST['cloaked_url'];
    $cloaked_url->save();

    $cloaking_url->url = $_POST['cloaking_url'];
    $cloaking_url->save();

    $offer->id = $_POST['offer_id'];
    $offer->network_id = $_POST['network_id'];
    $offer->name = $_POST['name'];
    $offer->cloaked_url = $cloaked_url->id;
    $offer->cloaking_url = $cloaking_url->id;
    $offer->payout = $_POST['payout'];
    $offer->campaign_id = $campaign_id;
    $offer->save();

    header('Location: '.ADMIN_URL."/offer/{$_GET['id']}/");
    exit;
}


function delete_offer_controller()
{
    $offer = get_entity_or_redirect('Offer', $_GET['offer_id']);

    $errors = array();
    if (!Destination::deleteById($offer->cloaked_url)) {
        $errors[] = 'Cloaked URL for the offer could not be deleted, because '. 
                    'following MySQL Error occurred: <br> <br>'.mysql_error();
    }
    if (!Destination::deleteById($offer->cloaking_url)) {
        $errors[] = 'Cloaking URL for the offer could not be deleted, because '. 
                    'following MySQL Error occurred: <br> <br>'.mysql_error();
    }
    if (!Offer::deleteById($offer->id)) {
        $errors[] = 'Offer could not be deleted, because the '. 
                    'following MySQL Error occurred: <br> <br>'.mysql_error();
    }

    if ($errors) {
        Flash::set(implode('<br/>', $errors));
    }

    header('Location: '.ADMIN_URL."/offer/{$_GET['id']}/");
    exit;
}
?>
