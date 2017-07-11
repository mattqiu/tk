<?php
/**
 *　商品管理
 * @date: 2015-7-13
 * @author: sky yuan
 */
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Goods extends MY_Controller {

 	public function __construct() {
		parent::__construct();

		$this->load->model('m_goods');
	}

	/* 增加或编辑商品 */
	public function index($main_sn = NULL) {

		// 功能过滤
		redirect(base_url('admin'));

		$this->_viewData['title'] = lang('add_goods');
		$this->_viewData['is_edit'] = 0;

		//获取所有分类
		$this->_viewData['category_all']=$this->m_goods->get_all_category();

		//获取所有牌子
		$this->_viewData['brand_all']=$this->m_goods->getBrandListALL();

		//获取所有风格
		$this->_viewData['effect_all']=$this->m_goods->getEffectList();

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

		//所有供应商
		$this->_viewData['supplier_all']=$this->m_goods->get_supplier_list();

		$data=array();
		if($main_sn){
			$data = $this->m_goods->get_goods($main_sn);

			$this->_viewData['title'] = lang('edit_goods');
			$this->_viewData['is_edit'] = 1;
		}
		$this->_viewData['data'] = $data;

		$this->_viewData['lang_list'] = $this->m_global->getLangList();

		$this->_viewData['goods_sn_main'] = $main_sn ? $main_sn : random_string('numeric', 8);

		parent::index('admin/','goods_form');
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

			if(empty($v['supplier_id']) || empty($v['cate_id']) || empty($v['shipper_id']) || empty($v['goods_name'])
					|| empty($v['goods_img']) || empty($v['goods_desc']) || empty($v['goods_weight'])
					|| empty($v['market_price']) || empty($v['shop_price']) || empty($v['purchase_price'])
					|| empty($v['meta_title']) || empty($v['sort_order']) || empty($v['country_flag']) || empty($v['sale_country'])) {
				$status=0;
				break;
			}

			if($v['market_price'] < $v['shop_price']) {
			    $status=-1;
			    break;
			}

			$sale_price=number_format(10 / 9 * $v['purchase_price'],2,'.','');
			if($v['shop_price'] < $sale_price) {
			    $status=-2;
			    break;
			}
		}

		if($status > 0) {
			foreach($data as $k=>$v) {
				$data_db=$v;
				$data_db['language_id']=$k;

				$data_db['is_alone_sale']=isset($v['is_alone_sale']) ? $data_db['is_alone_sale'] : 1;

				$data_db['is_on_sale']=isset($v['is_on_sale']) ? $data_db['is_on_sale'] : 0;
				$data_db['is_new']=isset($v['is_new']) ? $data_db['is_new'] : 0;
				$data_db['is_hot']=isset($v['is_hot']) ? $data_db['is_hot'] : 0;
				$data_db['is_home']=isset($v['is_home']) ? $data_db['is_home'] : 0;
				$data_db['is_best']=isset($v['is_best']) ? $data_db['is_best'] : 0;
				$data_db['is_free_shipping']=isset($v['is_free_shipping']) ? $data_db['is_free_shipping'] : 0;
				$data_db['is_ship24h']=isset($v['is_ship24h']) ? $data_db['is_ship24h'] : 0;
				$data_db['is_for_upgrade']=isset($v['is_for_upgrade']) ? $data_db['is_for_upgrade'] : 0;
				$data_db['group_goods_id']=intval($data_db['group_goods_id']);
				$data_db['goods_size']=!empty($v['goods_size']) ? $data_db['goods_size'] : 0;

				if($v['ship_note_type'] == 2) {
				    $data_db['ship_note_val']=strtotime($v['ship_note_val']);
				}else {
				    $data_db['ship_note_val']=intval($v['ship_note_val']);
				}

				//组合销售国家
				$tmp_sale_country='';
				foreach($v['sale_country'] as $country) {
				    $tmp_sale_country.=$country.'$';
				}
				$data_db['sale_country']=trim($tmp_sale_country,'$');

				if(empty($is_edit)) {
					$status=$this->m_goods->add_goods($data_db,$this->_adminInfo['email']);
				}else {
                    //编辑时候，增加了一种语种
                    if(empty($data_db['goods_id'])) {
                        $status=$this->m_goods->add_goods($data_db,$this->_adminInfo['email']);
                    }

					$status=$this->m_goods->update_goods($data_db,$k,$this->_adminInfo['email'],$this->_adminInfo['id']);
				}
			}
		}

		if($status > 0) {
			echo json_encode(array('error'=>0,'msg'=>lang('info_success')));
		}elseif($status == 0) {
			echo json_encode(array('error'=>1,'msg'=>lang('info_failed')));
		}elseif($status == -1) {
		    echo json_encode(array('error'=>1,'msg'=>lang('info_price_err')));
		}elseif($status == -2) {
		    echo json_encode(array('error'=>1,'msg'=>lang('info_price_err1').' = '.$sale_price));
		}

		exit;
	}

	/* 产品列表  */
	public function goods_list() {

		// 功能过滤
		redirect(base_url('admin'));

		$this->load->library('pagination');

		$this->_viewData['lang_all']=$this->m_global->getLangList();

		/* 获取所有分类  */
		$this->_viewData['category_all']=$this->m_goods->get_all_category();

		/* 获取仓库  */
		$this->_viewData['store_all']=$this->m_global->getStoreList();

		//所有供应商
		$this->_viewData['supplier_all']=$this->m_goods->get_supplier_list();

		/* 条件区域  */
		$searchData = $this->input->get()?$this->input->get():array();
		$searchData['page'] = max((int)(isset($searchData['page'])?$searchData['page']:1),1);
		$searchData['keywords'] = isset($searchData['keywords'])?$searchData['keywords']:'';
		$searchData['cate_id'] = isset($searchData['cate_id'])?$searchData['cate_id']:0;
		$searchData['store_code'] = isset($searchData['store_code'])?$searchData['store_code']:"0";
		$searchData['supplier_id'] = isset($searchData['supplier_id'])?$searchData['supplier_id']:0;

		$searchData['start'] = isset($searchData['start'])?$searchData['start']:'';
		$searchData['end'] = isset($searchData['end'])?$searchData['end']:'';

		$this->_viewData['searchData'] = $searchData;

		if($searchData['cate_id']) {
			//获取该分类下所有子类
			$cate_ids=$this->m_goods->get_children($this->m_goods->get_category_list(),$searchData['cate_id']);
			$cate_ids=$cate_ids.$searchData['cate_id'];
			$searchData['cate_id']=$cate_ids;
		}

		/* 分页数据 */
		$goods = $this->m_goods->get_goods_list_page($searchData,100);
		foreach($goods as &$item){
			$row = $this->db->select('supplier_name')->where('supplier_id',$item['shipper_id'])->get('mall_supplier')->row_array();
			$item['supplier_name'] = $row ? $row['supplier_name'] : '';
			$item['sale_country_str'] = $this->m_goods->get_sale_country_str($item['sale_country']);
		}
		$this->_viewData['list'] = $goods;
		$this->_viewData['title'] = lang('goods_list');

		$url = 'admin/goods/goods_list';
		add_params_to_url($url, $searchData);

		/* Pager */
		$config['base_url'] = base_url($url);
		$config['cur_page'] = $searchData['page'];
		$config['per_page'] = 100;
		$config['total_rows'] = $this->m_goods->get_goods_total($searchData);
		$this->pagination->initialize_ucenter($config);
		$this->_viewData['pager'] = $this->pagination->create_links();

		parent::index('admin/','goods_list');
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

		exit(lang('info_error'));
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

	/* 导入doba产品  */
	function import_doba_products() {
	    exit('Closed!');

	    set_time_limit(0);

	    $this->load->library('image_lib');

	    $dir_path=$config['upload_path'] = 'upload/doba_products_csv_temp/';
	    $config['allowed_types'] = '*';
	    $config['max_size'] = '4096';
	    $this->load->library('upload', $config);

	    $this->m_global->mkDirs($dir_path);

	    $input_file_name = "doba_goods";
	    if ( ! $this->upload->do_upload($input_file_name)) {

            exit($this->upload->display_errors());
		} else {
		    $info=$this->upload->data();

		    $handle = fopen($dir_path.$info['file_name'],'rb');

		    $i=0;
		    $total_upload_num=0;
            while ($data = fgetcsv($handle,10000)) {
                $i++;
                //print_r($data);exit;
                if($i == 1) {
                    continue;
                }

                //检查该商品是否已经存在
                if($this->m_goods->check_doba_item($data[15])) {
                    continue;
                }

                //开始处理数据
                $arr_main=array();
                $arr_main_detail=array();
                $arr_goods=array();

                //必须有库存而且数量要在10个以上
                if($data[33] == 'in-stock' && $data[32] > 10) {

                    //检查图片大小是否在800 * 800像素以上
                    if($data[42] < 800 || $data[43] < 800) {
                        continue;
                    }

                    //检查销售价是否高于建议销售价
                    $shop_price=$data[27] * 1.35;  //销售利润为35%
                    if($data[31] < $shop_price) {
                        continue;
                    }

                    //处理新分类
                    $tmp_arr_cate=explode('||', $data[39]);
                    $tmp_arr_cate=array_slice($tmp_arr_cate, 0,3); //只取3级分类

                    $arr_cate=array();
                    $parent_id=0;
                    foreach($tmp_arr_cate as $cate) {
                        //检查这个分类是否存在
                        $cate_id=$this->m_goods->check_doba_category($cate);
                        if(!$cate_id) {
                            $arr_cate['cate_sn']=random_string('alnum', 10);
                            $arr_cate['is_doba_cate']=1;
                            $arr_cate['cate_name']=addslashes($cate);
                            $arr_cate['parent_id']= $parent_id;
                            $arr_cate['meta_title']=addslashes($cate);
                            $arr_cate['language_id']=1;

                            $this->m_goods->inser_db_cate($arr_cate);

                            $parent_id=$this->db->insert_id();

                        }else {
                            $parent_id = $cate_id;
                        }
                    }

                    $goods_sn_main = 'dbus'.random_string('numeric', 8);

                    //处理相册和详情图片
                    $imgs_arr=array();
                    if(!empty($data[44])) {
                        $imgs_arr=explode('|', $data[44]);
                        //array_unshift($imgs_arr, $data[41]);
                    }else{
                        $imgs_arr[]=$data[41];
                    }
                    //下载原图
                    $dir_path='upload/products/'.date('Ym').'/'.$goods_sn_main.'/';
                    $this->m_global->mkDirs($dir_path);

                    $main_img_path='';
                    foreach($imgs_arr as $k=>$val) {
                        $img=file_get_contents($val);

                        if(!empty($img)) {
                            $img_name=date('YmdHis').'_org.jpg';
                            //保存原图
                            file_put_contents($dir_path.$img_name, $img);

                            $img_path=$dir_path.$img_name;

                            $conf['image_library'] = 'gd2';
                            $conf['source_image'] = $img_path;
                            $conf['create_thumb'] = TRUE;
                            $conf['maintain_ratio'] = TRUE;

                            //第一张图需要生成缩略图
                            if($k == 0) {
                                $conf['width'] = 250;
                                $conf['height'] = 250;
                                $conf['thumb_marker'] = '_list';

                                $this->image_lib->initialize($conf);
                                $this->image_lib->resize();

                                $img_path_arr=explode('.', $img_path);
                                $main_img_path=$img_path_arr[0].'_list.'.$img_path_arr[1];
                            }

                            //生成相册图 ,小图100*100,大图800*800
                            $conf['width'] = 800;
                            $conf['height'] = 800;
                            $conf['thumb_marker'] = '_big';

                            //大图800*800
                            $this->image_lib->initialize($conf);
                            $this->image_lib->resize();

                            //小图100*100
                            $conf['width'] = 100;
                            $conf['height'] = 100;
                            $conf['thumb_marker'] = '_thumb';
                            $this->image_lib->initialize($conf); //重新调整配置文件
                            $this->image_lib->resize();

                            $img_path_arr=explode('.', $img_path);
                            $img_path=$img_path_arr[0].'_thumb.'.$img_path_arr[1];
                            $img_path_big=$img_path_arr[0].'_big.'.$img_path_arr[1];

                            //保存图片路径到数据库
                            $img_id=$this->m_goods->save_gall_img($goods_sn_main.'-1',$img_path,$img_path_big);

                            //保存图片路径到数据库
                            $img_id=$this->m_goods->save_detail_img($goods_sn_main,$img_path_big,1);
                        }
                    }

                    //处理品牌
                    $brand_id=0;
                    if(!empty($data[11])) {
                        $brand_id=$this->m_goods->check_doba_brand($data[11]);
                        if(!$brand_id) {
                            $arr_brand['brand_name']=addslashes($data[11]);
                            $arr_brand['cate_id']=$parent_id;
                            $arr_brand['language_id']=1;

                            $this->m_goods->add_brand($arr_brand);

                            $brand_id=$this->db->insert_id();

                        }
                    }

                    //处理主信息
                    $arr_main['goods_sn_main'] = $goods_sn_main;
                    $arr_main['cate_id'] = $parent_id;
                    $arr_main['goods_name'] = $data[5];
                    $arr_main['goods_img'] =$main_img_path;
                    $arr_main['goods_weight'] =number_format($data[23] * 0.454,3,'.',''); //英镑转换成kg
                    $arr_main['purchase_price'] =$data[27];
                    $arr_main['market_price'] =$data[31]; //利润按35%算
                    $arr_main['shop_price'] =$shop_price;
                    $arr_main['is_free_shipping'] =1;
                    $arr_main['add_user'] =$this->_adminInfo['email'];
                    $arr_main['add_time'] =time();
                    $arr_main['language_id'] =1;
                    $arr_main['store_code'] ='USATL';
                    $arr_main['brand_id'] =$brand_id;
                    $arr_main['country_flag'] ='us';
                    $arr_main['sale_country'] ='840';
                    $arr_main['is_doba_goods'] =1;
                    $arr_main['doba_supplier_id'] =$data[0];
                    $arr_main['doba_drop_ship_fee'] =$data[1];
                    $arr_main['doba_supplier_name'] =$data[2];
                    $arr_main['doba_product_id'] =$data[3];
                    $arr_main['doba_product_sku'] =$data[4];
                    $arr_main['doba_warranty'] =$data[6]; //质保期
                    $arr_main['doba_manufacturer'] =$data[10]; //制造商
                    $arr_main['doba_country_of_origin'] =$data[13];
                    $arr_main['doba_item_id'] =$data[15];
                    $arr_main['doba_item_sku'] =$data[16];
                    $arr_main['doba_item_weight'] =number_format($data[20] * 0.454,3,'.','');
                    $arr_main['doba_ship_alone'] =$data[21];
                    $arr_main['doba_ship_weight'] =$data[23];
                    $arr_main['doba_ship_cost'] =$data[24];
                    $arr_main['doba_prepay_price'] =$data[29];
                    $arr_main['daba_msrp'] =$data[31];

                    if($main_goods_id=$this->m_goods->add_db_goods($arr_main)) {
                        //处理主信息详情
                        $arr_main_detail['goods_main_id'] =$main_goods_id;
                        $arr_main_detail['meta_title'] =$arr_main['goods_name'];
                        $arr_main_detail['goods_desc'] =strip_tags($data[7],'<br><a><p>');
                        $arr_main_detail['doba_details'] =$data[9];

                        $this->db->insert('mall_goods_main_detail',$arr_main_detail);

                         //处理子信息
                        $arr_goods['goods_sn_main']=$goods_sn_main;
                        $arr_goods['goods_sn']=$goods_sn_main.'-1';
                        $arr_goods['goods_number']=$data[32];
                        $arr_goods['language_id']=1;
                        $arr_goods['price']=$arr_main['shop_price'];

//                        $this->db->insert('mall_goods',$arr_goods);
                        $this->load->model("tb_mall_goods");
                        $this->tb_mall_goods->insert_one($arr_goods);

                        $total_upload_num ++ ;
                    }

                }

            }
            fclose($handle);

            echo 'Upload new products number is ',$total_upload_num,' Go to <a href="',base_url('admin/goods/goods_list'),'">goods list</a>';
		}

	}

	/* 下架商品  */
	function off_self() {
	    $goods_main_sn=trim($this->input->get('main_sn'));

        if(!empty($goods_main_sn)) {
            $this->load->model("tb_mall_goods_main");
            $res = $this->tb_mall_goods_main->update_one_auto([
                "data"=>['is_on_sale'=>0,'update_user'=>$this->_adminInfo['email'],'last_update'=>time()],
                "where"=>['goods_sn_main'=>$goods_main_sn],
            ]);
            if($res)
            {
                exit('ok');
            }
        }

	    exit('failed');
	}
}
