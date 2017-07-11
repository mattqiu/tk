<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="well">
	<?php foreach($status as $k=>$v){?>
		<span style="font-weight: bold;margin-left: 30px;"><?php echo $k.':'.$v ?></span>
	<?php }?>
</div>
<style>
	.tbodynew tr:nth-child(1)
	{
	   background:#eee;
	}
</style>
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
<div class="well">
<div class="search-well">
	<form id="search_form" name="search_form" class="form-inline" method="post">
	 
		<select id="sear_year" name="sear_year" style="width:100px;float:left;">
		  <?php for($year = 2016; $year <= 2050;$year++){ ?>
		      <?php if($searchData['sear_year']==$year){ ?>
		          <option value="<?php echo $year; ?>" selected="selected"><?php echo $year; ?></option>
		      <?php } else { ?>
		          <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
		      <?php } ?>
		      
		  <?php  } ?>
		</select>
		<div style="float:left;margin:5px 5px 0px 5px;">年</div>
		<select id="sear_month" name="sear_month" style="width:60px;float:left;" onchange = "search_form.submit()">		
		  <?php for($month = 1; $month <= 12;$month++){ ?>
		      <?php if($searchData['sear_month']==$month){ ?>
		          <option value="<?php echo $month; ?>" selected="selected"><?php echo $month; ?></option>
		      <?php } else { ?>
		          <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
		      <?php } ?>
		      
		  <?php  } ?>
		</select>
		<div style="float:left;margin:5px 5px 0px 5px;">月</div>
	</form>
</div>
	<table class="table">
		<thead>
		<tr>
			<th ><?php echo lang('admin_store_statistics_datetime');?></th>
			<th><?php echo lang('admin_user_level_f');?></th>
			<th  ><?php echo lang('admin_user_level_b');?></th>
			<th  ><?php echo lang('admin_user_level_s');?></th>
			<th  width="13%"><?php echo lang('admin_user_level_g');?></th>
			<th  width="13%"><?php echo lang('admin_user_level_p');?></th>
			<th  width="13%"><?php echo lang('admin_everyday_level_t');?></th>
		</tr>
		</thead>
		
		<tbody class="tbodynew">
		  <?php if ($results){ ?>
		  	<?php foreach ($results as $item) { ?>
    			<tr>
    			 <td><?php echo $item['date']; ?></td>
    			 <td><?php echo $item['free']; ?></td>
    			 <td><?php echo $item['bronze']; ?></td>
    			 <td><?php echo $item['silver']; ?></td>
    			 <td><?php echo $item['golden']; ?></td>
    			 <td><?php echo $item['diamond']; ?></td>
    			 <td><?php echo lang("admin_everyday_level_count_t");?><?php echo $item['total_x']; ?> <br/><?php echo lang("admin_everyday_level__t"); ?><?php echo $item['total_m']; ?></td>
    			</tr>
			<?php } ?>
		  <?php } else { ?>
		      <tr>
				<th colspan="9" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
			</tr>
		  <?php } ?>
		</tbody>
		<!-- 2017-1-11 废除老的， 使用新的统计方式
		<tbody>
		<?php if ($results){ $sum_0 = $sum_1 = $sum_2 = $sum_3 = $sum_4 = 0?>
			<?php foreach ($results as $key=>$item) {
			$sum_0 = $sum_0+$item[0];
			$sum_1 = $sum_1+$item[1];
			$sum_2 = $sum_2+$item[2];
			$sum_3 = $sum_3+$item[3];
			$sum_4 = $sum_4+$item[4];
			$sum_5 = $sum_0+$sum_1+$sum_2+$sum_3+$sum_4;
			?>

				<tr>
					<td><?php echo $key ?></td>
					<td><?php echo $item[0] ?></td>
					<td><?php echo $item[1] ?></td>
					<td><?php echo $item[2] ?></td>
					<td><?php echo $item[3] ?></td>
					<td><?php echo $item[4] ?></td>
					<th><?php echo $item[5] ?></th>
				</tr>

			<?php } ?>
			<tr>
				<th>合计（SUM）</th>
				<th><?php echo $sum_0 ?></th>
				<th><?php echo $sum_1 ?></th>
				<th><?php echo $sum_2 ?></th>
				<th><?php echo $sum_3 ?></th>
				<th><?php echo $sum_4 ?></th>
				<th><?php echo $sum_5 ?></th>
			</tr>

		<?php }else{ ?>
			<tr>
				<th colspan="9" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
			</tr>
		<?php } ?>
		</tbody>
		 -->
	</table>
</div>

<script>
	$(".search-well ul li > a").click(function(){
		 layer.load();
		});
	$("#sear_month").change(function(){
		layer.load();
		});
	$("#sear_year").change(function(){
		$("#sear_month").removeSelected();
		});
</script>
