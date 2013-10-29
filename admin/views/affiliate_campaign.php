<?php ob_start() ?>

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
        <h2 class="boxtitle">Manage Affiliate Campaign</h2>
        <form action="<?php echo ADMIN_URL; ?>save_affiliate_campaign/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td>Name</td>
                    <td>
                        <input size="38" name="name" type="text" value="<?php echo $data['name'] ?>">
                    </td>
                </tr>
                <tr>
                    <td>Affiliate Network</td>
                    <td>
                        <select name="affiliate_network_id" id="affnetwork" onchange="setAffNetwork()">
                        <option>-Select one-</option>
                        <?php foreach($data['affiliate_networks'] as $affiliate_network): ?>
                            <option value="<?php echo $affiliate_network['id'] ?>"
                            <?php if ($data['affiliate_network_id'] == $affiliate_network['id']) echo "selected"; ?> >
                                <?php echo $affiliate_network['name']; ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                        <b id="buttons">
                            <a href="#" onclick="addAffNetwork(this)">Add new</a>
                        </b>
                        <input type="hidden" id="id" name="id">
                        <div id="aff_net"></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                        <input id="save_aff_camp"type="submit" value="Save"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Existing Affiliate Campaign</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="1" class="table">
            <tbody>
            <tr class="hd"><td>ID</td><td>Name</td><td>Affiliate Network</td><td>Options</td></tr>

            <?php foreach($data['affiliate_campaigns'] as $affiliate_campaign): ?>
                <tr>
                    <td><?php echo $affiliate_campaign['id'] ?></td>
                    <td><?php echo $affiliate_campaign['name'] ?></td>
                    <td><?php echo $affiliate_campaign['affiliate_network_name'] ?></td>
                    <td>
                        <a href="<?php echo ADMIN_URL; ?>edit_affiliate_campaign/<?php echo $affiliate_campaign['id']; ?>/">
                            Edit
                        </a>&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo ADMIN_URL; ?>delete_affiliate_campaign/<?php echo $affiliate_campaign['id']; ?>/" 
                                onclick="return confirm('Are you sure to delete this affiliate campaign?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>

<script type="text/javascript">

var n = 0;

function setAffNetwork(){
    var text = $("#affnetwork option:selected").text();
    var value = $("#affnetwork option:selected").val();
    text = $.trim(text);
    $("#id").val(value);
    $("#aff_network").val(text);
    if(value > 0){ 
        var space = "&nbsp;&nbsp;&nbsp;";
        var del_msg = "'Are you sure to delete this Affiliate network and used this all Affiliate campaigns?'";
        var edit_btn = '<a href="#" onclick="editAffNetwork(this)">Edit</a>';
        var del_btn_link ='<?php echo ADMIN_URL; ?>delete_affiliate_network/'+value+'/';
        $('#buttons').html(space+edit_btn+space+'<a href='+del_btn_link+' onclick="return confirm('+del_msg+');">Delete</a>');
    } else {
        $('#buttons').html('<a href="#" onclick="addAffNetwork(this)">Add new</a>');
    }
}

function addAffNetwork(){
    if(n < 1){
        $('#buttons').hide();
        $('#affnetwork').hide();
        $('#save_aff_camp').hide();
        var action_value = "<?php echo ADMIN_URL; ?>save_affiliate_network/";
        var el_input = '<input size="38" id="aff_network" name="name" type="text">';
        var el_btn ='<button type="submit">Add</button>';
        $('#aff_net').append('<form action="'+action_value+'" method="POST">'+el_input+el_btn+'</form>');
        n += 1;
    }
}

function editAffNetwork(){
    if(n < 1){
        $('#buttons').hide();
        $('#affnetwork').hide();
        $('#save_aff_camp').hide();
        var action_value = "<?php echo ADMIN_URL; ?>save_affiliate_network/";
        var el_input = '<input size="38" id="aff_network" name="name" type="text">';
        var el_hidden = '<input type="hidden" id="network_id" name="id">';
        var el_btn ='<button type="submit">Update</button>';
        $('#aff_net').append('<form action="'+action_value+'" method="POST">'+el_input+el_hidden+el_btn+'</form>');
        n += 1;
        var text = $("#affnetwork option:selected").text();
        var id = $("#id").val();
        text = $.trim(text);
        $("#aff_network").val(text);
        $("#network_id").val(id);
    }
}
</script>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_dashboard.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 : #} ?>
