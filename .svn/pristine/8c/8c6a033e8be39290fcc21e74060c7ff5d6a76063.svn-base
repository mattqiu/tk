<?php
/**
 * User: ckf
 * Date: 2017/01/06
 * Time: 16:44
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class repair_abnormality_sale extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_user_email_exception_list');
        $this->load->model('tb_users');
        $this->load->model('tb_trade_orders');
        $this->load->model('m_group');
        $this->load->model('m_erp');
    }
    /**
     * 修复异常问题
     * User: Ckf
     * Date: 2017/01/06
     */
    public function repair()
    {
        // 输入参数
        $attr = $this->input->get();
        if (false === $attr)
        {
            $attr = array();
        }

        // 标签栏映射表
        $tabs_map = array(
//
            2 => array(
                'desc' => lang('user_appellation'),
                'url' => "admin/repair_abnormality_sale/repair?tabs_type=2",
            ),
            3 => array(
                'desc' => lang('user_monthly_sales'),
                'url' => "admin/repair_abnormality_sale/repair?tabs_type=3",
            ),
            4 => array(
                'desc' => lang('admin_trade_repair_recovery'),
                'url' => "admin/repair_abnormality_sale/repair?tabs_type=4",
            ),

        );
        $this->_viewData['tabs_map'] = $tabs_map;

        // 标签类型
        if (isset($attr['tabs_type']) && isset($tabs_map[$attr['tabs_type']]))
        {
            $tabs_type = $attr['tabs_type'];
        }
        else
        {
            $tabs_type = 1;
        }
        $this->_viewData['tabs_type'] = $tabs_type;

        // 状态映射表
        $this->_viewData['status_map'] = array(
            Order_enum::STATUS_INIT => array('class' => "text-default", 'text' => lang('admin_order_status_init')),
            Order_enum::STATUS_CHECKOUT => array('class' => "text-primary", 'text' => lang('admin_order_status_checkout')),
            Order_enum::STATUS_SHIPPING => array('class' => "text-warning", 'text' => lang('admin_order_status_paied')),
            Order_enum::STATUS_SHIPPED => array('class' => "text-info", 'text' => lang('admin_order_status_delivered')),
            Order_enum::STATUS_EVALUATION => array('class' => "text-success", 'text' => lang('admin_order_status_arrival')),
            Order_enum::STATUS_COMPLETE => array('class' => "text-muted", 'text' => lang('admin_order_status_finish')),
            Order_enum::STATUS_HOLDING => array('class' => "text-holding", 'text' => lang('admin_order_status_holding')),
            Order_enum::STATUS_RETURNING => array('class' => "text-danger", 'text' => lang('admin_order_status_returning')),
            Order_enum::STATUS_RETURN => array('class' => "text-danger", 'text' => lang('admin_order_status_refund')),
            Order_enum::STATUS_CANCEL => array('class' => "text-danger", 'text' => lang('admin_order_status_cancel')),
        );

        // 根据不同标签类型获取不同数据
        switch ($tabs_type)
        {
            case 1:
                $page = "direct_push";
                break;

            case 2:
                $page = "user_appellation_sale";
                break;

            case 3:
                $page = "user_monthly";
                break;

            case 4:
                $page = "trade_repair_recovery_upgrade";
                break;
            default:
                redirect(base_url('admin/repair_abnormality_sale/order_repair'));
        }

        // 公共 view data
        $this->_viewData['title'] = lang('repair_abnormality_sale');

        parent::index('admin/', $page);
    }
    /**
     * 修复会员下线总人数
     *User: Ckf
     *Date: 2017/01/06
     */
    public function push_num(){
        if($this->input->is_ajax_request()){
            $attr = $this->input->post();
            if (isset($attr['uid']) && $attr['uid'] != "" && isset($attr['num']) && $attr['num'] >= 0)
            {
                //检查用户id是否存在
                $one = $this->tb_users->getUserInfo($attr['uid']);
                if(empty($one)){
                    echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
                    exit();
                }

                $this->db->where('id',$attr['uid'])->set('child_count',$attr['num'])->update('users');
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
                exit;
            }
        }
    }

    /**
     * 修复会员职称
     *User: Ckf
     *Date: 2017/01/07
     */
    public function user_appellation_sale(){
        $this->load->model('tb_users');     
        $this->load->model('tb_users_child_group_info');        
        if($this->input->is_ajax_request()){
            $attr = $this->input->post();
            if (isset($attr['uid']) && $attr['uid'] != "")
            {
                //检查用户id是否存在
                $one = $this->tb_users->getUserInfo($attr['uid']);
                if(empty($one)){
                    echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
                    exit();
                }
                //更新职称升级时间
                if (isset($attr['u_time']) && $attr['u_time'] != "")
                {
                    $this->tb_users->edit_user_sale_up_time($attr['uid'],$attr['u_time']);
                }
                
                //更新下级数
                if (isset($attr['u_level']) && $attr['u_level'] != 0 )
                {
                    if (isset($attr['u_num']) && $attr['u_num'] != "" )
                    {
                        $this->tb_users_child_group_info->modify_user_level_num($attr['uid'],$attr['u_level'],$attr['u_num']);                        
                    }
                    else 
                    {
                        $this->tb_users_child_group_info->edit_user_sale_rank($attr['uid'],$attr['u_level']);
                    }
                }
                
                //更新so数
                if(isset($attr['infovalue']) && $attr['infovalue']!=0)
                {
                    $this->tb_users_child_group_info->edit_users_sale_rank_info_num($attr['uid'],$attr['so_num']);
                }
                
                $this->load->model('m_user');
                $this->m_user->checkSelfLevel($attr['uid']);
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
                exit;
            }
        }

    }
    
    /**
     * 获取会员职称情况
     *User: Ckf
     *Date: 2017/01/07
     */
    public function get_user_appellation_sale(){
        $this->load->model('tb_users');
        $this->load->model('tb_users_child_group_info');
        
        if($this->input->is_ajax_request())
        {
            $attr = $this->input->post();          
            if (isset($attr['user_id_search']) && $attr['user_id_search'] != "")
            {
                //检查用户id是否存在
                $one = $this->tb_users->getUserInfo($attr['user_id_search']);
                if(empty($one)){
                    echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
                    exit();
                }
              
                $result_value= $this->tb_users_child_group_info->find_by_user_id($attr['user_id_search']); 
                echo json_encode(array('success'=>true,'msg'=>$result_value));
                exit;
            }
        }
    
    }

    /**
     * 修复会员销售额
     * User:CKf
     * Date:2017/04/20
     */
    public function fixSaleAmount(){

        if($this->input->is_ajax_request()){
            $attr = $this->input->post();
            if (isset($attr['uid']) && $attr['uid'] != "")
            {
                //检查用户id是否存在
                $one = $this->tb_users->getUserInfo($attr['uid']);
                if(empty($one)){
                    echo json_encode(array('success'=>false,'msg'=>lang('no_exist')));
                    exit();
                }

                $this->load->model('tb_users_store_sale_info_monthly');
                $this->tb_users_store_sale_info_monthly->statistics_user_monthly($attr['uid'],$this->_adminInfo['id']);
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
                exit;
            }
        }
    }

    public function order_recovery(){
        if($this->input->is_ajax_request()){
            $attr = $this->input->post();
            $remark = trim($attr['remark']);
            $order_id = trim($attr['order_id']);
            $status = $attr['status'];

            //订单号不能空
            if($order_id == '')
            {
                echo json_encode(array('success'=>false,'msg'=>lang('order_id_not_null')));
                exit;
            }

            // 查询订单是否存在
            $one = $this->tb_trade_orders->getOrderInfo($order_id,array('status','customer_id','goods_amount_usd','score_year_month','order_profit_usd','shopkeeper_id','pay_time','txn_id','freight_info'));

            //备注不能为空
            if($remark == '')
            {
                echo json_encode(array('success'=>false,'msg'=>lang('pls_input_reson')));
                exit;
            }
            //恢复到等待收货和已完成时判断该订单是否有快递信息
            if(($status == 4 || $status == 6) && $one['freight_info'] ==''){
                echo json_encode(array('success'=>false,'msg'=>lang('admin_order_not_logistics')));
                exit;
            }

            //事务开始
            $this->db->trans_begin();

            //更改订单状态
//            $pay_time = empty($one['pay_time'])?date('Y-m-d H:i:s',time()):$one['pay_time'];
            if(empty($one['pay_time']) || $one['pay_time'] == '0000-00-00 00:00:00'){
                $pay_time = date('Y-m-d H:i:s',time());
            }else{
                $pay_time = $one['pay_time'];
            }
            $txn_id = empty($one['txn_id'])?$order_id:$one['txn_id'];
//            $this->db->where('order_id', $order_id)->update('trade_orders',array('status' => $status,'pay_time' => $pay_time,'txn_id' => $txn_id));
            $this->tb_trade_orders->update_one(['order_id'=>$order_id],
                array('status' => $status,'pay_time' => $pay_time,'txn_id' => $txn_id)
                );
            //恢复订单后库存变动
            $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$order_id)->from('trade_orders_goods')->get()->result_array();
            $this->m_group->update_goods_number($goods, $order_id);

            $this->db->insert('trade_order_remark_record',array(
                'order_id'=>$order_id,
                'type'=>'1',
                'remark'=>$remark,
                'admin_id'=>$this->_adminInfo['id']
            ));

            //插入到trade_orders_log
            $this->m_trade->add_order_log($order_id,112,'状态恢复status：'.$status.','.$remark,$this->_adminInfo['id']);

            //组装数组,添加到订单推送表
            $insert_data = array();
            $insert_data['oper_type'] = 'modify';
            $insert_data['data']['order_id'] = $order_id;
            $insert_data['data']['status'] = $status;
            $insert_data['data']['txn_id'] = $txn_id;
            $insert_data['data']['pay_time'] = $pay_time;

            $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

            $insert_data_remark = array();
            $insert_data_remark['oper_type'] = 'remark';
            $insert_data_remark['data']['order_id'] = $order_id;
            $insert_data_remark['data']['remark'] = $remark;
            $insert_data_remark['data']['type'] = "1"; //1 系统可见备注，2 用户可见备注
            $insert_data_remark['data']['recorder'] = $this->_adminInfo['id']; //操作人
            $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());

            $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('success'=>false,'msg'=>lang('transaction_rollback')));
                exit;
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
                exit;
            }
        }
    }
}