<!--<div class="btn-toolbar">
    <button class="btn">Export</button>
    <div class="btn-group">
    </div>
</div>-->

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <form class="form-inline" method="GET">
        <select name="add_source" id="addSource" class="addSouceSelect">
            <option value=""><?php echo lang('pls_sel_profit_sharing_adden_type');?></option>
            <?php foreach($add_source_txt_arr as $key=>$value){?>
            <option value="<?php echo $key?>"<?php if($key==$add_source){ echo " selected=selected";}?>><?php echo $value?></option>
            <?php }?>
        </select>
        
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
                <th><?php echo lang('type'); ?></th>
                <th><?php echo lang('money'); ?></th>
                <th><?php echo lang('sharing_point'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($addPointLogs){ ?>
            <?php foreach ($addPointLogs as $addPointLog) { ?>
                <tr>
                    <td><?php echo date('Y-m-d H:i:s', $addPointLog['create_time']); ?></td>
                    <td><?php echo $add_source_txt_arr[$addPointLog['add_source']]; ?></td>
                    <td>$ <?php echo $addPointLog['money']; ?></td>
                    <td><?php echo $addPointLog['point'] . ' ' . lang('point'); ?></td>
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
<?php
echo $pager;
