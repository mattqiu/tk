<?php
/**
 * 138发奖临时数据表（合格用户、用户在矩阵的下线数）
 * @author Terry
 */
class tb_138_grant_tmp extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*分页获取今天发奖的日分红人员*/
    public function getToday138UserListByPage($pageSize){

        return $this->db->query("select * from 138_grant_tmp limit $pageSize")->result_array();
    }

    /*按uid删除记录*/
    public function deleteByUid($uid){

        $this->db->query("delete from 138_grant_tmp where uid=".$uid);
    }


}
