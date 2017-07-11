<?php
/**
 * @author mercury
 */
class tb_bulletin_unread extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 创建缓存数据
     * @param $key  键名
     * @param $val  键值
     * @return boolean
     * @author Terry
     */
    public function add_bulletin($uid,$adid){

        $swrter_str = "INSERT INTO bulletin_unread SET uid='".$uid."', bulletin_id='".$adid."'";
		$this->db->query($swrter_str);
    }
    
}
