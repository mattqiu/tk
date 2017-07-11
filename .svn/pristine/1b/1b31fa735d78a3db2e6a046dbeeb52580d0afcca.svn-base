<?php

/**
 * Created by PhpStorm.
 * User: TPS
 * Date: 2017/5/22
 * Time: 17:16
 */
class add_incentive_system_management extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model("tb_bonus_system_management");
    }

    public function index(){
        $http_language_id = $this->input->get("language_id")?$this->input->get("language_id"):'';
        $id = $this->input->get("id")?$this->input->get("id"):'';
        //语言列表
        $this->_viewData['lang_all']=$this->m_global->getLangList();

        if($id){
            $this->_viewData['data'] = $this->tb_bonus_system_management->getByIdBonus($id);
            $this->_viewData['language_id'] = $this->_viewData['data']['lang'];
            $this->_viewData['max_sort'] = $this->_viewData['data']['sort'];
        }else{
            $this->_viewData['language_id'] = 2;
            $this->_viewData['max_sort'] = $this->tb_bonus_system_management->getSort();
        }
        $this->_viewData['title'] = lang("add_reward");
        $this->_viewData['http_language_id'] = $http_language_id;
        parent::index('admin/');
    }

    public function do_add(){
        $data = $this->input->post();
        $id = $this->input->get("id");

        if(empty($data['title'])){
            echo json_encode(array('error'=>['title'=>"标题不能为空"]));exit;
        }

        if(!$id){
            $this->tb_bonus_system_management->addBonusSystem($data);
        }else{
            $this->tb_bonus_system_management->updateBouns($id,$data);
        }
        echo json_encode(array('success'=>true));exit;
    }
}