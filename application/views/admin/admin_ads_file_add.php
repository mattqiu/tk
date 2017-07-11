<div>

    <?php if(isset($update_success)){ ?>
        <span style="<?php echo $update_success ? 'color:#00AA66;':'color:red;' ?>"><?php echo $update_success ? lang('admin_file_update_success'): lang('admin_file_update_fail') ?></span>
    <?php } ?>

    <?php if(isset($add_success)){ ?>
        <span style="<?php echo $add_success ? 'color:#00AA66;':'color:red;' ?>"><?php echo $add_success ? lang('admin_file_add_success'):lang('admin_file_add_fail') ?></span>
    <?php } ?>


    <form id="submit-form" action="<?php echo base_url('admin/admin_ads_file_manage/do_add_or_update'); ?>" method="post" enctype="multipart/form-data">

        <input id="sign" type="hidden" name="is_add" value="">

        <input id="mark" type="hidden" name="mark" value="<?php echo $mark; ?>">

        <input id="update_id" type="hidden" name="update_id" value="<?php if(!empty($row)){ echo $row['id'];} ?>">

    <div class="file-name">
        <span><?php echo lang('admin_file_name'); ?> :</span>
        <input value="<?php if(!empty($row)){ echo $row['file_name'];} ?>" style="width:450px;border-radius: 3px;margin-left: 5px;margin-top: 6px;" name="file_name" autocomplete="off" maxlength="100" type="text" class="form-control">
    </div>

    <div class="file-type"><span><?php echo lang('admin_file_type'); ?> :</span>

        <select  name="file_type" style="width: 180px;margin-right: 5px;margin-top: 6px;">
            <option value=""><?php echo lang('admin_file_type'); ?></option>

            <?php foreach(config_item('admin_file_type') as $k=>$v){  ?>

                <option value="<?php echo $k; ?>" <?php if(!empty($row) && $row['file_type']==$k){echo 'selected=selected';} ?>><?php echo lang($v); ?></option>

            <?php } ?>

        </select>

    </div>

    <div class="file-area"><span><?php echo lang('admin_file_area'); ?> :</span>

        <select  name="file_area" style="width: 180px;margin-right: 5px;margin-top: 6px;margin-left:20px;">
            <option value=""><?php echo lang('admin_file_area'); ?></option>

            <?php foreach(config_item('admin_file_area') as $k=>$v){  ?>

                <option value="<?php echo $k; ?>" <?php if(!empty($row) && $row['file_area']==$k){echo 'selected=selected';} ?>><?php echo $v; ?></option>

            <?php } ?>

        </select>

    </div>



    <div class="file-upload"><input type="file" name="upload_file"></div>

    <?php if(!empty($row)){ ?>

        <div class="file_name_2">
            <span><?php echo $row['file_real_name']; ?></span>
        </div>

    <?php } ?>

    <div class="is-show">
        <span style="margin-top: 6px;"><?php echo lang('admin_file_is_show'); ?>:</span>
        <input style="margin-left: 15px; margin-bottom: 8px;" value="1" type="radio" name="is_show" checked><span><?php echo lang('file_is_show'); ?></span>
        <input style="margin-bottom: 8px;" value="2" type="radio" name="is_show" <?php if(!empty($row) && $row['is_show']==2){echo 'checked';} ?>><span><?php echo lang('file_is_hide'); ?></span>
    </div>

    <div>
        <span class="error_msg"><?php if(!empty($errorData)){echo $errorData['error_msg'][0];}?></span>
    </div>

    <div class="submit-button">

        <button type="submit" class="btn"><?php echo lang('submit'); ?></button>

        <button style="margin-left: 30px;" type="reset" class="btn btn-back"><?php echo lang('cancel'); ?></button>

    </div>
    </form>
</div>

<script>
    $('.file-type,.file-upload,.is-show,.submit-button,.file_name_2,.file-area').css('margin-top','15px');

    var is_add = <?php echo $is_add; ?>;

    $(function(){
        $('#sign').val(is_add);

        $('.btn-back').click(function(){
            window.location.href = '<?php echo base_url('admin/admin_ads_file_manage'); ?>';
        });

    });

</script>