<style>
    .hidden{
        display: none;
    }
</style>
<div class="login_main">
    <div id="loginwrap1">
        <div class="login_bg">
            <form action="" method="post" id="login_form" name="login_form">
            <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
            <div class="maintext">
                <div class="toptitle"><h3><?php echo lang('login_welcome') ?><span><a href="<?php echo base_url('register')?>"><?php echo lang('nav_register') ?></a></span></h3></div>
                    <div class="inputcss"><input type="text" name="loginName" class="i_css" notice="<?php echo lang('login_name') ?>" value="<?php echo lang('login_name') ?>">
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                    </div>
                    <div class="inputcss1">
                        <input type="text" name="pwdOriginalText" notice="<?php echo lang('regi_pwd') ?>" class="i_css" value="<?php echo lang('regi_pwd') ?>">
                        <input type="password" id="pwd" name="pwdOriginal" notice="<?php echo lang('regi_pwd') ?>" class="i_css hidden" value="">
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/correct.jpg') ?>"></span>
                        <span class="hidden" style="position:relative;top:5px;"><img src="<?php echo base_url('img/new/wrong.jpg') ?>"></span>
                        <p class="msg" style="text-align: left;
                        font-size: 12px;
                        font-family: Arial,Helvetica,sans-serif;
                        color: #EA5A5A;
                        margin-top: 15px;"></p>
                    </div>

                    <div class="subcss"><input autocomplete="off" type="button" name="submit" class="s_css" value="<?php echo lang('login') ?>"></div>
                    <div class="bottext"><h3>
                            <!-- <span style="float: left;">
                                <a href="<?php echo base_url('inactive')?>"><?php echo lang('account_inactive')?></a>
                            </span> -->
                            <span>
                                <a href="<?php echo base_url('forgot_pwd')?>"><?php echo lang('login_forgot_pwd');?>?</a>
                            </span>
                        </h3></div>

            </div>
            </form>

        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#loginwrap1 input').focus(function () {
        if($(this).val()===$(this).attr('notice')){
            $(this).val('');
        }
        if($(this).attr('name')==='pwdOriginalText' || $(this).attr('name')==='pwdOriginal_reText'){
            $(this).hide();
            $(this).next().show().focus();
        }
    });
    $("#loginwrap1 input,select").blur(function () {
        var curElement = $(this);
        if(!curElement.val()){
            if ($(this).attr('name') === 'pwdOriginal' || $(this).attr('name') === 'pwdOriginal_re') {
                $(this).hide();
                $(this).prev().show();
            }
            curElement.val(curElement.attr('notice'));
        }
    });
    $("body").keydown(function(e) {
        var a = e||window.event
        if (a.keyCode == '13') {//keyCode=13是回车键
            $("input[name='submit']").click();
        }
    });

});

/*--获取网页传递的参数--*/
function request(paras)
{
    var url = location.href;
    var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
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
</script>