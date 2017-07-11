<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class choose_goods extends MY_Controller
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
        //未登录重定向
        if (empty($this->_userInfo))
        {
            redirect(base_url('login') . '?redirect = choose_goods');
        }

        //不能重复提交
        if($this->_userInfo['user_rank'] == 4 || $this->_userInfo['is_choose'] == 1)
        {
            echo "403 forbidden";
            exit();
        }

        //刷新页面,删除cookie
        $this->m_group->del_cookie();

        $this->_viewData['title'] = lang('m_title') . ' - ' . lang('choose_goods');
        $this->_viewData['keywords'] = lang('m_keywords');
        $this->_viewData['description'] = lang('m_description');
        $this->_viewData['canonical'] = base_url();

        $user_id = $this->_userInfo['id'];
        $name = $this->_userInfo['name'];

        /**显示个人信息**/
        $this->_viewData['data'] = $this->m_group->show_info($user_id,$name);

        //重新选购名单
        if(in_array($user_id,config_item('again_choose_group')) && $this->_userInfo['is_choose']==0){
            $this->_viewData['data']=$this->m_group->again_choose_group($user_id);
        }

        //所选区域ID
        $country_id = $this->session->userdata('location_id');

        //套装数据
        $this->_viewData['group_info'] = $this->m_group->group_info($country_id);

        //单品数据
        //$this->_viewData['goods_info'] = $this->m_coupons->get_goods_list($country_id);

        parent::index('mall/', '', $header = 'header');
    }



    //确认换购
    public function confirm_choose()
    {
        $all_product = $this->m_group->get_all_product();

        //必须选择一种套装
        if ($all_product['group'] == array())
        {
            echo json_encode(array('success' => false, 'msg' => lang('must_choose_a_product')));
            exit();
        }

        //升级的费用
        $pay_money =  $this->m_group->get_user_rank_money($this->_userInfo['id']);

        //重新选购名单
        $user_id = $this->_userInfo['id'];
        if(in_array($user_id,config_item('again_choose_group')) && $this->_userInfo['is_choose']==0)
        {
            $data = $this->m_group->again_choose_group($user_id);
            $pay_money = $data['pay_money'];
        }

        //所选购商品的总价
        $total_money = $this->m_group->get_product_total_money($all_product);

        //验证金额是否一致
        if ($total_money != $pay_money)
        {
            echo json_encode(array('success'=>false,'msg'=>lang('price_not_same')));
            exit();
        }

        $redirect_url = "/choose_goods_checkout";
        echo json_encode(array('success' => true, 'redirect_url' => $redirect_url));
        exit();
    }

    //添加到购物车
    public function add_cart()
    {
        $product_id = $this->input->post('product_id');
        $product_num = $this->input->post('product_num');
        $product_type = $this->input->post('product_type');
        $cancel_product = $this->input->post('cancel_product');   //取消勾选的商品

        //验证数量
        if(!is_numeric($product_num))
        {
            echo json_encode(array('success' => false, 'msg'=>lang('num_not_right')));
            exit();
        }

        //获取全部商品,拼接成html
        if ($product_id && $product_num && $product_type)
        {
            $all_product = $this->m_group->all_product($product_id, $product_num, $product_type, $cancel_product);
            $html = $this->m_group->create_html($all_product,$this->_viewData['img_host']);
        }

        echo json_encode(array('success' => true, 'str' => $html,'total_money'=>$this->m_group->get_product_total_money($all_product)));
        exit();
    }
}


