<?php
/**
 * @author Terry
 */
class tb_mall_goods extends MY_Model {

    private $goods_number_key_pre = "mall_goods:goods_number:";
    private $goods_number_opera_tag = ":opera";
    protected $table_name = "mall_goods";
    protected $DEBUG = false;
    protected $redis_log = true;//是否开启库存变更日志

    function __construct() {
        parent::__construct();
    }

    public function get_number($id = 0) {
        $id = intval($id);
        if (!$id) {
            return 0;
        }
        $num = $this->redis_get($this->goods_number_key_pre.$id);
        if (!$num) {
            $goods_one = $this->db->select('goods_number')->where('product_id', $id)->get('mall_goods')->row_array();
            $num = intval($goods_one['goods_number']);
        }
        return (int)$num;
    }

    /**
     * @desc 库存变动日志记录到redis
     * @auth tico.wong
     * @param $product_id
     * @param $msg
     */
    public function mall_goods_redis_log($product_id,$msg)
    {
        if(!$this->redis_log)
        {
            return;
        }
        $redis_log_key = "log:goods_number:".$product_id.":".date("Ymd");
        $this->redis_lPush($redis_log_key,date("Y-m-d H:i:s").":".$msg);
        if($this->redis_ttl($redis_log_key) === -1)
        {
            $this->redis_setTimeout($redis_log_key,3600*24*3);
        }
    }


    /**
     * 保存库存日志到数据库
     */
    public function save_mall_goods_redis_log()
    {
        $redis_log_key = "log:goods_number:*";
        $redis_keys = $this->redis_keys($redis_log_key);
        if($redis_keys)
        {
            sort($redis_keys);
            $this->load->model("tb_mall_goods_goods_number_logs");
            foreach($redis_keys as $key)
            {
                if($key)
                {
//                    echo("key:".$key."\n");ob_flush();
                    preg_match('/log:goods_number:(\d+):(\d+)/i',$key,$matchs);
                    if(isset($matchs[1]))
                    {
                        $product_id = $matchs[1];
                    }
                    if(isset($matchs[2]))
                    {
                        $date = $matchs[2];
                    }
//                    echo("product_id:".$product_id."\n");ob_flush();
//                    echo("opera_time:".$date."\n");ob_flush();
                    if(!$product_id || !$date)
                    {
                        continue;
                    }
                    if($date >= date('Ymd'))
                    {
                        continue;
                    }
                    while($d = $this->redis_rPop($key))
                    {
                        echo("rPop $key:".$d."\n");ob_flush();
                        preg_match('/^(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})/',$d,$matchs);
                        if(isset($matchs[1]))
                        {
                            $time = date('Y-m-d H:i:s',strtotime($matchs[1]));
                        }else{
                            $time = date('Y-m-d H:i:s');
                        }
                        $data['product_id'] = $product_id;
                        $data['opera_time'] = $time;
                        $data['log'] = $d;
                        $data['created_time'] = date("Y-m-d H:i:s");
                        $this->tb_mall_goods_goods_number_logs->insert_one($data);
                    }
                    $llen = $this->redis_lLen($key);
                    if(!$llen)
                    {
                        $this->redis_del($key);
                    }
                }
            }
        }
    }


    /**
     * 查询一个sku的商品库存
     * @return boolean
     * @author Terry
     */
    public function getOneGoods($sku) {
        //$this->db->from('mall_goods g');
		//$this->db->join('mall_goods_main m','g.goods_sn_main=m.goods_sn_main','left');
        //$lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
		//$this->db->where('m.language_id',$lang_id);
        //$lan = m.".language_id";
        //$list = $this->db->select('g.goods_sn,g.goods_sn_main,g.language_id,g.goods_number,m.goods_name')->where('goods_sn',$sku)->where('g.language_id',"$lan")->get()->result_array();
        $query = $this->db_slave->query("SELECT `g`.`product_id`,`g`.`goods_sn`, `g`.`goods_sn_main`, `g`.`language_id`, `g`.`goods_number`, `m`.`goods_name` FROM (`mall_goods` g) LEFT JOIN `mall_goods_main` m ON `g`.`goods_sn_main`=`m`.`goods_sn_main` WHERE `goods_sn` = '$sku' AND `g`.`language_id` = m.language_id");
        $list=$query->result_array();
        $list = $this->replace_mall_goods_list_goods_number($list);
//        echo $this->db->last_query();exit;
       return $list;
    }

    /* 获取商品价格 */
    public function get_goods_price($goods_sn){
        if(empty($goods_sn)){
            exit;
        }

        $day = date("Y-m-d H:i:s");
        $cur_language_id = get_cookie('curLan_id', true);

        //子商品表数据
//        $goods_info = $this->db->select("goods_sn_main,price")
//            ->where("goods_sn", $goods_sn)->where("language_id", $cur_language_id)
//            ->get("mall_goods")->row_array();
        $this->load->model("tb_mall_goods");
        $goods_info = $this->tb_mall_goods->get_one("goods_sn_main,price",
            ["goods_sn"=>$goods_sn,"language_id"=>$cur_language_id]);
        if(empty($goods_info)){
            exit;
        }

        //主商品表数据
//        $goods_main_info = $this->db->select("shop_price,is_promote")
//            ->where("goods_sn_main", $goods_info['goods_sn_main'])
//            ->where("language_id", $cur_language_id)
//            ->get("mall_goods_main")->row_array();
        $this->load->model("tb_mall_goods_main");
        $goods_main_info = $this->tb_mall_goods_main->get_one("shop_price,is_promote",
            ["goods_sn_main"=>$goods_info['goods_sn_main'],"language_id"=>$cur_language_id]);

        if(empty($goods_main_info)){
            exit;
        }

        /** 商品促销期 */
        if ($goods_main_info['is_promote'] == 1){
            $promote = $this->db->select('promote_price')->where('goods_sn',$goods_sn)->where('start_time <=',$day)->where('end_time >=',$day)->limit(1)->get('mall_goods_promote')->row_array();
            if(!empty($promote)){
                return $promote['promote_price']/100;
            }
        }

        return $goods_info['price'];
    }

    /* 获取商品库存 */
    public function get_goods_number($goods_sn,$area){
        $res = $this->db->query("select default_language from mall_goods_sale_country where country_id = $area")->row_array();
        if(empty($res)){
            return array();
        }

//        $goods_number_arr = $this->db->query("select goods_number,is_lock from mall_goods where goods_sn = '$goods_sn'")->row_array();
        $goods_number_arr = $this->get_one("goods_number,is_lock",["goods_sn"=>$goods_sn]);
        if(empty($goods_number_arr)){
           return array();
        }

        return $goods_number_arr;
    }

    ///////////////////////////////////////覆盖REDIS的操作//////////////////////////////////////////////
    /**
     * get_one前的回调函数
     * @param $defined_vars
     * @return bool|mixed|string
     */
    public function before_get_one(&$defined_vars)
    {
        if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_one".",".__FILE__.",".__LINE__."\n",8);
        $this->redis_cache_log("call before_get_one:",$defined_vars);
        $this->_force_select_all($defined_vars);
        $redis_key = $this->get_redis_key($defined_vars);
        extract($defined_vars);
        if($redis_key)
        {
            //尝试从redis获取数据
            $data = $this->_read_redis($redis_key);
            if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_one".",".__FILE__.",".__LINE__."\n",8);
            if($data)
            {
                if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_one".$data['product_id'].",".__FILE__.",".__LINE__."\n",8);
                //如果数据里product_id存在
                if(!empty($data['product_id']))
                {
                    if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_one".",".__FILE__.",".__LINE__."\n",8);
                    //如果缓存里存在单独的库存，用缓存里的库存替换他
                    $goods_number = $this->redis_get($this->goods_number_key_pre.$data['product_id']);
                    if($this->DEBUG)@file_put_contents("/tmp/store.log","goods_number:".$goods_number.",".__FILE__.",".__LINE__."\n",8);
                    if($goods_number !== false and $goods_number !== null)
                    {
                        if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_one".",".__FILE__.",".__LINE__."\n",8);
                        $data['goods_number'] = $goods_number;
                    }else{
                        //如果缓存里不存在独立库存，读取了库存值，那写到独立库存里去
                        if($data['goods_number'] === false and $goods_number !== null)
                        {
                            if($this->DEBUG)@file_put_contents("/tmp/store.log","goods_number:".$goods_number.",".__FILE__.",".__LINE__."\n",8);
                            if(is_numeric($data['goods_number']))
                            {
                                $this->redis_set($this->goods_number_key_pre.$data['product_id'],$data['goods_number']);
                            }
                        }
                    }
                }
                $this->redis_cache_log("call before_get_one,return from cache->{$redis_key}\n");
                if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_one".",".__FILE__.",".__LINE__."\n",8);
                if($this->DEBUG)@file_put_contents("/tmp/store.log",var_export($data,true).",".__FILE__.",".__LINE__."\n",8);
                return $data;
            }
        }
        $this->redis_cache_log("call before_get_one cache not found->{$redis_key},continue get one from db.");
        if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_one".",".__FILE__.",".__LINE__."\n",8);
        return false;
    }

    /**
     * get_one后的回调函数
     * @param null $data
     * @param $defined_vars
     *
     */
    function after_get_one(&$data=null,$defined_vars=null)
    {
        if($this->DEBUG)@file_put_contents("/tmp/store.log","after_get_one".",".__FILE__.",".__LINE__."\n",8);
        if(!$defined_vars)
        {
            return;
        }
        $config = $this->get_config();
        if(!$config)
        {
            return;
        }
        $redis_key = $this->get_redis_key($defined_vars);
        if($redis_key) {
            if($data)
            {
                //如果产品ID不为空
                if(!empty($data['product_id']))
                {
                    //如果读取了库存
                    if(!empty($data['goods_number']))
                    {
                        //如果缓存里不存在单独的库存
                        $goods_number = $this->redis_get($this->goods_number_key_pre.$data['product_id']);
                        if($goods_number === false)
                        {
                            if($this->DEBUG)@file_put_contents("/tmp/store.log","after_get_one".",".__FILE__.",".__LINE__."\n",8);
                            //写入到独立库存里
                            if(is_numeric($data['goods_number'])) {
                                $this->redis_set($this->goods_number_key_pre . $data['product_id'], $data['goods_number']);
                            }
                        }else{
                            if($this->DEBUG)@file_put_contents("/tmp/store.log","after_get_one".",".__FILE__.",".__LINE__."\n",8);
                            if($goods_number !== null){
                                $data['goods_number'] = $goods_number;
                            }
                        }
                    }
                }
                $this->_write_redis($redis_key,$data,$config['ttl']);
                $this->redis_cache_log("call after_get_one:write cache into redis->{$redis_key}.\n");
            }
        }
        if($this->DEBUG)@file_put_contents("/tmp/store.log","after_get_one".",".__FILE__.",".__LINE__."\n",8);
    }

    /**
     * get_list前的回调函数
     * @param $defined_vars
     * @return array|bool|mixed|string
     */
    function before_get_list(&$defined_vars)
    {
        if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_list:".",".__FILE__.",".__LINE__."\n",8);
        $this->redis_cache_log("call before_get_list:",$defined_vars);
        $this->_force_select_all($defined_vars);
        $redis_key = $this->get_redis_key($defined_vars);
        extract($defined_vars);
        if($redis_key) {
            $data = $this->_read_redis($redis_key);
            if($data)
            {
                $data = $this->replace_mall_goods_list_goods_number($data);
                if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_list:".var_export($data,true).",".__FILE__.",".__LINE__."\n",8);
                $this->redis_cache_log("call before_get_list return from cache:{$redis_key}\n");
                return $data;
            }
        }
        $this->redis_cache_log("call before_get_list cache not found->{$redis_key},continue get list from db.");
        if($this->DEBUG)@file_put_contents("/tmp/store.log","before_get_list:".",".__FILE__.",".__LINE__."\n",8);
        return false;
    }
    /**
     * 使用独立库存替换列表里的goods_number
     * @param $data
     **/
    function replace_mall_goods_list_goods_number($data)
    {
        if($this->DEBUG){var_dump($data);echo(__FILE__.",".__LINE__."<BR>");}
        foreach($data as $k=>$v)
        {
            if(is_array($v))
            {
                if($this->DEBUG){var_dump($v);echo(__FILE__.",".__LINE__."<BR>");}
                $data[$k] = $this->replace_mall_goods_goods_number($v);
                if($this->DEBUG){var_dump($data);echo(__FILE__.",".__LINE__."<BR>");}
            }
        }
        if($this->DEBUG){var_dump($data);echo(__FILE__.",".__LINE__."<BR>");}
        return $data;
    }

    /**
     * 使用独立库存替换列表里的goods_number
     * @param $data
     **/
    function replace_mall_goods_goods_number($data)
    {
        if(!empty($data['product_id']) and $data['goods_number'] !== null)
        {
            if($this->DEBUG)@file_put_contents("/tmp/store.log",var_export($data,true).",".__FILE__.",".__LINE__."\n",8);
            $goods_number = $this->redis_get($this->goods_number_key_pre.$data['product_id']);;
            if($goods_number !== false and $goods_number !== null)
            {
                if($this->DEBUG)@file_put_contents("/tmp/store.log",var_export($data,true).",".__FILE__.",".__LINE__."\n",8);
                $data['goods_number'] = intval($goods_number);
                if($this->DEBUG)@file_put_contents("/tmp/store.log",var_export($data,true).",".__FILE__.",".__LINE__."\n",8);
            }
        }
        return $data;
    }

    /**
     * get_list后的调用函数
     * @param null $data
     * @param $defined_vars
     * @return $data
     */
    function after_get_list(&$data=null,$defined_vars=null)
    {
        if($this->DEBUG)@file_put_contents("/tmp/store.log","after_get_list:".var_export($data,true).",".__FILE__.",".__LINE__."\n",8);
        $this->redis_cache_log("call after_get_list:",$defined_vars);
        $config = $this->get_config();
        if(!$config)
        {
            return;
        }
        $redis_key = $this->get_redis_key($defined_vars);
        if($redis_key) {
            if($data)
            {
                $data = $this->replace_mall_goods_list_goods_number($data);
                $this->redis_cache_log("call after_get_list:write data into redis->{$redis_key}.");
                $this->_write_redis($redis_key,$data,$config['ttl']);
            }
        }
        if($this->DEBUG)@file_put_contents("/tmp/store.log","after_get_list:".var_export($data,true).",".__FILE__.",".__LINE__."\n",8);
        return $data;
    }

    /**
     * 更新REDIS里的独立缓存
     * @param $product_id int 产品ID
     * @param $goods_number int 直接改变库存至本值,不传请传false或null
     * @param $goods_number_opera int 库存改变数，正负的整数
     * @return bool
     */
    function update_goods_number_in_redis($product_id,$goods_number,$goods_number_opera=0)
    {
        $tmp = $this->redis_get($this->goods_number_key_pre.$product_id);
//        $this->mall_goods_redis_log($product_id,"update_goods_number_in_redis:get:".$tmp);
        //如果缓存里没有独立库存
        if($tmp === false)
        {
            //如果传了独立库存数
            if($goods_number !== null)
            {
                if(is_numeric($goods_number)) {
                    $this->mall_goods_redis_log($product_id,$goods_number."->未到缓存的库存但更新redis里的库存");
                    $res = $this->redis_set($this->goods_number_key_pre . $product_id, "$goods_number");
                    $this->redis_incrBy($this->goods_number_key_pre . $product_id.$this->goods_number_opera_tag,1);
                    if(!$res)
                    {
                        $this->mall_goods_redis_log($product_id,$goods_number."->如果redis不起作用,更新数据库库存");
                        //如果redis不起作用,更新数据库库存
                        $this->update_one(["product_id"=>$product_id],['goods_number'=>$goods_number]);
                    }
                    return true;
                }
                $this->mall_goods_redis_log($product_id,$goods_number."->传入库存非数字");
                return true;
            }
        }
        else//如果缓存里有独立库存数
        {
            //如果传了独立库存数
            if($goods_number !== null)
            {
                //直接设置独立库存数
                if(is_numeric($goods_number)) {
                    $this->mall_goods_redis_log($product_id,$goods_number."->找到缓存里的库存并更新redis里的库存");
                    $res = $this->redis_set($this->goods_number_key_pre.$product_id,"$goods_number");
                    $this->redis_incrBy($this->goods_number_key_pre . $product_id.$this->goods_number_opera_tag,1);
                }
                if(!isset($res))
                {
                    $this->mall_goods_redis_log($product_id,$goods_number."->如果redis不起作用,更新数据库库存");
                    //如果redis不起作用,更新数据库库存
                        $this->update_one(["product_id" => $product_id], ['goods_number' => $goods_number]);
                }
                return true;
            }
            elseif($goods_number_opera)//如果传了库存变化数，则更新独立库存数
            {
                $this->mall_goods_redis_log($product_id,$goods_number."->如果传了库存变化数，则更新独立库存数");
                $this->redis_incrBy($this->goods_number_key_pre.$product_id,$goods_number_opera);
                //todo::如果redis不起作用,更新数据库库存
                return true;
            }
        }
        return false;
    }

//if($this->DEBUG)@file_put_contents("/tmp/store.log",'写到独立库存:'.$goods_number.",".__FILE__.",".__LINE__."\n",8);

//    /**
//     * update_one前调用的函数
//     * @param $defined_vars
//     */
//    function before_update_one($defined_vars)
//    {
//        $this->redis_cache_log("call before_update_one:",$defined_vars);
//        $this->remove_cache($defined_vars,__FUNCTION__);
//    }

//    /**
//     * update_one后调用的函数
//     * @param $defined_vars
//     */
//    function after_update_one($defined_vars)
//    {
//        $this->redis_cache_log("call before_update_one:",$defined_vars);
//        $this->remove_cache($defined_vars,__FUNCTION__);
//    }


    /**
     * 刷新所有redis库存到数据库
     */
    public function update_redis_goods_number_2_mysql_all()
    {
        $keys_arr = $this->redis_keys($this->goods_number_key_pre."*");
        foreach($keys_arr as $k=>$v)
        {
            $product_id = str_replace($this->goods_number_key_pre,"",$v);
            if($product_id){
                $goods_number = $this->redis_get($v);
                if($goods_number !== false){

                    $this->update_one(["product_id"=>$product_id],["goods_number"=>intval($goods_number)]);
                    echo("product_id:".$product_id."goods_number:".$goods_number."\n");
                }else{
                    echo("product_id:".$product_id."goods_number empty\n");
                }
                ob_flush();
            }
        }
    }

    /**
     * 刷新有改动，或全部redis库存到数据库
     * @param $b 不传或传0则只刷有改动的库存到数据库，否则全刷到库
     */
    public function update_redis_goods_number_2_mysql($b)
    {
        if($b)
        {
            return $this->update_redis_goods_number_2_mysql_all();
        }
        $keys_arr = $this->redis_keys($this->goods_number_key_pre."*".$this->goods_number_opera_tag);
        foreach($keys_arr as $k=>$v)
        {
            $product_id = str_replace($this->goods_number_key_pre,"",$v);
            $product_id = str_replace($this->goods_number_opera_tag,"",$product_id);
            $keys = str_replace($this->goods_number_opera_tag,"",$v);
            if($product_id){
                $goods_number = $this->redis_get($keys);
                if($goods_number !== false){
                    $this->update_one_auto(["where"=>["product_id"=>$product_id],
                        "data"=>["goods_number"=>intval($goods_number)],
                        "cache"=>0,
                    ]);
                    $this->redis_del($v);
//                    $this->db->where("product_id",$product_id)
//                        ->update('mall_goods',["goods_number"=>intval($goods_number)]);
                    echo("product_id:".$product_id.",   goods_number:".$goods_number."\n");
                }else{
                    echo("product_id:".$product_id.",   goods_number empty\n");
                }
                ob_flush();
            }
        }
    }
}
