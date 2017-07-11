<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 计划任务类
 * @author  soly
 */
class Cron_order_trade extends CI_Controller {
	
	private $_currentFilename = array();
	
    public function __construct() {
		parent::__construct();
		if(!$this->input->is_cli_request()){
            echo 'Please run this script in CLI.';
            exit;
        }
		$this->load->model('m_trade');
		$this->load->model('m_erp');
	}
	
	// 开始导入数据
	public function import() {
		// 设置与客户机断开是否会终止脚本的执行
        ignore_user_abort();
		
		// 设置永不超时
        set_time_limit(0);

		// 读取excel表格的数据 order_id Logistics Compny TrckingNumber
		$sqlSt      = 'SELECT * FROM trade_order_cron_import';
		$query      = $this->db->query($sqlSt);
		unset($sqlSt);
		
		// 没有需要处理的数据
		if (!$query->num_rows()) exit('not data.');
		
		$importData = array();
		$importData = $query->result_array();
		
		// 获取物流信息
		$freSQL      = 'SELECT company_code,company_name FROM trade_freight';
		$freightData = $freight = array();
		$freight     = $this->db->query($freSQL)->result_array();
		unset($freSQL);
		
		foreach ($freight as $items) {
			isset($freightData[$items['company_code']]) || $freightData[$items['company_code']] = $items['company_name'];
		}
		unset($freight);
		
		/* 检测完成，批量修改订单的物流信息 */
		$insert_trade_log_data = $update_data = array();
		
		$this->load->model('m_user_helper');
		
		// 事物处理
		$this->db->trans_begin();
		$deleteIds = array();
		foreach ($importData as $items) {
			$update_time   = date('Y-m-d H:i:s');
			// 构建批量更新订单状态的数据
			$freight_info  = $items['company_code'].'|'.$items['trck_num'];
			$freight_info  = trim($freight_info, '|');
			$update_data[] = array(
				'order_id' 		=> $items['order_id'],
				'freight_info' 	=> $freight_info,
				'status' 		=> Order_enum::STATUS_SHIPPED,
				'deliver_time' 	=> $update_time,
			);
			unset($freight_info);
			
			
			
			// 构建批量插入订单日志的数据 soly
			$company = isset($freightData[$items['company_code']]) && $freightData[$items['company_code']] 
						? $freightData[$items['company_code']] : $this->m_user_helper->getFreightName($items['company_code']);
			$insert_trade_log_data[] = array(
				'order_id' 		=> $items['order_id'],
				'oper_code' 	=> 102,
				'statement' 	=> "$company $items[trck_num]->批量导入",
				'operator_id' 	=> $items['uid'],
				'update_time' 	=> $update_time,
			);
			
			// 如果發貨的訂單支付是paypal 記錄跟踪號到mall_orders_paypal_info
			$this->m_trade->paypal_order_deliver($items['order_id'], array('company_code' => $items['company_code'], 'express_id' => $items['trck_num']));
			
			//添加到订单推送表
			$insert_data 							= array();
			$insert_data['oper_type'] 				= 'modify';
			$insert_data['data']['order_id'] 		= $items['order_id'];
			$insert_data['data']['status'] 			= Order_enum::STATUS_SHIPPED;
			$insert_data['data']['logistics_code'] 	= $items['company_code'];
			$insert_data['data']['tracking_no'] 	= $items['trck_num'];
			$insert_data['data']['deliver_time'] 	= $update_time;
			$this->m_erp->trade_order_to_erp_oper_queue($insert_data);
			unset($insert_data);
			
			$deleteIds[] = $items['id'];
		}
		unset($importData);
		
		// 批量插入订单日志数据
		$this->db->insert_batch('trade_orders_log', $insert_trade_log_data);

		// 更新订单状态
//		$this->db->update_batch('trade_orders', $update_data, 'order_id');
        $this->load->model("tb_trade_orders");
        $this->tb_trade_orders->update_batch_auto(
            [
                "data"=>$update_data,
                "index"=>'order_id'
            ]
        );

		unset($insert_trade_log_data, $update_data, $freightData);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			exit('update faild.');
		} else {
			if ($deleteIds) {
				$this->db->where_in('id', $deleteIds);
				$this->db->delete('trade_order_cron_import');
			}
			$this->db->trans_complete();
			unset($deleteIds);
			exit('update success.');
		}

		exit('run success.');
	}
}	