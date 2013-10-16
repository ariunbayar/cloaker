<h3 id="filter_toggle">Click here to toggle filter <span id="indicator">+</span></h3>
<form id="filter_form" style="display: none;" action="<?php echo sprintf($data['url_format'], 1)?>">
    <div class="column"/>
        <label>IP address:</label>
        <input type="text" name="ip" value="<?php echo $data['filters']['ip'] ?>"/>

        <label>Referer:</label>
        <input type="text" name="referer" value="<?php echo $data['filters']['referer'] ?>"/>

        <label>Host:</label>
        <input type="text" name="host" value="<?php echo $data['filters']['host'] ?>"/>

        <label>Country:</label>
        <input type="text" name="country" value="<?php echo $data['filters']['country'] ?>"/>
    </div>

    <div class="column"/>
        <label>Region:</label>
        <input type="text" name="region" value="<?php echo $data['filters']['region'] ?>"/>

        <label>City:</label>
        <input type="text" name="city" value="<?php echo $data['filters']['city'] ?>"/>

        <label>Cloak:</label>
        <select name="cloak">
            <option value="">---</option>
            <option value="yes" <?php echo ($data['filters']['cloak'] == 'yes' ? 'selected' : '') ?>>
                Yes
            </option>
            <option value="no" <?php echo ($data['filters']['cloak'] == 'no' ?  'selected' : '') ?>>
                No
            </option>
        </select>

        <label>Cloak reason:</label>
        <?php $options = array(
            'Referral URL Not Empty',
            'GET Parameter Match',
            'Reverse DNS Matched',
            'Geo Location Matched',
            'Geo Location Mismatch',
            'Denied IP Matched',
            'Denied IP Range Matched',
            'Visit Threshold Exceeded',
            'Unallowed User Agent',
        ); ?>
        <select name="cloak_reason">
            <option value="">---</option>
            <?php foreach ($options as $option){ ?>
            <option value="<?php echo $option ?>" <?php echo ($data['filters']['cloak_reason'] == $option ? 'selected' : '') ?>>
                <?php echo $option ?>
            </option>
            <?php } ?>
        </select>
    </div>

    <div class="column"/>
        <label>Access date:</label>
        <input type="text" name="access_date_from" value="<?php echo $data['filters']['access_date_from'] ?>"/>
        <label></label>
        <input type="text" name="access_date_to" value="<?php echo $data['filters']['access_date_to'] ?>"/>
        <label></label>
        <input type="submit" value="Filter"/>
    </div>
</form>

<script type="text/javascript">
$(function(){
    $('#filter_toggle').click(function(){
        if ($('#indicator').html() == '+'){
            $('#filter_form').show();
            $('#indicator').html('-');
        }else{
            $('#filter_form').hide();
            $('#indicator').html('+');
        }
    });
    $("input[name=access_date_from]").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
            $("input[name=access_date_to]").datepicker("option", "minDate", selectedDate);
        }
    });
    $("input[name=access_date_to]").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        dateFormat: "yy-mm-dd",
        onClose: function( selectedDate ) {
            $("input[name=access_date_from]").datepicker("option", "maxDate", selectedDate);
        }
    });
});
</script>
