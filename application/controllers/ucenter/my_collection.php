<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class my_collection extends MY_Controller
{
    public function __construct(){
        parent::__construct();
		$this->load->model('m_user_helper');
    }

    public function index(){
        $this->_viewData['title']=lang('my_collection');

		$searchData = $this->input->get();
		$page = $searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$page_size = 12;
		//改为查询从库 M BY BRADY.WANG
		//第一步 查找到 mall_wish表中该用户的所有收藏的商品
		$this->load->model("tb_mall_wish");
		$cur_language_id = get_cookie('curLan_id', true);
		if (empty($cur_language_id)) {
				$cur_language_id = 1;
		}
		$params = [
				'select'=>'mgm.goods_id,mgm.is_new,mgm.goods_sn_main,mgm.goods_name,mgm.goods_img,mgm.shop_price,mgm.market_price,mgm.country_flag',
				'join'=>[
						[
								'table'=>'mall_goods_main as mgm',
								'where'=>'mgm.goods_id = mall_wish.goods_id',
								'type'=>'inner'
						]
				],
				'where'=>[
						'mgm.language_id'=>$cur_language_id,
						'mall_wish.user_id'=>$this->_userInfo['id']
				]
		];
		$total_rows = $this->tb_mall_wish->get($params,true);
		if ($total_rows > 0) {
			$total_page = ceil($total_rows/$page_size);
			$page = ($page > $total_page) ? $total_page : $page;

			$params['limit'] = [
				'page'=>$page,
				'page_size'=>$page_size
			];
			$params['order'] = 'mall_wish.add_time desc';
			$list = $this->tb_mall_wish->get($params);
		} else {
			$list = [];
			$page = 1;
		}


		//$this->_viewData['lists'] = $this->m_user_helper->getCollection($searchData,$this->_userInfo['id']);
		$this->_viewData['lists'] =$list;

		if(!$this->_viewData['lists'] && $page >1 ){
			redirect(base_url('ucenter/my_collection'));
		}
		$url = 'ucenter/my_collection';

		$pager = $this->tb_mall_wish->get_pager($url,['page'=>$page,'page_size'=>$page_size],$total_rows);
		$this->_viewData['pager'] = $pager;

        parent::index();
    }

	public function cancel_collection(){
		if($this->input->is_ajax_request()){
			$goods_id = $this->input->post('goods_id');
			if($this->m_user_helper->cancel_collection($goods_id,$this->_userInfo['id'])){
				die(json_encode(array('success'=>1)));
			}else{
				die(json_encode(array('success'=>0,'msg'=>lang('try_again'))));
			}
		}
	}
}
