<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Demote_levels extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('demote_levels',$this->_adminInfo);
    }
    public function index($id = NULL) {

        $this->_viewData['title'] = lang('demote_level');
        parent::index('admin/');
    }

    /** 檢查用戶是否可以降級 */
    public function checkStoreLevel(){
         if($this->input->is_ajax_request()){
             $id = $this->input->post('id');
             $this->load->model('m_user');
             $user = $this->m_user->getUserByIdOrEmail($id);

             if($user && $user['status'] != 0){
                 $result['store_level_option'] = $result['monthly_fee_level_option'] = '';
                 foreach(config_item('user_ranks') as $k =>$value){
                     if($k >= $user['user_rank']){
                         $result['store_level_option'] .= "<option value=".$k.">".lang($value)."</option>";
                     }
                 }
                 foreach(config_item('monthly_fee_ranks') as $k =>$value){
                     if($k >= $user['month_fee_rank']){
                         $result['monthly_fee_level_option'] .= "<option value=".$k.">".lang($value)."</option>";
                     }
                 }
                 $result['store_level'] = lang(config_item('user_ranks')[$user['user_rank']]);
                 $result['store_name'] = $user['name'];
                 $result['store_status'] = lang('status_'.$user['status']);
                 $result['monthly_fee_level'] = lang(config_item('monthly_fee_ranks')[$user['month_fee_rank']]);
                 die(json_encode(array('success'=>1,'result'=>$result)));
             }else{
                 die(json_encode(array('success'=>0,'msg'=>lang('no_exist'))));
             }
         }
    }

    /** 执行降级操作 */
    public function do_demote(){
        if($this->input->is_ajax_request()){
            $data  =  $this->input->post();

            if(!$data['check_info']){
                die(json_encode(array('success'=>0,'msg'=>lang('remark').lang('required'))));
            }

            /** 不能有提现 不能有转账 */
            $this->load->model('m_admin_helper');
            if($this->m_admin_helper->isWithdrawal($data['id'])){
                //die(json_encode(array('success'=>0,'msg'=>lang('is_withdrawal'))));
            }
            if($this->m_admin_helper->isTransfer($data['id'])){
                //die(json_encode(array('success'=>0,'msg'=>lang('is_transfer'))));
            }
			$user = $this->db->select('status')->where('id',$data['id'])->get('users')->row_array();
			if($user['status'] == 4){
				die(json_encode(array('success'=>0,'msg'=>lang('admin_as_status_error'))));
			}

			$this->db->trans_begin();
            $this->load->model('m_overrides');
            $this->m_overrides->do_demote($data['id'],$data['store_level_option'],$data['monthly_fee_level_option'],$this->_adminInfo['id'],$data['check_info']);

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				die(json_encode(array('success'=>0,'msg'=>lang('update_failure'))));
			}
			else
			{
				$this->db->trans_commit();
				die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
			}

        }
    }

	/** 执行降级操作 */
	public function admin_do_demote(){

		if($this->input->is_ajax_request()){
			$as_id  =  $this->input->post('as_id',true);
                        $is_three_month = $this->input->post('is_three_month',true);
                        if($is_three_month){
                            $this->three_month_order($as_id);
                        }else{
                            /** 不能有提现 不能有转账 */
                            $this->load->model('m_admin_helper');

                            $order = $this->db->where('as_id',$as_id)->get('admin_after_sale_order')->row_array();
                            if(!$order){
                                    die(json_encode(array('success'=>0,'msg'=>lang('admin_as_not_exist'))));
                            }

                            $this->db->trans_begin();

                            if(in_array($order['type'],array(0,1))){//降级或者退会
                                $user = $this->db->select('id,status,is_choose,user_rank')->where('id',$order['uid'])->get('users')->row_array();
                                if($user['status'] == 4){
                                        die(json_encode(array('success'=>0,'msg'=>lang('admin_as_status_error'))));
                                }
                                /**月费等级没用  2017-4-20**/
                                if($order['type'] == '0'){
                                        $store_level = 4;
                                        $monthly_fee_level = 4;
                                }else{
                                    $store_level = $order['demote_level'];
                                    $monthly_fee_level = 4;
                                }
                                $this->load->model('m_coupons');
                                $this->load->model('m_suite_exchange_coupon');
                                $fhd_amount = (int)$order['refund_amount'];//分红点的金额
                                $coupons = $this->m_coupons->get_coupons_list($user['id'])['total_money'];//获取会员代品券总金额
                                /*
                                
                                 * 
                                 */
                                //fout($order);exit;
                               // $order['refund_amount'] = (int)$order['refund_amount'] > 0 ? $coupons : 0;
                                /** 减去会员代品券 */
                                if($order['type']==0){//退会的时候清空代品券
                                    $this->m_suite_exchange_coupon->useCoupon($order['uid'],$coupons);
                                }else{//降级的时候
                                    if($coupons > (int)$order['refund_amount']){//要退会员代品券金额，代品券不足的时候以代品券总金额为准判断是否退钱或者退多少钱
                                        $order['refund_amount'] = (int)$order['refund_amount'];
                                    }else{
                                        $order['refund_amount'] = $coupons;
                                    }
                                    $this->m_suite_exchange_coupon->useCoupon($order['uid'],$order['refund_amount']);
                                }
                                /** 减去分红点 */
                                $this->load->model('m_overrides');
                                $this->m_overrides->reduce_sharing_point($order['uid'],$fhd_amount);
                            }
                            if(in_array($order['type'],array(0,1))){
                                    $this->load->model('m_overrides');
                                    $this->m_overrides->new_do_demote($order['uid'],$store_level,$monthly_fee_level,$this->_adminInfo['id'],$order['type']);
                            }else if(in_array($order['type'],array(2)) && $order['refund_method'] == 1){ /** 退款到现金池 记录 */

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
                            }
                            if($order['refund_method'] == '0' || $order['refund_method'] == '2'){
                                    $as_status = 1;
                                    $remark = '已抽回(待付款)';
                            }else{
                                switch($order['type']){
                                    case 0 : $txt = $order["uid"].",于".date("Y-m-d",time())." 退会退款";break;
                                    case 1 : $txt = $order["uid"].",于".date("Y-m-d",time())." 降级退款";break;
                                    case 3 : $txt = $order["remark"];break;//退货退款直接用客服提交退货售后订单时填写的“反馈内容”
                                }
                                $this->load->model('m_commission');
                                $this->m_commission->commissionLogs($order['transfer_uid'],9,$order['refund_amount'],0,"","",$txt);
                                $this->db->where('id',$order['transfer_uid'])->set('amount','amount +'.$order['refund_amount'],FALSE)->update('users');
                                $as_status = 2;
                                $remark = '已抽回（已退款到现金池）';
                            }

                            $this->m_log->admin_after_sale_remark($as_id,$this->_adminInfo['id'],$remark);
                            $this->db->where('as_id',$as_id)->update('admin_after_sale_order',array('status'=>$as_status));

                            if ($this->db->trans_status() === FALSE)
                            {
                                    $this->db->trans_rollback();
                                    die(json_encode(array('success'=>0,'msg'=>lang('update_failure'))));
                            }
                            else
                            {
                                    $this->db->trans_commit();
                                    die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
                            }
                        }
                    }
	}
        function three_month_order($as_id){
                $this->load->model('o_cash_account');
                /** 事务开始  **/
                $this->db->trans_begin();
                $this->load->model('m_admin_helper');
                //检测订单是否存在
                $order = $this->db->where('as_id',$as_id)->get('admin_after_sale_order')->row_array();
                if(!$order){
                    die(json_encode(array('success'=>0,'msg'=>lang('admin_as_not_exist'))));
                }
                //查询用户相关信息
                $this->load->model('m_user');
                $user = $this->m_user->getUserByIdOrEmail($order['uid']);
               // $user = $this->db->select('id,status,is_choose,user_rank,first_monthly_fee_level')->where('id',$order['uid'])->get('users')->row_array();
                
                if($user['status'] == 4){
                    die(json_encode(array('success'=>0,'msg'=>lang('admin_as_status_error'))));
                }

                $store_level = $order['demote_level'];
                 //会员代品券 和分红点算法 未改动 

               // if($user['is_choose'] == 1){
                    $this->load->model('m_coupons');
                    /*
                    $coupons = $this->m_coupons->get_coupons_list($user['id'])['total_money'];
                    if((int)$order['refund_amount'] > $coupons){
                            die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_coupons'))));
                    }
                     * 
                     */

                    // 减去分红点 
                    $is_true = $this->m_user->is_first_upgrade_time_1_1($user["id"]);//检查用户是否是一月一日前的用户，因月费等级有变,查询月费

                    if($is_true){
                            $point = config_item('old_join_fee_and_month_fee');
                    }else{
                            $point = $this->m_user->getJoinFeeAndMonthFee();
                    }
                    $this->load->model('m_overrides');
                    if($store_level==4){//如果退到免费，则清空用户的月费券
                        $this->db->where('uid',$order['uid'])->delete('monthly_fee_coupon');
                    }
                    //echo $this->db->last_query();exit;
                    $refund_amount = $point[$user['user_rank']]["join_fee"] - $point[$store_level]["join_fee"];//所抽金额根据会员当前等级和下降目标等级之间的升级费用差额来计算
                    $this->m_overrides->reduce_sharing_point($order['uid'],(int)$refund_amount);//抽回奖励分红
                   // $this->m_overrides->reduceDailyBonus($order['uid'],$store_level,$user,$point);//抽回日分红
                    /*用户等级相关，月费相关，更新日志等*/
                    $this->m_overrides->new_updateUsersAllStatus($user["id"],$user,$store_level,$point,$user['user_rank']);
             //   }
                
                $this->load->model('m_overrides');
                $this->load->model('tb_cash_account_log_x');
                
                $tem_remark = explode("*", $order["remark"]);
                $order_arr = explode("#", $tem_remark[0]);
                $new_data = "";
                $this->load->model("tb_trade_orders");
                
                foreach($order_arr as $ov){//查询每个上线拿了多少佣金
                    if($ov){
                         $time = "";
//                         $time = $this->db->select("pay_time")->from("trade_orders")->where("order_id",$ov)->get()->row_array();
                         $time = $this->tb_trade_orders->get_one_auto([
                             "select"=>"pay_time",
                             "where"=>["order_id"=>$ov],
                         ]);
                         if(isset($time["pay_time"]) && $time["pay_time"]!="0000-00-00 00:00:00"){
                            /******* 查询用户的两个上级，取消升级订单执行的日期开始，用户只有两级，查两级,没有必要递归 ,parent_ids字段不是很准确,决定查询的资金变动表 ********/
                            $this->load->model('tb_users');
                            $user_up_one = $this->tb_users->getUserInfo($order['uid'], array('id, parent_id'));
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
                            $yearMonth = date("Ym",strtotime($time["pay_time"]));
                            $up_user_arr = $yearMonth >= config_item("cash_account_log_cut_table_end_1") ? $up_user_arr : array($order['uid']);
                            $comm_logs = "";
                            $search_arr = array("item_type"=>'3',"order_id"=>$ov);//tpye为3指团队销售佣金的type
                            $this->load->model("tb_cash_account_log_x");
                            if(!empty($up_user_arr)){
                                foreach($up_user_arr as $uid){
                                    $table = get_cash_account_log_table($uid,$yearMonth);
                                    $this->tb_cash_account_log_x->set_table($table);
                                    $param = [
                                        'select_escape'=>"*,related_uid as pay_user_id,amount/100 as amount",
                                        'where'=>[
                                            'item_type'=>3,
                                            'order_id'=>$ov
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
                                    $comm_logs[] = $this->tb_cash_account_log_x->get($param);
                                }
                            }
                           // fout($comm_logs);exit;
                            if(!empty($comm_logs))foreach($comm_logs as $c_first){
                                if(!empty($c_first))foreach($c_first as $cv){
                                    if(isset($new_data[$cv["uid"]])){
                                        $new_data[$cv["uid"]] +=$cv["amount"];
                                    }else{
                                        $new_data[$cv["uid"]] =$cv["amount"];
                                    }
                                }
                            }
                        }
                     }
                }
                //fout($new_data);exit;
                $this->load->model('m_debug');
                $this->m_debug->log($new_data);
                if($new_data)foreach($new_data as $comm_k=>$comm_log){
                    $gsl = $comm_id = '';
                    $real_cash =sprintf("%.2f", ($order["order_cance_amount"] * $comm_log) /$order["order_count_amount"]);
                    //$real_cash = $comm_log["amount"];
                    //添加新的佣金记录
                    $this->load->model('m_commission');
                    $comm_id = $this->m_commission->commissionLogs($comm_k,16,-1*$real_cash,$user["id"],"","","下级会员ID:".$order["uid"].",于".date("Y-m-d",time())."取消升级订单，"."由".config_item('user_rank_name')[$user['user_rank']]."降至".config_item('user_rank_name')[$store_level].",抽回团队销售佣金");
                    $gsl = $this->db->where('parent_id',$comm_k)->where('child_id',$user["id"])->get('generation_sales_logs')->row_array();
                    if(isset($gsl) && !empty($gsl)){//因这个表很多数据缺失，故先查询，如果有数据则写入，避免报错
                        $profit = $this->m_overrides->getUpgradeProfit($store_level,4,$user["id"])*0.8;
                        $this->m_generation_sales->generationSalesLogs($gsl['parent_id'],$gsl['child_id'],$gsl['level'],$profit,$gsl['percent'],-1*$real_cash,$comm_id);
                    }
                    $this->m_commission->reduceCommissionLogs($comm_k,$real_cash,3,$user["id"]);
                    $this->db->where('id', $comm_k)->set('amount', 'amount-' . $real_cash, FALSE)->set('team_commission', 'team_commission-' . $real_cash, FALSE)->update('users');
                    $this->m_debug->log($this->db->last_query());
                }
                
                if($order['refund_method'] == '0' || $order['refund_method'] == '2'){
                    $as_status = 1;
                    $remark = '已抽回(待付款)';
                }else{
                    $this->m_commission->commissionLogs($order['transfer_uid'],9,$order['refund_amount'],0,"","",$order["uid"].",于".date("Y-m-d",time())." 由".config_item('user_rank_name')[$user['user_rank']]."降至".config_item('user_rank_name')[$store_level]."退款");
                   
                    $this->db->where('id',$order['transfer_uid'])->set('amount','amount +'.$order['refund_amount'],FALSE)->update('users');
                    $as_status = 2;
                    $remark = '已抽回（已退款到现金池）';
                }

                $this->m_log->admin_after_sale_remark($as_id,$this->_adminInfo['id'],$remark);
                $this->db->where('as_id',$as_id)->update('admin_after_sale_order',array('status'=>$as_status));
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                //echo lang('update_failure');exit;
                die(json_encode(array('success'=>0,'msg'=>lang('update_failure'))));
            } else{
                $this->db->trans_commit();
               //  echo lang('update_success');exit;
                die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
            }
        }
}