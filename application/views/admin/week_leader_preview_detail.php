<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th><?php echo lang('cus_name') ?></th>
            <th><?php echo lang('week_leader_reward_date'); ?></th>
            <th><?php echo lang('week_leader_child_reward'); ?></th>
            <th><?php echo lang('week_leader_reward_percent'); ?></th>
            <th><?php echo lang('week_leader_due_amount'); ?></th>
            <th><?php echo lang('week_leader_current_amount'); ?></th>
            <th><?php echo lang('week_leader_total_amount'); ?></th>
            <th><?php echo lang('week_leader_reward_status'); ?></th>
            <th><?php echo lang('week_leader_reward_pay_time'); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $preview['uid']; ?></td>
            <td><?php echo $preview['name']; ?></td>
            <td><?php echo date('Y-m-d',$preview['date']); ?></td>
            <td style="color:red"><?php echo $preview['child_reward']; ?></td>
            <td><?php echo $preview['percent_show']; ?></td>
            <td><?php echo $preview['due_amount']; ?></td>
            <td><?php echo $preview['current_amount']; ?></td>
            <td><?php echo $preview['due_amount'] + $preview['current_amount']; ?></td>
            <td><?php echo lang('week_leader_status_'.$preview['status']); ?></td>
            <td><?php echo empty($preview['pay_time']) ? '--' : date('Y-m-d H:i:s',$preview['pay_time']); ?></td>
        </tr>
        </tbody>
    </table>
</div>

<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th><?php echo lang('type') ?></th>
            <th><?php echo lang('money_num'); ?></th>
            <th><?php echo lang('admin_store_statistics_datetime'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($childList){ ?>
            <?php $all=0; foreach ($childList as $key=>$item) { ?>

                <?php $index=0;$total=0; foreach($item['logs'] as $log) {?>
                <tr>
                    <?php if($index++==0) {?>
                    <td rowspan="<?php echo count($item['logs']); ?>" valign="top"><?php echo $key; ?>（一级）</td>
                    <?php } ?>
                    <td><?php echo lang(config_item('funds_change_report')[$log['item_type']]); ?></td>
                    <td><?php echo $log['amount']; ?></td>
                    <td><?php echo $log['create_time']; ?></td>
                </tr>
                <?php $total+=$log['amount']; } ?>
                <tr>
                    <td></td>
                    <td>小计</td>
                    <td><?php echo $total; $all+=$total ?></td>
                    <td></td>
                </tr>

                <?php foreach($item['child'] as $k=>$child) { ?>
                    <?php $index=0;$total=0; foreach($child as $log) {?>
                    <tr>
                        <?php if($index++==0) {?>
                            <td rowspan="<?php echo count($child); ?>" valign="top"><?php echo $k; ?>（二级）</td>
                        <?php } ?>
                        <td><?php echo lang(config_item('funds_change_report')[$log['item_type']]); ?></td>
                        <td><?php echo $log['amount']; ?></td>
                        <td><?php echo $log['create_time']; ?></td>
                    </tr>
                    <?php $total+=$log['amount']; } ?>
                <tr>
                    <td></td>
                    <td>小计</td>
                    <td><?php echo $total; $all+=$total ?></td>
                    <td></td>
                </tr>
                <?php } ?>

            <?php } ?>
            <tr>
                <td></td>
                <td>总计</td>
                <td style="color:red"><?php echo $all;?></td>
                <td><?php if($all==$preview['child_reward']) {echo lang('week_leader_detail_correct');}
                    else{echo lang('week_leader_detail_wrong');}?>
                </td>
            </tr>
        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>