<?php $main_title = 'Manage Statistics' ?>
<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Campaign Statistics: <?php echo $data['name']; ?></h2>
        <?php if ($data['total_pages']){ ?>
            <?php require dirname(__FILE__).'/_statistics_chart.php'; ?>
        <?php } ?>
        <?php require dirname(__FILE__).'/_statistics_filter.php'; ?>
        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
            <tbody>
            <tr class="hd">
                <td>Sub ID</td>
                <td></td>
                <td>Geolocation & IP</td>
                <td>Referer</td>
                <td>Affiliate Network</td>
                <td>Offer / Landing Page</td>
                <td># Views</td>
                <?php if($_SESSION['user_level'] == 'superadmin') { ?>
                    <td>Cloak Reason</td>
                <?php } ?>
                <td>Traffic source</td>
                <td>Access Date</td>
            </tr>
            <?php foreach($data['statistics'] as $stats): ?>
            <tr class="mhov">
                <td><?php echo $stats['id']; ?></td>
                <td>
                    <?php if ($stats['is_converted']){ ?>
                        <i class="fa fa-dollar fa-lg dollar-icon"></i>
                    <?php } ?>
                </td>
                <td>
                    <?php echo Geolocation_show($stats['country'],$stats['region'],$stats['city']); ?>
                    <abbr title="IP: <?php echo $stats['ip']; ?>">
                        <?php echo $stats['host']; ?>
                    </abbr>
                </td>
                <td><?php echo $stats['referral_url']; ?></td>
                <td><?php 
                    if ($stats['network_id']){ 
                        echo Network::getById($stats['network_id'])->name;
                    }
                    ?>
                </td>
                <td>
                    <?php if ($stats['tracker_id_for_lp']) { ?>
                        <?php echo Tracker::getById($stats['tracker_id_for_lp'])->landing_page_url; ?>
                    <?php } else { ?>
                        <?php if ($stats['offer_id']) { ?>
                        <abbr title="Offer URL: <?php if ($stats['cloak'] == "yes") {
                                echo Destination::getById(Offer::getById($stats['offer_id'])->cloaked_url)->url;
                            } else {
                                echo Destination::getById(Offer::getById($stats['offer_id'])->cloaking_url)->url;
                            }?>">
                            <?php echo Offer::getById($stats['offer_id'])->name; ?>
                        <?php } ?>
                        </abbr>
                    <?php } ?>
                </td>
                <td><?php echo $stats['page_views']; ?></td>
                <?php if($_SESSION['user_level'] == 'superadmin') { ?>
                    <td><?php echo $stats['reasonforcloak']; ?></td>
                <?php } ?>
                <td>
                    <?php if ($stats['traffic_source_id']){ ?>
                        <?php echo TrafficSource::getById($stats['traffic_source_id'])->name ?>
                    <?php }else{ ?>
                        -
                    <?php } ?>
                </td>
                <td>
                    <abbr title="Access time: <?php echo $stats['access_time']; ?>">
                        <?php echo date('Y-m-d H:i', strtotime($stats['ct_dt'])); ?>
                    </abbr>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>
Pages:
<?php if ($data['total_pages'] <= 5): ?>
<?php for($i = 1;$i <= $data['total_pages'];$i++): ?>
<?php if ($i == $data['page']): ?>
<a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
<?php else: ?>
<a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
<?php endif; ?>
<?php endfor; ?>
<?php elseif ($data['total_pages'] > 5): ?>
<?php if ($data['page'] >= 3 && $data['page'] <= $data['total_pages'] - 2): ?>
<a href="<?php echo sprintf($data['url_format'], 1) ?>">First</a>
<a href="<?php echo sprintf($data['url_format'], $data['page'] - 1) ?>">Previous</a>
<?php for($i = $data['page'] - 2;$i <=  $data['page'] + 2;$i++): ?>
<?php if ($i == $data['page']): ?>
<a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
<?php else: ?>
<a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
<?php endif; ?>
<?php endfor; ?>
<a href="<?php echo sprintf($data['url_format'], $data['page'] + 1) ?>">Next</a>
<a href="<?php echo sprintf($data['url_format'], $data['total_pages']) ?>">Last</a>
<?php elseif ($data['page'] < 3): ?>
<?php for($i = 1;$i <= 5;$i++): ?>
<?php if ($i == $data['page']): ?>
<a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
<?php else: ?>
<a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
<?php endif; ?>
<?php endfor; ?>
<a href="<?php echo sprintf($data['url_format'], $data['page'] + 1) ?>">Next</a>
<a href="<?php echo sprintf($data['url_format'], $data['total_pages']) ?>">Last</a>
<?php elseif ($data['page'] > $data['total_pages'] - 2): ?>
<a href="<?php echo sprintf($data['url_format'], 1) ?>">First</a>
<a href="<?php echo sprintf($data['url_format'], $data['page'] - 1) ?>">Previous</a>
<?php for($i = $data['total_pages'] - 5;$i <= $data['total_pages'];$i++): ?>
<?php if ($i == $data['page']): ?>
<a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
<?php else: ?>
<a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
<?php endif; ?>
<?php endfor; ?>								
<?php endif; ?>
<?php endif; ?>
<div style="float:right;">
    <a href="<?php echo ADMIN_URL.'statistics/'.$_GET['id'].'/?cleanup=yes' ?>"
       onclick="return confirm('You are about to wipe all records for current campaign.\nAre you sure?');">
        Click here to wipe all tracked records
    </a>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
