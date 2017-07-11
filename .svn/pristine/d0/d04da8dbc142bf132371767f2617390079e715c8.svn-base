<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('js/jquery.form.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <style>
            .dropdown-menu{
                min-width: 0px;
            }
        </style>
        <input type="text" name="order_id" value="<?php echo $searchData['order_id']; ?>" class="input-medium span2" placeholder="<?php echo lang('admin_order_id') ?>">
        <input type="text" name="txn_id" value="<?php echo $searchData['txn_id']; ?>" class="input-medium span2" placeholder="<?php echo lang('txn_id') ?>">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
         <button class="btn" onclick="history.go(-1);" type="button"> <?php echo lang('back') ?></button>
    </form>
</div>
<form class="form-list2" method="post">
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th width="10%"><?php echo lang('number_hao') ?></th>
                    <!--<th><?php echo lang('id'); ?></th>-->
                    <th><?php echo lang('admin_order_id'); ?></th>
                    <th><?php echo lang('txn_id'); ?></th>
                    <th><?php echo lang('time'); ?></th>
                    <th><?php echo lang('process_result'); ?></th>
                    <th><?php echo lang('remark'); ?></th>
                    <th><?php echo lang('admin_order_operate'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($list) { ?>
                    <?php foreach ($list as $k => $item) { ?>
                        <tr>
                            <td><?php echo $k + 1; ?></td>
                            <!--<td class="modal_main"><?php echo $item['id']; ?></td>-->
                            <td><?php echo $item['order_id']; ?></td>
                            <td><?php echo $item['txn_id']; ?></td>
                            <td><?php echo $item['time']; ?></td>
                            <td><?php echo $item['status']? lang('tps_status_1'):  lang('tps_status_0'); ?></td>
                            <td><?php if($item['num']){?><a href="#" onclick="get_remark_list('<?php echo $item['order_id'] ?>');"><?php echo lang('process') ?></a><?php }?></td>
                            <td>
                                <?php if(!$item['status']){?>
                                <a class="btn" href="#" onclick="reject_process('<?php echo $item['order_id'] ?>');"><?php echo lang('remark') ?></a>
                                <a class="btn" href="#" onclick="reject_process('<?php echo $item['order_id'] ?>', 1);"><?php echo lang('paypal_pending_cl') ?></a>
                                <?php }?>
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
<!-- 备注弹出层 -->  
<div id="turn_down_reason" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3  id="paypalts"></h3>  
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
                <td style="width:12%;"><?php echo lang('remark') ?>：</td>
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
<div id="turn_down_reason2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3><?php echo lang('remark') ?></h3>  
    </div>  
    <div class="modal-body">
        <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th  style="width:50%;"><?php echo lang('label_feedback_content'); ?></th>
                    <th><?php echo lang('operator_email'); ?></th>
                    <th><?php echo lang('time'); ?></th>
                </tr>
            </thead>
            <tbody  id="remark_content">
            </tbody>
        </table>
    </div>
    </div>
</div>
<style>
    #popUserInfo{position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
    #turn_down_reason {position:absolute;left:50%;top:50%;margin-left:-250px;margin-top:-200px;width:500px;}
    #turn_down_reason2 {position:absolute;left:50%;top:50%;margin-left:-500px;margin-top:-200px;width:1000px;z-index: 10000;}
</style>
    <?php echo $pager; ?>
<script>
//操作——备注js
    function reject_process(id, status) {
        if(!status){
            $("#paypalts").html('<?php echo lang('remark') ?>');
        }else{
            $("#paypalts").html('<?php echo lang('paypal_pending_ts') ?>');
        }
        $("#log_id").val(id);
        $("#log_status").val(status);
        $('#turn_down_reason').modal();
    }
    $("#reject_process").click(function () {
        if($("#log_status").val()&&!$("#cause").val()){
            layer.msg('<?php echo lang("pls_input_reson"); ?>');
            return false;
        }
        operating_process($("#log_id").val(),$("#log_status").val(), $("#cause").val());
        $('#turn_down_reason').modal('hide');
    });
    //操作——处理中js
    function operating_process(id,status, cause) {
        (cause) ? (cause) : (cause = '');
        var li;
        li = layer.load();
        $.ajax({
            type: "POST",
            url: "/admin/paypal_pending_log/alone_check_one",
            data: {id: id,status:status,cause: cause},
            dataType: "json",
            success: function (res) {
                layer.closeAll("loading");
                $("#cause").attr("value","");
                if (res.success) {
//                    layer.msg('<?php echo lang("result_ok"); ?>');
                    location.reload();
                } else {
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
    }
    //操作——处理中js
    function get_remark_list(id) {
        var li;
        li = layer.load();
        $.ajax({
            type: "POST",
            url: "/admin/paypal_pending_log/get_remark_list",
            data: {id: id},
            dataType: "json",
            success: function (res) {
                layer.closeAll("loading");
                if (res.success) {
                    $('#turn_down_reason2').modal();
                    var html='';
                        $.each(res.data, function(){
                            html+='<tr><td>'+this.remark+'</td>'+'<td>'+this.admin_user+'</td>'+'<td>'+this.time+'</td></tr>';
                        });
                        $('#remark_content').html(html);
                } else {
                    layer.msg('<?php echo lang("result_false"); ?>');
                }
            }
        });
    }
</script>