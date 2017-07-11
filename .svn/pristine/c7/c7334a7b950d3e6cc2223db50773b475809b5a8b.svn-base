<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class add_tickets extends MY_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('tb_admin_tickets');
        $this->load->model('tb_admin_tickets_logs');
        $this->load->model('tb_admin_tickets_attach');
        $this->load->model('tb_admin_tickets_template');
        $this->load->model('tb_admin_tickets_record');
    }

    public function index(){
        $this->_viewData['title'] = lang('add_tickets');
        $pro_type =config_item('tickets_problem_type');
        $this->_viewData['pro_type'] = $pro_type;
        parent::index('admin/','add_tickets');


//        $this->load->model('m_oss_api');
        //$arr = array('endpoint'=>2);
        //$this->m_oss_api->dynamic_set_oss_cfg($arr);

//        $options = array(
//            'delimiter' => '',
//            'prefix' => 'upload/temp/export_excel/198/20170424/',
//            'max-keys' => '',
//            'marker' => '',
//        );

//        $list = $this->m_oss_api->listAllObjects($options);
//
        //var_dump($list);

//        $objName = 'upload/temp/201704/e882a90b34ca12b07215e76ade9fac18.jpg';
//        $result = $this->m_oss_api->doDeleteObject($objName);
//        var_dump($result);
    }

    public function get_template(){
        if($this->input->is_ajax_request()){
            $data = $this->input->post(NULL,TRUE);
            if(is_numeric($data['t'])){
                $arr = $this->tb_admin_tickets_template->get_template($this->_adminInfo['id'],$type=$data['t']);
                foreach($arr as &$v){
                    $v['content'] = str_replace(array('&nbsp;','<br>','<br>'),array(" ","\n","\r\n"),$v['content']);
                }
                die(json_encode(array('success' => 1,'msg'=>$arr,)));
            }
        }
    }

    public function do_add_tickets(){

        if($this->input->is_ajax_request()){

            $data = $this->input->post(NULL,TRUE);

            if(!isset($data['type']) || $data['type'] == ''){
                die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_type') )));
            }

            if(!isset($data['title']) || $data['title'] == ''|| mb_strlen($data['title'],'utf8')>100){
                if(mb_strlen($data['title'],'utf8')>100){
                    die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit') )));
                }
                die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_title') )));
            }

            if(!isset($data['content']) || $data['content'] == ''|| mb_strlen($data['title'],'utf8')>1500){
                if(mb_strlen($data['title'],'utf8')>1500){
                    die(json_encode(array('success' => 0, 'msg' =>lang('exceed_words_limit') )));
                }
                die(json_encode(array('success' => 0, 'msg' =>lang('pls_t_content') )));
            }

            $insert_arr = array(
                'title'             =>htmlspecialchars($data['title']),
                'content'           =>str_replace(array("  ","\n","\r\n"),array('&nbsp;&nbsp;','<br>','<br>'),htmlspecialchars($data['content'])),
                'type'              =>$data['type'],
                'status'            =>2,
                'uid'               =>$data['uid'],
                'language_id'       =>$data['language'],
                'admin_id'          => $this->_adminInfo['id'],
                'sender '           => 1,
                'last_assign_time'  =>date('Y-m-d',time()),
                'last_reply'        =>1,
                'is_attach'         =>count($data)>5?1:0,
            );


            $this->db->trans_start();//事务

            $insert_id = $this->tb_admin_tickets->add_tickets($insert_arr);


            $status_arr = array(
                'tickets_id'=>$insert_id,
                'old_data'  =>100,
                'new_data'  =>100,
                'data_type' =>0,
                'admin_id'  =>$this->_adminInfo['id'],
                'is_admin'  =>1,
            );
            $status_insert_id = $this->tb_admin_tickets_logs->add_log($status_arr);//log


            $record = array(
                'admin_id'  =>$this->_adminInfo['id'],
                'type'      =>1,
                'count'     =>1,
                'assign_time'=>date('Y-m-d'),
            );
            $this->tb_admin_tickets_record->add_record($record);


            /**附件**/
            unset($data['content']);
            unset($data['title']);
            unset($data['type']);
            unset($data['uid']);
            unset($data['language']);

            if($insert_id && $status_insert_id && !empty($data)){//发送成功且有附件

                $save_file_path = 'upload/tickets/'.date('Ym').'/';

                foreach($data as $val){

                    $temp_arr       = explode('|', urldecode($val));
                    $new_save_path  = $save_file_path.explode('/',$temp_arr[1])[3];

                    //$this->load->model('m_do_img');
                    //if($this->m_do_img->mov_img($temp_arr[1],$new_save_path)){

                    $this->load->model('m_oss_api');
                    $from_object = $temp_arr[1];
                    $to_object   = $new_save_path;
                    $mov_res     = $this->m_oss_api->copyObject(null, $from_object, null, $to_object);

                    if($mov_res){

                        $this->m_oss_api->doDeleteObject($from_object);

                        $attach_arr =array(
                            'tickets_id' => $insert_id,
                            'name'	  	 => $temp_arr[0],
                            'path_name'	 => $new_save_path,
                            'extension'	 => $temp_arr[2],
                            'is_reply'	 => 0,
                        );
                        $this->tb_admin_tickets_attach->add_attach($attach_arr);

                    }
                }

                $this->db->trans_complete();

                if($this->db->trans_status()===FALSE){

                    $msg = lang('tickets_save_fail');

                }else{

                    $msg =lang('tickets_save_success');

                }
            }elseif($insert_id && empty($data)){//发送成功没有附件

                $this->db->trans_complete();
                $msg = lang('tickets_save_success');

            }else{//没有发送成功

                $this->db->trans_complete();
                $msg = lang('tickets_save_fail');

            }

            die(json_encode(array('success' => 1, 'msg' =>$msg )));

        }
    }

}