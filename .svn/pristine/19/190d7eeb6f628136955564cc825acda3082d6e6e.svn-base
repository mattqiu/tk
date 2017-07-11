<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class My_other_orders extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('M_order','m_order');
	}

	public function index($filed = ''){

		// 状态映射表
		$this->_viewData['status_map'] = array(
			0 => lang('admin_order_status_all'),
			Order_enum::STATUS_INIT => lang('admin_order_status_init'),
			Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
			Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
			Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
			Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
			Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
			Order_enum::STATUS_HOLDING =>lang('admin_order_status_holding'),
			Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
			Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
			Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
			Order_enum::STATUS_COMPONENT => lang('admin_order_status_component'),
		);

		$filed = 'customer_id';
		$this->_viewData['title'] = lang('my_orders');
        
         //获取会员所属国家ID
        $country_id =  $this->_userInfo['country_id'];
        if($country_id == 1) {
           $this->_viewData['country_id']  = 1;   //所属国家中国
        }

		$uid = $this->_userInfo['id'] ;
		$searchData = $this->input->get();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['order_input'] = isset($searchData['order_input'])? $searchData['order_input'] : '';
		$searchData['status'] = isset($searchData['status'])? $searchData['status'] : '';
		$searchData['month'] = isset($searchData['month'])? $searchData['month'] : '';

		$lists = $this->m_order->getOrders($searchData,$filed,$uid);

		$this->load->model('m_trade');
        $this->load->model('m_goods');
        
        $queue_arr = array();
		foreach($lists as &$list){

            $count = $this->m_log->check_order_in_queue($list['order_id']);
            if($count > 0){
//                if(substr($list['order_id'], 0, 1 )!='S'){
                    if(!in_array(substr($list['order_id'], 0, 1 ), array('S','L'))){
                $queue_arr[] = $list['order_id'];
                }
            }
            
            $arr  = $this->m_trade->get_order_supplier_id($list['order_id']);    //供应商ID数组
            $supplier_id = isset($arr['supplier_id'])? $arr['supplier_id']:0;
            
            //随机显示一个供应商客服QQ
            $supplier_info = $this->m_goods->get_supplier_info($supplier_id);
            
            if(!empty($supplier_info['supplier_qq'])) {
                
                $sup_str = $supplier_info['supplier_qq'];
                $char_1 = "\\";
                $char_2 = "/";
                if(strpos($sup_str, $char_1)>0) {     //目前存在少数不按照约定的英文逗号分隔的QQ号码 其为\或者/ 需要额外处理
                    
                } else if(strpos($sup_str, $char_2)>0) {
                    
                } else {
                    $qq_arr = explode(",",$supplier_info['supplier_qq']);    
                    $len = count($qq_arr);
                    if($len>1) {
                        $key = rand(0,$len-1);   
                    } else {
                        $key = 0;
                    }
                    $list['supplier_qq'] = $qq_arr[$key];
                }
            }
            
			$list['amount'] = $this->m_currency->price_format($list['order_amount_usd']);
			$list['goods_amount_usd'] = $this->m_currency->price_format($list['goods_amount_usd']);
			$list['order_amount_usd'] = $this->m_currency->price_format($list['order_amount_usd']);
			$list['customer_name'] = $this->m_trade->get_customer_name($list['customer_id']);
			$list['is_customer'] = $uid == $list['customer_id'] ? TRUE : FALSE;

			$list['sub_orders'] = array();
			if($list['order_prop'] == 2){
//				$list['sub_orders'] = $this->db->where('attach_id',$list['order_id'])->where('order_prop','1')->get('trade_orders')->result_array();
                $this->load->model("tb_trade_orders");
                $list['sub_orders'] = $this->tb_trade_orders->get_list_auto([
                    "where"=>[
                        'attach_id'=>$list['order_id'],
                        'order_prop'=>'1',
                    ]
                ]);
                if($list['sub_orders'])foreach($list['sub_orders'] as &$sub_order){
					// 新增限时退货处理业务 soly 取消和超时不显示退换货按钮 0 2 都不显示
					$sub_order['display'] = 0;
					$total = $this->db->from('my_order_exchange_log')
					  ->where('order_id', $sub_order['order_id'])
			          ->where_in('status', array(0,2))
			          ->count_all_results();

			        $total && $sub_order['display'] = 1;
                    
                    $arr  = $this->m_trade->get_order_supplier_id($sub_order['order_id']);    //供应商ID数组
                    $supplier_id = isset($arr['supplier_id'])? $arr['supplier_id']:0;
                    
                    //随机显示一个供应商客服QQ
                    $supplier_info = $this->m_goods->get_supplier_info($supplier_id);
                    if(!empty($supplier_info['supplier_qq'])) {
                        $sup_str = $supplier_info['supplier_qq'];
                        $char_1 = "\\";
                        $char_2 = "/";
                        if(strpos($sup_str, $char_1)>0) {    //目前存在少数不按照约定的英文逗号分隔的QQ号码 其为\或者/ 需要额外处理

                        } else if(strpos($sup_str, $char_2)>0) {

                        } else {
                            $qq_arr = explode(",",$supplier_info['supplier_qq']);    
                            $len = count($qq_arr);
                            if($len>1) {
                                $key = rand(0,$len-1);   
                            } else {
                                $key = 0;
                            }
                            $sub_order['supplier_qq'] = $qq_arr[$key];
                        }
                        
                    } 
                    
			        // 获取时间
			        $sub_order['timer'] = null;
			        $exchangeTime = $this->db->select('create_time')
			                 ->where('order_id', $sub_order['order_id'])
			                 ->get('my_order_exchange_time')->row_array();
			        if (isset($exchangeTime['create_time']) && $exchangeTime['create_time']) {
			        	$sub_order['timer'] = ((strtotime($exchangeTime['create_time']) + 72 * 3600) > time()) ? (72 * 3600) - (time() - strtotime($exchangeTime['create_time'])) : 0;
			        }
			        // 超时定时脚本还没开始运行的时候 直接隐藏掉换货按钮
			        ($sub_order['timer'] === 0) && $sub_order['display'] = 1;

			        // 取消换货不提示
			        $sub_order['excannel'] = 0;
			        $uc_cannel = $this->db->from('my_order_exchange_log')
					  ->where('order_id', $sub_order['order_id'])
			          ->where('status', 1)
			          ->count_all_results();
			        $uc_cannel &&  $sub_order['excannel'] = 1;

					$sub_order['amount'] = $this->m_currency->price_format($sub_order['order_amount_usd']);
					$sub_order['goods_amount_usd'] = $this->m_currency->price_format($sub_order['goods_amount_usd']);
					$sub_order['customer_name'] = $this->m_trade->get_customer_name($sub_order['customer_id']);
					$sub_order['is_customer'] = $uid == $sub_order['customer_id'] ? TRUE : FALSE;
					if($sub_order['status'] == '99'){
						$list['is_cancel'] = TRUE;
					}
				}
			} else {
				// 新增没有拆单的情况处理
				
				// 新增限时退货处理业务 soly 取消和超时不显示退换货按钮 0 2 都不显示
				$list['display'] = 0;
				$total = $this->db->from('my_order_exchange_log')
				  ->where('order_id', $list['order_id'])
		          ->where_in('status', array(0,2))
		          ->count_all_results();

		        $total && $list['display'] = 1;

		        // 获取时间
		        $list['timer'] = null;
		        $exchangeTime = $this->db->select('create_time')
		                 ->where('order_id', $list['order_id'])
		                 ->get('my_order_exchange_time')->row_array();
		        if (isset($exchangeTime['create_time']) && $exchangeTime['create_time']) {
		        	$list['timer'] = ((strtotime($exchangeTime['create_time']) + 72 * 3600) > time()) ? (72 * 3600) - (time() - strtotime($exchangeTime['create_time'])) : 0;
		        }
		        // 超时定时脚本还没开始运行的时候 直接隐藏掉换货按钮
		        ($list['timer'] === 0) && $list['display'] = 1;

		        // 取消换货不提示
		        $list['excannel'] = 0;
		        $uc_cannel = $this->db->from('my_order_exchange_log')
				  ->where('order_id', $list['order_id'])
		          ->where('status', 1)
		          ->count_all_results();
		        $uc_cannel &&  $list['excannel'] = 1;
			}
		}
		//echo '<pre>';print_r($lists);echo '</pre>';
		$this->_viewData['lists'] = $lists;
		$this->_viewData['searchData'] = $searchData;
		$this->_viewData['queue_str'] = implode(',',$queue_arr);

		$url = 'ucenter/my_other_orders';
		$this->_viewData['url'] = "ucenter/my_other_orders?status=".$searchData['status']."&order_input=".$searchData['order_input'];

		$this->load->library('pagination');
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $this->m_order->getOrdersRows($searchData,$filed,$uid);
		$config['cur_page'] = $searchData['page'];
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links();

        /*
         * 重要提示:在某月是29,31,30日的情况下，date('Y-m',strtotime('-1 month')) 与 date('Y-m',strtotime('-2 month')) 运行结果会不正确
         * 故不能完全采用这种方法获取前2个月的月份数字
         */
		$month[] = date('Y-m');
        $tmp_date = date("Ym"); 
        $tmp_year = substr($tmp_date,0,4);  //获取年份
        $tmp_mon = substr($tmp_date,4,2);  //获取月份
        $month_1 = mktime(0,0,0,$tmp_mon-1,1,$tmp_year);  
        $month[] = date('Y-m',$month_1);  //获取前一个月
        $month_2 = mktime(0,0,0,$tmp_mon-2,1,$tmp_year); 
        $month[] = date('Y-m',$month_2);  //获取前第二个月
        
		$month[] = date('Y-m',strtotime('-3 month'));  //获取前第三个月

		$this->_viewData['months'] = $month;
		$this->_viewData['lang_arr']=array(
			'840'=>'USA',
			'156'=>'中国',
			'344'=>'香港',
			'410'=>'한국',
			'000'=>'Other',
		);

		parent::index();
	}
    
	/** 修改未付款的订单地址信息 */
	public function edit_korea_order(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();
			if($data['address'] == ''){
				$result['success'] = 0;
				$result['msg'] = 'Address cannot be empty';
				die(json_encode($result));
			}
			/*if($data['customs_clearance'] == ''){
				$result['success'] = 0;
				$result['msg'] = 'Customs Clearance cannot be empty';
				die(json_encode($result));
			}
			if($data['zip_code'] == ''){
				$result['success'] = 0;
				$result['msg'] = 'Zip Code cannot be empty';
				die(json_encode($result));
			}*/
			$success = $this->m_order->update_korea_order($data);

            //修改成功 同步到erp
            if($success['success'] == 1){
                $this->load->model('m_erp');
                unset($data['country_address']);
                $this->m_erp->update_order_to_erp_log($data);
            }


			die(json_encode($success));
		}
	}

}
