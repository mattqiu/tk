<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sponsorship extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('level');
        $this->load->model('M_generation_sales', 'm_generation_sales');
    }

    public function export(){

        $result = $this->m_generation_sales->export($this->_userInfo['id']);

        //会员等级转化多语言
        foreach($result as &$item){
            $item['user_rank'] = lang('level_'.$item['user_rank']);
            $item['country_id'] = $item['country_id'] ? lang(config_item('countrys_and_areas')[$item['country_id']]) : '';
            $item['create_time'] = date('Y-m-d',$item['create_time']);
        }

        if($result){
             $this->load->library('excel');
             $this->excel->filename = 'My_referrer_'.time();
             $field = globalization(array_keys($result[0]));
             $this->excel->make_from_array($field, $result);
        }else{
            redirect('ucenter/sponsorship');
        }
    }

    public function index(){

        $this->load->model("tb_users");
        $data =  $this->input->get();
        $page = (int)$this->input->get('page')?(int)$this->input->get('page'):1;

        $perPage = 10;
        $uid = $this->_userInfo['id'];
        $filter['start_time'] = isset($data['start']) ? trim($data['start']) : '';
        $filter['end_time'] = isset($data['end']) ? trim($data['end']) : '';
        $this->_viewData['title'] =  lang('directly');
        $this->_viewData['start_time'] = $data['start'];
        $this->_viewData['end_time'] = $data['end'];


        //获取总数
        $param = [
            "select"=>"id,name,country_id,create_time,user_rank,email, mobile,store_url",
            'where'=>[
                'parent_id'=>$uid
            ]
        ];
        $where = array('parent_id'=>$uid);

        if(isset($filter['start_time']) && $filter['start_time'] != ''){
            $where['create_time >'] = $filter['start_time'];
            $param['where']['create_time >'] = strtotime($data['start']);
        }
        if(isset($filter['end_time']) && $filter['end_time'] != ''){
            $param['where']['create_time <'] = strtotime($data['end'])+ 3600*24-1;
        }
        $list = [];
        $total_rows = $this->tb_users->get($param,true);
        if ($total_rows>0) {
            $total_page = ceil($total_rows / $perPage);
            $page = ($page > $total_page) ? $total_page : $page;

            $param['limit'] = [
                'page' => $page,
                'page_size' => $perPage
            ];
            $param['order'] = 'id desc';
            $list = $this->tb_users->get($param);
        } else {
            $page = 1;
        }
        $this->_viewData['referrer'] = $list;
        $pager = $this->tb_users->get_pager("ucenter/sponsorship", ['page' => $page, 'page_size' => $perPage,'start'=>$data['start'],'end'=>$data['end']], $total_rows, true);
        $this->_viewData['pager'] = $pager;

        parent::index();
    }

	/** test */
	public function test(){

//		$this->load->model('m_overrides');
//		$this->m_overrides->generationSalesOverrides2(1380130461,1000,44444444444);

		//$this->load->model('m_user');
		//$this->m_user->sendEmailSyncQueue();
		//$this->load->model('m_helper');
		//$this->m_helper->get_order_goods2();

	}
}
