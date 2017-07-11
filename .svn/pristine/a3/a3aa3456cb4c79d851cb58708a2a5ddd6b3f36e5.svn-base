<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form action="<?php echo base_url('admin/mvp_live_order'); ?>" class="form-inline" method="GET">

        <input autocomplete="off" value="<?php if(isset($searchData['luyan_account'])){echo $searchData['luyan_account'];} ?>" name="luyan_account" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control"  placeholder="<?php echo lang('pls_input_luyan_account')?>" />

        <select name="payment_type" style="width: 180px;">
            <option value=""><?php echo lang('payment'); ?></option>
            <?php if($payment_map){
                foreach($payment_map as $k=>$v){ ?>
                    <option value="<?php echo $k; ?>" <?php if($searchData['payment_type']!=='' && $k==$searchData['payment_type']){echo 'selected';} ?>><?php echo $v; ?></option>
                <?php } ?>
            <?php } ?>
        </select>

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">

        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search'); ?></button>
    </form>
</div>

<div class="well">
    <form method="post" id='form1' action=""  name="listForm">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?php echo lang('order_sn') ?></th>
                <th><?php echo lang('luyan_account') ?></th>
                <th><?php echo lang('mvp_pay_amount') ?></th>
                <th><?php echo lang('payment'); ?></th>
                <th><?php echo lang('third_party_order_id'); ?></th>
                <th><?php echo lang('admin_order_pay_time'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($list)){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['order_id']; ?></td>
                    <td><?php echo $item['phone']; ?></td>
                    <td><?php echo '$'.$item['order_amount_usd']/100; ?></td>
                    <td><?php echo $payment_map[$item['payment_type']]; ?></td>
                    <td><?php echo $item['txn_id'];?></td>
                    <td><?php echo $item['pay_time']; ?></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="20" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
        </table>
    </form>
</div>
<div style="float: left;clear: both">
    <?php echo $pager;?>
</div>