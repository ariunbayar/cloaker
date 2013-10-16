<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Cloaker | Manage Destinations</title>
	<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>style.css">
	<script type="text/javascript" src="<?php echo ADMIN_URL; ?>jquery-1.9.0.min.js"></script>
</head>
<body>
	<h1 class="title">URL Cloaker</h1>
	<div class="menu">
		<div></div>
		<ul>
			<li><a href="<?php echo ADMIN_URL; ?>manage/<?php echo $data['id']; ?>/">Campaign Details<span></span></a></li>
			<li class="active"><a href="javascript:void(0);">Destinations<span></span></a></li>
			<li><a href="<?php echo ADMIN_URL; ?>statistics/<?php echo $data['id']; ?>/">Statistics<span></span></a></li>
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
		<div class="scont">
			<div class="box">
				<div class="tl"><div class="tr"></div></div>
				<h2 class="boxtitle">Manage Destinations: <?php echo $data['name']; ?></h2>
				<form action="<?php echo ADMIN_URL; ?>destinations/<?php echo $data['id']; ?>/" method="POST">
					<table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
						<tbody>
							<tr><td>Destination URL</td><td><input type="text" name="url" size="40" value="<?php if (!empty($data['url'])) echo $data['url'];?>"></td></tr>	
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
	</div>
	<script type="text/javascript">
	function editDestination(id)
	{
		$('#formAction').val('edit');
		$('#formID').val(id);
		document.forms[0].submit();
	}
	function deleteDestination(id)
	{
		$('#formAction').val('delete');
		$('#formID').val(id);
		document.forms[0].submit();
	}
	</script>
</body>
</html>