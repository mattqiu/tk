<!--<div class="btn-toolbar">
    <button class="btn">Export</button>
    <div class="btn-group">
    </div>
</div>-->

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $start_time;?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date')?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $end_time;?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date')?>">
        
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('time'); ?></th>
                <th><?php echo lang('money'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($pointToMoneyLogs){ ?>
            <?php foreach ($pointToMoneyLogs as $row) { ?>
                <tr>
                    <td><?php echo $row['create_time'] ?></td>
                    <td>$ <?php echo $row['amount'] ?></td>
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
<?php echo $pager;