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
                    <li><b>订单详情：</b><em>峰会门票：1000RMB</em></li>
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
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="xj_form_submit" name=alipayment action='/order/go_amount_pay' method=post>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">请输入资金密码</label>
                            <input id="recipient-name" type="password" autocomplete="off" placeholder="资金密码" value="" name="pay_pwd" class="form-control">
                            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                            <input type="hidden" name="payment_method" value="110">
                            <input type="hidden" name="passwordzj" value="">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">确认</button>
                </div>
            </div>
        </div>
    </div>
<script language="javascript">
    $(".paytype").click(function (){
        $.ajax({
            type: "POST",
            url: "/order/check_pay_info",
            data: $('#mall_form_submit').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#mall_form_submit').submit();
                } else {
                    layer.msg(res.msg);
                }

            }
        });
    });
    $(".btn-primary").click(function (){
        $.ajax({
            type: "POST",
            url: "/order/check_pay_info",
            data: $('#xj_form_submit').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    $('#xj_form_submit').submit();
                } else {
                    layer.msg(res.msg);
                }

            }
        });
    });
</script>