<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/otherpages.css?v=2'); ?>" />
<div class="other_wrap">
    <div class="other_page">
        <div class="left_part">
            <div class="top_img"><a href="##"><img src="<?php echo base_url('img/new/contact.png')?>" alt="about"></a></div>
            <div class="m_text">
                <div class="c_text">
                    <h2><?php echo lang('nav_contact_us');?></h2>
                    <p><?php echo lang('company_name');?></p>
                    <p><?php echo lang('address')?><span style="margin-left:15px;"><?php echo lang('company_address');?></span></p>
                    <p><?php echo lang('phone')?><span style="margin-left:15px;"><?php echo lang('us_phone')?><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo lang('zh_phone')?> </span></p>
                    <p><?php echo lang('fax')?><span style="margin-left:15px;">(852)3706-2329</span></p>
                    <p><?php echo lang('email')?><span style="margin-left:15px;">support@tps138.com</span></p>
                </div>
            </div>
        </div>

        <div class="righ_part">
            <div class="pre_nex">
                <!--<a href="./about_team.html" target="_self"><img src="images/contact_pre.png"></a>
                <a href="#"><img src="images/contact_next.png"></a>-->
                <div class="prelink">
                    <a href="<?php echo base_url('team')?>"><img src="<?php echo base_url('img/new/icon1.png')?>" onmousemove="this.src='<?php echo base_url('img/new/icon11.png');?>'" onmouseout="this.src='<?php echo base_url('img/new/icon1.png');?>'"></a>
                    <div class="txtpre"><?php echo lang('mana_team');?></div>
                </div>

                <div class="nextlink">
                    <a href="<?php echo base_url('about')?>" target="_self"><img src="<?php echo base_url('img/new/icon2.png')?>" onmousemove="this.src='<?php echo base_url('img/new/icon22.png');?>'" onmouseout="this.src='<?php echo base_url('img/new/icon2.png');?>'"></a>
                    <div class="txtnext"><?php echo lang('nav_about_us');?></div>
                </div>
            </div>
            <div class="title"><h3><?php echo lang('nav_about_us');?></h3></div>
            <div class="text2"><h3><a href="<?php echo base_url('about')?>" target="_self"><?php echo lang('nav_about_tps');?></a></h3></div>
            <div class="text2"><h3><a href="<?php echo base_url('vision'); ?>" target="_self"><?php echo lang('our_vision');?></a></h3></div>
            <div class="text2"><h3><a href="<?php echo base_url('team')?>" target="_self"><?php echo lang('mana_team');?></a></h3></div>
            <div class="text1"><h3><a href="<?php echo base_url('contact')?>" target="_self"><?php echo lang('nav_contact_us');?></a></h3></div>
        </div>
    </div>
</div>
