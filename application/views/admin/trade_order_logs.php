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
        <select name='oper_code'>
            <?php foreach($order_oper_map as $k => $item){?>
                <?php if($searchData['oper_code'] != '0'){?>
                        <?php if($k == $searchData['oper_code']){?>
                                <option selected value="<?php echo $k?>"><?php echo lang($item)?></option>
                        <?php }else{?>
                                <option  value="<?php echo $k?>"><?php echo lang($item)?></option>
                        <?php }?>
                <?php }?>
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
                    <td><?php echo $item['order_id'] ?></td>
                    <td>
                        <?php echo lang(config_item('order_oper_map')[$item['oper_code']]) ?>
                    </td>
                    <td><?php echo $item['statement'] ?></td>
                    <td><?php echo $item['operator_id'] ?></td>
                    <td><?php echo $item['update_time'] ?></td>
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