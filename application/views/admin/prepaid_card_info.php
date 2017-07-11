<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo lang('tps138_admin');?></title>
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/css/bootstrap.css')?>">
		<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
	</head>

	<body>

	<div class="box"></div>
	<div class="w100" style="margin-top:20px;">
		<form method="post" id="prepaid_card_form">
			<ul>
				<!--<li><span class="c-hong"><?php /*echo lang('pre_card_tip') */?></span></li>-->
				<li><div class="d-ib"><span class="tr-r"><b>*</b><?php echo lang('pc_name') ?>：</span><input type="text" autocomplete="off" name="name" value="<?php echo htmlspecialchars($pre_card['name'])?>" class="itxt" placeholder=""></div>
					</li><li><div class="d-ib"><span class="tr-r"><?php echo lang('chinese_name') ?>：</span><input type="text" autocomplete="off" name="chinese_name" class="itxt" value="<?php echo htmlspecialchars($pre_card['chinese_name'])?>" placeholder=""></div></li>
				<li><span class="tr-r"><b>*</b><?php echo lang('pc_mobile') ?>：</span><input type="text" name="mobile" autocomplete="off" class="itxt" value="<?php echo $pre_card['mobile']?>" placeholder=""></li>
				<li>
					<div class="d-ib">
						<span class="tr-r"><b>*</b><?php echo lang('pc_nationality') ?>：</span>
						<select class="itxt-l" name="nationality" autocomplete="off">
							<option value=""><?php echo lang('pc_nationality') ?></option>
							<?php foreach($country_arr as $k=>$country){?>
								<option value="<?php echo $k?>" <?php echo $k==$pre_card['nationality']?'selected':'' ?> ><?php echo $country?></option>
							<?php }?>
						</select>
					</div>
					<div class="d-ib">
						<span class="tr-r"><b>*</b><?php echo lang('pc_issuing_country') ?>：</span>
						<select class="itxt-l" name="issuing_country" autocomplete="off">
							<option value=""><?php echo lang('country') ?></option>
							<?php foreach($country_arr as $k=>$country){?>
								<option value="<?php echo $k?>" <?php echo $k==$pre_card['issuing_country']?'selected':'' ?> ><?php echo $country?></option>
							<?php }?>
						</select>
					</div>
				</li>
				<li>
					<div class="d-ib">
						<span class="tr-r"><b>*</b><?php echo lang('pc_ID_card') ?>：</span>
						<select class="itxt-l" name="ID_type" autocomplete="off">
							<option value="0" <?php echo $pre_card['ID_type'] == 0 ? 'selected':'';?> ><?php echo lang('pc_ID_card_type_0') ?></option>
							<option value="1" <?php echo $pre_card['ID_type'] == 1 ? 'selected':'';?> ><?php echo lang('pc_ID_card_type_1') ?></option>
						</select>
					</div>
					<input type="text" name="ID_no" autocomplete="off" value="<?php echo htmlspecialchars($pre_card['ID_no'])?>" class="itxt" placeholder="<?php echo lang('pc_ID_no') ?>">
				</li>
				<li>
					<span class="tr-r"><b>*</b><?php echo lang('pc_ID_card_ship')?>：</span>
					<select class="itxt-l" name="country" autocomplete="off">
						<option value=""><?php echo lang('pc_country')?></option>
						<?php foreach($country_arr as $k=>$country){?>
							<option value="<?php echo $k?>" <?php echo $k==$pre_card['country']?'selected':'' ?>><?php echo $country?></option>
						<?php }?>
					</select>
					<input type="text" class="itxt" autocomplete="off" value="<?php echo htmlspecialchars($pre_card['ship_to_address'])?>"  name="ship_to_address" placeholder="<?php echo lang('pc_ship_to_address')?>">
				</li>
				<li><span class="tr-r"><b>*</b><?php echo lang('pc_email')?>：</span><input type="text" autocomplete="off" name="email" value="<?php echo $pre_card['email']?>" class="itxt" placeholder="EMail地址"></li>
				<li><span class="tr-r"><b>*</b><?php echo lang('pc_card_no')?>：</span><input autocomplete="off" value="<?php echo $pre_card['card_no']?$pre_card['card_no']:''?>" <?php echo $pre_card['card_no']? 'disabled readonly':''?> type="text" name="card_no" class="itxt" placeholder="">
					<?php if(!$pre_card['card_no']){?>
						<!--
					<input class="btn btn-danger assign_card_no" type="button" autocomplete="off" value="<?php echo lang('assign_card_no')?>">
						-->
					<?php }?>
				</li>
				<li><span class="tr-r"><b>*</b><?php echo lang('pc_ID_card_upload') ?>：</span><span class="c-99"><?php echo lang('pc_ID_card_upload_tip') ?></span></li>
				<li>
					<div class="ID_front <?php echo $pre_card['ID_front']? 'hidden':'';?>">
						<input type="file" autocomplete="off" id="fileupload" name="ID_front" class="yinc"><span class="btn-c"><?php echo lang('pc_ID_front') ?></span>
					</div>
					<div id="showimg" class="<?php echo $pre_card['ID_front']? '':'hidden';?>">
						<a class="example-image-link" href="<?php echo config_item('img_server_url').'/'.$pre_card['ID_front']?>" data-lightbox="card_info">
							<img class="example-image" src="<?php echo config_item('img_server_url').'/'.$pre_card['ID_front']?>" alt="not exist"></a>
					</div>
					<?php if($pre_card['ID_reverse']){?>
					<div class="ID_reverse <?php echo $pre_card['ID_reverse']? 'hidden':'';?>">
						<input type="file" autocomplete="off" id="fileupload2" name="ID_reverse" class="yinc left"><span class="btn-c"><?php echo lang('pc_ID_reverse') ?></span>
					</div>
					<div id="showimg2" class="<?php echo $pre_card['ID_reverse']? '':'hidden';?>">
						<a class="example-image-link" href="<?php echo config_item('img_server_url').'/'.$pre_card['ID_reverse']?>" data-lightbox="card_info">
							<img class="example-image" src="<?php echo config_item('img_server_url').'/'.$pre_card['ID_reverse']?>" alt="not exist"></a>

					</div>
					<?php }?>
				</li>

				<li><span class="tr-r"><b>*</b><?php //echo lang('pc_address_prove') ?><!--：--></span><span class="c-99"><?php echo lang('pc_address_prove_tip') ?></span></li>
				<li>
					<div class="address_prove <?php echo $pre_card['address_prove']? 'hidden':'';?>">
						<input type="file" autocomplete="off" id="fileupload3" name="address_prove" class="yinc"><span class="btn-c"><?php echo lang('upload') ?></span>
					</div>
					<div id="showimg3" class="<?php echo $pre_card['address_prove']? '':'hidden';?>">
						<a class="example-image-link" href="<?php echo config_item('img_server_url').'/'.$pre_card['address_prove']?>" data-lightbox="card_info">
							<img class="example-image" src="<?php echo config_item('img_server_url').'/'.$pre_card['address_prove']?>" alt="not exist"></a>
					</div>
				</li>
				<li><span class="tr-r"><?php echo lang('status')?>：</span>
					<label><input type="radio" <?php echo $pre_card['status']==2?'checked':''?> name="status" value="2"><?php echo lang('pc_status_2');?></label>
					<label><input type="radio" name="status" value="5"><?php echo lang('pc_status_5');?></label>
					<label><input type="radio" name="status" value="3"><?php echo lang('pc_status_3');?></label>
					<label><input type="radio" name="status" value="4"><?php echo lang('pc_status_4');?></label>
				</li>
				<li class="reject_remark <?php echo $pre_card['status']==2?'':'hidden'?>"><span class="tr-r"><?php echo lang('refuse_reason')?>：</span>
					<textarea autocomplete="off" style="width:275px;" placeholder="" name="reject_remark"><?php echo $pre_card['reject_remark']?$pre_card['reject_remark']:''?></textarea>
				</li>
				<li>
					<input type="hidden" name="id" value="<?php echo $pre_card['id']?>" autocomplete="off">

					<input type="button" class="ml-140 btn-kg" id="prepaid_card_submit" autocomplete="off" value="<?php echo lang('update')?>"></li>
			</ul>
		</form>
	</div>

	<script src="<?php echo base_url('themes/mall/js/jquery-1.11.1.js')?>"></script>
	<script>
		$(function(){

			$('input[type="radio"]').click(function(){
				var type = $('input[type="radio"]:checked').val();
				if(type == 2){
					$(".reject_remark").removeClass('hidden');
				}else{
					$(".reject_remark").addClass('hidden');
				}
			});
			$('#prepaid_card_submit').click(function(){
				var curEle = $(this);
				var oldSubVal = curEle.val();
				$(this).attr("value", $('#loadingTxt').val());
				$(this).attr("disabled","disabled");
				var oldColor = '#d22215';
				curEle.css('background','#cccccc');
				$.ajax({
					type:'post',
					url:"/admin/check_prepaid_card/do_update",
					data:$('#prepaid_card_form').serialize(),
					dataType:'json',
					success:function(res){
						if (res.code == 0) {
							layer.msg(res.msg);
							//location.reload();
							setTimeout(function(){
								parent.location.reload();
							},1000)
						} else {
							curEle.css('background',oldColor);
							curEle.attr("value", oldSubVal);
							curEle.attr("disabled", false);
							layer.msg(res.msg);
						}
					}
				});
			});
			$('.assign_card_no').click(function(){
				var curEle = $(this);
				var oldSubVal = curEle.val();
				$(this).attr("value", $('#loadingTxt').val());
				$(this).attr("disabled","disabled");
				var oldColor = '#d22215';
				curEle.css('background','#cccccc');
				$.ajax({
					type:'post',
					url:"/admin/check_prepaid_card/assign_card_no",
					//data:$('#prepaid_card_form').serialize(),
					dataType:'json',
					success:function(res){
						if (res.success == 0) {
							layer.msg(res.msg);
							setTimeout(function(){
								location.reload();
							},1000)
						} else {
							curEle.css('background',oldColor);
							curEle.attr("value", oldSubVal);
							curEle.attr("disabled", false);
							$('[name="card_no"]').val(res.card_no);
							$('input[type="radio"]').get(1).checked = true;
							$('input[type="radio"]').get(0).disabled = true;
							$('input[type="radio"]').get(2).disabled = true;
							$('input[type="radio"]').get(3).disabled = true;
						}
					}
				});
			});
		});
		<?php if(in_array($pre_card['status'],array('3','4','5'))){ ?>
			$("#prepaid_card_form :text,#prepaid_card_form select").each(function(){
				$(this).attr('disabled',true);
			});
		<?php }?>
	</script>
	<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/prepaid_card.css?v=1'); ?>">
	<script src="<?php echo base_url('js/jquery.form.js'); ?>"></script>
	<script src="<?php echo base_url('ucenter_theme/js/prepaid_card_upload.js?v=3'); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
	<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.min.js?v=1'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
	</body>
</html>
