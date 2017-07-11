<script src="<?php echo base_url('js/register.js?v=15'); ?>"></script>
<div class="bj_zhuti">
	<div class="container clear">
		<div class="Register_z clear">
			<h3><?php echo lang('store_enroll')?></h3>
			<div class="Enrollment clear">
				<?php if(!$is_register){?>
				<div  class="col-md-7">
					<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
					<div class="item-ifo modal_main"> <em>*</em>
						<label>
						<input id="" value="1" type="radio" class="" name="reg_type" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_email') ?>">
						<?php echo lang('is_account')?>
						</label>

						<label style="margin-left:20px;">
						<input id="" checked value="0" type="radio" class="" name="reg_type" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_email') ?>">
						<?php echo lang('no_account')?>
						</label>
					</div>
					<div class="item-ifo height50"> <em>*</em>
						<input id="email" type="text" class="itxt" name="email" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_email') ?>">
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo height50"> <em>*</em>
						<input type="text" id="email_new" class="itxt" name="email_re" tabindex="1" autocomplete="off" placeholder="<?php echo lang('email_re') ?>">
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo height50"> <em>*</em>
						<input type="password" id="pwd" class="itxt" name="pwdOriginal" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_pwd') ?>">
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo height50"> <em>*</em>
						<input type="password" id="" class="itxt" name="pwdOriginal_re" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_pwd_re') ?>">
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo height50"> <em>*</em>
						<input type="text" id="" class="itxt" value="<?php echo $memberDomainInfo?$memberDomainInfo['id']:lang('regi_parent_id')?>" name="parent_id" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_pwd_re') ?>" <?php echo $memberDomainInfo?" disabled=disabled":""?>>
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>

				</div>
				<div class="col-md-5">
					<div class="item-ifo height50"> <em>*</em>
						<select id='con_and_area' autocomplete="off" name='country_id' class="guojia" style='width: 238px;height: 41px;color:#666'>
							<option value=""><?php echo lang('input_country'); ?></option>
							<?php foreach ($info_contrys as $key => $value) { ?>
								<option value="<?php echo $key ?>"><?php echo lang($value); ?></option>
							<?php } ?>
						</select>
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo height50"> <em>*</em>
						<input id="" type="text" class="itxt" name="name" tabindex="1" autocomplete="off" placeholder="<?php echo lang('name')?>">
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo height50"> <em>*</em>
						<input id="" type="text" class="itxt" name="mobile" tabindex="1" autocomplete="off" placeholder="<?php echo lang('mobile')?>">
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo i_wbk" style="height: 105px;"> <em>*</em>
						<textarea class="itxt" style="width: 220px; height: 70px;font-size: 13px;" cols="33.5" name="address" rows="2" value="" placeholder="<?php echo lang('address2').':'.lang('address_alert')?>"></textarea>
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
						<p class="hidden korea_address">주소는 반드시 한글로 입력해야 합니다</p>
					</div>
					<!-- <div class="item-ifo  height50 i_yzm"> <em>*</em>
						<input type="text" name="captcha" class="itxt" placeholder="<?php echo lang('login_code') ?>">
						<img id="captcha" basesrc="<?php echo base_url('/login/captcha')?>" src="<?php echo base_url('/login/captcha')?>" onclick="changeCaptcha();" />
						<a href="javascript:void(0);" onclick="changeCaptcha();" ><?php echo lang('login_change_captcha') ?></a>
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div> -->
				</div>
				<div class="col-md-12">
					<div class="item-ifo height50"><em>*</em>
						<label>
						<input type="checkbox" name="disclaimer" value="1" checked="checked">
						<?php echo lang('register_disclaimer')?>
						</label>
						<span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
						<p></p>
					</div>
					<div class="item-ifo">
						<input type="button" autocomplete="off" name="submit" class="btn-Login" id="" value="<?php echo lang('regi_register_now') ?>" tabindex="8">
					</div>
				</div>
				<?php }else{?>
					<div class="store_enroll_alert"><h2><?php echo lang('off_register')?></h2></div>
				<?php }?>
			</div>
		</div>
	</div>


