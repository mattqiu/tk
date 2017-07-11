<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<div class="search-well">
	<ul class="nav nav-tabs">
		<?php foreach ($tabs_map as $k => $v): ?>
			<li <?php if ($k == $tabs_type) echo " class=\"active\""; ?>>
				<a href="<?php echo base_url($v['url']); ?>">
					<?php echo $v['desc']; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="block ">
	<form id='commChangeForm'>
		<div class="block-body">
			<div class="row-fluid">
				<?php echo lang('user_id');?>: <input style="width:190px" id="user_id" type="text" name="uid" value="">
			</div>
			<div class="row-fluid">
				<?php echo lang('please_sel_level');?>: 
				<select name="levelValue" id="levelValue" style="width:110px;">
                    <option value="0"><?php echo lang('please_sel_level');?></option>
                    <option value="1"><?php echo lang('title_level_1'); ?></option>
                    <option value="2"><?php echo lang('title_level_2'); ?></option>
                    <option value="3"><?php echo lang('title_level_3'); ?></option>
                    <option value="4"><?php echo lang('title_level_4'); ?></option>
                </select>
                <input style="width:20px" id="user_num" type="text" name="user_num" value="" >
			</div>
			<div class="row-fluid">
				<?php echo lang('title_level_0');?>: 
				<select name="userinfovalue" id="userinfovalue" style="width:110px;">
                    <option value="0"><?php echo lang('please_sel_level');?></option>   
                    <option value="1"><?php echo lang('title_level_0'); ?></option>         
                </select>
                <input style="width:20px" id="user_so_num" type="text" name="user_so_num" value="" >
			</div>
			<div class="row-fluid">
		        <?php echo lang('user_sale_up_time');?>：
			<input class="Wdate span2 time_input" type="text" name="end" id="end" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'zh'})" placeholder="<?php echo lang('user_sale_up_time');?>">
			</div>		
			<div class="row-fluid">
    				<input id='rollbackSub' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
				<span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
			</div>
		</div>

</form>
</div>
<div  class="block ">

<div class="well">
		<?php echo lang("find");?>
	</div>
<form id='commChangeForm_search' >
		<div class="block-body">
			<div class="row-fluid">
				<?php echo lang('user_id');?>: <input style="width:190px" id="user_id_search" type="text" name="user_id_search" value="">
			
    				<input id='rollbackSub_search' autocomplete="off" name="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" type="button">
				
			</div>
		</div>
		<div id="user_score"></div>

</form>
</div>


<?php if (isset($err_msg)): ?>
	<div class="well">
		<p style="color: red;"><?php echo $err_msg; ?></p>
	</div>
<?php endif; ?>
<script>
	$("#rollbackSub").click(function () {
		var user_id = $('#user_id').val();
		var up_time = $('#end').val();	
		var up_level = $('#levelValue').val();	
		var up_num = $('#user_num').val();
		var userinfovalue = $('#userinfovalue').val();
		var user_so_num = $('#user_so_num').val();
		
        //防重复提交
        if ($('#rollbackSub').val() == $('#loadingTxt').val()) {
            return false;
        }
        $('#rollbackSub').val($('#loadingTxt').val());
        $('#rollbackSub').css("background", "#858C8F");
		$.ajax({
			type: "POST",
			url: "/admin/repair_abnormality_sale/user_appellation_sale",
			data: {uid: user_id,u_time:up_time,u_level:up_level,u_num:up_num,infovalue:userinfovalue,so_num:user_so_num},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					
					$('#submit_success').text(res.msg);
					
					$('#error_msg').text("");
                    setTimeout(function(){
                        $('#submit_success').text('')
                        //按钮可提交
                        $('#rollbackSub').val("<?php echo lang('submit'); ?>");
                        $('#rollbackSub').css("background", "#446688");
                    },3000);
				} else {
					$('#error_msg').text(res.msg);
					$('#submit_success').text("");
                    setTimeout(function(){
                        $('#error_msg').text('')
                        //按钮可提交
                        $('#rollbackSub').val("<?php echo lang('submit'); ?>");
                        $('#rollbackSub').css("background", "#446688");
                    },3000);
				}
			}
		});
	});

	
	$("#rollbackSub_search").click(function () {
		var user_id = $('#user_id_search').val();
		
        //防重复提交
        if ($('#rollbackSub_search').val() == $('#loadingTxt').val()) {
            return false;
        }
        $('#rollbackSub_search').val($('#loadingTxt').val());
        $('#rollbackSub_search').css("background", "#858C8F");
		$.ajax({
			type: "POST",
			url: "/admin/repair_abnormality_sale/get_user_appellation_sale",
			data: {user_id_search: user_id},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					
					var msg =res.msg;				
					var len = msg.length;
					var str_div = "<table class=\"table\"><tr><td>uid</td><td>group_id</td><td>mso_num</td><td>sm_num</td><td>sd_num</td><td>vp_num</td></tr>";
					for(var idx = 0; idx < len;idx++ )
					{						
						str_div +=`<tr><td>${msg[idx].uid}</td><td>${msg[idx].group_id}</td><td>${msg[idx].mso_num}</td><td>
						${msg[idx].sm_num}</td><td>${msg[idx].sd_num}</td><td>${msg[idx].vp_num}</td></tr>`;
					}
					str_div+="</table>";
					$('#user_score').html(str_div);
					$('#error_msg').text("");
					 setTimeout(function(){
	                        
	                        //按钮可提交
	                        $('#rollbackSub_search').val("<?php echo lang('submit'); ?>");
	                        $('#rollbackSub_search').css("background", "#446688");
	                    },3000);
				} else {
					$('#error_msg').text(res.msg);
					$('#user_score').html("");
                    setTimeout(function(){
                        $('#error_msg').text('')
                        //按钮可提交
                        $('#rollbackSub_search').val("<?php echo lang('submit'); ?>");
                        $('#rollbackSub_search').css("background", "#446688");
                    },3000);
				}
			}
		});
	});
	
</script>