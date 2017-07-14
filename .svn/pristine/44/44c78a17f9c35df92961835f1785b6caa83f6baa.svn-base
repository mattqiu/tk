
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

<div class="block ">
	<form id='commChangeForm'>
		<div class="block-body">
			<div class="row-fluid">
				<?php echo lang('order_sn');?>: <input style="width:190px" id="order_id" type="text" name="order_sn" value="">
			</div>
            <div class="row-fluid">
                <?php echo lang('admin_order_status');?>:
                <select id="order_status_roll">
                    <option value="0"><?php echo(lang("automatic"));?></option>
                    <?php
                    foreach ($status_map as $k => $v)
                    {
                        echo "<option value=\"{$k}\" class=\"{$v['class']}\">{$v['text']}</option>";
                    }
                    ?>
                </select>
            </div>
			<div>
				<?php echo lang('why')?>: <textarea rows="3" cols="20" id="commChangeDesc"  name="commChangeDesc"></textarea>
			</div>
			<div class="row-fluid">
				<input id='rollbackSub' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
				<span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
			</div>
            <div class="row-fluid"><span style="color: red"><?php //echo lang('order_rollback_show');?></span></div>
		</div>

</form>

</div>

<?php if (isset($err_msg)): ?>
	<div class="well">
		<p style="color: red;"><?php echo $err_msg; ?></p>
	</div>
<?php endif; ?>
<script>
	$("#rollbackSub").click(function () {
		var order_id = $('#order_id').val();
		var remark = $('#commChangeDesc').val();
		var status = $('#order_status_roll').val();
		$.ajax({
			type: "POST",
			url: "/admin/order_report/order_cancel_rollback",
			data: {order_id: order_id,remark: remark,status:status},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					$('#submit_success').text(res.msg);
					$('#error_msg').text("");
					setTimeout("$('#submit_success').text('')", 3000);
				} else {
					$('#error_msg').text(res.msg);
					$('#submit_success').text("");
					setTimeout("$('#error_msg').text('')", 3000);
				}
			}
		});
	});
</script>