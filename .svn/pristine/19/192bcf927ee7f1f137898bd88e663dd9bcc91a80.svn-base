<?php
/**
 * 佣金管理记录
 * Ckf
 * 20161017
 */
class tb_admin_manage_commission_logs extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查询用户收发邮件例外记录
     * @return boolean
     * @author Ckf
     */
    public function selectAll($filter,$perPage = 10) {
        $this->db->from('admin_manage_commission_logs');
        $this->filterForNews($filter);
        if(isset($filter['admin_id'])){
            $this->db->where('admin_id',$filter['admin_id']);
        }
        $list = $this->db->select('')->limit($perPage, ($filter['page'] - 1) * $perPage)->order_by('create_time desc')->get()->result_array();
        return $list;
    }

    /**
     * 获取分页总数
     */
    function  getExceptionRows($filter){
        $this->db->from('admin_manage_commission_logs');
        $this->filterForNews($filter);
        if(isset($filter['admin_id'])){
            $this->db->where('admin_id',$filter['admin_id']);
        }
        $row = $this->db->count_all_results();
        return $row;
    }

    public function filterForNews($filter){
        foreach ($filter as $k => $v) {
            if (!$v || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', strtotime($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', strtotime($v));
                    break;
                case 'uid':
                    if(is_numeric($v)){
                        $this->db->where('uid', $v);
                    }else{
                        $this->db->where('uid', $v);
                    }
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }
}
