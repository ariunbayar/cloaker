<?php ob_start() ?>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Generate links: <?php echo $data['name']; ?></h2>
        <form method="post">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                    <tr>
                        <td style="width: 200px;">Generate link for:</td>
                        <td>
                            <label>
                                <input type="radio" name="landing_page" value="0" class="radio" <?php if ($data['tracker']->is_landing_page != 1){ echo 'checked="checked"'; } ?>/>
                                Direct linking
                            </label>
                            <br/>
                            <label>
                                <input type="radio" name="landing_page" value="1" class="radio" <?php if ($data['tracker']->is_landing_page == 1){ echo 'checked="checked"'; } ?>/>
                                Landing page setup
                            </label>
                            <div class="scenario direct_linking_setup">
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

                            <div class="scenario landing_page_setup">
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
                                    &lt;generated LP code&gt;
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
                    <tr class="landing_page_setup">
                        <td>Landing page URL:</td>
                        <td>
                            <input type="text" name="landing_page_url" size="40" value="<?php if(isset($data['tracker'])){echo $data['tracker']->landing_page_url;}?>"/>
                            Please setup the landing page and enter the URL here
                        </td>
                    </tr>
                    <tr>
                        <td>Traffic source (optional):</td>
                        <td>
                            <?php  $traffic_source_id = (isset($data['tracker']) ? $data['tracker']->traffic_source_id : null);?>
                            <?php echo select_tag('traffic_source_id', $data['traffic_source_options'], $traffic_source_id, '-- not specified --') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Affiliate Network (optional)</td>
                        <td>
                            <?php  $affiliate_network_id = (isset($data['tracker']) ? $data['tracker']->network_id : null);?>
                            <?php echo select_tag('network_id', $data['network_options'], $affiliate_network_id, '-- not specified --') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Affiliate Offers:</td>
                        <td>
                            <span class="direct_linking_setup">
                                You can select only <strong>one offer</strong> for direct linking setup
                            </span>

                            <table width="100%" cellspacing="0" cellpadding="4" border="1" class="table" id="offers">
                                <thead>
                                <tr class="hd">
                                    <td></td>
                                    <td>Name</td>
                                    <td>Affiliate Network</td>
                                    <td>URL</td>
                                    <td>Payout</td>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach($data['offers'] as $offer): ?>
                                <tr class="network_<?php echo $offer->network_id ? $offer->network_id : ''?>">
                                    <td><input type="checkbox" name="offer_ids[]" value="<?php echo $offer->id ?>" class="radio" <?php $tracker_offers = TrackerOffer::getOffersByTrackerId($data['tracker']->id); foreach ($tracker_offers as $tracker_offer) { if ($tracker_offer->id == $offer->id){ echo 'checked="checked"'; }} ?>/></td>
                                    <td><?php echo $offer->name ?></td>
                                    <td>
                                        <?php if ($offer->network_id){ ?>
                                            <?php echo $offer->getNetwork()->name ?>
                                        <?php }else{ ?>
                                            --
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <b>Cloaked:</b> <?php echo $offer->getCloakedUrl()->url ?><br/>
                                        <b>Cloaking:</b> <?php echo $offer->getCloakingUrl()->url ?>
                                    </td>
                                    <td>
                                        $<?php echo $offer->payout ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="Generate tracking link" class="direct_linking_setup"/>
                            <input type="submit" value="Generate link and Landing page codes" class="landing_page_setup"/>
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
        <h2 class="boxtitle">Previously generated links: <?php echo $data['name'] ?></h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
            <thead>
                <tr class="hd">
                    <td>Type</td>
                    <td>Traffic source</td>
                    <td>Affiliate network</td>
                    <td>Offer (payout) (url)</td>
                    <td>Advertising URL</td>
                    <td>Created at</td>
                    <td>Options</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['trackers'] as $tracker){ ?>
                <tr>
                    <td>
                        <?php if ($tracker->is_landing_page){ ?>
                            Landing page setup<br/>
                            <a href="#" class="view_lp_code">
                                view landing page code
                            </a>
                        <?php }else{ ?>
                            Direct linking
                        <?php } ?>
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
                    <?php $url = substr(ADMIN_URL, 0, -6).$tracker->shortcode ?>
                    <td>
                        <ul>
                        <?php foreach ($tracker->getOffers() as $offer){ ?>
                            <li>
                                <?php echo $offer->name.' ($'.$offer->payout.')' ?>
                                <?php if ($tracker->is_landing_page){ ?>
                                    (<?php echo anchor_tag($url.'-'.$offer->id.'/') ?>)
                                <?php } ?>
                            </li>
                        <?php } ?>
                        </ul>
                    </td>
                    <td>
                        <?php echo anchor_tag($tracker->getAdURL()) ?>
                        <a href="<?php echo ADMIN_URL.'regenerate_url/'.$tracker->id.'/' ?>" class="btn">Regenerate Shortcode</a>
                    </td>
                    <td><?php echo $tracker->created_at ?></td>
                    <td>
                        <?php $url = ADMIN_URL."edit_tracker/{$tracker->id}/" ?>
                        <a href="<?php echo $url ?>">Edit</a>
                        &nbsp;&nbsp;&nbsp;
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

<div id="landing_page_code" title="Landing Page code" style="display:none">
    <p>
        Please put following in your landing page source code.
        <div>

<textarea class="code" readonly="readonly" onclick="this.focus();this.select();" style="width: 330px; height: 150px; font-family: 'Lucida Console', Monaco, monospace; font-size: 0.8em;">
<script type="text/javascript">
var s='<?php echo SITE_URL ?>'+location.search+'&y='+new Date().getTime()+'&r='+document.referrer;
document.write(
    '<s'+'cript src="'+s+'" type="text/javascript"></s'+'cript>'
);
</script>
</textarea>

        </div>
        So that we can track that your landing page is viewed.
    </p>
</div>

<?php ob_start() ?>
generateLinksTab();
<?php G::set('main_js', ob_get_clean(), True) ?>

<?php $main_content = ob_get_clean() ?>
<?php require dirname(__FILE__).'/layout_manage.php'; ?>
<?php // {# vim: set ts=4 sw=4 sts=4 fdn=20 tw=140 : #} ?>
