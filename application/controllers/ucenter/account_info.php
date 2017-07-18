<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account_info extends MY_Controller {

    public function __construct() {
        
        parent::__construct();
        parent::CheckPermission();
    }

    public function index(){
       
        $this->_viewData['title'] = lang('account_info');
        //$this->_userInfo用这个的话 修改完用户资料显示的还是修改之前的信息
        $user= $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);

        //m by brady.wang 用户个人中心的手机号码、身份证号码、邮箱名称部分改为星号显示 start
        //   176*****630
        if(!empty($user['mobile'])) {
            if (strlen($user['mobile']) >=7) {
                $user['mobile_encrypt'] = mobile_encrypy($user['mobile']);
            }
        }

        //br*****ng@foxmail.com
        if(!empty($user['email'])) {
            $email = $user['email'];
            $user['email_encrypt'] = email_encrypy($email);

        }

        //m by brady.wang 用户个人中心的手机号码、身份证号码、邮箱名称部分改为星号显示 end

        $this->load->model('m_user_helper');
        $card_ = $this->m_user_helper->getUserIdCard($this->_userInfo['id']);
		if(empty($card_)){
			redirect(base_url('ucenter/'));exit;
		}
        
        
        if($card_['check_status'] > 0){
            $card_['id_card_num'] = id_card_encrypy($card_['id_card_num']);
        }

        $this->_viewData['user'] = $user;
        $this->_viewData['idCard'] = $card_;

        $this->load->model('m_admin_helper');
        $no_update = FALSE;
        $log = $this->m_admin_helper->getTransferLog($this->_userInfo['id']);
        if($log && time() < strtotime($log['create_time']." +6 month" )){
            $no_update = TRUE;
        }
        $this->_viewData['no_update'] = $no_update;

        $levelInfo = array();
        switch ($this->_userInfo['user_rank']) {
            case 1:
                $levelInfo['class'] = 'diamond';
                $levelInfo['name'] = lang('member_diamond');
                break;
            case 2:
                $levelInfo['class'] = 'platinum';
                $levelInfo['name'] = lang('member_platinum');
                break;
            case 3:
                $levelInfo['class'] = 'silver';
                $levelInfo['name'] = lang('member_silver');
                break;
			case 5:
                $levelInfo['class'] = 'bronze';
                $levelInfo['name'] = lang('member_bronze');
                break;
            default:
                $levelInfo['class'] = 'free';
                $levelInfo['name'] = lang('member_free');
                break;
        }

        if($this->_userInfo['status'] != 1){
            $levelInfo['class'] = 'freeze';
        }

        $this->_viewData['levelInfo'] = $levelInfo;
        
        $storeModifyleftCounts = config_item('store_url_modify_counts_limit')-$this->_userInfo['store_url_modify_counts'];
        $memUrlModifyleftCounts = config_item('member_url_modify_counts_limit')-$this->_userInfo['member_url_modify_counts'];

        //判断该会员是否符合删除的条件，is_delete 1可删除，0不可删除
        $this->_viewData['is_delete'] = 1;
//        if(!preg_match("/^[0-9]*$/",$this->_userInfo['id']) || trim($this->_userInfo['id'])==''){
        if(!preg_match("/^138\d{7}$/",$this->_userInfo['id']) || trim($this->_userInfo['id'])==''){
            $this->_viewData['is_delete'] = 0;
        }
        $this->load->model('m_user');
        $this->load->model('m_order');
        $userInfo=$this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        //会员不存在
        if(!$userInfo){
            $this->_viewData['is_delete'] = 0;
        }
        //用户必须是免费用户并且没有分店
        $child_counts = $this->db->from('users')->where('parent_id',$this->_userInfo['id'])->count_all_results();
        if($userInfo['user_rank'] != 4 || $userInfo['month_fee_rank'] != 4 || $child_counts > 0){
            $this->_viewData['is_delete'] = 0;
        }

        //会员下单过不能删除
        $num = $this->m_order->getUserOrderNum($this->_userInfo['id']);
        $num = isset($num)?$num:0;
        if($num > 0){
            $this->_viewData['is_delete'] = 0;
        }
        //会员拿过奖金，有佣金记录的不能删除
        $this->load->model('tb_commission_logs');
        $commission = $this->tb_commission_logs->checkUserCommissionOfType($this->_userInfo['id'],'11');
        if($commission){
            $this->_viewData['is_delete'] = 0;
        }

        $this->_viewData['modify_store_url_notice'] = sprintf(lang('modify_store_url_notice'),$storeModifyleftCounts);
        $this->_viewData['modify_member_url_notice'] = sprintf(lang('modify_member_url_notice'),$memUrlModifyleftCounts);
        $this->_viewData['ewallet_email_tip'] = sprintf(lang('ewallet_email_tip'),$this->_userInfo['email']);

        //2015-6-24 modify by sky yuan 增加用户月费等级和店铺等级变化日志        
        //$this->_viewData['levelChangeLog']['monthLevel'] = $this->m_user->getUserLevelChangeLog($this->_userInfo['id']);
        $this->_viewData['levelChangeLog']['shopLevel'] = $this->m_user->getUserLevelChangeLog($this->_userInfo['id'],2);

        parent::index();
    }
    
    public function save_id_card_num(){
        $idCardNum = $this->input->post('idCardNum');
        $this->load->model('m_user');
        $this->load->model('m_admin_helper');
        $flag = $this->m_admin_helper->cardNumPassExit(trimall($idCardNum));
        if(!$flag){
            $this->m_user->saveIdCardNum($this->_userInfo['id'],trimall($idCardNum));
            echo json_encode(array('success'=>TRUE));
        }else{
            echo json_encode(array('success'=>FALSE,'msg'=>lang('uniqueCard')));
        }
    }
    
    public function saveName(){
        $curVal = $this->input->post('curVal');
        $this->load->model('m_user');
        if(!$curVal){
            $success = FALSE;
            $msg = lang('pls_input_name');
        }else{
            $success = TRUE;
            $this->m_user->saveUserName($this->_userInfo['id'],$curVal);
        }
        
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }
    
    public function saveAddr(){
        $curVal = $this->input->post('curVal');
        $this->load->model('m_user');
        if(!$curVal){
            $success = FALSE;
            $msg = lang('pls_input_addr');
        }else{
            $success = TRUE;
            $this->m_user->saveUserAddr($this->_userInfo['id'],$curVal);
            $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'],array(9));
            $msg = '';
        }
        
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }
    
    public function saveMobile(){
        $mobileVal = $this->input->post('mobileVal');
        $this->load->model('m_user');
        $error_code = $this->m_user->saveMobile($this->_userInfo['id'],$mobileVal);
        if($error_code){
            $success = FALSE;
            $msg = lang(config_item('error_code')[$error_code]);
        }else{
            $success = TRUE;
            $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'],array(7));
            $msg = '';
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }
    
    public function getEnableCash(){

        if($this->input->is_ajax_request()){

            $data = $this->input->post();
            $this->load->model('M_currency','m_currency');
            $result['month_fee'] = $this->m_currency->price_format($this->m_user->getLevelEnableAmount($this->_userInfo['id'],$this->_userInfo['user_rank']),$data['payment']);
            $result['success'] = 1;
            echo json_encode($result);
            exit;

        }else{
            $result['success'] = 0;
            echo json_encode($result);
            exit;
        }

    }
    public function checkMemAuth(){
//        if(!$this->_userInfo['id_card_num'] || !$this->_userInfo['id_card_scan']){
//            $success = FALSE;
//            $msg = lang('pls_complete_auth_info');
//        }
        echo json_encode(array('success'=>isset($success)?$success:TRUE,'msg'=>isset($msg)?$msg:''));
    }

    public function checkCard(){
        if($this->input->is_ajax_request()){
           $user =  $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
            $result['success'] = 1;
           if(!$user['id_card_num']){
              $result['success'] = 0;
              $result['item'] = 'id_card_num';
              $result['msg'] = lang('must_card');
           }
            elseif(!$user['id_card_scan']){
              $result['success'] = 0;
              $result['item'] = 'id_card_scan';
              $result['msg'] = lang('must_card_scan');
           }
           echo json_encode($result);
        }
    }

    public function editUser(){

        $data = $this->input->post();
        $result = array('msg'=>'','success'=>1);

        //加载CI黑名单过滤类库，初始化时传递的是存于配置文件中的参数
        $this->load->library('blacklist');
        $is_blocked = $this->blacklist->check_text($data['name'])->is_blocked();

        if($is_blocked){
            $result['msg'][] = lang('sensitive');
            $result['success'] = 0;
        }
        if(!is_numeric($data['mobile'])){
            $result['msg'][] = lang('mobile_error');
            $result['success'] = 0;
        }
        if (isset($data['name']) && $data['name']!='') {
            $len = strlen(trim($data['name']));
            if ($len < 3) {
                $result['msg'][] = lang('input_name_rule');
                $result['success'] = 0;
            }
        }
        if($len > 100){
            $result['msg'][] = lang('input_name_rule_100');
            $result['success'] = 0;
        }
        //如果没有id_card_num 用isset判断
        if (isset($data['id_card_num'])&&$data['id_card_num']) {
//            if ($this->_userInfo['id_card_num']) {
//                $result['msg'][] = lang('person_id_card_num_exitst');
//                $result['success'] = FALSE;
//            } else {
//                require APPPATH . 'third_party/idCartNumCheck.class.php';
//                $idCartNumCheckObj = new idCartNumCheck();
//                $checkRes = $idCartNumCheckObj->checkIdentity($data['id_card_num']);
//                if (!$checkRes) {
//                    $result['msg'][] = lang('person_id_card_num_error');
//                    $result['success'] = FALSE;
//                }
//            }
            
            require APPPATH . 'third_party/idCartNumCheck.class.php';
            $idCartNumCheckObj = new idCartNumCheck();
            $checkRes = $idCartNumCheckObj->checkIdentity($data['id_card_num']);
            if (!$checkRes) {
                $result['msg'][] = lang('person_id_card_num_error');
                $result['success'] = FALSE;
            }
        }
        
        if(isset($data['id_card_num'])&&$data['id_card_num']){
            $this->load->model('m_user_helper');
            $exist = $this->m_user_helper->findIdCardExist($this->_userInfo['id'],$data['id_card_num']);
            if($exist){
                $result['msg'][] = lang('id_card_num_exist');
                $result['success'] = FALSE;
            }
        }
        
        if($result['success']){
            if($data['id_card_num']){
                $this->load->model('m_user_helper');
                $idCard = array(
                    'uid'=>$this->_userInfo['id'],
                    'id_card_num'=>$data['id_card_num'],
                   // 'name'=>$data['name']
                );
                $this->m_user_helper->userIdCard($idCard);
            }
            unset($data['id_card_scan']);
            unset($data['id_card_scan_back']);
            $count = $this->m_user->updateUserInfo($data,$this->_userInfo['id']);
            if($count){
                $result['msg'] = lang('account_success');
            }else{
                $result['msg'] =  lang('account_error');
            }
        }
        if(is_array($result['msg'])){
           // $result['msg'] = implode('<br/>', $result['msg']);
            $all = '';
            foreach($result['msg'] as $k=>$msg){
                $all .= 1+$k.' . '.$msg .'<br>';
            }
            $result['msg'] = $all;
        }
        echo json_encode($result);
    }
    
    public function upScan() {
        set_time_limit(300);//设置上传超时时间5分钟
        if( $_FILES['id_card_scan_file']['error'] == 0 ) { //上传图片
            $picname = $_FILES['id_card_scan_file']['name'];
            $picsize = $_FILES['id_card_scan_file']['size'];
            if ($picname != "") {
                if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小 
					die(json_encode(array('success'=>0,'msg'=>lang('id_card_scan_too_large'))));
                }
                $type = strtolower(strrchr($picname, '.'));//限制上传格式
                if (!in_array($type,array('.gif','.jpg','.bmp','.jpeg','.png'))) {
					die(json_encode(array('success'=>0,'msg'=>lang('id_card_scan_type_ext_error'))));
                }
                
                $uid = $this->_userInfo['id'];
                $pathImg = 'idCardScan/'.date('Ymd').'/'.$uid.'face' .time(). $type; //待保存的图片路径
                
                /*上传图片*/
                $this->load->model('m_do_img');
                if($this->m_do_img->upload($pathImg,$_FILES['id_card_scan_file']['tmp_name'])){
                    $this->db->where('uid',$this->_userInfo['id'])->update('user_id_card_info',array('id_card_scan'=>$pathImg));
                }else{
					die(json_encode(array('success'=>0,'msg'=>lang('save_false'))));
				}
            }
            $size = round($picsize / 1024, 2); //转换成kb 
            $arr = array(
                'name' => $picname,
                'pic' => $pathImg,
                'size' => $size,
                'picUrl' => config_item('img_server_url').'/'.$pathImg
            );
            die(json_encode(array('success'=>1,'msg'=>$arr)));
        }else{
            echo '';exit;
        }
    }
    
    //阿里云接口验证身份证图片信息
    public function aliYunVerifyCard () {
        
        $info = array();
        
        if(isset($_POST['real_name'])  && !empty($_POST['real_name'])) {
            $info['name'] = $this->input->post('real_name');
        } 
        
        if(isset($_POST['id_card_num'])  && !empty($_POST['id_card_num'])) {
            $info['id_card_num'] = $this->input->post('id_card_num');
        }
        
        if(isset($_POST['taiwanFlag'])) {
            $taiwanFlag = $this->input->post('taiwanFlag');
            
        }
        
        $info['uid'] = $this->_userInfo['id'];
        
        set_time_limit(180);  //根据网络情况和图片大小实际设置上传不能超过时间3分钟
        if( ($_FILES['id_card_scan_file']['error'] == 0) && ($_FILES['id_card_scan_file_back']['error'] ==0)) {     // error 等于0表示上传图片成功，否则失败
           
    
            $face_type = $_FILES['id_card_scan_file']['type'];
            $back_type = $_FILES['id_card_scan_file_back']['type'];
            //允许的上传图片类型
            $allowed = array ('image/bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/jpg','image/PNG');
 
                //检测上传文件类型 若不是允许的图片类型则提示格式错误
                if( !in_array($face_type, $allowed) || !in_array($back_type,$allowed)) {
                    die(json_encode(array('success'=>0,'msg'=>lang('id_card_scan_type_ext_error'),'confirm'=>lang('label_attr_ok'))));
                }
  
                 $this->load->model('m_admin_helper');
                 $cardPassExit = $this->m_admin_helper->cardNumPassExit(trim($info['id_card_num']));
                 //此身份证号码已经通过审核,不能再次审核通过
                 if($cardPassExit) {
                     die(json_encode(array('success'=>0,'msg'=>lang('uniqueCard'),'confirm'=>lang('label_attr_ok'))));
                 } 
               
                //审核次数超过3次的会员处理 上传身份证图片到图片服务器由人工审核
               $this->load->model('tb_user_id_card_info');    
               $userInfo =  $this->tb_user_id_card_info->getUserIdCard($this->_userInfo['id']);
   
               if(isset($userInfo['check_times'])) {
                   $check_times = $userInfo['check_times'];
               
               if($check_times >= 3 || $taiwanFlag == 1 ) {  //超过3次保存到图片服务器 或者为台湾身份证的直接转为人工审核
                   
                   $type_arr = explode("/",$face_type);
                   $type_back_arr = explode("/", $back_type);
                   $pathImg_face = 'idCardScan/'.date('Ymd').'/'.$info['uid'].'face' . time().".".$type_arr[1]; //待保存的图片路径
                   $pathImg_back = 'idCardScan/'.date('Ymd').'/'.$info['uid'].'back' . time().".".$type_back_arr[1]; //待保存的图片路径
                   
                /*上传正面和反面图片*/
                    $this->load->model('m_do_img');
                    if($this->m_do_img->upload($pathImg_face,$_FILES['id_card_scan_file']['tmp_name']) && $this->m_do_img->upload($pathImg_back,$_FILES['id_card_scan_file_back']['tmp_name'])) {
                        //更新状态为审核中
                        $this->db->where('uid',$this->_userInfo['id'])->update('user_id_card_info',array('check_status' => 1, 'create_time'=>time(),'check_info'=>'超过三次审核未过,需人工审核,请耐心等待','id_card_scan'=>$pathImg_face,'id_card_scan_back'=>$pathImg_back ));
                    } else {
                        die(json_encode(array('success'=>0,'msg'=>lang('upload_failed'),'confirm'=>lang('label_attr_ok'))));
                    }  
                       if($taiwanFlag) {
                           die(json_encode(array('success'=>3,'msg'=>lang('check_taiwan_card'))));  //台湾身份证转人工审核
                       } else {
                           die(json_encode(array('success'=>3,'msg'=>lang('check_exceed_three'))));
                       }
                   }
               }
               
               $this->load->model('m_admin_helper');
               $ret = $this->m_admin_helper->getCardResult($_FILES,$info);
   
                if($ret === true){
                    
                    die(json_encode(array('success'=>1,'msg'=>lang('check_passed'))));  //审核成功
                    
                } else if($ret === false) {
                    
					die(json_encode(array('success'=>2,'msg'=>lang('check_failed'))));  //审核失败
                    
				} else {    // $ret ===NULL情况 阿里云接口调用次数用尽
                   
                    $this->load->model('tb_admin_notice');
                    $result = $this->tb_admin_notice->get_card_phone();
                    if($result == 0) {
                        $this->tb_admin_notice->turn_off_notice(); 
                        $arr = config_item('admin_phone_notice');   //配置文件获取手机号码与发送信息
                        $this->phone_yzm($arr['phone'],1,$arr['msg']);
                    } 
   
                    die(json_encode(array('success'=>4,'msg'=>lang('check_maintenance'),'confirm'=>lang('label_attr_ok'))));
                }
            
           
        } else {   //图片上传失败
            die(json_encode(array('success'=>0,'msg'=>lang('id_card_scan_too_large'),'confirm'=>lang('label_attr_ok'))));
        }
    }
    
    /*
     * 功能:非中国地区 人工审核身份证上传
     * 上传图片Base64字符串 优化版本
     * @author JacksonZheng
     */
    public function verifyCard() {
        set_time_limit(300);   //限制处理时间5分钟
        $uid = $this->_userInfo['id'];
        $user = $this->m_user->getUserByIdOrEmail($uid);
        $this->load->model('m_user_helper');
        $idCard = $this->m_user_helper->getUserIdCard($uid);
        $success = true;   //初始为真
        $errorMsg = array();
        if(empty($user['name'])) {
            $errorMsg['name'] = lang('pls_input_name');
            $success = false;  
        }
        
        if(empty($idCard['id_card_num'])) {
            $errorMsg['id_card_num'] = lang('pls_input_person_id_card_num');
            $success = false;
        }
        
        if(empty($idCard['id_card_scan']) && empty($idCard['id_card_scan_back'])) {
            //$errorMsg['id_card_scan'] = lang('pls_upload_id_card_scan');
            //$success = false;
        }
        
 
        $img_face =  $this->input->post('img_face');   //正面图片base64编码
        $img_back =  $this->input->post('img_back');   //反面图片base64编码
        //保存base64字符串为图片  身份证正面
        //匹配出图片的格式
        if(empty($img_face) &&  empty($img_back)) {
            $errorMsg['id_card_scan'] = lang('pls_upload_id_card_scan');
            $success = false;
        }
        
        if($success == false) {
            die(json_encode(array('success'=>$success,'msg'=>$errorMsg))); 
        }
        
        $uploadFlag = true;
        
            //证件正面上传
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_face, $result)){
              $type = $result[2];
              $face_img = base64_decode(str_replace($result[1], '', $img_face));
              $pathImg = 'idCardScan/'.date('Ymd').'/'.$uid.'face' .time(). ".".$type; //待保存的图片路径
              $this->load->model('m_do_img');
              
              if($this->m_do_img->uploadImgBase64($pathImg,$face_img) ) {
                    $this->db->where('uid',$this->_userInfo['id'])->update('user_id_card_info',array('id_card_scan'=>$pathImg));
              } else {
                    $success = false;
                    $uploadFlag = false;    //正面上传失败
                    $errorMsg['id_card_scan'] = lang('upload_failed');
              }

            }

            //证件反面上传
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_back, $result)) {
             $type = $result[2];
             $back_img = base64_decode(str_replace($result[1], '', $img_back));
             $pathImg = 'idCardScan/'.date('Ymd').'/'.$uid.'back' .time(). ".".$type; //待保存的图片路径
             
             if($uploadFlag) {    //证件正面上传失败, 反面不再上传
                $this->load->model('m_do_img');
                if($this->m_do_img->uploadImgBase64($pathImg,$back_img) ) {
                      $this->db->where('uid',$this->_userInfo['id'])->update('user_id_card_info',array('id_card_scan_back'=>$pathImg));
                }else{
                      $success = false;
                      $errorMsg['id_card_scan'] = lang('upload_failed');
                }
             }
             
           }
        
           if($success){
                $this->m_user_helper->updateIdCardStatus($uid);
            } 
            echo json_encode(array('success'=>$success,'msg'=>$errorMsg,'upload'=>$uploadFlag));
     
        
    }

    public function delImg(){
        $action = $this->input->get('act');
        if ($action == 'delimg') { //删除图片
            $filename = $this->input->post('imagename');
            $back = $this->input->get('back');
            if (!empty($filename)) {
                $this->load->model('m_user_helper');
                $affected_rows = $this->m_user_helper->delImg($this->_userInfo['id'],$back);
                if($affected_rows){
                    $this->load->model('m_do_img');
                    $this->m_do_img->delete($filename);
                }
                die(json_encode(array('success'=>1)));
            } else {
				die(json_encode(array('success'=>1)));
            }
        }
    }
    public function upScan2() {
        set_time_limit(300);//设置上传超时时间5分钟
       if($_FILES['id_card_scan_file_back']['error'] ==0) { //上传图片
            $picname = $_FILES['id_card_scan_file_back']['name'];
            $picsize = $_FILES['id_card_scan_file_back']['size'];
            if ($picname != "") {
                if ($picsize > config_item('id_card_scan_size_limit')) { //限制上传大小
					die(json_encode(array('success'=>0,'msg'=>lang('id_card_scan_too_large'))));
                }
				$type = strtolower(strrchr($picname, '.'));//限制上传格式
                if (!in_array($type,array('.gif','.jpg','.bmp','.jpeg','.png'))) {
					die(json_encode(array('success'=>0,'msg'=>lang('id_card_scan_type_ext_error'))));
                }
                
                $uid = $this->_userInfo['id'];
                $pathImg = 'idCardScan/'.date('Ymd').'/'.$uid."back" .time(). $type; //待保存的图片路径
         
                /*上传图片*/
                $this->load->model('m_do_img');
                if($this->m_do_img->upload($pathImg,$_FILES['id_card_scan_file_back']['tmp_name'])){
                    $this->db->where('uid',$this->_userInfo['id'])->update('user_id_card_info',array('id_card_scan_back'=>$pathImg));
                }else{
					die(json_encode(array('success'=>0,'msg'=>lang('save_false'))));
				}
            }
            $size = round($picsize / 1024, 2); //转换成kb
            $arr = array(
                'name' => $picname,
                'pic' => $pathImg,
                'size' => $size,
                'picUrl' => config_item('img_server_url').'/'.$pathImg
            );
		   die(json_encode(array('success'=>1,'msg'=>$arr)));
        }else{
            echo '';exit;
        }
    }

    public function modify_member_url(){
        if($this->_userInfo['member_url_modify_counts']<config_item('member_url_modify_counts_limit')){
            $success = TRUE;
            $msg='';
        }else{
            $success = false;
            $msg = lang('modify_member_url_count_end');
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }


    public function modify_store_url(){
        if($this->_userInfo['store_url_modify_counts']<config_item('store_url_modify_counts_limit')){
            $success = TRUE;
            $msg='';
        }else{
            $success = false;
            $msg = lang('modify_store_url_count_end');
        }
        echo json_encode(array('success'=>$success,'msg'=>$msg));
    }
    /**
     * 修改沃好商城网址
     */
    public function modify_store_url_submit(){
        $postData = $this->input->post();
        $store_url_prefix = strtolower(trim($postData['store_url_prefix']));
        if (!preg_match("/^[0-9a-zA-Z]{4,15}$/", $store_url_prefix)) {
            $success = FALSE;
            $msg = lang('store_url_prefix_format_error');
        }elseif(preg_match_all(config_item('url_black_words'), $store_url_prefix, $matches)){
            $success = FALSE;
            $msg = lang('have_black_word').' ['.  implode(',', current($matches)).'].';
        }else{
            $this->load->model('m_user');
            if ($this->m_user->checkStoreUrlExist($store_url_prefix,$this->_userInfo['id'])) {
                $success = FALSE;
                $msg = lang('store_url_exist');
            } elseif($this->m_user->checkStoreUrlLicit($store_url_prefix,$this->_userInfo['id'])){
                $success = false;
                $msg = lang('url_can_not_be_other_id');
            } elseif($store_url_prefix != $this->_userInfo['id'] && preg_match("/^[0-9]{4,15}$/", $store_url_prefix)){
                //网址除了自己的id或者是字母数字组合，否则无效
                $success = false;
                $msg = lang('url_not_id_exit');
            } else {
                $success = TRUE;
                if($store_url_prefix!==$this->_userInfo['store_url']){
                    $this->m_user->updateStoreUrl($store_url_prefix,$this->_userInfo['id']);
                    $userInfo = current($this->m_user->getInfo($this->_userInfo['id']));
                    $storeModifyleftCounts = config_item('store_url_modify_counts_limit')-$userInfo['store_url_modify_counts'];
                    $data = array('store_url'=>$userInfo['store_url'].'.'.config_item('wohao_host'),'storeModifyleftCounts'=>$storeModifyleftCounts);
                    $msg = lang('modify_store_url_success');
                    $this->m_user->addInfoToWohaoSyncQueue($userInfo['id'],array(10));
                }
            }
        }

        echo json_encode(array('success'=>$success,'msg'=>isset($msg)?$msg:'','data'=>isset($data)?$data:array()));
    }

    /**
     * 修改店铺网址
     */
    public function modify_member_url_submit(){
        $postData = $this->input->post();
        $member_url_prefix = strtolower(trim($postData['member_url_prefix']));
        if (!preg_match("/^[0-9a-zA-Z]{4,15}$/", $member_url_prefix)) {
            $success = FALSE;
            $msg = lang('member_url_prefix_format_error');
        }elseif(preg_match_all(config_item('url_black_words'), $member_url_prefix, $matches)){
            $success = FALSE;
            $msg = lang('have_black_word').' ['.  implode(',', current($matches)).'].';
        }else{
            $this->load->model('m_user');
            if ($this->m_user->checkMemberUrlExist($member_url_prefix,$this->_userInfo['id'])) {
                $success = FALSE;
                $msg = lang('url_exist');
            } elseif($this->m_user->checkMemberUrlLicit($member_url_prefix,$this->_userInfo['id'])){
                $success = false;
                $msg = lang('url_can_not_be_other_id');
            } elseif($member_url_prefix != $this->_userInfo['id'] && preg_match("/^[0-9]{4,15}$/", $member_url_prefix)){
                //网址除了自己的id或者是字母数字组合，否则无效
                $success = false;
                $msg = lang('url_not_id_exit');
            }else {
                $success = TRUE;
                if($member_url_prefix!==$this->_userInfo['member_url_prefix']){
                    $this->m_user->updateMemberUrl($member_url_prefix,$this->_userInfo['id']);
                    $userInfo = current($this->m_user->getInfo($this->_userInfo['id']));
                    $memUrlModifyleftCounts = config_item('member_url_modify_counts_limit')-$userInfo['member_url_modify_counts'];
                    $data = array('member_url'=>$userInfo['member_url_prefix'].'.'.get_public_domain(),'memUrlModifyleftCounts'=>$memUrlModifyleftCounts);
                    $msg = lang('modify_store_url_success');
					/**
					 * 删除旧的，生成新的二维码图片
					 */
					$path = "upload/qrcode/".$this->_userInfo['member_url_prefix'].".png";
					if(file_exists($path)){
						unlink($path);
					}
					create_qr_code($member_url_prefix);
                }
            }
        }

        echo json_encode(array('success'=>$success,'msg'=>isset($msg)?$msg:'','data'=>isset($data)?$data:array()));
    }

    public function apply_ewallet(){
        $user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
        if(!$user['name'] || !$user['address'] || !$user['mobile']){
            $result['success'] = 0;
            $result['msg'] = lang('view_complete_your_info');
            echo json_encode($result);exit;
        }
        $result['success'] = 1;
        echo json_encode($result);exit;
    }


    public function edit_account(){

        $data = $this->input->post();
        $result = array('msg'=>'','success'=>1);
        
        //敏感词列表
        $this->load->model('tb_admin_blacklist');
        $blacklist = $this->tb_admin_blacklist->get_blacklist_all();
        
        if(isset($data['name'])){
            
            $data['name'] = trim($data['name']);
			$len = strlen($data['name']);
			if ($len < 3) {
				$result['msg'] = lang('input_name_rule');
				$result['success'] = 0;
				die(json_encode($result));
			}
			if($len > 100){
				$result['msg'] = lang('input_name_rule_100');
				$result['success'] = 0;
				die(json_encode($result));
			}

            //加载CI黑名单过滤类库，初始化时传递的是存于配置文件中的参数
//            $this->load->library('blacklist');
//            $is_blocked = $this->blacklist->check_text($data['name'])->is_blocked();
//            if($is_blocked){
//                $result['msg'] = lang('sensitive');
//                $result['success'] = 0;
//				die(json_encode($result));
//            }
            $config_arr = config_item("name_sensitive");
            if($config_arr['switch'] == 1) {  //为1打开输入真实姓名敏感词开关
                if(!empty($blacklist)) {
                        foreach($blacklist as $key => $word) {
                            if (stripos($data['name'], $word) !== false)
                            {
                                $result['msg'] = lang('name_sensitive')."[".$word."].";
                                $result['success'] = 0;
                                break;
                            }
                        }
                }
            }
    
        }

		if($data['country_id'] == ''){
			$result['msg'] = lang('input_country');
			$result['success'] = 0;
			die(json_encode($result));
		}

		if(!trim($data['address'])){ 
			$result['msg'] = lang('no_address');
			$result['success'] = 0;
			die(json_encode($result));
		} else {
            if(!empty($blacklist)) {
                    $data['address'] = trim($data['address']);
                    foreach($blacklist as $key => $word) {
                        if (stripos($data['address'], $word) !== false)
                        {
                            $result['msg'] = lang('address_sensitive')."[".$word."].";
                            $result['success'] = 0;
                            break;
                        }
                    }
            }
 
        }

        if($result['success']){

            $count = $this->m_user->updateUserInfo($data,$this->_userInfo['id']);
            $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'],array(6,7,9));
            if($count){
                $this->load->model('m_user_helper');
                $idCard = array(
                    'uid'=>$this->_userInfo['id'],
                    'name'=>isset($data['name'])?$data['name']:''
                );
                $this->m_user_helper->userIdCard($idCard);
                $result['msg'] = lang('account_success');
            }else{
                $result['success'] = 0;
                $result['msg'] =  lang('account_error');
            }
        }
		die(json_encode($result));
    }
    public function complete_info(){
        if($this->input->is_ajax_request()){
            //$this->_userInfo用这个的话 修改完用户资料显示的还是修改之前的信息
            $user = $this->m_user->getUserByIdOrEmail($this->_userInfo['id']);
            $this->load->model('m_user_helper');
            $idCard = $this->m_user_helper->getUserIdCard($this->_userInfo['id']);
            $success = TRUE;
            $errorMsg = array();
            if(!$user['name']){
                $errorMsg['name'] = lang('pls_input_name');
                $success = FALSE;
            }
            if(!$idCard['id_card_num']){
                $errorMsg['id_card_num'] = lang('pls_input_person_id_card_num');
                $success = FALSE;
            }
            if(!$idCard['id_card_scan'] && !$idCard['id_card_scan_back']){
                $errorMsg['id_card_scan'] = lang('pls_upload_id_card_scan');
                $success = FALSE;
            }
            if($success){
                $this->m_user_helper->updateIdCardStatus($this->_userInfo['id']);
            }

            echo json_encode(array('success'=>$success,'msg'=>$errorMsg));
        }
    }

	/**  */
	public function modify_store_name_submit(){
		if($this->input->is_ajax_request()){
			$data['store_name'] = $this->input->post('store_name',true);
			if (preg_match("/[\x7f-\xff]/", $data['store_name'])){
				$data['store_name'] = trimall($data['store_name']);
			}
			$result = array('msg'=>'','success'=>1);
            
//			if(isset($data['store_name'])){
//				//加载CI黑名单过滤类库，初始化时传递的是存于配置文件中的参数
//				$this->load->library('blacklist');
//				$is_blocked = $this->blacklist->check_text($data['store_name'])->is_blocked();
//				if($is_blocked){
//					//$result['msg'] = lang('sensitive');
//                    $result['msg'] = lang('have_black_word')."[".$this->blacklist->_match_words."].";
//					$result['success'] = 0;
//				}
//			}
            
            if(isset($data['store_name'])){
				$this->load->model('tb_admin_blacklist');
                $blacklist = $this->tb_admin_blacklist->get_blacklist_all();
                
                if(!empty($blacklist)) {
                    foreach($blacklist as $key => $word) {
                        if (stripos($data['store_name'], $word) !== false)
                        {
                            $result['msg'] = lang('have_black_word')."[".$word."].";
                            $result['success'] = 0;
                            break;
                        }
                    }
                }
			}
            
			if (isset($data['store_name']) && $data['store_name']!='') {
				$len = strlen(trim($data['store_name']));
				if (preg_match("/[\x7f-\xff]/", $data['store_name'])){
					if ($len < 3) {
						$result['msg'] = lang('input_store_name_rule');
						$result['success'] = 0;
					}
					if($len > 36){
						$result['msg'] = lang('input_store_name_rule');
						$result['success'] = 0;
					}
				}else{
					if ($len < 3) {
						$result['msg'] = lang('input_store_name_rule');
						$result['success'] = 0;
					}
					if($len > 40){
						$result['msg'] = lang('input_store_name_rule');
						$result['success'] = 0;
					}
				}
			}
			$count = $this->db->from('users')->where('id <>',$this->_userInfo['id'])->where('store_name',$data['store_name'])->count_all_results();
			if($count){
				$result['msg'] = lang('input_store_name_exit');
				$result['success'] = 0;
			}
			if($result['success']){
				$count = $this->m_user->updateUserInfo($data,$this->_userInfo['id']);
					$result['msg'] = lang('account_success');
					$result['store_name'] = $data['store_name'];
			}
			die(json_encode($result));
		}
	}

	public function binding_user_info(){
		if($this->input->is_ajax_request()){
			$postData = $this->input->post();
			$postData['action_id'] = 4;//绑定标识
			$postData['reg_type'] = 0;//就会验证手机/邮箱的唯一性

            if(!isset($postData['email'])){
                $postData['email'] = $postData['phone'];
            }

			$this->load->model('m_user');
            $checkResult = $this->m_user->checkRegisterItems($postData);

            foreach ($checkResult as &$resultItem) {
                if (!$resultItem['isRight']) {
                    die(json_encode(array('success'=>0,'msg'=>$resultItem['msg'])));
                }
            }

			$this->load->model('tb_users');
			$this->load->model('m_user');

			$this->tb_users->binding_mobile_or_email($this->_userInfo['id'],$postData['email']);
            $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'],array(0));
			die(json_encode(array('success'=>1)));
		}
	}
    /**修改手机号**/
    public function modify_mobile_number(){
        if($this->input->is_ajax_request()){
            $postData = $this->input->post();
            $postData['action_id'] = 4;//绑定标识
            $postData['reg_type'] = 0;//就会验证手机/邮箱的唯一性

            if(!preg_match('/^1[34578]{1}\d{9}$/',$postData['phone'])){
                die(json_encode(array('success'=>0,'msg'=>lang(config_item('error_code')[1029]))));
            }
            $this->Verification_Code($postData['captcha'],$postData['phone']);//验证
            $res = $this->db->from('users')->where('id !=',$this->_userInfo['id'])->where('mobile',$postData['phone'])->get()->row_array();
            if($res){
                die(json_encode(array('success'=>0,'msg'=>lang(config_item('error_code')[1049]))));
            }
            $this->load->model('tb_users');
            $this->load->model('m_user');
            $is=$this->tb_users->binding_mobile_or_email($this->_userInfo['id'],$postData['phone']);
            $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'],array(7));
            die(json_encode(array('success'=>1,'msg'=>lang('modify_success'))));
        }
    }
    /**
     * 删除免费店铺
     * 功能: 免费账户不能直接删除，而是改为公司预留账户 释放该账户验证的邮箱，手机号码，身份证号码，解绑提现帐号
     * Ckf JacksonZheng
     * @date 20161101 20170223
     */
    public function del_shop() {
        if($this->input->is_ajax_request()) {
            $postData = $this->input->post();
            $user_id = (int)$postData['id'];//绑定标识

//            if(!preg_match("/^[0-9]*$/",$user_id) || trim($user_id)==''){
            if(!preg_match("/^138\d{7}$/",$user_id) || trim($user_id)==''){
                echo json_encode(array('success'=>false,'msg'=>lang('pls_t_correct_ID')));
                exit();
            }
            $this->load->model('m_user');
            $this->load->model('m_paypal_log',"paypal"); 
            $this->load->model('m_admin_helper');
            
            $userInfo=$this->m_user->getUserByIdOrEmail($user_id);
           
            if(!$userInfo){
                echo json_encode(array('success'=>false,'msg'=>lang('user_not_exits')));
                exit();
            }
            
            //更改用户邮箱,手机号,身份证为未验证状态 解绑支付宝或者paypal 状态更改为公司预留账户
            $user_paypal = $this->paypal->get_paypal($user_id);
            $res = $this->m_admin_helper->deleterUserLogs($user_id, $userInfo['parent_id'], 0);  //写入delete_users_logs 日志
            
            if($res>0) {
                 $this->m_admin_helper->deleteUserById($user_id, $userInfo,1);
                
            }
            
            //解绑paypal账号 
            if($this->paypal->get_paypal($user_id)) {
                
                $this->paypal->del_payapl($user_id,$user_paypal['paypal_email']);
            }

            if($res) {
                delete_cookie("userInfo",  get_public_domain());
                delete_cookie("unread_count",  get_public_domain());
                echo json_encode(array('success' => TRUE, 'msg' => lang('del_shop_success')));
                exit();
            } else {
                echo json_encode(array('success'=>false,'msg'=>lang('delete_failure')));
                exit();
                
            }

        }
    }

    /**
     * @author brady.wang
     * @desc 绑定用户手机号码
     * @param binding_mobile_phone 手机号
     * @param binding_mobile_code 验证码
     */
    public function binding_user_mobile()
    {
        $this->load->model("service_message_model");
        $this->load->model("tb_mobile_message_log");
        $this->load->model("tb_user_mobile_bind_log");
        $this->load->model('tb_users');
        $this->load->model('m_user');

        try {
            if ($this->input->is_ajax_request()) {
                $mobile = trim($this->input->post("binding_mobile_phone", true));
                $code = trim($this->input->post('binding_mobile_code', true));
                //数据验证
                //手机号码非空验证
                if (empty($mobile)) {
                    throw new Exception("10501001");
                }
                //手机号码格式验证
                if (!preg_match('/^1[34578]\d{9}$/', $mobile)) {
                    throw new Exception("10501002");
                }
                //短信验证码验证
                if (empty($code)) {
                    throw new Exception("10501003");
                }

                //验证手机号是否被使用
                $mobile_res = $this->m_user->check_mobile_exists($mobile);
                if (!empty($mobile_res)) {
                    throw new Exception("10501007");
                }
                //验证短信验证码是否正确
                $this->tb_mobile_message_log->verify_mobile_code($mobile, $code);

                //事物开始
                $this->db->trans_start();
                $affected_rows = $this->tb_users->binding_mobile_or_email($this->_userInfo['id'], $mobile);
               // $this->tb_users->unbind_mobile($this->_userInfo['id'], $mobile);
                if($affected_rows > 0) {
                    $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'], array(0));

                    //更新有该手机号的，其他用户
                    $this->load->model("tb_users");
                    $remark = $this->tb_users->update_user_mobile_batch($mobile, $this->_userInfo['id']);

                    $logs = [
                        'type' => "bind_mobile",
                        'uid'=>$this->_userInfo['id'],
                        'old_mobile' => '',
                        'new_mobile' => $mobile,
                        'create_time' => time(),
                        'remark' => substr($remark, 0, 254)
                    ];
                    $this->tb_user_mobile_bind_log->add_log($logs);
                    $this->tb_mobile_message_log->delete_code($mobile); //删除验证码
                    $this->db->trans_complete();
                    $data['message'] = lang("binding_mobile_success");
                    $this->service_message_model->success_response($data);
                } else {
                    throw new Exception("binding_mobile_failed");//绑定手机号失败
                }
            } else {
                throw new Exception("40501001");//hacker
            }
        } catch (Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }
    }

    /**
     * @author brady
     * @desc 解绑手机 验证老手机号码
     */
    public function verify_old_mobile()
    {
        $output = [];
        $this->load->model("service_message_model");
        $this->load->model("tb_mobile_message_log");
        $this->load->model('tb_users');
        $this->load->model('m_user');
        try {
            $mobile = trim($this->input->post('mobile', true));
            $code = trim($this->input->post("code", true));
            //手机号码非空验证
            if (empty($mobile)) {
                throw new Exception("10501001");
            }
            //手机号码格式验证
            if (!preg_match('/^1[34578]\d{9}$/', $mobile)) {
                throw new Exception("10501002");
            }
            //短信验证码验证
            if (empty($code)) {
                throw new Exception("10501003");
            }

            //验证短信验证码是否正确
            $this->tb_mobile_message_log->verify_mobile_code($mobile, $code);

            //验证成功 后删除验证码
            $this->tb_mobile_message_log->delete_code($mobile); //删除验证码
            $output['message'] = 'success';
            $this->service_message_model->success_response($output);

        } catch (Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }
    }

    /**
     * @author brady
     * @desc 换绑手机
     */
    public function modify_mobile_bind()
    {

        $output = [];
        $this->load->model("service_message_model");
        $this->load->model("tb_mobile_message_log");
        $this->load->model('tb_user_mobile_bind_log');
        $this->load->model('tb_users');
        $this->load->model('m_user');
        try {
            $mobile = trim($this->input->post('mobile', true));
            $old_mobile = trim($this->input->post('old_phone', true));
            $code = trim($this->input->post("code", true));
            //手机号码非空验证
            if (empty($mobile)) {
                throw new Exception("10501001");
            }
            //手机号码格式验证
            if (!preg_match('/^1[34578]\d{9}$/', $mobile)) {
                throw new Exception("10501002");
            }

            //验证手机号是否被使用
            $mobile_res = $this->m_user->check_mobile_exists($mobile);
            if (!empty($mobile_res)) {
                throw new Exception("10501007");
            }
            //短信验证码验证
            if (empty($code)) {
                throw new Exception("10501003");
            }

            //验证短信验证码是否正确
            $this->tb_mobile_message_log->verify_mobile_code($mobile, $code);
            $this->db->trans_start();
            $affected_rows = $this->tb_users->binding_mobile_or_email($this->_userInfo['id'], $mobile);
            if ($affected_rows > 0) {
                $this->m_user->addInfoToWohaoSyncQueue($this->_userInfo['id'], array(7)); //同步信息
                //日志
                $logs = [
                    'type' => "modify_bind_mobile",
                    'old_mobile' => $old_mobile,
                    'new_mobile' => $mobile,
                    'create_time' => time()
                ];
                $this->tb_user_mobile_bind_log->add_log($logs);
                $this->tb_mobile_message_log->delete_code($mobile); //删除验证码
                $this->db->trans_complete();
                $data['message'] = lang("binding_mobile_success");
                $this->service_message_model->success_response($data);
            } else {
                throw new Exception("10501004");//绑定手机号失败
            }
        } catch (Exception $e) {
            $this->service_message_model->error_response($e->getMessage());
        }
    }

}

