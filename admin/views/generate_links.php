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
                                <input type="radio" name="landing_page" value="0" class="radio"/>
                                Direct linking
                            </label>
                            <br/>
                            <label>
                                <input type="radio" name="landing_page" value="1" class="radio"/>
                                Landing page setup (<b style="color:red">NOT IMPLEMENTED</b>)
                            </label>
                            <div class="scenario" id="direct_linking_scenario" style="display:none">
                                <div class="box">
                                    <div class="title">Advertisement</div>
                                    <div class="headline">Next generation walkman</div>
                                    <div class="description">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    </div>
                                    <div class="display_url">http://cloaker.com/abc123/</div>
                                </div>

                                <div class="arrow">
                                    http://cloaker.com/abc123/
                                    &rarr;
                                </div>

                                <div class="box">
                                    <div class="title">Redirector</div>
                                    Cloaker redirects directly<br/>
                                    to product page
                                </div>

                                <div class="arrow">
                                    http://apple.com/ipod/
                                    &rarr;
                                </div>

                                <div class="box">
                                    <div class="title">Product page</div>
                                    product: iPod<br/>
                                    price: $399<br/>
                                    tech spec: 80G
                                    <div class="headline">order &gt;&gt;&gt;</div>
                                </div>
                            </div>

                            <div class="scenario" id="landing_page_scenario" style="display:none">
                                <div class="box">
                                    <div class="title">Advertisement</div>
                                    <div class="headline">Next generation walkman</div>
                                    <div class="description">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    </div>
                                    <div class="display_url">http://cloaker.com/abc123/</div>
                                </div>

                                <div class="arrow">
                                    http://.../offers.html?sc=abc123 &rarr;
                                </div>

                                <div class="box">
                                    <div class="title">Landing page</div>
                                    <div class="headline">iPod (offer 1)</div>
                                    <div class="description">http://cloaker.com/abc123-1/</div>
                                    <div class="headline">iPod Nano (offer 2)</div>
                                    <div class="description">http://cloaker.com/abc123-2/</div>
                                </div>
                                <div class="arrow">
                                    (selects 2nd offer)<br/>
                                    http://cloaker.com/abc123-2/ &rarr;
                                </div>

                                <div class="box">
                                    <div class="title">Redirector</div>
                                    Cloaker redirects directly<br/>
                                    to product page
                                </div>

                                <div class="arrow">
                                    http://apple.com/ipod-nano/ &rarr;
                                </div>

                                <div class="box">
                                    <div class="title">Product page</div>
                                    product: iPod Nano<br/>
                                    price: $199<br/>
                                    tech spec: 20G
                                    <div class="headline">order &gt;&gt;&gt;</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Affiliate Network:</td>
                        <td>
                            <?php echo select_tag('network_id', $data['network_options'], '', '-- not specified --') ?>
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
                    <td>Type</td>
                    <td>URL</td>
                    <td>Traffic source</td>
                    <td>Affiliate network</td>
                    <td>Created at</td>
                    <td>Options</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['trackers'] as $tracker){ ?>
                <tr>
                    <td>
                        <?php if ($tracker->is_landing_page){ ?>
                            Landing page setup
                        <?php }else{ ?>
                            Direct linking
                        <?php } ?>
                    </td>
                    <td>
                        <?php $url = substr(ADMIN_URL, 0, -6).$tracker->shortcode.'/' ?>
                        <?php echo anchor_tag($url) ?>
                        <a href="<?php echo ADMIN_URL.'regenerate_url/'.$tracker->id.'/' ?>" class="btn">Regenerate URL</a>
                    </td>
                    <td>
                        <?php if ($tracker->traffic_source_id){ ?>
                            <?php echo $tracker->getTrafficSource()->name ?>
                        <?php }else{ ?>
                        --
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($tracker->network_id){ ?>
                            <?php echo $tracker->getNetwork()->name ?>
                        <?php }else{ ?>
                        --
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

<?php ob_start() ?>
$('input[name=landing_page]').change(function(){
    var is_landing_page = ($(this).val() == 1);
    $('#direct_linking_scenario').toggle(!is_landing_page);
    $('#landing_page_scenario').toggle(is_landing_page);
});
<?php G::set('main_js', ob_get_clean(), True) ?>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
