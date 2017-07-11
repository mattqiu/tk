<style>
    select{
        padding: 0px;
    }
</style>
<script src="<?php echo base_url('js/jquery.form.js'); ?>"></script>
<script src="<?php echo base_url('js/register.js?v=10'); ?>"></script>
<div class="login_main" style="height:1040px">
    <div id="loginwrap1"><?php if(!$is_register){?>
        <div class="login_bg" style="height:940px">

            <form name="login_form" action="" autocomplete="off" id="register_form" enctype="multipart/form-data">
            <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
            <div class="maintext">
                    <div class="toptitle" style="margin-top:-10px;"><h3 style="margin-left:8px;">
                            <?php echo lang('nav_register') ?>
                            <span><a href="<?php echo base_url('login') ?>"><?php echo lang('login') ?></a></span></h3>
                    </div>
                    <div class="inputcss">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span> 
                        <input type="text" name="email" id="email_new" notice="<?php echo lang('regi_email') ?>" class="i_css" value="<?php echo lang('regi_email') ?>">
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px;display:block;"></p>
                    </div>
                <div class="inputcss">
                    <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span>
                    <input type="text" name="email_re" id="email_re" notice="<?php echo lang('email_re') ?>" class="i_css" value="<?php echo lang('email_re') ?>">
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                    <p style="margin-left:8px;display:block;"></p>
                </div>
                    <div class="inputcss1" style="margin-top:10px;">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span> 
                        <input type="text" name="pwdOriginalText" notice="<?php echo lang('regi_pwd') ?>" class="i_css" value="<?php echo lang('regi_pwd') ?>">
                        <input type="password" id="pwd" name="pwdOriginal" notice="<?php echo lang('regi_pwd') ?>" class="i_css hidden" value="">
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px;display:block;"></p>
                    </div>
                    <div class="inputcss1" style="margin-top:20px;">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span> 
                        <input type="text" name="pwdOriginal_reText" class="i_css" notice="<?php echo lang('regi_pwd_re') ?>" value="<?php echo lang('regi_pwd_re') ?>">
                        <input type="password" name="pwdOriginal_re" notice="<?php echo lang('regi_pwd_re') ?>" class="i_css hidden" value="">
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px;display:block;"></p>
                    </div>
                    <div class="inputcss1" style="margin-top:10px;">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span> 
                        <input type="text" name="parent_id" class="i_css" value="<?php echo $memberDomainInfo?$memberDomainInfo['id']:lang('regi_parent_id')?>" notice="<?php echo lang('regi_parent_id') ?>"<?php echo $memberDomainInfo?" disabled=disabled":""?> >
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px;display:block;"></p>
                    </div>
                    <div class="inputcss1" style="margin-top:10px;">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span>
                        <select id='con_and_area' autocomplete="off" name='country_id' class="i_css" style='width: 325px;height: 41px;'>
                            <option value=""><?php echo lang('input_country'); ?></option>
                            <?php foreach ($info_contrys as $key => $value) { ?>
                                <option value="<?php echo $key ?>"><?php echo lang($value); ?></option>
                            <?php } ?>
                        </select>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px;display:block;"></p>
                    </div>
                <!--
                    <div class="inputcss1" style="margin-top:10px;">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span>
                        <select autocomplete="off" name='user_rank' class="i_css" style='width: 325px;height: 41px;'>
                            <option value=""><?php echo lang('pls_sel_mem_rank'); ?></option>
                            <?php foreach ($user_ranks as $key => $value) { ?>
                                <option value="<?php echo $key ?>"><?php echo lang($value); ?></option>
                            <?php } ?>
                        </select>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px;display:block;"></p>
                    </div>
                -->
                <div class="inputcss1" style="margin-top:10px;">
                    <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span>
                    <input type="text" name="name" class="i_css" value="<?php echo lang('name')?>" notice="<?php echo lang('name');?>">
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                    <p style="margin-left:8px;display:block;"></p>
                </div>
                <div class="inputcss1" style="margin-top:10px;">
                    <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span>
                    <input type="text" name="mobile" class="i_css" value="<?php echo lang('mobile')?>" notice="<?php echo lang('mobile');?>">
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                    <p style="margin-left:8px;display:block;"></p>
                </div>
                <div class="inputcss1" style="margin-top:10px;height: 75px;">
                    <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span>
                    <textarea class="i_css" cols="37" name="address" rows="2" value="" notice="<?php echo lang('address2').':'.lang('address_alert')?>" style="height: 60px;font-size: 13px;"><?php echo lang('address2').':'.lang('address_alert')?></textarea>
                    <!--<input type="text" name="address" class="i_css" value="<?php /*echo lang('address2')*/?>" notice="<?php /*echo lang('address2');*/?>">-->
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                    <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                    <p style="margin-left:8px;display:block;"></p>
                </div>
                    <div class="inputcss1" style="margin-top:10px;">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span> 
                        <input type="text" style="height:29px;" name="captcha" notice="<?php echo lang('login_code') ?>" class="i_css1" value="<?php echo lang('login_code') ?>">
                        <div style="width:90px;height:40px;position:relative;top:-32px; left:140px;">
                            <img id="captcha" basesrc="<?php echo base_url('login/captcha'); ?>" src="<?php echo base_url('login/captcha'); ?>" onclick="changeCaptcha();" />                        </div>
                        <span style="position:relative;top:-75px;left:240px;font-size:12px;"><a href="javascript:void(0);" onclick="changeCaptcha();" ><?php echo lang('login_change_captcha') ?></a></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px; position:relative; top:-60px;"></p>
                    </div>
                    <div class="inputcss1" style="margin-top:10px;height:70px;color: #999;font-size: 15px;">
                        <span style="width:5px; height:4px;"><img src="<?php echo base_url('img/new/reg_icon.jpg') ?>" width="5" height="4"></span>
                        <label>
                        <input type="checkbox" name="disclaimer"  value="checked">
                        <?php echo lang('register_disclaimer')?>
                        </label>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"></span>
                        <p style="margin-left:8px;display:block;"></p>
                    </div>
                    <input type="hidden" value="<?php echo lang('alert_register')?>" id="alert_register">
                    <input type="hidden" value="<?php echo lang('alert_email')?>" id="alert_email">
                    <input type="hidden" value="<?php echo lang('name_alert')?>" id="name_alert">
                    <input type="hidden" value="<?php echo lang('address_alert')?>" id="address_alert">
                    <div class="subcss" style="margin-left:8px;margin-top:10px;">
                        <input autocomplete="off" type="button" name="submit" class="s_css" value="<?php echo lang('regi_register_now') ?>">
                    </div>
                    <div  class="subcss" style="text-align: left;color: #e35f40;margin-left: 10px;">

                    </div>
<!--                    <div class="bottext" style="margin-top:10px;margin-left:8px;">
                        <h3 class="cd-popup-trigger"><span style="position:relative;top:2px;"><input type="checkbox" name="agreed"></span> <?php echo lang('regi_accept_text') ?></h3>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p style="margin-left:8px;display:block;color: red;text-align: left;font-size: 12px;font-family: Arial,Helvetica,sans-serif;color: #EA5A5A;margin-top: 5px;"></p>
                    </div>-->
            </div>
            </form>

        </div>
        <?php }else{?>
            <div style="color: #ea5a5a;padding-top: 6em;"><h2><?php echo lang('off_register')?></h2></div>
        <?php }?>
        </div>
    </div>


