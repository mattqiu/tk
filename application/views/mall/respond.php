<div class="MyCart">
    <div class="w1200 m-h-430">
        <div class="cg_hui clear">
            <div class="chengg">
                <?php if($result['success']){?>
                <p class="tit"><s></s><?php 
                if(substr($result['order_id'], 0, 1 )=='S'){//订单号首字母为S，报名费订单
                    echo '  已付款，报名成功！';
                        }else{
                            echo lang('pay_success_');
                        }
                ?></p>
                <p class="zhif"><?php echo lang('pay_amount_'); ?><em><?php //echo $amount ;
                        if(substr($result['order_id'], 0, 1 )=='S'){//订单号首字母为S，报名费订单
                    echo '¥1,000.00';
                        }else{
                            echo $amount ;
                        }?></em></p>
                <p class="tishi"><?php if (substr($result['order_id'], 0, 1) == 'L') {//订单号首字母为S，直播费订单     ?>
                                    <div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'><?php echo lang('account_active_success_jump'); ?></div><div id='sec'></div>
                                <?php } ?></p>
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
<?php if (substr($result['order_id'], 0, 1) == 'L') {//订单号首字母为S，直播费订单  
            $rurl=get_cookie($result['order_id'].'url');
            $rdata['order_id']=$result['order_id'];
            $rdata['orderNo']=$result['phone'];
            $rdata['status']='success';
            $mkey='TPs1#)8!6';
            $rdata['token']=md5($rdata['orderNo'].$rdata['order_id'].$rdata['status'].$mkey);
            ?>
        <script type='text/javascript'>
            var tim = 4;
            function showTime() {
                tim -= 1;
                document.getElementById('sec').innerHTML = tim;
                if (tim == 0) {
                    location.href = '<?php echo $rurl.'?'.http_build_query($rdata);?>';
                }
                setTimeout('showTime()', 1000);
            }
            showTime();</script>
        <?php } ?>