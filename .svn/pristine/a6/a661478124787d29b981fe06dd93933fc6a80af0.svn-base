<?php
/**
 * redis 帮助类
 * @author derrick
 * @date 2017年5月5日
 */
class RedisCache
{
	/**
	 * @var string hostname to use for connecting to the redis server. Defaults to 'localhost'.
	 */
	public $hostname='localhost';
	/**
	 * @var int the port to use for connecting to the redis server. Default port is 6379.
	 */
	public $port=6379;
	/**
	 * @var string the password to use to authenticate with the redis server. If not set, no AUTH command will be sent.
	 */
	public $password;
	/**
	 * @var int the redis database to use. This is an integer value starting from 0. Defaults to 0.
	 */
	public $database=0;
	/**
	 * @var float timeout to use for connection to redis. If not set the timeout set in php.ini will be used: ini_get("default_socket_timeout")
	 */
	public $timeout=null;
	/**
	 * @var int $retry_inval  重连次数
	 */
	public $retry_inval = 0;
	/**
	 * @var resource redis socket connection
	 */
	private $_redis = null;

	/**
	 * init $table_name = array('table_name') //数组第一个参数传表名key
	 */
	public function __construct($table_name = '') {
		$config = get_config();
		if (ENVIRONMENT == 'production' && isset($config['config_redis']) && $table_name && isset($table_name[0]) && isset($config['config_redis'][$table_name[0]])) {
			$config = array(
				'host' => isset($config['config_redis'][$table_name[0]]['host']) ? $config['config_redis'][$table_name[0]]['host'] : '',
				'port' => isset($config['config_redis'][$table_name[0]]['port']) ? $config['config_redis'][$table_name[0]]['port'] : '',
				'timeout' => isset($config['config_redis'][$table_name[0]]['timeout']) ? $config['config_redis'][$table_name[0]]['timeout'] : 2,
				'auth' => isset($config['config_redis'][$table_name[0]]['auth']) ? $config['config_redis'][$table_name[0]]['auth'] : '',
				'retry_inval' => isset($config['config_redis'][$table_name[0]]['retry_inval']) ? $config['config_redis'][$table_name[0]]['retry_inval'] : 0
			);
		} else {
			$config = isset($config["redis"]) ? $config["redis"] : array();
		}
		
		$this->hostname = isset($config['host']) ? $config['host'] : '';
		$this->port = isset($config['port']) ? $config['port'] : '';
		$this->timeout = isset($config['timeout']) ? $config['timeout'] : 2;
		$this->password = isset($config['auth']) ? $config['auth'] : '';
// 		$this->database = $database;
		$this->retry_inval = isset($config['retry_inval']) ? $config['retry_inval'] : 0;
		$this->connect();
	}
	
	/**
	 * 连接redis服务器
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param boolean $reconnection 是否重连
	 * @reurn: return_type
	 */
	public function connect($reconnection = false) {
		if ($this->_redis && $reconnection === false) {
			return $this->_redis;
		}
		$this->_redis = new Redis();
		try {
			$this->_redis->connect($this->hostname, $this->port, $this->timeout, $this->retry_inval);
			if ($this->password) {
				$this->_redis->auth($this->password);
			}
		} catch (Exception $e) {
			print_r($e);
		}
	}

	/**
	 * @author: derrick 开启redis事务
	 * @date: 2017年6月28日
	 * @param: 
	 * @reurn: return_type
	 */
	public function start_transaction() {
// 		return $this->_redis->multi();
	}
	
	/**
	 * @author: derrick 提交事务
	 * @date: 2017年6月28日
	 * @param: 
	 * @reurn: return_type
	 */
	public function commit_transaction() {
// 		return $this->_redis->exec();
	}
	
	/**
	 * @author: derrick 回滚事务
	 * @date: 2017年6月28日
	 * @param: 
	 * @reurn: return_type
	 */
	public function rollback_transaction() {
// 		return $this->_redis->discard();
	}
	
	/**
	 * 获取单个值
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param unknown $key
	 * @param: @return Ambigous <multitype:, boolean, NULL, string>
	 * @reurn: Ambigous <multitype:, boolean, NULL, string>
	 */
	public function get($key) {
		return $this->_redis->get($key);
	}

	/**
	 * 获取多个值
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param array $keys
	 * @param: @return multitype:Ambigous <> 
	 * @reurn: multitype:Ambigous <>
	 */
	public function gets($keys) {
		return $this->_redis->mget($keys);
	}

	/**
	 * 设置一个值
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param unknown $key
	 * @param: @param unknown $value
	 * @param: @param unknown $expire
	 * @param: @return boolean
	 * @reurn: boolean
	 */
	public function set($key,$value,$expire) {
		return $this->_redis->set($key,$value,$expire);
	}

	/**
	 * 新增一个不存在的值
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param unknown $key
	 * @param: @param unknown $value
	 * @param: @param unknown $expire
	 * @param: @return boolean
	 * @reurn: boolean
	 */
	public function add($key,$value,$expire) {
		$this->_redis->setnx($key,$value);
		if ($expire) {
			$this->expire($key, $expire);
		}
	}
	
	/**
	 * 设置过期时间
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param string $key 设置key
	 * @param: @param int $expire 过期时间, 秒
	 * @reurn: return_type
	 */
	public function expire($key, $expire) {
		return $this->_redis->expire($key, $expire);
	}

	/**
	 * 删除值
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param unknown $key
	 * @param: @return boolean
	 * @reurn: boolean
	 */
	public function delete($key) {
		return $this->_redis->delete($key);
	}

	/**
	 * 设置自增值
	 * @author: derrick
	 * @date: 2017年5月5日
	 * @param: @param string $key
	 * @param: @param int $expire 过期时间
	 * @reurn: return_type
	 */
	public function incr($key, $expire = 0) {
		$incr = $this->_redis->incr($key);
		if ($expire) {
			$this->expire($key, $expire);
		}
		return $incr;
	}

	/**
	 * @author: derrick 设置一个哈希对象
	 * @date: 2017年6月28日
	 * @param: @param string $object_key
	 * @param: @param Array $object_values 对象数组
	 * @reurn: return_type
	 */
	public function set_hash_object($object_key, $object_values) {
		return $this->_redis->hMset($object_key, $object_values);
	}
	
	/**
	 * @author: derrick 获取一个哈希对象中的一个属性
	 * @date: 2017年6月28日
	 * @param: @param string $object_key 对象key
	 * @param: @param string $object_field 对象字段
	 * @reurn: return_type
	 */
	public function get_hash_object_field($object_key, $object_field) {
		return $this->_redis->hget($object_key, $object_field);
	}
	
	/**
	 * @author: derrick 获取一个哈希对象
	 * @date: 2017年6月28日
	 * @param: @param string $object_key 对象key
	 * @reurn: return_type
	 */
	public function get_hash_object($object_key) {
		return $this->_redis->hGetAll($object_key);
	}
	
	/**
	 * @author: derrick 对一个哈希对象的某个字段进行加减操作
	 * @date: 2017年6月28日
	 * @param: @param string $object_key 
	 * @param: @param string $object_field 对象字段
	 * @param: @param int $incr_value 加减值 (例如: +5, -4)
	 * @reurn: return_type
	 */
	public function incr_hash_object_field($object_key, $object_field, $incr_value) {
		return $this->_redis->hIncrBy($object_key, $object_field, $incr_value);
	}
	
	/**
	 * @author: derrick 判断一个哈希对象是否存在
	 * @date: 2017年6月28日
	 * @param: @param unknown $object_key
	 * @reurn: return_type
	 */
	public function check_hash_exists($object_key) {
		$length = $this->_redis->hLen($object_key);
		return $length > 0 ? true : false;
	}
	
	/**
	 * @author: derrick 往set list中添加元素
	 * @date: 2017年6月28日
	 * @param: @param String $list_key 列表kye
	 * @param: @param String $list_value 列表值
	 * @reurn: return_type
	 */
	public function add_set_list($list_key, $list_value) {
		return $this->_redis->sAdd($list_key, $list_value);
	}
	
	/**
	 * @author: derrick 获取set list列表
	 * @date: 2017年6月28日
	 * @param: @param string $list_key
	 * @reurn: return_type
	 */
	public function get_set_list($list_key) {
		return $this->_redis->sMembers($list_key);
	}
	
	/**
	 * @author: derrick 获取set list长度
	 * @date: 2017年6月28日
	 * @param: @param unknown $list_key
	 * @reurn: return_type
	 */
	public function get_set_length($list_key) {
		return $this->_redis->sCard($list_key);
	}
}