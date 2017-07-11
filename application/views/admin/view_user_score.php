<?php /*?>
<div class="search-well">
	<form class="form-inline" method="GET">
		<input name="score_month" type="text" name="uid" placeholder="<?php echo lang('score_month'); ?>(格式: 201707)" />
		
		<button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
	</form>
</div> 
<?php */?>

<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th colspan="2"><?php echo lang('current_store_sale_total_amount')?>: <?php echo $total / 100?>(美元)</th>
			</tr>
			<tr>
				<th><?php echo lang('score_month'); ?></th>
				<th><?php echo lang('store_sale_amount'); ?></th>
			</tr>
		</thead>
		<tbody>
		 <?php if (isset($list) && $list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['year_month'] ?></td>
                    <td><?php echo $item['sale_amount'] / 100 ?>(美元)</td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="2" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
		</tbody>
	</table>
</div>