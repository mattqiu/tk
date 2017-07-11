<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Debug extends MY_Controller {

	
  	public function index()
  	{
            $this->_viewData['logs'] = $this->m_debug->getLogs();
            $this->load->view('debug',$this->_viewData);
  	}
  	
	/**
	 * 订单支付成功的业绩统计队列执行日志
	 * @author: derrick
	 * @date: 2017年4月11日
	 * @param: 
	 * @reurn: return_type
	 */
	public function cron_queen_deal_queen_for_stat_user_store_history() {
		$page = $this->input->get('page');
		if (!is_numeric($page) || $page < 1) {
			$page = 1;
		}
		$size = $this->input->get('size');
		if (!is_numeric($size) || $size < 1) {
			$size = 500;
		}
		$start = ($page - 1) * $size;
		$end = $page * $size - 1;
		$end = $end < 1 ? 0 : $end;
		
		$this->load->model('o_queen');
		$logs = $this->o_queen->redis_lGetRange(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER_HISTORY, $start, $end);
		foreach ($logs as $key => $l) {
			$l = $this->o_queen->get_value($l);
			echo ($key+1).':';
			print_r($l);
			echo "<br>";
		}
	}
	
	/**
	 * 订单支付成功的业绩统计队列日志
	 * @author: derrick
	 * @date: 2017年4月13日
	 * @param: 
	 * @reurn: return_type
	 */
	public function cron_queen_deal_queen_for_stat_user_store_list() {
		$page = $this->input->get('page');
		if (!is_numeric($page) || $page < 1) {
			$page = 1;
		}
		$size = $this->input->get('size');
		if (!is_numeric($size) || $size < 1) {
			$size = 500;
		}
		$start = ($page - 1) * $size;
		$end = $page * $size - 1;
		$end = $end < 1 ? 0 : $end;
	
		$this->load->model('o_queen');
		$delete = $this->input->get('delete');
		if ($delete) {
			$this->o_queen->redis_ltrim(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER, $page*$size, $this->o_queen->get_queen_length(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER));
		}
		
		$logs = $this->o_queen->redis_lGetRange(o_queen::QUEEN_FOR_STAT_USER_STORE_DATE_AFTER_CREATE_ORDER, $start, $end);
		foreach ($logs as $key => $l) {
			$l = $this->o_queen->get_value($l);
			echo ($key+1).':';
			print_r($l);
			echo "<br>";
		}
	}

	public function test() {
		/* require_once APPPATH.'third_party/AES/AES.php';
		$aes = new aes();
		$id = 1381234567;
		$key = '4svp+!A138FS+d_O';
		echo '原文:'.$id."<br>";
		$data = $aes->aes256ecbEncrypt($id, '', $key);
		echo '密文:'.$data."<br>";
		echo '解密密文:'.$aes->aes256ecbDecrypt($data, '', $key)."<br>";
		echo '密钥:'.$key;
		exit; */
		echo send_mail('derrick.zhang@shoptps.com', 'test', 'test');
		/* echo send_mail('zhipiao.xie@shoptps.com', 'test', 'test');
		echo send_mail('511879941@qq.com', 'test', 'test');
		echo send_mail('jet.wu@shoptps.com', 'test', 'test'); */
		exit;
		$this->load->model('o_zookeeper');
		var_dump($this->o_zookeeper->get('/'));
		echo "<br>";
		var_dump($this->o_zookeeper->getChildren('/'));
		echo "<br>";
		var_dump($this->o_zookeeper->get('com.tps.report.statistics.service.GoodSaleCountService.testTask'));
		echo "<br>";
		$this->o_zookeeper->set('com.tps.report.statistics.service.GoodSaleCountService.findById', '1');
		var_dump($this->o_zookeeper->get('com.tps.report.statistics.service.GoodSaleCountService.findById'));
		exit;
		$this->o_zookeeper->watch('/com.tps.report.statistics.service.GoodSaleCountService.findById', function($call) {
			print_r($call);
			echo 'i am callback';
		});
		exit;
		
		$zc = new Zookeeper();
		$zc->connect('192.168.0.180:2181');
		$path = 'tps.report.statistics.service.GoodSaleCountService.testTask';
// 		$zc->set($path, 123);
		var_dump($zc->getChildren('/'));
	}
	
}