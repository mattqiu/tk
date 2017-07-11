$(document).ready(function () {

    $('.guojia').change(function(){
        if(this.value == '3'){
            $('.korea_address').removeClass('hidden');
        }else{
            $('.korea_address').addClass('hidden');
        }
    });

    $(".Enrollment input[type='text'],input[type='password']").blur(function () {
        var curElement = $(this);
        var itemValPost = curElement.val();
        var pwdValPost = $('#pwd').val();
        var reg_type = $('input[type="radio"]:checked').val();
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
                    displayFormItemCheckRes(itemName, checkResultVal);
                });
            }
        });
    });
    $("input[name='submit']").click(function () {
        /****************/
        var t=$("input[name='email']").val();//这个就是我们要判断的值了
                if(isNaN(t)){
                    if($("#youxs").css("display")=='block'){
                        if($("#youxs .msg img").attr("class")=='right_icon'){

                        }else{
                            return false;
                        }
                    }
                }
        /*****************/
        var curEle = $(this);
        var oldSubVal = curEle.val();
        $(this).attr("value", $('#loadingTxt').val());
        $(this).attr("disabled","disabled");
        var oldColor = '#d22215'/*curEle.css('background-color')*/;
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
        var disclaimer = $("input[name='disclaimer']:checked").val();
        var reg_type = $('input[type="radio"]:checked').val();
            $.ajax({
                type: "POST",
                url: "/register/submit",
                data: {email: email, pwdOriginal: pwdOriginal, pwdOriginal_re: pwdOriginal_re,parent_id:parent_id,country_id:country_id,user_rank:user_rank,captcha:captcha,name:name,mobile:mobile,address:address,disclaimer:disclaimer,email_re:email_re,reg_type:reg_type},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        if(data.jumpUrl){
                            window.location.href = data.jumpUrl;
                        }else{
                            window.location.href = '/reg_ok';
                        }
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

    $('input[type="radio"]').click(function(){

        if($(this).val() == 1){
            $('input[name="email_re"],input[name="pwdOriginal_re"]').parent().addClass('hidden');
        }else if ($(this).val() == 0){
            $('input[name="email_re"],input[name="pwdOriginal_re"]').parent().removeClass('hidden');
        }
    });

    $('.get_captcha').click(function(){
        var email_or_phone = $('input[name="email"]').val();
        var reg_type = $('input[type="radio"]:checked').val();
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
        $(".get_captcha").css('background','#666');
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
        if(itemName === 'captcha'){
            curElement.next().next().next().children('img').attr("class", "right_icon");
            curElement.next().next().next().children('img').css("vertical-align", "super");
        }else{
            curElement.next().children('img').attr("class", "right_icon");
        }
    } else {
        curElement.css("border", "1px red solid");
        if(itemName === 'disclaimer'){
            curElement.parent().next().children('img').attr("class", "error_icon");
        }else{
            curElement.next().children('img').attr("class", "error_icon");
        }
    }

    if(itemName === 'disclaimer'){
        curElement.parent().next().next().html(checkResultVal.msg);
    }else{
        curElement.next().next().html(checkResultVal.msg);
    }



}
function changeCaptcha() {
    $('#captcha')[0].src = $('#captcha').attr('basesrc')+'?t='+Math.random();
}