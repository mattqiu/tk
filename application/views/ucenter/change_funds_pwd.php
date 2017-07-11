<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">
<style type="text/css">
.nav-tabs > .active > a,
.nav-tabs > .active > a:hover {
  color: #fff;
  background-color: #001d59;
  cursor: default;
}
</style>
<div class="well">
    <?php if($curLocation_id == 156) { ?>
        <ul class="nav nav-tabs">
         <li class="active"><a href="#" data-target="#tab1" data-toggle="tab" id="email_reset_passwd"><?php echo lang("email_reset_takecash_passwd");?></a></li>
         <li><a href="#" data-target="#tab2" data-toggle="tab" id='phone_reset_passwd'><?php echo lang("phone_reset_takecash_passwd");?></a></li>
        </ul>
    <?php }  ?>
    <div class="tab-content">
     <div class="tab-pane active" id="tab1" style="overflow-x:hidden;">
        <!--    邮箱重置密码开始-->
        <div class="email_reset_passwd" style="display:block" >
            <div class="alert alert-success hidden">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong><?php echo lang('send_funds_success');?></php></strong>
            </div>
            <form class="form-horizontal" id="change_email" method="post" autocomplete="off">
                <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">

                <label for="new_passwd_email"><?php echo lang("new_takecash_passwd");?></label>
                <div class="inline">
                    <input class="input-xlarge pull-left" name="new_passwd_email" maxlength="20" autocomplete="off" type="password" placeholder="<?php echo lang('take_out_pwd2');?>" >
                </div>
                <div class="msg inline">
                    <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                    <span class="txt" id="pwd_span"></span>
                </div>
                <br>
                <!--再次输入密码-->
                <label for="new_passwd_re_email"><?php echo lang("new_takecash_passwd_again");?></label>
                <div class="inline">
                    <input class="input-xlarge pull-left" maxlength="20" autocomplete="off" name="new_passwd_re_email" type="password" placeholder="<?php echo lang('take_out_pwd2');?>" >
                </div>

                <div class="msg inline">
                    <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                    <span class="txt" id="pwd_span"></span>
                </div>
                <div style="clear: both;"></div>

                <!--   tps密码-->
                <label for="tps_pwd_email" style="margin-top: 20px;"><?php echo lang('tps_login_pwd_reset')?></label>
                <div class="inline">
                    <input class="input-xlarge pull-left" autocomplete="off" type="password" id="tps_pwd_email" name="tps_pwd_email" placeholder="<?php echo lang('tps_login_pwd_reset')?>" >
                </div>
                <div class="msg inline">
                    <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                    <span class="txt"></span>
                </div>
                <div style="clear: both;"></div>

                <!--            验证码-->
                <label for="email_code" style="margin-top: 20px;"><?php echo lang("verify_code");?></label>
                <div class="inline">
                    <input class="input-xlarge pull-left" name="email_code" class="btn btn-white btn-weak " autocomplete="off" maxlength="5" type="text" id="email_code"  placeholder="<?php echo lang("verify_code");?>" style="width:120px;" >&nbsp;&nbsp; <?php  if($is_verified_email == 1)  {  ?><input type="button" id = "get_email_code" value="<?php echo lang('get_captcha');?>" >  <?php  }  ?>
                    <?php if (!empty($email) && $is_verified_email == 1) {
                        if ($curLocation_id == '410') {
                            echo '('.  lang_attr('verify_code_tip3',array("email"=>'<span style="color:red;">'.$email.'</span> ')).')';
                        } else {
                            echo '('.  lang('verify_code_tip3'). '<span style="color:red;">'.$email_encrypt.'</span> )';
                        }

                    }else {
                        //echo "(<span style = 'color:red;'><a href= ".lang('please_bind_email_first')."</span>)";
                        echo "<span ><a  class=\"btn btn-primary\"  href=\"javaScript:binding_email()\" >".lang('please_bind_email_first')."</a></span>";
                    }?>
                </div>
                <br>
                <div style="margin-top:20px; ">
                    <button type="button" id="submit_reset_email" class="btn btn-primary" ><?php echo lang('submit')?></button>
                </div>
                <input type="hidden" value="<?php echo lang('resend_captcha')?>" id="resend_captcha_1">
                <input type="hidden" value="<?php echo lang('get_captcha')?>" id="get_captcha_1">

            </form>
        </div>
        <!--    邮箱重置密码结束-->
     </div>
     <div class="tab-pane" id="tab2" style="overflow-x:hidden;">
     
 <?php if($curLocation_id == 156) { ?>
    <!--    手机号重置密码开始-->
        <div class="phone_reset_passwd" >
            <form class="form-horizontal"  method="post" autocomplete="off">
                <input type="hidden" name ="mobile" value="<?php
                if (isset($userInfo['mobile']))
                {
                    echo $userInfo['mobile'];
                } else {
                    echo "";
                }
                ?>" />
                <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
    <!--            输入资金密码-->
                <label for="new_passwd"><?php echo lang("new_takecash_passwd");?></label>
                <div class="">
                    <input class="input-xlarge pull-left" type="password" name = "new_passwd" maxlength="16" placeholder="<?php echo lang('take_out_pwd2');?>"  >
                </div>
    
                <div class="msg inline">
                    <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                    <span class="txt" id="pwd_span"></span>
                </div>
                <br>
    <!--再次输入密码-->
                <label for="new_passwd_re"><?php echo lang("new_takecash_passwd_again");?></label>
                <div class="inline">
                    <input class="input-xlarge pull-left" name="new_passwd_re" type="password" maxlength="16" placeholder="<?php echo lang('take_out_pwd2');?>" >
                </div>
    
                <div class="msg inline">
                    <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                    <span class="txt" id="pwd_span"></span>
                </div>
                <div style="clear: both;"></div>
    
    <!--            tps密码-->
                <label for="tps_pwd" style="margin-top: 20px;"><?php echo lang('tps_login_pwd_reset')?></label>
                <div class="inline">
                    <input class="input-xlarge pull-left" type="password" id="pwd" name="tps_pwd" placeholder="<?php echo lang('tps_login_pwd_reset')?>" >
                </div>
                <div class="msg inline">
                    <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                    <span class="txt"></span>
                </div>
                <div style="clear: both;"></div>

    <!--            验证码-->
                <label for="phone_code" style="margin-top: 20px;"><?php echo lang("phone_code");?></label>
                <div class="inline">
                    <input class="input-xlarge pull-left" name="phone_code" class="btn btn-white btn-weak " type="text" id="pwd" name="phone_code" maxlength="4" placeholder="<?php echo lang("verify_code");?>" style="width:100px;" >&nbsp;&nbsp; <input type="button" id = "get_phone_code" value="<?php echo lang('get_phone_code');?>"><?php echo lang("verify_code_tip1"), $mobile_encrypt,lang('verify_code_tip2');?>
                </div>
                <br>
                <div style="margin-top:20px; ">
                    <button type="button" name="submit_phone" class="btn btn-primary" ><?php echo lang('submit')?></button>
                </div>
                <input type="hidden" value="<?php echo lang('resend_captcha')?>" id="resend_captcha_1">
                <input type="hidden" value="<?php echo lang('get_captcha')?>" id="get_captcha_1">
    
    
            </form>
        </div>
    
    <!--    手机号重置密码结束-->
     
     </div>
    </div>


<?php } ?>
</div>
<style>
    .main .msg {
        width: 100%;
    }
</style>
<script>
    
    function binding_email() {
    $('#binding_info_email').modal();
   }
   
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
                url: "/ucenter/change_funds_pwd/changeFundsPwd",
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
                    //curEle.html(oldSubVal);
                    
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

    //邮箱 手机号码重置密码
    $("#email_reset_passwd").click(function () {
        $(".email_reset_passwd").css('display', "block");
        $(".phone_reset_passwd").css('display', "none");
    })

    $("#phone_reset_passwd").click(function () {
        $(".email_reset_passwd").css('display', "none");
        $(".phone_reset_passwd").css('display', "block");
        var is_verified_mobile = "<?php echo $userInfo['is_verified_mobile'];?>";


    })

    function add1(timerc) {
        if (timerc > 1) {
            --timerc;
            $("#get_phone_code").val($('#resend_captcha_1').val() + timerc + 's');
            t = setTimeout("add1(" + timerc + ")", 1000);
        } else {
            $("#get_phone_code").val($('#get_captcha_1').val());
            $("#get_phone_code").attr('disabled', false);
            $("#get_phone_code").css('background', '#f7f7f7');
        }
    }

    function add2(timerc) {
        if (timerc > 1) {
            --timerc;
            $("#get_email_code").val($('#resend_captcha_1').val() +" "+ timerc + 's');
            t = setTimeout("add2(" + timerc + ")", 1000);
        } else {
            $("#get_email_code").val($('#get_captcha_1').val());
            $("#get_email_code").attr('disabled', false);
            $("#get_email_code").css('background', '#f7f7f7');
        }
    }

    //手机重置资金密码 提交
    $("button[name='submit_phone']").click(function () {
        var post_data = {};
        var is_verified_mobile = "<?php echo $userInfo['is_verified_mobile'];?>";
        var user_phone = "<?php echo $userInfo['mobile'];?>";
        if (user_phone.length <= 0 ) {
            layer.msg("您尚未验证手机号码，请至账户信息栏目进行验证");
            return;
        }

        //数据验证
        //1 新密码
        var new_passwd = $("input[name='new_passwd']").val();
        if (new_passwd == '') {
            layer.msg("<?php echo lang("new_passwd_not_null");?>");
            return '';
        }

        if (!new_passwd.match(/^[0-9A-Za-z]{8,16}$/)) {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
        } else if(!new_passwd.match(/[A-Z]+/))  {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
         } else if(!new_passwd.match(/[a-z]+/))  {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
         } else if(!new_passwd.match(/[0-9]+/))  {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
         }
         
        post_data.new_pwd = new_passwd;

        //验证两次密码是否一致
        var new_passwd_re = $("input[name='new_passwd_re']").val();
        if (new_passwd_re == '') {
            layer.msg("<?php  echo lang("enter_re_passwd");?>");
            return '';
        }

        if (new_passwd_re != new_passwd) {
            layer.msg("<?php echo lang("passwd_not_same");?>");
            return '';
        }
        post_data.new_pwd_re = new_passwd_re;
        //验证tps登陆密码
        var tps_pwd = $("input[name='tps_pwd']").val();
        if (tps_pwd == '') {
            layer.msg("<?php echo lang("enter_tps_passwd");?>");
            return '';
        }
        post_data.tps_pwd = tps_pwd;

        //验证码不能为空
        var phone_code = $("input[name='phone_code']").val();
        if (phone_code == '') {
            layer.msg("<?php echo lang("please_input_code");?>");
            return '';
        }


        if (!phone_code.match(/^\d{3,5}$/)) {
            layer.msg("<?php echo lang("phone_code_rule_error");?>");
            return '';
        }
        post_data.phone_code = phone_code

        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");

        $.ajax({
            type: "POST",
            url: "/ucenter/change_funds_pwd/resetPassword",
            data: post_data,
            dataType: "json",
            success: function (data) {
                if (data.error == false) {
                    layer.msg(data.msg);
                    location.reload();
                } else {
                    layer.msg(data.msg);
                }
                curEle.html(oldSubVal);
                curEle.attr("disabled", false);
            }
        });
    });

    //发送验证码
    $('#get_phone_code').click(function () {
        //取消验证 该字段
        var is_verified_mobile = "<?php echo $userInfo['is_verified_mobile'];?>";
        var user_phone = "<?php echo $userInfo['mobile'];?>";
        if (user_phone.length <= 0 ) {
            layer.msg("您尚未验证手机号码，请至账户信息栏目进行验证");
            return;
        }



        //数据验证
        //1 新密码
        var new_passwd = $("input[name='new_passwd']").val();
        if (new_passwd == '') {
            layer.msg("<?php echo lang("new_passwd_not_null");?>");
            return '';
        }

        if (!new_passwd.match(/^[0-9A-Za-z]{8,16}$/)) {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
        } else if(!new_passwd.match(/[A-Z]+/))  {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
        } else if(!new_passwd.match(/[a-z]+/))  {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
        } else if(!new_passwd.match(/[0-9]+/))  {
            layer.msg("<?php echo lang("take_out_pwd2");?>");
            return '';
        }


        //验证两次密码是否一致
        var new_passwd_re = $("input[name='new_passwd_re']").val();
        if (new_passwd_re == '') {
            layer.msg("<?php  echo lang("enter_re_passwd");?>");
            return '';
        }

        if (new_passwd_re != new_passwd) {
            layer.msg("<?php echo lang("passwd_not_same");?>");
            return '';
        }
        //验证tps登陆密码
        var tps_pwd = $("input[name='tps_pwd']").val();
        if (tps_pwd == '') {
            layer.msg("<?php echo lang("enter_tps_passwd");?>");
            return '';
        }

        var mobile = $('input[name="mobile"]').val();
        var reg_type = 2;
        $("#get_phone_code").attr('disabled', true);
        $("#get_phone_code").css('background', '#cccccc');
        add1(60);
        $.ajax({
            type: "POST",
            url: "/register/add_captcha_zj",
            data: {email_or_phone: mobile, reg_type: reg_type, action_id: 4},
            dataType: "json",
            success: function (data) {
                if (data.error == false) {
                    layer.msg("<?php echo lang('tickets_send_success');?>");
                } else {
                    clearTimeout(t);
                    $("#get_phone_code").removeAttr('disabled');
                    $("#get_phone_code").css('background', '#E5E5E5');
                    $("#get_phone_code").val('<?php echo lang('get_phone_code') ?>');
                    layer.msg(data.message);
                }
            }
        });
    });

    // m by brady.wang  邮箱重置密码
    $(function(){
        var front_verify = true; //前台验证标志
//        $("input[name='new_passwd_re_email']").on("blur",function(){
//            if (front_verify == true) {
//                var user_email = "<?php //echo $userInfo['email'];?>//";
//                var new_passwd = $.trim($("input[name='new_passwd_email']").val());
//                var new_passwd_re = $.trim($("input[name='new_passwd_re_email']").val());
//
//
//                //数据验证
//                //1 新密码
//                if (new_passwd == '') {
//                    layer.msg("<?php //echo lang("new_passwd_not_null");?>//");
//                    return '';
//                }
////                if (!new_passwd.match(/^\d{6}$/)) {
////                    layer.msg("<?php ////echo lang("new_passwd_rule");?>////");
////                    return '';
////                }
//                if (!new_passwd.match(/^[0-9A-Za-z]{8,16}$/)) {
//                    layer.msg("<?php //echo lang("take_out_pwd2");?>//");
//                    return '';
//                } else if(!new_passwd.match(/[A-Z]+/))  {
//                    layer.msg("<?php //echo lang("take_out_pwd2");?>//");
//                    return '';
//                } else if(!new_passwd.match(/[a-z]+/))  {
//                    layer.msg("<?php //echo lang("take_out_pwd2");?>//");
//                    return '';
//                } else if(!new_passwd.match(/[0-9]+/))  {
//                    layer.msg("<?php //echo lang("take_out_pwd2");?>//");
//                    return '';
//                }
//                //验证两次密码是否一致
//                if (new_passwd_re == '') {
//                    layer.msg("<?php // echo lang("enter_re_passwd");?>//");
//                    return '';
//                }
//
//                if (new_passwd_re != new_passwd) {
//                    layer.msg("<?php //echo lang("passwd_not_same");?>//");
//                    return '';
//                }
//            }
//
//        })



        $("#submit_reset_email").on('click',function(){

            var post_data = {};

            var user_email = "<?php echo $userInfo['email'];?>";
            var new_passwd = $.trim($("input[name='new_passwd_email']").val());
            var new_passwd_re = $.trim($("input[name='new_passwd_re_email']").val());
            var tps_pwd = $.trim($("input[name='tps_pwd_email']").val());
            var email_code = $.trim($("input[name='email_code']").val());

            var is_verified_email = "<?php echo $userInfo['is_verified_email'];?>";
            if (user_email.length <= 0 || is_verified_email != '1' ) {
                $('#binding_info_email').modal();
                return;
            }
            if (user_email.length <= 0 ) {
                layer.msg("<?php echo lang('please_bind_email_first')?>");
                return;
            }

            //数据验证
            if (front_verify == true) {
                //1 新密码
                if (new_passwd == '') {
                    layer.msg("<?php echo lang("new_passwd_not_null");?>");
                    return '';
                }
//                if (!new_passwd.match(/^\d{6}$/)) {
//                    layer.msg("<?php //echo lang("new_passwd_rule");?>//");
//                    return '';
//                }

                if (!new_passwd.match(/^[0-9A-Za-z]{8,16}$/)) {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                } else if(!new_passwd.match(/[A-Z]+/))  {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                } else if(!new_passwd.match(/[a-z]+/))  {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                } else if(!new_passwd.match(/[0-9]+/))  {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                }
                if (new_passwd_re == '') {
                    layer.msg("<?php  echo lang("enter_re_passwd");?>");
                    return '';
                }
                //验证两次密码是否一致
                if (new_passwd_re != new_passwd) {
                    layer.msg("<?php echo lang("passwd_not_same");?>");
                    return '';
                }
                //验证tps登陆密码
                if (tps_pwd == '') {
                    layer.msg("<?php echo lang("enter_tps_passwd");?>");
                    return '';
                }
                //验证码不能为空
                if (email_code == '') {
                    layer.msg("<?php echo lang("please_input_code");?>");
                    return '';
                }
                if (!email_code.match(/^\d{3,5}$/)) {
                    layer.msg("<?php echo lang("phone_code_rule_error");?>");
                    return '';
                }
            }
            post_data.new_pwd = new_passwd;
            post_data.new_pwd_re = new_passwd_re;
            post_data.tps_pwd = tps_pwd;
            post_data.email_code = email_code

            var curEle = $(this);
            var oldSubVal = curEle.text();
            curEle.html($('#loadingTxt').val());
            curEle.attr("disabled", "disabled");

            $.ajax({
                type: "POST",
                url: "/ucenter/change_funds_pwd/resetPasswordByEmail",
                data: post_data,
                dataType: "json",
                success: function (data) {
                    if (data.error == false) {
                        layer.msg(data.message);
                        location.reload();
                    } else {
                        layer.msg(data.message);
                    }
                    curEle.html(oldSubVal);
                    curEle.attr("disabled", false);
                }
            });
        })

        //发送邮箱验证码
        //发送验证码
        $('#get_email_code').click(function () {
            var  data = {};
            //取消验证 该字段
            var user_email = "<?php echo $userInfo['email'];?>";
            var new_passwd = $.trim($("input[name='new_passwd_email']").val());
            var new_passwd_re = $.trim($("input[name='new_passwd_re_email']").val());
            var tps_pwd = $.trim($("input[name='tps_pwd_email']").val());

            var is_verified_email = "<?php echo $userInfo['is_verified_email'];?>";
            if (user_email.length <= 0 || is_verified_email != '1' ) {
                $('#binding_info_email').modal();
                return;
            }
            if (user_email.length <= 0 ) {
                layer.msg("<?php echo lang('please_bind_email_first')?>");
                return;
            }
            if (front_verify == true) {

                if (user_email.length <= 0 ) {
                    layer.msg("<?php echo lang('please_bind_email_first')?>");
                    return;
                }
                //数据验证
                //1 新密码
                if (new_passwd == '') {
                    layer.msg("<?php echo lang("new_passwd_not_null");?>");
                    return '';
                }
//                if (!new_passwd.match(/^\d{6}$/)) {
//                    layer.msg("<?php //echo lang("new_passwd_rule");?>//");
//                    return '';
//                }

                if (!new_passwd.match(/^[0-9A-Za-z]{8,16}$/)) {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                } else if(!new_passwd.match(/[A-Z]+/))  {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                } else if(!new_passwd.match(/[a-z]+/))  {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                } else if(!new_passwd.match(/[0-9]+/))  {
                    layer.msg("<?php echo lang("take_out_pwd2");?>");
                    return '';
                }
                //验证两次密码是否一致
                if (new_passwd_re == '') {
                    layer.msg("<?php  echo lang("enter_re_passwd");?>");
                    return '';
                }
                if (new_passwd_re != new_passwd) {
                    layer.msg("<?php echo lang("passwd_not_same");?>");
                    return '';
                }
                if (tps_pwd == '') {
                    layer.msg("<?php echo lang("enter_tps_passwd");?>");
                    return '';
                }
            }

            data.email = user_email;
            $("#get_email_code").attr('disabled', true);
            $("#get_email_code").css('background', '#cccccc');
            add2(60);
            $.ajax({
                type: "POST",
                url: "/ucenter/mobile/send_email_code",
                data: {email: user_email},
                dataType: "json",
                success: function (data) {
                    if (data.error == false) {
                        layer.msg("<?php echo lang('tickets_send_success');?>");
                    } else {
                        clearTimeout(t);
                        $("#get_email_code").removeAttr('disabled');
                        $("#get_email_code").css('background', '#E5E5E5');
                        $("#get_email_code").val('<?php echo lang('get_phone_code') ?>');
                        layer.msg(data.message);
                    }
                }
            });
        });
    })

</script>

