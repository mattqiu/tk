<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 配置生效前提是：model继承了MY_Model，并且数据操作都改为MY_Model里的操作。
 * 如：get_one,get_list,update_one,update_batch,insert_one,insert_batch,delete_one,delete_batch。
 * 若需要重写缓存，重写befor_get_one,after_get_one等函数即可。
 * 若需要自行操作缓存，也可以直接使用Redis_Class类的方法直接进行redis读写。
 */

/**
 * REDIS服务器地址，
 * 若使用分布式配置，这里的配置将失效，
 * 分布式配置请参考config_redis_server_set
 */
define("REDIS_STOP",empty(config_item('redis')['stop'])?"":config_item('redis')['stop']);

/**
 * REDIS是否允许使用keys命令
 */
define("REDIS_USE_KEYS",empty(config_item('redis')['use_keys'])?"1":config_item('redis')['use_keys']);

/**
 * REDIS的KEYS的索引的更新时间间隔,KEYS的索引是增量型的，时间间隔外才进行更新一次
 */
define("REDIS_KEYS_INDEX_SPAN",empty(config_item('redis')['keys_index_span'])?"10":config_item('redis')['keys_index_span']);

/**
 * REDIS的KEYS的索引的过期时间,KEYS的索引是增量型的
 */
define("REDIS_KEYS_INDEX_TIME",empty(config_item('redis')['keys_index_time'])?"10":config_item('redis')['keys_index_time']);

/**
 * REDIS服务器地址，
 * 若使用分布式配置，这里的配置将失效，
 * 分布式配置请参考config_redis_server_set
 */
define("REDIS_HOST",empty(config_item('redis')['host'])?"":config_item('redis')['host']);

/**
 * REDIS服务器端口,
 * 地址端口缺一不可，
 * 少一个都将无法启用缓存，
 * 但如果使用分布式配置，这里的配置被覆盖
 */
define("REDIS_PORT",empty(config_item('redis')['port'])?"":config_item('redis')['port']);

/**
 * REDIS服务器认证信息
 */
define("REDIS_AUTH",empty(config_item('redis')['auth'])?"":config_item('redis')['auth']);

/**
 * REDIS服务器连接超时设置
 */
define("REDIS_CONNECT_TIMEOUT",empty(config_item('redis')['timeout'])?1:config_item('redis')['timeout']);


/**
 * redis键值对自动过期时间默认值,单位秒，若配置成0则永不过期。
 */
define("REDIS_CACHE_TTL",60);

/**
 * 是否使用数据库默认值,true或1为使用数据库，若为false或0则不使用数据库,
 * 若不使用数据库，那么该表的数据将不从数据库获取
 */
define("REDIS_CACHE_USE_DB",true);

/**
 * 是否打开日志默认值，日志文件存在:/tmp/{$table_name}_redis_$Y_$m_$d.log文件
 */
define("REDIS_CACHE_LOG",false);

/**
 * redis_key长度超过本值将使用md5后的值作为key，配置里不设置将使用本值
 */
define("REDIS_CACHE_MAX_LEN_MD5",256);

/**
 * //是否强制将SELECT参数转为*号的默认值，若设置为true或1,
 * 那么select里传入任何数据都将被替换成*号进行数据库查询，
 * 为精准更新缓存，默认使用true
 */
define("REDIS_CACHE_FORCE_SELECT_ALL",false);

/**
 *
 * 各配置字段定义：
 *---------------------------------------------------------------------------------------------
 *
 * key      字段：是缓存的关键字,用于缩短redis键值，
 *                若不设置所有条件都作为redis键值，
 *                若设置KEY，那么忽略where跟or_where里的所有其他关键字，
 *                如果设置，请谨慎，例如：如果设置了customer_id，那么其他条件都将被忽略，可选项
 *
 * use_db   字段：是【是否使用数据库】，可选项，如果不使用数据库，请务必配置key用于数据的存储及获取，
 *                获取时key要对应上，例如：如果配置了key为customer_id那么，查询时条件就需要包含customer_id
 *
 *---------------------------------------------------------------------------------------------
 *
 * ttl      字段：是缓存过期时间，可选项,设置0为长期缓存
 *
 *---------------------------------------------------------------------------------------------
 *
 * log      字段：否开启日志,如果开启日志将被写入/tmp/{$table_name}_date(Y-m-d).log文件里，可选项
 *
 *---------------------------------------------------------------------------------------------
 *
 * force_select_all     字段：是否强制将SELECT参数转为*号，可选项,对get_one,get_list生效
 *
 *---------------------------------------------------------------------------------------------
 *
 * update_one_keys      字段：用户更新数据后的快速清理单个缓存的关键字数据,请使用数组，如：["id","uid"]
 *                            遍历并清理非list缓存,若不配置则存在极大的性能下降问题
 *
 * update_list_keys     字段：用户更新数据后的快速清理列表缓存的关键字数据,请使用数组，如：["id","uid"],
 *                            特别需要注意，设置了本值后对应表的list数据将不全删，必须要按需求配置。例如：
 *                            如果配置了ID，那么更新了id为3的数据后list数据只会删除键值包含id:3的缓存数据
 *
 *---------------------------------------------------------------------------------------------
 *
 * host             字段：某个表独立使用某个redis服务器，优先级最高，然后是集群配置，最后是单服务器配置,
 *                        【非production环境无效】，2017-05-06新增
 *
 * port             字段：某个表独立使用redis服务器的端口,【非production环境无效】，2017-05-06新增
 *
 * auth             字段：某个表独立使用redis的认证信息,【非production环境无效】，2017-05-06新增
 *
 *---------------------------------------------------------------------------------------------
 *
 * refresh_list     字段：是否刷新列表缓存,默认是1
 *
 * primary_key      字段：主键字段，如果设置，get_one后将会存储一个key为:[表名:$primary_key:主键值]的缓存
 *                        这个键值是会在update_one跟update_list后必更新的键，与key配置不冲突，2017-05-9新增
 *
 * primary_pre_read 字段：主键缓存数据的刷新规则，是否预读数据到到缓存，如果是1那么，刷新缓存的时候直接读库并覆盖到缓存
 *                        如果是0，那么刷新缓存仅仅是将主键缓存删除，当读取该缓存的时候才会读取数据库并写入，2017-05-9
 *
 * replace_list     字段：是否使用设置了主键的数据替换到列表里，默认为0，
 *                        若开启则当refresh_list设置为0时，
 *                        每次拿数据都会读取[表名:primary_key:主键值]的缓存并替换进列表数据里。2017-05-9新增
 *
 * ---------------------------------------------------------------------------------------------
 *
 *
 *************************************************************
 * 复杂的redis需求请重写befor_*及after_*函数或直接使用redis。*
 *************************************************************
 */


/**
 * 若需要对表trade_addr_linkage进行缓存，需要添加如下配置
 */
$config_redis["trade_addr_linkage"] = ["ttl"=>120,"force_select_all"=>1];
/*** 其他表的配置 ***/
$config_redis["news"] = ["ttl" =>120,"force_select_all"=>0,"log" =>0,];
$config_redis["users"] = ["ttl" =>120,"force_select_all"=>1,"log" =>0,
    "update_one_keys"=>["id","email","name","mobile"]];
$config_redis["mall_ads"] = ["ttl" =>120,"force_select_all"=>1,"log" =>0,];
$config_redis["mall_goods"] = ["ttl"=>360,"force_select_all"=>1,"log" =>0,
    "update_one_keys"=>["product_id","goods_sn_main","goods_sn"],"refresh_list"=>1,];
$config_redis["mall_supplier"] = ["ttl"=>120,"force_select_all"=>1,"log" =>0,];
$config_redis["mall_goods_main"] = ["ttl"=>360,"force_select_all"=>1,"log" =>0,
    "update_one_keys"=>["goods_id","goods_sn_main"],"refresh_list"=>0,"replace_list"=>0,"primary_key"=>"goods_id",
    "host"=>"r-wz9b44b46d3e1e34.redis.rds.aliyuncs.com","port"=>6379,"auth"=>"E45trtrkjUY7uyhj6Rfgf"];
$config_redis["mall_goods_promote"] = ["ttl"=>120,"force_select_all"=>1,"log" =>0,
    "host"=>"r-wz9b44b46d3e1e34.redis.rds.aliyuncs.com","port"=>6379,"auth"=>"E45trtrkjUY7uyhj6Rfgf"];
$config_redis["mall_goods_category"] = ["ttl"=>120,"force_select_all"=>1,"log" =>0,
    "update_one_keys"=>["cate_id","cate_sn"],"refresh_list"=>1,
    "host"=>"r-wz9b44b46d3e1e34.redis.rds.aliyuncs.com","port"=>6379,"auth"=>"E45trtrkjUY7uyhj6Rfgf"];
$config_redis["mall_goods_sale_country"] = ["ttl"=>120,"force_select_all"=>1,"log" =>0,
    "host"=>"r-wz9b44b46d3e1e34.redis.rds.aliyuncs.com","port"=>6379,"auth"=>"E45trtrkjUY7uyhj6Rfgf"];
$config_redis["mall_goods_brand"] = ["ttl"=>600,];
$config_redis["mall_goods_comments"] = ["ttl"=>600,"log"=>0,];
$config_redis["mall_goods_detail_img"] = ["ttl"=>600,"log"=>0,];
$config_redis["mall_goods_gallery"] = ["ttl"=>600,"log"=>0,];
$config_redis["mall_goods_main_detail"] = ["ttl"=>600,"log"=>0,];
$config_redis["trade_freight"] = ["ttl" =>120,"force_select_all"=>1,"log" =>0];
//$config_redis["trade_user_address"] = ["ttl"=>120,"force_select_all"=>1,"log"=>1,
//    "update_one_keys"=>["id","uid"],"update_list_keys"=>["id","uid"],
//    "primary_key"=>'id',"refresh_list"=>1,"replace_list"=>0,"primary_pre_read"=>0,
//    "host"=>"r-wz9b44b46d3e1e34.redis.rds.aliyuncs.com","port"=>6379,"auth"=>"E45trtrkjUY7uyhj6Rfgf"];

/**
 * 若需要配置以mall开头的键的存储服务器，可以这样配置
 */
$config_redis["mall"] = ["host"=>"r-wz9b44b46d3e1e34.redis.rds.aliyuncs.com","port"=>6379,"auth"=>"E45trtrkjUY7uyhj6Rfgf"];
$config_redis["users_child_group_info"] = ["host"=>"r-wz9b44b46d3e1e34.redis.rds.aliyuncs.com","port"=>6379,"auth"=>"E45trtrkjUY7uyhj6Rfgf"];

/**
 * 将各配置统一在config_redis配置键值下
 */
$config["config_redis"] = $config_redis;

/**
 * 分布式redis的服务器节点列表配置.
 *
 * 若添加本配置，那么REDIS_HOST,REDIS_PORT将无效.
 * 若添加了本配置，但不添加服务器，那么将使用REDIS_HOST,REDIS_PORT的配置.
 * 配置数量无上限.
 * 若需要增加redis节点，请参考config_redis_server_add节点的配置步骤：
 */
$config["config_redis_server_set"] = empty(config_item('redis')['config_redis_server_set'])?[]:config_item('redis')['config_redis_server_set'];

/**
 * 需要添加的redis服务器节点的配置。
 *
 * 1.直接添加配置到本节点.
 * 2.数秒后，添加的节点将会获取到一定的数据。
 * 3.然后将本节点的配置数据全部移动到config_redis_server_set里。
 * 4.本节点的配置需要清空。若不清空，每600秒将严重影响访问的速度一次。
 *
 * 5.配置在这里的服务器请不要配置到待删除节点里，要不就白折腾了。
 * 6.600秒内添加过的服务器节点，如果被删除了，需要600秒后才生效，
 * 否则请删除redis里的migration:server_add:{$host}:{$port}键后立刻生效。
 */
$config["config_redis_server_add"] = empty(config_item('redis')['config_redis_server_add'])?[]:config_item('redis')['config_redis_server_add'];

/**
 * 需要删除的redis服务器节点的配置。
 *
 * 删除redis服务器节点，请按步骤操作
 * 1.请先配置此处,数秒后，待删除的redis节点的数据将自动被清空转移。
 * 2.确保节点数据清空后，清理config_redis_server_set里的配置。
 * 3.最后清理这里的配置。
 * 4.第2跟3可以同时进行。
 *
 * 若删除节点后这里的配置不清理，会拖慢请求，节点越多越慢。
 * 这里配置的节点数据要准确，弄个无法连接的节点会严重拖慢整体的速度。
 */
$config["config_redis_server_delete"] = empty(config_item('redis')['config_redis_server_delete'])?[]:config_item('redis')['config_redis_server_delete'];
