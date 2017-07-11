<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
<div class="block ">
    <form id='commChangeForm'>
    <p class="block-heading"><?php echo lang('commission_add_or_reduce') ?></p>
    <div class="block-body">
        <div class="row-fluid">
            <?php echo lang('user_id');?>: <input style="width:100px" type="text" name="commChangeUid" value="">
            <select name="commChangeOper" style="width:60px">
                <option value="1">＋</option>
                <option value="2">－</option>
            </select>
            <input type="text" name="commChangeAmount" value="" style="width:70px">
            <?php echo lang('usd_money')?>
        </div>
        <div>
            <?php echo lang('why')?>: <textarea rows="3" cols="20" name="commChangeDesc"></textarea>
        </div>
        <div class="row-fluid">
            <input id='commChangeSub' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
            <span class='msg' id='commChangeMsg'></span>
        </div>
    </div>

</div>

<div class="search-well">
    <form class="form-inline" method="GET">
        <input style="width:180px" class="input-small" id="admin_email" type="text" name="admin_email" placeholder="<?php echo lang('operator_email'); ?>" />
        <input class="input-small" id="uid" type="text" name="uid" placeholder="<?php echo lang('user_id'); ?>" />
        

        <button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th style="width: 20%"><?php echo lang('operator_email'); ?></th>
            <th style="width: 15%"><?php echo lang('user_id'); ?></th>
            <th style="width: 15%"><?php echo lang('money_num'); ?></th>
            <th style="width: 30%"><?php echo lang('admin_order_remark'); ?></th>
<!--            <th>--><?php //echo lang('operator_table'); ?><!--</th>-->
<!--            <th>--><?php //echo lang('operator_table_id'); ?><!--</th>-->
            <th style="width: 20%"><?php echo lang('update_time'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo isset($admin_email[$item['admin_id']]) ? $admin_email[$item['admin_id']]['email'] : ''; ?></td>
                    <td><?php echo $item['uid'] ?></td>
                    <td><?php echo $item['comm_amount'] ?></td>
                    <td><?php echo $item['desc'] ?></td>
<!--                    <td>--><?php //echo $item['operator_table'] ?><!--</td>-->
<!--                    <td>--><?php //echo $item['operator_table_id'] ?><!--</td>-->
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
<?php
if (isset($paginate))
{
    echo $paginate;
}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

    function errboxHtml(msg) {
        return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
    }

    $(function() {
        'use strict';

        var page_data = <?php echo json_encode($page_data); ?>;
        $('#status').children('[value=' + page_data.status + ']').prop('selected', true);
        $('#storehouse').children('[value=' + page_data.storehouse + ']').prop('selected', true);
        $('#order_type').children('[value=' + page_data.order_type + ']').prop('selected', true);
        if (page_data.uid != null) {
            $('#uid').val(page_data.uid);
        }
        if (page_data.admin_email != null) {
            $('#admin_email').val(page_data.admin_email);
        }
    });


</script>
