<?php $main_title = 'Dashboard' ?>
<?php ob_start() ?>

<h1 class="title">URL Cloaker</h1>

<div class="menu">
    <ul>
        <li <?php echo $data['current_page']=='dashboard'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL ?>">Manage Campaigns<span></span></a>
        </li>
        <li <?php echo $data['current_page']=='add_campaign'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL ?>add_campaign/">New Campaign<span></span></a>
        </li>
        <?php if($_SESSION['user_level'] == 'superadmin') { ?>
        <li <?php echo $data['current_page']=='global_ip'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL ?>giplist/">Global IP List<span></span></a>
        </li>
        <li <?php echo $data['current_page']=='migration_deploy'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL ?>migration_deploy/">Migration<span></span></a>
        </li>
        <?php } ?>
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

    <?php if (Flash::exists()): ?>
    <div class="scont">
        <div class="box">
            <div class="tl"><div class="tr"></div></div>
            <h2 class="boxtitle">Error</h2>
            <p><?php echo Flash::get() ?></p>
            <div class="bl"><div class="br"></div></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!empty($data['success'])): ?>
    <div class="scont">
        <div class="box">
            <div class="tl"><div class="tr"></div></div>
            <h2 class="boxtitle">Success</h2>
            <p><?php echo $data['success']; ?></p>
            <div class="bl"><div class="br"></div></div>
        </div>
    </div>
    <?php endif; ?>

    <?php echo $main_content ?>

</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
