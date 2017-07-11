<style>
</style>
<script type="text/javascript">
function formatSeconds(value) {
    var theTime  = parseInt(value);// 秒
    var theTime1 = 0;// 分
    var theTime2 = 0;// 小时
    if(theTime > 60) {
        theTime1 = parseInt(theTime/60);
        theTime  = parseInt(theTime%60);
        if(theTime1 > 60) {
            theTime2 = parseInt(theTime1/60);
            theTime1 = parseInt(theTime1%60);
        }
    }
    var result = ""+parseInt(theTime)+"S";
    if(theTime1 > 0) {
        result = ""+parseInt(theTime1)+"M:"+result;
    }
    if(theTime2 > 0) {
        result = ""+parseInt(theTime2)+"H:"+result;
    }
    return result;
}

function _setTime(order_id, timeout) {
    var _times  = setInterval(function() {
        timeout --;
        rest = formatSeconds(timeout);
        if (timeout <= 0 || rest == '0S') {
            clearInterval(_times);
            $('#timeout_'+order_id).html('').prevAll('a').remove();
        } else {
            $('#timeout_'+order_id).html('<?php echo lang('exchange_remaining_time').":";?>'+rest);
        }
        
    }, 1000);
}
</script>
<div class="container" style="border:0px;">
    	<div class="tb100">
        	<div class="my_orders">
            	<div class="top_info">
                	<div class="linfo"></div>
					<form class="order_search">
                    <div class="rinqu">
                    	<ul>
							<li style="width:90px;">
							<select name="month" class="orders_font orders_month">
								<option value=""><?php echo lang('filter_month')?></option>
								<option value="<?php echo $months[0]?>" <?php echo $searchData['month'] == $months[0] ? 'selected':'';?>><?php echo $months[0]?></option>
								<option value="<?php echo $months[1]?>" <?php echo $searchData['month'] == $months[1] ? 'selected':'';?>><?php echo $months[1]?></option>
								<option value="<?php echo $months[2]?>" <?php echo $searchData['month'] == $months[2] ? 'selected':'';?>><?php echo $months[2]?></option>
								<option value="<?php echo $months[3]?>" <?php echo $searchData['month'] == $months[3] ? 'selected':'';?>><?php echo $months[3]?></option>
							</select>
							</li>
                        	<li style="width:40%;margin-left:20px;"><input type="text" value="<?php echo $searchData['order_input']?>" name="order_input" style="color:#666;" class="orders_font"  placeholder="<?php echo lang('search_order');?>" /></li>
                            <li style="width:20%;"><input type="submit" style="color:#333;width:auto;margin-left:30px;float:left; min-width:50px;" class="orders_font" value="<?php echo lang('search');?>" /></li>
                        </ul>
                    </div>
                </div>
                <div class="nav">
                    <ul>

                        <li><?php echo lang('order_date')?></li>
                        <li><?php echo lang('order_pay_date');?></li>
                        <li><?php echo lang('order_sn');?></li>
                        <li><?php echo lang('order_amount')?></li>
                        <li>
	                        <?php echo lang('customer_name')?>
                        </li>
                        <li><select class="order_status" name="status" class="orders_font">
                            <option value="" <?php echo $searchData['status'] == '' ? 'selected':'';?>><?php echo lang('admin_order_status_all')?></option>
                            <option value="<?php echo Order_enum::STATUS_CHECKOUT; ?>" <?php echo $searchData['status'] == Order_enum::STATUS_CHECKOUT ? 'selected':'';?>><?php echo lang('admin_order_status_checkout')?></option>
                            <option value="<?php echo Order_enum::STATUS_SHIPPING; ?>" <?php echo $searchData['status'] == Order_enum::STATUS_SHIPPING ? 'selected':'';?>><?php echo lang('admin_order_status_paied')?></option>
							<option value="<?php echo Order_enum::STATUS_SHIPPED; ?>" <?php echo $searchData['status'] == Order_enum::STATUS_SHIPPED ? 'selected':'';?>><?php echo lang('admin_order_status_delivered')?></option>
							<option value="<?php echo Order_enum::STATUS_HOLDING; ?>" <?php echo $searchData['status'] == Order_enum::STATUS_HOLDING ? 'selected':'';?>><?php echo lang('admin_order_status_holding')?></option>
                        </select>
                        </li>
						<li></li>
                        <?php    if(isset($country_id)) { ?>
                        <li>商家客服</li>
                     <?php   } else {  ?>
                     <?php  }   ?>
                    </ul>
                </div>
                <div class="atten"><span style="color:#f00;">*</span> <?php echo lang('order_amount_no_tip')?></div>
				<?php if($lists){?>
					<?php foreach ($lists as $list){if(substr($list['order_id'], 0, 1 )!='S'){?>
               	<div class="prod_info">
                    <div class="detail">
                    	<ul class="<?php echo $list['order_prop'] == 2 ? 'c_order_sub':'';?>">
                            <li><?php echo $list['created_at']?></li>
                            <li>
<!--                                --><?php //echo $list['pay_time'] == null ? '' : $list['pay_time']?>
                                <?php if($list['pay_time'] == null || $list['pay_time'] == '0000-00-00 00:00:00'){
                                    echo '';
                                }else{
                                    echo $list['pay_time'];
                                }
                                ?>
                            </li>
							<li><?php echo $list['order_prop'] == 2 ? '<a rel="tooltip" href="##" data-original-title="'.lang('split_order_tip').'"><i class="icon-question-sign"></i></a>'.$list['order_id'] : $list['order_id']?></li>
							<li><?php echo $list['order_prop'] == 2 ? '':$list['goods_amount_usd'];?></li>
							<li><?php echo $list['order_prop'] == 2 ? '':$list['customer_name'];?></li>
                            <li><?php if($list['status'] == '90' && strstr($list['remark'],'#exchange')){
									echo lang('admin_order_status_holding_exchange');
								}else{
									echo $status_map[$list['status']];
								} ?></li>
                             <li style="">
								<?php if($list['status'] == 2  || ($list['status'] == 100 && !$list['txn_id'] && $list['order_profit_usd'] && !isset($list['is_cancel']) ) && $list['is_customer']){?>
									<a onclick="check_location('<?php echo $list['order_id']?>','<?php echo base_url('order/pay/'.$list['order_id'])?>')" href="javascript:;"><input type="button" value="<?php echo lang('go_pay')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                <?php }?>

						<?php if(date('Y-m-d H:i:s',time()) >= config_item('upgrade_exchange')){?>
                            <?php if($list['pay_time'] >= config_item('upgrade_not_3') && $list['created_at'] >= config_item('upgrade_not_4') && empty($list['display'])){?>
                                <!--                            取消换货按钮-->
                                <?php if($list['status'] == '90' && strstr($list['remark'],'#exchange')){?>
                                <a onclick="cancel_exchange('<?php echo $list['order_id']?>')" href="javascript:;"><input type="button" value="<?php echo lang('cancel_exchange')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                <?php }?>

                                <?php if($list['pay_time'] >= config_item('upgrade_not_3')){?>
                                    <!--                                去换货按钮-->
                                    <?php if($list['status'] == 3  && $list['order_type'] == 2){?>
                                    <a onclick="check_location_hh_1('<?php echo $list['order_id']?>','<?php echo base_url('choose_goods_for_upgrade_exchange')?>')" href="javascript:;"><input type="button" value="<?php echo lang('goto_exchange')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                        <span id="timeout"></span>
                                    <?php }?>
                                    <?php if(($list['status'] == 90 && $list['order_type'] == 2 && strstr($list['remark'],'#exchange'))){?>
                                    <a onclick="check_location_hh_1('<?php echo $list['order_id']?>','<?php echo base_url('choose_goods_for_upgrade_exchange')?>')" href="javascript:;"><input type="button" value="<?php echo lang('goto_exchange')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                        <span id="timeout_<?php echo $list['order_id'];?>"></span>
                                    <?php }?>
                                <?php }?>
                                <!--                                去换货按钮-->
                                <?php if(isset($list['timer']) && $list['timer']){?>
                                <script type="text/javascript">_setTime('<?php echo $list['order_id'];?>', '<?php echo $list['timer'];?>');</script>
                                <?php }?>
                            <?php }?>
						<?php }?>

								<?php if($list['status'] == Order_enum::STATUS_SHIPPED){?>
                                    <input attr_id="<?php echo $list['order_id']?>" class="confirm_deliver" type="button" value="<?php echo lang('confirm_deliver')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font">
                                <?php }?>
                                <?php if($list['status'] == Order_enum::STATUS_EVALUATION){?>
                                    <a href="<?php echo base_url('ucenter/my_orders_action/evaluate/'.$list['order_id'])?>"><input attr_id="<?php echo $list['order_id']?>" class="order_evaluate" type="button" value="<?php echo lang('evaluate')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                <?php }?>
                                <?php if(($list['status'] == 3) && isset($list['display']) && $list['display'] && empty($list['excannel'])){?>
                                    <span><?php echo lang('exchange_timeout');?></span>
                                <?php }?>
								<a onclick="check_location('<?php echo $list['order_id']?>','<?php echo base_url('ucenter/my_orders_action/order_info/'.$list['order_id'])?>')" href="javascript:;"><?php echo lang('view')?></a>
								<?php if($list['status'] == 2 || ($list['status'] == 100 && !$list['txn_id'] && $list['order_profit_usd'] && !isset($list['is_cancel']) )){?>
									/ <a attr_id="<?php echo $list['order_id']?>" href="javascript:;" class="confirm_cancel"><?php echo lang('cancel')?></a>
								<?php }?>
                            </li>
                            <?php  if($list['order_prop'] == 2 ) { ?>
                            <li></li>
                               <?php } else if(isset($list['supplier_qq']) && isset($country_id))  {  ?>
              <!-- 商家客服QQ -->              <li><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $list['supplier_qq'];   ?>&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $list['supplier_qq']; ?>:41" alt="客服" title="客服"/></a></li>
                            <?php  }  ?>
                        </ul>
						<?php if($list['order_prop'] == 2 && $list['sub_orders'])foreach($list['sub_orders'] as $sub_order){ ?>
							<ul>
								<li></li>
                                <li>
<!--                                    --><?php //echo $list['pay_time'] == null ? $sub_order['pay_time'] : $list['pay_time']?>
                                    <?php if($list['pay_time'] == null || $list['pay_time'] == '0000-00-00 00:00:00'){
                                        if($sub_order['pay_time'] == '0000-00-00 00:00:00'){
                                            echo '';
                                        }else{
                                            echo $sub_order['pay_time'];
                                        }
                                    }else{
                                        echo $list['pay_time'];
                                    }
                                    ?>
                                </li>
								<li><?php echo $sub_order['order_id']?></li>
								<li><?php echo $sub_order['goods_amount_usd'];?></li>
								<li><?php echo $sub_order['customer_name'];?></li>
								<!--<li><?php /*echo $sub_order['expect_deliver_date'];*/?></li>-->
<!--								<li>--><?php //echo $status_map[$sub_order['status']];?><!--</li>-->
                                <li><?php if($sub_order['status'] == '90' && strstr($sub_order['remark'],'#exchange')){
                                        echo lang('admin_order_status_holding_exchange');
                                    }else{
                                        echo $status_map[$sub_order['status']];
                                    } ?></li>
                              
								<li style="">
                                    <?php if(date('Y-m-d H:i:s',time()) >= config_item('upgrade_exchange')) {?>
                                        <?php if($sub_order['pay_time'] >= config_item('upgrade_not_3') && $sub_order['created_at'] >= config_item('upgrade_not_4') && empty($sub_order['display'])){?>
                                            <!--                                        取消换货按钮-->
                                            <?php if($sub_order['status'] == '90' && strstr($sub_order['remark'],'#exchange')){?>
                                            <a onclick="cancel_exchange('<?php echo $sub_order['order_id']?>')" href="javascript:;"><input type="button" value="<?php echo lang('cancel_exchange');?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                            <?php }?>

                                            <!--                                            去换货按钮-->
                                            <?php if($sub_order['status'] == 3  && $sub_order['order_type'] == 2){?>
                                            <a onclick="check_location_hh_1('<?php echo $sub_order['order_id']?>','<?php echo base_url('choose_goods_for_upgrade_exchange')?>')" href="javascript:;"><input type="button" value="<?php echo lang('goto_exchange')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                                <span id="timeout"></span>
                                            <?php }?>
                                            <?php if(($sub_order['status'] == 90 && $sub_order['order_type'] == 2 && strstr($sub_order['remark'],'#exchange'))){?>
                                            <a onclick="check_location_hh_1('<?php echo $sub_order['order_id']?>','<?php echo base_url('choose_goods_for_upgrade_exchange')?>')" href="javascript:;"><input type="button" value="<?php echo lang('goto_exchange')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
                                                <span id="timeout_<?php echo $sub_order['order_id'];?>"></span>
                                            <?php }?>
                                            <!--                                            去换货按钮-->
                                            <?php if(isset($sub_order['timer']) && $sub_order['timer']){?>
                                            <script type="text/javascript">_setTime('<?php echo $sub_order['order_id'];?>', '<?php echo $sub_order['timer'];?>');</script>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                    <?php if(($sub_order['status'] == 3) && isset($sub_order['display']) && $sub_order['display'] && empty($sub_order['excannel'])){?>
                                        <span><?php echo lang('exchange_timeout');?></span>
                                    <?php }?>
									<?php if($sub_order['status'] == 4 && $sub_order['is_customer']){?>
										<input attr_id="<?php echo $sub_order['order_id']?>" class="confirm_deliver" type="button" value="<?php echo lang('confirm_deliver')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font">
									<?php }?>
									<?php if($sub_order['status'] == 5 && $sub_order['is_customer']){?>
										<a href="<?php echo base_url('ucenter/my_orders_action/evaluate/'.$sub_order['order_id'])?>"><input attr_id="<?php echo $sub_order['order_id']?>" class="order_evaluate" type="button" value="<?php echo lang('evaluate')?>" style="color:#fff;background:#385a7c;height:25px;width:auto;min-width:50px;border:1px solid #ccc;" class="orders_font"></a>
									<?php }?>
									<a onclick="check_location('<?php echo $sub_order['order_id']?>','<?php echo base_url('ucenter/my_orders_action/order_info/'.$sub_order['order_id'])?>')" href="javascript:;"><?php echo lang('view')?></a>
								</li>
                                 <!-- 商家客服QQ -->     <?php  if(isset($sub_order['supplier_qq']) && isset($country_id))  {  ?>
                           <li><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $sub_order['supplier_qq'];   ?>&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $sub_order['supplier_qq']; ?>:41" alt="客服" title="客服"/></a></li>
                            <?php  }  ?>
							</ul>
						<?php }?>
                    </div>
                </div>
                                        <?php }}?>
				<?php }else{?>
					<div style="text-align: center;font-weight: bold;" class="text-success"><?php echo lang('no_item') ?></div>
				<?php }?>
            </div>
			<div class="page"><?php echo $pager;?></div>
        	<input type="hidden" value="<?php echo lang('sure_delivery')?>" class="sure_msg">
        	<input type="hidden" value="<?php echo lang('sure')?>" class="sure_msg2">
			<input type="hidden" value="<?php echo lang('ucenter_loc_sure')?>" class="ucenter_loc_sure">
        </div>
</div>
<?php  if(isset($country_id)) { ?>
	<style>
		.modal{width:250px;left:60%; }
        .tb100 .my_orders .prod_info .detail li{ width: 12%;}
        .tb100 .my_orders .nav li{ width: 12%;}
	</style>
<?php  }  else { ?>   
    <style>
		.modal{width:250px;left:60%; }
        .tb100 .my_orders .prod_info .detail li{ width: 13%;}
        .tb100 .my_orders .nav li{ width: 13%;}
	</style>
<?php  }  ?>
</form>
<?php if($queue_str){?>
<div class="modal hide fade queue_order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="board_news_title msg"><?php echo lang('april_title') ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo sprintf(lang('queue_order_content'),$queue_str); ?></p>
    </div>
</div>

<script>
    $(function(){
        $('.queue_order').modal();
    });


</script>
<style>
    .modal{
        width:450px;
        left:50%;
    }
</style>
<?php }?>
<script>
    /** 检测订单中心的操作 当前区域和收货区域是否一致 */
    function check_location_hh_1(order_id,next_href){
            $.ajax({
                type: "POST",
                url: "/ucenter/my_orders_action/check_location",
                dataType: "json",
                data:{order_id:order_id,h_id:1},
                success: function (res) {
                    if(res.success){
                        location.href = next_href;
                    }else{
                        $("#confirm_sponsor").modal();
                        $("#confirm_message").html($('.ucenter_loc_sure').val());
                        pre_location_cookie(res.other,next_href);
                    }
                }
            });
    }

    function check_location_hh_2(order_id,next_href){
            $.ajax({
                type: "POST",
                url: "/ucenter/my_orders_action/check_location",
                dataType: "json",
                data:{order_id:order_id,h_id:1},
                success: function (res) {
                    if(res.success){
                        location.href = next_href;
                    }else{
                        $("#confirm_sponsor").modal();
                        $("#confirm_message").html($('.ucenter_loc_sure').val());
                        pre_location_cookie(res.other,next_href);
                    }
                }
            });

    }

    function cancel_exchange(order_id){
        layer.confirm("<?php echo lang('cancel_exchange_confirm_msg');?>", {
            icon: 3,
            title: "<?php echo lang('cancel_exchange');?>",
            closeBtn: 2,
            btn: ['<?php echo lang('yes');?>', '<?php echo lang('no');?>']
        }, function() {
            $.ajax({
                type: "POST",
                url: "/ucenter/my_orders_action/exchange",
                dataType: "json",
                data:{order_id:order_id},
                success: function (res) {
                    if (res.success) {
                        window.location.reload();
                        layer.msg(res.msg);
                    }else{
                        layer.msg(res.msg);
                    }
                }
            });
        });
    }
</script>
