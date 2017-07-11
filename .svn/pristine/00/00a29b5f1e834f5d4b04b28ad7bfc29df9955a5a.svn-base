<div class="bj_zhuti">
    <div class="MyCart">
        <div class="container clear">
            <div class="row clear">
                <div class="cg_hui clear">
                    <div class="chengg">
                        <?php if($result['success']){?>
                        <p class="tit"><s></s><?php echo lang('pay_success_'); ?></p>
                        <p class="zhif"><?php echo lang('pay_amount_'); ?><em><?php echo $amount ?></em></p>
                        <p class="dingd"><?php echo lang('you_can_'); ?><!--<a class="c-b" href="<?php /*echo base_url('ucenter/my_other_orders')*/?>"><?php /*echo lang('my_baby_'); */?></a>--><a class="c-b" href="<?php echo base_url('ucenter/my_orders_action/order_info/'.$result['order_id'])?>"><?php echo lang('pay_order_details_'); ?></a></p>
                        <p class="tishi"><b>!</b><em><?php echo lang('security_alert_'); ?></em> <span><?php echo lang('security_alert_content'); ?></span></p>
                        <?php }else{?>
                            <p class="zhif" style="text-align: center;"><em><?php echo isset($result['msg'])? $result['msg'] : lang('no_tra'); ?></em></p>
                        <?php }?>

                    </div>
                    <div class="tuijian clear">
                        <!--<b>热销商品</b>
                        <div class="col-md-2 clear">
                            <div class="Drive marg_10 Single clear">
                                <div class="cp_detail"> <a href="">
                                        <h4><span>30+</span> Verified Suppliers of Consumer Electronics</h4>
                                        <p class="title"><span class="pink">$288.25</span>/<s>$368.00</s></p>
                                    </a> </div>
                                <div class="img_box"><a class="Collect" href=""><s></s>Collect</a><a href=""><img src="img/cp.jpg"></a></div>
                            </div>
                        </div>
                        <div class="col-md-2 clear">
                            <div class="Drive marg_10 Single clear">
                                <div class="cp_detail"> <a href="">
                                        <h4><span>30+</span> Verified Suppliers of Consumer Electronics</h4>
                                        <p class="title"><span class="pink">$288.25</span>/<s>$368.00</s></p>
                                    </a> </div>
                                <div class="img_box"><a class="Collect" href=""><s></s>Collect</a><a href=""><img src="img/cp.jpg"></a></div>
                            </div>
                        </div>
                        <div class="col-md-2 clear">
                            <div class="Drive marg_10 Single clear">
                                <div class="cp_detail"> <a href="">
                                        <h4><span>30+</span> Verified Suppliers of Consumer Electronics</h4>
                                        <p class="title"><span class="pink">$288.25</span>/<s>$368.00</s></p>
                                    </a> </div>
                                <div class="img_box"><a class="Collect" href=""><s></s>Collect</a><a href=""><img src="img/cp.jpg"></a></div>
                            </div>
                        </div>
                        <div class="col-md-2 clear">
                            <div class="Drive marg_10 Single clear">
                                <div class="cp_detail"> <a href="">
                                        <h4><span>30+</span> Verified Suppliers of Consumer Electronics</h4>
                                        <p class="title"><span class="pink">$288.25</span>/<s>$368.00</s></p>
                                    </a> </div>
                                <div class="img_box"><a class="Collect" href=""><s></s>Collect</a><a href=""><img src="img/cp.jpg"></a></div>
                            </div>
                        </div>
                        <div class="col-md-2 clear">
                            <div class="Drive marg_10 Single clear">
                                <div class="cp_detail"> <a href="">
                                        <h4><span>30+</span> Verified Suppliers of Consumer Electronics</h4>
                                        <p class="title"><span class="pink">$288.25</span>/<s>$368.00</s></p>
                                    </a> </div>
                                <div class="img_box"><a class="Collect" href=""><s></s>Collect</a><a href=""><img src="img/cp.jpg"></a></div>
                            </div>
                        </div>
                        <div class="col-md-2 clear">
                            <div class="Drive marg_10 Single clear">
                                <div class="cp_detail"> <a href="">
                                        <h4><span>30+</span> Verified Suppliers of Consumer Electronics</h4>
                                        <p class="title"><span class="pink">$288.25</span>/<s>$368.00</s></p>
                                    </a> </div>
                                <div class="img_box"><a class="Collect" href=""><s></s>Collect</a><a href=""><img src="img/cp.jpg"></a></div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(function(){
		create_receipt('<?php echo $result['order_id'] ?>');
	})
	function create_receipt(order_id){
		if(order_id){
			$.ajax({
				type: "POST",
				url: "/ucenter/my_orders_new/order_pdf_receipt_file/"+order_id,
				dataType: "json",
				success: function (data) {

				}
			});
		}
	}
</script>