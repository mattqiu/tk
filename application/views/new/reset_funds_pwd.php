<style>
    .hidden{
        display: none;
    }
</style>
<div class="login_main">
    <div id="loginwrap1">
        <div class="login_bg" style="width:540px;" >
            <?php if (!$disabled_status){?>
            <div class="maintext" style="width:500px;">
                <form name="forgetpwd_form" action="">
                    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
                    <div class="top_correct hidden" id="correct_id">
                        <span style="float:left;position:relative;top:8px;left:10px;"><img src="<?php echo base_url('img/new/correct1.jpg') ?>"></span>
                        <span id="correct_msg" style="text-align:left;float:left; position:relative;left:15px;top:10px;line-height:15px;"></span>
                        <!--<span style="position:relative;top:3px;color:#426ba7;"><a href="<?php echo base_url('login') ?>"><?php echo lang('go_login'); ?></a></span>-->
                    </div>
                    <div class="top_wrong hidden" id="wrong_id">
                        <span style="float:left;position:relative;top:8px;left:10px;"><img src="<?php echo base_url('img/new/wrong1.jpg') ?>"></span>
                        <span id="wrong_msg" style="text-align:left;float:left; position:relative;left:15px;top:10px;line-height:15px;"></span>
                    </div>
                    <div class="forgottext" style="height:80px;margin-left: 15px;">
                        <h2><?php echo lang('funds_pwd_reset'); ?></h2>
                    </div>
                    <div class="reset_fund_pwd">
                        <input type="password" style="width:460px; font-size:12px;" size="30" maxlength="16" name="funds_pwd_new" id="funds_pwd_new" placeholder="<?php echo lang('funds_pwd_new').lang('take_out_pwd2') ?>" class="i_css" value="" autocomplete="off">
                    </div>
                    <div class="reset_fund_pwd" style="margin-top:30px;">
                        <input type="password" style="width:460px; font-size:12px;" size="30" maxlength="16" name="funds_pwd_new_re" id="funds_pwd_new_re" class="i_css" placeholder="<?php echo lang('funds_pwd_new_re').lang('take_out_pwd2') ?>" value="" autocomplete="off">
                    </div>
                    <div class="reset_fund_pwd" style="margin-top:30px;">
                        <input type="password" style="width:460px; font-size:12px;" size="30"  name="pwdOld" id="pwdOld" class="i_css" placeholder="<?php echo lang('tps_login_password') ?>" value="" autocomplete="off">
                    </div>
                    <div class="bottsub" style="margin-left:15px;"><input id="get_password_sub" autocomplete="off" type="button" name="submit" class="s_css1" value="<?php echo lang('submit') ?>"></div>

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
            funds_pwd_new = $('#funds_pwd_new').val();
            funds_pwd_new_re = $('#funds_pwd_new_re').val();
            pwdOld = $('#pwdOld').val();
            $.ajax({
                type: "POST",
                url: window.location.href + "&ajax=resetFundsPwd",
                data: {funds_pwd_new: funds_pwd_new, funds_pwd_new_re: funds_pwd_new_re,pwdOld:pwdOld},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('#correct_id').removeClass('hidden');
                        $('#wrong_id').addClass('hidden');
                        $('#correct_msg').html(data.msg);
                        $('#funds_pwd_new,#funds_pwd_new_re,#pwdOld').val('');
                        $('#get_password_sub').val(oldSubVal);
                        setTimeout(function(){
                            location.href = '/ucenter'
                        },5000)
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