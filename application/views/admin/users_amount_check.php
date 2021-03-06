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
        <button class="btn" type="button" onclick="add_account_log()">批量修复记录</button>
        <button class="btn" type="button" onclick="sel_check()">批量删除</button>
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
			    <th></th>
				<th><?php echo lang('user_id'); ?></th>
				<th>转账金额</th>
				<th>转账用户</th>
				<th>转账时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
		 <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><input type="checkbox" name="chose_id" id="chose_id" style="width:18px;height: 18px;" value="<?php echo $item['id'];?>" /></td>
                    <td><?php echo $item['uid']; ?></td>
                    <td>$<?php echo $item['amount']/100; ?></td>
                    <td><?php echo $item['relate_uid']; ?></td>
                    <td>                        
					 <?php echo $item['transfer_time'];?>
					 </td>
					 <td>
					<a style="cursor:pointer;" onclick="add_user_account_log(<?php echo $item['id'];?>)"> 添加记录</a> 
					<a style="cursor:pointer;" onclick="del_user_account(<?php echo $item['id'];?>)">删除</a> 
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


function add_user_account_log(str)
{

	layer.confirm("<?php echo lang('confirm_add_account_log'); ?>", {
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
             url: '/admin/users_amount_check/add_user_account_log',
             data: {user_id:str},
             dataType: "json",
             success: function (data) {                 
                 if (data.success) {                
                	 window.location.href = '/admin/users_amount_check';
                 }           
                 curEle.attr("disabled", false);
             }
         });
	});
}

function del_user_account(str)
{

	layer.confirm("<?php echo lang('btn_del_option'); ?>", {
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
             url: '/admin/users_amount_check/del_user_account_option',
             data: {user_id:str},
             dataType: "json",
             success: function (data) {                 
                 if (data.success) {                
                	 window.location.href = '/admin/users_amount_check';
                 }           
                 curEle.attr("disabled", false);
             }
         });
	});
}

function sel_check()
{
	var chk_value =[]; 
	$('input[name="chose_id"]:checked').each(function(){ 
	chk_value.push($(this).val()); 
	}); 
	
	if(chk_value.length==0)
	{
		layer.msg("<?php echo lang("seleted_input_null");?>");
		return;
	}
	
	layer.confirm("<?php echo lang('btn_del_option'); ?>", {
		icon: 3,
		title: "<?php echo lang('sys_supplier_title'); ?>",
		closeBtn: 2,
		btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
	},function(){ 
		$.ajax({
            type:'POST',
            url: '/admin/users_amount_check/del_user_account_option_all',
            data: {user_id:chk_value},
            dataType: "json",
            success: function (data) {                 
                if (data.success) {                
               	 window.location.href = '/admin/users_amount_check';
                }           
                curEle.attr("disabled", false);
            }
        });
	});
}

function add_account_log()
{
	var chk_value =[]; 
	$('input[name="chose_id"]:checked').each(function(){ 
	chk_value.push($(this).val()); 
	}); 
	
	if(chk_value.length==0)
	{
		layer.msg("<?php echo lang("seleted_input_null");?>");
		return;
	}
	
	layer.confirm("<?php echo lang('confirm_add_account_log'); ?>", {
		icon: 3,
		title: "<?php echo lang('sys_supplier_title'); ?>",
		closeBtn: 2,
		btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
	},function(){ 
		$.ajax({
            type:'POST',
            url: '/admin/users_amount_check/add_user_account_all_log',
            data: {user_id:chk_value},
            dataType: "json",
            success: function (data) {                 
                if (data.success) {                
               	 window.location.href = '/admin/users_amount_check';
                }           
                curEle.attr("disabled", false);
            }
        });
	});
}


</script>
