<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
	<form class="form-inline" method="get" action="">
		<input class="Wdate span2 time_input" type="text" id="t_stime" name="t_stime" value="<?php echo $searchData['t_stime'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_start_time')?>" />
        -
        <input class="Wdate span2 time_input" type="text" id="t_end" name="t_end" value="<?php echo $searchData['t_end'];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'<?php echo $curLanguage; ?>'})" class="input-medium search-query" placeholder="<?php echo lang('reg_end_time')?>" />
        <select name="fx" id="fx">
            <option value="0">全部</option>
            <option value="1"><?php echo lang('btn_check'); ?></option>
            <option value="2"><?php echo lang('btn_f_check'); ?></option>
        </select>
        <button class="btn" type="submit" ><i class="icon-search"></i><?php echo lang('users_check_btn'); ?></button>
		<!-- <button class="btn" type="button" onclick="reset_pre_bonus()"><i class="icon-search"></i><?php echo lang('users_check_btn'); ?></button> -->
		
	</form>
</div> 

<?php if (isset($err_msg)): ?>
<div class="well">
	<p style="color: red;"><?php echo $err_msg; ?></p>
</div>
<?php endif; ?>

<div class="well">
	<table class="table">
		<thead>
			<tr>
				<th><?php echo lang('user_id'); ?></th>
				<th>转账金额</th>
				<th>转账用户</th>
				<th>转账时间</th>
			</tr>
		</thead>
		<tbody>
		 <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid']; ?></td>
                    <td>$<?php echo $item['amount']/100; ?></td>
                    <td><?php echo $item['relate_uid']; ?></td>
                    <td>                        
					 <?php echo $item['transfer_time'];?>
					 </td>
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


<!-- 冻结订单添加备注弹层 -->
<div id="div_add_remark" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 20%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3><?php echo lang('fill_in_frozen_remark')?></h3>
	</div>
	<div class="modal-body">
		<table class="tab_add_remark" style="margin: 0 auto">
			<tr>
				<input type="hidden" id="hidden_order_id">
			</tr>
			<tr>
				<td>
					<textarea id="remark_content" autocomplete="off" rows="3" cols="300" style="width: 50%" placeholder="<?php echo lang("fill_in_frozen_remark")?>"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><span id="add_remark_msg" class="msg error" style="margin-left:0px"></span></td>
			</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button autocomplete="off" class="btn btn-primary"  onclick="submit_frozen_remark()" id="add_remark_submit"><?php echo lang('submit'); ?></button>
	</div>
</div>



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

$(function() {
	'use strict';

	var page_data = <?php echo json_encode($page_data); ?>;
	if (page_data.uid != null) {
		$('#uid').val(page_data.uid);
	}
});


$('#fx').val('<?php echo $searchData['fx'];?>');


function show_msg()
{
	
	$.ajax({
        type:'POST',
        url: '/admin/users_amount_check/get_check_status',
        data: '',
        dataType: "json",
        success: function (data) 
        {           
          if(data.state!=1)
          {     
        	  setInterval(show_msg,1000);
          }	
          else
          {              
        	  window.location.href = '/admin/users_amount_check';
          }
        }
	 });     
	         
}



function reset_pre_bonus()
{

	var t_star = $("#start").val(); 
	var t_end = $("#end").val(); 
	var fx = $("#fx option:selected").val();
	layer.confirm("<?php echo lang('users_amount_check'); ?>", {
		icon: 3,
		title: "<?php echo lang('users_amount_check'); ?>",
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
             url: '/admin/users_amount_check/transfer_check',
             data: {t_stime:t_star,t_end:t_end,fx:fx},
             dataType: "json",
             success: function (data) {
                 if (data.success) {                
                	 show_msg();
                 }           
                 curEle.attr("disabled", false);
             }
         });
	});
}






</script>
