<?php
/**
 * @author Andy
 * 回复工单的数据
 */
class tb_admin_tickets_reply extends MY_Model
{
    protected $table_name = "admin_tickets_reply";
    function __construct(){
        parent::__construct();
    }
	/** 获取原始工单的回复 */
	public function get_ticket_reply($original_id){
        $select = 'id,tickets_id,content,admin_id,uid,is_attach,sender,type,create_time';
        $where['tickets_id'] = (int)$original_id;
        $res    = $this->get_list($select,$where);
        return  $res;
//        $this->db->from('admin_tickets_reply as t');
//        $this->db->select('t.id,t.tickets_id,t.content,t.admin_id,t.uid,t.is_attach,t.sender,t.type,t.create_time');
//        $this->db->where('t.tickets_id',(int)$original_id);
//        $result = $this->db->get()->result_array();
//        return $result;
	}

    /** 添加回复信息
     * @param $insert_arr
     * @return mixed
     */
    public function add_reply($insert_arr){
        $this->db->insert('admin_tickets_reply',$insert_arr);
        return $this->db->insert_id();
    }

    //获取回复工单信息
    // andy
    public function get_reply_tickets_by_id($reply_id){

        $select = 'id,tickets_id,content,admin_id,uid,is_attach,sender,type,create_time';
        $where['id'] = (int)$reply_id;

        return $this->get_one($select,$where);
    }
}