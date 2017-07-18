<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.tablesorter.min.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <style>
            .dropdown-menu{
                min-width: 0px;
            }
            /*            .table{
                            border-top: 1px solid #000;
                        }
                        .table  th{
                            border: 1px solid #000;
                        }
                        .table  td{
                            border: 1px solid #000;
                        }*/
        </style>
        <input type="text" name="uid" value="<?php echo $searchData['uid']; ?>" class="input-medium span2" placeholder="<?php echo lang('card_notice') ?>">
        <input type="text" name="card_number" value="<?php echo $searchData['card_number']; ?>" class="input-medium span2" placeholder="<?php echo lang('bank_card_num') ?>">
        <select name="status" class="com_type input-medium">
            <option value=""><?php echo lang('status') ?></option>
            <option value="1" <?php echo $searchData['status'] == '1' ? 'selected' : '' ?>><?php echo lang('tps_status_1') ?></option>
            <option value="2" <?php echo $searchData['status'] == '2' ? 'selected' : '' ?>><?php echo lang('tps_status_2') ?></option>
            <option value="3" <?php echo $searchData['status'] == '3' ? 'selected' : '' ?>><?php echo lang('reject') ?></option>
            <option value="4" <?php echo $searchData['status'] == '4' ? 'selected' : '' ?>><?php echo lang('tps_status_4') ?></option>
        </select>
        <select name="pay_type" class="com_type input-medium">
            <option value=""><?php echo lang('payment_interface') ?></option>
            <option value="1" <?php echo $searchData['status'] == '1' ? 'selected' : '' ?>><?php echo lang('payment_type_1') ?></option>
            <option value="2" <?php echo $searchData['status'] == '2' ? 'selected' : '' ?>><?php echo lang('payment_type_2') ?></option>
            <option value="3" <?php echo $searchData['status'] == '3' ? 'selected' : '' ?>><?php echo lang('payment_type_3') ?></option>
            <option value="4" <?php echo $searchData['status'] == '4' ? 'selected' : '' ?>><?php echo lang('payment_type_4') ?></option>
        </select>
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
        <?php if ($status <2) { ?>
            <?php if (in_array($uid,array(1,18,99,3,464))) { ?>
            <button class="btn submit_alipay" onclick="batch_Alipay('<?php echo $searchData['batch_num']; ?>');" type="button"> <?php echo lang('submit_pay_tyep') ?></button>
            <button class="btn" onclick="cancel_batch(<?php echo $batch_num; ?>)" type="button"> <?php echo lang('cancel_batch') ?></button>
            <?php } ?>
        <?php }else if($status ==2 && in_array($uid,array(1,99,3,18,198,464))){?>
            <button class="btn" onclick="cancel_batch(<?php echo $batch_num; ?>)" type="button"> <?php echo lang('cancel_batch') ?></button>
        <?php } ?>
    </form>
</div>
<div class="well" style="text-align: center;">
    <?php echo sprintf(lang('total_items_ts'), $tongji['zshuliang']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo sprintf(lang('total_money_ts'), sprintf("%.2f", $tongji['zjine'])); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo sprintf(lang('fee_num_ts'), $tongji['sxf']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo sprintf(lang('the_actual_amount_ts'), $tongji['sjje']).'('.$tongji['format_sjje'].')'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<form class="form-list2" method="post">
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo lang('number_hao') ?></th>
                    <th>ID</th>
                    <th><?php echo lang('member_id') ?></th>
                    <th><?php echo lang('realName') ?></th>
                    <th><?php echo lang('money_num'); ?></th>

                    <th><?php echo lang('fee_num'); ?></th>
                    <th><?php echo lang('the_actual_amount'); ?><a href="#"><i class="icon-arrow-down"></i></a></th>
                    <th><?php echo lang('exchange_rate') ?></th>
                    <th>CNY(￥)</th>
                    <th><?php echo lang('paypal_account_t'); ?></th>
                    <th><?php echo lang('payee_name'); ?></th>
                    <th width="5%"><?php echo lang('payment_interface'); ?></th>
                    <!--<th><?php echo lang('admin_order_remark'); ?></th>-->
                    <th><?php echo lang('application_time'); ?></th>
                    <th><?php echo lang('status'); ?></th>
                    <!--<th><?php echo lang('process_result'); ?></th>-->
                    <!--<th><?php echo lang('cause'); ?></th>-->
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($list) { ?>
                    <?php foreach ($list as $k => $item) { ?>
                        <tr>
                            <td><?php echo $k + 1; ?></td>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo $item['uid']; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td class="amount"><?php echo $item['amount'] ? $item['amount'] : ''; ?></td>
                            <td><?php echo $item['handle_fee']; ?></td>
                            <td><?php echo $item['actual_amount']; ?></td>
                            <td><?php echo $rate; ?></td>
                            <td><?php echo sprintf("%.2f", $item['actual_amount'] * $rate); ?></td>
                            <td><?php echo $item['card_number']; ?></td>
                            <td><?php echo $item['account_name']; ?></td>
                            <td><?php echo $item['pay_type']?lang('payment_type_'.$item['pay_type']):'未选择'; ?></td>
                            <!--<td><?php echo $item['remark']; ?></td>-->
                            <td><?php echo substr($item['create_time'], 0, 10).'<br>'.substr($item['create_time'],11); ?></td>
                            <td <?php
                            switch ($item['status']) {
                                case 0:
                                    echo 'class="text-error"';
                                    break;
                                case 1:
                                    echo 'class="text-success"';
                                    break;
                                case 2:
                                    echo 'class="text-warning"';
                                    break;
                                case 3:
                                    echo 'class="text-error"';
                                    break;
                            }
                            ?>><?php echo $item['check_info']?'<a rel="tooltip" href="##" data-original-title="'.$item['check_info'].'"><i class="icon-question-sign"></i></a>':'';echo lang('tps_status_' . $item['status']); ?></td>
                            <!--<td><?php echo lang('tps_status_' . $item['status']); ?></td>-->
                            <!--<td><?php echo $item['check_info']; ?></td>-->
                            <td width="8%">
                                <?php if (in_array($uid,array(1,18,64,99,68,198,464))) { ?>
                                    <?php if ($item['status'] != 3&&$status <2) { ?>
                                        <div class="btn-group">
                                            <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                                <?php echo lang('action'); ?>
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#" onclick="reject_process(<?php echo $item['id'] ?>, 3);"><?php echo lang('refuse') ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
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
<!-- 驳回理由弹出层 -->  
<div id="turn_down_reason" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3><?php echo lang('reject_confirm') ?></h3>  
    </div>  
    <div class="modal-body">
        <style>
            .word_break{
                color: #000000;
                display: block;
                font-weight: normal;
                word-break: break-all;
                word-wrap: break-word;
            }
        </style>
        <table class="enable_level_tb" style="border-collapse: separate;border-spacing: 5px;width: 450px">
            <tr>
                <td style="width:12%;" ><?php echo lang('cause') ?>：</td>
                <td><textarea id="cause" style="width:350px;height: 150px;" name="cause"></textarea></td>
            </tr>
            <tr>
                <td class="title"></td>
                <td class="main" >
                    <div style="width:50%;float:left"><button class="btn" id="reject_process" type="button" style="margin-left:40%;"><?php echo lang('confirm') ?></button></div>
                    <button class="btn" class="btn btn-default" data-dismiss="modal"><?php echo lang('admin_order_cancel') ?></button></td>
            </tr>

        </table>
    </div><input type="hidden" id="log_id" value="" />
    <input type="hidden" id="log_status" value="" />
</div>
<!-- 驳回理由弹出层 -->
<style>
    #turn_down_reason{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
</style>
<script>
    $(document).ready(function () {
//第一列不进行排序(索引从0开始) 
        $.tablesorter.defaults.headers = {0: {sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false}, 5: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false}, 10: {sorter: false}, 11: {sorter: false}, 12: {sorter: false}, 13: {sorter: false}};
        $("table").tablesorter();
    });
    //提交支付宝js
    function batch_Alipay(id) {
        var li;
        li = layer.load();
        $('.submit_alipay').attr('disabled',true);
        if(!$("select[name='pay_type']").val()){
            layer.close(li);
            layer.msg('<?php echo '未选择代付方式'; ?>');
        }
        $.ajax({
            type: "POST",
            url: "/admin/bank_withdraw/batch_Alipay",
            data: {id: id,pay_type:$("select[name='pay_type']").val()},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#turn_down_reason').append(res.data);
                } else {
                    layer.close(li);
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
    }

    //取消批次js
    function cancel_batch(id) {
        layer.confirm('<?php echo lang('double_confirm'); ?>', {icon: 3, title: '<?php echo lang('cancel_confirm'); ?>'}, function (index) {
            $.ajax({
            type: "POST",
            url: "/admin/bank_withdraw/generate_batch_qx",
            data: {id: id},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    layer.msg('<?php echo lang("result_ok"); ?>');
                    setTimeout("location = '<?php echo base_url('/admin/bank_withdraw/bank_withdraw_batch'); ?>'", 3000);
                } else {
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
        });
    }
    //操作——处理中js
    function operating_process(id, status, cause) {
        (cause) ? (cause) : (cause = '');
        var li;
        li = layer.load();
        $.ajax({
            type: "POST",
            url: "/admin/bank_withdraw/alone_check_one",
            data: {id: id, status: status, cause: cause},
            dataType: "json",
            success: function (res) {
                if (res.success) {
//                    layer.msg('<?php echo lang("result_ok"); ?>');
                    location.reload();
                } else {
                    layer.close(li);
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
    }
    //操作——驳回js
    function reject_process(id, status) {
        $("#log_id").val(id);
        $("#log_status").val(status);
        $('#turn_down_reason').modal();
    }
    $("#reject_process").click(function () {
        operating_process($("#log_id").val(), $("#log_status").val(), $("#cause").val());
        $('#turn_down_reason').modal('hide');
    });
</script>