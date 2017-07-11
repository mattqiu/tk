<?php
/**
 * mall_goods_category 功能类
 * @author ticowong
 */
class o_mall_goods_category extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /* 获取父类下所有底层子类id string 不含本身 */
    function get_children($cat_arr,$cat_id){
        $cat_id_string = '';
        foreach($cat_arr as $kk => $vv){
            if($vv['parent_id'] == $cat_id){
                $cat_id_string .= $vv['cate_id'].','.$this->get_children($cat_arr,$vv['cate_id']);
            }
        }
        return $cat_id_string;
    }

    /* 获取当前id的父级面包削导航  */
    public function get_nav_title($cate_list,$cate_id){
        static $nav_title='';

        if($cate_id !=0){
            foreach($cate_list as $keys =>$row){
                $isparent = false;
                if($row['cate_id']==$cate_id){
                    if($row['parent_id']==0)$isparent = true;
                    $thisurl =  base_url().'index/category?sn='.$row['cate_sn'];
                    $nav_title = " &gt; <a href=\"$thisurl\" title=\"$row[cate_name]\" rel=\"nofollow\">".$row["cate_name"]."</a>".$nav_title;
                    if($row["parent_id"]!=0)$this->get_nav_title($cate_list,$row["parent_id"]);
                }
            }
        }

        return $nav_title;
    }
}
