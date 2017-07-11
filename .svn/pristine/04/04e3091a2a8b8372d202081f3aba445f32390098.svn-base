<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<style>
   .handle{width: 70px;}
    .t_name{width: 80px;}
    .content{max-width: 800px;}
</style>

<!--<div class="search-well">-->
<!--    <button class="btn add_tpl" type="button"><i class="icon-add"></i>--><?php //echo lang('add') ?><!--</button>-->
<!--</div>-->
<div class="well">
    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th><?php echo lang('job_number'); ?></th>
            <th><?php echo lang('name'); ?></th>
            <th><?php echo lang('tickets_customer_permission');?></th>
            <th><?php echo lang('time'); ?></th>
            <th><?php echo lang('action'); ?></th>
            <th></th>
        </tr>
        </thead>
        <?php if(!empty($list)){?>
            <tbody>
            <?php foreach($list as $v){ ?>
                <tr>
                    <td class="job_number"><?php echo $v['job_number'] ?></td>
                    <td class="name"><?php echo explode('.',$v['email'])[0]; ?></td>
                    <td><?php if($v['role']==2){echo lang('tickets_customer_role_2');}else{ echo lang('tickets_customer_role_1');} ?></td>
                    <td><?php echo $v['create_time']; ?></td>
                    <td class="handle">
                        <a id="<?php echo $v['id']; ?>" aid="<?php echo $v['admin_id']; ?>" name="<?php echo $v['role']; ?>" class="update" href="javascript:"><?php echo lang('update'); ?></a>
                    </td>
                </tr>
            <?php }
        }else{ ?>
            <tr>
                <th colspan="10" style="text-align: center;" class="text-success"> <?php echo lang('no_item')?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    $(function() {
        $('.delete,.update').click(function () {
            var role  = $(this).attr('name')?$(this).attr('name'):1;
            var role1 = $(this).attr('name');
            var id    = $(this).attr('id');
            var aid   = $(this).attr('aid');
            var tips  = role==1?'<?php echo lang('confirm_update_customer_1'); ?>':'<?php echo lang('confirm_update_customer_2'); ?>';
            layer.confirm(tips, {
                icon: 3,
                title: '<?php echo lang('confirm'); ?>',
                btn:['<?php echo lang('confirm'); ?>','<?php echo lang('cancel'); ?>']
            }, function (index) {
                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('admin/tickets_customer_role/customer_role_action') ?>",
                    data: {id: id,role1:role1,aid:aid},
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
    });
</script>
