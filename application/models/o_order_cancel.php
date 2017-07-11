<?php
/**
 * 订单取消
 * @author terry
 */
class o_order_cancel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 对取消后2周内未补单的延迟抽回奖项执行抽回
     */
    public function withdrawOfOrder(){

        $this->load->model('tb_withdraw_task');
        $withdrawList = $this->tb_withdraw_task->getArrivedWithdraw();
        foreach ($withdrawList as $item) {
            //if ($item['uid'] == "1382681670") {
                $this->withdrawComm($item['uid'],$item['comm_type'],$item['comm_year_month']);
                $this->tb_withdraw_task->deleteWithdrawTask($item['uid'],$item['comm_type'],$item['comm_year_month']);
            //}

        }
    }

    /**
     * 根据用户id、佣金类型、佣金年月进行佣金抽回。
     */
    public function withdrawComm($uid,$comm_type,$comm_year_month){

        $this->load->model('tb_commission_logs');
        $this->load->model('o_cash_account');
        $this->load->model('tb_users');
        $remark = "";
        
        if ($comm_type == 26) {
        	$commLogs = $this->tb_commission_logs->get_new_member_bonus_log($uid,$comm_type,$comm_year_month);
            $remark = "系统自动抽回新会员专项奖";
        } else {
        	$commLogs = $this->tb_commission_logs->getCommLogByUidCommTypeYearMonth($uid,$comm_type,$comm_year_month);
        	switch ($comm_type){
                case 6://日分红
                    $remark = "系统自动抽回日分红";
                    break;
                case 2://138
                    $remark = "系统自动抽回138分红";
                    break;
                case 24://精英日分红
                    $remark = "系统自动抽回精英日分红";
                    break;
                case 8://月杰出店铺分红
                    $remark = "系统自动抽回月杰出店铺分红";
                    break;
                case 7://周领导对等奖
                    $remark = "系统自动抽回周领导对等奖";
                    break;
                case 1://每月团队组织分红奖
                    $remark = "系统自动抽回每月团队组织分红奖";
                    break;
                case 25://每周团队分红
                    $remark = "系统自动抽回每周团队分红";
                    break;
                case 23://每月领导分红奖
                    $remark = "系统自动抽回每月领导分红奖";
                    break;
                default:
                    # code...
                    break;
            }
        }
        if($comm_year_month==date('Ym')){
            switch ($comm_type) {
                case 6://日分红
                    $this->load->model('tb_daily_bonus_qualified_list');
                    $this->tb_daily_bonus_qualified_list->deleteOneUser($uid);
                    break;
                case 2://138
                    $this->load->model('tb_user_qualified_for_138');
                    $this->tb_user_qualified_for_138->deleteOneUser($uid);
                    break;
                case 24://精英日分红
                    $this->db->query("delete from daily_bonus_elite_qualified_list where uid=".$uid);
                    break;
                case 8://月杰出店铺分红
                    $this->load->model('tb_month_sharing_members');
                    $this->tb_month_sharing_members->deleteOneUser($uid);
                    break;
                case 7://周领导对等奖
                    $this->db->query("delete from week_leader_members where uid=".$uid);
                    break;
                case 1://每月团队组织分红奖
                    $this->db->query("delete from month_group_share_list where uid=".$uid);
                    break;
                case 25://每周团队分红
                    $this->db->query("delete from week_share_qualified_list where uid=".$uid);
                    break;
                case 23://每月领导分红奖
                    $shopkeeperInfo = $this->tb_users->getUserInfo($uid,array('user_rank','sale_rank'));
                    if($shopkeeperInfo){
                        $tableName = '';
                        switch($shopkeeperInfo['sale_rank']){
                            case 3://3销售主任,高级市场主管
                                $tableName = 'month_leader_bonus';
                                break;
                            case 4://4销售总监,市场总监
                                $tableName = 'month_top_leader_bonus';
                                break;
                            case 5://5全球销售副总裁,全球销售副总裁
                                $tableName = 'month_leader_bonus_lv5';
                                break;
                            default:
                                break;
                        }
                        if($tableName){
                            $this->db->query('delete from '.$tableName.' where uid='.$uid);
                        }
                    }
                    break;
                default:
                    # code...
                    break;
            }
        }

        foreach($commLogs as $item){

            $this->tb_users->updateUserAmount($uid,-$item['amount']/100);//从现金池抽回相应佣金

            $user = $this->tb_users->get_user_info($uid,"id,amount");
            $data = array(
                'uid'=>$uid,
                'item_type'=>16,
                'amount'=>-$item['amount'],
                'order_id'=>$item['order_id'],
                'related_uid'=>$item['related_uid'],
                'related_id'=>substr($item['tb_name'],17).'|'.$item['id'],
                'before_amount'=>$user['amount'] * 100 + $item['amount'],
                'after_amount'=>$user['amount'] * 100,
                'remark'=>$remark
            );
            $itemId = $this->o_cash_account->createCashAccountLog($data);//生成抽回的资金变动记录
        }

        return true;
    }

    /**
     * 订单预抽回。＃取消后相关的抽回（部分返利直接抽回，分红类做标记用以延迟抽回）
     * @param $orderId
     * @param $orderFrom #订单来源 'wh'＝>沃好商城；'1direct'＝>美国1direct商城；默认为空，代表tps138商城
     * @return boolean
     * @author Terry
     */
    public function preWithdrawOfOrder($orderId,$orderFrom=''){

        $this->load->model('tb_commission_logs');
        $this->load->model('tb_stat_intr_mem_month');
        $this->load->model('tb_users_store_sale_info');
        $this->load->model('tb_users_store_sale_info_monthly');
        $this->load->model('tb_users');
        $this->load->model('tb_month_sharing_members');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_infinity_generation_log');
        $this->load->model('tb_withdraw_task');
        $orderInfo = $this->__createOrderInfoForCancel($orderId,$orderFrom);
       
        if(!$orderInfo){
            return FALSE;
        }

        $shopkeeperInfo = $this->tb_users->getUserInfo($orderInfo['shopkeeper_id'],array('user_rank','sale_rank'));
        //echo "<pre>";print_r($orderInfo);exit;
        $this->__withdrawSaleAmount($orderInfo,$orderFrom);//取消订单后减掉用户店铺的销售额统计（总统计、月统计）  1  
        $this->__withdrawfirstGenerationRebateOfOrder($orderId);//抽回“个人店铺销售提成奖”       2
        $this->__revulsionTeamGeneration($orderId);//抽回“团队销售业绩提成奖”  1
        $this->__withdrawDayliBonusOfOrder($orderInfo,$shopkeeperInfo);//抽回日分红   1
        $this->__withdrawNewMemberBonusOfOrder($orderInfo,$shopkeeperInfo);//抽回新会员   1
        $this->__withdraw138BonusOfOrder($orderInfo,$shopkeeperInfo);//抽回138分红    1
        $this->__withdrawEliteDayliBonusOfOrder($orderInfo);//抽回精英日分红   1
        $this->__withdrawMonthSharingBonusOfOrder($orderInfo,$shopkeeperInfo);//抽回每月杰出店铺分红奖        1
        $this->__withdrawWeekLeaderBonusOfOrder($orderInfo);//抽回每周领导对等奖     1
        $this->__withdrawMonthLeaderBonusOfOrder($orderInfo,$shopkeeperInfo);//抽回每月领导分红奖       1
        $this->__withdrawMonthChairmanBonusOfOrder($orderInfo);//抽回每月总裁奖       1
        $this->__withdraw2x5ForceMatrix($orderInfo); //抽回每月团队组织分红奖
        $this->__withdrawPlanWeekShare($orderInfo);//抽回每周团队分红
    }
    
    

    public function test($order_id)
    {
        $this->load->model('tb_commission_logs');
        $this->load->model('tb_stat_intr_mem_month');
        $this->load->model('tb_users_store_sale_info');
        $this->load->model('tb_users_store_sale_info_monthly');
        $this->load->model('tb_users');
        $this->load->model('tb_month_sharing_members');
        $this->load->model('tb_week_leader_members');
        $this->load->model('tb_infinity_generation_log');
        $this->load->model('tb_withdraw_task');
        $orderInfo = $this->__createOrderInfoForCancel($order_id);
    }


    /**
     * 获取并整理订单信息（为佣金抽回）
     * @author Terry
     */
    private function __createOrderInfoForCancel($orderId,$orderFrom=''){

        $this->load->model('tb_trade_orders');
        $this->load->model('tb_mall_orders');
        $this->load->model('tb_one_direct_orders');
        if(!$orderFrom){

            $orderInfo = $this->tb_trade_orders->getOrderInfo($orderId,array('shopkeeper_id','goods_amount_usd','pay_time','score_year_month'));
        }elseif($orderFrom=='wh'){

            $orderInfo = $this->tb_mall_orders->getWhOrderInfo($orderId);
        }elseif($orderFrom=='1direct'){

            $orderInfo = $this->tb_one_direct_orders->get1DirctOrderInfo($orderId);
        }else{

            $orderInfo = '';
        }
        if(!$orderInfo || !$orderInfo['shopkeeper_id'] || !$orderInfo['pay_time']){
            return FALSE;
        }

        $orderInfo['year_month'] = $orderInfo['score_year_month']?$orderInfo['score_year_month']:date('Ym',strtotime($orderInfo['pay_time']));
        $orderInfo['year_month_next'] = yearMonthAddOne($orderInfo['year_month']);
        $year_month_next2 = yearMonthAddOne($orderInfo['year_month_next']);
        $orderInfo['commStartTime'] = substr($orderInfo['year_month_next'],0,4).'-'.substr($orderInfo['year_month_next'],4);
        $orderInfo['commEndTime'] = substr($year_month_next2,0,4).'-'.substr($year_month_next2,4);

        return $orderInfo;
    }

    /**
     * 取消订单后更新用户店铺的销售额统计（总统计、月统计）
     * @param $orderInfo array('shopkeeper_id'=>XXX,'goods_amount_usd'＝>XXX,'year_month'=>201603)
     * @return boolean
     * @author Terry
     */
    private function __withdrawSaleAmount($orderInfo,$orderFrom=""){
        //echo "<pre>";print_r($orderInfo);exit;
        $goods_amount_usd = ($orderFrom=='wh') ? -$orderInfo['goods_amount_usd']*100 :-$orderInfo['goods_amount_usd'];
        $this->tb_users_store_sale_info->updateByUid($orderInfo['shopkeeper_id'],array(
            'orders_num_update'=>-1,
            'sale_amount_update'=>$goods_amount_usd,
        ));
        
        $this->tb_users_store_sale_info_monthly->updateByUidAndDate($orderInfo['shopkeeper_id'],$orderInfo['year_month'],array(
            'orders_num_update'=>-1,
            'sale_amount_update'=>$goods_amount_usd,
        ));
        if($orderFrom=='wh'){
            $this->db->insert('order_cancel_log', array(
                'uid' => $orderInfo['shopkeeper_id'],
                'order_id' => $orderInfo["order_id"],
                'type' => 1,//沃好
                'content' => $this->db->last_query(),
                'system_time' => date("Y-m-d H:i:s",time()),
            ));
        }
        return true;
    }


    /**
     * 取消订单后抽回相关的“个人店铺销售提成奖”
     * @param $orderId var 订单号
     * @return boolean
    */
    private function __withdrawfirstGenerationRebateOfOrder($orderId){

        $this->load->model('o_cash_account');
        $firstGenerationRebateInfo = $this->o_cash_account->getFirstCommLog(array('order_id'=>$orderId,'item_type'=>5),'desc');
       // echo "<pre>";print_r($firstGenerationRebateInfo);exit;
        if($firstGenerationRebateInfo){

            $firstGenerationRebateInfoWithdraw = array(
                'uid'=>$firstGenerationRebateInfo['uid'],
                'item_type'=>16,
                'amount'=>-tps_int_format($firstGenerationRebateInfo['amount']),
                'order_id'=>$orderId,
                'related_uid'=>$firstGenerationRebateInfo['related_uid'],
            );
            $this->tb_users->updateUserAmount($firstGenerationRebateInfo['uid'],-$firstGenerationRebateInfo['amount']/100);//从现金池抽回相应佣金
            $this->o_cash_account->createCashAccountLog($firstGenerationRebateInfoWithdraw);//生成抽回的资金变动记录


        }

    	return true;
    }
    /**
     * @author brady.wang  
     * @desc   管你是几月的订单，只要你去掉了不满足50美金，那么就准备把你的新会员奖统统抽回来
     * @param  
     * @param 
     */
    public function __withdrawNewMemberBonusOfOrder($orderInfo,$shopkeeperInfo)
    {
//    	$this->load->model('tb_commission_logs');
//    	$this->load->model('tb_stat_intr_mem_month');
//    	$this->load->model('tb_users_store_sale_info');
//    	$this->load->model('tb_users_store_sale_info_monthly');
//    	$this->load->model('tb_users');
//    	$this->load->model('tb_month_sharing_members');
//    	$this->load->model('tb_week_leader_members');
//    	$this->load->model('tb_infinity_generation_log');
//    	$this->load->model('tb_withdraw_task');
//    	$orderInfo = $this->__createOrderInfoForCancel($orderId,$orderFrom);
//    	$shopkeeperInfo = $this->tb_users->getUserInfo($orderInfo['shopkeeper_id'],array('user_rank','sale_rank'));


    	$this->load->model('o_cash_account');
    	$curYm = date('Ym'); //当前月份
    	//第一次拿新会员奖的年月
    	$firstDayliComm = $this->o_cash_account->getFirstCommLog(array('uid'=>$orderInfo['shopkeeper_id'],'item_type'=>26));

    	if (!empty($firstDayliComm)) {
    		$firstDayliCOmmDate = date('Y-m',strtotime($firstDayliComm['create_time']));
    		$curSaleInfo = $this->tb_users_store_sale_info->getUserSaleInfo($orderInfo['shopkeeper_id']);

    		if( $curSaleInfo < 5000 ){
    			$sale_amount_lack = '';
    			$sale_amount_lack = 5000-$curSaleInfo;
    			if($sale_amount_lack){
    				$this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],26,$firstDayliCOmmDate,$orderInfo['year_month'],$sale_amount_lack);
    			}
    		}
    	}

    	 
    }
    
	
    /**
     * 取消订单后日分红的处理。
     * @param $orderInfo array('shopkeeper_id'=>XXX,'year_month'=>201604,'year_month_next'=>201605,'commStartTime'=>2016-05,'commEndTime'=>2016-06)
     * 
     */
    private function __withdrawDayliBonusOfOrder($orderInfo,$shopkeeperInfo){

        $this->load->model('o_cash_account');
        $curYm = date('Ym');

        if($orderInfo['year_month'] == $curYm){//取消当前月的订单
            
            $firstDayliComm = $this->o_cash_account->getFirstCommLog(array('uid'=>$orderInfo['shopkeeper_id'],'item_type'=>6));
            $firstDayliCOmmDate = $firstDayliComm?date('Ym',strtotime($firstDayliComm['create_time'])):'';
            if( $firstDayliCOmmDate == $curYm ){//如果是当月第一次拿到日分红

                $curSaleInfo = $this->tb_users_store_sale_info->getUserSaleInfo($orderInfo['shopkeeper_id']);
                if( $curSaleInfo < 10000 ){

                    $ifIntrPayedMember = $this->tb_stat_intr_mem_month->checkIfIntrPayedMember($orderInfo['shopkeeper_id']);
                    if(!$ifIntrPayedMember){

                        $sale_amount_lack = '';
                        if($shopkeeperInfo['user_rank']==4){

                            $sale_amount_lack = 10000-$curSaleInfo;
                        }elseif($curSaleInfo < 2500){

                            $sale_amount_lack = 2500-$curSaleInfo;
                        }
                        if($sale_amount_lack){
                            $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],6,$orderInfo['year_month'],$orderInfo['year_month'],$sale_amount_lack);
                        }
                    }
                }
            }
        }else{//取消历史月的订单

            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],6,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月日分红奖

                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($userSaleAmountMonth < 10000){

                    $ifIntrPayedMember = $this->tb_stat_intr_mem_month->checkIfIntrPayedMember($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                    if(!$ifIntrPayedMember){

                        $firstDayliComm = $this->o_cash_account->getFirstCommLog(array('uid'=>$orderInfo['shopkeeper_id'],'item_type'=>6));
                        $firstDayliCOmmDate = $firstDayliComm?date('Ym',strtotime($firstDayliComm['create_time'])):'';
                        if($firstDayliCOmmDate==$orderInfo['year_month_next']){
                            $ifIntrPayedMember = $this->tb_stat_intr_mem_month->checkIfIntrPayedMember($orderInfo['shopkeeper_id'],$orderInfo['year_month_next']);
                            if(!$ifIntrPayedMember){
                                $userSaleAmountNextMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month_next']);
                                if($userSaleAmountNextMonth>=10000){
                                    $ifIntrPayedMember = true;
                                }elseif($userSaleAmountNextMonth>=2500 && $shopkeeperInfo['user_rank']!=4){
                                    $ifIntrPayedMember = true;
                                }
                            }
                        }

                        if(!$ifIntrPayedMember){

                            $sale_amount_lack = '';
                            if($shopkeeperInfo['user_rank']==4){

                                $sale_amount_lack = 10000-$userSaleAmountMonth;
                            }elseif($userSaleAmountMonth < 2500){

                                $sale_amount_lack = 2500-$userSaleAmountMonth;
                            }
                            if($sale_amount_lack){
                                $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],6,$orderInfo['year_month_next'],$orderInfo['year_month'],$sale_amount_lack);
                            }
                        }
                    }
                }

            }
        }
    }

    /**
     * 取消订单后138分红的处理。
     * @param $orderInfo array('shopkeeper_id'=>XXX,'year_month'=>201604,'year_month_next'=>201605,'commStartTime'=>2016-05,'commEndTime'=>2016-06)
     * 
     */
    private function __withdraw138BonusOfOrder($orderInfo,$shopkeeperInfo){

        $this->load->model('o_cash_account');
        $curYm = date('Ym');

        if($orderInfo['year_month'] == $curYm){//取消当前月的订单
            
            $firstDayliComm = $this->o_cash_account->getFirstCommLog(array('uid'=>$orderInfo['shopkeeper_id'],'item_type'=>2));
            $firstDayliCOmmDate = $firstDayliComm?date('Ym',strtotime($firstDayliComm['create_time'])):'';
            if( $firstDayliCOmmDate == $curYm ){//如果是当月第一次拿到分红

                $curSaleInfo = $this->tb_users_store_sale_info->getUserSaleInfo($orderInfo['shopkeeper_id']);
                if( $curSaleInfo < 5000 ){

                    $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],2,$orderInfo['year_month'],$orderInfo['year_month'],5000-$curSaleInfo);
                }
            }
        }else{//取消历史月的订单

            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],2,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月分红奖

                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($userSaleAmountMonth < 5000){

                    $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],2,$orderInfo['year_month_next'],$orderInfo['year_month'],5000-$userSaleAmountMonth);
                }

            }
        }
    }

    /**
     * 取消订单后精英日分红的处理。
     * @param $orderInfo array('shopkeeper_id'=>XXX,'year_month'=>201604,'year_month_next'=>201605,'commStartTime'=>2016-05,'commEndTime'=>2016-06)
     * 
     */
    private function __withdrawEliteDayliBonusOfOrder($orderInfo){

        $this->load->model('o_cash_account');
        $curYm = date('Ym');

        if($orderInfo['year_month'] == $curYm){//取消当前月的订单
            
            $firstDayliComm = $this->o_cash_account->getFirstCommLog(array('uid'=>$orderInfo['shopkeeper_id'],'item_type'=>24));
            $firstDayliCOmmDate = $firstDayliComm?date('Ym',strtotime($firstDayliComm['create_time'])):'';
            if( $firstDayliCOmmDate == $curYm ){//如果是当月第一次拿奖

                $curSaleInfo = $this->tb_users_store_sale_info->getUserSaleInfo($orderInfo['shopkeeper_id']);
                if( $curSaleInfo < 25000 ){

                    $ifIntrPayedMember = $this->tb_stat_intr_mem_month->checkIfIntrPayedMember($orderInfo['shopkeeper_id']);
                    if(!$ifIntrPayedMember){

                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],24,$orderInfo['year_month'],$orderInfo['year_month'],25000-$curSaleInfo);
                    }
                }
            }
        }else{//取消历史月的订单

            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],24,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月奖金

                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($userSaleAmountMonth < 25000){

                    $ifIntrPayedMember = $this->tb_stat_intr_mem_month->checkIfIntrPayedMember($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                    if(!$ifIntrPayedMember){

                        $firstDayliComm = $this->o_cash_account->getFirstCommLog(array('uid'=>$orderInfo['shopkeeper_id'],'item_type'=>24));
                        $firstDayliCOmmDate = $firstDayliComm?date('Ym',strtotime($firstDayliComm['create_time'])):'';
                        if($firstDayliCOmmDate==$orderInfo['year_month_next']){
                            $ifIntrPayedMember = $this->tb_stat_intr_mem_month->checkIfIntrPayedMember($orderInfo['shopkeeper_id'],$orderInfo['year_month_next']);
                            if(!$ifIntrPayedMember){
                                $userSaleAmountNextMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month_next']);
                                if($userSaleAmountNextMonth>=25000){
                                    $ifIntrPayedMember = true;
                                }
                            }
                        }

                        if(!$ifIntrPayedMember){

                            $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],24,$orderInfo['year_month_next'],$orderInfo['year_month'],25000-$userSaleAmountMonth);
                        }
                    }
                }

            }
        }
    }

    /**
     * 取消订单后每月杰出店铺分红的处理。
     * @param $orderInfo array('shopkeeper_id'=>XXX,'year_month'=>201604,'year_month_next'=>201605,'commStartTime'=>2016-05,'commEndTime'=>2016-06)
     * 
     */
    private function __withdrawMonthSharingBonusOfOrder($orderInfo,$shopkeeperInfo){

        $curYm = date('Ym');

        if($orderInfo['year_month'] != $curYm){//取消历史月的订单

            $qualified = FALSE;
            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],8,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月奖金

                $qualified = TRUE;
            }elseif( $orderInfo['year_month_next']==$curYm && $this->tb_month_sharing_members->checkIfInCurMonthQualifiedList($orderInfo['shopkeeper_id']) ){

                $qualified = TRUE;
            }

            if($qualified){

                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($orderInfo['year_month'] >= config_item('commission_time')) {
                    if ($userSaleAmountMonth < 37500) {
                        $sale_amount_lack = '';
                        if ($shopkeeperInfo['sale_rank'] == 0) {
                            $sale_amount_lack = 37500 - $userSaleAmountMonth;
                        } elseif ($userSaleAmountMonth < 10000) {//一百美金
                            $sale_amount_lack = 10000 - $userSaleAmountMonth;
                        }
                        if ($sale_amount_lack) {
                            $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 8, $orderInfo['year_month_next'], $orderInfo['year_month'], $sale_amount_lack);
                        }
                    }
                }else{
                    if ($userSaleAmountMonth < 37500) {
                        $sale_amount_lack = '';
                        if ($shopkeeperInfo['sale_rank'] == 0) {
                            $sale_amount_lack = 37500 - $userSaleAmountMonth;
                        }elseif($userSaleAmountMonth < 7500){
                            $sale_amount_lack = 7500 - $userSaleAmountMonth;
                        }
                        if ($sale_amount_lack) {
                            $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 8, $orderInfo['year_month_next'], $orderInfo['year_month'], $sale_amount_lack);
                        }
                    }
                }
            }
        }
    }

    /**
     * 取消订单后每月领导分红的处理。
     * @param $orderInfo array('shopkeeper_id'=>XXX,'year_month'=>201604,'year_month_next'=>201605,'commStartTime'=>2016-05,'commEndTime'=>2016-06)
     * 
     */
    private function __withdrawMonthLeaderBonusOfOrder($orderInfo,$shopkeeperInfo){

        $curYm = date('Ym');

        if($orderInfo['year_month'] != $curYm){//取消历史月的订单

            $qualified = FALSE;
            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],23,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月奖金

                $qualified = TRUE;
            }elseif( $orderInfo['year_month_next']==$curYm && $this->checkIfInCurMonthQualifiedMonthLeaderList($orderInfo['shopkeeper_id']) ){

                $qualified = TRUE;
            }

            if($qualified){

                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($orderInfo['year_month'] >= config_item('commission_time')){
                    if($userSaleAmountMonth < 30000){//个人店铺达到了300美元的销售额。小于300美金就可能会补单
                        $sale_amount_lack = '';
                        if($shopkeeperInfo['sale_rank']==5){
                            $sale_amount_lack = 30000-$userSaleAmountMonth;
                        }elseif($shopkeeperInfo['sale_rank']==4 && $userSaleAmountMonth < 20000){
                            $sale_amount_lack = 20000-$userSaleAmountMonth;
                        }elseif($shopkeeperInfo['sale_rank']==3 && $userSaleAmountMonth < 10000){
                            $sale_amount_lack = 10000-$userSaleAmountMonth;
                        }
                        if($sale_amount_lack){

                            $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],23,$orderInfo['year_month_next'],$orderInfo['year_month'],$sale_amount_lack);
                        }
                    }
                }else {
                if($userSaleAmountMonth < 15000){
                        $sale_amount_lack = '';
                        if ($shopkeeperInfo['sale_rank'] == 5) {
                            $sale_amount_lack = 15000 - $userSaleAmountMonth;
                        }elseif($shopkeeperInfo['sale_rank']==4 && $userSaleAmountMonth < 10000){
                                $sale_amount_lack = 10000 - $userSaleAmountMonth;
                        }elseif($shopkeeperInfo['sale_rank']==3 && $userSaleAmountMonth < 7500){
                            $sale_amount_lack = 7500 - $userSaleAmountMonth;
                        }
                        if ($sale_amount_lack) {

                            $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 23, $orderInfo['year_month_next'], $orderInfo['year_month'], $sale_amount_lack);
                        }
                    }
                }
            }
        }
    }

    /**
     * 取消订单后每月总裁奖的处理。
     * @param $orderInfo array('shopkeeper_id'=>XXX,'year_month'=>201604,'year_month_next'=>201605,'commStartTime'=>2016-05,'commEndTime'=>2016-06)
     * 
     */
    private function __withdrawMonthChairmanBonusOfOrder($orderInfo){

        $curYm = date('Ym');

        if($orderInfo['year_month'] != $curYm){//取消历史月的订单

            $qualified = FALSE;
            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],4,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月奖金

                $qualified = TRUE;
            }elseif( $orderInfo['year_month_next']==$curYm && $this->tb_infinity_generation_log->checkIfInCurMonthChairmanList($orderInfo['shopkeeper_id']) ){

                $qualified = TRUE;
            }

            if($qualified){

                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($userSaleAmountMonth < 25000){

                    $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'],4,$orderInfo['year_month_next'],$orderInfo['year_month'],25000-$userSaleAmountMonth);
                }
            }
        }
    }

    public function checkIfInCurMonthQualifiedMonthLeaderList($uid){

        $this->load->model('tb_month_leader_bonus');
        $this->load->model('tb_month_top_leader_bonus');
        $this->load->model('tb_month_leader_bonus_lv5');

        if($this->tb_month_leader_bonus->checkIfInCurMonthLeader($uid)){
            return true;
        }
        if($this->tb_month_top_leader_bonus->checkIfInCurMonthTopLeader($uid)){
            return true;
        }
        if($this->tb_month_leader_bonus_lv5->checkIfInCurMonthLeaderLv5($uid)){
            return true;
        }
        return FALSE;
    }

    /**
     * 取消订单后处理每周领导对等奖。
     * @param $orderInfo array('shopkeeper_id'=>XXX,'year_month'=>201604,'year_month_next'=>201605,'commStartTime'=>2016-05,'commEndTime'=>2016-06)
     * 
     */
    private function __withdrawWeekLeaderBonusOfOrder($orderInfo){

        $this->load->model('o_cash_account');
        $curYm = date('Ym');

        if($orderInfo['year_month'] == $curYm){//取消当前月的订单
            
            $firstDayliComm = $this->o_cash_account->getFirstCommLog(array('uid'=>$orderInfo['shopkeeper_id'],'item_type'=>7));
            $firstDayliCOmmDate = '';
            if($firstDayliComm){
                $firstDayliCOmmDate = date('Ym',strtotime($firstDayliComm['create_time']));
            }elseif($this->tb_week_leader_members->checkIfInCurMonthQualifiedUserList($orderInfo['shopkeeper_id'])){
                $firstDayliCOmmDate = $curYm;
            }
            if( $firstDayliCOmmDate == $curYm ){//如果是当月第一次拿奖

                $curSaleInfo = $this->tb_users_store_sale_info->getUserSaleInfo($orderInfo['shopkeeper_id']);
                if($orderInfo['year_month'] >= config_item('commission_time')) {
                    if ($curSaleInfo < 10000) {//个人店铺零售订单上月后台显示必须达到100美金或以上。
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 7, $orderInfo['year_month'], $orderInfo['year_month'], 10000 - $curSaleInfo);
                    }
                }else{
                    if( $curSaleInfo < 7500 ){
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 7, $orderInfo['year_month'], $orderInfo['year_month'], 7500 - $curSaleInfo);
                    }
                }
            }
        }else{//取消历史月的订单

            $qualified = FALSE;
            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],7,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月奖金

                $qualified = TRUE;
            }elseif( $orderInfo['year_month_next']==$curYm && $this->tb_week_leader_members->checkIfInCurMonthQualifiedUserList($orderInfo['shopkeeper_id']) ){
                $qualified = TRUE;
            }
            if( $qualified ){
                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($orderInfo['year_month'] >= config_item('commission_time')) {
                    if ($userSaleAmountMonth < 10000) {//个人店铺零售订单上月后台显示必须达到100美金或以上。
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 7, $orderInfo['year_month_next'], $orderInfo['year_month'], 10000 - $userSaleAmountMonth);
                    }
                }else{
                    if($userSaleAmountMonth < 7500){
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 7, $orderInfo['year_month_next'], $orderInfo['year_month'], 7500 - $userSaleAmountMonth);
                    }
                }
            }
        }
    }

	/**
	 *	取消订单即时抽回团队销售
	 */
	private function __revulsionTeamGeneration($order_id){

        $this->load->model('o_cash_account');

		/** 判断订单是否已经抽回团队销售佣金 */
        $order_info = $this->tb_trade_orders->get_one("customer_id",['order_id'=>$order_id]);
        $uid = $order_info['customer_id'];
        //获取父id
        $user_info = $this->tb_users->get_user_info($uid,"id,parent_ids");
        if (!empty($user_info['parent_ids'])) {
            $parent_ids = explode(',',$user_info['parent_ids']);
        }
        $teamSales = array();
        if (isset($parent_ids[0])) {
            $is_grant_0 = $this->tb_commission_logs->is_grant_cash_order($parent_ids[0]);
        }

        if(isset($parent_ids[1])) {
            $is_grant_1 = $this->tb_commission_logs->is_grant_cash_order($parent_ids[1]);
        }

		//没有发放团队销售
		if($is_grant_0 === FALSE && $is_grant_1 === FALSE ) return;
        if($is_grant_0) {
            $teamSales_0 = $this->o_cash_account->getCashAccountLog(array('order_id'=>$order_id,'uid'=>$parent_ids[0],'item_type'=>3));
        }
        if($is_grant_1) {
            $teamSales_1 = $this->o_cash_account->getCashAccountLog(array('order_id'=>$order_id,'uid'=>$parent_ids[1],'item_type'=>3));
        }
        if (!empty($teamSales_0)) {
            $teamSales = $teamSales_0;
        }
        if (!empty($teamSales_1)) {
            $teamSales = array_merge($teamSales,$teamSales_1);
        }
		if($teamSales)foreach($teamSales as $item){
			$reduce_log = array(
				'uid'=>$item['uid'],
				'item_type'=>16,
				'amount'=>-tps_int_format($item['amount']),
				'order_id'=>$order_id,
				'related_uid'=>$item['related_uid'],
			);
            $this->tb_users->updateUserAmount($item['uid'],-$item['amount']/100);//从现金池抽回相应佣金
			$this->o_cash_account->createCashAccountLog($reduce_log);//生成抽回的资金变动记录


		}
	}

    /** 取消订单抽回每月团队组织分红奖
     * @param $orderInfo
     */
    private function __withdraw2x5ForceMatrix($orderInfo){
        $curYm = date('Ym');

        if($orderInfo['year_month'] != $curYm){//取消历史月的订单

            $qualified = FALSE;
            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],1,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月奖金
                $qualified = TRUE;
            }

            if($qualified){
                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($orderInfo['year_month'] >= config_item('commission_time')) {
                    if ($userSaleAmountMonth < 10000) {//一百美金
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 1, $orderInfo['year_month_next'], $orderInfo['year_month'], 10000 - $userSaleAmountMonth);
                    }
                }else{
                    if($userSaleAmountMonth < 7500){
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 1, $orderInfo['year_month_next'], $orderInfo['year_month'], 7500 - $userSaleAmountMonth);
                    }
                }
            }
        }
    }

    /** 取消订单抽回每周团队分红
     * @param $orderInfo
     */
    private function __withdrawPlanWeekShare($orderInfo){
        $curYm = date('Ym');

        if($orderInfo['year_month'] != $curYm){//取消历史月的订单

            $qualified = FALSE;
            if( $this->tb_commission_logs->checkUserCommissionOfType($orderInfo['shopkeeper_id'],25,$orderInfo['commStartTime'],$orderInfo['commEndTime']) ){//检查用户是否拿到取消订单影响的那个月奖金
                $qualified = TRUE;
            }

            if($qualified){
                $userSaleAmountMonth = $this->tb_users_store_sale_info_monthly->getSaleAmountByUidAndYearMonth($orderInfo['shopkeeper_id'],$orderInfo['year_month']);
                if($orderInfo['year_month'] >= config_item('commission_time')) {
                    if ($userSaleAmountMonth < 10000) {//个人店铺累计了100美金销售额。小于既要补单
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 25, $orderInfo['year_month_next'], $orderInfo['year_month'], 10000 - $userSaleAmountMonth);
                    }
                }else{
                    if($userSaleAmountMonth < 7500){
                        $this->tb_withdraw_task->addOne($orderInfo['shopkeeper_id'], 25, $orderInfo['year_month_next'], $orderInfo['year_month'], 7500 - $userSaleAmountMonth);
                    }
                }
            }
        }
    }

}
