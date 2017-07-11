<div class="bj_zhuti">
  <div class="container clear">
    <div class="row clear">
      <div class="password">
        <h3><?php echo lang('reg_success');?></h3>
        <dl class="form zhuc clear">
           <dt><s class="cg"></s></dt>
           <dd>
             <span class="cg"><?php echo lang('reg_success'); ?></span>
               <p><?php echo lang('reg_success_check_email'); ?></p>
               <p><?php echo lang('reg_success_notice_1'); ?></p>
             <p><?php echo lang('reg_success_notice_2'); ?></p>
             <p id="re_send_email_msg" style="font-size:16px; color: green;text-align: center;"></p>
           </dd>
        </dl>
      </div>
    </div>
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
    $(function(){
        $(".password .re_enable_mail").click(function () {
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
</script>