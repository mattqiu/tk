<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 计划任务类
 * @author  Terry Lu
 */
class Cron_my_order_exchage extends CI_Controller {

    public function __construct() {
		parent::__construct();
		if(!$this->input->is_cli_request()){
			echo 'Please run this script in CLI.';
			exit;
		}
    }
	
	/** 检测换货是否过期 **/
	public function testing() {
		// 设置与客户机断开是否会终止脚本的执行
        ignore_user_abort(true);
		// 设置永不超时
        set_time_limit(0);
		/** 检查换货中的数据 创建时间*72*3600 < time 更新状态 **/
		$sql   = 'SELECT id,create_time,uid,order_id FROM my_order_exchange_time ORDER BY id ASC LIMIT 5000';
		$query = $this->db->query($sql);
		if (!$query->num_rows()) exit('not data.');
		
		$returnData = array();
		$returnData = $query->result_array();

		// 事物处理
		$this->db->trans_begin();
		foreach ($returnData as $items) {
			$stime                = 72 * 3600;
			$items['create_time'] = trim($items['create_time']);
			$createTime 		  = strtotime($items['create_time']);
			
			if ($createTime && (time() - $createTime) >= $stime) {
				$time  = date('Y-m-d H:i:s');
				$total = 0;
				$total = $this->db->from('my_order_exchange_log')
								  ->where('order_id', $items['order_id'])
							      ->where('status', 2)->count_all_results();
				
				$insertData = array(
					'status' 		=> 2, 
					'uid' 			=> $items['uid'], 
					'order_id' 		=> $items['order_id'], 
					'create_time' 	=> $time
				);

				$total || $this->db->insert('my_order_exchange_log', $insertData);
				unset($insertData);
				
				// 换货超时更新订单状态 只有状态为冻结(待换货)的才更新订单状态记录操作日志
//				$isupdate = 0;
//				$isupdate = $this->db->from('trade_orders')
//								  ->where('order_id', $items['order_id'])
//							      ->where('status', Order_enum::STATUS_HOLDING)->count_all_results();

                //判断订单是不是换货中的订单，否则跳过不处理
                $this->load->model('tb_trade_orders');
                $order_info = $this->tb_trade_orders->getOrderInfo($items['order_id'], array('status','order_type','remark'));
//print_r($order_info);exit;
                if (!empty($order_info) && isset($order_info['order_type']) && isset($order_info['remark']) &&
                    $order_info['order_type'] == 2 && strstr($order_info['remark'],'#exchange') &&
                    isset($order_info['status']) && $order_info['status'] == '90') {

                    //修改订单
                    $is_ex = strstr($order_info['remark'],"#exchange");
                    $str = $order_info['remark'];
                    if($is_ex){
                        $count = strpos($order_info['remark'],"#exchange");
                        $str = substr_replace($order_info['remark'],"",$count,9);
                    }

					$this->tb_trade_orders->update_one(
					    array('order_id' => $items['order_id']),
                        array(
						'status' 	 => Order_enum::STATUS_SHIPPING,
						'remark' 	 => $str,
						'updated_at' => $time,
                        'deliver_time'=> null,
                        'freight_info'=> null
					    )
                    );
                    //订单状态同步到erp
					$this->load->model('m_erp');
					$insert_data = array();
					$insert_data['oper_type'] = 'modify';
					$insert_data['data']['order_id'] = $items['order_id'];
					$insert_data['data']['status'] = Order_enum::STATUS_SHIPPING;
                    $insert_data['data']['remark'] = $str;
                    $insert_data['data']['deliver_time'] = "0000-00-00 00:00:00";
                    $insert_data['data']['tracking_no'] = '';
                    $insert_data['data']['logistics_code'] = '-1';

					$this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

					// 订单更新日志记录
					$this->db->insert('trade_orders_log', array(
						'order_id'	  => $items['order_id'],
						'oper_code'   => 130,
						'statement'   => '72小时内未完成换货,系统自动取消',
						'operator_id' => 0,
						'update_time' => $time
					));
				}

                // 删除定时器数据
                $this->db->where('id', $items['id'])->delete('my_order_exchange_time');
			} else {
				continue;
			}
		}
		unset($returnData);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			exit('update faild.');
		} else {
			$this->db->trans_complete();
			exit('update success.');
		}
		exit('run success.');
	}
}
