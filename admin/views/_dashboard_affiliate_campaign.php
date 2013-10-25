<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Affiliate Campaign</h2>
        <form action="<?php echo ADMIN_URL; ?>create_aff_campaign/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td>Name</td><td><input size="38" name="name" type="text">
                    <td>Affiliate Network</td>
                    <td>
                        <input size="38" name="affiliate_network" type="text">
                        <input value="Create" type="submit">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        <table width="100%" cellspacing="0" cellpadding="4" border="1" class="table">
            <tbody>
            <tr><td>id</td><td>Name</td><td>Affiliate Network</td></tr>
            <?php foreach($data['aff_campaigns'] as $aff_campaign): ?>
                <tr>
                    <td><?php echo $aff_campaign['id']; ?></td>
                    <td><?php echo $aff_campaign['name']; ?></td>
                    <td><?php echo $aff_campaign['name']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>
