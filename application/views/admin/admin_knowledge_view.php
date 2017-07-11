<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>
<div>
    <form id="submit-form" action="" method="post" enctype="multipart/form-data">
        <input id="sign" type="hidden" name="is_add" value="">
        <input id="mark" type="hidden" name="mark" value="<?php echo $mark; ?>">
        <input id="update_id" type="hidden" name="update_id" value="<?php if(!empty($row)){ echo $row['id'];} ?>">
        <div class="title" style="text-align: center;font-size: 44px;line-height: 44px;background: white;padding: 20px;">
            <span><?php if(!empty($row)){ echo $row['title'];} ?></span>
            <a style="margin-right: 30px; float:right;" type="reset" class="btn btn-back"><?php echo lang('back'); ?></a>
        </div>
        <div class="clear"></div>
        <div style="max-height:600px; overflow-y: auto; height:600px;background: white;padding:30px;">
            <?php echo isset($row)?$row['content']:''?>
        </div>
        <div>
            <span class="error_msg"><?php if(!empty($errorData)){echo $errorData['error_msg'][0];}?></span>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('.btn-back').click(function(){
            window.location.href = '<?php echo base_url('admin/admin_knowledge'); ?>';
        });
    });
</script>