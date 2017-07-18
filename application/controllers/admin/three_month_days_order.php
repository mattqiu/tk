<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Three_month_days_order extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_user');
        $this->load->model("tb_cash_account_log_x");
    }

    public function index($as_id = NULL) {
        $as_info = $this->db->where('as_id',$as_id)->where_in('status',array('4','5'))->get('admin_after_sale_order')->row_array();
        if($as_id == NULL || empty($as_info)){
                $this->_viewData['title'] = lang('admin_add_after_sale');
                $this->_viewData['as_id'] = $this->m_admin_user->create_after_sale_id();
        }else{
                $this->_viewData['title'] = lang('admin_as_update');
                $this->_viewData['as_info'] = $as_info;
        }
        //fout($as_info);exit;
        $this->_viewData['level_arr']  = array(
            0=>"降级等级",
            LEVEL_DIAMOND=>lang("diamond"),//砖石
            LEVEL_GOLD=>lang("gold"),//白金
            LEVEL_SILVER=>lang("silver"),//银级
            LEVEL_COPPER=>lang("bronze"),//铜级
            LEVEL_FREE=>lang("free"),//免费
        );
        parent::index('admin/');
    }
    /**
     * 
     */
    public function get_order_detail(){
        $new_data = "";
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
        $order_id = trim($this->input->post('order_id'));
        $data = $this->tmp_order($order_id);
        /******* 查询用户的两个上级，取消升级订单执行的日期开始，用户只有两级，查两级,没有必要递归 ,parent_ids字段不是很准确,决定查询的资金变动表 ********/
        $this->load->model('tb_users');
    	$user_up_one = $this->tb_users->getUserInfo($data[$order_id]["customer_id"], array('id, parent_id'));
        $up_user_arr = array();
        if(isset($user_up_one["parent_id"]) && $user_up_one["parent_id"]){
            $up_user_arr[] = substr($user_up_one["parent_id"],0,4);
            $user_up_two = $this->tb_users->getUserInfo($user_up_one["parent_id"], array('id, parent_id'));
            if(isset($user_up_two["parent_id"]) && $user_up_two["parent_id"]){
                if(substr($user_up_one["parent_id"],0,4) != substr($user_up_two["parent_id"],0,4)){
                    $up_user_arr[] = substr($user_up_two["parent_id"],0,4);
                }
            }
        }
        $is_sj = 0;
        $ff_order_arr = "";
    
        if(!empty($data)){
            foreach($data as $k=>$v){
                switch($v["order_type"]){
                    case "2":$v["type_name"] = lang('admin_as_upgrade_order');break;
                    case "5":$v["type_name"] = lang('exchange_order');break; 
                    default:$v["type_name"] = lang('admin_as_consumed_order');
                }
                $v["order_amount_usd"] = $v["order_amount_usd"]/100;
                $v["deliver_fee_usd"] = $v["deliver_fee_usd"]/100;
                $v["status_name"] = $status_map[$v["status"]];
                //fout($v);exit;
                $new_data["list"][] = $v;
                if(substr($v["order_id"],0,1) != "P"){//订单总额
                    if(isset($new_data["amout_all"])){
                        $new_data["amout_all"] += $v["order_amount_usd"];
                    }else{
                        $new_data["amout_all"] = $v["order_amount_usd"];
                    }
                }
                if((substr($v["order_id"],0,1) == "P" || substr($v["order_id"],0,1) == "N") && $v["pay_time"]){ //P单和N单才发放团队销售佣金,根据订单支付时间决定查询表
                    $where_arr = array("item_type"=>'3',"order_id"=>$v["order_id"]);//tpye为3指团队销售佣金的type
                    $time = date("Ym",strtotime($v["pay_time"]));
                    $up_user_arr = $time >= config_item("cash_account_log_cut_table_end_1") ? $up_user_arr : array($v["customer_id"]);
                    if(!empty($up_user_arr)){
                        foreach($up_user_arr as $uid){
                            $table = get_cash_account_log_table($uid,$time);
                            $this->tb_cash_account_log_x->set_table($table);
                            $param = [
                                'select_escape'=>"*,related_uid as pay_user_id,amount/100 as amount",
                                'where'=>[
                                    'item_type'=>3,
                                    'order_id'=>$v['order_id']
                                ],
                                'order'=>[
                                    [
                                        'key'=>'create_time',
                                        'value'=>"desc"
                                    ],
                                    [
                                        'key'=>"id",
                                        'value'=>'desc'
                                    ]
                                ],
                                'limit'=>10

                            ];
                            $ff_order_arr[] = $this->tb_cash_account_log_x->get($param);
                      }
                    }
                }
                if($is_sj==false){
                    if($v["order_type"]==2){
                        $is_sj = 1;
                    }
                }
            }
            $user_data = $this->db->select('name')->where('id',$data[$order_id]["customer_id"])->get('users')->row_array();
            $new_data["user_id"] = $data[$order_id]["customer_id"];
            $new_data["user_name"] = $user_data["name"];
        }
        echo json_encode(array("status"=>$is_sj,"data"=>$new_data,"ff_order"=>$this->Arr_merge_to_one($ff_order_arr)));
    }
    function Arr_merge_to_one($arr){
        $new_arr = "";
        if(is_array($arr)){
            foreach($arr as $k=>$v){
                if(is_array($v)){
                    foreach($v as $kk=>$vv){
                        $new_arr[] = $vv;
                    }
                }
            }
        }
        return $new_arr;
    }
    /**
     * 升级订单,非换货订单（拆分子订单C，拆分主订单P，普通订单N）
     * @param type $order_id
     * @return type
     */
    function tmp_order($order_id){
        $this->load->model('tb_trade_orders');
//        $data = $this->db->select("order_id,customer_id,order_type,attach_id,remark,created_at,pay_time")->where(array("order_id"=>$order_id))->get("trade_orders")->row_array();
        $data = $this->tb_trade_orders->get_one_auto([
            "select"=>"order_id,customer_id,order_type,attach_id,remark,created_at,pay_time",
            "where"=>["order_id"=>$order_id]
        ]);
        if(empty($data)){
            return "";
        }
//        $n_data = $this->db->select("order_id,customer_id,order_type,status,order_amount_usd,deliver_fee_usd,remark,pay_time")->where(array("attach_id"=>$data["attach_id"]))->get("trade_orders")->result_array();
        $n_data = $this->tb_trade_orders->get_list_auto([
            "select"=>"order_id,customer_id,order_type,status,order_amount_usd,deliver_fee_usd,remark,pay_time",
            "where"=>["attach_id"=>$data["attach_id"]]
        ]);
        foreach($n_data as $v){
            $new_data[$v["order_id"]] = $v;
        } 
        if($data["order_type"]==5){
            $tmp_order = explode("#", $data["remark"]);
            $trunk_order_id = $tmp_order[0];
            $new_tem_data = $this->tmp_order($trunk_order_id);
            foreach($new_tem_data as $v){
                $new_data[$v["order_id"]] = $v;
            } 
        }else{
            $uid = $data["customer_id"];
            //查询当前用户的换货订单
//            $all_change_trade = $this->db->select("order_id,customer_id,order_type,status,order_amount_usd,deliver_fee_usd,remark,pay_time")->where(array("customer_id"=>$uid,"order_type"=>5,"created_at >"=>$data["created_at"]))->get("trade_orders")->result_array();
            $all_change_trade = $this->tb_trade_orders->get_list_auto([
                "select"=>"order_id,customer_id,order_type,status,order_amount_usd,deliver_fee_usd,remark,pay_time",
                "where"=>array("customer_id"=>$uid,"order_type"=>5,"created_at >"=>$data["created_at"])
            ]);
            if($all_change_trade){
                foreach($all_change_trade as $av){
                    $remark = explode("#", $av["remark"]);
                    if(array_key_exists($remark[0], $new_data)){
                        $new_data[$av["order_id"]] = $av;
                    }
                }
            }
        }
        return $new_data;
    }
    /** 添加或修改记录 */
	public function do_add_after_sale(){

		$data = $this->input->post(NULL,TRUE);
                $data["type"] = 1;
                $data["refund_amount"] = Sbc2Dbc($data["refund_amount"]);
                if($data["method"]==2){
                    $data["refund_amount"] = sprintf("%.2f",$data["refund_amount"]);
                }
                
                if(isset($data["order_cance_amount"]) && empty($data["order_cance_amount"])){
                    die(json_encode(array('success'=>0,'msg'=>"请选择要取消的订单")));
                }
                if(isset($data["demote_level"]) && empty($data["demote_level"])){
                    die(json_encode(array('success'=>0,'msg'=>"请选择降级等级")));
                }
                
                if($data['method'] == 0 && (preg_match('/^[\x{4E00}-\x{9FA5}]+$/u', $data['account_bank'])==false || $data['account_bank']=="" || iconv_strlen($data['account_bank'],"UTF-8")> 50)){//退款到银行卡
                    die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_brank_name'))));
                }
                if($data['method'] == 0 && (is_numeric($data['card_number'])==false || $data['card_number']=="" || iconv_strlen($data['card_number'],"UTF-8")> 50)){//卡号
                    die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_brank_num'))));
                }
                if($data['method'] == 0 && (preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$data['account_name']) || $data['account_name']=="" || iconv_strlen($data['account_name'],"UTF-8")> 50)){//退款到银行卡
                    die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_brank_pop'))));
                }
                if($data['refund_amount'] == ""){
			die(json_encode(array('success'=>0,'msg'=>lang('refund_amount_error'))));
		}
		$is_edit = FALSE;
		$as_order = $this->db->from('admin_after_sale_order')->where('as_id',$data['id'])->get()->row_array();
                
                if($as_order && isset($data['edit_as_id']) && $data['edit_as_id']){
			$is_edit = TRUE;
		}
                $this->load->model('m_user');
                $user = $this->m_user->getUserByIdOrEmail($data['uid']);
                if($is_edit == FALSE && $data['uid']){
                    if(!$user){
                            die(json_encode(array('success'=>0,'msg'=>lang('no_exist'))));
                    }
                    $is_dup2 = $this->db->from('admin_after_sale_order')->where('uid',$data['uid'])->where_in('status',array('0','1','4','5'))->count_all_results();
                    if($is_dup2){
                            die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_demote_info'))));
                    }
                    $is_dup1 = $this->db->from('admin_after_sale_order')->where('order_id',$data['order_id'])->where_in('status',array('2','3','1'))->count_all_results();
                    if($is_dup1){
                            die(json_encode(array('success'=>0,'msg'=>'此订单已经取消并抽回,不允许重复提交')));
                    }

                }
		if($data['uid'] == "" && in_array($data['type'],array(0,1))){
			die(json_encode(array('success'=>0,'msg'=>lang('user_id_list_requied'))));
		}
                /*
		if($data['refund_amount'] == "" || $data['refund_amount'] == 0 ){
			die(json_encode(array('success'=>0,'msg'=>lang('refund_amount_error'))));
		}
                 * 
                 */

		$add_data_arr = array(
			'as_id'=>$data['id'],
			'uid'=>$data['uid'],
			'name'=>$data['name'],
			'type'=>$data['type'],
			'refund_amount'=>$data['refund_amount'],
                        'demote_level' => $data['demote_level'],
                        'order_id' => $data['order_id'],
			'refund_method'=>$data['method'],
			'remark'=>$data['check_info'],
                        'is_three_month'=>1,
                        'order_count_amount'=>$data['dd_count'],
                        'order_cance_amount'=>$data['order_cance_amount'],
		);
                //转入银行卡
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
				die(json_encode(array('success'=>0,'msg'=>"请输入银行卡信息")));
			}
                        $add_data_arr['refund_amount'] = $add_data_arr['refund_amount'] - round($add_data_arr['refund_amount']*0.005,2);
                     //转入支付宝
		}else if($add_data_arr['refund_method'] == '2'){
                            $add_data_arr['card_number'] = $data['card_number'];
                            $add_data_arr['account_name'] = $data['account_name'];
                        if(!$data['card_number'] ||!$data['account_name'] ){
                            die(json_encode(array('success'=>0,'msg'=>lang('payee_info_incomplete'))));
                        }
                        $add_data_arr['refund_amount'] = $add_data_arr['refund_amount'] - round($add_data_arr['refund_amount']*0.005,2);
                        //转入现金池
                }else if($add_data_arr['refund_method'] == '1'){

                                $add_data_arr['transfer_uid'] = $data['transfer_uid'];


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
		if($data['check_info']==""){
			die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_remark_error'))));
		}else if($data['method'] == 1 && (!is_numeric($data['refund_amount']) || $data['refund_amount'] > 2000 || $data['refund_amount'] == "" || $data['refund_amount']<0)){
			die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_amount_error'))));
		}
               
		$this->db->trans_start();
		if($is_edit){
                    $update_arr['transfer_uid'] = $data['transfer_uid'];
                    $update_arr['demote_level'] = $data['demote_level'];
                    $update_arr['remark'] = $data['check_info'];
                    $update_arr['refund_amount'] =$add_data_arr['refund_amount'];
                    if($as_order['status'] == 5){
                            $update_arr['status'] = 1;
                    }else if($as_order['status'] == 4){
                            $update_arr['status'] = 0;
                    }
                    //fout($update_arr);exit;
                    $this->db->where('as_id',$data['edit_as_id'])->update('admin_after_sale_order',$update_arr);
                    $this->m_log->admin_after_sale_remark($data['edit_as_id'],$this->_adminInfo['id'],"提交修改申请信息");
                    $affected_rows = $this->db->affected_rows();
		}else{
                    $add_data_arr['admin_id'] = $this->_adminInfo['id'];
                    $add_data_arr['admin_email'] = $this->_adminInfo['email'];
                    //fout($add_data_arr);exit;
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