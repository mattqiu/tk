<?php
/**
 * @author Terry
 */
class tb_users_store_sale_info extends CI_Model {


    function __construct() {
        parent::__construct();
    }
    
    /**
     * 更新某个用户的销售业绩（订单数、订单总额）
     * @param $uid
     * @param $updateArr array('orders_num_update'=>$orders_num_update,'sale_amount_update'=>$sale_amount_update) (数组里面item非必需项)
     * @return boolean
     * @author Terry
     */
    public function updateByUid($uid,$updateArr){

        if( isset($updateArr['orders_num_update']) ){
            if($updateArr['orders_num_update']>=0){
                $updateArr['orders_num_update'] = '+'.$updateArr['orders_num_update'];
            }
            $this->db->set('orders_num', 'orders_num' . $updateArr['orders_num_update'], FALSE);
        }

        if( isset($updateArr['sale_amount_update']) ){
            if($updateArr['sale_amount_update']>=0){
                $updateArr['sale_amount_update'] = '+'.$updateArr['sale_amount_update'];
            }
            $this->db->set('sale_amount', 'sale_amount' . $updateArr['sale_amount_update'], FALSE);
        }

        $this->db->where('uid', $uid)->update('users_store_sale_info');
        $nu = $this->db->affected_rows();
        return $nu;
    }

    /**
     * 获取用户店铺销售业绩信息。
     * @param $uid
     * @param boolean $only_return_sale_amount 是否只返回sale_amount字段
     * @return sale_amount 销售额（单位：分）
        ))
     */
    public function getUserSaleInfo($uid, $only_return_sale_amount = true){
        $res = $this->db->select('sale_amount')->from('users_store_sale_info')->where('uid',$uid)->get();
        if ($res) {
        	$res = $res->row_array();
	        if ($only_return_sale_amount) {
		        return $res?$res['sale_amount']:0;
	        } else {
	        	return $res;
	        }
        }
        return array();
    }
    
    /**
     * 新增用户销售数据
     * @author: derrick
     * @date: 2017年3月25日
     * @param: @param int $uid 用户ID
     * @param: @param int $orders_num 店铺订单总数 
     * @param: @param int $sale_amount 店铺销售总额（不含运费，单位：美分）
     * @reurn: return_type
     */
    public function add_user_sale_info($uid, $orders_num, $sale_amount) {
    	$this->db->insert('users_store_sale_info',array(
            'uid'=>$uid,
            'orders_num'=>$orders_num,
            'sale_amount'=>$sale_amount,
        ));
        $nu = $this->db->affected_rows();
        return $nu;
    }
}
