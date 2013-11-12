<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Campaigns</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table" id="campaign_list">
            <thead>
            <tr class='hd'>
                <td rowspan='2'>ID</td>
                <td rowspan='2'>Name</td>
                <td rowspan='2'>Date Created</td>
                <td rowspan='2'>Last Modified</td>
                <td rowspan='2'>Cloaking</td>
                <td colspan='3'>
                    <center>
                        <form method="get" class="date_range">
                            <input type="text" name="date_from" value="<?php echo $data['filters']['date_from'] ?>"/>
                            to <input type="text" name="date_to" value="<?php echo $data['filters']['date_to'] ?>"/>
                            <input type="submit" value="GO">
                        </form>
                    </center>
                </td>
                <td rowspan='2'>Options</td>
            </tr>
            <tr class="hd">
                <td>
                    # Page Views
                </td>
                <td>
                    <abbr title="This is a converted click">
                        <i class="fa fa-dollar fa-lg dollar-icon"></i>
                    </abbr>
                </td>
                <td>
                        # Click
                </td>
            </tr>
            </thead>

            <tbody>
            <?php foreach($data['campaigns'] as $campaign): ?>
            <tr class="mhov" data-url="<?php echo ADMIN_URL; ?>manage/<?php echo $campaign['id']; ?>/">
                <td><?php echo $campaign['id']; ?></td>
                <td><?php echo $campaign['name']; ?></td>
                <td><?php echo $campaign['ct_dt']; ?></td>
                <td><?php echo $campaign['md_dt']; ?></td>
                <td><?php echo $campaign['cloak_status']; ?></td>
                <?php list($cloaked, $non_cloaked, $converted_clicks, $click) = $campaign['page_views']; ?>
                <td>
                    <?php echo $cloaked." cloaked, ".$non_cloaked." non-cloaked" ?>
                </td>
                <td>
                    <?php echo $converted_clicks ?>
                </td>
                <td>
                    <?php echo $click ?>
                </td>
                <td>
                    <a href="<?php echo ADMIN_URL; ?>manage/<?php echo $campaign['id']; ?>/">Manage</a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ADMIN_URL; ?>delete_campaign/<?php echo $campaign['id']; ?>/"
                       onclick="return confirm('Are you sure to delete this campaign?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_dashboard.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
