<?php
namespace com\cskj\pay\demo\action;

use com\cskj\pay\demo\common\ConfigUtil;
use com\cskj\pay\demo\common\TDESUtil;
use com\cskj\pay\demo\common\SignUtil;
use com\cskj\pay\demo\common\RSAUtils;
include '../common/ConfigUtil.php';
include '../common/TDESUtil.php';
include '../common/SignUtil.php';

class CallBack{
	public function execute(){
	    $param;
		/* $desKey = ConfigUtil::get_val_by_key("desKey");
		$keys = base64_decode($desKey);
		
		if($_POST["tradeNum"] != null && $_POST["tradeNum"]!=""){
			$param["tradeNum"]=TDESUtil::decrypt4HexStr($keys, $_POST["tradeNum"]);
		}
		if($_POST["amount"] != null && $_POST["amount"]!=""){
			$param["amount"]=TDESUtil::decrypt4HexStr($keys, $_POST["amount"]);
		}
		if($_POST["currency"] != null && $_POST["currency"]!=""){
			$param["currency"]=TDESUtil::decrypt4HexStr($keys, $_POST["currency"]);
		}
		if($_POST["tradeTime"] != null && $_POST["tradeTime"]!=""){
			$param["tradeTime"]=TDESUtil::decrypt4HexStr($keys, $_POST["tradeTime"]);
		}
		if($_POST["note"] != null && $_POST["note"]!=""){
			$param["note"]=TDESUtil::decrypt4HexStr($keys, $_POST["note"]);
		}
		if($_POST["status"] != null && $_POST["status"]!=""){
			$param["status"]=TDESUtil::decrypt4HexStr($keys, $_POST["status"]);
		}
		
		$sign =  $_POST["sign"];
*/
		//-------------------
		$unSignKeyList = array ("sign");
		 
		//echo  $_POST["currency"];
		// 		$desKey = ConfigUtil::get_val_by_key("desKey");
        //$param=json_encode(file_get_contents('php://input'));
	//	$sign = $param['sign'];//SignUtil::signMD5($param, $unSignKeyList);
		
		//$param = "{\"returnCode\":\"0\",\"resultCode\":\"0\",\"sign\":\"550BE3CEE2AD6F3D921E839D33B1B588\",\"status\":\"04\",\"channel\":\"wxPub\",\"body\":\"\u8d2d\u4e70\u5546\u54c1\",\"outTradeNo\":\"201609291428437\",\"amount\":0.01,\"currency\":\"CNY\",\"transTime\":\"20160929142845\",\"payChannelType\":\"weixin\"}"
	
        $respJson=json_decode("{\"returnCode\":\"0\",\"resultCode\":\"0\",\"sign\":\"550BE3CEE2AD6F3D921E839D33B1B588\",\"status\":\"04\",\"channel\":\"wxPub\",\"body\":\"\u8d2d\u4e70\u5546\u54c1\",\"outTradeNo\":\"201609291428437\",\"amount\":0.01,\"currency\":\"CNY\",\"transTime\":\"20160929142845\",\"payChannelType\":\"weixin\"}");
 		echo $respJson;
 		$sign = $param['sign'];
        $respSign = SignUtil::signMD5($param, $unSignKeyList);
		echo $respSign;
		
		if($sign!=$respSign){
			echo "验证签名失败！";
		}else{
			$_SESSION["tradeResultRes"]=$param;
			header("location:../page/success.php");
		}
		
	}
	
	
}
error_reporting(0);
$m = new CallBack();
$m->execute();
?>