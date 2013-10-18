<div class="charts">
    <div id="total_page_views" class="chart"></div>
    <!--<div id="page_views_by_country" class="chart"></div>-->
</div>
<script type="text/javascript">
$(function(){
    chartTotalPageViews(
        '#total_page_views',
        <?php echo json_encode($data['total_page_views']) ?>
    );
    //chartPageViewByCountry('#page_views_by_country');
});
</script>
