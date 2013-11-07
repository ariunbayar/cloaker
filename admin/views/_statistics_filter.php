<h3 id="filter_toggle">Click here to toggle filter <span id="indicator">+</span></h3>
<form id="filter_form" style="display: none;" action="<?php echo sprintf($data['url_format'], 1)?>">
    <div class="column">
        <label>Converted:</label>
        <select name="converted">
            <option value="">---</option>
            <option value="1" <?php echo ($data['filters']['converted'] == '1' ? 'selected' : '') ?>>
                Yes
            </option>
            <option value="0" <?php echo ($data['filters']['converted'] == '0' ?  'selected' : '') ?>>
                No
            </option>
        </select>

        <label>Traffic source:</label>
        <?php echo select_tag('traffic_source_id', $data['traffic_source_id'], $data['filters']['traffic_source_id'], '---') ?>

        <label>Affiliate Network</label>
        <?php echo select_tag('network', $data['network'], $data['filters']['network'], '---') ?>

        <label>Offer</label>
        <?php echo select_tag('offer', $data['offer'], $data['filters']['offer'], '---') ?>

    </div>

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

        <?php if($_SESSION['user_level'] == 'superadmin') { ?>
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
        <?php } ?>
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

<?php ob_start() ?>
statisticsFilter();
<?php G::set('main_js', ob_get_clean(), True) ?>
