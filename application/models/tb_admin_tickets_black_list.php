<?php
/*工单 黑名单
 * **/
class tb_admin_tickets_black_list extends CI_Model{
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * 添加黑名单
     */
    public function add_black_list($black_arr){
        $this->db->insert('admin_tickets_black_list',$black_arr);
        return $this->db->insert_id();
    }

    /*
     * 从黑名单中移除
     */
    public function delete_black_list($id){
        $this->db->set('status',1);
        $this->db->where('id',$id);
        $this->db->update('admin_tickets_black_list');
        return $this->db->affected_rows();
    }

    /*
     * 检查某个uid是否在黑名单中
     */
    public function is_black_list($uid){
        $this->db->from('admin_tickets_black_list');
        $this->db->where('status',0);
        $this->db->where('uid',$uid);
        $count =  $this->db->count_all_results();
        if($count>0){
            return true;
        }else{
            return false;
        }
    }

    //获取黑名单
    public function get_all_black_uid(){
        $this->db->from('admin_tickets_black_list');
        $this->db->where('status',0);
        return $this->db->select('uid')->get()->result_array();
    }
    /*
     * 获取黑名单列表
     */
    public function get_black_list($filter,$per_page=10){
        $this->db->from('admin_tickets_black_list as t');
        $this->db->select('t.*,u.email');
        $this->db->join('admin_users as u','t.admin_id=u.id','left');
        $this->filterForBlacklist($filter);
        return $this->db->order_by('t.uid','DESC')->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
    }

    /*
     * 总数
     */
    public function get_black_list_count($filter){
        $this->db->from('admin_tickets_black_list as t');
        $this->filterForBlacklist($filter);
        return $this->db->count_all_results();
    }

    public function filterForBlacklist($filter){
        foreach($filter as $k=>$v){
            if ($v === '' || $k=='page') {
                continue;
            }
            switch($k){
                case 'id':{
                    $this->db->where('t.id',$v);
                    break;
                }
                case 'uid':{
                    $this->db->like('t.uid',$v);
                    break;
                }
                case 'status':{
                    $this->db->where('t.status',$v);
                    break;
                }
                case 'start':{
                    $this->db->where('t.create_time >=',$v);
                    break;
                }
                case 'end':{
                    $this->db->where('t.create_time <=',$v);
                    break;
                }
                default : {
                    $this->db->where($k,$v);
                    break;
                }
            }

        }
    }
}