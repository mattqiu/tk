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
	
	<div class="search-well">
		<form class="form-inline" method="GET">
			<input type="text" class="input-small" id="invoice_num" name="invoice_num" placeholder="<?php echo lang('invoice_number');?>" />
			<input type="text" class="input-small" id="uid" name="uid" placeholder="<?php echo lang('member_id');?>" />
			<select class="input-medium" name="status" id="status">
				<?php foreach($status_map as $key => $value) {?>
				<option value="<?php echo $key;?>" <?php if(($searchData['status'] == $key) && $searchData['status'] != '') echo 'selected="selected"';?>><?php echo $value;?></option>
				<?php }?>
			</select>
			<select class="input-medium" name="invoice_type" id="invoice_type">
				<?php foreach($invoice_type_map as $key => $value) {?>
				<option value="<?php echo $key;?>" <?php if($searchData['invoice_type'] == $key) echo 'selected="selected"';?>><?php echo $value;?></option>
				<?php }?>
			</select>
			<select class="input-medium" name="invoice_type_2" id="invoice_type_2">
				<?php foreach($invoice_type_2_map as $key => $value) {?>
					<option value="<?php echo $key;?>" <?php if($searchData['invoice_type_2'] == $key) echo 'selected="selected"';?>><?php echo $value;?></option>
				<?php }?>
			</select>
			<input class="Wdate span2" id="start" name="start" onclick="WdatePicker({lang: 'zh'});" placeholder="开始日期" type="text" />
			<input class="Wdate span2" id="end" name="end" onclick="WdatePicker({lang: 'zh'});" placeholder="结束日期" type="text" />
			<button class="btn" type="submit"><i class="icon-search"></i>搜索</button>
		</form>
	</div>

	<table class="table">
		<tr>
			<th><?php echo lang('time');?></th>
			<th><?php echo lang('invoice_number');?></th>
			<th><?php echo lang('member_id');?></th>
			<th><?php echo lang('invoice_detail_money');?></th>
			<th><?php echo lang('invoice_form');?></th>
			<th><?php echo lang('admin_order_deliver_addr');?></th>
			<th><?php echo lang('admin_order_consignee');?></th>
			<th><?php echo lang('invoice_arrive_tyep');?></th>
			<th><?php echo lang('label_feedback_state');?></th>
			<th><?php echo lang('admin_order_operate');?></th>
		</tr>
		<?php if(empty($list)) {?>
		<tr><td colspan="7">该搜索条件无结果</td></tr>
		<?php } else {?>
		<!--地址操作js包含引用-->
		<script src="<?php echo base_url('file/js/user_address_linkage.js?v=170418'); ?>"></script>
		<script type="text/javascript">
			//'use strict';
			function getAddressList(provice,city,area) {
				// 获取省份名称
				var addressHTML = '';
				if (typeof(provice) != 'undefined' && !isNaN(provice) && provice > 0) {
					addressHTML += linkage[156].leaf[provice].name;
				}
				// 城市
				if (city != undefined && !isNaN(city) && city > 0) {
					if (linkage[156].leaf[provice].leaf[city].name != linkage[156].leaf[provice].name) addressHTML += ' ' + linkage[156].leaf[provice].leaf[city].name;
				}
				// 区域
				if (city != undefined && !isNaN(city) && area > 0) {
					addressHTML += ' ' + linkage[156].leaf[provice].leaf[city].leaf[area].name;
				}
				return addressHTML;
			}
		</script>
		<?php foreach($list as $items) {?>
		<tr>
			<td><?php echo $items['created_at'];?></td>
			<td>
			<?php if(in_array($adminInfo['role'], $allowlook)) {?>
			<a href="<?php echo base_url('/admin/invoice/seeinvoice/'.trim($items['invoice_num']));?>"><?php echo $items['invoice_num'];?></a>
			<?php } else {?>
			<?php echo $items['invoice_num'];?>
			<?php }?>
			</td>
			<td><?php echo $items['uid'];?></td>
			<td><?php echo '￥'.number_format($items['invoice_fact_money'] / 100, 2);?></td>
			<td><?php echo $invoice_type_map[$items['invoice_type']];?></td>
			<td>
			<?php if(!empty($items['invoice_type']) && $items['invoice_type'] == 1) {?>
				<?php if(empty($items['js_invoicenum'])){?>
				<script type="text/javascript">
				var _provice = <?php echo !empty($items['invoice_address']['provice']) ? $items['invoice_address']['provice'] : 0;?>;
				var _city    = <?php echo !empty($items['invoice_address']['city']) ? $items['invoice_address']['city'] : 0;?>;
				var _area    = <?php echo !empty($items['invoice_address']['area']) ? $items['invoice_address']['area'] : 0;?>;
				var _Html    = getAddressList(_provice, _city, _area);
				document.write(_Html);
				</script>
				<?php } else {?>
				<?php echo $items['js_invoicenum'];?>
				<?php }?>
			<?php }?>
			</td>
			<td><?php echo $items['recipient'];?></td>
			<td>
			<?php
				if (isset($express_arrive_type[$items['express_arrive']]) && $items['invoice_type'] == 1) {
					echo $express_arrive_type[$items['express_arrive']];
				}
			?>
			</td>
			<td><?php echo $status_map[$items['status']];?></td>
			<td>
				<!-- 客服经理 财务 管理员 当前发票由会员创建并且是被驳回的发票会员才有编辑权限 作废的订单不显示 -->
				<?php if(isset($items['status']) && !in_array($items['status'], array(4,9))) {?>
				<?php if(in_array($adminInfo['role'], $allowedit) || (($items['adminid'] == $adminInfo['id']) && $items['status'] == 8) || in_array($adminInfo['id'], $private_adminids)) {?>
					<a href="<?php echo base_url('/admin/invoice/editinvoice/'.trim($items['invoice_num']));?>"><?php echo lang('admin_order_operate');?></a>
				<?php }}?>
			</td>
		</tr>
		<?php }}?>
	</table>
</div>
<?php if (isset($pager)) { echo $pager; } ?>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>