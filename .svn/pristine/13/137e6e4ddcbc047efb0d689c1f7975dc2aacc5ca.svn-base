<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<div class="search-well">
	<form class="form-inline" method="GET" id="order_search">
		<!--
		<input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
		-
		<input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
		-->
		<select name="month" class="orders_font orders_month span2">
			<option value=""><?php echo lang('filter_month')?></option>
			<option value="<?php echo $months[0]?>" <?php echo $searchData['month'] == $months[0] ? 'selected':'';?>><?php echo $months[0]?></option>
			<option value="<?php echo $months[1]?>" <?php echo $searchData['month'] == $months[1] ? 'selected':'';?>><?php echo $months[1]?></option>
			<option value="<?php echo $months[2]?>" <?php echo $searchData['month'] == $months[2] ? 'selected':'';?>><?php echo $months[2]?></option>
			<option value="<?php echo $months[3]?>" <?php echo $searchData['month'] == $months[3] ? 'selected':'';?>><?php echo $months[3]?></option>
			<option value="<?php echo $months[4]?>" <?php echo $searchData['month'] == $months[4] ? 'selected':'';?>><?php echo $months[4]?></option>
			<option value="<?php echo $months[5]?>" <?php echo $searchData['month'] == $months[5] ? 'selected':'';?>><?php echo $months[5]?></option>
		</select>
		<button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
	</form>
	<form class="form-inline" method="GET" action="<?php echo base_url('admin/order_report/export') ?>">
		<input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
		-
		<input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
		<button class="btn" type="submit"><i class="icon-download-alt"></i> <?php echo lang('export') ?></button>
	</form>
</div>

<div class="well">
	<table class="table">
		<thead>
		<tr>
			<th ></th>
			<th colspan="2"><?php echo lang('zone_area_chn') ?></th>
			<th  colspan="2"><?php echo lang('zone_area_kor') ?></th>
			<th  colspan="2"><?php echo lang('zone_area_usa_other') ?></th>
			<th  colspan="2" width="22%"><?php echo lang('zone_area_hkg_mac_twn_asean') ?></th>
		</tr>
		<tr>
			<th></th>
			<th><?php echo lang('order_status_4') ?></th>
			<th><?php echo lang('order_status_3') ?></th>
			<th><?php echo lang('order_status_4') ?></th>
			<th><?php echo lang('order_status_3') ?></th>
			<th><?php echo lang('order_status_4') ?></th>
			<th><?php echo lang('order_status_3') ?></th>
			<th><?php echo lang('order_status_4') ?></th>
			<th><?php echo lang('order_status_3') ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if ($results){ $sum_0 = $sum_1 = $sum_2 = $sum_3 = $sum_4 = $sum_5 = $sum_6 = $sum_7 = 0?>
			<?php foreach ($results as $key=>$item) {
			$sum_0 = $sum_0+$item[0];
			$sum_1 = $sum_1+$item[1];
			$sum_2 = $sum_2+$item[2];
			$sum_3 = $sum_3+$item[3];
			$sum_4 = $sum_4+$item[4];
			$sum_5 = $sum_5+$item[5];
			$sum_6 = $sum_6+$item[6];
			$sum_7 = $sum_7+$item[7];
			?>

				<tr>
					<td><?php echo $key ?></td>
					<td><?php echo $item[0] ?></td>
					<td><?php echo $item[1] ?></td>
					<td><?php echo $item[2] ?></td>
					<td><?php echo $item[3] ?></td>
					<td><?php echo $item[4] ?></td>
					<td><?php echo $item[5] ?></td>
					<td><?php echo $item[6] ?></td>
					<td><?php echo $item[7] ?></td>
				</tr>

			<?php } ?>
			<tr>
				<th>SUM（合计）</th>
				<th><?php echo $sum_0 ?></th>
				<th><?php echo $sum_1 ?></th>
				<th><?php echo $sum_2 ?></th>
				<th><?php echo $sum_3 ?></th>
				<th><?php echo $sum_4 ?></th>
				<th><?php echo $sum_5 ?></th>
				<th><?php echo $sum_6 ?></th>
				<th><?php echo $sum_7 ?></th>
			</tr>

		<?php }else{ ?>
			<tr>
				<th colspan="9" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>