<?php

/**
 * Created by PhpStorm.
 * User: brady
 * Date: 2016/12/28
 * Time: 14:41
 */
class m_bulletin_read extends MY_Model
{
    protected $table = "bulletin_read";
    protected $table_name = "bulletin_read";

    public function __construct()
    {
        parent::__construct();
    }
    
    /*查询此表是否已经存在一条记录 存在为真 否则为假
     * 此表uid bulletin_id 为联合主键不能重复插入
     */ 
    public function getOne($arr) {
        
        $query = $this->db->get_where('bulletin_read', $arr, 1);
        if($query->num_rows>0) {
            return true;
        } else {
            return false;
        }
    }
   
}