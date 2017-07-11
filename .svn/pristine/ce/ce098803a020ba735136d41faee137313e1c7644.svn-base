<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<style>
   .handle{width: 70px;}
    .t_name{width: 80px;}
    .content{max-width: 800px;}
</style>

<div class="search-well">
    <form action="<?php echo base_url('admin/tickets_template'); ?>" class="form-inline" method="GET" style="margin-bottom: 10px;">
        <input autocomplete="off" value="<?php if(isset($searchData['tickets_template_name'])){echo $searchData['tickets_template_name'];} ?>" name="tickets_template_name" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control" id="tickets_template_name" placeholder="<?php echo lang('pls_t_t_name'); ?>" />
        <select name="type" style="width: 180px;">
            <option value=""><?php echo lang('type'); ?></option>
            <?php foreach (config_item('tickets_problem_type') as $key => $value) { ?>
                <option value="<?php echo $key ?>"
                    <?php if ($searchData['type'] >= '0' && $key == $searchData['type']) {
                        echo " selected=selected";
                    }
                    ?> >
                    <?php echo lang($value); ?>
                </option>
            <?php } ?>
        </select>
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
    <button class="btn add_tpl" type="button"><i class="icon-add"></i><?php echo lang('add') ?></button>
</div>
<div class="well">
    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th><?php echo lang('id'); ?></th>
            <th><?php echo lang('template_name'); ?></th>
            <th><?php echo lang('type');?></th>
            <th><?php echo lang('template_author'); ?></th>
            <th><?php echo lang('content'); ?></th>
            <th><?php echo lang('time'); ?></th>
            <th><?php echo lang('is_public'); ?></th>
            <th></th>
        </tr>
        </thead>
        <?php if(!empty($list)){
            foreach($list as $v){
                $temp = array(0=>'template_is_public',1=>'template_not_public'); ?>
                <tr class="">
                    <td class="id"><?php echo $v['id'] ?></td>
                    <td class="t_name"><div style="text-align: left"><?php echo $v['name']; ?></div></td>
                    <td><?php if(isset(config_item('tickets_problem_type')[$v['type']])){ echo lang(config_item('tickets_problem_type')[$v['type']]);}  ?></td>
                    <td class="admin_id"><?php echo explode('@',$v['email'])[0]; ?></td>
                    <td  class="content"><div style="text-align: left"><?php echo mb_substr($v['content'],0,50,'utf-8');if(mb_strlen($v['content'],'utf8')>50){echo '...';} ?></div> </td>
                    <td><?php echo $v['create_time'] ?></td>
                    <td class="status"><?php echo lang($temp[$v['status']])?></td>
                    <?php ?>
                    <td class="handle">
                        <?php if($adminInfo['id']==$v['admin_id'] || in_array($adminInfo['id'],array(68,62,144))){ ?>
                            <a class="update" id="<?php echo $v['id'] ?>" href="javascript:"><?php echo lang('admin_as_update')?></a>/<a id="<?php echo $v['id'] ?>" class="delete" href="javascript:"><?php echo lang('delete'); ?></a>
                        <?php }else{?>
                            <a class="view_tpl" id="<?php echo $v['id'] ?>" href="javascript:"><?php echo lang('view')?></a>
                        <?php }?>
                    </td>
                    <?php ?>
                </tr>
            <?php }
        }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item')?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php echo $pager;?>
<script>
    $(function() {

        $('.delete').click(function () {
            var id = $(this).attr('id');
            layer.confirm('<?php echo lang('confirm_delete_template'); ?>', {
                icon: 3,
                title: '<?php echo lang('confirm'); ?>',
                btn:['<?php echo lang('confirm'); ?>','<?php echo lang('cancel'); ?>']
            }, function (index) {
                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('admin/tickets_template/delete_tickets_template') ?>",
                    data: {id: id},
                    dataType: "json",
                    async: true,
                    success: function (res) {
                        if (res.success == 1) {
                            layer.msg(res.msg);
                            window.location.reload();
                        } else {
                            layer.msg(res.msg);
                        }
                    }
                });
            });
        });
        $('.add_tpl').click(function(){
            layer.open({
                type: 2,
                area: ['600px', '500px'],
                fix: true,
                shadeClose:true,
                maxmin:true,
                title:"<?php echo lang('admin_as_update')?>",
                content: '<?php echo base_url('admin/tickets_template/add_tickets_template'); ?>',
                end:function(){
                    window.location.reload();
                }
            });
        });

        $('.update').click(function(){
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                area: ['600px', '500px'],
                fix: true,
                shadeClose:true,
                maxmin:true,
                title:"<?php echo lang('admin_as_update')?>",
                content: '<?php echo base_url('admin/tickets_template/update_tickets_template'); ?>'+'/'+id,
                end:function(){
                    window.location.reload();
                }
            });
        });

        $('.view_tpl').click(function(){
            var id = $(this).attr('id');
            layer.open({
                type: 2,
                area: ['600px', '500px'],
                fix: true,
                shadeClose:true,
                maxmin:true,
                title:"<?php echo lang('admin_as_update')?>",
                content: '<?php echo base_url('admin/tickets_template/view_tickets_template'); ?>'+'/'+id,
                end:function(){
                    window.location.reload();
                }
            });
        });
    });
</script>
