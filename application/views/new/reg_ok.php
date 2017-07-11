<div class="login_main" style="height: 420px">
    <div id="loginwrap1">
        <div class="login_bg" style="height: 340px">
            <div class="maintext" style="top:10px">
                <input type="hidden" id="sending" value="<?php echo lang('sending')?>">
                    <div class="successtext">
                    	<img src="<?php echo base_url('img/new/tick.png');?>"><span style="color:#5e975e;font-family:Arial, Helvetica, sans-serif;font-size:24px;"><?php echo lang('reg_success');?></span>
                        <p style="font-size:18px;font-weight: bold"><?php echo lang('reg_success_check_email'); ?></p>
                        <div style="margin-top: 30px;">
                            <p style="font-size:14px; color: #F6470E"><?php echo lang('reg_success_notice_1'); ?></p>
                            <p style="font-size:14px; color: #F6470E"><?php echo lang('reg_success_notice_2'); ?></p>
                            <p id="re_send_email_msg" style="font-size:16px; color: green;text-align: center;"></p>
                        </div>
                        <script>
                            function sendEnableEmail(){
                                $.post("/common/resendEnableMail",
                                    function(data){
                                        if(data['success']){

                                        }else{
                                            $('#re_send_email_msg').html(data.msg);
                                        }
                                    },'json');
                            }
                            sendEnableEmail();
                        </script>
                    </div>
            </div>
        </div>
    </div>
</div>