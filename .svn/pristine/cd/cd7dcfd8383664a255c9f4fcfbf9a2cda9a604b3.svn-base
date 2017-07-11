<div class="MyCart">
    <div class="w1200">
        <!-- 确认订单信息 -->
        <h2><?php echo lang('checkout_confirm_info'); ?></h2>
        <div class="checkout-box clear">
            <!-- 收货地址列表 -->
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
														<span>
                                                            <img src= <?php echo "https://img.tps138.net/".$group['goods_img']?> />
                                                        </span>
                                                        <?php echo $group['goods_name']; ?><?php echo $group['goods_name']; ?>
                                                        <br><br>
                                                        <?php echo lang("label_sku").": ". $group['goods_sn'];?>
                                                    </div>
												</div>
												<div class="col-md-1">
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
														<span>
                                                            <img src= <?php echo "https://img.tps138.net/".$goods['goods_img']?> />
                                                        </span>
                                                        <?php echo $goods['goods_name']; ?>
                                                        <br><br>
                                                        <?php echo lang("label_sku").": ". $goods['goods_sn'];?>
													</div>
												</div>
												<div class="col-md-1">
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

                    <!--代品券清单-->
                    <?php if(date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon')){?>
                    <?php if($all_product['coupons'] != array()){?>
                        <dl class="ddxx mt-10 clear">
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
                                            <div class="col-md-10">
                                                <div class="col-md-10">
                                                    <div class="g-info">
                                                        <span class="coupons_box_cart" style="width: 70px;"><p>
                                                                <?php echo lang('d_p_coupons')?></p><p><?php echo $coupons['coupons_money'] ?></p>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1"><b><?php echo '$'.$coupons['coupons_money']; ?></b></div>
                                                <div class="col-md-1 center"><?php echo $coupons['coupons_num']; ?></div>
                                            </div>
                                    <?php } ?>

                                <?php } ?>

                        <?php } ?>
                                <div class="col-md-2">
                                    <div class="peisong clear">
                                        <p><?php echo lang('direct_send_your_account'); ?></p>
                                    </div>
                                </div>
                            </dd>
                        </dl>
                    <?php }?>
<!--                    <!-- 代品券清单 -->
                    <?php }?>



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
<!--                            <dl class="clear">-->
<!--                                <dt>--><?php //echo lang('checkout_receive_other'); ?><!--</dt>-->
<!--                                <dd>-->
<!--                                    --><?php //echo lang('checkout_receive_receipt'); ?><!--<input class="shouju"-->
<!--                                                                                          type="checkbox"-->
<!--                                                                                          checked>-->
<!--                                </dd>-->
<!---->
<!--                            </dl>-->

                        </div>
                        <!-- 金额 -->
                        <div class="right clear">
                            <dl class="clear">
                                <dt><?php echo lang('checkout_pay_product'); ?></dt>
                                <dd>
                                    <?php echo "$" . $total_money; ?>
                                </dd>
                            </dl>
                            <dl class="clear bk bottom_15">
                                <dt><?php echo lang('checkout_pay_deliver'); ?></dt>
                                <dd><?php echo lang('send_free'); ?></dd>
                            </dl>

                            <!--如果是代品券页面,则加提示语-->
                            <?php if($order_type == "coupons"){?>
                                <p style="color: #008000">
                                    <b><?php echo lang_attr('you_coupons_total_money',$coupons_list)?></b>
                                    <b>(<?php echo lang("system_auto_clear")?>)</b>
                                </p><br>
                            <?php } ?>

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

<?php $this->load->view("mall/address/js.php") ?>

<script>
    //刷新一次
    onload=refresh()
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

    function refresh(){
        var url = location.href;
        var times = url.split("?a=");
        if(times[times.length-1] != 'fresh') {
            url += "?a=fresh";
            self.location.replace(url);
        }
    }
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
        var data = {};
        data.customer_id = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;
        data.shopkeeper_id = <?php echo $store_id; ?>;
        var addr_id = $('.address-list li.selected').data('id')
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
        //关闭前台验证
        var not_deliever_flag = true;
        if (not_deliever_flag == true) {
            //冬季时间不发货的省份：
            //黑龙江、辽宁、吉林、新疆、西藏、内蒙古共6个地区，暂停发货时间暂定为：2016-11-30到2017-3-1
            //提示语：
            //您的订单中包含有不能发货到您所填写的收货地区的商品（商品编码：XXXXXXXX），请您核实后重新提交订单！
            //start
            var start_time = "<?php echo config_item('not_deliever_goods_start')?>"
            var end_time = "<?php echo config_item('not_deliever_goods_end')?>"
            var start_time_stamp = Date.parse(new Date(start_time));
            var end_time_stamp = Date.parse(new Date(end_time));
            start_time_stamp = start_time_stamp / 1000;
            end_time_stamp = end_time_stamp / 1000;
            //如果时间在这个区间进行检测
            var now_time_stamp = Date.parse(new Date());
            now_time_stamp = now_time_stamp / 1000;
            if (now_time_stamp > start_time_stamp && now_time_stamp < end_time_stamp) {
                var address_list = {};
                address_list = <?php echo json_encode($addr_list)?>;
                var address_info = '';
                if (address_list.length > 0) {
                    for (var i=0; i<address_list.length; i++) {
                        if (addr_id == address_list[i]['id']) {
                            address_info = address_list[i];
                            break;
                        }
                    }
                    //不支持的配送区域
                    //不支持的商品
                    var not_deliever_district = <?php echo json_encode(config_item("not_deliever_district"));?>;

                    console.log(not_deliever_district);

                    var not_deliever_goods = <?php echo json_encode(config_item("not_deliever_goods"));?>;
                    console.log(not_deliever_goods);

                    if (-1 !== not_deliever_district.indexOf(address_info.addr_lv2)){
                        //区域 遍历查询是否有商品不配送
                        var not_deliver = [];
                        var goods_sn_list = <?php echo json_encode($goods_sn_list);?>;
                        for(var j = 0; j < goods_sn_list.length; j++) {
                            if (not_deliever_goods.indexOf(goods_sn_list[j]) !== -1) {
                                not_deliver.push(goods_sn_list[j]);
                            }

                        }
                        //console.log(goods_sn_list);
                        if (not_deliver.length > 0) {
                            var not_deliver_str = not_deliver.join(',');
                            var area_cannot_reach_1 = '<?php echo lang('area_cannot_reach_1')?>';
                            var area_cannot_reach_2 = '<?php echo lang('area_cannot_reach_2')?>';
                            var attention = area_cannot_reach_1+not_deliver_str+area_cannot_reach_2
                            layer.msg(attention);
                            return false;
                        }
                    } else {

                    }

                }


            } else {

            }
        }

        var oldSubVal=$('.btn_hong').attr('value');
        $('.btn_hong').attr("disabled", 'disabled');
        $('.btn_hong').css('background-color','#CDCDCD');
        $('.btn_hong').attr('value', $('#loadingTxt').val());
        data.address_id = addr_id;
        data.consignee = addr.consignee;
        data.phone = addr.phone;
        data.reserve_num = addr.reserve_num;
        data.country = addr.country;
        data.zip_code = addr.zip_code;
        data.customs_clearance = addr.customs_clearance;
        data.address = addr.addr_str;

        data.deliver_time_type = $('[name=deliver_time_type]:checked').val();

        data.remark = $('#remark').val();

        //不发送单据，0不发送，1发送
        if ($('.shouju').prop('checked')) {
//            data.need_receipt = 1;
            data.need_receipt = 0;
        } else {
            data.need_receipt = 0;
        }

        data.currency = "<?php echo $curCur; ?>";

        data.currency_rate = <?php echo $cur_rate; ?>;
        var order_type = "<?php echo $order_type; ?>";
        if(order_type == 'choose') {
            $.ajax({
                type: "POST",
                url: "/choose_goods_checkout/submit_order",
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
        if(order_type == 'upgrade') {
//            layer.confirm("<?php //echo lang('tip_con')?>//", {
//                icon: 3,
//                title: "<?php //echo lang('tip_title')?>//",
//                closeBtn: 2,
//                btn: ['<?php //echo lang('yes');?>//', '<?php //echo lang('no');?>//']
//            }, function() {
//                if($('.layui-layer-btn0').attr("disabled") == 'disabled') {
//                    return false;
//                }
//                $('.layui-layer-btn0').attr("disabled", 'disabled');
                $.ajax({
                    type: "POST",
                    url: "/choose_goods_for_upgrade_checkout/submit_order",
                    data: {data:data},
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            window.location = "/order/pay/" + data.id;
                        }else{
                            $('.btn_hong').attr("disabled", false);
                            $('.btn_hong').css('background-color','#D22215 ');
                            $('.btn_hong').attr('value',oldSubVal);
                            layer.msg(data.msg);
                        }
                    }
                });
//            });
        }

        if(order_type == 'exchange') {
//            layer.confirm("<?php //echo lang('tip_con')?>//", {
//                icon: 3,
//                title: "<?php //echo lang('tip_title')?>//",
//                closeBtn: 2,
//                btn: ['<?php //echo lang('yes');?>//', '<?php //echo lang('no');?>//']
//            }, function() {
//                if($('.layui-layer-btn0').attr("disabled") == 'disabled') {
//                    return false;
//                }
//                $('.layui-layer-btn0').attr("disabled", 'disabled');
                $.ajax({
                    type: "POST",
                    url: "/choose_goods_for_exchange_checkout/submit_order",
                    data: {data:data},
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            if(data.code == 0){
                                layer.msg(data.msg);
                                setTimeout(function(){
                                    window.location.href="ucenter/my_other_orders";
                                },500)
                            }
                            if(data.code == 101){
                                window.location = "/order/pay/" + data.id;
                            }
                        }else{
                            $('.btn_hong').attr("disabled", false);
                            $('.btn_hong').css('background-color','#D22215 ');
                            $('.btn_hong').attr('value',oldSubVal);
                            layer.msg(data.msg);
                        }
                    }
                });
//            });
        }

        if(order_type == 'coupons') {
            $.ajax({
                type: "POST",
                url: "/choose_goods_for_coupons_checkout/submit_order",
                data: {data:data},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        if(data.code == 0){
                            layer.msg(data.msg);
                            setTimeout(function(){
                                window.location.href="ucenter/my_other_orders";
                            },500)
                        }
                        if(data.code == 101){
                            window.location = "/order/pay/" + data.id;
                        }
                    }else{
                        $('.btn_hong').attr("disabled", false);
                        $('.btn_hong').css('background-color','#D22215 ');
                        $('.btn_hong').attr('value',oldSubVal);
                        layer.msg(data.msg);
                    }
                }
            });
        }
    }
</script>