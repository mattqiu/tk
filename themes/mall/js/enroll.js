/**
 * Created by leon on 2017/3/10.
 * 店铺升级界面js
 */
$(document).ready(function () {

	/**
     * 店铺加盟 
     * 输入框 失去焦点事件
     */
    $(".Enrollment input[type='text'],.Enrollment input[type='password']").blur(function(){
        var curElement = $(this);
        var itemName = curElement.attr('name');                  //字段名
        var itemVal = curElement.val();                          //字段值
        var account = $('input[name="emailmobile"]').val();      //邮箱 或者 手机号
		var reg_type = $('input[type="radio"]:checked').val();   //单选信息     已有TPS顾客账户 = 1     还没有TPS顾客账户  = 0

        $.ajax({
            type:'post',
            url:"/register/checkRegisterItemNew",
            data:{
                itemName: itemName,
                itemVal: itemVal,
                account: account,
                reg_type: reg_type
            },
            dataType:"json",
            success:function(data){
                $.each(data, function (itemName, checkResultVal) {
                    displayFormItemCheckRes(itemName, checkResultVal);
                });
            }
        })//ajax end
        
    })
    
    $('.itxt').focus(function(){
        $(this).next().next().empty();
    })

	/**
	 * 店铺加盟 
	 * 获取验证码的按钮
	 */
    $('.get_captcha').click(function(){
        var email_or_phone = $('input[name="emailmobile"]').val();//账号
        $(".get_captcha").attr('disabled',true);
        $(".get_captcha").css('background','#cccccc');
        add(60);
        $.ajax({
            type: "POST",
            url: "/register/send_captcha",
            data: {account:email_or_phone},
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

    /**
     * 店铺加盟 
     * 单选按钮的点击
     */
    $('input[type="radio"]').click(function(){
        if($(this).val() == 1){
            $('input[name="email_re"],input[name="pwdOriginal_re"]').parent().addClass('hidden');
        }else if ($(this).val() == 0){
            $('input[name="email_re"],input[name="pwdOriginal_re"]').parent().removeClass('hidden');
        }
    });

    /**
     * 店铺加盟  
     * 马上注册 按钮
     */
    $("input[name='submit']").click(function () {
        var curEle = $(this);                                             //按钮本身
        var oldSubVal = curEle.val();                                     //按钮名字
        $(this).attr("value", $('#loadingTxt').val());                    //修改按钮的值
        $(this).attr("disabled","disabled");                              //设置按钮属性
        var oldColor = '#d22215';
        curEle.css('background','#cccccc');

        var reg_type = $('input[type="radio"]:checked').val();           //单选信息     已有TPS顾客账户 = 1     还没有TPS顾客账户  = 0
        var emailmobile = $("input[name='emailmobile']").val();          //邮件
        var pwd = $("input[name='pwd']").val();                          //密码
        var parent_id = $("input[name='parent_id']").val();              //父级ID
        var captcha = $("input[name='captcha']").val();                  //验证码
        var disclaimer = $("input[name='disclaimer']:checked").val();    //确定信息     选中等于=1
        
        $.ajax({
            type: "POST",
            url: "/register/submit_register",
            data: {
                emailmobile: emailmobile, 
                pwd: pwd,
                parent_id: parent_id,
                captcha: captcha,
                disclaimer: disclaimer,
                reg_type: reg_type
            },
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
                        curEle.attr("disabled", false);
                        curEle.attr("value", oldSubVal);
                        curEle.css('background',oldColor);
                    }
                }
            }
        });
    });

});//$(document).ready(function () {   end


/**
 * 店铺加盟 
 * 获取验证码按钮触发的定时器
 */
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

/**
 * 店铺加盟 
 * 界面中文本框内容的提示信息
 */
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
            //curElement.next().next().next().children('img').attr("class", "right_icon");
            //curElement.next().next().next().children('img').css("vertical-align", "super");
        curElement.next().children('img').attr("class", "right_icon");
        
    } else {
        curElement.css("border", "1px red solid");
        if(itemName === 'disclaimer'){
            curElement.parent().next().children('img').attr("class", "error_icon");
        }else if(itemName == "email"){
	    	itemName = "emailmobile";
	        curElement = $("input[name='" + itemName + "']");
	        curElement.next().children('img').attr("class", "error_icon");
	    }else if(itemName == "mobile"){
	    	itemName = "emailmobile";
	        curElement = $("input[name='" + itemName + "']");
	        curElement.next().children('img').attr("class", "error_icon");
        }else{
            
            curElement.next().children('img').attr("class", "error_icon");
        }
    }

    if(itemName === 'disclaimer'){
        if(!checkResultVal.isRight) {
           curElement.parent().next().next().html(checkResultVal.msg);
        } else {
           curElement.parent().next().next().html("");
        }
        
    }else{
        if(!checkResultVal.isRight) {
         curElement.next().next().html(checkResultVal.msg);
        } else {
          curElement.next().next().html("");
        }
    }

}