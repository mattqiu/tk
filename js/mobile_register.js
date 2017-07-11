$(document).ready(function () {

    $("input[type='text'],input[type='password']").blur(function () {
        var curElement = $(this);
        var itemValPost = curElement.val();
        var pwdValPost = $('#pwd').val();
        var reg_type = $('input[name="reg_type"]').val();
        if(curElement.attr('name') == 'email' && $('#email_new').val()!= ''){
            var email_new = $('#email_new').val();
        }else{
            var email_new = $('#email').val();
        }
        $.ajax({
            type: "POST",
            url: "/register/checkRegisterItem",
            data: {itemName: curElement.attr("name"), itemVal: itemValPost, pwdVal: pwdValPost,email_new:email_new,reg_type:reg_type},
            dataType: "json",
            success: function (data) {
                $.each(data, function (itemName, checkResultVal) {
//                    displayFormItemCheckRes(itemName, checkResultVal);
                        $('#tishi').html(checkResultVal.msg);
                });
            }
        });
    });
    $("input[name='submit']").click(function () {
        var curEle = $(this);
        var oldSubVal = curEle.val();
        $(this).attr("value", $('#loadingTxt').val());
        $(this).attr("disabled","disabled");
        var oldColor = '#333157';
        curEle.css('background','#cccccc');

        var email = $("input[name='email']").val();
        var email_re = $("input[name='email_re']").val();
        var pwdOriginal = $("input[name='pwdOriginal']").val();
        var pwdOriginal_re = $("input[name='pwdOriginal_re']").val();
        var parent_id = $("input[name='parent_id']").val();
        var country_id = $("select[name='country_id']").val();
        var user_rank = $("select[name='user_rank']").val();
        var captcha = $("input[name='captcha']").val();
        var name = $("input[name='name']").val();
        var mobile = $("input[name='mobile']").val();
        var address = $("textarea[name='address']").val();
        var disclaimer = 1;
        var reg_type = $('input[name="reg_type"]').val();
            $.ajax({
                type: "POST",
                url: "/register/submit",
                data: {email: email, pwdOriginal: pwdOriginal, pwdOriginal_re: pwdOriginal_re,parent_id:parent_id,country_id:country_id,user_rank:user_rank,captcha:captcha,name:name,mobile:mobile,address:address,disclaimer:disclaimer,email_re:email_re,reg_type:reg_type},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                            window.location.href = '/mobile_reg_success';
                    } else {
                        if(!data.msg){
                            $.each(data.checkResult, function (itemName, checkResultVal) {
                               displayFormItemCheckRes(itemName, checkResultVal);
                            });
                            curEle.css('background',oldColor);
                            curEle.attr("value", oldSubVal);
                            curEle.attr("disabled", false);
                        }else{
                            layer.msg(data.msg);
                        }
                    }

                }
            });
        //}
    });

    $('.get_captcha').click(function(){
        var email_or_phone = $('input[name="email"]').val();
        var reg_type = $('input[name="reg_type"]').val();
        $(".get_captcha").attr('disabled',true);
                    $(".get_captcha").css('background','#cccccc');
                    add(60);
        $.ajax({
            type: "POST",
            url: "/register/add_captcha",
            data: {email_or_phone:email_or_phone,reg_type:reg_type},
            dataType: "json",
            success: function (data) {
                if(data.success){
                    
                }else{
                    clearTimeout(t);
                    $.each(data.checkResult, function (itemName, checkResultVal) {
                        displayFormItemCheckRes(itemName, checkResultVal);
                    });
                }
            }
        });
    });

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
function displayFormItemCheckRes(itemName, checkResultVal) {
    if(itemName == 'address'){
        curElement = $("textarea[name='" + itemName + "']");
    }else if(itemName!=='country_id'){
        curElement = $("input[name='" + itemName + "']");
    }else{
         curElement = $("select[name='" + itemName + "']");
    }
    if (checkResultVal.isRight === true) {
        curElement.css("border", "");
    } else {
       // curElement.css("border", "1px red solid");
        layer.msg(checkResultVal.msg);
        //alert(checkResultVal.msg);
    }
}

function selType(type,email,nr){
    $('input[name="reg_type"]').val(type);
    $('.btn-primary').attr('placeholder',nr);
    if(email){
        $('#email').attr('placeholder',email);
    }
}
