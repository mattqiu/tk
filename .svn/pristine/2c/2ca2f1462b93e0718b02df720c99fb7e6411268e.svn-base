
<div class="search-well">
	<ul class="nav nav-tabs">
		<?php foreach ($tabs_map as $k => $v): ?>
			<li <?php if ($k == $tabs_type) echo " class=\"active\""; ?>>
				<a href="<?php echo base_url($v['url']); ?>">
					<?php echo $v['desc']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="search-well">
	<form class="form-inline" method="GET">
		<input type="hidden" name="tabs_type" value="1" />
		<input class="input-small" id="order_id" type="text" name="order_id" placeholder="<?php echo lang('admin_order_id'); ?>" />
		<button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
	</form>
</div>

<?php if (!empty($order_info)): ?>
<div class="well">
	<form class="form-horizontal">
		<div class="control-group">
			<label class="control-label"><?php echo lang('admin_order_id'); ?></label>
			<div class="controls">
				<input id="order_id" type="text" value="<?php echo $order_info['order_id']; ?>" disabled />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="addr_consignee"><?php echo lang('admin_order_consignee'); ?></label>
			<div class="controls">
				<input id="addr_consignee" type="text" value="<?php echo $order_info['consignee']; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo lang('admin_order_phone'); ?></label>
			<div class="controls">
				<input id="addr_phone" type="text" value="<?php echo $order_info['phone']; ?>" placeholder="<?php echo lang('checkout_phone'); ?>" />
				<input id="addr_reserve_num" type="text" value="<?php echo $order_info['reserve_num']; ?>" placeholder="<?php echo lang('checkout_reserve_num'); ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="addr_detail"><?php echo lang('admin_order_deliver_addr'); ?></label>
			<div class="controls">
				<input class="input-xxlarge" id="addr_detail" type="text" value="<?php echo $order_info['address']; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="zip_code"><?php echo lang('admin_order_zip_code'); ?></label>
			<div class="controls">
				<input id="zip_code" type="text" value="<?php echo $order_info['zip_code']; ?>" />
			</div>
		</div>
		<?php if ($order_info['country'] == "410"): ?>
		<div class="control-group">
			<label class="control-label" for="customs_clearance"><?php echo lang('admin_order_customs_clearance'); ?></label>
			<div class="controls">
				<input id="customs_clearance" type="text" value="<?php echo $order_info['customs_clearance']; ?>" />
			</div>
		</div>
		<?php endif; ?>
		<div class="control-group">
			<label class="control-label" for="addr_detail"><?php echo lang('admin_order_deliver_time'); ?></label>
			<div class="controls">
				<select id="deliver_time_type">
					<option value="1"><?php echo lang('checkout_deliver_time_period1'); ?></option>
					<option value="2"><?php echo lang('checkout_deliver_time_period2'); ?></option>
					<option value="3"><?php echo lang('checkout_deliver_time_period3'); ?></option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="deliver_fee"><?php echo lang('admin_order_deliver_fee'); ?></label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><?php echo $order_info['currency']; ?></span>
					<input class="span4" id="deliver_fee" type="text" value="<?php echo number_format($order_info['deliver_fee'] / 100, 2, ".", ""); ?>" />
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="expect_deliver_date"><?php echo lang('admin_order_expect_deliver_date'); ?></label>
			<div class="controls">
				<input class="Wdate span2" id="expect_deliver_date" type="text" value="<?php echo $order_info['expect_deliver_date']; ?>" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" />
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary" id="submit_btn" type="button" onclick="submit_modify();"><?php echo lang('ok'); ?></button>
			</div>
		</div>
	</form>
</div>
<?php endif; ?>

<div class="well text-danger">
	<div>Please Wait for developing!!!</div>
	<ul><span class="text-success">小谭</span> TODO....
		<li>db: trade_orders、trade_orders_log</li>
		<li>修bug</li>
		<li>已锁定的订单不允许修改，同时加句提示</li>
		<li>加操作流水</li>
		<li>删掉订单管理页面的修改功能（以后都在这儿修改订单信息）以及相关代码</li>
	</ul>
</div>

<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>

<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script>

function errboxHtml(msg) {
	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
}

$(function() {
	'use strict';

	<?php if (isset($order_id)): ?>
		$('#order_id').val("<?php echo $order_id; ?>");
	<?php endif; ?>

	$('#deliver_time_type').children('[value=<?php echo $order_info['deliver_time_type']; ?>]').prop('selected', true);
});

function submit_modify()
{
	var data = {};

	data.order_id = $('#order_id').val();
	data.consignee = $('#addr_consignee').val();
	data.phone = $('#addr_phone').val();
	data.reserve_num = $('#addr_reserve_num').val();
	data.address = $('#addr_detail').val();
	data.zip_code = $('#zip_code').val();
	data.customs_clearance = $('#customs_clearance').val();

	data.deliver_time_type = $('#deliver_time_type').val();

	data.deliver_fee = $('#deliver_fee').val();

	data.expect_deliver_date = $('#expect_deliver_date').val();

	$('#submit_btn').prop('disabled', true);
	$.ajax({
		url: '<?php echo base_url('admin/trade/do_repair_modify')?>',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				$.thinkbox.success(data.msg);
			} else {
				$.thinkbox(errboxHtml(data.msg));
			}
			$('#submit_btn').prop('disabled', false);
		},
	});
	return true;
}
</script>
