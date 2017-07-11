<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class reset_group extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->_viewData['title'] = lang('reset_group');
        $this->load->model('m_admin_helper');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['user_id'] = isset($searchData['user_id'])?$searchData['user_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';
        $this->_viewData['list'] = $this->m_admin_helper->get_reset_list($searchData);

        $this->load->library('pagination');
        $url = 'admin/reset_group';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->get_reset_group_rows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    public function check_submit(){
        $order_id=$this->input->post('order_id');
        $type=$this->input->post('type');

        //可重置的订单状态
        $status=array('3','99');

        //验证格式
//        if(!preg_match("/^[0-9]*$/",$order_id)){
//            echo json_encode(array('success'=>false,'msg'=>lang('order_not_exits')));
//            exit();
//        }

        //验证非空
        if(trim($order_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
            exit();
        }

        $this->load->model("tb_trade_orders");

        //重置选择套装
        if($type=='1'){
            //是否存在选购订单
//            $res=$this->db->query("select * from trade_orders where payment_type='1' and order_id='$order_id'")->result();

            $res=$this->tb_trade_orders->get_list("*",["payment_type"=>'1',"order_id"=>$order_id]);
            if(empty($res)){
                echo json_encode(array('success'=>false,'msg'=>lang('order_not_exits')));
                exit();
            }
        }

        //重置升级套装
        if($type=='2'){
            //是否存在选购套餐订单
//            $sql="select t.* from trade_orders as t, trade_orders_type as tt where t.order_id='$order_id' and t.order_id=tt.order_id  and t.pay_time is not null order by t.pay_time desc ";
//            $res=$this->db->query($sql)->result();

            $orders = $this->tb_trade_orders->get_list_auto([
                "select"=>"order_id",
                "where"=>[
                    "order_id"=>$order_id,
                    "pay_time is not null"=>null,
                ]
            ]);
            $this->load->model("tb_trade_orders_type");
            $orders_type = $this->tb_trade_orders_type->get_one("order_id",["order_id"=>$order_id]);

//            if(empty($res)){
            if(empty($orders) || empty($orders_type)){
                echo json_encode(array('success'=>false,'msg'=>lang('not_find_this_upgrade_order')));
                exit();
            }
        }

        //只有待发货的订单才可以重置
        if(in_array($res[0]->status,$status)== false){
            echo json_encode(array('success'=>false,'msg'=>lang('this_order_not_reset')));
            exit();
        }

        //订单里的代品券金额
        $coupons_amount=($res[0]->discount_amount_usd)/100;
        $user_id=$res[0]->customer_id;

        //使用过代品券则不可以重置
        $this->load->model('m_coupons');
        $coupons_list=$this->m_coupons->get_coupons_list($user_id);
        if($coupons_list['total_money']<$coupons_amount){
            echo json_encode(array('success'=>false,'msg'=>lang('you_use_coupons_not_can_reset')));
            exit();
        }

        //下单三天后不可以重置
//        $after_date=strtotime($res[0]->expect_deliver_date);
//        if(time()>$after_date){
//            echo json_encode(array('success'=>false,'msg'=>lang('order_a_timeout_not_can_reset')));
//            exit();
//        }

		//阶段性升级的用户不能重置升级订单
        $this->load->model("tb_trade_orders");
//		$customer_id = $this->db->query("select customer_id from trade_orders WHERE order_id = '$order_id'")->row_array();
		$customer_id = $this->tb_trade_orders->get_one_auto([
		    "select"=>"customer_id",
            "where"=>["order_id"=>$order_id]
        ]);
		$customer_id = $customer_id['customer_id'];
		$upgrade_count = $this->db->query("select * from user_upgrade_log WHERE uid='$customer_id'")->result_array();
		if(count($upgrade_count)>1)
		{
			echo json_encode(array('success'=>false,'msg'=>lang('this_user_have_more_than_once_upgrade_record')));
			exit();
		}

        $order_id=$res[0]->order_id;
        $this->reselect($user_id,$coupons_amount,$order_id,$type);
    }


    //重新换购
    public function reselect($user_id,$coupons_amount,$order_id,$type){
        $this->load->model("tb_trade_orders");
        $this->db->trans_begin();
        //取消订单
//        $sql="update trade_orders set status='99' where order_id='$order_id' ";
        $res_sql = $this->tb_trade_orders->update_one(["order_id"=>$order_id],["status"=>'99']);
        //删除代品券
//        if($this->db->query($sql)) {
        if($res_sql) {
            $this->load->model('m_suite_exchange_coupon');
            $this->m_suite_exchange_coupon->useCoupon($user_id,$coupons_amount);

            //选购状态设为0
            $sql = "update users set is_choose=0 where id=$user_id";

            if ($this->db->query($sql)) {
                //插入log表
                $data=array('user_id'=>$user_id,'order_id'=>"$order_id", 'reset_type'=>$type,'admin_id'=>$this->_adminInfo['id'],'create_time'=>date("Y-m-d H:i:s",time()));
                $this->db->insert('reset_group_log',$data);

                echo json_encode(array('success' => true, 'msg' => lang('reset_choose_group_success')));
            }
        }
        $this->db->trans_commit();
    }

    /***检查订单类型***/
    public function check_order_type(){
        $order_id=$this->input->post('order_id');

        //验证格式
//        if(!preg_match("/^[0-9]*$/",$order_id)){
//            echo json_encode(array('success'=>false,'msg'=>lang('order_not_exits')));
//            exit();
//        }

        //验证非空
        if(trim($order_id)==''){
            echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
            exit();
        }

        //查找订单是否存在
//        $res=$this->db->query("select * from trade_orders where order_id='$order_id' ")->result_array();
        $this->load->model("tb_trade_orders");
        $res = $this->tb_trade_orders->get_list("*",["order_id"=>$order_id]);
        if(empty($res)){
            echo json_encode(array('success'=>false,'msg'=>lang('order_not_exits')));
            exit();
        }

        //选购订单
        if($res[0]['payment_type']==1){
            echo json_encode(array('success'=>true,'type'=>'1','msg'=>lang('this_order_is_choose_order').$res[0]['customer_id']));
            exit();
        }

        //升级订单
        $upgrade_res=$this->db->query("select * from trade_orders_type where order_id='$order_id' ")->result_array();
        if(!empty($upgrade_res)){
            echo json_encode(array('success'=>true,'type'=>'2','msg'=>lang('this_order_is_upgrade_order').$res[0]['customer_id']));
            exit();
        }

        echo json_encode(array('success'=>false,'msg'=>lang('this_order_is_basic_order')));
        exit();
    }
}

