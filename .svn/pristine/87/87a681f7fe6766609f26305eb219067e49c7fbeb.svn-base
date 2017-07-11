<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class After_sale_order_list extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

	public function index(){
		$this->_viewData['title'] = lang('admin_add_after_sale_list');

		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['as_id'] = isset($searchData['as_id'])?$searchData['as_id']:'';
		$searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
		$searchData['email'] = isset($searchData['email'])?$searchData['email']:'';
		$searchData['status'] = isset($searchData['status'])?$searchData['status']:'0';
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$searchData['type'] = isset($searchData['type'])?$searchData['type']:'';
                $searchData['refund_method'] = isset($searchData['refund_method'])?$searchData['refund_method']:'';
		$this->_viewData['list'] = $this->m_admin_user->getAfterSaleList($searchData,$this->_adminInfo['id'],$this->_curControlName);

		$this->load->library('pagination');
		$url = 'admin/after_sale_order_list';
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $this->m_admin_user->getAfterSaleRows($searchData,$this->_adminInfo['id'],$this->_curControlName);
		$config['cur_page'] = $searchData['page'];
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links(true);

		$this->_viewData['searchData'] = $searchData;
		parent::index('admin/','after_sale_list');
	}

	/** 抽回信息 */
	public function demote_info($as_id){

		$this->_viewData['title'] = lang('admin_add_after_sale_list');
		if(!$as_id){
			redirect(base_url('admin/after_sale_order_list'));
		}
		$as_info = $this->db->where('as_id',$as_id)->get('admin_after_sale_order')->row_array();
		if($as_info['status'] != '0'){
			redirect(base_url('admin/after_sale_order_list'));
		}
                $tmp_order_arr = explode('*', $as_info['remark']); //分离显示有哪些取消的升级订单
                if(isset($tmp_order_arr[1]) && $tmp_order_arr[1]){
                      $as_info["cance_order"] = explode('#', $tmp_order_arr[1]);          
                }
		$this->_viewData['as_info'] = $as_info;

		$fields = 'order_id';
		$value = $as_info['order_id'];
		if(in_array($as_info['type'],array(0,1))){
			$amount = $this->db->select('amount,is_choose,user_rank,month_fee_pool')->where('id',$as_info['uid'])->get('users')->row_array();
			$coupons = 0;
			if($amount['is_choose'] == 1){
				$this->load->model('m_coupons');
				$coupons = $this->m_coupons->get_coupons_list($as_info['uid'])['total_money'];
			}
			$this->_viewData['amount'] = $amount['amount'].'===>'.lang('d_p_coupons').$coupons.'===>'.lang('level_'.$amount['user_rank']);
			$fields = 'customer_id';
			$value = $as_info['uid'];
            if($as_info['type'] == 0){
                $this->_viewData['amount_str'] = $this->m_admin_user->getProductSetByUid($as_info['uid'],$amount['user_rank'],$amount['month_fee_pool'])['amount_str'];
            }
        }

//		$orders = $this->db->select('order_id,order_amount_usd,deliver_fee_usd,status,payment_type')->where($fields,$value)->where_in('order_prop',array('0','1'))->get('trade_orders')->result_array();
        $this->load->model("tb_trade_orders");
        $orders = $this->tb_trade_orders->get_list_auto([
            "select"=>"order_id,order_amount_usd,deliver_fee_usd,status,payment_type",
            "where"=>[$fields=>$value,'order_prop'=>array('0','1')],
        ]);
		$this->load->model('m_split_order');
		$status_map = array(
			0 => lang('admin_order_status_all'),
			Order_enum::STATUS_INIT => lang('admin_order_status_init'),
			Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
			Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
			Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
			Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
                        Order_enum::STATUS_HOLDING => lang('account_disable'),
                    
			Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
			Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
			Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
			Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
			Order_enum::STATUS_COMPONENT => lang('admin_order_status_component'),
		);
		if($orders)foreach($orders as &$order){
			$type = $this->m_split_order->get_order_type($order['order_id']);
			if($type == '3'){
				$order['type_name'] = lang('admin_as_upgrade_order');
				$order['class'] = 'success';
			}else{
				$order['type_name'] = lang('admin_as_consumed_order');
				$order['class'] = '';
			}
			$order['status']  = $status_map[$order['status']];
		}
		$this->_viewData['orders'] = $orders;

		$remarks = $this->db->query("select ar.*,au.email from admin_after_sale_remark ar,admin_users au where ar.admin_id=au.id and as_id='$as_id' order by ar.create_time desc")->result_array();
		$this->_viewData['remarks'] = $remarks;

		parent::index('admin/','after_sale_order_info');
	}

	function batch_precess(){
		$param = $this->input->post();
		if($param && isset($param['batch_status'])){
			$status = $param['batch_status'];
			$as_id_arr = $param['checkboxes'];
			if($status==4){//驳回
				$return = $this->m_admin_user->update_status($as_id_arr,$status);
				if($return){
					die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
				}else{
					die(json_encode(array('success'=>0,'msg'=>lang('update_failure'))));
				}
			}elseif($status==2){//通过
				$this->db->trans_begin();
				$order_arr = $this->db->where_in('as_id',$as_id_arr)->get('admin_after_sale_order')->result_array();
				foreach($order_arr as $order){
						$this->load->model('m_trade');
						$insert_attr = array(
								'order_id' => $order['order_id'],
								'type' => 1,
								'remark' => sprintf(lang('admin_refund_amount'),$order['refund_amount']),
								'admin_id' => $this->_viewData['adminInfo']['id'],
						);
						$this->m_trade->add_order_remark_record($insert_attr);
						$insert_attr2 = array(
								'order_id' => $order['order_id'],
								'type' => 2,
								'remark' => sprintf(lang('admin_refund_amount'),$order['refund_amount']),
								'admin_id' => $this->_viewData['adminInfo']['id'],
						);
						$this->m_trade->add_order_remark_record($insert_attr2);
						$this->load->model('m_commission');
						$this->m_commission->commissionLogs($order['transfer_uid'],9,$order['refund_amount']);
						$this->db->where('id',$order['transfer_uid'])->set('amount','amount +'.$order['refund_amount'],FALSE)->update('users');
						$as_status = 2;
						$remark = '已抽回（已退款到现金池）';
					$this->m_log->admin_after_sale_remark($order['as_id'],$this->_adminInfo['id'],$remark);
					$this->db->where('as_id',$order['as_id'])->update('admin_after_sale_order',array('status'=>$as_status));
				}
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					die(json_encode(array('success'=>0,'msg'=>lang('update_failure'))));
				} else {
					$this->db->trans_commit();
					die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
				}
			}
		}
	}

    /**
     * 生成退会批次
     */
    function batch_precess_alipay(){
		$param = $this->input->post();

		if($param && isset($param['batch_status'])){
			$status = $param['batch_status'];
			$as_id_arr = isset($param['checkboxes'])?$param['checkboxes']:'';
            if(empty($as_id_arr)){
                die(json_encode(array('success'=>0,'msg'=>"请选择售后订单")));
            }
			if($status==2){//生成批次
				$this->db->trans_begin();
				$order_arr = $this->db->select('as_id,refund_amount,status,batch_id')->where_in('as_id',$as_id_arr)->get('admin_after_sale_order')->result_array();
                $total_count = 0;
                $total_sum = 0;
                /**
                 * 订单是待付款到银行-》已录入，批次id关联，
                 */
				foreach($order_arr as $order){
                    if($order['status'] != 1 || !empty($order['batch_id'])){
                         die(json_encode(array('success'=>0,'msg'=>$order['as_id']."状态或已有Batch_id不能生成批次")));
                    }
                    $total_count = $total_count + 1;
                    $total_sum = $total_sum + $order['refund_amount'];
				}
                $batch_num = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                $batch = array(
                    'batch_num' => $batch_num,
                    'total' => $total_count,
                    'lump_sum' => $total_sum,
                    'create_time' => date("Y-m-d H:i:s", time()),
                );
                $this->db->insert('admin_after_sale_batch', $batch);
                $insert_id = $this->db->insert_id();
                foreach ($order_arr as $key => $order) {
                    $data[$key]['as_id'] = $order['as_id'];
                    $data[$key]['batch_id'] = $insert_id;
                    $data[$key]['status'] = 7; //变成已录入状态
                }
                $this->db->update_batch('admin_after_sale_order', $data, 'as_id');

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					die(json_encode(array('success'=>0,'msg'=>lang('update_failure'))));
				} else {
					$this->db->trans_commit();
					die(json_encode(array('success'=>1,'msg'=>'操作成功')));
				}
			}
		}
	}



}