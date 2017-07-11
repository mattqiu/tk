<?php
/**
 * @author john
 */
class tb_walmart_orders extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	/**
	 * 接收沃尔玛传递过来的订单信息。
	 * @param array $data = array (
	 *  'order_id' => '381',
	 *  'shopkeeper_id' => '1380102590',
	 *  'order_amount' => '26.99',
	 *  'order_profit' => '7',
	 *  'currency' => 'USD',
	 *)
	 * @return int error_code
	 */
	public function addWalmartOrders($data) {

		/*处理参数和验证参数合法性*/
		$data['order_id'] = isset($data['order_id'])?$data['order_id'] :'';
		$data['customer_id'] = isset($data['customer_id'])?(int)$data['customer_id']:0;
		$data['shopkeeper_id'] = isset($data['shopkeeper_id'])?(int)$data['shopkeeper_id']:0;
		$data['order_amount'] = isset($data['order_amount'])?tps_money_format($data['order_amount']):0.00;
		$data['order_profit'] = isset($data['order_profit'])?tps_money_format($data['order_profit']):0.00;
		$data['currency'] = (isset($data['currency']) && in_array($data['currency'],array('USD','CNY')))?$data['currency']:'';
		$data['affiliate'] = trim($data['affiliate']);

		if(!$data['shopkeeper_id'] || !$data['order_id'] || $data['order_amount']<0.01 || !$data['currency']){
			$this->m_log->createCronLog('订单触发动作失败！订单信息：'.var_export($data,1));
			return 101;
		}

		$userInfo = $this->db->select('user_rank')->where('id',$data['shopkeeper_id'])->get('users')->row_array();
		if(!$userInfo || $userInfo['user_rank'] == 4 ){ /** 如果店主不是付费会员，不发奖励 */
			$this->m_log->createCronLog('订单触发动作失败！订单信息：'.var_export($data,1));
			return 101;
		}


		/*去掉其他无用参数*/
		$fieldList = array('order_id','customer_id','shopkeeper_id','order_amount','order_profit','currency','order_from','affiliate');
		foreach($data as $k=>$v){
			if(!in_array($k, $fieldList)){
				unset($data[$k]);
			}
		}

		$this->load->model('m_currency');
		$data['order_profit_usd'] = $this->m_currency->exchangeToUSD($data['order_profit'], $data['currency']);
		$data['order_amount_usd'] = $this->m_currency->exchangeToUSD($data['order_amount'], $data['currency']);
		$data['score_year_month'] = date('Ym');

		//add by andy 添加订单订单总额的三分之一，在“Affiliate Program”所有商家购买的商品金额必须是3倍所有奖金制度零售订单金额的要求
		$data['order_amount_usd_one_third'] = ceil(($data['order_amount_usd']*100)/3);

		$res = $this->db->select('order_id,customer_id,shopkeeper_id,order_amount,order_profit,currency,order_from')->from('walmart_orders')->where('order_id',$data['order_id'])->get()->row_array();
		if(!$res){
			$data['order_id'] = (string)$data['order_id'];
			$this->db->trans_start();
			$this->db->insert('walmart_orders', $data);


			/*记录到新订单处理队列*/
            $this->load->model('tb_new_order_trigger_queue');
            //$this->tb_new_order_trigger_queue->addNewOrderToQueue($data['order_id'],$data['shopkeeper_id'],tps_int_format($data['order_amount_usd']*100),tps_int_format($data['order_profit_usd']*100));
			$this->tb_new_order_trigger_queue->addNewOrderToQueue($data['order_id'],$data['shopkeeper_id'],tps_int_format($data['order_amount_usd_one_third']),tps_int_format($data['order_profit_usd']*100));

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$this->m_log->createCronLog('订单触发动作失败！订单信息：'.var_export($data,1));
				return 103;
			}
		}elseif($data['order_amount']!=$res['order_amount'] || $data['order_profit']!=$res['order_profit'] || $data['customer_id']!=$res['customer_id'] || $data['shopkeeper_id']!=$res['shopkeeper_id'] || $data['currency']!=$res['currency'] || $data['order_from']!=$res['order_from']){
			if(!strpos($data['order_id'],'_')){
				$data['order_id'] = $data['order_id'].'_2';
			}else{
				$arr_order_id = explode('_', $data['order_id']);
				$data['order_id'] = $arr_order_id[0].'_'.($arr_order_id[1]+1);
			}
			$this->addWalmartOrders($data);
		}

		return 0;
	}

	public function getWalmartOrders($filter, $page = false, $perPage = 10) {
		$this->db_slave->from('walmart_orders');
		foreach ($filter as $k => $v) {
			if (!$v) {
				continue;
			}
			if ($k == 'start') {
				$this->db_slave->where('create_time >=', $v);
				continue;
			}
			if ($k == 'end') {
				$this->db_slave->where('create_time <=', $v);
				continue;
			}
			$this->db_slave->where($k, $v);
		}
		return $this->db_slave->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
	}

	/**
     * 更新订单的业绩年月
     */
    public function updateScore_year_month($order_id,$score_year_month){

        $this->db->where('order_id', $order_id)->set('score_year_month', $score_year_month)->update('walmart_orders');
    }

	public function getWalmartTotalRows($filter) {
		$this->db_slave->from('walmart_orders');
		foreach ($filter as $k => $v) {
			if (!$v) {
				continue;
			}
			if ($k == 'start') {
				$this->db_slave->where('create_time >=', $v);
				continue;
			}
			if ($k == 'end') {
				$this->db_slave->where('create_time <=', $v);
				continue;
			}
			$this->db_slave->where($k, $v);
		}
		return $this->db_slave->count_all_results();
	}

	/**
	 * 根据订单ID获取信息
	 * @author: derrick
	 * @date: 2017年4月14日
	 * @param: @param String $order_id 订单Id
	 * @reurn: return_type
	 */
	public function find_by_order_id($order_id, $field = '*') {
		$res = $this->db->select($field)->where('order_id', $order_id)->get('walmart_orders');
		if ($res) {
			return $res->row_array();
		}
		return array();
	}
}
