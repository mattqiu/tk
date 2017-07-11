<?php
/** 
 * 新闻类数据访问层
 * @date: 2016-5-17 
 * @author: sky yuan
 * @parameter: 
 * @return: 
 */ 
class tb_news extends MY_Model {

    protected $table_name = "news";
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取首页新闻列表5条最新
     * @date: 2016-5-17
     * @author: sky yuan
     * @parameter:
     * @return:
     */
    public function get_notice_list($lan_id,$type_id) {
         return $this->db->select('id,title')->where('language_id',$lan_id)->where('cate_id',$type_id)->where('display',1)->order_by('create_time','desc')->limit(5)->get('news')->result_array();
    }

}