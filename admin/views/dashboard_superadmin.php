<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cloaker | Dashboard</title>
    <?php require dirname(__FILE__).'/_header_includes.php'; ?>
    <script type="text/javascript">
    function showTab(reference,id)
    {
        $('.menu li').removeClass('active');
        $('section').each(function(){
            if ($(this).css('display') == 'block')
            {
                if ($(this).attr('id') != 'textEditor')
                {
                    $(this).slideUp(400);
                }
            }
        });
        $('#'+id).slideDown(400);
        $(reference.parentNode).addClass('active');
    }
    </script>
</head>
<body>
    <h1 class="title">URL Cloaker</h1>
    <div class="menu">
        <div></div>
        <ul>
            <li class="active"><a href="javascript:void(0)" onclick="showTab(this,'manage')">Manage Campaigns<span></span></a></li>
            <li><a href="javascript:void(0)" onclick="showTab(this,'new')">New Campaign<span></span></a></li>
            <li><a href="javascript:void(0)" onclick="showTab(this,'globalip')">Global IP List<span></span></a></li>
            <li><a href="<?php echo ADMIN_URL ?>logout/">Logout<span></span></a></li>
        </ul>
        <br class="clear">
    </div>
    <div class="main">
        <?php if(!empty($data['errors'])): ?>
        <div class="scont">
            <div class="box">
                <div class="tl"><div class="tr"></div></div>
                <h2 class="boxtitle">Error</h2>
                <?php foreach($data['errors'] as $error): ?>
                <p><?php echo $error; ?></p>
                <?php endforeach; ?>
                <div class="bl"><div class="br"></div></div>
            </div>
        </div>
        <?php endif; ?>
        <section id="manage">
            <div class="scont">
                <div class="box">
                    <div class="tl"><div class="tr"></div></div>
                    <h2 class="boxtitle">Campaigns</h2>
                    <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                        <tbody>
                        <tr class="hd">
                            <td>ID</td>
                            <td>Name</td>
                            <td>Date Created</td>
                            <td>Last Modified</td>
                            <td>Cloaking</td>
                            <td>
                                <form method="get" class="date_range">
                                    # page views for
                                     <input type="text" name="date_from" value="<?php echo $data['filters']['date_from'] ?>"/>
                                    to <input type="text" name="date_to" value="<?php echo $data['filters']['date_to'] ?>"/>
                                    <input type="submit" value="GO">
                                </form>
                                <script type="text/javascript">
                                $(function(){
                                    $("input[name=date_from]").datepicker({
                                        defaultDate: "+1w",
                                        changeMonth: true,
                                        numberOfMonths: 3,
                                        dateFormat: "yy-mm-dd",
                                        onClose: function(selectedDate) {
                                            $("input[name=date_to]").datepicker("option", "minDate", selectedDate);
                                        }
                                    });
                                    $("input[name=date_to]").datepicker({
                                        defaultDate: "+1w",
                                        changeMonth: true,
                                        numberOfMonths: 3,
                                        dateFormat: "yy-mm-dd",
                                        onClose: function( selectedDate ) {
                                            $("input[name=date_from]").datepicker("option", "maxDate", selectedDate);
                                        }
                                    });
                                });
                                </script>
                            </td>
                            <td>Options</td>
                        </tr>
                        <?php foreach($data['campaigns'] as $campaign): ?>
                            <tr class="mhov" onclick="window.location.href='<?php echo ADMIN_URL; ?>manage/<?php echo $campaign['id']; ?>/'">
                                <td><?php echo $campaign['id']; ?></td>
                                <td><?php echo $campaign['name']; ?></td>
                                <td><?php echo $campaign['ct_dt']; ?></td>
                                <td><?php echo $campaign['md_dt']; ?></td>
                                <td><?php echo $campaign['cloak_status']; ?></td>
                                <td><?php list($cloaked, $non_cloaked) = $campaign['page_views'] ?>
                                    <?php echo $cloaked." cloaked, ".$non_cloaked." non-cloaked" ?>
                                </td>
                                <td>
                                    <a href="<?php echo ADMIN_URL; ?>manage/<?php echo $campaign['id']; ?>/">Manage</a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo ADMIN_URL; ?>delete/<?php echo $campaign['id']; ?>/">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="bl"><div class="br"></div></div>
                </div>
            </div>
        </section>
        <section id="new" style="display:none;">
            <div class="scont">
                <div class="box">
                    <div class="tl"><div class="tr"></div></div>
                    <h2 class="boxtitle">New Campaign</h2>
                    <form action="<?php echo ADMIN_URL; ?>create/" method="POST">
                        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                            <tbody>
                            <tr><td>Name</td><td><input size="38" name="name" type="text"></td></tr>
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
        </section>
        <section id="globalip" style="display: none;">
            <div class="scont">
                <div class="box">
                    <div class="tl"><div class="tr"></div></div>
                    <h2 class="boxtitle">Global Denied IP</h2>
                    <form action="<?php echo ADMIN_URL; ?>giplist/" method="POST">
                        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                            <tbody>
                            <tr>
                                <td>Global Denied IP List Edit</td><td>
                                    <textarea rows="20" cols="30" name="giplist"><?php echo implode(PHP_EOL,$data['giplist']); ?></textarea></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input value="Save" type="submit"></td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                    <div class="bl"><div class="br"></div></div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
