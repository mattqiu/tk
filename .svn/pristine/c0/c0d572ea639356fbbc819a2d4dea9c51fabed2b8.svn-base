<?php

/**tickets record
 * @author andy
 */
class tb_admin_tickets_record extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function add_record($record){
        $this->db->insert('admin_tickets_record',$record);
        return $this->db->insert_id();
    }

    public function batch_add_record($data){
        return $this->db->insert_batch('admin_tickets_record', $data);
    }

    public function batch_add_record_2($add_arr){
        if(!empty($add_arr)){
            foreach($add_arr as $k=>$u){
                //$this->update_count_by_cid($k,$u);
                $record = array(
                    'admin_id'      =>$k,
                    'type'          =>1,
                    'count'         =>$u,
                    'assign_time'   =>date('Y-m-d'),
                );
                $this->add_record($record);
            }
        }
    }

    public function get_record_count($type,$assign_time,$admin_id=''){
        if($admin_id!=''){
            $this->db->where('admin_id',$admin_id);
        }
        $this->db->from('admin_tickets_record');
        $this->db->select('admin_id,SUM(count) as count');
        $this->db->where('type',$type)->where('assign_time',$assign_time);
        $this->db->group_by('admin_id');
        return $this->db->get()->result_array();
    }

//    public function get_all_record_count($type,$assign_time){
//        $this->db->from('admin_tickets_record');
//        $this->db->select('admin_id,SUM(t.count) as count');
//        $this->db->where('type',$type)->where('assign_time',$assign_time);
//        $this->db->group_by('admin_id');
//        return $this->db->get()->result_array();
//    }
}