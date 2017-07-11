<?php
include("ysepay_service.php");
$obj_ysepay_service = new ysepay_service();
$date = date(Ymd);
if($_POST[sub]){
	//获取参数
	$input=array("BankType"=>"","BankAccountType"=>"","date"=>$_POST[date],"orderid"=>$_POST[orderid],"busicode"=>$_POST[busicode],"amount"=>$_POST[amount],"ordernote"=>$_POST[ordernote],"banktype"=>$_POST[banktype],"bankaccounttype"=>$_POST[bankaccounttype]);
	$return = $obj_ysepay_service->S3001_ysepay($input);
	$url = $return['url'];
	if ($return['success'] == 1)
	//echo $url;
	echo "<script>window.location.href='$url';</script>";
	else
	echo "result: ", $return['success'] ,$return['url'] ,$return['msg'];
}
?>
<h4>模拟S3001即时到账订单交易</h4>
<form action="S3001.php" method="post" target='_blank'>
			<h3 style="color:red">若为快捷支付，则在收银台界面选择“银联快捷”支付方式</h3>
			<h5 style="color:red">验证签名部分有问题，如有需要请自行做相应更改！</h5>
			订单号: &nbsp;&nbsp;<input type="text" name="orderid" /><br />
			业务代码: <input type="text" name="busicode" />(测试环境默认为:01000010,网上购物)<br />
			金额: &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="amount" />(最高15位，单位:分)<br />
			商户日期: <input type="text" name="date" />(8位日期,如:<?=$date?>)<br />
			订单说明: <input type="text" name="ordernote" /><br />
			<h3 style="color:red">若为纯网关方式，则下面参数必须填写</h3>
			银行行别: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="banktype" />(固定值：9001000)<br />
			付款方银行账户类型: <input type="text" name="bankaccounttype" />(如：11 - 对私借记卡)<br />
			<input type="submit" name="sub" value="确认订单,付款"/><input type="reset" value="重置" />
</form>