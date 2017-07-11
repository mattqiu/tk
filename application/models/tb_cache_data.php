<?php
/**
 * @author Terry
 */
class tb_cache_data extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 获取缓存数据
     * @param $key
     * @return value
     * @author Terry
     */
    public function getCacheData($key){

        $res = $this->db->select('cache_val')->from('cache_data')->where('cache_key',$key)->get()->row_array();
        return $res?$res['cache_val']:'';
    }

    /**
     * 创建缓存数据
     * @param $key  键名
     * @param $val  键值
     * @return boolean
     * @author Terry
     */
    public function createCacheData($key,$val){

        $res = $this->db->replace('cache_data',array(
            'cache_key'=>$key,
            'cache_val'=>$val
        ));

        return $res?true:false;
    }
    
}
