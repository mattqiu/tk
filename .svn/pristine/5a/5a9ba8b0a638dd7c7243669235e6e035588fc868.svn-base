<?php if($is_verified_email == 0){?>
    <script>
        $(function(){
            $('#binding_info_email').modal();
        });
    </script>
<?php }?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">
<div class="well">
<div class="alert alert-success hidden">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong><?php echo lang('send_email_success');?></php></strong>
</div>
<form class="form-horizontal" id="change_email" method="post">
        <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
        <label for="new_email"><?php echo lang('email')?></label>
    <div class="inline">
        <input class="input-xlarge pull-left" type="text" value="<?php echo $email_encrypt;?>" placeholder="<?php echo lang('email')?>" disabled>
    </div>
    <div class="msg inline">
        <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
        <span class="txt" id="pwd_span"></span>
    </div>
    <div style="clear: both;"></div>

    <label for="pwdOld" style="margin-top: 20px;"><?php echo lang('tps_login_password')?></label>
    <div class="inline">
        <input class="input-xlarge pull-left" type="password" id="pwd" name="pwdOld" placeholder="<?php echo lang('tps_login_password')?>" >
    </div>
    <div class="msg inline">
        <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
        <span class="txt"></span>
    </div>
    <div style="clear: both;"></div>

        <div style="margin-top:20px; ">
        <button type="button" name="submit" class="btn btn-primary" <?php if($is_verified_email == 0){echo "disabled='disabled'";}?>><?php echo lang('submit')?></button>
        </div>
    <div class="text-success" style="margin-top: 20px;">
        <strong><?php echo $reset_email_tip;?></php></strong>
    </div>
</form>
</div>
<style>
    .main .msg {
        width: 100%;
    }
</style>
<script>
    function submit_Count_down(timerc) {
        if (timerc > 1) {
            --timerc;
            $("button[name='submit']").text($('#resend_email').val()+ timerc + 's');
            setTimeout("submit_Count_down("+timerc+")", 1000);
        }else {
            $("button[name='submit']").text('<?php echo lang('submit')?>');
            $("button[name='submit']").attr('disabled',false);
            $("button[name='submit']").css('background','#113355');
        }
    }
    $(document).ready(function () {

        function displayFormItemCheckRes(itemName, checkResultVal) {
            curElement = $("input[name='" + itemName + "']");
            if (checkResultVal.isRight === true) {
                curElement.css("border", "");
                curElement.parent().next().children('img').attr("class", "right_icon");
                curElement.parent().next().children('span').html(checkResultVal.msg).removeClass('red');
            } else {
                curElement.css("border", "1px red solid");
                curElement.parent().next().children('img').attr("class", "error_icon");
                curElement.parent().next().children('span').html(checkResultVal.msg).addClass('red');

            }
        }
        
        $("button[name='submit']").click(function () {
            var curEle = $(this);
            var oldSubVal = curEle.text();
            curEle.html($('#loadingTxt').val());
            curEle.attr("disabled","disabled");
            $.ajax({
                type: "POST",
                url: "/ucenter/change_email/changeEmail",
                data: $('#change_email').serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('.alert').removeClass('hidden');
                        $('#pwd').val('');
                        curElement = $("input[name='pwdOld']");
                        curElement.css("border", "");
                        curElement.parent().next().children('img').attr("class", "");
                        curElement.parent().next().children('span').html('').removeClass('red');
                        curEle.attr("disabled","disabled");
                        curEle.css('background','#cccccc');
                        submit_Count_down(60);
                    } else {
                        $.each(data.checkResult, function (itemName, checkResultVal) {
                            displayFormItemCheckRes(itemName, checkResultVal);
                        });
                        curEle.html(oldSubVal);
                        curEle.attr("disabled",false);
                    }
                  //  curEle.html(oldSubVal);
                  //  curEle.attr("disabled",false);
                }
            });
            
        });

    });
</script>

<!-- 验证邮箱弹层 -->
<div id="binding_info_email" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo lang('binding_email')?></h3>
    </div>
    <div class="modal-body">
        <form action="" method="post" class="form-horizontal" id="binding_info_email_form">
            <table class="enable_level_tb">
                <tr>
                    <td class=""><?php echo lang('email_text'); ?>:</td>
                    <td class="main">
                        <input type="text" value="<?php echo $email?$email:'';?>" autocomplete="off" name="email" placeholder="<?php echo lang('email');?>" >
                    </td>
                </tr>
                <tr>
                    <td class=""><?php echo lang('regi_emails'); ?>:</td>
                    <td class="main">
                        <input type="text" value="<?php echo $email?$email:'';?>" autocomplete="off" name="regi_emails" placeholder="<?php echo lang('email');?>" >
                    </td>
                </tr>
                <tr>
                    <td class=""><?php echo lang('captcha'); ?>:</td>
                    <td class="main">
                        <input type="text" value="" name="captcha" placeholder="<?php echo lang('captcha');?>" >
                    </td>
                </tr><tr>
                    <td class=""></td>
                    <td class="main">
                        <input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha " value="<?php echo lang('get_captcha')?>">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" id="take_out_pwd_msg" class="msg"></td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo lang('resend_captcha')?>" id="resend_captcha">
            <input type="hidden" value="<?php echo lang('resend_email')?>" id="resend_email">
            <input type="hidden" value="<?php echo lang('get_captcha')?>" id="get_captcha">
        </form>
    </div>
    <div class="modal-footer">
        <button autocomplete="off" class="btn btn-primary" id="binding_info_email_btn"><?php echo lang('submit'); ?></button>
    </div>
</div>

<script>
    $('#binding_info_email_btn').click(function(){
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/binding_user_info",
            data:$('#binding_info_email_form').serialize(),
            dataType: "json",
            success: function (res) {
                if(res.success){
                    location.reload();
                }else{
                    layer.msg(res.msg);
                }
            }
        });
        curEle.html(oldSubVal);
        curEle.attr("disabled", false);
    });


    function add(timerc) {
        if (timerc > 1) {
            --timerc;
            $(".get_captcha").val($('#resend_captcha').val()+ timerc + 's');
            t=setTimeout("add("+timerc+")", 1000);
        } else {
            $(".get_captcha").val($('#get_captcha').val());
            $(".get_captcha").attr('disabled',false);
            $(".get_captcha").css('background','#f7f7f7');
        }
    }
    $('.get_captcha').click(function(){
        var email_or_phone = $('input[name="email"]').val();
        var regi_emails_or_phone = $('input[name="regi_emails"]').val();
        if(email_or_phone !== regi_emails_or_phone){
            layer.msg("<?php echo lang("regi_email2");?>");
            return false;
        }
        var reg_type = $('input[name="reg_type"]').val();
        $(".get_captcha").attr('disabled',true);
                    
                    
        $.ajax({
            type: "POST",
            url: "/register/bindEmail",
            data: {email_or_phone:email_or_phone,regi_emails_or_phone:regi_emails_or_phone,reg_type:reg_type,action_id:4},
            dataType: "json",
            success: function (data) {
                if(data.success){
                    $(".get_captcha").css('background','#cccccc');
                    add(60);
                }else{
                    $(".get_captcha").attr('disabled',false);
                    layer.msg(data.msg);
                }
            }
        });
       
    });
</script>
