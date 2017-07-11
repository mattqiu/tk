<?php
/**
 * 扣款月費失敗郵件通知例外
 */
class tb_user_email_exception_list extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	/**
	 * 會員是否是郵件通知例外 by john
	 */
	public function is_email_exception($uid){
		return $this->db->from('user_email_exception_list')->where('uid',$uid)->count_all_results();
	}
    
    /**
     * 查询用户收发邮件例外记录
     * @return boolean
     * @author Terry
     */
    public function selectAll($filter,$perPage = 10) {
        $this->db->from('user_email_exception_list');
        $this->filterForNews($filter);
//        $lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
//		$this->db->where('language_id',$lang_id);
        $list = $this->db->select('')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        foreach($list as $k=>$v){
            if(empty($v['number_english'])){
                $list[$k]['number_english'] = lang('number_null');
            }
            if(empty($v['number_zh'])){
                $list[$k]['number_zh'] = lang('number_null');
            }
            if(empty($v['number_hk'])){
                $list[$k]['number_hk'] = lang('number_null');
            }
            if(empty($v['number_kr'])){
                $list[$k]['number_kr'] = lang('number_null');
            }
        }
       return $list;
    }
    /**
     * 添加记录
     */
    public function add_ones($uid){
        $add = $this->db->insert('user_email_exception_list', array('uid' => $uid));
        return $add;
    }
    /**
     * 查看该id是否已经存在
     */
    public function findOne($uid){
            $this->db->from('user_email_exception_list');
            $one = $this->db->where('uid',$uid)->get()->row_array();
            return $one;
    }
    /**
     *删除一条记录
     */
    public function deldete($id){
        $res = $this->db->where('uid', $id)->delete('user_email_exception_list');
        return $res;
    }

    /**
     * 获取分页总数
     */
    function  getExceptionRows($filter){
        $this->db->from('user_email_exception_list');
        $this->filterForNews($filter);
        // $lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
        // $this->db->where('language_id',$lang_id);
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
