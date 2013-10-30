<?php $main_title = 'Manage Statistics' ?>
<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Campaign Statistics: <?php echo $data['name']; ?></h2>
        <?php require dirname(__FILE__).'/_statistics_filter.php'; ?>
        <?php if ($data['total_pages']){ ?>
            <?php require dirname(__FILE__).'/_statistics_chart.php'; ?>
        <?php } ?>
        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
            <tbody>
            <tr class="hd">
                <td>IP</td>
                <td>Referer</td>
                <td>Host</td>
                <td>Country</td>
                <td>Region</td>
                <td>City</td>
                <td>Page Views</td>
                <?php if($_SESSION['user_level'] == 'superadmin') { ?>
                    <td>Cloak</td>
                    <td>Cloak Reason</td>
                <?php } ?>
                <td>Traffic source</td>
                <td>Access Time</td>
                <td>Access Date</td>
            </tr>
            <?php foreach($data['statistics'] as $stats): ?>
            <tr class="mhov">
                <td><?php echo $stats['ip']; ?></td>
                <td><?php echo $stats['referral_url']; ?></td>
                <td><?php echo $stats['host']; ?></td>
                <td><?php echo $stats['country']; ?></td>
                <td><?php echo $stats['region']; ?></td>
                <td><?php echo $stats['city']; ?></td>
                <td><?php echo $stats['page_views']; ?></td>
                <?php if($_SESSION['user_level'] == 'superadmin') { ?>
                    <td><?php echo $stats['cloak']; ?></td>
                    <td><?php echo $stats['reasonforcloak']; ?></td>
                <?php } ?>
                <td>
                    <?php if ($stats['traffic_source_id']){ ?>
                        <?php echo TrafficSource::getById($stats['traffic_source_id'])->name ?>
                    <?php }else{ ?>
                        [not specified]
                    <?php } ?>
                </td>
                <td><?php echo $stats['access_time']; ?></td>
                <td><?php echo $stats['ct_dt']; ?></td>
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
