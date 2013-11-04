<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Migration Deploy</h2>
        <form action="<?php echo ADMIN_URL; ?>migration_deploy/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td></td>
                    <td>
                        Click following URL to run the migration:<br/>
                        <?php echo anchor_tag(ADMIN_URL.'migration_deploy/?execute=yes') ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_dashboard.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
