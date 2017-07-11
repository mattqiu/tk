<?php if($idCard['id_card_scan'] || $idCard['id_card_scan_back']){?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.min.js?v=3'); ?>"></script>
<?php }?>
<div class="well">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab"><?php echo lang('profile')?></a></li>
    </ul>
	<input type="hidden" value="<?php echo lang('resend_captcha')?>" id="resend_captcha">
	<input type="hidden" value="<?php echo lang('get_captcha')?>" id="get_captcha">
    <div id="myTabContent" class="tab-content">

        <div class="tab-pane active in" id="home">
            <div class="alert hidden account_info">
                <strong></strong>
            </div>
            <form id="account_info" autocomplete="off">
                <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
                <table class="account_info_tb">
                    <tr>
                        <td class="tab_name"><?php echo lang('id') ?></td>
                        <td><?php echo $user['id'] ?></td>
                    </tr>
                     <tr>
                        <td class="tab_name"><?php echo lang('regi_parent_id') ?>:</td>
                        <td><?php echo $user['parent_id'] ?></td>
                    </tr>

                    <tr>
                        <td class="tab_name"><?php echo lang('cur_level') ?>:</td>
                        <td>
                            <div class="cur <?php  echo $levelInfo['class'] == 'free' ? 'level_background' : ''?>" style="display: inline-block;">
                                    <span class="level <?php echo $levelInfo['class']; ?>" style="display: inline-block;<?php  echo $levelInfo['class'] != 'free' ? 'margin:0;' : ''?>">
                                        <p class="name"><?php echo $levelInfo['name']; ?></p>
                                    </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tab_name"><?php echo lang('email') ?>:</td>
                        <td>
							<?php echo $user['email']?$user['email']:''?>
							<?php if($user['is_verified_email'] == 1 && $user['email']){?>
								<strong style="color: #008000"><?php echo lang('is_binding')?></strong>
							<?php }else{?>
								<a href="javaScript:binding_email()"><?php echo lang('binding_email')?></a>
							<?php }?>
						</td>
                    </tr>
                    <tr>
                        <td class="tab_name"><?php echo lang('country') ?>:</td>
                        <td>
                            <?php foreach (config_item('countrys_and_areas') as $key => $value) { ?>
                              <?php if($user['country_id']==$key){ echo lang($value); } ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="tab_name"><?php echo lang('realName') ?>:	</td>
                        <td id="real_name">
                            <?php  echo $user['name']?$user['name']:'' ?>
                           <!-- <input type="text" name="name" id="name_input" value="<?php echo $user['name']?$user['name']:''?>" class="input-xlarge"<?php echo $idCard['check_status']?' disabled':''?>>
                            <span id="msg_name" style="color: #ff0000;"></span>-->
                        </td>
                    </tr>
                    <tr>
                        <td class="tab_name"><?php echo lang('user_address') ?>:</td>
                        <td>
                            <?php  if($idCard['check_status'] == 0  || ($user['country_id'] == '3')){?>
                            <input type="text" name="name" id="addr_input" value="<?php echo $user['address']?>" class="input-xlarge">
                            <span id="msg_addr" style="color: #ff0000;"></span>
							<?php }else{?>
								<?php echo $user['address']?>
							<?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td class="tab_name"><?php echo lang('person_id_card_num') ?>:</td>
                        <td>
                            <?php if (!$idCard['check_status'] && !$no_update) { ?>
                                <input type="text" id="id_card_num_input" name="id_card_num" value="<?php echo $idCard['id_card_num'] ? $idCard['id_card_num'] : ''; ?>" class="input-xlarge">
                                <span class="id_card_num" style="color: #ff0000;"></span>
                            <?php } else { ?>
                                <?php echo $idCard['id_card_num'] ? $idCard['id_card_num'] : ''; ?>
                            <?php } ?>
                            <span id="msg_id_card_num" style="color: #ff0000;"></span>
                        </td>
                    </tr>
					<?php if ($idCard['check_status'] != '2'){ ?>
                    <tr>
                                <td class="tab_name"><?php echo lang('id_scan') ?>:<?php if(!$idCard['check_status']){ ?><br/>(<?php echo lang('id_scan_condition'); ?>)<?php }?></td>
                                <td>
                         <div id="scan_card">
                                    <table>
                                    <tr>
                                    
                                        <td>
                                            <?php if ($idCard['check_status'] == 0) { ?>
                                                <div class="upload_btn" id="upload_btn" >
                                                    <span><?php echo lang('add_id_scan'); ?></span>
                                                    <input id="fileupload" type="file" name="id_card_scan_file" autocomplete="off"  onchange="PreviewImage(this,'scan_face','facePreview')">
                                                </div>
                                                <img id="upload_1_loading" style="display:none" src="<?php echo base_url('img/new/loading-min.gif'); ?>">
                                            <?php } ?>
                                            <div id="showimg" style="display: none">
                                                    <a  href="javascript:void(0);" class="example-image-link">
                                                        <div id="facePreview"><img  id="scan_face" alt="not exist" src="" class="example-image"></div>
                                                    </a>
                                                <a style="display: block;text-align: center; font-size: 16px;" href='javascript:void(0)' id='' onclick='delScanImg(this)'>删除</a>
                                            </div>
                                            <div class="files" style="display: inline;"></div><span class="txt"style="color: #ff0000;"></span>
                                        </td>
                                        <td>
                                           
                                                <div class="upload_btn" id="upload_btn2">
                                                    <span><?php echo lang('add_id_scan_back'); ?></span>
                                                    <input id="fileupload2" type="file" name="id_card_scan_file_back" autocomplete="off"  onchange="PreviewImage(this,'scan_back','backPreview')" >
                                                </div>
                                                <img id="upload_2_loading" style="display:none" src="<?php echo base_url('img/new/loading-min.gif'); ?>">
                                            <div id="showimg2" style="display: none"> 
                                                    <a  href="javascript:void(0);" class="example-image-link">
                                                        <div id="backPreview">
                                                            <img  id="scan_back" alt="" src="" class="example-image"></div>
                                                    </a>
                                                <a  style="display: block;text-align: center; font-size: 16px;" href='javascript:void(0)' id='' onclick='delScanImg2(this)'>删除</a>
                                            </div>
                                            <div class="files2" style="display: inline;"></div><span class="txt"style="color: #ff0000;"></span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" id="cardVerifyBtn" class="btn btn-primary accountInfoVeryBtn"><?php echo lang('pic_confirm') ?></a>
<!--                                                <span class="msg" id="id_card_error"></span>-->
                                        </td>
                                        
                                        <td>
                                            <span id="msg_id_card_scan" style="color: #ff0000;"></span>
                                        </td>

                                    </tr>
                                    </table></div>
                                </td>
                            </tr>
					<?php } ?>
                            <tr>
                                <td class="tab_name"><?php echo lang('id_scan_status') ?>:</td>
                                <td>
                                    <?php if (!$idCard['check_status']) { ?>
                                        <div class="msg">
                                            <?php if ($idCard['check_info']) { ?>
                                                <?php echo lang('validate_failure'); ?><?php echo $idCard['check_info']; ?> (<?php echo date('Y-m-d H:i:s', $idCard['check_time']); ?>)
                                            <?php } else { ?>
                                                <?php echo lang('no_validate'); ?>
                                            <?php } ?>
                                        </div>
                                    <?php } else if ($idCard['check_status'] == 2) { ?>
                                        <strong class="text-success"><?php echo lang('validate_success'); ?></strong>
                                    <?php } else { ?>
                                        <strong class="text-error" id="id_card_info"><?php echo lang('verify_info'); ?></strong>
                                    <?php } ?>
                                </td>
                    </tr>
                    <tr id="phone_yz">
                        <td class="tab_name"><?php echo lang('mobile') ?>:</td>
                        <td>

							<?php if($user['country_id'] != 1){?>
								<input type="text" id="mobile_input" name="mobile" value="<?php echo $user['mobile']?$user['mobile']:'' ?>" class="input-xlarge">
							<?php }else{?>
								<?php echo $user['mobile'] ? $user['mobile'] : '';?>
								<?php if($user['is_verified_mobile'] == 1 && $user['mobile']){?>
									<strong style="color: #008000"><?php echo lang('is_binding')?></strong>
								<?php }else{?>
									<a href="javaScript:binding_info()"><?php echo lang('binding_mobile')?></a>
								<?php }?>
							<?php }?>
							<?php if($user['country_id'] == 1){ ?>
								<a href="javaScript:modify_mobile_number()" class="modify_mobile_number"><?php echo lang('modify_mobile_number');?></a>
							<?php } ?>
						</td>
                        <td><span id="mobileMsg" style="color: #ff0000;margin-left: 5px;"></span></td>
                    </tr>
                    <?php if ($user['country_id'] == 1 && 1==2) { ?>
                        <tr>
                                <td class="tab_name"><?php echo lang('type_alipay') ?>:(<?php echo lang('not_require')?>)</td>
                                <td><input type="text" name='alipay_account' value="<?php echo $user['alipay_account'] ?>" class="input-xlarge"></td>
                        </tr>
                    <?php }?>
					<?php if($curLanguage == 'english' && $user['country_id'] == 2){ ?>
						<tr>
							<td class="tab_name">Maxie Mobile:</td>
							<td>
								<p>Great news for USA members. You now can apply for Maxie Mobile eWallet/Mastercard Debit Card for you to withdraw commissions.</p>

								<p>Please note that only US members can use this service for now!</p>

								<p>Before you click on the link, make sure you have your SSN, bank debit card and US address/Zipcode ready.</p>

								<p>Please click on the following link belowe to register & apply for a Mastercard Debit Card for you to withdraw commissions from your back office.</p>
								</p><a href="http://www.paymentsl.com/apps/forms/tpsv2/?ID_tps=<?php echo $user['id']?>" target="_blank">(http://www.paymentsl.com/apps/forms/tpsv2/?ID_tps=<?php echo $user['id']?>)</a></p>

								<p>When you click on the above link, you will be redirected to TPS Debit Card Registration form.</p>

								<p>Please note that there is an application fee of $20 for Maxie Mobile Debit Card which is none refundable. After the $20 application fee is paid, you will then be sent to the Maxie Mobile registration page to fill out your personal info so that Maxie Mobile can review, approve and activate your Maxie Mobile ewallet account.</p>
							</td>
							<td>

							</td>
						</tr>
					<?php }?>
<!--                    <tr>
                        <td class="tab_name"><?php /*echo lang('ewallet_name') */?>:</td>
                        <?php /*if(!$user['ewallet_name']){*/?>
                        <td>
                            <a href="Javascript:apply_ewallet();" class="alert alert-success"><?php /*echo lang('ewallet_apply');*/?></a>
                            <span id="apply_ewallet_msg" class="msg"></span>
                        </td>

                        <?php /*}else{*/?>
                        <td><input type="text" value="<?php /*echo $user['ewallet_name'] */?>" class="input-xlarge" disabled></td>
                        <?php /*}*/?>
                    </tr>-->

                    <tr id="go_modify_PIN">

                        <td class="tab_name"><?php echo lang('funds_pwd') ?>:</td>
                        <td id="set_take_cash_pwd_td" class="<?php if($user['pwd_take_out_cash']){ echo 'hide'; }?>">
                            <a href="Javascript:set_take_cash_pwd();" class="alert alert-success"><?php echo lang('no_take_cash_pwd');?></a>
                            <span id="apply_ewallet_msg" class="msg"></span>
                        </td>
                        <td id="modify_take_cash_pwd_td" class="<?php if(!$user['pwd_take_out_cash']){ echo 'hide'; }?>">
                            <a href="Javascript:modify_take_cash_pwd();" class="alert alert-success"><?php echo lang('had_take_cash_pwd');?></a>
                            <a href="<?php echo base_url('ucenter/change_funds_pwd')?>"><?php echo lang('forgot_funds_pwd');?></a>
                            <div id="modifyPIN"></div>
                            <script>
                                var index = location.href.indexOf('#');
                                var lc = location.href.substr(index+1,9);
                                if(index != -1 && lc == 'modifyPIN'){
                                    $('#go_modify_PIN').css('border','1px solid red');
                                }else if(index != -1 && lc == 'phone'){
                                    $('#phone_yz').css('border','1px solid red');
                                }
                            </script>
                        </td>
                        <td id="cash_pwd_msg" class="msg"></td>

                    </tr>

                   <!-- <tr>
						<td class="tab_name"><?php echo lang('month_upgrade_log_label')?>:</td>
						<td>
							<?php
							$count_monthLevel=count($levelChangeLog['monthLevel']);
							foreach($levelChangeLog['monthLevel'] as $key=>$item) {
								?>

								<div class="box"><span style="color:#f98635; font-weight:bold;"><?php echo $item['create_time']?></span> <?php if($item['old_level'] > $item['new_level']) echo lang('upgrade'); else echo lang('downgrade');echo lang('month_upgrade_from');?><span style="color:#f98635;"><?php echo lang($item['old_level_desc'])?></span><?php if($item['old_level'] > $item['new_level']) echo lang('upgrade_to'); else echo lang('downgrade_to');?><span style="color:#f98635;"><?php echo lang($item['new_level_desc'])?></span></div>
								<?php if(($key+1) < $count_monthLevel) { ?>
									<span class="arrow arrow-right"></span>
								<?php }?>
							<?php }?>

						</td>
					</tr> -->
                    <tr>
                        <td class="tab_name"><?php echo lang('shop_upgrade_log_label')?>:</td>
                        <td>
                     
                         <?php 
							$count_shopLevel=count($levelChangeLog['shopLevel']);
							foreach($levelChangeLog['shopLevel'] as $key=>$item) {
						?>
                 
                            <div class="box"><span style="color:#f98635; font-weight:bold;"><?php echo $item['create_time']?></span> <?php if($item['old_level'] > $item['new_level'] || ($item['old_level']==4 && $item['new_level'] == 5)) echo lang('upgrade'); else echo lang('downgrade');echo lang('shop_upgrade_from');?><span style="color:#f98635;"><?php echo lang($item['old_level_desc'])?></span><?php if($item['old_level'] > $item['new_level']|| ($item['old_level']==4 && $item['new_level'] == 5)) echo lang('upgrade_to'); else echo lang('downgrade_to');?><span style="color:#f98635;"><?php echo lang($item['new_level_desc'])?></span></div>
                            <?php if(($key+1) < $count_shopLevel) { ?>
                    		<span class="arrow arrow-right"></span>
                    		<?php }?>
                        <?php }?>  
                        </td>
                    </tr>
                    <?php if($is_delete == 1) { ?>
					<tr>
						<td class="tab_name"><?php echo lang('shop_management') ?>:</td>
						<td><a class="btn btn-primary" onclick="del_shop('<?php echo $user['id'] ?>');" href="javascript:"><?php echo lang('del_shop') ?></a></td>
					</tr>
                    <?php }?>
                </table>
            </form>
        </div>
    </div>
</div>

<!-- 申请ewallet弹层
<div id="ewallet_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo lang('ewallet_apply')?></h3>
    </div>
    <div class="modal-body">
            <table class="enable_level_tb">
                <tr>
                    <td colspan="2">
                        <strong class="text-success">
                            <?php echo $ewallet_email_tip; ?>
                        </strong>

                    </td>
                </tr>
                <tr>
                    <td class="title"><a href="javascript:void(0);" data-original-title="<?php echo lang('login_use'); ?>" rel="tooltip"><?php echo lang('ewallet_name'); ?>:</a></td>
                    <td  style="height: 100px;">
                        <input id="ewallet_name" autocomplete="off" type="text" value="<?php echo $user['email']?>" name="ewallet_name">
                    </td>
                </tr>
                <tr>
                    <td class="title"><a href="javascript:void(0);" data-placement="bottom" data-original-title="<?php echo lang('login_email'); ?>" rel="tooltip"><?php echo lang('ewallet_email'); ?>:</a></td>
                    <td >
                        <a href="javascript:void(0);" data-placement="bottom" data-original-title="<?php echo lang('login_email'); ?>" rel="tooltip"><span id="ewallet_email"><?php echo $user['email']?></span></a>
                    </td>
                </tr>

            </table>

    </div>
    <div class="modal-footer">
        <input type="hidden" value="<?php echo lang('ewallet_before')?>" id="ewallet_before">
        <span id="ewallet_info" class="msg"></span>
        <button autocomplete="off" class="btn btn-primary" id="ewallet_submit" onclick="create_ewallet()"><?php echo lang('submit'); ?></button>
    </div>
</div>
 申请ewallet弹层 -->
<script src="<?php echo base_url('ucenter_theme/js/account_info.js?v=7'); ?>"></script>
<script src="<?php echo base_url('js/jquery.form.js'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/js/upload.js?v=4'); ?>"></script>
<style>
    .level_background{
        height: 42px;
    }
    .cur span{
        margin:1px;
    }
	.box {
			float:left;
			text-align: left;
            font-size: 14px;
            color: white;
            padding: 10px;
            margin: 10px;
			background:#008001;
			background: -webkit-gradient(linear,left top,left bottom,from(#0a6),to(#008001));
            background: -moz-linear-gradient(top,#0a6,#008001);
			background:-ms-linear-gradient(top, #0a6 0%, #008001 100%) #008001;
    		background:linear-gradient(top, #0a6 0%, #008001 100%) #008001;

			-moz-border-radius: 15px;
			-webkit-border-radius: 15px;
			border-radius: 15px;
			
			-webkit-box-shadow:3px 3px 5px #000;
            -moz-box-shadow: -3px -3px 5px #000;
			-ms-box-shadow: 3px 3px 5px #000;
			box-shadow: 3px 3px 5px #000;
		}
@media screen and (min-width: 616px){
	.box {
            width: 140px;

		}
}
@media screen and (min-width: 576px) and (max-width: 615px) {
  .box {
            width: 50px;
		}
}
@media screen and (max-width: 575px) {
  .box {
            width: 30px;
		}
}
		
	.arrow {
	  width: 40px;
	  height: 40px;
	  position: relative;
	  display: inline-block;
	  margin: 30px 10px;
	  float:left;
	}
	.arrow:before, .arrow:after {
	  content: '';
	  border-color: transparent;
	  border-style: solid;
	  position: absolute;
	}
	
	
	.arrow-right:before {
	  border: none;
	  background-color: #066;
	  height: 30%;
	  width: 50%;
	  top: 35%;
	  left: 0;
	}
	.arrow-right:after {
	  left: 50%;
	  top: 0;
	  border-width: 20px 20px;
	  border-left-color: #066;
	}
    
    /*========================= 身份证审核遮罩层 ===========================================*/
#card_mask_layer { width:100%; height:100%; background-color:#000; position:absolute; top:0; left:0;z-index:2;opacity:0.3;
    /*兼容IE8及以下版本浏览器*/
    filter: alpha(opacity=30);
    display:none;
}
/*========================= 身份证审核弹出层 ===========================================*/
#card_popup_layer {  width:325px;  height:95px; background-color:#fff; font-size:16px;padding-top:30px;margin: auto; position: absolute; z-index:3; top: 0; bottom: 0;left: 0;right: 0;display:none; }
</style>


<?php if($user['pwd_take_out_cash']){ ?>
	<!-- 修改提現密碼弹层 -->
	<div id="modify_take_cash_pwd_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel"><?php echo lang('modify_take_out_pwd')?></h3>
		</div>
		<div class="modal-body">
			<form action="" method="post" class="form-horizontal" id="modify_take_cash_pwd_form">
				<table class="enable_level_tb">
					<tr>
						<td class="title"><?php echo lang('old_take_out_pwd'); ?>:</td>
						<td class="main">
							<input type="password" value="" name="old_take_out_pwd">
						</td>
					</tr>
					<tr>
						<td class="title"><?php echo lang('take_out_pwd'); ?>:</td>
						<td class="main">
							<input type="password" value="" name="take_cash_pwd" placeholder="<?php echo lang('take_out_pwd2');?>">
						</td>
					</tr>
					<tr>
						<td class="title"><?php echo lang('re_take_out_pwd'); ?>:</td>
						<td class="main">
							<input type="password" value="" name="take_cash_pwd_re" placeholder="<?php echo lang('take_out_pwd2');?>">
						</td>
					</tr>
					<tr>
						<td colspan="3" id="modify_take_out_pwd_msg" class="msg"></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="modal-footer">
			<button autocomplete="off" class="btn btn-primary" id="modify_take_cash_pwd_submit"><?php echo lang('submit'); ?></button>
		</div>
	</div>
	<!-- /修改提現密碼弹层 -->
<?php }else{?>
	<!-- 設置提現密碼弹层 -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel"><?php echo lang('set_take_cash_pwd')?></h3>
		</div>
		<div class="modal-body">
			<form action="" method="post" class="form-horizontal" id="set_take_cash_pwd_form">
				<table class="enable_level_tb">
					<tr>
						<td class="title"><?php echo lang('take_out_pwd'); ?>:</td>
						<td class="main">
							<input type="password" value="" name="take_cash_pwd" placeholder="<?php echo lang('take_out_pwd2');?>" >
						</td>
					</tr>
					<tr>
						<td class="title"><?php echo lang('re_take_out_pwd'); ?>:</td>
						<td class="main">
							<input type="password" value="" name="take_cash_pwd_re" placeholder="<?php echo lang('take_out_pwd2');?>" >
						</td>
					</tr>
					<tr>
						<td colspan="3" id="take_out_pwd_msg" class="msg"></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="modal-footer">
			<button autocomplete="off" class="btn btn-primary" id="set_take_cash_pwd_submit"><?php echo lang('submit'); ?></button>
		</div>
	</div>
	<!-- /設置提現密碼弹层 -->
<?php }?>

<?php if(!$user['is_verified_mobile'] || !$user['mobile']){?>
<div id="binding_info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo lang('binding_mobile')?></h3>
	</div>
	<div class="modal-body">
		<form action="" method="post" class="form-horizontal" id="binding_info_form">
			<table class="enable_level_tb">
				<tr>
					<td class=""><?php echo lang('mobile'); ?>:</td>
					<td class="main">
						<input type="text" value="<?php echo $user['mobile']?$user['mobile']:'';?>" autocomplete="off" name="phone" placeholder="<?php echo lang('mobile');?>" >
					</td>
				</tr>
				<tr>
					<td class=""><?php echo lang('captcha'); ?>:</td>
					<td class="main">
						<input type="text" value="" name="captcha" placeholder="<?php echo lang('captcha');?>" >
					</td>
				</tr><tr>
					<td class=""></td>
					<td class="main">
						<input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha2 " value="<?php echo lang('get_captcha')?>">
					</td>
				</tr>
				<tr>
					<td colspan="3" id="take_out_pwd_msg" class="msg"></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary" id="binding_info_btn"><?php echo lang('submit'); ?></button>
	</div>
</div>
<?php }?>

<?php if(!$user['is_verified_email'] || !$user['email']){?>
<div id="binding_info_email" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo lang('binding_email')?></h3>
	</div>
	<div class="modal-body">
		<form action="" method="post" class="form-horizontal" id="binding_info_email_form">
			<table class="enable_level_tb">
				<tr>
					<td class=""><?php echo lang('email_text'); ?>:</td>
					<td class="main">
						<input type="text" value="<?php echo $user['email']?$user['email']:'';?>" autocomplete="off" name="email" placeholder="<?php echo lang('email');?>" >
					</td>
				</tr>
                                <tr>
                                    <td class=""><?php echo lang('regi_emails'); ?>:</td>
                                    <td class="main">
                                        <input type="text" value="<?php echo $email?$email:'';?>" autocomplete="off" name="regi_emails" placeholder="<?php echo lang('email');?>" >
                                    </td>
                                </tr>
				<tr>
					<td class=""><?php echo lang('captcha'); ?>:</td>
					<td class="main">
						<input type="text" value="" name="captcha" placeholder="<?php echo lang('captcha');?>" >
					</td>
				</tr><tr>
					<td class=""></td>
					<td class="main">
						<input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha " value="<?php echo lang('get_captcha')?>">
					</td>
				</tr>
				<tr>
					<td colspan="3" id="take_out_pwd_msg" class="msg"></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary" id="binding_info_email_btn"><?php echo lang('submit'); ?></button>
	</div>
</div>
<?php }?>

<!--修改手机号弹出层-->
<div id="modify_mobile_number" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo lang('modify_mobile_number');?></h3>
	</div>
	<div class="modal-body">
		<form action="" method="post" class="form-horizontal" id="modify_mobile_number_form">
			<table class="enable_level_tb">
				<tr>
					<td class=""><?php echo lang('mobile'); ?>:</td>
					<td class="main">
						<input style="width: 256px;" type="text" class="modify_phone" value="" autocomplete="off" name="phone" placeholder="<?php echo lang('pls_input_new_number');?>" >
					</td>
				</tr>
				<tr>
					<td class=""><?php echo lang('captcha'); ?>:</td>
					<td class="main">
						<input style="width: 256px;" type="text" class="modify_captcha" autocomplete="off" value="" name="captcha" placeholder="<?php echo lang('captcha');?>" >
					</td>
				</tr><tr>
					<td class=""></td>
					<td class="main">
						<input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha_modify_number" value="<?php echo lang('get_captcha')?>">
					</td>
				</tr>
				<tr>
					<td colspan="3" id="modify_mobile_number_msg" class="msg"></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary" id="modify_mobile_number_btn"><?php echo lang('submit'); ?></button>
	</div>
</div>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>
    function del_shop(id){
        layer.confirm("<?php echo lang('is_show_delete'); ?>", {
            icon: 3,
            title: "<?php echo lang('is_delete_show'); ?>",
            closeBtn: 2,
            btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
        }, function(){
            $.ajax({
                url: "/ucenter/account_info/del_shop",
                type: "POST",
                data: {'id': id},
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        layer.msg(data.msg);
                        setTimeout(function(){
                            location.reload('');
                        },3000);
                        return false;
                    }else {
                        layer.msg(data.msg);
                        setTimeout(function(){
                            location.reload('');
                        },3000);
                        return false;
                    }
                },
                error: function(data) {
                    console.log(data.responseText);
                }
            });
        });
    }
	$(function(){
		$('.modify_phone').blur(function(){
			var number = $.trim($(this).val());
			$(this).val(number);
			if($(this).val() != ''){
				if(!(/^1[34578]\d{9}$/.test($(this).val()))){
					layer.msg("<?php echo lang('forms_authentication_geshi'); ?>");
					$('.get_captcha_modify_number').attr('disabled',true);
				}else{
					$('.get_captcha_modify_number').attr('disabled',false);
				}
			}else{
				$('.get_captcha_modify_number').attr('disabled',false);
			}
		});
		$('.get_captcha_modify_number').click(function(){
			if($('.modify_phone').val()==''){
				layer.msg("<?php echo lang('pls_input_new_number');?>");
			}
		});
	});
</script>
