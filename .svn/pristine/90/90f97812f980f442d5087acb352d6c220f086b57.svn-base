<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Check_card extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_admin_helper');
        $this->load->model('m_forced_matrix');
    }

    public function index($id = NULL) {

        $this->_viewData['title'] = lang('check_card');

        $searchData = $this->input->get() ? $this->input->get() : array();
        $searchData['page'] = max((int) (isset($searchData['page']) ? $searchData['page'] : 1), 1);
        $searchData['idEmail'] = isset($searchData['idEmail']) ? $searchData['idEmail'] : '';
        $searchData['start'] = isset($searchData['start']) ? $searchData['start'] : '';
        $searchData['end'] = isset($searchData['end']) ? $searchData['end'] : '';
        $searchData['check_start'] = isset($searchData['check_start']) ? $searchData['check_start'] : '';
        $searchData['check_end'] = isset($searchData['check_end']) ? $searchData['check_end'] : '';
        $searchData['id_card_num'] = isset($searchData['id_card_num']) ? $searchData['id_card_num'] : '';
        $searchData['check_status'] = isset($searchData['check_status']) ? $searchData['check_status'] : 1;
        $searchData['admin_id'] = isset($searchData['admin_id']) ? $searchData['admin_id'] : '';
        $searchData['country_id'] = isset($searchData['country_id']) ? $searchData['country_id'] : '';

		$data = $this->m_admin_helper->getCardList($searchData);
		foreach($data as &$item){
			$name = $this->db->select('name')->where('id',$item['uid'])->get('users')->row_array();
			$item['name'] = isset($name['name'])?$name['name']:'';
		}
        $this->_viewData['list'] = $data;

        foreach(config_item('countrys_and_areas') as $k=>$temp){
             if(in_array($k,array(1,2,3,4,0))){
                $country_list[$k] = $temp;
             }
         }
        $this->_viewData['country_list'] = $country_list;

        $this->load->library('pagination');
        $url = 'admin/check_card';
        add_params_to_url($url, $searchData);
        $config['base_url'] = base_url($url);
        $config['total_rows'] = $this->m_admin_helper->getCardListRows($searchData);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] = $this->pagination->create_links(true);
        $this->_viewData['searchData'] = $searchData;

        $this->load->model('m_admin_user');
        $customers = $this->m_admin_user->get_customers();
        $this->_viewData['cus'] = $customers;

        parent::index('admin/');
    }

    public function approve() {
        $msg = '';
        $this->load->model('m_forced_matrix');
        if($this->input->is_ajax_request()){
            $result = $this->m_admin_helper->getCardOne($this->input->post('uid'));
            if($result['check_status'] == 2 ){
                $success = 0;
                $msg = 'It has been processed.';
                echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
            }
            $uid  = $this->input->post('uid');
            $info = array(
                'check_status'=>2,
                'check_admin'=>$this->_adminInfo['email'],
                'check_admin_id'=>$this->_adminInfo['id'],
                'check_time'=>time(),
                'check_info'=>'',
            );
            $affected = $this->m_admin_helper->updateCheckCardStatus($uid,$info);
            if($affected){
                $success = 1;
                $this->load->model('m_user');
                $this->m_user->addInfoToWohaoSyncQueue($uid,array(0));
                /**如果身份审核通过,且这个月销售额达到50美金，则进去138矩阵**/
                if(config_item('enter_138_new_rule')) {
                    if(time() >= strtotime('2018-11-01 00:00:00')){
                        $order = $this->db->query("select sum(sale_amount) as sale_amount from users_store_sale_info_monthly where uid = '$uid'")->row_array();
                        if(isset($order['sale_amount']) && $order['sale_amount']>=5000) {
                            $this->m_forced_matrix->save_user_for_138($uid);
                        }
                    }else{
                        $this->m_forced_matrix->save_user_for_138($uid);
                    }
                    /*对于没有拿过138奖金的,如果这个月满足了条件，则立刻加入138合格列表*/
                    $this->m_forced_matrix->join_qualified_for_138($uid);
                }
                //删除照片
                $this->load->model('m_do_img');
                $this->m_do_img->delete($result['id_card_scan']);
                $this->m_do_img->delete($result['id_card_scan_back']);
                //清空保存的图片路径
                $this->load->model('m_user_helper');
                $this->m_user_helper->delCardImg($result['uid']);
                
            }else{
                $success = 0;
                $msg = 'Update Failure';
            }
            echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
        }
    }

    public function refuse() {
        if ($this->input->is_ajax_request()) {

            /* $result = $this->m_admin_helper->getCardOne($this->input->post('uid'));
              if($result['check_status'] == 2 ){
              $success = 0;
              $msg = 'It has been processed.';
              echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
              } */

            $uid = $this->input->post('uid');
            $result = $this->m_admin_helper->getCardOne($uid);
            $check_info = $this->input->post('info');
            $info = array(
                'check_status' => 0,
                'check_admin' => $this->_adminInfo['email'],
                'check_admin_id' => $this->_adminInfo['id'],
                'check_time' => time(),
                'check_info' => $check_info,
            );
            $affected = $this->m_admin_helper->updateCheckCardStatus($uid, $info);
            if ($affected) {
                
                //删除照片
                $this->load->model('m_do_img');
                $this->m_do_img->delete($result['id_card_scan']);
                $this->m_do_img->delete($result['id_card_scan_back']);
                //清空保存的图片路径
                $this->load->model('m_user_helper');
                $this->m_user_helper->delCardImg($result['uid']);
                
                $success = 1;
            } else {
                $success = 0;
            }
            echo json_encode(array('success' => $success));
        }
    }
}
