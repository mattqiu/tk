<?php if(!isset($pay_str)){ ?>
<form action="" method="post" id="mall_form_submit">
<div class="MyCart">
	<div class="w1200">
		<div class="qren">
            <p class="title"><s></s><?php echo lang('pay_success_info'); ?><span><?php echo lang('pay_order_id').$order_id; ?></span></p>
			<p class="dingd">
				<?php echo lang('pay_warning_info'); ?>
				<br><a href="##" class="c-b"><?php echo lang('pay_order_info'); ?>↓</a>
			</p>
			<div class="p-dizhi">
				<p><?php echo lang('pay_deliver_addr'); ?><?php echo $order_info['order_detail']['address']; ?>, <?php echo lang('receiver_')?><?php echo $order_info['order_detail']['consignee']; ?>, <?php echo lang('checkout_phone')?>:<?php echo $order_info['order_detail']['phone']; ?></p>
				<p><?php echo lang('pay_goods_name'); ?>
					<?php foreach($order_info['goods_info'] as $goods_info){?>
						<?php echo $goods_info['goods_name']; ?><br>
					<?php }?>
				</p>

			</div>
            <p class="zhif"><?php echo lang('pay_amount'); ?><em><?php echo $pay_amount_show; ?></em></p>
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
            <div class="bodys clear">
                <?php foreach($payments as $k=>$payment){
                    $checked = '';
                    if($curLanguage == 'english' && $payment['pay_code'] === 'm_paypal'){
                        $checked = 'checked';
                    }else if($payment['pay_code'] === 'm_alipay'){
                        $checked = 'checked';
                    }
                    ?>
                <p class="clear">
                    <label class="margin20">
                        <input <?php echo $payment['pay_code'] === 'm_amount' ? 'class="amount_radio"':'class="payment_margin"'?> <?php echo $payment['pay_code'] === 'm_amount' ? $disabled_amount : '';  echo $checked;?>
                            autocomplete="off" type="radio" name="payment_method" value="<?php echo $payment['pay_id']?>"/>
                        <?php if($payment['pay_code'] === 'm_amount'){ ?>
                            <span style="font-size: 2.0em;font-weight: bold"><?php echo lang('current_commission')?> </span>: <strong style="color: #ee330a;font-size: 1.3em"><?php echo $my_amount?></strong>
                        <?php }else{ ?>
                        <img style="" src="<?php echo base_url("img/paymentMethod/".$payment["pay_code"].".png");?>" alt="<?php echo $payment['pay_name']?>">
                        <?php }?>
                    </label>
                  <?php }?>
                </p>
                <p class="clear">
    				<label class="item-ifo pay_p" style="display: none" ><input type="password" autocomplete="off" placeholder="<?php echo lang('funds_pwd');?>"  value="" name="pay_pwd" class="pay_pwd itxt"></label>
                    <input class="hidden" value="<?php echo lang('pls_sel_payment')?>" id="pls_sel_payment">
                    <input class="hidden" value="<?php echo lang('enter_funds_pwd')?>" id="enter_funds_pwd">
                </p>
		     	<p class="clear"><input autocomplete="off" type="button" class="btn btn_zhifu go_pay_all" value="<?php echo lang('go_pay')?>"></p>
            </div>
		</div>
   </div>
</div>
</form>
<script src="<?php echo base_url('themes/mall/js/pay_order.js?v=1'); ?>"></script>
<?php }else{ ?>
    <?php echo $pay_str; ?>
<?php } ?>

