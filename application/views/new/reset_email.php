<style>
    .hidden{
        display: none;
    }
</style>
<div class="login_main">
    <div id="loginwrap1">
        <div class="login_bg">
            <?php if (!$disabled_status){?>
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
                        <h2><?php echo lang('email_reset'); ?></h2>
                    </div>
                    <div class="emailcss">
                        <input type="text" name="email_new" id="email_new" placeholder="<?php echo lang('email_new') ?>" class="i_css" value="" autocomplete="off">
                    </div>
                    <div class="emailcss" style="margin-top:30px;">
                        <input type="text" name="email_new_re" id="email_new_re" class="i_css" placeholder="<?php echo lang('email_new_re') ?>" value="" autocomplete="off">
                    </div>
                    <div class="emailcss" style="margin-top:30px;">
                        <input type="password" name="pwdOld" id="pwdOld" class="i_css" placeholder="<?php echo lang('tps_login_password') ?>" value="" autocomplete="off">
                    </div>
                    <div class="bottsub"><input id="get_password_sub" autocomplete="off" type="button" name="submit" class="s_css1" value="<?php echo lang('submit') ?>"></div>
                    <div style="font-size: 14px;color: orangered;margin-top: 10px;"><?php echo lang('validate_new_email_tip');?></div>
                </form>
            </div>
            <?php }else{?>
                <div style="color: #b08c32;padding-top: 233px;"><?php echo $msg?></div>
            <?php }?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("input[name='submit']").click(function () {
            var oldSubVal = $('#get_password_sub').val();
            $('#get_password_sub').val($('#loadingTxt').val());
            $('#get_password_sub').attr("disabled", "disabled");
            email_new = $('#email_new').val();
            email_new_re = $('#email_new_re').val();
            pwdOld = $('#pwdOld').val();
            $.ajax({
                type: "POST",
                url: window.location.href + "&ajax=resetEmail",
                data: {email: email_new, email_new_re: email_new_re,pwdOld:pwdOld},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#correct_id').removeClass('hidden');
                        $('#wrong_id').addClass('hidden');
                        $('#correct_msg').html(data.msg);
                        $('#email_new,#email_new_re,#pwdOld').val('');
                        $('#get_password_sub').val(oldSubVal);
                        setTimeout(function(){
                            location.href = '/'
                        },3000)
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