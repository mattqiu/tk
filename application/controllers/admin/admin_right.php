<?php

class admin_right extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->right();
        $this->load->model('tb_admin_right');

    }

    private function right(){
        if(!check_right('admin_right_manage')){
            exit('no permission');
        }
    }

    public function test(){
        $this->load->model('tb_admin_right');

//        $arr = array(
//            'admin_id' =>144,
//            'right_name'=>'工单统计权限',
//            'right_key' =>'tickets_statistics_right',
//            'remark'=>'工单统计模块权限',
//            'right'=>serialize(array(1,68,144,71,62,61,103,107,116,121,120,224,275,389,385,435,219,469,255)),
//        );
//
//        $this->tb_admin_right->addRight($arr);

        //check_right('this_key_is_null');

    }

    public function index(){
        $this->_viewData['title'] = '权限管理';
        $searchData = $this->input->get()?$this->input->get():array();
        $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
        $searchData['right_name'] = isset($searchData['right_name'])?$searchData['right_name']:'';

        $list = $this->tb_admin_right->getAllRightList($searchData);
        $this->_viewData['list'] = $list;
        $url = 'admin/admin_right';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $this->load->library('pagination');
        $config['total_rows'] = $this->tb_admin_right->getAllRightCount($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        parent::index('admin/');
    }

    public function editRight(){

        $this->_viewData['title'] = '编辑权限';

        $id = $this->input->get('id');

       $row = $this->tb_admin_right->getOneRightById($id);

        $this->_viewData['row'] = $row;

        parent::index('admin/','admin_edit_right');
    }

    public function updateRight(){

        $inputData = $this->input->post(NULL,TRUE);


        $right  = explode(',',$inputData['right']);

        $right2 = array();

        if($right){
            foreach($right as $v){
                array_push($right2,(int)$v);
            }
        }

        $one = $this->tb_admin_right->getOneRightById($inputData['right_id']);

        $updateArr = array(
            'right_name'=>htmlspecialchars($inputData['right_name']),
            'remark'    =>htmlspecialchars($inputData['remark']),
            'right'     =>serialize($right2),
        );

        $logs = array(
            'type'  =>1,
            'right_id'=>$inputData['right_id'],
            'admin_id'=>$this->_adminInfo['id'],
            'old_data'=>serialize($one),
            'new_data'=>serialize($updateArr),
        );

        $this->db->trans_start();

        $this->db->insert('admin_right_log',$logs);

        $this->tb_admin_right->updateRightForArr($inputData['right_id'],$updateArr);

        $this->db->trans_complete();

        $this->tb_admin_right->redis_del('admin:tb_admin_right:getAllRight');

       die(json_encode(array('success'=>1,'msg'=>$this->db->trans_status()!==FALSE ? 'success': 'fail')));

    }

}