<?php $main_title = (isset($main_title) ? $main_title : 'Manage Destinations') ?>
<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Manage Destinations: <?php echo $data['name']; ?></h2>
        <form action="<?php echo ADMIN_URL; ?>destinations/<?php echo $data['id']; ?>/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                    <tr>
                        <td>Destination URL</td>
                        <td>
                            <input type="text" name="url" size="40" value="<?php if (!empty($data['url'])) echo $data['url'];?>">
                            You can include [[subid]] in your url. Example: http://ad.com/?s=[[subid]]&amp;t=yes
                        </td>
                    </tr>	
                    <tr><td>Notes</td><td><textarea name="notes" cols="80" rows="8"><?php if (!empty($data['notes'])) echo $data['notes'];?></textarea></td></tr>
                    <?php if ((!empty($_POST['action'])) && ($_POST['action'] == 'edit')): ?>
                    <input id="formAction" type="hidden" name="action" value="update">
                    <input id="formID" type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                    <tr><td>&nbsp;</td><td><button type="submit">Update</button></td></tr>
                    <?php else: ?>
                    <input id="formAction" type="hidden" name="action" value="add">
                    <input id="formID" type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <tr><td>&nbsp;</td><td><button type="submit">Add</button></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>	

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Existing Destinations</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
            <tbody>
            <tr class="hd"><td>URL</td><td>Options</td></tr>
            <?php foreach($data['destinations'] as $destination): ?>
            <tr class="mhov" onclick="editDestination('<?php echo $destination['id']; ?>')"><td><?php echo $destination['url']; ?></td><td><a href="javascript:void(0)" onclick="editDestination('<?php echo $destination['id']; ?>')">Edit</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="deleteDestination('<?php echo $destination['id']; ?>')">Delete</a></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>			

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
