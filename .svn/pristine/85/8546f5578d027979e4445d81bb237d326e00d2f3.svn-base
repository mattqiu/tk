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
<style>
    .cont_style
    {
	   padding:10px;
    }
    
 .cont_style_left
    {
	   padding:10px;
    }
</style>

<div class="block ">
	
	   <form id="upload_form" name="upload_form"  action="<?php echo base_url('admin/trade/admin_upload_freight_info_by_orderid') ?>"  method="post" class="form-inline" enctype="multipart/form-data">
		<div class="block-body">
		    <div class="row-fluid cont_style">
            		<input autocomplete="off" class="input-mini" type="file" id="excelfile" name="excelfile"/>            	
			</div>
			<div class="row-fluid cont_style">
			<?php echo lang("orderid_users_config");?>: 
    			<select name="par_set" id="par_set" onchange="user_conf(this.value)" style="width:100px;">
    			     <option value="0"><?php echo lang("orderid_default_config"); ?></option>
    			     <?php if(1==$user_rooter){ ?>
    			     <option value="1"><?php echo lang("orderid_users_config");?></option>
    			     <?php } ?>
    			</select>
			</div>
			
			<!-- 默认配置 -->
			<div class="row-fluid cont_style" id="default_conf">
			     <input type="checkbox" name="order_cover" id="order_cover" checked="checked" disabled="disabled" value="1"> <?php echo lang("orderid_freight_info_cv"); ?>			     
			</div>
			<!-- /默认配置 -->
			
			<!-- 参数配置 -->
			<div class="row-fluid cont_style" id="user_set_conf" style="display:none;">
    			<div>
    			 <input type="checkbox" name="order_null" id="order_null" checked="checked" disabled="disabled" value="1"> <?php echo lang("orderid_freight_info_null"); ?>
    			</div>
    			<div style="margin-top:10px;">
    			 <?php echo lang("admin_order_status"); ?>：
    			     <select name="order_status" id="order_status" style="width:100px;">
    			         <option value="0" ><?php echo lang("please_choose");?></option>
    			         <option value="3"><?php echo lang("admin_order_status_paied"); ?></option>
    			         <option value="1"><?php echo lang("admin_order_status_init"); ?></option>
    			         <option value="4"><?php echo lang("admin_order_status_delivered"); ?></option>
    			     </select>     
    			</div>			    
			</div>
			<!-- /参数配置 -->
			
			
			<div class="row-fluid cont_style_left">
    		    <input id='submit_button' autocomplete="off"  value="<?php echo lang('submit');?>" class="btn btn-primary" type="submit">
			</div>
            <div class="row-fluid cont_style_left"><span style="color: red"><?php echo lang('order_modify_order_freight');?></span></div>
            <div class="row-fluid cont_style_left">
                <img src="../../img/zh/excel_format.png">   
            </div>
		</div><!--/end block-body -->
    </form>


</div>

<?php if (isset($err_msg)): ?>
	<div class="well">
		<p style="color: red;"><?php echo $err_msg; ?></p>
	</div>
<?php endif; ?>

<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

	function user_conf(strs)
	{
		if(1==strs)
		{
			$("#user_set_conf").show();
			$("#default_conf").hide();
		}
		else
		{
			$("#user_set_conf").hide();
			$("#default_conf").show();
		}
	}

    function errboxHtml(msg) {
    	return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
    }

    
    $('#upload_form').submit(function() {

		if ($('[name=excelfile]').val() == '') {
			layer.msg('<?php echo lang('admin_select_file')?>');
			return false;
		}

		if(1==$("#par_set").val())
		{
			if(0==parseInt($("#order_status option:selected").val()))
			{
				layer.msg('<?php echo lang('pls_t_status'); ?>');
				return false;
			}
		}
		
		var li ;
		$('#submit_button').attr('disabled', true);
		$(this).ajaxSubmit({
			dataType: 'json',
			success: function(res) {
				if (res.success) {
					layer.msg(res.msg);
					setTimeout(function(){
						location.reload();
					}, 2000)
				} else {
					$.thinkbox(errboxHtml(res.msg));
				}
			},
			error: function() {
				layer.msg('<?php echo lang('admin_request_failed')?>');
			},
			beforeSend: function() {
				li = layer.load();
			},
			complete: function() {
				layer.close(li);
				$('#submit_button').attr('disabled',false);
			}
		});
		
		return false;
	});
   
</script>