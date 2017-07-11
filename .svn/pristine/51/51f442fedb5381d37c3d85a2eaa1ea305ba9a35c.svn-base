<?php

/**
 * 后台文件管理
 * @author andy
 */
class admin_ads_file_manage extends  MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tb_admin_ads_file_manage');
    }

    public function index(){
        $this->_viewData['title'] = lang('admin_ads_file_manage');

        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['file_type'] = isset($searchData['file_type'])?$searchData['file_type']:'';
        $searchData['file_area'] = isset($searchData['file_area'])?$searchData['file_area']:'';
        $searchData['file_name'] = isset($searchData['file_name'])?$searchData['file_name']:'';
        $searchData['start_time'] = isset($searchData['start_time'])?$searchData['start_time']:'';
        $searchData['end_time'] =isset($searchData['end_time'])?$searchData['end_time']:'';

        $this->_viewData['list'] = $this->tb_admin_ads_file_manage->getFileList($searchData);

        $url = 'admin/admin_ads_file_manage';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_ads_file_manage->getFileListCount($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    /**
     * 添加/编辑 1->添加
     * @author andy
     */
    public function add_or_update(){

        $type = $this->input->get('type') ? $this->input->get('type') : 1;

        $this->mark();

        if($type==2){

            $id     = $this->input->get('id');

            $row    = $this->tb_admin_ads_file_manage->getOneFile($id);


            $this->_viewData['title']  = lang('admin_ads_file_modify');
            $this->_viewData['is_add'] = 2;
            $this->_viewData['row']    = $row;
            parent::index('admin/','admin_ads_file_add');

        }else{

            $this->_viewData['title']  = lang('admin_ads_file_add');
            $this->_viewData['is_add'] = 1;
            parent::index('admin/','admin_ads_file_add');

        }

    }

    /**
     * 添加/编辑
     * @author andy
     */
    public function do_add_or_update(){

        $this->load->model('m_do_img');

        $data = $this->input->post();

        $errorData      = array();
        $sta            = false;

        $mark           = $this->session->userdata('mark');

        if($data){

            if($data['is_add']==1)//添加
            {
                    if($mark==$data['mark']){

                        $file_name = trim($data['file_name']);

                        if(empty($file_name)){
                            $errorData['error_code'][] = 1001;
                            $errorData['error_msg'][]  = lang('admin_file_name_empty');
                        }

                        if(empty($data['file_type'])){
                            $errorData['error_code'][] = 1002;
                            $errorData['error_msg'][]  = lang('admin_file_type_empty');
                        }

                        if(empty($data['file_area'])){
                            $errorData['error_code'][] = 1003;
                            $errorData['error_msg'][]  = lang('admin_file_area_empty');
                        }

                        if(empty($_FILES['upload_file']['name'])){
                            $errorData['error_code'][] = 1004;
                            $errorData['error_msg'][]  = lang('admin_file_empty');
                        }

                        if(mb_strlen($_FILES['upload_file']['name'],'utf8') > 100){
                            $errorData['error_code'][] = 1005;
                            $errorData['error_msg'][]  = lang('admin_file_name_limit_100');
                        }

                        $ext = strtolower(end(explode('.',$_FILES['upload_file']['name'])));


                        if(!in_array($ext,array('gif','jpg','png','txt','doc','docx','xls','xlsx','bmp','pdf','rar','zip'))){
                            $errorData['error_code'][] = 1006;
                            $errorData['error_msg'][]  = lang('not_accepted_type');
                        }

                        if($_FILES['upload_file']['size']/1024 > 1024*10){
                            $errorData['error_code'][] = 1007;
                            $errorData['error_msg'][]  = lang('admin_file_limit_10m');
                        }

                        $dir_name   = 'upload/admin_file_manage/'.date('Ym').'/';
                        $path       = $dir_name .md5(date("dHis").mt_rand(1000, 9999)).'.'.$ext;

                        if(!$this->m_do_img->upload($path,$_FILES['upload_file']['tmp_name']))
                        {
                            $errorData['error_code'][] = 1008;
                            $errorData['error_msg'][]  = lang('admin_file_upload_fail');
                        }

                        if(in_array($ext,array('gif','jpg','png','bmp'))){

                            $img_info = getimagesize($_FILES['upload_file']['tmp_name']);

                        }else{

                            $img_info[0] = 0;
                            $img_info[1] = 0;

                        }

                        if(!$errorData)
                        {
                            //保存文件
                            $insertData = array(
                                'admin_id'       => $this->_adminInfo['id'],
                                'file_type'      => $data['file_type'],
                                'file_area'      => $data['file_area'],
                                'file_name'      => $data['file_name'],
                                'file_real_name' => $_FILES['upload_file']['name'],
                                'dir_name'       => $dir_name,
                                'file_path'      => $path,
                                'file_extension' => '.'.$ext,
                                'is_show'        => $data['is_show'],
                                'size'           =>$_FILES['upload_file']['size']/1024,
                                'width'          => $img_info[0],
                                'height'         => $img_info[1],
                            );

                            $this->db->trans_start();

                            $add_id = $this->tb_admin_ads_file_manage->addOneFile($insertData);

                            $logs = array(
                                'code'      => 1,
                                'admin_id'  => $this->_adminInfo['id'],
                                'file_id'   => $add_id,
                                'old_data'  => '',
                                'new_data'  => serialize($insertData),
                            );

                            $this->tb_admin_ads_file_manage->add_file_log($logs);

                            $this->db->trans_complete();

                            $sta = $this->db->trans_status() === TRUE ? 1 : 0 ;
                        }
                    }else{

                        $errorData['error_code'][] = 1009;
                        $errorData['error_msg'][]  = lang('admin_file_submit_error');

                    }

                    $this->mark();

                    $this->_viewData['errorData'] = $errorData;
                    $this->_viewData['add_success'] = $sta ? 1 : 0;
                    $this->_viewData['title']  = lang('admin_ads_file_add');
                    $this->_viewData['is_add'] = 1;
                    parent::index('admin/','admin_ads_file_add');

            }

            if($data['is_add']==2)//更新
            {

                $id         = $data['update_id'];
                $updateData = array();
                $sta        = false;

                if($mark==$data['mark'])
                {
                    $file_name = trim($data['file_name']);
                    if(empty($file_name)){
                        $errorData['error_code'][] = 1001;
                        $errorData['error_msg'][]  = lang('admin_file_name_empty');
                    }

                    if(empty($data['file_type'])){
                        $errorData['error_code'][] = 1002;
                        $errorData['error_msg'][]  = lang('admin_file_type_empty');
                    }

                    if(empty($data['file_area'])){
                        $errorData['error_code'][] = 1003;
                        $errorData['error_msg'][]  = lang('admin_file_area_empty');
                    }

                    if(!empty($_FILES['upload_file']['name'])){

                        if(mb_strlen($_FILES['upload_file']['name'],'utf8') > 100){
                            $errorData['error_code'][] = 1004;
                            $errorData['error_msg'][]  = lang('admin_file_name_limit_100');
                        }

                        $ext = strtolower(end(explode('.',$_FILES['upload_file']['name'])));


                        if(!in_array($ext,array('gif','jpg','png','txt','doc','docx','xls','xlsx','bmp','pdf','rar','zip'))){

                            $errorData['error_code'][] = 1005;
                            $errorData['error_msg'][]  = lang('not_accepted_type');
                        }

                        if($_FILES['upload_file']['size']/1024 > 1024*10){
                            $errorData['error_code'][] = 1006;
                            $errorData['error_msg'][]  = lang('admin_file_limit_10m');
                        }

                        $dir_name   = 'upload/admin_file_manage/'.date('Ym').'/';
                        $path       = $dir_name .md5(date("dHis").mt_rand(1000, 9999)).'.'.$ext;

                        if(!$this->m_do_img->upload($path,$_FILES['upload_file']['tmp_name']))
                        {
                            $errorData['error_code'][] = 1007;
                            $errorData['error_msg'][]  = lang('admin_file_upload_fail');
                        }

                        if(in_array($ext,array('gif','jpg','png','bmp'))){

                            $img_info = getimagesize($_FILES['upload_file']['tmp_name']);

                        }else{

                            $img_info[0] = 0;
                            $img_info[1] = 0;

                        }

                        $updateData['file_real_name']   = $_FILES['upload_file']['name'];
                        $updateData['dir_name']         = $dir_name;
                        $updateData['file_path']        = $path;
                        $updateData['file_extension']   = '.'.$ext;
                        $updateData['size']             = $_FILES['upload_file']['size']/1024;
                        $updateData['width']            = $img_info[0];
                        $updateData['height']           = $img_info[1];
                    }

                    $row = $this->tb_admin_ads_file_manage->getOneFile($id);

                    //更新
                    if(!$errorData){

                        $updateData['file_type']    =  $data['file_type'];
                        $updateData['file_area']    =  $data['file_area'];
                        $updateData['file_name']    = $data['file_name'];
                        $updateData['is_show']      = $data['is_show'];

                        $this->db->trans_start();

                        $logs = array(
                            'code'      => 2,
                            'admin_id'  => $this->_adminInfo['id'],
                            'file_id'   => $id,
                            'old_data'  => serialize($row),
                            'new_data'  => serialize($updateData),
                        );

                        $this->tb_admin_ads_file_manage->add_file_log($logs);

                        $this->tb_admin_ads_file_manage->updateOneFile($id,$updateData);

                        $this->db->trans_complete();

                        $sta = $this->db->trans_status() === TRUE ? 1 : 0 ;

                    }
                }else{

                    $errorData['error_code'][] = 1008;
                    $errorData['error_msg'][]  = lang('admin_file_submit_error');

                }

                $this->mark();

                $this->_viewData['errorData'] = $errorData;
                $row = $this->tb_admin_ads_file_manage->getOneFile($id);
                $this->_viewData['update_success'] = $sta ? 1 : 0;
                $this->_viewData['title']  = lang('admin_ads_file_modify');
                $this->_viewData['is_add'] = 2;
                $this->_viewData['row']    = $row;
                parent::index('admin/','admin_ads_file_add');

            }
        }else{

        }

    }


    /** 逻辑删除一个记录
     * @author andy
     */
    public function do_delete(){

        $id = $this->input->post('id');

        $deleteDate = array(
            'status' => 0,
        );


        $this->db->trans_start();
        $this->tb_admin_ads_file_manage->updateOneFile($id,$deleteDate);

        $logs = array(
            'code'      => 3,
            'admin_id'  => $this->_adminInfo['id'],
            'file_id'   => $id,
            'old_data'  => 1,
            'new_data'  => 0,
        );

        $this->tb_admin_ads_file_manage->add_file_log($logs);

        $this->db->trans_complete();

        $success = $this->db->trans_status() === TRUE ? 1 : 0 ;

        die(json_encode(array('success'=>$success,'msg'=>$success ? lang('admin_file_delete_success') : lang('admin_file_delete_fail'))));

    }


    //设置mark
    private  function mark(){

        $rand   =  md5('TPS'.time().mt_rand(100,999));

        $this->session->set_userdata('mark',$rand);

        $this->_viewData['mark'] = $rand;

    }

}