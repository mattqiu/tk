<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.tablesorter.min.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-list3" method="GET">
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
        <input type="text" name="card_number" value="<?php echo $searchData['card_number']; ?>" class="input-medium span2" placeholder="<?php echo lang('paypal_account') ?>">
        <select name="status" class="com_type input-medium">
            <option value=""><?php echo lang('status') ?></option>
            <option value="1" <?php echo $searchData['status'] == '1' ? 'selected' : '' ?>><?php echo lang('tps_status_1') ?></option>
            <option value="2" <?php echo $searchData['status'] == '2' ? 'selected' : '' ?>><?php echo lang('tps_status_2') ?></option>
            <option value="3" <?php echo $searchData['status'] == '3' ? 'selected' : '' ?>><?php echo lang('reject') ?></option>
            <option value="4" <?php echo $searchData['status'] == '4' ? 'selected' : '' ?>><?php echo lang('tps_status_4') ?></option>
        </select>
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
        <!--<button class="btn" onclick="history.go(-1);" type="button"> <?php echo lang('back') ?></button>
        <input autocomplete="off"  name="is_export_lock" type="checkbox" value="1"><?php echo lang('admin_select_is_lock') ?>-->
        <button class="btn" type="button" onclick="exportWithdrawal();"><i class="icon-download-alt"></i> <?php echo lang('export') ?> Paypal</button>
        <button class="btn" type="button" onclick="import_paypal();"><i class="icon-download-alt"></i> <?php echo lang('upload') ?> Paypal</button>
        
        <?php if ($status <2) { ?>
            <?php if (in_array($uid,array(1,3,18,68,64,99,198,60,173,188,212,277,210,8,9,291))) { ?>
            <button class="btn" onclick="cancel_batch(<?php echo $batch_num; ?>)" type="button"> <?php echo lang('cancel_batch') ?></button>
            <button class="btn submit_alipay" onclick="batch_Alipay('<?php echo $searchData['batch_num']; ?>');" type="button"> <?php echo lang('submit_paypal') ?></button>
            <button class="btn" onclick="cancel_batch2(<?php echo $batch_num; ?>)" type="button">线下处理</button>
            <?php } ?>
        <?php } ?>
        <button class="btn" onclick="deal_batch(<?php echo $batch_num; ?>)" type="button"> <?php echo lang('batch_modification'); ?></button>
    </form>
</div>
<div class="well" style="text-align: center;">
    <?php echo sprintf(lang('total_items_ts'), $tongji['zshuliang']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo sprintf(lang('total_money_ts'), sprintf("%.2f", $tongji['zjine'])); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo sprintf(lang('fee_num_ts'), $tongji['sxf']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo sprintf(lang('the_actual_amount_ts'), $tongji['sjje']).'('.$tongji['format_sjje'].')'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<form class="form-list2" method="GET">
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <label style="display: inline;font-weight:bold"><input type="checkbox" class="all_check_input" onclick="check_all(this)" autocomplete="off"/><?php echo lang('all') ?></label>
                    </th>
                    <th><?php echo lang('number_hao') ?></th>
                    <th>ID</th>
                    <th><?php echo lang('member_id') ?></th>
                    <th><?php echo lang('txn_id');?></th>
                    <th><?php echo lang('realName') ?></th>
                    <th><?php echo lang('money_num'); ?></th>

                    <th><?php echo lang('fee_num'); ?></th>
                    <th><?php echo lang('the_actual_amount'); ?><a href="#"><i class="icon-arrow-down"></i></a></th>
                    <th><?php echo lang('Paypal Account number'); ?></th>
                    <th><?php echo lang('admin_order_remark'); ?></th>
                    <th><?php echo lang('application_time'); ?></th>
                    <th><?php echo lang('process_time') ?></th>
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
                            <td class="modal_main">
                            <?php if ($item['status']!=1) { ?>
                            
                                <input type="checkbox" class="checkbox" name="checkboxes[]" onclick='chb_is_checked()' value="<?php echo $item['id'] ?>" autocomplete="off">
                            
                            <?php } ?>
                            </td>    
                            <td><?php echo $k + 1; ?></td>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo $item['uid']; ?></td>
                            <td><?php echo $item['trade_no'];?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td class="amount"><?php echo $item['amount'] ? $item['amount'] : ''; ?></td>
                            <td><?php echo $item['handle_fee']; ?></td>
                            <td><?php echo $item['actual_amount']; ?></td>
                            <td><?php echo $item['card_number']; ?></td>
                            <td><?php echo $item['remark']; ?></td>
                            <td><?php echo substr($item['create_time'], 0, 10).'<br>'.substr($item['create_time'],11); ?></td>
                            <td><?php echo $item['check_time']!='0000-00-00 00:00:00' ? substr($item['check_time'], 0, 10).'<br>'.substr($item['check_time'],11) :""; ?></td>
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
                                <?php if (in_array($uid,array(1,18,68,198,60,173,188,212,277,210,3,8,9,291))) { ?>
                                    <?php if ($item['status'] != 3) { ?>
                                        <div class="btn-group">
                                            <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                                <?php echo lang('action'); ?>
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#" onclick="reject_process(<?php echo $item['id'] ?>, 3);"><?php echo lang('refuse') ?></a>
                                                </li>
                                                <?php if (in_array($item['status'], array('2'))) { ?>
                                                <li>
                                                    <a href="#"  onclick="operating_process(<?php echo $item['id'] ?>, 1);"><?php echo lang("tps_status_1") ?></a>
                                                </li> 
                                               <?php } ?>
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
<!-- 导入paypal弹出层 -->  
<div id="import_paypal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3><?php echo lang('upload') ?> Paypal</h3>  
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
        <form id="upload_form" action="<?php echo base_url('admin/paypal_withdrawal_list/import_paypal') ?>" method="post" class="form-inline" enctype="multipart/form-data">
            <input autocomplete="off" class="input-mini" type="file" name="excelfile"/>
            <input type="hidden" name="detail" value="1"/>
            <input type="hidden" name="batch_num" value="<?php echo $searchData["batch_num"];?>"/>
            <button class="btn" type="submit" id="submit_button"><i class="icon-upload"></i> <?php echo lang('upload') ?> Paypal</button>
        </form>
    </div>
</div>
<style>
    #turn_down_reason{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
</style>
<script>
     $('#upload_form').submit(function () {

        if ($('[name=excelfile]').val() == '') {
            layer.msg('<?php echo lang('admin_select_file') ?>');
            return false;
        }
        var li;
        $('#submit_button').attr('disabled', true);
        $(this).ajaxSubmit({
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    layer.msg(res.msg);
                    setTimeout(function () {
                        location.reload();
                    }, 2000)
                } else {
                    $.thinkbox(errboxHtml(res.msg));
                }
            },
            error: function () {
                layer.msg('<?php echo lang('admin_request_failed') ?>');
            },
            beforeSend: function () {
                li = layer.load();
            },
            complete: function () {
                layer.close(li);
                $('#submit_button').attr('disabled', false);
            }
        });
        return false;
    });
    $(document).ready(function () {
//第一列不进行排序(索引从0开始) 
        $.tablesorter.defaults.headers = {0: {sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false}, 5: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false}, 10: {sorter: false}, 11: {sorter: false}, 12: {sorter: false}, 13: {sorter: false}};
        $("table").tablesorter();
    });
    //提交支付宝js
    function batch_Alipay(id) {
        layer.confirm('<?php echo lang('confirm_execute'); ?>', {icon: 3, title: '<?php echo lang('payment_tip'); ?>'}, function (index) {
        var li;
        li = layer.load();
        $('.submit_alipay').attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "/admin/paypal_withdrawal_list/batch_Alipay",
            data: {id: id},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    layer.close(li);
                    layer.msg('<?php echo lang("result_ok"); ?>');
                    setTimeout("location = '<?php echo base_url('/admin/paypal_withdrawal_list/paypal_withdraw_table'); ?>'", 3000);
                } else {
                    layer.close(li);
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
        });
    }

    //取消批次js
    function cancel_batch(id) {
        layer.confirm('<?php echo lang('double_confirm'); ?>', {icon: 3, title: '<?php echo lang('cancel_confirm'); ?>'}, function (index) {
            $.ajax({
            type: "POST",
            url: "/admin/paypal_withdrawal_list/cancel_batch",
            data: {id: id},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    layer.msg('<?php echo lang("result_ok"); ?>');
                    setTimeout("location = '<?php echo base_url('/admin/paypal_withdrawal_list/paypal_withdraw_table'); ?>'", 3000);
                } else {
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
        });
    }
    //批次改为线下处理js
    function cancel_batch2(id) {
        layer.confirm('确定改为线下处理吗', {icon: 3, title: '线下处理确认'}, function (index) {
            $.ajax({
            type: "POST",
            url: "/admin/paypal_withdrawal_list/cancel_batch2",
            data: {id: id},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    layer.msg('<?php echo lang("result_ok"); ?>');
                    setTimeout("location = '<?php echo base_url('/admin/paypal_withdrawal_list/paypal_withdraw_table'); ?>'", 3000);
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
            url: "/admin/paypal_withdrawal_list/alone_check_one",
            data: {id: id, status: status, cause: cause},
            dataType: "json",
            success: function (res) {
                if (res.success) {
//                    layer.msg('<?php echo lang("result_ok"); ?>');
                    location.reload();
                } else {
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
    //驳回理由确定
    $("#reject_process").click(function () {
        operating_process($("#log_id").val(), $("#log_status").val(), $("#cause").val());
        $('#turn_down_reason').modal('hide');
    });
    //批量修改为已处理状态
    function deal_batch(){
        var len = $(".checkbox:checked").length;
        var ids=new Array();
        $(".checkbox").each(function () {
            if ($(this).attr("checked")) {
                    ids.push($(this).val());
            }
        });
        operating_process(ids,1);
    }
    //上传EXCEL
    function import_paypal() {
        $('#import_paypal').modal();
    }
    //导出EXCEL表格
    function exportWithdrawal() {
        if (znum.toFixed(2) > 60000) {
            layer.msg('<?php echo lang("withdrawal_paypal_tip2"); ?>');
            $('#zje').css("color", "red");
            return false;
        }
        $('.form-list2').attr('action', "<?php echo base_url('/admin/paypal_withdrawal_list/exportWithdrawal'); ?>");
        $('.form-list2').submit();
        $('.form-list2').attr('action', "");
    }
    //全选js
    function check_all(all) {
        var ips = document.getElementsByTagName('input');
        for (var i = 0; i < ips.length; i++) {
            if (ips[i].type == 'checkbox' && !ips[i].disabled && ips[i].name == 'checkboxes[]')
                ips[i].checked = all.checked;
        }
        chb_is_checked();
    }
    //全选附属js
    function chb_is_checked() {
        var len = $(".checkbox:checked").length;
        znum = 0;
        var sxf = 0;
        var sjje = 0;
        z_listnum =0;
        $(".checkbox").each(function () {
            if ($(this).attr("checked")) {
                znum += parseFloat($(this).parent().parent().find(".amount").html());
                sxf += parseFloat($(this).parent().parent().find(".fee_num_ts2").html());
                sjje += parseFloat($(this).parent().parent().find("#shijidaozhang").html());
                z_listnum++;
            }
        });
        $('#zs').html("<?php echo lang('total_items_ts'); ?>".replace("%s", len));
        $('#zje').html("<?php echo lang('total_money_ts'); ?>".replace("%s", znum.toFixed(2)));
        if (z_listnum > 250) {
            layer.msg('<?php echo lang("withdrawal_paypal_tip3"); ?>');
            $('#zje').css("color", "red");
        } else {
            $('#zje').css("color", "#333333");
        }
        $('#sxf').html("<?php echo lang('fee_num_ts'); ?>".replace("%s", sxf.toFixed(2)));
        $('#sjje').html("<?php echo lang('the_actual_amount_ts'); ?>".replace("%s", sjje.toFixed(2)));

    }
</script>