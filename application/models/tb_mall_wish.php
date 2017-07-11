<?php
/** 
 *　商品关注相关
 * @date: 2016-6-27 
 * @author: sky yuan
 */
class tb_mall_wish extends MY_Model {

    protected $table_name = "mall_wish";
    protected $table = 'mall_wish';
    function __construct() {
        parent::__construct();
    }
    
    /** 
     * 检测用户是否关注了商品
     * @date: 2016-6-27 
     * @author: sky yuan
     * @parameter: 
     * @return: 
     */ 
    function check_user_is_attention_goods($user_id,$goods_id) {
        $res = FALSE;
        
        if($this->db->where('user_id',$user_id)->where('goods_id',$goods_id)->get('mall_wish')->row_array()) {
            $res = TRUE;
        }
        
        return $res;
    }

    /* 添加收藏 */
    function add_wish($goods_id,$user_id,$goods_sn) {
        $rs = $this->get_one("*",["user_id"=>$user_id,"goods_id"=>$goods_id]);
        $status=false;
        if(empty($rs)) {
            $status = $this->insert_one(
                ['user_id'=>$user_id,'goods_id'=>$goods_id,'add_time'=>time(),'goods_sn_main'=>$goods_sn]);
        }
        return $status;
    }

    /**
     * 批量添加到关注
     * @param $user_id
     * @param $goods_sns，逗号分隔的goods_sn_main的字符串
     */
    function add_wish_batch($user_id,$goods_sns)
    {
        $arr_goods_sn = explode(",",$goods_sns);
        foreach($arr_goods_sn as $v)
        {
            $arr_goods_snm[] = preg_replace("/-\\d+$/","",$v);
        }
        //一次性取出goods_sn_main对应的goods_id数据
        $goods_list = $this->db->select(["goods_id","goods_sn_main"])
            ->where_in("goods_sn_main",$arr_goods_snm)->get("mall_goods_main")->result_array();

        //一次性取出用户现有的关注列表，暂时不限制添加关注的数量
        $wish_list = $this->get_list("goods_id",["user_id"=>$user_id]);
        foreach($wish_list as $k=>$v)
        {
            $wish_goods_list[] = $v['goods_id'];
        }
        //遍历传入的参数goods_sn_main
        foreach($arr_goods_snm as $goods_snm)
        {
            //取goods_sn_main对应的goods_id
            foreach($goods_list as $k=>$row)
            {
                if($goods_snm == $row['goods_sn_main'])
                {
                    //如果goods_sn_main不在关注列表中
                    if(!in_array($row['goods_id'],$wish_goods_list)){
                        $data[] = ['user_id'=>$user_id,'goods_id'=>$row['goods_id'],
                            'add_time'=>time(),'goods_sn_main'=>$goods_snm];
                    }
                }
            }
        }
        //批量添加到关注列表
        if(0 < count($data)){
            $this->insert_batch($data);
        }

    }

}