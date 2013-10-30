<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Generate links: <?php echo $data['name']; ?></h2>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                    <tr>
                        <td style="width: 200px;">Generate link for:</td>
                        <td>
                            <label>
                                <input type="radio" name="landing_page" value="0" class="radio" checked/>
                                Direct linking
                            </label>
                            <br/>
                            <label>
                                <input type="radio" name="landing_page" value="0" class="radio"/>
                                Landing page setup (<b style="color:red">NOT IMPLEMENTED</b>)
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Affiliate Network:</td>
                        <td>
                            <?php if (isset($data['network']) && $data['network']){ ?>
                                <?php echo $data['network']->name ?>
                            <?php }else{ ?>
                                [Not specified. You can specify in "Campaign Details" tab]
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Traffic source:</td>
                        <td>
                            <?php echo select_tag('traffic_source_id', $data['traffic_source_options'], '', '-- not specified --') ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button type="submit">Generate tracking link</button></td>
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
        <h2 class="boxtitle">Previously generated links</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
            <thead>
                <tr class="hd">
                    <td>URL</td>
                    <td>Traffic source</td>
                    <td>Type</td>
                    <td>Created at</td>
                    <td>Options</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['trackers'] as $tracker){ ?>
                <tr>
                    <td>
                        <?php $url = substr(ADMIN_URL, 0, -6).$tracker->shortcode.'/' ?>
                        <?php echo anchor_tag($url) ?>
                        <a href="<?php echo ADMIN_URL.'regenerate_url/'.$tracker->id.'/' ?>" class="btn">Regenerate URL</a>
                    </td>
                    <td>
                        <?php if ($tracker->traffic_source_id){ ?>
                            <?php echo $tracker->getTrafficSource()->name ?>
                        <?php }else{ ?>
                            [not specified]
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($tracker->is_landing_page){ ?>
                            Landing page setup
                        <?php }else{ ?>
                            Direct linking
                        <?php } ?>
                    </td>
                    <td><?php echo $tracker->created_at ?></td>
                    <td>
                        <?php $url = ADMIN_URL."delete_tracker/{$tracker->id}/" ?>
                        <a href="<?php echo $url ?>" onclick="return confirm('Are you sure to delete this tracking setup?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>			

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
