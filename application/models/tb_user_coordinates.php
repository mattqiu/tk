<?php
/**
 * 138矩阵表类
 */
class tb_user_coordinates extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查看是否进入矩阵
     * @param $uid
     */
    public function is_exist_138($uid){
        $res = $this->db->select('*')->where('user_id',$uid)->from('user_coordinates')->get()->row_array();
        return $res;
    }

    /**
     *根据uid查询矩阵表
    */
    public function getYByUid($uid){
        $res_y = $this->db->query("select y from user_coordinates where user_id=$uid")->row_array();
        return $res_y?$res_y['y']:0;
    }

    /**
     * 手机端138矩阵信息
     * @param $uid
     * @return mixed
     */
    public function get_forced_matrix_138($uid){

        $data = array('my_info' => array(), 'child_info'=>array());

        $res = $this->db->query("select id,name,user_rank,sale_rank from users where id = $uid")->row_array();

        //查找138人数
        $info = $this->db->select("x,y")->where('user_id',$uid)->from('user_coordinates')->get()->row_array();

        $this->db->from('user_coordinates');
        $this->db->where('x',$info['x'])->where('y >',($info['y']));
        $count = $this->db->count_all_results();
        $res['child_count'] = $count;
        $data['my_info'][] = $res;

        $child = $this->db->query("select * from user_coordinates where x = {$info['x']} and y > {$info['y']} limit 0,4")->result_array();
        foreach($child as $item){
            $node = $this->db->query("select id,name,user_rank,sale_rank from users where id = {$item['user_id']}")->row_array();
            $data['child_info'][] = $node;
        }
        return $data;
    }

    /**
     * 手机端138矩阵信息
     * @param $res
     * @return array
     */
    public function get_child_forced_matrix_138($res){
        $child = array();

        if($res['left_id'] != null){
            $left_id = $res['left_id'];
            $left = $this->db->query("select us.child_count,u.id,u.name,u.user_rank,u.sale_rank from user_coordinates as us,users as u where us.user_id = u.id and us.user_id = $left_id")->row_array();
            array_push($child,$left);
        }

        if($res['right_id'] != null){
            $right_id = $res['right_id'];
            $right = $this->db->query("select us.child_count,u.id,u.name,u.user_rank,u.sale_rank from user_coordinates as us,users as u where us.user_id = u.id and us.user_id = $right_id")->row_array();
            array_push($child,$right);
        }
        return $child;
    }

}
