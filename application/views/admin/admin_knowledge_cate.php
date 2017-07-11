<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<style>
    form{margin:0 0 2px}
</style>
<div class="search-well">
    <?php if($haveModifyRole){?>
    <a href="<?php echo base_url('admin/admin_knowledge_cate/add_or_update?type=1'); ?>" class="btn  btn-success" type="button"><?php echo lang('add'); ?></a>
    <?php }?>

    <a href="<?php echo base_url('admin/admin_knowledge'); ?>" class="btn btn-info" type="button">
        <?php echo lang('admin_knowledge_manage'); ?></a>
</div>

<div class="well">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th><?php echo lang('admin_knowledge_title'); ?></th>
                <th><?php echo lang('sort'); ?></th>
                <?php if($haveModifyRole){?>
                    <th><?php echo lang('file_is_show'); ?>/<?php echo lang('file_is_hide'); ?></th>
                <?php }?>
                <th><?php echo lang('modify_user'); ?></th>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('modify').lang('time'); ?></th>
                <?php if($haveModifyRole){?>
                    <th><?php echo lang('action'); ?></th>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php if($list){ ?>
                <?php foreach($list as $item){ ?>
                    <tr>
                        <td><?php echo $item['id'] ?></td>
                        <td><?php echo $item['name'] ?></td>
                        <td><?php echo $item['sort'] ?></td>
                    <?php if($haveModifyRole){?>
                        <td><?php if($item['is_show']){echo(lang("file_is_show"));}else{echo(lang("file_is_hide"));} ?></td>
                    <?php }?>
                        <td><?php echo $admin_user_info[$item['admin_id']] ?></td>
                        <td><?php echo $item['create_time'] ?></td>
                        <td><?php echo $item['modify_time'] ?></td>
                        <?php if($haveModifyRole){?>
                        <td>
                            <a href="<?php echo base_url('admin/admin_knowledge_cate/add_or_update?type=2&id='.$item['id']) ?>"><?php echo lang('edit'); ?></a> /
                            <a id="<?php echo $item['id']; ?>" class="delete_admin_knowledge" href="#"><?php echo lang('delete'); ?></a>
                        </td>
                        <?php }?>
                    </tr>
                    <?php }?>
            <?php } else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php echo $pager ?>
</div>

<script>

    $(function(){

        $('.delete_admin_knowledge').click(function(){
            var id = $(this).attr("id");
            layer.confirm('<?php echo lang('sure'); ?>', {icon: 3, title:'<?php echo lang('delete_admin_knowledge'); ?>'},
                function(){
                    $.ajax({
                        type:"post",
                        url:"<?php echo base_url('admin/admin_knowledge_cate/do_delete'); ?>",
                        data:{id:id},
                        dataType:"json",
                        success:function(res){
                            if(res.code==0){
                                layer.msg(res.msg);
                                window.location.reload();
                            }else{
                                layer.msg(res.msg);
                            }
                        }
                    });
                }
            );

        });

    });

</script>