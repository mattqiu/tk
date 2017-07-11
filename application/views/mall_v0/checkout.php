<div class="bj_zhuti">
	<div class="MyCart">
		<div class="container clear">
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
							<?php foreach ($data as $v): ?>
								<li<?php if ($v['is_default']): ?> class="selected"<?php endif; ?> data-id="<?php echo $v['addr_id']; ?>" onclick="cb_addr_change($(this));">
									<i class="marker"></i>
									<span class="marker-tip"><?php echo lang('checkout_deliver_to'); ?></span>
									<input autocomplete="off" type="radio"<?php if ($v['is_default']): ?> checked<?php endif; ?> />
									<div class="address-info">
										<a href="javascript:void(0);" class="shanchu c-b" onclick="submit_delete_addr('<?php echo $v['addr_id']; ?>');"><?php echo lang('checkout_deliver_delete'); ?></a>
										<a href="javascript:showBg();" class="modify c-b" onclick="click_addr_edit('<?php echo $v['addr_id']; ?>');"><?php echo lang('checkout_deliver_modify'); ?></a>
										<label>
											<?php echo $v['addr_str']; ?>
											<?php
												if (!empty($v['zip_code']))
												{
													echo $v['zip_code'];
												}
											?>
											(<?php echo lang_attr('checkout_deliver_consignee', array('consignee' => $v['consignee'])); ?>)
											<em><?php echo isset($v['phone']) ? $v['phone'] : $v['reserve_num']; ?></em>
											<?php
												if (!empty($v['customs_clearance']))
												{
													echo "<em style=\"color: #f56f44;\">[{$v['customs_clearance']}]</em>";
												}
											?>
										</label>
										<?php if ($v['is_default']): ?>
											<em class="tip"><?php echo lang('checkout_deliver_default'); ?></em>
										<?php else: ?>
											<a href="javascript:void(0);" class="set-default c-b" onclick="submit_set_default_addr('<?php echo $v['addr_id']; ?>');">
												<?php echo lang('checkout_deliver_set_default'); ?>
											</a>
										<?php endif; ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="product_info_address"><?php echo $order_address_info_tip?></div>
				</div>
				<!-- 收货时间 -->
				<div class="body-bd clear">
					<h3><?php echo lang('checkout_deliver_time'); ?></h3>
					<div class="Shuo clear">
						<p>
							<label>
								<input type="radio" name="deliver_time_type" value="1" checked />
								<span><?php echo lang('checkout_deliver_time_period1'); ?></span>
								<i><?php echo lang('checkout_deliver_time_desc1'); ?></i>
							</label>
						</p>
						<p>
							<label>
								<input type="radio" name="deliver_time_type" value="2" />
								<span><?php echo lang('checkout_deliver_time_period2'); ?></span>
								<i><?php echo lang('checkout_deliver_time_desc2'); ?></i>
							</label>
						</p>
						<p>
							<label>
								<input type="radio" name="deliver_time_type" value="3" />
								<span><?php echo lang('checkout_deliver_time_period3'); ?></span>
							</label>
						</p>
					</div>
				</div>
				<!-- 订单商品信息 -->
				<div class="body-bd ddxx clear">
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
								<fieldset id="fs_goods_list">
								</fieldset>
							</dd>
						</dl>
						<!-- 商品清单页尾：配送时间、运费总计、订单实付 -->
						<p class="yunfei">
							<em>
								<?php echo lang('checkout_order_deliver_time'); ?>
								<span id="order_deliver_time_type"><?php echo lang('checkout_deliver_time_period1'); ?></span>
							</em>
							<?php echo lang('checkout_order_deliver_fee')." ".$curCur_flag; ?><a class="order_deliver_fee"></a>
							<span><?php echo lang('checkout_order_amount')." ".$curCur_flag; ?><a class="order_total_amount"></a></span>
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
								<dl class="clear">
									<dt><?php echo lang('checkout_receive_other'); ?></dt>
									<dd>
										<?php echo lang('checkout_receive_receipt'); ?>
										<input class="shouju" type="checkbox" checked />
									</dd>
								</dl>
							</div>
							<!-- 金额 -->
							<div class="right clear">
								<dl class="clear">
									<dt><?php echo lang('checkout_pay_product'); ?></dt>
									<dd><?php echo $curCur_flag; ?><span id="order_goods_amount"></span></dd>
								</dl>
								<dl class="clear bk bottom_15">
									<dt><?php echo lang('checkout_pay_deliver'); ?></dt>
									<dd><?php echo $curCur_flag; ?><span class="order_deliver_fee"></span></dd>
								</dl>
								<dl class="clear">
									<dt class="font"><?php echo lang('checkout_pay_amount'); ?></dt>
									<dd class="hse font">
										<em><?php echo $curCur_flag; ?></em><span class="order_total_amount"></span>
									</dd>
								</dl>
								<dl class="clear">
									<dd class="nnniu">
										<a class="btn_hong" id="submit_btn" href="javascript:void(0);" onclick="submit_order();">
											<?php echo lang('checkout_order_submit'); ?>
										</a>
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

	<!--收货地址-->
	<div class="item clear">
		<dl>
			<dt><?php echo lang('checkout_deliver_address'); ?><span>*</span></dt>
			<dd id="box_addr">
				<select class="select" id="box_country" onchange="cb_box_country();">
					<option value="0"><?php echo lang('checkout_addr_country'); ?></option>
				</select>
			</dd>
		</dl>
	</div>

	<!--详细收货地址-->
	<div class="item clear">
		<dl>
			<dt><span>&nbsp;</span></dt>
            <dd><textarea type="text" class="xxidz" id="box_addr_detail" maxlength="255" placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>"></textarea></dd>
		</dl>
	</div>

	<!--收货人-->
	<div class="item clear" id="div_consignee">
		<dl>
			<dt><?php echo lang('checkout_consignee'); ?><span>*</span></dt>
			<dd><input type="text" class="input" id="box_consignee"></dd>
		</dl>
	</div>

	<!--First Name-->
	<div class="item clear" id="div_first_name" hidden>
		<dl>
			<dt><?php echo lang('first_name'); ?><span>*</span></dt>
			<dd><input type="text"  maxlength="25" id="box_first_name" class="input" placeholder="<?php echo lang('first_name');?>"></dd>
		</dl>
	</div>
	<!--Last Name-->
	<div class="item clear" id="div_last_name" hidden>
		<dl>
			<dt><?php echo lang('last_name'); ?><span>*</span></dt>
			<dd><input type="text"  maxlength="25" id="box_last_name" class="input" placeholder="<?php echo lang('last_name');?>"></dd>
		</dl>
	</div>

	<!--联系电话-->
	<div class="item clear">
		<dl>
			<dt><?php echo lang('checkout_phone'); ?><span>*</span></dt>
			<dd><input type="text"  maxlength="50" class="input" id="box_phone"></dd>
		</dl>
	</div>

	<!--备用电话-->
	<div class="item clear">
		<dl>
			<dt><?php echo lang('checkout_reserve_num'); ?><span></span></dt>
			<dd><input type="text" class="input" id="box_reserve_num"></dd>
		</dl>
	</div>

	<!--邮编-->
	<div class="item clear" id="div_zip_code">
		<dl>
			<dt><?php echo lang('checkout_zip_code'); ?><span></span></dt>
			<dd><input type="text" maxlength="10" class="input" id="box_zip_code"></dd>
		</dl>
	</div>

	<!--海关号-->
	<div class="item clear" id="div_customs_clearance" hidden>
		<dl>
			<dt><?php echo lang('checkout_customs_clearance'); ?><span>*</span></dt>
			<dd><input type="text" class="input" id="box_customs_clearance"></dd>
		</dl>
	</div>


	<div class="item clear">
		<dl>
			<dt><span>&nbsp;</span></dt>
			<dd>
				<button class="btn shdz" type="button" onclick="submit_box_save_addr();">
					<?php echo lang('checkout_save_addr'); ?>
				</button>
			</dd>
		</dl>
	</div>
</div>
<div class="xm-backdrop" id="fullbg"></div>

<script src="<?php echo base_url('file/js/user_address_linkage.js?v=170206'); ?>"></script>
<script>
	var data = <?php echo json_encode($data); ?>;

	var lang_map = {
		"156": {
			"lv2": "<?php echo lang('checkout_addr_lv2_cn'); ?>",
			"lv3": "<?php echo lang('checkout_addr_lv3_cn'); ?>",
			"lv4": "<?php echo lang('checkout_addr_lv4_cn'); ?>"
		},
		"410": {
			"lv2": "<?php echo lang('checkout_addr_lv2_kr'); ?>",
			"lv3": "",
			"lv4": ""
		},
		"840": {
			"lv2": "<?php echo lang('checkout_addr_lv2_us'); ?>",
			"lv3": "",
			"lv4": ""
		}
	};

	var err_lang_map = {
		'country': "<?php echo lang('checkout_validator_country'); ?>",
		'addr_lv2': {
			"156": "<?php echo lang('checkout_validator_addr_lv2_cn'); ?>",
			"410": "<?php echo lang('checkout_validator_addr_lv2_kr'); ?>",
			"840": "<?php echo lang('checkout_validator_addr_lv2_us'); ?>",
		},
		'addr_lv3': {
			"156": "<?php echo lang('checkout_validator_addr_lv3_cn'); ?>",
		},
		'addr_lv4': {
			"156": "<?php echo lang('checkout_validator_addr_lv4_cn'); ?>",
		},
		'address_detail': "<?php echo lang('checkout_validator_address_detail'); ?>",
		'consignee': "<?php echo lang('checkout_validator_consignee'); ?>",
		'phone': "<?php echo lang('checkout_validator_phone'); ?>",
		'reserve_num': "<?php echo lang('checkout_validator_reserve_num'); ?>",
		'zip_code': "<?php echo lang('checkout_validator_zip_code'); ?>",
		'customs_clearance': "<?php echo lang('checkout_validator_customs_clearance'); ?>",
	};

	// 判断一个数组或对象是否为空
	function isEmpty(obj) {
		for (var p in obj) {
			return false;
		}
		return true;
	}

	// 防止重复提交
	var submit_flag = false;

	$(function() {
		'use strict';

		// 其他地区为000
		linkage['000'] = {name: '<?php echo lang('con_others'); ?>', leaf: []};

		// box append 国家列表
		$('#box_country').css('color', "#999");
		for (var country_code in linkage) {
			$('#box_country').append(
				$('<option/>').val(country_code).text(linkage[country_code].name)
			);
		}

		// 收货时间 change 事件，修改商品信息对应字段
		$('[name=deliver_time_type]').on('change', function() {
			var dlv_time = $('[name=deliver_time_type]:checked').next().text();
			$('#order_deliver_time_type').text(dlv_time);
		});

		// 根据默认收货地址，刷新订单信息
		for (var id in data) {
			if (data[id].is_default == 1) {
				refresh_order_info(id);
				break;
			}
		}
	});

	// 修改收货地址时刷新商品清单和订单金额
	function cb_addr_change(obj) {
		var addr_id = obj.data("id");

		// 商品信息
		refresh_order_info(addr_id);

		return true;
	}

	function shipping_type_change() {
		var addr_id = $('.address-list li.selected').data('id');

		var fee = +$(this).children(':selected').data("fee");

		$(this).parent().next().text("<?php echo lang('checkout_order_deliver_fee').$curCur_flag; ?>" + fee.toFixed(2));

		$('.order_deliver_fee').text(fee.toFixed(2));

		$('.order_total_amount').text(+data[addr_id].product_amount_show + fee)
	}

	// 根据地址 index 刷新商品信息表，标示不能寄送的商品，计算商品总额
	function refresh_order_info(i) {

		var checkout_flag = true;

		// 清掉数据
		$('#fs_goods_list').children().remove();

		// 商品金额、运费
		var shipping_fee = 0.00;
		var order_amount = 0.00;

		// 可配送清单
		if (isEmpty(data[i].deliverable_list)) {
			checkout_flag = false;
		} else {
			for (var j in data[i].deliverable_list) {
				$('#fs_goods_list').append(
					$('<div/>').addClass("x-hl clear").append(function() {
						var list_box = $('<div/>').addClass("col-md-10");
						for (var k in data[i].deliverable_list[j].list) {

							var item = data[i].deliverable_list[j].list[k];

							// 规格信息
							if (item.spec != "") {
								var spec = $('<em/>').text("<?php echo lang('checkout_order_color_size'); ?>" + item.spec);
							} else {
								var spec = false;
							}

							list_box.append(
								$('<div/>').addClass("col-md-10").append(
									$('<div/>').addClass("g-info").append(
										$('<span/>').append(
											$('<a/>').attr('href', "<?php echo base_url(); ?>index/product?snm=" + item.goods_sn_main).text(item.goods_name)
										)
									).append(
										spec
									)
								)
							).append(
								$('<div/>').addClass("col-md-1").append(
									$('<b/>').text("<?php echo $curCur_flag; ?>" + item.price_show)
								)
							).append(
								$('<div/>').addClass("col-md-1 center").append(
									$('<span/>').text(item.quantity)
								)
							);
						}

						return list_box;
					}).append(
						$('<div/>').addClass("col-md-2").append(function() {
							var peisong = $('<div/>').addClass("peisong clear");

							var select_tag = $('<select/>').addClass("select").attr("id", "deliver_id_" + j).on('change', shipping_type_change);

							if (isEmpty(data[i].deliverable_list[j].shipping_type)) {
								checkout_flag = false;
							} else {
								var shipping = data[i].deliverable_list[j].shipping_type;
								for (var k in shipping) {
									select_tag.append(
										$('<option/>').val(shipping[k].type).text(shipping[k].text).data("fee", shipping[k].fee)
									)
								}
							}

							peisong.append(
								$('<p/>').append(select_tag)
							);

							var fee = select_tag.children(':selected').data("fee");
							if (fee != undefined) {
								peisong.append(
									$('<p/>').append(
										"<?php echo lang('checkout_order_deliver_fee').$curCur_flag; ?>" + fee
									)
								);
							} else {
								peisong.append(
									$('<p/>').css('color', "#e4393c").text("<?php echo lang('checkout_order_deliver_unable'); ?>")
								)
							}

							// 运费
							shipping_fee += +fee;

							return peisong;
						})
					)
				);
			}
		}

		// 不能配送列表
		if (!isEmpty(data[i].invalid_list)) {
			$('#fs_goods_list').append(
				$('<div/>').addClass("x-hl clear").append(function() {
					var list_box = $('<div/>').addClass("col-md-10");
					for (var j in data[i].invalid_list) {

						var item = data[i].invalid_list[j];

						// 规格信息
						if (item.spec != "") {
							var spec = $('<em/>').addClass("hui").text("<?php echo lang('checkout_order_color_size'); ?>" + item.spec);
						} else {
							var spec = false;
						}

						list_box.append(
							$('<div/>').addClass("col-md-10").append(
								$('<div/>').addClass("g-info").append(
									$('<span/>').append(
										$('<a/>').addClass("hui").attr('href', "<?php echo base_url(); ?>index/product?snm=" + item.goods_sn_main).text(item.goods_name)
									)
								).append(
									spec
								)
							)
						).append(
							$('<div/>').addClass("col-md-1").append(
								$('<b/>').addClass("hui").text("<?php echo $curCur_flag; ?>" + item.price_show)
							)
						).append(
							$('<div/>').addClass("col-md-1 center").append(
								$('<span/>').addClass("hui").text(item.quantity)
							)
						).append(
							$('<div/>').addClass("col-md-10").append(
								$('<p/>').text(item.limit_area)
							)
						);
					}

					return list_box;
				})
			);
		}

		// 收货信息
		$('#info_addr').text(data[i].addr_str);

		// 商品金额
		$('#order_goods_amount').text(data[i].product_amount_show);

		// 订单无法提交
		if (checkout_flag == false) {
			order_amount = +data[i].product_amount_show;

			$('.order_deliver_fee').text("--");
			$("#submit_btn").removeClass("btn_hong").addClass("btn_Bhui");
		} else {
			order_amount = +data[i].product_amount_show + shipping_fee;

			$('.order_deliver_fee').text(shipping_fee.toFixed(2));
			$("#submit_btn").removeClass("btn_Bhui").addClass("btn_hong");
		}

		// 订单实付金额
		$('.order_total_amount').text(order_amount.toFixed(2));

		return true;
	}

	/******************** 收货地址 box start ********************/
	function refresh_box_addr_by_lv(id, lang, data)
	{
		var obj = $('#' + id);
		obj.children().remove();
		obj.append(
			$('<option/>').val(0).text(lang)
		);
		for (var code in data) {
			obj.append(
				$('<option/>').val(code).text(data[code].name)
			);
		}
		return true;
	}

	// 国家级联回调
	function cb_box_country()
	{
		var country_code = $('#box_country').val();

		$('#box_country').nextAll().remove();

		// 韩国特殊处理
		if (country_code === '410') {
			$('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder_kr'); ?>');
			$('#div_customs_clearance').prop('hidden', false);
			$('#div_zip_code').find('dt').find('span').text('*');
		} else {
			$('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
			$('#div_customs_clearance').prop('hidden', true);
		}

		//美国地址特殊处理
		if(country_code === '840'){
			$('#div_first_name').prop('hidden', false);
			$('#div_last_name').prop('hidden', false);
			$('#div_consignee').prop('hidden', true);
			$('#BOX_nav').css('width','700px');
			$('#div_zip_code').find('dt').find('span').text('*');
		}else{
			$('#box_city').prop('hidden', true);
			$('#div_consignee').prop('hidden', false);
			$('#div_first_name').prop('hidden', true);
			$('#div_last_name').prop('hidden', true);
			$('#BOX_nav').css('width','600px');
			if (country_code !== '410') {
				$('#div_zip_code').find('dt').find('span').text('');
			}
		}

		if (country_code === '0') {
			$('#box_country').css('color', "#999");
			return true;
		}
		$('#box_country').css('color', "#333");

		// 若 leaf 不为空，则可以走到 for 里面生成下一级
		for (var i in linkage[country_code].leaf) {
			$('#box_addr').append(
				$('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").on('change', cb_box_addr_lv2)
			);
			refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_code].lv2, linkage[country_code].leaf);
			break;
		}

		return true;
	}
	function cb_box_addr_lv2()
	{
		var country_code = $('#box_country').val();
		var lv2_code = $('#box_addr_lv2').val();

		$('#box_addr_lv2').nextAll().remove();

		//生成City Text
		if(country_code == 840){
			$('#box_addr').append(
				$('<input type="text" id="box_city" maxlength="25"/>').addClass("input").attr('placeholder', '<?php echo lang('city'); ?>')
			);
		}

		if (lv2_code == 0) {
			$('#box_addr_lv2').css('color', "#999");
			return true;
		}
		$('#box_addr_lv2').css('color', "#333");

		for (var i in linkage[country_code].leaf[lv2_code].leaf) {
			$('#box_addr').append(
				$('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv3").on('change', cb_box_addr_lv3)
			);
			refresh_box_addr_by_lv("box_addr_lv3", lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
			break;
		}
		return true;
	}
	function cb_box_addr_lv3()
	{
		var country_code = $('#box_country').val();
		var lv2_code = $('#box_addr_lv2').val();
		var lv3_code = $('#box_addr_lv3').val();

		$('#box_addr_lv3').nextAll().remove();

		if (lv3_code == 0) {
			$('#box_addr_lv3').css('color', "#999");
			return true;
		}
		$('#box_addr_lv3').css('color', "#333");

		for (var i in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
			$('#box_addr').append(
				$('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv4").on('change', cb_box_addr_lv4)
			);
			refresh_box_addr_by_lv("box_addr_lv4", lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
			break;
		}
		return true;
	}
	function cb_box_addr_lv4()
	{
		var lv4_code = $('#box_addr_lv4').val();

		if (lv4_code == 0) {
			$('#box_addr_lv4').css('color', "#999");
			return true;
		}
		$('#box_addr_lv4').css('color', "#333");
		return true;
	}

	function click_addr_add() {
		$('#box_title').text("<?php echo lang('checkout_addr_box_title_add'); ?>");
		$('#box_title_em').text("<?php echo lang('checkout_addr_box_title_add'); ?>");
		$('#box_type').val(1);

		// 清数据
		$('#box_consignee').val("");
		$('#box_phone').val("");
		$('#box_reserve_num').val("");
		$('#box_first_name').val("");
		$('#box_last_name').val("");
		$('#box_zip_code').val("");
		$('#div_zip_code').find('dt').find('span').text('');

		$('#box_addr_detail').val("");
		$('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
		$('#box_country').nextAll().remove();
		$('#box_country').css('color', "#999").children('[value=0]').prop('selected', true);
		$('#div_customs_clearance').prop('hidden', true);
	}

	function click_addr_edit(id) {

		var country_code = data[id].country;

		//编辑地址时，如果是美国地址,则特殊处理
		if(country_code == 840){
			$('#box_city').prop('hidden',false);
			$('#div_first_name').prop('hidden',false);
			$('#div_last_name').prop('hidden',false);
			$('#div_consignee').prop('hidden', true);
			$('#div_zip_code').find('dt').find('span').text('*');
            $('#BOX_nav').css('width','700px');
		}else{
			$('#box_city').prop('hidden',true);
			$('#div_first_name').prop('hidden',true);
			$('#div_last_name').prop('hidden',true);
			$('#div_consignee').prop('hidden', false);
			$('#div_zip_code').find('dt').find('span').text('');
            $('#BOX_nav').css('width','600px');
		}

		if (country_code === '410') {
			$('#div_zip_code').find('dt').find('span').text('*');
		}

		$('#box_title').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
		$('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
		$('#box_type').val(2);
		$('#box_id').val(id);

		$('#box_consignee').val(data[id].consignee);
		$('#box_phone').val(data[id].phone);
		$('#box_reserve_num').val(data[id].reserve_num);
		$('#box_addr_detail').val(data[id].address_detail);
		$('#box_zip_code').val(data[id].zip_code);

		// 韩国地址特殊处理
		if (country_code == 410) {
			$('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder_kr'); ?>');
			$('#div_customs_clearance').prop('hidden', false);
		} else {
			$('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
			$('#div_customs_clearance').prop('hidden', true);
		}

		// 海关报关号
		$('#box_customs_clearance').val(data[id].customs_clearance);

		$('#box_country').nextAll().remove();

		$('#box_country').css('color', "#333").children('[value=' + country_code + ']').prop('selected', true);

		for (var j in linkage[country_code].leaf) {
			$('#box_addr').append(
				$('<select/>').addClass("select").attr('id', "box_addr_lv2").on('change', cb_box_addr_lv2)
			);
			refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_code].lv2, linkage[country_code].leaf);
			break;
		}

		var lv2_code = data[id].addr_lv2;
		$('#box_addr_lv2').css('color', "#333").children('[value=' + lv2_code + ']').prop('selected', true);

		for (var j in linkage[country_code].leaf[lv2_code].leaf) {
			$('#box_addr').append(
				$('<select/>').addClass("select").attr('id', "box_addr_lv3").on('change', cb_box_addr_lv3)
			);
			refresh_box_addr_by_lv("box_addr_lv3", lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
			break;
		}

		//显示City的值
		if(country_code == 840){
			$('#box_addr').append(
				$('<input type="text" id="box_city" maxlength="25"/>').addClass("input").attr('placeholder', '<?php echo lang('city'); ?>')
			);
			$('#box_city').val(data[id].city);
			$('#box_first_name').val(data[id].first_name);
			$('#box_last_name').val(data[id].last_name);
		}

		if ($('#box_addr_lv3').length == 0) {
			return true;
		}

		var lv3_code = data[id].addr_lv3;
		$('#box_addr_lv3').css('color', "#333").children('[value=' + lv3_code + ']').prop('selected', true);

		for (var j in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
			$('#box_addr').append(
				$('<select/>').addClass("select").attr('id', "box_addr_lv4")
			);
			refresh_box_addr_by_lv("box_addr_lv4", lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
			break;
		}
		if ($('#box_addr_lv4').length == 0) {
			return true;
		}

		var lv4_code = data[id].addr_lv4;
		$('#box_addr_lv4').css('color', "#333").children('[value=' + lv4_code + ']').prop('selected', true);

	}

	function submit_box_save_addr() {

		var data = {};

		//国家ID
		var country = $('#box_country').children(':selected').val();
		if (country === '0') {
			layer.msg(err_lang_map.country);
			return false;
		}
		data.country = country;

		//二级地址
		if ($('#box_addr_lv2').children(':selected').val() == 0) {
			layer.msg(err_lang_map.addr_lv2[country]);
			return false;
		}
		data.addr_lv2 = $('#box_addr_lv2').children(':selected').val();

		//三级地址
		if ($('#box_addr_lv3').children(':selected').val() == 0) {
			layer.msg(err_lang_map.addr_lv3[country]);
			return false;
		}
		data.addr_lv3 = $('#box_addr_lv3').children(':selected').val();

		data.city = $('#box_city').val();

		//四级地址
		if ($('#box_addr_lv4').children(':selected').val() == 0) {
			layer.msg(err_lang_map.addr_lv4[country]);
			return false;
		}
		data.addr_lv4 = $('#box_addr_lv4').children(':selected').val();

		//五级地址
		if ($('#box_addr_lv5').children(':selected').val() == 0) {
			console.log("box_addr_lv5 null");
			return false;
		}
		data.addr_lv5 = $('#box_addr_lv5').children(':selected').val();

		//地址详情
		data.address_detail = $('#box_addr_detail').val();

		//联系人
		data.consignee = $('#box_consignee').val();

		//姓
		data.first_name = $('#box_first_name').val();

		//名
		data.last_name = $('#box_last_name').val();

		//电话
		data.phone = $('#box_phone').val();

		//备用电话
		data.reserve_num = $('#box_reserve_num').val();

		//邮编
		data.zip_code = $('#box_zip_code').val();

		//海关号
		data.customs_clearance = $('#box_customs_clearance').val();

		//普通订单
		data.order_type = 'general';

		var url = "";
		var type = $('#box_type').val();
		if (type == 1) {

			data.uid = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;

			data.is_default = 0;

			url = "/order/do_save_user_addr";

		} else if (type == 2) {

			data.id = $('#box_id').val();

			url = "/order/do_edit_user_addr";

		} else {
			return false;
		}

		$.ajax({
			url: url,
			type: "POST",
			data: data,
			dataType: "json",
			success: function(data) {
				if (data.code == 0)
				{
					window.location.reload();
				}
				else if(data.code == 204)
				{
					layer.msg(data.msg);
				}
				else
				{
					if(data.msg.indexOf('country') != -1){
						layer.msg(err_lang_map.country);
					}else if(data.msg.indexOf('addr_lv2') != -1){
						layer.msg(err_lang_map.addr_lv2);
					}else if(data.msg.indexOf('address_detail') != -1){
						layer.msg(err_lang_map.address_detail);
					}else if(data.msg.indexOf('consignee') != -1){
						layer.msg(err_lang_map.consignee);
					} else if(data.msg.indexOf('phone') != -1){
						layer.msg(err_lang_map.phone);
					}else if(data.msg.indexOf('reserve_num') != -1){
						layer.msg(err_lang_map.reserve_num);
					}else if(data.msg.indexOf('zip_code') != -1){
						layer.msg(err_lang_map.zip_code);
					}else {
						layer.msg(data.msg);
					}
				}
			},
			error: function(data) {
				console.log(data.responseText);
			}
		});
	}
	/******************** 收货地址 box end ********************/

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
	function submit_order() {

		if ($('#submit_btn').hasClass("btn_Bhui")) {
			return false;
		}

		if (submit_flag == true) {
			return false;
		}

		var ajax_data = {};

		ajax_data.customer_id = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;

		ajax_data.shopkeeper_id = <?php echo $store_id; ?>;

		var addr_id = $('.address-list li.selected').data('id');
		ajax_data.addr_id = addr_id;

		ajax_data.deliver_time_type = $('[name=deliver_time_type]:checked').val();

		ajax_data.remark = $('#remark').val();

		if ($('.shouju').prop('checked')) {
			ajax_data.need_receipt = 1;
		} else {
			ajax_data.need_receipt = 0;
		}

		var deliver = [];
		for (var store_code in data[addr_id].deliverable_list) {
			var list = [];
			for (var k in data[addr_id].deliverable_list[store_code].list) {
				list.push({
					goods_sn: data[addr_id].deliverable_list[store_code].list[k].goods_sn,
					quantity: data[addr_id].deliverable_list[store_code].list[k].quantity
				});
			}

			deliver.push({
				list: list,
				shipping_type: $('#deliver_id_' + store_code).children(':selected').val(),
				store_code: store_code
			});
		}

		ajax_data.deliver = deliver;

		ajax_data.clear_cart = "<?php echo $clear_cart; ?>";

		submit_flag = true;

		$.ajax({
			url: '/order/do_checkout_order',
			type: "POST",
			data: ajax_data,
			dataType: "json",
			success: function(data) {
				submit_flag = false;
				if (data.code == 0) {
					window.location = "/order/pay/" + data.id;
				} else if(data.code == 204){
					layer.msg(data.msg);
				} else {
					console.log(data);
					layer.msg(data.msg);
				}
			},
			error: function(data) {
				submit_flag = false;
				console.log(data.responseText);
				layer.msg(data.responseText);

			}
		});
		return true;
	}
</script>
