<?php
class tb_mall_feedback extends MY_Model {
    protected $table_name = "mall_feedback";
    function __construct() {
        parent::__construct();
    }
    /* 新增反馈信息  */
    function add_feedback($email,$content,$user_id) {
        return $this->db->insert('mall_feedback',array('email'=>$email,'content'=>$content,'add_time'=>time(),'user_id'=>$user_id));
    }

    /* 获取问题反馈分页列表  */
    public function get_feedback_list_page($filter, $per_page = 10) {
        $this->db->from('mall_feedback');
        $this->filter_for_feedback($filter);

        return $this->db->order_by('state','asc')->order_by('add_time','desc')->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
    }

    /* 获取问题反馈记录总数  */
    public function get_feedback_total($filter) {
        $this->db->from('mall_feedback');
        $this->filter_for_feedback($filter);

        return $this->db->get()->num_rows();
    }

    /* 问题反馈查询条件  */
    public function filter_for_feedback($filter){
        foreach ($filter as $k => $v) {
            if (!$v || $k=='page') {
                continue;
            }
            switch ($k) {

                case 'keywords':
                    $v=trim($v);
                    $this->db->where("(email like '%$v%' or user_id like '%$v%')", null, false);
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

    /* 改变问题反馈的状态 */
    function chang_feedback_state($id) {
        return $this->db->where('feed_id',$id)->update('mall_feedback',array('state'=>1));
    }
}