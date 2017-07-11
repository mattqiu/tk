<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">
<style>
    #job_number{margin-left:10px;margin-right: -2px;margin-top: 1px;}
    #job_number_label{margin-left:4px;}
</style>
        <div class="well">
            <div class="">
                <div class="">
                    <div class="alert alert-success hidden">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><?php echo lang('reg_success'); ?></php></strong>
                    </div>
                    <form id="register_form">
                        <input  type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
                        <div class="inline">
                        <label><?php echo lang('regi_email') ?></label>
                        <input type="text" name="email" class="span6">
                            </div>
                        <div class="msg inline">
                            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                            <span class="txt"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="inline">
                        <label><?php echo lang('login_pwd');?></label>
                        <input type="password" id="pwd" name="pwdOriginal" class="span6">
                            </div>
                        <div class="msg inline">
                            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                            <span class="txt"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="inline">
                        <label><?php echo lang('regi_pwd_re') ?></label>
                        <input type="password" name="pwdOriginal_re" class="span6" autocomplete="off">
                            </div>
                        <div class="msg inline">
                            <img src="<?php echo base_url('img/msdropdown/icons/blank.gif'); ?>">
                            <span class="txt"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="inline">
                            <label><?php echo lang('role') ?></label>
                            <select name="role" class="span6 role_select" autocomplete="off">
                                <?php foreach($admin_role as $k=>$item){?>
                                <?php if($k==0) {continue;}?>
                                <option value="<?php echo $k?>"><?php echo lang($item) ?></option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="inline cus_box">
                            <select name="cus_area" class="cus_area" autocomplete="off">
                                <?php foreach(config_item('tickets_cus_area') as $k=>$v){ ?>
                                    <option value="<?php echo $k ?>"><?php echo lang($v); ?></option>
                                <?php } ?>
                            </select>
                            <input type="checkbox" id="job_number"><span id="job_number_label"><?php echo lang('assign_cus_job_number'); ?></span>
                            <span id="job_number_box" style="display: none;"><?php echo lang('cus_job_number'); ?>:<span style="color: red;" class="job_number_val"></span></span>
                        </div>
                        <div class="clearfix" ></div>
                        <button type="button" autocomplete="false" id="register_submit" class="btn btn-primary" ><?php echo lang('submit') ?></button>
                        <div class="clearfix"></div>
                        <input id="job_number_select" type="hidden" name="job_number_select" value="">
                    </form>
                </div>
            </div>
		</div>
    <script>
        $(document).ready(function () {
            $("#register_form input").blur(function () {
                var curElement = $(this);
                $.ajax({
                    type: "POST",
                    url: "/admin/add_admin/checkRegisterItem",
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
                    url: "/admin/add_admin/submit",
                    data: $('#register_form').serialize(),
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            $('.alert').removeClass('hidden');
                            $('#register_form input').each(function(){
                                $(this).val('');
                            });
                            setTimeout(function(){
                                location.href = "/admin/add_admin";
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

            $(".role_select").change(function(){
                var val = $(this).find("option:selected").val();
                if(val ==1 || val==2 || val==5){
                    $('.cus_box').show();
                }else{
                    $('.job_number_val').text('');
                    $('#job_number_select').val('');
                    $('#job_number_box').hide();
                    $('#job_number').attr('checked',false);
                    $('.cus_box').hide();
                }
            });
            $('#job_number').change(function(){
                if($(this).is(':checked')){
                    var val = $(".cus_area").find("option:selected").val();
                    $.ajax({
                        type: "POST",
                        url: "/admin/add_admin/checkJobNumber",
                        data: {area: val},
                        dataType: "json",
                        success: function (data) {
                            $('#job_number_box').show();
                            $('.job_number_val').append(data.number);
                            $('#job_number_select').val(data.number);
                        }
                    });
                }else{
                    $('.job_number_val').text('');
                    $('#job_number_select').val('');
                    $('#job_number_box').hide();
                }
            });
            $('.cus_area').change(function(){
                $('#job_number_box').hide();
                $('.job_number_val').text('');
                $('#job_number').attr('checked',false);
                $('#job_number_select').val('');
            });
        });
    </script>