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
        <span><?php echo lang('admin_knowledge_title'); ?> :</span>
        <input value="<?php if(!empty($row)){ echo $row['title'];} ?>"
               style="width:450px;border-radius: 3px;margin-left: 5px;margin-top: 6px;"
               name="title" autocomplete="off" maxlength="150" type="text" class="form-control">
    </div>
    <div class="">
        <span><?php echo lang('admin_knowledge_cate'); ?> :</span>
        <select  name="category_id" style="width: 180px;margin-right: 5px;margin-top: 6px;">
            <option value=""><?php echo lang('admin_knowledge_cate'); ?></option>
            <?php foreach($knowledge_cate as $k=>$v){  ?>
                <option value="<?php echo $v['id']; ?>" <?php if(!empty($row) && $row['category_id']==$v['id']){echo 'selected=selected';} ?>><?php echo $v['name']; ?></option>
            <?php } ?>
        </select>
        <a href="<?php echo base_url('admin/admin_knowledge_cate/add_or_update?type=1'); ?>">
            <?php echo lang('admin_knowledge_cate'); ?><?php echo lang('add'); ?></a>
    </div>
    <div class="is-show">
        <span style="margin-top: 6px;"><?php echo lang('file_is_show'); ?>:</span>
        <input style="margin-left: 15px; margin-bottom: 8px;" value="1" type="radio" name="is_show" checked><span><?php echo lang('file_is_show'); ?></span>
        <input style="margin-bottom: 8px;" value="0" type="radio" name="is_show" data="<?php echo $row['is_show']?>" <?php if(!empty($row) && $row['is_show']==0){echo 'checked';} ?>><span><?php echo lang('file_is_hide'); ?></span>
    </div>
    <div class="clear">&nbsp;</div>
    <div>
        <script type="text/plain" id="myEditor" style="width:1000px;height:240px;">
            <?php echo isset($data)?$data['content']:''?>
        </script>
    </div>
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
            window.location.href = '<?php echo base_url('admin/admin_knowledge'); ?>';
        });
    });
    $('#submit_btn').click(function(){
        var html_content = $.trim(UM.getEditor('myEditor').getContent());
        var data = {
           "update_id":$('#update_id').val(),
           "title":$("input[name=title]").val(),
           "category_id":$('select[name=category_id]').val(),
           "content":html_content,
            "is_show":$('input[name=is_show]:checked').val(),
        };
        var url = "<?php echo base_url('admin/admin_knowledge/do_add_or_update'); ?>";
        $.ajax({
            type:"post",
            url:url,
            data:data,
            dataType:"json",
            success:function(res){
                if(res.code==0){
                    layer.msg(res.msg);
                    window.location.href = '<?php echo base_url('admin/admin_knowledge'); ?>';
                }else{
                    layer.msg(res.msg);
                }
            }
        });
        return false;
    });
    $(function(){
        //实例化编辑器
        var um = UM.getEditor('myEditor');
    })
</script>