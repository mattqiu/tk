<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<style type="text/css">
	.leftbox { position: relative; width:700px; }
	.rightbox { position: absolute; left:740px; top:70px; display:block; }
	.well { position: relative; }
	.input-group { margin: 10px 0; height: 26px; line-height: 26px; display: table; width: 100%; vertical-align:middle;}
	.input-group-addon, .form-control { display: inline-table; }
	.input-group-addon { width: 130px; text-align: right; }
	.input-group input, .input-group select { width:auto !important; margin-right:5px; }
	.require {color:red;}
	.orderlist, .address { display: inline-table;}
	.orderlist table { width: 580px; height: 100px; overflow-y: scroll; }
	.detail-rows {border-bottom: 1px solid #ddd;}
	.detail-rows .dinline { display: block; }
	.mleft { margin-left: 15px !important; }
	.rightbox h6 { text-align:center; margin: auto; }
	#invoice_top { width: 440px !important; }
	.textarea { position:relative; }
	.together { position:absolute; top:0; }
	#remark { min-width: 550px; }
	#invoice_taxpayer_id_number{width: 440px !important;}
</style>
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
	
	<div class="leftbox">
		<div class="input-group">
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_number');?>：</label>
			<input type="text" class="form-control" id="invoice_number" name="invoice_number" value="<?php echo $list['invoice_num'];?>" readonly=true />
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('member_id');?>：</label>
			  <input type="text" class="form-control" id="uid" name="uid" value="<?php echo $list['uid'];?>" readonly=true />
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_time');?>：</label>
			  <input type="text" class="form-control Wdate" id="start" name="start" placeholder="<?php echo lang('start_date'); ?>" disabled="disabled" value="<?php echo $list['invoice_start_time'];?>" />&nbsp;-&nbsp;
			  <input type="text" class="form-control Wdate" id="end" name="end" placeholder="<?php echo lang('end_date'); ?>" disabled="disabled" value="<?php echo $list['invoice_end_time'];?>" />
		</div>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_total_money');?>：</label>
			  <input type="text" class="form-control" id="invoice_total_money" name="invoice_total_money" value="<?php echo $list['invoice_total_money'];?>" disabled="disabled" />
			  <label class="input-group-addon lright10"><span class="require">*</span><?php echo lang('invoice_fact_total_money');?>：</label>
			  <input type="text" class="form-control" id="invoice_fact_total_money" name="invoice_fact_total_money" value="<?php echo $list['invoice_fact_money'];?>" disabled="disabled" />
		</div>
		<?php if(isset($list['orderlist']) && $list['orderlist']) { ?>
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
					<td><?php echo number_format($order['money'] / 100, 2);?></td>
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
		<?php } ?>

		<!-- 开票明细 -->
		<?php if(isset($list['details']) && $list['details']) {?>
		<div id="invoice_detail">
			<?php foreach($list['details'] as $detail) {?>
			<?php if(isset($detail['money']) && $detail['money'] > 0) {?>
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
<!--		<div class="input-group">-->
<!--			  <label class="input-group-addon"><span class="require">*</span>--><?php //echo lang('invoice_top');?><!--：</label>-->
<!--			  <input type="text" class="form-control" id="invoice_top" name="invoice_top" value="--><?php //echo $list['invoice_head'];?><!--" readonly=true />-->
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

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_title_type');?>：</label>
			<input type="radio" class="form-control invoice_title_type" id="invoice_title_type_1" name="invoice_title_type" autocomplete="off" value="1" placeholder="" <?php if($list['invoice_taxpayer_id_number'] != 0 ) { echo 'checked="checked"'; } ?> disabled="disabled"/> <?php echo lang('invoice_title_type_company');?>
			<input type="radio" class="form-control mleft invoice_title_type" id="invoice_title_type_2" name="invoice_title_type" autocomplete="off" value="2" placeholder="" <?php if($list['invoice_taxpayer_id_number'] == 0 ) { echo 'checked="checked"'; } ?> disabled="disabled" /> <?php echo lang('invoice_title_type_personage');?><br/>


			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_top');?>：</label>
			<input type="text" class="form-control" id="invoice_top" name="invoice_top" value="<?php echo $list['invoice_head'] ? $list['invoice_head'] : '';?>" readonly="true"/>

			<div class="invoice_taxpayer_id_number_box" style="<?php echo $list['invoice_taxpayer_id_number'] != 0 ? '':'display: none;' ?>">
				<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_taxpayer_id_number');?>：</label>
				<input type="text" class="form-control" id="invoice_taxpayer_id_number" name="invoice_taxpayer_id_number" placeholder="" value="<?php echo $list['invoice_taxpayer_id_number']; ?>" readonly="true"/>
			</div>

		</div>

		<div class=" invoice_type_tax_box" style="<?php echo $list['invoice_type_2']==2 ? '':'display: none;' ?>">
			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_country_name_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_county_name_type']:''; ?>" class="form-control" id="invoice_county_name_type" name="invoice_county_name_type" maxlength="100" placeholder="" readonly="true"/><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_identify_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_identify_type']:''; ?>" class="form-control" id="invoice_identify_type" name="invoice_identify_type" maxlength="100" placeholder="" readonly="true" /><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_bank_name_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_bank_name_type']:''; ?>" class="form-control" id="invoice_bank_name_type" name="invoice_bank_name_type" maxlength="100" placeholder="" readonly="true"/><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_bank_count_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_bank_count_type']:''; ?>" class="form-control" id="invoice_bank_count_type" name="invoice_bank_count_type" maxlength="100" placeholder="" readonly="true" /><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_company_address_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_company_address_type']:''; ?>" class="form-control" id="invoice_company_address_type" name="invoice_company_address_type" maxlength="100" placeholder="" readonly="true" /><br>

			<label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_company_phone_type');?>：</label>
			<input style="width: 500px;" type="text" value="<?php echo $list['invoice_type_2_content'] ? $list['invoice_type_2_content']['invoice_company_phone_type'] :''; ?>" class="form-control" id="invoice_company_phone_type" name="invoice_company_phone_type" maxlength="100" placeholder="" readonly="true" />

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
					  placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>" readonly=true><?php echo $list['addressinfo']['address'];?></textarea>
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
						<option value="<?php echo $k;?>" <?php if($k == 2) echo 'selected="selected"';?>><?php echo $v;?></option>
					<?php }?>
				  </select>
				  ￥&nbsp;<input type="text" class="form-control" id="express_free" name="express_free" value="<?php echo $list['zh_express_free'];?>" readonly=true />&nbsp;&nbsp;
				  $&nbsp;<input type="text" class="form-control" id="express_free_us" name="express_free_us" value="<?php echo $list['us_express_free'];?>" disabled="disabled" /> <a href="javascript:;" id="see_free"><?php echo lang('see_sf_free_num');?></a>
			</div>
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('receive_people');?>：</label>
				  <input type="text" class="form-control" id="receive_people" name="receive_people" value="<?php echo $list['recipient'];?>" readonly=true />
			</div>
		</div>
		<?php }?>

		<!-- 电子发票 -->
		<?php if($list['invoice_type'] == 2) {?>
		<div class="input-group" id="email">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_send_email');?>：</label>
			  <input type="text" class="form-control" id="invoice_send_email" name="invoice_send_email" value="<?php echo $list['email'];?>" readonly=true />
		</div>
		<?php }?>
		<div class="invoiceBoxPublic">
			<div class="input-group">
				  <label class="input-group-addon"><span class="require">*</span><?php echo lang('mobile');?>：</label>
				  <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $list['mobile'];?>" readonly=true />
				  <label class="input-group-addon lright10"><?php echo lang('invoice_spare_num');?>：</label>
				  <input type="text" class="form-control" id="invoice_spare_num" name="invoice_spare_num" value="<?php echo $list['backup_num'];?>" readonly=true />
			</div>
			<?php if(isset($list['remark']) && $list['remark']) {?>
			<div class="input-group">
				  <label class="input-group-addon"><?php echo lang('admin_order_remark');?>：</label>
				  <textarea id="remark" name="remark" rows="3" cols="20" readonly=true><?php echo $list['remark'];?></textarea>
			</div>
			<?php }?>
		</div>
		<?php if(isset($list['fpcode']) && $list['fpcode']) {?>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('invoice_code');?>：</label>
			  <?php foreach($list['fpcode'] as $code) {?>
			  <input type="text" class="form-control" name="invoice_code" value="<?php echo $code;?>" readonly=true />
			  <?php }?>
		</div>
		<?php }?>
		<?php if($list['express_num']) {?>
		<div class="input-group">
			  <label class="input-group-addon"><span class="require">*</span><?php echo lang('courier_number');?>：</label>
			  <input type="text" class="form-control" id="express_num" name="express_num" value="<?php echo $list['express_num'];?>" readonly=true />
		</div>
		<?php }?>
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
				$('select').attr('disabled', true);
				'use strict'; address_list('<?php echo $list['addressinfo']['country'];?>', '<?php echo $list['addressinfo']['provice'];?>', '<?php echo $list['addressinfo']['city'];?>', '<?php echo $list['addressinfo']['area'];?>', 1);
			});
		</script>
	<?php } ?>
</div>