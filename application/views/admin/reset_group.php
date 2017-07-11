<form id="from_reset_choose_group">
    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
    <table class="reset_choose_group">
        <tr>
            <td><input type="text" name='order_id' placeholder="<?php echo lang('order_id') ?>" id="order_id"></td>
        </tr>
        <tr>
            <td>
                <select id="select_type">
                    <option value="1"><?php echo lang('reset_choose_group')?></option>
                    <option value="2"><?php echo lang('reset_upgrade_group')?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <input type="button" autocomplete="false" id="btn_check_order_type" onclick="check_order_type()" class="btn btn-info" value="<?php echo lang('check_order_type');?>">
                <input type="button" autocomplete="false" id="btn_reset_choose_group" onclick="reset_choose_group()" class="btn btn-primary" value="<?php echo lang('submit');?>" style="margin-left: 50px;">
            </td>
            <td><span class="reset_choose_group_msg"></span></td>
        </tr>
    </table>
</form>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="user_id" value="<?php echo $searchData['user_id'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('id')?>">
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
            <th><?php echo lang('user_id'); ?></th>
            <th><?php echo lang('order_id'); ?></th>
            <th><?php echo lang('reset_type'); ?></th>
            <th><?php echo lang('role_super'); ?></th>
            <th><?php echo lang('create_time'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['user_id'] ?></td>
                    <td><?php echo $item['order_id'] ?></td>
                    <td>
                        <?php if($item['reset_type']==1) {
                            echo lang('reset_choose_group');
                        }?>
                        <?php if($item['reset_type']==2) {
                            echo lang('reset_upgrade_group');
                        }?>
                    </td>
                    <td><?php echo $item['admin_id'] ?></td>
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
<?php echo $pager;?>

<script>
    function reset_choose_group(){
        var oldVal=$('#btn_reset_choose_group').val();
        $('#btn_reset_choose_group').attr("value", $('#loadingTxt').val());
        $('#btn_reset_choose_group').attr("disabled", 'disabled');
        $.ajax({
            type: "POST",
            url: "reset_group/check_submit",
            data: {order_id:$('#order_id').val(),type:$('#select_type').val()},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#btn_reset_choose_group').attr("value", oldVal);
                    $('#btn_reset_choose_group').attr("disabled", false);
                    $('.reset_choose_group_msg').css('color','green');
                    $('.reset_choose_group_msg').text(res.msg);
                    setTimeout(function(){
                        $('.reset_choose_group_msg').text('');
                        window.location.reload();
                    },3000);
                    
                }else{
                    $('#btn_reset_choose_group').attr("value", oldVal);
                    $('#btn_reset_choose_group').attr("disabled", false);
                    $('.reset_choose_group_msg').css('color','#f00');
                    $('.reset_choose_group_msg').text(res.msg);
                    setTimeout(function(){
                        $('.reset_choose_group_msg').text('');
                    },5000);

                }
            }
        });
    }

    function check_order_type(){
        $.ajax({
            type: "POST",
            url: "reset_group/check_order_type",
            data: {order_id:$('#order_id').val(),confirm_order_id:$('#confirm_order_id').val()},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('.reset_choose_group_msg').css('color','green');
                    $('.reset_choose_group_msg').text(res.msg);
                }else{
                    $('.reset_choose_group_msg').css('color','#f00');
                    $('.reset_choose_group_msg').text(res.msg);
                    setTimeout(function(){
                        $('.reset_choose_group_msg').text('');
                    },5000);
                }
            }
        });
    }
</script>