<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <select name="table" class="com_type">
			<option value="user_month_fee_order" <?php echo $searchData['table']=='user_month_fee_order'? 'selected':''?>><?php echo lang('month_fee_order')?></option>
			<option value="user_upgrade_order" <?php echo $searchData['table']=='user_upgrade_order'? 'selected':''?>><?php echo lang('upgrade_order')?></option>
			<option value="user_upgrade_month_order" <?php echo $searchData['table']=='user_upgrade_month_order'? 'selected':''?>><?php echo lang('upgrade_month_order')?></option>
        </select>
        <input type="text" name="idEmail" value="<?php echo $searchData['idEmail'];?>" class="input-medium search-query" placeholder="<?php echo lang('login_name')?>">
        <input type="text" name="order_sn" value="<?php echo $searchData['order_sn'];?>" class="input-medium search-query" placeholder="<?php echo lang('order_sn')?>">
        <input type="text" name="txn_id" value="<?php echo $searchData['txn_id'];?>" class="input-medium search-query" placeholder="<?php echo lang('txn_id')?>">
        <select name="status" class="com_type">
            <option value="">---<?php echo lang('status')?>---</option>
            <option value="0" <?php echo $searchData['status']=='0'? 'selected':''?>><?php echo lang('unpaid')?></option>
            <option value="2" <?php echo $searchData['status']=='2'? 'selected':''?>><?php echo lang('paid')?></option>
        </select>
        <select name="payment_method" class="com_type">
            <option value="">---<?php echo lang('payment_method')?>---</option>
            <option value="alipay" <?php echo $searchData['payment_method']=='alipay'? 'selected':''?>>alipay</option>
            <option value="unionpay" <?php echo $searchData['payment_method']=='unionpay'? 'selected':''?>>unionpay</option>
            <option value="paypal" <?php echo $searchData['payment_method']=='paypal'? 'selected':''?>>paypal</option>
            <option value="eWallet" <?php echo $searchData['payment_method']=='eWallet'? 'selected':''?>>eWallet</option>
            <option value="manually" <?php echo $searchData['payment_method']=='manually'? 'selected':''?>>manually</option>
            <option value="tps_amount" <?php echo $searchData['payment_method']=='tps_amount'? 'selected':''?>>tps_amount</option>
        </select>
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>
<?php if ($list){ ?>
<div class="well">

    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('id'); ?></th>
                <th><?php echo lang('order_sn'); ?></th>
                <th><?php echo lang('total_money'); ?></th>
                <th><?php echo lang('join_fee').'|'.lang('monthly_fee') ?></th>
                <th><?php echo lang('txn_id'); ?></th>
                <th><?php echo lang('status'); ?></th>
                <th><?php echo lang('payment'); ?></th>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('pay_time'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid'] ?></td>
                    <td><?php echo $item['order_sn'] ?></td>
                    <td><?php if($item['payment'] == 'unionpay' || $item['payment'] == 'alipay'){ echo '￥'.$item['money']; }else{ echo '$'.$item['money'];}   ; ?></td>
                    <td><?php echo $searchData['table']=='user_upgrade_order'? '$'.$item['join_fee'] : '$'.$item['usd_money']; ?></td>
                    <td><?php echo $item['txn_id'] ? $item['txn_id'] : '';?></td>
                    <td><?php echo $item['status'] == 2 ? '<strong class="text-success">'.lang('paid').'</strong>' :  '<strong class="text-error">'.lang('unpaid').'</strong>'; ?></td>
                    <td><?php echo $item['payment'] ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$item['create_time']) ?></td>
                    <td><?php echo $item['pay_time'] ?></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>
<?php }?>
<?php echo $pager;