<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo lang('tps138_admin');?></title>
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/css/bootstrap.css')?>">
		<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
	</head>

	<body>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-offset-1 col-xs-10">
					<?php if (isset($err_msg)): ?>
					<p class="text-danger text-center"><?php echo $err_msg; ?></p>
					<?php else: ?>
					<p><br><br></p>
					<table class="table table-hover table-condensed">
						<tbody>
							<?php if (!empty($data['order_detail']['remark_data']["customer"]) || !empty($data['order_detail']['remark_data']["system"])): ?>
							<tr>
								<td class="text-success" colspan="4"><strong><?php echo lang('admin_order_remark_system'); ?></strong></td>
							</tr>
							<?php if (!empty($data['order_detail']['remark_data']["customer"])): ?>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_customer_remark'); ?></td>
								<td class="text-info" colspan="3">
									<table class="table">
										<thead>
											<tr>
												<td><?php echo lang('admin_order_remark'); ?></td>
												<td><?php echo lang('admin_order_remark_operator'); ?></td>
												<td><?php echo lang('admin_order_remark_create_time'); ?></td>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($data['order_detail']['remark_data']["customer"] as $v): ?>
											<tr>
												<td class="bg-primary"><?php echo $v['remark']; ?></td>
												<td class="bg-primary"><?php echo $v['admin_user']; ?></td>
												<td class="bg-primary"><?php echo $v['created_at']; ?></td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</td>
							</tr>
							<?php endif; ?>
							<?php if (!empty($data['order_detail']['remark_data']["system"])): ?>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_system_remark'); ?></td>
								<td class="text-info" colspan="3">
									<table class="table">
										<thead>
											<tr>
												<td><?php echo lang('admin_order_remark'); ?></td>
												<td><?php echo lang('admin_order_remark_operator'); ?></td>
												<td><?php echo lang('admin_order_remark_create_time'); ?></td>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($data['order_detail']['remark_data']["system"] as $v): ?>
											<tr>
												<td class="bg-primary"><?php echo $v['remark']; ?></td>
												<td class="bg-primary"><?php echo $v['admin_user']; ?></td>
												<td class="bg-primary"><?php echo $v['created_at']; ?></td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</td>
							</tr>
							<?php endif; ?>
							<?php endif; ?>
							<tr>
								<td class="text-success" colspan="4"><strong><?php echo lang('admin_order_info_basic'); ?></strong></td>
							</tr>
							<?php if($data['order_detail']['order_prop'] == 1){ ?>
							<tr>
								<td class="text-danger"><?php echo lang('admin_trade_order_attach'); ?></td>
								<td class="text-info" colspan="3"><?php echo $data['order_detail']['attach_id']; ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_id'); ?></td>
								<td class="text-info" colspan=""><?php echo $data['order_detail']['order_id']; ?></td>

								<td class="text-danger"><?php echo lang('admin_order_prop'); ?></td>
								<td class="text-info" colspan=""><?php echo $order_type_map[$data['order_detail']['order_type']]; ?></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_uid'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['customer_id']; ?></td>

								<td class="text-danger"><?php echo lang('admin_order_store_id'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['shopkeeper_id']; ?></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_deliver_time'); ?></td>
								<td class="text-info"><?php echo $deliver_time_map[$data['order_detail']['deliver_time_type']]; ?></td>
								<td class="text-danger"><?php echo lang('admin_order_receipt'); ?></td>
								<td class="text-info"><?php echo $receipt_map[$data['order_detail']['need_receipt']]; ?></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_consignee'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['consignee']; ?></td>
								<td class="text-danger"><?php echo lang('admin_order_phone'); ?></td>
								<td class="text-info"><?php echo "{$data['order_detail']['phone']} / {$data['order_detail']['reserve_num']}"; ?></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_deliver_addr'); ?></td>
								<td class="text-info" colspan="3"><?php echo$data['order_detail']['country_address'].' '.$data['order_detail']['address']; ?></td>
							</tr>
							<?php if (empty($data['order_detail']['customs_clearance'])): ?>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_zip_code'); ?></td>
								<td class="text-info" colspan="3"><?php echo $data['order_detail']['zip_code']; ?></td>
							</tr>
							<?php else: ?>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_zip_code'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['zip_code']; ?></td>
								<td class="text-danger"><?php echo lang('admin_order_customs_clearance'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['customs_clearance']; ?></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td class="text-success" colspan="4"><strong><?php echo lang('admin_order_info_goods'); ?></strong></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_goods_list'); ?></td>
								<td class="text-info" colspan="3">
									<table class="table">
										<thead>
											<tr>
												<td><?php echo lang('admin_order_goods_sn'); ?></td>
												<td><?php echo lang('admin_order_goods_name'); ?></td>
												<td></td>
												<td><?php echo lang('admin_order_goods_quantity'); ?></td>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($data['goods_info'] as $v): ?>
											<tr>
												<td><?php echo $v['goods_sn']; ?></td>
												<td>
													<?php echo $v['goods_name']; ?>
												</td>
												<td>
													<?php if($v['goods_attr']){?>
														<span><?php echo $v['goods_attr'] ?></span>
													<?php }?>
												</td>
												<td><?php echo $v['quantity']; ?></td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_remark'); ?></td>
								<td class="text-info" colspan="3"><?php echo $data['order_detail']['remark']; ?></td>
							</tr>
							<tr>
								<td class="text-success" colspan="4"><strong><?php echo lang('admin_order_info_pay'); ?></strong></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_currency'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['currency']; ?></td>
								<td class="text-danger"><?php echo lang('admin_order_rate'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['currency_rate']; ?></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_goods_amount'); ?></td>
								<td class="text-info"><?php echo number_format($data['order_detail']['goods_amount_usd'] / 100, 2); ?></td>
								<td class="text-danger"><?php echo lang('admin_order_deliver_fee'); ?></td>
								<td class="text-info"><?php echo number_format($data['order_detail']['deliver_fee_usd'] / 100, 2); ?></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_amount');?></td>
								<td class="text-info"><?php echo $data['order_detail']['format_paid_amount'] ? $data['order_detail']['format_paid_amount']:number_format($data['order_detail']['order_amount'] / 100, 2); ?></td>
								<td class="text-danger"><?php echo lang('admin_order_amount_usd'); ?></td>
								<td class="text-info"><?php echo number_format($data['order_detail']['order_amount_usd'] / 100, 2); ?></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_payment'); ?></td>
								<td class="text-info"><?php echo $payment_map[$data['order_detail']['payment_type']]; ?></td>
								<td class="text-danger"><?php echo lang('admin_order_pay_time'); ?></td>
								<td class="text-info">
									<?php if($data['order_detail']['pay_time'] == '0000-00-00 00:00:00') {
										echo '';
									}else{
										echo $data['order_detail']['pay_time'];
									} ; ?>
								</td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_pay_txn_id'); ?></td>
								<td class="text-info" colspan="3"><?php echo $data['order_detail']['txn_id']; ?></td>
							</tr>
							<tr>
								<td class="text-success" colspan="4"><strong><?php echo lang('admin_order_info_status'); ?></strong></td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_info_create_time'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['created_at']; ?></td>
								<td class="text-danger"><?php echo lang('admin_order_info_freight'); ?></td>
								<td class="text-info">
								<?php
									if (isset($data['order_detail']['freight_url']))
									{
										echo "<a href=\"{$data['order_detail']['freight_url']}\" target=\"_blank\">";
										echo $data['order_detail']['freight_info'];
										echo "</a>";
									}
									else
									{
										echo $data['order_detail']['freight_info'];
									}
								?>
								</td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_info_deliver_time'); ?></td>
								<td class="text-info">
<!--									时间格式不用显示 0000-00-00 00:00:00-->
									<?php if($data['order_detail']['deliver_time'] == '0000-00-00 00:00:00') {
										echo '';
									}else{
										echo $data['order_detail']['deliver_time'];
									} ; ?>
								</td>
								<td class="text-danger"><?php echo lang('admin_order_info_receive_time'); ?></td>
								<td class="text-info">
									<?php if($data['order_detail']['receive_time'] == '0000-00-00 00:00:00') {
										echo '';
									}else{
										echo $data['order_detail']['receive_time'];
									} ; ?>
								</td>
							</tr>
							<tr>
								<td class="text-danger"><?php echo lang('admin_order_status'); ?></td>

								<td class="text-info">
									<?php if($data['order_detail']['status'] == '90' && strstr($data['order_detail']['remark'],'#exchange')){
										echo lang('admin_order_status_holding_exchange');
									}else{
										echo $status_map[$data['order_detail']['status']];
									} ?>
<!--									--><?php //echo $status_map[$data['order_detail']['status']]; ?>
								</td>

								<td class="text-danger"><?php echo lang('admin_order_info_update_time'); ?></td>
								<td class="text-info"><?php echo $data['order_detail']['updated_at']; ?></td>
							</tr>
							<tr>
								<td colspan="4"></td>
							</tr>
						</tbody>
					</table>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<script src="<?php echo base_url('themes/mall/js/jquery-1.11.1.js')?>"></script>
		<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
	</body>
</html>
