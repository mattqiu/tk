<?php
/**
 * @author jason
 */
class tb_user_sort_2x5 extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查看是否进入矩阵
     * @param $uid
     */
    public function is_exist_2x5($uid){
        $res = $this->db->select('*')->where('user_id',$uid)->from('user_sort_2x5')->get()->row_array();
        return $res;
    }

    /**
     * 手机端2x5矩阵信息
     * @param $uid
     * @return mixed
     */
    public function get_forced_matrix_2x5($uid){

        $data = array('my_info' => array(), 'child_info'=>array());
        $res = $this->db->query("select id,name,user_rank,sale_rank from users where id = $uid")->row_array();

        $info = $this->db->query("select * from user_sort_2x5 WHERE user_id = {$res['id']}")->row_array();
        if(!empty($info)){
            $res['child_count'] = $info['child_count'];
            $data['my_info'][] = $res;

            if($info['left_id'] != null){
                $left_id = $info['left_id'];
                $left = $this->db->query("select id,name,user_rank,sale_rank from users where id = {$left_id}")->row_array();
                $data['child_info'][] = $left;
            }
            if($info['right_id'] != null){
                $right_id = $info['right_id'];
                $right = $this->db->query("select id,name,user_rank,sale_rank from users where id = {$right_id}")->row_array();
                $data['child_info'][] = $right;
            }
        }
        return $data;
    }
}
