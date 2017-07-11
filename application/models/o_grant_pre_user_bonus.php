<?php

class o_grant_pre_user_bonus extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /***
     * 添加预发奖记录
     * @param 发奖类型  $item_type
     * @param 月领导奖类别  $tb_type
     * @param 数据集（格式：二维数组array[]=[[0]=>array(uid=>uid,amount=>amount)]） $lists
     */
	public function add_grant_pre_user_bonus($item_type,$tb_type,$lists)
	{

	    $sql = "";
		switch($item_type)
		{
		    case 25:
		        // 每周团队销售分红奖
		        $sql = 'insert into grant_pre_every_week_team_bonus(uid,amount,create_time) VALUES ';
		        break;
		    case 1:
		        //每月团队组织分红奖
		        $sql = 'insert into grant_pre_every_month_team_bonus(uid,amount,create_time) VALUES ';
		        break;
		    case 23:
		        //每月领导分红奖
		        $sql = 'insert into grant_pre_every_month_leader_bonus(uid,amount,level,create_time) VALUES ';		         
		        break;
		}
		
		$create_time = time();
		
		if(0 != $item_type && $item_type == 23)
		{
		    foreach($lists as  $v) {
		        $sql .= "(".$v['uid'].",".$v['amount']."," .$tb_type.",'".$create_time."'),";
		    }
		}
		else
		{
		  foreach($lists as  $v) {
		        $sql .= "(".$v['uid'].",".$v['amount'].",'".$create_time."'),";
		    }
		}
		$sql = substr($sql,0,strlen($sql)-1);
		$this->db->query($sql);
		
	}

}