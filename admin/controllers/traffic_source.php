<?php

function traffic_source_controller($data = array())
{
    $campaign = get_entity_or_redirect('Campaign', $_GET['id']);
    $traffic_sources = TrafficSource::getByCampaignId($campaign->id);

    $data = array_merge($data, array(
        'id' => $campaign->id,  // fallback argument for campaign id
        'campaign' => $campaign,
        'current_page' => 'traffic_source',
        'traffic_sources' => $traffic_sources,
    ));
    View('traffic_source', $data);
    exit;
}


function edit_traffic_source_controller()
{
    $data = array(
        'traffic_source' => get_entity_or_redirect('TrafficSource', $_GET['ts_id']),
    );
    traffic_source_controller($data);
}


function save_traffic_source_controller()
{
    $campaign_id = $_GET['id'];
    $traffic_source = new TrafficSource;
    $traffic_source->id = $_POST['id'];
    $traffic_source->name = $_POST['name'];
    $traffic_source->campaign_id = $campaign_id;
    $traffic_source->save();

    header('Location: '.ADMIN_URL."/traffic_source/$campaign_id/");
    exit;
}


function delete_traffic_source_controller()
{
    if (!TrafficSource::deleteById($_GET['ts_id']))
    {
        Flash::set('Traffic source could not be deleted, because the '.
            'following MySQL Error occurred: <br> <br>'.mysql_error());
    };

    header('Location: '.ADMIN_URL."/traffic_source/{$_GET['id']}/");
    exit;
}
?>

<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
