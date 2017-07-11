<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Tico.wong
 * 简单封装REDIS操作层,便于扩展，
 * 例如：万一要替换Redis.Client等等，只需调整本类即可
 * 不操作redis不进行连接，操作时再进行连接
 * 避免创建太多空连接拖慢速度
 * 封装常用的操作。
 * 不常用操作请直接用get_redis_server()取得Redis操作对象后进行操作
 */
class Redis_Class extends CI_Model
{
    public $redis_servers = array();          //真实的redis服务器
    public $redis_server_delete = array();    //待移除的redis服务器节点
    public $redis_server_add = array();       //待添加的redis服务器节点
    private $_redis_servers = array();        //虚拟节点
    private $_redis_serverKeys = array();     //服务器crc32键值列表
    private $_redis_badServers = array();     //故障服务器列表
    private $_server_map_cache_dir = "/tmp/";  //虚拟节点哈希map缓存目录
    private $_count = 0;
    public static $REDIS_LIST = array(); 
    const SERVER_REPLICAS = 1000; //服务器虚拟副本数量，提高数据分布均匀程度

    protected  $redis = [];
    protected  $table_name = "";

    function __construct() {
        parent::__construct();
        $this->_init_config();
    }

    /**
     * 初始化配置项，增删节点后的数据迁移管理
     */
    private function _init_config()
    {
        //取真实服务器配置
        $this->redis_servers = $this->config->item("config_redis_server_set");

        //取需要移除的redis服务器节点
        $this->redis_server_delete = $this->config->item("config_redis_server_delete");
        if($this->redis_server_delete)
        {
            foreach($this->redis_server_delete as $kd=> $vd)
            {
                if(!$this->redis_servers)
                {
                    continue;
                }
                foreach($this->redis_servers as $ks=> $vs)
                {
                    if($vs['host'] == $vd['host'] and $vs['port'] == $vd['port'])
                    {
                        unset($this->redis_servers[$ks]);
                    }
                }
            }
            $this->redis_servers = array_values($this->redis_servers);
        }

        //取需要添加的redis服务器节点
        $this->redis_server_add = $this->config->item("config_redis_server_add");
        if($this->redis_server_add)
        {
            foreach($this->redis_server_add as $kd=> $vd)
            {
                $auth = "";
                if(isset($vd['auth']))
                {
                    $auth = $vd['auth'];
                }
                $this->redis_servers[] = ['host'=>$vd['host'],'port'=>$vd['port'],'auth'=>$auth];
            }
            $this->redis_servers = array_values($this->redis_servers);
        }

        //生成redis虚拟节点哈希表
        if($this->redis_servers)
        {
            $this->_count = count($this->redis_servers);
            $server_map_key = $this->_get_server_map_key();
            $tmp = $this->_read_server_map($server_map_key);
            if(!empty($tmp))
            {
                $this->_redis_servers = $tmp;
                $this->_redis_serverKeys = array_keys($tmp);
            }
            else
            {
                //Redis虚拟节点哈希表，ksort比较耗时用了2024微秒左右,crc32话了8微秒左右，array_keys耗时985微秒
                foreach($this ->redis_servers as $k => $server)
                {
                    for($i = 0; $i < self::SERVER_REPLICAS; $i++)
                    {
                        $hash = crc32($server[ 'host'] . '#' .$server['port'] . '#'. $i);
                        $this->_redis_servers[$hash] = $k;
                    }
                }
                ksort( $this->_redis_servers);
                $this->_redis_serverKeys = array_keys($this->_redis_servers);
                //写入redis集群哈希列表临时缓存
                $this->_write_server_map($this->_redis_servers,$server_map_key);
            }
        }

        //如果配置了需要添加的服务节点，
        //在生成虚拟节点后再进行迁移否则无法进行数据分片
        if(!empty($this->redis_server_add))
        {
            //判断是否所有待添加的服务器都已添加
            $migration_add_key = "migration:server_add";
            $all_add = true;
            foreach($this->redis_server_add as $k=> $v)
            {
                $r = $this->redis_get($migration_add_key.":".$v['host'].":".$v['port']);
                if(!$r)
                {
                    $all_add = false;
                    break;
                }
            }
            if(!$all_add){
                $this->_reids_migration_add();
                foreach($this->redis_server_add as $k=> $v)
                {
                    $this->redis_set($migration_add_key.":".$v['host'].":".$v['port'],"1",600);
                }
            }
        }
        //如果配置了需要删除的服务，加true调用针对删除节点，速度快一点
        //在生成虚拟节点后再进行迁移否则无法进行数据分片
        if(!empty($this->redis_server_delete))
        {
            $this->_redis_migration_delete();
        }
    }

    /**
     * 根据表名或key获取redis_cache配置
     * 默认根据表名获取,若传了key则根据key获取。
     * @param $key string 根据key获取配置
     * @return mixed
     */
    public function get_config($key="")
    {
        $CI = &get_instance();
        $redis_config = $this->config->item("config_redis");
        if($key)
        {
            $key_arr = explode(":",$key);
            if($key_arr && isset($key_arr[0]))
            {
                if(isset($redis_config[$key_arr[0]]))
                {
                    $tmp = $redis_config[$key_arr[0]];
                }
            }
        }else {
            if (!isset($redis_config[$this->table_name]))
            {
                return [];
            } else {
                if (isset($CI->_config_redis[$this->table_name]))
                {
                    return $CI->_config_redis[$this->table_name];
                }
                $tmp = $redis_config[$this->table_name];
            }
        }
        //补全配置项key，若不配置，使用默认值
        if(!isset($tmp['key']))
        {
            $tmp['key'] = [];
        }
        //补全配置项ttl，若不配置，使用默认值
        if(!isset($tmp['ttl']))
        {
            $tmp['ttl'] = REDIS_CACHE_TTL;
        }
        //补全配置项use_db，若不配置，使用默认值
        if(!isset($tmp['use_db']))
        {
            $tmp['use_db'] = REDIS_CACHE_USE_DB;
        }
        //补全配置项log，若不配置，使用默认值
        if(!isset($tmp['log']))
        {
            $tmp['log'] = REDIS_CACHE_LOG;
        }
        //补全配置项force_select_all，若不配置，使用默认值
        if(!isset($tmp['force_select_all']))
        {
            $tmp['force_select_all'] = REDIS_CACHE_FORCE_SELECT_ALL;
        }
        //补全配置项update_one_keys,若不配置，使用空串
        if(!isset($tmp['update_one_keys']))
        {
            $tmp['update_one_keys'] = [];
        }
        //补全配置项update_list_keys,若不配置，使用空串
        if(!isset($tmp['update_list_keys']))
        {
            $tmp['update_list_keys'] = [];
        }
        //补全配置项refresh_list,若不配置，则使用真
        if(!isset($tmp['refresh_list']))
        {
            $tmp['refresh_list'] = 1;
        }
        //补全配置项host,若不配置，则使用空串
        if(!isset($tmp['host']))
        {
            $tmp['host'] = "";
        }
        //补全配置项port,若不配置，则使用6379
        if(!isset($tmp['port']))
        {
            $tmp['port'] = "6379";
        }
        //补全配置项auth,若不配置，则使用空串
        if(!isset($tmp['auth']))
        {
            $tmp['auth'] = "";
        }
        //补全配置项primary_key,若不配置，则使用空串
        if(!isset($tmp['primary_key']))
        {
            $tmp['primary_key'] = "";
        }
        //补全配置项replace_list,若不配置，则使用0
        if(!isset($tmp['replace_list']))
        {
            $tmp['replace_list'] = 0;
        }
        //补全配置项primary_pre_read,若不配置，则使用0
        if(!isset($tmp['primary_pre_read']))
        {
            $tmp['primary_pre_read'] = 0;
        }
        $CI->_config_redis[$this->table_name] = $tmp;
        return $tmp;
    }

    /**
     * 连接到redis服务器
     * @param $host 服务器地址
     * @param int $port 端口号
     * @param string $auth 授权信息
     * @param int $time_out 超时时间
     * @param  int $retry_inval 重试次数
     * @return bool|Redis
     */
    private function _connect_redis_server($host,$port=6379,$auth="",$time_out=REDIS_CONNECT_TIMEOUT,$retry_inval=3)
    {
        if(class_exists("Redis")) {
            $CI = &get_instance();
            if(isset($CI->_redis_server_list[$host.":".$port]))
            {
                return $CI->_redis_server_list[$host.":".$port];
            }
            $redis = new Redis();
            try {
                $b = $redis->connect($host, $port,$time_out,$retry_inval);
                if(!$b)
                {
                    $CI->_redis_server_list[$host.":".$port] = false;
                    log_message("ERROR", "connect redis failure:".$host.":".$port);
                    return false;
                }
                if ($auth) {
                    $redis->auth($auth);
                }
            } catch (Exception $e) {
                $CI->_redis_server_list[$host.":".$port] = false;
                log_message("ERROR", "connect redis failure:".$host.":".$port);
                return false;
            }
            $CI->_redis_server_list[$host.":".$port] = $redis;
            return $redis;
        }else{
            log_message("ERROR","Redis plugin no support!from file-line:".__FILE__.",".__LINE__);
            return false;
        }
    }

    /**
     * 取redis集群配置的关键字
     * 作为缓存关键字
     * 防止每次都计算哈希分布表
     */
    private function _get_server_map_key()
    {
        $str = "";
        foreach($this ->redis_servers as $k => $server)
        {
            $str .= crc32($server[ 'host'] . '#' .$server['port']);
        }
        return substr(md5($str),0,16).".cache";
    }

    /**
     * 取redis集群的节点哈希键表
     * 用key读取缓存
     * 防止每次都计算哈希分布表
     * @param string $key
     * @return mixed $res
     */
    private  function _read_server_map($key)
    {
        $tmp = $this->_server_map_cache_dir.$key;
        if(file_exists($tmp))
        {
            require($tmp);
            if(!empty($data))
            {
                return $data;
            }
        }
        return false;
    }

    /**
     * @param $map
     * @param $key
     */
    private  function _write_server_map($map, $key)
    {
        $tmp_path = "";
        $arr = explode(DIRECTORY_SEPARATOR,$this->_server_map_cache_dir);
        foreach($arr as $k=>$v)
        {
            if(!$v)
            {
                continue;
            }
            if($k == count($arr)-1)
            {
                break;
            }
            $tmp_path = $tmp_path.DIRECTORY_SEPARATOR.$v;
            if(!is_dir($tmp_path))
            {
                mkdir($tmp_path,0777);
            }
        }
        $tmp = $this->_server_map_cache_dir.$key;
        $str = '<?php '."\n".' $data='.var_export($map,true) .";";
        @file_put_contents($tmp,$str);
    }

    /**
     * 使用一致性哈希分派服务器，附加故障检测及转移功能
     */
    private function _get_server($key){
        if(count($this->_redis_serverKeys) < 1)
        {
            return false;
        }
        $hash = crc32($key);
        $slen = $this->_count * self:: SERVER_REPLICAS;
        // 快速定位虚拟节点
        $sid = $hash > $this->_redis_serverKeys[$slen-1] ? 0 : $this->quickSearch($this->_redis_serverKeys, $hash, 0, $slen);
        $conn = false;
        $i = 0;
        do{
            $n = $this->_redis_servers[$this->_redis_serverKeys[$sid]];
            !in_array($n, $this->_redis_badServers ) && $conn = $this->getRedisConnect($n);
            $sid = ($sid + 1) % $slen;
        }while (!$conn && $i++ < $slen);
        return $conn ? $conn : new Redis();
    }

    /**
     * 二分法快速查找
     */
    private function quickSearch($stack, $find, $start, $length) {
        if ($length == 1) {
            return $start;
        }
        else if ($length == 2) {
            return $find <= $stack[$start] ? $start : ($start +1);
        }

        $mid = intval($length / 2);
        if ($find <= $stack[$start + $mid - 1]) {
            return $this->quickSearch($stack, $find, $start, $mid);
        }
        else {
            return $this->quickSearch($stack, $find, $start+$mid, $length-$mid);
        }
    }

    /**
     *取redis连接
     * @param int $n
     * @param boolean $use_exists_con 是否使用现有连接
     * @return bool|mixed
     */
    private function getRedisConnect($n=0, $use_exists_con = false){
        if (empty(self::$REDIS_LIST[$n])){
            $ret = $this->_connect_redis_server($this->redis_servers[$n]['host'], $this->redis_servers[$n]['port'],$this->redis_servers[$n]['auth']);
            if(!$ret)
            {
                unset(self::$REDIS_LIST[$n]);
                $this->_redis_badServers [] = $n;
                return false;
            }else{
                self::$REDIS_LIST[$n] = $ret;
            }
        }
        return self::$REDIS_LIST[$n];
    }

    /**
     * 获取redis服务器对象，若传空key进来直接获取非集群配置
     * @param $redis_key，缓存关键字，用于扩展，如果是自建分布式的多redis服务器（非redis集群），判断连接到哪个redis服务器。
     * @return bool|Redis
     */
    function get_redis_server($redis_key="")
    {
        if(REDIS_STOP == 1)
        {
            return false;
        }

        //在生产环境下,如果表配置了单独的redis服务器，优先使用之
        if(ENVIRONMENT == 'production')
        {
            $config = $this->get_config($redis_key);
            if(isset($config['host']) && $config['host'])
            {
                $tmp =  $this->_connect_redis_server($config['host'],$config['port'],$config['auth']);
                if($tmp)
                {
                    return $tmp;
                }
            }
        }

        //如果配置了分布式redis服务器配置，分布式服务器优先
        if($redis_key)
        {
            $redis_server = $this->_get_server($redis_key);
            if($redis_server)
            {
                return $redis_server;
            }
        }

        //检测单点redis服务器
        if(!$this->redis)
        {
            if(defined("REDIS_HOST") and defined("REDIS_PORT") and REDIS_HOST and REDIS_PORT)
            {
                if(defined("REDIS_AUTH") and REDIS_AUTH)
                {
                    $this->redis = $this->_connect_redis_server(REDIS_HOST,REDIS_PORT,REDIS_AUTH);
//                    echo("connect redis<br>");
                }else{
                    $this->redis = $this->_connect_redis_server(REDIS_HOST,REDIS_PORT);
                }
            }else{
                return false;
            }
        }
        return $this->redis;
    }

    /**
     * redis函数get的简单封装
     * @param $key
     * @return bool|string
     */
    function redis_get($key)
    {
        if($this->get_redis_server($key))
        {
            try
            {
                return $this->get_redis_server($key)->get($key);
            }
            catch (Exception $ex)
            {
                return false;
            }
        }
    }

    /**
     * redis函数set的简单封装
     * @param $key
     * @param $value
     * @param int $timeout
     * @return mixed
     */
    function redis_set($key,$value,$timeout=0)
    {
        $server = $this->get_redis_server($key);
        if($server)
        {
            if($timeout)
            {
                try
                {
//                    $this->_my_index($key);
                    return $server->set($key, $value, $timeout);
                }
                catch (Exception $ex)
                {
                    return false;
                }
            }
            else
            {
                try
                {
//                    $this->_my_index($key);
                    return $server->set($key, $value);
                }
                catch (Exception $ex)
                {
                    return false;
                }
            }
        }
    }

    /**
     * redis函数setex的简单封装
     * @param $key
     * @param $ttl
     * @param $value
     * @return bool
     */
    function redis_setEx($key,$ttl,$value)
    {
        if($this->get_redis_server($key))
        {
            try
            {
                return $this->get_redis_server($key)->setex($key, $ttl, $value);
            }
            catch (Exception $ex)
            {
                return false;
            }
        }
    }

    /**
     * redis函数type的简单封装
     * @return array
     */
    function redis_type($key)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->type($key);
        }
    }

    /**
     * redis函数time的简单封装
     * @return array
     */
    function redis_time($key)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->time();
        }
    }

    /**
     * @param  需要删除的键值，传入1到n个需要del的键值作为参数
     * redis函数del的简单封装
     * @return mixed
     */
    function redis_del()
    {
        $defined_vars = func_get_args();
        foreach($defined_vars as $k=>$v)
        {
            if($this->get_redis_server($v))
            {
                return $this->get_redis_server($v)->del($v);
            }
        }
    }

    /**
     * redis函数hset的简单封装
     * @param $key
     * @param $hashKey
     * @param $value
     * @return int
     */
    function redis_hSet($key,$hashKey,$value)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->hset($key, $hashKey, $value);
        }
    }

    /**
     * redis函数hget的简单封装
     * @param $key
     * @param $hashKey
     * @return string
     */
    function redis_hGet($key,$hashKey)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->hget($key, $hashKey);
        }
    }

    /**
     * redis函数hdel的简单封装
     * 第一个传入hash的key，第二个到第n个传入hashKey
     * @return int
     */
    function redis_hDel()
    {
        $res = 0;
        $defined_vars = func_get_args();
        if(empty($defined_vars[0]))
        {
            return false;
        }else{
            $key = $defined_vars[0];
        }
        if($this->get_redis_server($key))
        {
            foreach($defined_vars as $k=>$v)
            {
                if(0 == $k)
                {
                    continue;
                }
                $res += $this->get_redis_server($key)->hdel($key,$v);
            }
        }
        return $res;
    }

    /**
     * redis函数hGetAll的简单封装
     * @param $key
     * @return array
     */
    function redis_hGetAll($key)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->hGetAll($key);
        }
    }

    /**
     * redis函数hExists的简单封装
     * @param $key
     * @param $hashKey
     * @return bool
     */
    function redis_hExists($key,$hashKey)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->hExists($key, $hashKey);
        }
    }

    /**
     * redis函数hKeys的简单封装
     * @param $key
     * @return array
     */
    function redis_hKeys($key)
    {
        if($this->get_redis_server($key)) {
            return $this->get_redis_server($key)->hKeys($key);
        }
    }

    /**
     * redis函数hLen的简单封装
     * @param $key
     * @return int
     */
    function redis_hLen($key)
    {
        if($this->get_redis_server($key)) {
            return $this->get_redis_server($key)->hLen($key);
        }
    }

    /**
     * 获取List长度
     * @author: derrick
     * @date: 2017年3月28日
     * @param: 
     * @reurn: return_type
     */
    function redis_lLen($key) {
    	if($this->get_redis_server($key)) {
    		return $this->get_redis_server($key)->lLen($key);
    	}
    }
    /**
     * redis函数hMSet的简单封装
     * @param $key
     * @param $hashKeys
     * @return bool
     */
    function redis_hMSet($key,$hashKeys)
    {
        if($this->get_redis_server($key)) {
            return $this->get_redis_server($key)->hMSet($key, $hashKeys);
        }
    }

    /**
     * redis函数hMGet的简单封装
     * @param string $key
     * @param array $hashKeys
     * @return array
     */
    function redis_hMGet($key,$hashKeys)
    {
        if($this->get_redis_server($key)) {
            return $this->get_redis_server($key)->hMGet($key, $hashKeys);
        }
    }

    /**
     * redis函数ttl的简单封装
     * @param $key
     * @return int
     */
    function redis_ttl($key)
    {
        if($this->get_redis_server($key)) {
            return $this->get_redis_server($key)->ttl($key);
        }
    }
    /**
     * redis函数keys的简单封装
     * @param $pattern
     * @param bool $original 默认不使用原生的keys命令，太耗CPU
     * @return array
     */
    function redis_keys($pattern,$original=true)
    {
        if(!REDIS_USE_KEYS)
        {
            return [];
        }

        //是否使用原生的keys命令，默认为是
        if(!$original)
        {
            //return $this->_my_redis_keys($pattern);
        }

        $res = [];

        //读取keys命令的结果集缓存
        $tmp = $this->_redis_keys_read($pattern);
        if($tmp)
        {
            //如果读取到了就直接返回了
            return $tmp;
        }

        //如果是集群配置
        if($this->redis_servers)
        {
            foreach($this->redis_servers as $k=> $v)
            {
                $redis_server = $this->getRedisConnect($k);
                if($redis_server)
                {
                    $tmp =  $redis_server->keys($pattern);
                    $keys_time_key = "TIMES:KEYS:".date("YmdH");
                    $this->_record_times($redis_server,$keys_time_key);
                    $keys_time_key = "TIMES:KEYS:".date("Ymd");
                    $this->_record_times($redis_server,$keys_time_key);
                    if($tmp)
                    {
                        $res = array_merge($res,$tmp);
                    }
                }
            }
        }else{
            //如果不是集群配置
            if($this->get_redis_server(""))
            {
                $redis_server = $this->get_redis_server("");
                if($redis_server){
                    $res = $redis_server->keys($pattern);
                    $keys_time_key = "TIMES:KEYS:".date("YmdH");
                    $this->_record_times($redis_server,$keys_time_key);
                    $keys_time_key = "TIMES:KEYS:".date("Ymd");
                    $this->_record_times($redis_server,$keys_time_key);
                }
            }
        }

        //写入keys结果集的缓存内
        $this->_redis_keys_write($pattern,$res);

        foreach($res as $k=>$v)
        {
            if($v == 'NULL')
            {
                unset($res[$k]);
            }
        }

        return $res;
    }

    /**
     * 简单的次数累加器
     * @param $redis_server
     * @param $key
     * @param int $ttl
     */
    function _record_times($redis_server,$key,$ttl=86400)
    {
        $redis_server->incr($key);
        $tmp = $redis_server->ttl($key);
        if($tmp === -1)
        {
            $redis_server->setTimeOut($key,$ttl);
        }
    }

    /**
     * redis scan的简单封装
     * @param $pattern
     * @return array
     */
    function redis_scan($pattern)
    {
        $res = [];

        //如果是集群配置
        if($this->redis_servers)
        {
            foreach($this->redis_servers as $k=> $v)
            {
                if($this->getRedisConnect($k))
                {
                    $server = $this->getRedisConnect($k);
                    $iterator = null;
                    while(true) {
                        $keys = $server->scan($iterator,$pattern,100000);
                        $res = array_merge($res,$keys);
                        if(!$iterator)
                        {
                            break;
                        }
                    }
                }
            }
        }else{
            //如果不是集群配置
            if($this->get_redis_server(""))
            {
                $server = $this->get_redis_server("");
                if($server){
                    $iterator = null;
                    while(true) {
                        $keys = $server->scan($iterator,$pattern,100000);
                        $res = array_merge($res,$keys);
                        if(!$iterator)
                        {
                            break;
                        }
                    }
                }
            }
        }
        return $res;
    }

    /**
     * redis sscan的简单封装
     * @param $key
     * @param $pattern
     * @return array
     */
    function redis_sscan($key,$pattern)
    {
        $res = [];
        if($this->get_redis_server($key))
        {
            $server = $this->get_redis_server($key);
            if($server){
                $iterator = null;
                $i = 0;
                while(true) {
                    $keys = $server->sScan($key,$iterator,$pattern,10000);
                    $res = array_merge($res,$keys);
                    if(!$iterator)
                    {
                        break;
                    }
                    if($i > 10000)
                    {
                        break;
                    }
                    $i++;
                }
            }
        }
        return $res;
    }

    /**
     * sadd的简单封装
     * @param $key
     * @param $value
     */
    function redis_sadd($key,$value)
    {
        $server = $this->get_redis_server($key);
        if($server)
        {
            $server->sAdd($key,$value);
        }
    }

    /**
     * srem的简单封装
     * @param $key
     * @param $value
     */
    function redis_srem($key,$value)
    {
        $server = $this->get_redis_server($key);
        if($server)
        {
            $server->sRem($key,$value);
        }
    }
    
    /**
     * @author: derrick 返回集合中的随机一个元素
     * @date: 2017年6月29日
     * @param: @param string $key
     * @reurn: return_type
     */
    function redis_spop($key) {
    	$server = $this->get_redis_server($key);
    	if($server)
    	{
    		return $server->sPop($key);
    	}
    } 

    /**
     * scard的简单封装
     * @param $key
     * @return int
     */
    function redis_scard($key)
    {
        $server = $this->get_redis_server($key);
        if($server)
        {
            return $server->sCard($key);
        }
        return 0;
    }

    /**
     * smembers的简单封装
     * @param $key
     * @return array
     */
    function redis_smembers($key)
    {
        $res = false;
        $server = $this->get_redis_server($key);
        if($server)
        {
            $res = $server->sMembers($key);
        }
        return $res;
    }

    /**
     * sismember的简单封装
     * @param $key
     * @param $value
     * @return array|bool
     */
    function redis_sismember($key,$value)
    {
        $res = false;
        $server = $this->get_redis_server($key);
        if($server)
        {
            $res = $server->sIsMember($key,$value);
        }
        return $res;
    }

    /**
     * redis函数setTimeout的简单封装
     * @param $key
     * @param $ttl
     */
    function redis_setTimeout($key,$ttl)
    {
        if($this->get_redis_server($key))
        {
            $this->get_redis_server($key)->setTimeout($key, $ttl);
        }
    }
    /**
     * redis函数expire的简单封装
     * @param $key
     * @param $ttl
     * @return bool
     */
    function redis_expire($key,$ttl)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->expire($key, $ttl);
        }
    }
    /**
     * redis函数exists的简单封装
     * @param $key
     * @return bool
     */
    function redis_exists($key)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->exists($key);
        }
    }
    /**
     * redis函数lPush的简单封装
     * @param $key
     * @param $value
     * @return int
     */
    function redis_lPush($key,$value)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->lPush($key, $value);
        }
    }

    /**
     * 向list头部插入数据
     * @author: derrick
     * @date: 2017年4月11日
     * @param: @param unknown $key
     * @param: @param unknown $value
     * @reurn: return_type
     */
    function redis_lPush_one($key, $value) {
    	if($this->get_redis_server($key))
    	{
    		return $this->get_redis_server($key)->lPush($key, $value);
    	}
    }
    /**
     * redis函数lPop的简单封装
     * @param $key
     * @return string
     */
    function redis_lPop($key)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->lPop($key);
        }
    }
	/**
     * redis函数blPop的简单封装
     * @author: derrick
     * @date: 2017年3月22日
     * @param: @param String $key 队列名称
     * @param: @param int $timeout 超时时间
     * @reurn: Array
     */
    function redis_blPop($key, $timeout = 0) {
		if($this->get_redis_server($key)) {
			return $this->get_redis_server($key)->blPop($key, $timeout);
    	}
    }
    /**
     * redis函数lGetRange的简单封装
     * @param $key
     * @param $start
     * @param $end
     */
    function redis_lGetRange($key,$start,$end)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->lGetRange($key, $start, $end);
        }
    }
    /**
     * redis函数lGet的简单封装
     * @param $key
     * @param $index
     */
    function redis_lGet($key,$index)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->lGet($key, $index);
        }
    }
    /**
     * redis函数rPush的简单封装
     * @param $key
     * @param $value
     * @param null $value2
     * @param null $value3
     * @return int
     */
    function redis_rPush($key,$value,$value2=null,$value3=null)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->rPush($key, $value, $value2, $value3);
        }
    }
    /**
     * 队尾添加元素
     * @author: derrick
     * @date: 2017年3月28日
     * @param: @param unknown $key
     * @param: @param unknown $value
     * @reurn: return_type
     */
    function redis_rPush_one($key, $value) {
    	if($this->get_redis_server($key))
    	{
    		return $this->get_redis_server($key)->rPush($key, $value);
    	}
    }
    /**
     * 截取list, 从start位置处开始,到end位置截至.截取的元素保留在list中,其余的delete
     * @author: derrick
     * @date: 2017年4月10日
     * @param: @param unknown $key
     * @param: @param unknown $start
     * @param: @param unknown $end
     * @reurn: return_type
     */
    function redis_ltrim($key, $start, $end) {
    	if($this->get_redis_server($key))
    	{
    		return $this->get_redis_server($key)->ltrim($key, $start, $end);
    	}
    }
    /**
     * 设置list中下标为key的值
     * @author: derrick
     * @date: 2017年4月11日
     * @param: @param unknown $key
     * @param: @param unknown $list_index
     * @param: @param unknown $value
     * @reurn: return_type
     */
    function redis_lSet($key, $list_index, $value) {
    	if($this->get_redis_server($key)) {
    		return $this->get_redis_server($key)->lSet($key, $list_index, $value);
    	}
    }
    /**
     * redis函数rPop的简单封装
     * @param $key
     * @return string
     */
    function redis_rPop($key)
    {
        if($this->get_redis_server($key))
        {
            return $this->get_redis_server($key)->rPop($key);
        }
    }
    /**
     * redis函数client的简单封装,非分布式配置时才有效
     * @param $command
     * @param $arg
     * @return mixed
     */
    function redis_client($command,$arg)
    {
        if($this->get_redis_server())
        {
            return $this->get_redis_server()->client($command, $arg);
        }
    }
    /**
     * redis函数close的简单封装,非分布式配置才有效
     * @return bool
     */
    function redis_close()
    {
        if($this->redis)
        {
            try{
                $this->redis->close();
            }catch (Exception $e)
            {
                log_message("ERROR",$e->getMessage().",".__FILE__.",".__LINE__);
                return false;
            }
            return true;
        }
    }
    /**
     * 断开对应key所在的redis服务器连接
     * @author: derrick
     * @date: 2017年4月12日
     * @param: @param unknown $key
     * @reurn: return_type
     */
    function redis_closes($key) {
    	$server = $this->get_redis_server($key);
    	if ($server) {
    		$server->close();
    	}
    	$CI = &get_instance();
    	$CI->_redis_server_list = array();
    	return true;
    }
    /**
     * redis函数connect的简单封装
     * @param $host
     * @param $port
     * @param float $timeout
     * @param int $retry_intrval
     * @return bool
     */
    function  redis_connect($host,$port,$auth="",$timeout=0.0,$retry_intrval=0)
    {
        return $this->_connect_redis_server($host,$port,$auth,$timeout,$retry_intrval);
//        if($this->redis)
//        {
//            try {
//                return $this->redis->connect($host,$port,$timeout,$retry_intrval);
//            }
//            catch(Exception $e)
//            {
//                log_message("ERROR","connect redis failure:".$host.":".$port);
//            }
//        }else{
//            if(class_exists("Redis")) {
//                $this->redis = new Redis();
//                try {
//                    return $this->redis->connect($host, $port, $timeout, $retry_intrval);
//                } catch (Exception $e) {
//                    log_message("ERROR", "connect redis failure:" . $host . ":" .$port);
//                }
//            }
//        }
    }
    /**
     * redis函数ping的简单封装，非分布式配置才有效
     * @return string
     */
    function  redis_ping()
    {
        if($this->get_redis_server())
        {
            return $this->get_redis_server()->ping();
        }
    }
	/**
	 * 获取list中value对应的下标, 如果value传进来的是下标, 则返回下标对应的值
	 * @author: derrick
	 * @date: 2017年4月11日
	 * @param: @param unknown $key
	 * @param: @param unknown $value
	 * @reurn: return_type
	 */
	function redis_lindex($key, $value) {
		if($this->get_redis_server($key)) {
            return $this->get_redis_server($key)->lindex($key, $value);
        }
	}
    /**
     * redis函数incrBy的简单封装，非分布式配置才有效
     * @return string
     */
    function  redis_incrBy($keys,$int)
    {
        if($this->get_redis_server($keys))
        {
            return $this->get_redis_server($keys)->incrBy($keys,$int);
        }
    }
    
    /**
     * 自增计数器
     * @author: derrick
     * @date: 2017年4月10日
     * @param: @param unknown $keys
     * @reurn: return_type
     */
    function  redis_incr($keys)
    {
        if($this->get_redis_server($keys))
        {
            return $this->get_redis_server($keys)->incr($keys);
        }
    }

    function redis_test()
    {
//        return $this->get_redis()->setnx($key,$value);
    }

    /**
     * redis数据迁移
     * 增加或删除节点后的数据迁移方法
     * @param bool $remove_noed,是否单纯删除节点迁移
     * @return mixed
     */
    function redis_migration($remove_noed=false)
    {
        $stime = microtime(true);
        $total = 0;
        //删除节点的数据迁移
        $total = $total + $this->_redis_migration_delete();
        if(!$remove_noed)
        {
            //增加节点的数据迁移
            $total = $total + $this->_reids_migration_add();
        }
        $etime = microtime(true);
        $time_span = $etime - $stime;
        if($this->input->is_cli_request()){
            echo("total migration number:" . $total . "\n");
            echo("execute time:" . $time_span . "\n");
        }
        return $total;
    }

    /**
     * 清空所有redis服务器的连接
     * @author: derrick
     * @date: 2017年4月12日
     * @param: 
     * @reurn: return_type
     */
    function close_all() {
    	self::$REDIS_LIST = [];
    }
    
    /**
     * redis删除节点的数据迁移
     * 增加或删除节点后的数据迁移方法
     * @return mixed
     */
    private function _redis_migration_delete()
    {
        $total = 0;
        //若配置了删除服务器节点配置
        foreach($this->redis_server_delete as $k=> $v)
        {
            if(class_exists("Redis"))
            {
                $redis = $this->_connect_redis_server($v['host'],$v['port'],$v['auth']);
                if(!$redis)
                {
                    break;
                }
                //暂时不判断待删除的节点是否在现有节点列表里
                $keys = $redis->keys("*");
                //如果待移除服务器节点非空
                if($keys)
                {
                    //将数据移动到对应节点
                    foreach($keys as $kk=>$kv)
                    {
                        $ttl = $redis->ttl($kv);
                        $df = $redis->get($kv);
                        if($ttl and $ttl != -1){
                            $this->redis_set($kv,$df,$ttl);
                        }else{
                            $this->redis_set($kv,$df);
                        }
                        $total = $total + 1;
                        $redis->del($kv);
                    }
                }
            }else{
                log_message("ERROR","No Redis extends found!");
            }
        }
        return $total;
    }

    /**
     * redis增加节点的数据迁移
     * 增加或删除节点后的数据迁移方法
     * @return mixed
     */
    private function _reids_migration_add()
    {
        $total = 0;
        if(!$this->redis_servers)
        {
            return $total;
        }
        foreach($this->redis_servers as $k=> $v)
        {
            if($this->getRedisConnect($k))
            {
                $tmp =  $this->getRedisConnect($k)->keys("*");
                if($tmp)
                {
                    foreach($tmp as $kk=>$vk)
                    {
                        $d2 = $this->redis_get($vk);
                        //如果数据分片不在相应分片区
                        if(!$d2)
                        {
                            //取数据分片
                            $df = $this->getRedisConnect($k)->get($vk);
                            if($df)
                            {
                                //取分片ttl
                                $ttl = $this->getRedisConnect($k)->ttl($vk);
                                //移动数据分片到相应分片区
                                if($ttl and $ttl != -1){
                                    $this->redis_set($vk,$df,$ttl);
                                }else{
                                    $this->redis_set($vk,$df);
                                }
                                //删除不在相应分片区的数据
                                $this->getRedisConnect($k)->del($vk);
                                $total = $total + 1;
                            }
                        }
                    }
                }
            }
        }
        return $total;
    }

    /**
     * 取key的首路径
     * @param $keys
     * @return mixed
     */
    private function _pre_key($keys)
    {
        $res = "";
        $key_arr = explode(":",$keys);
        if($key_arr)
        {
            if(isset($key_arr[0]))
            {
                $res = $key_arr[0];
                if($res === "*")
                {
                    $res = "";
                }
            }
        }
        return $res;
    }

    /**
     * 自建索引的键值,若传空则返回键值前缀
     * @param $keys
     * @return string
     */
    private function _index_key($keys)
    {
        $index_key_pre = "index:set:";
        if(!$keys)
        {
            return $index_key_pre;
        }
        $res = "";
        $pre_key = $this->_pre_key($keys);
        if($pre_key)
        {
            $res = $index_key_pre.$pre_key;
        }
        return $res;
    }

    /**
     * 将键值加入自建索引
     * @param $keys
     */
    private function _my_index($keys)
    {
        $index_key = $this->_index_key($keys);
        if($index_key){
            $this->redis_sadd($index_key,$keys);
        }
    }

    /**
     * 自建索引的维护
     */
    public function my_index_refresh()
    {
        $index_key = $this->_index_key("");
        $index_keys = $index_key."*";

        $res = $this->redis_keys($index_keys,true);

        foreach($res as $v)
        {
            $smems = $this->redis_smembers($v);
            foreach($smems as $v2)
            {
                $b = $this->get_redis_server($v2)->exists($v2);
                echo("TEST :".$v2." from set:".$v."\n");
                if(!$b)
                {
                    $this->redis_srem($v,$v2);
                    echo("REMOVE :".$v2." from set:".$v."\n");
                    ob_flush();
                }
            }
        }
    }


    /**
     * 使用集合跟sscan代替keys这种非常消耗CPU的操作
     * @param $pattern
     * @return array
     */
    private function _my_redis_keys($pattern)
    {
        $key = $this->_index_key($pattern);
        $index = str_replace("*","___",$pattern);
        $redis_index = "index:index:".$index;
        //如果存在快速索引的集合就直接返回
        if($this->redis_exists($redis_index))
        {
            return $this->redis_smembers($redis_index);
        }
        $res = $this->redis_sscan($key,$pattern);
        //如果不存在快速索引的集合就写入
        foreach($res as $v)
        {
            $this->redis_sadd($redis_index,$v);
        }
        $this->redis_setTimeout($redis_index,30);
        return $res;
    }

    /**
     * 根据pattern取使用keys的结果的数据的存储的键值
     * @param $pattern
     * @return string
     */
    private function _get_index_keys($pattern)
    {
        $index = str_replace("*","___",$pattern);
        $redis_index = "index:keys:".$index;
        return $redis_index;
    }

    /**
     * 根据pattern直接读取keys命令结果集
     * @param $pattern
     * @return mixed
     */
    private function _redis_keys_read($pattern)
    {
        $redis_index = $this->_get_index_keys($pattern);
        //如果存在快速索引的集合就直接返回
        if($this->redis_exists($redis_index))
        {
            //临时改为有就直接返回,不再调整TTL时间
//            $res = $this->redis_smembers($redis_index);
//            return $res;

            $ttl = $this->redis_ttl($redis_index);
            $ttl_span = bcsub(REDIS_KEYS_INDEX_TIME,$ttl);

            //并且TTL间隔小于设置的间隔
            if(bccomp($ttl_span,REDIS_KEYS_INDEX_SPAN) < 1)
            {
                $res = $this->redis_smembers($redis_index);
                return $res;
            }else{
                //$this->redis_setTimeout($redis_index,REDIS_KEYS_INDEX_TIME);
            }
        }
        return false;
    }

    /**
     * 写入KEYS命令出来的结果集
     * @param $pattern
     * @param $data
     */
    private function _redis_keys_write($pattern,$data)
    {
        $redis_index = $this->_get_index_keys($pattern);
        $this->redis_sadd($redis_index,"");
        foreach($data as $v)
        {
            $this->redis_sadd($redis_index,$v);
        }
        $ttl = $this->redis_ttl($redis_index);
        if($ttl ==  -1)
        {
            $this->redis_setTimeout($redis_index,REDIS_KEYS_INDEX_TIME);
        }
    }
}