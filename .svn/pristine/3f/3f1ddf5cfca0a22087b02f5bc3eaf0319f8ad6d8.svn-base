<?php

class m_currency extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	public function exchangeToUSD($sum,$currency = 'CNY'){
//        $res = $this->db->select('rate')->from('exchange_rate')->where('currency', $currency)->get()->row_object();
//        if($res){
//            return sprintf("%.2f",$sum/$res->rate);
//        }
        $this->load->model("tb_exchange_rate");
        $res = $this->tb_exchange_rate->get_one_auto(["select"=>"rate","where"=>["currency"=>$currency]]);
        if($res)
        {
            return sprintf("%.2f",$sum/$res["rate"]);
        }
        return sprintf("%.2f",$sum);
    }

	/**
	 * 通过货币获取符号
	 */
	function get_icon_by_currency($currency)
	{
        $this->load->model("tb_exchange_rate");
//		$res =  $this->db->select('icon')->where('currency', $currency)->get('exchange_rate')->row_array();
        $res =  $this->tb_exchange_rate->get_one_auto(["select"=>"icon","where"=>["currency"=>$currency]]);
        return $res;
	}

	/** 匯率的轉換 月費充值，升級月費使用到 array by john */
	function price_format_array($price, $currency_format = 'USD')
	{
		$price = $price ? number_format($price, 2, '.', '') : 0;
//		$res = $this->db->select('rate, icon')->from('exchange_rate')->where('currency', $currency_format)->get()->row_object();
//		$data['format_money'] = sprintf($res->icon.'%.2f', $price*$res->rate);
//		$data['money'] = sprintf('%.2f', $price*$res->rate);
        $this->load->model("tb_exchange_rate");
        $res = $this->tb_exchange_rate->get_one_auto(["select"=>"rate, icon","where"=>["currency"=>$currency_format]]);
        $data['format_money'] = sprintf($res["icon"].'%.2f', $price*$res["rate"]);
		$data['money'] = sprintf('%.2f', $price*$res["rate"]);

		$data['usd_money'] = $price;

		return $data;
	}

	/** 匯率的轉換 and 分-->元 格式化貨幣金額  商城使用 by john */
	function price_format($price, $currency_format = 'USD')
	{
		$price = $price ? number_format($price, 2, '.', '') : 0;
//		$res = $this->db->select('rate, icon')->from('exchange_rate')->where('currency', $currency_format)->get()->row_object();
//		if (!$res)
//		{
//			return  $res->icon.number_format($price / 100,2);
//		}
//        return $res->icon.number_format($price*$res->rate / 100,2);

        $this->load->model("tb_exchange_rate");
        $res = $this->tb_exchange_rate->get_one_auto(["select"=>"rate, icon","where"=>["currency"=>$currency_format]]);
		if (!$res)
		{
			return  $res["icon"].number_format($price / 100,2);
		}
        return $res["icon"].number_format($price*$res["rate"] / 100,2);
    }


	/** 匯率的轉換 and 分-->元 */
	function currency_conversion($price, $currency_format)
	{
		$price = $price ? number_format($price, 2, '.', '') : 0;
//		$res = $this->db->select('rate, icon')->from('exchange_rate')->where('currency', $currency_format)->get()->row_object();
//		if (!$res)
//		{
//			return $price;
//		}
//		return sprintf('%.2f', $price*$res->rate / 100);
        $this->load->model("tb_exchange_rate");
        $res = $this->tb_exchange_rate->get_one_auto(["select"=>"rate, icon","where"=>["currency"=>$currency_format]]);
        if (!$res)
        {
            return $price;
        }
        return sprintf('%.2f', $price*$res["rate"] / 100);
	}

	/** 系統初始化使用 */
	public function createCurrency()
	{
		$data = array('currency' => 'CNY', 'rate' => '6.386400');
		$data1 = array('currency' => 'USD', 'rate' => '1');
		$data2 = array('currency' => 'HKD', 'rate' => '7.753700');
		$this->db->replace('exchange_rate', $data);
		$this->db->replace('exchange_rate', $data1);
		$this->db->replace('exchange_rate', $data2);
	}

	/** 每天更新一次匯率 */
	public function updateRate($data, $currency)
	{
	    $this->load->model("tb_exchange_rate");
		$update['rate'] = $data['rate'];
		if ($update['rate'] /*&& is_float($update['rate'])*/)
		{
//			$this->db->where('currency', $currency)->update('exchange_rate', $update);
            $this->tb_exchange_rate->update_one_auto(["where"=>['currency'=>$currency],"data"=>$update]);

			// ERP API: 修改汇率的同时需要更新 ERP 的汇率表
			$param = array(
				'currency' => $currency,
				'exchange_rate' => $update['rate'],
			);
			erp_api_query('Home/Api/updateExchageRate', $param);
		}
	}

	/**
	 * @每天记录汇率的历史
	 * @author andy
	 * @date 20170620
	 */
	public function addRateHistoryEveryday(){

		$this->load->model("tb_exchange_rate");

		$this->tb_exchange_rate->addRateHistory();

	}


	/** 当调用汇率接口失败后发送邮件
	 * @param $currency
	 * @author andy
	 * @date 20170620
	 */
	public function addRateSendMail($currency,$errorContent){

		$to 	= 'hzl.huang@shoptps.com;derrick.zhang@shoptps.com;haiya.hai@shoptps.com';
		$title 	= '汇率接口调用警告信息';
		$body  	= "汇率接口调用 [{$currency}] 获取数据失败，调用接口时间：".date('Y-m-d H:i:s').'<br>'.'错误信息：'.$errorContent;

		send_mail($to,$title,$body,$bccs=array(),$attach='');
	}
}
