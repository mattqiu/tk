<?php

class M_generation_sales extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /** 添加團隊銷售提成的日誌
     * @param $parent_id
     * @param int $child_id
     * @param int $level
     * @param int $sales
     * @param string $percent
     * @param $push_money
     * @param $comm_log_id
     */
    public function generationSalesLogs($parent_id,$child_id = 0,$level = 0,$sales = 0,$percent='',$push_money,$comm_log_id){
        $log_arr = array(
            'commission_id'=>$comm_log_id,
            'parent_id'=>$parent_id,
            'child_id'=>$child_id,
            'level'=>$level,
            'sales'=>$sales,
            'percent'=>$percent,
            'push_money'=>$push_money,
            'create_time'=>time()
        );
        $this->db->insert('generation_sales_logs',$log_arr);
    }

    // <!-- start  团队销售无限代奖励  分页 ->
    public function getSalesTotalRows($uid,$filter){
        $where = array('parent_id'=>$uid);

        if(isset($filter['start_time']) && $filter['start_time'] != ''){
            $where['create_time >'] = $filter['start_time'];
        }
        if(isset($filter['end_time']) && $filter['end_time'] != ''){
            $where['create_time <']= $filter['end_time'] + 3600*24-1;
        }
        return $this->db->from('generation_sales_logs')->where($where)->get()->num_rows();
    }

    public function getSalesLogs($uid,$page=false,$perPage=10,$filter) {
        $where = array('parent_id'=>$uid);

        if(isset($filter['start_time']) && $filter['start_time'] != ''){
            $where['create_time >='] = $filter['start_time'];
        }
        if(isset($filter['end_time']) && $filter['end_time'] != ''){
            $where['create_time <=']= $filter['end_time'] + 3600*24-1;
        }
        return $this->db->from('generation_sales_logs')->where($where)->order_by("create_time", "desc")->limit($perPage,($page-1)*$perPage)->get()->result_array();
    }
    // <!-- end  团队销售无限代奖励  分页 ->


    // <!-- start  无限代奖分页  ->
    public function getInfinityTotalRows($uid,$filter){
        $where = array('uid'=>$uid);

        if(isset($filter['start_time']) && $filter['start_time'] != ''){
            $where['create_time >'] = $filter['start_time'];
        }
        if(isset($filter['end_time']) && $filter['end_time'] != ''){
            $where['create_time <']= $filter['end_time'] + 3600*24-1;
        }
        return $this->db->from('infinity_generation_log')->where($where)->get()->num_rows();
    }

    public function getInfinityLogs($uid,$page=false,$perPage=10,$filter) {
        $where = array('uid'=>$uid);

        if(isset($filter['start_time']) && $filter['start_time'] != ''){
            $where['create_time >='] = $filter['start_time'];
        }
        if(isset($filter['end_time']) && $filter['end_time'] != ''){
            $where['create_time <=']= $filter['end_time'] + 3600*24-1;
        }
        return $this->db->from('infinity_generation_log')->where($where)->order_by("create_time", "desc")->limit($perPage,($page-1)*$perPage)->get()->result_array();
    }
    // <!-- end  无限代奖分页  ->

    // <!-- start  直推人列表  分页 ->
    public function getReferrerRows($uid,$filter){

        $where = array('parent_id'=>$uid);
        if(isset($filter['start_time']) && $filter['start_time'] != ''){
            $where['create_time >'] = $filter['start_time'];
        }
        if(isset($filter['end_time']) && $filter['end_time'] != ''){
            $where['create_time <']= $filter['end_time'] + 3600*24-1;
        }
        return $this->db_slave->from('users')->where($where)->count_all_results();
    }

    /**  直推人分頁
     * @param $uid
     * @param bool $page
     * @param int $perPage
     * @param bool $filter
     * @return mixed
     */
    public function  referrer($uid ,$page=false,$perPage=10,$filter=false){

        $where = array('parent_id'=>$uid);

        if(isset($filter['start_time']) && $filter['start_time'] != ''){
            $where['create_time >'] = $filter['start_time'];
        }
        if(isset($filter['end_time']) && $filter['end_time'] != ''){
            $where['create_time <']= $filter['end_time'] + 3600*24-1;
        }

        $referred = $this->db_slave->select('id,name,country_id,create_time,user_rank,email, mobile,store_url')->from('users')
            ->where($where)->order_by('create_time','DESC')->limit($perPage,($page-1)*$perPage)->get()->result_array();

        return $referred;
    }
    // <!-- end  直推人列表 分页 ->

    /**  不用递归统计会员数量
     * @param $user_id
     */
    public function getChildCount($user_id){
        $last_time = get_last_timestamp();
        $sql = "select count(*) as member_count from users u,user_upgrade_log uul where u.store_qualified = 1 and u.user_rank <> 4 and u.parent_ids LIKE ? and u.id=uul.uid and uul.create_time<=$last_time";
        $childs = $this->db->query($sql,array('%'.$user_id.'%'))->row_array();
        return $childs['member_count'];
    }

    /**  統計11层以下的銀級以上會員
     * @param $user_id
     * @return array
     */
    public function getChildIdsAndMoney($user_id){

		$this->load->model('M_order','m_order');
		$start = date('Y-m-01', strtotime('-1 month'));
		$last = date('Y-m-t 23:59:59', strtotime('-1 month'));
		$level_cash = config_item('level_cash');

		$sql = "select u.id,u.parent_ids from users u where u.parent_ids LIKE ? and u.status !=0";
		$childs = $this->db->query($sql,array('%'.$user_id.'%'))->result_array();
		$child_arr = array();
        $all_cash = 0 ;
        foreach($childs as $child){
            $parent = explode(',',$child['parent_ids']);
            $parent_index = array_search($user_id, $parent);
			unset($parent);
            if($parent_index >= 10){
                $child_arr[] = $child['id'];
				unset($child);
				unset($parent_index);
			}
		}
		$all_cash += $this->m_order->getOrderSales($child_arr,$start,$last,$level_cash);
		$child_count = count($child_arr);
		unset($child_arr);
		unset($childs);
		return array('child_count'=>$child_count,'all_cash'=>$all_cash);
    }

    /**  會員上個月的利潤
     * @param $uid
     * @param $user_rank
     * @return mixed
     */
    public function addCash($uid, $user_rank){
        //上个月的订单利润+產品套裝
        //$this->load->model('M_order','m_order');
        //$orderSales = $this->m_order->getOrderSales($uid,$user_rank);

        //return  $orderSales ;
    }
    /**  获取用户所有推荐的人的信息
     * @param $uid 用户ID
     * @return array 用户所有推荐的人
     */
    public function  export($uid){

        $referred = $this->db->select('id,name,email,country_id,user_rank,create_time')->from('users')
            ->where('parent_id',$uid)->get()->result_array();

        return $referred;
    }
    
    /**
     * 发放个人店铺销售提成
     * @param type $uid
     * @param type $commission_amount
     * @param type $order_id
     */
    public function assignStoreCommission($uid, $commission_amount, $order_id) {

        if($commission_amount<0.01){
            return false;
        }

        $this->load->model('m_commission');
        $this->m_commission->commissionLogs($uid,5,$commission_amount,0,$order_id);

        /*佣金自动转分红点*/
        $this->load->model('m_profit_sharing');
        $rate = $this->m_profit_sharing->getProportion($uid,'sale_commissions_proportion')/100;
        $commissionToPoint = tps_money_format($commission_amount*$rate);
        if($commissionToPoint>=0.01){
            $this->db->where('id', $uid)->set('profit_sharing_point','profit_sharing_point+'.$commissionToPoint,FALSE)->set('profit_sharing_point_from_sale','profit_sharing_point_from_sale+'.$commissionToPoint,FALSE)->update('users');
            $comm_id = $this->m_commission->commissionLogs($uid,17,-1*$commissionToPoint); //佣金轉分紅點的现金池纪录
            $this->m_profit_sharing->createPointAddLog(array(
                'uid' => $uid,
                'commission_id' => $comm_id,
                'add_source' => 1,
                'money' => $commissionToPoint,
                'point' => $commissionToPoint
            ));
        }
        
        $this->db->where('id', $uid)->set('amount', 'amount+' . ($commission_amount-$commissionToPoint), FALSE)->set('amount_store_commission', 'amount_store_commission+' . $commission_amount, FALSE)->update('users');
    }

    public function setYesterdayOrderWhereSql(&$db){
        $db->where('create_time >=', date("Y-m-d",strtotime("-1 day")));
        $db->where('create_time <', date("Y-m-d"));
    }
    
    public function getStoreCommissionLogs($filter, $page = false, $perPage = 10) {
        $this->db->from('individual_store_commission_log');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', $v);
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->order_by("create_time", "desc")->limit($perPage, ($page - 1) * $perPage)->get()->result_array();
    }
    
    public function getStoreCommissionLogsTotalRows($filter) {
        $this->db->from('individual_store_commission_log');
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            if ($k == 'start') {
                $this->db->where('create_time >=', $v);
                continue;
            }
            if ($k == 'end') {
                $this->db->where('create_time <=', $v);
                continue;
            }
            $this->db->where($k, $v);
        }
        return $this->db->get()->num_rows();
    }
}
