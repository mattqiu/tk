$(document).ready(function () {
    $('#member_num').text('0');
//    updateMemberNum();

    $(".successtext .re_enable_mail").click(function () {
        $('#re_send_email_msg').text('');
        var curEle = $(this);
        var oldSubVal = curEle.text();
        curEle.html($('#sending').val());
        curEle.attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: "/common/resendEnableMail",
            dataType: "json",
            success: function (res) {
                curEle.html(oldSubVal);
                curEle.attr("disabled", false);
                if (res.success) {
                    $('#re_send_email_msg').text(res.msg);
                }else{
                    $('#re_send_email_msg').text(res.msg);
                }
            }
        });
    });

});


function updateMemberNum(){
    $.ajax({
        type: "POST",
        url: "/common/getCurMemNum",
        dataType: "json",
        success: function (res) {
            if(res.success){
                $('#member_num').text(res.data.num);
            }
            setTimeout("updateMemberNum()", 60000);
        }
    });
} 

function sendPwdResetMail() {
    var oldSubVal = $('#get_password_sub').text();
    $('#get_password_sub').text($('#loadingTxt').val());
    $('#get_password_sub').attr("disabled","disabled");
    email = $('#email').val();
    $.ajax({
        type: "POST",
        url: window.location.href+"/sendPwdResetMail",
        data: {email:email},
        dataType: "json",
        success: function (data) {
            if (data.success) {
                $('.cart_msg').removeClass('msg_error');
                $('.cart_msg').addClass('msg_success');
                $('.cart_msg img').attr('class','right_icon');
                $('.cart_msg .vam').html(data.msg);
                $('.cart_msg').removeClass('hidden');
                $('#get_password_sub').text(oldSubVal);
            } else {
                $('.cart_msg').addClass('msg_error');
                $('.cart_msg img').attr('class','error_icon');
                $('.cart_msg .vam').html(data.msg);
                $('.cart_msg').removeClass('hidden');
                $('#get_password_sub').text(oldSubVal);
                $('#get_password_sub').attr("disabled",false);
            }
        }
    });
}

function resetPwd(){
    var oldSubVal = $('#get_password_sub').text();
    $('#get_password_sub').text($('#loadingTxt').val());
    $('#get_password_sub').attr("disabled","disabled");
    newPwd = $('#newPwd').val();
    newPwdRe = $('#newPwdRe').val();
    $.ajax({
        type: "POST",
        url: window.location.href+"&ajax=resetPwd",
        data: {newPwd:newPwd,newPwdRe:newPwdRe},
        dataType: "json",
        success: function (data) {
            if (data.success) {
                $('.cart_msg').removeClass('msg_error');
                $('.cart_msg').addClass('msg_success');
                $('.cart_msg img').attr('class','right_icon');
                $('.cart_msg .vam').html(data.msg);
                $('.cart_msg').removeClass('hidden');
                $('#get_password_sub').text(oldSubVal);
            } else {
                $('.cart_msg').addClass('msg_error');
                $('.cart_msg img').attr('class','error_icon');
                $('.cart_msg .vam').html(data.msg);
                $('.cart_msg').removeClass('hidden');
                $('#get_password_sub').text(oldSubVal);
                $('#get_password_sub').attr("disabled",false);
            }
        }
    });
}

