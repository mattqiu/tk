
<?php if (isset($err_msg)): ?>

<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>

<?php else: ?>

<div class="well">
	<form class="form-horizontal">
		<div class="control-group">
			<label class="control-label"><?php echo lang('admin_order_id'); ?></label>
			<div class="controls">
				<input id="order_id" type="text" value="<?php echo $data['order_detail']['order_id']; ?>" disabled />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="addr_consignee"><?php echo lang('admin_order_consignee'); ?></label>
			<div class="controls">
				<input id="addr_consignee" type="text" value="<?php echo $data['order_detail']['consignee']; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo lang('admin_order_phone'); ?></label>
			<div class="controls">
				<input id="addr_phone" type="text" value="<?php echo $data['order_detail']['phone']; ?>" placeholder="<?php echo lang('checkout_phone'); ?>" />
				<input id="addr_reserve_num" type="text" value="<?php echo $data['order_detail']['reserve_num']; ?>" placeholder="<?php echo lang('checkout_reserve_num'); ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="addr_detail"><?php echo lang('admin_order_deliver_addr'); ?></label>
			<div class="controls">
				<input class="input-xxlarge" id="addr_detail" type="text" value="<?php echo $data['order_detail']['address']; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="zip_code"><?php echo lang('admin_order_zip_code'); ?></label>
			<div class="controls">
				<input id="zip_code" type="text" value="<?php echo $data['order_detail']['zip_code']; ?>" />
			</div>
		</div>
		<?php if ($data['order_detail']['area'] == 410): ?>
		<div class="control-group">
			<label class="control-label" for="customs_clearance"><?php echo lang('admin_order_customs_clearance'); ?></label>
			<div class="controls">
				<input id="customs_clearance" type="text" value="<?php echo $data['order_detail']['customs_clearance']; ?>" />
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
				<div class="input-append">
					<input class="span4" id="deliver_fee" type="text" value="<?php echo number_format($data['order_detail']['deliver_fee'] / 100, 2, ".", ""); ?>" />
					<span class="add-on"><?php echo $cur_icon; ?></span>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="expect_deliver_date"><?php echo lang('admin_order_expect_deliver_date'); ?></label>
			<div class="controls">
				<input class="Wdate span2" id="expect_deliver_date" type="text" value="<?php echo $data['order_detail']['expect_deliver_date']; ?>" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" />
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<a class="btn btn-danger" href="<?php echo $url; ?>"><?php echo lang('back'); ?></a>
				<button type="button" class="btn btn-primary" id="submit_button" onclick="submit_modify();"><?php echo lang('ok'); ?></button>
			</div>
		</div>
	</form>
</div>

<?php endif; ?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script>
$(function() {
	'use strict';

	$('#deliver_time_type').children('[value=<?php echo $data['order_detail']['deliver_time_type']; ?>]').prop('selected', true);

	<?php if ($data['order_detail']['is_export_lock'] == 1 && !in_array($adminInfo['role'],array(0,3)) && $adminInfo['id']!=66 ): ?>
		$('#addr_consignee').prop('disabled', true);
		$('#addr_phone').prop('disabled', true);
		$('#addr_reserve_num').prop('disabled', true);
		$('#addr_detail').prop('disabled', true);
		$('#zip_code').prop('disabled', true);
		$('#deliver_time_type').prop('disabled', true);
		$('#deliver_fee').prop('disabled', true);
		$('#expect_deliver_date').prop('disabled', true);
		$('#submit_button').prop('disabled', true);
	<?php endif; ?>
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

	$.ajax({
		url: '<?php echo base_url('admin/trade/do_order_modify')?>',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				window.location = "<?php echo $url; ?>";
			} else {
				console.log(data);
				layer.msg(data.msg);
			}
		},
		error: function(data) {
			console.log(data.responseText);
// 			layer.msg(data.responseText);
		}
	});
	return true;
}
</script>
