<style>
    .hidden{
        display: none;
    }
</style>
<div class="login_main">
    <div id="loginwrap1">
        <div class="login_bg">
            <div class="maintext">
                <form name="forgetpwd_form" action="">
                    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
                    <div class="top_correct hidden" id="correct_id">
                        <span style="float:left;position:relative;top:8px;left:10px;"><img src="<?php echo base_url('img/new/correct1.jpg') ?>"></span>
                        <span id="correct_msg" style="text-align:left;float:left; position:relative;left:15px;top:10px;line-height:15px;"></span>
                        <!--<span style="position:relative;top:3px;color:#426ba7;"><a href="<?php echo base_url('login') ?>"><?php echo lang('go_login'); ?></a></span>-->
                    </div>
                    <div class="top_wrong hidden" id="wrong_id" style="width:350px">
                        <span style="float:left;position:relative;top:8px;left:10px;"><img src="<?php echo base_url('img/new/wrong1.jpg') ?>"></span>
                        <span id="wrong_msg" style="text-align:left;float:left; position:relative;left:15px;top:10px;line-height:15px;"></span>
                    </div>
                    <div class="forgottext" style="height:80px;">
                        <h2><?php echo lang('pwd_reset'); ?></h2>
                    </div>
                    <div class="emailcss">
                        <input type="text" name="pwdOriginalText" notice="<?php echo lang('pwd_new') ?>" class="i_css" value="<?php echo lang('pwd_new') ?>">
                        <input type="password" id="newPwd" name="pwdOriginal" notice="<?php echo lang('pwd_new') ?>" class="i_css hidden" value="">
                    </div>
                    <div class="emailcss" style="margin-top:30px;">
                        <input type="text" name="pwdOriginal_reText" class="i_css" notice="<?php echo lang('regi_pwd_re') ?>" value="<?php echo lang('regi_pwd_re') ?>">
                        <input type="password" id="newPwdRe" name="pwdOriginal_re" notice="<?php echo lang('regi_pwd_re') ?>" class="i_css hidden" value="">
                    </div>
                    <div class="bottsub"><input id="get_password_sub" autocomplete="off" type="button" name="submit" class="s_css1" value="<?php echo lang('submit') ?>"></div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#loginwrap1 input').focus(function () {
            if ($(this).val() === $(this).attr('notice')) {
                $(this).val('');
            }
            if ($(this).attr('name') === 'pwdOriginalText' || $(this).attr('name') === 'pwdOriginal_reText') {
                $(this).hide();
                $(this).next().show().focus();
            }
        });
        $("#loginwrap1 input,select").blur(function () {
            var curElement = $(this);
            if (!curElement.val()) {
                if ($(this).attr('name') === 'pwdOriginal' || $(this).attr('name') === 'pwdOriginal_re') {
                    $(this).hide();
                    $(this).prev().show();
                }
                curElement.val(curElement.attr('notice'));
            }
        });
        $("input[name='submit']").click(function () {
            var oldSubVal = $('#get_password_sub').val();
            $('#get_password_sub').val($('#loadingTxt').val());
            $('#get_password_sub').attr("disabled", "disabled");
            newPwd = $('#newPwd').val();
            newPwdRe = $('#newPwdRe').val();
            $.ajax({
                type: "POST",
                url: window.location.href + "&ajax=resetPwd",
                data: {newPwd: newPwd, newPwdRe: newPwdRe},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#correct_id').removeClass('hidden');
                        $('#wrong_id').addClass('hidden');
                        $('#correct_msg').html(data.msg);
                        $('#get_password_sub').val(oldSubVal);
                        $('#newPwdRe').val('');
                        $('#newPwd').val('');
                    } else {
                        $('#wrong_id').removeClass('hidden');
                        $('#correct_id').addClass('hidden');
                        $('#wrong_msg').html(data.msg);
                        $('#get_password_sub').val(oldSubVal);
                        $('#get_password_sub').attr("disabled", false);
                    }
                }
            });
        });

    });
</script>