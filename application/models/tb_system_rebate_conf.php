<?php

/**
 * 分红比例配置
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/25
 * Time: 12:04
 */
class tb_system_rebate_conf extends  MY_Model
{

	protected $table = "system_rebate_conf";
	protected $table_name = "system_rebate_conf";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_by_type($cat_id)
	{
		$res = $this->db->from($this->table)->select("id,category_id,child_id,rate_a,rate_b,rate_c,rate_d,rate_e")
			->where(['category_id'=>$cat_id])
			->limit(1)
			->get()
			->row_array();
		return $res;
	}
}