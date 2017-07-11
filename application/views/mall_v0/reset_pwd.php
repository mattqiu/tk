<div class="bj_zhuti">
    <div class="container clear">
        <div class="row clear">
            <div class="password">
                <h3><?php echo lang('pwd_reset'); ?></h3>
                <div class="form Reset">
                    <div class="msg inline item-ifo" style="height: 30px;text-align: center;">
                        <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                        <span class="txt"></span>
                    </div>
                    <div class="clearfix"></div>
                    <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
                    <div class="item-ifo">
                        <span class="label"><?php echo lang('pwd_new') ?></span>
                        <input type="password" autocomplete="off" placeholder="<?php echo lang('pwd_new') ?>" tabindex="1" name="loginname" class="itxt" id="newPwd">
                    </div>
                    <div class="item-ifo">
                        <span class="label"><?php echo lang('regi_pwd_re') ?></span>
                        <input type="password" autocomplete="off" placeholder="<?php echo lang('regi_pwd_re') ?>" tabindex="1" name="loginname" class="itxt" id="newPwdRe">
                    </div>
                    <div class="item-ifo">
                        <span class="label"></span>
                        <input type="button" autocomplete="off" name="submit" value="<?php echo lang('submit')?>" id="" class="btn-Register">
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
            curEle.val($('#loadingTxt').val());
            curEle.attr("disabled", "disabled");
            newPwd = $('#newPwd').val();
            newPwdRe = $('#newPwdRe').val();
            var oldColor = '#2c3444'/*curEle.css('background-color')*/;
            curEle.css('background-color','#cccccc');
            $.ajax({
                type: "POST",
                url: window.location.href + "&ajax=resetPwd",
                data: {newPwd: newPwd, newPwdRe: newPwdRe},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('.msg').children('img').addClass('right_icon');
                        $('.txt').html(data.msg).addClass('green');
                        $('#newPwdRe').val('');
                        $('#newPwd').val('');
                    } else {
                        $('.msg').children('img').addClass('error_icon');
                        $('.txt').html(data.msg).addClass('red');
                    }
                    curEle.val(oldSubVal);
                    curEle.attr("disabled", false);
                    curEle.css("background-color", oldColor);
                }
            });
        });

    });
</script>