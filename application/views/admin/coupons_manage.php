<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
<div class="block ">
    <form id='commChangeForm'>
        <p class="block-heading"><?php echo lang('coupons_add_or_reduce') ?></p>
        <div class="block-body">
            <div class="row-fluid">
                <?php echo lang('user_id');?>: <input style="width:100px" type="text" name="user_id" value="">
                <select name="operation" style="width:60px">
                    <option value="1">＋</option>
                    <option value="2">－</option>
                </select>
                <input type="text" name="voucher" value="" style="width:70px">
                <?php echo lang('voucher')?>
            </div>
            <div>
                <?php echo lang('why')?>: <textarea rows="3" cols="20" name="remark"></textarea>
            </div>
            <div class="row-fluid">
                <input id='btn_voucher_manage' onclick="voucher_manage_submit()" autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
                <span class='msg' id='commChangeMsg'></span>
            </div>
        </div>
    </form>
</div>
<br>
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
            <th><?php echo lang('voucher_value'); ?></th>
            <th><?php echo lang('role_super'); ?></th>
            <th><?php echo lang('remark'); ?></th>
            <th><?php echo lang('create_time'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['user_id'] ?></td>
                    <td><?php echo $item['voucher_value'] ?></td>
                    <td><?php echo $item['admin_id'] ?></td>
                    <td><?php echo $item['reason'] ?></td>
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
    function voucher_manage_submit(){
        var oldVal=$('#btn_voucher_manage').val();
        $('#btn_voucher_manage').attr("value", $('#loadingTxt').val());
        $('#btn_voucher_manage').attr("disabled", 'disabled');
        $.ajax({
            type: "POST",
            url: "coupons_manage/submit",
            data: $('#commChangeForm').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#btn_voucher_manage').attr("value", oldVal);
                    $('#btn_voucher_manage').attr("disabled", false);
                    $('#commChangeMsg').css('color','green');
                    $('#commChangeMsg').text(res.msg);
                    setTimeout(function(){
                        $('#commChangeMsg').text('');
                        window.location.reload();
                    },2000);
                }else{
                    $('#btn_voucher_manage').attr("value", oldVal);
                    $('#btn_voucher_manage').attr("disabled", false);
                    $('#commChangeMsg').css('color','#f00');
                    $('#commChangeMsg').text(res.msg);
                    setTimeout(function(){
                        $('#commChangeMsg').text('');
                    },5000);
                }
            }
        });
    }
</script>