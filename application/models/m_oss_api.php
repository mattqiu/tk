<?php
/** oss 请求接口
 * Created by andy
 * Date: 2017/3/28
 */

use OSS\OssClient;
use OSS\Core\OssException;

require_once APPPATH .'third_party/OSS/aliyun_sdk.phar';//2.2.2



class m_oss_api{

    private  $access_key_id     = NULL;
    private  $access_key_secret = NULL;
    private  $endpoint          = NULL;//OSS数据中心访问域名
    private  $bucket            = NULL;//存储空间名字
    private  $oss_client        = NULL;
    private  $isCName           = FALSE;//boolean 是否对Bucket做了域名绑定，并且Endpoint参数填写的是自己的域名
    private  $security_token    = NULL;
    private  $acl               = NULL;//存储空间权限
    private  $options           = NULL;//array(OssClient::OSS_CHECK_MD5 => true);//MD5检验，性能有所损失

    private  $result            = FALSE;//返回结果

    public function __construct(){

        $this->_init_oss_cfg();

    }

    //设置参数
    private function _init_oss_cfg(){

        $this->access_key_id        = config_item('oss_api_cfg')[0]['access_key_id'];
        $this->access_key_secret    = config_item('oss_api_cfg')[0]['access_key_secret'];
        $this->endpoint             = config_item('oss_api_cfg')[0]['endpoint'];//默认
        $this->bucket               = config_item('oss_api_cfg')[0]['bucket'];//默认
        $this->isCName              = config_item('oss_api_cfg')[0]['is_c_ame'];//默认
        $this->acl                  = config_item('oss_api_cfg')[0]['acl'];//默认

        $this->_getOssClient();
    }

    /** 不使用默认配置，根据实际情况动态指定参数，必须在配置文件配置->强制
     *  参数出错 或者 参数为空，则使用默认值
     * @param null $cfg_index  可选  n 为配置数组索引->强制
     * @author andy
     */
    public function dynamic_set_oss_cfg($cfg_index = 0){

        if($cfg_index !=0 && isset(config_item('oss_api_cfg')[$cfg_index])){

                /**设置参数**/
                foreach(config_item('oss_api_cfg')[$cfg_index] as $k=>$v){

                    switch($k){
                        case 'endpoint':{

                            $this->endpoint = config_item('oss_api_cfg')[$cfg_index]['endpoint'];
                            break;

                        }
                        case 'bucket':{

                            $this->bucket   = config_item('oss_api_cfg')[$cfg_index]['bucket'];
                            break;

                        }
                        case 'acl':{

                            $this->acl      = config_item('oss_api_cfg')[$cfg_index]['acl'];
                            break;

                        }
                        case 'is_c_ame':{

                            $this->isCName  = config_item('oss_api_cfg')[$cfg_index]['is_c_ame'];
                            break;

                        }

                        default:{

                            break;

                        }
                    }

                }

                /** 重新获取 OssClient **/
                $this->_getOssClient();

        }
    }


    /**
     * 获取OssClient
     * author andy
     * */
    private function _getOssClient(){

        try {

            $this->oss_client = new OssClient($this->access_key_id, $this->access_key_secret, $this->endpoint,$this->isCName, $this->security_token);

        } catch (OssException $e) {

            log_message('error',__CLASS__.' new OssClient error '.var_export($e->getMessage(),true),false);
            exit;

        }

    }

    public  function test(){
        //$this->getOssClient();
    }

    /** 上传普通文件
     * @param null $objName 对象的保存名字,不能以 / 或者 \ 开头
     * @param null $filePath 本地文件路径
     * @param null $options  其他参数
     * @return bool 是否成功
     * @author andy
     */
    public function doUploadFile($objName,$filePath){

        //提前检查，可以不发送网络请求
        if(empty($objName) || empty($filePath))
        {

                $this->result   =  false;

        }else{

            try{

                $this->result   =  $this->oss_client->uploadFile($this->bucket, $objName, $filePath, $this->options);

            }catch (OssException $e){

                log_message('error',__CLASS__.' doUploadFile error '.var_export($e->getMessage(),true),false);
                $this->result   = false;

            }

        }

        return $this->result;

    }

    /** 上传内存中的内容
     * @param null $objName
     * @param null $content
     * @param null $options
     * @return bool
     * @author andy
     */
    public function doPutObject($objName,$content){

        if(empty($objName) || empty($content)){

                $this->result   = false;

        }else{

            try{

                $this->result   = $this->oss_client->putObject($this->bucket, $objName, $content, $this->options);

            }catch (OssException $e){

                log_message('error',__CLASS__.' doPutObject error '.var_export($e->getMessage(),true),false);

                $this->result   = false;

            }

        }

        return $this->result;

    }

    /** 删除obj
     * @param $objName
     * @return bool
     * @author andy
     */
    public function doDeleteObject($objName){

        if(empty($objName)){

                $this->result   = false;

        }else{

            try{

                $this->result   = $this->oss_client->deleteObject($this->bucket, $objName, $this->options);

            }catch (OssException $e){

                log_message('error',__CLASS__.' doDeleteObject error '.var_export($e->getMessage(),true),false);

                $this->result   = false;

            }

        }

        return $this->result;

    }


    /** 下载文件到本地
     * @param $object   对象名称
     * @param $localFile  本地文件名
     * @author andy
     */
    public function getObjToLocalFile($object,$localFile){

        if(empty($object) || empty($localFile))
        {

            $this->result = false;

        }else{

            $this->options = array(
                OssClient::OSS_FILE_DOWNLOAD => $localFile,
            );

            try{

                $this->result = $this->oss_client->getObject($this->bucket, $object, $this->options);

            } catch(OssException $e) {

                log_message('error',__CLASS__.' getObjToLocalFile error '.var_export($e->getMessage(),true),false);
                $this->result = false;

            }

        }

        return $this->result;

    }

    /** 下载文件到内存
     * @param $object 对象名字
     * @return bool
     */
    public function getObject($object){

        if(empty($object))
        {

            $this->result = false;

        }else{

            try{

                $this->result =  $this->oss_client->getObject($this->bucket, $object);

            } catch(OssException $e) {

                log_message('error',__CLASS__.' getObject error '.var_export($e->getMessage(),true),false);
                $this->result = false;

            }

        }

        return $this->result;

    }

    /** 判断文件是否存在
     * @param $object
     * @return bool
     * @author andy
     */
    public function doesObjectExist($object){

        if(empty($object))
        {

                $this->result = false;

        }else{

            try{

                $this->result = $this->oss_client->doesObjectExist($this->bucket, $object);

            } catch(OssException $e) {

                log_message('error',__CLASS__.' doesObjectExist error '.var_export($e->getMessage(),true),false);
                $this->result = false;

            }

        }

        return $this->result;

    }

    /** 拷贝对象 可以拷贝小于1GB的文件
     * @param $from_bucket 源bucket  默认配置 需要设置请在配置文件找
     * @param $from_object 源obj
     * @param $to_bucket 目标bucket  默认配置
     * @param $to_object 目标obj
     * @return bool
     */
    public function copyObject($from_bucket=null, $from_object, $to_bucket=null, $to_object){

        if(empty($from_bucket) || empty($to_bucket)){
            //设置默认值
            $from_bucket = $this->bucket;
            $to_bucket   = $this->bucket;

        }

        if(empty($from_object) || empty($to_object))
        {
            //不能为空
            $this->result =  false;

        }else {

            try{

                $this->result = $this->oss_client->copyObject($from_bucket, $from_object, $to_bucket, $to_object);

            } catch(OssException $e) {

                log_message('error',__CLASS__.' copyObject error '.var_export($e->getMessage(),true),false);

                $this->result = false;

            }
        }

        return $this->result;

    }

    /** 获取列表
     * $options = array(
     *      'max-keys'  => max-keys用于限定此次返回object的最大数，如果不设定，默认为100，max-keys取值不能大于1000。
     *      'prefix'    => 限定返回的object key必须以prefix作为前缀。注意使用prefix查询时，返回的key中仍会包含prefix。
     *      'delimiter' => 是一个用于对Object名字进行分组的字符。所有名字包含指定的前缀且第一次出现delimiter字符之间的object作为一组元素
     *      'marker'    => 用户设定结果从marker之后按字母排序的第一个开始返回。
     *)
     *  其中 prefix，marker用来实现分页显示效果，参数的长度必须小于256字节。
     */

    function listAllObjects($options = NULL){

            try {

                $listObjectInfo = $this->oss_client->listObjects($this->bucket, $options);

                // 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表
                $this->result['nextMarker'] = $listObjectInfo->getNextMarker();
                $this->result['listPrefix'] = $listObjectInfo->getPrefixList();
                $this->result['listObject'] = $listObjectInfo->getObjectList();


            } catch (OssException $e) {

                log_message('error',__CLASS__.' listAllObjects error '.var_export($e->getMessage(),true),false);

                $this->result = false;

            }

            return $this->result;

    }


}
