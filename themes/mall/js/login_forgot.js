/**
 * 忘记密码 js 内容
 * leon 2017-01-06
 */

$(document).ready(function(){
	
	///*注册页面tab切换调用*/
    //tps_reg_tab(tps_reg_ui);

    ///*调用隐藏显示未收到验证码的内容切换*/
    //tps_msg_show(msg_ui,msg_content_ui);
    //tps_msg_show(msg_telui,msg_telcontent_ui);

    /**
     * 输入框 失去焦点事件
     */
    $(".register_email_mobile input[type='text'],.register_email_mobile input[type='password']").blur(function(){

        var curElement = $(this);
        var itemName = curElement.attr('name');      //字段名
        var itemVal = curElement.val();              //字段值

        //验证 验证码
        var account = "";
        if(itemName == 'email_captcha'){
        	account = $('input[name="email"]').val();//账号
        }else if(itemName == 'mobile_captcha'){
        	account = $('input[name="mobile"]').val();//账号
        }

        //判断两次密码是不是一样
        var pwd_judge = true;
        if(itemName == 'email_pwd' || itemName == 'email_pwd_again'){
            //pwd_judge = email_pwd_distinction(itemName,itemVal);
        }else if(itemName == 'mobile_pwd' || itemName == 'mobile_pwd_again'){
        	//pwd_judge = mobile_pwd_distinction(itemName,itemVal);
        }
        if(pwd_judge){
            $.ajax({
                type:'post',
                url:"/forgot_pwd/checkRegisterItemNew",
                data:{
                    itemName: itemName,
                    itemVal: itemVal,
                    account: account
                },
                dataType:"json",
                success:function(data){
                    $.each(data, function (itemName, checkResultVal) {
                        displayFormItemCheckRes(itemName, checkResultVal);
                    });
                }
            })//ajax end
        }
    })
    
    $('.tps_input').focus(function(){
        $(this).next().next().empty();
    });

    //邮箱发送验证码
    $('.email_send_captcha').click(function(){
        var curEle = $(this);
        //curEle.attr("disabled","disabled");       //按钮不可用
        curEle.hide();                              //按钮隐藏
        //curEle.css('background','#cccccc');       //设置背景颜色
        //email_add(60);                            //添加倒计时时间

        var account = $('#email').val();          //获取邮箱信息
        $.ajax({
            type:'post',
            url:'/forgot_pwd/send_captcha',
            data:{account:account},
            dataType:'json',
            success:function(data){
                if(data.success == 1){
                    curEle.hide();                                      //隐藏按钮
                    $('.email_send_captcha_info').html(account);        //提示信息中的发送号码
                    $('.email_send_captcha_on').show();                 //显示提示信息

                    //取消验证码的错误信息
                    $('.email_send_captcha_on_show').hide();
                    $('#email_captcha').css("border", "");
                }else{
                    curEle.show();                                      //显示按钮
                    //$(".email_send_captcha").attr('disabled',false);    //按钮回复可用
                    $('.email_send_captcha_info').html(account);        //提示信息中的发送邮箱
                    $('.email_send_captcha_on').show();                 //显示提示信息

                    //取消验证码的错误信息
                    $('.email_send_captcha_on_show').hide();
                    $('#email_captcha').css("border", "");
                }
            }
        })
    })

    //    })
    //}

    //手机发送验证码
    $('.mobile_send_captcha').click(function(){
    	var curEle = $(this);
        //curEle.attr("disabled","disabled");   //按钮不可用
        curEle.hide();
        //curEle.css('background','#cccccc'); //设置背景颜色
        //mobile_add(60);                     //添加倒计时时间

        var account = $('#mobile').val();     //获取邮箱信息
        $.ajax({
            type:'post',
            url:'/forgot_pwd/send_captcha',
            data:{account:account},
            dataType:'json',
            success:function(data){
                if(data.success == 1){
                    curEle.hide();                                      //隐藏按钮
                	$('.mobile_send_captcha_info').html(account);
                    $('.mobile_send_captcha_on').show();

                    //取消验证码的错误信息
                    $('.mobile_send_captcha_on_show').hide();
                    $('#mobile_captcha').css("border", "");
                }else{
                    curEle.show();                                      //显示按钮
                    //$(".mobile_send_captcha").attr('disabled',false);   //按钮回复可用
                    $('.mobile_send_captcha_info').html(account);       //提示信息中的发送邮箱
                    $('.mobile_send_captcha_on').show();                //显示提示信息

                    //取消验证码的错误信息
                    $('.mobile_send_captcha_on_show').hide();
                    $('#mobile_captcha').css("border", "");
                }
            }
        })
    })

    //邮箱提交
    $('.email_submit_button').click(function(){
    	var curEle = $(this);
        var oldSubVal = curEle.val();                          //按钮的名字
        $(this).attr("value", $('#loadingTxt').val());         //设置按钮名称
        $(this).attr("disabled","disabled");                   //设置按钮不可用
        curEle.css('background','#cccccc');                    //设置按钮颜色为灰色
        var oldColor = '#c80606';                              //颜色
        $.ajax({
            type: "POST",
            url: "/forgot_pwd/submit_register",
            data: $('.register_email').serialize(),
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    window.location.href = '/login';
                } else {
                    $.each(data.checkResult, function (itemName, checkResultVal) {
                        if(itemName == 'email'){
                            var itemName = itemName;
                        }else{
                            var itemName = 'email_'+itemName;
                        }
                        displayFormItemCheckRes(itemName, checkResultVal);
                    });
                    curEle.css('background',oldColor);       //设置按钮背景颜色
                    curEle.attr("value", oldSubVal);         //回复按钮名称
                    curEle.attr("disabled", false);          //按钮回复可以使用
                }
            }
        });
    })

    //手机号提交
    $('.mobile_submit_button').click(function(){
    	var curEle = $(this);
        var oldSubVal = curEle.val();                          //按钮的名字
        $(this).attr("value", $('#loadingTxt').val());         //设置按钮名称
        $(this).attr("disabled","disabled");                   //设置按钮不可用
        curEle.css('background','#cccccc');                    //设置按钮颜色为灰色
        var oldColor = '#c80606';                              //颜色
        $.ajax({
            type: "POST",
            url: "/forgot_pwd/submit_register",
            data: $('.register_mobile').serialize(),
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    window.location.href = '/login';
                } else {
                    $.each(data.checkResult, function (itemName, checkResultVal) {
                        if(itemName == 'mobile'){
                            var itemName = itemName;
                        }else{
                            var itemName = 'mobile_'+itemName;
                        }
                        displayFormItemCheckRes(itemName, checkResultVal);
                    });
                    curEle.css('background',oldColor);       //设置按钮背景颜色
                    curEle.attr("value", oldSubVal);         //回复按钮名称
                    curEle.attr("disabled", false);          //按钮回复可以使用
                }
            }
        });
    })

    /**
     * 回车事件
     */
    $(window).keydown(function(e) {
        var a = e||window.event
        if (a.keyCode == '13') {//keyCode=13是回车键
            var mail_ul = $('#mail_ul').css('display');//邮件
            var tel_ul = $('#tel_ul').css('display');  //电话
            if(mail_ul == 'block'){
                //alert("邮件显示");
                $('.email_submit_button').click();
            }
            if(tel_ul == 'block'){
                //alert("电话显示");
                $('.mobile_submit_button').click();
            }
        }
    });

})

/**
 * 内容验证
 * leon 新增
 */
function displayFormItemCheckRes(itemName, checkResultVal) {
    curElement = $("input[name='" + itemName + "']");
    if (checkResultVal.isRight === true) {
        curElement.css("border", "");
        curElement.next().children('img').attr("class", "right_icon");
        //curElement.next().next().html(checkResultVal.msg).removeClass('c-o');//错误提示信息
        //显示验证码框
        if(itemName == 'email'){
        	$('.emailCaptcha').show();
        }else if(itemName == 'mobile'){
        	$('.mobileCaptcha').show();
        }

        //显示验证码的提示信息
        if(itemName == 'email_captcha'){
            $('.email_send_captcha_on').show(); //显示验证码没有发送成功的提示信息
        }else if(itemName == 'mobile_captcha'){
            $('.mobile_send_captcha_on').show();//显示验证码没有发送成功的提示信息
        }
    } else {
        curElement.css("border", "1px red solid");
        //curElement.next().children('img').attr("class", "error_icon");
        //curElement.next().next().html(checkResultVal.msg).addClass('c-o');//错误提示信息

        //判断验证码的提示信息
        if(itemName == 'email_captcha'){
            $('.email_send_captcha_on_show').show();                                  //显示 错误提示信息
            $('.email_send_captcha_on').hide();                                       //隐藏验证码没有发送成功的提示信息
            $('.email_send_captcha_on_show').html(checkResultVal.msg).addClass('c-o');//设置错误提示信息内容
        }else if(itemName == 'mobile_captcha'){
            $('.mobile_send_captcha_on_show').show();                                  //显示 错误提示信息
            $('.mobile_send_captcha_on').hide();                                       //隐藏验证码没有发送成功的提示信息
            $('.mobile_send_captcha_on_show').html(checkResultVal.msg).addClass('c-o');//设置错误提示信息内容
        }else if(itemName == 'mobile_compare_pwd'){                
            $('#mobile_compare_pwd_tip').html(checkResultVal.msg).addClass('c-o');//设置错误提示信息内容
        }else if(itemName == 'email_compare_pwd'){
            $('#email_compare_pwd_tip').html(checkResultVal.msg).addClass('c-o');//设置错误提示信息内容
        } else{
            curElement.next().children('img').attr("class", "error_icon");
            curElement.next().next().html(checkResultVal.msg).addClass('c-o');//错误提示信息
        }

        //隐藏验证码框
        if(itemName == 'email'){
        	$('.emailCaptcha').hide();
        }else if(itemName == 'mobile'){
        	$('.mobileCaptcha').hide();
        }
    }
}

/**
 * 获取验证码
 * 添加定时时间
 */
function email_add(timerc) {
    if (timerc > 1) {
        --timerc;
        $(".email_send_captcha").val($('#resend_captcha').val()+ timerc + 's');
        t=setTimeout("email_add("+timerc+")", 1000);
    } else {
        $(".email_send_captcha").val($('#get_captcha').val());
        $(".email_send_captcha").attr('disabled',false);
        $(".email_send_captcha").css('background','#666');
    }
}
/**
 * 获取验证码
 * 添加定时时间
 */
function mobile_add(timerc) {
    if (timerc > 1) {
        --timerc;
        $(".mobile_send_captcha").val($('#resend_captcha').val()+ timerc + 's');
        t=setTimeout("mobile_add("+timerc+")", 1000);
    } else {
        $(".mobile_send_captcha").val($('#get_captcha').val());
        $(".mobile_send_captcha").attr('disabled',false);
        $(".mobile_send_captcha").css('background','#666');
    }
}



///**
// * 功能：注册、密码找回tab切换
// * params:id
// * */
//function tps_reg_tab(id){
//    //通过id找到下面的li进行循环
//    $(id).find("li").each(function(){
//        //当前对象进行点击事件
//        $(this).on("click",function(){
//            //获取当前对象的属性值
//            var tab = $(this).attr("tab");
//            //给当前选中的对象加上激活的样式，其他同级元素删除激活样式
//            $(this).addClass("active").siblings().removeClass("active");
//            //匹配和选择中的对象的内容显示出来，其它隐藏
//            $(tab).show().siblings().hide();
//        })
//    })
//}
//
///**
// * 功能：隐藏显示未收到验证码的内容
// * params:pid,cid
// * */
//function tps_msg_show(pid,cid){
//    $(pid).mouseover(function(){
//        $(cid).fadeIn();
//    });
//    $(pid).mouseout(function(){
//        $(cid).fadeOut();
//    });
//}

