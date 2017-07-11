<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<div class="MyCart">
	<div class="w1200">
		<h2><?php echo lang('checkout_confirm_info'); ?></h2>
		<div class="checkout-box clear">
			<div class="body-bd clear">
				<div class="xinzeng clear"><b><?php echo lang('checkout_deliver_info'); ?></b></div>
                <input type="hidden" id="box_type" />
                <input type="hidden" id="box_id" />
				<div class="xm-edit-addr">
                    <?php $this->load->view("mall/address/checkout_form.php") ?>

				</div>
				<div class="product_info_address"><?php echo $order_address_info_tip?></div>
			</div>
<!--			<div class="body-bd clear">-->
<!--				<h3>--><?php //echo lang('checkout_deliver_time'); ?><!--</h3>-->
<!--				<div class="Shuo clear">-->
<!--					<p>-->
<!--						<label>-->
<!--							<input name="deliver_time" type="radio" checked />-->
<!--							<span>--><?php //echo lang('checkout_deliver_time_period1'); ?><!--</span>-->
<!--							<i>--><?php //echo lang('checkout_deliver_time_desc1'); ?><!--</i>-->
<!--						</label>-->
<!--					</p>-->
<!--					<p>-->
<!--						<label>-->
<!--							<input name="deliver_time" type="radio" />-->
<!--							<span>--><?php //echo lang('checkout_deliver_time_period2'); ?><!--</span>-->
<!--							<i>--><?php //echo lang('checkout_deliver_time_desc2'); ?><!--</i>-->
<!--						</label>-->
<!--					</p>-->
<!--					<p>-->
<!--						<label>-->
<!--							<input name="deliver_time" type="radio" />-->
<!--							<span>--><?php //echo lang('checkout_deliver_time_period3'); ?><!--</span>-->
<!--						</label>-->
<!--					</p>-->
<!--				</div>-->
<!--			</div>-->
			<div class="body-bd clear">
				<h3><?php echo lang('checkout_order_info'); ?></h3>
				<div class="box-bd">
					<dl class="clear">
						<dt class="clear">
							<span class="col-md-5"><span class="P_left"><?php echo lang('checkout_order_list'); ?></span></span>
							<span class="col-md-2 tc"><?php echo lang('goods_attr'); ?></span>
							<span class="col-md-2 center"><?php echo lang('checkout_order_price'); ?></span>
							<span class="col-md-1 center"><?php echo lang('checkout_order_quantity'); ?></span>
							<span class="col-md-2 center"><?php echo lang('checkout_order_deliver'); ?></span>
						</dt>
						<dd class="clear">
							<div class="x-hl clear">
								<div class="col-md-10">
									<?php foreach ($data['goods_list'] as $v): ?>
									<div class="col-md-9">
										<span class="g-info">
											<span>
                                                <img src= "<?php echo "$img_host".$v['goods_img']?>" />
											</span>
                                            <a class="xq" href="<?php echo base_url(); ?>index/product?snm=<?php echo $v['goods_sn_main']; ?>">
                                                <?php echo $v['goods_name']; ?>
                                            </a>
											<?php if (!empty($v['spec'])): ?>
											<em><?php echo lang('checkout_order_color_size').$v['spec']; ?></em>
											<?php endif; ?>
										</span >
									</div>
									<div class="col-md-2 center"><b style="text-align: left"><?php echo $v['price_show']; ?></b></div>
									<div class="col-md-1 center"><?php echo $v['quantity']; ?></div>
									<?php endforeach; ?>
								</div>
							</div>
						</dd>
					</dl>
					<p class="yunfei">
						<span>
							<?php echo lang('checkout_order_deliver_time'); ?>
							<!--							<em id="order_deliver_time_type"></em>-->
							<select id="order_deliver_time_type" name="deliver_time_type" class="form-control">
								<option value="1"><?php echo lang('checkout_deliver_time_period1'); ?></option>
								<option value="2"><?php echo lang('checkout_deliver_time_period2'); ?></option>
								<option value="3"><?php echo lang('checkout_deliver_time_period3'); ?></option>
							</select>
						</span>
						<?php echo lang('checkout_order_deliver_fee')." ".$curCur_flag; ?><a class="order_deliver_fee"></a>
						<em><?php echo lang('checkout_order_amount')." ".$curCur_flag; ?><a class="order_total_amount c-o"></a></em>
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
<!--							<dl class="clear">-->
<!--								<dt>--><?php //echo lang('checkout_receive_other'); ?><!--</dt>-->
<!--								<dd>-->
<!--									--><?php //echo lang('checkout_receive_receipt'); ?><!--<input class="shouju" type="checkbox" checked>-->
<!--								</dd>-->
<!--							</dl>-->
						</div>
						<div class="right clear">
							<dl class="clear">
								<dt><?php echo lang('checkout_pay_product'); ?></dt>
								<dd><?php echo $data['amount_show']; ?></dd>
							</dl>
							<dl class="clear bk bottom_15">
								<dt><?php echo lang('checkout_pay_deliver'); ?></dt>
								<dd>--</dd>
							</dl>
							<dl class="clear">
								<dt class="font"><?php echo lang('checkout_pay_amount'); ?></dt>
								<dd class="hse font">
									<em><?php echo $data['amount_show']; ?></em>
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
<!--地址操作js包含引用-->
<?php $this->load->view("mall/address/js.php") ?>

<script>
$(function() {
	'use strict';
    address_init();
});

</script>
