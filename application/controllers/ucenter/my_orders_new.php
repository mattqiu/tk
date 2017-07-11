<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class My_orders_new extends MY_Controller {

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

		$filed = 'shopkeeper_id';
		$this->_viewData['title'] = lang('my_tps_orders');
        
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
                $queue_arr[] = $list['order_id'];
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
			$list['customer_name'] = $this->m_trade->get_customer_name($list['customer_id']);
			$list['is_customer'] = $uid == $list['customer_id'] ? TRUE : FALSE;
			$list['sub_orders'] = array();

			if($list['order_prop'] == 2){
//				$list['sub_orders'] = $this->db_slave->where('attach_id',$list['order_id'])->where('order_prop','1')->get('trade_orders')->result_array();
                $this->load->model("tb_trade_orders");
                $list['sub_orders'] = $this->tb_trade_orders->get_list_auto([
                    "select"=>"order_id,order_prop,created_at,pay_time,score_year_month,status,
                    order_amount_usd,goods_amount_usd,txn_id,customer_id",
                    "where"=>[
                        'attach_id'=>$list['order_id'],
                        'order_prop'=>'1'
                    ]
                ]);
                if($list['sub_orders']) foreach($list['sub_orders'] as &$sub_order){
        
                  $arr  = $this->m_trade->get_order_supplier_id($sub_order['order_id']);
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
					$sub_order['amount'] = $this->m_currency->price_format($sub_order['order_amount_usd']);
					$sub_order['goods_amount_usd'] = $this->m_currency->price_format($sub_order['goods_amount_usd']);
					$sub_order['customer_name'] = $this->m_trade->get_customer_name($sub_order['customer_id']);
					$sub_order['is_customer'] = $uid == $sub_order['customer_id'] ? TRUE : FALSE;
				}
			}
		}

		$this->_viewData['lists'] = $lists;
		$this->_viewData['searchData'] = $searchData;
        $this->_viewData['queue_str'] = implode(',',$queue_arr);

		$url = 'ucenter/my_orders_new';
		$this->_viewData['url'] = "ucenter/my_orders_new?status=".$searchData['status']."&order_input=".$searchData['order_input'];

		$this->load->library('pagination');
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $this->m_order->getOrdersRows($searchData,$filed,$uid);
		$config['cur_page'] = $searchData['page'];
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links();

		$now_month = date("m");
		if ($now_month == "03") {
			$month = [
				date('Y-m'),
				date("Y-02"),
				$month[] = date('Y-m',strtotime('-2 month')),
				$month[] = date('Y-m',strtotime('-3 month')),
			];
		}else {
			$month[] = date('Y-m');
			if(date("d") > 3)
            {
                //避免在31号时出bug
                $month[] = date('Y-m',strtotime('-1 month -3 day'));
                $month[] = date('Y-m',strtotime('-2 month -3 day'));
                $month[] = date('Y-m',strtotime('-3 month -3 day'));
            }else{
                $month[] = date('Y-m',strtotime('-1 month'));
                $month[] = date('Y-m',strtotime('-2 month'));
                $month[] = date('Y-m',strtotime('-3 month'));
            }
		}


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

	/**
	 * 退换货
	 */
	public function  refund_and_replace(){
		$this->_viewData['title'] = lang('my_orders').">".lang('refund_and_replace');
		parent::index('','refund_and_replace');
	}



	/** 添加评论 */
	public function do_evaluate(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();
			$data['time'] = time();
			if(isset($data['ev_checkbox'])){ //匿名
				$data['com_user'] = '';
			}else{
				$data['com_user'] = $this->_userInfo['email'];
			}
			$data['uid'] = $this->_userInfo['id'];

			//事务开始
			$this->db->trans_begin();

			$this->load->model('m_user_helper');
			$result = $this->m_user_helper->do_comments($data);

            //同步到erp(已完成)
            if($result['success'] == 1){
                $this->load->model('m_erp');
				$insert_data = array();
				$insert_data['oper_type'] = 'modify';
				$insert_data['data']['order_id'] = $data['order_id'];
				$insert_data['data']['status'] = Order_enum::STATUS_COMPLETE;

				$this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
            }

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				exit;
			}
			else
			{
				$this->db->trans_commit();
				die(json_encode($result));
				exit;
			}
		}
	}

	/**
	 * 电子收据下载
	 */
	public function order_pdf_receipt($order_id)
	{
		if (empty($order_id))
		{
			redirect('ucenter/my_orders_new/');
			exit;
		}

		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			redirect(base_url('login').'?redirect=ucenter/my_orders_new');
		}
		$uid = $this->_userInfo['id'];

		$this->load->library('pdf');

		$html = $this->m_trade->get_order_receipt_html($order_id, $this->_viewData['curLan_id'], $uid);

		$this->pdf->write_pdf_html($html);

		$this->pdf->output_pdf();
		exit;
	}

	/**
	 * 电子收据文件生成
	 */
	public function order_pdf_receipt_file($order_id)
	{
		$ret_data = array(
			'code' => 0,
		);

		if (empty($order_id))
		{
			$ret_data['code'] = 101;
			echo json_encode($ret_data);
			exit;
		}

		// 校验用户登陆状态
		if (empty($this->_userInfo))
		{
			$ret_data['code'] = 101;
			echo json_encode($ret_data);
			exit;
		}

		// 检测用户是否勾选收据checkbox
		$this->load->model('m_trade');
		$is_receipt = $this->m_trade->is_receipt($order_id);
		if(!$is_receipt){
			$ret_data['code'] = 101;
			echo json_encode($ret_data);
			exit;
		}

		$uid = $this->_userInfo['id'];

		$this->load->library('pdf');

		$html = $this->m_trade->get_order_receipt_html($order_id, $this->_viewData['curLan_id'], $uid);

		$this->pdf->write_pdf_html($html);

		$this->pdf->order_receipt_file($order_id);

		echo json_encode($ret_data);
		exit;
	}
}
