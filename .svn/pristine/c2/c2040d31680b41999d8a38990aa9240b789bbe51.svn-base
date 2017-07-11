<?php
/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/4/19
 * Time: 17:36
 */

class tb_cron_status extends  MY_Model
{
    protected $table = "cron_status";
    protected $table_name = "cron_status";

    public function __construct()
    {
        parent::__construct();
    }

    public function add_one($type,$status,$content='')
    {
        $data = ["type"=>$type,'status'=>$status,'cron_day'=>date("Ymd",time()),'content'=>$content,'create_time'=>date("Y-m-d H:i:s")];
        $this->db->replace($this->table,$data);
        return $this->db->insert_id();
    }

    /**
     * 删除某个类型的 正在执行的
     * @param $type
     */
    public function del_one($type)
    {
        $this->db->delete($this->table,array('type'=>$type,'status'=>1,'cron_day'=>date("Ymd")));
        return $this->db->affected_rows();
    }

    public function delete($where) {
        $this->db->delete($this->table,$where);
        return $this->db->affected_rows();
    }

    public function get_failed($type)
    {
        $res = $this->db->from($this->table)
            ->where(['type'=>$type,'status'=>3,'cron_day'=>date("Ymd")])
            ->limit(1)
            ->get()
            ->row_array();
        if (!empty($res)) {
            return false;
        } else {
            $rs = $this->db->from($this->table)
                ->where(['type'=>$type,'status'=>2,'cron_day'=>date("Ymd")])
                ->limit(1)
                ->get()
                ->row_array();
            if (!empty($rs)) {
                return true;
            } else {
                return false;
            }
        }
    }
}