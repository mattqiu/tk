<?php
/**
 * Created by PhpStorm.
 * User: leon
 * Date: 2017/3/1
 * Time: 14:08
 */
class tb_temp_save_coupons extends MY_Model{

    protected $table_name = "temp_save_coupons";
    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取总金额和总数量
     * @param $user_id
     */
    public function get_coupons_list($user_id){

        $this->load->model('tb_user_suite_exchange_coupon');
//        $select="face_value,count(id) num";
//        $where = ['uid'=>$user_id, 'status'=>0];
//        $order_by["face_value"] = "desc";
        //获取用户未使用的代金卷
        $coupons_info = $this->tb_user_suite_exchange_coupon->get_total_money($user_id);

        print_r($coupons_info);


    }

}








