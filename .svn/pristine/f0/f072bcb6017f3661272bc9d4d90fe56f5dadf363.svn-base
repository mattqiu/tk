<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="well">
	
	<form id="search_form" name="search_form" class="form-inline" method="GET">
                <input style="float:left;margin:0px 5px 0px 0px;" type="text" name="id" value="<?php echo $search_arr['id'];?>" class="input-medium search-query" placeholder="<?php echo lang('login_name')?>">
		<select id="sear_year" name="year" style="width:100px;float:left;">
		  <?php for($year = 2015; $year <= 2050;$year++){ if($year<= date("Y")){ ?>
                          <option value="<?php echo $year; ?>" <?php if($search_arr['year']==$year){ ?> selected="selected" <?php } ?>><?php echo $year; ?></option>
                  <?php } } ?>
		</select>
		<div style="float:left;margin:5px 5px 0px 5px;">年</div>
		<select id="sear_month" name="month" style="width:60px;float:left;">		
		  <?php for($month = 1; $month <= 12;$month++){ ?>
                          <option value="<?php echo $month; ?>" <?php if($search_arr['month']==$month){ ?> selected="selected" <?php } ?>><?php echo $month; ?></option>
		  <?php  } ?>
		</select>
		<div style="float:left;margin:5px 5px 0px 5px;">月</div>
                <button class="btn" id="submit_button" type="button"><i class="icon-search"></i> <?php echo lang('search') ?></button>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"><?php echo isset($code) && $code==1003 ? lang('uid_not_null') :"";?><?php echo isset($code) && $code==1004 ? lang('no_exist') :"";?></span>
	</form>
        <div class="block-body">
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
                                    [ <?php echo lang('2x5_commission') ?> ]: $ <?php echo isset($commission_item['1']) ? $commission_item['1'] : '0.00' ?>
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
                    <div class="row-fluid comm_infinite" style="color:red;font-weight:bold;">
                         <?php echo lang('commission_month_sum') ?> : $ <?php echo $commission_item ? array_sum($commission_item) : '0.00';?>
                    </div>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<style>
	.tbodynew tr:nth-child(1)
	{
	   background:#eee;
	}
</style>

<script>
  $("#submit_button").click(function(){
        var idEmail = $("#search_form input[name='id']").val();
        $("#search_form").submit();
    })
</script>
