<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.tablesorter.min.js?v=1'); ?>"></script>
<form class="form-list2" method="GET">
    <div class="search-well">

        <style>
            .dropdown-menu{
                min-width: 0px;
            }
        </style>
        <input type="text" name="uid" value="<?php echo $searchData['uid']; ?>" class="input-medium span2" placeholder="<?php echo lang('card_notice') ?>">
        <input type="text" name="card_number" value="<?php echo $searchData['card_number']; ?>" class="input-medium span2" placeholder="<?php echo lang('bank_card_num') ?>">

        <select name="status" class="com_type input-medium">
            <option value=""><?php echo lang('status') ?></option>
            <option value="0" <?php echo $searchData['status'] == '0' ? 'selected' : '' ?>><?php echo lang('tps_status_0') ?></option>
            <option value="1" <?php echo $searchData['status'] == '1' ? 'selected' : '' ?>><?php echo lang('tps_status_1') ?></option>
            <option value="2" <?php echo $searchData['status'] == '2' ? 'selected' : '' ?>><?php echo lang('tps_status_2') ?></option>
            <option value="3" <?php echo $searchData['status'] == '3' ? 'selected' : '' ?>><?php echo lang('reject') ?></option>
            <option value="4" <?php echo $searchData['status'] == '4' ? 'selected' : '' ?>><?php echo lang('tps_status_4') ?></option>
        </select>
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <label>
            <input type="text" name="batch_num" value="<?php echo $searchData['batch_num']; ?>" class="input-medium span2" placeholder="<?php echo lang('batch_number') ?>">
            <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
            <!--<button class="btn" type="button" onclick="exportWithdrawal();"><?php echo lang('export_EXCEL') ?></button>-->
            <?php if(in_array($adminInfo['role'],array(4)) || in_array($adminInfo['id'],array(1,99,18,68,198,144,280,464)) ){?>
            <button class="btn" onclick="generate_batch();" type="button"><?php echo lang('generate_batch') ?></button>
            <?php }?>
            <a class="btn" href="<?php echo base_url('/admin/bank_withdraw/bank_withdraw_batch') ?>" ><?php echo lang('view_batch') ?></a>
        </label>
        <label>
            <input value="<?php echo $rate; ?>" class="span2" autocomplete="off" placeholder="<?php echo lang('admin_order_rate') ?>"  name="rate" type="text">
            <button class="btn withdrawal_rate" type="button"><?php echo lang('submit') ?></button>
        </label>

    </div>
    <div class="well" style="text-align: center;">
        <?php echo sprintf(lang('total_items_ts'), $tongji['zshuliang']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo sprintf(lang('total_money_ts'), $tongji['zjine']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo sprintf(lang('fee_num_ts'), $tongji['sxf']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php echo sprintf(lang('the_actual_amount_ts'), $tongji['sjje']).'('.$tongji['format_sjje'].')'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <label style="display: inline;font-weight:bold"><input type="checkbox" class="all_check_input" onclick="check_all(this)" autocomplete="off"/><?php echo lang('all') ?></label>
                    </th>
                    <th><?php echo lang('number_hao') ?></th>
                    <th><?php echo lang('member_id') ?></th>
                    <th><?php echo lang('realName') ?></th>
                    <th><?php echo lang('money_num'); ?></th>

                    <th><?php echo lang('fee_num'); ?></th>
                    <th><?php echo lang('the_actual_amount'); ?><a href="#"><i class="icon-arrow-down"></i></a></th>
                    <th><?php echo lang('exchange_rate') ?></th>
                    <th>CNY(￥)</th>
                    <th width="5%"><?php echo lang('bank_card_num'); ?></th>
                    <th><?php echo lang('payee_name'); ?></th>
                    <th width="5%"><?php echo lang('payment_interface'); ?></th>
                    <th><?php echo lang('application_time'); ?></th>
                    <th><?php echo lang('status'); ?></th>
                    <th><?php echo lang('batch_number'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($list) { ?>
                    <?php foreach ($list as $k => $item) { ?>
                        <tr>
                            <td class="modal_main">
                                <?php if (!$item['status']) { ?>
                                    <input type="checkbox" class="checkbox" name="checkboxes[]" onclick='chb_is_checked()' value="<?php echo $item['id'] ?>" autocomplete="off">
                                <?php } ?>
                            </td>
                            <td><?php echo $k + 1; ?></td>
                            <td><?php echo $item['uid']; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td class="amount"><?php echo $item['amount'] ? $item['amount'] : ''; ?></td>
                            <td class="fee_num_ts2"><?php echo $item['handle_fee']; ?></td>
                            <td><?php echo $item['actual_amount']; ?></td>
                            <td><?php echo $hl=$item['exchange_rate'] ? $item['exchange_rate'] : $rate; ?></td>
                            <td><?php echo sprintf("%.2f", $item['actual_amount'] * $hl); ?></td>
                            <td><?php echo $item['card_number']; ?></td>
                            <td><?php echo $item['account_name']; ?></td>
                            <td><?php echo $item['pay_type']?lang('payment_type_'.$item['pay_type']):'未选择'; ?></td>
                            <td><?php echo substr($item['create_time'], 0, 10) . '<br>' . substr($item['create_time'], 11); ?></td>
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
                            ?>><?php echo $item['check_info'] && $item['status'] == 3 ? '<a rel="tooltip" href="##" data-original-title="' . $item['check_info'] . '"><i class="icon-question-sign"></i></a>' : '';
                            echo lang('tps_status_' . $item['status']); ?></td>
                            <td><?php echo $item['batch_num'] ? $item['batch_num'] : ''; ?></td>
                            <td width="8%">
                                        <?php if (!$item['status']) { ?>
                                    <div class="btn-group">
                                        <a class="btn btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
            <?php echo lang('action'); ?>
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <!-- dropdown menu links -->
                                            <li>
                                                <a href="#" onclick="reject_process(<?php echo $item['id'] ?>, 3);"><?php echo lang('refuse') ?></a>
                                            </li>
                                            <!-- <li>
                                                 <a href="#"  onclick="operating_process(<?php echo $item['id'] ?>, 2);"><?php echo lang("tps_status_2") ?></a>
                                             </li> -->
                                        </ul>
                                    </div>
                        <?php } ?>
                            </td>
                        </tr>
    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <th colspan="15" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
                    </tr>
<?php } ?>
            </tbody>
        </table>
    </div>
    <input id="payment_reason" name="payment_reason" type="hidden" value="" />
    <input id="batch_number"  name="batch_number" type="hidden" value="" />
    <input id="total" name="total" type="hidden" value="" />
    <input id="lump_sum" name="lump_sum" type="hidden" value="" />
    <div style="float:left;margin: 20px 0;">
        <select style="width:60px;" id="page_num" name="page_num">
            <option value="100" <?php echo $page_num == '100' ? 'selected' : '' ?> >100</option>
            <option value="200" <?php echo $page_num == '200' ? 'selected' : '' ?> >200</option>
            <option value="300" <?php echo $page_num == '300' ? 'selected' : '' ?> >300</option>
            <option value="400" <?php echo $page_num == '400' ? 'selected' : '' ?> >400</option>
            <option value="500" <?php echo $page_num == '500' ? 'selected' : '' ?> >500</option>
            <option value="600" <?php echo $page_num == '600' ? 'selected' : '' ?> >600</option>
            <option value="700" <?php echo $page_num == '700' ? 'selected' : '' ?> >700</option>
        </select>
    </div>
    <div style="float:right;">
<?php echo $pager; ?>
    </div>
    <div class="clearfix"></div>
</form>
<!-- 生成批次弹出层 -->  
<div id="popUserInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3><?php echo lang('batch_processing') ?></h3>  
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
                <td class="title"><?php echo lang('generate_batch_num') ?>：</td>
                <td class="main" id="generate_batch_num"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('total_items') ?>:</td>
                <td class="main" id="total_items"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('admin_order_rate') ?>:</td>
                <td class="main" id="admin_order_rate"><?php echo $rate; ?></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('fee_num_ts2') ?>:</td>
                <td class="main" id="fee_num_ts2"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('total_money') ?>:</td>
                <td class="main" id="total_money"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('payment_reason') ?>：</td>
                <td class="main"  id="info_month_level_change_log">
                    <select id="u185_input">
                        <option value=""><?php echo lang('please_choose') ?></option>
<!--                        <option value="1"><?php echo lang('choose1') ?></option>
                        <option value="2"><?php echo lang('choose2') ?></option>
                        <option value="3"><?php echo lang('choose3') ?></option>-->
                        <option value="4"><?php echo lang('choose4') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
            <input type="hidden" id="log_id" value="" />
            <input type="hidden" id="log_status" value="" />
            <td class="title"></td>
            <td class="main" ><button class="btn confirm" type="button" style="margin-right: 45px;"><?php echo lang('confirm') ?></button><button class="btn" class="btn btn-default" data-dismiss="modal"><?php echo lang('admin_order_cancel') ?></button></td>
            </tr>

        </table>
    </div>
</div>
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
    </div>
</div>
<!-- /用户详细信息弹层 -->
<style>
    #popUserInfo{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
    #turn_down_reason{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
</style>
<script>
    $(document).ready(function () {
//第一列不进行排序(索引从0开始) 
        $.tablesorter.defaults.headers = {0: {sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false}, 5: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false}, 10: {sorter: false}, 11: {sorter: false}, 12: {sorter: false}, 13: {sorter: false}};
        $("table").tablesorter();
    });
    ///******汇率自定义
    $('.withdrawal_rate').click(function () {
        var rate = $('[name="rate"]').val();
        
        if (rate < 0 || rate >= 7 || isNaN(rate)) {
            layer.msg('<?php echo lang('admin_exchange_rate_error') ?>');
            return;
        }
        
        var li;
        li = layer.load();
        $('.withdrawal_rate').attr('disabled','disabled');
        $.post("/admin/bank_withdraw/withdrawal_rate", {rate: rate}, function (data) {
            if (data.success) {
                location.reload();
                $('.withdrawal_rate').attr('disabled',false);
            } else {
                layer.close(li);
                layer.msg(data.msg);
                location.reload();
            }
        }, 'json');
        
    });
    //选择付款理由js
    $("#page_num").change(function () {
        $(".form-list2").submit()
    });
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
    function exportWithdrawal() {
        $('.form-list2').attr('action', "<?php echo base_url('/admin/bank_withdraw/exportWithdrawal'); ?>");
        $('.form-list2').submit();
        $('.form-list2').attr('action', "");
    }
    
    //生成批次按钮js
    function generate_batch() {
        var len = $(".checkbox:checked").length;
        var znum = 0;
        var sxf = 0;
        $('#total_items').html(len);
        $(".checkbox").each(function () {
            if ($(this).attr("checked")) {
                znum += parseFloat($(this).parent().parent().find(".amount").html());
                sxf += parseFloat($(this).parent().parent().find(".fee_num_ts2").html());
            }
        });
        $('#total_money').html('$' + znum.toFixed(2));
        $('#fee_num_ts2').html('$' + sxf.toFixed(2));
        $.ajax({
            type: "POST",
            url: "/admin/bank_withdraw/only_name",
            data: '',
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $("#generate_batch_num").html(res.msg);
                    $("#batch_number").val(res.msg);
                    $("#total").val(len);
                    $("#lump_sum").val(znum);
                }
            }
        });
        $('#popUserInfo').modal();
    }
    //选择付款理由js
    $("#u185_input").change(function () {
        $("#payment_reason").val($(this).val())
    });
    //提交生成批次信息js
    $(".confirm").click(function () {
        $(this).attr('disabled','disabled');
        var len = $(".checkbox:checked").length;
        if (len == 0) {
            layer.msg('<?php echo lang("unselected"); ?>');
            return false;
        }
        if (!$("#payment_reason").val()) {
            layer.msg('<?php echo lang("unselected_reason"); ?>');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/admin/bank_withdraw/generate_batch",
            data: $(".form-list2").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#popUserInfo').modal('hide');
                    layer.msg(res.msg);
                    setTimeout("location = '<?php echo base_url('/admin/bank_withdraw/bank_withdraw_batch'); ?>'", 0);
                } else if (res.msg == 8) {
                    layer.msg('<?php echo lang("batch_error"); ?>');
                } else {
                    layer.msg(res.msg);
                }
            }
        });
    });
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
        var is_checked = false;
        var ips = document.getElementsByTagName('input');
        for (var i = 0; i < ips.length; i++) {
            if (ips[i].type == "checkbox" && !ips[i].disabled && ips[i].checked && ips[i].name == 'checkboxes[]') {
                is_checked = true;
                break;
            }
        }
        var batch_type = document.getElementById('batch_type');
        if (is_checked) {
            if (batch_type != null) {
                document.getElementById('batch_type').disabled = '';
                document.getElementById('btn_type').disabled = '';
            }
        } else {
            if (batch_type != null) {
                document.getElementById('batch_type').disabled = 'disabled';
                document.getElementById('btn_type').disabled = 'disabled';
            }
        }
    }
</script>