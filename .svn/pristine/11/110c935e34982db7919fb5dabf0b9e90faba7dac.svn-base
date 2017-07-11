<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
<script src="<?php echo base_url('themes/admin/lightbox/js/jQueryRotateCompressed.js?v=2'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.js?v=2'); ?>"></script>
<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<div class="search-well">
	<form class="form-inline" method="GET">
		<select class="input-medium" name="status" id="status">
		<?php
			foreach ($status_select as $k => $v)
			{
				echo "<option value=\"{$k}\">{$v}</option>";
			}
		?>
		</select>
		<?php if($storehouse_map){ ?>
		<select class="input-medium" name="storehouse" id="storehouse">
		<?php
		foreach ($storehouse_map as $k => $v)
			{
				echo "<option value=\"{$k}\">{$v}</option>";
			}
		?>
		</select>
		<?php }?>
		<select class="input-medium" name="order_type" id="order_type">
		<?php
			foreach ($order_type as $k => $v)
			{
				echo "<option value=\"{$k}\">{$v}</option>";
			}
		?>
		</select>
		<input class="input-small" id="order_id" type="text" name="order_id" placeholder="<?php echo lang('admin_order_id'); ?>" />
		<input class="input-small" id="uid" type="text" name="uid" placeholder="<?php echo lang('admin_order_uid'); ?>" />
		<input class="input-small" id="store_id" type="text" name="store_id" placeholder="<?php echo lang('admin_order_store_id'); ?>" />    
                <select name="express" id="express" class="input-medium">
                    <option value=""><?php echo lang("all_express");?></option>
                    <?php
                    $new_freight_map = $freight_map;
                    unset($new_freight_map[0]);//去掉自定义
                    foreach ($new_freight_map as $k => $v)
                            {
                                    echo "<option value=\"{$k}\">{$v}</option>";
                            }
                    ?>
                </select>
		<input class="input-small" id="tracking_num" type="text" name="tracking_num" placeholder="<?php echo lang('admin_order_tracking_num'); ?>" />
		<input class="input-small" id="txn_id" type="text" name="txn_id" placeholder="<?php echo lang('txn_id'); ?>" />
		<input class="Wdate span2" id="start_date" type="text" name="start_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('start_date'); ?>">
		<input class="Wdate span2" id="end_date" type="text" name="end_date" onClick="WdatePicker({lang: '<?php echo $curLanguage; ?>'});" placeholder="<?php echo lang('end_date'); ?>">
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
				<th></th>
				<th><?php echo lang('admin_order_id'); ?></th>
				<th><?php echo lang('admin_order_customer'); ?></th>
				<th><?php echo lang('admin_order_store_id'); ?></th>
				<th><?php echo lang('admin_order_goods_amount'); ?></th>
				<th><?php echo lang('admin_order_deliver_fee'); ?></th>
				<th><?php echo lang('admin_order_status'); ?></th>
				<th><?php echo lang('admin_order_expect_deliver_date'); ?></th>
				<th><?php echo lang('check_card'); ?></th>
				<th><?php echo lang('admin_order_operate'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
                if($order_list){
			foreach ($order_list as $v){
				echo "<tr>";
				echo "<td>";
				if ($v['having_remark'] == 1)
				{
					echo "<i class=\"icon-info-sign text-danger\"></i>&nbsp;";
				}
				echo "</td>";
				if($v['doba_order_id']){
					echo "<td><a href=\"javascript:void(0)\" onclick=\"click_order_info('{$v['order_id']}');\">{$v['order_id']}</a><br/><a target=\"_blank\" href=\"https://enterprise.doba.com/members/orders/status?id=".$v['doba_order_id']."\">",$v['doba_order_label'],$v['doba_order_id'],"</a></td>";
				}else {
					echo "<td><a href=\"javascript:void(0)\" onclick=\"click_order_info('{$v['order_id']}');\">{$v['order_id']}</a><br/>".$v['doba_order_label']."</td>";
				}
				echo "<td>{$v['customer']}</td>";
				echo "<td>{$v['shopkeeper_id']}</td>";
				echo "<td>{$v['goods_amount_show']}</td>";
				echo "<td>{$v['deliver_fee_show']}</td>";

                if($v['status'] == '90' && strstr($v['remark'],'#exchange')){
//										echo lang('admin_order_status_holding_exchange');
                    echo "<td class=\"{$status_map[$v['status']]['class']}\">".lang('admin_order_status_holding_exchange')."</td>";
									}else{
                    echo "<td class=\"{$status_map[$v['status']]['class']}\">{$status_map[$v['status']]['text']}</td>";
									}
				echo "<td>{$v['expect_deliver_date']}</td>";
				if(!empty($v['ID_front'])){
					echo "<td>
					<a data-lightbox='pic-{$v['order_id']}' href='".config_item('img_server_url').'\/'."{$v['ID_front']}' class='example-image-link' rel='{$v['ID_no']}' fullname='{$v['consignee']}'>
                            <img alt='not exist' src='".config_item('img_server_url').'\/'."{$v['ID_front']}' class='example-image'></a>
                            <a data-lightbox='pic-{$v['order_id']}' href='".config_item('img_server_url').'\/'."{$v['ID_reverse']}' class='example-image-link' rel='{$v['ID_no']}' fullname='{$v['consignee']}'>
                            <img alt='not exist' src='".config_item('img_server_url').'\/'."{$v['ID_reverse']}' class='example-image'></a>
				    </td>";
				}elseif(!empty($v['ID_no'])){
					echo "<td>{$v['ID_no']}</td>";
				}else{
					echo "<td></td>";
				}
				echo "<td>";
			 	if(in_array($adminInfo['role'],array(0,1,2,3,5,6,7))){
					if (in_array($v['status'],array('2','3')) ||($v['status'] == 1 && ($adminInfo['id'] == 19 || $adminInfo['role'] == 3) ))
					{
                        echo "<a class=\"btn btn-primary\" href=\"".base_url()."admin/trade/order_modify/{$v['order_id']}/".urlencode(trim($_SERVER['REQUEST_URI'], '/'))."\">".lang('modify')."</a>&nbsp;&nbsp;";
					}
					if ($v['status']==1)
					{
					    if(in_array($adminInfo['role'],array(0,2,3,4)))
					    {
                            if($v['order_type'] != 2 && $v['order_type'] != 5) {
                                if (in_array($v['status'], array('1', '2', '3', '111')) && in_array($v['order_type'], array('0', '1', '3', '4'))) {
                                    echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','99');\">" . lang('cancel') . "</button>&nbsp;&nbsp;";
                                }
                            }else {
                                if ($v['pay_time'] < config_item('upgrade_not_3')) {
                                    if (in_array($v['status'], array('1', '2', '3', '111')) && in_array($v['order_type'], array('0', '2', '1', '3', '4'))) {
                                        echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','99');\">" . lang('cancel') . "</button>&nbsp;&nbsp;";
                                    }
                                }elseif(($v['pay_time'] >= config_item('upgrade_exchange') && in_array($v['status'], array('1', '3')) && in_array($v['order_type'], array('2','5'))) || ($v['status'] == '90' && strstr($v['remark'],'#exchange'))) {
                                    //3月3日凌晨之后支付的订单、换货订单在等待发货、正在发货中、等待收货、等待评价、已完成、冻结（等待换货），这六种状态下开放“取消”按钮，提示框里面仅显示“仅取消”选项，不能退代品劵
                                    if (in_array($adminInfo['role'], array('0', '2'))) {
                                        echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','99');\">" . lang('cancel') . "</button>&nbsp;&nbsp;";
                                    }
                                }
                            }
					    }
					}
					else {
                        if ($v['order_type'] != 2 && $v['order_type'] != 5) {
                            if (in_array($v['status'], array('1', '2', '3', '111')) && in_array($v['order_type'], array('0', '1', '3', '4'))) {
                                echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','99');\">" . lang('cancel') . "</button>&nbsp;&nbsp;";
                            }
                        } else {
                            if ($v['pay_time'] < config_item('upgrade_not_3')) {
                                if (in_array($v['status'], array('1', '2', '3', '111')) && in_array($v['order_type'], array('0', '2', '1', '3', '4'))) {
                                    echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','99');\">" . lang('cancel') . "</button>&nbsp;&nbsp;";
                                }
                            }elseif(($v['pay_time'] >= config_item('upgrade_exchange') && in_array($v['status'], array('1',  '3')) && in_array($v['order_type'], array('2','5'))) || ($v['status'] == '90' && strstr($v['remark'],'#exchange'))) {
                                if (in_array($adminInfo['role'], array('0', '2'))) {
                                    //3月3日凌晨之后支付的订单、换货订单在等待发货、正在发货中、等待收货、等待评价、已完成、冻结（等待换货），这六种状态下开放“取消”按钮，提示框里面仅显示“仅取消”选项，不能退代品劵
                                    echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','99');\">" . lang('cancel') . "</button>&nbsp;&nbsp;";
                                }
                            }
                        }

					}
                    if(($v['pay_time'] >= config_item('upgrade_exchange') && in_array($v['status'], array('1',  '3')) && in_array($v['order_type'], array('2','5'))) || ($v['status'] == '90' && strstr($v['remark'],'#exchange'))) {
                        if (in_array($adminInfo['role'], array('0', '2'))) {
                            if (in_array($v['status'], array('4', '5', '6'))) {
                                echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','98');\">" . lang('admin_order_refund') . "</button>&nbsp;&nbsp;";
                            }
                        }
                    }else {
                        if (in_array($v['status'], array('4', '5', '6'))) {
                            echo "<button class=\"btn btn-warning\" type=\"button\" onclick=\"confirm_cancel('{$v['order_id']}','98');\">" . lang('admin_order_refund') . "</button>&nbsp;&nbsp;";
                        }
                    }
					if (in_array($v['status'],array('1','3','4')))
					{
						echo "<button class=\"btn btn-info\" type=\"button\" onclick=\"click_shipping_print('{$v['order_id']}');\">".lang('admin_order_shipping_print')."</button>&nbsp;&nbsp;";
					}
					if (in_array($v['status'],array('111')))
					{
						echo "<button class=\"btn btn-success\" type=\"button\" onclick=\"click_doba_info('{$v['order_id']}');\">".lang('admin_doba_order_fix')."</button>&nbsp;&nbsp;";
					}
					if (in_array($v['status'],array('1','3')))
					{
						echo "<button class=\"btn btn-success\" type=\"button\" onclick=\"click_deliver('{$v['order_id']}');\">".lang('admin_order_operate_deliver')."</button>&nbsp;&nbsp;";
						echo "<button class=\"btn btn-hold\" type=\"button\" onclick=\"click_frozen('{$v['order_id']}');\">".lang('admin_order_status_holding')."</button>&nbsp;&nbsp;";
					}

                    //允许换货按钮，条件：3月3日凌晨之后支付的订单在正在发货中、等待收货、等待评价、已完成，升级订单，权限 客服经理和管理员
                   if(in_array($adminInfo['role'],array('0','2')) && $v['pay_time'] >= config_item('upgrade_exchange') && in_array($v['order_type'], array('2'))) {
                        if (in_array($v['status'],array('1','4','5','6'))) {
                            echo "<button class=\"btn btn-restore\" type=\"button\" onclick=\"click_exchange('{$v['order_id']}');\">" . lang('allow_exchange') . "</button>&nbsp;&nbsp;";
                        }
                    }

                    if(in_array($adminInfo['role'],array('0','2'))) {
                        if ($v['status'] == 90) {
                            echo "<button class=\"btn btn-restore\" type=\"button\" onclick=\"remove_frozen('{$v['order_id']}');\">" . lang('order_remove_frozen') . "</button>&nbsp;&nbsp;";
                        }
                    }

                    echo "<a class=\"btn btn-danger\" href=\"".base_url()."admin/trade/order_remark_manager/{$v['order_id']}/".urlencode(trim($_SERVER['REQUEST_URI'], '/'))."\">".lang('admin_order_remark')."</a>";
				}
				echo "</td>";
				echo "</tr>";
                        }
                        } else{ ?>
                    <tr>
                        <th colspan="10" style="text-align: center;" class="text-success"> <?php echo $error_code==1001 ? lang('no_item')  : lang('search_data'); ?></th>
                    </tr>
                 <?php       }?>
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
					<textarea id="remark_content" autocomplete="off" rows="3" cols="300" style="width: 50%" placeholder="<?php echo lang("fill_in_frozen_remark_2")?>"></textarea>
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
<link rel="stylesheet" href="<?php echo base_url('themes/admin/select2/select2.min.css?v=2'); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('themes/admin/select2/select2.min.js?v=2'); ?>"></script>
<style>
    .select2{    margin-top: -10px;}
</style>
<script>
    $("#storehouse").select2({
		width:434,
    });
    $("#express").select2({
                width:265,
    });
    
var Select = {
del : function(obj,e){
if((e.keyCode||e.which||e.charCode) == 8){
var opt = obj.options[0];
opt.text = opt.value = opt.value.substring(0, opt.value.length>0?opt.value.length-1:0);
}
},
write : function(obj,e){
if((e.keyCode||e.which||e.charCode) == 8)return ;
var opt = obj.options[0];
opt.selected = "selected";
opt.text = opt.value += String.fromCharCode(e.charCode||e.which||e.keyCode);
}
}
function test(){
alert(document.getElementById("select").value);
}
</script>
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
        $('#express').children('[value=' + page_data.express + ']').prop('selected', true);
	if (page_data.order_id != null) {
		$('#order_id').val(page_data.order_id);
	}
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
        if (page_data.express != null) {
            $("#select2-express-container").text($('#express').children('[value=' + page_data.express + ']').text());
        }
        if (page_data.storehouse != null) {
            $("#select2-storehouse-container").text($('#storehouse').children('[value=' + page_data.storehouse + ']').text());
        }
});

function click_doba_info(id) {
	var deliver_box_cont = '';
	deliver_box_cont += '<form style="margin: 20px;" class="form-inline"><div class="input-append">';

	deliver_box_cont += '<input type="text" class="input-small" id="doba_id" placeholder="<?php echo lang('admin_doba_order_id'); ?>" />';
	deliver_box_cont += '<input type="hidden" id="doba_info_order_id" value="' + id + '" />';
	deliver_box_cont += '<button type="button" class="btn" onclick="submit_doba_info();"><?php echo lang('admin_doba_order_request'); ?></button>';
	deliver_box_cont += '</div></form>';

	$.thinkbox(
		deliver_box_cont,
		{
			'title': ' <?php echo lang('admin_doba_order_fix'); ?>',
		}
	);
}

function submit_doba_info() {
	var data = {};

	data.doba_id = $.trim($('#doba_id').val());
	data.order_id = $('#doba_info_order_id').val();

	$.ajax({
		url: '/admin/trade/get_doba_order_info',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				layer.msg('<?php echo lang('admin_doba_order_request_succ'); ?>');
				window.location.reload();
			} else {
				$.thinkbox(errboxHtml("system error"));
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

function click_order_info(id)
{
	var screen_width = document.body.clientWidth;
	if (screen_width > 768) {
		screen_width = screen_width * 3 / 4;
		if (screen_width > 1280) {
			screen_width = 1280;
		}
	}

	var screen_height = document.documentElement.clientHeight;
	if (screen_height > 576) {
		screen_height = screen_height * 3 / 4;
		if (screen_height > 720) {
			screen_height = 720;
		}
	} else {
		screen_height -= 50;
	}

	$.thinkbox.iframe('<?php echo base_url('admin/trade/get_order_info'); ?>/' + id, {
		'title': "<?php echo lang('admin_order_info'); ?>",
		'dataEle': this,
		'unload': true,
		'width': screen_width,
		'height': screen_height,
		'scrolling': "yes"
	});
	return true;
}

function click_shipping_print(id)
{
	var screen_width = document.body.clientWidth;
	if (screen_width > 768) {
		screen_width = screen_width * 3 / 4;
		if (screen_width > 1280) {
			screen_width = 1280;
		}
	}

	var screen_height = document.documentElement.clientHeight;
	if (screen_height > 576) {
		screen_height = screen_height * 3 / 4;
		if (screen_height > 720) {
			screen_height = 720;
		}
	} else {
		screen_height -= 50;
	}

	$.thinkbox.iframe('<?php echo base_url('admin/trade/order_shipping_print'); ?>/' + id, {
		'title': "<?php echo lang('admin_order_shipping_print'); ?>",
		'dataEle': this,
		'unload': true,
		'width': screen_width,
		'height': screen_height,
		'scrolling': "yes"
	});
	return true;
}

function confirm_cancel(id,status)
{
	$.ajax({
		url: '/admin/trade/do_cancel_order_check',
		type: "POST",
		data: {'order_id': id,'status':status},
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				var refundHtml = data.refundHtml;

				layer.confirm("<?php echo lang('admin_order_confirm_cancel'); ?>"+refundHtml, {
					icon: 3,
					title: "<?php echo lang('admin_order_cancel_confirm'); ?>",
					closeBtn: 2,
					btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
				}, function(index) {
//                    var oldVal = $(".layui-layer-btn0").html();
//
                    // $('.layui-layer-btn0').html($("#loadingTxt").val());
                    $('.layui-layer-btn0').attr('disabled', true);

                    var remark = $('#cancel_remark').val();
                    var refund_type = $("input[name='refund_type']:checked").val() ? $("input[name='refund_type']:checked").val() : '';

                    //layer.close(index);
                    if ($('.layui-layer-btn0').html() == $('#loadingTxt').val()) {
                        return false;
                    }

                    if (typeof(remark) != "undefined") {
                        //去掉字符串两边的口空格
                        var remark_qk = remark.replace(/(^\s*)|(\s*$)/g, "");
                        if (!remark_qk == '') {
                            //点击确定后按钮变成处理中，且不可再点击，防止重复提交
                            $('.layui-layer-btn0').html($('#loadingTxt').val());
                            $('.layui-layer-btn0').css("background", "#858C8F");
                        }
                    }else {
                        //点击确定后按钮变成处理中，且不可再点击，防止重复提交
                        $('.layui-layer-btn0').html($('#loadingTxt').val());
                        $('.layui-layer-btn0').css("background", "#858C8F");
                    }
					$.ajax({
						url: '/admin/trade/do_cancel_order',
						type: "POST",
						data: {'order_id': id,'status':status,'refund_type':refund_type,'remark':remark},
						dataType: "json",
						success: function(data) {
							if (data.code == 0) {
								window.location.reload();
							}else if(data.code == 112){
								layer.msg('<?php echo lang('admin_order_lock')?>');
							}else if(data.code == 118){
                                $("#cancel_order_msg").text('<?php echo lang('pls_input_reson')?>');
                                setTimeout(function(){
                                    $("#cancel_order_msg").text('');
                                },3000);
                            }else if(data.code == 111){
								layer.msg('4月份活动订单不能取消、退货！');
							}else if(data.code == 1001){
								layer.msg('升级订单目前不能再取消，可允许换货，换货中的订单处于冻结状态！');
							}else if(data.code == 1003){
								layer.msg('订单正在取消, 请稍后');
							}
						},
						error: function(data) {
							console.log(data.responseText);
						}
					});
				});
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}

//升级订单和
function click_deliver(id)
{
	var deliver_box_cont = '';
	deliver_box_cont += '<form style="margin: 20px;" class="form-inline"><div class="input-append">';
	deliver_box_cont += '<select class="span2" id="deliver_box_code">';
	<?php foreach ($freight_map as $code => $name): ?>
	deliver_box_cont += '<option value="<?php echo $code; ?>"><?php echo $name; ?></option>';
	<?php endforeach; ?>
	deliver_box_cont += '</select>';
	deliver_box_cont += '<input type="text" class="input-small" id="deliver_box_id" placeholder="<?php echo lang('admin_order_deliver_box_id'); ?>" />';
	deliver_box_cont += '<input type="hidden" id="deliver_box_order_id" value="' + id + '" />';
	deliver_box_cont += '<button type="button" class="btn" onclick="submit_deliver();"><?php echo lang('ok'); ?></button>';
	deliver_box_cont += '</div></form>';

	$.thinkbox(
		deliver_box_cont,
		{
			'title': id + ' <?php echo lang('admin_order_deliver_box_title'); ?>',
		}
	);
}

//添加备注弹层
function click_frozen(id){
	$('#div_add_remark').modal();
	$('#hidden_order_id').val(id);
}
function click_exchange(order_id){
    layer.confirm("<textarea id='remark' placeholder='<?php echo lang('pls_input_reson_2');?>'></textarea>", {
        icon: 3,
        title: "<?php echo lang('allow_exchange');?>",
        closeBtn: 2,
        btn: ['<?php echo lang('yes');?>', '<?php echo lang('no')?>']
    }, function() {
        var remark = $('#remark').val();
        $.ajax({
            type: "POST",
            url: "/admin/trade/do_exchange_order",
            dataType: "json",
            data:{order_id:order_id,remark:remark},
            success: function (res) {
                if (res.success) {
                    window.location.reload();
                    layer.msg(res.msg);
                }else{
                    layer.msg(res.msg);
                }
            }
        });
    });
}



//提交备注
function submit_frozen_remark(){
	var order_id = $("#hidden_order_id").val();
	var remark = $("#remark_content").val();
	$.ajax({
		type: "POST",
		url: "/admin/trade/order_frozen",
		data: {order_id:order_id,remark:remark},
		dataType: "json",
		success: function (res) {
			if (res.success) {
				window.location.reload();
				layer.msg(res.msg);
			}else{
				$("#add_remark_msg").text(res.msg);
				setTimeout(
					function() {
						$("#add_remark_msg").text("");
					},
					3000
				)
			}
		}
	});
}

/***解除冻结***/
function remove_frozen(id){
	layer.confirm("<textarea id='unfrozen_remark' placeholder='<?php echo lang('pls_input_reson');?>'></textarea>", {
		icon: 3,
		title: "<?php echo lang('order_remove_frozen'); ?>",
		closeBtn: 2,
		btn: ['<?php echo lang('ok'); ?>', '<?php echo lang('admin_order_cancel'); ?>']
	},function(){
        var remark = $('#unfrozen_remark').val();
		$.ajax({
			type: "POST",
			url: "/admin/trade/remove_frozen",
			data: {order_id:id,remark:remark},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					window.location.reload();
					layer.msg(res.msg);
				}else{
					layer.msg(res.msg);
				}
			}
		});
	});
}

function submit_deliver()
{
	var data = {};

	data.order_id = $('#deliver_box_order_id').val();
	data.company_code = $('#deliver_box_code').val();
	data.express_id = $('#deliver_box_id').val();

	console.log(data);

	$.ajax({
		url: '/admin/trade/do_order_deliver',
		type: "POST",
		data: data,
		dataType: "json",
		success: function(data) {
			if (data.code == 0) {
				window.location.reload();
			} else {
				$.thinkbox(errboxHtml("system error"));
			}
		},
		error: function(data) {
			console.log(data.responseText);
		}
	});
}
</script>
<script>
    $(function(){
        var value2 = 0
        $(".lb-nav").rotate({

            bind:

            {

                click : function() {
                    value2 +=90;
                    if(value2 > 360){
                        value2 = 90;
                    }
                    //$('#lightbox').css({overflow:"hidden"});
                    $(this).prev().rotate({angle:45,animateTo:value2})
                    //$(this).parent().parent().rotate({angle: 0,animateTo:value2})
                    $('.lb-dataContainer').css({width:'70%'});
                }
            }
        });
        $("[rel=tooltip]").tooltip();
    });
</script>