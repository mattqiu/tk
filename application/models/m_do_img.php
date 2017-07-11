<?php
/**
 * 图片处理类
 * @author Terry
*/
class m_do_img extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('m_oss_api');
    }

    /**
     * 上传图片
     */
    public function upload($path,$source) {
//        $ch = curl_init();
//        $data = array('act'=>'upload','path'=>$path,'source'=>'@'. $source);
//        curl_setopt($ch,CURLOPT_URL,config_item('img_server_url')."/receive.php");
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//        curl_setopt($ch,CURLOPT_POST,true);
//        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//        $result = json_decode(curl_exec($ch));
//        curl_close($ch);
//
//        return isset($result->success)?$result->success:false;


        /**替换 oss 201704**/
        $result = $this->m_oss_api->doUploadFile($path,$source);

        return  $result ? true : false;

    }
    
    /*
     * 上传图片的Base64编码 
     */
    public function uploadImgBase64($path,$imgBase64) {
        
//        $ch = curl_init();
//        $data = array('act'=>'uploadImgBase64','path'=>$path,'imgBase64'=>$imgBase64);
//        curl_setopt($ch,CURLOPT_URL,config_item('img_server_url')."/receive.php");
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//        curl_setopt($ch,CURLOPT_POST,true);
//        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//        $result = json_decode(curl_exec($ch));
//        curl_close($ch);
//        return isset($result->success)?$result->success:false;


        $result = $this->m_oss_api->doPutObject($path,$imgBase64);

        return $result ? true : false;

    }

    /**
     * 删除图片
     */
    public function delete($path) {

//        $ch = curl_init();
//        $data = array('act'=>'delete','path'=>$path);
//        curl_setopt($ch,CURLOPT_URL,config_item('img_server_url')."/receive.php");
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//        curl_setopt($ch,CURLOPT_POST,true);
//        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//        $result = json_decode(curl_exec($ch));
//        curl_close($ch);
//
//        return isset($result->success)?$result->success:false;

        $result = $this->m_oss_api->doDeleteObject($path);

        return $result ? true : false;

    }

    /**
     * 验证图片
     */
    public function reg_img($path){

//        //如果传入数据为空，跳过查询图片服务器
//        if(!$path)
//        {
//            return false;
//        }
//        $ch = curl_init();
//        $data = array('act' => 'reg_img', 'path' => $path);
//        curl_setopt($ch, CURLOPT_URL, config_item('img_server_url') . "/receive.php");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        $result = json_decode(curl_exec($ch));
//        curl_close($ch);
//        return isset($result->success) ? $result->success : false;

        $result = $this->m_oss_api->doesObjectExist($path);

        return  $result ? true : false;

    }


    /**
     * 复制图片
     */
    public function copy_img($old_path,$new_path){
//        $ch = curl_init();
//        $data = array('act' => 'copy_img', 'old_path' => $old_path, 'new_path' => $new_path);
//        curl_setopt($ch, CURLOPT_URL, config_item('img_server_url') . "/receive.php");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        $result = json_decode(curl_exec($ch));
//        curl_close($ch);
//        return isset($result->success) ? $result->success : false;

        $result = $this->m_oss_api->copyObject(null,$old_path,null, $new_path);

        return $result ? true : false;

    }

    /**移动文件*/
    public function mov_img($old_path,$new_path){
//        $ch = curl_init();
//        $data = array('act' => 'mov_img', 'old_path' => $old_path, 'new_path' => $new_path);
//        curl_setopt($ch, CURLOPT_URL, config_item('img_server_url') . "/receive.php");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        $result = json_decode(curl_exec($ch));
//        curl_close($ch);
//        return isset($result->success) ? $result->success : false;

        $copy_result = $this->m_oss_api->copyObject(null,$old_path,null, $new_path);

        if($copy_result)
        {
            $this->m_oss_api->doDeleteObject($old_path);
        }

        return $copy_result ? true : false;

    }
    
}
