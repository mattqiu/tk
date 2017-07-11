<!-- <div class="search-well">
    <form class="form-inline" method="GET">
        
        <select name="comm_type" class="user_ranks_sel mbot10">
            <option value="">---<?php echo lang('commission_type');?>---</option>
            <?php foreach(config_item('commission_type_for_order_repair') as $key=>$value){?>
                <option value="<?php echo $key?>"<?php if($key==$searchData['comm_type']){ echo " selected=selected";}?>><?php echo lang($value)?></option>
            <?php }?>
        </select>
        
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div> -->
<p style='color:red'><?php echo lang('order_repair_notice')?></p>
<p style='color:red'><?php echo lang('order_repair_step')?></p>
<div class="well">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('repair_order_year_month'); ?></th>
                <th><?php echo lang('commission_type'); ?> (<?php echo lang('comm_date'); ?>)</th>
                <th><?php echo lang('commission_withdraw_amount'); ?><br/>(<?php echo lang('if_not_repair_order_before_deadline')?>)</th>
				<th><?php echo lang('sale_amount_lack') ?></th>
                <th><?php echo lang('deadline'); ?>（<?php echo lang('day')?>）</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $k=>$item) { ?>
                <tr>
					<td><?php echo substr($item['order_year_month'],0,4).'-'.substr($item['order_year_month'],4); ?></td>
					<td>
                        <?php echo lang(config_item('commission_type_for_order_repair')[$item['comm_type']]);?>
                        （<?php echo substr($item['comm_year_month'],0,4).'-'.substr($item['comm_year_month'],4); ?>）
					</td>
                    <td><?php echo $item['comm_amount']?'$ '.$item['comm_amount']/100:''; ?></td>
                    <td><?php echo '$ '.$item['sale_amount_lack']/100; ?></td>
                    <td><?php echo 14-computer_flow_days($item['create_time']); ?></td>

                    <?php if($k==0 || $item['order_year_month']!=$list[$k-1]['order_year_month']){?>
                    <td style="text-align: center">
                        
                        <?php if($item['status']){?>
                            <?php echo lang('order_repairing')?>
                        <?php }else{?>
                            <input class='repair_order_button' attr_order_year_month='<?php echo $item['order_year_month']?>' autocomplete="off" name="repair_order" value="<?php echo lang('repair_order');?>" class="btn btn-primary" type="button">
                        <?php }?>
                    </td>
                    <?php }?>
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