<html>
<body>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/wxpay.css?v=12'); ?>">
</head>
<script>
	$(function(){
	/** 定時器請求支付是否成功，跳轉頁面 */
	setInterval(function(){
		 $.ajax({
		 type: "POST",
		 url: "/order/get_orders_notify",
		 data:{order_id:"<?php echo $order_id;?>"},
		 dataType: "json",
			 success: function (res) {
				 if (res.success) {
					 location.href='/respond/wx_do_return/' + "<?php echo $order_id;?>"
				 }
			 }
		 })
	 },5000);
	});
</script>
<div class="wx_content">
                <?php if(isset($class)){ ?>
    <div class="con_top" style="background-color: #01a9f2;">
        <div class="title" style="background: url(/img/paymentMethod/<?php echo $class;?>.gif) no-repeat;"></div>
                <?php }else { ?>
            <div class="con_top">
                    <div class="title"></div>
                <?php }?>
		<div class="dbcode">
			<?php if(isset($msg)){ ?>
				<span><?php echo $msg?></span>
			<?php }else{?>
                                <img width="246" height="246" src="<?php echo base_url('common/get_wx_code?data='.urlencode($url))?>"/>
			<?php }?>
		</div>
	</div>
	<div class="con_bot">
		<div class="txt270">
			<p>TPS<br>提供支付服务</p>
		</div><br>

	</div>
</div>
</body>
</html>




        

		



