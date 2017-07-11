<form  id="do_action">
    <table class="reset_user_pwd">
		<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
        <tr>
            <td><input type="text" name='user_id' placeholder="<?php echo lang('user_id') ?>" id="user_id" autocomplete="off"></td>
        </tr>
        <tr>
            <td><input type="text" name='confirm_user_id' placeholder="<?php echo lang('confirm_user_id') ?>" autocomplete="off" id="confirm_user_id"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" autocomplete="false" id="action_users" class="btn btn-primary" value="<?php echo lang('submit');?>">
                <span id="action_users_msg" ></span>
            </td>
        </tr>
    </table>
</form>
<h2><?php echo lang('delPlan_title');?></h2>
<form  id="delPlan">
    <table class="reset_user_pwd">
        <input type="hidden" id="loadingTxt1" value="<?php echo lang('processing');?>">
        <tr>
            <td><input type="text" name='user_id' placeholder="<?php echo lang('user_id') ?>" id="user_id2" autocomplete="off"></td>
        </tr>
        <tr>
            <td><input type="text" name='confirm_user_id' placeholder="<?php echo lang('confirm_user_id') ?>" autocomplete="off" id="confirm_user_id2"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" autocomplete="false" id="action_users2" class="btn btn-primary" value="<?php echo lang('label_goods_delete');?>">
                <span id="action_users_msg2" ></span>
            </td>
        </tr>
    </table>
</form>
<script>
    $("#action_users2").click(function(){
        var curEle = $(this);
        var oldSubVal = curEle.val();
        $(this).attr("value", $('#loadingTxt1').val());
//        $(this).attr("disabled","disabled");
        $.ajax({
            type: "POST",
            url: "/admin/join_plan/delPlan",
            data: $('#delPlan').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#action_users_msg2').text(res.msg).addClass('text-success');
                    $('#user_id2,#confirm_user_id2').val('');
                    setTimeout(function(){
                        location.reload();
                    },2000);
                } else {
                    $('#action_users_msg2').text(res.msg).addClass('text-error');
                    curEle.attr("value", oldSubVal);
                    curEle.attr("disabled", false);
                }
            }
        });
    });
</script>