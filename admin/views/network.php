<?php ob_start() ?>

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
<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Manage Affiliate Network</h2>
        <form action="<?php echo ADMIN_URL.'save_network/'.$data['campaign']->id ?>/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td>Name</td>
                    <td>
                        <?php $network = (isset($data['network']) ? $data['network'] : null) ?> 
                        <input size="38" name="name" type="text" value="<?php if ($network) echo $network->name ?>"/>
                        <?php if($network) { ?>
                            <input id="formID" type="hidden" name="network_id" value="<?php echo $network->id ?>"/>
                        <?php } ?>
                        <input type="submit" value="Save"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>
<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Existing Affiliate Network</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="1" class="table">
            <tbody>
            <tr class="hd"><td>ID</td><td>Name</td><td>Options</td></tr>

            <?php foreach($data['networks'] as $network): ?>
                <tr>
                    <td><?php echo $network->id ?></td>
                    <td><?php echo $network->name ?></td>
                    <td>
                        <a href="<?php echo ADMIN_URL.'delete_network/'.$data['campaign']->id.'/?network_id='.$network->id ?>" 
                        onclick="return confirm('Are you sure to delete this network?');">
                            Delete
                        </a>
                     </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
