<?php
/**
 * Created by PhpStorm.
 * User: Tico
 * Date: 2017/1/9
 * Time: 17:28
 * curl使用xml传输数据
 */
class doba_curl
{
    //doba接口配置信息
    private $doba_url='https://www.doba.com/api/20110301/xml_retailer_api.php';
    private $doba_account='richard@goventura.com';
    private $doba_password='2587Yef831';
    private $doba_id='5076889';

    //请求日志文件
    private $doba_log_enable = true;//是否打开日志
    private $doba_log_file = "/tmp/doba_curl_request.log";
    private $curl_time_out = 120;

    //缓存目录的根目录
    private $cache_dir = "/tmp/doba/";
    private $cache_time = 86400;//单位（秒）


    function __construct() {
        if(ENVIRONMENT != "production")
        {
            $this->doba_url = "https://www.doba.com/api/20110301/xml_retailer_api.php";
        }
        $this->doba_log_file = "/tmp/doba_curl_request_".date("Y-m-d").".log";
    }

    /**
     * 通过CURL请求接口
     * @param $URL
     * @param $strRequest
     * @return mixed
     */
    private function curl_request($URL,$strRequest)
    {
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, $URL );
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($connection, CURLOPT_POST, 1);
        curl_setopt($connection, CURLOPT_POSTFIELDS, $strRequest);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        set_time_limit($this->curl_time_out);


        $strResponse = curl_exec($connection);
        if($this->doba_log_enable)
        {
            @file_put_contents($this->doba_log_file,date("Y-m-d h:i:s",time()).":Request URL: " . $URL."\n",FILE_APPEND);
        }
        if(curl_errno($connection)) {
            if($this->doba_log_enable) {
                @file_put_contents($this->doba_log_file, date("Y-m-d h:i:s", time()) . ":Curl error: " . curl_error($connection) . "\n", FILE_APPEND);
            }
        } else {
            $info = curl_getinfo($connection);
            if($this->doba_log_enable) {
                @file_put_contents($this->doba_log_file, date("Y-m-d h:i:s", time()) . ":HTTP Response Code = " . $info["http_code"] . "\n", FILE_APPEND);
            }
        }
        curl_close($connection);
        return $strResponse;
    }

    /**
     * 调用orderLookUp接口的方法
     * @param $items,必须包含【doba_item_id，quantity】两个字段
     * @param $shipping_street
     * @param $shipping_city
     * @param $shipping_state
     * @param $shipping_postal
     * @param $shipping_country
     * @return xml
     */
    public function order_look_up($items,$shipping_street,$shipping_city,$shipping_state,$shipping_postal,$shipping_country)
    {
        $URL = $this->doba_url;
        $username = $this->doba_account;
        $password = $this->doba_password;
        $retailer_id = $this->doba_id;
        $strRequest = "
<dce>
  <request>
    <authentication>
      <username>$username</username>
      <password>$password</password>
    </authentication>
    <retailer_id>$retailer_id</retailer_id>
    <action>orderLookup</action>
    <shipping_street>$shipping_street</shipping_street>
    <shipping_city>$shipping_city</shipping_city>
    <shipping_state>$shipping_state</shipping_state>
    <shipping_postal>$shipping_postal</shipping_postal>
    <shipping_country>$shipping_country</shipping_country>
    <items>";
foreach($items as $k=>$v){
    $item_id = $v['doba_item_id'];
    $quantity = $v['quantity'];
    $strRequest .= "
        <item>
            <item_id>$item_id</item_id>
            <quantity>$quantity</quantity>
        </item>";
}
$strRequest .="
    </items>
  </request>
</dce>
";
        $response_str = $this->curl_request($URL,$strRequest);
        return $response_str;
    }

    /**
     * 取DOBA的运费
     * @param $items
     * @param $addr
     * @return float|int
     */
    public function get_shipping_fee($items,$addr)
    {
        //检查并从缓存读取
        $cache_file = $this->get_cache_file_path($items);
        if(!$cache_file)
        {
            log_message("ERROR","FILE:".__FILE__.",LINE:".__LINE__.",input data:".var_export($items,true));
            return 0;
        }
        $cache = $this->read_cache_file($this->cache_dir.$cache_file);
        if($cache)
        {
            if(isset($cache['shipping_fee']))
            {
                return $cache['shipping_fee'];
            }
        }
        //若无法读取缓存，继续进行接口请求
        if($this->doba_log_enable) {
            @file_put_contents($this->doba_log_file, date("Y-m-d h:i:s", time()) . ",doba_curl->get_shipping_fee()->start();\n", FILE_APPEND);
        }
        $shipping_fee = 0;
        $shipping_street = $addr['address_detail'];
        $shipping_city = $addr['city'];
        $shipping_state = $addr['addr_lv2'];
        $shipping_postal = $addr['zip_code'];
        $shipping_country = $addr['country_address'];
        $xml = $this->order_look_up($items,$shipping_street,$shipping_city,$shipping_state,$shipping_postal,$shipping_country);
        if(!$xml)
        {
            return $shipping_fee;
        }
        $obj_xml = simplexml_load_string($xml);
        if(!isset($obj_xml->response))
        {
            return $shipping_fee;
        }
        if("success" == $obj_xml->response->outcome){
            if(isset($obj_xml->response) and isset($obj_xml->response->drop_ship_fees)){
                $shipping_fee = (double)$shipping_fee + (double)$obj_xml->response->drop_ship_fees;
            }
            if(isset($obj_xml->response) and isset($obj_xml->response->shipping_fees)){
                $shipping_fee = (double)$shipping_fee + (double)$obj_xml->response->shipping_fees;
            }
            if($this->doba_log_enable) {
                @file_put_contents($this->doba_log_file, date("Y-m-d h:i:s", time()) . "," . json_encode($obj_xml) . "\n", FILE_APPEND);
            }
        }else{
            if($this->doba_log_enable) {
                @file_put_contents($this->doba_log_file, date("Y-m-d h:i:s", time()) . json_encode($obj_xml) . "\n", FILE_APPEND);
                @file_put_contents($this->doba_log_file, date("Y-m-d h:i:s", time()) . "address is:" . json_encode($addr) . "\n", FILE_APPEND);
            }
        }
        if($this->doba_log_enable) {
            @file_put_contents($this->doba_log_file, date("Y-m-d h:i:s", time()) . ",doba_curl->get_shipping_fee()->end();\n", FILE_APPEND);
        }
        if($shipping_fee !== 0)
        {
            $this->write_cache_file($this->cache_dir . $cache_file, ["shipping_fee" => $shipping_fee]);
        }
        return $shipping_fee;
    }
    /**
     * 根据items获取缓存文件名
     */
    public function get_cache_file_path($items)
    {
        $res = "";
        $doba_item_ids = [];
        $quantitys = [];
        foreach($items as $k=>$v)
        {
            if(isset($v['doba_item_id']) and isset($v['quantity']))
            {
                $doba_item_ids[] = $v['doba_item_id'];
                $quantitys[] = $v['quantity'];
            }
        }
        if(count($doba_item_ids))
        {
            $res = implode("_",$doba_item_ids).DIRECTORY_SEPARATOR.implode("_",$quantitys).".cache";
        }
        return $res;
    }
    /**
     * 从缓存文件读数据
     * @param $file
     * @return mixed
     */
    public function read_cache_file($file)
    {
        if($file)
        {
            if(file_exists($file))
            {
                require $file;
                if(isset($cache) and isset($cache['timestamp']))
                {
                    $span = bcsub(time(),$cache['timestamp']);
                    //如果缓存过期
                    if(bccomp($span,$this->cache_time) > 0)
                    {
                        return "";
                    }
                    //如果缓存未过期
                    return $cache;
                }
            }
        }
    }
    /**
     * 写入到缓存文件
     * @param $file
     * @param $data
     */
    public function write_cache_file($file,$data)
    {
        $data['timestamp'] = time();
        //检查目录是否存在，不存在则创建
        $tmp_path = "";
        $arr = explode(DIRECTORY_SEPARATOR,$file);
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
                mkdir($tmp_path);
            }
        }
        $res = var_export($data,true);
        @file_put_contents($file,"<?php \n".'$cache='.$res.";");
    }

}
