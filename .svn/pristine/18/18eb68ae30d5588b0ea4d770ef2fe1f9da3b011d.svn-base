<?php
/**
 *　广告管理
 * @date: 2015-12-18
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ads extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('m_goods');

        $this->_viewData['lang_all']=$this->m_global->getLangList();
    }

    /* 广告列表  */
    public function ads_list() {

        // 广告管理已经迁移到 ERP，请删除TPS广告管理的相关代码 carter
        return false;

        /* 条件区域  */
        $searchData = $this->input->get()?$this->input->get():array();

        if(empty($searchData['language_id'])) {
            $searchData['language_id'] = 1;
        }
        $this->_viewData['searchData'] = $searchData;

        $this->_viewData['list']=$this->m_goods->get_ads_lists($searchData['language_id']);

        $this->_viewData['title'] = lang('ads_list');
        parent::index('admin/','ads_list');
    }

    /* 增加/编辑广告 */
    public function ads_add($ads_id = NULL) {

        // 广告管理已经迁移到 ERP，请删除TPS广告管理的相关代码 carter
        return false;

        $this->_viewData['title'] = lang('ads_add');
        $this->_viewData['is_edit'] = 0;

        $data=array();
        if($ads_id){
            $data = $this->m_goods->get_ads($ads_id);

            $this->_viewData['title'] = lang('edit_ads');
            $this->_viewData['is_edit'] = 1;
        }
        $this->_viewData['data'] = $data;

        parent::index('admin/','ads_form');
    }

    /* 增加或编辑AJAX提交 */
    public function do_add(){
        $is_edit = $this->input->post('is_edit');
        $data = encodeHtml($this->input->post());

        $status=1;

        if( empty($data['ad_img']) || empty($data['ad_url']) || empty($data['location'])) {
            $status=0;
            echo json_encode(array('error'=>1,'msg'=>lang('info_failed')));
            exit;
        }

        if($status > 0) {
            unset($data['is_edit']);

            if(empty($is_edit)) {
                $status=$this->m_goods->add_ads($data);
            }else {

                $status=$this->m_goods->update_ads($data);
            }
        }

        if($status > 0) {
            echo json_encode(array('error'=>0,'msg'=>lang('info_success')));
        }else {
            echo json_encode(array('error'=>1,'msg'=>lang('info_failed')));
        }

        exit;
    }

    /* 上传广告图片  */
    public function new_pic(){
        $size=$this->input->get('size');

        $input_file_name=$this->input->get('input_name') ? $this->input->get('input_name') : 'userfile';

        $dir_path='upload/banner/';

        $this->m_global->mkDirs($dir_path);

        $config['upload_path'] = $dir_path;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '300';
        $config['max_width']  = '2500';
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

            //生产缩略图
            $size_arr=explode('*', $size);
            $conf['width'] = $size_arr[0];
            $conf['height'] = $size_arr[1];

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

    /* 删除广告图片  */
    public function del_pic() {
        $path=trim($this->input->post('path'));
        if(!empty($path) && unlink($path)) {

            $this->m_goods->imgsvr_delete($path); //同步删除图片服务器上的图片

            exit('ok');
        }

        exit(lang('info_error'));
    }
}
