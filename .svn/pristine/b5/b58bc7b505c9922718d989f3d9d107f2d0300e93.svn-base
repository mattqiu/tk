
<style>
	body { font-family: <?php echo lang('order_receipt_font'); ?>; }
	span { color: #43699c; }

	.frame_box { border: solid 1px #afafaf; }

	.basic_table { margin-top: 5%; border: }
	.info_title { text-align: left; font-size: 20px; font-weight: lighter; color: #666; padding-bottom: 12px; }
	.info_key { color: #999; font-weight: bold; width: 30%;}

	.basic_company { width: 36%; padding-left: 5%; }
	.basic_company td { color: #999; line-height: 1.3em; }

	.basic_logo { width: 28%; text-align: center; }

	.basic_customer { height: width: 36%; padding: 0 4% 0 1%; }
	.basic_customer_date { font-style: normal; }
	.basic_customer td { color: #999; line-height: 1.3em; }

	.order_info { margin-top: 5%; display: block; position: relative; }
	.receipt_title { font-size: 5em; color: #6b6f78; line-height: 1.0em; text-align: center; }
	.order_number { font-size: 1.2em; color: #6b6f78; line-height: 2.4em; text-align: center; font-weight: bold; }
	.detail_box { margin: 1% 5%; border: solid 2px #d0d7d9; color: #666; }

	.detail_head { margin: 4% 2% 2%; padding-bottom: 1%; border-bottom: solid 2px #d0d7d9; color: #aaa; }
	.detail_line { margin: 0% 2% 2%; padding-bottom: 2%; border-bottom: solid 1px #d0d7d9; color: #222; }
	.detail_column_1 { width: 62%; padding-right: 3%; }
	.detail_column_2 { width: 18%; padding-right: 3%; }
	.detail_column_3 { width: 8%; padding-right: 3%; }
	.detail_column_4 { width: 12%; }

	.detail_amount { margin: 0 2%; padding-bottom: 2%; border-bottom: dotted 1px #777; line-height: 2em; }
	.amount_column_1 { width: 88%; padding-right: 3%; text-align: right; }
	.amount_column_2 { width: 12%; padding-right: 3%; }
	.product_amount { color: #222; }
	.secondary_amount { color: #aaa; }

	.detail_payment { margin: 2% 2% 3%; padding-bottom: 2%; line-height: 2em; }
	.actual_payment { font-weight: bold; color: #000; }
	.payment_amount { color: #cc0000; }

	.payment_term { margin: 0 2%; }
	.payment_term span { font-size: 1.5em; }
	.payment_postfix { margin: 1% 2% 3%; font-size: 0.9em; }

	.billing_unit { margin: 1% 7% 5%; color: #666; }
	.billing_unit span { color: #aaa; }

	.thank_you { line-height: 2.5; background: #ffedf1; color: #dc7c7c; text-align: center; font-size: 14px; }
</style>

<body>
<div class="frame_box">
	<div class="basic_table">
		<table width="100%">
			<tr>
				<td class="basic_company">
					<table>
						<thead>
							<tr>
								<th class="info_title" colspan="2"><?php echo lang('order_receipt_company'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_company_address'); ?></td>
								<td><?php echo lang('order_receipt_company_address_detail'); ?></td>
							</tr>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_company_phone'); ?></td>
								<td><?php echo lang('order_receipt_company_phone_detail'); ?></td>
							</tr>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_company_fax'); ?></td>
								<td><?php echo lang('order_receipt_company_fax_detail'); ?></td>
							</tr>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_company_email'); ?></td>
								<td><?php echo lang('order_receipt_company_email_detail'); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td class="basic_logo">
					<img src="themes/mall_v0/img/tps.png" alt="TPS">
				</td>
				<td class="basic_customer">
					<table>
						<thead>
							<tr>
								<th class="info_title" colspan="2">
									<?php echo lang('order_receipt_purchase_date');?>
									<em class="basic_customer_date"><?php echo $purchase_date; ?></em>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_member_id');?></td>
								<td><?php echo $customer_id; ?></td>
							</tr>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_store_level');?></td>
								<td><?php echo $month_fee; ?></td>
							</tr>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_user_phone');?></td>
								<td><?php echo $user_phone; ?></td>
							</tr>
							<tr>
								<td class="info_key" valign="top"><?php echo lang('order_receipt_receiving_address');?></td>
								<td><?php echo $address; ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div class="order_info" style="position:relative">
		<div class="receipt_title"><?php echo lang('order_receipt_title'); ?></div>
		<div class="order_number">
			<?php echo lang('order_receipt_order_number'); ?>
			<span><?php echo $order_id; ?></span>
		</div>
		<div class="detail_box" >
			<table class="detail_head" width="100%">
				<tr>
					<td class="detail_column_1"><?php echo lang('order_receipt_detail_product'); ?></td>
					<td class="detail_column_2"><?php echo lang('order_receipt_detail_price'); ?></td>
					<td class="detail_column_3"><?php echo lang('order_receipt_detail_qty'); ?></td>
					<td class="detail_column_4"><?php echo lang('order_receipt_detail_amount'); ?></td>
				</tr>
			</table>
			<?php foreach ($goods as $v): ?>
			<table class="detail_line" width="100%">
				<tr>
					<td class="detail_column_1"><?php echo $v['name']; ?></td>
					<td class="detail_column_2">$<?php echo $v['price']; ?></td>
					<td class="detail_column_3"><?php echo $v['qty']; ?></td>
					<td class="detail_column_4">$<?php echo $v['amount']; ?></td>
				</tr>
			</table>
			<?php endforeach; ?>




			<table class="detail_amount" width="100%">
				<tr>
					<td class="amount_column_1 product_amount"><?php echo lang('order_receipt_product_amount'); ?></td>
					<td class="amount_column_2 product_amount">$<?php echo $goods_amount_show; ?></td>
				</tr>
				<?php if ($discount_type == 2): ?>
				<tr>
					<td class="amount_column_1 secondary_amount"><?php echo lang('order_receipt_coupons_amount'); ?></td>
					<td class="amount_column_2 secondary_amount">$<?php echo $discount_amount_show; ?></td>
				</tr>
				<?php endif; ?>
				<tr>
					<td class="amount_column_1 secondary_amount"><?php echo lang('order_receipt_freight'); ?></td>
					<td class="amount_column_2 secondary_amount">$<?php echo $deliver_fee_show; ?></td>
				</tr>
			</table>
			<table class="detail_payment" width="100%">
				<tr>
					<td class="amount_column_1 actual_payment"><?php echo lang('order_receipt_actual_payment'); ?></td>
					<td class="amount_column_2 payment_amount">$<?php echo $order_amount_show; ?></td>
				</tr>
			</table>
			<div class="payment_term">
				<?php echo lang('order_receipt_payment_terms'); ?> <span><?php echo lang($payment); ?></span>
			</div>
			<div class="payment_postfix"><?php echo lang('order_receipt_commitment');?></div>
		</div>
		<div class="billing_unit">
			<span><?php echo lang('order_receipt_payment_billing_unit'); ?></span> <?php echo lang('order_receipt_company'); ?>
		</div>
		<div class="thank_you"><?php echo lang('order_receipt_thank');?></div>
	</div>
</div>

<?php if($is_sj){?>
<!--			医疗商品的特殊说明 start-->
<!--<div style="position:absolute; top:600px;left:100px;padding:10px;background:rgba(0,0,0,0.2);box-sizing:border-box;width: 250px; margin-left: 200px;">-->
<div style="position:absolute; top:600px;left:115px;padding:10px;background:rgba(255,255,255,0.2);box-sizing:border-box;width: 220px; margin-left: 205px;">
	<h3>特別注意事項：</h3>
	<p>1.	客戶購買疫苗項目後，不設退款，也不可當現金使用作其他消費。但如出現不適合注射疫苗之情況如過敏反應等，將取消此疫苗項目的服務，按剩餘未注射的疫苗劑量計算費用退回。</p>
	<p>2.	疫苗項目之收據只可兌換服務1次。</p>
	<p>3.	進行疫苗注射前請致電體檢中心預約接種疫苗之日期及時間，並於進行疫苗注射當天向職員出示此收據。</p>
	<p>4.	此疫苗項目需在購買日當天起計三個月內使用，逾期無效。</p>
	<p>查詢熱線：2369-0680</p>
</div>
<!--			医疗商品的特殊说明 end-->
<?php }?>
</body>
