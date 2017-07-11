<form id="mall_form_submit" name=alipayment action='/respond/go_order_paywap' method=post>
    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
    <input type="hidden" name="passwordzj" value="">
    <div class="container">
        <div class="row">
            <p class="header"> 订单支付 </p>
            <div class="zhifu-tt">
                <p class="h">订单提交成功，仅查一步完成支付，请您尽快付款！</p>
                <ul>
                    <li><b><?php echo lang('pay_order_id'); ?></b>	<em><?php echo $order_id; ?></em></li>
                    <li><b>订单详情：</b><em>直播：2 RMB</em></li>
                    <li class="mb-n"><b><?php echo lang('pay_amount'); ?></b><em><?php echo $pay_amount_show; ?></em></li>
                </ul>
            </div>
            <div class="zhifu">
                <?php
                foreach ($payments as $k => $payment) {
                    if ($payment['pay_name'] == 'Amount') {
                        ?>
                        <div class="radio yuan">
                            <a href="#myModal" role="button" data-toggle="modal">
                                <label> 
                                    <input class="payment_margin" checked="" autocomplete="off" type="radio" name="payment_method" value="<?php echo $payment['pay_id']; ?>">
                                    <span class="text"><b><?php echo lang('current_commission') ?>：</b><em><?php echo $my_amount ?></em></span>
                                </label>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="radio yuan">
                            <label>
                                <input class="payment_margin paytype" checked="" autocomplete="off" type="radio" name="payment_method" value="<?php echo $payment['pay_id']; ?>">
                                <span class="text"><i class="pc-tps <?php echo $payment['pay_name']; ?>"></i></span>
                            </label>
                        </div>
                    <?php
                    }
                }
                ?>
<!--<p><button type="button" class="btn btn-primary">确认支付</button></p>-->
            </div>
        </div>
    </div>
</form>
<div id="myModal" style="width: 100%;height: 100%;z-index: 100;background-color: #f3f3f7;position: absolute;left: 0px;top: 0px;display: none;">
    <div style="float: right;">
        <span style="font-size:12px;color: #000;">请在菜单中选择浏览器打开，以完成支付</span>
        <img src="<?php echo base_url('img/new/ddd20170310121037.png');?>" />
    </div>
    <div style="clear: both;">
        <button style="margin: 0px auto;display: block;border:none;" id="fhui" type="button" >返回</button>
    </div>
    </div>
<script language="javascript">
    function is_weixn(){  
    var ua = navigator.userAgent.toLowerCase();  
    if(ua.match(/MicroMessenger/i)=="micromessenger") {  
        return true;  
    } else {  
        return false;  
    }  
} 
    $(".paytype").click(function (){
        var $input=$('input:radio[name="payment_method"]:checked').val();
        if(is_weixn()&&$input=='105'){
            $("#myModal").css('display','block'); 
        }else{
            $.ajax({
                type: "POST",
                url: "/order/check_pay_info",
                data: $('#mall_form_submit').serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.success) {
                        if($input=='104'){//微信
                            $('#mall_form_submit').attr('action','/respond/go_order_pay'); 
                        }else if($input=='105'){//支付宝
                            $('#mall_form_submit').attr('action','/respond/go_order_paywap'); 
                        }
                        $('#mall_form_submit').submit();
                    } else {
                        layer.msg(res.msg);
                    }

                }
            });
        }
    });
    $("#fhui").click(function (){
        $("#myModal").css('display','none'); 
    });
</script>