<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
	<form class="form-inline" method="GET">
		<input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $start_time;?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date')?>">
		-
		<input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $end_time;?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date')?>">

		<button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
	</form>
</div>

<div class="well">
	<table class="table">
		<thead>
		<tr>
			<th><?php echo lang('order_id'); ?></th>
			<th><?php echo lang('order_confirm_time'); ?></th>
			<th><?php echo lang('score_year_month'); ?></th>
			<th><?php echo lang('customer_'); ?></th>
			<th><?php echo lang('order_amount'); ?></th>
            <th><?php echo lang('effective_performance');  ?></th>
			<th><?php echo lang('type'); ?></th>
			<th><?php echo lang('status'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if(!empty($list)){ ?>
			<?php foreach ($list as $row) { ?>
				<tr>
					<td><?php echo $row['order_id'] ?></td>
					<td><?php echo $row['create_time']; ?></td>
					<td><?php echo $row['score_year_month']?(substr($row['score_year_month'],0,4).'-'.substr($row['score_year_month'],4)):substr($row['create_time'],0,7); ?></td>
					<td><?php echo ($row['customer_id']?$row['customer_id']:'').(isset($customer_info_list[$row['customer_id']])?('('.$customer_info_list[$row['customer_id']]['email'].')'):''); ?></td>
					<td><?php echo $row['order_amount'].' ('.$row['currency'].')'.($row['currency']!='USD'?(' ; '.$row['order_amount_usd'].'(USD)'):''); ?></td>
                    <td><?php echo tps_money_format($row['order_amount_usd_one_third']/100).'(USD)'; ?></td>
					<td><?php echo lang('order_from_'.$row['order_from'])  ?></td>
					<td><?php echo $row['status']==1?lang('admin_order_status_finish'):lang('admin_order_status_cancel') ?></td>
				</tr>
			<?php } ?>
		<?php }else{ ?>
			<tr>
				<th colspan="7" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
<?php echo $pager;