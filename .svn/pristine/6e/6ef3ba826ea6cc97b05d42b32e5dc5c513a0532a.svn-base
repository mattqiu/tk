<?php
/** 
 *　商品分类管理
 * @date: 2015-7-10 
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Category extends MY_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('m_goods');
	}

	/* 增加或编辑分类 */
	public function index($cate_sn = NULL) {
		$this->_viewData['title'] = lang('add_category');
		$this->_viewData['is_edit'] = 0;
		
		//获取所有分类
		$this->_viewData['category_all']=$this->m_goods->get_all_category();
		
		if($cate_sn){
			$data = $this->m_goods->get_category($cate_sn,false);
			$this->_viewData['data'] = $data;
			
			$this->_viewData['title'] = lang('edit_category');			
			$this->_viewData['is_edit'] = 1;
		}
		
		$this->_viewData['lang_list'] = $this->m_global->getLangList();
		
		$this->_viewData['cate_sn'] = random_string('alnum', 10);
		
		parent::index('admin/','category_form');
	}
	
	/* 增加或编辑AJAX提交 */
    public function do_add(){
        $is_edit = $this->input->post('is_edit');
        $data = $this->input->post('cate');

        $data_db=array();

        foreach($data as $k=>$v) {

            if(empty($v['cate_name']) || empty($v['meta_title'])) {
                unset($data[$k]);
            }

        }

        if(empty($data)) {
            echo json_encode(array('error'=>1,'msg'=>lang('info_failed')));
            exit;
        }

        if(empty($is_edit)) {
               $ret =  $this->m_goods->add_category($data);
                echo  json_encode($ret);
        } else {
                $ret = $this->m_goods->update_category($data);
                echo json_encode($ret);
        }
    }
	
	/* 分类列表  */
	public function category_list() {
		$this->_viewData['lang_all']=$this->m_global->getLangList();
		
		$this->load->library('pagination');
		
		/* 条件区域  */
		$searchData = $this->input->get()?$this->input->get():array();		
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);	
		$searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
		$this->_viewData['searchData'] = $searchData;
		
		/* 分页数据 */
		$this->_viewData['list'] = $this->m_goods->cate_level($this->m_goods->get_category_list_page($searchData));	
		$this->_viewData['title'] = lang('goods_list');
		
		$url = 'admin/category/category_list';		
		add_params_to_url($url, $searchData);
		
		/* Pager */
		$config['per_page'] = 1000;
		$config['base_url'] = base_url($url);
		$config['cur_page'] = $searchData['page'];
		$config['total_rows'] = $this->m_goods->get_category_total($searchData);			
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links(true);		
		
		parent::index('admin/','category_list');
	} 

	/* 上传分类图片  */
	public function new_pic(){
	    $input_file_name=$this->input->get('input_name') ? $this->input->get('input_name') : 'userfile';
	
	    $dir_path='upload/cate_img/';
	
	    $this->m_global->mkDirs($dir_path);
	
	    $config['upload_path'] = $dir_path;
	    $config['allowed_types'] = 'gif|jpg|png';
	    $config['max_size'] = '256';
	    //$config['max_width']  = '1500';
	    //$config['max_height']  = '1000';
	    $config['file_name']  = date('YmdHis');
	    $this->load->library('upload', $config); //加载上传类文件
	
	    $this->upload->set_xss_clean(TRUE);//开启图片进行XSS过滤 uploaded will be run through the XSS filter.
	
	    if ( ! $this->upload->do_upload($input_file_name)) {
	
	        $error = array('success'=>0,'upload_data' => $this->upload->display_errors());
	    } else {
	        $img_path=$config['upload_path'].$this->upload->file_name;
	
	        $conf['image_library'] = 'gd2';
	        $conf['source_image'] = $img_path;
	        $conf['create_thumb'] = TRUE;
	        $conf['maintain_ratio'] = TRUE;
	
            $conf['width'] = 221;
            $conf['height'] = 217;

            $this->load->library('image_lib',$conf);
            $this->image_lib->resize();
            unlink($img_path);
            $img_path_arr=explode('.', $img_path);
            $img_path=$img_path_arr[0].'_thumb.'.$img_path_arr[1];
            
            $sync=$this->m_goods->imgsvr_upload($img_path,$img_path); //同步到图片服务器
            
            $error = array('success'=>1,'path'=>$img_path,'sync'=>$sync);
	
	    }
	    echo json_encode($error);
	    exit;
	
	}
}