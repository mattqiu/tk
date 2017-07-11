<?php

class tb_grant_bonus_user_logs extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    
	/***
	 * 添加发奖日志
	 * @param 数据集 $data
	 */
	public function add_grant_amount_logs($data){

		$sql = "INSERT INTO grant_bonus_user_logs SET uid=".$data['uid'].
		", proportion=".(int)$data['proportion'].
		", share_point=".(int)$data['share_point'].
		", amount=".(int)$data['amount'].
		", bonus=".(int)$data['bonus'].
		", type=".(int)$data['type'].
		", item_type=".(int)$data['item_type']. ",create_time=Now()";
		$this->db->query($sql);
	}

}