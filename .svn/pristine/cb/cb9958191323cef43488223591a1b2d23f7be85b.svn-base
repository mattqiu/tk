<?php
include("ysepay_service.php");
$obj_ysepay_service = new ysepay_service();
if($_POST[sub]){
	//获取参数
	$input=array("orderid"=>$_POST[orderid],"tradesn"=>$_POST[tradesn]);
	$return = $obj_ysepay_service->S5003_ysepay($input);
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
		if(trim($msgCode)==="R5003"){
			ereg("<OrderId>(.*)</OrderId>",$unsign['data'],$OrderId);
			file_put_contents("Response/R5003/"."S5003_".date(YmdHis)."_".$OrderId[1].".txt",$unsign['data']);
			ereg("<Code>(.*)</Code>",$unsign['data'],$Code);
			ereg("<Note>(.*)</Note>",$unsign['data'],$Note);
			ereg("<ShopDate>(.*)</ShopDate>",$unsign['data'],$ShopDate);
			ereg("<Cur>(.*)</Cur>",$unsign['data'],$Cur);
			ereg("<Amount>(.*)</Amount>",$unsign['data'],$Amount);
			ereg("<Remark>(.*)</Remark>",$unsign['data'],$Remark);
			ereg("<State>(.*)</State>",$unsign['data'],$State);
			if($Code[1]==="0000")
			//查询成功，处理自己的业务..
			print_r("查询状态：".iconv("GBK","UTF-8//IGNORE",$Note[1])." 订单号：".$OrderId[1]." 商户日期：".$ShopDate[1]." 金额：".$Amount[1].$Cur[1]." 备注：".$Remark[1]." 订单状态：".$State[1]);
			else 
			//出现异常，处理自己的业务..
			print_r("返回码：".$Code[1]." 返回码说明：".iconv("GBK","UTF-8//IGNORE",$Note[1]));
		}else if(trim($msgCode)==="R9001"){//报文校验不合法
			echo "<script>window.location.href='return_xml.php?xml=".urlencode($unsign['data'])."';</script>";
		}
	}
	//echo $url;
	//echo "<script>window.location.href='$url';</script>";
	else
	echo "result: ", $return['success'] ,$return['url'] ,$return['msg'];
}
?>
<form action="S5003.php" method="post" target='_blank'>
	<h2>两者选填其一</h2>
			订单号: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="orderid" /><br />
			交易流水: &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="tradesn" /><br />
			<input type="submit" name="sub" value="发起查询"/><input type="reset" value="重置" />
</form>