<?php
/** 
 *　供应商商品管理
 * @date: 2015-9-17 
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Goods extends MY_Controller {
    private $supplier=array();

	public function __construct() {
		parent::__construct();
		
		$this->load->model('m_goods');
		
		$this->supplier=unserialize(filter_input(INPUT_COOKIE, 'adminSupplierInfo'));
	}

	/* 增加或编辑商品 */
	public function index($main_sn = NULL) {
		$this->_viewData['title'] = lang('add_goods');
		$this->_viewData['is_edit'] = 0;
		
		//获取所有分类
		$this->_viewData['category_all']=$this->m_goods->get_all_category();
		
		//获取所有牌子
		$this->_viewData['brand_all']=$this->m_goods->getBrandListALL();
		
		//获取所有仓库
		$store_list = $this->m_global->getStoreList();
		$storehouse = array(
			'tps' => array(),
			'third' => array(),
		);
		foreach ($store_list as $v)
		{
			if ($v['storehouse_type'] == 1)
			{
				$storehouse['tps'][] = $v;
			}
			else if ($v['storehouse_type'] == 3)
			{
				$storehouse['third'][] = $v;
			}
		}
		$this->_viewData['storehouse'] = $storehouse;
		
		//获取颜色
		$this->_viewData['color_all']=$this->m_goods->get_goods_attr('color',false);
		
		//获取尺码
		$this->_viewData['size_all']=$this->m_goods->get_goods_attr('size');
		
		//所有销售目的国家
		$this->_viewData['sale_country_all']=$this->m_goods->get_sale_country();
	
		$data=array();
		if($main_sn){
			$data = $this->m_goods->get_goods($main_sn);			
			
			$this->_viewData['title'] = lang('edit_goods');			
			$this->_viewData['is_edit'] = 1;
		}
		$this->_viewData['data'] = $data;
		$this->_viewData['lang_list'] = $this->m_global->getLangList();
		
		$this->_viewData['goods_sn_main'] = 'su'.random_string('numeric', 8);
		
		parent::index('supplier/','goods_form');
	}
	
	/* 增加或编辑AJAX提交 */
	public function do_add(){
		$is_edit = $this->input->post('is_edit');
		$data = encodeHtml($this->input->post('cate'));
				
		$data_db=array();
		$status=1;
		
		foreach($data as $k=>$v) {
		    //检查是否勾选了此种语种
		    if(empty($v['lang_check'][$k])) {
		        unset($data[$k]);
		        continue;
		    }
		    
		    unset($data[$k]['lang_check']);
			
			if( empty($v['seller_note']) || empty($v['cate_id']) || empty($v['sale_country']) || empty($v['goods_name'])|| empty($v['country_flag']) 
					|| empty($v['goods_img']) || empty($v['goods_desc']) || empty($v['goods_weight'])
					 || empty($v['purchase_price'])
					|| empty($v['meta_title']) ) {
				$status=0;
				break;
			}
			
			if(!preg_match('/^[0-9]{1,}(\.[0-9]{1,3})?$/',$v['goods_weight'])) {
			    $status=-1;
			    break;
			}
			
			if(!preg_match('/^[0-9]{1,}(\.[0-9]{1,3})?$/',$v['purchase_price'])) {
			    $status=-2;
			    break;
			}
			
		}
		
		if($status > 0) {
			foreach($data as $k=>$v) {	
				$data_db=$v;
				$data_db['language_id']=$k;
				
				$data_db['is_on_sale']=2;
				$data_db['supplier_id']=$this->supplier['supplier_id'];
				$data_db['shop_price']=1;

				//组合销售国家
				$tmp_sale_country='';
				foreach($v['sale_country'] as $country) {
				    $tmp_sale_country.=$country.'$';
				}
				$data_db['sale_country']=trim($tmp_sale_country,'$');
				
				if(empty($is_edit)) {
					$status=$this->m_goods->add_goods($data_db,$this->supplier['supplier_user']);
				}else {
					
					$status=$this->m_goods->update_goods($data_db,$k,'su-'.$this->supplier['supplier_user']);
				}
			}
		}
		
		if($status > 0) {
			echo json_encode(array('error'=>0,'msg'=>lang('info_success')));
		}elseif($status == 0) {
			echo json_encode(array('error'=>1,'msg'=>lang('info_failed')));
		}elseif($status == -1) {
		    echo json_encode(array('error'=>1,'msg'=>lang('info_err_weight')));
		}elseif($status == -2) {
		    echo json_encode(array('error'=>1,'msg'=>lang('info_err_purchase_price')));
		}
		
		exit;
	}
	
	/* 产品列表  */
	public function goods_list() {
	
		$this->load->library('pagination');
		
		$this->_viewData['lang_all']=$this->m_global->getLangList();
		
		/* 获取所有分类  */
		$this->_viewData['category_all']=$this->m_goods->get_all_category();		
			
		/* 条件区域  */
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
		$searchData['cate_id'] = isset($searchData['cate_id'])?$searchData['cate_id']:0;

		$searchData['supplier_id'] = $this->supplier['supplier_id'];
		
		$this->_viewData['searchData'] = $searchData;
		
		if($searchData['cate_id']) {
			//获取该分类下所有子类
			$cate_ids=$this->m_goods->get_children($this->m_goods->get_category_list(),$searchData['cate_id']);
			$cate_ids=$cate_ids.$searchData['cate_id'];
			$searchData['cate_id']=$cate_ids;
		}
	
		/* 分页数据 */
		$this->_viewData['list'] = $this->m_goods->get_goods_list_page($searchData,100);
		$this->_viewData['title'] = lang('goods_list');
	
		$url = 'supplier/goods/goods_list';
		add_params_to_url($url, $searchData);
	
		/* Pager */
		$config['base_url'] = base_url($url);
		$config['cur_page'] = $searchData['page'];
		$config['per_page'] = 100;
		$config['total_rows'] = $this->m_goods->get_goods_total($searchData);
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links();
	
		parent::index('supplier/','goods_list');
	}
	
	/* 获取子sku列表  */
	public function get_sub_sn_list() {
		$error=array();
		
		if($this->input->is_ajax_request()) {
			$main_sn=trim($this->input->get('main_sn'));
			$lang_id=intval($this->input->get('lang_id'));
			
			$rs=$this->m_goods->get_sub_sn($main_sn,$lang_id);
			
			echo json_encode(array('error'=>0,'info'=>$rs));
			
		}else {
			$error['error']=1;
			$error['info']=lang('info_unvalid_request');
		}
	}

	/* 上传商品图片  */
	public function new_pic(){
		$goods_sn=$this->input->get('goods_sn');
		$type=$this->input->get('type');
		$input_file_name=$this->input->get('input_name') ? $this->input->get('input_name') : 'userfile';
		
		$dir_path='upload/products/'.date('Ym').'/'.$goods_sn.'/';
		
		$this->m_global->mkDirs($dir_path);

		$config['upload_path'] = $dir_path;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '2048';
		//$config['max_width']  = '1500';
		//$config['max_height']  = '1000';
		$config['file_name']  = date('YmdHis');
		$this->load->library('upload', $config); //加载上传类文件
	
		$this->upload->set_xss_clean(TRUE);//开启图片进行XSS过滤 uploaded will be run through the XSS filter.
	
		if ( ! $this->upload->do_upload($input_file_name)) {
			
			$error = array('success'=>0,'upload_data' => $this->upload->display_errors());
		} else {
			$img_path=$config['upload_path'].$this->upload->file_name;
			
			$conf['image_library'] = 'gd2';
			$conf['source_image'] = $img_path;
			$conf['create_thumb'] = TRUE;
			$conf['maintain_ratio'] = TRUE;
			
			//生产缩略图
			switch($type){
				case 1: //列表图250*250
				    $is_scale=$this->input->get('is_scale');
				    
					$conf['width'] = 250;
					$conf['height'] = 250;
					
					if($is_scale) {
					    $this->load->library('image_lib',$conf);
					    $this->image_lib->resize();
					    unlink($img_path);
					    $img_path_arr=explode('.', $img_path);
					    $img_path=$img_path_arr[0].'_thumb.'.$img_path_arr[1];
					}			
					
					$error = array('success'=>1,'path'=>$img_path);
					break;
				case 2: //小图100*100,大图800*800
					$conf['width'] = 800;
					$conf['height'] = 800;
					$conf['thumb_marker'] = '_big';
					
					$this->load->library('image_lib');
					
					//大图800*800
					$this->image_lib->initialize($conf);
					$this->image_lib->resize();					

					//小图100*100
					$conf['width'] = 100;
					$conf['height'] = 100;
					$conf['thumb_marker'] = '_thumb';
					$this->image_lib->initialize($conf); //重新调整配置文件
					$this->image_lib->resize();
					
					unlink($img_path);
					
					$img_path_arr=explode('.', $img_path);
					$img_path=$img_path_arr[0].'_thumb.'.$img_path_arr[1];
					$img_path_big=$img_path_arr[0].'_big.'.$img_path_arr[1];
					
					//保存图片路径到数据库
					$img_id=$this->m_goods->save_gall_img($goods_sn,$img_path,$img_path_big);
						
					$error = array('success'=>1,'path'=>$img_path,'img_id'=>$img_id);				
					break;
				case 3: //详情图800*800
					$conf['width'] = 800;
					//$conf['height'] = 800;
					$lang_id=intval($this->input->get('lang_id'));
						
					$this->load->library('image_lib',$conf);
					
					$this->image_lib->resize();
						
					unlink($img_path);
						
					$img_path_arr=explode('.', $img_path);
					$img_path=$img_path_arr[0].'_thumb.'.$img_path_arr[1];
					
					//保存图片路径到数据库
					$img_id=$this->m_goods->save_detail_img($goods_sn,$img_path,$lang_id);
					
					$error = array('success'=>1,'path'=>$img_path,'img_id'=>$img_id);
					break;
			}
	
		}
		echo json_encode($error);
		exit;

	}
	
	/* 删除商品图片  */
	public function del_pic() {
		$type=$this->input->post('type');
		$path=$this->input->post('path');
		$img_id=intval($this->input->post('img_id'));
		
		switch($type){
			case 1: 
				if(unlink($path)) {
					exit('ok');
				}
								
				break;
			case 2: 
				if($this->m_goods->del_img_gallery('mall_goods_gallery',$img_id)) {
					exit('ok');
				}
				break;
			case 3: 
				
				if(unlink($path) && $this->m_goods->del_img('mall_goods_detail_img',$img_id)) {
					exit('ok');
				}
				break;
		}
		
		exit(lang(info_error));
	}
	
	/* 删除子sku */
	public function del_sub_sn() {
		$product_id=intval($this->input->get('product_id'));
		$goods_sn=intval($this->input->get('goods_sn'));
		
		if($this->m_goods->del_sub_sn($product_id,$goods_sn)) {
			exit('ok');
		}
		
		exit(lang(info_error));
	}
	
	/* 自动计算套餐销售价 8折 */
	function get_group_sale_price() {
        $group_id=intval($this->input->get('group_id'));
	    if($group_id && $this->input->is_ajax_request()) {
	        $goods_info=$this->m_goods->get_goods_group_info($group_id);
	        
	        if($goods_info) {
	            exit(number_format($goods_info['total'] * 0.8,2,'.',''));
	        }
	    }
	    exit(0);
	}
	
}
