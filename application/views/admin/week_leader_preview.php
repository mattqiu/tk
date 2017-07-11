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

            <th colspan="3"><?php echo lang('action')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo date('Y-m-d',$item['date']); ?></td>
                    <td><?php echo $item['child_reward']; ?></td>
                    <td><?php echo $item['percent_show']; ?></td>
                    <td><?php echo $item['due_amount']; ?></td>
                    <td><?php echo $item['current_amount']; ?></td>

                    <td><?php echo $item['due_amount'] + $item['current_amount']; ?></td>
                    <td><?php echo lang('week_leader_status_'.$item['status']); ?></td>
                    <td><?php echo empty($item['pay_time']) ? '--' : date('Y-m-d H:i:s',$item['pay_time']); ?></td>

                    <td>
                        <a href="<?echo base_url('/admin/week_leader_preview/detail?uid='.$item['id'].'&date='
                                                 .$item['date']);
                        ?>"><?php
                            echo lang('week_leader_detail')?></a>
                    </td>
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
<?php echo $pager;?>