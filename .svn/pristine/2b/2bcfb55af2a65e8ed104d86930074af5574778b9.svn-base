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
                    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
                    <div class="top_correct hidden" id="correct_id">
                        <span style="float:left;position:relative;top:6px;left:10px;"><img src="<?php echo base_url('img/new/correct1.jpg') ?>"></span>
                        <span id="correct_msg" style="text-align:left;float:left; position:relative;left:30px;top:7px;line-height:15px;"></span>
                    </div>
                    <div class="top_wrong hidden" id="wrong_id">
                        <span style="float:left;position:relative;top:6px;left:15px;"><img src="<?php echo base_url('img/new/wrong1.jpg') ?>"></span>
                        <span id="wrong_msg" style="text-align:left;float:left; position:relative;left:20px;top:7px;line-height:15px;"></span>
                    </div>
                    <div class="forgottext">
                        <h2><?php echo lang('Active_account'); ?></h2>
                        <p><?php echo lang('pls_enter_your_mail_below'); ?></p>
                        <p><?php echo lang('receive_enable_link'); ?></p>
                    </div>
                    <div class="emailcss"><input type="text" id="email" name="email" class="i_css" notice="<?php echo lang('regi_email'); ?>" value="<?php echo lang('regi_email'); ?>"></div>
                    <div class="bottsub">
                        <input id="get_password_sub" autocomplete="off" type="button" name="submit" class="s_css1" value="<?php echo lang('submit'); ?>">
                        <span><a href="<?php echo base_url('login') ?>"><?php echo lang('back_to_login') ?></a></span>
                        <img id="loading" class="hidden" src="<?php echo base_url('img/new/loading.jpg'); ?>" >
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#email').focus(function () {
            if ($(this).val() === $(this).attr('notice')) {
                $(this).val('');
            }
        });
        $("#email").blur(function () {
            var curElement = $(this);
            if (!curElement.val()) {
                curElement.val(curElement.attr('notice'));
            }
        });
        $("input[name='submit']").click(function () {

            var curEle = $(this);
            var oldSubVal = curEle.val();
            curEle.attr("value", $('#loadingTxt').val());
            curEle.attr("disabled","disabled");
            var oldColor = curEle.css('background-color');
            curEle.css('background-color','#cccccc');
            $('#loading').removeClass('hidden');
            email = $('#email').val();
            $.ajax({
                type: "POST",
                url: "/inactive/sendEnableMail",
                data: {email: email},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#correct_id').removeClass('hidden');
                        $('#wrong_id').addClass('hidden');
                        $('#correct_msg').html(data.msg);
                        $('#loading').addClass('hidden');
                        $('#email').val('');
                        setTimeout(function(){
                            window.location.href = '/login';
                        },3000);
                    } else {
                        $('#wrong_id').removeClass('hidden');
                        $('#correct_id').addClass('hidden');
                        $('#wrong_msg').html(data.msg);
                        $('#loading').addClass('hidden');
                    }
                    curEle.attr("value", oldSubVal);
                    curEle.attr("disabled", false);
                    curEle.css("background-color", oldColor);
                }
            });
        });
    });
</script>