<?php
/**
 * 店铺类。
 */
class m_debug extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function log($content){
        $this->db->insert('debug_logs', array(
            'content' => var_export($content, 1),
        ));
    }
    //2017.1.3调试记录表
    public function dd_log($content = '',$content1 = '',$content2 = '',$content3 = '',$content4 = ''){
        $this->db->insert('debug_rx', array(
            'content' => var_export($content, 1),
            'content1' => $content1,
            'content2' => $content2,
            'content3' => $content3,
            'content4' => $content4,
        ));
    }
    
    public function getLogs(){
        return $this->db->query('select * from debug_logs order by id desc limit 1000')->result_array();
    }
    
    public function clearAllLog(){
        $this->db->query('delete from debug_logs');
    }

}
