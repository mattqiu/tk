<style type="text/css">
.search-well{ padding-top:40px;}
</style>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<input class="user_id" type="hidden"  name="user_id" id="hiddenMaxCash" value="" >
<table>
    <tr>
        <td>
            <span><?php echo lang('user_id') ?></span>
        </td>
        <td>
            <span><?php echo lang('month_fee') ?></span>
            <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
        </td>
        <td></td>
        <td><?php echo lang('amount_') ?></td>
    </tr>
    <tr>
        <form method="post"  id="month_fee_to_amount">
            <td><input class="user_id" type="text"  name="user_id" id="userId" value="" ></td>
            <td><input class="proportion_input" type="text"  name="month_fee_pool" id="monthFeePool" value=""></td>
            <td><span><?php echo lang('to') ?></span></td>
            <td><input class="proportion_input" type="text"  name="amount_pool" id="amount_pool" value=""  readonly="true" ></td>
            <td><input type="button" id="submit_id" class="btn btn-primary" value=<?php echo lang('confirm') ?> ></td>
            <td>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
            </td>
        </form>
    </tr>
</table>

<div class="search-well">
    <form class="form-inline" method="GET">
    	<input type="text" class="span2" placeholder="<?php echo lang('id') ?>" name="user_id" value="<?php echo $searchData['user_id'];?>" />
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
       
    </form>

</div>
<input type="hidden" id="url" value="<?php echo base_url('admin/email/eamil_info'); ?>" />
<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('user_id') ?></th>
                <th><?php echo lang('role_super') ?></th>
                <th><?php echo lang('old_month_fee_pool') ?></th>
                <th><?php echo lang('new_month_fee_pool') ?></th>
                <th><?php echo lang('cash') ?></th>
                <th><?php echo lang('admin_order_info_create_time') ?></th>
            </tr>
        </thead>
        <tbody class="tbody">
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['user_id'] ?></td>
                    <td><?php echo $item['admin_id'] ?></td>
                    <td><?php echo $item['old_month_fee_pool'] ?></td>
                    <td><?php echo $item['month_fee_pool'] ?></td>
                    <td><?php echo $item['cash'] ?></td>
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
<?php echo $pager; ?>



<script>
    $(function() {
        $(".user_id").blur(function () {
            var user_id = $(this).val();
            $('#month_fee_to_amount input').val(user_id);
            $.ajax({
                success: "success",
                url: "/admin/month_fee_to_amount/checkUserId",
                dataType: "json",
                type: "post",
                data: {user_id: user_id},
                success: function (res) {
                    if (res.success) {
                        $('#maxLimit').text(res.msg);
                        $('#amount_pool').val(res.amount)
                        $('#error_msg').text("");
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#maxLimit').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });
        });

        $("#submit_id").click(function(){
            var user_id = $('#userId').val();
            var month_fee_pool=$('#monthFeePool').val();
            $.ajax({
                success: "success",
                url: "/admin/month_fee_to_amount/checkData",
                dataType: "json",
                type: "post",
                data: {user_id: user_id,month_fee_pool:month_fee_pool},
                success: function (res) {
                    if (res.success) {
                        $('#submit_success').text(res.msg);
                        $('#error_msg').text("");
                        setTimeout("$('#submit_success').text('')", 3000);
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#submit_success').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        })
    })
</script>
