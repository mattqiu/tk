<?php 
/**
 * 登录视图界面
 */
?>
<script src="<?php echo base_url('themes/mall/js/login_register.js?v=3'); ?>"></script>
<div class="tps_reg ">
    <div class="w1200 pr">
        <div class="tps_reg_box login_ui">
            <h3 class="reg_nav" id="tps_reg_ui"><?php echo lang('tps_account_login');?></h3><!-- 账号登录 -->
            <div class="mail_reg" id="mail_ul">
                <ul>
                    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
                    <li >
                        <input name="loginName" type="text" class="tps_input" placeholder="<?php echo lang('login_name'); ?>"/>
                        <i class="pc-tps">&#xe64d;</i>
                    </li>
                    <li>
                        <input name="pwdOriginal_login" type="password" class="tps_input" placeholder="<?php echo lang('login_pwd'); ?>"/>
                        <i class="pc-tps">&#xe66a;</i>
                        <label style="display: block" class="result_msg "></label>
                    </li>
                     <?php  $conf = config_item('login_captcha');  if($conf['switch'] ==1) {  ?>
                    <li>
                        <input id="captcha" type="text" class="tps_input  yyy" placeholder="<?php echo lang('login_code');  ?>"/>      
                        <img id="img_captcha" basesrc="<?php echo base_url('/login/output_captcha/'); ?>" src="<?php echo base_url('/login/output_captcha')."?t=".mt_rand(1,100); ?>"  onclick="changeLoginCaptcha();" width="120" height="40" >
                        <label class="code_msg"></label>
                    </li>
                      <?php   } else {  ?>
                              <input  id="captcha"   type="hidden" name="" value="NULL"  />
                      <?php    }  ?>
                    
                    <input type="hidden" name="fp" value="<?php if(!empty($fp)) echo $fp; ?>" id="fp">
                    
                    <input name="login_submit" type="button" class="tps_reg_btn" value="<?php echo lang('login'); ?>">

                     <p class="fon mt-10"><?php echo lang('tps_not_members');?>？
                        <a class="c-b" href="<?php echo base_url('register')?>"><?php echo lang('regi_register_now');?></a>
                        <a class="c-b fr" href="<?php echo base_url('forgot_pwd')?>"><?php echo lang('login_forgot_pwd');?></a>
                    </p>
                </ul>
            </div>
        </div>
    </div>
</div>