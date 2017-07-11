<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">
<div class="well">
<div class="alert alert-success hidden">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong><?php echo lang('account_success'); ?></php></strong>
</div>
<form class="form-horizontal" id="change_pwd" method="post" action="<?php echo base_url('ucenter/change_pwd/changePwd')?>">
        <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
        <label for="pwdOld"><?php echo lang('ori_password')?></label>
        <div class="inline">
             <input class="input-xlarge pull-left" type="password" id="pwdOld" name="pwdOld" placeholder="<?php echo lang('ori_password')?>" >
        </div>
        <div class="msg inline">
            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
            <span class="txt"></span>
        </div>
        <div style="clear: both;"></div>
        <label for="pwd"><?php echo lang('new_password')?></label>
        <div class="inline">
            <input class="input-xlarge pull-left" type="password" id="pwd" name="pwdOriginal" placeholder="<?php echo lang('new_password')?>">
        </div>
        <div class="msg inline">
            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
            <span class="txt" id="pwd_span"></span>
        </div>
        <div style="clear: both;"></div>
        <label for="re_pwd"><?php echo lang('re_password')?></label>
        <div class="inline ">
            <input class="input-xlarge pull-left" type="password" id="re_pwd" name="pwdOriginal_re" placeholder="<?php echo lang('re_password')?>" >
        </div>
        <div class="msg inline">
            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
            <span class="txt"></span>
        </div>
        <div style="clear: both;"></div>
        <div style="margin-top:20px; ">
        <button type="button" name="submit" class="btn btn-primary"><?php echo lang('submit')?></button>
        </div>
</form>
</div>
<style>
    .main .msg {
        width: 100%;
    }
</style>
<script>
    $(document).ready(function () {
        $("#change_pwd input").blur(function () {
            var curElement = $(this);
            $.ajax({
                type: "POST",
                url: "/admin/register/checkRegisterItem",
                data: {itemName: curElement.attr("name"), itemVal: curElement.val(), pwdVal: $('#pwd').val()},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (itemName, checkResultVal) {
                        displayFormItemCheckRes(itemName, checkResultVal);
                    });
                }
            });
        });
        function displayFormItemCheckRes(itemName, checkResultVal) {
            curElement = $("input[name='" + itemName + "']");
            if (checkResultVal.isRight === true) {
                curElement.css("border", "");
                curElement.parent().next().children('img').attr("class", "right_icon");
                curElement.parent().next().children('span').html(checkResultVal.msg).removeClass('red');
            } else {
                curElement.css("border", "1px red solid");
                curElement.parent().next().children('img').attr("class", "error_icon");
                curElement.parent().next().children('span').html(checkResultVal.msg).addClass('red');

            }
        }
        $("button[name='submit']").click(function () {
            var curEle = $(this);
            var oldSubVal = curEle.text();
            curEle.html($('#loadingTxt').val());
            curEle.attr("disabled","disabled");
            $.ajax({
                type: "POST",
                url: "/admin/reset_pwd/changePwd",
                data: $('#change_pwd').serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        $('.alert').removeClass('hidden');
                        $('#change_pwd input').each(function(){
                           $(this).val('');
                        });
                        setTimeout(function(){
                           window.location.reload();
                        },2000);
                    } else {
                        $.each(data.checkResult, function (itemName, checkResultVal) {
                            displayFormItemCheckRes(itemName, checkResultVal);
                        });
                    }
                    curEle.html(oldSubVal);
                    curEle.attr("disabled",false);
                }
            });
        });

    });
</script>
