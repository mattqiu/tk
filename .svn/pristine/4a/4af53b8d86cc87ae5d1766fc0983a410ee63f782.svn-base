<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.form.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <style>
            .dropdown-menu{
                min-width: 0px;
            }
        </style>
        <input type="text" name="batch_num" value="<?php echo $searchData['batch_num']; ?>" class="input-medium span2" placeholder="<?php echo lang('batch_number') ?>">
        <select name="status" class="com_type input-medium">
            <option value=""><?php echo lang('status') ?></option>
            <option value="1" <?php echo $searchData['status'] == '1' ? 'selected' : '' ?>><?php echo lang('status_n1') ?></option>
            <option value="2" <?php echo $searchData['status'] == '2' ? 'selected' : '' ?>><?php echo lang('status_n2') ?></option>
            <option value="3" <?php echo $searchData['status'] == '3' ? 'selected' : '' ?>><?php echo lang('status_n3') ?></option>
        </select>
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>
<form class="form-list2" method="post">
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th width="10%"><?php echo lang('number_hao') ?></th>
                    <th><?php echo lang('batch_number'); ?></th>
                    <th><?php echo lang('total_items'); ?></th>
                    <th><?php echo lang('total_money'); ?></th>
                    <th><?php echo lang('paypal_status') ?></th>
                    <th><?php echo lang('generate_time'); ?></th>
                    <th><?php echo lang('process_time') ?></th>
                    <th><?php echo lang('process_result'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($list) { ?>
                    <?php foreach ($list as $k => $item) { ?>
                        <tr>
                            <td><?php echo $k + 1; ?></td>
                            <td class="modal_main"><?php echo $item['batch_num']; ?></td>
                            <td><?php echo $item['total']; ?></td>
                            <td><?php echo '￥'.$item['lump_sum']; ?></td>
                            <td <?php
                            switch ($item['status']) {
                                case 0:
                                    echo 'class="text-error"';
                                    break;
                                case 1:
                                    echo 'class="text-error"';
                                    break;
                                case 2:
                                    echo 'class="text-warning"';
                                    break;
                                case 3:
                                    echo 'class="text-success"';
                                    break;
                            }
                            ?>><?php echo lang('status_n'.$item['status']); ?></td>
                            <td><?php echo ($item['create_time']); ?></td>
                            <td><?php echo $item['process_time']; ?></td>
                            <td><?php echo $item['success'].'/'; ?><span style="color:red;"><?php echo $item['failure'];?></span></td>
                            <td width="8%">
                                <a class="btn" target="_blank" href="<?php echo base_url('/admin/admin_as_refund_list?batch_id='.$item['id']) ?>"><?php echo lang('process') ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <th colspan="12" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</form>
    <?php echo $pager; ?>