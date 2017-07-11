<?php

class m_admin_helper extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    /*
     * 补发2x5佣金
     * @param $user_id
     * @param $leader_id
     */
//    public function getCommission($user_id,$leader_id){
//
//        $this->load->model('o_cash_account');
//        $commission=0.0;      //所得佣金
//        $levelNum=0;
//        $QSO_count=$this->getQSO($leader_id);       //合格直推人
//        $leader_rank=$this->userInfo($leader_id)->user_rank; //当前leader的店铺等级
//        $leader_status=$this->userInfo($leader_id)->status;   //当前leader的状态（1=>激活状态）
//
//        /**合格直推人达到2个或以上，且状态为1，达到奖励条件**/
//        if($QSO_count>=2 && $leader_status==1 && $leader_rank<4) {
//            if($QSO_count==2){$levelNum=5;}
//            else if($QSO_count==3){$levelNum=10;}
//            else if($QSO_count==4){$levelNum=15;}
//            else if($QSO_count==5){$levelNum=20;}
//            else if($QSO_count>5){$levelNum=25;}
//
//            $user_level=$this->userInfo2x5($user_id)->level;            //当前升级用户的所在层数
//            $leader_level=$this->userInfo2x5($leader_id)->level;        //上级所在层数
//            $month_fee_rank=$this->userInfo($user_id)->month_fee_rank;  //当前升级用户的月费等级
//
//            //如果升级ID在leader的层级内，则拿升级用户的佣金
//            if($user_level-$leader_level<$levelNum){
//                switch($leader_rank){
//                    case 1:
//                        if($month_fee_rank==LEVEL_DIAMOND)$commission=DIAMOND_CASH;
//                        if($month_fee_rank==LEVEL_GOLD)$commission=GOLD_CASH;
//                        if($month_fee_rank==LEVEL_SILVER)$commission=SILVER_CASH;
//                        break;
//                    case 2:
//                        if($month_fee_rank<=LEVEL_GOLD)$commission=GOLD_CASH;
//                        if($month_fee_rank==LEVEL_SILVER)$commission=SILVER_CASH;
//                        break;
//                    case 3:
//                        if($month_fee_rank<=LEVEL_SILVER)$commission=SILVER_CASH;
//                        break;
//                }
//
//                /**记录到commission_log表**/
//                $data = array(
//                    'uid' => $leader_id,
//                    'item_type' => REWARD_1,
//                    'amount' => tps_int_format($commission*100),
//                    'create_time' => $this->getUserUpgradeTime($user_id),
//                    'related_uid' => $user_id
//                );
//
//                if($this->o_cash_account->createCashAccountLog($data)){
//                    /* 佣金自动转分红点 */
//                    $this->load->model('m_profit_sharing');
//                    $rate = $this->m_profit_sharing->getProportion($leader_id, 'sale_commissions_proportion') / 100;
//                    $commissionToPoint = 0;
//                    if ($rate > 0) {
//                        $commissionToPoint = tps_money_format($commission * $rate);
//                        if($commissionToPoint>=0.01){
//                            $this->db->where('id', $leader_id)->set('profit_sharing_point', 'profit_sharing_point+' . $commissionToPoint, FALSE)->set('profit_sharing_point_from_force', 'profit_sharing_point_from_force+' . $commissionToPoint, FALSE)->update('users');
//                            $this->m_profit_sharing->createPointAddLog(array(
//                                'uid' => $leader_id,
//                                'add_source' => 2,
//                                'money' => $commissionToPoint,
//                                'point' => $commissionToPoint
//                            ));
//                        }
//                    }
//                    /***累加amount、 personal_commission字段值***/
//                    $this->db->where('id', $leader_id)
//                        ->set('amount', 'amount+' . ($commission - $commissionToPoint), FALSE)
//                        ->set('personal_commission', 'personal_commission+' . $commission, FALSE)->update('users');
//                    return true;
//                }
//            }
//        }
//        return false;
//    }

    /**
     * 扣除2x5佣金
     * @param $user_id
     * @param $leader_id
     */
    public function removeCommission($user_id,$leader_id){

    }
}
