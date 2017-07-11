<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class choose_package extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_coupons');
        $this->load->model('m_trade');
        $this->load->model('m_group');
        $this->load->model('m_goods');
    }

    public function index()
    {
        /**限定只能从后台链接进入**/
        if($this->_userInfo['user_rank']==4 || $this->_userInfo['is_choose']==1){
            echo "403 forbidden";
            exit();
        }

        if (empty($this->_userInfo)) {
            redirect(base_url('login') . '?redirect=choose_package');
        }

        /**刷新页面,删除cookie**/
        $this->m_group->del_cookie();

        $this->_viewData['title'] = lang('m_title') . ' - ' . lang('choose_package');
        $this->_viewData['keywords'] = lang('m_keywords');
        $this->_viewData['description'] = lang('m_description');
        $this->_viewData['canonical'] = base_url();

        $user_id = $this->_userInfo['id'];
        $name=$this->_userInfo['name'];

        /**显示个人信息**/
        $this->_viewData['data']=$this->m_group->show_info($user_id,$name);

        //重新选购名单
        if(in_array($user_id,config_item('again_choose_group')) && $this->_userInfo['is_choose']==0){
            $this->_viewData['data']=$this->m_group->again_choose_group($user_id);
        }


        //选择收货国家
        $country_id = '';
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $country_id = unserialize(base64_decode($_GET['param']));
            if($this->m_goods->is_exist_country($country_id)){
                $is_show = FALSE;
            }else{
                $is_show = TRUE;
            }
        }else{
            $is_show = TRUE;
        }

        //把country_id存入cookie
        set_cookie('sel_country', serialize($country_id), 0, get_public_domain());

        $this->_viewData['is_show'] = $is_show;     //是否显示弹层 选择国家
        $this->_viewData['country_id'] = $country_id;     //选择国家

        $this->_viewData['group_info'] = $this->m_group->group_info($country_id);          //套装数据

        $this->_viewData['goods_info'] = $this->m_coupons->get_goods_list($country_id);    //单品数据

		$this->_viewData['countrys'] = $this->m_goods->get_sale_country();     //获得國家數據

        parent::index('mall/', '', $header = 'header1');
    }


    /***获取所有选购商品***/
    public function click_show()
    {
        $product_id = $this->input->post('product_id');
        $product_num = $this->input->post('product_num');
        $product_type = $this->input->post('product_type');
        $cancel_product = $this->input->post('cancel_product');   //取消勾选的商品

        //验证数量
        if(!is_numeric($product_num)){
            echo json_encode(array('success' => false, 'msg'=>lang('num_not_right')));
            exit();
        }

        //获取全部商品,拼接成html
        if ($product_id && $product_num && $product_type) {
            $all_product = $this->m_group->all_product($product_id, $product_num, $product_type, $cancel_product);
            $html = $this->m_group->create_html($all_product,$this->_viewData['img_host']);
        }
        echo json_encode(array('success' => true, 'str' => $html,'total_money'=>$this->m_group->get_product_total_money($all_product)));
        exit();
    }

    /***获取所有选购商品---代品券的商品金额计算，用四舍五入取整***/
    public function click_show_coupons()
    {
        $product_id = $this->input->post('product_id');
        $product_num = $this->input->post('product_num');
        $product_type = $this->input->post('product_type');
        $cancel_product = $this->input->post('cancel_product');   //取消勾选的商品

        //验证数量
        if(!is_numeric($product_num)){
            echo json_encode(array('success' => false, 'msg'=>lang('num_not_right')));
            exit();
        }

        //获取全部商品,拼接成html
        if ($product_id && $product_num && $product_type) {
            $all_product = $this->m_group->all_product_coupons($product_id, $product_num, $product_type, $cancel_product);
            $html = $this->m_group->create_html_coupons($all_product,$this->_viewData['img_host']);
        }
        echo json_encode(array('success' => true, 'str' => $html,'total_money'=>$this->m_group->get_product_total_money_coupons($all_product)));
        exit();
    }

    /***点击确认换购***/
    public function confirm_choose()
    {
        $all_product=$this->m_group->get_all_product();

        //必须选择一种套装
        if ($all_product['group'] == array()) {
            echo json_encode(array('success' => false, 'msg' => lang('must_choose_a_product')));
            exit();
        }

        //升级的费用
        $pay_money=$this->m_group->get_user_rank_money($this->_userInfo['id']);

        //重新选购名单
        $user_id=$this->_userInfo['id'];
        if(in_array($user_id,config_item('again_choose_group')) && $this->_userInfo['is_choose']==0){
            $data=$this->m_group->again_choose_group($user_id);
            $pay_money=$data['pay_money'];
        }

        //所选购商品的总价
        $total_money = $this->m_group->get_product_total_money($all_product);

        //验证金额是否一致
        if ($total_money != $pay_money) {
            echo json_encode(array('success'=>false,'msg'=>lang('price_not_same')));
            exit();
        }

        //提交时检查库存
        //$goods_list=$this->m_group->get_goods_list($all_product);
        //$this->m_group->check_stock($goods_list);

        $redirect_url = "/clearing";
        echo json_encode(array('success' => true, 'redirect_url' => $redirect_url));
        exit();
    }
}


