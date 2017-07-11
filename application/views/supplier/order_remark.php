
<?php if (isset($err_msg)): ?>

<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>

<?php else: ?>

<div class="well">
	<form class="form-horizontal">
		<div class="control-group">
			<label class="control-label text-primary"><?php echo lang('admin_order_id'); ?></label>
			<div class="controls">
				<p class="control-label" style="text-align: left;"><?php echo $order_id; ?></p>
			</div>
		</div>
		<?php if (!empty($customer)): ?>
		<div class="control-group">
			<label class="control-label text-success"><?php echo lang('admin_order_customer_remark'); ?></label>
			<div class="controls">
				<table class="table">
					<thead>
						<tr>
							<th><?php echo lang('admin_order_remark'); ?></th>
							<!--th><?php echo lang('admin_order_remark_operator'); ?></th-->
							<th><?php echo lang('admin_order_remark_create_time'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($customer as $v): ?>
						<tr>
							<td><?php echo $v['remark']; ?></td>
							<!--td><?php echo $v['admin_user']; ?></td-->
							<td><?php echo $v['created_at']; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php endif; ?>
		<div class="control-group">
			<label class="control-label text-success"><?php echo lang('admin_order_customer_remark_add'); ?></label>
			<div class="controls">
				<input class="input-xlarge" id="add_customer_remark" type="text" />
				<button type="button" class="btn btn-success" onclick="submit_add_remark(2);"><?php echo lang('add'); ?></button>
			</div>
		</div>
		<?php if (!empty($system)): ?>
		<div class="control-group">
			<label class="control-label text-danger"><?php echo lang('admin_order_system_remark'); ?></label>
			<div class="controls">
				<table class="table">
					<thead>
						<tr>
							<th><?php echo lang('admin_order_remark'); ?></th>
							<!--th><?php echo lang('admin_order_remark_operator'); ?></th-->
							<th><?php echo lang('admin_order_remark_create_time'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($system as $v): ?>
						<tr>
							<td><?php echo $v['remark']; ?></td>
							<!--td><?php echo $v['admin_user']; ?></td-->
							<td><?php echo $v['created_at']; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php endif; ?>
		<div class="control-group">
			<label class="control-label text-danger"><?php echo lang('admin_order_system_remark_add'); ?></label>
			<div class="controls">
				<input class="input-xlarge" id="add_system_remark" type="text" />
				<button type="button" class="btn btn-danger" onclick="submit_add_remark(1);"><?php echo lang('add'); ?></button>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<a class="btn btn-inverse" href="<?php echo $url; ?>"><?php echo lang('back'); ?></a>
			</div>
		</div>
	</form>
</div>

<?php endif; ?>

<script>
$(function() {
	'use strict';

});

function submit_add_remark(type) {

	if (1 == type) {
		var remark = $('#add_system_remark').val();
	} else if (2 == type) {
		var remark = $('#add_customer_remark').val();
	} else {
		return false;
	}

	var data = {};
	data.order_id = '<?php echo $order_id; ?>';
	data.type = type;
	data.remark = remark;

	$.ajax({
		url: '/supplier/order/do_add_order_remark',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				window.location.reload();
			} else {
				layer.msg("system error");
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

</script>
