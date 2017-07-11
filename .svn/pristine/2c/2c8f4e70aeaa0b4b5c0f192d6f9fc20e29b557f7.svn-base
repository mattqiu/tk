<?php
/**
 * 日志类
 */
class m_log extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function createCronLog($content){
        $this->db->insert('logs_cron', array(
            'content' => var_export($content, 1),
        ));
    }

    /** 订单失败 或 支付异步通知异常 by john 2015-7-23 */
    public function createOrdersLog($order_sn,$content){
        $this->db->insert('logs_orders', array(
            'order_sn' => $order_sn,
            'content' => var_export($content, 1),
        ));
    }

    //2017.6.27海关erp记录表
    public function dd_log($content = '',$content1 = '',$content2 = '',$content3 = '',$content4 = ''){
        $this->db->insert('log_erp_hg', array(
            'content' => var_export($content, 1),
            'content1' => $content1,
            'content2' => $content2,
            'content3' => $content3,
            'content4' => $content4,
        ));
    }
    public function createInterfaceLog($content){
        $this->db->insert('logs_interface', array(
            'content' => var_export($content, 1),
        ));
    }

    public function addInterfaceWalhao($content,$type=1){
        $this->db->insert('logs_interface_walhao', array(
            'type' => $type,
            'content' => var_export($content, 1),
        ));
    }

	/** 支付方式异步通知记录 */
	public function createOrdersNotifyLog($type,$payment_type,$content){
		$this->db->insert('logs_orders_notify', array(
			'type' => $type,
			'payment_type' => $payment_type,
			'content' => var_export($content, 1),
		));
	}

	/** 支付成功，订单回滚记录 */
	public function ordersRollbackLog($order_id,$txn_id){
		$count = $this->db->from('logs_orders_rollback')->where('order_id',$order_id)->count_all_results();
		if($count == 0){
			$this->db->insert('logs_orders_rollback', array(
				'order_id' => $order_id,
				'txn_id' => $txn_id,
				'status' => 0,
				'process_num' => 0,
			));
		}
	}

    /* 读取 logs_orders_rollback 列表 */
    public function ordersRollbackLogList($filter,$perPage = 10){
        $this->db->from('logs_orders_rollback');
        $this->filterForNews($filter);
        $list = $this->db->select('')->limit($perPage, ($filter['page'] - 1) * $perPage)->order_by('create_time','desc')->get()->result_array();
        return $list;
    }

    /**
     * 获取分页总数
     */
    function  getExceptionRows($filter){
        $this->db->from('logs_orders_rollback');
        $this->filterForNews($filter);
        $row = $this->db->count_all_results();
        return $row;
    }

    public function filterForNews($filter){
        foreach ($filter as $k => $v) {
            if (!$v || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', strtotime($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', strtotime($v));
                    break;
                case 'order_sn':
                    if(is_numeric($v)){
                        $this->db->where('order_id', $v);
                    }else{
                        $this->db->where('order_id', $v);
                    }
                    break;
                case 'tabs_type':
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }


	/** 管理员操作日常日志
	 * @param $admin_id 管理员ID
	 * @param $action   管理员操作行为:删除，修改，新增
	 * @param $action_table 操作对应的表：users，orders
	 * @param $action_object_id 操作的对象：user_id,order_id,email
	 * @param $action_field 操作的字段：email
	 * @param $before_data 操作前的数据
	 * @param $after_data 操作后的数据
	 * @param $change_data 增加或减少 如：月费转现金池，现金池转分红点，分红点转现金池
	 */
	public function adminActionLog($admin_id,$action,$action_table,$action_object_id,$action_field,$before_data,$after_data,$change_data=0){
		$this->db->insert('admin_action_logs', array(
			'admin_id' => $admin_id,
			'action' => $action,
			'action_table' => $action_table,
			'action_object_id' => $action_object_id,
			'action_field' => $action_field,
			'before_data' => $before_data,
			'after_data' => $after_data,
			'change_data' => $change_data
		));
	}

	public function admin_after_sale_remark($as_id,$admin_id,$remark){
		$this->db->insert('admin_after_sale_remark', array(
			'as_id' => $as_id,
			'admin_id' => $admin_id,
			'remark' => $remark,
		));
	}

    /**
     * 检测订单是否在交易队列里面
     */
    public function check_order_in_queue($order_id){
        return $this->db->from('logs_orders_rollback')->where('order_id',$order_id)->where('status',0)->where('process_num <',10)->count_all_results();
    }

}
