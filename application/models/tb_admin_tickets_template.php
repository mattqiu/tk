<?php
/**
 * 自定义模板
 * @author andy
 */
class tb_admin_tickets_template extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    /** 添加一条模板
     * @param $template_arr
     * @return mixed
     */
    public function add_tickets_template($template_arr){
        $this->db->insert('admin_tickets_template',$template_arr);
        return $this->db->insert_id();
    }

    //显示主列表,（条件组合）
    public function get_template_list($searchData,$perPage = 10){
        $sql = "SELECT a.*,u.email AS email FROM admin_tickets_template AS a LEFT JOIN admin_users AS u ON(a.admin_id=u.id) WHERE (a.admin_id = ? OR a.status = ?)";
        $data =array($searchData['admin_id'], 0);
        if(isset($searchData['type'])){
            if($searchData['type']!=''){
                $sql.="AND a.type = ?";
                array_push( $data,$searchData['type']);
            }
        }
        if(!empty($searchData['tickets_template_name'])){
            $sql.="AND a.name like ?";
            array_push($data,'%'.$searchData['tickets_template_name'].'%');
        }
        $sql.= " ORDER BY a.create_time DESC";
        $sql.=" limit ".($searchData['page'] - 1) * $perPage.",".$perPage;
        $arr =  $this->db->order_by('a.create_time','ASC')->query($sql,$data)->result_array();
        //var_dump($str = $this->db->last_query());
        return $arr;
    }

    //获取列表
    public function get_template($admin_id,$type){
        $this->db->from('admin_tickets_template');
        $sql = "SELECT * FROM admin_tickets_template WHERE (admin_id = ? OR status = ?) AND type=?";
        $data = array($admin_id,0,$type);
        return $this->db->query($sql,$data)->result_array();
    }

    public function get_template_by_id($id){
        $this->db->from('admin_tickets_template');
        $this->db->where('id',$id);
        return $this->db->get()->row_array();
    }

    public function update_template($arr,$admin_id){
        $this->db->set('content',$arr['content']);
        $this->db->set('name',$arr['name']);
        $this->db->set('type',$arr['type']);
        $this->db->set('status',$arr['status']);
        $this->db->where('id',$arr['id']);
        if(!in_array($admin_id,array(62,144,68))){
            $this->db->where('admin_id',$admin_id);
        }
        $this->db->update('admin_tickets_template');
        return $this->db->affected_rows();
    }

    public function delete_tickets_template($id){
        return $this->db->delete('admin_tickets_template',array('id' => $id));
    }

    public function get_template_count($searchData){
        $sql = "SELECT * FROM admin_tickets_template WHERE (admin_id = ? OR status = ?)";
        $data =array($searchData['admin_id'], 0);
        if(isset($searchData['type']) && $searchData['type']!='' ){
            $sql.="AND type = ?";
            array_push( $data,$searchData['type']);
        }
        if(!empty($searchData['tickets_template_name'])){
            $sql.="AND name like ?";
            array_push($data,'%'.$searchData['tickets_template_name'].'%');
        }
        $num =  $this->db->query($sql,$data)->num_rows();
        return $num;
    }
}