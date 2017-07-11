<div class="well">
	<input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
	<form id="after_sale_form" method="post">
	<table class="upgradeTb" cellspacing="30px">
		<tr>
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_id')?>:
			</td>
			<td class="content">
				<span><?php echo $as_info['as_id']?></span>
				<input type="hidden" id="as_id" value="<?php echo $as_info['as_id']?>">
			</td>
		</tr>
		<?php if(in_array($as_info['type'],array(0,1))){ ?>
		<tr>
			<td class="title" style="height: 40px;">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_uid')?>:
			</td>
			<td class="content"><span><?php echo $as_info['uid']?></span></td>
		</tr>
		<tr>
			<td class="title" style="height: 40px;">
				<?php echo lang('admin_after_sale_name')?>:
			</td>
			<td class="content">
				<span><?php echo $as_info['name']?></span>
			</td>
		</tr>
		<tr style="height: 40px;">
			<td class="title">
				<?php echo lang('current_commission')?>:
			</td>
			<td class="content">
				<span id="store_amount"><?php echo $amount?></span>
			</td>
		</tr>
		<?php }?>
		<tr style="height: 40px;">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_type')?>:
			</td>
			<td class="content">
				<strong>
				<?php echo in_array($as_info['type'],array(0,2,3)) ? lang('admin_after_sale_type_'.$as_info['type']) : lang('admin_after_sale_type_'.$as_info['type']).'->'.lang('level_'.$as_info['demote_level'])?>
				</strong>
			</td>
		</tr>
		<tr class="demote_level hidden">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_demote')?>:
			</td>
			<td class="content">
					<?php echo $as_info['demote_level'] ?>
			</td>
		</tr>
		<tr>
			<td class="title">
				<?php echo lang('admin_order_info')?>:
			</td>
			<td class="content">
				<div id="order_table">
					<table class="table">
						<tr><th><?php echo lang('order_id') ?></th>
						<th><?php echo lang('type') ?></th>
						<th><?php echo lang('pay_amount_order') ?></th>
						<th><?php echo lang('label_shipping') ?></th>
						<th><?php echo lang('status') ?></th></tr>
						<th><?php echo lang('payment') ?></th></tr>
						<?php if($orders){
							foreach($orders as $item){ ?>
								<tr class="<?php echo $item['class'] ?>">
									<td><?php echo $item['order_id'] ?></td>
									<td><?php echo $item['type_name'] ?></td>
									<td><?php echo $item['order_amount_usd']/100 ?></td>
									<td><?php echo $item['deliver_fee_usd']/100 ?></td>
									<td><?php echo $item['status'] ?></td>
									<td><?php echo lang('payment_'.$item['payment_type']) ?></td>
								</tr>
							<?php }?>
						<?php }else{?>
							<tr>
								<th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
							</tr>
						<?php }?>
					</table>

				</div>
			</td>
		</tr>
                <tr style="height: 40px;">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('tickets_order_cancellation')?>:
			</td>
			<td class="content">
                            <span style="color:red"><?php if(isset($as_info["cance_order"])){
                                foreach($as_info["cance_order"] as $cance_order){
                                    echo $cance_order."<br>";
                                }
                            } ?></span>
			</td>
		</tr>
		<tr style="height: 40px;">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_amount')?>:
			</td>
			<td class="content">
				<span><?php echo $as_info['refund_amount'] ?></span>
				<?php if($as_info['type'] == 1){?>
					<strong class="demote_info text-error">将减去会员 <?php echo (int)$as_info['refund_amount']?> 代品券</strong>
				<?php }?>
			</td>
		</tr>
                <tr>
                    <td class="content">
                    </td>
                    <td class="content" colspan="2">
                        <strong class="refund_msg text-error"><?php echo isset($amount_str) ? $amount_str : '';?></strong>
                    </td>
                </tr>
		<tr style="height: 50px;">
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_method')?>:
			</td>
			<td class="content">

				<?php echo lang('admin_after_sale_method_'.$as_info['refund_method'])?>
			</td>
		</tr>
		<tr class="title transfer">
			<td><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('payee_info');?>:</td>
			<td>
                            <span><?php if(in_array($as_info['refund_method'],array(0,2))){if ($as_info['account_bank']) {echo $as_info['account_bank'];}echo $as_info['card_number'].' '.$as_info['account_name'];}else{echo $as_info['transfer_uid'];}?></span>
			</td>
		</tr>
		<tr style="height: 50px;" >
			<td class="title">
				<img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('admin_after_sale_remark')?>:
			</td>
			<td class="content">
				<span><?php echo $as_info['remark'] ?></span>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<table class="table">
					<?php if($remarks){
						foreach($remarks as $item){ ?>
							<tr>
								<td><?php echo $item['create_time'] ?></td>
								<td><?php echo $item['email'] ?></td>
								<td><?php echo $item['remark'] ?></td>
							</tr>
						<?php }?>
					<?php }else{?>
						<tr>
							<th colspan="6" style="text-align: center;" class="text-success"> <?php echo lang('no_item') ?></th>
						</tr>
					<?php }?>
				</table>
			</td>
		</tr>
		<tr>
			<td class="title">
				<input type="button" id="after_sale" autocomplete="off" class="btn btn-primary" value="<?php echo lang('admin_check_pass');?>">
			</td>
			<td class="content"><input type="button" name="button1" class="btn btn-danger" value="<?php echo lang('back')?>" onclick="history.go(-1)"></td>
			<td>
				<span id="after_sale_msg"></div>
			</td>
		</tr>

	</table>

	</form>

</div>
<script>
	$(function(){
		$('#after_sale').click(function(){

			var oldSubVal = $('#after_sale').val();
			$('#after_sale').val($('#loadingTxt').val());
			$('#after_sale').attr("disabled","disabled");
                        var is_three_month = '<?php echo $as_info["is_three_month"];?>';
			$.ajax({
				type: "POST",
				url: "/admin/demote_levels/admin_do_demote",
				data: {as_id:$('#as_id').val(),is_three_month:is_three_month},
				dataType: "json",
				success: function (res) {
					if (res.success) {
						$('#after_sale_msg').html(res.msg).addClass('text-success');
						$('#after_sale').val(oldSubVal);
						setTimeout(function(){
							location.href='/admin/after_sale_order_list?status=0';
						},1000);
					}else{
						$('#after_sale_msg').html('× '+res.msg).addClass('text-error');
						$('#after_sale').attr("disabled",false);
						$('#after_sale').val(oldSubVal);
					}
				}
			});
		});
	})

</script>