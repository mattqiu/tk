<?php
/**
 * User: John
 */
class tb_admin_blacklist extends MY_Model
{

    function __construct(){
        parent::__construct();
    }

    /**
     * 得到所有的黑名单列表
     */
    public function get_blacklist($filter, $perPage = 10) {
        $this->db->from('admin_blacklist');
        $this->filter_for_blacklist($filter);
        $res = $this->db->order_by("create_time", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        return $res;
    }

    /** 拼接查询条件
     * @param $filter
     */
    public function filter_for_blacklist($filter){
        foreach ($filter as $k => $v) {
            if ($v == '' || $k=='page') {
                continue;
            }
            switch ($k) {
                case 'start':
                    $this->db->where('create_time >=', ($v));
                    break;
                case 'end':
                    $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                    break;
                default:
                    $this->db->where($k, $v);
                    break;
            }
        }
    }

    /** 黑名单的总数
     * @param $filter
     * @return mixed
     */
    public function get_blacklist_rows($filter) {
        $this->db->from('admin_blacklist');
        $this->filter_for_blacklist($filter);
        return $this->db->count_all_results();
    }

    /**
     * 增加黑名单
     */
    public function add_blacklist($data){
        $this->db->insert('admin_blacklist',$data);
        return $this->db->insert_id();
    }

    public function get_blacklist_all() {
        $this->db->from('admin_blacklist');
        $res = $this->db->order_by("id", "asc")->select('content')->get()->result_array();
        $arr = [];
        if ($res) {
            foreach ($res as  $v) {
                $arr[] = trim($v['content']);
            }
        }
        unset($res);
        return $arr;
    }

}