<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">
<div class="container-fluid">
    <div class="row-fluid">
        <div class="dialog span4">
            <div class="block">
                <div class="block-heading"><?php echo lang('nav_register');?>
                    <a href="<?php echo base_url('admin/sign_in') ?>" class="pull-right"><?php echo lang('back_to_login') ?></a>
                </div>
                <div class="block-body">
                    <div class="alert alert-success hidden">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><?php echo lang('reg_success'); ?></php></strong>
                    </div>
                    <form id="register_form">
                        <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
                        <div class="inline">
                        <label><?php echo lang('regi_email') ?></label>
                        <input type="text" name="email" class="span12">
                            </div>
                        <div class="msg inline">
                            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                            <span class="txt"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="inline">
                        <label><?php echo lang('login_pwd');?></label>
                        <input type="password" id="pwd" name="pwdOriginal" class="span12">
                            </div>
                        <div class="msg inline">
                            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                            <span class="txt"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="inline">
                        <label><?php echo lang('regi_pwd_re') ?></label>
                        <input type="password" name="pwdOriginal_re" class="span12" autocomplete="off">
                            </div>
                        <div class="msg inline">
                            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                            <span class="txt"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="inline">
                            <label><?php echo lang('role') ?></label>
                            <select name="role" class="span12" autocomplete="off">
                                <option value="1"><?php echo lang('role_customer_service') ?></option>
                                <option value="2"><?php echo lang('role_customer_service_manager') ?></option>
                                <option value="3"><?php echo lang('operations_personnel') ?></option>
                                <option value="4"><?php echo lang('financial_officer') ?></option>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <button type="button" autocomplete="false" id="register_submit" class="btn btn-primary pull-right" ><?php echo lang('regi_register_now') ?></button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#register_form input").blur(function () {
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
            $("#register_submit").click(function () {
                var curEle = $(this);
                var oldSubVal = curEle.text();
                curEle.html($('#loadingTxt').val());
                curEle.attr("disabled","disabled");
                $.ajax({
                    type: "POST",
                    url: "/admin/register/submit",
                    data: $('#register_form').serialize(),
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            $('.alert').removeClass('hidden');
                            $('#register_form input').each(function(){
                                $(this).val('');
                            });
                            setTimeout(function(){
                                location.href = "/admin/sign_in";
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