<style type="text/css">
    td{border:0px solid #039;}
</style>
<div class="block upgrade">
    <p class="block-heading"><?php echo lang('current_commission') ?> : $<span id='curAmount'><?php echo $userInfo['amount'] ?></span></p>
    <div class="block-body">
        <form id="alipay_binding_form" class="form-inline" method="GET">
            <?php if (!$userInfo['paypal_email']) { ?>
                <input type="hidden" name="bd_type" value="4" />
            <?php } else { ?>
                <input type="hidden" name="bd_type" value="5" />
            <?php } ?>
            <table>
                <tr>
                    <td class="right_duiqi"><?php echo lang('paypal_email'); ?>：</td>
                    <td>
                        <input class="paypal_email" style="width:auto;width:175px;" autocomplete="off" type="text" <?php if ($userInfo['paypal_email']) { ?>readonly="readonly" value="<?php echo $userInfo['paypal_email']; ?>"<?php } ?> name="paypal_email"><span class="msg paypal_email"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                    </td>
                </tr>
                <?php if (!$userInfo['paypal_email']) { ?>
                    <tr>
                        <td class="right_duiqi"><?php echo lang('paypal_email_q'); ?>：</td>
                        <td>
                            <input class="paypal_email" style="width:auto;width:175px;" autocomplete="off" type="text" name="paypal_email_q"><span class="msg paypal_email_q"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="right_duiqi"><?php echo lang('alipay_binding_vcode'); ?>：</td>
                    <td>
                        <input style="width:auto;width:175px;" autocomplete="off" type="text" name="vcode">&nbsp;&nbsp;<input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha" value="<?php echo lang('get_captcha') ?>"> <a href="#" onclick="$('#popUserInfo').modal();" ><?php echo lang('where_code') ?></a>
                    </td>
                </tr>
                <tr>
                    <td class="right_duiqi"><?php echo lang('funds_pwd'); ?>：</td>
                    <td>
                        <input style="width:auto;width:175px;" autocomplete="off" type="password" name="password">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>&nbsp;</span>
                    </td>
                    <td>
                        <span style="color:red;" id="prompt"></span>
                    </td>
                </tr>
                <tr>

                    <td>

                    </td>
                    <td>
                        <input type="hidden" name="yinc" id="yinc" value="<?php
                        if ($userInfo['paypal_email']) {
                            echo '1';
                        }
                        ?>" />
                        <input type="hidden" name="type" value="<?php
                        if ($userInfo['paypal_email']) {
                            echo '1';
                        }
                        ?>" />
                        <input id="alipay_submit_confirm" autocomplete="off" name="submit" value="<?php echo lang('confirm'); ?>" class="btn btn-primary" type="button">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input id="alipay_submit_cancel" autocomplete="off" onclick="javascript:history.back(-1);" value="<?php echo lang('cancel'); ?>" class="btn btn-primary" type="button">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php if (!$userInfo['paypal_email']) { ?>
    <script>
        $('.paypal_email').blur(function () {
            var paypal_email = $.trim($('input[name="paypal_email"]').val());
            if (!paypal_email.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/))
            {
                $('.paypal_email').find('img').prop('class', 'error_icon');
            } else {
                $('.paypal_email').find('img').prop('class', 'right_icon');
            }
            var paypal_email_q = $.trim($('input[name="paypal_email_q"]').val());
            if(paypal_email_q){
                var paypal_email = $.trim($('input[name="paypal_email"]').val());
            if (paypal_email_q != paypal_email||!paypal_email.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/)) {
                $('.paypal_email_q').find('img').prop('class', 'error_icon');
            } else {
                $('.paypal_email_q').find('img').prop('class', 'right_icon');
            }
            }
            class_length();
        });
        function class_length() {
            if ($(".right_icon").length == 2) {
                $("#yinc").val('1')
            }
        }
    </script>
<?php } ?>
<div id="popUserInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="position:absolute;left:50%;top: 50%;width:400px;margin-left: -300px;">  
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3><?php echo lang('prompt_titlesa') ?></h3>  
    </div>  
    <div class="modal-body">
        <style>
            .word_break{
                color: #000000;
                display: block;
                font-weight: normal;
                word-break: break-all;
                word-wrap: break-word;
            }
            .right_duiqi{
                float: right;
            }
            .msg .error_icon{width: 19px;height: 19px; background: url("../../../img/register.png") no-repeat scroll -14px 0px transparent;vertical-align: middle;margin: 0px 5px 0px 5px;}
            .msg .right_icon{width: 19px;height: 19px; background: url("../../../img/register.png") no-repeat scroll -33px 0px transparent;vertical-align: middle;margin: 5px;}
        </style>
        <table class="enable_level_tb" style="border-collapse: separate;border-spacing: 5px;width: 350px">
            <tr>
                <td class="title"><?php echo lang('for_example'); ?>：</td>
                <td class="main" id="info_id"></td>
            </tr>
        </table>
        <table class="enable_level_tb" style="border-collapse: separate;border-spacing: 5px;width: 350px">
            <tr>
                <td><?php echo lang('prompt_2sa'); ?></td>
            </tr>
        </table>
        <button type="button" style="display:block;clear: both;margin: 0 auto;margin-top: 10px;" class="btn btn-default"
                data-dismiss="modal"><?php echo lang('confirm'); ?>
        </button>
    </div>
</div>
<!-- /用户详细信息弹层 -->
<script>
    $('#alipay_submit_confirm').click(function () {
        if (!$("#yinc").val()) {
            layer.msg('<?php echo lang('info_email'); ?>'); //返回信息
            return false;
        }
        $("input[name='submit']").val('<?php echo lang('processing'); ?>');
        $("input[name='submit']").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "/ucenter/paypal_binding/submit",
            data: $('#alipay_binding_form').serialize(),
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    layer.msg('<?php echo lang('result_ok'); ?>'); //返回信息
                    setTimeout("location = '/ucenter/take_out_cash'", 3000);
                } else {
                    layer.msg(data.msg); //返回失败信息
                }
                $("input[name='submit']").val('<?php echo lang('confirm'); ?>');
                $("input[name='submit']").attr("disabled", false);
            }
        });

    });
    $('.get_captcha').click(function () {
        var paypal_email = $('input[name="paypal_email"]').val();
        console.log(paypal_email);
        if (!$("#yinc").val()) {
            layer.msg('<?php echo lang('info_email'); ?>'); //返回信息
            return false;
        }
<?php if (!$userInfo['paypal_email']) { ?>
            var bd_type = 4;//未绑定
<?php } else { ?>
            var bd_type = 5;//解绑定
<?php } ?>
        $.ajax({
            type: "POST",
            url: "/register/add_captcha_zj",
            data: {email_or_phone: paypal_email, reg_type: 0, bd_type: bd_type},
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    $(".get_captcha").attr('disabled', true);
                    $(".get_captcha").css('background', '#cccccc');
                    add(60);
                }
            }
        });

    });
    function add(timerc) {
        if (timerc > 1) {
            --timerc;
            $(".get_captcha").val('<?php echo lang('resend_captcha') ?>' + timerc + 's');
            setTimeout("add(" + timerc + ")", 1000);
        } else {
            $(".get_captcha").val('<?php echo lang('get_captcha') ?>');
            $(".get_captcha").attr('disabled', false);
            $(".get_captcha").css('background', '#f7f7f7');
        }
    }
</script>
