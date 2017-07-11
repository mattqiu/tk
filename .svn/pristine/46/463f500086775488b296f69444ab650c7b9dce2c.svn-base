<div class="" style="">
    <p class="" style="color:#50505;padding-left: 1em; font-weight: bold; font-size: 1em; line-height: 3em;">
        <span style=''><?php echo lang('today_commission'); ?>: $ <?php echo tps_money_format($today_commission);?></span>
        <span style='padding-left:25px;'><?php echo lang('current_month_commission'); ?>: $ <?php echo tps_money_format($current_month_commission);?></span>
    </p>
    
</div>

<!-- 当用佣金统计 start -->
<div class="block " style="margin: 1em 0em 1.5em 0em;">
    <p class="block-heading">
        <span style=''><?php echo lang('current_month_comm');  ?></span>
    </p>
    <div class="block-body">
        <table class="tb" width="80%">
            <tr style="width:33%;">
				<td>
					<div class="row-fluid person_sale">
						[ <?php echo lang('personal_sale') ?> ]: $ <?php echo isset($commData[5])?$commData[5]/100:"0.00";  ?>
						<!--<p class="title">[ <?php echo lang('personal_sale') ?> ]</p>
                        <div class="total_stat_amount">$ <?php echo $userInfo['amount_store_commission']; ?></div>-->
					</div>
				</td>

                <td>
                    <div class="row-fluid comm_team">
                    	[ <?php echo lang('group_sale') ?> ]: $ <?php echo isset($commData[3])?$commData[3]/100:"0.00";   ?>
                        <!--<p class="title">[ <?php echo lang('group_sale') ?> ]</p>
                        <div class="total_stat_amount">$ <?php echo $userInfo['team_commission'] ?></div>-->
                    </div>
                </td>
				<td>
					<div class="row-fluid week_profit_sharing">
                        [ <?php echo lang('day_profit_sharing') ?> ]: $ <?php if(isset($commData[6])){?><?php echo tps_money_format($commData['6']/100); ?><?php }else{?>0.00<?php };?>
						<!--<p class="title">[ <?php echo lang('day_profit_sharing') ?> ]</p>
                        <div class="total_stat_amount">$ <?php echo $userInfo['amount_profit_sharing_comm'] ?></div>-->
					</div>
				</td>

             </tr>
             <tr style="width:33%;">
				 <td>
					 <div class="row-fluid comm_25"> 
						 [ <?php echo lang('2x5_force_matrix') ?> ]: $ <?php if(isset($commData[1])){?><?php echo tps_money_format($commData[1]/100); ?><?php }else{?>0.00<?php };?>
						 <!--<p class="title">[ <?php echo lang('2x5_commission') ?> ]</p>
                        <div class="total_stat_amount">$ <?php echo $userInfo['personal_commission'] ?></div>-->
					 </div>
				 </td>
				 <td>
					 <div class="row-fluid comm_month_leader">
						 [ <?php echo lang('month_leader_profit_sharing') ?> ]: $ <?php echo isset($commData[8])?$commData[8]/100:"0.00"; ?>
						
					 </div>
				 </td>
				 <td>
					 <div class="row-fluid comm_138">
						 [ <?php echo lang('plan_9') ?> ]: $ <?php if(isset($commData[2])){?><?php echo tps_money_format($commData[2]/100); ?><?php }else{?>0.00<?php };?>
					 </div>
				 </td>
             </tr>

             <tr style="width:33%;">
                <td>
                    <div class="row-fluid comm_week_leader">
                    	[ <?php echo lang('week_leader_matching') ?> ]: $ <?php if(isset($commData[7])){?><?php echo tps_money_format($commData[7]/100); ?><?php }else{?>0.00<?php };?>
                    </div>
                </td>

                <td>
                    <div class="row-fluid" style="color:#666;">
                        [ <?php echo lang('month_middel_leader_profit_sharing') ?> ]: $ <?php if(isset($commData[23])){?><?php echo tps_money_format($commData[23]/100); ?><?php }else{?>0.00<?php };?>
                    </div>
                </td>

                <td>
                     <div class="row-fluid comm_infinite">
                         [ <?php echo lang('group_sale_infinity') ?> ]: $ <?php  echo isset($commData[4])?$commData[4]/100:"0.00"; ?>
                        
                     </div>
                 </td>
            </tr>
            <tr>
                <td>
                    <div class="row-fluid comm_infinite">
                        [ <?php echo lang('daily_top_performers_sales_pool') ?> ]: $ <?php if(isset($commData[24])){?><?php echo tps_money_format($commData[24]/100); ?><?php }else{?>0.00<?php };?>
                    </div>
                </td>
                <td>
                    <div class="row-fluid comm_infinite">
                        [ <?php echo lang('plan_week_share') ?> ]: $ <?php if(isset($commData[25])){?><?php echo tps_money_format($commData[25]/100); ?><?php }else{?>0.00<?php };?>
                    </div>
                </td>
                <td>
                    <div class="row-fluid comm_infinite">
                        [ <?php echo lang('new_member_bonus') ?> ]: $ <?php if(isset($commData[26])){?><?php echo tps_money_format($commData[26]/100); ?><?php }else{?>0.00<?php };?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="row-fluid comm_infinite">
                        [ <?php echo lang('supplier_recommendation') ?> ]: $ <?php if(isset($commData[27])){?><?php echo tps_money_format($commData[27]/100); ?><?php }else{?>0.00<?php };?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<!-- 当月佣金统计 end -->

<?php  if(!$korea_hide)   {  ?>

<!-- 历史月份各项佣金查询 start -->
<div  class="block " style="margin: 1em 0em 1.5em 0em;">
    <p class="block-heading">
        <span style=''><?php echo lang('comm_statis_history');  ?></span>
    </p>
    <div class="block-body">
    <div class="search-well">
        <form class="form-inline" method="GET" id="search_form">

            <select id="sear_year" name="year" style="width:100px;">
                <option value="0"><?php echo lang('please_choose'); ?></option>
              <?php for($year = 2015; $year <= 2050;$year++){ if($year<= date("Y")){ ?>
                              <option value="<?php echo $year; ?>" <?php if($search_arr['year']==$year){ ?> selected="selected" <?php } ?>><?php echo $year; ?></option>
                      <?php } } ?>
            </select>
            -
            <select id="sear_month" name="month" style="width:100px;">
                <option value="0"><?php echo lang('please_choose'); ?></option>
              <?php for($month = 1; $month <= 12;$month++){ ?>
                              <option value="<?php echo $month; ?>" <?php if($search_arr['month']==$month){ ?> selected="selected" <?php } ?>><?php echo $month; ?></option>
              <?php  } ?>
            </select>
            <button class="btn" id="submit_button"  type="button"><i class="icon-search"></i> <?php echo lang('search') ?></button>
        </form>
        <?php  if(isset($commission_item))   {     ?>
        <table class="tb" width="80%">
            <?php if(isset($code)){ ?>
                <tr>
                    <th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_search') ?></th>
                </tr>
            <?php }else{ ?>
                <tr style="width:33%;">
                    <td>
                        <div class="row-fluid person_sale">
                                [ <?php echo lang('personal_sale') ?> ]: $ <?php echo isset($commission_item['5']) ? $commission_item['5'] : '0.00' ?>
                        </div>
                    </td>
                    <td>
                        <div class="row-fluid comm_team">
                            [ <?php echo lang('group_sale') ?> ]: $ <?php echo isset($commission_item['3']) ? $commission_item['3'] : '0.00' ?>
                        </div>
                    </td>
                    <td>
                        <div class="row-fluid week_profit_sharing">
                                [ <?php echo lang('day_profit_sharing') ?> ]: $ <?php echo isset($commission_item['6']) ? $commission_item['6'] : '0.00' ?>
                        </div>
                    </td>

                 </tr>
                 <tr style="width:33%;">
                        <td>
                            <div class="row-fluid comm_25">
                                    [ <?php echo lang('2x5_force_matrix') ?> ]: $ <?php echo isset($commission_item['1']) ? $commission_item['1'] : '0.00' ?>
                            </div>
                        </td>
                        <td>
                            <div class="row-fluid comm_month_leader">
                                    [ <?php echo lang('month_leader_profit_sharing') ?> ]: $ <?php echo isset($commission_item['8']) ? $commission_item['8'] : '0.00' ?>
                            </div>
                        </td>
                        <td>
                            <div class="row-fluid comm_138">
                                    [ <?php echo lang('plan_9') ?> ]: $ <?php echo isset($commission_item['2']) ? $commission_item['2'] : '0.00' ?>
                            </div>
                        </td>
                 </tr>

                 <tr style="width:33%;">
                    <td>
                        <div class="row-fluid comm_week_leader">
                            [ <?php echo lang('week_leader_matching') ?> ]: $ <?php echo isset($commission_item['7']) ? $commission_item['7'] : '0.00' ?>
                        </div>
                    </td>

                    <td>
                        <div class="row-fluid">
                            [ <?php echo lang('month_middel_leader_profit_sharing') ?> ]: $ <?php echo isset($commission_item['23']) ? $commission_item['23'] : '0.00' ?>
                        </div>
                    </td>

                    <td>
                         <div class="row-fluid comm_infinite">
                             [ <?php echo lang('group_sale_infinity') ?> ]: $ <?php echo isset($commission_item['4']) ? $commission_item['4'] : '0.00' ?>
                         </div>
                     </td>
                </tr>
                <tr>
                    <td>
                        <div class="row-fluid comm_infinite">
                            [ <?php echo lang('daily_top_performers_sales_pool') ?> ]: $ <?php echo isset($commission_item['24']) ? $commission_item['24'] : '0.00' ?>
                        </div>
                    </td>
                    <td>
                        <div class="row-fluid comm_infinite">
                            [ <?php echo lang('plan_week_share') ?> ]: $ <?php echo isset($commission_item['25']) ? $commission_item['25'] : '0.00' ?>
                        </div>
                    </td>
                    <td>
                        <div class="row-fluid comm_infinite">
                            [ <?php echo lang('new_member_bonus') ?> ]: $ <?php echo isset($commission_item['26']) ? $commission_item['26'] : '0.00' ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="row-fluid comm_infinite">
                            [ <?php echo lang('supplier_recommendation') ?> ]: $ <?php echo isset($commission_item['27']) ? $commission_item['27'] : '0.00' ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                    <div class="row-fluid comm_infinite" style="font-weight:bold;">
                         <?php echo lang('commission_month_sum') ?> : $ <?php echo $commission_item ? tps_money_format(array_sum($commission_item)) : '0.00';?>
                    </div>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php  }  ?>
    </div>
    </div>
 <script>
  $("#submit_button").click(function(){
        var year = $('#sear_year option:selected').val();
        var month = $('#sear_month option:selected').val();
        if(year == 0) {
            layer.msg("<?php echo lang('no_year');  ?>");
            return;
        }
        
        if(month == 0) {
            layer.msg("<?php echo lang('no_month');  ?>");
            return;
        }
        $("#search_form").submit();
    })
</script>
</div>
<?php  }  ?>
