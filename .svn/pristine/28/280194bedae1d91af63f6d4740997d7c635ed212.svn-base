<?php
/**
 * Created by PhpStorm.
 * User: WYS
 * Date: 2017/3/29
 * Time: 15:39
 */
class tb_monthly_fee_coupon  extends MY_Model
{
	protected $table = "monthly_fee_coupon";
	protected $table_name  ="monthly_fee_coupon";

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @authro brady
	 * @desc 删除用户最早的一张月费券
	 */
	public function delete_coupon_one($id)
	{
		return $this->db->delete($this->table,array('id'=>$id));
	}


}