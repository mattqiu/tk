<div id="total_bg">
    <div id="pagewrap_total">
        <div id="total">
            <div class="text1"><?php echo lang('introduce'); ?></div>
            <!--<div class="text2"><?php /*echo lang('introduce_content'); */?></div>-->
        </div>
    </div>
</div>

<div id="pagewrap_footer">
    <div id="footer">
        <div class="f_img_txt1">
            <h4><?php echo lang('connect_us'); ?></h4>
            <div class="img1_list">
                <ul>
                    <li>
                        <img src="<?php echo base_url('img/new/footer1.png');?>" onMouseMove="this.src='<?php echo base_url('img/new/footer11.png');?>'" onMouseOut="this.src='<?php echo base_url('img/new/footer1.png');?>'">
                        <img src="<?php echo base_url('img/new/footer2.png');?>" onMouseMove="this.src='<?php echo base_url('img/new/footer22.png');?>'" onMouseOut="this.src='<?php echo base_url('img/new/footer2.png');?>'">
                        <img src="<?php echo base_url('img/new/footer3.png');?>" onMouseMove="this.src='<?php echo base_url('img/new/footer33.png');?>'" onMouseOut="this.src='<?php echo base_url('img/new/footer3.png');?>'">
                        <img src="<?php echo base_url('img/new/footer4.png');?>" onMouseMove="this.src='<?php echo base_url('img/new/footer44.png');?>'" onMouseOut="this.src='<?php echo base_url('img/new/footer4.png');?>'">
                    </li>
                    <li>
                        <img src="<?php echo base_url('img/new/footer5.png');?>" onMouseMove="this.src='<?php echo base_url('img/new/footer55.png');?>'" onMouseOut="this.src='<?php echo base_url('img/new/footer5.png');?>'">
                        <img src="<?php echo base_url('img/new/footer6.png');?>" onMouseMove="this.src='<?php echo base_url('img/new/footer66.png');?>'" onMouseOut="this.src='<?php echo base_url('img/new/footer6.png');?>'">
                        <img src="<?php echo base_url('img/new/footer7.png');?>" onMouseMove="this.src='<?php echo base_url('img/new/footer77.png');?>'" onMouseOut="this.src='<?php echo base_url('img/new/footer7.png');?>'">
                    </li>
                </ul>
            </div>
        </div>

        <div class="f_img_line"><img src="<?php echo base_url('img/new/lines.png');?>"></div>

        <div class="f_img_txt2">
            <h4><?php echo lang('nav_about_tps'); ?></h4>
            <div class="img2_list">
                <ul>
                    <li><a href="<?php echo base_url('about')?>"><?php echo lang('who_are'); ?></a></li>
                    <li><a href="<?php echo base_url('team')?>"><?php echo lang('mana_team'); ?></a></li>
                    <li><a href="<?php echo base_url('vision')?>"><?php echo lang('our_vision'); ?></a></li>
                </ul>
            </div>
        </div>

        <div class="f_img_line"><img src="<?php echo base_url('img/new/lines.png');?>"></div>

        <div class="f_img_txt3">
            <h4><?php echo lang('nav_opportunity'); ?></h4>
            <div class="img3_list">
                <ul>
                    <li><a href="<?php echo base_url('why_tps')?>"><?php echo lang('why_tps'); ?></a></li>
                    <li><a href="<?php echo base_url('advantage')?>"><?php echo lang('cme_ade'); ?></a></li>
                    <li><a href="<?php echo base_url('plan')?>"><?php echo lang('cmn_plan'); ?></a></li>
                </ul>
            </div>
        </div>

        <div class="f_img_line"><img src="<?php echo base_url('img/new/lines.png');?>"></div>

        <div class="f_img_txt4">
            <h4><?php echo lang('terms_conditions'); ?></h4>
            <div class="img4_list">
                <ul>
                    <li><a href="<?php echo base_url('ecosko')?>"><?php echo lang('disclaimer'); ?></a></li>
                   <!-- <li><?php /*echo lang('about_ecosko'); */?></li>-->
                    <!--<li><?php /*echo lang('secure_payment'); */?></li>-->
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="footer_bot" >
    <div id="footer_bot2" <?php echo $curLanguage != 'english' && 1==2 ? 'style="height: 109px;"':''?>>
        <div style="margin-bottom: 20px;"><?php echo lang('footer'); ?></div>
        <?php if($curLanguage != 'english' && 1==2){?>
            <script id="ebsgovicon" src="https://cert.ebs.gov.cn/govicon.js?id=5fd7245a-b6e0-45c8-bf65-b66c7ab2c1a5&width=128&height=52&type=2" type="text/javascript" charset="utf-8"></script>
        <?php }?>
    </div></div>

</body>
</html>
<script src="<?php echo base_url('js/base.js?v=12'); ?>"></script>