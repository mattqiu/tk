<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Desc:
 * Date: 2017/7/5
 * Time: 17:01
 */
?>
<style type="text/css">
    td{border:0px solid #039;}
</style>
<div class="block upgrade">
<!--    <p class="block-heading">--><?php //echo lang('current_commission') ?><!-- : $<span id='curAmount'>--><?php //echo $userInfo['amount'] ?><!--</span></p>-->
    <div class="block-body">
        <form id="alipay_binding_form" class="form-inline" method="GET">
            <?php if (!empty($user_card)) { ?>
<!--                解绑-->
                <input type="hidden" name="bd_type" value="5" />
            <?php } else { ?>
                <input type="hidden" name="bd_type" value="4" />
            <?php } ?>
            <table >
                <tr align="right">

                    <td class="right_duiqi"> <img src="/img/new/reg_icon.jpg">
                        <?php echo lang("bank_name");?>：</td>
                    <td align="left">
                        <input value = "<?php if(isset($user_card['bank_name'])){echo $user_card['bank_name'];} ?>" <?php if(!empty($user_card)){echo "readonly";} ?> maxlength="50" style="width:auto;width:250px;"  placeholder="<?php echo lang("bank_name");?>" maxlength="50" autocomplete="off" type="text"  name="bank_name"><span class="msg alipay_account"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span><span id="tip1" style="color:red;"><?php
                           echo lang_attr("bind_bank_needname",array('name'=>$user['name'])) ?></span>
                    </td>
                </tr>

                <tr align="right">
                    <td class="right_duiqi"><img src="/img/new/reg_icon.jpg"><?php echo lang("bank_branch_name");?>：</td>
                    <td align="left">
                        <input value = "<?php if(isset($user_card['bank_branch_name'])){echo $user_card['bank_branch_name'];} ?>" <?php if(!empty($user_card)){echo "readonly";} ?> maxlength="50" style="width:auto;width:250px;"  placeholder="<?php echo lang("bank_branch_name")?>" maxlength="50" name="bank_branch_name" autocomplete="off"  type="text"  /></span>
                    </td>
                </tr>

                <tr align="right">
                    <td class="right_duiqi"><img src="/img/new/reg_icon.jpg"><?php echo lang("bank_number")?>：</td>
                    <td align="left">
                        <input value = "<?php if(isset($user_card['bank_number'])){echo $user_card['bank_number'];} ?>" <?php if(!empty($user_card)){echo "readonly";} ?> maxlength="25" style="width:auto;width:250px;" onpaste="return false" oncontextmenu="return false"  oncopy="return false" oncut="return false"  onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" autocomplete="off" type="text" placeholder="<?php echo lang("bank_number")?>" name="bank_number" value="" />
                    </td>
                </tr>
                <?php if(empty($user_card)){?>
                <tr align="right">
                    <td class="right_duiqi"><img src="/img/new/reg_icon.jpg"><?php echo lang("confirm_bank_number");?>：</td>
                    <td align="left">
                        <input style="width:auto;width:250px;" autocomplete="off" type="text" placeholder="<?php echo lang("confirm_bank_number");?>" onpaste="return false" oncontextmenu="return false"  oncopy="return false" oncut="return false" name="bank_number_repeat"  onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="25" value="" />
                    </td>
                </tr>
                <?php }?>
                <tr align="right">
                    <td class="right_duiqi"><img src="/img/new/reg_icon.jpg"><?php echo lang("bank_user_name");?>：</td>
                    <td align="left">
                        <input value = "<?php if(isset($user['name'])){echo $user['name'];} ?>" <?php if(!empty($user_card) || isset($user['name'])){echo "readonly";} ?> style="width:auto;width:250px;" autocomplete="off" type="text" placeholder="<?php echo lang("bank_user_name");?>" maxlength="50" name="bank_user_name" value="" />
                    </td>
                </tr>
                <tr align="right">
                    <td class="right_duiqi"><img src="/img/new/reg_icon.jpg"><?php echo lang('alipay_binding_vcode'); ?>：</td>
                    <td align="left">
                        <input style="width:80px;" autocomplete="off" type="text" maxlength="6" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"  name="vcode">&nbsp;&nbsp;<input type="button" autocomplete="off" class="btn btn-white btn-weak get_captcha" value="<?php echo lang('get_captcha') ?>">
                        <?php
                            if(!empty($user['mobile']) && $user['is_verified_mobile'] == 1) {
                                echo lang("verify_code_tip1").$user['mobile'].lang("verify_code_tip2");
                            } else {
                                if(empty($user['mobile'])) {
                                    echo "(".lang("please_bind_mobile").")";
                                } else if($user['is_verified_mobile'] == 0) {
                                    echo "(".lang("please_verify_mobile").")";
                                }
                            }
                        ?>
                    </td>
                </tr>
                <tr align="right">
                    <td class="right_duiqi"><img src="/img/new/reg_icon.jpg"><?php echo lang('funds_pwd'); ?>：</td>
                    <td align="left">
                        <input style="width:250px;" autocomplete="off" type="password" name="password">&nbsp;&nbsp;<span class="msg success"><?php echo lang('no_funds_pwd_notice')?></span><a href="<?php echo base_url('ucenter/change_funds_pwd')?>"><?php echo lang('forgot_funds_pwd');?></a>
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
                        <input id="debit_card_submit" autocomplete="off" name="submit" value="<?php echo lang('confirm'); ?>" class="btn btn-primary" type="button">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input id="alipay_submit_cancel" autocomplete="off" onclick="javascript:history.back(-1);" value="<?php echo lang('cancel'); ?>" class="btn btn-primary" type="button">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
<input type="hidden"  id="resend_code_text" value="<?php echo lang('resend_code_again');?>"/>
<input type="hidden"  id="get_code_text" value="<?php echo lang('tps_get_captcha')?>"/>
<script>
    $(function(){
        $("#debit_card_submit").click(function(){
            var front_check = true;
            var bd_type = $("input[name='bd_type']").val();
            var bank_name = $.trim($("input[name='bank_name']").val());
            var bank_branch_name = $.trim($("input[name='bank_branch_name']").val());
            var bank_number = $.trim($("input[name='bank_number']").val());
            var bank_number_repeat = $.trim($("input[name='bank_number_repeat']").val());
            var bank_user_name = $.trim($("input[name='bank_user_name']").val());
            var vcode = $.trim($("input[name='vcode']").val());
            var password = $.trim($("input[name='password']").val());

            if (front_check == true) {
                if(bd_type == 4) {
                    if(bank_name.length <= 0) {
                        layer.msg("<?php echo lang('please_input_bank_name');?>");
                        return ;
                    }
                    if(bank_name.length >50) {
                        layer.msg("<?php echo lang('not_beyond_50');?>");
                        return ;
                    }
                    var reg = new RegExp("[\\u4E00-\\u9FFF]+","g");
                    if(!reg.test(bank_name)) {
                        layer.msg("<?php echo lang('bank_name_china_only');?>");
                        return ;
                    }
                        if(bank_branch_name.length <= 0) {
                        layer.msg("<?php echo lang('please_input_bank_branch_name');?>");
                        return ;
                    }

                    if(bank_name.length >50) {
                        layer.msg("<?php echo lang('not_beyond_50');?>");
                        return ;
                    }
                    var reg = new RegExp("[\\u4E00-\\u9FFF]+","g");
                    if(!reg.test(bank_branch_name)) {
                        layer.msg("<?php echo lang('bank_branch_name_china_only');?>");
                        return ;
                    }



                    if(bank_number.length <= 0) {
                        layer.msg("<?php echo lang('please_input_bank_number');?>");
                        return ;
                    }

                    if(bank_number_repeat.length <=0) {
                        layer.msg("<?php echo lang('confirm_bank_number');?>");
                        return ;
                    }

                    if(bank_number_repeat != bank_number ) {
                        layer.msg("<?php echo lang('bank_number_not_same');?>");
                        return ;
                    }



                }


                if(vcode.length <= 0) {
                    layer.msg("<?php echo lang('phone_code_not_null');?>");
                    return ;
                }

                if(password.length <= 0) {
                    layer.msg("<?php echo lang('please_input_password');?>");
                    return ;
                }
            }

            var mobile = "<?php echo isset($user['mobile']) ? $user['mobile'] : '';?>";
            var is_verified_mobile = "<?php echo isset($user['is_verified_mobile']) ? $user['is_verified_mobile'] : '';?>";
            if(mobile.length <=0) {
                layer.msg("<?php echo lang('please_bind_mobile');?>");
                return ;
            }
            if(is_verified_mobile == 0) {
                layer.msg("<?php echo lang('please_verify_mobile');?>");
                return ;
            }
            var data = {};
            data.uid = "<?php echo $user['id'];?>";
            data.bank_name = bank_name;
            data.bank_branch_name = bank_branch_name;
            data.bank_number = bank_number;
            data.bank_user_name = bank_user_name;
            data.bank_number_repeat = bank_number_repeat;
            data.vcode = vcode;
            data.password = password;
            data.bd_type = bd_type;

             var oldSubVal = $('#debit_card_submit').val();
             $('#debit_card_submit').val($('#loadingTxt').val());
             $('#debit_card_submit').attr("disabled","disabled");

            if(bd_type == 4){
                $.ajax({
                    type:'post',
                    data:data,
                    url:'/ucenter/debit_card/do_bind',
                    dataType:'json',
                    success:function(res){
                        if (res.success == true) {
                            layer.msg(res.message);
                            setTimeout('window.location.href="/ucenter/take_out_cash"',1000);
                        }else if(res.error == true || res.success == false) {
                            layer.msg(res.message);
                            $('#debit_card_submit').removeAttr("disabled");
                            $('#debit_card_submit').val(oldSubVal);
                        }
                    }
                })
            }else {
                $.ajax({
                    type:'post',
                    data:data,
                    url:'/ucenter/debit_card/do_unbind',
                    dataType:'json',
                    success:function(res){
                        if (res.success == true) {
                            layer.msg(res.message);
                            setTimeout('window.location.href="/ucenter/take_out_cash"',1000);
                        }else if(res.error == true || res.success == false) {
                            layer.msg(res.message);
                            $('#debit_card_submit').removeAttr("disabled");
                            $('#debit_card_submit').val(oldSubVal);
                        }

                    }
                })
            }


        })
    })

    var t,t1;
    function add(timerc) {
        if (timerc > 1) {
            --timerc;
            $(".get_captcha").val($('#resend_code_text').val() +" "+ timerc + 's');
            t = setTimeout("add(" + timerc + ")", 1000);
        } else {
            $(".get_captcha").val($('#get_code_text').val());
            $(".get_captcha").removeAttr("disable");
            $(".get_captcha").css('background', '#f7f7f7');
        }
    }
    //发送验证码
    $(function(){
        $('.get_captcha').click(function(){

            var mobile = "<?php echo isset($user['mobile']) ? $user['mobile'] : '';?>";
            var is_verified_mobile = "<?php echo isset($user['is_verified_mobile']) ? $user['is_verified_mobile'] : '';?>";
            if(mobile.length <=0) {
                layer.msg("<?php echo lang('please_bind_mobile');?>");
                return ;
            }
            if(is_verified_mobile == 0) {
                layer.msg("<?php echo lang('please_verify_mobile');?>");
                return ;
            }
            var data = {};
            data.mobile = mobile;
            var url ="/ucenter/mobile/get_mobile_code"

            $(".get_captcha").attr('disabled', true);
            $(".get_captcha").css('background', '#cccccc');
            add(60);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data.error == false) {
                        layer.msg("<?php echo lang('tickets_send_success');?>");
                    } else {
                        clearTimeout(t);
                        $(".get_captcha").removeAttr('disabled');
                        $(".get_captcha").css('background', '#E5E5E5');
                        $(".get_captcha").val($('#get_code_text').val());
                        layer.msg(data.message);
                    }
                }
            });
        })
    })
</script>
