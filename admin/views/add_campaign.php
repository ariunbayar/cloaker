<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">New Campaign</h2>
        <form action="<?php echo ADMIN_URL; ?>add_campaign/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr><td>Name</td><td><input size="38" name="name" type="text"></td></tr>

                <tr>
                    <td>Affiliate Network</td>
                    <td>
                        <?php echo select_tag('network_id', $data['network_options'], '', '[optional]') ?>
                    </td>
                </tr>


                <tr><td>Cloaked URL (aka Bad URL)</td><td><input size="38" name="cloaked_url" type="text"></td></tr>
                <tr><td>Cloaking URL (aka Good URL)</td><td><input size="38" name="cloaking_url" type="text"></td></tr>
                <tr>
                    <td>Cloak based on non empty referal url:</td><td><input name="ref_status" value="on" checked="" type="radio" class="radio"> On
                    <input name="ref_status" value="off" type="radio" class="radio"> Off
                </td>
                </tr>
                <tr>
                    <td>GET Parameter Keys</td><td><input size="38" name="googleurl" value="utm_source,utm_medium" type="text"></td>
                </tr><tr>
                    <td>Cloak upon GET Parameter Key Match?</td><td><input name="ad_status" value="on" checked="" type="radio" class="radio"> On
                    <input name="ad_status" value="off" type="radio" class="radio"> Off
                </td>
                </tr>
                <?php if($_SESSION['user_level'] == 'superadmin') { ?>
                <tr>
                    <td>Denied IP List Edit(superadmin)</td><td>
                        <textarea rows="20" cols="30" name="iplist_admin"></textarea></td>
                </tr>
                <?php } ?>
                <tr>
                    <td>Denied IP List Edit</td><td>
                        <textarea rows="20" cols="30" name="iplist"></textarea></td>
                </tr>
                <tr>
                    <td>Deny the above IP List</td><td><input name="deniedip_status" value="on" checked="" type="radio" class="radio"> On
                    <input name="deniedip_status" value="off" type="radio" class="radio"> Off
                </td>
                </tr>
                <tr>
                    <td>Denied IP Range</td><td><textarea rows="20" cols="30" name="iprange"></textarea></td>
                </tr>
                <tr>
                    <td>Deny the above IP Range</td><td><input name="denyiprange_status" value="on" checked="" type="radio" class="radio"> On
                    <input name="denyiprange_status" value="off" type="radio" class="radio"> Off
                </td>
                </tr>
                <tr>
                    <td>Visits Count (in number of visits)</td><td><input name="visit_count" value="5" type="text"></td>
                </tr>
                <tr>
                    <td>Cloak Based on Visits Count?</td><td><input name="visitcount_status" value="on" checked="" type="radio" class="radio"> On
                    <input name="visitcount_status" value="off" type="radio" class="radio"> Off
                </td>

                </tr><tr>
                    <td>Reverse DNS </td><td><input size="38" name="rdns" value="google.com,googlebot.com,microsoft.com,bing.com,google,msn,msn.com,msnbot" type="text">
                </td>
                </tr>
                <tr>
                    <td>Cloak based on Reverse DNS?</td><td><input name="rdns_status" value="on" checked="" type="radio" class="radio"> On
                    <input name="rdns_status" value="off" type="radio" class="radio"> Off
                </td>
                </tr>
                <tr>
                    <td>Geo Location List (comma-separated list of town, state and/or country names)</td><td><input size="38" name="geolocation" value="JAPAN,TOKYO,REDMOND" type="text"></td>
                </tr>
                <tr>
                    <td>Cloak upon Geo Location Match?</td><td><input name="geoloc_status" value="on" checked="" type="radio" class="radio"> On
                    <input name="geoloc_status" value="off" type="radio" class="radio"> Off
                    </td>
                </tr>
                <tr>
                    <td>Cloak upon Geo Location Mismatch?</td><td><input name="geoloc_mismatch_status" value="on" type="radio" class="radio"> On
                    <input name="geoloc_mismatch_status" value="off" type="radio" class="radio" checked> Off
                    </td>
                </tr>
                <tr><td>User Agent strings (comma-separated)</td><td><input size="38" name="ua_strings" type="text"></td></tr>
                <tr><td>Cloak based on User Agent?</td><td><input name="ua_status" value="on" type="radio" class="radio"> On <input name="ua_status" value="off" type="radio" class="radio" checked> Off</td></tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input value="Create" type="submit"></td>
                </tr>
                </tbody>
            </table>
        </form>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_dashboard.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
