<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Common extends MY_Controller {

    /* 改变收货区域  */
    public function changeLanguage() {
		
        //清空选择的套餐内容 --- leon 2017-06-30
        $this->load->model('m_group');
        $this->m_group->del_cookie();

        $public_domain=get_public_domain();
        $location = $this->input->post('location_id');
        $goods_sn = $this->input->post('goods_sn');
		$goods_sn_main = $this->input->post('goods_sn_main');
		$jump = $this->input->post('jump'); //有值，刷新本页面
        $lang_cur_arr = isset($this->_lang_cur_arr[$location])?$this->_lang_cur_arr[$location]:$this->_lang_cur_arr['000'];

        delete_cookie('curLoc',$public_domain);
        set_cookie('curLoc', $location, 3600 * 24 * 365, $public_domain,'/');

        //区域改变语言也需要改变
        if(!$jump && $goods_sn && $goods_sn_main){ /** 如果在商品详情页，检测更换的 */
            $this->load->model('m_goods');
			$jump = $this->m_goods->is_goods_exist($goods_sn_main,$goods_sn,$lang_cur_arr['curLan_id'],$location);
        }

        delete_cookie('curLan',$public_domain);
        delete_cookie('curLan_id',$public_domain);
        delete_cookie('curLan_name',$public_domain);
        set_cookie('curLan', $lang_cur_arr['curLan'], 3600 * 24 * 365, $public_domain,'/');
        set_cookie('curLan_id', $lang_cur_arr['curLan_id'], 3600 * 24 * 365, $public_domain,'/');
        set_cookie('curLan_name', $lang_cur_arr['curLan_name'], 3600 * 24 * 365, $public_domain,'/');

        set_cookie('changeCurLoc', '1', 3600 * 24 * 365, $public_domain,'/');

        // 如果用户没有选定币种，随语言所改变,如果选择了，则不随语言的改变
        $this->chang_currency($lang_cur_arr['curCur'],$lang_cur_arr['curCur_name'],$lang_cur_arr['curCur_flag'],true);

        echo json_encode(array('success'=>  TRUE,'is_exist'=>$jump));
        exit;
    }
    
    public function changeCurrency() {

        $icon = $this->input->post('icon');
        $currency = $this->input->post('currency');
        $currency_name = $this->input->post('currency_name');

    	$this->chang_currency($currency,$currency_name,$icon);
    
    	echo json_encode(array('success'=>  TRUE));
    	exit;
    }
    
    function chang_currency($currency,$currency_name,$icon,$is_change_currency=true) {
		$public_domain=get_public_domain();
    	$cur_currency=get_cookie('curCur_manual',true);
    	if(!$cur_currency || ($cur_currency && $is_change_currency)) {
        	delete_cookie('curCur',  $public_domain);
        	delete_cookie('curCur_name',  $public_domain);
        	delete_cookie('curCur_flag',  $public_domain);
        
        	set_cookie('curCur', $currency, 3600 * 24 * 365, $public_domain,'/');
        	set_cookie('curCur_name', $currency_name, 3600 * 24 * 365, $public_domain,'/');
        	set_cookie('curCur_flag', $icon, 3600 * 24 * 365, $public_domain,'/');

            delete_cookie('curCur_manual',  $public_domain);
        	set_cookie('curCur_manual', '1', 3600 * 24 * 365, $public_domain,'/');
    	}
    }
    
    public function getCurMemNum(){
        $num = $this->db->select('count(*) num')->from('users')->where('parent_id !=',0)->get()->row_object()->num;
        echo json_encode(array('success'=>  TRUE,'data'=>array('num'=>$num)));
    }
    
    public function resendEnableMail(){
        $regDataSeri = filter_input(INPUT_COOKIE, 'reg_data');
        if($regDataSeri){
            $this->load->model('m_user');
            $reg_data = unserialize($regDataSeri);
            $user = $this->m_user->getUserByIdOrEmail($reg_data['id']);
            if($user['send_email_time'] > time()-60){
                echo json_encode(array('success'=>  false,'msg'=>lang('send_again')));exit;
            }
            $this->m_user->updateCreateTime($reg_data['id']);
            $this->m_user->sendAccountActivationEmail($reg_data);
            echo json_encode(array('success'=>  TRUE,'msg'=>lang('re_send_mail_success')));exit;
        }else{
            echo json_encode(array('success'=> FALSE,'msg'=>lang('send_again')));exit;
        }
    }

	public function get_wx_code(){
		require_once APPPATH.'third_party/qrcode/phpqrcode.php';
		$url = urldecode($_GET["data"]);

		$errorCorrectionLevel = 'H';//容错级别
		$matrixPointSize = 6;//生成图片大小
		//生成二维码图片
		//QRcode::png($url,false,$errorCorrectionLevel,$matrixPointSize, 2);
		QRcode::png($url, 'upload/qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
		$logo = 'themes/mall/img/tps.png';//准备好的logo图片
		$QR = 'upload/qrcode.png';//已经生成的原始二维码图

		if ($logo !== FALSE) {
			$QR = imagecreatefromstring(file_get_contents($QR));
			$logo = imagecreatefromstring(file_get_contents($logo));
			$QR_width = imagesx($QR);//二维码图片宽度
			$QR_height = imagesy($QR);//二维码图片高度
			$logo_width = imagesx($logo);//logo图片宽度
			$logo_height = imagesy($logo);//logo图片高度
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			//重新组合图片并调整大小
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
				$logo_qr_height, $logo_width, $logo_height);
		}
		//输出图片
		ob_clean();
		Header("Content-type: image/png");
		ImagePng($QR);
	}

    
    public function clearAllLog(){
        $this->m_debug->clearAllLog();
        echo json_encode(array('success'=> TRUE));
    }
    
    public function test(){
        echo date('Y-m-d',strtotime('-1 month')).'<br/>';
        echo date('Y-m-d').'<br/>';
    }

}