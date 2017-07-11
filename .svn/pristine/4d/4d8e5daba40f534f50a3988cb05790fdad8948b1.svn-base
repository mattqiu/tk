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
    <script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
    <style>
        .add_template{padding: 20px;}

        .add_btn{clear: both;float: left;margin-top:10px;margin-left: 36%;}
        .name_label{margin-left: 105px}
        .name_span{float: left;margin-right: 5px;height:34px;line-height:34px;overflow:hidden;}
        .name{width: 250px;float: left;}

    </style>
</head>

<body>

<div>
    <form id="submit_template" name="t_add" method="post">
    <div class="add_template">
        <div class="name_label">
            <span class="name_span"><?php echo lang('blacklist_ex'); ?>:</span>
            <input class="form-control name" autocomplete="off" name="t_name" value=""/>
        </div>

        <span class="add_btn">
            <button class="btn btn-primary confirm_add" type="button" name="submit"><?php echo lang('commission_isok') ?></button>
            <button class="btn btn-info cancel_add" type="button" style="margin-left: 25px;"><?php echo lang('cancel') ?></button>
        </span>
    </div>
    </form>
</div>
</body>
<script>
    $(function(){
        $("button[name='submit']").click(function () {

            var curEle = $(this);
            curEle.attr("disabled","disabled");
            var oldColor = '#337ab7';
            curEle.css('background','#cccccc');

                $.ajax({
                    type: "POST",
                    url: "/admin/blacklist/add_blacklist",
                    data: $('#submit_template').serialize(),
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            location.reload()
                        } else {
                            curEle.attr("disabled", false);
                            curEle.css('background',oldColor);
                            layer.msg(data.msg);
                        }
                    }
                });



        });

        $('.cancel_add').click(function(){
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        });

    });
</script>