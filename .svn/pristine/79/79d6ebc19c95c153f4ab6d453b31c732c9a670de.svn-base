<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Do_avatar extends MY_Controller {

    public function __construct() {

        parent::__construct();
    }

    public function index(){

        $this->load->model('M_user','m_user');
        $this->_viewData['title'] = lang('upload_avatar');
        $this->_viewData['img'] = $this->_userInfo['img'] ? $this->_userInfo['img'] : 'img/default.jpg';
        parent::index();

    }

    public function avatar(){

            $action = $this->input->post('act');
            if($action == 'del'){
                $path = $_POST['path'];
                if(file_exists($path)){
                    unlink($path);
                    echo '1';
                }else{
                    echo '0';
                }
            }else{
                if (!is_dir('upload/orig/')){
                    mkdir('upload/orig/', DIR_WRITE_MODE);
                }
                $config['upload_path'] = 'upload/orig/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '1024';
                $config['max_width']  = '1024';
                $config['max_height']  = '768';
                $config['file_name']  = date('YmdHis');
                $this->load->library('upload', $config); //加载上传类文件

                $this->upload->set_xss_clean(TRUE);//开启图片进行XSS过滤 uploaded will be run through the XSS filter.

                if ( ! $this->upload->do_upload())
                {
                    $error = array('upload_data' => $this->upload->display_errors(),'success'=>0);
                } else
                {
                    $error = array('success'=>1,'path'=>$config['upload_path'].$this->upload->file_name);
                }
                echo json_encode($error);
            }
    }

    public function cropped(){

        if( $this->input->post()){
            $data = $this->input->post();
            //裁剪头像图片 的位置
            $sliceImg = croppedImg($data['x'],$data['y'],$data['w'],$data['h'],$data['src'],$this->_userInfo['id']);
            $this->load->model('M_user','m_user');
            if($this->m_user->updateImg($this->_userInfo['id'],$sliceImg)){
                echo json_encode(array('success'=>1,'img'=>$sliceImg));
                exit;
            }
            echo json_encode(array('success'=>0,'img'=>$sliceImg));
        }
    }

}

