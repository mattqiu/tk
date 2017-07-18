<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
<!-- 用户信息 -->
<div class="block ">
    <p class="block-heading"><?php echo lang('view_detail_info') ?></p>
    <div class="block-body">
        <form id="user_commissions" method="post" action="commission_special_check/fix_user_commission_check">
        <div class="row-fluid">
            <div class="row-fluid">
            <input type="text" name="user_id" id="user_id" value="" placeholder="<?php echo lang('user_id')?>">
            </div>
            <div class="row-fluid" style="padding: 5px 0px 5px 0px;">
            <label><input name="user_month_info" id="user_month_info" checked="checked" type="checkbox" value="1" style="width:18px;height:18px;margin-right:10px;" /><?php echo lang("admin_show_user_monthly"); ?></label>
            </div>
            <div class="row-fluid" style="padding: 5px 0px 5px 0px;">
            <label><input name="day_bonus" id="day_bonus"  type="checkbox" value="6" style="width:18px;height:18px;margin-right:10px;" /><?php echo lang("admin_show_day_bonsu_monthly");?></label>
            </div>
            <div class="row-fluid" style="padding: 5px 0px 5px 0px;">
            <div>
            <label style="float: left;"><input name="day_bonus" id="day_bonus"  type="checkbox" value="25" style="width:18px;height:18px;margin-right:10px;" /><?php echo lang("admin_show_week_bonsu_monthly"); ?></label>
           
            <label style="float: left;margin-left:25px;"><input name="day_bonus" id="day_bonus"  type="checkbox" value="1" style="width:18px;height:18px;margin-right:10px;" /><?php echo lang("admin_show_month_bonsu_monthly"); ?></label>
            </div>
            </div>
            <div class="row-fluid">
            <input id="user_info_btn" style="width:100px;" onclick="get_user_info()" autocomplete="off" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
            </div>
        </div>
        </form>
    </div>
    <div class="block-body" id="user_score"></div>
</div>

<div class="block ">
    <p class="block-heading"><?php echo lang('fix_user_commission') ?></p>
    <div class="block-body">
        <form id="fix_user_commissions" method="post" action="commission_special_check/fix_user_commission">
        <div class="row-fluid">
            <input type="text" name="uid" id="uid"  value="" placeholder="<?php echo lang('user_id')?>">
            <select name="item_type" id="item_type">
                <option value="">--<?php echo lang('pls_sel_comm_item')?>--</option>
                <?php foreach($commList as $v){ ?>      
                      <?php if(in_array($v, array(6,25,1,23))){ ?>
                        <option value="<?php echo $v ?>"><?php echo lang(config_item('funds_change_report')[$v]) ?></option>
                       <?php } ?>                              
                <?php }?>
            </select>
            <input class="Wdate span2 time_input" type="text" name="start" id="start" value="" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('start_date') ?>">
            -
            <input class="Wdate span2 time_input" type="text" name="end" id="end" value="" onClick="WdatePicker({dateFmt: 'yyyy-MM-dd', lang: '<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('end_date') ?>">

            <input id="user_bonus_btn" onclick="user_bonus_info()"  autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
           
        </div>
        </form>
    </div>
    <div class="block-body" id="user_bonus_show"></div>
</div>

<script>
    
    function get_user_info() {
    	var chk_value =[]; 
		var user_id = $('#user_id').val();
		if(user_id.length==0)
		{
			layer.msg("<?php echo lang("confirm_user_id");?>");
			return;
		}	
		$('input[name="day_bonus"]:checked').each(function(){ 
		chk_value.push($(this).val()); 
		}); 

		var user_month_show =$('input[name="user_month_info"]:checked').val();
		
        if ($('#user_info_btn').val() == $('#loadingTxt').val()) {
            return false;
        }
        $('#user_info_btn').val($('#loadingTxt').val());
        $('#user_info_btn').css("background", "#858C8F");
		$.ajax({
			type: "POST",
			url: "/admin/commission_special_check/fix_user_commission_check",
			data: {user_id: user_id,bonus_type:chk_value},
			dataType: "json",
			success: function (res) {
				console.log(res.msg);
				if (res.success) {
					
					var msg =res.msg.monthly;				
					var len = msg.length;
					var sale_rank = "";
					var user_rank = "";
					switch(parseInt(res.msg.user_info['sale_rank']))
					{
						case 1:
							sale_rank ="<?php echo lang("title_level_1"); ?>";
							break;
						case 2:
							sale_rank ="<?php echo lang("title_level_2"); ?>";
							break;
						case 3:
							sale_rank ="<?php echo lang("title_level_3"); ?>";
							break;
						case 4:
							sale_rank ="<?php echo lang("title_level_4"); ?>";
							break;
						case 5:
							sale_rank ="<?php echo lang("title_level_5"); ?>";
							break;
						default:
							sale_rank ="<?php echo lang("title_level_0"); ?>";
							break;
					}
					
					switch(parseInt(res.msg.user_info['user_rank']))
					{
						case 1:
							user_rank ="<?php echo lang("admin_user_level_p"); ?>";
							break;
						case 2:
							user_rank ="<?php echo lang("admin_user_level_g"); ?>";
							break;
						case 3:
							user_rank ="<?php echo lang("admin_user_level_s"); ?>";
							break;
						case 4:
							user_rank ="<?php echo lang("admin_user_level_f"); ?>";
							break;
						case 5:
							user_rank ="<?php echo lang("admin_user_level_b"); ?>";
							break;
						default:
							user_rank ="<?php echo lang("admin_user_level_f"); ?>";
							break;
					}
					
					var str_div = "<table class=\"table\"><tr><td><?php echo lang("label_feedback_userid"); ?>："+res.msg.user_info['uid']+"</td><td style='color:#f57403;'><?php echo lang("admin_user_rank");?>："+user_rank+"</td><td><?php echo lang("user_up_time"); ?>："+res.msg.user_info['user_rank_up_time']+"</td><td style='color:#f57403;'><?php echo lang("mvp_professional_title"); ?>："+sale_rank+"</td><td><?php echo lang("user_sale_up_time"); ?>："+res.msg.user_info['sale_rank_up_time']+"</td></tr></table>";

					if(user_month_show==1)
					{
						str_div += "<table class=\"table\"><tr><td><?php echo lang("score_month"); ?></td><td><?php echo lang("store_sale_amount"); ?></td></tr>";
						for(var idx = 0; idx < len;idx++ )
						{						
							str_div +=`<tr><td>${msg[idx].year_month}</td><td>$${msg[idx].sale_amount/100}</td></tr>`;
						}
					}
					
					str_div+="</table>";
					str_div +="<table class=\"table\">";
					//每天全球利润分红
					var day_bonus_info = res.msg.day_bonus;
					if(day_bonus_info!="")
					{						
						switch(parseInt(res.msg.day_bonus.user_rank))
						{
							case 5:
								user_rank ="<?php echo lang("admin_user_level_p"); ?>";
								break;
							case 4:
								user_rank ="<?php echo lang("admin_user_level_g"); ?>";
								break;
							case 3:
								user_rank ="<?php echo lang("admin_user_level_s"); ?>";
								break;
							case 2:
								user_rank ="<?php echo lang("admin_user_level_f"); ?>";
								break;
							case 1:
								user_rank ="<?php echo lang("admin_user_level_b"); ?>";
								break;
							default:
								user_rank ="<?php echo lang("admin_user_level_f"); ?>";
								break;
						}
						str_div += "<tr><td rowspan='2' style='width:250px;'><?php echo lang("admin_day_bonus_list");?></td><td><?php echo lang("store_sale_amount"); ?></td><td><?php echo lang("admin_user_rank");?></td><td colspan='2'></td></tr>";
						str_div +=`<tr><td>$${res.msg.day_bonus.amount/100}</td><td>${user_rank}</td><td colspan='2'></td></tr>`;						
					}

					//每周团队销售分红
					var week_bonus_info = res.msg.week_bonus_member;
					if(week_bonus_info!="")
					{						
						var user_sale_ranks = Math.sqrt(week_bonus_info.sale_rank_weight);
						switch(parseInt(user_sale_ranks))
						{
							case 1:
								sale_rank ="<?php echo lang("title_level_1"); ?>";
								break;
							case 2:
								sale_rank ="<?php echo lang("title_level_2"); ?>";
								break;
							case 3:
								sale_rank ="<?php echo lang("title_level_3"); ?>";
								break;
							case 4:
								sale_rank ="<?php echo lang("title_level_4"); ?>";
								break;
							case 5:
								sale_rank ="<?php echo lang("title_level_5"); ?>";
								break;
							default:
								sale_rank ="<?php echo lang("title_level_0"); ?>";
								break;
						}
						str_div += "<tr><td rowspan='2' style='width:250px;'><?php echo lang("admin_week_bonus_list");?></td><td><?php echo lang("store_sale_amount"); ?></td><td><?php echo lang("mvp_professional_title"); ?></td><td><?php echo lang("admin_user_rank");?></td><td>分红点</td></tr>";
						str_div +=`<tr><td>$${res.msg.week_bonus_member.sale_amount_weight/100}</td>
						<td>${sale_rank}</td>
						<td>${res.msg.week_bonus_member.store_rank_weight}</td><td>${res.msg.week_bonus_member.share_point_weight}</td></tr>`;	
					}

					//每月团队销售分红
					var month_bonus_info = res.msg.month_bonus_member;
					if(month_bonus_info!="")
					{
						var user_sale_ranks = Math.sqrt(month_bonus_info.sale_rank_weight);
						switch(user_sale_ranks-1)
						{
							case 1:
								sale_rank ="<?php echo lang("title_level_1"); ?>";
								break;
							case 2:
								sale_rank ="<?php echo lang("title_level_2"); ?>";
								break;
							case 3:
								sale_rank ="<?php echo lang("title_level_3"); ?>";
								break;
							case 4:
								sale_rank ="<?php echo lang("title_level_4"); ?>";
								break;
							case 5:
								sale_rank ="<?php echo lang("title_level_5"); ?>";
								break;
							default:
								sale_rank ="<?php echo lang("title_level_0"); ?>";
								break;
						}
						str_div += "<tr><td rowspan='2' style='width:250px;'><?php echo lang("admin_month_bonus_list"); ?></td><td><?php echo lang("store_sale_amount"); ?></td><td><?php echo lang("mvp_professional_title"); ?></td><td><?php echo lang("admin_user_rank");?></td><td></td></tr>";
						str_div +=`<tr><td>$${month_bonus_info.sale_amount_weight/100}</td>
						<td>${sale_rank}</td>
						<td>${month_bonus_info.store_rank_weight}</td><td></td></tr>`;	
					}
					
					str_div +="</table>";
					
					$('#user_score').html(str_div);
					$('#error_msg').text("");
					 setTimeout(function(){	                        
	                        //按钮可提交
	                        $('#user_info_btn').val("<?php echo lang('submit'); ?>");
	                        $('#user_info_btn').css("background", "#446688");
	                    },3000);
				} else {
					$('#error_msg').text(res.msg);
					$('#user_score').html("");
                    setTimeout(function(){
                        $('#error_msg').text('')
                        //按钮可提交
                        $('#user_info_btn').val("<?php echo lang('submit'); ?>");
                        $('#user_info_btn').css("background", "#446688");
                    },3000);
				}
			}
		});
	}

    
    function user_bonus_info() {
    	
		var user_id = $('#uid').val();			
		var item = $('#item_type').val();			
		var start = $('#start').val();			
		var end = $('#end').val();			

		if(user_id.length==0)
		{
			layer.msg("<?php echo lang("confirm_user_id");?>");
			return;
		}	

		if(item.length==0)
		{
			layer.msg("<?php echo lang("pls_sel_comm_item");?>");
			return;
		}			

		if(start.length==0 || end.length==0 )
		{
			layer.msg("<?php echo lang("no_time_all_null");?>");
			return;
		}			
		
        $('#user_bonus_btn').val($('#loadingTxt').val());
        $('#user_bonus_btn').css("background", "#858C8F");
		$.ajax({
			type: "POST",
			url: "/admin/commission_special_check/fix_user_commission",
			data: {uid: user_id,item_type:item,start:start,end:end},
			dataType: "json",
			success: function (res) {
				console.log(res.msg);
				if (res.success) {
					
					var msg =res.msg;				
					var len = msg.length;
					var str_div="";
					var sum_amount = 0;
					str_div += "<table class=\"table\"><tr><td><?php echo lang("commission_number"); ?></td><td><?php echo lang("create_time"); ?></td></tr>";
					for(var idx = 0; idx < len;idx++ )
					{						
						str_div +=`<tr><td>$${msg[idx].amount/100}</td><td>${msg[idx].time}</td></tr>`;
						sum_amount += msg[idx].amount/100;
					}
															
					str_div +="<tr><td><?php echo lang("admin_user_level_t"); ?>：$"+sum_amount.toFixed(2)+"</td></tr></table>";
					
					$('#user_bonus_show').html(str_div);
					$('#error_msg').text("");
					 setTimeout(function(){	                        
	                        //按钮可提交
	                        $('#user_bonus_btn').val("<?php echo lang('submit'); ?>");
	                        $('#user_bonus_btn').css("background", "#446688");
	                    },3000);
				} else {
					layer.msg(res.msg);
					$('#user_bonus_show').html("");
                    setTimeout(function(){
                        $('#error_msg').text('')
                        //按钮可提交
                        $('#user_bonus_btn').val("<?php echo lang('submit'); ?>");
                        $('#user_bonus_btn').css("background", "#446688");
                    },3000);
				}
			}
		});
	}
	
</script>