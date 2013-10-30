<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Generate links: <?php echo $data['name']; ?></h2>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>	

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Generated links</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
            <thead>
                <tr class="hd">
                    <td>URL</td>
                    <td>Options</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['trackers'] as $tracker){ ?>
                <tr>
                    <td>
                        <?php $url = substr(ADMIN_URL, 0, -6).$tracker->shortcode.'/' ?>
                        <?php echo anchor_tag($url) ?>
                    </td>
                    <td>edit delete</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>			

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
