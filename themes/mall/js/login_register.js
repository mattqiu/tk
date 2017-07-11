/**
 * Created by john on 15-8-1.
 *
 * 登录js内容
 *
 * leon 修改
 */


    $(document).ready(function(){


    	//登录按钮
    	$('.tps_reg_btn').click(function(){
    		var curEle = $(this);
            var oldSubVal = curEle.val();                          //登录按钮的值（登录）
            $(this).attr("value", $('#loadingTxt').val());         //设置按钮当前状态值（处理中）
            $(this).attr("disabled","disabled");                   //设置按钮的状态（不可点击）
            var oldColor = '#d22215';
            curEle.css('background','#cccccc');                    //设置当前按钮的样式（灰色）

            var redirect = request('redirect');
            var url = redirect ? redirect :'ucenter';

            var fp =$.trim($("#fp").val());
            fp = fp != ''  ? 'fp' : '';
            var captcha_code = $.trim($('#captcha').val());     //获取登陆图片验证码
            $.ajax({
                type: "POST",
                url: "/login/submit",
                data: {loginName:$('input[name="loginName"]').val(),pwdOriginal:$('input[name="pwdOriginal_login"]').val(),captcha:captcha_code},
                xhrFields: {
                    withCredentials: true
                },
                crossDomain: true,
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        var uid = data.userInfo.id;
                        $('.result_msg').text('');
                        if (fp != '') {
                        	 window.location.href = '/index/tps_summit_meeting';
                        } else {
                        	window.location.href = '/'+ url;
                        }

                    } else {
                        if(data.code_msg) {
                            $('.code_msg').text(data.code_msg);
                        } else {
                            $('.result_msg').text(data.msg);
                        }

                        curEle.attr("value", oldSubVal);
                        curEle.attr("disabled", false);
                        curEle.css('background',oldColor);
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
                $('.tps_reg_btn').click();
            }
        });

         $(".tps_input").focus(function(){
            $('.result_msg, .code_msg').text('');
        });

    })//$(function(){   end

    /**
     * 登录按钮
     * 获取网页传递的参数
     */
    function request(paras){
        var url = location.href;//获取当前的地址
        var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");//substring截取字符串，split 分割字符串为数组
        var paraObj = {}
        for (i=0; j=paraString[i]; i++){
            paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
        }
        var returnValue = paraObj[paras.toLowerCase()];
        if(typeof(returnValue)=="undefined"){
            return "";
        }else{
            return returnValue;
        }
    }

    function changeLoginCaptcha() {
    $('#img_captcha')[0].src = $('#img_captcha').attr('basesrc')+'?t='+Math.random();
   }
