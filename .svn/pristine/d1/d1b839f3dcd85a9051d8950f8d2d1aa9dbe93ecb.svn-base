<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Add_after_sale_order extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    public function index($as_id = NULL) {

		$as_info = $this->db->where('as_id',$as_id)->where_in('status',array('4','5'))->get('admin_after_sale_order')->row_array();

		if($as_id == NULL || empty($as_info)){
			$this->_viewData['title'] = lang('admin_add_after_sale');
			$this->_viewData['as_id'] = $this->m_admin_user->create_after_sale_id();
		}else{
			$this->_viewData['title'] = lang('admin_as_update');
			$this->_viewData['as_info'] = $as_info;

			/*$fields = 'order_id';
			$value = $as_info['order_id'];
			if($as_info['type'] != 2){
				$amount = $this->db->select('amount,is_choose')->where('id',$as_info['uid'])->get('users')->row_array();
				$coupons = '';
				if($amount['is_choose'] == 1){
					$this->load->model('m_coupons');
					$coupons = $this->m_coupons->get_coupons_list($as_info['uid'])['total_money'];
				}
				$this->_viewData['amount'] = $amount['amount'].'===>'.lang('d_p_coupons').$coupons;
				$fields = 'customer_id';
				$value = $as_info['uid'];
			}*/

			/*$orders = $this->db->select('order_id,order_amount_usd,deliver_fee_usd,status,order_prop,attach_id')->where($fields,$value)->where('payment_type <>','0')->where_in('order_prop',array('0','1'))->get('trade_orders')->result_array();
			$this->load->model('m_split_order');
			$status_map = array(
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
			);
			if($orders)foreach($orders as &$order){
				$order_id = $order['order_prop'] == 1 ? $order['attach_id'] : $order['order_id'];
				$type = $this->m_split_order->get_order_type($order_id);
				if($type == '3'){
					$order['type_name'] = lang('admin_as_upgrade_order');
					$order['class'] = 'success';
				}else{
					$order['type_name'] = lang('admin_as_consumed_order');
					$order['class'] = '';
				}
				$order['status']  = $status_map[$order['status']];
			}
			$this->_viewData['orders'] = $orders;*/

		}

        parent::index('admin/');
    }

	/** 售后订单列表 */
	public function after_sale_list(){
		$this->_viewData['title'] = lang('admin_add_after_sale_list');

		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['uid'] = isset($searchData['uid'])?$searchData['uid']:'';
		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
		$this->_viewData['list'] = $this->m_admin_user->getAfterSaleList($searchData);

		$this->load->library('pagination');
		$url = 'admin/delete_users';
		add_params_to_url($url, $searchData);
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $this->m_admin_user->getAfterSaleRows($searchData);
		$config['cur_page'] = $searchData['page'];
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links();

		$this->_viewData['searchData'] = $searchData;
		parent::index('admin/','after_sale_order');
	}

	/** 檢查用戶是否可以退回 */
	public function checkStoreLevel(){
		if($this->input->is_ajax_request()){
			$id = $this->input->post('id');
			$type = $this->input->post('type');
			$demote_level = $this->input->post('demote_level');
			$this->load->model('m_user');
			$user = $this->m_user->getUserByIdOrEmail($id);
			if(!$user){
				die(json_encode(array('success'=>0,'msg'=>lang('no_exist'))));
			}
			if($user['status'] == 4 ){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_as_status_error'))));
			}

			$coupons='';
			$result['coupons'] = '';
			if($user['is_choose'] == 1){
				$this->load->model('m_coupons');
				$coupons = $this->m_coupons->get_coupons_list($id)['total_money'];
			}
			$result['amount'] = $user['amount'].'===>'.lang('d_p_coupons').$coupons.'===>'.lang('level_'.$user['user_rank']).'===>'.$user['upgrade_time'];
			$result['name'] = $user['name'];
			$data = $this->m_admin_user->getProductSetByUid($id,$user['user_rank'],$user['month_fee_pool']);
			$result['refund_amount'] =  $data['amount'];
			$result['refund_amount_str'] =  $data['amount_str'];
			if($type == 1 && ($demote_level > 0 || $demote_level === false)){

				if($user['user_rank'] == 5 ||  $user['user_rank'] == 3){
					//die(json_encode(array('success'=>0,'msg'=>lang('admin_not_demote_tip'))));
				}

				$result['is_load'] = FALSE;
				$this->load->model('M_overrides','m_overrides');

				if($demote_level === false){
					$result['store_level_option'] = '';
					foreach(config_item('user_ranks') as $k =>$value){
						if($k > $user['user_rank'] || ($user['user_rank']==5 && $k==4)){
							$result['store_level_option'] .= "<option value=".$k.">".lang($value)."</option>";
						}
					}
					$result['is_load'] = TRUE;
					$demote_level = $user['user_rank']+1;
				}
				$result['refund_amount'] =  $this->m_overrides->getUpgradeProfit($user['user_rank'],$demote_level,$user['id']);


				if($coupons){
					$this->load->model('M_overrides','m_overrides');
					$duce_amount =  $this->m_overrides->getUpgradeProfit($user['user_rank'],$demote_level,$user['id']);
					if($duce_amount > $coupons){
						$tmp = $duce_amount - $coupons;
						$result['refund_amount'] = $result['refund_amount'] - $tmp;
						$result['coupons'] = lang('admin_after_sale_coupons');
					}
				}
			}else{
				$result['store_level_option'] = '';
				foreach(config_item('user_ranks') as $k =>$value){
					if($k > $user['user_rank'] || ($user['user_rank']==5 && $k==4)){
						$result['store_level_option'] .= "<option value=".$k.">".lang($value)."</option>";
					}
				}
				$result['is_load'] = TRUE;
			}
//			$orders = $this->db->select('order_id,order_prop,order_amount_usd,deliver_fee_usd,status,payment_type,attach_id')->
//				where('customer_id',$id)->where('payment_type <>','0')->where_in('order_prop',array('0','1'))->get('trade_orders')->result_array();
            $this->load->model("tb_trade_orders");
            $orders = $this->tb_trade_orders->get_list_auto([
                "select"=>"order_id,order_prop,order_amount_usd,deliver_fee_usd,status,payment_type,attach_id",
                "where"=>['customer_id'=>$id,'payment_type <>'=>'0','order_prop'=>array('0','1')],
            ]);
            $table_str = "<table class='table'><th>".lang('order_id')."</th><th>".lang('type')."</th><th>".lang('pay_amount_order')."</th><th>".lang('label_shipping')."</th><th>".lang('status')."</th><th>".lang('payment')."</th>";
			$this->load->model('m_split_order');
			$status_map = array(
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
			);

			if($orders)foreach($orders as $order){
				$order_id = $order['order_prop'] == 1 ? $order['attach_id'] : $order['order_id'];
				$type = $this->m_split_order->get_order_type($order_id);
				if($type == '3'){
					$type_name = lang('admin_as_upgrade_order');
					$class = 'success';
				}else{
					$type_name = lang('admin_as_consumed_order');
					$class = '';
				}
				$order_amount = $order['order_amount_usd']/100;
				$deliver_fee_usd = $order['deliver_fee_usd']/100;
				$payment_name = lang('payment_'.$order['payment_type']);
				$table_str .= "<tr class='{$class}' ><td>{$order['order_id']}</td><td>$type_name</td><td>$order_amount</td><td>$deliver_fee_usd</td><td>{$status_map[$order['status']]}</td><td>{$payment_name}</td></tr>";
			}else{
				$table_str .= "<tr><th colspan='6' style='text-align: center;' class='text-success'> ".lang('no_item')."</th></tr>";
			}
			$table_str .= '</table>';
			$result['order_table'] = $table_str;
			die(json_encode(array('success'=>1,'result'=>$result)));

		}
	}

	public function get_order_info(){
		if($this->input->is_ajax_request()){
			$data = $this->input->post();
//			$orders = $this->db->select('order_id,order_amount_usd,deliver_fee_usd,status,payment_type')->
//				where('order_id',trim($data['order_id']))->where_in('order_prop',array('0','1'))->get('trade_orders')->result_array();
            $this->load->model("tb_trade_orders");
            $orders = $this->tb_trade_orders->get_list_auto([
                "select"=>"order_id,order_amount_usd,deliver_fee_usd,status,payment_type",
                "where"=>['order_id'=>trim($data['order_id']),'order_prop'=>array('0','1')],
            ]);
			$table_str = "<table class='table'><th>".lang('order_id')."</th><th>".lang('type')."</th><th>".lang('pay_amount_order')."</th><th>".lang('label_shipping')."</th><th>".lang('status')."</th><th>".lang('payment')."</th>";
			$this->load->model('m_split_order');
			$status_map = array(
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
			);

			if($orders)foreach($orders as $order){
				$type = $this->m_split_order->get_order_type($order['order_id']);
				if($type == '3'){
					$type_name = lang('admin_as_upgrade_order');
					$class = 'success';
				}else{
					$type_name = lang('admin_as_consumed_order');
					$class = '';
				}
				$order_amount = $order['order_amount_usd']/100;
				$deliver_fee_usd = $order['deliver_fee_usd']/100;
				$payment_name = lang('payment_'.$order['payment_type']);
				$table_str .= "<tr class='{$class}' ><td>{$order['order_id']}</td><td>$type_name</td><td>$order_amount</td><td>$deliver_fee_usd</td><td>{$status_map[$order['status']]}</td><td>{$payment_name}</td></tr>";
			}else{
				$table_str .= "<tr><th colspan='6' style='text-align: center;' class='text-success'> ".lang('no_item')."</th></tr>";
			}
			$table_str .= '</table>';
			$result['order_table'] = $table_str;

			$process = $this->db->where('order_id',$data['order_id'])->get('admin_after_sale_order')->result_array();
			$count = count($process);
			$tip = sprintf('此订单运费处理有%s次',$count);
			if($count == 0){
				$tip.="。";
			}else{
				$tip.="：";
			}
			if($process)foreach($process as $key=>$item){
				if($key == $count-1){
					$tip .= '$'.$item['refund_amount'];
				}else{
					$tip .= '$'.$item['refund_amount'].' 、';
				}
			}

			$result['order_count_str'] = $tip;

//			$c = $this->db->from('trade_orders')->where('order_id',$data['order_id'])->count_all_results();
            $this->load->model("tb_trade_orders");
			$c = $this->tb_trade_orders->get_counts(['order_id'=>$data['order_id']]);
			if($c == 0){
				$result['order_count_str'] = lang('order_not_exits');
			}

			die(json_encode(array('success'=>1,'result'=>$result)));
		}
	}

	/** 添加或修改记录 */
	public function do_add_after_sale(){

		$data = $this->input->post(NULL,TRUE);
                $data["refund_amount"] = Sbc2Dbc($data["refund_amount"]);
                if($data["method"]==2){
                    $data["refund_amount"] = sprintf("%.2f",$data["refund_amount"]);
                }
		$is_edit = FALSE;
		$as_order = $this->db->from('admin_after_sale_order')->where('as_id',$data['id'])->get()->row_array();
		if($as_order && isset($data['edit_as_id']) && $data['edit_as_id']){
			$is_edit = TRUE;
		}

		if(!isset($data['demote_level']) && $data['type'] == 1){
			die(json_encode(array('success'=>0,'msg'=>lang('no_operate'))));
		}

		if(in_array($data['type'],array(0,1))){
			$this->load->model('m_user');
			$user = $this->m_user->getUserByIdOrEmail($data['uid']);

			if($is_edit == FALSE && $data['uid']){

				if(!$user){
					die(json_encode(array('success'=>0,'msg'=>lang('no_exist'))));
				}
				if($user['status'] == 4 ){
					die(json_encode(array('success'=>0,'msg'=>lang('admin_as_status_error'))));
				}


				$is_dup = $this->db->from('admin_after_sale_order')->where('type','0')->where('status <>','6')->where('uid',$data['uid'])->count_all_results();
				if($is_dup){
					die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_repeat'))));
				}

				$is_dup2 = $this->db->from('admin_after_sale_order')->where('uid',$data['uid'])->where_in('status',array('0','1','4','5'))->count_all_results();
				if($is_dup2){

					die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_demote_info'))));
				}

			}
		}else if(in_array($data['type'],array(2)) && isset($data['order_id'])&&$data['order_id']){
//			$c = $this->db->from('trade_orders')->where('order_id',trim($data['order_id']))->where_in('order_prop',array('0','1'))->get()->row_array();
            $this->load->model("tb_trade_orders");
            $c = $this->tb_trade_orders->get_one("order_id,status,deliver_fee_usd,customer_id,",
                [
                    'order_id'=>trim($data['order_id']),
                    'order_prop'=>array('0','1')
                ]);
            if(!$c){
				die(json_encode(array('success'=>0,'msg'=>lang('order_not_exits'))));
			}
			if(in_array($c['status'],array(99,2,90,98,111))){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_return_fee_tip1'))));
			}
			if($data['refund_amount'] > ($c['deliver_fee_usd']/100)){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_return_fee_tip2'))));
			}
			$u = $this->db->select('status')->where('id',$c['customer_id'])->get('users')->row_array();
			if($u['status'] == 4){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_as_status_error'))));
			}
			$again = $this->db->select('status')->from('admin_after_sale_order')->where('order_id',$data['order_id'])->where('type','2')->get()->row_array();
			if($again && $again['status'] != 6 && $is_edit === FALSE ){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_return_fee_tip3'))));
			}
			$again_order = $this->db->from('admin_after_sale_order')->where('uid',$c['customer_id'])->where('type','0')
				->where_in('status',array('0','1','2','3','4','5','7'))->count_all_results();
			if($again_order > 0){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_return_fee_tip4'))));
			}
		}else{
//            $c = $this->db->from('trade_orders')->where('order_id',trim($data['order_id']))->where_in('order_prop',array('0','1'))->get()->row_array();
            $this->load->model("tb_trade_orders");
            $c = $this->tb_trade_orders->get_one("order_id",[
                'order_id'=>trim($data['order_id']),
                'order_prop'=>array('0','1')
            ]);
            if(!$c){
                die(json_encode(array('success'=>0,'msg'=>lang('order_not_exits'))));
            }
        }

		if($data['uid'] == "" && in_array($data['type'],array(0,1))){
			die(json_encode(array('success'=>0,'msg'=>lang('user_id_list_requied'))));
		}
		if($data['refund_amount'] == "" || $data['refund_amount'] == 0 ){
			die(json_encode(array('success'=>0,'msg'=>lang('refund_amount_error'))));
		}

		$add_data_arr = array(
			'as_id'=>$data['id'],
			'uid'=>$data['uid'],
			'name'=>$data['name'],
			'type'=>$data['type'],
			'refund_amount'=>$data['refund_amount'],
			'refund_method'=>$data['method'],
			'remark'=>$data['check_info'],
		);
		if($add_data_arr['refund_method'] == '0'){
			if($is_edit){
				$update_arr['account_bank'] = $data['account_bank'];
				$update_arr['card_number'] = $data['card_number'];
				$update_arr['account_name'] = $data['account_name'];
			}else{
				$add_data_arr['account_bank'] = $data['account_bank'];
				$add_data_arr['card_number'] = $data['card_number'];
				$add_data_arr['account_name'] = $data['account_name'];
			}
			if(!$data['account_bank'] || !$data['card_number'] ||!$data['account_name'] ){
				die(json_encode(array('success'=>0,'msg'=>"请输入支付宝信息")));
			}
		}else if($add_data_arr['refund_method'] == '2'){
            if($is_edit){
                $update_arr['card_number'] = $data['card_number'];
                $update_arr['account_name'] = $data['account_name'];
            }else{
                $add_data_arr['card_number'] = $data['card_number'];
                $add_data_arr['account_name'] = $data['account_name'];
            }
            if(!$data['card_number'] ||!$data['account_name'] ){
                die(json_encode(array('success'=>0,'msg'=>lang('payee_info_incomplete'))));
            }
            $add_data_arr['refund_amount'] = $add_data_arr['refund_amount'] - round($add_data_arr['refund_amount']*0.005,2);
        }else if($add_data_arr['refund_method'] == '1'){

			if($is_edit){

				$update_arr['transfer_uid'] = $data['transfer_uid'];

			}else{

				$add_data_arr['transfer_uid'] = $data['transfer_uid'];
			}

			if(!$data['transfer_uid']){
				die(json_encode(array('success'=>0,'msg'=>lang('payee_info_incomplete'))));
			}
			$count = $this->db->select('status')->from('users')->where('id',$data['transfer_uid'])->get()->row_array();
			if(!$count){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_as_payee_no_exist'))));
			}
			if($count['status'] == 4){
				die(json_encode(array('success'=>0,'msg'=>"收款人的状态是公司账号")));
			}

		}

		if($data['type'] == 1){
			$add_data_arr['demote_level'] = $data['demote_level'];
			/*if($user['is_choose'] == 1){
				$this->load->model('m_coupons');
				$coupons = $this->m_coupons->get_coupons_list($user['id'])['total_money'];

				$this->load->model('M_overrides','m_overrides');
				$duce_amount =  $this->m_overrides->getUpgradeProfit($user['user_rank'],$data['demote_level'],$user['id']);
				if($duce_amount > $coupons){
					die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_coupons'))));
				}
			}*/
		}

		if($data['check_info'] == ""){
			die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_remark_error'))));
		}else if($data['method'] == 1 && (!is_numeric($data['refund_amount']) || $data['refund_amount'] > 2000)){
			die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_amount_error'))));
		}

		$this->db->trans_start();
		if($is_edit){
			$update_arr['remark'] = $data['check_info'];
			$update_arr['refund_amount'] =$add_data_arr['refund_amount'];
			if($as_order['status'] == 5){
				$update_arr['status'] = 1;
			}else if($as_order['status'] == 4){
				$update_arr['status'] = 0;
			}
                        
			$this->db->where('as_id',$data['edit_as_id'])->update('admin_after_sale_order',$update_arr);
			$this->m_log->admin_after_sale_remark($data['edit_as_id'],$this->_adminInfo['id'],"提交修改申请信息");

			$affected_rows = $this->db->affected_rows();
		}else{

			$add_data_arr['admin_id'] = $this->_adminInfo['id'];
			$add_data_arr['admin_email'] = $this->_adminInfo['email'];
			if($data['type'] == 2 || $data['type'] == 3){
				$add_data_arr['uid'] = 0;
				$add_data_arr['order_id'] = trim($data['order_id']);
			}
                        if($data['type'] == 0){//退会的时候改变用户的状态为退会中，status=6
                            $this->db->where('id', $data["uid"])->set('status', 6, FALSE)->update('users');
                        }
			$this->db->insert('admin_after_sale_order',$add_data_arr);

			$affected_rows = $this->db->insert_id();

			$this->m_log->admin_after_sale_remark($data['id'],$this->_adminInfo['id'],'申请售后订单');
		}
		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE){
			die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
		}else{
			$this->db->trans_rollback();
			die(json_encode(array('success'=>0,'msg'=>'system error')));
		}

	}


}