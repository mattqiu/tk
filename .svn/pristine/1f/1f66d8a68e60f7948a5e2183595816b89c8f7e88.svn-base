<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/25
 * Time: 14:39
 */
class tb_grant_pre_users_new_member_bonus extends  MY_Model
{
	protected $table  ="grant_pre_users_new_member_bonus";
	protected $table_name = "grant_pre_users_new_member_bonus";

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @author brady
	 * @desc 批量插入的数据
	 * @param $data
	 * ['uid'=>$v['uid'],'amount'=>tps_int_format($v['bonus_shar_weight'] / $totalWeight*$totalMoney),"create_time"=>time(),"bonus_time"=>date("Ymd",strtotime("-1 day"))]
	 */
	public function add_batch($data)
	{
		$sql = "insert ignore into ".$this->table."(uid,amount,create_time,bonus_time) values";
		foreach($data as $v) {
			$sql .= "(".$v['uid'].",".$v['amount'].",".$v['create_time'].",".$v['bonus_time']."),";
		}
		$sql = substr($sql,0,strlen($sql)-1);
		$this->db->query($sql);

	}

	/**
	 * @author brady
	 * @desc 每次执行预发布的时候，清空上一次的数据
	 */
	public function empty_data()
	{
		$this->db->truncate($this->table);
	}

	/**
	 * 批量删除
	 */
	public function del_batch($ids)
	{
		$this->db->where_in("id",$ids);
		$this->db->delete($this->table);

	}

	/**
	 * 获取总数
	 */
	public function get_total_rows($uid='')
	{

		if (!empty($uid)) {
			$res = $this->db->from($this->table_name)
				->select('count(id) as number')
				->where(array('uid'=>$uid))
				->get()
				->row_object()
				->number;
		} else {
			$res = $this->db->from($this->table_name)
				->select('count(id) as number')
				->get()
				->row_object()
				->number;
		}

		return $res;
	}

	/**
	 * 分页获取
	 */
	public function get_by_list($page,$page_size,$uid='')
	{
		if (empty($uid)) {
			$res = $this->db->from("grant_pre_users_new_member_bonus as a")
				->select("a.id,a.uid,a.amount,a.bonus_time,a.create_time,users.name")
				->order_by('a.id')
				->join('users',"users.id = a.uid")
				->limit($page_size,($page-1)*$page_size)
				->get()
				->result_array();
		} else {
			$res = $this->db->from("grant_pre_users_new_member_bonus as a")
				->select("a.id,a.uid,a.amount,a.bonus_time,a.create_time,users.name")

				->order_by('a.id')
				->join('users',"users.id = a.uid")
				->where(array('a.uid'=>$uid))
				->limit($page_size,($page-1)*$page_size)
				->get()
				->result_array();
		}


		return $res;
	}

	public function get_total_money()
	{
		return $this->db->from($this->table)
			->select("sum(amount) as total_money")
			->get()
			->row_object()
			->total_money;

	}



}