<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Manage Campaign: <?php echo $data['name']; ?></h2>
        <form action="<?php echo ADMIN_URL; ?>manage/<?php echo $data['id']; ?>/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <tr>
                        <td>Name</td>
                        <td><input size="38" name="name" type="text" value="<?php echo $data['name']; ?>"></td>
                    </tr>
                    <?php if ($data['cloak_status'] == 'on'): ?>
                    <tr><td>Cloaking</td><td><input name="cloak_status" value="on" type="radio" class="radio" checked> ON <input name="cloak_status" value="off" type="radio" class="radio"> OFF</td></tr>
                    <?php else: ?>
                    <tr><td>Cloaking</td><td><input name="cloak_status" value="on" type="radio" class="radio"> ON <input name="cloak_status" value="off" type="radio" class="radio" checked> OFF</td></tr>
                    <?php endif; ?>
                    <?php if ($data['ref_status'] == 'on'): ?>
                    <tr><td>Cloak based on non-empty referal url:</td><td><input name="ref_status" value="on" type="radio" class="radio" checked> On <input name="ref_status" value="off" type="radio" class="radio"> Off</td></tr>
                    <?php else: ?>
                    <tr><td>Cloak based on non-empty referal url:</td><td><input name="ref_status" value="on" type="radio" class="radio"> On <input name="ref_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                    <?php endif; ?>
                    <tr><td>GET Parameter Keys</td><td><input size="38" name="googleurl" value="<?php echo $data['googleurl'] ?>" type="text"></td></tr>
                    <?php if ($data['ad_status'] == 'on'): ?>
                    <tr><td>Cloak upon GET Parameter Key Match?</td><td><input name="ad_status" value="on" type="radio" class="radio" checked> On <input name="ad_status" value="off" type="radio" class="radio"> Off</td></tr>
                    <?php else: ?>
                    <tr><td>Cloak upon GET Parameter Key Match?</td><td><input name="ad_status" value="on" type="radio" class="radio"> On <input name="ad_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                    <?php endif; ?>
                    <tr><td>Denied IP List</td><td><textarea rows="10" cols="30" name="iplist"><?php echo implode(PHP_EOL,$data['iplist']); ?></textarea></td></tr>
                    <?php if ($data['deniedip_status'] == 'on'): ?>
                    <tr><td>Deny the above IP List</td><td><input name="deniedip_status" value="on" type="radio" class="radio" checked> On <input name="deniedip_status" value="off" type="radio" class="radio"> Off</td></tr>
                    <?php else: ?>
                    <tr><td>Deny the above IP List</td><td><input name="deniedip_status" value="on" type="radio" class="radio"> On <input name="deniedip_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                    <?php endif; ?>
                    <tr><td>Denied IP Range</td><td><textarea rows="10" cols="30" name="iprange"><?php echo implode(PHP_EOL,$data['iprange']); ?></textarea></td></tr>									
                    <?php if ($data['denyiprange_status'] == 'on'): ?>
                    <tr><td>Deny the above IP Range</td><td><input name="denyiprange_status" value="on" type="radio" class="radio" checked> On <input name="denyiprange_status" value="off" type="radio" class="radio"> Off</td></tr>
                    <?php else: ?>
                    <tr><td>Deny the above IP Range</td><td><input name="denyiprange_status" value="on" type="radio" class="radio"> On <input name="denyiprange_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                    <?php endif; ?>
                    <tr><td>Visits Count (in number of visits)</td><td><input name="visit_count" value="<?php echo $data['visit_count'] ?>" type="text"></td></tr>
                    <?php if ($data['visitcount_status'] == 'on'): ?>
                    <tr><td>Cloak Based on Visits Count?</td><td><input name="visitcount_status" value="on" type="radio" class="radio" checked> On <input name="visitcount_status" value="off" type="radio" class="radio"> Off</td></tr>
                    <?php else: ?>
                    <tr><td>Cloak Based on Visits Count?</td><td><input name="visitcount_status" value="on" type="radio" class="radio"> On <input name="visitcount_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                    <?php endif; ?>									
                    <tr><td>Reverse DNS </td><td><input size="38" name="rdns" value="<?php echo $data['rdns']; ?>" type="text"></td></tr>
                    <?php if ($data['rdns_status'] == 'on'): ?>
                    <tr><td>Cloak based on Reverse DNS?</td><td><input name="rdns_status" value="on" type="radio" class="radio" checked> On <input name="rdns_status" value="off" type="radio" class="radio"> Off</td></tr>
                    <?php else: ?>
                    <tr><td>Cloak based on Reverse DNS?</td><td><input name="rdns_status" value="on" type="radio" class="radio"> On <input name="rdns_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                    <?php endif; ?>									
                    <tr><td>Geo Location List (comma-separated list of town, state and/or country names)</td><td><input size="38" name="geolocation" value="<?php echo $data['geolocation']; ?>" type="text"></td></tr>
                    <?php if ($data['geoloc_status'] == 'on'): ?>
                    <tr><td>Cloak upon Geo Location Match?</td><td><input name="geoloc_status" value="on" type="radio" class="radio" checked> On <input name="geoloc_status" value="off" type="radio" class="radio"> Off </td></tr>
                    <?php else: ?>
                    <tr><td>Cloak upon Geo Location Match?</td><td><input name="geoloc_status" value="on" type="radio" class="radio"> On <input name="geoloc_status" value="off" type="radio" class="radio" checked> Off </td></tr>
                    <?php endif; ?>
                    <?php if ($data['geoloc_mismatch_status'] == 'on'): ?>
                    <tr><td>Cloak upon Geo Location Mismatch?</td><td><input name="geoloc_mismatch_status" value="on" type="radio" class="radio" checked> On <input name="geoloc_mismatch_status" value="off" type="radio" class="radio"> Off </td></tr>
                    <?php else: ?>
                    <tr><td>Cloak upon Geo Location Mismatch?</td><td><input name="geoloc_mismatch_status" value="on" type="radio" class="radio"> On <input name="geoloc_mismatch_status" value="off" type="radio" class="radio" checked> Off </td></tr>
                    <?php endif; ?>
                    <tr><td>User Agent strings (comma-separated)</td><td><input size="38" name="ua_strings" value="<?php echo $data['ua_strings']; ?>" type="text"></td></tr>
                    <?php if ($data['ua_status'] == 'on'): ?>
                    <tr><td>Cloak based on User Agent?</td><td><input name="ua_status" value="on" type="radio" class="radio" checked> On <input name="ua_status" value="off" type="radio" class="radio"> Off</td></tr>
                    <?php else: ?>
                    <tr><td>Cloak based on User Agent?</td><td><input name="ua_status" value="on" type="radio" class="radio"> On <input name="ua_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                    <?php endif; ?>	


                    <tr><td>&nbsp;</td><td><input value="Update" type="submit"></td></tr>
                </tbody>
            </table>
        </form>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
