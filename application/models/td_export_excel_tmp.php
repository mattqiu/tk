<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/3/20
 * Time: 17:31
 */
class td_export_excel_tmp extends MY_Model {

    protected $table = "export_order_tmp";

    public function __construct()
    {
        parent::__construct();
    }


    public function getone(){
        $max_thead = $this->redis_get("export:max:threads");
        if(!$max_thead){
            $max_thead = 5;
        }
        $count = $this->db->from($this->table)->where("status",1)->force_master()->count_all_results();
        if($count >= $max_thead){
            return "";
        }
        $this->db->from($this->table);
        $query = $this->db->where("status",0)->force_master()->order_by("system_time","asc")->get()->row_array();
        return $query;
    }

    public function getList($admin_id){
        $this->db->from($this->table);
        $this->db->select("id,operator_id,status,admin_id,system_time,filename,update_path");
        $data = $this->db->where("admin_id",$admin_id)->where("status !=",3)->order_by("system_time","desc")->limit(10)->get()->result_array();
        if($data){
            foreach($data as $v){
                if(time()-strtotime($v["system_time"])>=60*60 && $v["status"]==1){
                    $this->db->where("id",$v["id"])->update("export_order_tmp",array("status"=>4));
                }
            }
        }
        return $data;
    }

}