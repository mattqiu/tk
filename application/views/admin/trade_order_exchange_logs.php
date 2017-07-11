<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">

    <ul class="nav nav-tabs">
        <?php foreach ($tab_map as $k => $v): ?>
            <li <?php if ($k == $fun) echo " class=\"active\""; ?>>
                <a href="<?php echo base_url($v['url']); ?>">
                    <?php echo $v['desc']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <form class="form-inline" method="GET">
        <input type="text" name="order_id" value="<?php echo $searchData['order_id'];?>" class="input-medium search-query mbot10" placeholder="<?php echo lang('order_id')?>">
        <select name='status'>
            <?php foreach($status_map as $k => $item){?>
                <option <?php if($searchData['status'] == $k){?>selected="selected"<?php }?> value="<?php echo $k;?>"><?php echo $item;?></option>
            <?php }?>
        </select>

        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end']; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="">
    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th><?php echo lang('order_id'); ?></th>
            <th><?php echo lang('type'); ?></th>
            <th><?php echo lang('remark'); ?></th>
            <th><?php echo lang('operator_id'); ?></th>
            <th><?php echo lang('update_time'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td>
                    <!-- 客服经理和管理员可执行此操作 -->
                    <?php if(in_array($adminInfo['role'],array('0', '2'))){?>
                    <a href="javascript:;" onclick="delTimer('<?php echo trim($item['order_id']); ?>');">
                        <?php echo $item['order_id'] ?>
                    </a>
                    <?php } else {?>
                        <?php echo $item['order_id'] ?>
                    <?php }?>
                    </td>
                    <td><?php echo $item['status_mark'];?></td>
                    <td><?php echo $item['statement'] ?></td>
                    <td><?php echo $item['operator_id'] ?></td>
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
<script type="text/javascript">
    // 清除计时器
    function delTimer(orderid) {
        if (orderid == '') return false;

        layer.confirm("<?php echo lang('exchange_timer_reset');?>", {
            icon: 3,
            title: "<?php echo lang('cancel_exchange');?>",
            closeBtn: 2,
            btn: ['<?php echo lang('yes');?>', '<?php echo lang('no');?>']
        }, function() {
            $.ajax({
                type: "POST",
                url: "/admin/trade_order_logs/del_timer_ajax",
                dataType: "json",
                data:{orderid:orderid},
                success: function (res) {
                    if (res.success) {
                        window.location.reload();
                        layer.msg(res.msg);
                    }else{
                        layer.msg(res.msg);
                    }
                }
            });
        });
    }
</script>