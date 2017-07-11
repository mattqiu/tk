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
                            <a href="javascript:showBg();" class="XS_btn"
                               onclick="click_addr_add();"><i>+</i><?php echo lang('checkout_deliver_add'); ?></a>
                        </div>
                        <div class="address-list clear">
                            <ul>
                                <?php foreach ($address_list as $k => $v): ?>
                                    <li<?php if ($v['is_default']): ?> class="selected"<?php endif; ?>
                                        data-id="<?php echo $v['id']; ?>" onclick="cb_addr_change($(this));">
                                        <i class="marker"></i>
                                        <span class="marker-tip"><?php echo lang('checkout_deliver_to'); ?></span>
										<input autocomplete="off" type="radio"<?php if ($v['is_default']): ?> checked<?php endif; ?>>
                                        <div class="address-info">
                                        	<a href="javascript:void(0);" class="shanchu c-b"
                                               onclick="submit_delete_addr('<?php echo $v['id']; ?>');"><?php echo lang('checkout_deliver_delete'); ?></a>
                                            <a href="javascript:showBg();" class="modify c-b"
                                               onclick="click_addr_edit('<?php echo $k; ?>');"><?php echo lang('checkout_deliver_modify'); ?></a>
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
                            <dl class="ddxx clear">
                                <dt class="clear">
                                    <span class="col-md-8"><span
                                            class="P_left"><?php echo lang('checkout_order_list'); ?></span></span>
                                    <span class="col-md-1 center"><?php echo lang('checkout_order_price'); ?></span>
                                    <span class="col-md-1 center"><?php echo lang('checkout_order_quantity'); ?></span>
                                    <span class="col-md-2 center"><?php echo lang('checkout_order_deliver'); ?></span>
                                </dt>
								<dd class="clear">
									<div class="x-hl clear">
										<div class="col-md-10">
											<?php foreach ($all_product as $k => $v) { ?>
												<?php if ($k == 'group') { ?>
													<?php foreach ($v as $group) { ?>
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
													<?php } ?>
												<?php } ?>
												<?php if ($k == 'goods') { ?>
													<?php foreach ($v as $goods) { ?>
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
													<?php } ?>
												<?php } ?>
											<?php } ?>
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

                            <dl class="ddxx clear">
                                <dt class="clear">
                                    <span class="col-md-8"><span class="P_left"><?php echo lang('the_coupons_list'); ?></span></span>
                                    <span class="col-md-1 center"><?php echo lang('checkout_order_price'); ?></span>
                                    <span class="col-md-1 center"><?php echo lang('checkout_order_quantity'); ?></span>
                                    <span class="col-md-2 center"><?php echo lang('checkout_order_deliver'); ?></span>
                                </dt>
                                <?php foreach ($all_product as $k => $v) { ?>
                                    <?php if ($k == 'coupons') { ?>
                                        <?php foreach ($v as $coupons) { ?>
                                            <dd class="clear">
                                                <div class="col-md-8">
                                                    <div class="g-info">
                                                        <span class="coupons_box_cart" style="width: 70px;"><p>
                                                                <?php echo lang('d_p_coupons')?></p><p><?php echo $coupons['coupons_money'] ?></p></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 center">
                                                    <b><?php echo '$'.$coupons['coupons_money']; ?></b></div>
                                                <div
                                                    class="col-md-1 center"><?php echo $coupons['coupons_num']; ?></div>
                                            </dd>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <dd class="clear">
                                    <div class="col-md-2 psfs">
                                        <div class="peisong">
                                            <p><?php echo lang('direct_send_your_account'); ?></p>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <!-- 金额 -->
                            <p class="yunfei">
                                <?php echo lang('checkout_order_deliver_fee') . " " . lang('send_free'); ?>
                            </p>
                            <!-- 备注 -->
                            <ul class="beizhu clear">
                                <li><?php echo lang('checkout_order_remark'); ?></li>
                                <li>
                                    <textarea class="input" id="remark"
                                              placeholder="<?php echo lang('checkout_order_remark_palceholder'); ?>"></textarea>
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
                                            <?php echo lang('checkout_receive_receipt'); ?><input class="shouju"
                                                                                                  type="checkbox"
                                                                                                  checked>
                                        </dd>
                                    </dl>
                                </div>
                                <!-- 金额 -->
                                <div class="right clear">
                                    <dl class="clear">
                                        <dt><?php echo lang('checkout_pay_product'); ?></dt>
                                        <dd><?php echo "$" . $total_money; ?></dd>
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
                                            <input type="button"  style="width: 150px; height: 40px;" class="btn_hong btn-Login" id="submit_choose_info" onclick="submit_choose();" value="<?php echo lang('checkout_order_submit'); ?>">
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
        <input type="hidden" id="box_type"/>
        <input type="hidden" id="box_id"/>
    </div>
    <div class="item clear">
        <dl>
            <dt><?php echo lang('checkout_consignee'); ?><span>*</span></dt>
            <dd><input type="text" class="input" id="box_consignee"></dd>
        </dl>
    </div>
    <div class="item clear">
        <dl>
            <dt><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd><input type="text" class="input" id="box_phone"></dd>
        </dl>
    </div>
    <div class="item clear">
        <dl>
            <dt><?php echo lang('checkout_reserve_num'); ?><span>*</span></dt>
            <dd><input type="text" class="input" id="box_reserve_num"></dd>
        </dl>
    </div>
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
    <div class="item clear">
        <dl>
            <dt><span></span></dt>
            <dd><textarea type="text" class="xxidz" id="box_addr_detail" maxlength="255"
                          placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>"></textarea>
            </dd>
        </dl>
    </div>

    <!--邮编-->
    <div class="item clear" id="div_zip_code">
        <dl>
            <dt><?php echo lang('checkout_zip_code'); ?><span>&nbsp;</span></dt>
            <dd><input type="text" class="input" id="box_zip_code"></dd>
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
            <dt><label><span></span></label></dt>
            <dd>
                <button class="btn shdz" type="button"
                        onclick="submit_box_save_addr();"><?php echo lang('checkout_save_addr'); ?></button>
            </dd>
        </dl>
    </div>
</div>
<div class="xm-backdrop" id="fullbg"></div>

<script src="<?php echo base_url('file/js/user_address_linkage.js?v=170206'); ?>"></script>
<script>

    var addr_list = <?php echo json_encode($address_list); ?>;
    var cart_cont = <?php echo json_encode($cart_cont); ?>;

    var lang_map = {
        "156": {
            "lv2": "<?php echo lang('checkout_addr_lv2_cn'); ?>",
            "lv3": "<?php echo lang('checkout_addr_lv3_cn'); ?>",
            "lv4": "<?php echo lang('checkout_addr_lv4_cn'); ?>",
        },
        "410": {
            "lv2": "<?php echo lang('checkout_addr_lv2_kr'); ?>",
            "lv3": "",
            "lv4": "",
        },
        "840": {
            "lv2": "<?php echo lang('checkout_addr_lv2_us'); ?>",
            "lv3": "",
            "lv4": "",
        },
    };

    var err_lang_map = {
        'consignee': "<?php echo lang('checkout_validator_consignee'); ?>",
        'phone': "<?php echo lang('checkout_validator_phone'); ?>",
        'reserve_num': "<?php echo lang('checkout_validator_reserve_num'); ?>",
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
        'customs_clearance': "<?php echo lang('checkout_validator_customs_clearance'); ?>",
    };

    // 防止重复提交
    var submit_flag = false;

    $(function () {
        'use strict';

        // 其他地区为000
        linkage['000'] = {name: '<?php echo lang('con_others'); ?>', leaf: []};


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

        // box 国家
        $('#box_country').css('color', "#999");
        for (var country_code in linkage) {
            $('#box_country').append(
                $('<option/>').val(country_code).text(linkage[country_code].name)
            );
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
        } else {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
            $('#div_customs_clearance').prop('hidden', true);
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
		$('#div_zip_code').find('dt').find('span').text('');

        $('#box_addr_detail').val("");
        $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
        $('#box_country').nextAll().remove();
        $('#box_country').css('color', "#999").children('[value=0]').prop('selected', true);
        $('#div_customs_clearance').prop('hidden', true);
    }

    function click_addr_edit(i) {

        var country_code = addr_list[i].country;
        $('#box_country').css('color', "#333").children('[value=' + country_code + ']').prop('selected', true);

        // 韩国地址特殊处理
        if (country_code == 410) {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder_kr'); ?>');
            $('#div_customs_clearance').prop('hidden', false);
			$('#div_zip_code').find('dt').find('span').text('*');
        } else {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
            $('#div_customs_clearance').prop('hidden', true);
        }

        $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
        $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
        $('#box_type').val(2);
        $('#box_id').val(addr_list[i].id);

        $('#box_consignee').val(addr_list[i].consignee);
        $('#box_phone').val(addr_list[i].phone);
        $('#box_reserve_num').val(addr_list[i].reserve_num);

        $('#box_addr_detail').val(addr_list[i].address_detail);

        // 邮编
        $('#box_zip_code').val(addr_list[i].zip_code);

        // 海关报关号
        $('#box_customs_clearance').val(addr_list[i].customs_clearance);

        $('#box_country').nextAll().remove();

        for (var j in linkage[country_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").attr('id', "box_addr_lv2").on('change', cb_box_addr_lv2)
            );
            refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_code].lv2, linkage[country_code].leaf);
            break;
        }

        var lv2_code = addr_list[i].addr_lv2;
        $('#box_addr_lv2').css('color', "#333").children('[value=' + lv2_code + ']').prop('selected', true);

        for (var j in linkage[country_code].leaf[lv2_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").attr('id', "box_addr_lv3").on('change', cb_box_addr_lv3)
            );
            refresh_box_addr_by_lv("box_addr_lv3", lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
            break;
        }
        if ($('#box_addr_lv3').length == 0) {
            return true;
        }

        var lv3_code = addr_list[i].addr_lv3;
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

        var lv4_code = addr_list[i].addr_lv4;
        $('#box_addr_lv4').css('color', "#333").children('[value=' + lv4_code + ']').prop('selected', true);

    }

    function submit_box_save_addr() {

        var data = {};

        data.consignee = $('#box_consignee').val();

        data.phone = $('#box_phone').val();

        data.reserve_num = $('#box_reserve_num').val();

        var country = $('#box_country').children(':selected').val();
        if (country === '0') {
            layer.msg(err_lang_map.country);
            return false;
        }
        data.country = country;

        if ($('#box_addr_lv2').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv2[country]);
            return false;
        }
        data.addr_lv2 = $('#box_addr_lv2').children(':selected').val();

        if ($('#box_addr_lv3').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv3[country]);
            return false;
        }
        data.addr_lv3 = $('#box_addr_lv3').children(':selected').val();

        if ($('#box_addr_lv4').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv4[country]);
            return false;
        }
        data.addr_lv4 = $('#box_addr_lv4').children(':selected').val();

        if ($('#box_addr_lv5').children(':selected').val() == 0) {
            console.log("box_addr_lv5 null");
            return false;
        }
        data.addr_lv5 = $('#box_addr_lv5').children(':selected').val();

        data.address_detail = $('#box_addr_detail').val();

        data.zip_code = $('#box_zip_code').val();

        data.customs_clearance = $('#box_customs_clearance').val();

		//选购订单
		data.order_type = 'choose';

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
                if (data.code == 0) {
                    window.location.reload();
                } else {
                    if (data.msg.indexOf("consignee") != -1) {
                        layer.msg(err_lang_map.consignee);
                    } else if (data.msg.indexOf("phone") != -1) {
                        layer.msg(err_lang_map.phone);
                    } else if (data.msg.indexOf("reserve_num") != -1) {
                        layer.msg(err_lang_map.reserve_num);
                    } else if (data.msg.indexOf("address_detail") != -1) {
                        layer.msg(err_lang_map.address_detail);
                    } else if (data.msg.indexOf("customs_clearance") != -1) {
                        layer.msg(data.msg);
                    } else {
                        console.log(data);
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
    function submit_choose() {

        var oldSubVal=$('.btn_hong').attr('value');
        $('.btn_hong').attr("disabled", 'disabled');
        $('.btn_hong').css('background-color','#CDCDCD');
        $('.btn_hong').attr('value', $('#loadingTxt').val());

        var data = {};

        data.customer_id = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;

        data.shopkeeper_id = <?php echo $store_id; ?>;

        var addr_id = $('.address-list li.selected').data('id');
        for (var i in addr_list) {
            if (addr_list[i].id == addr_id) {
                var addr = addr_list[i];
                break;
            }
        }
        if (addr == null)
        {
            layer.msg("invalid deliver address");
            return false;
        }

        data.address_id = addr_id;
        data.consignee = addr.consignee;
        data.phone = addr.phone;
        data.reserve_num = addr.reserve_num;
        data.country = addr.country;
        data.address = addr.address;
        data.country_address = addr.country_address;
        data.zip_code = addr.zip_code;
        data.customs_clearance = addr.customs_clearance;

        data.deliver_time_type = $('[name=deliver_time_type]:checked').val();

        var goods_list = "";
        for (var i in cart_cont.items) {
            goods_list += cart_cont.items[i].goods_sn + ":" + cart_cont.items[i].quantity + "$";
        }
        data.goods_list = goods_list.substr(0, goods_list.length - 1);

        data.remark = $('#remark').val();

        if ($('.shouju').prop('checked')) {
            data.need_receipt = 1;
        } else {
            data.need_receipt = 0;
        }

        data.currency = "<?php echo $curCur; ?>";

        data.currency_rate = <?php echo $cur_rate; ?>;

        data.goods_amount_usd = <?php echo $cart_cont['total_amount']; ?>;

        data.deliver_fee_usd = addr.fee_usd;

        data.order_amount_usd = addr.amount_total_usd;
        $.ajax({
            type: "POST",
            url: "/clearing/submit_choose_package",
            data: {data:data},
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    layer.msg(data.msg);
                    setTimeout(function(){
                        window.location.href="ucenter/my_other_orders";
                    },500)
                }else{
                    $('.btn_hong').attr("disabled", false);
                    $('.btn_hong').css('background-color','#D22215 ');
                    $('.btn_hong').attr('value',oldSubVal);
                    layer.msg(data.msg);
                }
            }
        });
    }
</script>