<?php

class m_commission extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->load->model("tb_cash_account_log_x");
    }

    /** 添加佣金记录
     * @param $uid
     * @param $type
     * @param $amount
     * @param $child_id
     * @param $order_id
     */
    public function commissionLogs($uid,$type,$amount,$child_id=0,$order_id = '',$create_time='',$remark=''){

        $this->load->model('o_cash_account');
        $amount = tps_int_format($amount*100);
        return $this->o_cash_account->createCashAccountLog(array(
            'uid'=>$uid,
            'item_type'=>$type,
            'amount'=>$amount,
            'create_time'=>$create_time,
            'related_uid'=>$child_id,
            'order_id'=>$order_id,
            'remark' => $remark
        ));
    }

    /**
    *检测用户是否拿过某种奖金
    * @param int $uid
    * @param int 佣金类型
    * @author Terry
    */
    public function checkUserComm($uid,$commType,$start_time=''){

        $this->load->model('o_cash_account');
        $data = array(
            'uid'=>$uid,
            'item_type'=>$commType
        );
        if (!empty($start_time)) {
           $data['start_time'] = $start_time;
        }
        $num = $this->o_cash_account->getCashAccountLogNum($data);
        return $num>0?true:false;
    }

    /** 月费变动表 */
    public function monthFeeChangeLog($uid,$old,$new,$money,$create_time,$type=0,$old_coupon_num=0,$coupon_num=0,$coupon_num_change=0){
        $log = array(
            'user_id'=>$uid,
            'old_month_fee_pool'=>$old,
            'month_fee_pool'=> $new,
            'cash'=>$money,
            'create_time'=>$create_time,
            'type'=>$type,
            'old_coupon_num'=>$old_coupon_num,
            'coupon_num'=>$coupon_num,
            'coupon_num_change'=>$coupon_num_change,
        );
        $this->db->insert('month_fee_change',$log);
    }

    /** 佣金抽回記錄 */
    public function reduceCommissionLogs($uid,$amount,$type,$pay_user_id = 0){
        $log = array(
            'uid'=>$uid,
            'amount'=>$amount,
            'type'=> $type,
            'pay_user_id'=> $pay_user_id,
        );
        $this->db->insert('user_reduce_commission_logs',$log);
    }

    /*获取会员当日佣金 By Terry*/
    public function getTodayComm($uid){
        
        $redis_key = "TodayComm:".$uid;
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            return $tmp;
        }
        
        $this->load->model('o_cash_account');
        $amount = $this->o_cash_account->getSumAmount(array(
            'uid'=>$uid,
            'item_type in'=>array(1,2,3,4,5,6,7,8,23,24,25,26,27),
            'start_time'=>date('Y-m-d'),
        ));
        $this->redis_set($redis_key,$amount/100,300);
        return $amount/100;
    }

    public function getMemberTotalComm($uid,$commType){

        $redis_key = "dayliTotalComm:".$uid.":".$commType;
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            return $tmp;
        }
        
        $this->load->model('o_cash_account');
        $amount = $this->o_cash_account->getSumAmount(array(
            'uid'=>$uid,
            'item_type'=>$commType
        ));

        $this->redis_set($redis_key,$amount/100,300);
        
        return $amount/100;
    }

    /*获取会员当月佣金 By Terry*/
    public function getCurMonthComm($uid){
        
        $redis_key = "CurMonthComm:".$uid;
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            return $tmp;
        }
        
        $this->load->model('o_cash_account');
        $amount = $this->o_cash_account->getSumAmount(array(
            'uid'=>$uid,
            'item_type in'=>array(1,2,3,4,5,6,7,8,19,16,23,24,25,26),
            'start_time'=>date('Y-m-1'),
        ));
        $this->redis_set($redis_key,$amount/100,180); 
        return $amount/100;
    }
    
    /*
     * @desc 获取会员当月各项佣金
     * @author JacksonZheng
     * @return array
     * @date 2017-04-17
     */
    public function getCurMonthEachComm($uid) {
        $redis_key = "CurMonthEachComm:".$uid;
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            return unserialize($tmp);
        }
        $this->load->model('tb_cash_account_log_x','account_log_x');
        $res = $this->account_log_x->getSumAmountGroupByType(array(
            'uid'=>$uid,
            'curMonth'=>date('Ym'),
        ));
        
        $tmp = serialize($res);
        $this->redis_set($redis_key,$tmp,300);
        return $res;
        
    }
    
    /*
     * @desc 获取会员历史月份各项佣金
     * @author JacksonZheng
     * @return array
     * @date 2017-04-17
     */
    
    public function getHistoryMonthEachComm($search)  {
         $item_str = $search['item_str'];
         $uid = $search['uid'];
         $last_month = date('Ym', strtotime('last month'));  //上个月时间
         $search_month = $search['search_month'];
         $start_time = $search['start_time'];
         $end_time = $search['end_time'];
         $redis_key = "HistoryMonthComm:$uid:$search_month";
         $data = array();
         $tmp = $this->redis_get($redis_key);
         if($tmp)
         {
               // return unserialize($tmp);
         }
        $item_arr = explode(',',$item_str);
        if(isset($search['commission_logs'])) {    //查询commission_logs旧表
            $sql = "SELECT type as item_type,SUM(amount) as sum_amount FROM commission_logs where type in({$item_str}) and uid={$uid} and create_time>='{$start_time}' and  create_time <='{$end_time}' group by type";
//            $this->tb_cash_account_log_x->set_table("commission_logs");
//
//            $param = [
//                'select_escape'=>"type as item_type,SUM(amount) as sum_amount",
//                'where'=>[
//                    'uid'=>$uid,
//                    'create_time >='=>$start_time,
//                    'create_time <='=>$end_time
//                ],
//                'where_in'=>[
//                    'key'=>'type',
//                    'value'=>$item_arr
//                ],
//                'group'=>"type"
//            ];
            $res = $this->db->query($sql)->result_array();

        } else {
            $table = get_cash_account_log_table($uid,$search_month);
            $this->tb_cash_account_log_x->set_table($table);
            //$sql = "SELECT item_type,SUM(amount) as sum_amount FROM {$table} where item_type in({$item_str}) and uid= {$uid} group by item_type";
            $param = [
                'select_escape'=>'item_type,SUM(amount) as sum_amount',
                'where_in'=>[
                    'key'=>"item_type",
                    'value'=>$item_arr
                ],
                'where'=>[
                    'uid'=>$uid
                ],
                'group'=>"item_type"
            ];
            $res = $this->tb_cash_account_log_x->get($param);

        }
        if (count($res) > 0)
        {
           foreach ($res as $row)
           {
               $k = $row['item_type'];
               $data[$k] = $row['sum_amount'];
              
           }
        } 
        $tmp = serialize($data);
        $this->redis_set($redis_key,$tmp,3600*24*30*3);
        return $data;
        
    }
    

    //销售套装日分红
    public function sale_group_day_bonus($uid){
        
        $redis_key = "saleGroupDayBonus:".$uid;
        $tmp = $this->redis_get($redis_key);
        if($tmp)
        {
            return $tmp;
        }
        
        $this->load->model('o_cash_account');
        $amount = $this->o_cash_account->getSumAmount(array(
            'uid'=>$uid,
            'item_type'=>24
        ));
        $this->redis_set($redis_key,$amount/100,300);
        return $amount/100;
    }

    /* 统计会员推荐供应商奖 */
    public function get_supplier_recommend_commission(){
        exit("function exit;".__FILE__.",".__LINE__);
//        $this->load->model('tb_mall_supplier');
//        $this->load->model("tb_mall_goods_main");
//        $recommend_map = $this->tb_mall_supplier->get_recommend_map();
//
//        $this->db->trans_begin();
//        foreach($recommend_map as $uid =>$supplier_arr){
//            $user_info = $this->db->query("select user_rank,name from users where id = $uid")->row_array();
//            if(empty($user_info)){
//                continue;
//            }
//
//            if(in_array($user_info['user_rank'],array(3,4,5))){
//                $percentage = 0.01;
//            }
//            if($user_info['user_rank'] == 2){
//                $percentage = 0.015;
//            }
//            if($user_info['user_rank'] == 1){
//                $percentage = 0.02;
//            }
//
//            foreach($supplier_arr as $supplier_id){
//
////                $goods_sn_main_arr = $this->db->query("select goods_sn_main from mall_goods_main where supplier_id = $supplier_id")->result_array();
//                $goods_sn_main_arr = $this->tb_mall_goods_main->get_list_auto([
//                    "select"=>"goods_sn_main",
//                    "where"=>["supplier_id"=>$supplier_id],
//                    "page_size"=>0
//                ]);
//
//                $goods_sn_main_list = array();
//                foreach($goods_sn_main_arr as $item){
//                    if(!in_array($item['goods_sn_main'],$goods_sn_main_list)){
//                        $goods_sn_main_list[] = $item['goods_sn_main'];
//                    }
//                }
//
//                //此条件，无需改成tb_trade_orders或tb_trade_orders_goods
//                foreach($goods_sn_main_list as $goods_sn_main){
//                    $sql  = "";
//                    $sql .= "select sum(tg.goods_number) AS total_number,tg.goods_price,tg.goods_sn,tg.goods_name,tg.goods_sn_main,t.order_amount_usd,t.order_type,t.order_profit_usd from trade_orders t,trade_orders_goods tg";
//                    $sql .= " where t.order_id = tg.order_id and t.status in (4,5,6) and t.created_at <'2016-10-01'";
//                    $sql .= " and tg.goods_sn_main = '$goods_sn_main'";
//
//                    $goods = $this->db->query($sql)->row_array();
//                    if($goods['total_number'] == null){
//                        continue;
//                    }
//
//                    //商品售价
////                    $shop_price_arr = $this->db->select('price')->where('goods_sn_main', $goods['goods_sn_main'])
////                        ->from('mall_goods')->get()->row_array();
//                    $this->load->model("tb_mall_goods");
//                    $shop_price_arr = $this->tb_mall_goods->get_one("price,purchase_price",
//                        ["goods_sn_main"=>$goods["goods_sn_main"]]);
//                    //商品成本价
////                    $purchase_price_arr = $this->db->select('purchase_price')->where('goods_sn_main', $goods['goods_sn_main'])
////                        ->from('mall_goods')->get()->row_array();
//
//                    $goods_profit = $shop_price_arr['price'] - $shop_price_arr['purchase_price'] - $goods['goods_price'] * 0.05;
//                    $amount = $goods_profit * $goods['total_number'] * $percentage;
//
//                    //插入到推荐佣金表
//                    if($amount > 0) {
//                        $this->db->insert('user_recommend_commission_logs', array(
//                            'uid' => $uid,
//                            'name'=>$user_info['name'],
//                            'supplier_id' => $supplier_id,
//                            'amount' => $amount * 0.8,
//                            'goods_sn_main' => $goods['goods_sn_main'],
//                            'goods_name' => $goods['goods_name'],
//                            'sale_number' => $goods['total_number'],
//                        ));
//                    }
//                }
//            }
//        }
//
//        //发放推荐佣金
//        $this->send_supplier_recommend_commission();
//        if ($this->db->trans_status() === FALSE)
//        {
//            $this->db->trans_rollback();
//        }
//        else
//        {
//            $this->db->trans_commit();
//        }
    }

    /* 发放会员推荐供应商佣金 */
    public function send_supplier_recommend_commission(){
        if(!config_item('leader_bonus_test'))
        {
            $this->load->model('tb_mall_supplier');
            $this->load->model('o_cash_account');
            $recommend_map = $this->tb_mall_supplier->get_recommend_map();

            foreach($recommend_map as $uid=>$supplier_list){

                foreach($supplier_list as $supplier_id){

                    $amount_arr = $this->db->query("select sum(amount)AS amount,sum(sale_number)as total from user_recommend_commission_logs where uid = $uid AND supplier_id = $supplier_id")->row_array();

                    if(!empty($amount_arr['amount']))
                    {
                        $amount = $amount_arr['amount'];

                        //2.累加现金池
                        $this->db->where('id', $uid)->set('amount', 'amount+' . $amount, false)->update('users');

                        //插入到佣金表
                        if ($amount > 0) {
                            $this->o_cash_account->createCashAccountLog(array(
                                'uid' => $uid,
                                'item_type' => 9,
                                'amount' => $amount * 100,
                                'remark' => lang('supplier_recommend_commission').':'.lang_attr('total_sale_goods_number', array('sale_number' => $amount_arr['total'])),
                            ));
                        }

                    }
                }
            }
        }
    }
}
