<?php
/**
 * 新用户专属奖日志
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/15
 * Time: 10:42
 */

class tb_logs_new_member_bonus extends  MY_Model
{
    protected $table = "logs_new_member_bonus";
    protected $table_name = "logs_new_member_bonus";

    public function add_ignore($uid)
    {
        $create_time = date("Y-m-d H:i:s",time());
        $this->db->query('insert ignore into '.$this->table_name."(uid,create_time) values({$uid},'{$create_time}')");
    }
    public function add_ignore_v2($uid)
    {
        $create_time = date("Y-m-d H:i:s",strtotime("-1 day"));
        $this->db->query('insert ignore into '.$this->table_name."(uid,create_time) values({$uid},'{$create_time}')");
    }

    /**
     * 根据用户ID查询记录
     * @author: derrick
     * @date: 2017年4月5日
     * @param: @param unknown $uid
     * @reurn: return_type
     */
	public function find_by_uid($uid) {
		$res = $this->db->where('uid',(int)$uid)->get($this->table_name);
		if ($res) {
			return $res->row_array();
		} else {
			return array();
		}
	}
}