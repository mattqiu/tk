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
