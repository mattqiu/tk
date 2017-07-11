<div class="bj_zhuti">
    <div class="container clear">
        <div class="row clear">
            <div class="password">
                <h3><?php echo lang('Active_account'); ?></h3>
                <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
                <div class="form">
                    <div class="msg inline" style="margin-bottom:10px;height: 20px;">
                        <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                        <span class="txt"></span>
                    </div>
                    <div class="item">
                        <?php echo lang('pls_enter_your_mail_below') . ' ' . lang('receive_enable_link'); ?>
                    </div>
                    <div class="item-ifo">
                        <b><?php echo lang('regi_email'); ?> : </b>
                        <input id="email" class="itxt" name="loginname" tabindex="1" autocomplete="off" placeholder="<?php echo lang('regi_email'); ?>">
                        <input type="button" class="btn-Register" name="submit" value="<?php echo lang('submit'); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("input[name='submit']").click(function () {

            var curEle = $(this);
            var oldSubVal = curEle.val();
            curEle.attr("value", $('#loadingTxt').val());
            curEle.attr("disabled","disabled");
            var oldColor = '#2c3444'/*curEle.css('background-color')*/;
            curEle.css('background-color','#cccccc');
            $('#loading').removeClass('hidden');
            email = $('#email').val();
            $.ajax({
                type: "POST",
                url: "/inactive/sendEnableMail",
                data: {email: email},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('.msg').children('img').addClass('right_icon');
                        $('.txt').html(data.msg).addClass('green');
                        $('#email').val('');
                        setTimeout(function(){
                            window.location.href = '/login';
                        },3000);
                    } else {
                        $('.msg').children('img').addClass('error_icon');
                        $('.txt').html(data.msg).addClass('red');
                    }
                    curEle.attr("value", oldSubVal);
                    curEle.attr("disabled", false);
                    curEle.css("background-color", oldColor);
                }
            });
        });
    });
</script>