
<div class="search-well">
	<form class="form-inline" method="GET">
		
		<input class="input-small" id="goods_sn" type="text" name="goods_sn" placeholder="<?php echo lang('label_goods_main_sn'); ?>" />
		
		<button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
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
				<th><?php echo lang('label_sub_sn'); ?></th>
				<th><?php echo lang('label_goods_name'); ?></th>
				<th><?php echo lang('number_zh'); ?></th>
				<th><?php echo lang('number_hk'); ?></th>
				<th><?php echo lang('number_english'); ?></th>
				<th><?php echo lang('number_kr'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($list as $v)
			{
				echo "<tr>";
				echo "<td>";
				echo "<td>{$v['goods_sn']}</td>";
				echo "<td>{$v['goods_name']}</td>";
				echo "<td>{$v['number_zh']}</td>";
				echo "<td>{$v['number_hk']}</td>";
				echo "<td>{$v['number_english']}</td>";
				echo "<td>{$v['number_kr']}</td>";
				echo "<td>";
				echo "</td>";
				echo "</tr>";
			}
		?>
		</tbody>
	</table>
</div>


<!-- 冻结订单添加备注弹层 -->
<div id="div_add_remark" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 20%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo lang('fill_in_frozen_remark')?></h3>
	</div>
	<div class="modal-body">
		<table class="tab_add_remark" style="margin: 0 auto">
			<tr>
				<input type="hidden" id="hidden_order_id">
			</tr>
			<tr>
				<td>
					<textarea id="remark_content" autocomplete="off" rows="3" cols="300" style="width: 50%" placeholder="<?php echo lang("fill_in_frozen_remark")?>"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><span id="add_remark_msg" class="msg error" style="margin-left:0px"></span></td>
			</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary"  onclick="submit_frozen_remark()" id="add_remark_submit"><?php echo lang('submit'); ?></button>
	</div>
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

function errboxHtml(msg) {
	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
}

$(function() {
	'use strict';

	var page_data = <?php echo json_encode($page_data); ?>;
	$('#status').children('[value=' + page_data.status + ']').prop('selected', true);
	$('#storehouse').children('[value=' + page_data.storehouse + ']').prop('selected', true);
	$('#order_type').children('[value=' + page_data.order_type + ']').prop('selected', true);
	if (page_data.goods_sn != null) {
		$('#goods_sn').val(page_data.goods_sn);
	}
	if (page_data.uid != null) {
		$('#uid').val(page_data.uid);
	}
	if (page_data.store_id != null) {
		$('#store_id').val(page_data.store_id);
	}
	if (page_data.tracking_num != null) {
		$('#tracking_num').val(page_data.tracking_num);
	}if (page_data.txn_id != null) {
		$('#txn_id').val(page_data.txn_id);
	}
	if (page_data.start_date != null) {
		$('#start_date').val(page_data.start_date);
	}
	if (page_data.end_date != null) {
		$('#end_date').val(page_data.end_date);
	}
});


</script>