<?php

/**tickets status log
 * @author andy
 */
class tb_admin_tickets_logs extends MY_Model{
    protected $table_name = "admin_tickets_logs";

    function __construct(){
        parent::__construct();
    }


    /** 插入记录
     * @param $status_arr
     * @return mixed
     */
    public function add_log($status_arr){
        //$this->db->insert('admin_tickets_logs',$status_arr);
        //return $this->db->insert_id();
        return $this->insert_one($status_arr);
    }

    /** 批量插入记录
     * @param $data
     * @return mixed
     */
    public function batch_add_logs($data){
        //return $this->db->insert_batch('admin_tickets_logs', $data);
        return $this->insert_batch($data);
    }


    /** According to the tickets id to read log
     * @param $tickets_id 已废弃
     */
    public function get_logs_by_tickets_id_2($tickets_id){
        $this->db->from('admin_tickets_logs as t')->select('t.*,u.email');
        $this->db->where('t.tickets_id',$tickets_id);
        $this->db->join('admin_users as u','t.admin_id =u.id','left');
        return $this->db->get()->result_array();
    }

    public function get_logs_by_tickets_id($tickets_id){
        $select              = 'id,tickets_id,old_data,new_data,data_type,admin_id,is_admin,create_time';
        $where['tickets_id'] = $tickets_id;
        $list                = $this->get_list($select,$where);
        $admin_ids           = array();
        $all_cus             = array();
        $cus_info            = array();
        if($list){
            foreach($list as $val){
                if(!in_array($val['admin_id'],$admin_ids) && $val['is_admin']==1){
                    array_push($admin_ids,$val['admin_id']);
                }
            }

            $this->load->model('m_admin_user');
            if($admin_ids){
                $cus_info = $this->m_admin_user->get_customer_by_ids($admin_ids);
            }
            if($cus_info){
                foreach($cus_info as $item){
                    $all_cus[$item['id']] = $item;
                }
            }

            foreach($list as &$v){
                if(isset($all_cus[$v['admin_id']])){
                    $v['email'] = $all_cus[$v['admin_id']]['email'];
                }else{
                    $v['email'] = 0;
                }
            }
        }
        return $list;
    }

    /**
    public function search_transfer_date_by_id($admin_id,$tickets_id){
        $this->db->from('admin_tickets_logs');
        $this->db->where('tickets_id',$tickets_id);
        $this->db->where('data_type',3);
        $this->db->where('new_data',$admin_id);
        $res = $this->db->get()->result_array();
        if($res){
            $row = end($res);
           return date('Y-m-d',strtotime($row['create_time']));
        }else{
            return false;
        }
    }
     * **/
}