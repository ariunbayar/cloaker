<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Manage Affiliate Offer: <?php echo $data['campaign']->name ?></h2>
        <?php $offer = $data['offer'] ?>
        <form action="<?php echo sprintf($data['save_url'], $offer->id) ?>" method="POST">
            <input type="hidden" name="offer_id" value="<?php echo $offer->id ?>"/>
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td>Offer name</td>
                    <td>
                        <input size="38" name="name" type="text" value="<?php echo $offer->name ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Affiliate Network (optional)</td>
                    <td>
                        <?php echo select_tag('network_id', $data['network_options'], $offer->network_id, '-- not specified --') ?>
                    </td>
                </tr>
                <tr>
                    <td>Cloaked/Bad URL</td>
                    <td>
                        <?php $val = $offer->getCloakedURL() ?>
                        <input size="38" name="cloaked_url" type="text" value="<?php echo ($val ? $val->url : '') ?>"/>
                        You can include [[subid]] in your url. Example: http://ad.com/?s=[[subid]]&amp;t=yes
                    </td>
                </tr>
                <tr>
                    <td>Cloaking/Good URL</td>
                    <td>
                        <?php $val = $offer->getCloakingURL() ?>
                        <input size="38" name="cloaking_url" type="text" value="<?php echo ($val ? $val->url : '') ?>"/>
                        You can include [[subid]] in your url. Example: http://ad.com/?s=[[subid]]&amp;t=yes
                    </td>
                </tr>
                <tr>
                    <td>Payout</td>
                    <td>
                        <input size="38" name="payout" type="text" value="<?php echo $offer->payout ?>"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Save offer"/></td>
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
        <h2 class="boxtitle">Affiliate Offers</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="1" class="table">
            <tbody>
            <tr class="hd">
                <td>Name</td>
                <td>Network</td>
                <td>URL</td>
                <td>Payout</td>
                <td>Options</td>
            </tr>

            <?php foreach($data['offers'] as $offer): ?>
            <tr>
                <td><?php echo $offer->name ?></td>
                <td>
                    <?php if ($offer->network_id){ ?>
                        <?php echo $offer->getNetwork()->name ?>
                    <?php }else{ ?>
                        --
                    <?php } ?>
                </td>
                <td>
                    <b>Cloaked:</b> <?php echo anchor_tag($offer->getCloakedUrl()->url) ?><br/>
                    <b>Cloaking:</b> <?php echo anchor_tag($offer->getCloakingUrl()->url) ?>
                </td>
                <td>
                    $<?php echo $offer->payout ?>
                </td>
                <td>
                    <a href="<?php echo sprintf($data['delete_url'], $offer->id) ?>"
                       onclick="return confirm('Are you sure to delete this affiliate offer?');">
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

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
