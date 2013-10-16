<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Cloaker | Dashboard</title>
    <?php require dirname(__FILE__).'/_header_includes.php'; ?>
</head>
<body>
	<h1 class="title">URL Cloaker</h1>
	<div class="menu">
		<div></div>
		<ul>
			<li><a href="<?php echo ADMIN_URL; ?>manage/<?php echo $data['id']; ?>/">Campaign Details<span></span></a></li>
			<li><a href="<?php echo ADMIN_URL; ?>destinations/<?php echo $data['id']; ?>/">Destinations<span></span></a></li>
			<li class="active"><a href="javascript:void(0);">Statistics<span></span></a></li>
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
		<div class="scont">
			<div class="box">
				<div class="tl"><div class="tr"></div></div>
				<h2 class="boxtitle">Campaign Statistics: <?php echo $data['name']; ?></h2>
                <?php require dirname(__FILE__).'/_statistics_filter.php'; ?>
				<table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
					<tbody>
					<tr class="hd"><td>IP</td><td>Referer</td><td>Host</td><td>Country</td><td>Region</td><td>City</td><td>Page Views</td><td>Cloak</td><td>Cloak Reason</td><td>Access Time</td><td>Access Date</td></tr>
					<?php foreach($data['statistics'] as $stats): ?>
					<tr class="mhov"><td><?php echo $stats['ip']; ?></td><td><?php echo $stats['referral_url']; ?></td><td><?php echo $stats['host']; ?></td><td><?php echo $stats['country']; ?></td><td><?php echo $stats['region']; ?></td><td><?php echo $stats['city']; ?></td><td><?php echo $stats['page_views']; ?></td><td><?php echo $stats['cloak']; ?></td><td><?php echo $stats['reasonforcloak']; ?></td><td><?php echo $stats['access_time']; ?></td><td><?php echo $stats['ct_dt']; ?></td></tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<div class="bl"><div class="br"></div></div>
			</div>
		</div>
		Pages:
		<?php if ($data['total_pages'] <= 5): ?>
        <?php for($i = 1;$i <= $data['total_pages'];$i++): ?>
        <?php if ($i == $data['page']): ?>
        <a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
        <?php else: ?>
        <a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
        <?php endif; ?>
        <?php endfor; ?>
		<?php elseif ($data['total_pages'] > 5): ?>
		<?php if ($data['page'] >= 3 && $data['page'] <= $data['total_pages'] - 2): ?>
		<a href="<?php echo sprintf($data['url_format'], 1) ?>">First</a>
		<a href="<?php echo sprintf($data['url_format'], $data['page'] - 1) ?>">Previous</a>
		<?php for($i = $data['page'] - 2;$i <=  $data['page'] + 2;$i++): ?>
		<?php if ($i == $data['page']): ?>
		<a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
		<?php else: ?>
		<a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
		<?php endif; ?>
		<?php endfor; ?>
		<a href="<?php echo sprintf($data['url_format'], $data['page'] + 1) ?>">Next</a>
		<a href="<?php echo sprintf($data['url_format'], $data['total_pages']) ?>">Last</a>
		<?php elseif ($data['page'] < 3): ?>
		<?php for($i = 1;$i <= 5;$i++): ?>
		<?php if ($i == $data['page']): ?>
		<a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
		<?php else: ?>
		<a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
		<?php endif; ?>
		<?php endfor; ?>
		<a href="<?php echo sprintf($data['url_format'], $data['page'] + 1) ?>">Next</a>
		<a href="<?php echo sprintf($data['url_format'], $data['total_pages']) ?>">Last</a>
		<?php elseif ($data['page'] > $data['total_pages'] - 2): ?>
		<a href="<?php echo sprintf($data['url_format'], 1) ?>">First</a>
		<a href="<?php echo sprintf($data['url_format'], $data['page'] - 1) ?>">Previous</a>
		<?php for($i = $data['total_pages'] - 5;$i <= $data['total_pages'];$i++): ?>
		<?php if ($i == $data['page']): ?>
		<a href="javascript:void(0)" class="selected"><?php echo $i; ?></a>
		<?php else: ?>
		<a href="<?php echo sprintf($data['url_format'], $i) ?>"><?php echo $i; ?></a>
		<?php endif; ?>
		<?php endfor; ?>								
		<?php endif; ?>
		<?php endif; ?>
	</div>
</body>
</html>
