
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
                <span><?php echo lang('user_point_total') ?></span>
                <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
            </td>

        </tr>
        <tr>
                <td><input  type="text"  name="uid_txt1"  readonly="true" id="sys_point" value=""></td>
                <td>
                    <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
                </td>
        </tr>
        <tr>

            <td>
                <span><?php echo lang('user_cur_point_total') ?></span>
                <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
            </td>

        </tr>
        <tr>

                <td><input  type="text" name="uid_txt2" id="user_point" readonly="true" value=""></td>
                <td>
                    <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                    <span  id="submit_success" style="color: #009900; font-size: 12px;"></span>
                </td>
        </tr>

        <tr>

                <td>
                <input type="button" id="submit_id_1" class="btn btn-primary" value=<?php echo lang('find') ?> >
                
                <!-- 修复权限开给管理员和客服经理-->
                <?php /*if(in_array($adminInfo['role'],array(0,2))){?>
                    <input type="button" id="submit_id" style="margin-left: 20px;" class="btn btn-primary" value=<?php echo lang('repair_amount') ?> >
                <?php }*/?>
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
if (isset($paginate)) {
    echo $paginate;
}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

    function errboxHtml(msg) {
        return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
    }

</script>
<script>


    $(function() {
        //查询
        $("#submit_id_1").click(function(){
            var uid_txt=$('#uid_txt').val();
            $.ajax({
                success: "success",
                url: "/admin/repair_users_point/checkData_query",
                dataType: "json",
                type: "post",
                data: {"uid":uid_txt},
                success: function (res) {
                    if (res.success) {
                        $('#error_msg').text("");
                        $('#sys_point').val(res.sys_point);
                        $('#user_point').val(res.user_point);
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#submit_success').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        })


        //提交
        $("#submit_id").click(function(){
            var uid_txt=$('#uid_txt').val();
            $.ajax({
                success: "success",
                url: "/admin/repair_users_point/repair",
                dataType: "json",
                type: "post",
                data: {uid_txt:uid_txt},
                success: function (res) {
                    if (res.success) {
                        $('#submit_success').text(res.msg);
                        $('#error_msg').text("");
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
    })
</script>