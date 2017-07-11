<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH."core/Redis_Class.php");

/**
 * @author Tico.wong
 * REDIS缓存层，
 * 函数不返回数据则继续执行，
 * 若返回数据将不继续执行原来代码段，直接返回数据。
 * 如：befor_get_one，如果返回数据，那么get_one函数的后面代码将不执行。直接返回befor_get_one返回的数据
 */
class Redis_Cache extends Redis_Class
{
    protected  $table_name = "";

    function __construct() {
        $this->load->config("config_redis");
        parent::__construct();

        if(!defined("REDIS_CACHE_MAX_LEN_MD5"))
        {
            define(REDIS_CACHE_MAX_LEN_MD5,128);
        }
    }

    /**
     * 记录日志，传入1到n个参数，
     * 都将被顺序作为一行数据并记录到日志
     */
    protected function redis_cache_log()
    {
        $config = $this->get_config();
        if(!$config)
        {
            return;
        }
        if(!$config['log'])
        {
            return;
        }
        $defined_vars = func_get_args();
        $file = "/tmp/".$this->table_name."_redis_".date("Y_m_d",time()).".log";
        $msg = "";
        foreach($defined_vars as $k=>$v)
        {
            if(is_string($v))
            {
                $msg .= $v;
            }else{
                $msg .= preg_replace('/\n/','',var_export($v,1));
            }
        }
        if($msg){
            //echo(date("Y-m-d h:i:s")."-->".$msg."\n");ob_flush();
            @file_put_contents($file,date("Y-m-d h:i:s")."-->".$msg."\n",8);
        }
    }

    /**
     * 取表名方法，子类构造中调并用传入__CLASS__后进行赋值即可
     * 或是子类直接设置table_name后子类中无需调用本方法
     */
    public function get_table_name($cls_name="")
    {
        if(isset($this->table_name))
        {
            if(!empty($this->table_name)){
                return $this->table_name;
            }
        }
        if(empty($cls_name))
        {
            log_message("ERROR","model必须要设置表名:table_name");
            exit("model必须要设置表名:table_name");
        }
        $this->table_name =  preg_replace('/^tb_/i',"",$cls_name);
        return $this->table_name;
    }

    /**
     * 取use_db的配置
     * @return bool
     */
    public function get_use_db_config($func_name="")
    {
        $use_db = true;
        $config = $this->get_config();
        //监测是否有配置
        if($config)
        {
            //监测是否使用数据库
            if(!$config['use_db'])
            {
                $use_db = $config['use_db'];
                //如果不使用数据库，那么就最少需要配置一个key了
                if(empty($config['key']))
                {
                    //配置了不使用数据库，必须要配置key
                    log_message("ERROR","表{$this->table_name}配置了use_db=false，但未配置key,操作无法继续进行，现在仍然使用数据库");
                    $this->redis_cache_log("call {$func_name},use_db = false,but,no key setting,continue insert into mysql");
                    return true;
                }
                if($config['ttl'] > 0)
                {
                    if($config['ttl'] < 300)
                    {
                        log_message("DEBUG","表配置了use_db=false，但配置的超时时间太短，不到300秒");
                        $this->redis_cache_log("warning.call {$func_name},use_db = false,but,ttl too sort,less than 300 sec.");
                    }else if($config['ttl'] < 3600){
                        log_message("DEBUG","表配置了use_db=false，但配置的超时时间比较短，不到3600秒");
                        $this->redis_cache_log("warning.call {$func_name},use_db = false,but,ttl too sort,less than 3600 sec.");
                    }
                    $use_db = false;
                }
            }
        }
        return $use_db;
    }

    /**
     * 格式化REDIS使用的key
     * @param $val
     * @return mixed
     */
    public function format_redis_key($val)
    {
        if(is_array($val))
        {
            $tmp = "";
            foreach($val as $k=>$v)
            {
                if($tmp)
                {
                    $tmp .="_";
                }
                $tmp .= $this->format_redis_key($k)."_".$this->format_redis_key($v);
            }
            return $tmp;
        }
        //将空格字符去掉，否则redis无法支持
        $val = preg_replace('/\s/',"",$val);
        //将星号转为大写ALL
        $val = preg_replace('/\*/',"ALL",$val);
        //将逗转为大写下划线
        $val = preg_replace('/,/',"_",$val);
        return $val;
    }

    /**
     * 获取生成redis_key的数组
     * @param $arr
     * @return bool|string
     */
    public function get_redis_key_arr($arr)
    {
        $redis_key = [];
        //检测表名是否有设置
        if(!$this->table_name){
            return false;
        }
        //检测config_redis里是否配置了当前表使用缓存
        $redis_config = $this->get_config();
        if(empty($redis_config))
        {
            return false;
        }
        if(empty($arr))
        {
            return false;
        }
        //遍历传入的参数
        foreach($arr as $k=>$v)
        {
            //如果是数组
            if(is_array($v)) {
                foreach ($v as $kw => $vw)
                {
                    //判断是否配置了key
                    if (!empty($redis_config['key']))
                    {
                        if (in_array($kw, $redis_config['key']))
                        {
                            $kw = $this->format_redis_key($kw);
                            $vw = $this->format_redis_key($vw);
                            $redis_key[]= [$kw=>$vw];
                        }
                    } else {
                        $kw = $this->format_redis_key($kw);
                        $vw = $this->format_redis_key($vw);
                        //未配置key，将所有条件作为key.
                        $redis_key[]= [$kw=>$vw];
                    }
                }
            }else if(is_string($v))
            {
                //判断是否配置了key
                if (!empty($redis_config['key']))
                {
                    if (in_array($k, $redis_config['key']))
                    {
                        $k = $this->format_redis_key($k);
                        $v = $this->format_redis_key($v);
                        $redis_key[]=[$k=>$v];
                    }
                } else {
                    //未配置key，将所有条件作为key.
                    $k = $this->format_redis_key($k);
                    $v = $this->format_redis_key($v);
                    $redis_key[]=[$k=>$v];
                }
            }else{
                //避免get_one,get_list混淆，非数组条件必作为键值区分
                $k = $this->format_redis_key($k);
                $v = $this->format_redis_key($v);
                $redis_key[]=[$k=>$v];
            }
        }
        return $redis_key;
    }

    /**
     * 根据配置取redis_key，需要区分的关键字都要传入.select,where,or_where,limit,index等
     * @param array $arr
     * @return mixed
     */
    public function get_redis_key($arr)
    {
        $redis_key = "";
        $redis_key_arr = $this->get_redis_key_arr($arr);
        if(!$redis_key_arr)
        {
            return $redis_key;
        }
        if(!is_array($redis_key_arr))
        {
            return $redis_key;
        }
        foreach($redis_key_arr as $k=>$v)
        {
            foreach($v as $k1=>$v1){
                if(is_array($v1)){
                    $redis_key .= ":".$k1.":".implode("_",$v1);
                }else{
                    $redis_key .= ":".$k1.":".$v1;
                }
            }
        }
        if(get_str_lenght($redis_key) > REDIS_CACHE_MAX_LEN_MD5)
        {
            $redis_key = ":".md5($redis_key);
        }
        if($redis_key)
        {
            $redis_key = $this->table_name.$redis_key;
        }
        return $redis_key;
    }

    /**
     * 写入redis
     * @param $redis_key redis的键值
     * @param mixed $data 写入redis的数据
     * @param int $ttl 缓存自动过期时间
     */
    protected function _write_redis($redis_key, $data, $ttl=0)
    {
        $data = serialize($data);
        //写入redis
        if($ttl)
        {
            $this->redis_cache_log("write redis->{$redis_key},with ttl:{$ttl}");
            $this->redis_set($redis_key,$data,$ttl);
        }else{
            $this->redis_cache_log("write redis->{$redis_key}");
            $this->redis_set($redis_key,$data);
        }
    }

    /**
     * 从redis读取
     * @param $redis_key
     * @return bool|mixed|string
     */
    protected function _read_redis($redis_key)
    {
        $data = $this->redis_get($redis_key);
        if($data)
        {
            $this->redis_cache_log("read redis->{$redis_key}");
            $data = unserialize($data);
            return $data;
        }
    }

    /**
     * 判断是否需要强制select*号,如果需要，将select替换成*号
     * @param $defined_vars
     */
    protected function _force_select_all(&$defined_vars)
    {
        $res = $this->get_config();
        if($res)
        {
            if(isset($res["force_select_all"]) and $res["force_select_all"])
            {
                foreach($defined_vars as $k=>$v)
                {
                    if($k == "select")
                    {
                        //select 条件里有as的支持
                        $select_as = [];
                        foreach(explode(",",$v) as $kv=>$vv)
                        {
                            $tmp = preg_match("/\\sas\\s/i",$vv);
                            if($tmp > 0)
                            {
                                $select_as[] = $vv;
                            }
                        }
                        $this->redis_cache_log("force_select_all option is effective");
                        if(!empty($select_as))
                        {
                            $defined_vars[$k] = "*".",".implode(",",$select_as);
                        }
                        else
                        {
                            $defined_vars[$k] = "*";
                        }
                    }
                }
            }
        }
    }

    /**
     * 将defined_vars转化成
     * 将调用MY_Model函数的参数转成数组用于判断REDIS_KEY
     * @param $defined_vars
     * @return array
     */
    function defined_vars_to_arr($defined_vars)
    {
        $res = [];
        foreach($defined_vars as $k=>$v)
        {
            if(is_array($v))
            {
                foreach($v as $kv=>$vv)
                {
                    $res[$kv][] = $vv;
                }
            }else{
                $res[$k][] = $v;
            }
        }
        return $res;
    }

    /**
     * 刷新缓存
     * @param $defined_vars
     * @return bool
     */
    function remove_cache($defined_vars,$log_msg="")
    {
        //先清理所有列表的缓存
        $this->remove_list_cache($defined_vars);
        //清理所有单数据缓存
        $this->remove_one_cache($defined_vars);
        return true;
    }

    /**
     * 根据defined_vars取需要删除的redis_key
     * @param $defined_vars
     * @param array $update_keys
     * @return array
     */
    function get_redis_keys_4_delete_by_definded_vars($defined_vars,$update_keys=[])
    {
        $redis_keys_4_delete = [];
        $where = [];
        $where_sql = [];
        $or_where_sql = [];
        //取where条件的字段
        if(!empty($defined_vars['where']))
        {
            $where[] = $defined_vars['where'];
            $where_sql = $defined_vars['where'];
//            var_dump($where_sql);echo(__FILE__.",".__LINE__."<BR>");
        }
        //取or_where条件的字段
        if(!empty($defined_vars['or_where']))
        {
            $where[] = $defined_vars['or_where'];
            $or_where_sql =$defined_vars['or_where'];
        }
        //取where的key值
        $where_keys = [];
        foreach($where as $kw=>$vw)
        {
            foreach($vw as $k=>$v)
            {
                $where_keys[] = $k;
            }
        }
        //遍历条件
        foreach($where as $kw=>$vw)
        {
            //遍历update_keys
            foreach($update_keys as $kk=>$vk)
            {
                if(is_string($vk))
                {
                    if(in_array($vk,$where_keys))
                    {
                        if(!empty($vw) and !empty($vw[$vk]))
                        {
                            $tmp = $this->table_name.":*:".$vk.":".$vw[$vk].":*";
                            if(!in_array($tmp,$redis_keys_4_delete))$redis_keys_4_delete[] =$tmp;
                            $tmp = $this->table_name.":*:".$vk.":".$vw[$vk];
                            if(!in_array($tmp,$redis_keys_4_delete))$redis_keys_4_delete[] =$tmp;
                        }
                    }
                }
                if(is_array($vk))
                {
                    //todo::多条件的update_keys
                }
            }
        }
        //根据配置的update_keys取数据库记录,合成完整的待更新keys组合
        $this->db->select(implode(",",$update_keys))->from($this->table_name);
        if(!empty($where_sql))
        {
            foreach($where_sql as $k=>$v)
            {
                if(is_string($v))
                {
                    $this->db->where($k,$v);
                }
                if(is_array($v))
                {
                    $this->db->where_in($k,$v);
                }
            }
        }
        if(!empty($or_where_sql))
        {
            foreach($or_where_sql as $k=>$v)
            {
                if(is_string($v))
                {
                    $this->db->or_where($k,$v);
                }
            }
        }
        $data = $this->db->limit(1)->get();
        if($data)
        {
            $data = $data->row_array();
        }

        if(!empty($data))
        {
            foreach($data as $k=>$v)
            {
                if(in_array($k,$update_keys))
                {
                    $tmp = $this->table_name.":*:".$k.":".$v.":*";
                    if(!in_array($tmp,$redis_keys_4_delete))$redis_keys_4_delete[] =$tmp;
                    $tmp = $this->table_name.":*:".$k.":".$v;
                    if(!in_array($tmp,$redis_keys_4_delete))$redis_keys_4_delete[] =$tmp;
                }
            }
        }
        return $redis_keys_4_delete;
    }

    /**
     * 清理get_list的缓存
     */
    function remove_list_cache($defined_vars)
    {
        $config = $this->get_config();

        //如果设置了refresh_list为false，则不清理列表缓存
        if(isset($config['refresh_list']) and !$config['refresh_list'])
        {
            $this->redis_cache_log("remove_list_cache ingore by config...");
            return true;
        }

        $this->redis_cache_log("remove_list_cache begin...");

        //如果设置了update_list_keys，根据这个key快速清理list缓存
        $update_keys = empty($config['update_list_keys'])?false:$config['update_list_keys'];
        if($update_keys)
        {
            $redis_keys_4_delete = $this->get_redis_keys_4_delete_by_definded_vars($defined_vars,$update_keys);
            foreach($redis_keys_4_delete as $v)
            {
                $tmp = $this->redis_keys($v);
                foreach($tmp as $kv)
                {
                    if($kv){
                        $this->redis_cache_log("remove_list_cache by keys:",$kv);
                        $this->redis_del($kv);
                    }
                }
            }
            if(!empty($redis_keys_4_delete))
            {
                $this->redis_cache_log("remove_list_cache by key finished...");
                return true;
            }
        }
        //未设置update_list_keys，清理全部list缓存
        $arr = $this->redis_keys($this->get_table_name().":*:page_index:*");
        foreach($arr as $k=>$v)
        {
            $this->redis_cache_log("remove cache:",$v);
            $this->redis_del($v);
        }
        $this->redis_cache_log("remove_list_cache finished...");
        return true;
    }

    /**
     * 清理get_one的缓存
     * @param $defined_vars
     * @param $log_msg
     * @return bool
     */
    function remove_one_cache($defined_vars,$log_msg="")
    {
        //如果设置了update_key，根据这个key快速清理非list缓存
        $config = $this->get_config();

        //如果设置了refresh_list为false，则不清理列表缓存
        if(isset($config['refresh_list']) and !$config['refresh_list'])
        {
            $this->redis_cache_log("remove_one_cache ingore by config...");
            return true;
        }

        $this->redis_cache_log("remove_one_cache begin...");

        $update_keys = isset($config['update_one_keys'])?$config['update_one_keys']:[];
        if($update_keys)
        {
            $redis_keys_4_delete = $this->get_redis_keys_4_delete_by_definded_vars($defined_vars,$update_keys);

//            var_dump($defined_vars);var_dump($redis_keys_4_delete);//exit(__FILE__.",".__LINE__."\n");
            foreach($redis_keys_4_delete as $v)
            {
                $arr = $this->redis_keys($v);
                foreach($arr as $kv)
                {
                    if($kv and $kv != 'NULL'){
                        $this->redis_cache_log("remove_one_cache by keys:",$kv);
                        $this->redis_del($kv);
                    }
                }
            }

            if(count($redis_keys_4_delete))
            {
                $this->redis_cache_log("remove_one_cache by keys finished...");
                return true;
            }
        }

        //遍历并清理非list缓存,存在极大的性能下降问题，需要配置update_one_keys避免这种遍历
        $arr = $this->redis_keys($this->get_table_name().":*");
        $defined_vars_arr = $this->defined_vars_to_arr($defined_vars);
        $defined_vars_keys = array_keys($defined_vars_arr);
        foreach($arr as $k=>$v)
        {
            $tmp = $this->redis_get($v);
            if(!$tmp)continue;
            $tmp = unserialize($tmp);
            if(!$tmp)continue;
            if(is_array($tmp))
            {
                //检测缓存数据是否包含条件，如果包含则清理
                foreach($defined_vars_keys as $kk=>$vk)
                {
                    if(array_key_exists($vk,$tmp))
                    {
                        if(in_array($tmp[$vk],$defined_vars_arr[$vk]))
                        {
                            $this->redis_cache_log("remove cache:".$log_msg.":",$v);
                            $this->redis_del($v);
                        }
                    }
                }
            }
        }
        $this->redis_cache_log("remove_one_cache finished...");
        return true;
    }

    /**
     * get_one前的回调函数
     * @param $defined_vars
     * @return bool|mixed|string
     */
    function before_get_one(&$defined_vars)
    {
        $this->redis_cache_log("call before_get_one:",$defined_vars);
        $this->_force_select_all($defined_vars);
        $redis_key = $this->get_redis_key($defined_vars);
        extract($defined_vars);
        if($redis_key)
        {
            //尝试从redis获取数据
            $data = $this->_read_redis($redis_key);
            if($data)
            {
                $this->redis_cache_log("call before_get_one,return from cache->{$redis_key}\n");
                return $data;
            }
        }
        $this->redis_cache_log("call before_get_one cache not found->{$redis_key},continue get one from db.");
    }

    /**
     * get_one后的回调函数
     * @param null $data
     * @param $defined_vars
     *
     */
    function after_get_one(&$data=null,$defined_vars=null)
    {
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
                $this->_write_redis($redis_key,$data,$config['ttl']);
                $this->redis_cache_log("call after_get_one:write cache into redis->{$redis_key}.\n");
            }
        }
        if($data)
        {
            $this->primary_key_set($data);
        }
    }

    /**
     * get_list前的回调函数
     * @param $defined_vars
     * @return array|bool|mixed|string
     */
    function before_get_list(&$defined_vars)
    {
        $this->redis_cache_log("call before_get_list:",$defined_vars);
        $this->_force_select_all($defined_vars);
        $redis_key = $this->get_redis_key($defined_vars);
        extract($defined_vars);
        if($redis_key) {
            $data = $this->_read_redis($redis_key);
            if($data)
            {
                $this->redis_cache_log("call before_get_list return from cache:{$redis_key}\n");
                return $data;
            }
        }
        $this->redis_cache_log("call before_get_list cache not found->{$redis_key},continue get list from db.");
    }

    /**
     * get_list后的调用函数
     * @param null $data
     * @param $defined_vars
     */
    function after_get_list($data=null,$defined_vars=null)
    {
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
                $this->redis_cache_log("call after_get_list:write data into redis->{$redis_key}.");
                $this->_write_redis($redis_key,$data,$config['ttl']);
            }
        }
    }

    /**
     * update_one前调用的函数
     * @param $defined_vars
     */
    function before_update_one($defined_vars)
    {
        $this->redis_cache_log("call before_update_one:",$defined_vars);
        $this->remove_cache($defined_vars,__FUNCTION__);
    }

    function after_update_one($defined_vars)
    {
        $this->remove_cache($defined_vars,__FUNCTION__);
        $this->primary_key_refresh_by_where($defined_vars['where']);
    }

    function before_update_batch($defined_vars)
    {
        $this->remove_cache($defined_vars,__FUNCTION__);
    }

    function after_update_batch($defined_vars)
    {
        $this->primary_key_refresh_by_where($defined_vars['where']);
    }

    function before_insert_one($data)
    {
        $use_db = $this->get_use_db_config(__FUNCTION__);
        $this->redis_cache_log("call before_insert_one:",$data);
        $config = $this->get_config();
        //监测是否有配置，如果不使用数据库
        if(!$use_db)
        {
            //将数据直接插入缓存
            $redis_key = $this->get_redis_key($data);
            $tmp = $this->_read_redis($redis_key);
            $tmp[] = $data['data'];
            $this->_write_redis($redis_key,$tmp,$config['ttl']);
            $this->redis_cache_log("call before_insert_one,write redis->{$redis_key}");
            //如果配置不使用数据库，不继续插入数据到库
            $this->redis_cache_log("call before_insert_one,use_db = false, no use db and return.");
            return true;
        }
    }

    function after_insert_one($data)
    {
        $this->remove_list_cache(['where'=>$data]);
    }

    function before_insert_batch($data)
    {
        $use_db = $this->get_use_db_config(__FUNCTION__);
        $this->redis_cache_log("call before_insert_batch:",$data);
        $config = $this->get_config();
        //如果不使用数据库
        if(!$use_db)
        {
            foreach($data['data'] as $k=>$v)
            {
                //将数据直接插入缓存
                $redis_key = $this->get_redis_key([$v]);
                $tmp = $this->_read_redis($redis_key);
                $tmp[] = $v;
                $this->_write_redis($redis_key,$tmp,$config['ttl']);
                $this->redis_cache_log("call before_insert_batch,write redis->{$redis_key}");
            }
            //如果配置不使用数据库，不继续插入数据到库
            $this->redis_cache_log("call before_insert_batch,use_db = false, no use db and return.");
            return true;
        }
    }

    function after_insert_batch($data)
    {
        $this->remove_list_cache(['where'=>$data]);
    }

    function before_delete_one($defined_vars)
    {
        $this->remove_cache($defined_vars,__FUNCTION__);
    }

    function after_delete_one($defined_vars)
    {

    }

    function before_delete_batch($defined_vars)
    {
        $this->remove_cache($defined_vars,__FUNCTION__);
    }

    function after_delete_batch($defined_vars)
    {

    }

    /**
     * 将主键数据存储到缓存，只对设置了主键的数据有效
     * @param $data array
     * @return mixed
     */
    protected function primary_key_set($data)
    {
        $config =$this->get_config();
        //如果设置了主键
        if($config['primary_key'])
        {
            $keys = $this->table_name.":".$config['primary_key'].":".$data[$config['primary_key']];

            //如果设置了强制查询所有
            //当前数据可作为主键数据存储
            if($config['force_select_all'])
            {
                //主键数据不设置过期时间
                $this->redis_set($keys,serialize($data));
                $this->redis_cache_log($this->table_name." write primary_key by value {$data[$config['primary_key']]}.");
                return true;
            }else{
                //如果不是force_select_all的数据，那么不能作为主键缓存数据，需要从DB查询并缓存
                if(isset($data[$config['primary_key']]))
                {
                    //如果无法查到缓存，读db并缓存。
                    $tmp = $this->db->select("*")->where([$config['primary_key']=>$data[$config['primary_key']]])->get($this->table_name);
                    if($tmp)
                    {
                        $tmp = $tmp->row_array();
                        if($tmp)
                        {
                            //写入到缓存
                            $this->redis_set($keys,serialize($tmp));
                            $this->redis_cache_log($this->table_name." write primary_key by value {$data[$config['primary_key']]},read db and cache.");
                            //返回结果
                            return true;
                        }
                    }else{
                        $this->redis_cache_log($this->table_name." can't found  data in db");
                    }
                }
            }
        }else{
            $this->redis_cache_log($this->table_name." can't found  primary_key config.");
            return false;
        }
        $this->redis_cache_log($this->table_name." primary_key_set error.");
        return false;
    }

    /**
     * 根据主键值获取[主键缓存],若找不到则从数据库读取并存缓存并返回
     * 主键缓存就是根据一个数据的列的一个指定数据获取改行的整行数据
     * @param $value
     * @return mixed
     */
    protected function primary_key_get($value)
    {
        if(!$value)
        {
            $this->redis_cache_log($this->table_name." call primary_key_get with nothing");
            return false;
        }
        $config =$this->get_config();
        //如果设置了主键
        if($config['primary_key'])
        {
            $keys = $this->table_name.":".$config['primary_key'].":".$value;
            //根据传入主键键值查询缓存
            $res = $this->redis_get($keys);
            if($res)
            {
                $res = unserialize($res);
                if($res)
                {
                    //如果能查到缓存，直接返回。
                    $this->redis_cache_log($this->table_name." found cache data by primary_key with value {$value}");
                    return $res;
                }
            }else{
                //如果无法查到缓存，读库并缓存。
                $tmp = $this->db->select("*")->where([$config['primary_key']=>$value])->get($this->table_name);
                if($tmp)
                {
                    $tmp = $tmp->row_array();
                    if($tmp)
                    {
                        //写入到缓存
                        $this->redis_set($keys,serialize($tmp));
                        $this->redis_cache_log($this->table_name." can't found cache data by primary_key with value {$value},read db and cache.".$this->db->last_query());
                        //返回结果
                        return $tmp;
                    }
                }
                $this->redis_cache_log($this->table_name." can't found cache data by primary_key with value {$value},read db but no data found.".$this->db->last_query());
            }
        }else{
            $this->redis_cache_log($this->table_name." not primary_key found,return nothing.");
        }
        return false;
    }

    /**
     * 刷新主键缓存，传入主键的值
     * @param $where
     * @return mixed
     */
    protected function primary_key_refresh_by_where($where)
    {
        $config =$this->get_config();
        //如果设置了主键
        if(isset($config['primary_key']) and $config['primary_key'])
        {
            $data = $this->db->select($config['primary_key'])->from($this->table_name)->where($where)->get();
            if($data)
            {
                $data = $data->result_array();
            }else{
                $this->redis_cache_log($this->table_name." primary_key_refresh_by_where no data found in db.");
                return false;
            }
            foreach($data as $v)
            {
                if(isset($v[$config['primary_key']]))
                {
                    $keys = $this->table_name.":".$config['primary_key'].":".$v[$config['primary_key']];
                    if($config['primary_pre_read'])
                    {
                        //直接删除缓存
                        $this->redis_del($keys);
                        $this->redis_cache_log($this->table_name." primary_key_refresh_by_where remove key:".$keys);
                        //并且写入缓存
                        $this->primary_key_get($v[$config['primary_key']]);
                        $this->redis_cache_log($this->table_name." primary_key_refresh_by_where write cache:".$keys);
                    }else{
                        //直接删除缓存
                        $this->redis_del($keys);
                        $this->redis_cache_log($this->table_name." primary_key_refresh_by_where remove key:".$keys);
                    }
                }
            }
        }
    }

    /**
     * 刷新主键缓存，传入主键的值
     * @param $data
     */
    protected function primary_key_refresh($data)
    {
        $config =$this->get_config();
        //如果设置了主键
        if($config['primary_key'])
        {
            if(isset($data[$config['primary_key']]))
            {
                $keys = $this->table_name.":".$config['primary_key'].":".$data[$config['primary_key']];
                if($config['primary_pre_read'])
                {
                    //直接删除缓存
                    $this->redis_del($keys);
                    //并且写入缓存
                    $this->primary_key_get($data[$config['primary_key']]);
                }else{
                    //直接删除缓存
                    $this->redis_del($keys);
                }
            }
        }
    }

    /**
     * 如果配置了replace_list为真并且refresh_list为假时起作用
     * @param $data_list
     * @return mixed
     */
    protected  function primary_key_replace_list($data_list)
    {
        $config =$this->get_config();
        //如果配置了[主键的数据]替换到列表里
        if($config['replace_list'])
        {
            //并且不主动刷新列表缓存
            if(!$config['refresh_list'])
            {
                $this->redis_cache_log($this->table_name." primary_key_replace_list begin.");
                //遍历传入的列表数据
                foreach($data_list as $k=>$v)
                {
                    //如果数据列表里有配置的主键
                    if(isset($data_list[$k][$config['primary_key']]))
                    {
                        $tmp = $this->primary_key_get($data_list[$k][$config['primary_key']]);
                        if($tmp)
                        {
                            $data_list[$k] = array_merge($data_list[$k],$tmp);
                        }
                    }
                }
                $this->redis_cache_log($this->table_name." primary_key_replace_list success.");
            }else{
                $this->redis_cache_log($this->table_name." primary_key_replace_list called but refresh_list is true,ignore replace.");
            }
        }else{
            $this->redis_cache_log($this->table_name." primary_key_replace_list called but no replace_list config found");
        }
        return $data_list;
    }

}