
<div class="search-well">
<form method="post" id="month_fee_to_amount_1"  name="month_fee_to_amount_1" enctype="multipart/form-data">
    <table>
        <tr>
            <td>
                <span><?php echo lang('user_id') ?></span>
                <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
            </td>
        </tr>
        <tr>            
            <td><input type="text"  name="uid_txt" id="uid_txt" value=""></td>
            <td>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
            </td>
               
        </tr>
        <tr>
            <td>
                <select name="com_type" id="com_type" class="com_type input-medium" style="width:220px;">
                    <option value=""><?php echo lang('type'); ?></option>
                            <?php foreach ($commission_type as $key => $value) { ?>
                             <option value="<?php echo $key ?>"<?php if ($key == $curComType) {} ?>>
                                <?php echo lang($value); ?>
                            </option>
                            
                			<?php } ?>
                </select>
                
            </td>
        </tr>
        <tr>

            <td>
                <span><?php echo lang('user_account_total') ?></span>
                <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
            </td>

        </tr>
        <tr>
                <td><input  type="text"  name="uid_txt1"  readonly="true" id="user_account_total" value=""></td>
                <td>
                    <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
                </td>
        </tr>
        <tr>

            <td>
                <span><?php echo lang('user_amount_total') ?></span>
                <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
            </td>

        </tr>
        <tr>

                <td><input  type="text" name="uid_txt2" id="user_amount_total" readonly="true" value=""></td>
                <td>
                    <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                    <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
                </td>
        </tr>

        <tr>

                <td>
                <input type="button" id="submit_id_1" class="btn btn-primary" value=<?php echo lang('find') ?> >
                
                <!-- 修复权限开给管理员和客服经理-->
                <?php if(in_array($adminInfo['role'],array(0,2))){?>
                    <input type="button" id="submit_id" style="margin-left: 20px;" class="btn btn-primary" value=<?php echo lang('repair_amount') ?> >
                <?php }?>
               <input type="button" id="to_export" name="to_export" style="margin-left: 20px;" class="btn btn-primary" value="<?php echo lang('export_EXCEL'); ?>" >
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
                </td>

        </tr>
    </table>
     </form>
</div>

<?php if (isset($err_msg)): ?>
    <div class="well">
        <p style="color: red;"><?php echo $err_msg; ?></p>
    </div>
<?php endif; ?>
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

//    $(function() {
//        'use strict';
//
//        var page_data = <?php //echo json_encode($page_data); ?>//;
//        $('#status').children('[value=' + page_data.status + ']').prop('selected', true);
//        $('#storehouse').children('[value=' + page_data.storehouse + ']').prop('selected', true);
//        $('#order_type').children('[value=' + page_data.order_type + ']').prop('selected', true);
//        if (page_data.uid != null) {
//            $('#uid').val(page_data.uid);
//        }
//    });

</script>
<script>


    $(function() {
        //查询
        $("#submit_id_1").click(function(){
            var uid_txt=$('#uid_txt').val();
            $.ajax({
                success: "success",
                url: "/admin/repair_users_amount/checkData_query",
                dataType: "json",
                type: "post",
                data: {uid_txt:uid_txt},
                success: function (res) {
                    if (res.success) {
                        $('#error_msg').text("");
                        $('#user_account_total').val(res.sun_amount);
                        $('#user_amount_total').val(res.amount);
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#submit_success').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        })

        //鼠标离开验证订单号
        $(".uid_txt").blur(function () {
            var uid_txt = $(this).val();
            $('#month_fee_to_amount input').val(uid_txt);
            $.ajax({
                success: "success",
                url: "/admin/repair_users_amount/checkOrderId",
                dataType: "json",
                type: "post",
                data: {uid_txt: uid_txt},
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


        //提交
        $("#submit_id").click(function(){
            var uid_txt=$('#uid_txt').val();
            $.ajax({
                success: "success",
                url: "/admin/repair_users_amount/checkData",
                dataType: "json",
                type: "post",
                data: {uid_txt:uid_txt},
                success: function (res) {
                    if (res.success) {
                        $('#submit_success').text(res.msg);
                        $('#error_msg').text("");
                        $('#user_account_total').val(res.sun_amount);
                        $('#user_amount_total').val(res.amount);
                        setTimeout("$('#submit_success').text('')", 3000);
                        location.reload('');
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#submit_success').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        })

		//导出excel
        $("#to_export").click(function(){    
        	var uid_txt=$('#uid_txt').val();        	
			if(uid_txt.trim().length==0)
			{				
				$('#error_msg').text("<?php echo lang('uid_not_null'); ?>");				
				return;
			}		
        	month_fee_to_amount_1.action = "/admin/repair_users_amount/export";
        	month_fee_to_amount_1.submit();        	
        })
        
    })
</script>