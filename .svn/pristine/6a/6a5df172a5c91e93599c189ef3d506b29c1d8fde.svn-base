<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin_demo_level extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->m_global->checkPermission('admin_demo_level',$this->_adminInfo);
        $this->_viewData['title'] = '售后订单特别处理';
    }

  
    public function index() {
        parent::index('admin/');
    }
    function do_demo_level(){
        if($this->input->is_ajax_request()){
            $searchData = $this->input->post(NULL,TRUE);
            $as_id = isset($searchData['as_id'])?$searchData['as_id']:'';
            $order_id = isset($searchData['order_id'])?$searchData['order_id'].",":'';//加逗号 
            $exchange_rate = isset($searchData['exchange_rate'])?$searchData['exchange_rate']:'';
            $new_exchange_rate = explode('/', $exchange_rate);
            $new_exchange_rate[1] = isset($new_exchange_rate[1]) ? $new_exchange_rate[1] : 1;
            if(empty($as_id) || empty($order_id) || empty($exchange_rate)){
                die(json_encode(array('success'=>0,'msg'=>'信息不完整!')));
            }
            $this->load->model('o_cash_account');
            /** 事务开始  **/
            $this->db->trans_begin();
            $this->load->model('m_admin_helper');
            //检测订单是否存在
            $order = $this->db->where('as_id',$as_id)->get('admin_after_sale_order')->row_array();
            if(!$order){
                die(json_encode(array('success'=>0,'msg'=>lang('admin_as_not_exist'))));//售后订单不存在
            }
            if(in_array($order['type'],array(0,1))){
                //查询用户相关信息
                $user = $this->db->select('id,status,is_choose,user_rank,first_monthly_fee_level')->where('id',$order['uid'])->get('users')->row_array();
                if($user['status'] == 4){
                    echo lang('admin_as_status_error');exit;
                    die(json_encode(array('success'=>0,'msg'=>lang('admin_as_status_error'))));
                }
                if($order['type'] == '0'){
                    $store_level = 4;
                    $monthly_fee_level = 4;
                }else{
                    $store_level = $order['demote_level'];
                    $monthly_fee_level = 4;
                     //会员代品券 和分红点算法
                    if($user['is_choose'] == 1){
                        $this->load->model('m_coupons');
                        $coupons = $this->m_coupons->get_coupons_list($user['id'])['total_money'];
                        if((int)$order['refund_amount'] > $coupons){
                             die(json_encode(array('success'=>0,'msg'=>lang('admin_after_sale_coupons'))));
                        }
                        //减去会员代品券 
                        $this->load->model('m_suite_exchange_coupon');
                        $this->m_suite_exchange_coupon->useCoupon($order['uid'],(int)$order['refund_amount']);

                        // 减去分红点 
                        $this->load->model('m_overrides');
                        $this->m_overrides->reduce_sharing_point($order['uid'],(int)$order['refund_amount']);
                    }
                }
            }

            if(in_array($order['type'],array(0,1))){
                $this->load->model('m_overrides');
                $this->load->model('tb_cash_account_log_x');
                $this->load->model('m_user');
                $is_true = $this->m_user->is_first_upgrade_time_1_1($user["id"]);//检查用户是否是一月一日前的用户，因月费等级有变,查询月费
                if($is_true){
                        $point = config_item('old_join_fee_and_month_fee');
                }else{
                        $point = $this->m_user->getJoinFeeAndMonthFee();
                }

                /*用户等级相关，月费相关，更新日志等*/
                $this->m_overrides->updateUsersAllStatus($user["id"],$user,$store_level,$point);
                $tmp_order_arr = explode(',', $order_id);
                foreach($tmp_order_arr as $tmp_order){
                    $yearMonth = substr($tmp_order,1,6);//通过订单号确定订单时间
                    $start_time_for_year_month = substr($yearMonth,0,4)."-".substr($yearMonth,4,2)."-01 00:00:00";
                    if($yearMonth>201606){
                        $table_uid = substr($order['uid'], 0,4);
                        $table = $yearMonth > 201705 ? 'cash_account_log_'.$yearMonth.'_'.$table_uid : 'cash_account_log_'.$yearMonth;
                        $search_arr = array("item_type"=>'3',"create_time"=>$start_time_for_year_month,"order_id"=>$tmp_order);//tpye为3指团队销售佣金的type
                        $sql = "SELECT *,related_uid as pay_user_id,amount/100 as amount FROM {$table}";
                      //  $this->db->from('cash_account_log_'.$yearMonth);
                       // $this->db->where($where);
                        $where = '';
                        foreach($search_arr as $k=>$v){
                            if($k=='create_time'){
                                //如果只是年月，已分表数据库不需要本条件
                                //$where.=' and create_time >="'.$v.'"';
                            }else{
                            //    $this->db->where($k,$v);
                                $where.=' and '.$k.' ="'.$v.'"';
                            }
                        }
                        $sql.=$where?' where 1=1'.$where:'';
                        $sql.=' order by create_time DESC,id DESC limit 10';
                        //fout($sql);exit;
                        $comm_logs = $this->db->query($sql)->result_array();
                    }else{
                        $where = array("type"=>'3',"create_time >="=>$start_time_for_year_month,"order_id"=>$tmp_order);//tpye为3指团队销售佣金的type
                        $this->db->select("*");
                        $this->db->from('commission_logs');
                        $this->db->where($where);
                        $comm_logs = $this->db->order_by("create_time", "desc")->order_by('id','desc')->limit(10,0)->get()->result_array();
                    }
                    if($comm_logs){
                        
                        foreach($comm_logs as $comm_log){
                            $gsl = $comm_id = '';
                            $real_cash = $comm_log["amount"]* ($new_exchange_rate[0]/$new_exchange_rate[1]);
                            //添加新的佣金记录
                            $this->load->model('m_commission');
                            $comm_id = $this->m_commission->commissionLogs($comm_log['uid'],16,-1*$real_cash,$comm_log['pay_user_id']);
                            $gsl = $this->db->where('parent_id',$comm_log['uid'])->where('child_id',$comm_log['pay_user_id'])->get('generation_sales_logs')->row_array();
                            if(isset($gsl) && !empty($gsl)){//因这个表很多数据缺失，故先查询，如果有数据则写入，避免报错
                                $profit = $this->m_overrides->getUpgradeProfit($store_level,4,$user["id"])*0.8;
                                $this->m_generation_sales->generationSalesLogs($gsl['parent_id'],$gsl['child_id'],$gsl['level'],$profit,$gsl['percent'],-1*$real_cash,$comm_id);
                            }
                            $this->m_commission->reduceCommissionLogs($comm_log['uid'],$real_cash,3,$comm_log['pay_user_id']);
                            $this->db->where('id', $comm_log['uid'])->set('amount', 'amount-' . $real_cash, FALSE)->set('team_commission', 'team_commission-' . $real_cash, FALSE)->update('users');
                        }
                    }else{
                        die(json_encode(array('success'=>0,'msg'=>'订单没有发放佣金')));
                    }
                }
            }
            if($order['refund_method'] == '0' || $order['refund_method'] == '2'){
                $as_status = 1;
                $remark = '已抽回(待付款)';
            }else{
                $this->m_commission->commissionLogs($order['transfer_uid'],9,$order['refund_amount']);
                $this->db->where('id',$order['transfer_uid'])->set('amount','amount +'.$order['refund_amount'],FALSE)->update('users');
                $as_status = 2;
                $remark = '已抽回（已退款到现金池）';
            }
            if($order['type'] == 0){//退会的时候审核通过改变用户的状态为公司预留，status=4
                $this->db->where('id', $order["uid"])->set('status', 4, FALSE)->update('users');
            }
            $this->m_log->admin_after_sale_remark($as_id,$this->_adminInfo['id'],$remark);
            $this->db->where('as_id',$as_id)->update('admin_after_sale_order',array('status'=>$as_status));

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                die(json_encode(array('success'=>0,'msg'=>lang('update_failure'))));
            } else{
                $this->db->trans_commit();
                die(json_encode(array('success'=>1,'msg'=>lang('update_success'))));
            }
        }
    }
}