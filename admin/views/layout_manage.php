<?php $main_title = (isset($main_title) ? $main_title : 'Manage Campaign') ?>
<?php ob_start() ?>

<h1 class="title">URL Cloaker</h1>

<div class="menu">
    <div></div>
    <ul>
        <li>
        <li <?php echo $data['current_page']=='manage'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL; ?>manage/<?php echo $data['id']; ?>/">Campaign Details<span></span></a>
        </li>
        <li <?php echo $data['current_page']=='traffic_source'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL ?>traffic_source/<?php echo $data['id'] ?>/">Traffic Sources<span></span></a>
        </li>
        <li <?php echo $data['current_page']=='network'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL.'network/'.$data['id'].'/' ?>">Affiliate Network<span></span></a>
        </li>
        <li <?php echo $data['current_page']=='offer'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL.'offer/'.$data['id'].'/' ?>">Offers<span></span></a>
        </li>
        <?php /*
        <li <?php echo $data['current_page']=='destinations'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL; ?>destinations/<?php echo $data['id']; ?>/">Destinations<span></span></a>
        </li>
        */ ?>
        <li <?php echo $data['current_page']=='generate_links'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL; ?>generate_links/<?php echo $data['id']; ?>/">Generate Links<span></span></a>
        </li>

        <li <?php echo $data['current_page']=='statistics'?'class="active"':'' ?>>
            <a href="<?php echo ADMIN_URL; ?>statistics/<?php echo $data['id']; ?>/">Statistics<span></span></a>
        </li>
        <li><a href="<?php echo ADMIN_URL; ?>">Back to Dashboard<span></span></a></li>
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
    <?php if(!empty($data['success'])): ?>
    <div class="scont">
        <div class="box">
            <div class="tl"><div class="tr"></div></div>
            <h2 class="boxtitle">Success</h2>
            <?php foreach($data['success'] as $message): ?>
            <p><?php echo $message; ?></p>
            <?php endforeach; ?>
            <div class="bl"><div class="br"></div></div>
        </div>
    </div>
    <?php endif; ?>
    <?php if(!empty($data['warning'])): ?>
    <div class="scont">
        <div class="box">
            <div class="tl"><div class="tr"></div></div>
            <h2 class="boxtitle">Warning</h2>
            <?php foreach($data['warning'] as $message): ?>
            <p><?php echo $message; ?></p>
            <?php endforeach; ?>
            <div class="bl"><div class="br"></div></div>
        </div>
    </div>
    <?php endif; ?>


    <?php echo $main_content ?>

</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
