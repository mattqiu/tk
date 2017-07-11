<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>

<div>

    <?php if(isset($update_success)){ ?>
        <span style="<?php echo $update_success ? 'color:#00AA66;':'color:red;' ?>"><?php echo $update_success ? lang('admin_file_update_success'): lang('admin_file_update_fail') ?></span>
    <?php } ?>

    <?php if(isset($add_success)){ ?>
        <span style="<?php echo $add_success ? 'color:#00AA66;':'color:red;' ?>"><?php echo $add_success ? lang('admin_file_add_success'):lang('admin_file_add_fail') ?></span>
    <?php } ?>


    <form id="submit-form" action="" method="post" enctype="multipart/form-data">

        <input id="sign" type="hidden" name="is_add" value="">

        <input id="mark" type="hidden" name="mark" value="<?php echo $mark; ?>">

        <input id="update_id" type="hidden" name="update_id" value="<?php if(!empty($row)){ echo $row['id'];} ?>">

    <div class="">
        <span><?php echo lang('admin_knowledge_cate'); ?> :</span>
        <input value="<?php if(!empty($row)){ echo $row['name'];} ?>"
               style="width:450px;border-radius: 3px;margin-left: 5px;margin-top: 6px;"
               name="name" autocomplete="off" maxlength="150" type="text" class="form-control">
    </div>

        <div class="">
            <span><?php echo lang('sort'); ?> :</span>
            <input value="<?php if(!empty($row)){ echo $row['sort'];} ?>"
                   style="width:450px;border-radius: 3px;margin-left: 5px;margin-top: 6px;"
                   name="sort" autocomplete="off" maxlength="150" type="number" class="form-control" >
        </div>

    <?php if(!empty($row)){ ?>

        <div class="file_name_2">
            <span><?php echo $row['title']; ?></span>
        </div>

    <?php } ?>

    <div class="is-show">
        <span style="margin-top: 6px;"><?php echo lang('file_is_show'); ?>:</span>
        <input style="margin-left: 15px; margin-bottom: 8px;" value="1" type="radio" name="is_show" checked><span><?php echo lang('file_is_show'); ?></span>
        <input style="margin-bottom: 8px;" value="0" type="radio" name="is_show" <?php if(!empty($row) && $row['is_show']==0){echo 'checked';} ?>><span><?php echo lang('file_is_hide'); ?></span>
    </div>

    <div class="clear">&nbsp;</div>

    <div>
        <span class="error_msg"><?php if(!empty($errorData)){echo $errorData['error_msg'][0];}?></span>
    </div>

    <div class="submit-button">

        <a class="btn" id="submit_btn"><?php echo lang('submit'); ?></a>

        <a style="margin-left: 30px;" type="reset" class="btn btn-back"><?php echo lang('cancel'); ?></a>

    </div>
    </form>
</div>

<script>
    $('.file-type,.file-upload,.is-show,.submit-button,.file_name_2,.file-area').css('margin-top','15px');

    var is_add = <?php echo $is_add; ?>;

    $(function(){
        $('#sign').val(is_add);

        $('.btn-back').click(function(){
            window.location.href = '<?php echo base_url('admin/admin_knowledge_cate'); ?>';
        });

        $('#submit_btn').click(function(){
            var data = $('#submit-form').serialize();
            var url = "<?php echo base_url('admin/admin_knowledge_cate/do_add_or_update'); ?>";
            $.ajax({
                type:"post",
                url:url,
                data:data,
                dataType:"json",
                success:function(res){
                    if(res.code==0){
                        layer.confirm('<?php echo  lang('admin_knowledge_success'); ?>', {icon: 3, title:'<?php echo lang('success'); ?>'},
                            function(){
                                window.location.href = '<?php echo base_url('admin/admin_knowledge_cate'); ?>';
                            },
                            function(){
                                window.location.reload();
                            }
                        );
                    }else{
                        layer.msg(res.msg);
                    }
                }
            });
        });

    });


    $(function(){
        //实例化编辑器
        var um = UM.getEditor('myEditor');
    })
</script>