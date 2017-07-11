
<div class="bj_zhuti">
    <div class="MyCart">
        <div class="container clear">
            <h2><?php echo lang('checkout_confirm_info'); ?></h2>
            <div class="checkout-box clear">
                <div class="body-bd clear">
                    <div class="xinzeng clear"><b><?php echo lang('checkout_deliver_info'); ?></b></div>
                    <div class="xm-edit-addr">

                        <?php $this->load->view("mall/address/checkout_form.php") ?>

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
                        <p class="yunfei">
							<?php echo lang('checkout_order_deliver_fee'); ?> --
							<span></span>
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
<!--                                <dl class="clear">-->
<!--                                    <dt>--><?php //echo lang('checkout_receive_other'); ?><!--</dt>-->
<!--                                    <dd>-->
<!--                                        --><?php //echo lang('checkout_receive_receipt'); ?><!--<input autocomplete="off" class="shouju" type="checkbox" checked>-->
<!--                                    </dd>-->
<!--                                </dl>-->
                            </div>
                            <div class="right clear">
                                <dl class="clear">
                                    <dt><?php echo lang('checkout_pay_product'); ?></dt>
                                    <dd><?php echo "$".$total_money ?></dd>
                                </dl>
                                <dl class="clear bk bottom_15">
                                    <dt><?php echo lang('checkout_pay_deliver'); ?></dt>
                                    <dd>--</dd>
                                </dl>
                                <dl class="clear">
                                    <dt class="font"><?php echo lang('checkout_pay_amount'); ?></dt>
                                    <dd class="hse font">
										<?php echo "$".$real_pay_money ?>
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

<?php $this->load->view("mall/address/js.php") ?>

<script>

    $(function() {
        'use strict';
        address_init();
        $('[name=deliver_time]').on('change', function() {
            var dlv_time = $('[name=deliver_time]:checked').next().text();
            $('#order_deliver_time').text(dlv_time);
        });
    });

</script>
