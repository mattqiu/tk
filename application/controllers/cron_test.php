<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}


/**
 * @author brady
 * @desc 测试cron
 * Class cron_test
 */
class Cron_test extends MY_Controller {
	
	public function __construct() {

		parent::__construct();
		$this->load->model('o_cron');
		$this->load->model('o_pcntl');
		ignore_user_abort(true);
		set_time_limit(0);
	}
	
	/**
	 * @author brady
	 * @desc 初始化用户的积分
	 */
	public function init_user_credit($page_size = 10)
	{
		$this->o_cron->init_user_credit($page_size);
	}





}