<form id="reset_user_pwd">
    <table class="reset_user_pwd">
        <tr>
            <td><input type="text" name='user_id' placeholder="<?php echo lang('user_id') ?>" id="user_id"></td>
        </tr>
        <tr>
            <td><input type="text" name='confirm_user_id' placeholder="<?php echo lang('confirm_user_id') ?>" id="confirm_user_id"></td>
        </tr>
        <tr>
            <td><span style="color: #ff0000" class="reset_user_pwd_msg"></span></td>
        </tr>
        <tr>
            <td>
                <input type="button" autocomplete="false" id="btn_reset_user_pwd" class="btn btn-primary" value="<?php echo lang('submit');?>">
            </td>
            <td><span style="color: #009900" class="reset_user_pwd_ok"></span></td>
        </tr>
    </table>
</form>