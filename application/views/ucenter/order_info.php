<?php if(isset($curLoc_name)) {?>
    <div class="backdrop location"></div>
    <div class="g-jia location">
        <i class="close_location">×</i>
        <s></s>
        <span>
			<?php echo sprintf(lang('label_location_tps'),$curLoc_name);?>
			</span>
    </div>
<?php }?>
<form>
    <div class="container" style="border:0px;">
        <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">
        <div class="row-fluid">
            <?php if($order['order_detail']['order_prop'] != '2'){?>
                <div class="tb2">
                    <div class="orderinfo">
                        <div class="process_txt">
                            <ul>
                                <li><?php echo lang('checkout_order_submit')?></li>
                                <li><?php echo lang('goods_deliver')?></li>
                                <li><?php echo lang('goods_deliver_2')?></li>
                            </ul>
                        </div>
                        <div class="process_bg">
                            <div class="w5" <?php echo $order['order_detail']['status'] <= 3 ? 'style="display:block;"' : 'style="display:none;"'?>></div>
                            <div class="w45" <?php echo $order['order_detail']['status'] == 4 ? 'style="display:block;"' : 'style="display:none;"'?>>
                                <div class="lbg"></div>
                                <div class="mbg"></div>
                                <div class="rbg"></div>
                            </div>
                            <div class="w90" <?php echo $order['order_detail']['status'] == 5 ||  $order['order_detail']['status'] == 6? 'style="display:block;"' : 'style="display:none;"'?>>
                                <div class="lbg">
                                    <div class="llbg"></div>
                                    <div class="mmbg"></div>
                                    <div class="rrbg"></div>
                                </div>
                                <div class="rbg">
                                    <div class="mmbg"></div>
                                    <div class="rrbg"></div>
                                </div>
                            </div>
                        </div>
                        <div class="process_time">
                            <ul>
                                <li><?php echo $order['order_detail']['created_at'] ?></li>
                                <li><?php echo $order['order_detail']['status'] >= 4 ? $order['order_detail']['deliver_time'] : '';?></li>
                                <li><?php echo $order['order_detail']['status'] >= 5 ? $order['order_detail']['receive_time'] : '';?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php }?>
            <div class="o_info">
                <div class="linfo" id="linfo_id">
                    <div class="deta_info">
                        <div class="text">
                            <h4><?php echo lang('order_info')?>
                                <!-- <?php if( in_array($order['order_detail']['status'],array('2')) ){ ?>
							<a style="color: #f50;" href="javascript:;" class="pull-right edit_order"><?php echo lang('edit_address')?></a>
							<?php }?> -->
                            </h4>
                            <p><?php echo lang('pay_deliver_addr'); ?><?php echo $order['order_detail']['consignee']; ?> <?php echo $order['order_detail']['phone']; ?> <?php echo $order['order_detail']['country_address']; ?> <?php echo $order['order_detail']['address']; ?> <?php echo $order['order_detail']['zip_code']; ?>  <?php echo $order['order_detail']['customs_clearance']; ?></p>
                            <!-- 修改收货地址 start 只有订单状态为2 3 的时候显示 -->
                            <?php  if(in_array($order['order_detail']['status'],[2,3]) && in_array($curLocation_id,['156','344','410']) && $curLocation_id === $order['order_detail']['area']){  ?>
                                <div  style="float:right;width:150px;">
                                    <input type="button"  style="width:100px;height:30px;" id="edit_address"  value="<?php echo lang('update_order_address');?>">
                                    <span style="cursor:pointer;color:red;" onclick="edit_address_desc();"><img src = '/themes/admin/images/u4.png' width="18" height="18"></span>
                                </div>
                            <?php }?>
                            <!-- 修改收货地址 end -->
                        </div>
                        <div class="text">
                            <h4><?php echo lang('pay_deliver')?></h4>
                            <p><?php echo lang('payment')?>:<?php echo $order['order_detail']['payment_type'] ? lang('payment_'.$order['order_detail']['payment_type']) : ''; ?></php><br />
                                <?php if (preg_match('/HK TPS OFFICE/',$order['order_detail']['address'])){
                                    echo lang('checkout_pay_deliver_tps');
                                }else {
                                    echo lang('checkout_pay_deliver');
                                } ?><?php echo $this->m_currency->price_format($order['order_detail']['deliver_fee_usd']); ?><br /><?php echo lang('deliver_time')?>:<?php echo $order['order_detail']['status'] >= 4 ? $order['order_detail']['deliver_time'] : '';?></p>
                        </div>
                        <div class="text">
                            <h4><?php echo lang('receipt_info')?></h4>
                            <p>
                                <?php echo lang('receipt_type')?>:<?php echo lang('receipt_content')?>
                                <?php if(in_array($order['order_detail']['status'],array('1','3','4','5','6'))){?>
                                    （<a target="_blank" href="<?php echo base_url('ucenter/my_orders_new/order_pdf_receipt/'.$order['order_detail']['order_id'])?>"><?php echo lang('down_load')?></a>）
                                <?php }?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="rinfo" id="rinfo_id">
                    <div class="limg"></div>
                    <div class="rtxt">
                        <div class="ttxt">
                            <p><?php echo lang('pay_order_id') ?><?php echo $order['order_detail']['order_id']?>   <?php echo lang('order_status_content')?>:<?php echo $status_map[$order['order_detail']['status']]; ?></p>
                            <p><?php if(!empty($order['order_detail']['freight_info'])){ ?>
                                    <?php echo lang('deliver_way')?><?php echo lang('deliver_general')?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php echo lang('deliver_info')?>
                                    <?php
                                    if (isset($order['order_detail']['freight_url']))
                                    {
                                        echo "<a href=\"{$order['order_detail']['freight_url']}\" target=\"_blank\">{$order['order_detail']['freight_info']}</a>";
                                    }
                                    else
                                    {
                                        echo $order['order_detail']['freight_info'];
                                    }
                                    ?>
                                <?php }?>
                            </p>
                        </div>
                        <div class="btxt" id="detxt_id">
                            <?php if($is_queue_order){?>
                                <p><?php echo sprintf(lang('queue_order_content'),$order['order_detail']['order_id'])?></p>
                            <?php }?>
                            <?php if($remarks)foreach($remarks as $remark){?>
                                <p><span style="color:#0088cc;"><?php echo $remark['created_at']?> </span> <?php echo $remark['remark']?></p>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shop_list">
                <div class="ttop">
                    <ul>
                        <li style="width:50%;"><?php echo lang('cart_product_name')?></li>
                        <li style="width:25%;"><?php echo lang('goods_amount')?></li>
                        <li style="width:25%;"><?php echo lang('label_qty')?></li>
                    </ul>
                </div>
                <?php if($order['goods_info'])foreach($order['goods_info'] as $key=>$goods_info){?>
                    <div class="shop_info"  <?php echo $key != 0 ? 'style="border-top:1px solid #ccc;"' : ''?>>
                        <ul>
                            <li style="width:50%;">
                                <div class="text"><a href="<?php echo base_url('index/product?snm='.$goods_info['goods_sn_main'])?>" target="_blank"><img src="<?php echo $img_host.($goods_info['goods_img'])?>" /></a>
                                    <a href="<?php echo base_url('index/product?snm='.$goods_info['goods_sn_main']).'&sn='.$goods_info['goods_sn']?>" style="margin-left:10px;" target="_blank"><?php echo $goods_info['goods_name']?></a>
                                    <br>
                                    <?php if($goods_info['goods_attr']){?>
                                        <span style="margin-left:10px;"><?php echo $goods_info['goods_attr'] ?></span>
                                    <?php }?>
                                    <?php if(in_array($goods_info['goods_sn_main'],array('97138869','30900909','41265859'))){?>
                                        <br><span style="color:red;margin-left: 10px;"><?php echo sprintf(lang('label_shipping_note2'),$order['order_detail']['expect_deliver_date'])?></span>
                                    <?php }?>
                                </div> </li>
                            <li style="width:25%;margin-top:15px;"><?php echo $order['order_detail']['discount_type'] > 0 ? '$'.round($goods_info['shop_price']) : $this->m_currency->price_format($goods_info['shop_price']*100)?></li>
                            <li style="width:25%;margin-top:15px;"><?php echo $goods_info['quantity']?></li>
                        </ul>
                    </div>
                <?php }?>
                <div class="tbot">
                    <p><?php echo lang('checkout_pay_product')?><?php echo $this->m_currency->price_format($order['order_detail']['goods_amount_usd'])?><br />
                        <?php echo $order['order_detail']['discount_type'] == 1 ? '+' : '-' ?> <?php echo lang('dai_coupon')?><?php echo '$'.$order['order_detail']['discount_amount_usd'] / 100?><br />
                        + <?php if (preg_match('/HK TPS OFFICE/',$order['order_detail']['address'])){
                            echo lang('checkout_pay_deliver_tps');
                        }else {
                            echo lang('checkout_pay_deliver');
                        } ?><?php echo $this->m_currency->price_format($order['order_detail']['deliver_fee_usd']);?><br />
                        = <?php echo lang('pay_amount_order');?>:<?php echo $this->m_currency->price_format($order['order_detail']['order_amount_usd'])?></p>
                </div>

            </div>
        </div>
    </div>
</form>
<?php if( in_array($order['order_detail']['status'],array('2')) ){ ?>
    <script>
        $(function(){
            $('.edit_order').click(function(){
                $('#edit_order_Modal').modal();
            });
            $('.edit_order_submit').click(function(){

                if($.trim($('input[name="country_address"]').val()) == ''){
                    layer.msg('Country cannot be empty');
                    return;
                }if($.trim($('input[name="address"]').val()) == ''){
                    layer.msg('Address cannot be empty');
                    return;
                }
                <?php if($order['order_detail']['area'] == '410'){?>
                if($.trim($('input[name="customs_clearance"]').val()) == ''){
                    layer.msg('Customs Clearance cannot be empty');
                    return;
                }

                if($.trim($('input[name="zip_code"]').val()) == ''){
                    layer.msg('Zip Code cannot be empty');
                    return;
                }
                <?php }?>
                var curEle = $(this);
                curEle.attr("disabled", "disabled");
                $.ajax({
                    type: "POST",
                    url: "/ucenter/my_other_orders/edit_korea_order",
                    data:$('.edit_order_form').serialize(),
                    dataType: "json",
                    success: function (res) {
                        curEle.attr("disabled", false);
                        if (res.success) {
                            location.reload();
                        }else{
                            layer.msg(res.msg);
                        }
                    }
                });

            });
        })
    </script>


<?php }?>
<!-- 编辑收货地址弹出层  start-->
<link rel="stylesheet" href="<?php echo base_url('themes/mall/css/head_food_1.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('themes/mall/css/base.css?v=1.0'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/store_register.css?v=2'); ?>" />
<script src="<?php echo base_url('themes/mall/js/main.js?v=20170614'); ?>"></script>
<script src="<?php echo base_url('themes/mall/js/base.js?v=2'); ?>"></script>
<script src="<?php echo base_url('file/js/user_address_linkage.js?v=3'); ?>"></script>
<style>
    body{font-size:14px}
</style>
<!--地址修改说明开始-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_address_rule1');?></h4>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><?php echo lang('edit_address_rule_content1');?></li>

                    <li class="list-group-item"><?php echo lang('edit_address_rule_content2');?></li>
                </ul>
            </div>
            <div class="modal-footer">
                <!--				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>-->
                <button id="close_btn" type="button" class="btn btn-primary submit" aria-hidden="true">确定</button>
            </div>
        </div>
    </div>
</div>

<!--地址修改说明结束-->
<div id="BOX_nav" class="xm-edit-addr wodl BOX_nav clear" style="width: 790px;height:750px;display: none;">

    <span class="close" onclick="closeBg();">×</span>
    <h3 id="box_title"></h3>
    <div class="item">
        <!--		<em id="box_title_em"></em>-->
        <div class="item-title">(<?php echo lang('edit_address_rule'); ?>! <span style="cursor:pointer;color:red;" onclick="edit_address_desc();"><img src="/themes/admin/images/u4.png" width="18" height="18"></span>)</div>
        <input type="hidden" id="box_type" />
        <input type="hidden" id="box_id" />
    </div>

    <!--收货地址-->
    <!--
    <div class="item clear">
        <dl style="clear:left;">
            <dt style="float:left;"><?php echo lang('checkout_deliver_address'); ?><span>*</span></dt>
            <dd id="box_addr" style="float:left;">
                <select class="select" id="box_country" onchange="cb_box_country();">
                    <option value="0"><?php echo lang('checkout_addr_country'); ?></option>

                </select>
            </dd>
        </dl>
    </div>
    -->
    <style type="text/css">

        dt {
            float: left;
        }
    </style>
    <div style="clear: both;width: 100%;height: 60px;">
    <dl>
        <dt style="width: 100px;display: block;"><?php echo lang('checkout_deliver_address'); ?><span>*</span></dt>
        <dd id="box_addr" style="position: relative;left: 30px;">
            <select class="select" id="box_country" onchange="cb_box_country();">
                <option value="0"><?php echo lang('checkout_addr_country'); ?></option>

            </select>
        </dd>
        <!--详细收货地址-->
        <dt style="width: 100px;display: block;margin-top: 30px;"><span>&nbsp;</span></dt>
        <dd style="position: relative;left: 12px;"><textarea type="text" class="xxidz" id="box_addr_detail" onblur="detail_address_end();" placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>"></textarea></dd>
    </dl>
    </div>
    <!--收货人-->
    <div style="clear: both;position: relative;top: 20px;width: 100%;height: 60px;" id="div_consignee">
    <dl>
        <dt style="width: 100px;display: block;"><?php echo lang('checkout_consignee'); ?><span>*</span></dt>
        <dd style="position: relative;left: 30px;"><input type="text" class="input" id="box_consignee" maxlength="50" placeholder="<?php echo lang('checkout_consignee'); ?>"></dd>
    </dl>
    </div>

    <!--First Name-->
    <div style="clear: both;display: none;position: relative;top: 20px;width: 100%;height: 60px;" id="div_first_name">
        <dl>
            <dt style="width: 100px;display: block;"><?php echo lang('first_name'); ?><span>*</span></dt>
            <dd style="position: relative;left: 30px;"><input type="text"  maxlength="25" id="box_first_name" class="input" placeholder="<?php echo lang('first_name');?>"></dd>
        </dl>
    </div>

    <!--Last Name-->
    <div style="clear: both;display: none;position: relative;top: 20px;width: 100%;height: 60px;" id="div_last_name">
        <dl>
            <dt style="width: 100px;display: block;"><?php echo lang('last_name'); ?><span>*</span></dt>
            <dd style="position: relative;left: 30px;"><input type="text"  maxlength="25" id="box_last_name" class="input" placeholder="<?php echo lang('last_name');?>"></dd>
        </dl>
    </div>

    <!--联系电话-->
    <div style="clear: both;position: relative;top: 20px;width: 100%;height: 60px;" id="div_phone">
        <dl>
            <dt style="width: 100px;display: block;"><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd style="position: relative;left: 30px;"><input type="text"   maxlength="50" class="input" id="box_phone" placeholder="<?php echo lang('checkout_phone'); ?>"></dd>
        </dl>
    </div>

    <!--韩国联系电话-->
    <div style="clear: both; display: none;position: relative;top: 20px;width: 100%;height: 60px;" id="div_phone_kr">
        <dl>
            <dt style="width: 100px;display: block;"><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd style="position: relative;left: 30px;">
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T4_onkeyup()"  onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 30px;" autocomplete = "off" class="input"  id="box_phone_kr_1"   maxlength="3" size="3"  >
                <span style="position: absolute;top:5px;left:55px;font-size:20px;color:#333">-</span>
                <input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'') ;return T5_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"  style="width: 50px;" autocomplete = "off" class="input"  id="box_phone_kr_2"  maxlength="4" size="4"  >
                <span style="position: absolute;top:5px;left:137px;font-size:20px;color:#333">-</span>
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T6_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 50px;" autocomplete = "off" class="input"  id="box_phone_kr_3" maxlength="4" size="4"  >

            </dd>
        </dl>
    </div>

    <!--备用电话-->
    <div style="clear: both;position: relative;top: 20px;width: 100%;height: 60px;" id="div_reserve_num">
        <dl>
            <dt style="width: 100px;display: block;"><?php echo lang('checkout_reserve_num'); ?><span></span></dt>
            <dd style="position: relative;left: 30px;"><input type="text" class="input" id="box_reserve_num" maxlength="20"  placeholder="<?php echo lang('check_addr_rule_reserve_num');?>"></dd>
        </dl>
    </div>

    <!--邮编-->
    <div style="clear: both;position: relative;top: 20px;width: 100%;height: 60px;" id="div_zip_code">
        <dl>
            <dt style="width: 100px;display: block;"><?php echo lang('checkout_zip_code'); ?><span></span></dt>
            <dd style="position: relative;left: 30px;"><input type="text" maxlength="10" class="input" id="box_zip_code" placeholder="<?php echo lang('check_addr_rule_zip_code');?>" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" ></dd>
        </dl>
    </div>

    <!--短信验证码-->
    <div style="clear: both;position: relative;top: 20px;width: 100%;height: 60px;" id="div_mobile_code">
        <dl>
            <dt style="width: 100px;display: block;"><?php echo lang('phone_code')."："; ?><span></span></dt>
            <dd style="position: relative;left: 30px;"><input type="text" maxlength="6" class="input" id="mobile_code" placeholder="" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" style="width:90px;" >&nbsp;<input type="button" style="height:29px;margin-top:5px;position: relative;top: -7px;" id = "get_mobile_code" value="<?php echo lang('get_captcha')?>" />&nbsp;&nbsp;<?php echo $bind_mobile_message;?></dd>
        </dl>
    </div>
    <input type="hidden" value="<?php echo lang('resend_captcha')?>" id="resend_captcha">
    <input type="hidden" value="<?php echo lang('get_captcha')?>" id="get_captcha">

    <!--保存信息-->
    <div style="clear: both;position: relative;top: 20px;width: 100%;height: 60px;" id="div_zip_code">
        <dl>
            <dt style="width: 100px;display: block;"><span></span></dt>
            <dd style="position: relative;left: 30px;">
                <button id ="checkout_save_addr" style="width:110px;height:35px;margin-left:90px;" type="button" onclick="submit_box_save_addr(this);">
                    <?php echo lang('checkout_save_addr'); ?>
                </button>
            </dd>
        </dl>
    </div>
    <!--
    <dl>
        <dt><span>&nbsp;</span></dt>
        <dd style="position: relative;left: 70px;"><textarea type="text" class="xxidz" id="box_addr_detail" onblur="detail_address_end();" placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>"></textarea></dd>
    </dl>-->
    <!--
    <dl>
        <dt>标签：</dt>
        <dd>鲜花</dd>
    </dl>
    -->
    <!--详细收货地址-->
    <!--<div class="item clear">
        <dl>
            <dt><span>&nbsp;</span></dt>
            <dd><textarea type="text" class="xxidz" id="box_addr_detail" onblur="detail_address_end();" placeholder="<?php echo lang('checkout_addr_detail_placeholder'); ?>"></textarea></dd>
        </dl>
    </div>-->

    <!--收货人-->
    <!--<div class="item clear" id="div_consignee">
        <dl>
            <dt><?php echo lang('checkout_consignee'); ?><span>*</span></dt>
            <dd><input type="text" class="input" id="box_consignee" maxlength="50" placeholder="<?php echo lang('checkout_consignee'); ?>"></dd>
        </dl>
    </div>-->

    <!--First Name-->
   <!-- <div class="item clear d-n" id="div_first_name" style="display:none;">
        <dl>
            <dt><?php echo lang('first_name'); ?><span>*</span></dt>
            <dd><input type="text"  maxlength="25" id="box_first_name" class="input" placeholder="<?php echo lang('first_name');?>"></dd>
        </dl>
    </div>-->
    <!--Last Name-->
    <!--<div class="item clear d-n" id="div_last_name" style="display:none;">
        <dl>
            <dt><?php echo lang('last_name'); ?><span>*</span></dt>
            <dd><input type="text"  maxlength="25" id="box_last_name" class="input" placeholder="<?php echo lang('last_name');?>"></dd>
        </dl>
    </div>-->

    <!--联系电话-->
    <!--<div class="item clear" id="div_phone">
        <dl>
            <dt><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd><input type="text"   maxlength="50" class="input" id="box_phone" placeholder="<?php echo lang('checkout_phone'); ?>"></dd>
        </dl>
    </div>-->

    <!--    韩国联系电话-->
    <!--<div class="item clear" id="div_phone_kr" style="display:none;">
        <dl>
            <dt><?php echo lang('checkout_phone'); ?><span>*</span></dt>
            <dd style="position: relative">
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T4_onkeyup()"  onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 30px;" autocomplete = "off" class="input"  id="box_phone_kr_1"   maxlength="3" size="3"  >
                <span style="position: absolute;top:5px;left:55px;font-size:20px;color:#333">-</span>
                <input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'') ;return T5_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"  style="width: 50px;" autocomplete = "off" class="input"  id="box_phone_kr_2"  maxlength="4" size="4"  >
                <span style="position: absolute;top:5px;left:137px;font-size:20px;color:#333">-</span>
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'');return T6_onkeyup()"   onafterpaste="this.value=this.value.replace(/\D/g,'')"   style="width: 50px;" autocomplete = "off" class="input"  id="box_phone_kr_3" maxlength="4" size="4"  >
            </dd>

        </dl>
    </div>-->

    <!--备用电话-->
    <!--<div class="item clear" id = "div_reserve_num">
        <dl>
            <dt><?php echo lang('checkout_reserve_num'); ?><span></span></dt>
            <dd><input type="text" class="input" id="box_reserve_num" maxlength="20"  placeholder="<?php echo lang('check_addr_rule_reserve_num');?>"></dd>
        </dl>
    </div>-->

    <!--邮编-->
    <!--<div class="item clear" id="div_zip_code">
        <dl>
            <dt><?php echo lang('checkout_zip_code'); ?><span></span></dt>
            <dd><input type="text" maxlength="10" class="input" id="box_zip_code" placeholder="<?php echo lang('check_addr_rule_zip_code');?>" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" ></dd>
        </dl>
    </div>-->

    <!--短信验证码-->

    <!--<div class="item clear" id="div_mobile_code">
        <dl>
            <dt><?php echo "手机验证码："; ?><span></span></dt>
            <dd><input type="text" maxlength="6" class="input" id="mobile_code" placeholder="" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" style="width:90px;" >&nbsp;<input type="button" style="height:29px;margin-top:5px;" id = "get_mobile_code" value="<?php echo lang('get_captcha')?>" />&nbsp;&nbsp;<?php echo $bind_mobile_message;?></dd>

        </dl>
    </div>
    <input type="hidden" value="<?php echo lang('resend_captcha')?>" id="resend_captcha">
    <input type="hidden" value="<?php echo lang('get_captcha')?>" id="get_captcha">-->
    <!--海关号-->
<!--    <div class="item clear d-n" id="div_customs_clearance">-->
<!--        <dl>-->
<!--            <dt>--><?php //echo lang('checkout_customs_clearance'); ?><!--<span>*</span></dt>-->
<!--            <dd><input type="text" maxlength = '32' class="input"  id="box_customs_clearance" maxlength="100" autocomplete="off" placeholder="--><?php //echo lang('checkout_customs_clearance'); ?><!--"></dd>-->
<!--        </dl>-->
<!--    </div>-->


    <!--<div class="item clear">
        <dl>
            <dt><span></span></dt>
            <dd>
                <button id ="checkout_save_addr" style="width:110px;height:35px;margin-left:16px;" type="button" onclick="submit_box_save_addr(this);">
                    <?php echo lang('checkout_save_addr'); ?>
                </button>
            </dd>
        </dl>
    </div>-->
</div>
<div id="fullbg" class="xm-backdrop" style="height: 1507px; width: 100%; display: none;"></div>
<!-- 编辑收货地址弹出层 end -->
<script type="text/javascript">
    linkage['000'] = {name: '<?php echo lang('con_others'); ?>', leaf: []};

    var country_id = "<?php echo $curLocation_id?>";
    var order_area = <?php echo $order['order_detail']['area'] ?>;
    var user_country_id = "<?php echo $user['country_id'];?>";
    if(country_id == 156){
        delete(linkage[country_id].leaf[81]);
        delete(linkage[country_id].leaf[82]);
        delete(linkage[country_id].leaf[71]);
        if (user_country_id == 1) {
            $('#div_mobile_code').css('display', 'block');
        } else {
            $('#div_mobile_code').css('display', 'none');
        }

    }
    else if(country_id == 344){
        country_id = 156;
        for ( var i= 11;i<=71;i++){
            delete(linkage[country_id].leaf[i]);
        }
        delete(linkage[country_id].leaf[82]);
        $('#div_mobile_code').css('display', 'none');
    } else {
        $('#div_mobile_code').css('display', 'none');
    }

    // box append 国家列表
    $('#box_country').css('color', "#999");
    for (var country_code in linkage) {
        // if(in_array(country_code,[156,840,410,'000',344,446,158,'001'])) {
        if(in_array(country_code,[country_id])) {
            $('#box_country').append(
                $('<option/>').val(country_code).text(linkage[country_code].name)
            );
        }
    }



    var lang_map = {
        "156": {
            "lv2": "<?php echo lang('checkout_addr_lv2_cn'); ?>",
            "lv3": "<?php echo lang('checkout_addr_lv3_cn'); ?>",
            "lv4": "<?php echo lang('checkout_addr_lv4_cn'); ?>"
        },
        "410": {
            "lv2": "<?php echo lang('checkout_addr_lv2_kr'); ?>",
            "lv3": "",
            "lv4": ""
        },
        "840": {
            "lv2": "<?php echo lang('checkout_addr_lv2_us'); ?>",
            "lv3": "",
            "lv4": ""
        }
    };

    var err_lang_map = {
        'country': "<?php echo lang('checkout_validator_country'); ?>",
        'addr_lv2': {
            "156": "<?php echo lang('checkout_validator_addr_lv2_cn'); ?>",
            "410": "<?php echo lang('checkout_validator_addr_lv2_kr'); ?>",
            "840": "<?php echo lang('checkout_validator_addr_lv2_us'); ?>"
        },
        'addr_lv3': {
            "156": "<?php echo lang('checkout_validator_addr_lv3_cn'); ?>"
        },
        'addr_lv4': {
            "156": "<?php echo lang('checkout_validator_addr_lv4_cn'); ?>"
        },
        'address_detail': "<?php echo lang('checkout_validator_address_detail'); ?>",
        'consignee': "<?php echo lang('checkout_validator_consignee'); ?>",
        'phone': "<?php echo lang('checkout_validator_phone'); ?>",
        'reserve_num': "<?php echo lang('checkout_validator_reserve_num'); ?>",
        'zip_code': "<?php echo lang('checkout_validator_zip_code'); ?>",
        'customs_clearance': "<?php echo lang('checkout_validator_customs_clearance'); ?>"
    };


    //地址修改弹出
    $("#edit_address").click(function(){

        $('#box_title').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
        $('#box_title_em').text("<?php echo lang('checkout_addr_box_title_edit'); ?>");
        $('#box_type').val(1);

        // 清数据
        $('#box_consignee').val("");
        $('#box_phone').val("");
        $('#box_reserve_num').val("");
        $('#box_first_name').val("");
        $('#box_last_name').val("");
        $('#box_zip_code').val("");
        $('#div_zip_code').find('dt').find('span').text('');

        $('#box_addr_detail').val("");
        $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
        $('#box_country').nextAll().remove();
        $('#box_country').css('color', "#999").children('[value=0]').prop('selected', true);
        //$('#div_customs_clearance').css('display', 'none');
        $("#fullbg").css('display','block');
        $("#BOX_nav").css('display','block');

    })

    /******************** 收货地址 box start ********************/
    function refresh_box_addr_by_lv(id, lang, data)
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
    function cb_box_country()
    {
        var country_code = $('#box_country').val();

        $('#box_country').nextAll().remove();

        // 韩国特殊处理
        if (country_code === '410') {
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder_kr'); ?>');
           // $('#div_customs_clearance').css('display', 'block');
            $('#div_reserve_num').css('display', 'none');
            $('#div_phone_kr').css('display', 'block');
            $('#div_phone').css('display', 'none');

            $('#div_zip_code').find('dt').find('span').text('*');


        } else {
            $('#div_phone_kr').css('display', 'none');
            $('#div_phone').css('display', 'block');
            $('#box_addr_detail').attr('placeholder', '<?php echo lang('checkout_addr_detail_placeholder'); ?>');
            //$('#div_customs_clearance').css('display', 'none');
        }

        //美国地址特殊处理

        if(country_code === '840'){
            $('#div_first_name').css('display', 'block');
            $('#div_last_name').css('display', 'block');
            $('#div_consignee').css('display', 'none');

            $('#div_zip_code').find('dt').find('span').text('*');
        }else{
            $('#box_city').css('display', 'none');
            $('#div_consignee').css('display', 'block');
            $('#div_first_name').css('display', 'none');
            $('#div_last_name').css('display', 'none');

            if (country_code !== '410') {
                $('#div_zip_code').find('dt').find('span').text('');
            }
        }

        if (country_code === '0') {
            $('#box_country').css('color', "#999");

            return true;
        }
        $('#box_country').css('color', "#333");

        // 若 leaf 不为空，则可以走到 for 里面生成下一级
        for (var i in linkage[country_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv2").on('change', cb_box_addr_lv2)
            );
            refresh_box_addr_by_lv("box_addr_lv2", lang_map[country_code].lv2, linkage[country_code].leaf);
            break;
        }

        return true;
    }


    function cb_box_addr_lv2()
    {
        var country_code = $('#box_country').val();
        var lv2_code = $('#box_addr_lv2').val();

        $('#box_addr_lv2').nextAll().remove();

        //生成City Text
        if(country_code == 840){
            $('#box_addr').append(
                $('<input type="text" id="box_city" maxlength="25"/>').addClass("input").attr('placeholder', '<?php echo lang('city'); ?>')
            );
        }

        if (lv2_code == 0) {
            $('#box_addr_lv2').css('color', "#999");
            return true;
        }
        $('#box_addr_lv2').css('color', "#333");

        for (var i in linkage[country_code].leaf[lv2_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv3").on('change', cb_box_addr_lv3)
            );
            refresh_box_addr_by_lv("box_addr_lv3", lang_map[country_code].lv3, linkage[country_code].leaf[lv2_code].leaf);
            break;
        }
        return true;
    }

    function cb_box_addr_lv3()
    {
        var country_code = $('#box_country').val();
        var lv2_code = $('#box_addr_lv2').val();
        var lv3_code = $('#box_addr_lv3').val();
        if (lv3_code == '8104') {
            $("#box_addr_detail").val('4/F, Center, No.29-39 Ashley Road, Tsim Sha Tsui,  Kowloon 尖沙咀亞士厘道29-39號九龍中心4樓');
            $("#box_addr_detail").attr('readonly','readonly');
            $("#box_addr_detail").attr('disable','disable');
        } else {
            $("#box_addr_detail").val('');
            $("#box_addr_detail").removeAttr('readonly');
            $("#box_addr_detail").removeAttr('disable');
        }
        $('#box_addr_lv3').nextAll().remove();

        if (lv3_code == 0) {
            $('#box_addr_lv3').css('color', "#999");
            return true;
        }
        $('#box_addr_lv3').css('color', "#333");

        for (var i in linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf) {
            $('#box_addr').append(
                $('<select/>').addClass("select").css('color', "#999").attr('id', "box_addr_lv4").on('change', cb_box_addr_lv4)
            );
            refresh_box_addr_by_lv("box_addr_lv4", lang_map[country_code].lv4, linkage[country_code].leaf[lv2_code].leaf[lv3_code].leaf);
            break;
        }
        return true;
    }

    function cb_box_addr_lv4()
    {
        var lv4_code = $('#box_addr_lv4').val();

        if (lv4_code == 0) {
            $('#box_addr_lv4').css('color', "#999");
            return true;
        }
        $('#box_addr_lv4').css('color', "#333");
        return true;
    }
    var save_addr_flag = true;
    function submit_box_save_addr(obj) {

        var data = {};

        //订单号
        var order_id = "<?php echo $order['order_detail']['order_id']?> ";
        data.order_id = order_id;
        //国家ID
        var country = $('#box_country').children(':selected').val();
        if (country === '0') {
            layer.msg(err_lang_map.country);
            return false;
        }


        data.country = country;

        //二级地址
        if ($('#box_addr_lv2').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv2[country]);
            return false;
        }
        data.addr_lv2 = $('#box_addr_lv2').children(':selected').val();

        //三级地址
        if ($('#box_addr_lv3').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv3[country]);
            return false;
        }
        data.addr_lv3 = $('#box_addr_lv3').children(':selected').val();

        data.city = $('#box_city').val();

        //四级地址
        if ($('#box_addr_lv4').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv4[country]);
            return false;
        }
        data.addr_lv4 = $('#box_addr_lv4').children(':selected').val();

        //五级地址
        if ($('#box_addr_lv5').children(':selected').val() == 0) {
            console.log("box_addr_lv5 null");
            return false;
        }
        data.addr_lv5 = $('#box_addr_lv5').children(':selected').val();

        //地址详情
        data.address_detail = $('#box_addr_detail').val();

        //联系人
        data.consignee = $('#box_consignee').val();

        //姓
        data.first_name = $('#box_first_name').val();

        //名
        data.last_name = $('#box_last_name').val();



        //备用电话
        data.reserve_num = $('#box_reserve_num').val();

        //邮编
        data.zip_code = $('#box_zip_code').val();

        //海关号
        //data.customs_clearance = $('#box_customs_clearance').val();

        //数据验证
        //详细地址不能为空
        var user_country_id = "<?php echo $user['country_id'];?>";
        if (data.country == '156' && user_country_id == 1 && data.addr_lv2 != 81) {

            var bind_mobile = "<?php echo $bind_mobile;?>";
            var bind_mobile_message_1 = "<?php echo $bind_mobile_message_1;?>";
            if (bind_mobile != 1) {
                layer.msg(bind_mobile_message_1);
                return ;
            }


        }



        if (data.address_detail == '') {
            layer.msg("<?php echo lang('checkout_validator_address_detail');?>")
            return;
        }
        //收货人不能为空
        if (data.consignee == '') {
            layer.msg("<?php echo lang('checkout_validator_consignee');?>")
            return;
        }

        //手机号不能为空
        if (data.phone == '') {
            layer.msg("<?php echo lang('phone_not_null');?>")
            return;
        }

        if(data.country == '410'){
            if ($('#box_phone_kr_1').val().length > 3 || $('#box_phone_kr_1').val().length <=0 || $('#box_phone_kr_2').val().length != 4 || $('#box_phone_kr_3').val().length != 4)
            {
                layer.msg("<?php echo lang('check_addr_rule_phone');?>");
                return ;
            }
            data.phone = $('#box_phone_kr_1').val()+"-"+$('#box_phone_kr_2').val()+"-"+$('#box_phone_kr_3').val();
        } else {
            //电话
            data.phone = $('#box_phone').val();
        }

        //手机号码8-13位
        if (data.country != '410') {
            var re = /^\d{8,13}$/;
            if (!re.test(data.phone)) {
                layer.msg("<?php echo lang('phone_check_length');?>")
                return;
            }
        }

        if (data.country == '156') {
            var mobile_code = $("#mobile_code").val();

//            if(mobile_code.length == 0) {
//                layer.msg("<?php //echo lang('phone_code_not_null');?>//");
//                return ;
//            }
//            if (mobile_code.length <4 || mobile_code.length > 6) {
//                layer.msg("<?php //echo lang('phone_code_rule_error');?>//");
//                return ;
//            }
            data.mobile_code = mobile_code;
        }



        var url = "";
        var type = $('#box_type').val();
        if (type == 1) {

            data.uid = <?php echo isset($userInfo['id']) ? $userInfo['id'] : 0; ?>;

            data.is_default = 0;

            url = "/order/do_save_user_addr_order";

        } else {
            return false;
        }
        var old_freight = <?php echo $order['order_detail']['deliver_fee']/100;?>;

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

                var not_deliever_district = <?php echo json_encode(config_item("not_deliever_district"));?>;


                var not_deliever_goods = <?php echo json_encode(config_item("not_deliever_goods"));?>;

                if (-1 !== not_deliever_district.indexOf(data.addr_lv2)) {
                    //区域 遍历查询是否有商品不配送
                    var not_deliver = [];
                    var goods_sn_list = <?php echo json_encode($order['goods_info']);?>;
                    for (var j = 0; j < goods_sn_list.length; j++) {
                        if (not_deliever_goods.indexOf(goods_sn_list[j]['goods_sn']) !== -1) {

                            not_deliver.push(goods_sn_list[j]['goods_sn']);
                        }

                    }
                    if (not_deliver.length > 0) {
                        var not_deliver_str = not_deliver.join(',');
                        var area_cannot_reach_1 = '<?php echo lang('area_cannot_reach_1')?>';
                        var area_cannot_reach_2 = '<?php echo lang('area_cannot_reach_2')?>';
                        var attention = area_cannot_reach_1 + not_deliver_str + ")"
                        layer.msg(attention);
                        return false;
                    }
                }
            }

        }
        //限制end
        save_addr_flag = true;
        if (save_addr_flag == true) {
            save_addr_flag = false;
            data.old_freight = old_freight;
            var curEle = $("#checkout_save_addr");
            var oldSubVal = curEle.text();
            curEle.html($('#loadingTxt').val());
            curEle.attr("disabled", "disabled");
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: "json",
                success: function(data) {
                    save_addr_flag = true;
                    curEle.html(oldSubVal);
                    curEle.attr("disabled", false);

                    if (data.code == 200)
                    {
                        layer.msg(data.msg);
                        window.location.reload();
                    }
                    else if(data.code == 204)
                    {
                        layer.msg(data.msg);
                    }
                    else if(data.error == true) {
                        layer.msg(data.message);
                    }
                    else {
                        if(data.msg.indexOf('country') != -1){
                            layer.msg(err_lang_map.country);
                        }else if(data.msg.indexOf('addr_lv2') != -1){
                            layer.msg(err_lang_map.addr_lv2);
                        }else if(data.msg.indexOf('address_detail') != -1){
                            layer.msg(err_lang_map.address_detail);
                        }else if(data.msg.indexOf('consignee') != -1){
                            layer.msg(err_lang_map.consignee);
                        } else if(data.msg.indexOf('zip_code') != -1){
                            layer.msg(err_lang_map.zip_code);
                        }else {
                            layer.msg(data.msg);
                        }

                    }
                },
                error: function(data) {
                    console.log(data.responseText);
                    //curEle.html(oldSubVal);
                    //curEle.attr("disabled", false);
                }

            });
        }
    }

    //详细地址失去焦点
    function detail_address_end() {


        var data = {};
        //订单号
        var order_id = "<?php echo $order['order_detail']['order_id']?> ";
        data.order_id = order_id;
        //国家ID
        var country = $('#box_country').children(':selected').val();
        if (country === '0') {
            layer.msg(err_lang_map.country);
            return false;
        }
        data.country = country;

        //二级地址
        if ($('#box_addr_lv2').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv2[country]);
            return false;
        }
        data.addr_lv2 = $('#box_addr_lv2').children(':selected').val();

        //三级地址
        if ($('#box_addr_lv3').children(':selected').val() == 0) {
            layer.msg(err_lang_map.addr_lv3[country]);
            return false;
        }
        data.addr_lv3 = $('#box_addr_lv3').children(':selected').val();

        data.city = $('#box_city').val();

        //四级地址
        if ($('#box_addr_lv4').children(':selected').val() == 0) {

            return false;
        }
        data.addr_lv4 = $('#box_addr_lv4').children(':selected').val();

        //五级地址
        if ($('#box_addr_lv5').children(':selected').val() == 0) {
            console.log("box_addr_lv5 null");
            return false;
        }
        data.addr_lv5 = $('#box_addr_lv5').children(':selected').val();

        //北方六省不发货也要限制

        //冬季时间不发货的省份：
        //黑龙江、辽宁、吉林、新疆、西藏、内蒙古共6个地区，暂停发货时间暂定为：2016-11-30到2017-3-1
        //提示语：
        //您的订单中包含有不能发货到您所填写的收货地区的商品（商品编码：XXXXXXXX），请您核实后重新提交订单！
        //start
        var not_deliever_flag = true;
        if (not_deliever_flag == true) {
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

                //不支持的配送区域
                //不支持的商品
                var not_deliever_district = <?php echo json_encode(config_item("not_deliever_district"));?>;
                var not_deliever_goods = <?php echo json_encode(config_item("not_deliever_goods"));?>;
                if (-1 !== not_deliever_district.indexOf(data.addr_lv2)) {
                    //区域 遍历查询是否有商品不配送
                    var not_deliver = [];
                    var goods_sn_list = <?php echo json_encode($order['goods_info']);?>;
                    for (var j = 0; j < goods_sn_list.length; j++) {
                        if (not_deliever_goods.indexOf(goods_sn_list[j]['goods_sn']) !== -1) {

                            not_deliver.push(goods_sn_list[j]['goods_sn']);
                        }

                    }
                    if (not_deliver.length > 0) {
                        var not_deliver_str = not_deliver.join(',');
                        var area_cannot_reach_1 = '<?php echo lang('area_cannot_reach_1')?>';
                        var area_cannot_reach_2 = '<?php echo lang('area_cannot_reach_2')?>';
                        var attention = area_cannot_reach_1 + not_deliver_str + ")"
                        layer.msg(attention);
                        return false;
                    }
                } else {

                }


            }
        }
        //限制end

        //如果是店铺升级订单和 换购的就不对运费进行验证
        var customer_id = "<?php echo $order['order_detail']['customer_id'];?>";
        var shopkeeper_id = "<?php echo $order['order_detail']['shopkeeper_id'];?>";
        if (shopkeeper_id === '0') {
            return true;
        }
        var old_freight = <?php echo $order['order_detail']['deliver_fee']/100;?>;
        //韩国全部包邮  其他地区要验证新选的地址邮费是否超过原有的邮费
        var country_code_arr = ['156','344','410'];
        if (country_code_arr.indexOf(country) !== -1) {
            if (country !== '410') {
                var url = '/order/getFreight2'
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    dataType: "json",
                    success:function(data){
                        if(data.error == false) {
                            if(data.msg > old_freight) {
                                var msg = '<?php echo lang("order_fee_beyond")?>';
                                console.log(old_freight);
                                layer.msg(msg);
                            }
                        } else {
                            layer.msg(data.msg);
                        }
                    },
                    error:function(data){
                        console.log(data.responseText);
                    }
                })

            }
        } else {
            layer.msg("Does not support area");

        }


    }

    //修改地址说明

    function edit_address_desc()
    {
        $("#myModal").modal('show');
    }
    $("#close_btn").click(function(){
        $("#myModal").modal('hide');
    })

    function T4_onkeyup() {
        if($("#box_phone_kr_1").val().length == 3){
            $("#box_phone_kr_2").focus();
        }
    }
    function T5_onkeyup() {
        if($("#box_phone_kr_2").val().length == 4){
            $("#box_phone_kr_3").focus();
        }
    }

    function T6_onkeyup() {
        if($("#box_phone_kr_3").val().length == 4){
            $("#box_phone_kr_1").focus();
        }
    }



    //发送验证码
    $('#get_mobile_code').click(function () {
        var bind_mobile = "<?php echo $bind_mobile;?>";
        var bind_mobile_message_1 = "<?php echo $bind_mobile_message_1;?>";
        if (bind_mobile != 1) {
            layer.msg(bind_mobile_message_1);
            return ;
        }
        var phone = "<?php echo $user['mobile'];?>";
        var send_message = "<?php echo $mobile_sended;?>";
        $("#get_mobile_code").attr('disabled', true);
        $("#get_mobile_code").css('background', '#cccccc');
        add1(60);
        $.ajax({
            type: "POST",
            url: "/ucenter/mobile/get_mobile_code",
            data: {mobile:phone},
            dataType: "json",
            success: function (data) {
                if (data.error == false) {
                    layer.msg(send_message);
                } else {
                    clearTimeout(t);
                    $("#get_phone_code").removeAttr('disabled');
                    $("#get_phone_code").css('background', '#E5E5E5');
                    $("#get_phone_code").val('<?php echo lang('get_phone_code') ?>');
                    layer.msg(data.message);
                }
            }
        });
    });

    function add1(timerc) {
        if (timerc > 1) {
            --timerc;
            $("#get_mobile_code").val($('#resend_captcha').val() + timerc + 's');
            t = setTimeout("add1(" + timerc + ")", 1000);
        } else {
            $("#get_mobile_code").val($('#get_captcha').val());
            $("#get_mobile_code").attr('disabled', false);
        }
    }
</script>
