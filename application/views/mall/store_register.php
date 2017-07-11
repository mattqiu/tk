<script src="<?php echo base_url('themes/mall/js/enroll.js?v=4.2'); ?>"></script>
<div class="w1200">
	<div class="Register_z clear">
		<h3><?php echo lang('store_enroll')?></h3>
		<div class="Enrollment clear">
			
		<?php if(!$is_register){?>
			<div  class="col-md-7">
				
				<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
				
				<div class="item-ifo modal_main" style="height: 25px;"> <em>*</em>
					<label>
						<input id="" name="reg_type"   type="radio" class="" value="1" tabindex="1" autocomplete="off"><?php echo lang('is_account')?>
					</label>
					<label style="margin-left:20px;">
						<input id="" name="reg_type"   type="radio" class="" value="0" tabindex="1" autocomplete="off" checked><?php echo lang('no_account')?>
					</label>
				</div>

				<!-- 电子邮箱 手机号 -->
				<div class="item-ifo height50" id="youx"> <em>*</em>
					<input id="emailmobile" name="emailmobile"    type="text" class="itxt" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_email') ?>">
					<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
					<p></p>
				</div>
                
				<!-- 密码 -->
				<div class="item-ifo height50"> <em>*</em>
					<input id="pwd" name="pwd"   type="password" class="itxt"  tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_pwd') ?>">
					<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
					<p></p>
				</div>
				
				<!-- 邀请人ID -->
				<div class="item-ifo height50"> <em>*</em>
					<input id="" name="parent_id"   type="text" class="itxt" value="<?php echo $memberDomainInfo?$memberDomainInfo['id']:lang('regi_parent_id')?>"  tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_pwd_re') ?>" <?php echo $memberDomainInfo?" disabled=disabled":""?>>
					<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
					<p></p>
				</div>

				<!-- 验证码 -->
				<div class="item-ifo height50"><em>*</em>
					<input id="" name="captcha"   type="text" class="itxt" style="" tabindex="1" autocomplete="off" placeholder="<?php echo lang('captcha')?>">
					<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
					<p style="10px"></p>
				</div>
				
				<!-- 获取验证码按钮 -->
				<input id="" name=""   type="button" class="btn btn-white btn-weak get_captcha " autocomplete="off" value="<?php echo lang('get_captcha')?>" style="margin-left: 15px;">
			
			</div>

			<div class="col-md-12">
				
				<!-- 协议 -->
				<div class="item-ifo" style="height: 25px;"><em>*</em>
					<label>
						<input id="" name="disclaimer" type="checkbox" value="1" checked="checked"><?php echo lang('register_disclaimer')?>
					</label>
					<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
					<p></p>
				</div>

				<!-- 马上注册按钮 -->
				<div class="item-ifo">
					<input id="" name="submit" type="button" class="btn-Login" style="margin-left: 15px;" autocomplete="off" value="<?php echo lang('regi_register_now') ?>" tabindex="8">
					<input id="resend_captcha" name="" type="hidden" value="<?php echo lang('resend_captcha')?>" >
					<input id="get_captcha" name="" type="hidden" value="<?php echo lang('get_captcha')?>" >
				</div>
			</div>

		<?php }else{?>
			<div class="store_enroll_alert">
				<h2><?php echo lang('off_register')?></h2>
			</div>
		<?php }?>
		</div>
	</div>
</div>

<style>
	.item-ifo p {margin: 1% 0 0 16px; }
</style>


