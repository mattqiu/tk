<?php
/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/6
 * Time: 16:37
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="<?php echo base_url('ucenter_theme/stylesheets') ?>/change_mobile.css" rel ="stylesheet" />
</head>
<body>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
<input type="hidden"  id="resend_code_text" value="<?php echo lang('resend_code_again');?>"/>
<input type="hidden"  id="get_code_text" value="<?php echo lang('tps_get_captcha')?>"/>
<div class="warp">
    <header class="mobile_header"><?php echo lang('change_mobile');?></header>
    <section class="mobile_main">
        <ul>
            <li>
                <i class="step">1</i>
                <span class="modify_info"><?php echo lang('choose_edit_type');?></span>
            </li>
            <li>
                <i class="step step2" id="icon_tep2">2</i>
                <span class="info_gray" id="info_active"><?php echo lang('verify_identify');?></span>
            </li>
            <li>
                <i class="step step2" id="icon_tep3">3</i>
                <span class="info_gray" id="info2_active"><?php echo lang('verify_new_mobile');?></span>
            </li>
            <div class="modify_step_line" id="line_active"></div>
            <div class="modify_step_line line2" id="line2_active"></div>
        </ul>
        <div class="clx"></div>
        <div class="modify_content">
            <div style="display: block">
                <div style="height:300px;" id="style_modify">
                    <a class="modify_btn active"  href="javascript:void(0);" data-type="mobile"><?php echo lang('verify_by_old_phone');?></a>
                    <a class="modify_btn" href="javascript:void(0);" data-type="email"><?php echo lang('verify_by_email');?></a>
                </div>
                <a class="next_btn" id="first_btn" href="javascript:void(0);"><?php echo lang('next_step');?></a>
            </div>
            <div class="modify_info_step2" id="show_step2" style="display: none">
                <div class="code_msg" style="height:300px;">
                    <span><?php echo lang('alipay_binding_vcode');?></span>&nbsp;
                    <input type="text" id="old_code" name="old_code" placeholder="<?php echo lang('alipay_binding_vcode');?>" autocomplete="off" maxlength="4"   onkeyup="value=value.replace(/[^\d]/g,'') "onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/>
                    <input type="button" id="get_code" value="<?php echo lang('tps_get_captcha');?>"  />
                    <p style="margin-top:15px;font-size:14px;margin-left:50px" id="old_code_tips"></p>
                </div>
                <input type="button" class="next_btn" id="second_btn"  value = "<?php echo lang('next_step');?>">
            </div>
            <div class="modify_info_step2" id="show_step3" style="display: none">

            </div>
            <div class="modify_info_step2" id="show_step4" style="display:none">
                <div class="code_msg" style="height:300px;">
                    <i><img src="<?php echo base_url('ucenter_theme');?>/images/success.png" style="vertical-align: middle"/></i>
                    <span style="font-size:18px;margin-left:20px"><?php echo lang('new_phone_edit_successed');?></span>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    $(function(){
        $("#style_modify a").on("click",function(){
            $(this).addClass("active").siblings().removeClass("active")
        })


//        $("#info_submit").on("click",function(){
//            $("#show_step4").show().siblings().hide();
//        })
    });
</script>
</body>
</html>



<script>
    var t,t1;
    function add(timerc) {
        if (timerc > 1) {
            --timerc;
            $("#get_code").val($('#resend_code_text').val() +" "+ timerc + 's');
            t = setTimeout("add(" + timerc + ")", 1000);
        } else {
            $("#get_code").val($('#get_code_text').val());
            $("#get_code").removeAttr("disable");
            $("#get_code").css('background', '#f7f7f7');
        }
    }
    function add1(timerc) {
        if (timerc > 1) {
            --timerc;
            $("#get_new_code").val($('#resend_code_text').val() +" "+ timerc + 's');
            t1 = setTimeout("add1(" + timerc + ")", 1000);
        } else {
            $("#get_new_code").val($('#get_code_text').val());
            $("#get_new_code").attr('disabled', false);
            $("#get_new_code").css('background', '#f7f7f7');
        }
    }


    $(function(){
        var email  = "<?php echo $user_info['email']?>";
        var email_encrypt  = "<?php echo $user_info['email_encrypt']?>";
        var is_verified_email  = "<?php echo $user_info['is_verified_email']?>";
        var mobile = "<?php echo $user_info['mobile']?>";
        var mobile_encrypt = "<?php echo $user_info['mobile_encrypt']?>";
        var uid = "<?php echo $user_info['id']; ?>";
        var front_flag = false;//前台验证标志
        var country_id = "<?php echo $curLocation_id;?>";
        var verify_type;
        var url = '';
        $("#first_btn").on("click",function(){

            $("#get_new_code").removeAttr('disabled');
            $("#get_new_code").css('background', '#E5E5E5');
            $("#get_new_code").val($('#get_code_text').val());

            $("#get_code").removeAttr('disabled');
            $("#get_code").css('background', '#E5E5E5');
            $("#get_code").val($('#get_code_text').val());

            verify_type = $("a.modify_btn.active").attr('data-type');
            if (verify_type == 'mobile') {
                if (country_id == '410') {
                    $("#old_code_tips").html("<?php echo lang_attr('verify_code_tip3',array('email'=>$user_info['mobile_encrypt']));?>")
                } else {
                    $("#old_code_tips").html("<?php echo lang('verify_code_tip3');?> <span style='color:#f00'>"+mobile_encrypt+"</span>")
                }


            } else {
                if (country_id == '410') {
                    $("#old_code_tips").html("<?php echo lang_attr('verify_code_tip3',array('email'=>$user_info['email_encrypt']));?>")
                } else {
                    $("#old_code_tips").html("<?php echo lang('verify_code_tip3');?> <span style='color:#f00'>"+email_encrypt+"</span>")
                }


            }
            $("#show_step2").show().siblings().hide();
            $("#line_active").addClass("step_line_active");
            $("#icon_tep2").addClass("step_active");
            $("#icon_tep2").removeClass("step2");
            $("#info_active").addClass("info_active");
            $("#info_active").removeClass("info_gray");
            $("#old_code").focus();
        });

        //获取验证码
        $("#get_code").on("click",function(){
            if (verify_type == 'email') {
                url = "/ucenter/mobile/send_email_code";
                var data = {email: email,uid:uid};
            } else {
                url = "/ucenter/mobile/get_mobile_code";
                var data = {mobile: mobile,uid:uid};
            }

            $("#get_code").attr('disabled', true);
            $("#get_code").css('background', '#cccccc');
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
                        $("#get_code").removeAttr('disabled');
                        $("#get_code").css('background', '#E5E5E5');
                        $("#get_code").val($('#get_code_text').val());
                        layer.msg(data.message);
                    }
                }
            });

        })//end of 获取邮箱验证码



        //验证下一步
        $("#second_btn").on("click",function(){



            var old_code = $("input[name='old_code']").val();
            var data = {};
            var url = '';
            if (verify_type == 'mobile') {
                url = "change_mobile/verify_mobile_code"
            } else {
                url = 'change_mobile/verify_email_code';
            }

            var pattern = /^\d{4}$/;

            //验证码数据验证
            if(old_code.length == 0) {
                layer.msg("<?php echo lang('email_code_not_nul');?>");
                return ;
            }
            if (!pattern.test(old_code)) {
                layer.msg("<?php echo lang('phone_code_rule_error');?>");
                return '';
            }
            data.old_code = old_code;
            data.mobile = mobile;
            data.email = email;
            data.uid = uid;
            var curEle = $("#second_btn");
            var oldSubVal = curEle.val();
            curEle.val($('#loadingTxt').val());
            curEle.attr("disabled", "disabled");

            $.ajax({
                type:"post",
                url:url,
                data:data,
                success:function(data) {
                    if (data.error == false) {
                        $("#show_step3").html(data.message).show();
                        $("#show_step3").siblings().hide();
                        $("#line2_active").addClass("step3_line_active");
                        $("#icon_tep3").addClass("step_active");
                        $("#icon_tep3").removeClass("step2");
                        $("#info2_active").addClass("info_active");
                        $("#info2_active").removeClass("info_gray");
                        curEle.removeAttr("disabled");
                        curEle.val(oldSubVal);

                    } else {
                        curEle.removeAttr("disabled");
                        curEle.val(oldSubVal);
                        layer.msg(data.message);
                    }
                }
            });

        }) //end of 邮箱验证下一步

        //新手机号码获取验证码
        $("body").on('click',"#get_new_code",function(){
            var new_mobile = $.trim($("input[name='new_phone']").val());
            var uid = "<?php echo $user_info['id'];?>";
            if (new_mobile.length <=0) {
                layer.msg("<?php echo lang('new_mobile_not_null');?>");
                return ;
            }
            var re = /^1[34578]\d{9}$/;
            if (!re.test(new_mobile)) {
                layer.msg("<?php echo lang('new_phone_rule_error');?>");
                return ;
            }

            //获取验证码
            url = "/ucenter/mobile/get_mobile_code";
            var data = {mobile: new_mobile,uid:uid,'type':"modify_mobile_bind"};

            $("#get_new_code").attr('disabled', true);
            $("#get_new_code").css('background', '#cccccc');
            add1(60);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data.error == false) {
                        layer.msg("<?php echo lang('tickets_send_success');?>");
                    } else {
                        clearTimeout(t1);
                        layer.msg(data.message);
                        $("#get_new_code").val($('#get_code_text').val());
                        $("#get_new_code").attr('disabled', false);
                        $("#get_new_code").removeAttr('disabled');
                        $("#get_new_code").css('background', '#E5E5E5');
                        $("#get_new_code").val($('#get_code_text').val());

                    }
                }
            });

        })

        //提交修改
        $("body").on('click','#info_submit',function(){
            var new_mobile = $.trim($("input[name='new_phone']").val());
            var old_mobile = "<?php echo $user_info['mobile'];?>";
            var uid = "<?php echo $user_info['id'];?>";
            var new_code = $("input[name='new_code']").val();
            var data = {};
            front_flag = true;
            if (front_flag == true) {
                if (new_mobile.length <= 0) {
                    layer.msg("<?php echo lang('new_mobile_not_null');?>");
                    return ;
                }

                var re = /^1[34578]\d{9}$/;
                if (!re.test(new_mobile)) {
                    layer.msg("<?php echo lang('new_phone_rule_error');?>");
                    return ;
                }

                if (new_code.length <= 0) {
                    layer.msg("<?php echo lang('email_code_not_nul');?>");
                    return ;
                }
                if (!(/^\d{4}$/.test(new_code))) {
                    layer.msg("<?php echo lang('phone_code_rule_error');?>");
                    return ;
                }

            }

            data.uid = uid;
            data.new_mobile = new_mobile;
            data.new_code = new_code;
            data.old_mobile = old_mobile;

            var curEle = $("#info_submit");
            var oldSubVal = curEle.val();
            curEle.val($('#loadingTxt').val());
            curEle.attr("disabled", "disabled");
            $.ajax({
                type:'post',
                data:data,
                url:'change_mobile/submit_change',
                dataType:'json',
                success:function(data) {
                    if (data.error == false) {
                        $("#show_step4").show().siblings().hide();
                        curEle.removeAttr("disabled");
                        curEle.val(oldSubVal);
                    } else {
                        layer.msg(data.message);
                        curEle.removeAttr("disabled");
                        curEle.val(oldSubVal);
                    }
                }
            })
        })

    })
</script>