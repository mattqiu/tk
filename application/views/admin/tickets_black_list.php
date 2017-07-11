<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<div class="search-well">
    <form action="<?php echo base_url('admin/tickets_black_list'); ?>" class="form-inline" method="GET" style="margin-bottom: 10px;">
        <input autocomplete="off" value="<?php if(isset($searchData['uid'])){echo $searchData['uid'];} ?>" name="uid" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control tickets_input_box_trim"  placeholder="<?php echo lang('pls_t_uid'); ?>" />
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
    <input maxlength="10" autocomplete="off" value="" name="add_black_uid" type="text" style="border-radius: 4px;display: inline-block;width: 180px;" class="form-control add_black_uid tickets_input_box_trim"  placeholder="<?php echo lang('pls_t_uid'); ?>" />
    <button class="btn add_black_btn" type="button"><i class="icon-add"></i><?php echo lang('add') ?></button>
</div>
<div class="well">
    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th><?php echo lang('id'); ?></th>
            <th><?php echo lang('black_uid'); ?></th>
            <th><?php echo lang('tickets_handler'); ?></th>
            <th><?php echo lang('time'); ?></th>
            <th></th>
        </tr>
        </thead>
        <?php if(!empty($list)){
            foreach($list as $v){ ?>
                <tr class="">
                    <td class="id"><?php echo $v['id'] ?></td>
                    <td class="black_uid"><?php echo $v['uid']; ?></td>
                    <td class="black_admin_name"><?php echo explode('@',$v['email'])[0]; ?></td>
                    <td><?php echo $v['create_time'] ?></td>
                    <?php ?>
                    <td class="handle">
                        <a class="delete_black_list" id="<?php echo $v['id'] ?>" href="javascript:"><?php echo lang('delete'); ?></a>
                    </td>
                    <?php ?>
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

<?php echo $pager;?>
<script>
    $(function() {
        $('.delete_black_list').click(function () {
            var id = $(this).attr('id');
            layer.confirm('<?php echo lang('confirm_delete_black_list'); ?>', {
                icon: 3,
                title: '<?php echo lang('confirm'); ?>'
            }, function (index) {
                $.ajax({
                    type: "post",
                    url: "<?php echo base_url('admin/tickets_black_list/delete_tickets_black_list') ?>",
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
    $('.add_black_btn').click(function(){
         var uid = $('.add_black_uid').val();
        if((uid=='' || isNaN(uid) && uid.length>10)){
            layer.msg("<?php echo lang('pls_t_correct_ID');?>");
            return;
        }
        $.ajax({
            type: "post",
            url: "<?php echo base_url('admin/tickets_black_list/add_tickets_black_list') ?>",
            data: {uid: uid},
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
</script>
