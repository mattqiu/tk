<?php
header("Content-type: text/html; charset=utf-8");
/**
 * @类名：ysepay_service
 * @功能：银盛支付订单支付接口构造类
 * @详细：构造银盛支付各接口请求报文
 * @日期：2014-10-30
 * @author 蒋波<alber_bob@hotmail.com>
 * @copyright   Copyright(C) 2014 深圳银盛电子支付科技有限公司
 * @version 1.0
 * @说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究银盛支付接口使用，只是提供一个参考。
 */
class ysepay_service {
	/**
	 * ***************配置部分（带#号部分商户需根据实际情况修改）*****************
	 *#usercode 商户号
	 *#merchantname 商户名
	 *#pfxpath 商户私钥证书路径(发送交易签名使用)
	 *#businessgatecerpath 银盛支付公钥证书路径(接收到银盛支付回执时验签使用)
	 *#pfxpassword 商户私钥证书密码
	 *#noticepg_url 前台通知地址:商户系统提供，支付成功跳转商户体统，为空不跳转。银盛支付平台在此URL后追加固定的参数向商户系统跳转:Msg=“订单号|金额（单位：分），然后对Msg做Base64编码”;Check=“Msg的签名后，再进行Base64”
	 *#noticebg_url 后台通知地址:商户系统提供，支付成功后，银盛支付平台返回R3501报文
	 *#host 银盛支付url
	 * xmlpage_url 页面接口类银盛支付网关地址
	 * xmlbackmsg_url 后台接口类银盛支付网关地址
	 * filemsg_url 文件接口类银盛支付网关地址
	 */
	function ysepay_service($config)
	{
		$this->param = array ();
		$this->param['usercode']            = $config['yspay_username'];
		$this->param['merchantname']		= $config['yspay_merchantname'];
		$this->param['pfxpath']             = APPPATH . "third_party/yspay/key/" . $config['yspay_pfxpath'];
		$this->param['businessgatecerpath'] = APPPATH . "third_party/yspay/key/" . $config['yspay_certpath'];
		$this->param['pfxpassword']         = $config['yspay_pfxpassword'];
		$this->param['noticepg_url']        = base_url('respond/do_return?code='.base64_encode(serialize('m_yspay')));
		$this->param['noticebg_url']        = base_url('respond/do_notify?code='.base64_encode(serialize('m_yspay')));
 		//$this->param['host']                = "113.106.160.201:889";//生产环境需更换为：pay.ysepay.com
 		$this->param['host']                = $config['yspay_host'];//生产环境需更换为：pay.ysepay.com
		$this->param['xmlpage_url']      		= $this->param['host']."/businessgate/yspay.do";
		$this->param['xmlbackmsg_url']      = $this->param['host']."/businessgate/xmlbackmsg.do";
		$this->param['filemsg_url']      		= $this->param['host']."/businessgate/filemsg.do";
	}

	/**
	 * 即时到帐
	 * @功能：构造S3001报文，返回包含请求报文的url的数组
	 * @param array $input
	 * @return success
	 * @return msg
	 * @return url
	 */
	function S3001_ysepay($input){
		$MsgCode          = "S3001";
		$orderid          = $input['orderid'];
		
		//组参数
		$param=array(
		"Ver"=>"1.0",
		"MsgCode"=>$MsgCode,
		"datetime_string"=>self::datetime2string(date('YmdHis')),
		"busicode"=>$input['busicode'],
		"orderid"=>$orderid,
		"cur"=>"USD",
		"date"=>date('Ymd',strtotime($input['date'])),
		"amount_units_fen"=>(double)$input['amount'],
		"note"=>$input['ordernote'],
		"timeout"=>60*24*7,
		"banktype"=>$input['banktype'],
		"bankaccounttype"=>$input['bankaccounttype']
		);
		
		//组报文
		$xml = self::buildxml_S3001($param);
		
		//加密
		$sign_encrypt = self::sign_encrypt(array('data'=>$xml));
		
		//组跳转url
		$url = 'http://'.$this->param['xmlpage_url']."?".http_build_query(array(
			'src'     => $this->param['usercode'],
			'msgCode' => $MsgCode,
			'msgId'   => sprintf("%014d",$orderid),
			'check'   => $sign_encrypt['check'],
			'msg'     => $sign_encrypt['msg'],
		));
		
		$return = array('success'=>0,'msg'=>'内部错误，请与客服联系！');
		
		if(strlen($url)){
			$return['success'] = 1;
			$return['url']     = $url;
			$return['msg']     = "获取成功";
		}
		
		return $return;
	}
	
	/**
	 * 即时到账
	 * 后台报文方式
	 * @功能：构造S3101报文，返回包含请求报文的数据的数组
	 * @param array $input
	 * @return success
	 * @return msg
	 * @return data
	 */
	function S3101_ysepay($input){
		$MsgCode          = "S3101";
		$orderid          = $input['orderid'];
		
		$param=array(
		"Ver"=>"1.0",
		"MsgCode"=>$MsgCode,
		"datetime_string"=>self::datetime2string(date('YmdHis')),
		"busicode"=>$input['busicode'],
		"orderid"=>$orderid,
		"cur"=>"CNY",
		"date"=>date('Ymd',strtotime($input['date'])),
		"amount_units_fen"=>(double)$input['amount'],
		"note"=>$input['ordernote'],
		"timeout"=>60*24*7,
		"usercode"=>$input['usercode'],
		"name"=>$input['name']
		);
		
		$xml = self::buildxml_S3101($param);
		
		$sign_encrypt = self::sign_encrypt(array('data'=>$xml));
		
		$data = http_build_query(array(
			'src'     => $this->param['usercode'],
			'msgCode' => $MsgCode,
			'msgId'   => sprintf("%014d",$orderid),
			'check'   => $sign_encrypt['check'],
			'msg'     => $sign_encrypt['msg']
		));

		$return = array('success'=>0,'msg'=>'内部错误，请与客服联系！');

		if(strlen($data)){
			$return['success'] = 1;
			$return['data']     = $data;
			$return['msg']     = "获取成功";
		}

		return $return;
	}
	
	/**
	 * 支付单笔查询
	 * @功能：构造S5003报文，返回包含请求报文的数据的数组
	 * @param array $input
	 * @return success
	 * @return msg
	 * @return data
	 */
	function S5003_ysepay($input){
		$MsgCode          = "S5003";
		$orderid          = $input['orderid'];
		$tradesn          = $input['tradesn'];
		
		$param=array(
		"Ver"=>"1.0",
		"MsgCode"=>$MsgCode,
		"datetime_string"=>self::datetime2string(date('YmdHis')),
		"orderid"=>$orderid,
		"tradesn"=>$tradesn
		);
		
		$xml = self::buildxml_S5003($param);
		
		$sign_encrypt = self::sign_encrypt(array('data'=>$xml));
		
		$data = http_build_query(array(
			'src'     => $this->param['usercode'],
			'msgCode' => $MsgCode,
			'msgId'   => sprintf("%014d",$orderid),
			'check'   => $sign_encrypt['check'],
			'msg'     => $sign_encrypt['msg']
		));

		$return = array('success'=>0,'msg'=>'内部错误，请与客服联系！');

		if(strlen($data)){
			$return['success'] = 1;
			$return['data']     = $data;
			$return['msg']     = "获取成功";
		}

		return $return;
	}
	
	/**
	 * 退款
	 * @功能：构造S3107报文，返回包含请求报文的数据的数组
	 * @param array $input
	 * @return success
	 * @return msg
	 * @return data
	 */
	function S3107_ysepay($input){
		$MsgCode          = "S3107";
		$refundorderid    = $input['refundorderid'];
		
		$param=array(
		"Ver"=>"1.0",
		"MsgCode"=>$MsgCode,
		"datetime_string"=>self::datetime2string(date('YmdHis')),
		"refundorderid"=>$input['refundorderid'],
		"reason"=>$input['reason'],
		"refundamount_units_fen"=>(double)$input['refundamount'],
		"orderid"=>$input['orderid'],
		"cur"=>$input['cur'],
		"amount_units_fen"=>(double)$input['amount']
		);
		
		$xml = self::buildxml_S3107($param);
		
		$sign_encrypt = self::sign_encrypt(array('data'=>$xml));
		
		$data = http_build_query(array(
			'src'     => $this->param['usercode'],
			'msgCode' => $MsgCode,
			'msgId'   => sprintf("%014d",$refundorderid),
			'check'   => $sign_encrypt['check'],
			'msg'     => $sign_encrypt['msg']
		));

		$return = array('success'=>0,'msg'=>'内部错误，请与客服联系！');

		if(strlen($data)){
			$return['success'] = 1;
			$return['data']     = $data;
			$return['msg']     = "获取成功";
		}

		return $return;
	}
	
	/**
	 * 退款查询
	 * @功能：构造S5107报文，返回包含请求报文的数据的数组
	 * @param array $input
	 * @return success
	 * @return msg
	 * @return data
	 */
	function S5107_ysepay($input){
		$MsgCode          = "S5107";
		$refundorderid    = $input['refundorderid'];
		
		$param=array(
		"Ver"=>"1.0",
		"MsgCode"=>$MsgCode,
		"datetime_string"=>self::datetime2string(date('YmdHis')),
		"refundorderid"=>$input['refundorderid'],
		"refunddate"=>$input['refunddate'],
		"tradesn"=>$input['tradesn'],
		);
		
		$xml = self::buildxml_S5107($param);
		
		$sign_encrypt = self::sign_encrypt(array('data'=>$xml));
		
		$data = http_build_query(array(
			'src'     => $this->param['usercode'],
			'msgCode' => $MsgCode,
			'msgId'   => sprintf("%014d",$refundorderid),
			'check'   => $sign_encrypt['check'],
			'msg'     => $sign_encrypt['msg']
		));

		$return = array('success'=>0,'msg'=>'内部错误，请与客服联系！');

		if(strlen($data)){
			$return['success'] = 1;
			$return['data']     = $data;
			$return['msg']     = "获取成功";
		}

		return $return;
	}
	
	/**
	 * 对账
	 * @功能：构造S7001报文，返回包含请求报文的数据的数组
	 * @param array $input
	 * @return success
	 * @return msg
	 * @return data
	 */
	function S7001_ysepay($input){
		$MsgCode          = "S7001";
		$refundorderid    = $input['refundorderid'];
		
		$param=array(
		"Ver"=>"1.0",
		"MsgCode"=>$MsgCode,
		"datetime_string"=>self::datetime2string(date('YmdHis')),
		"accountdate"=>$input['accountdate'],
		);
		
		$xml = self::buildxml_S7001($param);
		
		$sign_encrypt = self::sign_encrypt(array('data'=>$xml));
		
		$data = http_build_query(array(
			'src'     => $this->param['usercode'],
			'msgCode' => $MsgCode,
			'msgId'   => sprintf("%014d",$param[datetime_string].'-'.rand(1000,9999)),
			'check'   => $sign_encrypt['check'],
			'msg'     => $sign_encrypt['msg']
		));

		$return = array('success'=>0,'msg'=>'内部错误，请与客服联系！');

		if(strlen($data)){
			$return['success'] = 1;
			$return['data']     = $data;
			$return['msg']     = "获取成功";
		}

		return $return;
	}
	
	
	/**
	 *日期转字符
	 *输入参数：yyyy-MM-dd HH:mm:ss
	 *输出参数：yyyyMMddHHmmss
	 */
	function datetime2string($datetime){
		return preg_replace('/\-*\:*\s*/','',$datetime);
	}

	/**
	 * 验签转明码
	 * @param input check
	 * @param input msg
	 * @return data
	 * @return success
	 */
	function unsign_crypt($input){
		$check  = $input['check'];
		$msg    = $input['msg'];

		$return = array('success'=>0,'msg'=>'','check'=>'');
		
		$publickeyFile = $this->param['businessgatecerpath']; //公钥
		$certificateCAcerContent = file_get_contents($publickeyFile);
		$certificateCApemContent =  '-----BEGIN CERTIFICATE-----'.PHP_EOL.chunk_split(base64_encode($certificateCAcerContent), 64, PHP_EOL).'-----END CERTIFICATE-----'.PHP_EOL;
		$success = openssl_public_decrypt (base64_decode($check),$finaltext,openssl_get_publickey($certificateCApemContent));
		
		$return = array('data'=>'','success'=>0);
		if($success){
			$return = array(
				'data'    => base64_decode($msg),
				'success' => 1 ,
			);
		}
		return $return;
	}

	/**
	 * 签名加密
	 * @param input data
	 * @return success
	 * @return check
	 * @return msg
	 */
	function sign_encrypt($input){
		$input['data'] = iconv("UTF-8","GBK//IGNORE",$input['data']);
		
		$return = array('success'=>0,'msg'=>'','check'=>'');
			
		$pkcs12 = file_get_contents($this->param['pfxpath']); //私钥
		if (openssl_pkcs12_read($pkcs12, $certs, $this->param['pfxpassword'])) {
			$privateKey = $certs['pkey']; 
			$publicKey  = $certs['cert'];
			
			$signedMsg = ""; 
			if (openssl_sign($input['data'], $signedMsg, $privateKey,OPENSSL_ALGO_MD5)) { 
				$return['success'] = 1;
				$return['check']   = sprintf('%-256s',base64_encode($signedMsg));
				$return['msg']     = base64_encode($input['data']);
			}
		}
		
		return $return;
	}
	
	
	/**
		*构造S3001报文
		*
		*/
	function buildxml_S3001($param, $xml=null){
		$xml =
		'<?xml version="1.0" encoding="GBK"?>
			<yspay>
				<head>
					<Ver>'.$param['Ver'].'</Ver>
					<Src>'.$this->param['usercode'].'</Src>
					<MsgCode>'.$param['MsgCode'].'</MsgCode>
					<Time>'.$param['datetime_string'].'</Time>
				</head>
				<body>
					<Order>
						<OrderId>'.$param['orderid'].'</OrderId>
						<BusiCode>'.$param['busicode'].'</BusiCode>
						<ShopDate>'.$param['date'].'</ShopDate>
						<Cur>'.$param['cur'].'</Cur>
						<Amount>'.$param['amount_units_fen'].'</Amount>
						<Note>'.$param["note"].'</Note>
						<Timeout>'.$param['timeout'].'</Timeout>
						<BankType>'.$param['banktype'].'</BankType>
						<BankAccountType>'.$param['bankaccounttype'].'</BankAccountType>
					</Order>
					<Payee>
						<UserCode>'.$this->param['usercode'].'</UserCode>
						<Name>'.$this->param['merchantname'].'</Name>
						<Amount>'.$param['amount_units_fen'].'</Amount>
					</Payee>
					<Notice>
						<PgUrl>'.$this->param['noticepg_url'].'</PgUrl>
						<BgUrl>'.$this->param['noticebg_url'].'</BgUrl>
					</Notice>
				</body>
			</yspay>
		';
		//$xml = self::buildxml_head($xml,$param);
		//$xml = self::buildxml_body($xml,$param);
		return $xml;
	}
	
	/**
		*构造S3101报文
		*
		*/
	function buildxml_S3101($param, $xml=null){
		$xml =
		'<?xml version="1.0" encoding="GBK"?>
			<yspay>
				<head>
					<Ver>'.$param['Ver'].'</Ver>
					<Src>'.$this->param['usercode'].'</Src>
					<MsgCode>'.$param['MsgCode'].'</MsgCode>
					<Time>'.$param['datetime_string'].'</Time>
				</head>
				<body>
					<Order>
						<OrderId>'.$param['orderid'].'</OrderId>
						<BusiCode>'.$param['busicode'].'</BusiCode>
						<ShopDate>'.$param['date'].'</ShopDate>
						<Cur>'.$param['cur'].'</Cur>
						<Amount>'.$param['amount_units_fen'].'</Amount>
						<Note>'.$param["note"].'</Note>
						<Timeout>'.$param['timeout'].'</Timeout>
					</Order>
					<Payee>
						<UserCode>'.$param['usercode'].'</UserCode>
						<Name>'.$param['name'].'</Name>
					</Payee>
					<Notice>
						<PgUrl>'.$this->param['noticepg_url'].'</PgUrl>
						<BgUrl>'.$this->param['noticebg_url'].'</BgUrl>
					</Notice>
				</body>
			</yspay>
		';
		return $xml;
	}
	
	/**
		*构造S5003报文
		*
		*/
	function buildxml_S5003($param, $xml=null){
		$xml =
		'<?xml version="1.0" encoding="GBK"?>
			<yspay>
				<head>
					<Ver>'.$param['Ver'].'</Ver>
					<Src>'.$this->param['usercode'].'</Src>
					<MsgCode>'.$param['MsgCode'].'</MsgCode>
					<Time>'.$param['datetime_string'].'</Time>
				</head>
				<body>
					<OrderId>'.$param['orderid'].'</OrderId>
					<TradeSN>'.$param['tradesn'].'</TradeSN>
				</body>
			</yspay>
		';
		return $xml;
	}
	
	/**
		*构造S3107报文
		*
		*/
	function buildxml_S3107($param, $xml=null){
		$xml =
		'<?xml version="1.0" encoding="GBK"?>
			<yspay>
				<head>
					<Ver>'.$param['Ver'].'</Ver>
					<Src>'.$this->param['usercode'].'</Src>
					<MsgCode>'.$param['MsgCode'].'</MsgCode>
					<Time>'.$param['datetime_string'].'</Time>
				</head>
				<body>
					<RefundOrder>
						<RefundOrderId>'.$param['refundorderid'].'</RefundOrderId>
						<Reason>'.$param['reason'].'</Reason>
						<RefundAmount>'.$param['refundamount_units_fen'].'</RefundAmount>
					</RefundOrder>
					<OrgOrder>
						<OrderId>'.$param['orderid'].'</OrderId>
						<Cur>'.$param['cur'].'</Cur>
						<Amount>'.$param['amount_units_fen'].'</Amount>
					</OrgOrder>
				</body>
			</yspay>
		';
		return $xml;
	}
	
	/**
		*构造S5107报文
		*
		*/
	function buildxml_S5107($param, $xml=null){
		$xml =
		'<?xml version="1.0" encoding="GBK"?>
			<yspay>
				<head>
					<Ver>'.$param['Ver'].'</Ver>
					<Src>'.$this->param['usercode'].'</Src>
					<MsgCode>'.$param['MsgCode'].'</MsgCode>
					<Time>'.$param['datetime_string'].'</Time>
				</head>
				<body>
					<RefundOrderId>'.$param['refundorderid'].'</RefundOrderId>
					<RefundDate>'.$param['refunddate'].'</RefundDate>
					<TradeSN>'.$param['tradesn'].'</TradeSN>
				</body>
			</yspay>
		';
		return $xml;
	}
	
	/**
		*构造S7001报文
		*
		*/
	function buildxml_S7001($param, $xml=null){
		$xml =
		'<?xml version="1.0" encoding="GBK"?>
			<yspay>
				<head>
					<Ver>'.$param['Ver'].'</Ver>
					<Src>'.$this->param['usercode'].'</Src>
					<MsgCode>'.$param['MsgCode'].'</MsgCode>
					<Time>'.$param['datetime_string'].'</Time>
				</head>
				<body>
					<AccountDate>'.$param['accountdate'].'</AccountDate>
				</body>
			</yspay>
		';
		return $xml;
	}
}
?>