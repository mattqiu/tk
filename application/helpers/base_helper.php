<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('del_redis_val')) {
	function del_redis_val($key = '') {
		if (empty($key)) {
			return false;
		}
		$ci = &get_instance();
		$ci->load->model("tb_empty");
		if (is_array($key)) {
			foreach ($key as $v) {
				$ci->tb_empty->redis_del($v);
			}
		} else if (is_string($key) && !empty($key)) {
			$ci->tb_empty->redis_del($key);
		}
	}
}

if (!function_exists('tps_log')) {
	function tps_log($data) {
		@error_log('[' . var_export($data, 1) . "]\r\n", 3, config_item('log_path') . 'tps.log');
	}
}

if (!function_exists('substr_tps')) {

	function substr_tps($str, $start = 0, $length = 12, $suffix = '...') {
		if (strlen($str) <= $length + $start) {
			return $str;
		}
		return substr($str, $start, $length) . $suffix;
	}

}

if(!function_exists('json_dump')){
    
    function json_dump($data){
        
        echo json_encode($data);
        exit;
    }
}

//**手机号加密
if (!function_exists('mobile_encrypy')) {

	function mobile_encrypy($mobile) {
		$left_length = 3;
		$right_length = 4;
		$left_3 = substr($mobile,0,$left_length);
		$right_3 = substr($mobile,"-".$right_length,$right_length);
		return $left_3."*****".$right_3;
	}

}


//**邮箱加密
if (!function_exists('email_encrypy')) {

	function email_encrypy($email) {
		$email_arr = explode("@",$email);
		if (!empty($email_arr) && count($email_arr) == 2 && strlen($email_arr[0]) >=3) {
			$email_start = "";
			if (strlen($email_arr[0]) >=5) {
				$left = 2;
				$right = 2;
			} else {
				$left = 1;
				$right = 1;

			}
			$start_count = 6;
			$length = $left + $right;
			$email_left = substr($email_arr[0],0,$left);
			$email_right = substr($email_arr[0],"-".$right,$right);
			for($i=0; $i< $start_count; $i++)
			{
				$email_start .= "*";
			}
			$email_encrypt = $email_left.$email_start.$email_right."@".$email_arr[1];
		} else {
			$email_encrypt = $email;
		}
		return $email_encrypt;
	}
}
//**身份证加密
if (!function_exists('id_card_encrypy')) {

	function id_card_encrypy($id_card_num) {
		$len = strlen($id_card_num)-6;
		$star = '';
		for($i=1;$i<=$len;$i++){
			$star .= '*';
		}
		$id_card_left = substr($id_card_num,0,2);
		$id_card_right = substr($id_card_num,-4,4);
		return $id_card_left.$star.$id_card_right;
	}
}



if (!function_exists('get_domain_prefix')) {

	function get_domain_prefix() {
		$host = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$arrHost = explode('.', $host);
		$countDomainItem = count($arrHost);
		if($countDomainItem==3){
			return $arrHost[0];
		}elseif($countDomainItem<3){
			return '';
		}else{
			unset($arrHost[$countDomainItem-1]);
			unset($arrHost[$countDomainItem-2]);
			return implode('.', $arrHost);
		}
	}
}

if (!function_exists('get_public_domain')) {

	function get_public_domain() {
		$host = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$host = preg_replace('/:\d+/',"",$host);
		$arrHost = explode('.', $host);
		$arrHostCount = count($arrHost);
		return $arrHostCount > 1 ? $arrHost[$arrHostCount - 2] . '.' . $arrHost[$arrHostCount - 1] : '';
	}

}

if (!function_exists('get_public_domain_port')) {

	function get_public_domain_port() {
		$host = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$arrHost = explode('.', $host);
		$arrHostCount = count($arrHost);
		return $arrHostCount > 1 ? $arrHost[$arrHostCount - 2] . '.' . $arrHost[$arrHostCount - 1] : '';
	}

}

/*计算某日期到今天已经过了几天*/
if(!function_exists('computer_flow_days')){

	function computer_flow_days($date){

		$days = (strtotime(date('Y-m-d')) - strtotime(substr($date,0,10)))/(3600*24);
		return $days<14?$days:14;
	}
}

// if(!function_exists('send_mail')){
// 	function send_mail($to,$title,$body,$bccs=array(),$attach='')
// 	{
// 		$ci =& get_instance();
// 		require_once APPPATH.'third_party/php_mailer/class.phpmailer.php';
// 		require_once APPPATH.'third_party/php_mailer/class.smtp.php';
//		
// 		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
// 		$mail->IsSMTP(); // telling the class to use SMTP
//
// 		try {
// 		  $mail->Username   = 'info@tps138.com'; // SMTP account username
// 		  $mail->Password   = 'soong123';        // SMTP account password
// 		  $mail->Host       = 'email.tps138.com'; // SMTP server
// 		  $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
// 		  $mail->SMTPAuth   = true;                  // enable SMTP authentication
// 		  $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
// 		  $mail->CharSet = "utf-8";
// 		  $mail->SMTPSecure = "ssl";
// 		  $mail->SetFrom($mail->Username, 'TPS');
// 		  $mail->AddAddress($to, '');
//		  
// 		  $mail->Subject = $title;
// 		  $mail->MsgHTML($body);
//
//		  if($bccs){
//			foreach($bccs as $bcc){
// 				$mail->addCC($bcc);
// 			}
// 		  }
//
//		  if($attach){
// 		  	$mail->addAttachment($attach);
// 		  }
//
// 		  $mail->Send();
// 		  return true;
// 		} catch (phpmailerException $e) {
// 			// error_log($e->errorMessage(),3,'/tmp/ly.txt');
// 		  echo $e->errorMessage(); //Pretty error messages from PHPMailer
// 		  return false;
// 		} catch (Exception $e) {
// 			error_log($e->getMessage(),3,'/tmp/ly.txt');
// 		   echo $e->getMessage(); //Boring error messages from anything else!
// 		  return false;
// 		}
//			
// 	}
// }

if(!function_exists('send_mail')){
	function send_mail($to,$title,$body,$bccs=array(),$attach='')
	{

		$url = 'http://sendcloud.sohu.com/webapi/mail.send.json';

        $param = array(
            'api_user' => 'ayonggege_xn_subdomain', # 使用api_user和api_key进行验证
            'api_key' => 'B9Mrzk9EmEKv2sGz',
            'from' => 'info@tps138.com', # 发信人，用正确邮件地址替代
            'fromname' => 'TPS',
            'to' => $to,# 收件人地址, 用正确邮件地址替代, 多个地址用';'分隔
            'subject' => $title,
            'html' => $body,
            'resp_email_id' => 'true'
        );


        $data = http_build_query($param);

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
        ));
        $context  = stream_context_create($options);
        $result = file_get_contents($url, FILE_TEXT, $context);

        return $result;
	}
}

// if(!function_exists('send_mail')){
// 	function send_mail($to,$title,$body,$bccs=array(),$attach='')
// 	{
// 		$ci =& get_instance();
// 		require_once APPPATH.'third_party/php_mailer/class.phpmailer.php';
// 		require_once APPPATH.'third_party/php_mailer/class.smtp.php';
		
// 		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
// 		$mail->IsSMTP(); // telling the class to use SMTP

// 		try {
// 		  $mail->Host       = 'smtpdm.aliyun.com'; // SMTP server
// 		  $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
// 		  $mail->SMTPAuth   = true;                  // enable SMTP authentication
// 		  $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
// 		  $mail->CharSet = "utf-8";
// 		  $mail->SMTPSecure = "ssl";
// 		  $mail->Username   = 'info@tmail.shoptps.com'; // SMTP account username
// 		  $mail->Password   = '2er3TR33r2r7282g2gw';        // SMTP account password

// 		  $mail->AddAddress($to, '');
// 		  $mail->SetFrom($mail->Username, 'TPS');
		 
// 		  $mail->Subject = $title;
// 		  $mail->MsgHTML($body);

// 		  if($bccs){
// 			foreach($bccs as $bcc){
// 				$mail->addCC($bcc);
// 			}
// 		  }

// 		  if($attach){
// 		  	$mail->addAttachment($attach);
// 		  }

// 		  $mail->Send();
// 		  return true;
// 		} catch (phpmailerException $e) {
// 		  // echo $e->errorMessage(); //Pretty error messages from PHPMailer
// 		  return false;
// 		} catch (Exception $e) {
// 		  // echo $e->getMessage(); //Boring error messages from anything else!
// 		  return false;
// 		}
			
// 	}
// }

//if(!function_exists('send_mail')){
//	function send_mail($to,$title,$body,$bccs=array(),$attach='')
//	{
//		$ci =& get_instance();
//		require_once APPPATH.'third_party/php_mailer/class.phpmailer.php';
//		require_once APPPATH.'third_party/php_mailer/class.smtp.php';
//		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
//		$mail->IsSMTP(); // telling the class to use SMTP
//
//		try {
//		  $mail->Host       = 'smtpdm.aliyun.com'; // SMTP server
//		  $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
//		  $mail->SMTPAuth   = true;                  // enable SMTP authentication
//		  $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
//		  $mail->CharSet = "utf-8";
//		  $mail->SMTPSecure = "ssl";
//		  $mail->Username   = 'info@tmail.shoptps.com'; // SMTP account username
//		  $mail->Password   = '2er3TR33r2r7282g2gw';        // SMTP account password
//
//		  $mail->AddAddress($to, '');
//		  $mail->SetFrom($mail->Username, 'TPS');
//		 
//		  $mail->Subject = $title;
//		  $mail->MsgHTML($body);
//
//		  if($bccs){
//			foreach($bccs as $bcc){
//				$mail->addCC($bcc);
//			}
//		  }
//
//		  if($attach){
//		  	$mail->addAttachment($attach);
//		  }
//
//		  $mail->Send();
//		  return true;
//		} catch (phpmailerException $e) {
//		   echo $e->errorMessage(); //Pretty error messages from PHPMailer
//		  return false;
//		} catch (Exception $e) {
//		   echo $e->getMessage(); //Boring error messages from anything else!
//		  return false;
//		}
//			
//	}
//}


//if(!function_exists('send_mail')){
//	function send_mail($to,$title,$body,$bccs=array(),$attach='')
//	{
//		$ci =& get_instance();
//		require_once APPPATH.'third_party/php_mailer/class.phpmailer.php';
//		require_once APPPATH.'third_party/php_mailer/class.smtp.php';
//		
//		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
//		$mail->IsSMTP(); // telling the class to use SMTP
//
//		try {
//		  $mail->Host       = 'smtpdm.aliyun.com'; // SMTP server
//		  $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
//		  $mail->SMTPAuth   = true;                  // enable SMTP authentication
//		  $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
//		  $mail->CharSet = "utf-8";
//		  $mail->SMTPSecure = "ssl";
//		  $mail->Username   = 'info@tmail.shoptps.com'; // SMTP account username
//		  $mail->Password   = '2er3TR33r2r7282g2gw';        // SMTP account password
//
//		  $mail->AddAddress($to, '');
//		  $mail->SetFrom($mail->Username, 'TPS');
//		 
//		  $mail->Subject = $title;
//		  $mail->MsgHTML($body);
//
//		  if($bccs){
//			foreach($bccs as $bcc){
//				$mail->addCC($bcc);
//			}
//		  }
//
//		  if($attach){
//		  	$mail->addAttachment($attach);
//		  }
//
//		  $mail->Send();
//		  return true;
//		} catch (phpmailerException $e) {
//		  // echo $e->errorMessage(); //Pretty error messages from PHPMailer
//		  return false;
//		} catch (Exception $e) {
//		  // echo $e->getMessage(); //Boring error messages from anything else!
//		  return false;
//		}
//			
//	}
//}


if (!function_exists('api_create_sign')) {

	function api_create_sign($token) {
		return sha1('tps' . sha1($token) . '!#*');
	}

}

/*api mobile 生成sign By Terry.*/
if (!function_exists('api_mobile_create_sign')) {

	function api_mobile_create_sign($token) {
		return sha1(config_item('mobile')['api_key'].sha1($token).config_item('mobile')['api_key2']);
	}

}

/**
 * @author brady
 * @desc   资金变动报表分表后 通过uid，获取表名
 */
if (!function_exists('get_cash_account_log_table')) {

	function get_cash_account_log_table($uid,$month='') {
		if (empty($month)) {
			$month = date("Ym");
		}
		if (config_item('cash_account_log_read_new') == false) {
			return $table = "cash_account_log_".$month;
		}
		if ($month <= config_item("cash_account_log_cut_table_end")) {
			return $table = "cash_account_log_".$month;
		} else {
			$left_4 = substr($uid,0,4);
			return $table = "cash_account_log_".$month."_".$left_4;
		}
	}

}

if (!function_exists('get_cash_account_log_table_write')) {

	function get_cash_account_log_table_write($uid,$month='') {

		if (empty($month)) {
			$month = date("Ym");
		}
		if ($month <= config_item("cash_account_log_cut_table_end")) {
			return $table = "cash_account_log_".$month;
		} else {
			$left_4 = substr($uid,0,4);
			return $table = "cash_account_log_".$month."_".$left_4;
		}
	}

}

if (!function_exists('api_encode_pwd')) {

	function api_encode_pwd($pwd, $token) {
		$interferStr = sha1($token . 'tps!#*');
		$prefix = substr($interferStr, 13, 8);
		$suffix = substr($interferStr, 8, 3);
		return base64_encode($prefix . $pwd . $suffix);
	}

}

if (!function_exists('api_decode_pwd')) {

	function api_decode_pwd($pwdEncode) {
		return substr(base64_decode($pwdEncode), 8, -3);
	}

}

if (!function_exists('globalization')){
	 /**  导出时 数据库字段转化多语言*/
	 function globalization($data){

		foreach($data as $key=>$item){
			$data[$key]  = lang($item);
		}
		return $data;
	}
}

if(!function_exists('get_decimal_places')){
	function get_decimal_places($numerical){
		$arr = explode( '.',$numerical);
		return isset($arr[1])?strlen($arr[1]):0;
	}
}

/**
 * 金額格式化函數，保留2位小數
 * @author Terry Lu
 */
if(!function_exists('tps_money_format')){
	function tps_money_format($money){
		return sprintf("%0.2f",round($money,2));
	}
}

/**
 * tps取整函数（4舍5入）
 * @author Terry Lu
 */
if(!function_exists('tps_int_format')){
	function tps_int_format($number){
		return round($number);
	}
}

if(!function_exists('get_last_timestamp')){
	function get_last_timestamp(){ //上个月最后一天的时间戳
		return strtotime(date('Y-m-t 23:59:59', strtotime('-1 month')));
	}
}

if(!function_exists('get_first_timestamp')){
	function get_first_timestamp(){ //上个月第一天的时间戳
		return strtotime(date('Y-m-01', strtotime('-1 month')));
	}
}
if(!function_exists('get_order_sn')){
	function get_order_sn($prefix = 'U')
	{
		/* 选择一个随机的方案 */
		mt_srand((double) microtime() * 1000000);

		return $prefix.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}

if(!function_exists('get_after_sale_id')){
	function get_after_sale_id($prefix = 'TH')
	{
		/* 选择一个随机的方案 */
		mt_srand((double) microtime() * 1000000);

		return $prefix.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}

if(!function_exists('croppedImg')){
	function croppedImg($x,$y,$w,$h,$pic,$uid){ //剪切图片
		//剪切后小图片的名字
		$str = explode(".",$pic);//图片的格式
		$type = $str[1]; //图片的格式
		$filename = $uid.'_'.date('YmdHis').".". $type;
		$uploadBanner = $pic;
		if (!is_dir('upload/cropped/')) {
			mkdir('upload/cropped/', DIR_WRITE_MODE); // 使用最大权限0777创建文件
		}
		$sliceBanner = "upload/cropped/".$filename;//剪切后的图片存放的位置

		$size=getimagesize($uploadBanner);
		switch($size['mime']){
			case 'image/jpeg': $src_pic = imagecreatefromjpeg($uploadBanner);break;
			case 'image/gif' : $src_pic = imagecreatefromgif($uploadBanner);break;
			case 'image/png' : $src_pic = imagecreatefrompng($uploadBanner);break;
			default: $src_pic=false;break;
		}
		//创建图片
		$dst_pic = imagecreatetruecolor($w, $h);
		@imagecopyresampled($dst_pic,$src_pic,0,0,$x,$y,$w,$h,$w,$h);
		imagejpeg($dst_pic, $sliceBanner);
		@imagedestroy($src_pic);
		@imagedestroy($dst_pic);

		//删除已上传未裁切的图片
		if(file_exists($uploadBanner)) {
			unlink($uploadBanner);
		}
		return $sliceBanner;
	}
}

if(!function_exists('create_qr_code')){
	function create_qr_code($uid){ //生成二維碼圖片
		include_once APPPATH .'third_party/qrcode/phpqrcode.php';
		$value = 'http://'.$uid.'.tps138.com/mobile_enroll'; //二维码内容
		$errorCorrectionLevel = 'H';//容错级别
		$matrixPointSize = 6;//生成图片大小

		if (!is_dir('upload/qrcode/')) {
			mkdir('upload/qrcode/', DIR_WRITE_MODE); // 使用最大权限0777创建文件
		}

		//生成二维码图片
		QRcode::png($value, 'upload/qrcode/'.$uid.'.png', $errorCorrectionLevel, $matrixPointSize, 2);

		$logo = 'themes/mall/img/tps.png';//准备好的logo图片
		$QR = 'upload/qrcode/'.$uid.'.png';//已经生成的原始二维码图

		if ($logo !== FALSE) {
			$QR = imagecreatefromstring(file_get_contents($QR));
			$logo = imagecreatefromstring(file_get_contents($logo));
			$QR_width = imagesx($QR);//二维码图片宽度
			$QR_height = imagesy($QR);//二维码图片高度
			$logo_width = imagesx($logo);//logo图片宽度
			$logo_height = imagesy($logo);//logo图片高度
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			//重新组合图片并调整大小
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
				$logo_qr_height, $logo_width, $logo_height);
		}
		//输出图片
		imagepng($QR, 'upload/qrcode/'.$uid.'.png');
	}
}

if(!function_exists('use_temporary_rule')){
	function use_temporary_rule(){
		if(date('Y-m-d')< config_item('rule_switch_time')){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

if(!function_exists('tps_curl_post2')){
	function tps_curl_post2($url, $postData) {
		$dataFormat = '';
		foreach($postData as $k=>$v){
			$v = @iconv("UTF-8","GBK", $v);
			if($v==''){
				$v='default';
			}
			$dataFormat.='&'.$k.'='.urlencode($v);
		}

		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => substr($dataFormat,1),
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		curl_close($ch);
		return json_decode($result);
	}
}

if(!function_exists('add_params_to_url')){
	function add_params_to_url(&$url,$params){
		$params_url = '';
		foreach($params as $k=>$v){
			if($k=='page'){
				continue;
			}
			$params_url.='&'.$k.'='.$v;
		}
		if(!strpos($url, '?')){
			$params_url = '?'.substr($params_url,1);
		}
		$url.=$params_url;
	}
}

//解析csv文件，返回二维数组，第一维是一共有多少行csv数据，第二维是键名为csv列名，值为当前行当前列的csv数据值
if(!function_exists('input_csv')){
	function input_csv($csv_file) {
		$csv_file = fopen($csv_file, 'r');
		$result_arr = array ();
		$i = 0;
		while ($data_line = fgetcsv($csv_file)) {
			if($i == 0){
				$GLOBALS['csv_key_name_arr'] = $data_line;
				$i++;
				continue;
			}

			foreach($GLOBALS['csv_key_name_arr'] as $csv_key_num=>$csv_key_name){
				$result_arr[$i][$csv_key_name] = $data_line[$csv_key_num];
			}
			$i++;
		}
		fclose($csv_file);

		return $result_arr;
	}
}

if(!function_exists('get_unionpay_config')){
	function get_unionpay_config(){
		if (config_item('payment_test')) {
			$unionpayConfig = config_item('unionpay_test');
		}else{
			$unionpayConfig = config_item('unionpay');
		}
		return $unionpayConfig;
	}
}
if(!function_exists('trimall')){
	function trimall($str)//删除空格
	{
		$qian=array(" ","　","\t","\n","\r","&nbsp;");$hou=array("","","","","","",);
		return str_replace($qian,$hou,$str);
	}
}
if(!function_exists('rangePassword')){
	function rangePassword($len=6,$format='ALL'){
	switch($format) {
		case 'ALL':
			$chars='ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789-@#~'; break;
		case 'CHAR':
			$chars='ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz-@#~'; break;
		case 'NUMBER':
			$chars='0123456789'; break;
		case 'NUMBER_AND_LETTER':
			$chars='ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789';
			break;
		default :
			$chars='ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789-@#~';
			break;
	}

	$password="";
	$end = strlen($chars) -1;
	for ( $i = 1; $i <= $len; $i++ )
	{
		$password .= $chars[mt_rand(0,$end)];
	}
	return $password;
 }
}

/* 计算当前汇率下的价格  */
function format_price($price,$rate) {
	return number_format($price * $rate,2,'.','');
}

/* 将当前货币下的价格换算成美元价格 */
function format_price_to_dollor($price,$rate) {
	return number_format($price / $rate,2,'.','');
}

/**
 * 高精度换算指定汇率的价格，精确到分
 * @param int $price 单位为分的整数型价格
 * @param float $rate 6位小数的汇率
 * @return float 单位为元、两位小数浮点型的价格
 */
function format_price_high_accuracy($price, $rate)
{
	return number_format(round($price * $rate) / 100, 2, '.', '');
}

/**
 * 处理序列化的支付、配送的配置参数 返回一个以name为索引的数组
 */
function unserialize_config($cfg)
{
	if (is_string($cfg) && ($arr = unserialize($cfg)) !== false)
	{
		$config = array();
		foreach ($arr AS $val)
		{
			$config[$val['name']] = $val['value'];
		}

		return $config;
	}
	else
	{
		return false;
	}
}

/* 获取父类下所有底层子类id string */
function getChildArr($catKeyArr,$cat_id){
	$cat_idArr = '';
	foreach($catKeyArr as $kk => $vv){
		if($vv['parent_id'] == $cat_id){
			$cat_idArr .= $vv['cate_id'].','.getChildArr($catKeyArr,$vv['cate_id']);
		}
	}

	return $cat_idArr;

}
/**
 * 获取产品分类的最高分类
 *@return array
 */
function getParentArr($arr, $pid) {
	$ret = [];
	foreach ($arr as  $v) {
		if ($v['cate_id'] == $pid) {
			$ret = $v;
			if ($v['parent_id'] == 0) {
				break;
			}
			$ret = getParentArr($arr, $v['parent_id']);
		}
	}
	return $ret;
}

/** 读取excel的数据 */
function readExcel($filePath, $mime){

	require_once APPPATH.'third_party/PHPExcel/PHPExcel.php';
	require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
	require_once APPPATH.'third_party/PHPExcel/PHPExcel/Reader/Excel2007.php';
	require_once APPPATH.'third_party/PHPExcel/PHPExcel/Reader/Excel5.php';

	# excel 97-2003
	if ($mime == "application/vnd.ms-excel") {
		PHPExcel_IOFactory::createReader('Excel5');
	}
	# excel 2007
	else if ($mime == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
		PHPExcel_IOFactory::createReader('Excel2007');
	} else {
		PHPExcel_IOFactory::createReader('Excel2007');
	}

	$objPHPExcel = PHPExcel_IOFactory::load($filePath);

	$sheet = $objPHPExcel->getActiveSheet();
	$rowCount = (int)$sheet->getHighestRow();
	$columnCount =  PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
	$retData = array();
	for ($i = 1; $i <= $rowCount; $i++) {
		for ($j = 0; $j < $columnCount; $j++) {
			$unit = $sheet->getCellByColumnAndRow($j, $i)->getValue();
			$retData[$i][$j] = $unit;
		}
	}
	return $retData;
}

// HTML字符转义成实体
function encodeHtml($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = encodeHtml($val);
		}
	} else {
		$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
		if(strpos($string, '&amp;#') !== false) {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
		}
	}
	return $string;
}




/**
 * leon
 * 字符串检测
 * 修改之前
 */
if(!function_exists('string_detection_leon')){
    function string_detection_leon($value){
        $rex = '/select|insert|and|or|update|delete|union|into|load_file|outfile/i';
        //先做个简单的验证，
        if(preg_match($rex, $value)){
            return false;//有存在
        }else{
            return true; //没有存在
        }
        
        //$aaa = trim($value,$matches[0]);
        //echo strlen($aaa);
        //$a = strlen(trim($matches[0]));
        //$b = strlen(trim($value));
        //if($a == $b){
        //    return false;//有存在
        //}else{
        //    return true; //没有存在
        //}
            
    }
}
/**
 * leon
 * 字符串检测
 * 修改之后
 */
if(!function_exists('string_detection')){
    function string_detection($value){
        $rex = '/select|insert|and|or|update|delete|union|into|load_file|outfile/i';
        $data = array();
        if(!empty($value)){
            if(preg_match($rex, $value, $matches)){
                if(empty($matches)){
                    $data['code']='1';
                    $data['msg']="";
                }else{
                    $str_array = explode(' ',$value);
                    if(in_array($matches[0], $str_array)){
                        $data['code']='0';
                        $data['msg']=$matches[0];//字符中存在的敏感词
                    }else{
                        $data['code']='1';
                        $data['msg']="";
                    }
                }
            }else{
                $data['code']='1';
                $data['msg']="";
            }
        }else{
            $data['code']='1';
            $data['msg']="";
        }
        return $data;
    }
}

/**
 * leon
 * 转换要保存的数据内容中 html内容
 */
if(!function_exists('htmlspecialchars_detection')){
    function htmlspecialchars_detection($value){
        if(is_array($value)){
            foreach ($value as $k => $v){
                $data[$k] = htmlspecialchars_detection($v);
            }
        }else{
            $data = htmlspecialchars($value,ENT_QUOTES);//使用函数转换内容为字符串
        }
        return $data;
    }
}

/**
 * leon
 * 转换要保存的数据内容中 html内容
 */
if(!function_exists('htmlspecialchars_decode_detection')){
    function htmlspecialchars_decode_detection($value){
        if(is_array($value)){
            foreach ($value as $k => $v){
                $data[$k] = htmlspecialchars_decode_detection($v);
            }
        }else{
            $data = htmlspecialchars_decode($value,ENT_QUOTES);//使用函数转换内容为字符串
        }
        return $data;
    }
}

/**
 * leon
 * 清空数组中值的前后空格
 * @param  array
 * @return array
 */
if(!function_exists('TrimArray')){
	function TrimArray($Input){
		if (!is_array($Input))
			return trim($Input);
		return array_map('TrimArray', $Input);
	}
}


/*年月＋1算法 By Terry*/
function yearMonthAddOne($yearMonth){
    $year = substr($yearMonth,0,4);
    $month = substr($yearMonth,4);
    if($month!=12){
        $month = $month+1;
    }else{
        $year = $year+1;
        $month = 1;
    }
    $month = $month<10?('0'.$month):$month;
    return $year.$month;
}

if(!function_exists('get_year_months_by_time_period')){

	function get_year_months_by_time_period($start_time,$end_time='',$sort='asc'){

		$return = array();

		$startYearMonth = date('Ym',strtotime($start_time));
		$endYearMonth = $end_time?date('Ym',strtotime($end_time)):date('Ym');

		$yearMonth = $startYearMonth;
		$return[] = $yearMonth;
		while ($yearMonth<$endYearMonth){

			$yearMonth = yearMonthAddOne($yearMonth);
			$return[] = $yearMonth;
		}

		if($sort=='desc'){
			$return = array_reverse($return);
		}

		return $return;
	}
}

/**
 * 返回快递单追踪运单信息的URL
 */
if (!function_exists('get_tracking_url'))
{
	function get_tracking_url($url, $tracking_no)
	{
		$ret_url = $url;

		// 存在追踪号
		if (false !== strpos($url, '#TrackingNo#'))
		{
			$ret_url = str_replace('#TrackingNo#', $tracking_no, $ret_url);
		}

		return $ret_url;
	}
}
/**
 * 获得用户的真实IP地址
 *
 * @access  public
 * @return  string
 */
if (!function_exists('get_real_ip'))
{
	function get_real_ip()
	{
		static $realip = NULL;

		if ($realip !== NULL)
		{
			return $realip;
		}

		if (isset($_SERVER))
		{
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

				/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
				foreach ($arr AS $ip)
				{
					$ip = trim($ip);

					if ($ip != 'unknown')
					{
						$realip = $ip;

						break;
					}
				}
			}
			elseif (isset($_SERVER['HTTP_CLIENT_IP']))
			{
				$realip = $_SERVER['HTTP_CLIENT_IP'];
			}
			else
			{
				if (isset($_SERVER['REMOTE_ADDR']))
				{
					$realip = $_SERVER['REMOTE_ADDR'];
				}
				else
				{
					$realip = '0.0.0.0';
				}
			}
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif (getenv('HTTP_CLIENT_IP'))
			{
				$realip = getenv('HTTP_CLIENT_IP');
			}
			else
			{
				$realip = getenv('REMOTE_ADDR');
			}
		}

		preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
		$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

		return $realip;
	}
}

/**
 * api 加密接口，轻量级加密算法
 */
function erp_api_encrypt($string) {

	$encryptKey = md5('TPs1#)8!6');
	$keyLen = strlen($encryptKey);

	$data = substr(md5($string.$encryptKey), 0, 8).$string;
	$dataLen = strlen($data);

	$rndkey = array();
	$box = array();
	$cipherText = "";

	for ($i = 0; $i < 256; $i++) {
		$rndkey[$i] = ord($encryptKey[$i % $keyLen]);
		$box[$i] = $i;
	}

	for ($i = 0, $j = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($i = 0, $j = 0, $k = 0; $i < $dataLen; $i++) {
		$k = ($k + 1) % 256;
		$j = ($j + $box[$k]) % 256;
		$tmp = $box[$k];
		$box[$k] = $box[$j];
		$box[$j] = $tmp;
		$cipherText .= chr(ord($data[$i]) ^ ($box[($box[$k] + $box[$j]) % 256]));
	}

	return str_replace('=', '', base64_encode($cipherText));
}

/**
 * api 解密接口，轻量级解密算法
 */
function erp_api_decrypt($cipherText) {

	$encryptKey = md5('TPs1#)8!6');
	$keyLen = strlen($encryptKey);

	$cipherText = base64_decode($cipherText);
	$textLen = strlen($cipherText);

	$rndkey = array();
	$box = array();
	$decryptText = "";

	for ($i = 0; $i < 256; $i++) {
		$rndkey[$i] = ord($encryptKey[$i % $keyLen]);
		$box[$i] = $i;
	}

	for ($i = 0, $j = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($i = 0, $j = 0, $k = 0; $i < $textLen; $i++) {
		$k = ($k + 1) % 256;
		$j = ($j + $box[$k]) % 256;
		$tmp = $box[$k];
		$box[$k] = $box[$j];
		$box[$j] = $tmp;
		$decryptText .= chr(ord($cipherText[$i]) ^ ($box[($box[$k] + $box[$j]) % 256]));
	}

	if (substr($decryptText, 0, 8) == substr(md5(substr($decryptText, 8).$encryptKey), 0, 8)) {
		return substr($decryptText, 8);
	} else {
		return false;
	}
}

/**
 * 调用 ERP 接口
 */
function erp_api_query($url, $param) {

	$iphost = config_item('api_erp_iphost');
	$lanhost = config_item('api_erp_lanhost');

	// ERP 接口密钥
	$signKey = 'TPs1#)8!6';

	// 生成有效签名，需要过滤掉指定字符
	$signData = array(
		'url' => $url,
		'param' => $param,
	);
	$filterArr = array(
		// 不同系统换行符不一致（win: CRLF, unix: LF, mac: CR），经过调用后都转成 LF，所以全部过滤掉
		"\n",
		"\r",
	);
	$clearSign = substr_replace(serialize($signData), $filterArr, "");
	$param['sign'] = hash_hmac('md5', $clearSign, $signKey);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "{$iphost}/{$url}");

	// 局域网 URL 使用 IP，再在 header 中设置 host
	if (!empty($lanhost)) {
		$host = array("Host:{$lanhost}");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $host);
	}

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

	$output = curl_exec($ch);
	curl_close($ch);

	$apiRet = json_decode(erp_api_decrypt($output), true);
	if (is_null($apiRet)) {
		return $output;
	} else {
		return $apiRet;
	}
}

/**
 * 调用 外代ERP 接口
 */
function wd_erp_api_query($param,$service) {
        $url=config_item('api_erp_customs_url');//地址
        $appSecret=config_item('api_erp_customs_secret');//密钥
    
        $body['service']=$service;//服务名称
	$body['appkey'] = config_item('api_erp_customs_appkey');//appkey接入时申请
        $body['secret'] = $appSecret;//签名
        $body['encrypt']='0';//是否加密（0/1）
        $body['content']=json_encode($param);
        $body['format']='json';
        $body['result']='json';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$url");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
	$output = curl_exec($ch);
	curl_close($ch);
        $apiRet=  json_decode($output);
	if ($apiRet->isSuccess) {
		return TRUE;
	} else {
		return $apiRet->error;
	}
}

function generate_code($length = 6) {
	//return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
	return rand(1001,9999);
}

/**
 * @author brady.wang
 * @desc 给用户发送手机验证码
 * @param $number 验证码位数
 * @param int $type 默认类型 1：全数字  2：数字字母 3：全字母
 * @return array
 */
function generate_verify_code($number = 4,$type='1')
{
	$arr = range(0, 9);
	return $str = substr(str_shuffle(join('', $arr)), 0, $number);
}

function aliapy_withdrawal_fee($cash){
	$withdrawal_fee  = $cash * 0.005;
	$withdrawal_fee = $withdrawal_fee <= 5 ? $withdrawal_fee : 5;
	$withdrawal_fee = $withdrawal_fee <0.1 ? 0.1 : $withdrawal_fee;
	$withdrawal_fee = round($withdrawal_fee, 2);
	$actual_fee = $cash - $withdrawal_fee;
	return array('actual_fee'=>$actual_fee,'withdrawal_fee'=>$withdrawal_fee);
}

/**
 * 二维数组排序
 */
function sortArrByField(&$array, $field, $desc = false){
	$fieldArr = array();
	foreach ($array as $k => $v) {
		$fieldArr[$k] = $v[$field];
	}
	$sort = $desc == false ? SORT_ASC : SORT_DESC;
	array_multisort($fieldArr, $sort, $array);
}

if (!function_exists('dump')) {
	function dump($arr)
	{
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}
}

/**
 * leon
 * 返回字符串长度
 */
if(!function_exists('get_str_lenght')){
    function get_str_lenght($str = ''){
        
        $str = trim($str);
        if ($str == '') {
            return 0;
        }
        $len = 0;
        preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $str, $arr1);
        preg_match_all("/\w+/", $str, $arr2);
        if ($arr1[0]) {
            foreach ($arr1[0] as $k => $v) {
                $len = $len + (mb_strlen($v)*2);
            }
        }
        if ($arr2[0]) {
            foreach ($arr2[0] as $k => $v) {
                $len = $len + mb_strlen($v);
            }
        }
        return $len;
        
    }
}
/**
 * leon
 * 获取中英文字符串的长度
 */
if(!function_exists('get_str_lenght_utf8')){
    function get_str_lenght_utf8($string = ''){
        $str = trim($string);
        preg_match_all('/./us', $str, $match);
        $str_count = count($match[0]);
        return $str_count;
    }
}

/**
 * 手机号检测
 * leon
 */
if(!function_exists('is_phone')){
	function is_phone($str){
		$is_bool = preg_match('/^1[34578]{1}\d{9}$/',$str);//是手机 返回true
		return $is_bool;
	}
}

/**
 * 生成随机数
 */
if(!function_exists('generate_code_new')){
    function generate_code_new($length = 6) {
        //return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        return mt_rand(1111,9999);
    }
}
/**
 * @authro brady
 * @desc 根据给的日期，返回当前月的开始和结束
 */
if(!function_exists('get_month_first_end')){
	function get_month_first_end($month)
	{
		$Y = substr($month,0,4);
		$m = substr($month,4,2);
		$new_date = date($Y."-".$m);
		$next_month = date("Y-m",strtotime($new_date." +1 month"));
		return array("start"=>$new_date,"end"=>$next_month);
	}

}
/**
 * 邮箱检测
 * leon
 */
if(!function_exists('is_email')){
	function is_email($str){
		$is_bool = preg_match('/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/', $str);
		return $is_bool;
	}
}

/**
 * 不好调试时候写入文件进行调试
 */
if(!function_exists('debug_log')){
	function debug_log($str){
		$time = date("Y-m-d H:i:s");
		file_put_contents('debug_log.txt',$time." : "."$str"."\r\n",FILE_APPEND);
	}
}
/**
 * 输出
 */
if(!function_exists('fout')){
    
    function fout($txt){
        if(is_array($txt)){
            echo "<pre>";print_r($txt);
        }else{
            echo $txt;
        }
    }
}

/**
 * 校验表单是否重复提交
 * @author: derrick
 * @date: 2017年3月30日
 * @param: @param string $user_identity 用户唯一标识
 * @param: @param string $action 表单动作
 * @param: @param Array $form_data 表单数据, 只有表单数据一致 ,才认为是同一个表单
 * @param: @param int $valid_time 有效时间, 秒
 * @reurn: return_type
 */
if (!function_exists('check_form_is_repeat_submit')) {
	function check_form_is_repeat_submit($user_identity, $action, $form_data, $valid_time = 15) {
		$redis = new Redis_Class();
 		if (is_array($form_data)) {
 			$form_data = json_encode($form_data);
 		}
 		$key = md5($user_identity.$action.$form_data);
 		$incr_val = $redis->redis_incr($key);
 		if (is_null($incr_val)) {
 			//当redis无法使用时, 直接略过防重验证
 			return true;
 		}
		if ($incr_val == 1) {
			$redis->redis_expire($key, $valid_time);
			return true;
		}
		return false;
	}
}
/**
* @字符串全角转化半角
*/
if(!function_exists('Sbc2Dbc')){
    function Sbc2Dbc($str) { 
        $arr = array( 
        '０'=>'0', '１'=>'1', '２'=>'2', '３'=>'3', '４'=>'4','５'=>'5', '６'=>'6', '７'=>'7', '８'=>'8', '９'=>'9', 
        'Ａ'=>'A', 'Ｂ'=>'B', 'Ｃ'=>'C', 'Ｄ'=>'D', 'Ｅ'=>'E','Ｆ'=>'F', 'Ｇ'=>'G', 'Ｈ'=>'H', 'Ｉ'=>'I', 'Ｊ'=>'J', 
        'Ｋ'=>'K', 'Ｌ'=>'L', 'Ｍ'=>'M', 'Ｎ'=>'N', 'Ｏ'=>'O','Ｐ'=>'P', 'Ｑ'=>'Q', 'Ｒ'=>'R', 'Ｓ'=>'S', 'Ｔ'=>'T', 
        'Ｕ'=>'U', 'Ｖ'=>'V', 'Ｗ'=>'W', 'Ｘ'=>'X', 'Ｙ'=>'Y','Ｚ'=>'Z', 'ａ'=>'a', 'ｂ'=>'b', 'ｃ'=>'c', 'ｄ'=>'d', 
        'ｅ'=>'e', 'ｆ'=>'f', 'ｇ'=>'g', 'ｈ'=>'h', 'ｉ'=>'i','ｊ'=>'j', 'ｋ'=>'k', 'ｌ'=>'l', 'ｍ'=>'m', 'ｎ'=>'n', 
        'ｏ'=>'o', 'ｐ'=>'p', 'ｑ'=>'q', 'ｒ'=>'r', 'ｓ'=>'s', 'ｔ'=>'t', 'ｕ'=>'u', 'ｖ'=>'v', 'ｗ'=>'w', 'ｘ'=>'x', 
        'ｙ'=>'y', 'ｚ'=>'z', 
        '（'=>'(', '）'=>')', '〔'=>'(', '〕'=>')', '【'=>'[','】'=>']', '〖'=>'[', '〗'=>']', '“'=>'"', '”'=>'"', 
        '‘'=>'\'', '\''=>'\'', '｛'=>'{', '｝'=>'}', '《'=>'<','》'=>'>','％'=>'%', '＋'=>'+', '—'=>'-', '－'=>'-', 
        '～'=>'~','：'=>':', '。'=>'.', '、'=>',', '，'=>',', '、'=>',', '；'=>';', '？'=>'?', '！'=>'!', '…'=>'-', 
        '‖'=>'|', '”'=>'"', '\''=>'`', '‘'=>'`', '｜'=>'|', '〃'=>'"','　'=>' ', '×'=>'*', '￣'=>'~', '．'=>'.', '＊'=>'*', 
        '＆'=>'&','＜'=>'<', '＞'=>'>', '＄'=>'$', '＠'=>'@', '＾'=>'^', '＿'=>'_', '＂'=>'"', '￥'=>'$', '＝'=>'=', 
        '＼'=>'\\', '／'=>'/' 
        ); 
        return strtr($str, $arr); 
    } 
}

/**
 * 自定义日志
 * @author: derrick
 * @date: 2017年4月1日
 * @param: @param String $log_content 日志内容
 * @param: @param string $foler_name 保存文件夹名称
 * @param: @param string $log_type 日志类型 notice, error.. etc, 可自定义
 * @param: @return boolean
 * @reurn: boolean
 */
if (!function_exists('write_custom_log')) {
	function write_custom_log($log_content, $foler_name = 'new_order_trigger_queue', $log_type = 'notice') {
		$filepath = APPPATH.'logs/'.date('ymd').'/'.$foler_name.'/';
		$message  = '['.$log_type.']'.date('Y-m-d H:i:s').':';

		if ( ! file_exists($filepath)) {
			mkdirs($filepath, 777);
		}

		$filepath .= 'log'.date('H').'.log';
		if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
			return FALSE;
		}

		if (is_array($log_content)) {
			$message .= var_export($log_content, true)."\n";
		} else {
			$message .= $log_content."\n";
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, FILE_WRITE_MODE);
		return TRUE;
	}
}

/**
 * 递归创建文件夹
 * @author: derrick
 * @date: 2017年4月1日
 * @param: @param String $path 文件目录
 * @param: @param string $mode 权限
 * @param: @return boolean
 * @reurn: boolean
 */
if (!function_exists('mkdirs')) {
	function mkdirs($path, $mode = '0777') {
		if (file_exists ( $path )) {
			return true;
		}
		if (file_exists ( dirname ( $path ) )) {
			// 父目录已经存在，直接创建
			return mkdir ( $path );
		}
		// 从子目录往上创建
		mkdirs ( dirname ( $path ) );
		return @mkdir ( $path );
	}
}

/**
 * 从一个数组中提取一个字段, 并以数组返回
 *
 * @param array $array
 *        	源数组
 * @param string $column
 *        	需要提取的字段key
 *        	@demo
 *        	UtilsHelper::extractColumnFromArray($array, 'id');
 *        	input:
 *        	array(
 *        	array('id' => 1, 'name' => 'A', 'sname' => 'a'),
 *        	array('id' => 2, 'name' => 'B', 'sname' => 'b'),
 *        	array('id' => 3, 'name' => 'C', 'sname' => 'c'),
 *        	array('id' => 4, 'name' => 'D', 'sname' => 'd'),
 *        	)
 *        	return:
 *        	array(1,2,3,4)
 * @author derrick
 */
if (!function_exists('extract_column_from_array')) {
	function extract_column_from_array($array, $column) {
		if (! function_exists ( 'array_column' )) {
			function array_column($arr, $key) {
				return array_map ( function ($val) use($key) {
					return is_object($val) ? $val->{$key} : $val [$key];
				}, $arr );
			}
		}
			$data = array_column ( $array, $column );
		return $data;
	}
}

/**
 * 权限管理公共函数,取得特定功能的权限配置信息
 * @$rightKey 配置权限的key 全局不能重复
 * @$defaultRight 是否默认拥有权限的人 默认1为超级权限
 * @return boolean
 * @author andy
 */
if (!function_exists('check_right')) {
	function check_right($rightKey,$defaultRight=[]) {

		$config_right = config_item('config_right');//array['key']
		$admin_id	  = config_item('config_right_admin_id');

		if(isset($config_right[$rightKey])){

			$right = $defaultRight ? array_merge($config_right[$rightKey],$defaultRight) : $config_right[$rightKey];

		}else{

			$CI =&get_instance();
			$CI->load->model('tb_admin_right');
			$CI->tb_admin_right->autoAddRight($rightKey);

			$right = $defaultRight ? $defaultRight : array();

		}

		//array_push($right,1);

		if(in_array($admin_id,$right))
		{

			return TRUE;

		}else{

			return FALSE;

		}

	}
}

/**
 * 返回某个时间段中的时间
 * @param $startdate 开始时间
 * @param $enddate 结束时间
 * @return array
 */
if (!function_exists('get_day')) {
    function get_day($startdate, $enddate)
    {
        $day_data = array();
        if (!empty($startdate) && !empty($enddate)) {
            //先把两个日期转为时间戳
            $startdate = strtotime($startdate);
            $enddate = strtotime($enddate);
            for ($i = $startdate, $index = 0; $i <= $enddate; $i += (24 * 3600), $index++) {
                $day_data[$index] = date("Ymd", $i);
            }
        }
        return $day_data;
    }
}

/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */





