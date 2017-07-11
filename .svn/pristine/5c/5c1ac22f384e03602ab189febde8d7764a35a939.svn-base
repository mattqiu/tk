<div class="search-well">
    <table>
        <tr>
            <td>
                <span><?php echo lang('user_id') ?></span>
            </td>
            <td>
                <span><?php echo lang('commission_number') ?></span>
                <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
            </td>

        </tr>
        <form method="post"  id="month_fee_to_amount">
            <td><input class="user_id" type="text"  name="user_id" id="user_id" placeholder="<?php echo lang('user_id') ?>" value="" ></td>
            <td><input class="commission_number" type="text"  name="commission_number" id="commission_number" value="0"></td>
            <td><input type="button" id="submit_id" class="btn btn-primary" value=<?php echo lang('commission_isok') ?> ></td>
            <td>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
            </td>
        </form>
        </tr>

    </table>

</div> 

<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>



<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

function errboxHtml(msg) {
	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
}

//添加138佣金记录
//提交
$(function() {
    $("#submit_id").click(function(){
        var user_id = $('#user_id').val();
        var commission_number=$('#commission_number').val();
        $.ajax({
            success: "success",
            url: "/admin/user_qualified/addQualified",
            dataType: "json",
            type: "post",
            data: {user_id: user_id,commission_number:commission_number},
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
});
</script>