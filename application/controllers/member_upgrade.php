<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class member_upgrade extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_trade');
        $this->load->model('m_group');
        $this->load->model('m_goods');
        $this->load->model('m_coupons');
    }

    public function index()
    {

        $product_set=isset($_COOKIE['product_set'])?unserialize($_COOKIE['product_set']):array();

        if (empty($this->_userInfo)) {
            redirect(base_url('login') . '?redirect=member_upgrade');
        }

        if($product_set==array()){
            echo "403 forbidden";
            exit();
        }

        /**刷新页面,删除cookie**/
        $this->m_group->del_cookie();

        $this->_viewData['title'] = lang('m_title') . ' - ' . lang('choose_package');
        $this->_viewData['keywords'] = lang('m_keywords');
        $this->_viewData['description'] = lang('m_description');
        $this->_viewData['canonical'] = base_url();

        $user_id = $this->_userInfo['id'];

        /**显示个人信息**/
        $data=$this->m_group->show_info_upgrade($user_id,$product_set);

        //选择收货地址
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

        //把选择的商品地址存入cookie
        set_cookie('sel_country', serialize($country_id), 0, get_public_domain());

        $this->_viewData['data'] = $data;
        $this->_viewData['group_info'] = $this->m_group->group_info($country_id);


        $this->_viewData['goods_info'] = $this->m_coupons->get_goods_list($country_id);


        $this->_viewData['countrys'] = $this->m_goods->get_sale_country();     //获得國家數據


        $this->_viewData['is_show'] = $is_show;     //是否显示弹层 选择国家
        $this->_viewData['country_id'] = $country_id;     //选择国家

        parent::index('mall/', 'member_upgrade', $header = 'header1');
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
        $pay_money=$this->m_group->get_upgrade_money($this->_userInfo['user_rank'],$this->_userInfo['id']);

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

        $redirect_url = "/submit_order";

        echo json_encode(array('success' => true, 'redirect_url' => $redirect_url));
        exit();
    }

    /** 選擇國家轉向 */
    public function return_to(){
        if($this->input->is_ajax_request()){
            $country_id = $this->input->post('country_id');
            $this->load->model('m_goods');
            if($this->m_goods->is_exist_country($country_id)){
                $param = base64_encode(serialize($country_id));
                echo json_encode(array('success'=>1,'param'=>$param));
            }else{
                echo json_encode(array('success'=>0,'msg'=>lang('try_again')));
            }
        }
    }
}


