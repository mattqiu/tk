<?php
/** 
 * 知识库的数据库访问层
 * @date: 2017-06-07
 * @author: tico.wong
 * @parameter: 
 * @return: 
 */ 
class tb_admin_knowledge extends MY_Model {

    protected $table_name = "admin_knowledge";
    function __construct() {
        parent::__construct();
    }

}