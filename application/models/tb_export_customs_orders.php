<?php
/**
 * Created by PhpStorm.
 * User: Ckf
 * Date: 2017/6/28
 * Time: 15:46
 */
class tb_export_customs_orders extends MY_Model
{
    protected $table_name = "export_customs_orders";
    function __construct(){
        parent::__construct();
    }

    public function getList($admin_id){
        $this->db->from('export_customs_orders');
        $this->db->select("id,fifter_array,file_name,file_path,status,admin_id,create_time");
        $data = $this->db->where("admin_id",$admin_id)->order_by("create_time","desc")->limit(10)->get()->result_array();
        if($data){
            foreach($data as $v){
                if(time()-strtotime($v["create_time"])>=60*60 && $v["status"]==1){
                    $this->db->where("id",$v["id"])->update("export_customs_orders",array("status"=>4));
                }
            }
        }
        return $data;
    }
}