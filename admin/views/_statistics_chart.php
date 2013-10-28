<div class="charts">
    <div id="total_page_views" class="chart"></div>
    <div id="page_views_by_geolocation" class="chart"></div>
</div>

<?php ob_start() ?>
chartTotalPageViews(
    '#total_page_views',
    <?php echo json_encode($data['total_page_views']) ?>
);
chartPageViewByGeolocation(
    '#page_views_by_geolocation',
    <?php echo json_encode($data['page_views_by_geolocation']) ?>
);
<?php G::set('main_js', ob_get_clean(), True) ?>
