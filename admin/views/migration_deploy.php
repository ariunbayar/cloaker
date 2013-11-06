<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Migration Deploy</h2>
        <p>
            <?php $files = $data['migration_files'] ?>
            <?php foreach ($data['migrations'] as $migration){ ?>
                <?php $idx = array_search($migration->file_name, $files) ?>
                <?php echo ($idx === false ? 'no' : '<b>yes</b>') ?>
                -
                <?php echo $migration->file_name ?>
                <br/>
                <?php unset($files[$idx]) ?>
            <?php } ?>
            <?php foreach ($files as $file){ ?>
                no - <?php echo $file ?>
                <br/>
            <?php } ?>

            <br/>
            <a href="<?php echo ADMIN_URL.'migration_deploy/?execute=yes' ?>" class="btn">
                Click here to run the missing migration
            </a>
        </p>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_dashboard.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
