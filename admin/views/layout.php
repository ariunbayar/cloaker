<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cloaker<?php echo (isset($main_title) ? ' | '.$main_title : '') ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL; ?>css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css">
</head>
<body>
    <?php echo $main_content ?>

    <!-- JS should be included after elements are loaded -->
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/highcharts/highcharts.js"></script>
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/main.js"></script>
</body>
</html>
