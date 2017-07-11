<?php

class m_function_lib extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * 查找字段是否存在
     * @param $column_name 字段名
     * @param $table_name   表名
     * @return bool
     */
    public function column_exits($column_name,$table_name){
        $sql="show columns from $table_name";
        $column_list=array();
        $result=$this->db->query($sql)->result();
        foreach($result as $value){
            $column=$value->Field;
            array_push($column_list,$column);
        }
        if(in_array($column_name,$column_list)){
            return true;
        }else{
            return false;
        }
    }



}
