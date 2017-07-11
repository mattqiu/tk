<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>

<div class="search-well">
    <form class="form-inline" method="GET">
        
        <select name="type" class="user_ranks_sel mbot10">
            <option value="">---<?php echo lang('type');?>---</option>
            <?php foreach(config_item('monthly_fee_report') as $key=>$value){?>
                <option value="<?php echo $key?>"<?php if($key==$searchData['type']){ echo " selected=selected";}?>><?php echo lang($value)?></option>
            <?php }?>
        </select>
        <input class="Wdate span2 time_input" type="text" name="start" value="<?php echo $searchData['start'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_start_time')?>">
        -
        <input class="Wdate span2 time_input" type="text" name="end" value="<?php echo $searchData['end'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_end_time')?>">
        
        <button class="btn" type="submit"><i class="icon-search"></i> <?php echo lang('search') ?></button>
    </form>
</div>

<div class="well">
    <table class="table">
        <thead>
            <tr>
				<th><?php echo lang('new_month'); ?></th>
				<th><?php echo lang('money_update') ?></th>
                <th><?php echo lang('type'); ?></th>
                <th><?php echo lang('time'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
					<td><?php echo $item['month_fee_pool'].($item['coupon_num']?' ('.$item['coupon_num'].lang('mothlyFeeCoupon').')':''); ?></td>
					<td><?php
						$symbol =  '';
						if(in_array($item['type'],array(1,2,3))){
							$symbol =  '+';
						}
                        if($item['coupon_num_change']>0){
                            $couponSymbol = '+';
                        }else{
                            $couponSymbol = '';
                        }
						echo $symbol.$item['cash'].($item['coupon_num_change']?' ('.$couponSymbol.$item['coupon_num_change'].lang('mothlyFeeCoupon').')':''); ?></td>
                    <td><?php echo lang(config_item('monthly_fee_report')[$item['type']]); ?></td>
                    <td><?php echo $item['create_time']; ?></td>

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