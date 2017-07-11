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
        <input type="text" name="card_number" value="<?php echo $searchData['card_number']; ?>" class="input-medium span2" placeholder="<?php echo lang('paypal_account') ?>">
        <select name="country_id" class="com_type input-medium">
            <option value=""><?php echo lang('country') ?></option>
            <option value="1" <?php echo $searchData['country_id'] == '1' ? 'selected' : '' ?>><?php echo lang('con_china') ?></option>
            <option value="2" <?php echo $searchData['country_id'] == '2' ? 'selected' : '' ?>><?php echo lang('con_usa') ?></option>
            <option value="3" <?php echo $searchData['country_id'] == '3' ? 'selected' : '' ?>><?php echo lang('con_korea') ?></option>
            <option value="4" <?php echo $searchData['country_id'] == '4' ? 'selected' : '' ?>><?php echo lang('con_hongkong') ?></option>
            <option value="x" <?php echo $searchData['country_id'] == 'x' ? 'selected' : '' ?>><?php echo lang('other') ?></option>
        </select>
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
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
            <!--<button class="btn" type="button" onclick="exportWithdrawal();"><?php echo lang('export_EXCEL') ?></button>-->
        <?php if(in_array($adminInfo['role'],array(4,0))||in_array($adminInfo['id'],array(1,3,18,68,198,99,60,173,188,212,277,210,8,9,291))){?>
        <input autocomplete="off"  name="is_export_lock" type="checkbox" value="1"><?php echo lang('admin_select_is_lock') ?>
        <button class="btn" type="button" onclick="exportWithdrawal();"><i class="icon-download-alt"></i> <?php echo lang('export') ?> Paypal</button>
        <button class="btn" type="button" onclick="import_paypal();"><i class="icon-download-alt"></i> <?php echo lang('upload') ?> Paypal</button>
        <a class="btn" href="<?php echo base_url('/admin/paypal_withdrawal_list/paypal_withdraw_table') ?>" ><?php echo lang('view_batch') ?></a>
        <?php }?>
        <?php if(in_array($adminInfo['role'],array(4)) || in_array($adminInfo['id'],array(1,3,18,68,198,99,60,173,188,212,277,210,8,9,291)) ){?>
        <button class="popUserInfo btn" onclick="generate_batch();" type="button"><?php echo lang('generate_batch') ?></button>
        <?php }?>
    </div>
    <div class="well" style="text-align: center;">
        <span id="zs"><?php echo sprintf(lang('total_items_ts'), 0); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="zje"><?php echo sprintf(lang('total_money_ts'), 0); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="sxf"><?php echo sprintf(lang('fee_num_ts'), 0); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="sjje"><?php echo sprintf(lang('the_actual_amount_ts'), 0); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <label style="display: inline;font-weight:bold"><input type="checkbox" class="all_check_input" onclick="check_all(this)" autocomplete="off"/><?php echo lang('all') ?></label>
                    </th>
                    <th>ID</th>
                    <th>UID</th>
                    <th><?php echo lang('txn_id');?></th>
                    <th><?php echo lang('country') ?></th>
                    <th><?php echo lang('name'); ?></th>
                    <th><?php echo lang('money'); ?></th>
                    <th><?php echo lang('withdrawal_fee_'); ?></th>
                    <th><?php echo lang('withdrawal_actual_'); ?><a href="#"><i class="icon-arrow-down"></i></a></th>
                    <th><?php echo lang('Paypal Account number'); ?></th>
                    <th style="width: 5%;"><?php echo lang('remark'); ?></th>
                    <th><?php echo lang('generate_time'); ?></th>
                    <th><?php echo lang('process_time') ?></th>
                    <th><?php echo lang('txn_id'); ?></th>
                    <th><?php echo lang('status'); ?></th>
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
                            <td><?php echo $item['id']; ?></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $item['uid']; ?></td>
                            <td><?php echo $item["trade_no"];?></td>
                            <td><?php switch($item["country_id"]){
                                case "1":echo lang('con_china');break;
                                case "2":echo lang('con_usa');break;
                                case "3":echo lang('con_korea');break;
                                case "4":echo lang('con_hongkong');break;
                                default:echo lang('other');
                            };?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td class="amount"><?php echo $item['amount'] ? $item['amount'] : ''; ?></td>
                            <td class="fee_num_ts2"><?php echo $item['handle_fee']; ?></td>
                            <td id="shijidaozhang"><?php echo $item['actual_amount']; ?></td>
                            <td><?php echo $item['card_number']; ?></td>
                            <td><?php echo $item['remark']; ?></td>
                            <td><?php echo substr($item['create_time'], 0, 10).'<br>'.substr($item['create_time'],11); ?></td>
                            <td><?php echo $item['check_time']!='0000-00-00 00:00:00' ? substr($item['check_time'], 0, 10).'<br>'.substr($item['check_time'],11) :""; ?></td>
                            <td><?php echo $item['trade_no'] ?></td>
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
                            }?>>
                                <?php 
                               // if($item['status'] == 3){
                                   $check_txt =  $item['check_info']!="收件人的帐户已锁定或无效" ? $item['check_info'] : "This email did not go to your Paypal registered Paypal account, or your Paypal account not login authentication Paypal website./님의 이 이메일 주소는 아직 Paypal계정으로 등록되여 있지 않았거나 Paypal사이트에서 로그인 인증을 마치지 않았습니다.";
                               // }
                                    echo $item['check_info'] && $item['status'] == 3 ? '<a rel="tooltip" href="##" data-original-title="' . $check_txt . '"><i class="icon-question-sign"></i></a>' : '';
                                    echo lang('tps_status_' . $item['status']);
                                    ?></td>
                            <td        ><?php echo $item['batch_num'] ? $item['batch_num'] : ''; ?></td>
                            <td width="8%">
                                <?php if (in_array($item['status'], array('0','1','2'))&& in_array($adminInfo['id'],array(1,3,18,68,198,99,60,173,188,212,277,210,8,9,291)) ) { ?>
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
                                            <?php if (in_array($item['status'], array('2'))) { ?>
                                             <li>
                                                 <a href="#"  onclick="operating_process(<?php echo $item['id'] ?>, 1);"><?php echo lang("tps_status_1") ?></a>
                                             </li> 
                                            <?php } ?>
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
    <input id="batch_number"  name="batch_number" type="hidden" value="" />
    <input id="total" name="total" type="hidden" value="" />
    <input id="lump_sum" name="lump_sum" type="hidden" value="" />
    <input id="handle_fee" name="handle_fee" type="hidden" value="" />
    <input id="actual_amount" name="actual_amount" type="hidden" value="" />
    <div style="float:left;margin: 20px 0;">
        <select style="width:60px;" id="page_num" name="page_num">
            <option value="30" <?php echo $page_num == '30' ? 'selected' : '' ?> >30</option>
            <option value="50" <?php echo $page_num == '50' ? 'selected' : '' ?> >50</option>
            <option value="80" <?php echo $page_num == '80' ? 'selected' : '' ?> >80</option>
            <option value="100" <?php echo $page_num == '100' ? 'selected' : '' ?> >100</option>
            <option value="200" <?php echo $page_num == '200' ? 'selected' : '' ?> >200</option>
            <option value="250" <?php echo $page_num == '250' ? 'selected' : '' ?> >250</option>
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
            <!--
            <tr>
                <td class="title"><?php echo lang('admin_order_rate') ?>:</td>
                <td class="main" id="admin_order_rate"><?php echo $rate; ?></td>
            </tr>
            -->
            <tr>
                <td class="title"><?php echo lang('fee_num_ts2') ?>:</td>
                <td class="main" id="fee_num_ts2"></td>
            </tr>
            <tr>
                <td class="title"><?php echo lang('total_money') ?>:</td>
                <td class="main" id="total_money"></td>
            </tr>
            <!--
            <tr>
                <td class="title"><?php echo lang('payment_reason') ?>：</td>
                <td class="main"  id="info_month_level_change_log">
                    <select id="u185_input">
                        <option value=""><?php echo lang('please_choose') ?></option>
                        <option value="4"><?php echo lang('choose4') ?></option>
                    </select>
                </td>
            </tr>
            -->
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
            <input type="hidden" id="log_id" value="" />
            <input type="hidden" id="log_status" value="" />
        </table>
    </div>
</div>
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
            <button class="btn" type="submit" id="submit_button"><i class="icon-upload"></i> <?php echo lang('upload') ?> Paypal</button>
        </form>
    </div>
</div>
<!-- /用户详细信息弹层 -->
<style>
    #popUserInfo{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
    #turn_down_reason{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
    #import_paypal{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
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
        $.tablesorter.defaults.headers = {0: {sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false}, 6: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false}, 10: {sorter: false}};
        $("table").tablesorter();
    });
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
    //选择信息数量js
    $("#page_num").change(function () {
        $(".form-list2").submit()
    });

    //操作——驳回js
    function reject_process(id, status) {
        $("#log_id").val(id);
        $("#log_status").val(status);
        $('#turn_down_reason').modal();
    }
    //上传EXCEL
    function import_paypal() {
        $('#import_paypal').modal();
    }
    $("#reject_process").click(function () {
        operating_process($("#log_id").val(), $("#log_status").val(), $("#cause").val());
        $('#turn_down_reason').modal('hide');
    });
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
    //生成批次按钮js
    function generate_batch() {
        var len = $(".checkbox:checked").length;
        var znum = 0;
        var sxf = 0;
        var sjje = 0;
        $('#total_items').html(len);
        $(".checkbox").each(function () {
            if ($(this).attr("checked")) {
                znum += parseFloat($(this).parent().parent().find(".amount").html());
                sxf += parseFloat($(this).parent().parent().find(".fee_num_ts2").html());
                sjje += parseFloat($(this).parent().parent().find("#shijidaozhang").html());
            }
        });
        $('#total_money').html('$' + znum.toFixed(2));
        $('#fee_num_ts2').html('$' + sxf.toFixed(2));
        $.ajax({
            type: "POST",
            url: "/admin/paypal_withdrawal_list/only_name",
            data: '',
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $("#generate_batch_num").html(res.msg);
                    $("#batch_number").val(res.msg);
                    $("#total").val(len);
                    $("#lump_sum").val(znum);
                    $("#handle_fee").val(sxf);
                    $("#actual_amount").val(sjje);
                }
            }
        });
        $('#popUserInfo').modal();
    }
    //提交生成批次信息js
    $(".confirm").click(function () {
        $(this).attr('disabled','disabled');
        var len = $(".checkbox:checked").length;
        if (len == 0) {
            layer.msg('<?php echo lang("unselected"); ?>');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/admin/paypal_withdrawal_list/generate_batch",
            data: $(".form-list2").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#popUserInfo').modal('hide');
                    layer.msg(res.msg);
                    setTimeout("location = '<?php echo base_url('/admin/paypal_withdrawal_list/paypal_withdraw_table'); ?>'", 0);
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
        var len = $(".checkbox:checked").length;
        znum = 0;
        z_listnum =0;
        var sxf = 0;
        var sjje = 0;
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
            $(".popUserInfo").hide();
        } else {
            $('#zje').css("color", "#333333");
            $(".popUserInfo").show();
        }
        $('#sxf').html("<?php echo lang('fee_num_ts'); ?>".replace("%s", sxf.toFixed(2)));
        $('#sjje').html("<?php echo lang('the_actual_amount_ts'); ?>".replace("%s", sjje.toFixed(2)));

    }
</script>