<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 2015/12/30
 * Time: 11:36
 * 订单操作明细
 */
class trade_order_logs extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index(){
        $this->_viewData['title'] = lang('trade_order_logs');

        $this->load->model('m_admin_helper');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);

        // 标签栏目映射
        $tab_map = array(
            'index'    => array('desc' => lang('trade_order_logs'), 'url' => 'admin/trade_order_logs'),
            'exchange' => array('desc' => lang('uc_exchange_order_logs'), 'url' => 'admin/trade_order_logs/exchange')
        );

        // 订单流水操作代码映射表
        $order_oper_map = array(
            000 => "all_oper_code",             // 所有状态
            100 => "order_log_oper_create",     // 订单创建
            101 => "order_log_oper_modify",     // 订单修改
            108 => "order_log_oper_export",     //訂單導出
            102 => "order_log_oper_diliver",    // 订单发货
            103 => "order_log_oper_reset",      // 订单重置
            104 => "order_log_oper_rollback",   // 订单回滚
            105 => "order_log_oper_cancel",     // 订单取消
            106 => "order_log_oper_frozen",     // 订单冻结
            107 => "order_log_oper_unfrozen",   // 订单解除冻结
            109 => "order_log_oper_addr_edit",  // ERP 修改订单信息
            150 => "order_log_oper_erpmodify",  // ERP 修改订单信息

        );

        $this->_viewData['order_oper_map'] = $order_oper_map;
        $searchData['oper_code'] = isset($searchData['oper_code']) && $searchData['oper_code'] != '000' ? $searchData['oper_code']:'';
        $searchData['order_id'] = isset($searchData['order_id'])?$searchData['order_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';

        //id转换成email显示
        $list = $this->m_admin_helper->get_trade_orders_log($searchData);

        foreach($list as $k => $v)
        {
            if($v['operator_id'] != 0){
                $is_front_user = $this->db->from('users')->select('id')->where(array('id'=>$v['operator_id']))->limit(1)->get()->row_array();
                if (empty($is_front_user)) {
                    $email = $this->db->query("select email from admin_users WHERE id = {$v['operator_id']}")->row()->email;
                    $list[$k]['operator_id'] = $email;
                } else {
                    $list[$k]['operator_id'] = $v['operator_id'];
                }


            } else {
                $list[$k]['operator_id'] = 'system';
            }
        }

        $this->_viewData['list'] = $list;

        $this->load->library('pagination');
        $url = 'admin/trade_order_logs';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->get_trade_orders_log_rows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        $this->_viewData['tab_map'] = $tab_map;
        $this->_viewData['fun']     = $this->router->fetch_method();

        parent::index('admin/');
    }

    // 会员换货操作日志 soly
    public function exchange() {
        $this->_viewData['title'] = lang('uc_exchange_order_logs');

        $this->load->model('tb_trade_order_exchange_log');
        $searchData         = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);

        // 标签栏目映射
        $tab_map = array(
            'index'    => array('desc' => lang('trade_order_logs'), 'url' => 'admin/trade_order_logs'),
            'exchange' => array('desc' => lang('uc_exchange_order_logs'), 'url' => 'admin/trade_order_logs/exchange')
        );

        // 订单流水操作代码映射表
        $status_map = array(
            99  => lang('all_oper_code'),
            0   => lang('cancel_exchange'),
            1   => lang('exchanging'),
            2   => lang('exchange_timeout')
        );

        $searchData['status'] = isset($searchData['status']) && $searchData['status'] != 99 ? $searchData['status'] : '';
        $searchData['order_id'] = isset($searchData['order_id'])?$searchData['order_id']:'';
        $searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
        $searchData['end'] = isset($searchData['end'])?$searchData['end']:'';

        //id转换成email显示
        $list = $this->tb_trade_order_exchange_log->get_trade_orders_exchange_log($searchData);

        foreach($list as $k => &$v) {
            $v['status_mark'] = isset($status_map[$v['status']]) && $status_map[$v['status']] ? $status_map[$v['status']] : '';
            $v['statement']   = ($v['status'] == 2) ? lang('exchange_timeout_msg') : '';
            $v['operator_id'] = ($v['status'] == 2) ? 'system' : (empty($v['uid']) ? 'system' : $v['uid']);
        }

        $this->_viewData['list'] = $list;

        $this->load->library('pagination');
        $url = 'admin/trade_order_logs/exchange';
        add_params_to_url($url, $searchData);
        $config['base_url']   = base_url($url);
        $config['total_rows'] = $this->tb_trade_order_exchange_log->get_trade_orders_log_rows($searchData);
        $config['cur_page']   = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager']      = $this->pagination->create_links(true);

        $searchData['status'] = ($searchData['status'] == '') ? 99 :  $searchData['status'];
        
        $this->_viewData['searchData'] = $searchData;


        $this->_viewData['tab_map']    = $tab_map;
        $this->_viewData['fun']        = $this->router->fetch_method();
        $this->_viewData['status_map'] = $status_map;

        parent::index('admin/', 'trade_order_exchange_logs');
    }

    // 清除计时器 重新计时
    public function del_timer_ajax() {
        if ($this->input->is_ajax_request()) {
            $orderid = $this->input->post('orderid');
            if (!$orderid) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('info_unvalid_request')
                )));
            }

            // 管理员和客服经理才可以拥有重置定时器的权限
            if(!in_array($this->_adminInfo['role'], array('0', '2'))) {
                exit(json_encode(array(
                    'success' => 0,
                    'msg'     => lang('info_unvalid_request')
                )));
            }

            $this->db->trans_begin();
            // 删除计时器中的数据
            $this->db->where('order_id', $orderid)->delete('my_order_exchange_time');
            $this->db->where('order_id', $orderid)->delete('my_order_exchange_log');

            // 添加计时器
            $time = date('Y-m-d H:i:s');
            /*$this->db->insert('my_order_exchange_time', array(
                'uid'           => $this->_adminInfo['id'],
                'order_id'      => $orderid,
                'create_time'   => $time
            ));
            // 添加操作日志
            $this->db->insert('my_order_exchange_log', array(
                'uid'           => 0,
                'order_id'      => $orderid,
                'status'        => 1,
                'create_time'   => $time
            ));*/

            // 去换货订单操作日志
            $this->db->insert('trade_orders_log', array(
                'order_id'      => $orderid,
                'oper_code'     => 130,
                'statement'     => '管理清除计时器,重新创建',
                'operator_id'   => $this->_adminInfo['id'],
                'update_time'   => $time
            ));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['success']  = 0;
                $result['msg']      = 'System Error!';
            } else {
                $this->db->trans_complete();
                $result['success']  = 1;
                $result['msg']      = lang('update_success');
            }
            die(json_encode($result));
        }
    }
}
