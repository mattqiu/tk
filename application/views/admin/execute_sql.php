<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<textarea id="sql_input" style="width: 90%;height: 100px; color: #333;font-size: 16px" placeholder="<?php echo lang('please_enter_sql')?>">
</textarea>

<br>
<textarea id="remark_input" style="width: 90%;height: 50px; color: #333;font-size: 16px" placeholder="<?php echo lang('please_enter_remark')?>">
</textarea>

<br>

<input type="button" id="submit_sql" class="btn btn-primary" value="<?php echo lang('submit')?>">
<span class="message" style="margin-left: 50px; font-size: 14px;"></span>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<br><br><br>
<div class="search-well">


    <form class="form-inline" method="GET">
        <select name="status">
            <?php foreach($status_list as $key => $value){?>
                    <?php if("$key" === $searchData['status']){?>
                        <option value="<?php echo $key?>" selected><?php echo $value?></option>
                    <?php }else{?>
                        <option value="<?php echo $key?>"><?php echo $value?></option>
                    <?php }?>
            <?php }?>
        </select>

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>
<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('sql_source'); ?></th>
            <th><?php echo lang('remark'); ?></th>
            <th><?php echo lang('submit_email'); ?></th>
            <th><?php echo lang('status'); ?></th>

            <!--未处理-->
            <?php if($searchData['status'] == 0){?>
                <?php if(in_array($admin_id,array(1,5,70))){?>
                    <th><?php echo lang('action'); ?></th>
                <?php }?>
            <?php }?>


            <!--通过-->
            <?php if($searchData['status'] == 1){?>
                <th><?php echo lang('audit_email'); ?></th>
                <th><?php echo lang('audit_time'); ?></th>
            <?php }?>

            <!--驳回-->
            <?php if($searchData['status'] == 2){?>
                <th><?php echo lang('audit_email'); ?></th>
                <th><?php echo lang('audit_time'); ?></th>
                <th><?php echo lang('refuse_reason'); ?></th>
            <?php }?>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td style="width: 290px"><?php echo $item['sql'] ?></td>
                    <td style="width: 190px"><?php echo $item['remark'] ?></td>
                    <td><?php echo $item['admin_id'] ?></td>

                    <!--未处理-->
                    <?php if($item['status'] == 0){?>
                        <td><?php echo lang('awaiting_processing') ?></td>
                        <?php if(in_array($admin_id,array(1,5,70))){?>
                            <td>
                                <input type="button" id="approve" onclick="approve(<?php echo $item['Id']?>)" class="btn btn-primary" value=<?php echo lang('approve') ?> >
                                <input type="button" id="refuse" onclick="refuse(<?php echo $item['Id']?>)"  class="btn btn-warning" value=<?php echo lang('refuse') ?> >
                            </td>
                        <?php }?>
                    <?php }?>

                    <!--通过-->
                    <?php if($item['status'] == 1){?>
                        <td><?php echo lang('has_been_completed') ?></td>
                        <td><?php echo $item['audit_id'] ?></td>
                        <td><?php echo $item['audit_time'] ?></td>
                    <?php }?>

                    <!--驳回-->
                    <?php if($item['status'] == 2){?>
                        <td><?php echo lang('refuse') ?></td>
                        <td><?php echo $item['audit_id'] ?></td>
                        <td><?php echo $item['audit_time'] ?></td>
                        <td><?php echo $item['refuse_reason'] ?></td>
                    <?php }?>
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


<script>
    $("#submit_sql").click(function(){
        var oldVal=$('#submit_sql').val();
        $('#submit_sql').attr("value", $('#loadingTxt').val());
        $('#submit_sql').attr("disabled", 'disabled');

        $.ajax({
            type: "POST",
            url: "/admin/execute_sql/submit_execute_sql",
            data: {sql:$("#sql_input").val(),remark:$("#remark_input").val()},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#submit_sql').attr("value", oldVal);
                    $('#submit_sql').attr("disabled", false);
                    $(".message").css('color','green');
                    $(".message").text(res.msg);
                    setTimeout(function(){
                        window.location.reload();
                    },3000);
                } else {
                    $('#submit_sql').attr("value", oldVal);
                    $('#submit_sql').attr("disabled", false);
                    $(".message").css('color','red');
                    $(".message").text(res.msg);
                }
            }
        });
    })

    function approve(id){
        layer.confirm("<span><?php echo lang('confirm_execute')?></span>", {
            icon: 3,
            title: "<?php echo lang('confirm'); ?>",
            closeBtn: 2,
            btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
        },function(){
            
            if($('.layui-layer-btn0').html() == $('#loadingTxt').val()){
                return false;
            }
            var oldVal = $('.layui-layer-btn0').html();
            $('.layui-layer-btn0').html($('#loadingTxt').val());
            $('.layui-layer-btn0').css("background","#858C8F");

            $.ajax({
                type: "POST",
                url: "/admin/execute_sql/do_execute_sql",
                data: {id:id},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg);
                        setTimeout(function(){
                            window.location.reload();
                        },3000)
                    }else{
                        layer.msg(res.msg);
                    }
                }
            });
        });
    }

    function refuse(id){
        layer.confirm("<textarea id='refuse_reason' placeholder='<?php echo lang('pls_input_reson');?>'></textarea>", {
            icon: 3,
            title: "<?php echo lang('refuse'); ?>",
            closeBtn: 2,
            btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
        },function(){
            var remark = $('#refuse_reason').val();
            $.ajax({
                type: "POST",
                url: "/admin/execute_sql/cancel_execute_sql",
                data: {id:id,remark:remark},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        window.location.reload();
                        layer.msg(res.msg);
                    }else{
                        layer.msg(res.msg);
                    }
                }
            });
        });
    }
</script>