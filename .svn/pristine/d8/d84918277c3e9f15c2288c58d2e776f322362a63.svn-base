<?php
include("ysepay_service.php");
$obj_ysepay_service = new ysepay_service();
$date = date(Ymd);
if($_POST[sub]){
	//获取参数
	$input=array("refundorderid"=>$_POST[refundorderid],"refunddate"=>$_POST[refunddate],"tradesn"=>$_POST[tradesn]);
	$return = $obj_ysepay_service->S5107_ysepay($input);
	$data = $return['data'];
	$uri = $obj_ysepay_service->param['xmlbackmsg_url'];
	
	if ($return['success'] == 1)
	{
		//发送请求
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt (	$ch, CURLOPT_TIMEOUT,60);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		$return = curl_exec ( $ch );
		curl_close ( $ch );
		
		//处理回执
		if (strlen($return)<=313) {
		 	print_r("银盛支付回执信息不完整".$return);
		}
		$src=substr($return,0,20);
		$msgCode=substr($return,20,5);
		$msgid=substr($return,25,32);
		$check=substr($return,57,256);
		$msg=substr($return,313);
		$unsign=$obj_ysepay_service->unsign_crypt(array("check"=>trim($check),"msg"=>trim($msg)));
		if(trim($msgCode)==="R5107"){
			ereg("<RefundOrderId>(.*)</RefundOrderId>",$unsign['data'],$RefundOrderId);
			file_put_contents("Response/R5107/"."S5107_".date(YmdHis)."_".$RefundOrderId[1].".txt",$unsign['data']);
			ereg("<Code>(.*)</Code>",$unsign['data'],$Code);
			ereg("<Note>(.*)</Note>",$unsign['data'],$Note);
			ereg("<RefundDate>(.*)</RefundDate>",$unsign['data'],$RefundDate);
			ereg("<RefundAmount>(.*)</RefundAmount>",$unsign['data'],$RefundAmount);
			ereg("<Reason>(.*)</Reason>",$unsign['data'],$Reason);
			ereg("<State>(.*)</State>",$unsign['data'],$State);
			if($Code[1]==="0000")
			//查询成功，处理自己的业务..
			print_r("查询状态：".iconv("GBK","UTF-8//IGNORE",$Note[1])." 退款订单号：".$RefundOrderId[1]." 退款日期：".$RefundDate[1]." 退款金额：".$RefundAmount[1]." 退款原因：".iconv("GBK","UTF-8//IGNORE",$Reason[1])." 状态：".$State[1]);
			else 
			//出现异常，处理自己的业务..
			print_r("返回码：".$Code[1]." 返回码说明：".iconv("GBK","UTF-8//IGNORE",$Note[1]));
		}else if(trim($msgCode)==="R9001"){//报文校验不合法
			echo "<script>window.location.href='return_xml.php?xml=".urlencode($unsign['data'])."';</script>";
		}
	}
	else
	echo "result: ", $return['success'] ,$return['url'] ,$return['msg'];
}
?>
<form action="S5107.php" method="post" target='_blank'>
			退款订单号: <input type="text" name="refundorderid" /><br />
			退款日期: &nbsp;&nbsp;<input type="text" name="refunddate" />(8位日期,如:<?=$date?>)<br />
			交易流水: &nbsp;&nbsp;<input type="text" name="tradesn" />(选填。支付平台的交易流水)<br />
			<input type="submit" name="sub" value="发起查询"/><input type="reset" value="重置" />
</form>