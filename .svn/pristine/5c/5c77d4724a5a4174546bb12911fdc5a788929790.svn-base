<div class="block">
    <p class="block-heading"><?php echo lang('accumulation_commission'); ?></p>
    <div class="block-body my-total-point">
        $ <?php echo $accumulation_commission; ?>
    </div>
</div>

<!--<div class="btn-toolbar">
    <button class="btn">Export</button>
    <div class="btn-group">
    </div>
</div>-->

<div class="block">
    <p class="block-heading"><?php echo lang('commission_log'); ?></p>
    <div class="block-body">
        <script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
        <div class="search-well">
            <form class="form-inline" method="GET">
                <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $start_time; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
                -
                <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $end_time; ?>" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">

                <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
            </form>
        </div>

        <div class="well">
            <table class="table">
                <thead>
                    <tr>
                        <th><?php echo lang('time'); ?></th>
                        <th><?php echo lang('order_id'); ?></th>
                        <th><?php echo lang('commission'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item) { ?>
                        <tr>
                            <td><?php echo $item['create_time'] ?></td>
                            <td><?php echo $item['order_id'] ?></td>
                            <td>$ <?php echo $item['money'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php echo $pager; ?>
    </div>
</div>