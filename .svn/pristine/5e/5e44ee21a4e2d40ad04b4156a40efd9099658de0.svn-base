<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class My_orders_action extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('M_order','m_order');
	}


	/**
	 * 订单详情
	 */
	public function order_info($order_id){
		if($order_id !== NULL ){
			// 状态映射表
			$this->_viewData['status_map'] = array(
				0 => lang('admin_order_status_all'),
				Order_enum::STATUS_INIT => lang('admin_order_status_init'),
				Order_enum::STATUS_CHECKOUT => lang('admin_order_status_checkout'),
				Order_enum::STATUS_SHIPPING => lang('admin_order_status_paied'),
				Order_enum::STATUS_SHIPPED => lang('admin_order_status_delivered'),
				Order_enum::STATUS_EVALUATION => lang('admin_order_status_arrival'),
				Order_enum::STATUS_COMPLETE => lang('admin_order_status_finish'),
				Order_enum::STATUS_RETURNING => lang('admin_order_status_returning'),
				Order_enum::STATUS_RETURN => lang('admin_order_status_refund'),
				Order_enum::STATUS_CANCEL => lang('admin_order_status_cancel'),
				Order_enum::STATUS_COMPONENT => lang('admin_order_status_component'),
                Order_enum::STATUS_HOLDING =>lang("admin_order_status_holding"),
			);

			$order = $this->m_trade->get_order_data($order_id);
			if(!$order){
				redirect('ucenter/my_orders_new');exit;
			}

			$this->load->model("tb_users");
			$uid = $this->_userInfo['id'];
			$user_info = $this->tb_users->get([
				'select'=>'id,mobile,is_verified_mobile,country_id',
				'where'=>[
					'id'=>$uid
				],
				'limit'=>1
			],false,true,true);

			$this->_viewData['user'] = $user_info;
			if (!empty($user_info['mobile'])) {
				if ($user_info['is_verified_mobile'] == 1) {
					$this->_viewData['bind_mobile'] = 1;
					$this->_viewData['bind_mobile_message'] = lang_attr('mobile_code_will_send', array('mobile' => $user_info['mobile']));
					$this->_viewData['bind_mobile_message_1'] ="";
				} else {
					$this->_viewData['bind_mobile'] = 0;
					$this->_viewData['bind_mobile_message']  = lang("mobile_not_bind");
					$this->_viewData['bind_mobile_message_1'] =lang("mobile_not_verified");
				}
				$this->_viewData['mobile_sended'] = lang_attr('mobile_code_has_send', array('mobile' => $user_info['mobile']));

			} else {
				$this->_viewData['bind_mobile'] = 0;
				$this->_viewData['bind_mobile_message']  = lang("mobile_not_bind");
				$this->_viewData['bind_mobile_message_1'] =lang("mobile_not_verified");
				$this->_viewData['mobile_sended'] = "";
			}



            $this->_viewData['is_queue_order'] = $this->m_log->check_order_in_queue($order_id);
			$this->_viewData['remarks'] = $this->m_trade->get_order_customer_data($order_id);
			$this->_viewData['title'] = lang('order_info');
			$this->_viewData['order'] = $order;
			$this->_viewData['disabled_lang'] = TRUE;

			parent::index('','order_info');

		}else{
			redirect('ucenter/my_orders_new/');exit;
		}

	}

	/** 確認收貨 */
	function confirm_deliver(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
            if(empty($id)){
                die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
            }
			/*事务开始*/
            $this->db->trans_begin();

			$this->load->model('m_user_helper');
			$res = $this->m_user_helper->confirm_deliver($id,$this->_userInfo['id']);
			if($res > 0){
                //同步到erp(等待评价)
                $this->load->model('m_erp');
                $insert_data = array();
                $insert_data['oper_type'] = 'modify';
                $insert_data['data']['order_id'] = $id;
                $insert_data['data']['status'] = Order_enum::STATUS_EVALUATION;

                $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                //事务回滚了
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    exit;
                }
                //事务提交
                else
                {
                    $this->db->trans_commit();
                    die(json_encode(array('success'=>1,'msg'=>'')));
                    exit;
                }
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
			}
		}
	}


	/** 未付款之前用户可以自己取消订单 */
	function confirm_cancel(){

		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			$this->load->model('m_user_helper');
			$res = $this->m_user_helper->confirm_cancel($id,$this->_userInfo['id']);
			if($res){
				die(json_encode(array('success'=>1,'msg'=>'')));
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
			}
		}
	}


	/**商品评价**/
	public function evaluate($order_id = NULL){

		$this->_viewData['title'] = lang('my_orders').">".lang('label_goods_com');
		if ($order_id !== NULL)
		{
			$order = $this->m_trade->get_order_data($order_id);
			if (!$order || $order['order_detail']['status'] != Order_enum::STATUS_EVALUATION)
			{
				redirect('ucenter/my_orders_new');
				exit;
			}
			$this->_viewData['order'] = $order;

			parent::index('','evaluate');
		}
		else
		{
			redirect('ucenter/my_orders_new/');
			exit;
		}
	}

	/** 检测订单中心的操作 当前区域和收货区域是否一致 */
	public function check_location(){
		if($this->input->is_ajax_request()){
            $this->load->model('tb_trade_orders');
			$attr = $this->input->post();
			$order_id = $attr['order_id'];
			$h_id = isset($attr['h_id'])?$attr['h_id']:0;

			//开始事务
			$this->db->trans_begin();
            //保存要换的订单信息到cooike
            //换货功能的生效时间
            if ($h_id) {
                $publicDomain = get_public_domain();
                $order_info = $this->tb_trade_orders->getOrderInfo($order_id, array('remark','status', 'attach_id', 'order_prop', 'customer_id', 'goods_amount_usd', 'order_amount_usd', 'deliver_fee', 'order_type'));

                //子订单，以该订单的主订单去查询表 trade_orders_type ，存在数据则是升级订单
                $attach_id = $order_id;
                if ($order_info['order_prop'] == 1) {
                    $attach_id = $order_info['attach_id'];
                }
                $upgrade_res = $this->db->query("select * from trade_orders_type where order_id = '$attach_id'")->row_array();
                $type = '';
                $level = '';
                //升级订单的换货
                if (!empty($upgrade_res)) {
                    $type = 'exchange';
                    $level = $upgrade_res['level'];
                } else {
                    echo '订单类型有误';
                    exit;
                }

                if ($order_info['status'] == 3) {
                    $remark_in = '#exchange' . $order_info['remark'];
                    //状态换货中
//                    $this->db->where('order_id', $order_id)->update('trade_orders', array('status' => '90', 'remark' => $remark_in));
                    $this->tb_trade_orders->update_one(["order_id"=>$order_id],
                        array('status' => '90', 'remark' => $remark_in));
                    $insert_data = array();
                    $insert_data['oper_type'] = 'modify';
                    $insert_data['data']['order_id'] = $order_id;
                    $insert_data['data']['status'] = '90';
                    $insert_data['data']['remark'] = $remark_in;
                    $this->load->model('m_erp');
                    $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单
                }

                //订单除开运费的金额

                $order_money = ($order_info['order_amount_usd'] - $order_info['deliver_fee']) / 100;

                $exchange = array(
                    'order_id' => $order_id,
                    'now_level' => $level,
                    'order_money' => $order_money,
                    'name' => $this->_userInfo['name'],
                    'type' => $type
                );
                set_cookie("exchange", serialize($exchange), 0, $publicDomain);


                // 添加换货计时器 soly
                $timerTotal = $this->db->from('my_order_exchange_time')->where('order_id', $order_id)->count_all_results();
                $timerTotal || $this->db->insert('my_order_exchange_time', array(
                        'uid' 			=> $this->_userInfo['id'],
                        'order_id'		=> $order_id,
                        'create_time'	=> date('Y-m-d H:i:s')
                ));

                // 记录换货日志 soly
                $total = $this->db->from('my_order_exchange_log')
                        ->where('order_id', $order_id)
                        ->where('status', 1)
                        ->count_all_results();
                $total || $this->db->insert('my_order_exchange_log', array(
                        'uid'       	=> $this->_userInfo['id'],
                        'order_id'  	=> $order_id,
                        'status'		=> 1, // 退货中
                        'create_time'	=> date('Y-m-d H:i:s')
                ));
            }

			$location_id=$this->session->userdata('location_id');

			$this->load->model('m_order');
			$data = $this->m_order->check_location($order_id,$location_id);

			$other = array();
			if($data['flag'] === false){
				$result =  $this->m_goods->get_sale_country($data['area']);
				$other['location_id'] = $data['area'];
				$other['location_lang'] = $result[0]['default_language'];
				$other['currency_id'] = $result[0]['default_flag'];
			}

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				exit('System error.');
			}
			else
			{
				$this->db->trans_commit();
				die(json_encode(array('success'=>$data['flag'],'other'=>$other)));
			}
		}
	}

    /**
     * 取消换货
     */
    public function exchange(){
        if($this->input->is_ajax_request()){
            $attr = $this->input->post();
            $order_id = $attr['order_id'];
            $this->load->model('tb_trade_orders');
            $order_info = $this->tb_trade_orders->getOrderInfo($order_id, array('status,remark'));
            if($order_info['status'] != 90){
                echo json_encode(array('success'=>false,'msg'=>'订单不需取消换货'));
                exit;
            }

//            $count=strpos($order_info['remark'],"#exchange");
//            $str = substr_replace($order_info['remark'],"",$count,9);

			$is_ex = strstr($order_info['remark'],"#exchange");
			$str = $order_info['remark'];
			if($is_ex){
				$count = strpos($order_info['remark'],"#exchange");
				$str = substr_replace($order_info['remark'],"",$count,9);
			}

//            $res = $this->db->where('order_id',$order_id)->update('trade_orders',array('status'=> 3,'remark'=>$str));
            $res = $this->tb_trade_orders->update_one(["order_id"=>$order_id],array('status'=> 3,'remark'=>$str));
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order_id;
            $insert_data['data']['status'] = '3';
            $insert_data['data']['remark'] = $str;

            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

            // 删除去换货计时器数据soly
            $this->db->where('order_id', $order_id)->delete('my_order_exchange_time');

            // 记录取消换货日志 soly
			$total = $this->db->from('my_order_exchange_log')
			          ->where('order_id', $order_id)
			          ->where('status', 0)
			          ->count_all_results();
			$total || $this->db->insert('my_order_exchange_log', array(
				'uid'       	=> $this->_userInfo['id'],
				'order_id'  	=> $order_id,
				'status'		=> 0, // 退货中
				'create_time'	=> date('Y-m-d H:i:s')
			));

            if($res){
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
                exit;
            }
        }
    }

}
