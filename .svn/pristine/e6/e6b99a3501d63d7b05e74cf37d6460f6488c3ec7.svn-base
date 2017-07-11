<?php

include_once 'Util/memberAutoloader.php';

class CardAPI {

    private static $appKey = "23582457";
    private static $appSecret = "2ea86a6079bb9a6acaca4f0acc118fa5";
    private static $host = "https://dm-51.data.aliyun.com";

    /**
     * method=POST且是非表单提交，请求示例,ocr应用实例
     */
    public function doPostString($face,$back,$appKey,$appSecret) {
        
        //域名后、query前的部分
        //$path = "/poststring";
        $path = "/rest/160601/ocr/ocr_idcard.json";
        //$request = new HttpRequest($this::$host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);
        $request = new HttpRequest($this::$host, $path, HttpMethod::POST, $appKey, $appSecret);
        //传入内容是json格式的字符串
        //$bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"base64_image_string\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\\\"side\\\":\\\"face\\\"}\"}}]}";
        $bodyContent = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"" . $face . "\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\\\"side\\\":\\\"face\\\"}\"}},{\"image\": {\"dataType\": 50,\"dataValue\": \"" .$back . "\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\\\"side\\\":\\\"back\\\"}\"}}]}";
        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);

        //设定Accept，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
        //如果是调用测试环境请设置
        //$request->setHeader(SystemHeader::X_CA_STAG, "TEST");
        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
        //$request->setHeader("b-header2", "headervalue2");
        //$request->setHeader("a-header1", "headervalue1");
        //注意：业务query部分，如果没有则无此行；请不要、不要、不要做UrlEncode处理
        //$request->setQuery("b-query2", "queryvalue2");
        //$request->setQuery("a-query1", "queryvalue1");
        //注意：业务body部分，不能设置key值，只能有value
        if (0 < strlen($bodyContent)) {
            $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
            $request->setBodyString($bodyContent);
        }

        //指定参与签名的header
        $request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);
        //$request->setSignHeader("a-header1");
        //$request->setSignHeader("b-header2");
//        print_r($request);exit;
        $response = HttpClient::execute($request);
        //echo mb_detect_encoding($response,'GBK,GB2312,UTF-8');
//		print_r($response);
        //var_dump($response);
        
        return $response->getBody();
    }

}
