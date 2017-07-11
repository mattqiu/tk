<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="order_id" value="<?php echo $searchData['order_id'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('order_id')?>">
        <input type="text" name="txn_id" value="<?php echo $searchData['txn_id'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('txn_id')?>">
        <input type="text" name="email" value="<?php echo $searchData['email'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('email')?>">
		<select name="type" class="">
			<option value="">---<?php echo lang('type')?>---</option>
			<option value="Refund" <?php echo $searchData['type']=='Refund'? 'selected':''?>>Refund</option>
			<option value="Reversal" <?php echo $searchData['type']=='Reversal'? 'selected':''?>>Reversal</option>

		</select>
        <!--
		<input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        -->
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
		<div class="pull-right">
			<button onclick="location.href='<?php echo base_url('admin/paypal_search')?>'" data-original-title="<?php echo lang('admin_paypal_failure_search')?>" rel="tooltip" type="button" class="btn"><i class="icon-search"></i> <?php echo lang('admin_paypal_failure_search')?></button>
		</div>
	</form>
</div>

<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('order_id'); ?></th>
            <th><?php echo lang('txn_id'); ?></th>
            <th><?php echo lang('email'); ?></th>
            <th><?php echo lang('name'); ?></th>
            <th><?php echo lang('type'); ?></th>
            <th><?php echo lang('amount'); ?></th>
            <th width="18%"><?php echo lang('remark'); ?></th>
            <th><?php echo lang('time'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['order_id'] ?></td>
                    <td><?php echo $item['txn_id'] ?></td>
                    <td><?php echo $item['email'] ?></td>
                    <td><?php echo $item['name'] ?></td>
                    <td><?php echo $item['type'] ?></td>
                    <td><?php echo $item['amount'] ?></td>
                    <td><?php echo $item['note'] ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;