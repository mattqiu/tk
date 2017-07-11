<?php
/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/23
 * Time: 10:26
 */
class tb_bonus_log extends MY_Model
{
	protected $table = "bonus_log";
	protected $table_name = "bonus_log";

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 创建日志
	 */
	public function create_log($data)
	{
		return $this->db->insert($this->table_name,$data);

	}
}