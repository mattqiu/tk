<?php
/**
 * @author Ckf
 */
class tb_user_qualified_for_138 extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查询用户是否已存在该表
     */
    public function getOne($uid){
        return $this->db->from('user_qualified_for_138')->where('user_id',$uid)->get()->row_array();
    }

    /*获取今天发放138的总人数 By Terry*/
    public function get138SharNum(){

    	return $this->db->query("select count(*) totalNum from user_qualified_for_138")->row_object()->totalNum;
    }

    public function addToList($uid,$x,$y){

        $this->db->query("insert ignore into user_qualified_for_138(user_id,x,y) values($uid,".$x.",".$y.")");
    }

    public function getLikeUid($y,$uid){
        $y = rand($y-1100,$y-1000);
        $y = ($y>10)?$y:10;
        $res_like_user = $this->db->query("select user_id from user_qualified_for_138 where y=".$y." and user_id<>$uid order by RAND() limit 1")->row_array();
        return $res_like_user?$res_like_user['user_id']:0;
    }

    public function deleteOneUser($uid){

        $this->db->query("delete from user_qualified_for_138 where user_id=".$uid);
    }
}
