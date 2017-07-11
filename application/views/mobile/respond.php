<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <title><?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <meta name="description" content="<?php echo $description; ?>" />
        <link rel="canonical" href="<?php echo $canonical; ?>" />
        <!--加载css文件-->
        <link rel="stylesheet" href="<?php echo base_url(MOBILE . '/css/bootstrap.css?v=1'); ?>">
        <link rel="stylesheet" href="<?php echo base_url(MOBILE . '/css/base.css?v=1'); ?>">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="ok-zf">
                    <div class="zhifu-tt">
                        <p class="k"><i class="pc-tps ok"></i>付款成功</p>
                        <ul>
                            <li><b>付款金额：</b><em class="fz-1-5"><?php
                                    if (substr($result['order_id'], 0, 1) == 'S') {//订单号首字母为S，报名费订单
                                        echo '¥1,000.00';
                                    } else {
                                        echo $amount;
                                    }
                                    ?></em></li>
                            <li class="mb-n"><b>订单号</b><a class="c-o" href=""><?php echo $result['order_id']; ?></a></li>
                            <li><?php if (substr($result['order_id'], 0, 1) == 'L') {//订单号首字母为L，直播费订单     ?>
                                    <div style='margin: 0 auto;line-height: 50px;text-align:center;font-size: large;color:red;'><?php echo lang('account_active_success_jump'); ?></div><div id='sec'></div>
                                <?php } ?></li>
                            <li class="b-t">
                                <p class="c-b">安全提示：</p>
                                <p>下单后，用手机短信或QQ给您发送链接办理退款的都是骗子，TPS商城系统升级会公告公布，也不存在订单异常等问题，谨防假冒客服电话诈骗！</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
    </body>
</html>