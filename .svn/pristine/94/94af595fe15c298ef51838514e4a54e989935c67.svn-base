<?php 
/**
 * 忘记密码界面
 */
?>
<!--忘记密码 start-->
<div class="tps_reg">
    <div class="w1200 pr">
        <div class="tps_reg_box">
            <nav class="reg_nav" >
                <ul id="tps_reg_ui">
                    <?php if($login_language_id == '156'){?>
                        <li class="active" tab="#mail_ul" ><a href="javascript:void(0);"><?php echo lang('tps_email_find')?></a></li>
                        <li tab="#tel_ul"><a href="javascript:void(0);"><?php echo lang('tps_mobile_find')?></a></li>
                    <?php }else{?>
                        <li class="active" tab="#mail_ul"><a href="javascript:void(0);"><?php echo lang('tps_email_find')?></a></li>
                    <?php }?>
                </ul>
            </nav>
            
            <div class="register_email_mobile">
                <!-- 电子邮箱 -->
                <div class="mail_reg" id="mail_ul">
                    <form class="register_email">
                        <ul>
                            <li>
                                <input id="email" name="email" type="text" class="tps_input" autocomplete="off" placeholder="<?php echo lang('email_text') ?>"/><!-- 电子邮箱 -->
                                <span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                                <p class="font c-g"></p>
                            </li>

                            <li class="emailCaptcha d-n">
                                <!-- 验证码输入框 -->
                                <input id="email_captcha" name="email_captcha" type="text" class="tps_input" autocomplete="off" placeholder="<?php echo lang('captcha')?>"/>
                                <!-- 发送验证码按钮 -->
                                <input type='button' class="email_send_captcha email_send_captcha1" value="<?php echo lang('tps_get_captcha');?>">
                                <p class="font email_send_captcha_on_show d-n"></p>
                                <p class="font email_send_captcha_on d-n">
                                    <?php echo lang('tps_captcha_send')?>
                                    <span class="email_send_captcha_info"></span>
                                    <span class="c-b" id="msg_ui">. <?php echo lang('tps_not_received_captcha')?>？</span>
                                </p>
                            </li>

                            <li>
                                <input id="email_pwd" name="email_pwd" type="password" class="tps_input" maxlength="18" autocomplete="off" placeholder="<?php echo lang('tps_pwd_new'); ?>" />
                                <span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                                <p class="font c-g"></p>
                            </li>
                            <li>
                                <input id="email_pwd_again" name="email_pwd_again" type="password" class="tps_input" maxlength="18" autocomplete="off" placeholder="<?php echo lang('tps_pwd_again'); ?>"/>
                                <span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                                <p id="email_compare_pwd_tip" class="font c-g"></p>
                            </li>
                            <li>
                                <input autocomplete="off" name="email_disclaimer" checked type="checkbox" class="tps_checkbox"/>
                                <?php echo lang('user_register_agreement')?>
                            </li>
                            <!-- 改密内容提交 按钮 -->
                            <input type='button' class="tps_reg_btn email_submit_button tps_login_btn" value="<?php echo lang('submit')?>">
                            <p class="font">
                                <?php echo lang('tps_exist_account')?>？
                                <a class="c-b" href="<?php echo base_url('login')?>"><?php echo lang('go_login');?></a>
                            </p>
                        </ul>
                        <div class="error_msg" id="msg_content_ui">
                            <p>
                                <?php echo lang('tps_possible')?><br/>
                                <?php echo lang('tps_email_correct')?><br/>
                                <?php echo lang('tps_email_garbage')?><br/>
                                <?php echo lang('tps_email_delay')?>
                            </p>
                        </div>
                    </form>
                </div>
                
                <?php if($login_language_id == '156'){ ?>
                <!-- 手机号码 -->
                <div class="mail_reg" id="tel_ul" style="display: none;">
                    <form class="register_mobile">
                        <ul>
                            <li>
                                <input id="mobile" name="mobile" type="text" class="tps_input" maxlength="11" autocomplete="off" placeholder="<?php echo lang('tps_china_moblie')?>"/>
                                <span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                                <p class="font c-g"></p>
                            </li>

                            <li class="mobileCaptcha d-n">
                                <input id="mobile_captcha" name="mobile_captcha" type="text" class="tps_input" autocomplete="off" placeholder="<?php echo lang('captcha')?>"/>
                                <input type='button' class="mobile_send_captcha" value="<?php echo lang('tps_get_captcha');?>">
                                <p class="font mobile_send_captcha_on_show d-n"></p>
                                <p class="font mobile_send_captcha_on d-n">
                                    <?php echo lang('tps_captcha_send')?>
                                    <span class="mobile_send_captcha_info"></span>
                                    <span class="c-b" id="msg_telui">. <?php echo lang('tps_not_received_captcha')?>？</span>
                                </p>
                            </li>

                            <li>
                                <input id="mobile_pwd" name="mobile_pwd" type="password" class="tps_input" maxlength="18" autocomplete="off" placeholder="<?php echo lang('tps_pwd_new'); ?>"/>
                                <span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                                <p class="font c-g"></p>
                            </li>
                            <li>
                                <input id="mobile_pwd_again" name="mobile_pwd_again" type="password" class="tps_input" maxlength="18" autocomplete="off" placeholder="<?php echo lang('tps_pwd_again'); ?>"/>
                                <span class="msg"><img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>"></span>
                                <p id="mobile_compare_pwd_tip" class="font c-g"></p>
                            </li>
                            <li>
                                <input autocomplete="off" name="mobile_disclaimer" checked type="checkbox" class="tps_checkbox"/>
                                <?php echo lang('user_register_agreement')?>
                            </li>
                            <!-- 改密内容提交 按钮 -->
                            <input type='button' class="tps_reg_btn mobile_submit_button" value="<?php echo lang('submit')?>">
                            
                            <p class="font mt-10">
                                <?php echo lang('tps_exist_account')?>？
                                <a class="c-b" href="<?php echo base_url('login')?>"><?php echo lang('go_login');?></a>
                            </p>
                        </ul>
                        <div class="error_msg" id="msg_telcontent_ui">
                            <p>
                                <?php echo lang('tps_possible')?><br/>
                                <?php echo lang('tps_moblie_correct')?><br/>
                                <?php echo lang('tps_moblie_garbage')?><br/>
                                <?php echo lang('tps_moblie_delay')?>
                            </p>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
            <!-- 内容是：重发验证码 -->
            <input type="hidden" value="<?php echo lang('resend_captcha');?>" id="resend_captcha">
            <!-- 内容是：获取验证码 -->
            <input type="hidden" value="<?php echo lang('get_captcha');?>" id="get_captcha">
        </div>
    </div>
</div>
<!--end 注册-->


<div class="xm-backdrop " id="fullbg"></div>
<div class="wodl thickbox clear" id="BOX_nav"> <span onclick="closeBg();" class="close">×</span>
	<h3><?php echo lang('register_agreement');?></h3>
	<div class="agree_content">
	</div>
	<input type="button" onclick="closeBg();" value="<?php echo lang('agree_continue')?>" class="btn-Login">
</div>


<script src="<?php echo base_url('themes/mall/js/login_forgot.js?v=3.2'); ?>"></script>

<script>

    $(document).ready(function(){
    	
    	/*注册页面tab切换调用*/
        tps_reg_tab(tps_reg_ui);
    
        /*调用隐藏显示未收到验证码的内容切换*/
        tps_msg_show(msg_ui,msg_content_ui);
        if(<?php echo $login_language_id;?> == '156'){
            tps_msg_show(msg_telui,msg_telcontent_ui);
        }
    })


    /**
     * 功能：注册、密码找回tab切换
     * params:id
     * */
    function tps_reg_tab(id){
        //通过id找到下面的li进行循环
        $(id).find("li").each(function(){
            //当前对象进行点击事件
            $(this).on("click",function(){
                //获取当前对象的属性值
                var tab = $(this).attr("tab");
                //给当前选中的对象加上激活的样式，其他同级元素删除激活样式
                $(this).addClass("active").siblings().removeClass("active");
                //匹配和选择中的对象的内容显示出来，其它隐藏
                $(tab).show().siblings().hide();
            })
        })
    }
    
    /**
     * 功能：隐藏显示未收到验证码的内容
     * params:pid,cid
     * */
    function tps_msg_show(pid,cid){
        $(pid).mouseover(function(){
            $(cid).fadeIn();
        });
        $(pid).mouseout(function(){
            $(cid).fadeOut();
        });
    }
    
    
    /**
     * 邮件密码区别问题
     * leon 新增
     */   
    function email_pwd_distinction(name,val){
        var pwd = $('#email_pwd').val();
        var pwd_again = $('#email_pwd_again').val();
        
        if(val == '' || val == null || val == 'null'){
            //当前值为空
            curElement = $("input[name='email_pwd']");
            curElement.next().children('img').attr("class", "error_icon");
            curElement.next().next().text('<?php echo lang("regi_errormsg_repwd_2");?>').addClass('c-o');
            return false;
        }else{
            var error_info = "<?php echo lang('regi_errormsg_repwd');?>";
            if(name == 'email_pwd'){
                //是否有一个内容为空
                if(pwd_again == '' || pwd_again == null || pwd_again == 'null' || pwd_again.length == 0){
                    curElement = $("input[name='email_pwd']");
                    //curElement.next().children('img').attr("class", "right_icon");
                    //curElement.next().next().text("").addClass('c-o');
                    return true;
                }else{
                    //内容是不是相同
                    if(pwd == pwd_again){
                        //自身属性
                        curElement = $("input[name='email_pwd']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');

                        //对方属性
                        curElement = $("input[name='email_pwd_again']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');
                        return true;
                    }else{
                        curElement = $("input[name='email_pwd']");
                        curElement.next().children('img').attr("class", "error_icon");
                        curElement.next().next().text(error_info).addClass('c-o');
                        return false;
                    }
                }
            }
            if(name == 'email_pwd_again'){
                if(pwd == '' || pwd == null || pwd == 'null' || pwd.length == 0){
                    //是否有一个内容为空
                    //curElement = $("input[name='email_pwd_again']");
                    //curElement.next().children('img').attr("class", "right_icon");
                    //curElement.next().next().text("").addClass('c-o');
                    return true;
                }else{
                    //内容是不是相同
                    if(pwd == pwd_again){
                        curElement = $("input[name='email_pwd_again']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');

                        curElement = $("input[name='email_pwd']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');
                        return true;
                    }else{
                        curElement = $("input[name='email_pwd_again']");
                        curElement.next().children('img').attr("class", "error_icon");
                        curElement.next().next().text(error_info).addClass('c-o');
                        return false;
                    }
                }
            }
        }
    }

    /**
     * 手机密码区别问题
     * leon 新增
     */
    function mobile_pwd_distinction(name,val){
        var pwd = $('#mobile_pwd').val();
        var pwd_again = $('#mobile_pwd_again').val();
        
        if(val == '' || val == null || val == 'null'){
            //当前值为空
            curElement = $("input[name='mobile_pwd']");
            curElement.next().children('img').attr("class", "error_icon");
            curElement.next().next().text("<?php echo lang('regi_errormsg_repwd_2');?>").addClass('c-o');
            return false;
        }else{
            if(name == 'mobile_pwd'){
                //是否有一个内容为空
                if(pwd_again == '' || pwd_again == null || pwd_again == 'null' || pwd_again.length == 0){
                    curElement = $("input[name='mobile_pwd']");
                    //curElement.next().children('img').attr("class", "right_icon");
                    //curElement.next().next().text("").addClass('c-o');
                    return true;
                }else{
                    //内容是不是相同
                    if(pwd == pwd_again){
                        curElement = $("input[name='mobile_pwd']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');

                        curElement = $("input[name='mobile_pwd_again']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');
                        return true;
                    }else{
                        curElement = $("input[name='mobile_pwd']");
                        curElement.next().children('img').attr("class", "error_icon");
                        curElement.next().next().text("<?php echo lang('regi_errormsg_repwd');?>").addClass('c-o');
                        return false;
                    }
                }
            }
            if(name == 'mobile_pwd_again'){
                if(pwd == '' || pwd == null || pwd == 'null' || pwd.length == 0){
                    //是否有一个内容为空
                    //curElement = $("input[name='mobile_pwd_again']");
                    //curElement.next().children('img').attr("class", "right_icon");
                    //curElement.next().next().text("").addClass('c-o');
                    return true;
                }else{
                    //内容是不是相同
                    if(pwd == pwd_again){
                        curElement = $("input[name='mobile_pwd_again']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');

                        curElement = $("input[name='mobile_pwd']");
                        curElement.next().children('img').attr("class", "right_icon");
                        curElement.next().next().text("").addClass('c-o');
                        return true;
                    }else{
                        curElement = $("input[name='mobile_pwd_again']");
                        curElement.next().children('img').attr("class", "error_icon");
                        curElement.next().next().text("<?php echo lang('regi_errormsg_repwd');?>").addClass('c-o');
                        return false;
                    }
                }
            }
        }
    }

</script>
