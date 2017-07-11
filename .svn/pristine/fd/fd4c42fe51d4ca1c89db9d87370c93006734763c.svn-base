<?php
/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2017/5/22
 * Time: 17:37
 */
?>

<form id="do_delete">
    <table class="reset_user_pwd">
        <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
        <tr>
            <td><input type="text" name='account' placeholder="<?php echo lang('login_name'); ?>" id="account" autocomplete="off"></td>
        </tr>
        <tr>
            <td><input type="text" name='account_confirm' placeholder="<?php echo lang('login_name'); ?>" autocomplete="off" id="account_confirm"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" autocomplete="false" id="unfrost" class="btn btn-primary" value="<?php echo lang('submit');?>">
                <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
                <input type="hidden" id="sub" value="<?php echo lang('submit'); ?>">
            </td>
        </tr>
    </table>
</form>

<script>
    $(function(){
        $("#unfrost").click(function(){
            var account = $.trim($("#account").val());
            var account_confirm = $.trim($("#account_confirm").val());
            if (account.length == 0) {
                layer.msg("<?php echo lang("please_input_unfrost_account")?>");
                return ;
            }

            if (account_confirm.length == 0) {
                layer.msg("<?php echo lang('please_input_unfrost_account_again');?>");
                return ;
            }

            if (account !== account_confirm) {
                layer.msg("<?php echo lang("input_unfrost_not_same");?>");
                return ;
            }

            $("#unfrost").val($("#loadingTxt").val());
            $("#unfrost").attr("disabled",'disabled');
            var data = {};
            data.account = account;
            data.account_confirm = account_confirm;
            $.ajax({
                data:data,
                url:"unfrost_submit",
                type:"post",
                dataType:'json',
                success:function(res){
                        if (res.error == true) {
                            layer.msg(res.msg);
                        } else {
                            layer.msg(res.msg);
                            setTimeout("location.reload();",1500);

                        }

                        $("#unfrost").val($("#sub").val());
                        $("#unfrost").attr("disabled",false);
                }

            })
        })
    })
</script>
