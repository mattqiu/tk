<?php
/**工单自定义标签提示
 * @create time 2016..6.29
 * @author andy
 */
class tb_admin_tickets_tips extends CI_Model{
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin_user');
    }

    /** 写入一个记录
     * @param $tips_arr
     * @author andy
     * @return mixed
     */
    public function add_tips($tips_arr){
        $this->db->insert('admin_tickets_tips',$tips_arr);
        return  $this->db->insert_id();
    }

    /** 获取某个订单下的所有提示
     * @param $tickets_id  可改为  join
     * @author andy
     * @return mixed
     */
    public function get_tips_by_tickets_id($tickets_id){
        $this->db->select('t.tickets_id,t.reply_id,t.content,t.admin_id,t.create_time,t.is_reply,u.email,u.role');
        $this->db->from('admin_tickets_tips as t');
        $this->db->where('t.tickets_id',$tickets_id);
        $this->db->join('admin_users as u','t.admin_id=u.id');
        return $this->db->get()->result_array();
    }
}