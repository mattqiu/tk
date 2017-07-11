<div class="container" style="border:0px;">
	<div class="row-fluid">
    	<!--<div class="tb2">-->

            <?php if($allCoupons){?>
            	<div class="coupons">

                    <div class="rule_link" style='text-align:left;'>
                        <b class="money"><?php echo $coupons_total_num_text?></b>,
                        <b class="money"><?php echo lang('value')." $".$coupon_total_amount?></b>
                        <a href="<?php echo base_url('choose_goods_for_coupons')?>" target="_blank" style="margin-left:10px;" class="alert alert-success"><?php echo lang('goto_use');?></a>
                    </div>

                	<div class="rule_link">
                        <a href="<?php echo base_url('ucenter/about_exchange_coupon')?>">
                        <?php echo lang('suitExchangeCouponRule')?>?
                        </a>
                    </div>
                    
					<div class="coup_main">
                    <?php foreach($allCoupons as $k=>$v){?>
                    <div class="coup_info">
                    	<div class="lcoup">
                            <div class="lcouptxt">
                            	<div class="limg">
                                	<div class="limg_bg"><span style="position:relative;top:10px;"><?php echo lang('exchangeCoupon')?></span><br /><span class="cou_money">$<?php echo substr($k,1)?></span></div>
                                </div>
                                <div class="rtxt">
                                	<div class="llimg"></div>
                                    <div class="rrtxt">
                                    	<p><?php echo lang('only_use_in_exchange')?></p><p><?php echo lang('num').': '.$v?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rcoup">
                        	<p></p>
                        	<p><?php echo lang('expiration').': '.lang('unlimited')?><br /><br /><br /></p>
                            <!-- <a href="/use_coupons" class="cn">去使用</a></p> -->
                        </div>
                    </div>
                    <?php }?>
                    </div>
                     
                    
                   <!--  <div class="coup_info">
                    	<div class="lcoup">
                        	<h4 class="cn">卷编号：8888888888</h4>
                            <div class="lcouptxt">
                            	<div class="limg" style="border:2px dashed #ebebeb;">
                                	<div class="limg_bg" style="background:#f1f1f1;color:#949494;"><span style="position:relative;top:10px;" class="cn">代 品 券</span><br /><span style="font-family:Arial, Helvetica, sans-serif;font-size:24px;position:relative;top:20px;">$25</span></div>
                                </div>
                                <div class="rtxt">
                                	<div class="llimg"></div>
                                    <div class="rrtxt">
                                    	<p class="cn">只限换购区选购商品<br />生效日期：2015-07-08</p><p>来源：TPS活动 </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rcoup">
                        	<p></p>
                        	<p style="margin-top:23px;" class="cn">订单编号：9642678125制<br />订单编号：9642678125</p>
                        </div>
                    </div> -->
                    
                </div>
            <?php }else{?>
                <?php echo lang('no_exchange_coupons');?>
            <?php }?>
        
        <!--</div>-->
    </div>
</div>

<!-- 代品券规则说明弹层 -->
<div id="suitCouponRuleModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  
    <div class="modal-header">  
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>  
        <h3><?php echo lang('suitExchangeCouponRule')?></h3>  
    </div>  
    <div class="modal-body">
        <p style="margin-left:0px;margin-bottom: 30px;;color:blue;font-weight:bold;font-size: 0.9em">
            <?php echo lang('suitExchangeCouponRuleContent')?>
        </p>
    </div>
</div>
<!-- /代品券规则说明弹层 -->