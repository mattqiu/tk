<?php
/**
 * 工单模块
 * 客服账号管理
 */
class tb_admin_tickets_customer_role extends CI_Model{

    function __construct()
    {
        parent::__construct();
    }

    public function get_all_customer(){
        $this->db->from('admin_tickets_customer_role as c');
        $this->db->select('u.id as admin_id,u.job_number,u.email,u.create_time,c.role,c.id');
        $this->db->join('admin_users as u','c.admin_id=u.id','right');
        $this->db->where_in('status',array(1,2))->order_by('u.job_number','ASC')->where('job_number >',0);
        return $this->db->get()->result_array();
    }

    public function get_customer_by_admin_id($admin_id){
        $this->db->from('admin_tickets_customer_role');
        $this->db->select('role');
        $this->db->where('admin_id',$admin_id);
        return $this->db->get()->row_array();
    }
    public function add_customer($arr){
        $this->db->insert('admin_tickets_customer_role',$arr);
        return $this->db->insert_id();
    }

    public function update_customer($id,$role){
        $this->db->set('role',$role);
        $this->db->where('id',$id);
        $this->db->update('admin_tickets_customer_role');
        return $this->db->affected_rows();
    }

    public function delete_customer($id){
        $this->db->where('id',$id);
        return $this->db->delete('admin_tickets_customer_role');
    }

    public function add_logs($arr){
        $this->db->insert('admin_tickets_customer_role_logs',$arr);
        return $this->db->insert_id();
    }
}