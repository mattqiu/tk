<?php
/**
 * Created by PhpStorm.
 * User: WYS
 * Date: 2017/3/29
 * Time: 16:23
 */
class tb_users_month_fee_log extends MY_Model
{
	protected $table = "users_month_fee_log";
	protected $table_name = "users_month_fee_log";

	public function __construct()
	{
		parent::__construct();
	}

	public function add_ignore($data)
	{
		return $this->db->replace($this->table,$data);
	}


}