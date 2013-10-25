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
            <form action="<?php echo ADMIN_URL; ?>traffic_source/" method="POST">
                <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                    <tbody>
                    <tr>
                        <td>Name</td>
                        <td>
                            <input size="38" name="name" type="text" value="<?php if (!empty($data['id'])) echo $data['name'];?>">
                        <?php if ((!empty($_POST['action'])) && ($_POST['action'] == 'edit')): ?>
                            <input id="formAction" type="hidden" name="action" value="update">
                            <input id="formID" type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                            <button type="submit">Update</button>
                        </td>
                        <?php else: ?>
                            <input id="formAction" type="hidden" name="action" value="add">
                            <input id="formID" type="hidden" name="id" value="<?php echo $data['id']; ?>">
                            <button type="submit">Add</button>
                        </td>
                        <?php endif; ?>
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
                <tr><td>ID</td><td>Name</td><td>Options</td></tr>

                <?php foreach($data['traffic_sources'] as $traffic): ?>
                    <tr class="mhov" onclick="editDestination('<?php echo $destination['id']; ?>')">
                        <td><?php echo $traffic['id'] ?></td>
                        <td><?php echo $traffic['name'] ?></td>
                        <td><a href="javascript:void(0)" onclick="editTrafficSource('<?php echo $traffic['id']; ?>')">Edit</a>&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0)" onclick="deleteTrafficSource('<?php echo $traffic['id']; ?>')">Delete</a></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
            <div class="bl"><div class="br"></div></div>
        </div>
    </div>
    <script type="text/javascript">
	function editTrafficSource(id)
	{
		$('#formAction').val('edit');
		$('#formID').val(id);
		document.forms[0].submit();
	}
	function deleteTrafficSource(id)
	{
		$('#formAction').val('delete');
		$('#formID').val(id);
		document.forms[0].submit();
	}
	</script>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_dashboard.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
