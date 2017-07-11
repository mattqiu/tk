
<div class="search-well">
	<form class="form-inline" method="GET">
		<select class="input-medium" name="status" id="status">
		<?php
			foreach ($status_select as $k => $v)
			{
				echo "<option value=\"{$k}\">{$v}</option>";
			}
		?>
		</select>

		<input class="input-small" id="order_id" type="text" name="order_id" placeholder="<?php echo lang('admin_order_id'); ?>" />
		<input class="input-small" id="tracking_num" type="text" name="tracking_num" placeholder="<?php echo lang('admin_order_tracking_num'); ?>" />
		<input class="Wdate span2" id="start_date" type="text" name="start_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('start_date'); ?>">
		<input class="Wdate span2" id="end_date" type="text" name="end_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('end_date'); ?>">
		<button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
        <button class="btn scan_shipping" type="button"><?php echo lang('admin_scan_shipping')?></button>
	</form>
</div>

<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>

<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th><?php echo lang('admin_order_id'); ?></th>
				<th><?php echo lang('admin_order_customer'); ?></th>

				<!--th><?php echo lang('admin_order_goods_amount'); ?></th-->

				<th><?php echo lang('admin_order_status'); ?></th>
                <th><?php echo lang('admin_order_remark'); ?></th>
                <th><?php echo lang('admin_order_deliver_addr'); ?></th>

				<th><?php echo lang('admin_order_operate'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($order_list as $v)
			{
				echo "<tr>";
				echo "<td>";
				if ($v['having_remark'] == 1)
				{
					echo "<i class=\"icon-info-sign text-danger\"></i>&nbsp;";
				}
				echo "</td>";
				echo "<td><a href=\"javascript:void(0)\" onclick=\"click_order_info('{$v['order_id']}');\">{$v['order_id']}</a></td>";
				echo "<td>{$v['customer']}</td>";
				//echo "<td>{$v['goods_amount_show']}</td>";
				
				echo "<td class=\"{$status_map[$v['status']]['class']}\">{$status_map[$v['status']]['text']}</td>";
				echo "<td>{$v['remark']}</td>";
				echo "<td>{$v['address']}</td>";
				echo "<td>";
			 	//if(in_array($adminInfo['role'],array(0,1,2,3))){
					/*if ($v['status'] < 4)
					{
						echo "<a class=\"btn btn-primary\" href=\"".base_url()."supplier/order/order_modify/{$v['order_id']}/".urlencode(trim($_SERVER['REQUEST_URI'], '/'))."\">".lang('modify')."</a>&nbsp;&nbsp;";
					}
					if ($v['status'] < 4)
					{
						echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','99');\">".lang('cancel')."</button>&nbsp;&nbsp;";
					}
					if (in_array($v['status'],array('4','5','6')))
					{
						echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','98');\">".lang('admin_order_refund')."</button>&nbsp;&nbsp;";
					}*/
					if ($v['status'] == 4 || $v['status'] == 3)
					{
						echo "<button class=\"btn btn-info\" type=\"button\" onclick=\"click_shipping_print('{$v['order_id']}');\">".lang('admin_order_shipping_print')."</button>&nbsp;&nbsp;";
					}
					if ($v['status'] == 3)
					{
						echo "<button class=\"btn btn-success\" type=\"button\" onclick=\"click_deliver('{$v['order_id']}');\">".lang('admin_order_operate_deliver')."</button>&nbsp;&nbsp;";
					}
					echo "<a class=\"btn btn-danger\" href=\"".base_url()."supplier/order/order_remark_manager/{$v['order_id']}/".urlencode(trim($_SERVER['REQUEST_URI'], '/'))."\">".lang('admin_order_remark')."</a>";
				}
				echo "</td>";
				echo "</tr>";
			//}
		?>
		</tbody>
	</table>
</div>
<?php
	if (isset($paginate))
	{
		echo $paginate;
	}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>
$(function() {
	$('.scan_shipping').click(function() {
		window.open('<?php echo base_url('supplier/order/scan_shipping'); ?>');
	});
});

function errboxHtml(msg) {
	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
}

$(function() {
	'use strict';

	var page_data = <?php echo json_encode($page_data); ?>;
	$('#status').children('[value=' + page_data.status + ']').prop('selected', true);
	$('#storehouse').children('[value=' + page_data.storehouse + ']').prop('selected', true);
	if (page_data.order_id != null) {
		$('#order_id').val(page_data.order_id);
	}
	if (page_data.uid != null) {
		$('#uid').val(page_data.uid);
	}
	if (page_data.store_id != null) {
		$('#store_id').val(page_data.store_id);
	}
	if (page_data.tracking_num != null) {
		$('#tracking_num').val(page_data.tracking_num);
	}
	if (page_data.start_date != null) {
		$('#start_date').val(page_data.start_date);
	}
	if (page_data.end_date != null) {
		$('#end_date').val(page_data.end_date);
	}
});

function click_order_info(id)
{
	var screen_width = document.body.clientWidth;
	if (screen_width > 768) {
		screen_width = screen_width * 3 / 4;
		if (screen_width > 1280) {
			screen_width = 1280;
		}
	}

	var screen_height = document.documentElement.clientHeight;
	if (screen_height > 576) {
		screen_height = screen_height * 3 / 4;
		if (screen_height > 720) {
			screen_height = 720;
		}
	} else {
		screen_height -= 50;
	}

	$.thinkbox.iframe('<?php echo base_url('supplier/order/get_order_info'); ?>/' + id, {
		'title': "<?php echo lang('admin_order_info'); ?>",
		'dataEle': this,
		'unload': true,
		'width': screen_width,
		'height': screen_height,
		'scrolling': "yes"
	});
	return true;
}

function click_shipping_print(id)
{
	var screen_width = document.body.clientWidth;
	if (screen_width > 768) {
		screen_width = screen_width * 3 / 4;
		if (screen_width > 1280) {
			screen_width = 1280;
		}
	}

	var screen_height = document.documentElement.clientHeight;
	if (screen_height > 576) {
		screen_height = screen_height * 3 / 4;
		if (screen_height > 720) {
			screen_height = 720;
		}
	} else {
		screen_height -= 50;
	}

	$.thinkbox.iframe('<?php echo base_url('supplier/order/order_shipping_print'); ?>/' + id, {
		'title': "<?php echo lang('admin_order_shipping_print'); ?>",
		'dataEle': this,
		'unload': true,
		'width': screen_width,
		'height': screen_height,
		'scrolling': "yes"
	});
	return true;
}

function confirm_cancel(id,status)
{
	$.ajax({
		url: '/supplier/order/do_cancel_order_check',
		type: "POST",
		data: {'order_id': id,'status':status},
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				var refundHtml = data.refundHtml;

				layer.confirm("<?php echo lang('admin_order_confirm_cancel'); ?>"+refundHtml, {
					icon: 3,
					title: "<?php echo lang('admin_order_cancel_confirm'); ?>",
					closeBtn: 2,
					btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
				}, function(index){
					var refund_type = $("input[name='refund_type']:checked").val()?$("input[name='refund_type']:checked").val():'';
					layer.close(index);
					$.ajax({
						url: '/supplier/order/do_cancel_order',
						type: "POST",
						data: {'order_id': id,'status':status,'refund_type':refund_type},
						dataType: "json",
						success: function(data) {
							if (data.code == 0) {
								window.location.reload();
							}else if(data.code == 112){
								layer.msg('<?php echo lang('admin_order_lock')?>');
							}
						},
						error: function(data) {
							console.log(data.responseText);
						}
					});
				});
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

function click_deliver(id)
{
	var deliver_box_cont = '';
	deliver_box_cont += '<form style="margin: 20px;" class="form-inline"><div class="input-append">';
	deliver_box_cont += '<select class="span2" id="deliver_box_code">';
	<?php foreach ($freight_map as $code => $name): ?>
	deliver_box_cont += '<option value="<?php echo $code; ?>"><?php echo $name; ?></option>';
	<?php endforeach; ?>
	deliver_box_cont += '</select>';
	deliver_box_cont += '<input type="text" class="input-small" id="deliver_box_id" placeholder="<?php echo lang('admin_order_deliver_box_id'); ?>" />';
	deliver_box_cont += '<input type="hidden" id="deliver_box_order_id" value="' + id + '" />';
	deliver_box_cont += '<button type="button" class="btn" onclick="submit_deliver();"><?php echo lang('ok'); ?></button>';
	deliver_box_cont += '</div></form>';

	$.thinkbox(
		deliver_box_cont,
		{
			'title': id + ' <?php echo lang('admin_order_deliver_box_title'); ?>',
		}
	);
}

function submit_deliver()
{
	var data = {};

	data.order_id = $('#deliver_box_order_id').val();
	data.company_code = $('#deliver_box_code').val();
	data.express_id = $('#deliver_box_id').val();

	console.log(data);

	$.ajax({
		url: '/supplier/order/do_order_deliver',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				window.location.reload();
			} else {
				$.thinkbox(errboxHtml("system error"));
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}
</script>