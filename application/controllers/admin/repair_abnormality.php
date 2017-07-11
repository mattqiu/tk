<?php
/**
 * User: ckf
 * Date: 2017/01/06
 * Time: 16:44
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class repair_abnormality extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_user_email_exception_list');
        $this->load->model('tb_users');
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
                'url' => "admin/repair_abnormality/repair?tabs_type=2",
            ),
            3 => array(
                'desc' => lang('user_monthly_sales'),
                'url' => "admin/repair_abnormality/repair?tabs_type=3",
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
                $page = "user_appellation";
                break;

            case 3:
                $page = "user_monthly";
                break;

            case 4:
                $page = "trade_repair_addnumber";
                break;
            case 5:
                $page = "trade_repair_add_doba";
                break;
            case 6:
                $page = "trade_repair_recovery";
                break;

            default:
                redirect(base_url('admin/repair_abnormality/order_repair'));
        }

        // 公共 view data
        $this->_viewData['title'] = lang('repair_abnormality');

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
    public function user_appellation(){
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

                $this->load->model('m_user');
                $this->m_user->checkSelfLevel($attr['uid']);
                echo json_encode(array('success'=>true,'msg'=>lang('update_success')));
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
}