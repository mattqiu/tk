
<div class="bj_zhuti">
	<div class="MyCart">
		<div class="container clear">
			<div class="row clear">
				<!-- 确认订单信息 -->
				<h2><?php echo lang('checkout_confirm_info'); ?></h2>
				<div class="checkout-box clear">
					<!-- 收货信息 -->
					<div class="body-bd clear">
						<div class="xinzeng clear">
							<b><?php echo lang('checkout_deliver_info'); ?></b>
							<a href="javascript:showBg();" class="XS_btn" onclick="click_addr_add();">
								<i>+</i>
								<?php echo lang('checkout_deliver_add'); ?>
							</a>
						</div>
					</div>
					<!-- 收货地址列表 -->
					<div class="body-bd clear">
						<div class="address-list clear">
							<ul>
								<?php foreach ($address_list as $k => $v): ?>
									<li<?php if ($v['is_default']): ?> class="selected"<?php endif; ?>
										data-id="<?php echo $v['id']; ?>" onclick="cb_addr_change($(this));">
										<i class="marker"></i>
										<span class="marker-tip"><?php echo lang('checkout_deliver_to'); ?></span>
										<input autocomplete="off" type="radio"<?php if ($v['is_default']): ?> checked<?php endif; ?>>
										<div class="address-info">
											<a href="javascript:showBg();" class="modify c-b"
											   onclick="click_addr_edit('<?php echo $k; ?>');"><?php echo lang('checkout_deliver_modify'); ?></a>
											<a href="javascript:void(0);" class="shanchu c-b"
											   onclick="submit_delete_addr('<?php echo $v['id']; ?>');"><?php echo lang('checkout_deliver_delete'); ?></a>
											<label>
												<?php echo $v['addr_str']; ?>
												(<?php echo lang_attr('checkout_deliver_consignee', array('consignee' => $v['consignee'])); ?>
												)
												<em><?php echo $v['phone']; ?></em>
											</label>
											<?php if ($v['is_default']): ?>
												<em class="tip"><?php echo lang('checkout_deliver_default'); ?></em>
											<?php else: ?>
												<a href="javascript:void(0);" class="set-default c-b"
												   onclick="submit_set_default_addr('<?php echo $v['id']; ?>');">
													<?php echo lang('checkout_deliver_set_default'); ?>
												</a>
											<?php endif; ?>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="product_info_address"><?php echo $order_address_info_tip ?></div>
					</div>

					<!-- 收货时间 -->
					<div class="body-bd clear">
						<h3><?php echo lang('checkout_deliver_time'); ?></h3>

						<div class="Shuo clear">
							<p>
								<label>
									<input type="radio" name="deliver_time_type" value="1" checked>
									<span><?php echo lang('checkout_deliver_time_period1'); ?></span>
									<i><?php echo lang('checkout_deliver_time_desc1'); ?></i>
								</label>
							</p>

							<p>
								<label>
									<input type="radio" name="deliver_time_type" value="2">
									<span><?php echo lang('checkout_deliver_time_period2'); ?></span>
									<i><?php echo lang('checkout_deliver_time_desc2'); ?></i>
								</label>
							</p>

							<p>
								<label>
									<input type="radio" name="deliver_time_type" value="3">
									<span><?php echo lang('checkout_deliver_time_period3'); ?></span>
								</label>
							</p>
						</div>
					</div>
					<!-- 订单商品信息 -->
					<div class="body-bd clear">
						<h3><?php echo lang('checkout_order_info'); ?></h3>

						<div class="box-bd">
							<!-- 发货清单 -->
							<dl class="clear">
								<dt class="clear">
									<span class="col-md-8"><span class="P_left"><?php echo lang('checkout_order_list'); ?></span></span>
									<span class="col-md-1 center"><?php echo lang('checkout_order_price'); ?></span>
									<span class="col-md-1 center"><?php echo lang('checkout_order_quantity'); ?></span>
									<span class="col-md-2 center"><?php echo lang('checkout_order_deliver'); ?></span>
								</dt>
								<dd class="clear">
									<div class="x-hl clear">
										<div class="col-md-10">
											<?php foreach ($all_product['group'] as $group): ?>
												<div class="col-md-10">
													<div class="g-info">
														<span><?php echo $group['goods_name']; ?></span>
													</div>
												</div>
												<div class="col-md-1 center">
													<b>$<?php echo ($group['shop_price']); ?></b>
												</div>
												<div class="col-md-1 center">
													<?php echo $group['goods_num']; ?>
												</div>
											<?php endforeach; ?>
											<?php foreach ($all_product['goods'] as $goods): ?>
												<div class="col-md-10">
													<div class="g-info">
														<span><?php echo $goods['goods_name']; ?></span>
													</div>
												</div>
												<div class="col-md-1 center">
													<b>$<?php echo ($goods['shop_price']); ?></b>
												</div>
												<div class="col-md-1 center">
													<?php echo $goods['goods_num']; ?>
												</div>
											<?php endforeach; ?>
										</div>
										<div class="col-md-2">
											<div class="peisong clear">
												<p><?php echo lang('checkout_order_deliver_type'); ?></p>
												<p><?php echo lang($deliver_info['type'])." ".lang('send_free'); ?></p>
												<p>
													<?php echo lang('checkout_order_deliver_time'); ?><br>
													<span id="order_deliver_time_type">
														<?php echo lang('checkout_deliver_time_period1'); ?>
													</span>
												</p>
											</div>
										</div>
									</div>
								</dd>
							</dl>

							<!-- 代品券 -->
							<?php if (!empty($all_product['coupons'])):?>
							<dl class="ddxx clear">
								<dt class="clear">
									<span class="col-md-9">
										<span class="col-md-8"><span class="P_left"><?php echo lang('the_coupons_list'); ?></span></span>
										<span class="col-md-2 center"><?php echo lang('checkout_order_price'); ?></span>
										<span class="col-md-2 center"><?php echo lang('checkout_order_quantity'); ?></span>
									</span>
									<span class="col-md-3 center"><?php echo lang('checkout_order_deliver'); ?></span>
								</dt>
								<dd class="clear">
									<div class="x-hl clear">
										<div class="col-md-9">
											<?php foreach ($all_product['coupons'] as $coupons): ?>
												<div class="col-md-8">
													<div class="g-info">
														<span class="coupons_box_cart" style="width: 70px;">
															<p><?php echo lang('d_p_coupons')?></p>
															<p><?php echo "$".$coupons['coupons_money'] ?></p>
														</span>
													</div>
												</div>
												<div class="col-md-2 center">
													<b><?php echo '$'.$coupons['coupons_money']; ?></b>
												</div>
												<div class="col-md-2 center">
													<?php echo $coupons['coupons_num']; ?>
												</div>
											<?php endforeach; ?>
										</div>
										<div class="col-md-3">
											<div class="peisong clear">
												<p><?php echo lang('direct_send_your_account'); ?></p>
											</div>
										</div>
									</div>
								</dd>
							</dl>
							<?php endif; ?>

							<!-- 金额 -->
							<p class="yunfei">
								<?php echo lang('checkout_order_deliver_fee')." ".lang('send_free'); ?>
							</p>
							<!-- 备注 -->
							<ul class="beizhu clear">
								<li><?php echo lang('checkout_order_remark'); ?></li>
								<li>
									<textarea class="input" id="remark" placeholder="<?php echo lang('checkout_order_remark_palceholder'); ?>"></textarea>
								</li>
								<li><?php echo lang('checkout_order_remark_tip'); ?></li>
							</ul>
							<!-- 提交框 -->
							<div class="zongs clear">
								<!-- 收货信息，其他信息 -->
								<div class="left clear">
									<dl class="clear">
										<dt><?php echo lang('checkout_receive_info'); ?></dt>
										<dd id="info_addr"></dd>
									</dl>
<!--									<dl class="clear">-->
<!--										<dt>--><?php //echo lang('checkout_receive_other'); ?><!--</dt>-->
<!--										<dd>-->
<!--											--><?php //echo lang('checkout_receive_receipt'); ?>
<!--											<input autocomplete="off" class="shouju" type="checkbox" checked />-->
<!--										</dd>-->
<!--									</dl>-->
								</div>
								<!-- 金额 -->
								<div class="right clear">
									<dl class="clear">
										<dt><?php echo lang('checkout_pay_product'); ?></dt>
										<dd><?php echo "$".$total_money; ?></dd>
									</dl>
									<dl class="clear bk bottom_15">
										<dt><?php echo lang('checkout_pay_deliver'); ?></dt>
										<dd><?php echo lang('send_free'); ?></dd>
									</dl>
									<dl class="clear">
										<dt class="font"><?php echo lang('checkout_pay_amount'); ?></dt>
										<dd class="hse font">
											<?php echo "$".$real_pay_money; ?>
										</dd>
									</dl>
									<dl class="clear">
										<dd class="nnniu">
											<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
											<input type="button" style="width: 150px; height: 40px;" class="btn_hong btn-Login" id="submit_choose_info" onclick="submit_choose();" value="<?php echo lang('checkout_order_submit'); ?>">
										</dd>
									</dl>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- 新增 / 修改模拟框 -->
<div class="xm-edit-addr wodl BOX_nav clear" id="BOX_nav">
	<span class="close" onclick="closeBg();">×</span>
	<h3 id="box_title"></h3>
	<div class="item">
		<em id="box_title_em"></em>
		<span class="item-title"><?php echo lang('checkout_addr_box_remark'); ?></span>
		<input type="hidden" id="box_type" />
		<input type="hidden" id="box_id" />
	</div>

    <?php $this->load->view("mall/address/choose_checkout_form.php") ?>

</div>
<div class="xm-backdrop" id="fullbg"></div>

<script src="<?php echo base_url('themes/mall/js/jquery-1.11.1.js'); ?>"></script>

<?php $this->load->view("mall/address/js.php") ?>

<script>
	var addr_list = <?php echo json_encode($address_list); ?>;

	// 防止重复提交
	var submit_flag = false;

	$(function () {
		'use strict';

		address_init();

		// 收货时间 change 事件，修改商品信息对应字段
		$('[name=deliver_time_type]').on('change', function () {
			var dlv_time = $('[name=deliver_time_type]:checked').next().text();
			$('#order_deliver_time_type').text(dlv_time);
		});

		// 根据默认收货地址，填写收货信息对应字段
		for (var i in addr_list) {
			if (addr_list[i].is_default == 1) {
				$('#info_addr').text(addr_list[i].consignee + "." + addr_list[i].phone + "." + addr_list[i].addr_str);
				break;
			}
		}
	});

	// 收货地址 change 回调
	function cb_addr_change(obj) {
		var id = obj.data("id");
		for (var i in addr_list) {
			if (addr_list[i].id == id) {
				$('#info_addr').text(addr_list[i].consignee + "." + addr_list[i].phone + "." + addr_list[i].addr_str);
				break;
			}
		}
		return true;
	}

	//设置默认收货地址
	function submit_set_default_addr(id) {

		var data = {
			'id': id
		};

		$.ajax({
			url: '/order/do_set_default_addr',
			type: "POST",
			data: data,
			dataType: "json",
			success: function(data) {
				if (data.code == 0) {
					window.location.reload();
				}
			},
			error: function(data) {
				console.log(data.responseText);
			}
		});
	}

	//删除地址
	function submit_delete_addr(id) {

		var data = {
			'id': id
		};

		$.ajax({
			url: '/order/do_delete_addr',
			type: "POST",
			data: data,
			dataType: "json",
			success: function(data) {
				if (data.code == 0) {
					window.location.reload();
				}
			},
			error: function(data) {
				console.log(data.responseText);
			}
		});
	}

	// 提交订单
	function submit_choose() {

		if ($('#submit_btn').hasClass("btn_Bhui")) {
			return false;
		}

		if (submit_flag == true) {
			return false;
		}

		var oldSubVal = $('.btn_hong').attr('value');
		$('.btn_hong').attr("disabled", 'disabled');
		$('.btn_hong').css('background-color', '#CDCDCD');
		$('.btn_hong').attr('value', $('#loadingTxt').val());

		var data = {};

		data.customer_id = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;

		var addr_id = $('.address-list li.selected').data('id');
		data.addr_id = addr_id;

		data.deliver_time_type = $('[name=deliver_time_type]:checked').val();

		data.remark = $('#remark').val();

		//不发送单据，0不发送，1发送
		if ($('.shouju').prop('checked')) {
//			data.need_receipt = 1;
			data.need_receipt = 0;
		} else {
			data.need_receipt = 0;
		}

		data.order_amount_usd = <?php echo $real_pay_money * 100; ?>;

		submit_flag = true;

		$.ajax({
			url: '/submit_order/do_checkout_order',
			type: "POST",
			data: data,
			dataType: "json",
			success: function(data) {
				submit_flag = false;
				if (data.code == 0) {
					window.location = "/order/pay/" + data.id;
				} else {
					layer.msg(data.msg);
					$('.btn_hong').attr("disabled", false);
					$('.btn_hong').css('background-color', '#D22215');
					$('.btn_hong').attr('value', oldSubVal);
				}
			},
			error: function(data) {
				submit_flag = false;
				console.log(data.responseText);
				layer.msg(data.responseText);
				$('.btn_hong').attr("disabled", false);
				$('.btn_hong').css('background-color', '#D22215');
				$('.btn_hong').attr('value', oldSubVal);
			}
		});
		return true;
	}
</script>