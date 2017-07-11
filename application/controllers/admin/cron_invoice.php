<?php
/**
 * 发票状态已完成的定时任务
 * soly
 * 2017-05-10
 */
 
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cron_invoice extends CI_Controller {

    public function __construct() {
		parent::__construct();
		if(!$this->input->is_cli_request()){
			echo 'Please run this script in CLI.';
			exit;
		}
    }
	
	/** 发票已邮寄之后72小时自动处理已完成 **/
	public function changestatus() {
		$outTime = 3600 * 24 * 7;
		$time    = date('Y-m-d H:i:s');
		// 获取已邮寄的状态的数据
		$invoiceList = array();
		$invoiceList = $this->db->where('status', 2)->get('trade_invoice')->result_array();
		
		if (empty($invoiceList)) exit('not data.');
		
		// 处理数据
		foreach ($invoiceList as $invoice) {
			$invoice['created_at'] = trim($invoice['created_at']);
			$createdTime           = strtotime($invoice['created_at']);
			if ((time() - $createdTime) >= $outTime) {
				
				$this->db->update('trade_invoice', array('status' => 4, 'update_at' => $time), array('id' => intval($invoice['id'])));
				
				// 添加记录日志
				$this->db->insert('trade_invoice_log', array(
                    'operator_id'   => 0,
                    'invoice_num'   => trim($invoice['invoice_num']),
                    'status'        => 4,
                    'remark'        => '已完成【邮寄之后系统7天后自动处理】',
                    'created_at'    => $time
                ));
			} else { continue; }
		}
		exit('run success.');
	}
}