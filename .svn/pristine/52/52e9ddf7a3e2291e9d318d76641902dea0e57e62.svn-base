<?php

if (!defined('BASEPATH')) {
exit('No direct script access allowed');
}

class Add_news extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_news');
    }

    public function index($id = NULL) {
    	
    	//语言列表
    	$this->_viewData['lang_all']=$this->m_global->getLangList();
    	
    	//获取新闻种类
    	$this->_viewData['type_all']=$this->m_news->get_news_type();
    	
        if($id){
            $data = $this->m_news->getOneNews($id);
            $this->_viewData['data'] = $data;
        }
        $this->_viewData['title'] = lang('add_news');
        parent::index('admin/');
    }

    public function do_add(){
        $data = $this->input->post();
        foreach($data as $key=>$item){
            switch ($key){
                case 'title':
                    if(!$data['title']){
                        $error['title'] = lang('no_title');
                    }else if(strlen($data['title']) > 200){
                        $error['title'] = 'Title too long.';
                    }
                    else{
                        $error['title'] = 0;
                    }
                    break;

               /*  case 'source':
                    if(!$data['source']){
                        $error['source'] = lang('no_source');
                    }else if(strlen($data['source']) > 30){
                        $error['source'] = 'Source too long.';
                    }
                    else{
                        $error['source'] = 0;
                    }
                    break; */
                case 'html_content':
                    if ($data['html_content'] == '<p>&nbsp; &nbsp;</p>') {
                        $error['html_content'] = lang('no_content');
                    }else{
                        $error['html_content'] = 0;
                    }
                    break;
                /* case 'img':
                    if (!$data['img']) {
                        $error['img'] = lang('no_img');
                    }else{
                        $error['img'] = 0;
                    }
                    break; */
            }

        }

        //if(isset($error['title'])&&$error['title']===0 && isset($error['source'])&&$error['source']===0 && isset($error['html_content'])&&$error['html_content']===0&& isset($error['img'])&&$error['img']===0){
        if(isset($error['title'])&&$error['title']===0  && isset($error['html_content'])&&$error['html_content']===0){
            $error_code = 0;
        }else{
            $error_code = 101;
        }
        if($error_code){
            echo json_encode(array('error_code'=>$error_code,'error'=>$error));exit;
        }


        /*if($data['type'] == 1){
            $data['content'] = mb_substr($data['content'], 0, 150, 'utf-8').'...';
        }else{
            $data['content'] = mb_substr($data['content'], 0, 50, 'utf-8').'...';
        }*/
        if($data['news_id']){
            $this->m_news->updateNews($data);
        }else{
            $this->m_news->createNews($data);
            
        }
        echo json_encode(array('success'=>1));exit;
    }

    public function news_pic(){

        $action = $this->input->get('act');
        if($action == 'del'){
            $path = $_POST['path'];
            if(file_exists($path)){
                unlink($path);
                echo '1';
            }else{
                echo '0';
            }
        }else{
            $pic_path = 'upload/news/';
            if (!is_dir($pic_path)){
                mkdir($pic_path, DIR_WRITE_MODE);
            }
            $config['upload_path'] = $pic_path;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '1024';
            $config['max_width']  = '200';
            $config['max_height']  = '200';
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
            echo json_encode($error);exit;
        }
    }
    
    /* 增加分类  */
    function add_news_type() {
    	
    	$lang_id=intval($this->input->get('lang_id'));
    	$name=trim($this->input->get('type_name'));
    
    	if(!empty($name) && $this->input->is_ajax_request()) {
    		$id=$this->m_news->add_type($name,$lang_id);
	    	if($id) {
	    		echo $id;
	    		exit;
	    	}
    	}
    	
    	exit('0');
    }

}