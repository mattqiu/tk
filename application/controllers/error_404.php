<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error_404 extends MY_Controller {

	
  	public function index()
  	{
        $this->_viewData['title']=lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();

  		parent::index('mall/','','header');
  	}

    public function report_404(){

        if($this->input->is_ajax_request()){
            $this->load->model('M_error','m_error');
            $url = $this->input->post('curUrl');
            $data = array('url'=>$url);
            $count = $this->m_error->createError404($data);
            if($count){
                echo json_encode(1);
            }else{
                echo json_encode(0);
            }

        }
    }
}
