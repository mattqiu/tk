<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<style>
    form{margin:0 0 2px}
</style>
<div class="search-well">
    <form class="form-inline" id="admin_file_manage" action="<?php echo base_url('admin/admin_knowledge'); ?>" method="get">
        <select name="knowledge_cate" style="width: 180px;">
            <option value=""><?php echo lang('admin_knowledge_cate'); ?></option>
            <?php
                foreach($knowledge_cate as $k=>$v){
             ?>
                <option value="<?php echo $v['id']; ?>" <?php if($v['id']==$searchData['knowledge_cate']){echo 'selected=selected';} ?> ><?php echo $v['name']; ?></option>
            <?php
                }
             ?>
        </select>

        <input autocomplete="off" name="title" type="text" class="form-control"
               style="border-radius: 4px;display: inline-block;width: 180px;" placeholder="<?php echo lang('admin_knowledge_title'); ?>"
        value="<?php if(isset($searchData['title'])) echo($searchData['title']);?>">

        <input autocomplete="off" name="content" type="text" class="form-control"
               style="border-radius: 4px;display: inline-block;width: 180px;" placeholder="<?php echo lang('content'); ?>"
               value="<?php if(isset($searchData['content'])) echo($searchData['content']);?>">

        <?php if($haveModifyRole){?>
        <select name="is_show" style="width: 180px;">
            <option value="-1"><?php echo lang('all'); ?></option>
            <option value="0" <?php if("0"==$searchData['is_show']){echo 'selected=selected';} ?> >
                <?php echo(lang("file_is_hide"));?></option>
            <option value="1" <?php if("1"==$searchData['is_show']){echo 'selected=selected';} ?> >
                <?php echo(lang("file_is_show"));?></option>
        </select>
        <?php }?>
        <button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>

        <span style="float:right">
            <?php if($haveModifyRole){?>
                <a href="<?php echo base_url('admin/admin_knowledge/add_or_update?type=1'); ?>" class="btn btn-success" type="button">
                    <?php echo lang('add'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php }?>
            <a href="<?php echo base_url('admin/admin_knowledge_cate'); ?>" class="btn btn-info" type="button">
                <?php echo lang('admin_knowledge_cate_manage'); ?></a>
        </span>
    </form>
</div>

<div class="well">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th><?php echo lang('admin_knowledge_cate'); ?></th>
                <th><?php echo lang('admin_knowledge_title'); ?></th>
                <th><?php echo lang('content'); ?></th>
                <?php if($haveModifyRole){?>
                    <th><?php echo lang('file_is_show'); ?>/<?php echo lang('file_is_hide'); ?></th>
                <?php }?>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('modify_user'); ?></th>
                <th><?php echo lang('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($list){ ?>
                <?php foreach($list as $item){ ?>
                    <tr>
                        <td><?php echo $item['id'] ?></td>
                        <td data="<?php echo $item['category_id']?>"><?php echo $knowledge_cate_name[$item['category_id']] ?></td>
                        <td><a id="<?php echo $item['id']; ?>"
                               href="<?php echo base_url('admin/admin_knowledge/view?id='.$item['id']) ?>">
                                <?php echo $item['title']; ?></a></td>
                        <td><a id="<?php echo $item['id']; ?>"
                               href="<?php echo base_url('admin/admin_knowledge/view?id='.$item['id']) ?>">
                            <?php echo mb_substr(str_replace("&nbsp;","",strip_tags($item['content'])),0,30) ?></a></td>
                        <?php if($haveModifyRole){?>
                            <td><?php if($item['is_show']){echo(lang("file_is_show"));}else{echo(lang("file_is_hide"));} ?></td>
                        <?php }?>
                        <td><?php echo $item['create_time'] ?></td>
                        <td><?php echo $admin_user_info[$item['admin_id']] ?></td>
                        <td>
                            <a id="<?php echo $item['id']; ?>"
                               href="<?php echo base_url('admin/admin_knowledge/view?id='.$item['id']) ?>">
                                <?php echo lang('view'); ?></a>
                    <?php if($haveModifyRole){?>
                            / <a href="<?php echo base_url('admin/admin_knowledge/add_or_update?type=2&id='.$item['id']) ?>"><?php echo lang('edit'); ?></a> /
                            <a id="<?php echo $item['id']; ?>" class="delete_admin_knowledge" href="#"><?php echo lang('delete'); ?></a>
                    <?php }?>
                        </td>
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
                        url:"<?php echo base_url('admin/admin_knowledge/do_delete'); ?>",
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