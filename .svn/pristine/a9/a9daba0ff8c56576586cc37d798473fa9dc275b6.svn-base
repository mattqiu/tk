var account_info = {
    "msg":{},
}
function confirm_card() {
    $('#card_mask_layer').hide();
    $('#card_popup_layer').hide();
    location.reload();

}

$(function () {
     var cardPassExit = false;   //是否存在已通过审核的身份证号码标志变量 默认为假
     
    $("[name='payment_method']:radio").click(chooseLevel);
    $("#accountInfoVeryBtn").click(function() {
        var lang = $.cookie('curLan');   //获取语言类型
       //输入的身份证号码已经在数据库中通过审核
        if(cardPassExit) {
            if(lang == 'english') {
                msg = 'Government ID number already existed!';
            } else if(lang == 'zh') {
                msg = '您输入的身份证号码已经存在!';
            } else if(lang == 'hk') {
                msg = '您輸入的身份證號碼已經存在!';
            } else if(lang == 'kr') {
                msg = '등록증이 이미 존재하여 있습니다.';
            } else {
                msg = 'Government ID number already existed!';
            }
            $('#msg_id_card_num').text('');
            layer.msg(msg);
            return;
        }

       var face_src = $('#img_face').attr("src");
       var back_src = $('#img_back').attr("src");
       if(face_src !=""  || back_src !="") {
           $(this).removeAttr('href');   
           $(this).unbind("click");  //移除点击事件
        }
     $.ajax({
            beforeSend: function () {   //开始上传
                if(face_src !="") {
                    $('#upload_btn').hide();
                    $('#showimg').hide();
                    $('#upload_1_loading').show();
                }
                if(back_src !="") {
                    $('#upload_btn2').hide();
                    $('#showimg2').hide();
                    $('#upload_2_loading').show();
                }
                
            },
            type: "POST",
            url: "/ucenter/account_info/verifyCard",
            dataType:"json",
            data: {img_face:face_src,img_back:back_src},
            success: function(res){
   
                if (res.success) {
                  location.reload();
                  
               } else {
                    $.each(res.msg, function (itemName, msgVal) {   //提示出错信息
                    $('#msg_'+itemName).html(msgVal);
                    });
                   $('#upload_1_loading').hide();
                   $('#upload_2_loading').hide();
                   if(res.upload == false) {   //上传失败
                        $('#img_face, #img_back').attr("src","");
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
              }
            },
            error: function (xhr) {  //上传失败
                if(xhr.responseText){
                  
                    //files2.html(xhr.responseText); //返回失败信息
                    location.reload();
                }
            }
         });
    });

//中国区审核身份证专用
    $("#cardVerifyBtn").click(function(){
        var real_name = $.trim($('#real_name').text());
        var id_card_num = $.trim($('#id_card_num_input').val());

        if(real_name == "") {
            layer.msg('您未填写您的真实姓名!');
        }
        //身份证正则表达式验证  同框验证中国大陆区或台湾地区身份证号码
        var landReg=/^[1-9]{1}[0-9]{14}$|^[1-9]{1}[0-9]{16}([0-9]|[xX])$/;   //正则中国大陆区
        var taiwanReg = /^[a-zA-Z][0-9]{9}$/;   //正则中国台湾地区
        var lang = $.cookie('curLan');   //获取语言类型
        var landFlag = landReg.test(id_card_num);
        var taiwanFlag = taiwanReg.test(id_card_num);   //是否是台湾身份证标识

        if(!landFlag && !taiwanFlag) {
            if(lang == 'english') {
                msg = 'ID card number entered is not valid!';
            } else if(lang == 'zh') {
                msg = '您填写的不是有效的身份证号码!';
            } else if(lang == 'hk') {
                msg = '您填寫的不是有效的身份證號碼';
            } else if(lang == 'kr') {
                msg = '입력하신 등록증번호는 무효된 등록증번호입니다';
            } else {
                msg = 'ID card number entered is not valid!';
            }
            layer.msg(msg);
            return;
        }
        
        var taiwanVal;
        if(taiwanFlag) {
           taiwanVal = 1;
        } else {
           taiwanVal = 0;
        }
        

        //输入的身份证号码已经在数据库中通过审核
        if(cardPassExit) {
            if(lang == 'english') {
                msg = 'Government ID number already existed!';
            } else if(lang == 'zh') {
                msg = '您输入的身份证号码已经存在!';
            } else if(lang == 'hk') {
                msg = '您輸入的身份證號碼已經存在!';
            } else if(lang == 'kr') {
                msg = '등록증이 이미 존재하여 있습니다.';
            } else {
                msg = 'Government ID number already existed!';
            }
            $('#msg_id_card_num').text('');
            layer.msg(msg);
            return;
        }
        
        if($('#scan_face').attr('src') == ""  || $('#scan_back').attr('src') == "") {

            if(lang == 'english') {
                msg = 'Please upload both front and back of ID card!';
            } else if(lang == 'zh') {
                msg = '请您上传您的身份证的正面和背面,两者缺一不可!';
            } else if(lang == 'hk') {
                msg = '請您上傳您的身份證的正面和背面,兩者缺壹不可!';
            } else if(lang == 'kr') {
                msg = '등록증 정면과 뒤면의 이미지를 모두 업로드하셔야 합니다. 하나만 업로드하시면 무효입니다';
            } else {
                msg = 'Please upload both front and back of ID card!';
            }

            layer.msg(msg);
            return;
        }


        //console.log(real_name);  console.log(id_card_num);

        $("#card_upload").ajaxSubmit({
            dataType: 'json', //数据格式为json
            data: {  'real_name':real_name, 'id_card_num':id_card_num,'taiwanFlag':taiwanVal },

            beforeSend: function () { //开始上传
                if(taiwanFlag) {
                    $('#card_popup_layer').find("div").eq(1).remove();
                }
                //最初自己写的遮罩层
                $('#card_mask_layer').show();
                $('#card_popup_layer').show();

            },
            uploadProgress: function (event, position, total, percentComplete) {

            },
            success: function (data) { //上传身份证图片返回成功
                if(data.success == 1){   //审核成功
                    $('#card_popup_layer').html(data.msg);
                } else if(data.success == 2) {   //审核失败
                    $('#card_popup_layer').css({width:"400px",height:"435px"});
                    $('#card_popup_layer').html(data.msg);

                } else if(data.success == 3) {   //审核超过3次 转人工审核
                    $('#card_popup_layer').css({width:"500px"});
                    $('#card_popup_layer').html(data.msg);
                } else if(data.success == 0) {   //上传图片类型不符合要求和大小超过限制 或者转存图片服务器失败
                    $('#card_popup_layer').html('<div style="text-align: center; padding:10px 0;box-sizing:border-box;"><img src="../img/card/caution.png"/></div><div style="text-align: center;font-size:16px;font-family:微软雅黑;padding:0 80px;line-height:25px; box-sizing:border-box;">'+data.msg+'</div><div style="margin-top:20px; font-size:16px; text-align:center; font-family:微软雅黑;"><div style="margin-top:15px; text-align:center;"><button  style="width:100px;height:35px;border:1px solid #999;border-radius:6px;background:#fff;margin-top:15px;box-sizing:border-box;" onclick="confirm_card()"  type="button">'+data.confirm+'</button></div>');
                } else {   //审核功能维护中
                    $('#card_popup_layer').html(data.msg);
                }
            },
            error: function (xhr) { //上传失败
                if(xhr.responseText){

                    // btn.html("上传失败");
                    //files2.html(xhr.responseText); //返回失败信息
                    $('#card_mask_layer').hide();
                    $('#card_popup_layer').hide();
                    var msg ="上传失败";
                    layer.msg(msg);
                    //location.reload();
                }
            }
        });
    });



    var timeoutName_input;
    $('#name_input').bind('input propertychange', function () {
        clearTimeout(timeoutName_input);
        timeoutName_input = setTimeout(function () {
            var curVal = $('#name_input').val();
            $.ajax({
                type: "POST",
                url: "/ucenter/account_info/saveName",
                data: {curVal: curVal},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $('#msg_name').text('');
                    }else{
                        $('#msg_name').text(res.msg);
                    }
                }
            });
        }, 500);
    });

    var timeoutAddr_input;
    $('#addr_input').bind('input propertychange', function () {
        clearTimeout(timeoutAddr_input);
        timeoutAddr_input = setTimeout(function () {
            var curVal = $('#addr_input').val();
            $.ajax({
                type: "POST",
                url: "/ucenter/account_info/saveAddr",
                data: {curVal: curVal},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $('#msg_addr').text('');
                    }else{
                        $('#msg_addr').text(res.msg);
                    }
                }
            });
        }, 500);
    });

   

    $('#id_card_num_input').blur(function(){
        var idCardNum =  $.trim($('#id_card_num_input').val());
        if(idCardNum =="") {
            return;
        }
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/save_id_card_num",
            data: {idCardNum: idCardNum},
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    cardPassExit = false;
                    $('#msg_id_card_num').text('');
                }else{
                    cardPassExit = true;
                    $('#msg_id_card_num').text(res.msg);
                }
            }
        });
    });

    var timeoutSaveMobile;
    $('#mobile_input').bind('input propertychange', function () {
        clearTimeout(timeoutSaveMobile);
        timeoutSaveMobile = setTimeout(function () {
            var mobileVal = $('#mobile_input').val();
            $.ajax({
                type: "POST",
                url: "/ucenter/account_info/saveMobile",
                data: {mobileVal: mobileVal},
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        $('#mobileMsg').text('');
                    }else{
                        $('#mobileMsg').text(res.msg);
                    }
                }
            });
        }, 500);
    });
    /*
     $('.get_captcha').click(function(){
     var email_or_phone = $('input[name="email"]').val();
     $(".get_captcha").attr('disabled',true);
     $(".get_captcha").css('background','#cccccc');
     add(60);
     $.ajax({
     type: "POST",
     url: "/register/add_captcha",
     data: {email_or_phone:email_or_phone,reg_type:2,action_id:4},
     dataType: "json",
     success: function (data) {
     if(data.success){

     }else{
     clearTimeout(t);
     if(data.msg){
     layer.msg(data.msg);
     }else{

     layer.msg(data.checkResult.email.msg);
     }
     }
     }
     });

     });
     */
    $('.get_captcha').click(function(){
        var email_or_phone = $('input[name="email"]').val();
        var regi_emails_or_phone = $('input[name="regi_emails"]').val();
        $(".get_captcha").attr('disabled',true);
        $.ajax({
            type: "POST",
            url: "/register/bindEmail",
            data: {email_or_phone:email_or_phone,regi_emails_or_phone:regi_emails_or_phone,reg_type:2,action_id:4},
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


    $('.get_captcha2').click(function(){
        var email_or_phone = $('input[name="phone"]').val();
        $(".get_captcha2").attr('disabled',true);
        $(".get_captcha2").css('background','#cccccc');
        add2(60);
        $.ajax({
            type: "POST",
            url: "/register/add_captcha",
            data: {email_or_phone:email_or_phone,reg_type:2,action_id:4},
            dataType: "json",
            success: function (data) {
                if(data.success){

                }else{
                    if(data.msg){
                        layer.msg(data.msg);
                    }else{

                        layer.msg(data.checkResult.email.msg);
                    }
                }
            }
        });

    });

    //修改号码
    $('.get_captcha_modify_number').click(function(){
        var email_or_phone = $('.modify_phone').val();
        if(email_or_phone != ''){
            $.ajax({
                type: "POST",
                url: "/register/add_captcha",
                data: {email_or_phone:email_or_phone,reg_type:2,action_id:4},
                dataType: "json",
                success: function (data) {
                    if(data.success){
                        $(".get_captcha_modify_number").attr('disabled',true).css('background','#cccccc');
                        add3(60);
                    }else{
                        if(data.msg){
                            layer.msg(data.msg);
                        }else{
                            layer.msg(data.checkResult.email.msg);
                        }
                    }
                }
            });
        }
    });

    $('#binding_info_btn').click(function(){
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/binding_user_info",
            data:$('#binding_info_form').serialize(),
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

});
function enableMemLevel(){
    $.ajax({
        type: "POST",
        url: "/ucenter/account_info/checkMemAuth",
        dataType: "json",
        success: function (res) {
            if (res.success) {
                chooseLevel();
                $('#myModal').modal();
            } else {
                $('#enable_level_btn_msg').text(res.msg);
            }
        }
    });
}
function setAgree() {
    if (!$("input:checkbox[name='agree']").is(':checked')) {
        $('#msg_agree').html('× '+ $('#agreeMsg').val());
    } else {
        $('#msg_agree').html('');
    }
}
function chooseLevel() {
    payment = $("[name='payment_method']").filter(":checked").val();

    $.post("/ucenter/account_info/getEnableCash", {payment : payment},
        function(data){
            if(data['success']){
                $('#enable_level_amount').val(data.month_fee.money);
                $('#format_enable_level_amount').html(data.month_fee.format_money );
            }
        },'json');

    if (payment === 'USD') {
        $('#go_payment').attr('action', '/ucenter/paypal/do_paypal');
    } else if (payment === 'CNY') {
        $('#go_payment').attr('action', '/ucenter/pay/do_alipay');
    }

}

/**
 * 申请ewallet
 */
function apply_ewallet(){
    $.ajax({
        type: "POST",
        url: "/ucenter/account_info/apply_ewallet",
        dataType: "json",
        success: function (res) {
            if (res.success) {
                $('#ewallet_modal').modal();
            } else {
                $('#apply_ewallet_msg').text('× '+res.msg);
                setTimeout(function () {
                    $('#apply_ewallet_msg').text('');
                }, 3000);
            }
        }
    });
}

/**
 * 绑定手机号码
 */
function binding_info(){
    $('#binding_info').modal({backdrop: 'static', keyboard: false});
}
/**
 * 修改手机号
 */
function modify_mobile_number(){
    $('#modify_mobile_number').modal({backdrop: 'static', keyboard: false});
}
/**
 * 绑定邮箱
 */
function binding_email(){
    $('#binding_info_email').modal();
}

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

function add2(timerc) {
    if (timerc > 1) {
        --timerc;
        $(".get_captcha2").val($('#resend_captcha').val()+ timerc + 's');
        setTimeout("add2("+timerc+")", 1000);
    } else {
        $(".get_captcha2").val($('#get_captcha').val());
        $(".get_captcha2").attr('disabled',false);
        $(".get_captcha2").css('background','#f7f7f7');
    }
}

function add3(timerc) {
    if (timerc > 1) {
        --timerc;
        $(".get_captcha_modify_number").val($('#resend_captcha').val()+ timerc + 's');
        setTimeout("add3("+timerc+")", 1000);
    } else {
        $(".get_captcha_modify_number").val($('#get_captcha').val()).attr('disabled',false).css('background','#f7f7f7');
    }
}


function add_v2(timerc) {
    if (timerc > 1) {
        --timerc;
        $(".get_mobile_code").val($('#resend_captcha').val()+ timerc + 's');
        t=setTimeout("add_v2("+timerc+")", 1000);
    } else {
        $(".get_mobile_code").val($('#get_captcha').val());
        $(".get_mobile_code").attr('disabled',false);
        $(".get_mobile_code").css('background','#f7f7f7');
    }
}

function add_modify_old(timerc) {
    if (timerc > 1) {
        --timerc;
        $(".get_mobile_code_old_phone").val($('#resend_captcha').val()+ timerc + 's');
        t=setTimeout("add_modify_old("+timerc+")", 1000);
    } else {
        $(".get_mobile_code_old_phone").val($('#get_captcha').val());
        $(".get_mobile_code_old_phone").attr('disabled',false);
        $(".get_mobile_code_old_phone").css('background','#f7f7f7');
    }
}

function add_modify_new(timerc) {
    if (timerc > 1) {
        --timerc;
        $(".get_mobile_code_old_phone_new").val($('#resend_captcha').val()+ timerc + 's');
        t=setTimeout("add_modify_new("+timerc+")", 1000);
    } else {
        $(".get_mobile_code_old_phone_new").val($('#get_captcha').val());
        $(".get_mobile_code_old_phone_new").attr('disabled',false);
        $(".get_mobile_code_old_phone_new").css('background','#f7f7f7');
    }
}

function modify_mobile_bind()
{
    $('#modify_mobile_bind').modal({backdrop: 'static', keyboard: false});
}


//m by brady.wang 2017/1/6 绑定 换绑手机号
$(function(){

    //发送验证码
    var front_verify_flag = true;
    //绑定手机号发送短信
    $(".get_mobile_code").click(function(){
        var phone = $.trim($("input[name='binding_mobile_phone']").val());

        //验证手机号不能为空
        //前端是否验证
        if (front_verify_flag == true) {
            if (phone.length == 0) {
                layer.msg(account_info.msg.please_input_mobile);
                return false;
            }
            if(!(/^1[34578]\d{9}$/.test(phone))){
                layer.msg(account_info.msg.mobile_format_error);
                return false;
            }
        }
        var data = {};
        data.mobile = phone;
        data.type = "bind_mobile";
        //发送验证码 前进行倒计时
        $(".get_mobile_code").attr('disabled',true).css('background','#cccccc');
        add_v2(60);
        $.ajax({
            type: "POST",
            url: "mobile/get_mobile_code",
            data:data,
            dataType: "json",
            success: function (res) {
                if (res.error == false) {
                    //发送成功 显示发送的消息
                    var html = account_info.msg.code_has_sent_to +'<span style="color:red;">'+phone+'</span>，'+account_info.msg.please_check_out+'。<span style="color:#0000ff" class="not_receive_code" id="error_msg"> '+account_info.msg.not_receive_code+'</span>';
                    $(".code_message").html(html)
                    $(".code_message_place").css('display','none');
                    $(".code_message").css('display','block');
                } else{
                    layer.msg(res.message);
                    $(".code_message_place").css('display','block');
                    $(".code_message").css('display','none');
                    $(".get_mobile_code").val($('#get_captcha').val());
                    $(".get_mobile_code").attr('disabled',false);
                    $(".get_mobile_code").css('background','#f7f7f7');
                    clearTimeout(t);
                }
            }
        });

    })

    //绑定手机号
    $('#binding_mobile_btn').click(function(){
        //数据验证
        var phone = $.trim($("input[name='binding_mobile_phone']").val());
        var verify_code =  $.trim($("input[name='binding_mobile_code']").val());

        if (front_verify_flag == true) {
            if (phone.length <= 0) {
                layer.msg(account_info.msg.please_input_mobile);
                return false;
            }
            if(!(/^1[34578]\d{9}$/.test(phone))){
                layer.msg(account_info.msg.mobile_format_error);
                return false;
            }
            if (verify_code.length == 0 ) {
                layer.msg(account_info.msg.please_input_code);
                return false;
            }

        }

        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/binding_user_mobile",
            data:$('#binding_info_form').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.error == false) {
                    curEle.html(oldSubVal);
                    curEle.attr("disabled", false);
                    layer.msg(account_info.msg.bind_success);
                    $('#binding_info').modal('hide');
                    location.reload()
                } else {
                    curEle.html(oldSubVal);
                    curEle.attr("disabled", false);
                    layer.msg(res.message);
                }
            }
        });

    });

    //老手机号码发送
    $(".get_mobile_code_old_phone").click(function(){
        var phone = $.trim($(".old_mobile_num ").html());

        var is_verify_old_phone = $("input[name='is_verify_old_phone']").val();
        //if (is_verify_old_phone != '1') {
        //      layer.msg(account_info.msg.please_verify_old_phone);
        //      return false;
        //}

        if (front_verify_flag == true) {
            if (phone.length == 0) {
                layer.msg(account_info.msg.please_input_mobile);
                return false;
            }
            if(!(/^1[34578]\d{9}$/.test(phone))){
                layer.msg(account_info.msg.mobile_format_error);
                return false;
            }
        }
        var data = {};
        data.mobile = phone;
        //发送验证码 前进行倒计时
        $(".get_mobile_code_old_phone").attr('disabled',true).css('background','#cccccc');
        add_modify_old(60);
        $.ajax({
            type: "POST",
            url: "mobile/get_mobile_code",
            data:data,
            dataType: "json",
            success: function (res) {
                if (res.error == false) {
                    //发送成功 显示发送的消息
                    var html = account_info.msg.code_has_sent_to +'<span style="color:red;">'+phone+'</span>，'+account_info.msg.please_check_out+'。<span style="color:#0000ff" class="not_receive_code"> '+account_info.msg.not_receive_code+'</span>';
                    $(".code_message").html(html)
                    $(".code_message_place").css('display','none');
                    $(".code_message").css('display','block');
                } else{
                    layer.msg(res.message);
                    $(".code_message_place").css('display','block');
                    $(".code_message").css('display','none');
                    $(".get_mobile_code_old_phone").val($('#get_captcha').val());
                    $(".get_mobile_code_old_phone").attr('disabled',false);
                    $(".get_mobile_code_old_phone").css('background','#f7f7f7');
                    clearTimeout(t);
                }
            }
        });

    })

    //换绑验证老手机号
    $("#modify_mobile_bind_btn").click(function(){
        //数据验证
        var curEle = $(this);
        var phone = $.trim($(".old_mobile_num").html());
        var verify_code =  $.trim($("input[name='modify_mobile_bind_captcha']").val());

        if (front_verify_flag == true) {
            if (phone.length <= 0) {
                layer.msg(account_info.msg.please_input_mobile);
                return false;
            }
            if(!(/^1[34578]\d{9}$/.test(phone))){
                layer.msg(account_info.msg.mobile_format_error);
                return false;
            }
            if (verify_code.length == 0 ) {
                layer.msg(account_info.msg.please_input_code);
                return false;
            }

        }

        var data = {};
        data.mobile = phone;
        data.code = verify_code;


        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");

        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/verify_old_mobile",
            data:data,
            dataType: "json",
            success: function (res) {
                curEle.html(oldSubVal);
                curEle.attr("disabled", false);
                if(res.error === false){
                    $("#new_mobile").css('display','block');
                    $("#old_mobile").css('display','none');
                    $(".code_message_place").css('display','block');
                    $(".code_message").css('display','none');
                    $(".modal-footer.new_mobile").css('display','block');
                    $(".modal-footer.old_mobile").css('display','none');
                    $("input[name='is_verify_old_phone']").val(1);
                }else{
                    layer.msg(res.message);
                }
            }
        });

    })

    //换绑验证新手机号码
    $(".get_mobile_code_old_phone_new").click(function(){
        var phone = $.trim($("input[name='modify_mobile_bind_phone_new']").val());
        var is_verify_old_phone = $("input[name='is_verify_old_phone']").val();
        if (is_verify_old_phone != '1') {
            layer.msg(account_info.msg.please_verify_old_phone);
            return false;
        }

        var old_phone = $.trim($(".old_mobile_num").html());
        if (old_phone == phone) {
            layer.msg(account_info.msg.mobile_can_not_same);
            return false;
        }
        //验证手机号不能为空
        //前端是否验证
        if (front_verify_flag == true) {
            if (phone.length == 0) {
                layer.msg(account_info.msg.please_input_mobile);
                return false;
            }
            if(!(/^1[34578]\d{9}$/.test(phone))){
                layer.msg(account_info.msg.mobile_format_error);
                return false;
            }
        }
        var data = {};
        data.mobile = phone;
        data.type = "modify_mobile_bind";
        //发送验证码 前进行倒计时
        $(".get_mobile_code_old_phone_new").attr('disabled',true).css('background','#cccccc');
        add_modify_new(60);
        $.ajax({
            type: "POST",
            url: "mobile/get_mobile_code",
            data:data,
            dataType: "json",
            success: function (res) {
                if (res.error == false) {
                    //发送成功 显示发送的消息
                    var html = account_info.msg.code_has_sent_to +'<span style="color:red;">'+phone+'</span>，'+account_info.msg.please_check_out+'。<span style="color:#0000ff" class="not_receive_code" id="error_msg"> '+account_info.msg.not_receive_code+'</span>';

                    $(".code_message").html(html)
                    $(".code_message_place").css('display','none');
                    $(".code_message").css('display','block');
                } else{
                    clearTimeout(t);
                    layer.msg(res.message);
                    $(".code_message_place").css('display','block');
                    $(".code_message").css('display','none');
                    $(".get_mobile_code_old_phone_new").val($('#get_captcha').val());
                    $(".get_mobile_code_old_phone_new").attr('disabled',false);
                    $(".get_mobile_code_old_phone_new").css('background','#f7f7f7');
                }
            }
        });

    })
    //提交换绑
    $("#modify_mobile_bind_btn_new").click(function(){
        //数据验证
        var phone = $.trim($("input[name='modify_mobile_bind_phone_new']").val());
        var old_phone =  $.trim($("input[name='old_phone_number']").val());

        if (old_phone == phone) {
            layer.msg(account_info.msg.mobile_can_not_same);
            return false;
        }
        var verify_code =  $.trim($("input[name='modify_mobile_bind_captcha_new']").val());
        if (front_verify_flag == true) {
            if (phone.length <= 0) {
                layer.msg(account_info.msg.please_input_mobile);
                return false;
            }
            if(!(/^1[34578]\d{9}$/.test(phone))){
                layer.msg(account_info.msg.mobile_format_error);
                return false;
            }
            if (verify_code.length == 0 ) {
                layer.msg(account_info.msg.please_input_code);
                return false;
            }

        }

        var data = {};
        data.mobile = phone;
        data.code = verify_code;
        data.old_phone = old_phone;
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#loadingTxt').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/ucenter/account_info/modify_mobile_bind",
            data:data,
            dataType: "json",
            success: function (res) {
                curEle.html(oldSubVal);
                curEle.attr("disabled", false);
                if (res.error == false) {
                    layer.msg(account_info.msg.bind_success);
                    $('#myModalLabel').modal('hide');
                    location.reload()
                } else {
                    layer.msg(res.message);
                }
            }
        });


    })

})