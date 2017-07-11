<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<?php if(isset($list) && $list['status'] == 8 && $list['adminid'] == $adminInfo['id']) { ?>
<style type="text/css">
	.input-group { margin: 10px 0; height: 26px; line-height: 26px; display: table; width: 100%; vertical-align:middle;}
	.input-group-addon, .form-control { display: inline-table; }
	.input-group-addon { width: 130px; text-align: right; }
	.require {color:red;}
	.orderlist, .address { display: inline-table;}
	.orderlist table { width: 580px; height: 100px; overflow-y: scroll; }
	.detail-rows {border-bottom: 1px solid #ddd;}
	.detail-rows .dinline { display: block; }
	.mleft { margin-left: 15px !important; }
	.invoiceBox,#email { display: none; }
	#invoice_top { width: 440px; }
	.textarea { position:relative; }
	.together { position:absolute; top:0; }
	#address_error { position:absolute; left:676px; top:26px; }
	#remark { min-width: 560px; }
	#invoice_taxpayer_id_number{ width: 440px; }
</style>
<?php } else { ?>
<style type="text/css">
	.leftbox { position: relative; width:710px; }
	.rightbox { position: absolute; left:740px; top:70px; display:block; }
	.well { position: relative; }
	.input-group { margin: 10px 0; height: 26px; line-height: 26px; display: table; width: 100%; vertical-align:middle;}
	.input-group-addon, .form-control { display: inline-table; }
	.input-group-addon { width: 130px; text-align: right; }
	.input-group input, .input-group select { width:auto !important; }
	.require {color:red;}
	.orderlist, .address { display: inline-table;}
	.orderlist table { width: 580px; height: 100px; overflow-y: scroll; }
	.detail-rows {border-bottom: 1px solid #ddd;}
	.detail-rows .dinline { display: block; }
	.mleft { margin-left: 15px !important; }
	.rightbox h6 { text-align:center; margin: auto; }
	.rmargin5 { margin-right:5px; }
	#invoice_top { width: 440px; }
	.textarea { position:relative; }
	.together { position:absolute; top:0; }
	#address_error { position:absolute; left:676px; top:26px; }
	#remark { min-width: 560px; }
	#invoice_taxpayer_id_number{ width: 440px; }
</style>
<?php } ?>
<div class="well">
	<ul class="nav nav-tabs">
        <?php foreach ($tab_map as $k => $v): ?>
            <li <?php if ($k == $fun) echo " class=\"active\""; ?>>
                <a href="<?php echo base_url($v['url']); ?>">
                    <?php echo $v['desc']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
	
	<?php if(isset($list) && $list['status'] == 8 && $list['adminid'] == $adminInfo['id']) { ?>
    <form class="form-inline" id="invoiceAjax"  method="post" action="<?php echo base_url('admin/invoice/editsave') ?>">
		<div class="input-group">
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_number');?>：</label>
			<input type="text" class="form-control" id="invoice_num" name="invoice_num" value="<?php echo $list['invoice_num'];?>" placeholder="" readonly="true" />
			<span class="require" id="invoice_number_error"></span>
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('member_id');?>：</label>
			  <input type="text" class="form-control" id="uid" name="uid" value="<?php echo $list['uid']; ?>" readonly="true" />
			  <span class="require" id="uid_error"></span>
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_time');?>：</label>
			  <input type="text" class="form-control Wdate" id="start" name="start" onclick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('start_date');?>" value="<?php echo $list['invoice_start_time'];?>" />&nbsp;-&nbsp;
			  <input type="text" class="form-control Wdate" id="end" name="end" onclick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('end_date'); ?>" value="<?php echo $list['invoice_end_time'];?>" />
			  <span class="require" id="invoice_time_error"></span>
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_total_money');?>：</label>
			  <input type="text" class="form-control" id="invoice_total_money" name="invoice_total_money" value="<?php echo $list['invoice_total_money'];?>" readonly="true" />
			  <label class="input-group-addon lright10"><span class="require">*</span><?php echo lang('invoice_fact_total_money');?>：</label>
			  <input type="text" class="form-control" id="invoice_fact_total_money" name="invoice_fact_total_money" value="<?php echo $list['invoice_fact_money'];?>" />
			  <span class="require" id="invoice_money_error"></span>
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_order');?>：</label>
			  <div class="orderlist" id="orderlist">
				<table class="table">
				  <tbody>
				  <tr>
					<th><?php echo lang('admin_order_id');?></th>
					<th><?php echo lang('admin_order_prop');?></th>
					<th><?php echo lang('invoice_money');?></th>
					<th><?php echo lang('choose2');?></th>
					<th><?php echo lang('label_feedback_state');?></th>
					<th><?php echo lang('admin_order_operate');?></th>
				  </tr>
				  <?php foreach($list['orderlist'] as $order) {?>
				  <tr>
					<td><?php echo $order['order_id'];?></td>
					<td><?php echo $order_type[$order['order_type']];?></td>
					<td><?php echo number_format($order['money'] / 100, 2, '.', '');?></td>
					<td><?php echo $order['express_free'];?></td>
					<td><?php echo $status_select[$order['status']];?></td>
					<td>
						<input type="hidden" name="orderlist[]" value="<?php echo $order['order_id'].'|'.$order['order_type'].'|'.number_format($order['money'] / 100, 2, '.', '').'|'.$order['express_free'].'|'.$order['status'].'|'.$order['cateids'];?>" />
						<input class="checked" type="checkbox" id="<?php echo $order['order_id'] ?>" name="orderlistchecked[]" cateids="<?php echo $order['cateids'];?>" price="<?php echo number_format($order['money'] / 100, 2, '.', ''); ?>" value="<?php echo $order['order_id'];?>" <?php if($order['mark']) { echo 'checked="checked"'; } ?> />
					</td>
				  </tr>
				  
				  <?php if (isset($order['goods_list']) && $order['goods_list']) {?>
				  
				  <tr>
					<td class="text-danger"><?php echo lang('goods_list');?></td>
					<td class="text-info" colspan="5">
						<table class="table">
							<tr>
								<td colspan="4" style="text-align:left;"><?php echo lang('label_goods_name');?></td>
								<td><?php echo lang('admin_order_goods_quantity');?></td>
							</tr>
							<?php foreach($order['goods_list'] as $goodslist) {?>
							<tr>
								<td colspan="4" style="text-align:left; max-width:270px;"><?php echo $goodslist['goods_name'];?></td>
								<td><?php echo $goodslist['goods_number'];?></td>
							</tr>
							<?php }?>
						</table>
					</td>
				  </tr>
				  <?php }?>
				  <?php }?>
				  </tbody>
				</table>
			  </div>
			  <input type="hidden" id="isempty" value="1" />
			  <span class="require" id="invoice_order_error"></span>
		</div>

		<!-- 开票明细 -->
		<div id="invoice_detail">
			<?php if(isset($list['details']) && $list['details']) {?>
			<?php foreach($list['details'] as $detail) {?>
			  <div class="detail-rows" <?php if(isset($detail['money']) && $detail['money'] <= 0) echo 'style="display:none;"'; ?>>
				<div class="dinline">
					<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_detail');?>：</label><?php echo $detail['cate_name'];?>
				</div>
				<div class="dinline">
					<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_detail_money');?>：</label><m id="m_<?php echo $detail['cate_id'];?>"><?php echo $detail['money'];?></m>
					<?php if(isset($detail['orderids']) && $detail['orderids']) { foreach($detail['orderids'] as $sub) { ?>
					<input type="hidden" cate_id="<?php echo $detail['cate_id'];?>" name="subdetailist[]" id="subdetail_<?php echo $sub['order_id'].'_'.$detail['cate_id'];?>" value="<?php echo $sub['price'];?>" />
					<?php }} ?>
				</div>
				<input type="hidden" id="detail_<?php echo $detail['cate_id'];?>" cate_name="<?php echo $detail['cate_name'];?>" price="<?php echo $detail['money'];?>" name="detailinfo[]" value="<?php echo $detail['cate_name'].':@'.$detail['money'].':@'.$detail['cate_id'].':@'.$detail['order_id'];?>" />
			  </div>
			<?php }}?>
		</div>

<!--		<div class="input-group">-->
<!--			  <label class="input-group-addon"><span class="require">*</span>--><?php //echo lang('invoice_top');?><!--：</label>-->
<!--			  <input type="text" class="form-control" id="invoice_top" name="invoice_top" value="--><?php //echo $list['invoice_head'];?><!--" />-->
<!--			  <span class="require" id="invoice_top_error"></span>-->
<!--		</div>-->

		<!-- 发票类型 -->
		<div class="input-group">
			<input type="hidden" name="invoice_type_2" value="<?php echo $list['invoice_type_2'];?>" />
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_type_2');;?>：</label>
			<input type="radio" class="form-control invoice_type" id="invoice_type_common" name="invoice_type_2" placeholder="" value="1" <?php if($list['invoice_type_2'] == 1) { echo 'checked="checked"'; } ?>  disabled="disabled"/> <?php echo lang('invoice_type_common');?>
			<input type="radio" class="form-control mleft invoice_type" id="invoice_type_tax" name="invoice_type_2" value="2" placeholder="" <?php if($list['invoice_type_2'] == 2) { echo 'checked="checked"'; } ?> disabled="disabled"/> <?php echo lang('invoice_type_tax');?>
			<span class="require" id="invoice_type_form_error"></span>
		</div>

		<div class="input-group invoice_type_top" style="<?php echo $list['invoice_type_2']==1 ? '':'display: none;' ?>">

			<input type="hidden" name="invoice_title_type_value" value="<?php echo $list['invoice_taxpayer_id_number'] != 0 ? 1 : 2;?>" />

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_title_type');?>：</label>
			<input type="radio" class="form-control invoice_title_type" id="invoice_title_type_1" name="invoice_title_type" autocomplete="off" value="1" placeholder="" <?php if($list['invoice_taxpayer_id_number'] != 0 ) { echo 'checked="checked"'; } ?> disabled="disabled"/> <?php echo lang('invoice_title_type_company');?>
			<input type="radio" class="form-control mleft invoice_title_type" id="invoice_title_type_2" name="invoice_title_type" autocomplete="off" value="2" placeholder="" <?php if($list['invoice_taxpayer_id_number'] == 0 ) { echo 'checked="checked"'; } ?> disabled="disabled" /> <?php echo lang('invoice_title_type_personage');?><br/>


			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_top');?>：</label>
			<input type="text" class="form-control" id="invoice_top" name="invoice_top" value="<?php echo $list['invoice_head'] ? $list['invoice_head'] : '';?>" />
			<span class="require" id="invoice_top_error"></span>

			<div class="invoice_taxpayer_id_number_box" style="<?php echo $list['invoice_taxpayer_id_number'] != 0 ? '':'display: none;' ?>">
				<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_taxpayer_id_number');?>：</label>
				<input type="text" class="form-control" id="invoice_taxpayer_id_number" name="invoice_taxpayer_id_number" placeholder="" maxlength="100" value="<?php echo $list['invoice_taxpayer_id_number']; ?>"/>
				<span class="require" id="invoice_taxpayer_id_number_error"></span>
			</div>

		</div>

		<div class=" invoice_type_tax_box" style="<?php echo $list['invoice_type_2']==2 ? '':'display: none;' ?>">
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_country_name_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_county_name_type']:''; ?>" class="form-control" id="invoice_county_name_type" name="invoice_county_name_type" maxlength="100" placeholder=""/>
			<span class="require" id="invoice_county_name_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_identify_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_identify_type']:''; ?>" class="form-control" id="invoice_identify_type" name="invoice_identify_type" maxlength="100" placeholder="" />
			<span class="require" id="invoice_identify_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_bank_name_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_bank_name_type']:''; ?>" class="form-control" id="invoice_bank_name_type" name="invoice_bank_name_type" maxlength="100" placeholder="" />
			<span class="require" id="invoice_bank_name_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_bank_count_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_bank_count_type']:''; ?>" class="form-control" id="invoice_bank_count_type" name="invoice_bank_count_type" maxlength="100" placeholder="" />
			<span class="require" id="invoice_bank_count_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_company_address_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_company_address_type']:''; ?>" class="form-control" id="invoice_company_address_type" name="invoice_company_address_type" maxlength="100" placeholder="" />
			<span class="require" id="invoice_company_address_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_company_phone_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_company_phone_type'] :''; ?>" class="form-control" id="invoice_company_phone_type" name="invoice_company_phone_type" maxlength="100" placeholder="" />
			<span class="require" id="invoice_company_phone_type_error"></span>

		</div>

		<!-- 发票形式 -->
		<div class="input-group">
			<input type="hidden" name="invoicetype" value="<?php echo $list['invoice_type'];?>" />
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_form');?>：</label>
			  <input type="radio" class="form-control invoices" id="invoice_paper" name="invoices" value="1" <?php if($list['invoice_type'] == 1) { echo 'checked="checked"'; } ?> disabled="disabled" /> <?php echo lang('invoice_paper');?>

			  <input type="radio" class="form-control mleft invoices" id="invoice_electron" name="invoices" value="2" <?php if($list['invoice_type'] == 2) { echo 'checked="checked"'; } ?> disabled="disabled" /> <?php echo lang('invoice_electron');?>
			  <span class="require" id="invoice_form_error"></span>
		</div>

		<!-- 纸质发票 -->
		<div class="invoiceBox" <?php if($list['invoice_type'] == 1) echo 'style="display:block;"';?>>
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_address');?>：</label>
				  <div class="address" id="box_addr">
						<select class="select" id="box_country" name="country" onchange="cb_box_country();" disabled="disabled">
			                <option value="0"><?php echo lang('checkout_addr_country'); ?></option>
			            </select>
				  </div>
			</div>
			<div class="input-group textarea">
				  <label class="input-group-addon"></label>
				  <textarea type="text" name="address" class="xxidz" id="box_addr_detail" maxlength="255"
                      placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>" disabled="disabled"><?php if(!empty($list['addressinfo']['address'])) echo $list['addressinfo']['address'];?></textarea>
				<?php if(isset($list['invoice_type']) && $list['invoice_type'] == 1) {?>
				  <span class="together mleft">  
					  <input id="together" name="together" value="1" type="checkbox" <?php if(isset($list['js_invoicenum']) && $list['js_invoicenum']){?>checked="checked"<?php }?>>&nbsp;与其它发票一起寄送<br>
					  <label style="display:inline-block;"><span class="require">*</span>开票单号：</label><input id="js_invoicenum" name="js_invoicenum" type="text" value="<?php if (isset($list['js_invoicenum']) && $list['js_invoicenum']) { echo $list['js_invoicenum']; }?>" />
				  </span>
				<?php }?>   
                  <span class="require" id="address_error"></span>
			</div>
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('express_free');?>：</label>
				  <select name="express_arrive" id="express_arrive" disabled="disabled">
				  	<?php foreach($express_arrive_type as $k => $v) {?>
						<option value="<?php echo $k;?>" <?php if($k == $list['express_arrive']) echo 'selected="selected"';?>><?php echo $v;?></option>
					<?php }?>
				  </select>
				  ￥&nbsp;<input type="text" class="form-control" id="express_free" name="express_free" value="<?php echo $list['zh_express_free'];?>" disabled="disabled" />&nbsp;&nbsp;
				  $&nbsp;<input type="text" class="form-control" id="express_free_us" name="express_free_us" value="<?php echo $list['us_express_free'];?>" disabled="disabled" />
				  <span class="require" id="express_free_error"></span>
			</div>
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('receive_people');?>：</label>
				  <input type="text" class="form-control" id="receive_people" name="receive_people" value="<?php echo $list['recipient'];?>" />
				  <span class="require" id="receive_people_error"></span>
			</div>
		</div>

		<!-- 电子发票 -->
		<div class="input-group" id="email" <?php if($list['invoice_type'] == 2) echo 'style="display:block;"';?>>
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_send_email');?>：</label>
			  <input type="text" class="form-control" id="invoice_send_email" name="invoice_send_email" value="<?php echo $list['email'];?>" />
			  <span class="require" id="invoice_send_email_error"></span>
		</div>
		
		<div class="invoiceBoxPublic">
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('mobile');?>：</label>
				  <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $list['mobile'];?>" />
				  <label class="input-group-addon lright10"><?php echo lang('invoice_spare_num');?>：</label>
				  <input type="text" class="form-control" id="invoice_spare_num" name="invoice_spare_num" value="<?php echo $list['backup_num'];?>" />
				  <span class="require" id="mobile_error"></span>
			</div>
			<div class="input-group">
				  <label class="input-group-addon"><?php echo lang('admin_order_remark');?>：</label>
				  <textarea id="remark" name="remark" rows="3" cols="20"><?php echo $list['remark'];?></textarea>
			</div>
		</div>

		<div class="input-group">
			  <label class="input-group-addon"></label>
			  <button type="submit" class="btn btn-primary" style="margin-right:10px;"><?php echo lang('submit');?></button>
			  <input type="hidden" name="submitype" value="edit" />
			  <a href="javascript:;" id="tovoid" class="btn btn-primary"><?php echo lang('admin_after_sale_status_6');?></a>
		</div>
    </form>
   
	<!--地址操作js包含引用-->
	<?php if(isset($list['invoice_type']) && ($list['invoice_type'] == 1)) {?>
	<?php $this->load->view("admin/invoice_address_js.php") ?>
	<script type="text/javascript">
		var _jsNum      = <?php echo empty($list['js_invoicenum']) ? 0 : 1;?>;
		var invoiceType = 1;
		var _country    = '<?php echo empty($list['addressinfo']['country']) ? 156 : $list['addressinfo']['country'];?>';
		var _provice    = '<?php echo empty($list['addressinfo']['provice']) ? 0 : $list['addressinfo']['provice'];?>';
		var _city       = '<?php echo empty($list['addressinfo']['city']) ? 0 : $list['addressinfo']['city'];?>';
		var _area       = '<?php echo empty($list['addressinfo']['area']) ? 0 : $list['addressinfo']['area'];?>';
		
		if (_jsNum <= 0) {
			var _display    = 1;
		} else {
			var _display    = $('#together').attr('checked') ? 1 : 0;
		}
		
		$(function() {
			'use strict'; address_list(_country, _provice, _city, _area, _display);
			<?php if(empty($list['addressinfo']['country'])) { echo "$('#box_country option:first').prop('selected', 'selected').siblings().removeAttr('selected');";}?> 
		});
	</script>
	<?php } else {?>
	<script type="text/javascript">
		var invoiceType = 2;
	</script>
	<?php }?>
	<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
	<script type="text/javascript">
		// 验证
		var invoicenum = $('#invoice_num');
		var uid        = $('#uid');
		var start      = $('#start');
		var end        = $('#end');
		var invoice_total_money = $('#invoice_total_money');
		var invoice_fact_total_money = $('#invoice_fact_total_money');
		var invoice_top = $('#invoice_top');

		var invoice_send_email = $('#invoice_send_email');
		var mobile = $('#mobile');
		var box_country = $('#box_country');

		var timeEx   = /^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/;
		var uidEx    = /^[1-9]\d*$/;
		var priceEx  = /(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/;
		var emailEx  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var mobileEx = /^1[3|4|5|7|8][0-9]\d{4,8}$/;
		var currentYear = new Date().getFullYear();

		var county_name_type 	= $('#invoice_county_name_type');
		var identify_type 		= $('#invoice_identify_type');
		var bank_name_type 		= $('#invoice_bank_name_type');
		var bank_count_type 	= $('#invoice_bank_count_type');
		var company_address_type = $('#invoice_company_address_type');
		var company_phone_type 	= $('#invoice_company_phone_type');
		
		total();

		start.blur(function() {
			if (end.val() == '' || end.val() == undefined) end.val(threedayDate(start.val(), 30, 1));
			getInvoiceInfo();
		});

		// ajax 获取订单信息
		end.blur(function() {
			// 自动填充 开始时间
			start.val(threedayDate(end.val(), 30));
			getInvoiceInfo();
		});

		// 发票形式处理
		
		$('.invoices').click(function() {
			if ($(this).attr('checked')) {
				$('#invoice_form_error').text('');
				// 纸质发票
				if ($(this).val() == 1) {
					$('#email').hide();
					$('.invoiceBox').show();
					$('.invoiceBoxPublic').show();
					invoiceType = 1;
				} else {
					$('.invoiceBox').hide();
					$('.invoiceBoxPublic').show();
					$('#email').show();
					invoiceType = 2;
				}
			} else {
				$('#email').hide();
				$('.invoiceBox').hide();
				$('.invoiceBoxPublic').hide();
				invoiceType = 0;
			}
		});

		$('select.select').live('change', function() {
			if (invoiceType == 1 && $(this).val() != 0 && $(this).attr('id') != 'box_country') {
				var areaid    = $('#box_addr_lv4').val();
				var cityid    = $('#box_addr_lv3').val();
				var proviceid = $('#box_addr_lv2').val();
				autoExpressFree(areaid, cityid, proviceid, $('#express_arrive').val());
			}
		});
		
		$('#together').click(function() {
			if (_jsNum > 0) {
				if ($(this).attr('checked')) {
					$('#express_arrive').attr('disabled', true);
					$('#express_free').val(0).attr('disabled', true);
					$('#express_free_us').val(0).attr('disabled', true);
					
					$('#box_country').attr('disabled', true);
					$('#box_addr_lv2').attr('disabled', true);
					$('#box_addr_lv3').attr('disabled', true);
					$('#box_addr_lv4').attr('disabled', true);
					$('#box_addr_detail').attr('disabled', true);
					
					$('#js_invoicenum').attr('readonly', false);
				} else {
					$('#express_arrive').attr('disabled', false);
					$('#express_free').val('').attr('disabled', false);
					$('#express_free_us').val('').attr('disabled', false);
					
					$('#box_country').attr('disabled', false);
					$('#box_addr_lv2').attr('disabled', false);
					$('#box_addr_lv3').attr('disabled', false);
					$('#box_addr_lv4').attr('disabled', false);
					$('#box_addr_detail').attr('disabled', false);
					$('#js_invoicenum').val('');
					$('#js_invoicenum').attr('readonly', true);
				}
			}
		});

		// 切换到达类型是调用
		$('#express_arrive').change(function() {
			if (invoiceType == 1 && $(this).val() != 0) {
				var areaid    = $('#box_addr_lv4').val();
				var cityid    = $('#box_addr_lv3').val();
				var proviceid = $('#box_addr_lv2').val();
				autoExpressFree(areaid, cityid, proviceid, $('#express_arrive').val());
			}
		});

		// 人民币转换成美元
		$('#express_free').keyup(function() {
			if (invoiceType == 1) {
				var cur_rate = '<?php echo $us_rate;?>';
				var curValue = $(this).val();
				curValue     = (curValue > 100) ? 100 : curValue;
				$(this).val(curValue);
				var mymoney  = number_format(curValue / cur_rate,2,'.','');
				mymoney      = (curValue == 0) ? 0 : mymoney;
				$('#express_free_us').val(mymoney);
			}
		});
		
		$('#express_free').blur(function() {
			if (invoiceType == 1) {
				var cur_rate = '<?php echo $us_rate;?>';
				var curValue = $(this).val();
				var freelimit= $('#free_limit').val();
				curValue     = (curValue > 100) ? 100 : curValue;
				$(this).val(curValue);
				if (Math.round(curValue * 100) < Math.round(freelimit * 100)) {
					$('#express_free_error').text('输入的运费有误,当前地区运费必须>=' + freelimit);
				} else {
					$('#express_free_error').text('');
				}
				var mymoney  = number_format(curValue / cur_rate,2,'.','');
				mymoney      = (curValue == 0) ? 0 : mymoney;
				$('#express_free_us').val(mymoney);
			}
		});
		
		var ischecked = false;
		// 提交表单
		$('#invoiceAjax').submit(function() {
			
			// 验证开票单号
			if (invoicenum.val() == '') {
				$('#invoice_number_error').text('<?php echo lang('please_input').lang('invoice_number');?>');
				return false;
			}
			$('#invoice_number_error').text('');

			// 验证会员id
			if (uid.val() == '') {
				$('#uid_error').text('<?php echo lang('please_input').lang('member_id');?>');
				return false;
			} else if (!uidEx.test(uid.val())) {
				$('#uid_error').text('<?php echo lang('format_is_not_correct');?>');
				return false;
			}
			$('#uid_error').text('');

			// 检查输入的会员id是否存在
			$.getJSON('/admin/invoice/check_uid_ajax', {uid :uid.val()}, function(res) {
				if (res.success) {
					$('#uid_error').text('');
				} else {
					$('#uid_error').text(res.msg);
				}
				return false;
			});

			// 验证开票时间
			if (start.val() == '' || end.val() == '') {
				$('#invoice_time_error').text('<?php echo lang('please_input').lang('invoice_time');?>');
				return false;
			}
			var currentYearTime   = Date.parse(currentYear);
			var tempstartYearTime = Date.parse(new Date(start.val()));
			var tempendYearTime   = Date.parse(new Date(end.val()));
			if (tempstartYearTime < currentYearTime || tempendYearTime < currentYearTime) {
				$('#invoice_time_error').text('输入日期无效,年份不能小于' + currentYear);
				return false;
			}
			$('#invoice_time_error').text('');

			if ($('#isempty').val() == 0) getInvoiceInfo();

			// 验证开票金额
			if (invoice_total_money.val() == '' || invoice_fact_total_money.val() == '') {
				$('#invoice_money_error').text('<?php echo lang('please_input').lang('money');?>');
				return false;
			} else if (!priceEx.test(invoice_total_money.val()) || !priceEx.test(invoice_fact_total_money.val())) {
				$('#invoice_money_error').text('<?php echo lang('format_is_not_correct');?>');
				return false;
			}
			$('#invoice_money_error').text('');


			if($("input[name='invoice_type_2']:checked").val()==1){

				// 验证开票抬头
				if (invoice_top.val() == '') {
					$('#invoice_top_error').text('<?php echo lang('please_input').lang('invoice_top');?>');
					return false;
				}
				$('#invoice_top_error').text('');

				//验证纳税识别号
				if($("input[name='invoice_title_type']:checked").val()==1){

					if($('#invoice_taxpayer_id_number').val()==''){
						$('#invoice_taxpayer_id_number_error').text('<?php echo lang('please_input').lang('invoice_taxpayer_id_number_error');?>');
						return false;
					}

				}
				$('#invoice_taxpayer_id_number_error').text('');


			}else if($("input[name='invoice_type_2']:checked").val()==2){

				//验证发票类型
				if(county_name_type.val()==''){
					$('#invoice_county_name_type_error').text('<?php echo lang('invoice_county_name_type_error');?>');
					return false;
				}
				$('#invoice_county_name_type_error').text('');

				if(identify_type.val()==''){
					$('#invoice_identify_type_error').text('<?php echo lang('invoice_identify_type_error');?>');
					return false;
				}
				$('#invoice_identify_type_error').text('');

				if(bank_name_type.val()==''){
					$('#invoice_bank_name_type_error').text('<?php echo lang('invoice_bank_name_type_error');?>');
					return false;
				}
				$('#invoice_bank_name_type_error').text('');

				if(bank_count_type.val()==''){
					$('#invoice_bank_count_type_error').text('<?php echo lang('invoice_bank_count_type_error');?>');
					return false;
				}
				$('#invoice_bank_count_type_error').text('');

				if(company_address_type.val()==''){
					$('#invoice_company_address_type_error').text('<?php echo lang('invoice_company_address_type_error');?>');
					return false;
				}
				$('#invoice_company_address_type_error').text('');

				if(company_phone_type.val()==''){
					$('#invoice_company_phone_type_error').text('<?php echo lang('invoice_company_phone_type_error');?>');
					return false;
				}
				$('#invoice_company_phone_type_error').text('');

			}else{
				$('#invoice_type_form_error').text('<?php echo lang('invoice_type_form_error');?>');
				return false;
			}
			$('#invoice_type_form_error').text('');


			// 验证发票形式
			if (invoiceType == 0) {
				$('#invoice_form_error').text('<?php echo lang('please_select_invoice_form');?>');
				return false;
			}
			$('#invoice_form_error').text('');

			// 如果是电子发票
			if (invoiceType == 2) {
				// 验证收票邮箱
				if (invoice_send_email.val() == '') {
					$('#invoice_send_email_error').text('<?php echo lang('please_input').lang('invoice_send_email');?>');
					return false;
				} else if (!emailEx.test(invoice_send_email.val())) {
					$('#invoice_send_email_error').text('<?php echo lang('format_is_not_correct');?>');
					return false;
				}
				$('#invoice_send_email_error').text('');
			} else if (invoiceType == 1) {
				// 验证收票地址
				if (box_country.val() == 0 && !$('#together').attr('checked')) {
					$('#address_error').text('<?php echo lang('please_select').lang('country');?>');
					return false;
				} else if ($('#box_addr_lv2').val() == 0 && !$('#together').attr('checked')) {
					$('#address_error').text('<?php echo lang('please_select').'省份';?>');
					return false;
				} else if($('#box_addr_lv3').val() == 0 && !$('#together').attr('checked')) {
					$('#address_error').text('<?php echo lang('please_select').'城市';?>');
					return false;
				} else if ($('#box_addr_lv4').val() == 0 && !$('#together').attr('checked')) {
					$('#address_error').text('<?php echo lang('please_select').'区/县';?>');
					return false;
				} else if ($('#box_addr_detail').val() == '' && !$('#together').attr('checked')) {
					$('#address_error').text('<?php echo lang('please_input').'详细地址';?>');
					return false;
				}
				
				// 验证如果勾选了与其他发票一起寄送则验证开票单号是否正确
				if ($('#together').attr('checked')) {
					var fpexg = /^KP\d{16}$/;
					if ($('#js_invoicenum').val() == '') {
						$('#address_error').text('请填写开票单号');
						return false;
					} else if (!fpexg.test($('#js_invoicenum').val())) {
						$('#address_error').text('开票单号格式有误');
						return false;
					} else if ($('#js_invoicenum').val() == $('#invoice_num').val()) {
						$('#address_error').text('开票单号有误，您寄送的的开票单号重复');
						return false;
					} else if (!isCheckedInvoiceNum()) {
						$('#address_error').text('开票单号不存在，请核查');
						return false;
					}
				}
				$('#address_error').text('');

				// 验证到达类型
				if ($('#express_arrive').val() == 0) {
					$('#express_free_error').text('<?php echo lang('please_select').lang('express_type');?>');
					return false;
				} else if ($('#express_free').val() == '' && !$('#together').attr('checked')) {
					$('#express_free_error').text('<?php echo lang('please_input').lang('express_free');?>');
					return false;
				} else if ($('#express_free').val() < 6 && !$('#together').attr('checked')) {
					$('#express_free_error').text('输入运费有误,运费不能低于￥6,请重新输入');
					return false;
				} else if (!priceEx.test($('#express_free').val()) && !$('#together').attr('checked')) {
					$('#express_free_error').text('<?php echo lang('format_is_not_correct');?>');
					return false;
				} else if (Math.round($('#express_free').val() * 100) < Math.round($('#free_limit').val() * 100) && !$('#together').attr('checked')) {
					$('#express_free_error').text('输入的运费有误,当前地区运费必须>=' + $('#free_limit').val());
					return false;
				}
				$('#express_free_error').text('');
				// 验证收件人
				if ($('#receive_people').val() == '') {
					$('#receive_people_error').text('<?php echo lang('please_input').lang('receive_people');?>');
					return false;
				}
				$('#receive_people_error').text('');
			}

			// 验证手机号
			if (mobile.val() == '') {
				$('#mobile_error').text('<?php echo lang('please_input').lang('mobile');?>');
				return false;
			} else if (!mobileEx.test(mobile.val())) {
				$('#mobile_error').text('<?php echo lang('format_is_not_correct');?>');
				return false;
			}
			$('#mobile_error').text('');

			// 没有开票数据阻止订单提交
			if ($('#isempty').val() == 0) {
				layer.msg('当前无有效开票订单,请核实后重新提交');
				return false;
			}

			// 提交表单
			$(this).attr('disabled', true);
			$(this).ajaxSubmit({
				dataType: 'json',
				success: function(res) {
					if (res.success) {
						layer.msg(res.msg);
						setTimeout(function(){
                            location.href = "/admin/invoice";
                        },2000);
					} else {
						$.thinkbox(errboxHtml(res.msg));
					}
				},
				error: function() {
					layer.open({
					  type: 4,
					  title: false,
					  closeBtn: 1,
					  shadeClose: true,
					  skin: 'layui-layer layui-anim layui-layer-dialog layui-layer-border layui-layer-msg layui-layer-hui',
					  content: '<?php echo lang('admin_request_failed')?>'
					});
				},
				beforeSend: function() {
					lis = layer.load();
				},
				complete: function() {
					layer.close(lis);
					$(this).attr('disabled', false);
				}
			});
			return false;
		});
		
		// 检查开票单号是否存
		function isCheckedInvoiceNum() {
			var ischecked = false;
			$.ajaxSettings.async = false;
			$.getJSON('/admin/invoice/checkinvoicenum', {invoicenum: $('#js_invoicenum').val()}, function(res) {
				if (res.success) ischecked = true;
			});
			return ischecked;
		}

		// 获取可开票信息
		function getInvoiceInfo() {
			if (uid.val() && uidEx.test(uid.val()) && timeEx.test(end.val()) && timeEx.test(start.val())) {
				// 清除数据
				$('#invoice_time_error').text('');
				$('#orderlist').html('');
				$('#invoice_detail').html('');
				$('#invoice_order_error').text('');
				$('#invoice_total_money').val('');
				$('#invoice_fact_total_money').val('');
				$('#invoice_money_error').val('');

				var li;
				$.ajax({
					url: '/admin/invoice/get_uc_orderinfo_ajax',
					type: 'POST',
					dataType: 'json',
					data: {uid: uid.val(), start: start.val(), end: end.val()},
					success: function(res) {
						if (res.success) {
							$('#invoice_total_money').val(res.total_money);
							$('#invoice_fact_total_money').val(res.total_money);
							$('#orderlist').html(createOrderListHtml(res.orderinfo));
							$('#invoice_detail').html(createOrderDetailHtml(res.detailist));
							total();
							$('#isempty').val('1');
						} else {
							$('#invoice_order_error').text(res.msg);
							$('#isempty').val('0');
						}
					},
					error: function() {
						layer.open({
						  type: 4,
						  title: false,
						  closeBtn: 1,
						  shadeClose: true,
						  skin: 'layui-layer layui-anim layui-layer-dialog layui-layer-border layui-layer-msg layui-layer-hui',
						  content: '<?php echo lang('admin_request_failed')?>'
						});
					},
					beforeSend: function() {
						li = layer.load();
					},
					complete: function() {
						layer.close(li);
					}
				});
			}
		}

		// 计算运费
		function autoExpressFree(areaId, cityId, proviceId, typeId) {
			if (!areaId && !cityId && !proviceId) return false;
			$.getJSON('/admin/invoice/sf_express_free_ajax', {areaid: areaId, cityid: cityId, proviceid: proviceId, type: typeId}, function(res) {
				if (res.success) {
					$('#express_free').val(res.zh_free);
					$('#express_free_us').val(res.us_free);
				} else {
					layer.msg(res.msg);
				}
			});
		}

		// 创建可开票订单列表
		function createOrderListHtml(lists) {
			var ORDERLISTHTML = '';
			ORDERLISTHTML += '<table class="table">';

			ORDERLISTHTML += '<tr>';
			ORDERLISTHTML += '<th><?php echo lang('admin_order_id');?></th>';
			ORDERLISTHTML += '<th><?php echo lang('admin_order_prop');?></th>';
			ORDERLISTHTML += '<th><?php echo lang('invoice_money');?></th>';
			ORDERLISTHTML += '<th><?php echo lang('choose2');?></th>';
			ORDERLISTHTML += '<th><?php echo lang('label_feedback_state');?></th>';
			ORDERLISTHTML += '<th><?php echo lang('admin_order_operate');?></th>';

			// 循环输出内容 新增商品名称和数量
			$.each(lists, function(index, items) {
				ORDERLISTHTML += '<tr>';

				ORDERLISTHTML += '<td>'+ items.order_id +'</td>';
				ORDERLISTHTML += '<td>'+ items.order_type_map +'</td>';
				ORDERLISTHTML += '<td>'+ items.order_money +'</td>';
				ORDERLISTHTML += '<td>'+ items.free +'</td>';
				ORDERLISTHTML += '<td>'+ items.status_map +'</td>';
				ORDERLISTHTML += '<td>';
				ORDERLISTHTML += '<input type="hidden" name="orderlist[]" value="'+items.order_id+'|'+items.order_type+'|'+items.order_money+'|'+items.free+'|'+items.status+'|'+ items.cateids +'" />';
				ORDERLISTHTML += '<input class="checked" type="checkbox" id="'+ items.order_id +'" name="orderlistchecked[]" cateids="'+ items.cateids +'" price="'+ items.order_money +'" value="'+items.order_id+'" checked />';
				ORDERLISTHTML += '</td>';

				ORDERLISTHTML += '</tr>';
				
				if (items.goods_list != undefined && items.goods_list) {
					ORDERLISTHTML += '<tr>';
					ORDERLISTHTML += '<td class="text-danger"><?php echo lang('goods_list');?></td>';
					ORDERLISTHTML += '<td class="text-info" colspan="5">';
					
					ORDERLISTHTML += '<table class="table">';
					ORDERLISTHTML += '<tr>';
					ORDERLISTHTML += '<td colspan="4" style="text-align:left;"><?php echo lang('label_goods_name');?></td>';
					ORDERLISTHTML += '<td><?php echo lang('admin_order_goods_quantity');?></td>';
					ORDERLISTHTML += '</tr>';
					$.each(items.goods_list, function(gindex, gitems) {
						// 商品列表循环
						ORDERLISTHTML += '<tr>';
						ORDERLISTHTML += '<td colspan="4" style="text-align:left; max-width:270px;">'+ gitems.name +'</td>';
						ORDERLISTHTML += '<td>'+ gitems.number +'</td>';
						ORDERLISTHTML += '</tr>';
					});
					ORDERLISTHTML += '</table>';
					ORDERLISTHTML += '</td>';
					ORDERLISTHTML += '</tr>';
				}
			});
			

			ORDERLISTHTML += '</tr>';

			ORDERLISTHTML += '</table>';

			return ORDERLISTHTML;
		}

		// 创建开票明细
		function createOrderDetailHtml(detailist) {
			var DEILHTMLS = '';

			// 循环输出内容
			$.each(detailist, function(index, rows) {
				DEILHTMLS += '<div class="detail-rows">';

				DEILHTMLS += '<div class="detail-rows"><label class="input-group-addon">';
				DEILHTMLS += '<span class="require">*</span>';
				DEILHTMLS += '<?php echo lang('invoice_detail'); ?>：';
				DEILHTMLS += '</label>'+ rows.cate_name +'</div>';

				DEILHTMLS += '<div class="detail-rows"><label class="input-group-addon">';
				DEILHTMLS += '<span class="require">*</span>';
				DEILHTMLS += '<?php echo lang('invoice_detail_money'); ?>：';

				$.each(rows.orderids, function(sindex, srows) {
					DEILHTMLS += '<input type="hidden" cate_id="'+ index +'" name="subdetailist[]" id="subdetail_'+ srows.order_id +'_'+ index +'" value="'+ srows.price +'" />';
				});

				DEILHTMLS += '</label><m id="m_'+ index +'">'+ rows.money +'</m></div>';

				DEILHTMLS += '<input type="hidden" id="detail_'+ index +'" cate_name="'+ rows.cate_name +'" price="'+ rows.money +'" name="detailinfo[]" value="'+ rows.cate_name +':@'+ rows.money +':@'+ index +':@'+ rows.attach +'" />';
				DEILHTMLS += '</div>';
			});

			return DEILHTMLS;
		}

		/**
		 * 将字符串转为数字
		 */
		var parse2num = function(s)
		{
		    if(undefined == s)
		    {
		        return 0;
		    }
		    for(var i=0;i<20;i++)
		    {
		        s = s.replace(/[^\d\.]/i,"");
		    }
		    return parseFloat(s);
		}
		
		// 选中计算金额
		function total() {
			$('.checked').each(function() {
				$(this).click(function() {
					$('#invoice_fact_total_money').val(selectCheckedBoxTotalMoney());
					selectDetailMoney(this);
				});
			});
		}

		// 计算选中的总金额
		function selectCheckedBoxTotalMoney() {
			var totalMoney = 0;
			$('.checked').each(function() {
				if ($(this).attr('checked')) {
					var cuprice = parse2num($(this).attr('price'));
					if (NaN != cuprice && undefined != cuprice && 0 != cuprice) {
						totalMoney += cuprice;
					}
				}
			});
			//取小数点后两位
			totalMoney = Math.round(totalMoney * 100) / 100;
			totalMoney = number_format(totalMoney, 2, '.', '');
			return totalMoney;
		}

		// 开票明细价格
		function selectDetailMoney(self) {
			var orderid    = $(self).attr('id');
			var cateids    = $(self).attr('cateids');
			var cateArry   = new Array();
			var totalMoney = 0.00;

			if (cateids != undefined && cateids.indexOf(',')) cateArry = cateids.split(',');

			// 一个订单可能会对应多个分类
			if ($(self).attr('checked')) {
				// 如果是数组
				if (cateArry != undefined && cateArry.length != 0) {
					for (var i in cateArry) {
						var detailTempPrice = $('#detail_' + cateArry[i]).attr('price');
						var detailTempValue = $('#detail_' + cateArry[i]).val();
						var detailTempArray = new Array();
						var tempPrice       = $('#subdetail_' + orderid + '_' + cateArry[i]).val();

						// 字符串转换成数字
						detailTempPrice     = parse2num(detailTempPrice);
						tempPrice           = parse2num(tempPrice);

						// 将浮点数转换成整数计算
						detailTempPrice     = Math.round(detailTempPrice * 100);
						tempPrice           = Math.round(tempPrice * 100);

						totalMoney          = ((detailTempPrice + tempPrice) / 100);
						totalMoney          = number_format(totalMoney, 2, '.', '');

						// 将字符串分割成数组
						detailTempArray     = detailTempValue.split(':@');
						// 拼接value的值
						var detailString    = detailTempArray[0] + ':@' + totalMoney + ':@' + detailTempArray[2] + ':@' + detailTempArray[3];

						// 设置总价格
						$('#detail_' + cateArry[i]).attr('price', totalMoney);
						$('#detail_' + cateArry[i]).val(detailString);
						$('#m_' + cateArry[i]).text(totalMoney);
						
						// 显示分类
						if (totalMoney > 0) $('#detail_' + cateArry[i]).parent().show();
					}

				}

			} else {
				// 如果是数组
				if (cateArry != undefined && cateArry.length) {
					for (var i in cateArry) {
						var detailTempPrice = $('#detail_' + cateArry[i]).attr('price');
						var detailTempValue = $('#detail_' + cateArry[i]).val();
						var detailTempArray = new Array();
						var tempPrice       = $('#subdetail_' + orderid + '_' + cateArry[i]).val();

						// 字符串转换成数字
						detailTempPrice     = parse2num(detailTempPrice);
						tempPrice           = parse2num(tempPrice);

						// 将浮点数转换成整数计算
						detailTempPrice     = Math.round(detailTempPrice * 100);
						tempPrice           = Math.round(tempPrice * 100);

						totalMoney          = ((detailTempPrice - tempPrice) / 100);
						totalMoney          = number_format(totalMoney, 2, '.', '');

						// 将字符串分割成数组
						detailTempArray     = detailTempValue.split(':@');
						// 拼接value的值
						var detailString    = detailTempArray[0] + ':@' + totalMoney + ':@' + detailTempArray[2] + ':@' + detailTempArray[3];

						// 设置总价格
						$('#detail_' + cateArry[i]).attr('price', totalMoney);
						$('#detail_' + cateArry[i]).val(detailString);
						$('#m_' + cateArry[i]).text(totalMoney);
						
						// 隐藏分类
						if (totalMoney <= 0) $('#detail_' + cateArry[i]).parent().hide();
					}
				}
			}
		}

		/**
		 * number_format
		 *
		 * @param int or float number
		 * @param int          decimals
		 * @param string       dec_point
		 * @param string       thousands_sep
		 * @return string
		 */
		var number_format = function(number, decimals, dec_point, thousands_sep) {
		    var n = !isFinite(+number) ? 0 : +number,
		    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		    s = '',
		    toFixedFix = function (n, prec) {
		        var k = Math.pow(10, prec);
		        return '' + Math.round(n * k) / k;        };
		    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
		    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		    if (s[0].length > 3) {
		        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		    }
		    if ((s[1] || '').length < prec) {
		        s[1] = s[1] || '';
		        s[1] += new Array(prec - s[1].length + 1).join('0');
		    }
		    return s.join(dec);
		}
		
	
		var threedayDate = function(currentDate, jday, fh) {
			if (currentDate == undefined || currentDate == '' || currentDate == null) return '';
			var _date = new Date(currentDate);

			// 获取当前输入日期的前30天日期
			(fh == 0 || fh == undefined || fh == null) ? _date.setDate(_date.getDate() - jday) : _date.setDate(_date.getDate() + jday);
		   var _Y = _date.getFullYear();
		   
		   //获取当前月份的日期，不足10补0  
		   var _M = ((_date.getMonth() + 1) < 10) ? "0" + (_date.getMonth() + 1) : (_date.getMonth() + 1);
		   var _D = (_date.getDate() < 10) ? "0" + _date.getDate() : _date.getDate();
		   
		   return _Y + '-' + _M + '-' + _D;
		}

		function errboxHtml(msg) {
			return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
		}
		
		// 发票作废
		$('#tovoid').click(function() {
			layer.confirm("<textarea id='rejectremark' placeholder='请填写原因'></textarea>", {
				icon: 3,
				title: "作废理由：",
				closeBtn: 2,
				btn: ['确定', '取消']
			}, function() {
				var remark = $('#rejectremark').val();
				$.ajax({
					type: "POST",
					url: "/admin/invoice/ajaxcannel",
					dataType: "json",
					data:{invoice_num:invoicenum.val(), reject:remark, status: 9},
					success: function (res) {
						if (res.success) {
							location.href = "/admin/invoice";
							layer.msg(res.msg);
						}else{
							layer.msg(res.msg);
						}
					}
				});
			});
		});
	</script>
	<?php } else { ?>
	<div class="leftbox">
		<div class="input-group">
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_number');?>：</label>
			<input type="text" class="form-control" id="invoice_number" name="invoice_number" value="<?php echo $list['invoice_num'];?>" readonly="true" />
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('member_id');?>：</label>
			  <input type="text" class="form-control" id="uid" name="uid" value="<?php echo $list['uid'];?>" readonly="true" />
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_time');?>：</label>
			  <input type="text" class="form-control Wdate" id="start" name="start" placeholder="<?php echo lang('start_date'); ?>" readonly="true" value="<?php echo $list['invoice_start_time'];?>" />&nbsp;-&nbsp;
			  <input type="text" class="form-control Wdate" id="end" name="end" placeholder="<?php echo lang('end_date'); ?>" readonly="true" value="<?php echo $list['invoice_end_time'];?>" />
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_total_money');?>：</label>
			  <input type="text" class="form-control" id="invoice_total_money" name="invoice_total_money" value="<?php echo $list['invoice_total_money'];?>" readonly="true" />
			  <label class="input-group-addon lright10"><span class="require">*</span><?php echo lang('invoice_fact_total_money');?>：</label>
			  <input type="text" class="form-control" id="invoice_fact_total_money" name="invoice_fact_total_money" value="<?php echo $list['invoice_fact_money'];?>" readonly="true" />
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_order');?>：</label>
			  <table class="table">
				  <tbody>
				  <tr>
					<th><?php echo lang('admin_order_id');?></th>
					<th><?php echo lang('admin_order_prop');?></th>
					<th><?php echo lang('invoice_money');?></th>
					<th><?php echo lang('choose2');?></th>
					<th><?php echo lang('label_feedback_state');?></th>
					<th><?php echo lang('admin_order_operate');?></th>
				  </tr>
				  <?php foreach($list['orderlist'] as $order) {?>
				  <tr>
					<td><?php echo $order['order_id'];?></td>
					<td><?php echo $order_type[$order['order_type']];?></td>
					<td><?php echo number_format($order['money'] / 100, 2, '.', '');?></td>
					<td><?php echo $order['express_free'];?></td>
					<td><?php echo $status_select[$order['status']];?></td>
					<td><input type="checkbox" <?php if($order['mark']) { echo 'checked="checked"'; } ?> disabled="disabled" /></td>
				  </tr>
				  <?php if (isset($order['goods_list']) && $order['goods_list']) {?>
				  
				  <tr>
					<td class="text-danger"><?php echo lang('goods_list');?></td>
					<td class="text-info" colspan="5">
						<table class="table">
							<tr>
								<td colspan="4" style="text-align:left;"><?php echo lang('label_goods_name');?></td>
								<td><?php echo lang('admin_order_goods_quantity');?></td>
							</tr>
							<?php foreach($order['goods_list'] as $goodslist) {?>
							<tr>
								<td colspan="4" style="text-align:left; max-width:270px;"><?php echo $goodslist['goods_name'];?></td>
								<td><?php echo $goodslist['goods_number'];?></td>
							</tr>
							<?php }?>
						</table>
					</td>
				  </tr>
				  <?php }?>
				  <?php }?>
				  </tbody>
			  </table>
		</div>

		<!-- 开票明细 -->
		<?php if(isset($list['details']) && $list['details']) {?>
		<div id="invoice_detail">
			<?php foreach($list['details'] as $detail) {?>
			<?php if($detail['money'] > 0) {?>
			  <div class="detail-rows">
				<div class="dinline">
					<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_detail');?>：</label><?php echo $detail['cate_name'];?>
				</div>
				<div class="dinline">
					<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_detail_money');?>：</label><?php echo $detail['money'];?>
				</div>
			  </div>
			<?php }}?>
		</div>
		<?php }?>

		<!-- 发票类型 -->
		<div class="input-group">
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_type_2');;?>：</label>
			<input type="radio" class="form-control invoice_type" id="invoice_type_common" name="invoice_type_2" placeholder="" value="1" <?php if($list['invoice_type_2'] == 1) { echo 'checked="checked"'; } ?>  disabled="disabled"/> <?php echo lang('invoice_type_common');?>
			<input type="radio" class="form-control mleft invoice_type" id="invoice_type_tax" name="invoice_type_2" value="2" placeholder="" <?php if($list['invoice_type_2'] == 2) { echo 'checked="checked"'; } ?> disabled="disabled"/> <?php echo lang('invoice_type_tax');?>
			<span class="require" id="invoice_type_form_error"></span>
		</div>

		<div class="invoice_type_top" style="<?php echo $list['invoice_type_2']==1 ? '':'display: none;' ?>">

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_title_type');?>：</label>
			<input type="radio" class="form-control invoice_title_type" id="invoice_title_type_1" name="invoice_title_type" autocomplete="off" value="1" placeholder="" <?php if($list['invoice_taxpayer_id_number'] != 0 ) { echo 'checked="checked"'; } ?> disabled="disabled"/> <?php echo lang('invoice_title_type_company');?>
			<input type="radio" class="form-control mleft invoice_title_type" id="invoice_title_type_2" name="invoice_title_type" autocomplete="off" value="2" placeholder="" <?php if($list['invoice_taxpayer_id_number'] == 0 ) { echo 'checked="checked"'; } ?> disabled="disabled" /> <?php echo lang('invoice_title_type_personage');?><br/>


			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_top');?>：</label>
			<input type="text" class="form-control" id="invoice_top" name="invoice_top" value="<?php echo $list['invoice_head'] ? $list['invoice_head'] : '';?>" readonly="true"/>
			<span class="require" id="invoice_top_error"></span>

			<div class="invoice_taxpayer_id_number_box" style="<?php echo $list['invoice_taxpayer_id_number'] != 0 ? '':'display: none;' ?>">
				<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_taxpayer_id_number');?>：</label>
				<input type="text" class="form-control" id="invoice_taxpayer_id_number" name="invoice_taxpayer_id_number" placeholder="" value="<?php echo $list['invoice_taxpayer_id_number']; ?>" readonly="true"/>
			</div>

		</div>

		<div class="invoice_type_tax_box" style="<?php echo $list['invoice_type_2']==2 ? '':'display: none;' ?>">
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_country_name_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_county_name_type']:''; ?>" class="form-control" id="invoice_county_name_type" name="invoice_county_name_type" maxlength="100" placeholder="" readonly="true"/>
			<span class="require" id="invoice_county_name_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_identify_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_identify_type']:''; ?>" class="form-control" id="invoice_identify_type" name="invoice_identify_type" maxlength="100" placeholder="" readonly="true"/>
			<span class="require" id="invoice_identify_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_bank_name_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_bank_name_type']:''; ?>" class="form-control" id="invoice_bank_name_type" name="invoice_bank_name_type" maxlength="100" placeholder="" readonly="true"/>
			<span class="require" id="invoice_bank_name_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_bank_count_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_bank_count_type']:''; ?>" class="form-control" id="invoice_bank_count_type" name="invoice_bank_count_type" maxlength="100" placeholder="" readonly="true"/>
			<span class="require" id="invoice_bank_count_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_company_address_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_company_address_type']:''; ?>" class="form-control" id="invoice_company_address_type" name="invoice_company_address_type" maxlength="100" placeholder="" readonly="true"/>
			<span class="require" id="invoice_company_address_type_error"></span><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_company_phone_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_company_phone_type'] :''; ?>" class="form-control" id="invoice_company_phone_type" name="invoice_company_phone_type" maxlength="100" placeholder="" readonly="true"/>
			<span class="require" id="invoice_company_phone_type_error"></span>

		</div>

		<!-- 发票形式 -->
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_form');?>：</label>
			  <input type="radio" class="form-control invoices" id="invoice_paper" name="invoices" value="1" <?php if($list['invoice_type'] == 1) { echo 'checked="checked"'; } ?> disabled="disabled" /> <?php echo lang('invoice_paper');?>
			  <input type="radio" class="form-control mleft invoices" id="invoice_electron" name="invoices" value="2" <?php if($list['invoice_type'] == 2) { echo 'checked="checked"'; } ?> disabled="disabled" /> <?php echo lang('invoice_electron');?>
		</div>

		<!-- 纸质发票 -->
		<?php if($list['invoice_type'] == 1) {?>
		<div class="invoiceBox">
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_address');?>：</label>
				  <div class="address" id="box_addr">
						<select class="select" id="box_country" name="country" onchange="cb_box_country();">
							<option value="0"><?php echo lang('checkout_addr_country'); ?></option>
						</select>
				  </div>
			</div>
			<?php if(isset($list['addressinfo']['address'])) {?>
			<div class="input-group textarea">
				  <label class="input-group-addon"></label>
				  <textarea type="text" name="address" class="xxidz" id="box_addr_detail" maxlength="255"
					  placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>" readonly="true"><?php echo $list['addressinfo']['address'];?></textarea>
					  
				  <?php if(isset($list['js_invoicenum']) && $list['js_invoicenum']) {?>
				  <span class="together mleft">  
					  <input id="together" name="together" value="1" type="checkbox" checked="checked">&nbsp;与其它发票一起寄送<br>
					  <label style="display:inline-block;"><span class="require">*</span>开票单号：</label><input id="js_invoicenum" name="js_invoicenum" type="text" value="<?php echo $list['js_invoicenum']?>" />
				  </span>
			      <?php }?>
			</div>
			<?php }?>
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('express_free');?>：</label>
				  <select name="express_arrive" id="express_arrive">
				  	<?php foreach($express_arrive_type as $k => $v) {?>
						<option value="<?php echo $k;?>" <?php if(isset($list['express_arrive']) && $k == $list['express_arrive']) {echo 'selected="selected"';}?>><?php echo $v;?></option>
					<?php }?>
				  </select>
				  ￥&nbsp;<input type="text" class="form-control" id="express_free" name="express_free" value="<?php echo $list['zh_express_free'];?>" readonly="true" />&nbsp;&nbsp;
				  $&nbsp;<input type="text" class="form-control" id="express_free_us" name="express_free_us" value="<?php echo $list['us_express_free'];?>" readonly="true" />&nbsp;<a href="javascript:;" id="see_free"><?php echo lang('see_sf_free_num');?></a>
			</div>
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('receive_people');?>：</label>
				  <input type="text" class="form-control" id="receive_people" name="receive_people" value="<?php echo $list['recipient'];?>" readonly="true" />
			</div>
		</div>
		<?php }?>

		<!-- 电子发票 -->
		<?php if($list['invoice_type'] == 2) {?>
		<div class="input-group" id="email">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_send_email');?>：</label>
			  <input type="text" class="form-control" id="invoice_send_email" name="invoice_send_email" value="<?php echo $list['email'];?>" readonly="true" />
		</div>
		<?php }?>
		<div class="invoiceBoxPublic">
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('mobile');?>：</label>
				  <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $list['mobile'];?>" readonly="true" />
				  <label class="input-group-addon lright10"><?php echo lang('invoice_spare_num');?>：</label>
				  <input type="text" class="form-control" id="invoice_spare_num" name="invoice_spare_num" value="<?php echo $list['backup_num'];?>" readonly="true" />
			</div>
			<?php if(isset($list['remark']) && $list['remark']) {?>
			<div class="input-group">
				  <label class="input-group-addon"><?php echo lang('admin_order_remark');?>：</label>
				  <textarea id="remark" name="remark" rows="3" cols="20" readonly="true"><?php echo $list['remark'];?></textarea>
			</div>
			<?php }?>
		</div>
	</div>
	<?php if($logs) { ?>
	<div class="rightbox">
		<h6><?php echo lang('operation_log');?></h6>
		<ul>
			<?php foreach($logs as $log) {?>
			<li>
				<?php echo $log['created_at'];?>
				<?php echo $log['operator'];?>
				<?php echo $log['remark'];?>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php }?>
	<?php if(isset($list['status']) && $list['status'] != 8) { ?>
	<form class="form-inline" id="invoiceAjaxEdit"  method="post" action="<?php echo base_url('admin/invoice/editsave') ?>">
		<div class="input-group">
			<input type="hidden" id="invoice_num" name="invoice_num" value="<?php echo $list['invoice_num'];?>" />
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_code');?>：</label>
			<span id="codegroup">
			<?php if(isset($list['fpcode']) && $list['fpcode']) {?>
			<?php foreach($list['fpcode'] as $incode) { ?>
			<input type="text" class="form-control fpnum" name="invoice_code[]" value="<?php echo $incode;?>" <?php if($list['status']!=0){echo  'readonly="true"';} ?> />
			<?php }} ?>
			<?php if($list['status']==0 && empty($list['fpcode'])){ ?>
			<input type="text" class="form-control fpnum" id="invoice_code" name="invoice_code[]" />
			<?php }?>
			</span>
			<a href="javascript:;" class="btn add_blacklist"><i class="icon-plus"></i></a>
			<span class="require" id="invoice_code_error"></span>
		</div>
		<div class="input-group" style="<?php echo  $list['status']==0 ? 'display: none;' : '';?>">
			<label class="input-group-addon"><?php if(isset($list['status']) && isset($list['invoice_type']) && $list['status'] == 1 && $list['invoice_type'] == 1) {?><span class="require">*</span><?php }?><?php echo lang('courier_number');?>：</label>
			<input type="text" class="form-control" id="courier_number" name="courier_number" value="<?php echo $list['express_num'];?>" />
			<span class="require" id="courier_number_error"></span>
		</div>
		<div class="input-group">
			<label class="input-group-addon"></label>
			<?php if(isset($list['status']) && empty($list['status'])) {?>
			<button type="button" id="invoiced" class="btn btn-primary action" name="action" value="1"><?php echo lang('invoiced');?></button>
			<?php } elseif (isset($list['status']) && $list['status'] == 1) { ?>
			<button type="button" class="btn btn-primary action mleft" name="action" value="2"><?php echo lang('mailed');?></button>
			<?php }?>
			<?php if (isset($list['status']) && in_array($list['status'],array(0,1,2))) {?>
			<a href="javascript:;" id="reject" v="<?php echo $list['status']; ?>" class="btn btn-primary mleft"><?php echo lang('reject');?></a>
			<?php }?>
		</div>
	</form>
	
	<!--地址操作js包含引用-->
	<?php if (isset($list['addressinfo']) && $list['addressinfo'] && $list['invoice_type'] == 1) {?>
		<?php $this->load->view("admin/invoice_address_js.php") ?>
		<script type="text/javascript">
			$('#see_free').click(function() {
				layer.open({
				  type: 1,
				  title: '预览',
				  closeBtn: 1,
				  area: '880px',
				  skin: 'layui-layer-nobg', //没有背景色
				  shadeClose: true,
				  fixed: false,
				  scrollbar: false,
				  content: '<img src="<?php echo base_url('themes/admin/images/SF_express_free.png'); ?>" />'
				});
			});
			$(function() {
				$('.leftbox select, .leftbox input').attr('disabled', true);
				'use strict'; address_list('<?php echo $list['addressinfo']['country'];?>', '<?php echo $list['addressinfo']['provice'];?>', '<?php echo $list['addressinfo']['city'];?>', '<?php echo $list['addressinfo']['area'];?>', 1);
			});
		</script>
	<?php } ?>
	<script type="text/javascript">
		// 添加表单数据
		$('.add_blacklist').click(function() {
			$('#codegroup').append('<input type="text" class="form-control rmargin5" name="invoice_code[]" />');
		});
		// 提交表单
		$('.action').click(function() {
			var fpErg     = /^[a-zA-Z\d]+$/;
			var curstatus = '<?php echo (isset($list['status']) && $list['status']) ? $list['status'] : 0;?>';
			var invoicetype = '<?php echo (isset($list['invoice_type']) && $list['invoice_type']) ? $list['invoice_type'] : 0;?>';
			var courier_number = $('#courier_number').val();
			var courierErg = /^\d+$/;
			
			if ($('#invoice_code').val() == '' && curstatus == 0) {
				$('#invoice_code_error').text('<?php echo lang('please_input').lang('invoice_code');?>');
				return false;
			} else if (!fpErg.test($('#invoice_code').val()) && curstatus != 1) {
				$('#invoice_code_error').text('发票编号有误,只能包含字母和数字');
				return false;
			} else if (curstatus == 1 && $('#invoice_code').val() != '') {
				if (!fpErg.test($('#invoice_code').val())) {
					$('#invoice_code_error').text('发票编号有误,只能包含字母和数字');
					return false;
				}
			}
			$('#invoice_code_error').text('');
			
			if (curstatus == 1 && courier_number == '' && invoicetype == 1) {
				$('#courier_number_error').text('<?php echo lang('please_input').lang('courier_number');?>');
				return false;
			}

			// 不为空验证格式
			if (courier_number != '') {
				if (!courierErg.test(courier_number)) {
					$('#courier_number_error').text('快递单号格式不正确');
					return false;
				}
			}
			$('#courier_number_error').text('');

			// 提交表单
			$('#invoiceAjaxEdit').ajaxSubmit({
				dataType: 'json',
				data: {active: $(this).val()},
				success: function(res) {
					if (res.success) {
						layer.msg(res.msg);
						setTimeout(function(){
                            location.href = "/admin/invoice/";
                        },2000);
					} else {
						layer.msg(res.msg);
					}
				},
				error: function() {
					layer.open({
					  type: 4,
					  title: false,
					  closeBtn: 1,
					  shadeClose: true,
					  skin: 'layui-layer layui-anim layui-layer-dialog layui-layer-border layui-layer-msg layui-layer-hui',
					  content: '<?php echo lang('admin_request_failed')?>'
					});
				},
				beforeSend: function() {
					lis = layer.load();
				},
				complete: function() {
					layer.close(lis);
				}
			});
			
			return false;
		});
		
		// 取消换货
		$('#reject').click(function() {
			var reject_st	   = $(this).attr('v');
			layer.confirm("<textarea id='rejectremark' placeholder='请填写原因'></textarea>", {
				icon: 3,
				title: "驳回理由：",
				closeBtn: 2,
				btn: ['确定', '取消']
			}, function() {
				var remark = $('#rejectremark').val();
				$.ajax({
					type: "POST",
					url: "/admin/invoice/ajaxcannel",
					dataType: "json",
					data:{invoice_num:$('#invoice_num').val(), reject:remark, status: 8,reject_st:reject_st},
					success: function (res) {
						if (res.success) {
							location.href = "/admin/invoice";
							layer.msg(res.msg);
						}else{
							layer.msg(res.msg);
						}
					}
				});
			});
		});
	</script>
	<?php }} ?>
</div>