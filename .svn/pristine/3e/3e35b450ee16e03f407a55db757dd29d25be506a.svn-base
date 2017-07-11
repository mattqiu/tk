<style type="text/css">
    td{border:0px solid #039;}
</style>
<div class="block upgrade">
    <p class="block-heading"><?php echo lang('current_commission') ?> : $<span id='curAmount'><?php echo $userInfo['amount'] ?></span></p>
    <div class="block-body">
        <form id="alipay_binding_form" class="form-inline" method="GET">
            <?php if ($userInfo['alipay_account'] && $userInfo['alipay_name']) { ?>
                <input type="hidden" name="bd_type" value="5" />
            <?php } else { ?>
                <input type="hidden" name="bd_type" value="4" />
            <?php } ?>
            <table>
                <tr>
                    <td class="right_duiqi"><?php echo lang('alipay_binding_accounts'); ?>：</td>
                    <td>
                        <input style="width:auto;width:250px;"  placeholder="<?php  echo sprintf(lang('alipay_binding_name_prompt'), $userInfo['name']);?>" autocomplete="off" type="text" <?php if ($userInfo['alipay_account'] && $userInfo['alipay_name']) { ?>readonly="readonly" value="<?php echo $userInfo['alipay_account']; ?>"<?php } ?> name="alipay_account"><span class="msg alipay_account"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span><span id="tip1" style="color:red;"><?php
                            ?></span>
                    </td>
                </tr>
                <?php if (!$userInfo['alipay_account'] || !$userInfo['alipay_name']) { ?>
                    <tr>
                        <td class="right_duiqi"><?php echo lang('alipay_binding_accounts_q'); ?>：</td>
                        <td>
                            <input style="width:auto;width:250px;" autocomplete="off" type="text" name="alipay_account_q"><span class="msg alipay_account_q"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span><span id="tip2" style="color:red"></span>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="right_duiqi"><?php echo lang('alipay_actual_name'); ?>：</td>
                    <td>
                        <input style="width:auto;width:250px;" autocomplete="off" type="text" name="alipay_name"  <?php if($user['country_id']!=4){ echo 'readonly="readonly"'; } ?> value="<?php echo $userInfo['name']; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="right_duiqi"><?php echo lang('alipay_binding_vcode'); ?>：</td>
                    <td>
                        <input style="width:auto;width:250px;" autocomplete="off" type="text" name="vcode">&nbsp;&nbsp;<input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha" value="<?php echo lang('get_captcha') ?>"> <a href="#" onclick="$('#popUserInfo').modal();" ><?php echo lang('where_code');?></a>
                    </td>
                </tr>
                <tr>
                    <td class="right_duiqi"><?php echo lang('funds_pwd'); ?>：</td>
                    <td>
                        <input style="width:auto;width:250px;" autocomplete="off" type="password" name="password">&nbsp;&nbsp;<span class="msg success"><?php echo lang('no_funds_pwd_notice')?></span><a href="<?php echo base_url('ucenter/change_funds_pwd')?>"><?php echo lang('forgot_funds_pwd');?></a>
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
                        <input id="alipay_submit_confirm" autocomplete="off" name="submit" value="<?php echo lang('confirm'); ?>" class="btn btn-primary" type="button">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input id="alipay_submit_cancel" autocomplete="off" onclick="javascript:history.back(-1);" value="<?php echo lang('cancel'); ?>" class="btn btn-primary" type="button">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!-- 用户详细信息弹层 -->
<div id="popUserInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="position:absolute;left:50%;top: 50%;width:400px;margin-left: -300px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo lang('prompt_title') ?></h3>
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
                <td><?php echo lang('prompt_1'); ?></td>
            </tr>
        </table>
        <table class="enable_level_tb" style="border-collapse: separate;border-spacing: 5px;width: 350px">
            <tr>
                <td><?php echo lang('prompt_2'); ?></td>
            </tr>
        </table>
        <button type="button" style="display:block;clear: both;margin: 0 auto;margin-top: 10px;" class="btn btn-default"
                data-dismiss="modal"><?php echo lang('confirm'); ?>
        </button>
    </div>
</div>
<!-- /用户详细信息弹层 -->

<script>

    //请求后台验证是否账号重复


    function add(timerc) {
        if (timerc > 1) {
            --timerc;
            $(".get_captcha").val('<?php echo lang('resend_captcha') ?>' + timerc + 's');
            t=setTimeout("add(" + timerc + ")", 1000);
        } else {
            $(".get_captcha").val('<?php echo lang('get_captcha') ?>');
            $(".get_captcha").attr('disabled', false);
            $(".get_captcha").css('background', '#f7f7f7');
        }
    }

    $(document).ready(function(){
        //支付宝绑定与解绑 m by brady.wang
        var bd_type = $('input[name="bd_type"]').val();
        var country_id = "<?php  echo $user['country_id']; ?>";
        $('input[name="alipay_account"]').on('blur',function(){
            var alipay_account = $.trim($('input[name="alipay_account"]').val());
            //支付宝非空验证
            if (alipay_account.length <= 0) {
                layer.msg('<?php echo lang("alipay_account_not_empty");?>');
                return false;
            }
            //支付宝格式验证
            if (country_id == '4') {
                if(!alipay_account.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/)) {
                    layer.msg('<?php echo sprintf(lang('alipay_binding_name_prompt'), $userInfo['name']); ?>');
                    return false;
                }
            } else {
                if(!(alipay_account.match(/^1[34578]\d{9}$/) || alipay_account.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/))) {
                    layer.msg('<?php echo sprintf(lang('alipay_binding_name_prompt'), $userInfo['name']); ?>');
                    return false;
                }
            }

            if (bd_type == 4) {
                return true;
            }
            //支付宝重复验证
            $.ajax({
                type: "POST",
                url: "/ucenter/alipay_binding_unbundling/alipay_account_ajax",
                data: {alipay_account: alipay_account},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        flag = true;
                    } else {
                        layer.msg('<?php echo lang('repeat_account'); ?>'); //返回信息
                        flag = false;
                        return false;
                    }
                }
            });
        })

        //支付宝账号重复验证
        $("input[name='alipay_account_q']").on('blur',function(){
            var alipay_account = $.trim($('input[name="alipay_account"]').val());
            var alipay_account_q = $.trim($('input[name="alipay_account_q"]').val());
            if (alipay_account_q.length <= 0) {
                layer.msg("<?php echo lang('alipay_account_input_again');?>");
                return false;
            }
            if (alipay_account !== alipay_account_q) {
                layer.msg("<?php echo lang('alipay_account_not_same');?>");
                return false;
            }
        })
        //发送验证码
        $('.get_captcha').click(function(){
            var alipay_account = $.trim($('input[name="alipay_account"]').val());
            var alipay_account_q = $.trim($('input[name="alipay_account_q"]').val());
            //支付宝非空验证
            if (bd_type == 5) {
                $(".get_captcha").attr('disabled', true);
                $(".get_captcha").css('background', '#cccccc');
                add(60);
                $.ajax({
                    type: "POST",
                    url: "/register/add_captcha_zj",
                    data: {email_or_phone:alipay_account,reg_type:0},
                    dataType: "json",
                    success: function (data) {
                        if (data.error == false) {
                            layer.msg("<?php echo lang('tickets_send_success');?>");
                        }else{
                            clearTimeout(t);
                            $(".get_captcha").removeAttr('disabled');
                            $(".get_captcha").val('<?php echo lang('get_captcha') ?>');
                            $(".get_captcha").css('background', '#f7f7f7');
                            layer.msg(data.message);
                        }
                    }
                });
            } else {
                if (alipay_account.length <= 0) {
                    layer.msg('<?php echo lang("alipay_account_not_empty");?>');
                    return false;
                }
                //支付宝格式验证
                if (country_id == '4') {
                    if(!alipay_account.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/)) {
                        layer.msg('<?php echo sprintf(lang('alipay_binding_name_prompt'), $userInfo['name']); ?>');
                        return false;
                    }
                } else {
                    if(!(alipay_account.match(/^1[34578]\d{9}$/) || alipay_account.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/))) {
                        layer.msg('<?php echo sprintf(lang('alipay_binding_name_prompt'), $userInfo['name']); ?>');
                        return false;
                    }
                }
                if (alipay_account_q.length <= 0) {
                    layer.msg("<?php echo lang('alipay_account_input_again');?>");
                    return false;
                }
                if (alipay_account !== alipay_account_q) {
                    layer.msg("<?php echo lang('alipay_account_not_same');?>");
                    return false;
                }
                //支付宝重复验证
                $.ajax({
                    type: "POST",
                    url: "/ucenter/alipay_binding_unbundling/alipay_account_ajax",
                    data: {alipay_account: alipay_account},
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            $(".get_captcha").attr('disabled', true);
                            $(".get_captcha").css('background', '#cccccc');
                            add(60);
                            $.ajax({
                                type: "POST",
                                url: "/register/add_captcha_zj",
                                data: {email_or_phone:alipay_account,reg_type:0},
                                dataType: "json",
                                success: function (data) {
                                    if (data.error == false) {
                                        layer.msg("<?php echo lang('tickets_send_success');?>");
                                    }else{
                                        clearTimeout(t);
                                        $(".get_captcha").removeAttr('disabled');
                                        $(".get_captcha").val('<?php echo lang('get_captcha') ?>');
                                        $(".get_captcha").css('background', '#f7f7f7');
                                        layer.msg(data.message);
                                    }
                                }
                            });
                        } else {
                            layer.msg('<?php echo lang('repeat_account'); ?>'); //返回信息
                            return false;
                        }
                    }
                });
            }


        })

        $('#alipay_submit_confirm').click(function () {
            var alipay_account = $.trim($('input[name="alipay_account"]').val());
            var alipay_account_q = $.trim($('input[name="alipay_account_q"]').val());
            //支付宝非空验证
            if (bd_type == 4) {
                if (alipay_account.length <= 0) {
                    layer.msg('<?php echo lang("alipay_account_not_empty");?>');
                    return false;
                }
                //支付宝格式验证
                if (country_id == '4') {
                    if(!alipay_account.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/)) {
                        layer.msg('<?php echo sprintf(lang('alipay_binding_name_prompt'), $userInfo['name']); ?>');
                        return false;
                    }
                } else {
                    if(!(alipay_account.match(/^1[34578]\d{9}$/) || alipay_account.match(/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/))) {
                        layer.msg('<?php echo sprintf(lang('alipay_binding_name_prompt'), $userInfo['name']); ?>');
                        return false;
                    }
                }
                //支付宝重复验证

                if (alipay_account_q.length <= 0) {
                    layer.msg("<?php echo lang('alipay_account_input_again');?>");
                    return false;
                }
                if (alipay_account !== alipay_account_q) {
                    layer.msg("<?php echo lang('alipay_account_not_same');?>");
                    return false;
                }
            }

            //m by brady.wang 验证码和资金密码都没输入的,不请求后台
            var vcode = $.trim($("input[name='vcode']").val());
            var password = $.trim($("input[name='password']").val());

            vcode = $.trim(vcode);
            password = $.trim(password);
            if (vcode.length == 0) {
                layer.msg("<?php echo lang('please_input_code');?>");
                return ;
            }

            if (password.length ==0) {
                layer.msg("<?php echo lang('please_input_cash_passwd');?>");
                return ;
            }

            $("input[name='submit']").val('<?php echo lang('processing'); ?>');
            $("input[name='submit']").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "/ucenter/alipay_binding_unbundling/submit",
                data: $('#alipay_binding_form').serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        layer.msg('<?php echo lang('result_ok'); ?>'); //返回信息
                        location.href = "/ucenter/take_out_cash";
                        //setTimeout("location = '/ucenter/take_out_cash'", 3000);
                    } else {
                        layer.msg(data.msg); //返回失败信息
                    }
                    $("input[name='submit']").val('<?php echo lang('confirm'); ?>');
                    $("input[name='submit']").attr("disabled", false);
                }
            });

        });

    })


</script>

