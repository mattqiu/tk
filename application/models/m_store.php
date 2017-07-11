<?php
/**
 * 店铺类。
 */
class m_store extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 获取用户的店铺相关信息。
     * @author Terry
     * @return array (
        'id' => '1381234767',//店铺id/用户id
        'user_rank' => '4',//店铺等级
        'status' => '1',//状态（激活、月费支付等信息）
        'qualified' => true,//是否合格店铺
      )
     */
    public function getStoreInfo($uid){
        $return = array();
        $storeBaseInfo = $this->getStoreBaseInfo($uid);
        if(!$storeBaseInfo){
            return array();
        }
        $return+=$storeBaseInfo;
        $return['qualified'] = $this->getQualified($storeBaseInfo);
        return $return;
    }

    /*获取用户店铺的销售额（套装销售额＋零售销售额）*/
    public function getStoreSaleAmount($uid,$year_month){

        $sale_amount = 0;
        $formatYearMonth = substr($year_month,0,4).'-'.substr($year_month,4);
        $nextYearMonth = yearMonthAddOne($year_month);
        $formatNextYearMonth = substr($nextYearMonth,0,4).'-'.substr($nextYearMonth,4);

        $res = $this->db->select('id')->from('users')->where('parent_id',$uid)->get()->result_array();
        foreach($res as $item){
            $recentLevelChangeInfo = $this->db->select('new_level')->from('users_level_change_log')->where('level_type',2)->where('uid',$item['id'])->where('create_time >=',$formatYearMonth)->where('create_time <',$formatNextYearMonth)->order_by('create_time','desc')->get()->row_array();

            if($recentLevelChangeInfo){
                $recentLevel = $recentLevelChangeInfo['new_level'];
                $resOldLevel = $this->db->select('new_level')->from('users_level_change_log')->where('level_type',2)->where('uid',$item['id'])->where('create_time <',$formatYearMonth)->order_by('new_level')->get()->row_array();
                $oldLevel = $resOldLevel?$resOldLevel['new_level']:4;
                $sale_amount += $this->getLevelChangeAmount($oldLevel,$recentLevel);
            }
        }

        $resSaleAmount2 = $this->db->select('sale_amount')->from('users_store_sale_info_monthly')->where('uid',$uid)->where('year_month',$year_month)->get()->row_object();
        $sale_amount += $resSaleAmount2?$resSaleAmount2->sale_amount:0;
        $sale_amount = $sale_amount>=25000?$sale_amount:0;

        return $sale_amount;
    }

    public function getLevelChangeAmount($oldLevel,$newLevel){

        $sale_amount = 0;
        switch ($oldLevel) {
            case 4:
                if($newLevel==5){
                    $sale_amount = 25000;
                }else{
                    $sale_amount = ($oldLevel-$newLevel)*50000;
                }
                break;
            case 5:
                if($newLevel==4 || $newLevel==5){
                    $sale_amount = 0;
                }else{
                    $sale_amount = ($oldLevel-$newLevel-1)*50000-25000;
                }
                break;
            case 3:
                if($newLevel>=3){
                    $sale_amount = 0;
                }else{
                    $sale_amount = ($oldLevel-$newLevel)*50000;
                }
                break;
            case 2:
                if($newLevel>=2){
                    $sale_amount = 0;
                }else{
                    $sale_amount = ($oldLevel-$newLevel)*50000;
                }
                break;
            case 1:
                $sale_amount = 0;
                break;
        }
        return $sale_amount;
    }
    
    /**
     * 获取商铺基础信息
     * @param int $uid
     * @author Terry
     */
    public function getStoreBaseInfo($uid){
        return $this->db->select('id,user_rank,status')->from('users')->where('id',$uid)->get()->row_array();
    }
    
    /**
     * 获取店铺的合格状态
     * @author Terry
     */
    public function getQualified($storeBaseInfo){

        $return = FALSE;
        if($storeBaseInfo['user_rank']==4){
            if($storeBaseInfo['status']==1){
                if(use_temporary_rule()){
                    $return = TRUE;
                }else{
                    $return = TRUE;//todo@terry在自己店铺有一个25美金以上的订单。
                }
            }
        }else{
            if($storeBaseInfo['status']==1){
                $return = TRUE;
            }
        }
        return $return;
    }
    
}
