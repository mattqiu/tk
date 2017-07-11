<?php
/*
 *@desc 文件下载 
 * 多种不同文件类型 同一页面显示隐藏分页
 *@author JacksonZheng
 *@date 2017-05-15
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class file_download extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('tb_admin_ads_file_manage');
    }

    public function index() {
        
        $this->lang->load('resource_library');
        $this->load->library('pagination');
        
        $this->_viewData['title'] = lang('file_download');
        $type_arr = config_item('admin_file_type');
        $config_type = array();
        $list_item = array();
        
        $country_id =  $this->_userInfo['country_id'];
        //会员所属国家地区
        if($country_id == 2) {
            $file_area = 1;
        } else if ($country_id == 1) {
            $file_area = 2;
        } else if ($country_id == 4) {
            $file_area = 3;
        } else if ($country_id == 3) {
            $file_area = 4;
        } else {
            $file_area = 5;
        }
        
        $searchData = $this->input->get()?$this->input->get():array();
        
        foreach ($type_arr as $key =>$val) {
              
              $config_type[$key]['file_type'] = $key;   //初始默认
              $config_type[$key]['is_show'] = 1; 
              $config_type[$key]['status'] = 1;
              $config_type[$key]['page'] = 1;
              $config_type[$key]['file_area'] = $file_area;
             
              if(isset($searchData['file_type']) && $searchData['file_type'] ==$key) {
                $config_type[$key]['page'] = (int)$searchData['page'];
                $this->_viewData['select_type'] = (int)$searchData['file_type'];
                $list_item[$key]['type_show']  = true;   //控制是否显示 为真显示
              } else {
                $list_item[$key]['type_show']  = false;  //控制是否显示 为假不显示
              }
              
              if(!isset($searchData['file_type'])) {
                 $list_item[1]['type_show']  = true;   //默认显示第一个文件类型
              }
             
              $list_item[$key]['list'] = $this->tb_admin_ads_file_manage->getFileList($config_type[$key]);
             
              $url = 'ucenter/file_download';
              
              $urlData = $searchData;
              unset($urlData['file_type']);
              add_params_to_url($url, $urlData);
             
              $config_type[$key]['total_rows'] = $this->tb_admin_ads_file_manage->getFileListCount($config_type[$key]);
              unset($config_type[$key]['is_show']);
              unset($config_type[$key]['status']);
              
              $config_type[$key]['cur_page'] = $config_type[$key]['page'];
              $config_type[$key]['base_url'] = base_url($url."&file_type=$key");
              $this->pagination->initialize_ucenter($config_type[$key]);
              $list_item[$key]['page'] = $this->pagination->create_links(false);

        }
        
        //echo "<pre>"; print_r($list_item); exit;
        $this->_viewData['list'] = $list_item;
      
        parent::index();
    }
    
    public function download() {
        
         $getData = $this->input->get()?$this->input->get():array();
         
         $id = (int)$getData['id'];
         $item = $this->tb_admin_ads_file_manage->getOneFile($id);
         
         $userBrowser = $_SERVER['HTTP_USER_AGENT'];

         $file_path = config_item('img_server_url').'/'.$item['file_path'];
         $down_name = $item['file_real_name'];
         
         if ( preg_match( '/MSIE/i', $userBrowser ) ) {   //IE解决下载文件名中文会乱码
              $down_name = urlencode($down_name);
              
         }
         
         $down_name = iconv('UTF-8', 'GBK//IGNORE', $down_name);
         $hfile = fopen($file_path, "rb");
         Header("Content-type: application/octet-stream");
         //Header("Content-type:text/html;charset=utf-8");
         Header("Content-Transfer-Encoding: binary");
         Header("Accept-Ranges: bytes");
         Header("Content-Length: ".filesize($file_path));
         Header("Content-Disposition: attachment; filename=\"$down_name\"");
         ob_clean();
         flush();
         while (!feof($hfile)) {
            echo fread($hfile, 32768);
         }
         fclose($hfile);
  
        
    }

}