<form class="evaluate_form" action="?" method="post">
<?php if($order['goods_info'])foreach($order['goods_info'] as $key=>$goods_info){?>
<div class="container c_eval_h">
	<div class="title">
    	<div class="tb_tit">
        	<div class="ltxt"><?php echo lang('checkout_order_info')?></div>
            <div class="rtxt" style="position:relative;right:10px;"><?php echo lang('time')?></div>
        </div>
    </div>
    
	<div class="tb6">
    	<div class="evaluate">
        	<div class="lshop"><a href="<?php echo base_url('index/product?snm='.$goods_info['goods_sn_main'])?>" target="_blank"><img src="<?php echo base_url($goods_info['goods_img'])?>" /></a></div>
            <div class="mcont">
            	<div class="tlink"><p><a href="<?php echo base_url('index/product?snm='.$goods_info['goods_sn_main'])?>" target="_blank"><?php echo $goods_info['goods_name']?>(<?php echo $goods_info['goods_sn']?>)</a></p></div>
                <div class="beval">
                	<div class="score" style="display:block;">
                    	<div class="line_sc">
                            <div class="ltxt cn"><span style="color:#f16d22;">*</span> <?php echo lang('goods_star')?>:</div>
                            <div class="rstar">
                            	<div class="starts">
                                 <ul id="goods_star_<?php echo $key?>">
                                  <li class="on"  rel="0" onmouseover="starchange('goods_star_<?php echo $key?>',0);"></li>
                                  <li class="on" rel="1" onmouseover="starchange('goods_star_<?php echo $key?>',1);"></li>
                                  <li class="on" rel="2" onmouseover="starchange('goods_star_<?php echo $key?>',2);"></li>
                                  <li class="on"rel="3" onmouseover="starchange('goods_star_<?php echo $key?>',3);"></li>
                                  <li class="on" rel="4" onmouseover="starchange('goods_star_<?php echo $key?>',4);"></li>
									 <input type="hidden" name="goods_start[<?php echo $goods_info['goods_id']?>]" value="5" autocomplete="off">
                                 </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="line_sc cn" style="margin-top:10px;">
                            <div class="ltxt"><span style="color:#f16d22;">*</span> <?php echo lang('service_star')?>:</div>
                            <div class="rstar">
                            	<div class="starts">
                                 <ul id="service_star_<?php echo $key?>">
                                  <li class="on" rel="0" onmouseover="starchange('service_star_<?php echo $key?>',0);"></li>
                                  <li class="on" rel="1" onmouseover="starchange('service_star_<?php echo $key?>',1);"></li>
                                  <li class="on" rel="2" onmouseover="starchange('service_star_<?php echo $key?>',2);"></li>
                                  <li class="on" rel="3" onmouseover="starchange('service_star_<?php echo $key?>',3);"></li>
                                  <li class="on" rel="4" onmouseover="starchange('service_star_<?php echo $key?>',4);"></li>
									 <input type="hidden" name="goods_service[<?php echo $goods_info['goods_id']?>]" value="5" autocomplete="off">
                                 </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="score" style="display:none;">
                    	<div class="thank">感谢您的评分</div>
                    </div>
                                  
                </div>
            </div>
            <div class="rtime"><p><?php echo $order['order_detail']['created_at']?></p></div>
        </div>
    </div>
</div>
<input type="hidden" name="goods_sn_main[<?php echo $goods_info['goods_id']?>]" value="<?php echo $goods_info['goods_sn_main']?>" autocomplete="off">
<?php }?>
<div class="container" style="border:0px;">
    <div class="e_bott">
		<input type="hidden" name="order_id" class="span6" value="<?php echo $order['order_detail']['order_id'] ?>">
        <div class="btncon"><input autocomplete="off" type="button" name="ev_sub" value="<?php echo lang('submit')?>" class="ev_btn" onclick="do_evaluate(this)" />
		<label style="color: #666"><input autocomplete="off" type="checkbox" name="ev_checkbox" class="ev_che_css" /> <?php echo lang('anonymous_evaluation')?></label></div>
    </div>
</div>
</form>