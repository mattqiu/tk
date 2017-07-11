
<div class="search-well">
<table>
    <tr>
     
        <td>
            <span><?php echo lang('user_id') ?></span>
            <span id="maxLimit" style="color: #009900;margin-left: 20px;"></span>
        </td>
        
    </tr>
	<form method="post"  id="month_fee_to_amount">
            <td><input class="uid_txt" type="text"  name="uid_txt" id="uid_txt" value=""></td>
            <td><input type="button" id="submit_id" class="btn btn-primary" value=<?php echo lang('confirm') ?> ></td>
            <td>
                <span id="error_msg" style="color: #ff0000;margin-left: 20px;"></span>
                <span  id="submit_success" style="color: #009900; font-size: 12px;">
            </td>
        </form>
  </tr>
</table>


	<form class="form-inline" method="GET">
		
		<input class="input-small" id="uid" type="text" name="uid" placeholder="<?php echo lang('user_id'); ?>" />
		
		<button class="btn" type="submit"><i class="icon-search"></i><?php echo lang('search'); ?></button>
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
				<th><?php echo lang('action'); ?></th>
			</tr>
		</thead>
		<tbody>
		 <?php if ($list){ ?>
            <?php foreach ($list as $item) { ?>
                <tr>
                    <td><?php echo $item['uid'] ?></td>
                   
                    <td>
                    	<a class="btn btn-primary" onclick="deletes('<?php echo $item['uid'] ?>');" href="javascript:"><?php echo lang('label_goods_delete') ?></a>
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
	$('#status').children('[value=' + page_data.status + ']').prop('selected', true);
	$('#storehouse').children('[value=' + page_data.storehouse + ']').prop('selected', true);
	$('#order_type').children('[value=' + page_data.order_type + ']').prop('selected', true);
	if (page_data.uid != null) {
		$('#uid').val(page_data.uid);
	}
	
	if (page_data.store_id != null) {
		$('#store_id').val(page_data.store_id);
	}
	if (page_data.tracking_num != null) {
		$('#tracking_num').val(page_data.tracking_num);
	}if (page_data.txn_id != null) {
		$('#txn_id').val(page_data.txn_id);
	}
	if (page_data.start_date != null) {
		$('#start_date').val(page_data.start_date);
	}
	if (page_data.end_date != null) {
		$('#end_date').val(page_data.end_date);
	}
});


</script>
<script>
    $(function() {
		//鼠标离开验证订单号
        $(".uid_txt").blur(function () {
            var uid_txt = $(this).val();
            $('#month_fee_to_amount input').val(uid_txt);
            $.ajax({
                success: "success",
                url: "/admin/user_email_exception_list/checkOrderId",
                dataType: "json",
                type: "post",
                data: {uid_txt: uid_txt},
                success: function (res) {
                    if (res.success) {
                        $('#maxLimit').text(res.msg);
                        $('#amount_pool').val(res.amount)
                        $('#error_msg').text("");
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#maxLimit').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        });
	
		
		//提交
        $("#submit_id").click(function(){
            var uid_txt=$('#uid_txt').val();
            $.ajax({
                success: "success",
                url: "/admin/user_email_exception_list/checkData",
                dataType: "json",
                type: "post",
                data: {uid_txt:uid_txt},
                success: function (res) {
                    if (res.success) {
                        $('#submit_success').text(res.msg);
                        $('#error_msg').text("");
                        setTimeout("$('#submit_success').text('')", 3000);
						location.reload('');
                    } else {
                        $('#error_msg').text(res.msg);
                        $('#submit_success').text("");
                        setTimeout("$('#error_msg').text('')", 3000);
                    }
                }
            });

        })
    })
	//删除一条记录
function deletes(id){
	
				layer.confirm("<?php echo lang('is_uid_delete'); ?>", {
					icon: 3,
					title: "<?php echo lang('is_delete_uid'); ?>",
					closeBtn: 2,
					btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
				}, function(index){

                    var remark = $('#cancel_remark').val();
					var refund_type = $("input[name='refund_type']:checked").val()?$("input[name='refund_type']:checked").val():'';
					//layer.close(index);
					$.ajax({
		url: '/admin/user_email_exception_list/do_delete_freight',
		type: "POST",
		data: {'id': id},
		dataType: "json",
		success: function(data) {
			if (data.success) {
				location.reload('');
				layer.msg(data.msg);
				return false;
			}else {
				location.reload('');
				layer.msg(data.msg);
				return false;
               }
					
					},
		error: function(data) {
			console.log(data.responseText);
		}
	});
					
				});
				
				//
		
	
	//
}
</script>