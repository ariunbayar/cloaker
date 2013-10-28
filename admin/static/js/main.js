function toDateRange(from_field, to_field)
{
    $(from_field).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
            $(to_field).datepicker("option", "minDate", selectedDate);
        }
    });
    $(to_field).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        dateFormat: "yy-mm-dd",
        onClose: function( selectedDate ) {
            $(from_field).datepicker("option", "maxDate", selectedDate);
        }
    });
}


function chartTotalPageViews(el, data)
{
    $(el).highcharts({
        chart: {
            type: 'area',
            zoomType: 'x'
        },
        title: {
            text: 'Number of regular and cloaked page views'
        },
        subtitle: {
            text: 'Click and drag in the plot area to zoom in'
        },
        xAxis: {
            type: 'datetime',
            maxZoom: 24 * 3600000,
            title: { text: null }
        },
        yAxis: {
            title: { text: 'page views' }
        },
        tooltip: { shared: true },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: { enabled: false }
            }
        },
        series: [
            {
                name: 'Cloaked',
                pointInterval: 24 * 3600 * 1000,
                pointStart: Date.UTC(data.start_date[0], data.start_date[1]-1, data.start_date[2]),
                data: data.cloaked
            },
            {
                name: 'Non-cloaked',
                pointInterval: 24 * 3600 * 1000,
                pointStart: Date.UTC(data.start_date[0], data.start_date[1]-1, data.start_date[2]),
                data: data.non_cloaked
            }
        ]
    });
}


function chartPageViewByGeolocation(el, data)
{
    var start_date = Date.UTC(data.start_date[0], data.start_date[1]-1, data.start_date[2]);
    var series = [];
    $.each(data, function(geolocation, page_view_data){
        if (geolocation == 'start_date') return;
        series.push({
            name: geolocation,
            pointInterval: 24 * 3600 * 1000,
            pointStart: start_date,
            data: page_view_data
        });
    });
    $(el).highcharts({
        chart: {
            type: 'column',
            zoomType: 'x'
        },
        title: {
            text: 'Page views by Geological Location'
        },
        subtitle: {
            text: 'Click and drag in the plot area to zoom in'
        },
        xAxis: {
            type: 'datetime',
            maxZoom: 24 * 3600000,
            title: { text: null }
        },
        yAxis: {
            title: { text: 'page views' }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0,
                borderWidth: 0
            }
        },
        series: series
    });
}


/* TODO tabs are changed to links
function showTab(el)
{
    var id = $(el).attr('data-id');
    $('.menu li').removeClass('active');
    $('section').each(function(){
        if ($(this).css('display') == 'block')
        {
            if ($(this).attr('id') != 'textEditor')
            {
                $(this).slideUp(400);
            }
        }
    });
    $('#'+id).slideDown(400);
    $(el).parent().addClass('active');

    url = window.location.href.split('#')[0];
    window.location.href = url + "#" + id;
}
*/


function clickableRowsForCampaignList(table) {
    var el = $(table);
    el.find('tbody tr td:nth-child(-n+6)').click(function(e){
        window.location.href = $(this).parent().attr('data-url');
        e.preventDefault();
    });
}


$(function(){
    toDateRange("input[name=date_from]", "input[name=date_to]");
    clickableRowsForCampaignList('#campaign_list');

    /* TODO tabs are changed to links
    $('.menu li a').click(function(e){
        showTab(this);
        e.preventDefault();
    });
    if (window.location.hash){
        var id = window.location.hash.substr(1);
        showTab($('.menu li a[data-id='+id+']'));
    }
    */
});
