<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
	<form class="form-inline" method="post" action="users_bonus_list_check/user_bonus_list_checks">	       
	      
	    <div class="search-well">
	       <input style="width:190px" placeholder="<?php echo lang("user_id"); ?>" id="user_id" type="text" name="user_id" value="">
	    </div>    
	    
	    <div class="search-well">
	     <select name="item_type" id="item_type" style="width:204px;" onchange="show_sele_option(this.value)">
                <option value="">--<?php echo lang('pls_sel_comm_item')?>--</option>
                <?php foreach($commList as $v){?>           
                        <?php if(in_array($v, array(1,6,24,25))) { ?>       
                            <option value="<?php echo $v ?>"><?php echo lang(config_item('funds_change_report')[$v]) ?></option>                              
                        <?php }?>
                <?php }?>
         </select>
         <select name="user_monthly" id="user_monthly" style="width:100px;" onchange="show_sale_input(this.value)">
                <option value="">--<?php echo lang('pls_sel_comm_item')?>--</option>              
         </select>
         <input type="text" name="sale_rank" id = "sale_rank" value="" style="display: none;width:50px;">
	    </div>	    
	    
	    
		<div class="search-well">
        <button class="btn" type="button"  onclick="user_conf()"><?php echo lang('submit'); ?></button> 
       
       </div>
		
		
	</form>
</div> 

<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>

<?php
	if (isset($paginate))
	{
		echo $paginate;
	}
?>

<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

function errboxHtml(msg) {
	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
}

function show_sele_option(str)
{
	var str_option = "<option value=''>--<?php echo lang('pls_sel_comm_item')?>--</option>";	
	switch(parseInt(str))
	{	
		case 1:
			str_option += "<option value='1'>业绩</option><option value='2'>职称权重</option>";
			break;
    	case 6:
    		str_option += "<option value='1'>业绩</option>";
    		break;
    	case 24:
    		str_option += "<option value='2'>分红点权重</option>";
    		break;
    	case 25:
    		str_option += "<option value='1'>业绩</option><option value='2'>职称权重</option>";
    		break;
	}	
	$("#user_monthly").html(str_option);
}


function show_sale_input(str)
{
	if(parseInt(str)==2)
	{
		$("#sale_rank").show();
	}
	else
	{
		$("#sale_rank").hide();
	}
}


function user_conf()
{
	var user_id = $('#user_id').val();			
	var item = $('#item_type').val();			
	var user_month = $('#user_monthly').val();			
	var sale_rank = $('#sale_rank').val();			

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
	
	layer.confirm("<?php echo lang('admin_exchange_user_info_content'); ?>", {
		icon: 3,
		title: "<?php echo lang('sys_supplier_title'); ?>",
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
             url: '/admin/users_bonus_list_check/user_bonus_list_checks',
             data: {user_id:user_id,item_type:item,user_monthly:user_month,sale_rank:sale_rank},
             dataType: "json",
             success: function (data) {                 
                 if (data.success) {                
                	 window.location.href = '/admin/users_bonus_list_check';
                 }           
                 curEle.attr("disabled", false);
             }
         });
	});
}


</script>
