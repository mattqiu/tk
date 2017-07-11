<div class="w100">
	<form method="post" id="prepaid_card_form">
		<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
	<ul>
		<li><span class="c-hong"><?php echo lang('pre_card_tip') ?></span></li>
		<li><div class="d-ib"><span class="tr-r"><b>*</b><?php echo lang('pc_name') ?>：</span><input type="text" name="name" class="itxt" placeholder=""></div><div class="d-ib"><span class="tr-r"><?php echo lang('chinese_name') ?>：</span><input type="text" name="chinese_name" class="itxt" placeholder=""></div></li>
		<li><span class="tr-r"><b>*</b><?php echo lang('pc_mobile') ?>：</span><input type="text" name="mobile" class="itxt" placeholder=""></li>
		<li>
			<div class="d-ib">
				<span class="tr-r"><b>*</b><?php echo lang('pc_nationality') ?>：</span>
				<select class="itxt-l" name="nationality">
					<option selected="" value=""><?php echo lang('pc_nationality') ?></option>
					<?php foreach($country_arr as $k=>$country){?>
						<option value="<?php echo $k?>"><?php echo $country?></option>
					<?php }?>
				</select>
			</div>
			<div class="d-ib">
				<span class="tr-r"><b>*</b><?php echo lang('pc_issuing_country') ?>：</span>
				<select class="itxt-l" name="issuing_country">
					<option selected="" value=""><?php echo lang('country') ?></option>
					<?php foreach($country_arr as $k=>$country){?>
						<option value="<?php echo $k?>"><?php echo $country?></option>
					<?php }?>
				</select>
			</div>
		</li>

		<li>
			<div class="d-ib">
				<span class="tr-r"><b>*</b><?php echo lang('pc_ID_card') ?>：</span>
				<select class="itxt-l" name="ID_type">
					<option selected="" value="0"><?php echo lang('pc_ID_card_type_0') ?></option>
					<option value="1"><?php echo lang('pc_ID_card_type_1') ?></option>
				</select>
			</div>
			<input type="text" name="ID_no" class="itxt" placeholder="<?php echo lang('pc_ID_no') ?>">
		</li>
		<li>
			<span class="tr-r"><b>*</b><?php echo lang('pc_ID_card_ship')?>：</span>
			<select class="itxt-l" name="country">
				<option selected="" value=""><?php echo lang('pc_country')?></option>
				<?php foreach($country_arr as $k=>$country){?>
					<option value="<?php echo $k?>"><?php echo $country?></option>
				<?php }?>
			</select>
			<input type="text" class="itxt"  name="ship_to_address" placeholder="<?php echo lang('pc_ship_to_address')?>">
		</li>
		<li><span class="tr-r"><b>*</b><?php echo lang('pc_email')?>：</span><input type="text" name="email" class="itxt" placeholder="EMail"></li>
		<li><span class="tr-r"><b>*</b><?php echo lang('pc_card_no')?>：</span><input style="width: 225px;" type="text" name="card_no" class="itxt" placeholder="<?php //echo lang('pc_card_no_tip')?>">
			<p style="margin-left: 140px;"><?php echo lang('pc_card_no_tip2')?></p>
		</li>
		<li><span class="tr-r"><b>*</b><?php echo lang('pc_ID_card_upload') ?>：</span><span class="c-99"><?php echo lang('pc_ID_card_upload_tip') ?></span></li>
		<li>
		<div class="ID_front">
				<input autocomplete="off" type="file" id="fileupload" name="ID_front" class="yinc"><span class="btn-c"><?php echo lang('pc_ID_front') ?></span>
			</div>
		<div id="showimg" class="hidden">
				<a class="example-image-link" href="" data-lightbox="card_info">
					<img class="example-image" src="" alt="not exist"></a>
				<a rel="" onclick="delimg()" id="delimg" href="##"><?php echo lang('delete')?></a>
			</div>
		<div class="ID_reverse">
				<input type="file" autocomplete="off" id="fileupload2" name="ID_reverse" class="yinc left"><span class="btn-c"><?php echo lang('pc_ID_reverse') ?></span>
			</div>
		<div id="showimg2" class="hidden">
				<a class="example-image-link" href="" data-lightbox="card_info">
					<img class="example-image" src="" alt="not exist"></a>
				<a rel="" onclick="delimg2()" id="delimg2" href="##"><?php echo lang('delete')?></a>
			</div>
		</li>
		<li><span class="tr-r"><?php //echo lang('pc_address_prove') ?><!--：--></span><span class="c-99"><?php echo lang('pc_address_prove_tip') ?></span></li>
		<li>
		<div class="address_prove">
				<input type="file" autocomplete="off" id="fileupload3" name="address_prove" class="yinc"><span class="btn-c"><?php echo lang('upload') ?></span>
			</div>
		<div id="showimg3" class="hidden">
				<a class="example-image-link" href="" data-lightbox="card_info">
					<img class="example-image" src="" alt="not exist"></a>
				<a rel="" onclick="delimg3()" id="delimg3" href="##"><?php echo lang('delete')?></a>
			</div>
		</li>
		<li><span class="tr-r"><b>*</b><?php echo lang('payment_method')?>：</span>
			<label class="modal_main">
				<input style="display: inline" type="radio" value="110" checked name="" autocomplete="off">
				<span style="font-size: 1.2em;font-weight: bold;display: inline"><?php echo lang('current_commission')?> </span>: <strong style="color: #ee330a;font-size: 1.3em"><?php echo '$'.$my_amount?></strong></span>
			</label>
				<p class="ml-140"><?php echo lang('pc_payment_tip')?></p>
		</li>
		<li><label class="ml-140 w-560 modal_main">
			<input type="checkbox" name="agreement" ><?php echo lang('pc_agreement').'<br>'.lang('pc_agree_t')?>

			</label>
			<div class="ml-140 xinxi prepaid_agree_content">

			</div>
		</li>
		<li>
			<input type="hidden" name="ID_front" id="ID_front_path" autocomplete="off">
			<input type="hidden" name="ID_reverse" id="ID_reverse_path" autocomplete="off">
			<input type="hidden" name="address_prove" id="address_prove_path" autocomplete="off">
			<input type="button" class="ml-140 btn-kg" id="prepaid_card_submit" value="<?php echo lang('pc_submit')?>"></li>
	</ul>
	</form>
</div>

<script src="<?php echo base_url('js/jquery.form.js'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/js/prepaid_card_upload.js?v=2'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/prepaid_card.css?v=3'); ?>">
<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.min.js?v=1'); ?>"></script>
<script>
	$(".yued").bind("click",function(){
		$('.xinxi').toggle(100);
	});
</script>
<script>
	$(document).ready(function($){
		$('#prepaid_card_submit').click(function(){
			var curEle = $(this);
			var oldSubVal = curEle.val();
			$(this).attr("value", $('#loadingTxt').val());
			$(this).attr("disabled","disabled");
			var oldColor = '#d22215';
			curEle.css('background','#cccccc');
			$.ajax({
				type:'post',
				url:"/ucenter/prepaid_card/do_apply",
				data:$('#prepaid_card_form').serialize(),
				dataType:'json',
				success:function(res){
					if (res.code == 0) {
						layer.msg(res.msg);
						location.href='/ucenter/take_out_cash'
					} else {
						curEle.css('background',oldColor);
						curEle.attr("value", oldSubVal);
						curEle.attr("disabled", false);
						layer.msg(res.msg);
					}
				}
			});
		});
		getAgreeContent('<?php echo $curLanguage?>');

	});
	function getAgreeContent($circle) {
		var $circle_array=['zh',"english","hk"];
		if($.inArray($circle,$circle_array) != '-1'){
			$circle = $circle;
		}else{
			$circle = 'english';
		}
		$('.prepaid_agree_content').load('/themes/mall/js/prepaid_agree_'+$circle+'.html');
	}
</script>
