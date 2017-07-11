<?php
/**
 * 套餐代品券类。
 * @terry
 */
class m_suite_exchange_coupon extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 给用户发代品券
     * @param int $uid
     * @author Terry
     */
    public function add($uid,$face_value,$num=1){
        for($i=1;$i<=$num;$i++){
            $this->db->insert('user_suite_exchange_coupon', array(
                'face_value'=>$face_value,
                'uid'=>$uid,
            ));
        }
    }


    /**
     * @param $user_id  用户id
     * @param $voucher  代品券的金额
     */
    public function add_voucher($user_id,$voucher){
        $voucher = intval($voucher);

        $length=strlen($voucher);

        for($i=1;$i<=$length;$i++){
            if($i<3){
                $arr[$i] = substr($voucher, $length-$i,1);      //获取十位和个位
            }else{
                $arr[$i] = substr($voucher, 0,$length-2);       //获取百位
                break;
            }
        }
        foreach($arr as $k=>$v){
            if($k==1){
                $this->add($user_id,1,$v);
                continue;
            }
            if($k==2){
                $this->add($user_id,10,$v);
                continue;
            }
            if($k>2){
                $this->add($user_id,100,$v);
                continue;
            }
        }
    }

    /*
    * 获取用户所有代品券 by Terry.
    * @param int $status  0未使用，1已使用
    * @author Terry
    */
    public function getAll($uid,$status=0){

        $return = array();
        $res = $this->db->query("select face_value,count(id) num from user_suite_exchange_coupon a where a.uid=$uid and a.status=$status group by face_value order by face_value desc")->result_array();
        foreach($res as $item){
            $return['m'.$item['face_value']] = $item['num'];
        }
        return $return;
    }

    /*
     * 获取用户所有代品劵的总额
     * @param int $status 0未使用，1已使用
     * @author Andy
     */
    public function getAmount($uid,$status=0){
        $num = 0;
        $res = $this->db->query("select face_value from user_suite_exchange_coupon a where a.uid=$uid and a.status=$status")->result_array();
        foreach($res as $item){
            $temp = intval($item['face_value']);
            $num+=$temp;
        }
        return $num;
    }

    private function __useOneFaceValueCoupon($uid,$face_value,$num=1){
        $res = $this->db->select('id')->from('user_suite_exchange_coupon')->where('uid',$uid)->where('face_value',$face_value)->where('status',0)->limit($num)->get()->result_array();
        $usedIds = array();
        foreach($res as $item){
            $usedIds[] = $item['id'];
        }
        $this->db->where_in('id', $usedIds)->update('user_suite_exchange_coupon', array('status'=>1,'use_time'=>date('Y-m-d H:i:s')));
    }

    public function useAllCoupon($uid){
        $this->db->where('uid', $uid)->where('status',0)->update('user_suite_exchange_coupon', array('status'=>1,'use_time'=>date('Y-m-d H:i:s')));
    }

    /**
    * 使用代品券抵扣金额
    * @param $uid 用户id
    * @param $amount 需要抵扣的金额（必须为整数）
    * @return 抵扣后剩余金额。 #用代品券抵扣金额后的剩余金额（0代表抵扣完成，如果>0代表代品券全部抵扣后的剩余金额。）
    * @author Terry
    */
    public function useCoupon($uid,$amount){

        $amount = (int)$amount;
        $allCoupons = $this->getAll($uid);
        $totalCouponAmount = 0;
        foreach($allCoupons as $k=>$couponNum){
            $totalCouponAmount += (int)substr($k,1) * $couponNum;
        }

        if($totalCouponAmount==0){
            return $amount;
        }

        if($totalCouponAmount<=$amount){
            $this->useAllCoupon($uid);
            return $amount-$totalCouponAmount;
        }

        $suite_exchange_coupon_face_value = config_item('suite_exchange_coupon_face_value');
        foreach($suite_exchange_coupon_face_value as $val){

            if(isset($allCoupons['m'.$val])){

                $num = floor($amount/$val);
                if($num==0){
                    continue;
                }
                if($allCoupons['m'.$val]>=$num){
                    $useNum = $num;
                }else{
                    $useNum = $allCoupons['m'.$val];
                }
                $this->__useOneFaceValueCoupon($uid,$val,$useNum);
                $amount = $amount-$useNum*$val;
                if($amount==0){
                    return 0;
                }
            }
        }

        /*start 当代品券没有合适面额抵扣全部金额时，这里触发找零机制。*/
        $allCoupons = $this->getAll($uid);
        $suite_exchange_coupon_face_value_reverse = array_reverse($suite_exchange_coupon_face_value);
        foreach($suite_exchange_coupon_face_value_reverse as $val){
            if($val==1){
                continue;
            }

            if(isset($allCoupons['m'.$val])){
                $this->__useOneFaceValueCoupon($uid,$val);
                $amountNeedAdd = $val-$amount;
                foreach($suite_exchange_coupon_face_value as $valAdd){
                    if($valAdd<=$amountNeedAdd){
                        $num = floor($amountNeedAdd/$valAdd);
                        $this->add($uid,$valAdd,$num);
                        $amountNeedAdd = $amountNeedAdd-$valAdd*$num;
                        if($amountNeedAdd==0){
                            return 0;
                        }
                    }
                }
            }
        }
        /*end*/
    }
}
