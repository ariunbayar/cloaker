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
        <h2 class="boxtitle">Manage Traffic Source</h2>
        <form action="<?php echo ADMIN_URL; ?>save_traffic_source/<?php echo $data['campaign']->id ?>/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td>Name</td>
                    <td>
                        <?php $ts = (isset($data['traffic_source']) ? $data['traffic_source'] : null) ?>
                        <input size="38" name="name" type="text" value="<?php if ($ts) echo $ts->name ?>"/>
                        <?php if ($ts) { ?>
                            <input id="formID" type="hidden" name="id" value="<?php echo $ts->id ?>">
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
        <h2 class="boxtitle">Existing Traffic Sources</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="1" class="table">
            <tbody>
            <tr class="hd"><td>ID</td><td>Name</td><td>Options</td></tr>
            <?php foreach($data['traffic_sources'] as $traffic): ?>
            <tr>
                <td><?php echo $traffic->id ?></td>
                <td><?php echo $traffic->name ?></td>
                <td>
                    <a href="<?php echo ADMIN_URL.'edit_traffic_source/'.$data['campaign']->id.'/?ts_id='.$traffic->id ?>">
                        Edit
                    </a>&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ADMIN_URL.'delete_traffic_source/'.$data['campaign']->id.'/?ts_id='.$traffic->id ?>"
                        onclick="return confirm('Are you sure to delete this traffic source?');">
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
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
