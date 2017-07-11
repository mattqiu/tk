<div class="search-well">
	<form class="form-inline" method="get">
		<input type="text" name="uid" value="" autocomplete="off"  class="input-xlarge search-query" placeholder="用户ID">
		<button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>

	</form>

	<div class="clearfix"></div>
</div>


<div class="well">
	<table class="table">
		<thead>
		<tr>
			<th>id</th>
			<th>uid</th>
			<th>姓名</th>
			<th>奖金所属日期</th>
			<th>金额(美金)</th>
			<th>占比</th>
			<th>创建时间</th>
		</tr>
		</thead>
		<tbody>
		<?php if(empty($list)){
			echo "<td colspan='7' class='text-success'> 无匹配的记录 </td>";
		} else {?>
			<?php foreach($list as $v):?>
				<tr>
					<td><?php echo $v['id'];?></td>
					<td><?php echo $v['uid'];?></td>
					<td><?php echo $v['name'];?></td>
					<td><?php echo $v['bonus_time'];?></td>
					<td><?php echo $v['amount']/100;?></td>
					<td><?php echo $v['rate'];?>%</td>
					<td><?php echo date("Y-m-d H:i:s",$v['create_time']);?></td>
				</tr>

			<?php endforeach; ?>
		<?php  }	?>
		</tbody>
	</table>
</div>





<?php
if (isset($pager))
{
	echo $pager;
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