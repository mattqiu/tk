<?php

/**
 * User: Able
 * Date: 2017/5/8
 * Time: 15:55
 */
class o_logistics_interface extends MY_Model
{

    function __construct() {
        parent::__construct();
    }


    /**
     * 筛选快递
     * @param $keyword
     * @param $orderNo
     */
    function searchExpress($keyword,$orderNo){

        $returnData = "";

        switch ($keyword){

            case 101: //韵达快递 1 w
                $url = "http://dev.yundasys.com:15105/join/query/json.php?partnerid=yunda&mailno=$orderNo&charset=utf8"; //测试URL
                $result = $this->curlRequest($url,"get");
                $res = json_decode($result,true);
                if($res['result']){
                    $returnData = $res['steps'];
                }else{
                    $returnData = array();
                }
                break;


            case 102: //顺丰速运 qq 不用
                $appId = "00027274";
                $appKey = "8EFC34568527805D0D12AF26ACA9CDFC";
                $url="https://open-sbox.sf-express.com/public/v1.0/security/access_token/sf_appid/$appId/sf_appkey/$appKey";  //申请token
                $data='{"head":{"transType":"301","transMessageId":"'.date("Ymd").$this->randNumber().'"}}';
                $result = $this->doCurl($url,$data);
                $data = json_decode($result,true);
                if(empty($data['body'])){
                    return "";
                }

                $url = "https://open-sbox.sf-express.com/rest/v1.0/route/query/access_token/".$data['body']['accessToken']."/sf_appid/$appId/sf_appkey/$appKey";
                $data = '{"head":{"transType":"501","transMessageId":"'.date("Ymd").$this->randNumber().'"},"body":{"trackingType":"2","trackingNumber":"'.$orderNo.'","methodType":"1"}}';

                $result = $this->doCurl($url,$data);
                $returnData = json_decode($result,true);
                break;


            case 103: //中通快递 ok
                $url="http://japi.zto.cn/gateway.do";
                $msg_type = "TRACEINTERFACE_NEW_TRACES";
                $keys = "cdd035f8d87b";
                $company_id = "9d42aebf573c4d7fb56576c93d339eea";
                $data_digest = base64_encode(md5("['$orderNo']".$keys, TRUE));
                $post_data = array(
                    "data"=>"['$orderNo']",
                    "data_digest" =>$data_digest,
                    "company_id"=>$company_id,
                    "msg_type"=>$msg_type
                );
                $result = $this->curlRequest($url,"post",$post_data);
                $data = json_decode($result,true);
                if(!empty($data['data'])){
                    $returnData = $data['data'][0]['traces'];
                }
                break;
            case 104: //天天快递

                break;
            case 105: //申通快递 ok

                $postData   = array(
                    'billcode'  => $orderNo,
                    'format'    => 'json',
                    'sort'      => 'desc',
                    'AppKey'    => 'tps138com',
                    'AppSecret' => 'ef8c86a252014ac4b533f595409b42b6',
                );

                $url        = 'http://expressapi.sto-express.cn:8000/api/Track/GetScans';

                $data       = $this->curlRequest($url,'post',$postData);

                $returnData = json_decode($data,true);

                if(isset($returnData['Data'][0]['ScanList'])){
                    $returnData = $returnData['Data'][0]['ScanList'];
                }else{
                    $returnData = array();
                }

                break;


            case 106: //圆通速递 1

                $app_key    = 'yu0g9h';
                $method     = 'yto.Marketing.WaybillTrace';
                $timestamp  = date('Y-m-d H:i:s');
                $format     = 'JSON';
                $user_id    = 'qq6688';
                $v          = '1.01';
                $param      = json_encode(array('Number'=>$orderNo));
                $Secret_ket = 'L7QmOq';
                $url        = 'http://MarketingInterface.yto.net.cn';
                $sign       = strtoupper(md5("{$Secret_ket}app_key{$app_key}format{$format}method{$method}timestamp{$timestamp}user_id{$user_id}v{$v}"));
                $sendData   = "sign={$sign}&app_key={$app_key}&format={$format}&method={$method}&timestamp={$timestamp}&user_id=$user_id&v={$v}&param=[$param]";

                $tempData   = $this->curlRequest($url,'post',$sendData);

                $tempDataArr = json_decode($tempData,true);

                if(isset($tempDataArr['message']) && $tempDataArr['status']==0){
                    $returnData = array();
                }else{
                    $returnData = $tempDataArr;
                }

                break;

            case 107: //全峰快递

                break;
            case 110: //宅急送快递

                break;
            case 111: //优速快递 1
                //$partner     = "80238798";
                $partner     = "80407677";
                $charset     = "utf-8";
                $dataType    = "json";
                $serviceName = "query_trace";
                //$key         = "cbbc2b0bd37466688e9eeed59c570ce6";
                $key         = "752eae83304e26f22c69733aff11aead";
                //$url         = "http://119.147.84.81/com.uc56.uop.main/gateway/gateway.action";
                $url         = "http://uop.uc56.com/gateway/gateway.action";
                $data        = '{"bill":{"billCode":"'.$orderNo.'","orderCode":"","queryType":"0"}}';
                $str         = "charset=".$charset."&data=".$data."&dataType=".$dataType."&partner=".$partner."&serviceName=".$serviceName.$key;
                $dataSign    = md5($str);
                $post_data   = array(
                    "partner"=>$partner,
                    "charset"=>$charset,
                    "dataType"=>$dataType,
                    "serviceName"=>$serviceName,
                    "data"=>$data,
                    "dataSign"=>$dataSign
                );
                $result = $this->curlRequest($url,"post",$post_data);
                $data = json_decode($result,true);
                if($data['response']['isSuccess'] == "T" && isset($data['response']['traceList'])){
                    $returnData = $data['response']['traceList'][0]['trace'];
                }else{
                    $returnData = array();
                }
                break;
            case 112: //德邦

                break;
            case 113: //百世汇通

                break;
            case 114: //安能物流

                break;
            case 117: //邮政快递

                break;
            case 118: //国通快递

                break;
            case 120: //快捷快递

                break;
            case 130: //百世快递

                break;
        }
        if(!empty($returnData)){

            return $returnData;

        }else{

            //快递100接口
            return array();

        }

    }

    /** 下单
     * @param $keyWords
     * @param string $data
     * @return mixed|string
     */
    public function checkOrder($keyWords,$data=''){

            $result = '';

        switch($keyWords){

            case 102:{//顺丰下单

                //获取token
                $appId = "00027274";
                $appKey = "8EFC34568527805D0D12AF26ACA9CDFC";
                $url="https://open-sbox.sf-express.com/public/v1.0/security/access_token/sf_appid/$appId/sf_appkey/$appKey";  //申请token
                $data='{"head":{"transType":"301","transMessageId":"'.date("Ymd").$this->randNumber().'"}}';
                $result = $this->doCurl($url,$data);
                $data = json_decode($result,true);
                if(empty($data['body'])){
                    return "";
                }

                //下单
                $url = "https://open-sbox.sf-express.com/rest/v1.0/order/access_token/".$data['body']['accessToken']."/sf_appid/$appId/sf_appkey/$appKey";

                $sendData = '{
                "body": {
                "addedServices": [],
                "cargoInfo": {
                    "cargo": "手机IP6s",
                    "cargoAmount": "5200",
                    "cargoCount": "4",
                    "cargoIndex": 0,
                    "cargoTotalWeight": 600,
                    "cargoUnit": "部",
                    "cargoWeight": "121",
                    "orderId": "",
                    "parcelQuantity": 1
                },
                "consigneeInfo": {
                    "address": "世界彼岸海风大厦",
                    "city": "深圳市",
                    "company": "顺丰",
                    "contact": "张三丰",
                    "country": "中国",
                    "county": "南山区",
                    "mobile": "18588413321",
                    "province": "广东省",
                    "shipperCode": "518100",
                    "tel": "0755-33915561"
                },
                "custId": "7550010173",
                "deliverInfo": {
                    "address": "神罗科技公司",
                    "city": "北京市",
                    "company": "神罗科技",
                    "contact": "李逍遥",
                    "country": "中国",
                    "county": "海淀区",
                    "mobile": "13612822894",
                    "province": "北京市",
                    "shipperCode": "787564",
                    "tel": "010-95123669"
                },
                "expressType": 1,
                "isDoCall": 1,
                "isGenBillNo": 1,
                "isGenEletricPic": 1,
                "needReturnTrackingNo": 0,
                "orderId": "OPEN201688801-001",
                "payArea": "",
                "payMethod": 1,
                "remark": "易碎物品，小心轻放",
                "sendStartTime": "2017-5-24 09:30:00"
            },
            "head": {
                "transMessageId": "201409040916141788",
                "transType": 200
            }
            }';

                $result = $this->doCurl($url,$sendData);

                $result = json_decode($result,true);

                break;
            }
            default : break;
        }

        return $result;
    }


    /**
     * CURL 请求方法
     * @param $url
     * @param $post_data
     * @param $requestType post get
     * @return mixed
     */
    private function curlRequest($url,$requestType,$post_data=""){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($requestType == "post"){
            curl_setopt($ch, CURLOPT_POST, 1);
            if(!empty($post_data)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            }
        }
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    /**
     * 报文请求
     * @param $url
     * @param $data
     * @return mixed
     */
    function doCurl($url,$data){
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,5);
        curl_setopt($ch,CURLOPT_POST, true);
        $header = $this->FormatHeader($url,$data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        $rs=curl_exec($ch);
        curl_close($ch);
        return $rs;
    }

    function FormatHeader($url,$data){
        $temp = parse_url($url);
        $query = isset($temp['query']) ? $temp['query'] : '';
        $path = isset($temp['path']) ? $temp['path'] : '/';
        $header = array (
            "POST {$path}?{$query} HTTP/1.1",
            "Host: {$temp['host']}",
            "Content-Type: application/json",
            "Content-length: ".strlen($data),
            "Connection: Close"
        );
        return $header;
    }


    private function randNumber(){
        $a = range(0,9);
        for($i=0;$i<10;$i++){
            $b[] = array_rand($a);
        }
        return join("",$b);
    }


    /**
     * 报文请求
     * @param $url
     * @param $data
     * @return mixed
     */
    function doCurl_test($url,$data){
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT,5);
        curl_setopt($ch,CURLOPT_POST, true);
        $header = $this->FormatHeader($url,$data);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        $rs=curl_exec($ch);
        curl_close($ch);
        return $rs;
    }

}