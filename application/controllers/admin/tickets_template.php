<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class tickets_template extends MY_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('tb_admin_tickets');
        $this->load->model('tb_admin_tickets_template');
    }

    public function index(){
        $this->_viewData['title'] = lang('tickets_template');
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['tickets_template_name'] = isset($searchData['tickets_template_name'])?$searchData['tickets_template_name']:'';
        $searchData['type'] =isset($searchData['type'])?$searchData['type']:'';
        $searchData['admin_id'] = $this->_adminInfo['id'];
        $list = $this->tb_admin_tickets_template->get_template_list($searchData);
        $this->_viewData['list'] = $list;
        $url = 'admin/tickets_template';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_tickets_template->get_template_count($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;
        parent::index('admin/');
    }

    public function do_update_tickets_template(){
        if($this->input->is_ajax_request()){
            $data = $this->input->post();
            if(!empty($data)){
                $arr = array(
                    'id' =>$data['id'],
                    'name'=>$data['t_name'],
                    'content' =>str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),htmlspecialchars($data['t_content'])),
                    'type' =>$data['t_type'],
                    'status'=>$data['t_status'],
                );
                $b = $this->tb_admin_tickets_template->update_template($arr,$this->_adminInfo['id']);
                if($b){
                    die(json_encode(array('success'=>1,'msg'=>lang('update_template_success'))));
                }else{
                    die(json_encode(array('success'=>1,'msg'=>lang('update_template_fail'))));
                }
            }else{
                die(json_encode(array('success'=>1,'msg'=>lang('update_template_fail'))));
            }

        }
    }

    public function delete_tickets_template(){
        if($this->input->is_ajax_request()){
            $data = $this->input->post(NULL,TRUE);
            if(!empty($data) && is_numeric($data['id'])){
                $b = $this->tb_admin_tickets_template->delete_tickets_template($data['id']);
                if($b){
                    die(json_encode(array('success'=>1,'msg'=>lang('delete_template_success'))));
                }else{
                    die(json_encode(array('success'=>1,'msg'=>lang('delete_template_fail'))));
                }
            }else{
                die(json_encode(array('success'=>1,'msg'=>lang('delete_template_fail'))));
            }
        }
    }
    //load add tpl view
    public function add_tickets_template(){
        $this->load->view('admin/add_tickets_template');
    }
    //load update tpl view
    public function update_tickets_template($id=NULL){
        if(is_numeric($id)){
            $tpl['tpl']= $this->tb_admin_tickets_template->get_template_by_id($id);
            $tpl['tpl']['content'] = str_replace(array('&nbsp;','<br>','<br>'),array(" ","\n","\r\n"),$tpl['tpl']['content']);
            $this->load->view('admin/add_tickets_template',$tpl);
        }
    }

    public function view_tickets_template($id=NULL){
        if(is_numeric($id)){
            $tpl['tpl']= $this->tb_admin_tickets_template->get_template_by_id($id);
            $tpl['tpl']['content'] = str_replace(array('&nbsp;','<br>','<br>'),array(" ","\n","\r\n"),$tpl['tpl']['content']);
            $tpl['is_view'] = 1;
            $this->load->view('admin/add_tickets_template',$tpl);
        }
    }


    public function do_add__tickets_template(){
      if($this->input->is_ajax_request()){
          $data = $this->input->post(NULL,TRUE);
          if(!empty($data)){
              $insert_data = array(
                  'name'=>$data['t_name'],
                  'admin_id' =>$this->_adminInfo['id'],
                  'content' =>str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),htmlspecialchars($data['t_content'])),
                  'type' =>$data['t_type'],
                  'status'=>$data['t_status'],
              );
              $b = $this->tb_admin_tickets_template->add_tickets_template($insert_data);
              if($b){
                  die(json_encode(array('success'=>1,'msg'=>lang('add_template_success'))));
                }else{
                  die(json_encode(array('success'=>0,'msg'=>lang('add_template_fail'))));
              }
          }else{
              die(json_encode(array('success'=>0,'msg'=>lang('add_template_fail'))));
          }
        }

    }
}