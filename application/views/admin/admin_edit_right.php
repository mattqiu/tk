<div class="well">

    <form id="update_form">

    <input class="right_id" type="hidden" name="right_id" value="<?php echo $row['id']; ?>">

    admin id : <span style="color: red;">(0 为系统自动生成)</span>
        <div><input disabled class="admin_id" type="text" name="admin_id" value="<?php echo $row['admin_id']; ?>"></div><br>

    name : <div><input autocomplete="off" class="right_name" type="text" name="right_name" value="<?php echo $row['right_name'] ?>"></div><br>

    right key : <div><input disabled class="right_key" type="text" name="right_key" value="<?php echo $row['right_key'] ?>"></div><br>

    time : <div><input disabled class="create_time" type="text" name="create_time" value="<?php echo $row['create_time'] ?>"></div><br>

    remark : <div><textarea class="remark" rows="5" cols="10" name="remark"><?php echo $row['remark']; ?></textarea></div><br>

    right :<span style="color: red;">(英文逗号加id)</span>
        <div><textarea class="right" style="width: 608px; height: 90px;" name="right"><?php echo implode(',',unserialize($row['right'])); ?></textarea></div><br>

    <button type="button" class="btn submit_button">submit</button>

    &nbsp;&nbsp;&nbsp;

    <a href="<?php echo base_url('admin/admin_right'); ?>" type="button" class="btn">back</a>

    </form>
</div>

<script>
    $(function(){
        $('.submit_button').click(function(){
            $.ajax({
                type:'post',
                url:'<?php echo base_url('admin/admin_right/updateRight') ?>',
                data:$('#update_form').serialize(),
                dataType:'json',
                success:function(res){
                    if(res.success==1){
                        layer.msg(res.msg);
                        window.location.reload(true);
                    }
                }
            });
        });
    });
</script>