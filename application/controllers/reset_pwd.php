<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reset_pwd extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        $dataGet = $this->input->get();
        $this->load->model('m_user');
        $userInfo = $this->m_user->getUserByIdOrEmail($dataGet['uid']);
        
        if(!$userInfo||$dataGet['updateToken']!=$this->m_user->createToken($userInfo['update_token_time']) || (time()-$userInfo['update_token_time'])>1800){
            $disabled_status = TRUE;
        }else{
            $disabled_status = FALSE;
        }
        
        if(isset($dataGet['ajax'])&&$dataGet['ajax']=='resetPwd'){
            $success = TRUE;
            $msg = '';
            if(!$disabled_status){
                $dataPost = $this->input->post();
                $checkRes = $this->m_user->checkRegisterItems(array('pwdOriginal'=>$dataPost['newPwd'],'pwdOriginal_re'=>$dataPost['newPwdRe']));
                foreach($checkRes as $itemName=>$itemRes){
                    if(!$itemRes['isRight']){
                        $msg.='['.($itemName=='pwdOriginal'?lang('pwd_new'):lang('regi_pwd_re')).']:'.$itemRes['msg'].'<br/>';
                    }
                }
                if(!$msg){
                    $newPwdEncy = $this->m_user->pwdEncryption(trim($dataPost['newPwd']), $userInfo['token']);
                    $this->m_user->updatePwdEncy($userInfo['id'],$newPwdEncy);
                    $this->m_user->addInfoToWohaoSyncQueue($userInfo['id'],array(2));
                    $msg = lang('reset_pwd_success');
                }else{
                    $success = FALSE;
                }
            }else{
                $success = FALSE;
                $msg = lang('reset_link_fail');
            }
            echo json_encode(array('success'=>$success,'msg'=>$msg));exit;
        }
        $this->_viewData['title']=lang('reset_pwd').' - '.lang('m_title');
        $this->_viewData['keywords']=lang('m_keywords');
        $this->_viewData['description']=lang('m_description');
        $this->_viewData['canonical']=base_url();
        $this->_viewData['disabled_status'] = $disabled_status;
        parent::index('mall/','','header');
    }
    
}
