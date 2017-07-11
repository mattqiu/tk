<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input type="text" name="admin_id" value="<?php echo $searchData['admin_id'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('role_super')?>">
        <input type="text" name="content" value="<?php echo $searchData['content'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('content')?>">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
        <div class="pull-right">
            <button class="btn add_blacklist" type="button"><i class="icon-plus"></i><?php echo lang('add_blacklist'); ?></button>
        </div>
    </form>
</div>
<script>
    $(function () {
        $('.add_blacklist').click(function(){
            layer.open({
                type: 2,
                area: ['600px', '200px'],
                fix: true,
                shadeClose:true,
                maxmin:true,
                title:"<?php echo lang('add_blacklist')?>",
                content: '<?php echo base_url('admin/blacklist/view_add_blacklist'); ?>',
                end:function(){
                    window.location.reload();
                }
            });
        });
    });
</script>
<div class="well">
    <table class="table">
        <thead>
        <tr>
            <th><?php echo lang('id'); ?></th>
            <th><?php echo lang('content'); ?></th>
            <th><?php echo lang('role_super'); ?></th>
            <th><?php echo lang('time'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['id'] ?></td>
                    <td><?php echo $item['content'] ?></td>
                    <td><?php echo $item['admin_id'] ?></td>
                    <td><?php echo $item['create_time'] ?></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $pager;?>