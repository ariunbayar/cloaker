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

    <div class="footer">
    </div>

    <!-- Javascript should be included after elements are loaded -->
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/highcharts/highcharts.js"></script>
    <script type="text/javascript" src="<?php echo STATIC_URL; ?>js/main.js"></script>
    <script type="text/javascript">
        <?php $main_js_array = G::get('main_js', True) ?>
        $(function(){
        <?php foreach ($main_js_array as $main_js){ ?>
            <?php echo (isset($main_js) ? $main_js : '') ?>;
        <?php } ?>
        });
    </script>
</body>
</html>
