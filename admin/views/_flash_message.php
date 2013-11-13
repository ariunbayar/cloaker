<?php ob_start() ?>
    <?php if (Flash::exists()): ?>
    <div class="scont">
        <div class="box">
            <div class="tl"><div class="tr"></div></div>
            <?php list($label, $msg) = Flash::get() ?>
            <h2 class="boxtitle"><?php echo $label ?></h2>
            <p><?php echo $msg ?></p>
            <div class="bl"><div class="br"></div></div>
        </div>
    </div>
    <?php endif; ?>
<?php $flash_message = ob_get_clean() ?>
