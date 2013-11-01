<?php ob_start() ?>

<div class="main">
	<div class="box" style="margin:8px auto; width:350px;">
		<div class="tl"><div class="tr"></div></div>
		<h3 class="boxtitle">Login Required</h3>
		<div class="pad">
			<form action="" method="post" enctype="application/x-www-form-urlencoded">
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
					<tr>
						<td width="50%">Username:</td>
						<td><input name="username" class="xinput" type="text"></td>
					</tr>
					<tr>
						<td width="50%">Password:</td>
						<td><input class="xinput" name="password" type="password"></td>
					</tr>
				</table>
				<p align="center"><input type="submit" name="login" value="Login"></p>
			</form>
		</div>
        <div class="bl"><div class="br"></div> &copy 2013</div>
	</div>
</div>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
