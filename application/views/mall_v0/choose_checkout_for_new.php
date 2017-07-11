
<div class="bj_zhuti">
    <div class="MyCart">
        <div class="container clear">
            <h2><?php echo lang('checkout_confirm_info'); ?></h2>
            <div class="checkout-box clear">
                <div class="body-bd clear">
                    <div class="xinzeng clear"><b><?php echo lang('checkout_deliver_info'); ?></b></div>
                    <div class="xm-edit-addr">
                        <div class="item clear" class="input">
                            <label><?php echo lang('checkout_consignee'); ?><span>*</span></label>
                            <input type="text" class="input" id="consignee">
                        </div>
                        <div class="item clear">
                            <label><?php echo lang('checkout_phone'); ?><span>*</span></label>
                            <input type="text" class="input" id="phone">
                        </div>
                        <div class="item clear">
                            <label><?php echo lang('checkout_reserve_num'); ?><span>*</span></label>
                            <input type="text" class="input" id="reserve_num">
                        </div>
                        <div class="item clear" id="deliver_addr">
                            <label><?php echo lang('checkout_deliver_address'); ?><span>*</span></label>
                            <select class="select" id="country" onchange="cb_country();">
                                <option value="0"><?php echo lang('checkout_addr_country'); ?></option>
                            </select>
                        </div>
                        <div class="item clear">
                            <label><span></span></label>
                            <input style="margin-left: 17.5%" type="text" class="xxidz" id="address_detail" placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>" >
                        </div>

                        <!--邮编-->
                        <div class="item clear" id="div_zip_code">
                            <label><?php echo lang('checkout_zip_code'); ?><span>&nbsp;</span></label>
                            <input type="text" class="input" id="zip_code">
                        </div>

                        <!--海关号-->
                        <div class="item clear" id="div_customs_clearance" hidden>
                            <label><?php echo lang('checkout_customs_clearance'); ?><span>*</span></label>
                            <input type="text" class="input" id="customs_clearance">
                        </div>

                        <div class="item clear">
                            <label><span></span></label>
                            <button class="btn shdz" type="button" onclick="submit_save_address();"><?php echo lang('checkout_save_addr'); ?></button>
                        </div>
                    </div>
					<div class="product_info_address"><?php echo $order_address_info_tip ?></div>
                </div>
                <div class="body-bd clear">
                    <h3><?php echo lang('checkout_deliver_time'); ?></h3>
                    <div class="Shuo clear">
                        <p>
                            <label>
                                <input name="deliver_time" type="radio" checked>
                                <span><?php echo lang('checkout_deliver_time_period1'); ?></span>
                                <i><?php echo lang('checkout_deliver_time_desc1'); ?></i>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input name="deliver_time" type="radio">
                                <span><?php echo lang('checkout_deliver_time_period2'); ?></span>
                                <i><?php echo lang('checkout_deliver_time_desc2'); ?></i>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input name="deliver_time" type="radio">
                                <span><?php echo lang('checkout_deliver_time_period3'); ?></span>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="body-bd clear">
                    <h3><?php echo lang('checkout_order_info'); ?></h3>
                    <div class="box-bd">
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
                        <p class="yunfei">
                            <?php echo lang('checkout_order_deliver_fee'); ?> --
                            <span><?php echo lang('checkout_order_amount')." ".$curCur_flag.format_price_high_accuracy($cart_cont['total_amount'], $cur_rate); ?></span>
                        </p>
                        <ul class="beizhu clear">
                            <li><?php echo lang('checkout_order_remark'); ?></li>
                            <li><textarea class="input" data-error="" placeholder="<?php echo lang('checkout_order_remark_palceholder'); ?>"></textarea></li>
                            <li><?php echo lang('checkout_order_remark_tip'); ?></li>
                        </ul>
                        <div class="zongs clear">
                            <div class="left clear">
                                <dl class="clear">
                                    <dt><?php echo lang('checkout_receive_info'); ?></dt>
                                    <dd></dd>
                                </dl>
                                <dl class="clear">
                                    <dt><?php echo lang('checkout_receive_other'); ?></dt>
                                    <dd>
                                        <?php echo lang('checkout_receive_receipt'); ?><input class="shouju" type="checkbox" checked>
                                    </dd>
                                </dl>
                            </div>
                            <div class="right clear">
                                <dl class="clear">
                                    <dt><?php echo lang('checkout_pay_product'); ?></dt>
                                    <dd><?php echo $curCur_flag.format_price_high_accuracy($cart_cont['total_amount'], $cur_rate); ?></dd>
                                </dl>
                                <dl class="clear bk bottom_15">
                                    <dt><?php echo lang('checkout_pay_deliver'); ?></dt>
                                    <dd>--</dd>
                                </dl>
                                <dl class="clear">
                                    <dt class="font"><?php echo lang('checkout_pay_amount'); ?></dt>
                                    <dd class="hse font">
                                        <em><?php echo $curCur_flag; ?></em>
                                        <?php echo format_price_high_accuracy($cart_cont['total_amount'], $cur_rate); ?>
                                    </dd>
                                </dl>
                                <dl class="clear">
                                    <dd class="nnniu">
                                        <a class="btn_Bhui" href="javascript:void(0);">
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

<script src="<?php echo base_url('file/js/user_address_linkage.js?v=170206'); ?>"></script>
<script>
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

    $(function() {
        'use strict';
        // 其他地区为000
        linkage['000'] = {name: '<?php echo lang('con_others'); ?>', leaf: []};

        $('#country').css('color', "#999");
        for (var country_code in linkage)
        {
            $('#country').append(
                $('<option/>').val(country_code).text(linkage[country_code].name)
            );
        }

        $('[name=deliver_time]').on('change', function() {
            var dlv_time = $('[name=deliver_time]:checked').next().text();
            $('#order_deliver_time').text(dlv_time);
        });

    });

    function refresh_addr_by_lv(id, lang, data)
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
    function cb_country()
    {
        var country_code = $('#country').val();

        $('#country').nextAll().remove();

        // 韩国特殊处理
        if (country_code === '410') {
            $('#address_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder_kr'); ?>');
            $('#div_customs_clearance').prop('hidden', false);
			$('#div_zip_code').find('label').find('span').text('*');
        } else {
            $('#address_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
            $('#div_customs_clearance').prop('hidden', true);
        }

        if (country_code === '0') {
            $('#country').css('color', "#999");
            return true;
        }
        $('#country').css('color', "#333");

        // 若 leaf 不为空，则可以走到 for 里面生成下一级
        for (var i in linkage[country_code].leaf) {
            $('#deliver_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "addr_lv2").on('change', cb_addr_lv2)
            );
            refresh_addr_by_lv("addr_lv2", lang_map[country_code].lv2, linkage[country_code].leaf);
            break;
        }
        return true;
    }

    function cb_addr_lv2()
    {
        var country_code = $('#country').val();
        var lv2_code = $('#addr_lv2').val();

        $('#addr_lv2').nextAll().remove();

        if (lv2_code == 0) {
            $('#addr_lv2').css('color', "#999");
            return true;
        }
        $('#addr_lv2').css('color', "#333");

        for (var i in linkage[country_code].leaf[lv2_code].leaf) {
            $('#deliver_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "addr_lv3").on('change', cb_addr_lv3)
            );
            refresh_addr_by_lv("addr_lv3", lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
            break;
        }
        return true;
    }

    function cb_addr_lv3()
    {
        var country_code = $('#country').val();
        var lv2_code = $('#addr_lv2').val();
        var lv3_code = $('#addr_lv3').val();

        $('#addr_lv3').nextAll().remove();

        if (lv3_code == 0) {
            $('#addr_lv3').css('color', "#999");
            return true;
        }
        $('#addr_lv3').css('color', "#333");

        for (var i in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
            $('#deliver_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "addr_lv4").on('change', cb_addr_lv4)
            );
            refresh_addr_by_lv("addr_lv4", lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
            break;
        }
        return true;
    }

    function cb_addr_lv4()
    {
        var lv4_code = $('#addr_lv4').val();

        if (lv4_code == 0) {
            $('#addr_lv4').css('color', "#999");
            return true;
        }
        $('#addr_lv4').css('color', "#333");
        return true;
    }


    function submit_save_address()
    {
        var data = {};

        data.uid = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;

        data.is_default = 1;

        data.consignee = $('#consignee').val();

        data.phone = $('#phone').val();

        data.reserve_num = $('#reserve_num').val();

        var country = $('#country').children(':selected').val();
        if (country === '0') {
            layer.msg(err_lang_map.country);
            return false;
        }
        data.country = country;

        if ($('#addr_lv2').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv2[country]);
            return false;
        }
        data.addr_lv2 = $('#addr_lv2').children(':selected').val();

        if ($('#addr_lv3').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv3[country]);
            return false;
        }
        data.addr_lv3 = $('#addr_lv3').children(':selected').val();

        if ($('#addr_lv4').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv4[country]);
            return false;
        }
        data.addr_lv4 = $('#addr_lv4').children(':selected').val();

        if ($('#addr_lv5').children(':selected').val() == 0) {
            console.log("addr_lv5 null");
            return false;
        }
        data.addr_lv5 = $('#addr_lv5').children(':selected').val();

        data.address_detail = $('#address_detail').val();

        data.zip_code = $('#zip_code').val();

        data.customs_clearance = $('#customs_clearance').val();

        $.ajax({
            url: '/order/do_save_user_addr',
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

</script>
