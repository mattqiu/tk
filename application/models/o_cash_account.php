<?php
/**
 * 现金账户处理类
 * @author terry
 */
class o_cash_account extends CI_Model {

    const TIME_NODE_OLD = '201606';

    function __construct() {
        parent::__construct();
        $this->load->model('tb_cash_account_log_x');
    }

    /**
     * 生成资金变动纪录
     * @param $data array(
     *   'uid'=>用户id int （必需）
     *   'item_type'=>金额类型 int（必需）
     *   'amount'=>金额（单位分）（必需）
     *   'create_time'=>创建时间 timestamp 默认当前时间
     *   'order_id'=>订单号 var
     *   'related_uid'＝>关联的用户id int
     *   'related_id'＝>关联的其他id int
     *   'remark'＝>备注 text
     *)
     * @return boolean
     * @author Terry
     */
    public function createCashAccountLog($data){

        $data['create_time'] = ( isset($data['create_time']) && $data['create_time']!='' )?$data['create_time']:date('Y-m-d H:i:s');
        $data['order_id'] = isset($data['order_id'])?$data['order_id']:'';
        $data['related_uid'] = isset($data['related_uid'])?$data['related_uid']:0;
        $data['remark'] = isset($data['remark'])?$data['remark']:'';

        $yearMonth = date('Ym',strtotime($data['create_time']));

        if($yearMonth>self::TIME_NODE_OLD){

            $this->tb_cash_account_log_x->createCashAccountMonthLogNew($data,$yearMonth);
        }else{

            $this->load->model('tb_commission_logs');
            $this->tb_commission_logs->insert(array(
                'uid'=>$data['uid'],
                'type'=>$data['item_type'],
                'amount'=>$data['amount']/100,
                'order_id'=>$data['order_id'],
                'create_time'=>$data['create_time'],
                'pay_user_id'=>$data['related_uid'],
                'remark' => $data['remark']
            ));
        }

        return $this->db->insert_id();
    }

    public function getRelatedIdInfo($related_id){
        $arr = explode('|', $related_id);
        $funds_change_report = $this->config->item('funds_change_report');
        if($arr[0]=='commission_logs'){

            $this->load->model('tb_commission_logs');
            $info = current($this->tb_commission_logs->getCommLogs(array('id'=>$arr[1])));
        }else{
            if (config_item("cash_account_log_read_new") == true) {
                $info = $this->tb_cash_account_log_x->getCashAccountLogByTbnameAndId("cash_account_log_".$arr[0],$arr[1]);
            } else {
                if (preg_match('/^\d+$/',$arr[0])) {
                    $arr[0] = "cash_account_log_".$arr[0];
                }
                $info = $this->tb_cash_account_log_x->getCashAccountLogByTbnameAndId($arr[0],$arr[1]);
            }

        }

        if($info){
            $return = array('commName'=>lang($funds_change_report[$info['item_type']]),'createTime'=>$info['create_time']);
        }else{
            $return = array();
        }

        return $return;
    }

    public function getUserTransferAmount($uid){

        $this->load->model('tb_commission_logs');

        $oldAmount = $this->tb_commission_logs->getUserTransferAmountOld($uid);
        $newAmount = $this->tb_cash_account_log_x->getUserTransferAmountNewBranch($uid,self::TIME_NODE_OLD);

        return $oldAmount+$newAmount;
    }

    //资金变动报表列表
    public function getCashAccountLogByPage($searchData,$page,$listType=''){

        if(!isset($searchData['start_time']) || $searchData['start_time']=='' || date('Ym',strtotime($searchData['start_time']))<=self::TIME_NODE_OLD ){
            $start_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $start_time_for_year_month = $searchData['start_time'];
        }

        if(!isset($searchData['end_time']) || $searchData['end_time']==''){
            $end_time_for_year_month = '';
        }elseif(date('Ym',strtotime($searchData['end_time']))<=self::TIME_NODE_OLD){
            $end_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $end_time_for_year_month = $searchData['end_time'];
        }

        if($listType=='commission_report'){
            $searchData['item_type in'] = array_keys($this->config->item('commission_type'));
        }

        $yearMonths = get_year_months_by_time_period($start_time_for_year_month,$end_time_for_year_month,'desc');
        $retrunList = array();
        $coordinate = 0;
        foreach($yearMonths as $k=>$yearMonth){

            $leftNum = 10-count($retrunList);
            if($leftNum<=0){
                break;
            }

            if($yearMonth <= self::TIME_NODE_OLD){

                $this->load->model('tb_commission_logs');
                $resList = $this->tb_commission_logs->getCommLogsByPage($searchData,$page,$leftNum,$coordinate);
                $retrunList = array_merge($retrunList,$resList);
            }else{

                $resList = $this->tb_cash_account_log_x->getCashAccountMonthLogByPage($searchData,$yearMonth,$page,$leftNum);
                $retrunList = array_merge($retrunList,$resList);
            }

            $page = 1;
        }

        return $retrunList;

    }
    //资金变动报表列表---新查询，只查询一张表的
    public function getCashAccountLogByPageNew($searchData,$page,$listType=''){
        
        $start_time_for_year_month = $searchData['start_time'];
        if($listType=='commission_report' && !isset($searchData['item_type'])) {
            $searchData['item_type in'] = array_keys($this->config->item('commission_type'));
        }
       
        $coordinate = 0;
        $leftNum = 10;//每页查10条
        $yearMonth = date('Ym',strtotime($start_time_for_year_month));//只查一个月的数据，就是只查一张表，无需循环
        
        if($yearMonth <= self::TIME_NODE_OLD){
            $this->load->model('tb_commission_logs');
            $retrunList = $this->tb_commission_logs->getCommLogsByPage($searchData,$page,$leftNum,$coordinate);
        }else{
            $retrunList = $this->tb_cash_account_log_x->getCashAccountMonthLogByPage($searchData,$yearMonth,$page,$leftNum);
        }
        return $retrunList;
    }

    /**
     * 获取用户第一次（最近一次）的佣金记录
     * @param $sort asc代表顺序第一次，desc代表倒序最近一次 
     */
    public function getFirstCommLog($searchData,$sort='asc'){

        $yearMonths = get_year_months_by_time_period(substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4),'',$sort);
        $retrunList = array();
        foreach($yearMonths as $k=>$yearMonth){

            if($yearMonth <=self::TIME_NODE_OLD){

                $this->load->model('tb_commission_logs');
                $resList = $this->tb_commission_logs->getCommLogs($searchData,'*',true,$sort);
            }else{

                $resList = $this->tb_cash_account_log_x->getCashAccountMonthLog($searchData,$yearMonth,'*',true,$sort);
            }
            if($resList){
                return $resList;
            }
        }
        return false;
    }

    /**
     * 获取资金变动纪录
     */
    public function getCashAccountLog($searchData,$select='*'){

        if(!isset($searchData['start_time']) || $searchData['start_time']=='' || date('Ym',strtotime($searchData['start_time']))<=self::TIME_NODE_OLD ){
            $start_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $start_time_for_year_month = $searchData['start_time'];
        }

        if(!isset($searchData['end_time']) || $searchData['end_time']==''){
            $end_time_for_year_month = '';
        }elseif(date('Ym',strtotime($searchData['end_time']))<=self::TIME_NODE_OLD){
            $end_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $end_time_for_year_month = $searchData['end_time'];
        }

        $yearMonths = get_year_months_by_time_period($start_time_for_year_month,$end_time_for_year_month);
        $retrunList = array();
        foreach($yearMonths as $k=>$yearMonth){

            if($yearMonth <=self::TIME_NODE_OLD){

                $this->load->model('tb_commission_logs');
                $resList = $this->tb_commission_logs->getCommLogs($searchData,$select=='distinct uid,related_uid as pay_user_id'?'distinct uid,pay_user_id':$select);
                $retrunList = array_merge($retrunList,$resList);
            }else{

                $resList = $this->tb_cash_account_log_x->getCashAccountMonthLog($searchData,$yearMonth,$select);
                $retrunList = array_merge($retrunList,$resList);
            }
        }

        return $retrunList;

    }

    /**
     * @desc 检测用户是否已经拿过奖
     * @author brady
     */
    public function check_exists($uid,$time,$item_type)
    {
        $time_start = date("Y-m-d",$time);
        $res = $this->tb_cash_account_log_x->check_exits_by_days($uid,$item_type,$time_start);
        if(empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据查询条件获取资金变动报表纪录数
     * @param $searchData = array( //非必需
        'start_time'=>,
        'end_time'=>,
        'uid'=>,
        'item_type'=>,
        'item_type in'=>,
        'order_id'=>,
        'related_uid'=>,
     );
     */
    public function getCashAccountLogNum($searchData){

        if(!isset($searchData['start_time']) || $searchData['start_time']=='' || date('Ym',strtotime($searchData['start_time']))<=self::TIME_NODE_OLD ){
            $start_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $start_time_for_year_month = $searchData['start_time'];
        }

        if(!isset($searchData['end_time']) || $searchData['end_time']==''){
            $end_time_for_year_month = '';
        }elseif(date('Ym',strtotime($searchData['end_time']))<=self::TIME_NODE_OLD){
            $end_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $end_time_for_year_month = $searchData['end_time'];
        }

        $num = 0;
        $yearMonths = get_year_months_by_time_period($start_time_for_year_month,$end_time_for_year_month);
        foreach($yearMonths as $k=>$yearMonth){

            if($yearMonth <=self::TIME_NODE_OLD){

                $this->load->model('tb_commission_logs');
                $num += $this->tb_commission_logs->getCommLogsNum($searchData);
            }else{

                $num += $this->tb_cash_account_log_x->getCashAccountMonthLogNum($searchData,$yearMonth);
            }
        }

        return $num;
    }

    //根据查询条件获取资金变动报表纪录数 ,新方法，只查询一个表的
    public function getCashAccountLogNumNew($searchData){

        if(!isset($searchData['start_time']) || $searchData['start_time']=='' || date('Ym',strtotime($searchData['start_time']))<=self::TIME_NODE_OLD ){
            $start_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $start_time_for_year_month = $searchData['start_time'];
        }
        //只查询一个月之内的数据，就是只查一个表，无需循环
        $yearMonth = date('Ym',strtotime($start_time_for_year_month));
        if($yearMonth <=self::TIME_NODE_OLD){
           $this->load->model('tb_commission_logs');
           $num = $this->tb_commission_logs->getCommLogsNum($searchData);
        }else{
           $num = $this->tb_cash_account_log_x->getCashAccountMonthLogNum($searchData,$yearMonth);
        }
        return $num;
    }

    /*会员之间转帐*/
    public function tranMoneyBetweenMem($fromId,$tranToMemId,$tranToMemAmount,$tranToMemFundsPwd){

        $this->load->model('tb_users');

        $fromIdInfo = $this->tb_users->getUserByIdOrEmail($fromId);
        if (!is_numeric($tranToMemAmount) || $tranToMemAmount <= 0 ||  get_decimal_places($tranToMemAmount)>2) {
            
            return 1013;
        }
        if($tranToMemAmount>$fromIdInfo['amount']){
            
            return 1030;
        }
        if(!$tranToMemId){
            
            return 1033;
        }
        if(!$this->tb_users->get_user_info($tranToMemId,'id')){
            
            return 1008;
        }
        if($fromId==$tranToMemId){
            
            return 1047;
        }
        if(!$this->tb_users->checkTakeOutPwd($fromId,$tranToMemFundsPwd)){
            
            return 1048;
        }

        $this->tb_users->updateUserAmount($fromId,-$tranToMemAmount);
        $this->createCashAccountLog(array(
            'uid'=>$fromId,
            'item_type'=>11,
            'amount'=>-tps_int_format($tranToMemAmount*100),
            'related_uid'=>$tranToMemId
        ));
        $this->tb_users->updateUserAmount($tranToMemId,$tranToMemAmount);
        $this->createCashAccountLog(array(
            'uid'=>$tranToMemId,
            'item_type'=>11,
            'amount'=>tps_int_format($tranToMemAmount*100),
            'related_uid'=>$fromId
        ));

        return 0;
    }

    /**
     * 根据查询条件统计纪录总额
     * @param $searchData = array( //非必需
        'start_time'=>,
        'end_time'=>,
        'uid'=>,
        'uid in'=>array(),
        'item_type'=>,
        'item_type in'=>array(),
        'order_id'=>,
        'related_uid'=>,
     );
     */
    
    
    public function getSumAmount($searchData){

        if(!isset($searchData['start_time']) || $searchData['start_time']=='' || date('Ym',strtotime($searchData['start_time']))<=self::TIME_NODE_OLD ){
            $start_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $start_time_for_year_month = $searchData['start_time'];
        }

        if(!isset($searchData['end_time']) || $searchData['end_time']==''){
            $end_time_for_year_month = '';
        }elseif(date('Ym',strtotime($searchData['end_time']))<=self::TIME_NODE_OLD){
            $end_time_for_year_month = substr(self::TIME_NODE_OLD,0,4).'-'.substr(self::TIME_NODE_OLD,4);
        }else{
            $end_time_for_year_month = $searchData['end_time'];
        }

        $sumAmount = 0;
        $yearMonths = get_year_months_by_time_period($start_time_for_year_month,$end_time_for_year_month);
       
        foreach($yearMonths as $k=>$yearMonth){

            if($yearMonth <=self::TIME_NODE_OLD){

                $this->load->model('tb_commission_logs');
                $sumAmount += $this->tb_commission_logs->getCommLogsSumAmount($searchData);
            }else{

                $sumAmount += $this->tb_cash_account_log_x->getCashAccountMonthLogSumAmount($searchData,$yearMonth);
            }
        }
       
        return $sumAmount;
    }

}
