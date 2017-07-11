
<div class="well" style="margin-right: 20px; position: relative;">
	<table class="mytable" style="margin-bottom: 0px;">		
		<tbody id="well_6">
			<tr>
				<td align="left" width="75%" style="float: left;text-align:left;"><?php echo lang('pre_week_team_bonus'); ?></td>
				
				<td align="left" width="25%" style="float: left">				 				
				 				
			     <input type="button" id="submit_sql" onclick="reset_pre_bonus(25)" class="btn btn-primary" value="<?php echo lang('pre_bonus_submit'); ?>">			     
	             <input type="button" id="submit_check" onclick="check_info(25)" style="margin-left: 10px;" class="btn btn-primary" value="<?php echo lang('view_detail_info'); ?>">
	             
				</td>
			</tr>
			<tr>
				<td align="left" width="75%" style="float: left;text-align:left;"><?php echo lang('pre_month_team_bonus'); ?></td>
				
				<td align="left" width="25%" style="float: left">
			     <input type="button" id="submit_sql" onclick="reset_pre_bonus(1)" class="btn btn-primary" value="<?php echo lang('pre_bonus_submit'); ?>">
			     <input type="button" id="submit_check" onclick="check_info(1)" style="margin-left: 10px;" class="btn btn-primary" value="<?php echo lang('view_detail_info'); ?>">
				</td>
			</tr>
			<tr>
				<td align="left" width="75%" style="float: left;text-align:left;"><?php echo lang('pre_month_leader_bonus'); ?></td>
				
				<td align="left" width="25%" style="float: left">
			     <input type="button" id="submit_sql" onclick="reset_pre_bonus(23)" class="btn btn-primary" value="<?php echo lang('pre_bonus_submit'); ?>">
			     <input type="button" id="submit_check" onclick="check_info(23)" style="margin-left: 10px;" class="btn btn-primary" value="<?php echo lang('view_detail_info'); ?>">
				</td>
			</tr>
		</tbody>
	</table>
	
</div>

<style>
.mytable {
	width: 100%;
	margin-bottom: 20px;
}

.mytable {
	max-width: 100%;
	background-color: transparent;
	border-collapse: collapse;
	border-spacing: 0;
}

.mytable tr td {
	line-height: 20px;
}

.mytable th, .mytable td {
	padding: 10px 0;
	line-height: 22px;
	text-align: center;
}
</style>

<script>

function show_msg(item)
{
	
	$.ajax({
        type:'POST',
        url: '/admin/grant_user_bonus_option/get_pre_bonus_state',
        data: {item_type:item},
        dataType: "json",
        success: function (data) 
        {           
          if(data.state!=3)
          {     
        	  setInterval(show_msg(item),1000);
          }	
          else
          {              
        	  window.location.href = '/admin/grant_user_bonus_option';
          }
        }
	 });      
	         
}

function reset_pre_bonus(item_types)
{

	layer.confirm("<?php echo lang('pre_bonus_submit'); ?>", {
		icon: 3,
		title: "<?php echo lang('pre_bonus_submit'); ?>",
		closeBtn: 2,
		btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
	},function(){       
		var index = layer.load(1, {
		  shade: [0.5,'#000000'] //0.1透明度的白色背景
		});
    	curEle = $(this);
    	curEle.attr("disabled", true);	
    	 $.ajax({
             type:'POST',
             url: '/admin/grant_user_bonus_option/pre_user_bonus',
             data: {item_type:item_types,pre_type:1},
             dataType: "json",
             success: function (data) {
                 if (data.success) {                
                	 show_msg(item_types);
                 }           
                 curEle.attr("disabled", false);
             }
         });
	});
}

function check_info(item)
{
	switch(item)
	{
	case 25:
		window.location.href="/admin/pre_week_team_bonus";
		break;
	case 1:
		window.location.href="/admin/pre_month_team_bonus";
		break;
	case 23:
		window.location.href="/admin/pre_month_leader_bonus";
		break;
	}
}


</script>
