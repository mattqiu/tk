<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo lang('tps138_admin');?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <script src="<?php echo base_url('js/jquery-1.11.2.min.js')?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
    <script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
    <script src="<?php echo base_url('themes/admin/lightbox/js/jQueryRotateCompressed.js?v=2'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('themes/admin/javascripts/tickets_base.js')?>"></script>
    <script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.js?v=2'); ?>"></script>
    <script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
    <style>
        .add_template{padding: 20px;background-color: #f5f5f5;overflow: auto;height: 422px;}
        .well{margin-bottom: 0px;}
        .add_content{width:100%;margin-top: 5px;}
        .add_status{float: left;margin-top: 2px;margin-left: 2px;}
        .add_btn{clear: both;float: left;margin-top:10px;margin-left: 36%;}
        .name_label{float: left;height: auto;}
        .name_span{float: left;margin-right: 5px;height:34px;line-height:34px;overflow:hidden;}
        .name{width: 210px;float: left;}
        .content_label{margin-top: 48px;clear: both;}
        .t_status{float: left;clear: both;margin-top: 5px;}
        .t_type{float: left;clear: both;margin-top: 5px;}
    </style>
</head>

<body>

<div class="well">
    <form id="submit_template" name="t_add" method="post">
    <div class="add_template">
        <div class="name_label">
            <span class="name_span"><?php echo lang('t_template_name'); ?></span>
            <input class="form-control name" autocomplete="off" name="t_name" value="<?php if(!empty($tpl)){echo $tpl['name'];} ?>"/>
        </div>
        <div class="content_label">
            <span ><?php echo lang('t_template_content'); ?></span>
            <textarea class="add_content form-control" maxlength="1500" name="t_content" cols='40' rows='5'><?php if(!empty($tpl)){echo $tpl['content'];} ?></textarea>
        </div>
        <div class="t_type">
            <span><?php echo lang('t_template_type'); ?></span>
            <select name="t_type" style="width: 100px;">
                <?php foreach (config_item('tickets_problem_type') as $key => $value) { ?>
                    <option value="<?php echo $key ?>" <?php if(!empty($tpl) && $key==$tpl['type']){echo 'selected=selected';} ?>><?php echo lang($value); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="t_status">
            <span class="add_status"><?php echo lang('is_public'); ?></span>
            <select class="add_status" name="t_status" style="width: 60px;">
                <option value=0 <?php if(!empty($tpl) && 0==$tpl['status']){echo 'selected=selected';} ?>><?php echo lang('template_is_public')?></option>
                <option value=1 <?php if(!empty($tpl) && 1==$tpl['status']){echo 'selected=selected';} ?>><?php echo lang('template_not_public');?></option>
            </select>
        </div>


        <span class="add_btn">
            <button class="btn btn-primary confirm_add" <?php if(!empty($tpl) && !empty($is_view)){echo 'disabled';} ?> type="button" name="submit"><?php echo lang('confirm') ?></button>
            <button class="btn btn-info cancel_add" type="button" style="margin-left: 25px;"><?php echo lang('cancel') ?></button>
        </span>
    </div>
    </form>
</div>
</body>
<script>
    $(function(){
        var u = <?php if(!empty($tpl) && $tpl['id']){echo $tpl['id'];}else{echo 'false';} ?>;
        var tpl_content_obj = $('.add_content');
        var tpl_name_obj    = $('.name');
        /**草稿**/
        if(!u){
            var new_template = getCookie('new_template');
            var new_template_name = getCookie('new_template_name');
            tpl_content_obj.val(new_template);
            tpl_name_obj.val(new_template_name);
        }
        tpl_content_obj.keyup(function(){
            addCookie('new_template',tpl_content_obj.val(),48);
        });
        tpl_name_obj.keyup(function(){
            addCookie('new_template_name',tpl_name_obj.val(),48);
        });
        $('.cancel_add').click(function(){
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        });
        $("button[name='submit']").click(function () {
            var url = "/admin/tickets_template/do_add__tickets_template";
            if(u){
                $('.add_template').append("<input type='hidden' name='id' value='"+u+"'/>");
                url  = "/admin/tickets_template/do_update_tickets_template";
            }
            if($.trim($('.name').val())==""){
                layer.msg("<?php echo lang('pls_t_t_name'); ?>");
                return;
            }
            if($.trim($('.add_content').val())==""){
                layer.msg("<?php echo lang('pls_t_t_content'); ?>");
                return;
            }
            $.ajax({
                type: "POST",
                url: url,
                data: $('#submit_template').serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.success==1) {
                        layer.msg(data.msg);
                        if(!u){
                            $('.add_content').val("");
                        }
                        deleteCookie('new_template');
                        deleteCookie('new_template_name');
                        window.location.reload();
                    } else {
                        layer.msg(data.msg);
                    }
                }
            });
        });


    });
</script>