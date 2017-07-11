<div class="container-fluid">
  <div class="row-fluid">
    <div class="dialog span4">
      <div class="block">
		  <!--
        <div class="block-heading"><?php echo lang('login_welcome');?> <a href="<?php echo base_url('admin/register')?>" class="pull-right"><?php echo lang('regi_register_now');?></a> </div>
        -->
        <div class="block-body">
          <form id="login_form" method="post">
            <div>
              <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
              <label><?php echo lang('user_name');?></label>
              <input type="text" name="user_name" class="span12" autocomplete="off">
              <label><?php echo lang('login_pwd');?></label>
              <input type="password" name="pwd" class="span12" autocomplete="off">
              <?php $config_arr = config_item("admin_login_captcha");
                    if($config_arr['switch'] == 1) {  //为1打开登陆图片验证码 ?>
               <div>
                <input style="width: 150px;height: 32px;margin-top: 5px;" type="text" name="captcha_code" class="span12" autocomplete="off" placeholder="<?php echo lang('login_code');?>">
                <img   style="width: 100px;height: 32px;margin-top: -6px;" class="" onclick="this.src = this.src+'?v='+Math.random()*100" src="<?php echo base_url('admin/sign_in/output_captcha'); ?>" />
              </div>
                    <?php  } ?>
              <p id="login_msg" class="remember-me error_msg"></p>
            </div>
            <div>
              <input type="button" autocomplete="false" id="login_submit" class="btn btn-primary pull-right" value="<?php echo lang('login');?>">
              </a> </div>
            <!--<label class="remember-me"><input type="checkbox"> Remember me</label>-->
            <div class="clearfix"></div>
          </form>
        </div>
      </div>
      <!--<p class="pull-right" style=""><a href="#" target="blank">Theme by Portnine</a></p>--> 
      
      <!--<p><a href="reset-password.html">Forgot your password?</a></p>--> 
    </div>
  </div>
</div>
