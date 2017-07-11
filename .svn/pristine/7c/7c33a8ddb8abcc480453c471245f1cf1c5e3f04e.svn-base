<?php
include("ysepay_service.php");
$obj_ysepay_service = new ysepay_service();
$date = date(Ymd);
if($_POST[sub]){
	//获取参数
	$input=array("accountdate"=>$_POST[accountdate]);
	$return = $obj_ysepay_service->S7001_ysepay($input);
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
		if(trim($msgCode)==="R7001"){
			file_put_contents("Response/R7001/"."S7001_".$input['accountdate'].".txt",$msg);
			if($msg!==null)
			//对账单下载成功，处理自己的业务..
			print_r("对账单下载成功");
		}else if(trim($msgCode)==="R9001"){//报文校验不合法
			echo "<script>window.location.href='return_xml.php?xml=".urlencode($unsign['data'])."';</script>";
		}
	}
	else
	echo "result: ", $return['success'] ,$return['url'] ,$return['msg'];
}
?>
<form action="S7001.php" method="post" target='_blank'>
			对账日期: <input type="text" name="accountdate" />(8位日期,如:<?=$date?>)<br />
			<input type="submit" name="sub" value="发起对账"/><input type="reset" value="重置" />
</form>