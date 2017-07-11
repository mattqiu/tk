<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class forced_matrix_138 extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('forced_matrix');
        $this->load->model('m_forced_matrix');
        $this->load->model('m_overrides');
        $this->load->model('m_helper');
    }

    public function index() {

        $this->_viewData['title'] = lang('forced_matrix_138');

        //每次显示数量
        $show_num = 50 * MAX_X;

        $sql = "";
        $sql .= "select uc.*,uc.month_fee_rank as join_month_fee_rank,u.status,u.user_rank,u.user_rank";
        $sql .= " from user_coordinates uc,users u";
        $sql .= " where u.id = user_id order by id asc limit 0,$show_num";

        $data = $this->db->query($sql)->result_array();

        //创建138矩阵html代码
        $html = $this->m_forced_matrix->create_html_for_138($data);

        $this->_viewData['html'] = $html;

        parent::index('admin/');
    }


    //加载更多
    public function load_more(){
        $pager = intval($this->input->post('pager'));

        //每次显示数量
        $show_num = 50 * MAX_X;

        //总页数
        $total_num = $this->db->query("select count(*) as total_num from user_coordinates")->row()->total_num;
        $total_page = ceil($total_num / $show_num);

        $data = $pager * $show_num;

        $sql = "";
        $sql .= "select uc.*,uc.month_fee_rank as join_month_fee_rank,u.status,u.user_rank,u.user_rank";
        $sql .= " from user_coordinates uc,users u";
        $sql .= " where u.id = user_id order by id asc limit $data,$show_num";

        $data = $this->db->query($sql)->result_array();

        //创建138矩阵html代码
        $html = $this->m_forced_matrix->create_html_for_138($data);

        if($pager < $total_page)
        {
            echo json_encode(array("success"=>true,"code"=>0,"str"=>$html));
        }
        else
        {
            echo json_encode(array("success"=>true,"code"=>101,"str"=>$html,"msg"=>lang("load_finish")));
        }
        exit;
    }

    //查找138用户
    public function find_user(){
        $user_id = $this->input->post('user_id');
        if(strlen($user_id) != 10){
            echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
            exit();
        }

        $res = $this->db->query("select * from user_coordinates where user_id = {$user_id}")->row_array();
        if(empty($res)){
            echo json_encode(array('success'=>false,'msg'=>lang('this_user_not_sort')));
            exit();
        }

        $children = $this->db->query("select * from user_coordinates where x = {$res['x']} and y > {$res['y']}")->result_array();

        $msg = '';
        $msg .= "ID:".$res['user_id'];
        $msg .= "－－－";
        $msg .= lang("the_number_of_matrix").count($children);
        echo json_encode(array('success'=>true, 'msg'=>$msg,'y'=>$res['y']));

    }

}

?>