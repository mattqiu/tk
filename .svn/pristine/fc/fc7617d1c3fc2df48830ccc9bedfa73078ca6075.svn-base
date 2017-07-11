<div class="well">
	<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
	<form id="after_sale_form" method="post">
	<table class="upgradeTb" cellspacing="30px">
		<tr>
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_id')?>:
			</td>
			<td class="content">
				<input name="id" type="text" autocomplete="off" id="id" value="<?php echo isset($as_info) ? $as_info['as_id'] : $as_id?>" readonly placeholder="" <?php echo isset($as_info) ? 'readonly' : ''?>>
				<?php if(isset($as_info)){ ?>
				<input type="hidden" name="edit_as_id" value="<?php echo $as_info['as_id']?>">
				<?php } ?>
			</td>
		</tr>
		<tr style="height: 40px;">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_type')?>:
			</td>
			<td class="content">
				<?php if(isset($as_info)){?>
					<strong>
						<input type="hidden" name="demote_level" value="<?php echo $as_info['demote_level']?>">
						<input type="hidden" name="type" value="<?php echo $as_info['type']?>">
						<?php echo $as_info['type'] == '0'||$as_info['type'] == '2' ? lang('admin_after_sale_type_'.$as_info['type']) : lang('admin_after_sale_type_'.$as_info['type']).'->'.lang('level_'.$as_info['demote_level'])?>
					</strong>
				<?php }else{?>
					<select name="type" autocomplete="off" <?php echo isset($as_info) ? 'readonly' : ''?>>
						<option value="0" <?php echo isset($as_info)&&$as_info['type'] == '0' ? 'selected':'' ?> ><?php echo lang('admin_after_sale_type_0')?></option>
						<option value="1" <?php echo isset($as_info)&&$as_info['type'] == '1' ? 'selected':'' ?> ><?php echo lang('admin_after_sale_type_1')?></option>
						<!--
                        <option value="2" <?php echo isset($as_info)&&$as_info['type'] == '2' ? 'selected':'' ?> ><?php echo lang('admin_after_sale_type_2')?></option>
						-->
						<option value="3" <?php echo isset($as_info)&&$as_info['type'] == '3' ? 'selected':'' ?> ><?php echo lang('admin_after_sale_type_3')?></option>
						</select>
				<?php }?>
			</td>

		</tr>

		<tr class="order_input <?php echo isset($as_info)&&$as_info['type']!=2? 'hidden':'' ?>">
			<td class="title" style="height: 40px;">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('order_id')?>:
			</td>
			<td class="content">
				<input name="order_id" id="order_id" type="text" autocomplete="off" value="<?php echo isset($as_info) ? $as_info['order_id'] : ''?>" placeholder="" <?php echo isset($as_info) ? 'readonly' : ''?>>
			</td>
			<td class="content">
				<span class="text-error" id="order_id_msg"></span>
			<td>
		</tr>

		<tr class="no_tui <?php echo isset($as_info)&&$as_info['type']==2? 'hidden':'' ?>">
			<td class="title" style="height: 40px;">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_uid')?>:
			</td>
			<td class="content">
				<input name="uid" id="user_id" type="text" autocomplete="off" value="<?php echo isset($as_info) ? $as_info['uid'] : ''?>" placeholder="" <?php echo isset($as_info) ? 'readonly' : ''?>>
			</td>
			<td class="content">
				<span class="text-error" id="uid_msg"></span>
			<td>
		</tr>

		<tr class="no_tui <?php echo isset($as_info)&&$as_info['type']==2? 'hidden':'' ?>">
			<td class="title" style="height: 40px;">
				<?php echo lang('admin_after_sale_name')?>:
			</td>
			<td class="content">
				<input name="name" id="store_name" type="hidden" autocomplete="off" value="" placeholder="">
				<strong id="store_name_2"><?php echo isset($as_info) ? $as_info['name'] : ''?></strong>
			</td>
		</tr>
		<tr style="height: 40px;" class="no_tui <?php echo isset($as_info)&&$as_info['type']==2? 'hidden':'' ?>">
			<td class="title">
				<?php echo lang('current_commission')?>:
			</td>
			<td class="content">
				<strong id="store_amount"><?php echo isset($amount)? $amount : ''; ?></strong>
			</td>
		</tr>

		<tr class="demote_level hidden">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_demote')?>:
			</td>
			<td class="content">
				<select name="demote_level">

				</select>
			</td>
			<td>
				<strong class="coupon_tip text-error"></strong>
			</td>
		</tr>
		<tr>
			<td class="title">
				<?php echo lang('admin_order_info')?>:
			</td>
			<td class="content">
				<div id="order_table">
					<?php if(isset($orders)){ ?>
					<table class="table">
						<tr><th><?php echo lang('order_id') ?></th>
							<th><?php echo lang('type') ?></th>
							<th><?php echo lang('pay_amount_order') ?></th>
							<th><?php echo lang('label_shipping') ?></th>
							<th><?php echo lang('status') ?></th></tr>
						<?php if($orders){
							foreach($orders as $item){ ?>
								<tr class="<?php echo $item['class'] ?>">
									<td><?php echo $item['order_id'] ?></td>
									<td><?php echo $item['type_name'] ?></td>
									<td><?php echo $item['order_amount_usd']/100 ?></td>
									<td><?php echo $item['deliver_fee_usd']/100 ?></td>
									<td><?php echo $item['status'] ?></td>
								</tr>
							<?php }?>
						<?php }else{?>
							<tr>
								<th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
							</tr>
						<?php }?>
					</table>
					<?php }?>
				</div>
			</td>
		</tr>

		<tr>
			<td class="content">
			</td>
			<td class="content" colspan="2">
				<strong class="refund_msg text-error"></strong>
			</td>
		</tr>
		<tr style="height: 50px;">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_method')?>:
			</td>
			<td class="content" >
				<?php if(isset($as_info)){?>
					<input type="hidden" name="method" value="<?php echo $as_info['refund_method']?>">
					<strong>
						<?php echo lang('admin_after_sale_method_'.$as_info['refund_method'])?>
					</strong>
				<?php }else{?>
                <!--
				<label class="modal_main" style="display: inline"><input type="radio" value="0" checked <?php echo isset($as_info)&&$as_info['refund_method'] == '0' ? 'checked':'' ?> name="method"><?php echo lang('admin_after_sale_method_0')?></label>
				-->
				<label class="modal_main" style="display: inline"><input type="radio" value="1" checked<?php echo isset($as_info)&&$as_info['refund_method'] == '1' ? 'checked':'' ?> name="method"><?php echo lang('admin_after_sale_method_1')?></label>
				<label class="modal_main" style="display: inline"><input type="radio" value="2" <?php echo isset($as_info)&&$as_info['refund_method'] == '2' ? 'checked':'' ?> name="method"><?php echo lang('admin_after_sale_method_2')?></label>
				<?php }?>
			</td>
		</tr>
		<tr class="title transfer <?php echo isset($as_info)&&$as_info['refund_method'] != 1 ? 'hidden' : '' ?>">
			<td><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('payee_info');?>:</td>
			<td>
				<input name="transfer_uid" type="text" value="<?php echo isset($as_info) ? $as_info['transfer_uid'] : ''?>"  autocomplete="off" placeholder="<?php echo lang('member_id')?>" >
			</td>
		</tr>
		<tr class="title manually <?php echo isset($as_info)&&$as_info['refund_method'] != 0 ? 'hidden' : '' ?>">
			<td><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('payee_info');?>:</td>
			<td>
				<input name="account_bank" type="text" value="<?php echo isset($as_info) ? $as_info['account_bank'] : ''?>"  autocomplete="off" placeholder="<?php echo lang('bank_name').lang('example1').'  '.lang('subbranch').lang('example2');?>" >
			</td>
		</tr>
		<tr class="manually <?php echo isset($as_info)&&$as_info['refund_method'] != 0 ? 'hidden' : '' ?>">
			<td></td>
			<td>
				<input id="card_number" name="card_number" value="<?php echo isset($as_info) ? $as_info['card_number'] : ''?>" type="text"  autocomplete="off" placeholder="<?php echo lang('bank_card_number');?>">
			</td>
		</tr>
		<tr class="manually <?php echo isset($as_info)&&$as_info['refund_method'] != 0 ? 'hidden' : '' ?>">
			<td></td>
			<td>
				<input name="account_name" type="text" value="<?php echo isset($as_info) ? $as_info['account_name'] : ''?>"  autocomplete="off" placeholder="<?php echo lang('card_holder_name');?>">
			</td>
		</tr>
        <tr class="alipay <?php echo isset($as_info)&&$as_info['refund_method'] != 2 ? 'hidden' : '' ?>">
            <td></td>
            <td>
                <input id="card_number" name="card_number" value="<?php echo isset($as_info) ? $as_info['card_number'] : ''?>" type="text"  autocomplete="off" placeholder="<?php echo lang('withdrawal_alipay_');?>">
            </td>
        </tr>
        <tr class="alipay <?php echo isset($as_info)&&$as_info['refund_method'] != 2 ? 'hidden' : '' ?>">
            <td></td>
            <td>
                <input name="account_name" type="text" value="<?php echo isset($as_info) ? $as_info['account_name'] : ''?>"  autocomplete="off" placeholder="<?php echo lang('alipay_actual_name');?>">
            </td>
        </tr>
        <tr style="height: 40px;">
            <td class="title">
                <img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_amount')?>:
            </td>
            <td class="content">
                <input name="refund_amount" id="refund_amount" type="text" autocomplete="off" value="" placeholder="">
            </td>
            <td>
                <strong class="alipay_tip text-error hidden">(支付宝退款金额是￥人民币)，程序将自动减去%0.5的手续费:<span class="alipay_amount"></span></strong>
                <br><strong class="demote_info text-error hidden">降级时你输入多少退款金额就会减去会员多少代品券</strong>
            </td>
        </tr>
		<tr style="height: 50px;" >
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_remark')?>:
			</td>
			<td class="content">
				<textarea autocomplete="off" id="check_info" style="width: 362px; height: 164px;" name="check_info" placeholder="<?php echo lang('admin_after_sale_remark_example'); ?>"><?php echo isset($as_info) ? $as_info['remark'] : ''?></textarea>
			</td>
		</tr>
		<tr>
			<td class="title">
				<input type="button" id="after_sale" autocomplete="off" class="btn btn-primary" value="<?php echo lang('submit');?>">
			</td>
                <input type="hidden" id="meiyuan" value="" />
			<td class="content" id="after_sale_msg"></td>
		</tr>
	</table>
	</form>
</div>
<script>
	$(function(){
		chooseRadio();
		chooseSelectType();
		$("[name='method']:radio").click(chooseRadio);
		$("[name='type']").change(chooseSelectType);
		$("[name='demote_level']").change(get_amount);

		var check_user;
		$('#user_id').bind('input propertychange', function () {
			clearTimeout(check_user);
			check_user = setTimeout(get_amount,500);
		});
		var check_user2;
		$('#order_id').bind('input propertychange', function () {
			clearTimeout(check_user2);
			check_user2 = setTimeout(get_order_info,500);
		});

        var check_user3;
        $('#refund_amount').bind('input propertychange', function () {
            clearTimeout(check_user3);
            check_user3 = setTimeout(function(){
                $('.alipay_amount').text(($('#refund_amount').val()*0.005).toFixed(2));
            },1000);
        });

		$('#after_sale').click(function(){

			var oldSubVal = $('#after_sale').val();
			$('#after_sale').val($('#loadingTxt').val());
			$('#after_sale').attr("disabled","disabled");
			$.ajax({
				type: "POST",
				url: "/admin/add_after_sale_order/do_add_after_sale",
				data: $('#after_sale_form').serialize(),
				dataType: "json",
				success: function (res) {
					if (res.success) {
						$('#after_sale_msg').html(res.msg).addClass('text-success');
						$('#after_sale').val(oldSubVal);
						setTimeout(function(){
							location.href="/admin/after_sale_order_list";
						},1000);
					}else{
						$('#after_sale_msg').html('× '+res.msg).addClass('text-error');
						$('#after_sale').attr("disabled",false);
						$('#after_sale').val(oldSubVal);
					}

				}
			});
		});
	});
	function get_amount(){
		var uid = $('#user_id').val();
		if(uid == '') return;
        <?php if(!isset($as_info)){?>
            var order_type = $("[name='type'] option:selected").val();
        <?php }else{?>
            var order_type = "<?php echo $as_info['type']?>";
        <?php }?>
		$.ajax({
			type: "POST",
			url: "/admin/add_after_sale_order/checkStoreLevel",
			data: {id: uid,type:order_type,demote_level:$("[name='demote_level'] option:selected").val()},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					$('#uid_msg').text('');
					$('#store_name').val(res['result']['name']);
					$('#store_name_2').text(res['result']['name']);
					$('#store_amount').text(res['result']['amount']);
					 if(order_type == 0){
						$('.refund_msg').text(res['result']['refund_amount_str']);
					}else{
						$('.refund_msg').text('');
					 }
					<?php if(!isset($as_info)){?>
                                            var method_type = $("[name='method']").filter(":checked").val();
                                            if(method_type != 2){
                                                //$('#refund_amount').val(res['result']['refund_amount']);
                                                $('#meiyuan').val(res['result']['refund_amount']);
                                            }
					<?php }?>
					$('#order_table').html(res['result']['order_table']);
					$('.coupon_tip').html(res['result']['coupons']);
					if(res['result']['is_load']){
						$("[name='demote_level']").text('');
						$("[name='demote_level']").append(res['result']['store_level_option']);
					}

				}else{
					$('#uid_msg').text(res.msg);
					$("[name='demote_level']").text('')
				}
			}
		});
	}
	function get_order_info(){
		var order_id = $('#order_id').val();
		if(order_id == '') return;
		$.ajax({
			type: "POST",
			url: "/admin/add_after_sale_order/get_order_info",
			data: {order_id: order_id,type:$("[name='type'] option:selected").val()},
			dataType: "json",
			success: function (res) {
				if (res.success) {
					//$('#uid_msg').text('');
					$('#order_table').html(res['result']['order_table']);
					$('#order_id_msg').text(res['result']['order_count_str']);

				}else{
					$('#order_id_msg').text(res.msg);
					$("[name='demote_level']").text('')
				}
			}
		});
	}
	function chooseRadio(){
		var type = $("[name='method']").filter(":checked").val();
		if(type == 1){
            $('#refund_amount').attr('placeholder','请输入美金（$）');
            if($('#meiyuan').val()){
                $('#refund_amount').val(Number($('#meiyuan').val()).toFixed(2));
            }
			$('.manually').addClass('hidden');
			$('.transfer').removeClass('hidden');
			$('.alipay').addClass('hidden');
		}else if (type == 0){
			$('.transfer').addClass('hidden');
			$('.manually').removeClass('hidden');
            $('.alipay').addClass('hidden');
		}else if(type == 2){
            $('#refund_amount').attr('placeholder','请输入人民币（￥）');
            $('#refund_amount').val('');
            $('.transfer').addClass('hidden');
            $('.alipay').removeClass('hidden');
            $('.manually').addClass('hidden');
            $('.alipay_tip').removeClass('hidden');
        }
	}
	function chooseSelectType(){
		var type = $("[name='type'] option:selected").val();
		if(type == 1){
			$('.demote_level').removeClass('hidden');
			$('.no_tui').removeClass('hidden');
			$('.order_input').addClass('hidden');
            $('.demote_info').removeClass('hidden');
		}else if(type == 0){
			$('.demote_level').addClass('hidden');
			$('.no_tui').removeClass('hidden');
			$('.order_input').addClass('hidden');
            $('.demote_info').addClass('hidden');
		}else if(type == 2 || type == 3){
			$('.order_input').removeClass('hidden');
			$('.demote_level').addClass('hidden');
			$('.no_tui').addClass('hidden');
            $('.demote_info').addClass('hidden');
		}
		get_amount();
	}

</script>