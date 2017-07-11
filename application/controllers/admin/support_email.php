<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class support_email extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('tb_email');
        $this->load->model('tb_email_attach');

    }

    public function index(){
        $this->_viewData['email_status_all']=$this->config->item('support_mail_status');

        $this->_viewData['lang_all']=$this->m_global->getLangList();
        $this->load->library('pagination');

        /* 条件区域  */
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
        $this->_viewData['searchData'] = $searchData;
        /* 分页数据 */
        $this->_viewData['list'] = $this->tb_email->get_all_email_list($searchData,20);
        $this->_viewData['title'] = lang('label_support_email_list_m');

        $url = 'admin/support_email';
        add_params_to_url($url, $searchData);

        /* Pager */
        $config['per_page'] = 25;
        $config['base_url'] =base_url($url);
        $config['cur_page'] = $searchData['page'];
        $config['total_rows'] = $this->tb_email->get_email_total();
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();

        parent::index('admin/','all_email_list');
    }

    //每个工单号的处理详情页
    public function email_detail(){
        $email_id = $this->input->get('id');
        $email['detail'] = $this->tb_email->get_email_detail($email_id);
        $email['title'] = isset($email['detail'][0]['title'])?$email['detail'][0]['title']:'';
        $email['id'] = $email_id;
        //var_dump($email['detail'][0]['title']);
        $email['email_attach'] =  $this->tb_email_attach->get_email_attach($email_id);
        $this->load->view('admin/email_detail',$email);
    }

    //下载附件 ? 附件路径不正确
    public function download_attach(){
        $this->load->helper('download');
        //$path = $this->input->get('path');
        $path='upload/temp/2016.jpg';
        $name = $this->input->get('name');
        $data = @file_get_contents($path);
        force_download($name, $data);
    }

    //回复邮件
    public function reply_email(){
        $content  = $this->input->post('reply_content');
        $state    = $this->input->post('state');
        $email_id = $this->input->post('id');

        //处理文件上传
        if(!empty($_FILES['attach']['tmp_name'])){

            $config['upload_path'] = $upload_path = $this->tb_email_attach->set_attach_temp_path();
            $config['allowed_types'] = 'gif|jpg|png|txt|doc';
            $config['max_size'] = '1024000';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $config['file_name']  =$_FILES['attach']['name'];//$this->tb_email_attach->set_attach_name($_FILES['attach']['name']);
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('attach')) {//error
                //$error = $this->upload->display_errors();
                $email['error'] = '文件上传失败';
                $email['detail'] =  $this->tb_email->get_email_detail($email_id);
                $email['title'] = isset($email['detail'][0]['title'])?$email['detail'][0]['title']:'';
                $email['id'] = $email_id;
                $this->load->view('admin/email_detail',$email);
            }else {//success
                $data = $this->upload->data();
                $attach_arr = array(
                    'email_id'   => intval($email_id),
                    'name'       => $data['client_name'],
                    'path_name'  => $upload_path.$data['file_name'],
                    'type'       => 1,
                    'extension'  => substr($data['file_ext'],1),
                );
                $attach_id = $this->tb_email_attach->add_email_attach($attach_arr);
                if($attach_id){
                    //send_mail('1039129810@qq.com', 'title', $content,'', $upload_path.$config['file_name']);
                 }
                $email['detail'] =  $this->tb_email->get_email_detail($email_id);
                $email['title'] = isset($email['detail'][0]['title'])?$email['detail'][0]['title']:'';
                $email['id'] = $email_id;
                //$email['detail'][0]['from_address'];
                $this->load->view('admin/email_detail',$email);
            }
    }else{
            //不上传文件
            //send_mail('1039129810@qq.com', 'title', $content,'', '');
            $email['detail'] =  $this->tb_email->get_email_detail($email_id);
            $email['title'] = isset($email['detail'][0]['title'])?$email['detail'][0]['title']:'';
            $email['id'] = $email_id;
            $this->load->view('admin/email_detail',$email);
        }
    }

    //附件上传函数
    public function upload_email_attach(){
        //$files=$_POST['typeCode'];
        $config['upload_path'] = $upload_path = $this->tb_email_attach->set_attach_temp_path();
        $config['allowed_types'] = 'gif|jpg|png|txt|doc';
        $config['max_size'] = '1024000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['file_name']  =$this->tb_email_attach->set_attach_name($_FILES['Filedata']['name']);
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('Filedata')){
            $data = $this->upload->data();
            //$file_name = $data['file_name'];
            echo  json_encode($data);
            exit;
        }else{
            echo json_encode($error = $this->upload->display_errors());
            exit;
        }
    }

    //删除附件
    public function delete_email_attach(){
        $file_name = $this->input->post('f_name');
        $path = $this->tb_email_attach->set_attach_temp_path();
        $file_name = $path.$file_name;
        if( is_file( $file_name ) )
        {
            if( unlink($file_name) ) {
                $return =101;//  '文件删除成功';
            }else {
                $return =102;// '文件删除失败，权限不够';
            }
        }else
        {
            $return =103;// '不是有一个有效的文件';
        }
        $arr= array(
            'success'=>$return,
            'f_name'=>$file_name,
        );
        echo json_encode($arr);
        exit;
    }

    public function email_my_list(){
        $this->_viewData['category_all']=$this->m_goods->get_all_category();

        $this->_viewData['lang_all']=$this->m_global->getLangList();

        $this->load->library('pagination');

        /* 条件区域  */
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';

        $this->_viewData['searchData'] = $searchData;

        /* 分页数据 */
        //$this->_viewData['list'] = $this->m_goods->get_brand_list_page($searchData,25);
        $this->_viewData['list'] = $this->tb_email->get_all_email_list($searchData,20);
        $this->_viewData['title'] = "所有邮件";

        $url = 'admin/support_email/email_my_list';
        add_params_to_url($url, $searchData);

        /* Pager */
        $config['per_page'] = 20;
        $config['base_url'] =base_url($url);
        $config['cur_page'] = $searchData['page'];
        $config['total_rows'] = $this->tb_email->get_email_total();
        //var_dump($config['total_rows']);
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();

        parent::index('admin/','email_my_list');
    }

    public function email_no_handle_list(){
        $this->_viewData['category_all']=$this->m_goods->get_all_category();

        $this->_viewData['lang_all']=$this->m_global->getLangList();

        $this->load->library('pagination');

        /* 条件区域  */
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';

        $this->_viewData['searchData'] = $searchData;

        /* 分页数据 */
        //$this->_viewData['list'] = $this->m_goods->get_brand_list_page($searchData,25);
        $this->_viewData['list'] = $this->tb_email->get_all_email_list($searchData,20);
        $this->_viewData['title'] = "所有邮件";

        $url = 'admin/support_email/email_no_handle_list';
        add_params_to_url($url, $searchData);

        /* Pager */
        $config['per_page'] = 20;
        $config['base_url'] =base_url($url);
        $config['cur_page'] = $searchData['page'];
        $config['total_rows'] = $this->tb_email->get_email_total();
        var_dump($config['total_rows']);
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links();

        parent::index('admin/','email_no_handle_list');
    }
}