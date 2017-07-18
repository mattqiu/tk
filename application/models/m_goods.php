<?php
/**
 *　商品/分类相关
 * @date: 2015-7-10
 * @author: sky yuan
 */
class m_goods extends CI_Model{
	private $doba_url='https://www.doba.com/api/20110301/xml_retailer_api.php';
	private $doba_account='richard@goventura.com';
	private $doba_password='2587Yef831';
	private $doba_id='5076889';
	/* 获取同一分类sku下全部语种的分类  */
	public function get_category($cate_sn,$status=1){
		$rs=$arr=array();

		$this->db->from('mall_goods_category');

		if($status) {
		    $this->db->where('status',$status);
		}

		$rs=$this->db->where('cate_sn',$cate_sn)->get()->result_array();

		foreach($rs as $val) {
			$arr[$val['language_id']]=$val;
		}

		return $arr;
	}

	/* 新增分类  */
    public function add_category($data_db){

        $this->db->trans_begin();
        foreach ($data_db as $key=>$v) {
            $data = $v;
            $data['language_id'] = $key;

            if($this->check_category($data['cate_name'], $data['language_id'],$data['parent_id'])) {
                return  array('error'=>1,'msg' => lang('cate_exist'),'data'=>'2');
            }

            $this->db->insert('mall_goods_category', $data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return  array('error' => 1,'msg' => lang('info_failed'));
            }
            $data['cate_id'] = $this->db->insert_id();
            $erpData[] = $data;
        }
        // 同步到ERP分类添加
        $addDate['data'] = $erpData;
        $url = 'Api/Commodity/addCategory';
        $snyRet = erp_api_query($url, $addDate);
        if ($snyRet['code'] == 200) {
            $this->db->trans_commit();
            return  array('error' => 0,'msg' => lang('info_success'));
        } else {
            $this->db->trans_rollback();
            return  array('error' => 1,'msg' => $snyRet['msg']);
        }
    }

    /* 检查分类名称是否重复 */
    function check_category($cate_name,$lang_id,$parent_id) {
        $rs = $this->db->where('language_id',$lang_id)->where('cate_name',$cate_name)->where('parent_id',$parent_id)->get('mall_goods_category')->row_array();
        return !empty($rs) ? true : false;
    }

	/* 更新分类  */
    public function update_category($data_db) {
        $differentDate = array();
        foreach ($data_db as $key=>$v) {
            $oneDate = $v;
            $oneDate['language_id'] = $key;
            $exitDate = $this->db->get_where('mall_goods_category',array('cate_sn'=>$v['cate_sn'],'language_id'=>(int)$key))->row_array();
            if ($exitDate) {
                $different =  array_diff_assoc($oneDate,$exitDate);
                if ($different) {
                    $different['language_id'] = $key;
                    $different['cate_sn'] = $v['cate_sn'];
                    $differentDate[]=$different;
                }
            }
        }

        if (!empty($differentDate)) { // 如果存在不同的数据才修改
            $this->db->trans_begin();
            foreach ($differentDate as $d) {
                $saveDate = $d;
                unset($saveDate['cate_sn'],$saveDate['language_id']);
                $where=array('cate_sn'=>$d['cate_sn'],'language_id'=>(int)$d['language_id']);
                $this->db->update('mall_goods_category',$saveDate,$where);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return  array('error' => 1,'msg' => lang('info_failed'));
                }
            }
            // 同步更新分类
            $url = 'Api/Commodity/updateCategory';
            $updateDate['data'] = $differentDate;
            $snyRet = erp_api_query($url, $updateDate);
            if ($snyRet['code'] == 200) {
                $this->db->trans_commit();
                return  array('error' => 0,'msg' => lang('info_success'));
            } else {
                $this->db->trans_rollback();
                return  array('error' => 1,'msg' => 'Snyc ERP'.$snyRet['msg']);
            }
        } else {
            return  array('error' => 0,'msg' => lang('info_success'));
        }
    }

	/* 获取所有分类层级关系  */
	public function get_all_category($language_id=''){
		$this->load->model('tb_mall_goods_category');

		$where['status'] = 1;

		if(!empty($language_id)) {
            $where['language_id'] = $language_id;
		}
        $rs  = $this->tb_mall_goods_category->get_list("*",$where,[],10000,0,['sort_order'=>'asc']);
        return $this->cate_level($rs);
		
	}

	/* 获取所有分类  */
	public function get_category_list($language_id=0){

		$this->db->where('status',1);

		if(!empty($language_id)) {
			$this->db->where('language_id',$language_id);
		}

		return $this->db->get('mall_goods_category')->result_array();
	}

	/* 获取分类分页列表  */
	public function get_category_list_page($filter, $per_page = 1000) {
		$this->db->from('mall_goods_category');
		$this->filter_for_category($filter);

		return $this->db->order_by('language_id', 'asc')->order_by('sort_order','asc')->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
	}

	/* 获取分类记录总数  */
	public function get_category_total($filter) {
		$this->db->from('mall_goods_category');
		$this->filter_for_category($filter);

		return $this->db->get()->num_rows();
	}

	/* 通过id获取分类  */
	public function get_cate_info($cate_sn) {
		$language_id=intval($this->session->userdata('language_id'));

		return $this->db->where('language_id',$language_id)->where('status',1)->where('cate_sn',$cate_sn)->get('mall_goods_category')->row_array();
	}

	/* 根据大类id获取最热的5个推荐产品  */
	public function get_recomment_goods_by_cateid($cate_ids, $limit = 5, $order = '' ) {

	    if(empty($cate_ids)) {
	        return array();
	    }
	    $limit = intval($limit);
	    $limit = $limit < 0 ? 5 : $limit;
	    $order = trim($order);
	    $location_id=$this->session->userdata('location_id');
	    $language_id=(int)$this->session->userdata('language_id');

	    $this->db->select('goods_name,goods_sn_main,goods_img,shop_price,market_price,country_flag,is_new,is_hot,is_promote,is_free_shipping,promote_price,promote_start_date,promote_end_date,is_direc_goods')->from('mall_goods_main')->where('is_on_sale',1)->where('is_hot',1)->where('is_best',1)->where('language_id',$language_id)->where("cate_id IN($cate_ids)", null, false)->where('sale_country',$location_id);
	    if ($limit) {
	    	$this->db->limit($limit);
	    }
	    if ($order) {
	    	$this->db->order_by($order);
	    }

	    $rs = $this->db->get()->result_array();
	    
	    if ($rs !== false) {
	        //$time= time();
			$day  = date('Y-m-d H:i:s');
	        foreach($rs as $k=>$row) {

				//$rs[$k]['left_time']=0;
				$rs[$k]['price_off'] = 0;
				/*商品在促销期 */
				if ($row['is_promote'] == 1){
                    $this->load->model("tb_mall_goods_promote");
					$promote = $this->tb_mall_goods_promote->get_goods_promote_info($row['goods_sn_main'],$day);
					if($promote){
                        $this->load->model("o_mall_goods_main");
					    $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price'], $row, $language_id);

					    $rs[$k]['shop_price'] = $promote_info['shop_price'];
					    $rs[$k]['price_off'] = $promote_info['price_off'];

					}
				}
	        }

	    }

	    return $rs;
	}

	/** 
	 * 获取分类产品
	 * 
	 * 添加参数：$is_fields    0 默认 使用* 查询  。  1 使用指定字段查询     （ leon 201-12-30 添加 ）
	 */
	public function get_goods_info_by_cateid($filter,$per_page = 25,$order='',$location_id='',$language_id='',$is_fields='0') {
	    $location_id=$location_id ? $location_id :$this->session->userdata('location_id');
	    $language_id=$language_id ? $language_id : (int)$this->session->userdata('language_id');

	    //leon 添加查询字段 2016-12-30
	    if ($is_fields == 1){
	        $this->db->select('is_hot,is_new,goods_id,goods_img,goods_name,shop_price,country_flag,market_price,comment_count,goods_sn_main,is_direc_goods,is_free_shipping,comment_star_avg,is_promote');
	    }
	    	    
		$this->db->from('mall_goods_main')->where('is_on_sale',1);

		$this->filter_for_category_pre($filter);

		//leon 2016-12-22 取消like的使用
		//$this->db->like('sale_country',$location_id);
		$this->db->where('sale_country',$location_id);
		if(!empty($order)) {
		    $this->db->order_by($order);
		}

		$rs = $this->db->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();

		if ($rs !== false) {
			$day  = date('Y-m-d H:i:s');
		    foreach($rs as $k=>$row) {
                $rs[$k]['old_shop_price'] = $row['shop_price'];
				//$rs[$k]['left_time']=0;
				$rs[$k]['price_off'] = 0;
				/*商品在促销期 */
				if ($row['is_promote'] == 1){
                    $this->load->model("tb_mall_goods_promote");
				    $promote = $this->tb_mall_goods_promote->get_goods_promote_info($row['goods_sn_main'],$day);
				    if($promote){
                        $this->load->model("o_mall_goods_main");
				        $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price'], $row, $language_id);

				        $rs[$k]['shop_price'] = $promote_info['shop_price'];
				        $rs[$k]['price_off'] = $promote_info['price_off'];

				    }

				}
		    }

		}

		return $rs;
	}

	/* 获取浏览历史 */
	public function get_history_list() {
	    $goods_his=get_cookie('goods_history');

	    $history_goods=array();
		if(!empty($goods_his)) {
			$history_goods=$this->get_history_goods(trim($goods_his,','));
		}

	    return $history_goods;
	}

	/* 分类页过滤  */
	public function filter_for_category_pre($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'cate_id':
					$this->db->where("cate_id IN($v)", null, false);
					break;
				case 'price':
					$tmp_arr=explode('-',$v);
					$cur_rate=$this->session->userdata('cur_rate');

					$tmp_arr[0]=format_price_to_dollor($tmp_arr[0],$cur_rate);
					$tmp_arr[1]=format_price_to_dollor($tmp_arr[1],$cur_rate);

					$this->db->where("shop_price between {$tmp_arr[0]} and {$tmp_arr[1]}", null, false);
					break;
				case 'order': //排序方式
					switch($v) {
						case 'composite':
							$this->db->order_by('comment_count','desc');
							break;
						case 'sale':
							$this->db->order_by('comment_star_avg','desc');
							break;
						case 'comments':
							$this->db->order_by('click_count','desc');
							break;
						case 'price':
							$order='asc';
							if($filter['arr'] == 'down') {
								$order='desc';
							}

							$this->db->order_by('shop_price',$order); //价格排序
							break;
					}
				case 'arr': //价格的排序方式
					break;
				case 'keywords':
				    $language_id=isset($filter['language_id']) ? $filter['language_id'] : intval($this->session->userdata('language_id'));

					$this->db->where('language_id',$language_id);
					if(!empty($v)) {
					    $this->db->where("(goods_name like '%$v%' or goods_tags like '%$v%' or goods_sn_main = '$v')",null,false);
					}
					break;
				case 'date': //发布时间在给定日期
				    $start_time=strtotime($v.' 00:00:00');
				    $end_time=strtotime($v.' 23:59:59');
				    $this->db->where("add_time between $start_time and $end_time",null,false);
				    break;
			    case 'date_start': //发布时间在给定日期之后的产品
			        $this->db->where("add_time >= $v",null,false);
			        break;
				case 'country_flag': //leon 增加的 判断是属于哪个国家的产品
					$this->db->where_in("country_flag",$v);
					break;
				case 'is_new':       //leon 增加的 是不是最新产品
					$this->db->where("is_new",$v);
					break;
				case 'is_promote':   //leon 增加的 是不是促销商品
					$this->db->where_in("is_promote",$v);
					break;
				case 'is_hot':       //leon 增加的 是不是热门产品
					$this->db->where_in("is_hot",$v);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}
	/**
	 * 分类页过滤 
	 * leon新增
	 * 优化sql新增内容 
	 * @param unknown $filter
	 */
	public function filter_for_category_pre_new($filter){
	    foreach ($filter as $k => $v) {
	        if (!$v || $k=='page' || 'order' == $k) {
	            continue;
	        }
	        switch ($k) {
	            case 'cate_id':
	                $this->db->where("cate_id IN($v)", null, false);
	                break;
	            case 'price':
	                $tmp_arr=explode('-',$v);
	                $cur_rate=$this->session->userdata('cur_rate');
	
	                $tmp_arr[0]=format_price_to_dollor($tmp_arr[0],$cur_rate);
	                $tmp_arr[1]=format_price_to_dollor($tmp_arr[1],$cur_rate);
	
	                $this->db->where("shop_price between {$tmp_arr[0]} and {$tmp_arr[1]}", null, false);
	                break;
	     /*       case 'order': //排序方式
	                switch($v) {
	                    case 'composite':
	                        $this->db->order_by('comment_count','desc');
	                        break;
	                    case 'sale':
	                        $this->db->order_by('comment_star_avg','desc');
	                        break;
	                    case 'comments':
	                        $this->db->order_by('click_count','desc');
	                        break;
	                    case 'price':
	                        $order='asc';
	                        if($filter['arr'] == 'down') {
	                            $order='desc';
	                        }
	
	                        $this->db->order_by('shop_price',$order); //价格排序
	                        break;
	                }  */
	            case 'arr': //价格的排序方式
	                break;
	            case 'keywords':
	                $language_id=isset($filter['language_id']) ? $filter['language_id'] : intval($this->session->userdata('language_id'));
	
	                $this->db->where('language_id',$language_id);
	                if(!empty($v)) {
	                    $this->db->where("(goods_name like '%$v%' or goods_tags like '%$v%' or goods_sn_main = '$v')",null,false);
	                }
	                break;
	            case 'date': //发布时间在给定日期
	                $start_time=strtotime($v.' 00:00:00');
	                $end_time=strtotime($v.' 23:59:59');
	                $this->db->where("add_time between $start_time and $end_time",null,false);
	                break;
	            case 'date_start': //发布时间在给定日期之后的产品
	                $this->db->where("add_time >= $v",null,false);
	                break;
				case 'country_flag': //leon 增加的 判断是属于哪个国家的产品
					$this->db->where_in("country_flag",$v);
					break;
				case 'is_new':       //leon 增加的 是不是最新产品
					$this->db->where("is_new",$v);
					break;
				case 'is_promote':   //leon 增加的 是不是促销商品
					$this->db->where_in("is_promote",$v);
					break;
				case 'is_hot':       //leon 增加的 是不是热门产品
					$this->db->where_in("is_hot",$v);
					break;
	            default:
	                $this->db->where($k, $v);
	                break;
	        }
	    }
	    return $this->db->count_all_results();
	     
	}

	/**
	 * 获取全球购中 馆 中的产品的分类ID
	 * leon
	 * @param $filter  array()
	 * @return array()
	 */
	public function get_goods_cate_id($filter){

		$this->db->select('cate_id');
		$this->db->from('mall_goods_main');
		$this->db->where('is_on_sale',1);              //销售中的
		$this->db->where_in('country_flag',$filter);   //馆
		$this->db->distinct('cate_id');                //去重
		$query = $this->db->get()->result_array();

		return $query;
	}

	/**
	 * 判读
	 *   属于哪个馆
	 *   当前选中的是哪个商品
	 *
	 * leon
	 */
	public function judge_global_goods($global,$goods=""){

		$global_goods = array();

		switch($global){
			/* 韩国 */
			case 'korea':
				$global_goods['global']="korea";                            //馆的名字
				$global_goods['global_views']="global_ko_shopping";      //馆对应的视图
				$global_goods['state']=array('ko');                      //馆对应的国家简称

				$global_goods['global_ads']='11110';                     //馆信的品牌图片参数
				break;

			/* 美国 */
			case 'america':
				$global_goods['global']="america";
				$global_goods['global_views']="global_us_shopping";
				$global_goods['state']=array('us');

				$global_goods['global_ads']='11112';                     //馆信的品牌图片参数
				break;

			/* 港澳台 */
			case 'gat':
				$global_goods['global']="gat";
				$global_goods['global_views']="global_gat_shopping";
				$global_goods['state']=array('hk','tw','ma');

				$global_goods['global_ads']='11114';                     //馆信的品牌图片参数
				break;

			/* 加拿大 */
			case 'canada':
				$global_goods['global']="canada";
				$global_goods['global_views']="global_ca_shopping";
				$global_goods['state']=array('ca');

				$global_goods['global_ads']='11116';                     //馆信的品牌图片参数
				break;

			/* 日本 */
			case 'japan':
				$global_goods['global']="japan";
				$global_goods['global_views']="global_jp_shopping";
				$global_goods['state']=array('jp');

				$global_goods['global_ads']='11118';                     //馆信的品牌图片参数
				break;

			/* 欧洲 */
			case 'europe':
				$global_goods['global']="europe";
				$global_goods['global_views']="global_oz_shopping";
				$global_goods['state']=array('ho','fr','sp','ge','fi','it','en','che','rus','se','po','ch','kk','gr');

				$global_goods['global_ads']='11120';                     //馆信的品牌图片参数
				break;

			/* 澳洲 */
			case 'australia':
				$global_goods['global']="australia";
				$global_goods['global_views']="global_as_shopping";
				$global_goods['state']=array('as','ne');

				$global_goods['global_ads']='11122';                     //馆信的品牌图片参数
				break;

			/* 东南亚 */
			case 'southeast-asia':
				$global_goods['global']="southeast-asia";
				$global_goods['global_views']="global_dny_shopping";
				$global_goods['state']=array('ph','sg','mal','vie','th','mmr','in');

				$global_goods['global_ads']='11124';                     //馆信的品牌图片参数
				break;
		}

		/* 馆中的产品分类 */
		switch($goods){
			/* 新品上架 */
			case 'is_new':
				$global_goods['goods']="新品上架";
				break;

			/* 热门推荐 */
			case 'is_hot':
				$global_goods['goods']="热门推荐";
				break;

			/* 特惠促销 */
			case 'is_promote':
				$global_goods['goods']="特惠专区";
				break;
		}

		return $global_goods;
	}


	/**
	 * 输出商品销售数量
	 */
	public function goods_sales($goods_sku,$datatime){

		$this->db->select('trade_orders_goods.goods_sn_main,trade_orders_goods.goods_number');
		$this->db->select_sum('trade_orders_goods.goods_number');
		$this->db->from('trade_orders');
		$this->db->join('trade_orders_goods', 'trade_orders_goods.order_id = trade_orders.order_id', 'left');
		$this->db->where("trade_orders.created_at>$datatime",null,false);
		$this->db->where_in('trade_orders_goods.goods_sn_main',$goods_sku);

		$this->db->group_by('trade_orders_goods.goods_sn_main');
		$query = $this->db->get()->result_array();

		//echo $this->db->last_query();
		//print_r($query);

		return $query;



	}












	/* 获取分类页产品总数  */
	public function get_cate_goods_total($filter,$location_id='') {
	    $location_id=$location_id ? $location_id : $this->session->userdata('location_id');

		$this->db->from('mall_goods_main')->where('is_on_sale',1);
		
		$this->db->where('sale_country',$location_id);
		$query = $this->filter_for_category_pre_new($filter);

		//$this->filter_for_category_pre($filter);
		//leon 2016-12-22 取消like的使用
		//return $this->db->like('sale_country',$location_id)->get()->num_rows();
		//$query = $this->db->where('sale_country',$location_id)->get()->num_rows();
		//echo $this->db->last_query();
		
		return $query;
	}

	/* 分类条件查询  */
	public function filter_for_category($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'keywords':
					$v=trim($v);
					$this->db->where("cate_name like '%$v%'", null, false);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}

	/* 无限循环分类 */
	public function cate_level($volist,$current_id=0,$html='&nbsp;&nbsp;',$level=0) {
		$arr=array();

		foreach($volist as $val) {
			if ($val['parent_id'] == $current_id) {
				$val['classname'] = $val['cate_name'];
				$val['html'] = str_repeat($html,$level);
				$val['level'] = $level;
				$arr[] = $val;
				$arr = array_merge($arr,self::cate_level($volist,$val['cate_id'],$html,$level+1));
			}
		}
		return $arr;
	}

	/* 获取商品分页列表  */
	public function get_goods_list_page($filter, $per_page = 10) {
		$this->db->from('mall_goods_main');
		$this->filter_for_goods($filter);

		return $this->db->order_by('add_time','desc')->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();

	}

	/* 获取商品记录总数  */
	public function get_goods_total($filter) {
		$this->db->from('mall_goods_main');
		$this->filter_for_goods($filter);

		return $this->db->get()->num_rows();
	}

	/* 商品查询条件  */
	public function filter_for_goods($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'cate_id':
					$this->db->where("cate_id IN ($v)", null, false);
					break;
				case 'store_id':
					$this->db->where("store_id like '%$v%'", null, false);
					break;
				case 'keywords':
					$v=trim($v);
					$this->db->where("(goods_name like '%$v%' or goods_name_cn like '%$v%' or goods_sn_main like '%$v%')", null, false);
					break;
				case 'start':
					$this->db->where('add_time >=', strtotime($v));
					break;
				case 'end':
					$this->db->where('add_time <=', strtotime($v));
					break;
				case 'state':
					$this->db->where("$v",null,false);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}

	/* 获取供应商分页列表  */
	public function get_supplier_list_page($filter, $per_page = 10) {
		$this->db->from('mall_supplier');
		$this->filter_for_supplier($filter);

		return $this->db->order_by('supplier_addtime','desc')->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
	}

	/* 获取供应商记录总数  */
	public function get_supplier_total($filter) {
		$this->db->from('mall_supplier');
		$this->filter_for_supplier($filter);

		return $this->db->get()->num_rows();
	}

	/* 供应商查询条件  */
	public function filter_for_supplier($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {

				case 'keywords':
					$v=trim($v);
					$this->db->where("(supplier_name like '%$v%' or supplier_user like '%$v%')", null, false);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}

	/* 获取商品套餐分页列表  */
	public function get_goods_group_list_page($filter, $per_page = 10) {
		$this->db->from('mall_goods_group');
		$this->filter_for_goods_group($filter);

		return $this->db->limit($per_page, ($filter['page'] - 1) * $per_page)->order_by('group_id','desc')->get()->result_array();
	}

	/* 获取商品套餐记录总数  */
	public function get_goods_group_total($filter) {
		$this->db->from('mall_goods_group');
		$this->filter_for_goods_group($filter);

		return $this->db->get()->num_rows();
	}

	/* 商品套餐查询条件  */
	public function filter_for_goods_group($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'keywords':
					$this->db->where("group_id=$v", null, false);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}

	/* 获取品牌分页列表  */
	public function get_brand_list_page($filter, $per_page = 10) {
		$this->db->from('mall_goods_brand');
		$this->filter_for_brand($filter);

		return $this->db->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
	}

	/* 获取品牌总数  */
	public function get_brand_total($filter) {
		$this->db->from('mall_goods_brand');
		$this->filter_for_brand($filter);

		return $this->db->get()->num_rows();
	}

	/* 查询条件  */
	public function filter_for_brand($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {
				case 'keywords':
					$this->db->where("brand_name like '%$v%'", null, false);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}

	/* 获取父类下所有底层子类id string 不含本身 */
	function get_children($cat_arr,$cat_id){
		$cat_id_string = '';
		foreach($cat_arr as $kk => $vv){
			if($vv['parent_id'] == $cat_id){
				$cat_id_string .= $vv['cate_id'].','.$this->get_children($cat_arr,$vv['cate_id']);
			}
		}

		return $cat_id_string;
	}

	/* 获取子sku列表 */
	function get_sub_sn($main_sn,$lang_id) {
//		return $this->db->where('goods_sn_main',$main_sn)->where('language_id',$lang_id)->get('mall_goods')->result_array();
        $this->load->model("tb_mall_goods");
        return $this->tb_mall_goods->get_list("*",["goods_sn_main"=>$main_sn,"language_id"=>$lang_id]);
	}

	/* 获取品牌列表  */
	public function getBrandList($cate_id=null) {
		$language_id=intval($this->session->userdata('language_id'));

		if($cate_id) {
		    $cate_id_arr=explode(',', $cate_id);
			$this->db->where_in('cate_id',$cate_id_arr);
		}
		//暂时隐藏不需要品牌 
		$this->db->where_not_in('brand_id', [4,5,6,37,38,39]);

		return $this->db->where('language_id',$language_id)->limit(10)->get('mall_goods_brand')->result_array();
	}

	/* 获取品牌列表  */
	public function getBrandListALL() {
		return $this->db->get('mall_goods_brand')->result_array();
	}

	/* 获取风格列表  */
	public function getEffectList() {
		$language_id=intval($this->session->userdata('language_id'));

		return $this->db->where('language_id',$language_id)->get('mall_goods_effect')->result_array();
	}

	/* 获取同一主sku下全部的产品  */
	public function get_goods($main_sn){
		$rs=$arr=array();

//		$rs=$this->db->from('mall_goods_main m')->join('mall_goods_main_detail','goods_id=goods_main_id','left')->where(array('goods_sn_main'=>$main_sn))->get()->result_array();

        $this->load->model("tb_mall_goods_main_detail");
        $this->load->model("tb_mall_goods_main");
        $rs = $this->tb_mall_goods_main->get_list_auto([
            "where"=>['goods_sn_main'=>$main_sn]
        ]);
        foreach($rs as $k=>$v)
        {
            $tmp = $this->tb_mall_goods_main_detail->get_one_auto([
                "where"=>["goods_main_id"=>$v['goods_id']]
            ]);
            if($tmp)
            {
                $rs[$k] = array_merge($rs[$k],$tmp);
            }
        }

		foreach($rs as $val) {
			$arr[$val['language_id']]=$val;

			//获取子sku
//			$arr[$val['language_id']]['sub_sn_list']=$this->db->where('goods_sn_main',$val['goods_sn_main'])->where('language_id',$val['language_id'])->order_by('goods_sn','asc')->get('mall_goods')->result_array();
            $this->load->model("tb_mall_goods");
            $arr[$val['language_id']]['sub_sn_list']= $this->tb_mall_goods->get_list("*",
                ["goods_sn_main"=>$val['goods_sn_main'],"language_id"=>$val['language_id']],[],1000,0,["goods_sn"=>"asc"]);

			//获取子sku的图片
			foreach($arr[$val['language_id']]['sub_sn_list'] as $k=>$v) {
				$arr[$val['language_id']]['sub_sn_list'][$k]['imgs']=$this->db->where('goods_sn',$v['goods_sn'])->get('mall_goods_gallery')->result_array();
			}

			//获取详情图片
			$arr[$val['language_id']]['detail_imgs']=$this->db->where('goods_sn_main',$val['goods_sn_main'])->get('mall_goods_detail_img')->result_array();
		}
		//print_r($arr);
		return $arr;
	}

	/* 获取供应商  */
	public function get_supplier($supplier_id){

		return $this->db->from('mall_supplier')->where(array('supplier_id'=>$supplier_id))->get()->row_array();

	}

	/* 新增供应商 */
	public function add_supplier($data) {
		unset($data['supplier_id'],$data['is_edit']);
		$data['supplier_addtime']=time();

		return $this->db->insert('mall_supplier',$data);
	}

	/* 编辑供应商 */
	public function update_supplier($data) {
		$supplier_id=$data['supplier_id'];
		unset($data['supplier_id'],$data['is_edit']);
		$data['supplier_addtime']=time();

		return $this->db->where('supplier_id',$supplier_id)->update('mall_supplier',$data);
	}

	/* 检查供应商是否已经存在  */
	public function supplier_check($supplier_name) {
		$rs=array();
		$rs=$this->db->where('supplier_name',$supplier_name)->get('mall_supplier')->row_array();

		return empty($rs) ? false : true;
	}

	/* 检查供应商登录名是否已经存在  */
	public function get_supplier_username($supplier_username) {

		return $this->db->where('supplier_username',$supplier_username)->get('mall_supplier')->row_array();
	}

	/* 获取供应商列表  */
	public function get_supplier_list() {

		return $this->db->get('mall_supplier')->result_array();
	}

	/**
	 * ERP 接口创建供应商
	 */
	public function erpapi_create_supplier($attr) {

		$insertData = array(
			'supplier_id' => $attr['id'],
			'supplier_name' => $attr['supplier'],
			'supplier_address' => $attr['address'],
			'supplier_user' => $attr['contact'],
			'supplier_tel' => $attr['cellphone'],
			'supplier_phone' => $attr['telephone'],
			'supplier_qq' => $attr['qq'],
			'supplier_ww' => $attr['aliim'],
			'supplier_type' => '',
			'supplier_addtime' => time(),
			'supplier_email' => $attr['email'],
			'supplier_link' => $attr['portal'],
			'supplier_username' => '',
			'supplier_password' => '',
			'supplier_last_time' => 0,
			'supplier_login_time' => 0,
			'is_supplier_shipping' => $attr['is_shipper'],
			'supplier_recommend' => $attr['supplier_recommend'],
            'status' => $attr['status'],
            'operator_id' => $attr['operator_id'],
            'country_code' => $attr['country_code'],
            'addr_lv2' => $attr['secondCode'],
            'addr_lv3' => $attr['thirdCode']
		);
		if (false == $this->db->insert('mall_supplier', $insertData)) {
			return false;
		}

		// 插入发货商数据
		if ($attr['is_shipper'] == 1) {
			$insertData = array(
				'shipper_id' => $attr['id'],
				'sale_area' => $attr['sale_area'],
				'freight_company_code' => 0,
				'permit_customer_pickup' => 0,
				'shipping_currency' => $attr['shipping_currency'],
				'area_rule' => $attr['area_rule'],
				'store_location' => $attr['store_location'],
				'store_location_code' => $attr['store_location_code'],
			);
			if (false == $this->db->insert('mall_goods_shipper', $insertData)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * ERP 接口修改供应商
	 */
	public function erpapi_modify_supplier($id, $attr) {

		// 获取供应商信息
		$supplierInfo = $this->db->where('supplier_id',$id)->get('mall_supplier')->row_array();
		if (false === $supplierInfo || $supplierInfo == null) {
			return false;
		}

		// 先过滤出拥有相同 key 的数组，再获取 value 不同的列
		$intersectArr = array_intersect_key($attr, $supplierInfo);
		$updateData = array_diff_assoc($intersectArr, $supplierInfo);
		if ($updateData != array()) {
			// 修改供应商数据
			if (false == $this->db->where('supplier_id', $id)->update('mall_supplier', $updateData)) {
				return false;
			}
		}

		// 修改发货商数据
		if ($supplierInfo['is_supplier_shipping'] == 1) {

			$updateAttr = array();
			if (isset($attr['area_rule'])) {
				$updateAttr['area_rule'] = $attr['area_rule'];
			}
			if (isset($attr['sale_area'])) {
				$updateAttr['sale_area'] = $attr['sale_area'];
			}
			if (isset($attr['store_location_code'])) {
				$updateAttr['store_location_code'] = $attr['store_location_code'];
			}
			if (isset($attr['supplier_address'])) {
				$updateAttr['store_location'] = $attr['supplier_address'];
			}
			if (isset($attr['shipping_currency'])) {
				$updateAttr['shipping_currency'] = $attr['shipping_currency'];
			}
			if ($updateAttr != array()) {
				// 获取发货商信息
				$shipperInfo = $this->db->where('shipper_id',$id)->get('mall_goods_shipper')->row_array();
				if (false === $shipperInfo || $shipperInfo == null) { // 不存在发货商，则插入新发货商
					if (false == $this->db->insert('mall_goods_shipper',  array('shipper_id'=>$id)+$updateAttr)) {
						return false;
					}
				} else {
					if (false == $this->db->where('shipper_id', $id)->update('mall_goods_shipper',$updateAttr)) {
						return false;
					}
				}
			}
		}
		return true;
	}

    /**
	 * ERP 接口修改供应商帐号
	 */
	public function erpapi_modify_supplier_user($id, $attr) {

		return $this->db->where('supplier_id', $id)->update('mall_supplier', $attr);
	}

    /**
     * ERP 接口添加商品
     */
    public function erpapi_create_goods($attr) {
        $debug = false;//是否打开日志，只有调试的时候才能打开，否则必须关闭
        $retData = array(
            'code' => 200,
            'msg' => "",
        );
        $lid = $attr['language_id'];
        //检测是否已存在sku开始
        $goods_sn_data = $this->db->select('goods_sn_main')
            ->where('goods_sn_main', $attr['goods_sn_main'])
            ->get("mall_goods_main")->row_array();

            $this->load->model("tb_empty");
//        $this->load->model("tb_mall_goods_main");
//        $goods_sn_data = $this->tb_mall_goods_main->get_list("goods_sn_main",
//            ['goods_sn_main'=>$attr['goods_sn_main'],"language_id"=>$lid]);
//        $goods_sn_data = $this->tb_mall_goods_main->get_list_auto([
//            "select"=>"goods_sn_main",
//            "where"=>['goods_sn_main'=>$attr['goods_sn_main'],"language_id"=>$lid],
//            "force_master"=>1
//        ]);
        if(!empty($goods_sn_data))
        {
            $tmp_goods_sn = [];
            foreach($goods_sn_data as $v)
            {
                $tmp_goods_sn[] = $v['goods_sn_main'];
            }
            $retData['code'] = 1102;
            $retData['msg'] = "goods_sn_main ".implode(",",$tmp_goods_sn)." already exists!";
            return $retData;
        }
        //检测是否已存在sku结束
		$this->db->trans_begin();
		// 添加主商品数据
		$mainData = array(
			'goods_sn_main' => $attr['goods_sn_main'],
			'cate_id' => $attr['tps_cate_id'],
			'goods_name' => $attr['goods_name'],
			'sale_number' => 0,
			'goods_img' => $attr['goods_img'],
			'goods_weight' => number_format($attr['goods_weight'] / 1000, 3, '.', ''),
			'goods_size' => number_format($attr['goods_volume'] / 1000000, 3, '.', ''),
			'market_price' => number_format($attr['market_price'] / 100, 2, '.', ''),
			'shop_price' => number_format($attr['shop_price'] / 100, 2, '.', ''),
			'is_on_sale' => $attr['tps_sale'],
			'is_delete' => 0,
			'is_best' => $attr['is_best'],
			'is_new' => $attr['is_new'],
			'is_hot' => $attr['is_hot'],
			'is_home' => $attr['is_home'],
			'is_free_shipping' => $attr['is_free_shipping'],
			'is_direc_goods' => $attr['is_direct'],
			'is_for_app' => $attr['is_for_app'],
			'is_alone_sale' => $attr['is_alone_sale'],
			'group_goods_id' => $attr['group_goods_id'],
			'is_for_upgrade' => $attr['is_upgrade'],
			'is_require_id' => $attr['is_require_id'],
			'require_type' => $attr['require_type'],
			'add_user' => "",
			'update_user' => "",
			'seller_note' => $attr['seller_note'],
			'click_count' => 0,
			'comment_count' => 0,
			'comment_star_avg' => 5.0,
			'last_update' => $attr['create_time'],
			'goods_grade' => 1,
			'add_time' => $attr['create_time'],
			'language_id' => $lid,
			'store_code' => "",
			'brand_id' => $attr['tps_brand_id'],
			'effect_id' => 0,
			'country_flag' => $attr['provenance'],
			'like_num' => 0,
			'goods_note' => $attr['goods_note'],
			'sale_country' => $attr['sale_region'],
			'gift_skus' => "",
			'goods_tags' => $attr['goods_tags'],
			'supplier_id' => $attr['supplier_id'],
			'ship_note_type' => 0,
			'ship_note_val' => 0,
			'is_doba_goods' => 0,
			'shipper_id' => $attr['shipper_id'],
			'is_voucher_goods' => $attr['is_voucher_goods'],
			'is_promote' => $attr['is_promote'],
			'is_hg' => $attr['is_hg'],
			'goods_unit' => $attr['goods_unit'],
			'country_code' => $attr['country_code'],

		);
		$this->db->insert('mall_goods_main', $mainData);
		if ($this->db->trans_status() === FALSE) {
			$retData['code']  = 1102;
			$retData['msg'] = "insert main failed, ".$this->db->_error_message();
			$this->db->trans_rollback();
			return $retData;
		}
		$goodsId = $this->db->insert_id();
		// 添加seo部分
		$mainDetailData = array(
			'goods_main_id' => $goodsId,
			'meta_title' => $attr['meta_title'],
			'meta_keywords' => $attr['meta_keywords'],
			'meta_desc' => $attr['meta_desc'],
			'goods_desc' => $attr['goods_desc'],
			'goods_tags' => "",
		);
		$this->db->insert('mall_goods_main_detail', $mainDetailData);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retData['code'] = 1102;
			$retData['msg'] = "insert main detail failed, ".$this->db->_error_message();
			return $retData;
		}
		// 商品详情图
		foreach ($attr['detail_img'] as $dk => $item) {
			$detailImgData = array(
				'goods_sn_main' => $attr['goods_sn_main'],
				'image_url' => $item['img_uri'],
				'language_id' => $lid,
				'img_order' => $dk
			);
			$this->db->insert('mall_goods_detail_img', $detailImgData);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retData['code'] = 1102;
				$retData['msg'] = "insert detail img failed, ".$this->db->_error_message();
				return $retData;
			}
		}
		//海关
		if (isset($attr['hgInfo']) && isset($attr['is_hg']) && $attr['is_hg'] == 1) {
			foreach ($attr['hgInfo'] as $hk => $hv) {
				$hgData = array(
					'goods_sn_main' => $hv['goods_sn_main'],
					'goods_sn' => $hv['goods_sn'],
					'ciqgno' => $hv['ciqgno'],
					'gcode' => $hv['gcode'],
					'gmodel' => $hv['gmodel'],
					'ciqgmodel' => $hv['ciqgmodel'],
				);
				$this->db->insert('mall_goods_customs', $hgData);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$retData['code'] = 1102;
					$retData['msg'] = "insert mall_goods_customs failed, ".$this->db->_error_message();
					return $retData;
				}
			}
		}

		// 子商品
        foreach ($attr['subsidiary'] as $sub) {
			$subData = array(
				'goods_sn_main' => $sub['goods_sn_main'],
				'goods_sn' => $sub['goods_sn'],
				'color' => $sub['color'],
				'size' => $sub['size'],
				'customer' => $sub['customer'],
				'goods_number' => $sub['inventory'],
				'is_lock' => $sub['is_lock'],
				'warn_number' => 1,
				'language_id' => $lid,
				'price' => number_format($sub['price'] / 100, 2, '.', ''),
				'purchase_price' => number_format($sub['purchase_price'] / 100, 2, '.', ''),
				'goods_currency' => $sub['goods_currency'],
			);

			$this->db->insert('mall_goods', $subData);
			/*$this->load->model("tb_mall_goods");
			$this->tb_mall_goods->insert_one($subData);*/
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retData['code'] = 1102;
				$retData['msg'] = "insert sub failed, ".$this->db->_error_message();
				return $retData;
			}
			// 相册
            foreach ($sub['gallery'] as $gk => $v) {
                $galleryData = array(
                    'goods_sn' => $sub['goods_sn'],
                    'thumb_img' => $v['thumb_img'],
                    'big_img' => $v['big_img'],
                    'img_order' => $gk,
                );
				$this->db->insert('mall_goods_gallery', $galleryData);
                if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
                    $retData['code'] = 1102;
                    $retData['msg'] = "insert gallery failed, ".$this->db->_error_message();
                    return $retData;
                }
            }
        }
        // 添加促销信息
        if (isset($attr['is_promote']) && $attr['is_promote'] ==1) {
            $mainSku  = $attr['goods_sn_main'];
            foreach ($attr['promote'] as $p) {
                $promoteData = array(
                    'goods_sn_main' => $mainSku,
                    'goods_sn' => $p['goods_sn'],
                    'promote_price_main' => $p['promote_price_main'],
                    'promote_price' => $p['promote_price'],
                    'start_time' => $p['start_time'],
                    'end_time' => $p['end_time'],
                    'promote_currency' => $p['promote_currency'],
                );
                $this->db->insert('mall_goods_promote', $promoteData);
                if ($this->db->trans_status() === FALSE ) {
                    $retData['code'] = 1102;
                    $retData['msg'] = "insert promote failed, ".$this->db->_error_message();
                    return $retData;
                }
            }
        }
		$this->db->trans_commit();
        return $retData;
    }

    private function execute_start($start=0)
    {
        if($start)
        {
            $this->_start_execute_time_ = $start;
        }else{
            $this->_start_execute_time_ = microtime(true);
        }
    }

    private function execute_end()
    {
        return floatval(microtime(true))-floatval($this->_start_execute_time_);
    }

    /**
     * ERP 接口修改商品
     */
    public function erpapi_modify_goods($attr) {

        $debug = false;//调试日志开关
        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        if($debug){echo(__FILE__.",".__LINE__."<BR>");$this->execute_start();}
        $skuMain = $attr['goods_sn_main'];
        $langId = $attr['language_id'];
        $goods_sn_data = $this->db->select('goods_sn_main,sale_country')
            ->where('goods_sn_main', $attr['goods_sn_main'])
            ->get("mall_goods_main")->row_array();
        if (empty($goods_sn_data)) {
        	$retData['code']  = 1103;
            $retData['msg'] = "update main failed, ".$this->db->_error_message();
            return $retData;
        }

        $sale_country = $goods_sn_data['sale_country'];
        $this->db->trans_begin();
        // 更新主商品表
        $mainData = array();
        if (isset($attr['tps_cate_id'])) {
            $mainData['cate_id'] = $attr['tps_cate_id'];
        }
        if (isset($attr['goods_name'])) {
            $mainData['goods_name'] = $attr['goods_name'];
        }
        if (isset($attr['goods_img'])) {
            $mainData['goods_img'] = $attr['goods_img'];
        }
        if (isset($attr['goods_weight'])) {
            $mainData['goods_weight'] = number_format($attr['goods_weight'] / 1000, 3, '.', '');
        }
        if (isset($attr['goods_volume'])) {
            $mainData['goods_size'] = number_format($attr['goods_volume'] / 1000000, 3, '.', '');
        }
        if (isset($attr['market_price'])) {
            $mainData['market_price'] = number_format($attr['market_price'] / 100, 2, '.', '');
        }
        if (isset($attr['shop_price'])) {
            $mainData['shop_price'] = number_format($attr['shop_price'] / 100, 2, '.', '');
        }
        if (isset($attr['is_promote'])) {
            $mainData['is_promote'] = $attr['is_promote'];
        }
        if (isset($attr['tps_sale'])) {
            $mainData['is_on_sale'] = $attr['tps_sale'];
        }
        if (isset($attr['is_best'])) {
            $mainData['is_best'] = $attr['is_best'];
        }
        if (isset($attr['is_new'])) {
            $mainData['is_new'] = $attr['is_new'];
        }
        if (isset($attr['is_hot'])) {
            $mainData['is_hot'] = $attr['is_hot'];
        }
        if (isset($attr['is_home'])) {
            $mainData['is_home'] = $attr['is_home'];
        }
        if (isset($attr['is_free_shipping'])) {
            $mainData['is_free_shipping'] = $attr['is_free_shipping'];
        }
        if (isset($attr['is_alone_sale'])) {
            $mainData['is_alone_sale'] = $attr['is_alone_sale'];
        }
        if (isset($attr['group_goods_id'])) {
            $mainData['group_goods_id'] = $attr['group_goods_id'];
        }
        if (isset($attr['is_upgrade'])) {
            $mainData['is_for_upgrade'] = $attr['is_upgrade'];
        }
        if (isset($attr['is_require_id'])) {
            $mainData['is_require_id'] = $attr['is_require_id'];
        }
        if (isset($attr['require_type'])) {
            $mainData['require_type'] = $attr['require_type'];
        }
        if (isset($attr['seller_note'])) {
            $mainData['seller_note'] = $attr['seller_note'];
        }
        if (isset($attr['tps_brand_id'])) {
            $mainData['brand_id'] = $attr['tps_brand_id'];
        }
        if (isset($attr['provenance'])) {
            $mainData['country_flag'] = $attr['provenance'];
        }
        if (isset($attr['goods_note'])) {
            $mainData['goods_note'] = $attr['goods_note'];
        }
        if (isset($attr['sale_region'])) {
            $mainData['sale_country'] = $attr['sale_region'];
        }
        if (isset($attr['goods_tags'])) {
            $mainData['goods_tags'] = $attr['goods_tags'];
        }
        if (isset($attr['is_voucher_goods'])) {
            $mainData['is_voucher_goods'] = $attr['is_voucher_goods'];
        }
        if (isset($attr['is_direct'])) {
            $mainData['is_direc_goods'] = $attr['is_direct'];
        }
        if (isset($attr['is_for_app'])) {
            $mainData['is_for_app'] = $attr['is_for_app'];
        }
        // 临时使用用于修复商品数据 供应商和发货方
        if (isset($attr['shipper_id'])) {
            $mainData['shipper_id'] = $attr['shipper_id'];
        }

        if (isset($attr['is_hg'])) {
            $mainData['is_hg'] = $attr['is_hg'];
        }

        if (isset($attr['goods_unit'])) {
            $mainData['goods_unit'] = $attr['goods_unit'];
        }

        if (isset($attr['country_code'])) {
            $mainData['country_code'] = $attr['country_code'];
        }
        // 1.更新主表
        if (!empty($mainData)) {
            $mainData['last_update'] = time();
            $this->db->where('goods_sn_main', $skuMain)->update('mall_goods_main', $mainData);
            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
            if ($this->db->trans_status() === FALSE) {
                $retData['code']  = 1103;
                $retData['msg'] = "update main failed, ".$this->db->_error_message();
                $this->db->trans_rollback();
                return $retData;
            }
        }

        // 2.主商品大信息表
        $mainDetailData = array();
        if (isset($attr['goods_desc'])) {
            $mainDetailData['goods_desc'] = $attr['goods_desc'];
        }
        if (isset($attr['meta_title'])) {
            $mainDetailData['meta_title'] = $attr['meta_title'];
        }
        if (isset($attr['meta_keywords'])) {
            $mainDetailData['meta_keywords'] = $attr['meta_keywords'];
        }
        if (isset($attr['meta_desc'])) {
            $mainDetailData['meta_desc'] = $attr['meta_desc'];
        }
        // 更新mall_goods_main_detail
        if (!empty($mainDetailData)) {
            $mainInfo = $this
                ->db
                ->select("goods_id")
                ->where("goods_sn_main", $skuMain)
                ->get("mall_goods_main")
                ->row_array();
            $this
                ->db
                ->where('goods_main_id', $mainInfo['goods_id'])
                ->update('mall_goods_main_detail', $mainDetailData);
            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
            if ($this->db->trans_status() === FALSE) {
                $retData['code']  = 1103;
                $retData['msg'] = "update main detail failed, ".$this->db->_error_message();
                $this->db->trans_rollback();
                return $retData;
            }
        }

        // 3.商品详情图
        if (isset($attr['detail_img']) && is_array($attr['detail_img'])) {
            foreach ($attr['detail_img'] as $img) {
                // 只定义了 img_uri 表示添加图片
                if (isset($img['img_uri']) && !isset($img['old_uri'])) {
                    $detailImgData = array(
                        'goods_sn_main' => $skuMain,
                        'image_url' => $img['img_uri'],
                        'language_id' => $langId,
                        'img_order' => $img['img_order']
                    );
                    $this->db->insert('mall_goods_detail_img', $detailImgData);
                    if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
                    if ($this->db->trans_status() === FALSE) {
                        $retData['code']  = 1103;
                        $retData['msg'] = "insert detail img failed, ".$this->db->_error_message();
                        $this->db->trans_rollback();
                        return $retData;
                    }
                }
                // 只定义了 old_uri 表示删除图片
                else if (isset($img['old_uri']) && !isset($img['img_uri'])) {
                    $this
                        ->db
                        ->where("goods_sn_main", $skuMain)
                        ->where("language_id", $langId)
                        ->where("image_url", $img['old_uri'])
                        ->delete("mall_goods_detail_img");

                    if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
                    if ($this->db->trans_status() === FALSE) {
                        $retData['code']  = 1103;
                        $retData['msg'] = "delete detail img failed, ".$this->db->_error_message();
                        $this->db->trans_rollback();
                        return $retData;
                    }
                }
                // 只定义了 update_uri 表示更新排序
                else if (isset($img['update_uri'])) {
                    $this
                        ->db
                        ->where("goods_sn_main", $skuMain)
                        ->where("language_id", $langId)
                        ->where("image_url", $img['update_uri'])
                        ->update("mall_goods_detail_img",array('img_order' => $img['img_order']));

                    if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
                    if ($this->db->trans_status() === FALSE) {
                        $retData['code']  = 1103;
                        $retData['msg'] = "update detail img failed, ".$this->db->_error_message();
                        $this->db->trans_rollback();
                        return $retData;
                    }
                }
            }
        }

         //海关 
        // 1.变为非需要海关信息 删除
        $isHg = isset($attr['is_hg'])  ? $attr['is_hg'] : null;
        if (isset($isHg) && $isHg == 0) {
        	$this->db->delete('mall_goods_customs', array('goods_sn_main' => $skuMain));
    	    if ($this->db->trans_status() === FALSE) {
                $retData['code']  = 1103;
                $retData['msg'] = "delete detail mall_goods_customs failed, ".$this->db->_error_message();
                $this->db->trans_rollback();
                return $retData;
            }

        }
         // 2.以前没有直接新增的海关信息 批量插入
        if (isset($isHg) && $isHg == 1) {
        	$this->db->insert_batch('mall_goods_customs', $attr['hgInfo']);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$retData['code'] = 1102;
					$retData['msg'] = "insert mall_goods_customs failed, ".$this->db->_error_message();
					return $retData;
				}
        }

        // 3. 单纯的更新
        if (!isset($isHg) && !empty($attr['hgInfo'] )) {
			foreach ($attr['hgInfo'] as $hk => $hv) {
				$hg_update = $where_hg = array();
				// 修改的时候新增
				if (isset($hv['is_new']) && $hv['is_new'] == 1) {
						$hgData = array(
							'goods_sn_main' => isset($hv['goods_sn_main']) ? $hv['goods_sn_main'] : '',
							'goods_sn'      => isset($hv['goods_sn']) ? $hv['goods_sn'] : '',
							'ciqgno'        => isset($hv['ciqgno']) ? $hv['ciqgno'] : '',
							'gcode'         => isset($hv['gcode']) ? $hv['gcode'] : '',
							'gmodel'        => isset($hv['gmodel']) ? $hv['gmodel'] : '',
							'ciqgmodel'     => isset($hv['ciqgmodel']) ? $hv['ciqgmodel'] : '', 
						);
						$this->db->insert('mall_goods_customs', $hgData);
						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							$retData['code'] = 1102;
							$retData['msg'] = "insert mall_goods_customs failed, ".$this->db->_error_message();
							return $retData;
						}
				} else {
					//只修改的有传值
					foreach ($hv as $k => $v) {
						if ('goods_sn' == $k) {
							$where_hg['goods_sn'] = $v;
							continue;
						}
						$hg_update[$k] = $v;
					}
					$this->db->where($where_hg)->update('mall_goods_customs', $hg_update);
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$retData['code'] = 1102;
						$retData['msg'] = "update mall_goods_customs failed, ".$this->db->_error_message();
						return $retData;
					}
				}
			}
        }

        // 4.子商品 更新部分
        if (isset($attr['subsidiary']) && is_array($attr['subsidiary'])) {
            foreach ($attr['subsidiary'] as $sn => $sub) {
                // 子商品表，不区分语言
                $goodsSn = $sub['goods_sn'];
                $subData = array();
                if (isset($sub['color'])) {
                    $subData['color'] = $sub['color'];
                }
                if (isset($sub['size'])) {
                    $subData['size'] = $sub['size'];
                }
                if (isset($sub['customer'])) {
                    $subData['customer'] = $sub['customer'];
                }
                if (isset($sub['price'])) {
                    $subData['price'] = number_format($sub['price'] / 100, 2, '.', '');
                }
                if (isset($sub['purchase_price'])) {
                    $subData['purchase_price'] = number_format($sub['purchase_price'] / 100, 2, '.', '');
                }
                if (isset($sub['inventory'])) {
                    $subData['goods_number'] = $sub['inventory'];
                }
                if (isset($sub['is_lock'])) {
                    $subData['is_lock'] = $sub['is_lock'];
                }
                if (isset($sub['goods_currency'])) {
                    $subData['goods_currency'] = $sub['goods_currency'];
                }
                if (!empty($subData)) {
                    $this->db->where('goods_sn', $sub['goods_sn'])->update('mall_goods', $subData);
                    $this->load->model("tb_mall_goods");
//                    $this->tb_mall_goods->update_one(["goods_sn"=>$sub['goods_sn']],$subData);
                    //更新redis里的独立库存
                    if(isset($subData['goods_number']))
                    {
                        $mall_goods_tmp = $this->tb_mall_goods->get_one("product_id",['goods_sn'=>$sub['goods_sn']]);
                        $this->tb_mall_goods->mall_goods_redis_log($mall_goods_tmp['product_id'],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                        $this->tb_mall_goods->update_goods_number_in_redis($mall_goods_tmp['product_id'],$subData['goods_number']);
                    }
                    if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
                    if ($this->db->trans_status() === FALSE) {
                        $retData['code']  = 1103;
                        $retData['msg'] = "update sub img failed, ".$this->db->_error_message();
                        $this->db->trans_rollback();
                        return $retData;
                    }
                }

                // 相册图
                if (isset($sub['gallery']) && is_array($sub['gallery'])) {
                    foreach ($sub['gallery'] as $v) {
                        // 只定义了 big_img thumb_img 表示添加图片
                        if (isset($v['big_img']) && isset($v['thumb_img']) && !isset($v['old_big_img'])) {
                            $galleryData = array(
                                'goods_sn' => $sub['goods_sn'],
                                'thumb_img' => $v['thumb_img'],
                                'big_img' => $v['big_img'],
                                'img_order' => $v['img_order']
                            );
                            $this->db->insert('mall_goods_gallery', $galleryData);
                            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
                            if ($this->db->trans_status() === FALSE) {
                                $retData['code']  = 1103;
                                $retData['msg'] = "insert gallery failed, ".$this->db->_error_message();
                                $this->db->trans_rollback();
                                return $retData;
                            }
                        }
                        // 只定义了 old_big_img 表示删除图片
                        else if (isset($v['old_big_img'])) {
                            $this
                                ->db
                                ->where("goods_sn", $goodsSn)
                                ->where("big_img", $v['old_big_img'])
                                ->delete("mall_goods_gallery");

                            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
                            if ($this->db->trans_status() === FALSE) {
                                $retData['code']  = 1103;
                                $retData['msg'] = "delete gallery failed, ".$this->db->_error_message();
                                $this->db->trans_rollback();
                                return $retData;
                            }
                            //  只针对更新相册排序
                        } else if ($v['update_img']) {
                                $this
                                ->db
                                ->where("goods_sn", $goodsSn)
                                ->where("big_img", $v['update_img'])
                                ->update("mall_goods_gallery",array('img_order' => $v['img_order']));
                            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
                            if ($this->db->trans_status() === FALSE) {
                                $retData['code']  = 1103;
                                $retData['msg'] = "update gallery failed, ".$this->db->_error_message();
                                $this->db->trans_rollback();
                                return $retData;
                            }
                        }
                    }
                }
            }
        }

        // 5.有新的子商品添加
        if (!empty($attr['new_subsidiary'])) {
            foreach ($attr['new_subsidiary'] as $sv) {
                $newSn = $sv['goods_sn'];
                $addSubData[] = array(
                    'goods_sn_main' => $sv['goods_sn_main'],
                    'goods_sn' => $newSn,
                    'color' => $sv['color'],
                    'size' => $sv['size'],
                    'customer' => $sv['customer'],
                    'goods_number' => $sv['inventory'],
                    'is_lock' => $sv['is_lock'],
                    'warn_number' => 1,
                    'language_id' => $langId,
                    'price' => number_format($sv['price'] / 100, 2, '.', ''),
                    'purchase_price' => number_format($sv['purchase_price'] / 100, 2, '.', ''),
                    'goods_currency' => $sv['goods_currency'],
                    
                );
                // 添加相册
                foreach ($sv['gallery'] as $gk => $gv) {
                    $insertGalleryData[] = array(
                        'goods_sn' => $newSn,
                        'thumb_img' => $gv['thumb_img'],
                        'big_img' => $gv['big_img'],
                        'img_order' => $gk,
                    );
                }
            }
            // 批量插入子商品数据
            $this->db->insert_batch('mall_goods', $addSubData);
//            $this->load->model("tb_mall_goods");
//            $this->tb_mall_goods->insert_batch($addSubData);

            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $retData['code'] = 1103;
                $retData['msg'] = "insert sub goods failed, ".$this->db->_error_message();
                return $retData;
            }
            // 批量添加相册
            $this->db->insert_batch('mall_goods_gallery', $insertGalleryData);
            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $retData['code'] = 1102;
                $retData['msg'] = "insert gallery failed, ".$this->db->_error_message();
                return $retData;
            }
        }


        // 6.促销部分 如果非促销商品查询是否原来有数据 有就删除
        if (isset($attr['is_promote']) && $attr['is_promote'] == 0) {
            $this->delete_promote($skuMain);
            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $retData['code'] = 1103;
                $retData['msg'] = "delete goods promote failed, ".$this->db->_error_message();
                return $retData;
            }
        }

        // 7.更新促销信息
        if (isset($attr['promote'])) {
            // 删除原有的添加传过来的数据
            $this->delete_promote($skuMain);
            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $retData['code'] = 1103;
                $retData['msg'] = "delete goods promote failed, ".$this->db->_error_message();
                return $retData;
            }
            $this->db->insert_batch('mall_goods_promote',$attr['promote']);
            if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $retData['code'] = 1103;
                $retData['msg'] = "add goods promote failed, ".$this->db->_error_message();
                return $retData;
            }
        }
        if($debug){echo($this->execute_end().",".__FILE__.",".__LINE__."<BR>");}
        
        $this->db->trans_commit();
        return $retData;
    }

    /**
     * ERP 接口批量修改商品（新）
     */
    public function erpapi_batch_modify_goods($data) {

        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        $this->db->trans_begin();
        try {
        foreach ($data as $goods) {
            // 主商品表
            $mainData = array();
            if (isset($goods['goods_name'])) {
                $mainData['goods_name'] = $goods['goods_name'];
            }
            if (isset($goods['cate_id'])) {
                $mainData['cate_id'] = $goods['cate_id'];
            }
            if (isset($goods['is_promote'])) {
                $mainData['is_promote'] = $goods['is_promote'];
            }
            if (isset($goods['shop_price'])) {
                $mainData['shop_price'] = number_format($goods['shop_price'] / 100, 2, '.', '');
            }
            if (isset($goods['goods_note'])) {
                $mainData['goods_note'] = $goods['goods_note'];
            }
            $goodsSnMain = isset($goods['goods_sn_main']) ? $goods['goods_sn_main'] : '';
            if (!empty($mainData)) {
                $mainData['last_update'] = time();
        			$this->db->where('goods_sn_main', $goodsSnMain)->update('mall_goods_main', $mainData);
        			$success = $this->db->affected_rows();
        			if ($goodsSnMain == '' || $success < 1) {
        				$this->db->trans_rollback();
        				$retData['code'] = 1103;
        				$retData['msg'] = "update main failed, ".$this->db->_error_message();
        				return $retData;
        			}
        		}
        	
        		// 子商品表
        		if (isset($goods['subsidiary']) && is_array($goods['subsidiary'])) {
        			foreach ($goods['subsidiary'] as $sub) {
        				if (!isset($sub['goods_sn'])) {
        					continue;
        				}
        	
        				$subData = array();
        				if (isset($sub['price'])) {
        					$subData['price'] = number_format($sub['price'] / 100, 2, '.', '');
        				}
        				// 新增采购价
        				if (isset($sub['purchase_price'])) {
        					$subData['purchase_price'] = number_format($sub['purchase_price'] / 100, 2, '.', '');
        				}
        				if (!empty($subData)) {
        					$this->load->model("tb_mall_goods");
        					$res = $this->tb_mall_goods->update_one(['goods_sn'=>$sub['goods_sn']],$subData);
        					//                        if (false === $this->db->where('goods_sn', $sub['goods_sn'])->update('mall_goods', $subData)) {
        					if (false === $res) {
        					$this->db->trans_rollback();
                            $retData['code'] = 1103;
                            $retData['msg'] = "update sub failed, ".$this->db->_error_message();
                            return $retData;
                        }
                    }
                }
            }

            // 删除促销数据
            if (isset($goods['is_promote'])) {
                $delPromote = $this->db->where('goods_sn_main', $goodsSnMain)->delete('mall_goods_promote');
                if ($delPromote === false) {
        			$this->db->trans_rollback();
                    $retData['code'] = 1103;
                    $retData['msg'] = "delete promote failed, ".$this->db->_error_message();
                    return $retData;
                }
            }
        }
        	$this->db->trans_commit();
        } catch (Exception $e) {
        	$this->db->trans_rollback();
        	$retData['code'] = 1103;
        	$retData['msg'] = "process execute failed, ".$e;
        	return $retData;
        }

        return $retData;
    }

    /**
     * 删除促销信息
     */
    public function delete_promote($sn_main) {
        $exitRet = $this
            ->db
            ->select("goods_sn_main")
            ->where("goods_sn_main", $sn_main)
            ->get("mall_goods_promote")
            ->row_array();
        if ($exitRet) {
            $ret = $this->db->delete('mall_goods_promote',array('goods_sn_main'=>$sn_main));
            return $ret;
        }
        return true;
    }

    /**
     * ERP 接口修改商品库存
     */
    public function erpapi_modify_goods_store($attr) {

        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        if (empty($attr['goods_sn'])) {
            $retData['code'] = 1001;
            $retData['msg'] = "goods sn can not be null";
            return $retData;
        }

        $updateData = array(
            'goods_number' => $attr['store_number'],
        );
        $this->load->model("tb_mall_goods");
//        $res = $this->tb_mall_goods->update_one(['goods_sn'=>$attr['goods_sn']],$updateData);
        $mall_goods_tmp = $this->tb_mall_goods->get_one("product_id",['goods_sn'=>$attr['goods_sn']]);
        $this->tb_mall_goods->mall_goods_redis_log($mall_goods_tmp['product_id'],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
        $res =  $this->tb_mall_goods->update_goods_number_in_redis($mall_goods_tmp['product_id'],$updateData['goods_number']);
//        if (false === $this->db->where('goods_sn', $attr['goods_sn'])->update('mall_goods', $updateData)) {
            if (false === $res) {
            $retData['code'] = 1103;
            $retData['msg'] = "update failed, ".$this->db->_error_message();
            return $retData;
        }

        return $retData;
    }

    /**
     * 取消促销数据 更改状态
     */
    public function erpapi_cancel_promote($attr){
        $retData = array(
            'code' => 200,
            'msg' => "",
        );
        $sku = trim($attr['goods_sn_main']);
        $sn = trim($attr['goods_sn']);
        $type = trim($attr['price_adjust_type']);
        $price = number_format(intval($attr['promote_price']) / 100, 2, '.', '');
        if (!$sku || !$sn || !$type || !$price) {
            $retData['code'] = 1104;
            $retData['msg'] = "缺少参数";
            return $retData;
        } else {
        	$this->db->trans_begin();
            // 1.删掉促销数据
            $where = array('goods_sn' => $sn);
            $delPromote = $this->db->where($where)->delete('mall_goods_promote');
            if (!$delPromote) {
            	$this->db->trans_rollback();
                $retData['code'] = 1104;
                $retData['msg'] = "删除促销数据失败 sn:".$sn;
                return $retData;
            }
            // 2.更改促销标识
            $mainWhere = array('goods_sn_main' => $sku);
            $promoteInfo = $this->db->select("is_promote")
                ->where($mainWhere)
                ->get("mall_goods_main")
                ->row_array();
            if ($promoteInfo['is_promote'] == 1) {
                $upPromoteMark = $this->db->where($mainWhere)->update('mall_goods_main', array('is_promote' => 0));
                if (!$upPromoteMark) {
                	$this->db->trans_rollback();
                    $retData['code'] = 1104;
                    $retData['msg'] = "更新促销标识失败 sku:".$sku;
                    return $retData;
                }
            }
            // 判断价格类型
            if ($type == 2) {
                // 更改主商品价格
                $mainPrice = number_format(intval($attr['promote_price_main']) / 100, 2, '.', '');
                $mainPriceInfo = $this->db->select("shop_price")->where($mainWhere)
                    ->get("mall_goods_main")
                    ->row_array();
                if ($mainPriceInfo['shop_price'] != $mainPrice) {
                    $upPriceRet = $this->db->where($mainWhere)->update('mall_goods_main', array('shop_price' => $mainPrice));
                    if (!$upPriceRet) {
                    	$this->db->trans_rollback();
                        $retData['code'] = 1104;
                        $retData['msg'] = "更新促销标识失败 sku:".$sku;
                        return $retData;
                    }
                }

                // 更改子商品价格
//                $goodsInfo = $this->db->select("price")->where($where)
//                    ->get("mall_goods")
//                    ->row_array();
                $this->load->model("tb_mall_goods");
                $goodsInfo =  $this->tb_mall_goods->get_one("price",$where);
                if ($goodsInfo['price'] != $price) {
                    $upPriceRet = $this->db->where($where)->update('mall_goods', array('price' => $price));
                    //$upPriceRet = $this->tb_mall_goods->update_one($where,['price'=>$price]);
                    if (!$upPriceRet) {
                    	$this->db->trans_rollback();
                        $retData['code'] = 1104;
                        $retData['msg'] = "更新促销标识失败 sku:".$sku;
                        return $retData;
                    }
                }
            }
            
           
            $this->db->trans_commit();
            return $retData;
        }
    }

    /**
     * 修复描述图片接口使用
     * @param $attr
     * @return array
     * @author james
     */
	public function repair_desc_pic($attr) {
        $retData = array(
            'code' => 200,
            'msg' => "",
        );
        // 先删除描述图片的数据 在添加
        $sku = $attr['sku'];
        $delRet = $this
            ->db
            ->where("goods_sn_main", $sku)
            ->delete("mall_goods_detail_img");

        if ($delRet === FALSE) {
            $retData['code']  = 1103;
            $retData['msg'] = "delete detail img failed, ".$this->db->_error_message();
            $this->db->trans_rollback();
            return $retData;
        }
        $batchData = $attr['data'];
        $addRet = $this->db->insert_batch('mall_goods_detail_img', $batchData);
        if ($addRet === FALSE) {
            $retData['code']  = 1103;
            $retData['msg'] = "add detail img failed, ".$this->db->_error_message();
            $this->db->trans_rollback();
            return $retData;
        }

        return $retData;
    }

    /* 获取颜色尺码等属性 */
    public function get_goods_attr($attr_name,$where_language=true) {

		$language_id=intval($this->session->userdata('language_id'));

		if($where_language) {
			$this->db->where('language_id',$language_id);
		}

		return $this->db->where('attr_name',$attr_name)->order_by('attr_values')->get('mall_goods_attribute')->result_array();
	}

	/* 保存详细图路径到数据库  */
	public function save_detail_img($main_sn,$path,$lang_id) {
		if(!$this->db->insert('mall_goods_detail_img',array('goods_sn_main'=>$main_sn,'image_url'=>$path,'language_id'=>$lang_id))) {
			return false;
		}

		return $this->db->insert_id();

	}

	/* 保存相册图路径到数据库  */
	public function save_gall_img($sub_sn,$path_thumb,$path_big) {
		if(!$this->db->insert('mall_goods_gallery',array('goods_sn'=>$sub_sn,'thumb_img'=>$path_thumb,'big_img'=>$path_big))) {
			return false;
		}

		return $this->db->insert_id();

	}

	/* 删除图片数据记录  */
	public function del_img($table_name,$img_id) {
		return $this->db->where('img_id',$img_id)->delete($table_name);
	}

	/* 删除相册中图片和图片数据记录  */
	public function del_img_gallery($table_name,$img_id) {
		$rs=$this->db->where('img_id',$img_id)->get($table_name)->row_array();

		if(unlink($rs['thumb_img']) && unlink($rs['big_img']) && $this->db->where('img_id',$img_id)->delete($table_name)) {
			return true;
		}

		return false;
	}

	/* 新增商品  */
	public function add_goods($data_db,$add_user){
		$sub_sku=$data_db['goods_sku'];
		unset($data_db['goods_sku']);

		//增加子sku信息
		foreach($sub_sku as $v) {
			$v['language_id']=$data_db['language_id'];
			$v['goods_sn_main']=$data_db['goods_sn_main'];
			$v['price']=$v['price'] > 0 ? $v['price'] : $data_db['shop_price'];

			$this->db->insert('mall_goods',$v);
		}

		$data['meta_title']=$data_db['meta_title'];
		$data['meta_keywords']=isset($data_db['meta_keywords']) ? $data_db['meta_keywords'] : '';
		$data['meta_desc']=isset($data_db['meta_desc']) ? $data_db['meta_desc'] : '';
		$data['goods_desc']=$data_db['goods_desc'];
		unset($data_db['meta_title'],$data_db['meta_keywords'],$data_db['meta_desc'],$data_db['goods_desc']);

		//增加主产品信息
		$data_db['goods_tags']=$data['meta_keywords'];
		$data_db['add_user']=$add_user;
		$data_db['add_time']=time();
		$data_db['goods_id']=NULL;
		$data_db['group_goods_id']=isset($data_db['group_goods_id']) ? $data_db['group_goods_id'] : 0;
		$this->db->insert('mall_goods_main',$data_db);
		//echo $this->db->last_query();

		//增加主产品扩展信息
		$data['goods_main_id']=$this->db->insert_id();

		return $this->db->insert('mall_goods_main_detail',$data);
	}

	/* 更新商品  */
	public function update_goods($data_db,$language_id,$update_user='',$admin_id = 0){
		$sub_sku=$data_db['goods_sku'];
		$goods_id=$data_db['goods_id'];
		unset($data_db['goods_sku'],$data_db['goods_id']);

		//更新子sku信息
		foreach($sub_sku as $v) {
			$v['price']=$v['price']>0 ? $v['price'] : $data_db['shop_price'];

			//检测是否新增了子sku
			$is_exist=$this->db->where('language_id',$language_id)->where('goods_sn',$v['goods_sn'])->get('mall_goods')->row_array();
			if(empty($is_exist)) {
				$v['language_id']=$language_id;
				$v['goods_sn_main']=$data_db['goods_sn_main'];

				$this->db->insert('mall_goods',$v);
			}else {
				$this->db->update('mall_goods',$v,array('language_id'=>$language_id,'goods_sn'=>$v['goods_sn']));
			}
		}

		$data['meta_title']=$data_db['meta_title'];
		$data['meta_keywords']=isset($data_db['meta_keywords']) ? $data_db['meta_keywords'] : '';
		$data['meta_desc']=isset($data_db['meta_desc']) ? $data_db['meta_desc'] : '';
		$data['goods_desc']=$data_db['goods_desc'];
		unset($data_db['meta_title'],$data_db['meta_keywords'],$data_db['meta_desc'],$data_db['goods_desc']);

		/** 如果发货商变动了 记录操作人 by john */
		$old_store = $this->db->select('shipper_id')->where('language_id',$language_id)->where('goods_sn_main',$data_db['goods_sn_main'])->get('mall_goods_main')->row_array();
		if($old_store['shipper_id'] != $data_db['shipper_id']){
			$this->m_log->adminActionLog($admin_id,'admin_change_shipper_id','mall_goods_main',$data_db['goods_sn_main'],'shipper_id',
				$old_store['shipper_id'],$data_db['shipper_id']);
		}

		//更新主产品信息
		$data_db['last_update']=time();
		$data_db['update_user']=$update_user;
		$data_db['goods_tags']=$data['meta_keywords'];
		$this->db->update('mall_goods_main',$data_db,array('language_id'=>$language_id,'goods_sn_main'=>$data_db['goods_sn_main']));

		//更新主产品扩展信息
		return $this->db->update('mall_goods_main_detail',$data,array('goods_main_id'=>$goods_id));
	}

	/* 删除子sku以及清理其的图片 */
	public function del_sub_sn($product_id,$goods_sn) {
		/* 	$rs_img=$this->db->where('goods_sn',$goods_sn)->get('mall_goods_gallery')->result_array();

			foreach($rs_img as $v) {
				unlink($v['thumb_img']);
				unlink($v['big_img']);

				$this->db->where('img_id',$v['img_id'])->delete('mall_goods_gallery');

			} */

		return $this->db->where('product_id',$product_id)->delete('mall_goods');
	}

	/* 获取产品详细  */
	public function get_goods_info($goods_sn_main,$goods_sn,$is_prev_view=false) {
		$language_id=(int)$this->session->userdata('language_id');
		$location_id=$this->session->userdata('location_id');
		$goods_main=array();

		//leon 2016-12-22 取消like的使用
		//$this->db->where('language_id',$language_id)->where('goods_sn_main',$goods_sn_main)->where("sale_country like '%$location_id%' ");
		$this->db->where('language_id',$language_id)->where('goods_sn_main',$goods_sn_main)->where('sale_country',$location_id);

		/* if($is_prev_view) {
		    $this->db->where('(is_on_sale=2 or is_on_sale=1)',null,false); //后台预览
		} */

		$goods_main=$this->db->where('is_delete',0)->get('mall_goods_main')->row_array();

		if(!empty($goods_main)) {
			$goods_main['sale_country']=$this->get_sale_country_str($goods_main['sale_country']);

			//获取商品扩展信息
//			$goods_main['extends']=$this->db->where('goods_main_id',$goods_main['goods_id'])->get('mall_goods_main_detail')->row_array();
			$this->load->model("tb_mall_goods_main_detail");
            $goods_main['extends']=$this->tb_mall_goods_main_detail->get_one_auto(
                [
                    "where"=>['goods_main_id'=>$goods_main['goods_id']],
                ]);

			if(empty($goods_sn)) {
				$goods_sn=$goods_main['goods_sn_main'].'-1';
			}

			//获取商品子sku信息
			//$goods_sub=$this->db->where('goods_sn_main',$goods_main['goods_sn_main'])->where('language_id',$language_id)->get('mall_goods g')->result_array();
			$this->load->model("tb_mall_goods");
            $goods_sub = $this->tb_mall_goods->get_list("*",
                ['goods_sn_main'=>$goods_main['goods_sn_main'],'language_id'=>$language_id]);

            $attr_arr=$color_arr=$size_arr=$other_arr=$size_key_arr=$customer_key_arr = array();
			foreach($goods_sub as $k=>$v){

				$attr_arr[$k]['sn']=$v['goods_sn'];
				$attr_arr[$k]['color']=$v['color'];
				$attr_arr[$k]['size']=$v['size'];
				$attr_arr[$k]['other']=$v['customer'];
				$attr_arr[$k]['number']=$v['goods_number'];
				$v['customer'] = trim($v['customer']);

				if(!empty($v['color']) && !array_key_exists($v['color'], $color_arr)) {
					$tmp_row=$this->db->select('thumb_img')->where('goods_sn',$v['goods_sn'])->order_by('img_order','asc')->get('mall_goods_gallery')->row_array();
					$color_arr[$v['color']]=isset($tmp_row['thumb_img'])?$tmp_row['thumb_img']:'';
				}

				if(!empty($v['size']) && !in_array($v['size'],$size_arr)) {
					$size_arr[]=$v['size'];
				}
                //检查是否有尺寸
				/*$size_key = $v['customer'].'-'.$v['color'];
				if ($v['goods_number'] > 0 && !empty($v['size']) && (!empty($v['color']) || !empty($v['customer']))  && !in_array($v['size'],$size_key_arr[$v['customer']])) {
					$size_key_arr[$size_key][] = $v['size'];
				}
				if (!isset($size_key_arr[$v['goods_sn']])) {
						$size_key_arr[$v['goods_sn']] = $size_key;
				}*/

				if(!empty($v['customer']) && !in_array($v['customer'],$other_arr)) {
					$other_arr[]=$v['customer'];
				}
                //检查是否有规格
			/*	if ($v['color']) {
					$customer_key = 'color_'.$v['color'];
					if ($v['goods_number'] > 0 && !empty($v['customer'])  && !empty($v['color'])  && !in_array($v['customer'],$customer_key_arr[$customer_key])) {
						$customer_key_arr[$customer_key][] = $v['customer'];
					}

					if (!$customer_key_arr[$v['goods_sn']]) {
						$customer_key_arr[$v['goods_sn']] = $customer_key;
					}
				}*/
				if($v['goods_sn'] == $goods_sn) {
					$goods_main['goods_number']=$v['goods_number'];

					if($v['price'] > 0) {
						$goods_main['shop_price']=$v['price']; //优先选择子sku的私有价格
					}

					$goods_main['color']=$v['color'];
					$goods_main['size']=$v['size'];
					$goods_main['other']=$v['customer'];
				}
			}
			sort($size_arr);
			$goods_main['sn_list']=json_encode($attr_arr);
			$goods_main['color_list']=$color_arr;
			$goods_main['size_list']=$size_arr;
			$goods_main['other_list']=$other_arr;
            $goods_main['customer_list']=$size_key_arr;
			$goods_main['customer_key_arr'] = $customer_key_arr;


			//获取商品相册图
			//$goods_main['img_list'] = $this->db->where('goods_sn',$goods_sn)->order_by('img_order')->get('mall_goods_gallery')->result_array();
            $this->load->model("tb_mall_goods_gallery");
            $goods_main['img_list'] = $this->tb_mall_goods_gallery->get_list("*",["goods_sn"=>$goods_sn],[],100,0,["img_order"=>"asc"]);
                //获取商品详细图
            //$detail_img = $this->db->where('language_id',$language_id)->where('goods_sn_main',$goods_main['goods_sn_main'])->limit(2)->get('mall_goods_detail_img')->result_array();
            $this->load->model("tb_mall_goods_detail_img");
            $detail_img = $this->tb_mall_goods_detail_img->get_list("*",["language_id"=>$language_id,"goods_sn_main"=>$goods_main['goods_sn_main']],[],2);
            if (count($detail_img)>1 && $detail_img[0]['img_order']==0 && $detail_img[0]['img_order'] ==$detail_img[1]['img_order']) {
                $order = 'img_id';
            }else{
                $order = 'img_order';
            }
            $goods_main['detail_img_list'] = $this->tb_mall_goods_detail_img->get_list("*",
                ["language_id"=>$language_id,"goods_sn_main"=>$goods_main['goods_sn_main']],[],100,0,[$order=>"asc"]);
			//$goods_main['detail_img_list'] = $this->db->where('language_id',$language_id)->where('goods_sn_main',$goods_main['goods_sn_main'])->order_by($order)->get('mall_goods_detail_img')->result_array();
		}

		return $goods_main;
	}

	/* 获取当前id的父级面包削导航  */
	public function get_nav_title($cate_list,$cate_id){
		static $nav_title='';

		if($cate_id !=0){
			foreach($cate_list as $keys =>$row){
				$isparent = false;
				if($row['cate_id']==$cate_id){
					if($row['parent_id']==0)$isparent = true;
					$thisurl =  base_url().'index/category?sn='.$row['cate_sn'];
					$nav_title = " &gt; <a href=\"$thisurl\" title=\"$row[cate_name]\" rel=\"nofollow\">".$row["cate_name"]."</a>".$nav_title;
					if($row["parent_id"]!=0)$this->get_nav_title($cate_list,$row["parent_id"]);
				}
			}
		}

		return $nav_title;
	}

	/* 获取某个产品的收藏数量  */
	function get_wish_goods_count($goods_id) {

		return count($this->db->where('goods_id',$goods_id)->get('mall_wish')->result_array());
	}

	/* 增加浏览量  */
	function add_goods_click($goods_id) {
		$res =  $this->db->set('click_count','click_count+1',false)->where('goods_id',$goods_id)->update('mall_goods_main');
		return $res;
	}

	/* 获取浏览历史  */
	function get_history_goods($goods_ids) {
	    $language_id=(int)$this->session->userdata('language_id');
	    $location_id=$this->session->userdata('location_id');
		
	    //leon 2016-12-22 取消like的使用
	    //$sql="select goods_sn_main,goods_name,shop_price,is_free_shipping,is_hot,is_new,market_price,goods_img,country_flag,is_promote,is_direc_goods from mall_goods_main where goods_id IN($goods_ids) and language_id=$language_id and sale_country like '%$location_id%' and is_on_sale=1 order by find_in_set(goods_id,'".$goods_ids."')";
		$sql="select goods_sn_main,goods_name,shop_price,is_free_shipping,is_hot,is_new,market_price,goods_img,country_flag,is_promote,is_direc_goods from mall_goods_main where goods_id IN($goods_ids) and language_id=$language_id and sale_country='$location_id' and is_on_sale=1 order by find_in_set(goods_id,'".$goods_ids."')";

		$rs = $this->db->query($sql)->result_array();

		if ($rs !== false) {
		    //$time= time();
			$day  = date('Y-m-d H:i:s');
		    foreach($rs as $k=>$row) {

				//$rs[$k]['left_time']=0;
				$rs[$k]['price_off'] = 0;
				/*商品在促销期 */
				if ($row['is_promote'] == 1){
                    $this->load->model("tb_mall_goods_promote");
				    $promote = $this->tb_mall_goods_promote->get_goods_promote_info($row['goods_sn_main'],$day);
				    if($promote){
                        $this->load->model("o_mall_goods_main");
				        $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price'], $row, $language_id);

				        $rs[$k]['shop_price'] = $promote_info['shop_price'];
				        $rs[$k]['price_off'] = $promote_info['price_off'];

				    }

				}
		    }

		}

		return $rs;
	}

	/* 获取评论列表  */
	function get_goods_comments($goods_sn_main) {
		return $this->db->where('goods_sn_main',$goods_sn_main)->order_by('com_id','desc')->limit(20)->get('mall_goods_comments')->result_array();
	}

	/* 获取评论得分分组  */
	function get_goods_comments_star($goods_sn_main) {

		$rs=$this->db->where('goods_sn_main',$goods_sn_main)->select('com_score,count(*) as c')->group_by('com_score')->order_by('com_score','desc')->get('mall_goods_comments')->result_array();
		$arr['list']=$rs;

		$count=0;
		foreach($rs as $v) {
			$count+=$v['c'];
		}
		$arr['count']=$count;

		return $arr;
	}

	/* 增加喜欢数量  */
	function set_like_num($goods_id,$action) {

		if($action == 'add') {
			$action='+';
		}else {
			$action='-';
		}

		return $this->db->set('like_num',"like_num {$action} 1",false)->where('goods_id',$goods_id)->update('mall_goods_main');
	}

	/* 添加收藏，已移动到tb_mall_wish里 */
//	function add_wish($goods_id,$user_id,$goods_sn) {
//		$rs=$this->db->where('user_id',$user_id)->where('goods_id',$goods_id)->get('mall_wish')->row_array();
//		$stauts=false;
//		if(empty($rs)) {
//			$stauts=$this->db->insert('mall_wish',array('user_id'=>$user_id,'goods_id'=>$goods_id,'add_time'=>time(),'goods_sn_main'=>$goods_sn));
//		}
//		return $stauts;
//	}

	/* 获取产品套装详情-后台管理  */
	function get_goods_group_info_admin($goods_group_id) {
	    $rs=$this->db->where('group_id',$goods_group_id)->get('mall_goods_group')->row_array();

	    if(empty($rs)) {
	        return array();
	    }

	    $goods_arr=explode('|', $rs['group_goods']);

	    $goods=array();
	    foreach($goods_arr as $k=>$g) {
	        $tmp_arr=explode('*',$g);

	        $tmp_rs=$this->db->select('goods_id,goods_sn_main,goods_name,goods_img,shop_price')
	        ->where('goods_sn_main',$tmp_arr[0])->get('mall_goods_main')->row_array();

	        $goods['list'][$k]['info']=$tmp_rs;
	        $goods['list'][$k]['num']=$tmp_arr[1];

	    }

	    return $goods;
	}

	
	
	/**
	 * leon
	 * 套餐搜索中使用的方法
	 * 获取包含 sku 的套餐ID
	 */
	function get_goods_group_info_id($group_goods_id) {
	    $rs=$this->db->from('mall_goods_group')->get()->result_array();
	    if(empty($rs)) {
	        return array();
	    }
	    $goods=array();
	    foreach ($rs as $key => $value){
	        $group_id=$value['group_id'];
	        $goods_arr=explode('|', $value['group_goods']);
	        foreach($goods_arr as $k => $g) {
	            $tmp_arr=explode('*',$g);
	            $a = (int)trim($group_goods_id);
	            $b = (int)trim($tmp_arr[0]);
	            if($a == $b){
	                $goods[$key] = $group_id;
	            }
	        }
	    }
	    return $goods;
	}
	
	
	
	/* 获取产品套装详情  */
	function get_goods_group_info($goods_group_id,$country_id = 156) {
		$language_id=(int)$this->session->userdata('language_id');

        if($language_id == ''){
            $this->load->model('tb_language');
            $language_id = $this->tb_language->get_language_by_location($country_id);
        }

		//获取的是一个套装内容
		$rs = $this->db->where('group_id',$goods_group_id)->get('mall_goods_group')->row_array();

		if(empty($rs)) {
			return false;
		}

		$goods_arr=explode('|', $rs['group_goods']);
		$goods=array();
		$number=0;
		$total=0.00;

		$day  = date('Y-m-d H:i:s');
		foreach($goods_arr as $k=>$g) {
			$tmp_arr=explode('*',$g);

			//获取 套装中的产品
			$tmp_rs = $this->db->select('goods_id,goods_sn_main,
			                           goods_name,goods_img,
			                           market_price,shop_price,
			                           is_promote,promote_price,
			                           is_on_sale,promote_start_date,
			                           promote_end_date,is_hot,
			                           is_new,is_free_shipping,
			                           country_flag')
					->where('goods_sn_main',$tmp_arr[0])
					->where('language_id',$language_id)
					->get('mall_goods_main')
					->row_array();

			//判断是不是有内容
            if(empty($tmp_rs)){
                continue;
            }

			//添加数组内容  价钱
			//$rs[$k]['left_time']=0;
			$rs[$k]['price_off'] = 0;

			/*商品在促销期 */
			if ($tmp_rs['is_promote'] == 1){
                $this->load->model("tb_mall_goods_promote");

				//获取促销商品的信息
			    $promote = $this->tb_mall_goods_promote->get_goods_promote_info($tmp_rs['goods_sn_main'],$day);

				if($promote){
                    $this->load->model('o_mall_goods_main');
					//计算折扣比例        promote_price：促销价格。货币美元，单位分           $tmp_rs：产品信息
			        $promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price'], $tmp_rs, $language_id);

			        $tmp_rs['shop_price'] = $promote_info['shop_price'];
			        $tmp_rs['price_off'] = $promote_info['price_off'];

			    }

			}
			if(empty($tmp_rs)){
				continue;
			}

			$goods['list'][$k]['info']=$tmp_rs;         //产品信息
			$goods['list'][$k]['num']=$tmp_arr[1];      //数量
			$total += $tmp_rs['shop_price'] * $tmp_arr[1];//价格

            $number += $tmp_arr[1];
		}
		$goods['number']=$number;
		$goods['total']=$total;
		$goods['goods_name']='';
		$goods['shop_price']=0;
		$goods['goods_sn_main']='';
		$goods['language_id']='';

		return $goods;
	}

	/**
	 * 单品数据固定搜索内容方法
	 * leon
	 */
	function get_goods_group_info_one($goods_group_id,$country_id = 156) {
		$language_id=(int)$this->session->userdata('language_id');

		if($language_id == ''){
			$this->load->model('tb_language');
			$language_id = $this->tb_language->get_language_by_location($country_id);
		}

		//获取的是一个套装内容
		$rs = $this->db->where('group_id',$goods_group_id)->get('mall_goods_group')->row_array();

		if(empty($rs)) {
			return false;
		}

		$goods_arr=explode('|', $rs['group_goods']);
		$goods=array();
		$number=0;
		$total=0.00;

		$day  = date('Y-m-d H:i:s');
		foreach($goods_arr as $k=>$g) {
			$tmp_arr=explode('*',$g);

			//获取 套装中的产品
			$tmp_rs = $this->db->select('goods_id,goods_sn_main,
			                           goods_name,goods_img,
			                           market_price,shop_price,
			                           is_promote,promote_price,
			                           is_on_sale,promote_start_date,
			                           promote_end_date,is_hot,
			                           is_new,is_free_shipping,
			                           country_flag')
					->where('goods_sn_main',$tmp_arr[0])
					->where('language_id',$language_id)
					->get('mall_goods_main')
					->row_array();

			//判断是不是有内容
			if(empty($tmp_rs)){
				continue;
			}

			//添加数组内容  价钱
			//$rs[$k]['left_time']=0;
			$rs[$k]['price_off'] = 0;

			/*商品在促销期 */
			if ($tmp_rs['is_promote'] == 1){
				$this->load->model("tb_mall_goods_promote");

				//获取促销商品的信息
				$promote = $this->tb_mall_goods_promote->get_goods_promote_info($tmp_rs['goods_sn_main'],$day);

				if($promote){
					$this->load->model('o_mall_goods_main');
					//计算折扣比例        promote_price：促销价格。货币美元，单位分           $tmp_rs：产品信息
					$promote_info=$this->o_mall_goods_main->cal_price_off($promote['promote_price'], $tmp_rs, $language_id);

					$tmp_rs['shop_price'] = $promote_info['shop_price'];
					$tmp_rs['price_off'] = $promote_info['price_off'];

				}

			}
			if(empty($tmp_rs)){
				continue;
			}

			$goods['list'][$k]['info']=$tmp_rs;         //产品信息
			$goods['list'][$k]['num']=$tmp_arr[1];      //数量
			$total += $tmp_rs['shop_price'] * $tmp_arr[1];//价格

			$number += $tmp_arr[1];
		}
		$goods['number']=$number;
		$goods['total']=$total;
		$goods['goods_name']='';
		$goods['shop_price']=0;
		$goods['goods_sn_main']='';
		$goods['language_id']='';

		return $goods;
	}












	/* 获取产品套装详情列表  */
	function get_goods_group_info_list($goods_group_ids) {
		$language_id=(int)$this->session->userdata('language_id');

		if(empty($goods_group_ids)) {
			return false;
		}

		$rs=$this->db->where("group_id IN($goods_group_ids)",null,false)->get('mall_goods_group')->result_array();

		if(empty($rs)) {
			return false;
		}

		$goods_array=array();
		foreach($rs as $val) {
			$goods_arr=explode('|', $val['group_goods']);
			foreach($goods_arr as $k=>$g) {
				$tmp_arr=explode('*',$g);
				$goods_array[]=$tmp_arr[0];
			}
		}
		$goods_arr_uniq=array_unique($goods_array);
		$good_ids=implode('\',\'',$goods_arr_uniq);
		$good_ids ='\''.$good_ids.'\'';

		$goods=$this->db->where('is_on_sale',1)->where('is_alone_sale',1)->where("goods_sn_main IN($good_ids) and language_id=$language_id",null,false)->get('mall_goods_main')->result_array();

		return $goods;
	}

	/* 增加品牌  */
    function add_brand($data_db) {
        if($this->check_brand($data_db['brand_name'], $data_db['language_id'])) {
             return  array('error'=>1,'msg' => lang('brand_exists'),'data'=>'2');
        }

        $this->db->trans_begin();
        $this->db->insert('mall_goods_brand',$data_db);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return  array('error'=>1,'msg' => lang('info_failed'),'data'=>'2');
        } else {
            // 同步到ERP品牌添加
            $data_db['brand_id'] = $this->db->insert_id();
            $url = 'Api/Commodity/addBrand';
            $snyRet = erp_api_query($url, $data_db);
            if ($snyRet['code'] == 200) {
                $this->db->trans_commit();
                return  array('error' => 0,'msg' => lang('info_success'));
            } else {
                $this->db->trans_rollback();
                return  array('error' => 1,'msg' => 'syn'.$snyRet['msg'],'data'=>'3');
            }
        }
    }

	/* 更新品牌  */
    function update_brand($data_db) {
        // 查询数据
        $brand_id = $data_db['brand_id'];
        $exitData = $this->db->where('brand_id',$brand_id)->get('mall_goods_brand')->row_array();
        $different = array_diff_assoc($data_db,$exitData);
        if ($different) {
            $this->db->trans_begin();
            $this->db->where('brand_id',$brand_id)->update('mall_goods_brand',$different);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return  array('error' => 1,'msg' => lang('info_failed'));
            } else {
                $url = 'Api/Commodity/updateBrand';
                $different['brand_id'] = $brand_id;
                $snyRet = erp_api_query($url, $different);
                if ($snyRet['code'] == 200) {
                    $this->db->trans_commit();
                    return  array('error' => 0,'msg' => lang('info_success'));
                } else {
                    $this->db->trans_rollback();
                    return  array('error' => 1,'msg' => $snyRet['msg']);
                }
            }
        } else {
            return  array('error' => 0,'msg' => lang('info_success'));
        }
    }

	/* 获取品牌信息  */
	function get_brand($id) {
		return $this->db->where('brand_id',$id)->get('mall_goods_brand')->row_array();
	}

	/* 检查品牌名称是否重复 */
	function check_brand($brand_name,$lang_id) {
	    $rs = $this->db->where('language_id',$lang_id)->where('brand_name',$brand_name)->get('mall_goods_brand')->row_array();

	    return !empty($rs) ? true : false;
	}

	/* 增加一个套餐  */
	function add_goods_group($ids) {

		return $this->db->insert('mall_goods_group',array('group_goods'=>$ids));
	}

	/* 更新一个套餐  */
	function update_goods_group($ids,$id) {
		return $this->db->where('group_id',$id)->update('mall_goods_group',array('group_goods'=>$ids));
	}

	/* 获取套餐信息 */
	function get_goods_group($id) {
		$rs=$this->db->where('group_id',$id)->get('mall_goods_group')->row_array();
		$rs['goods_list']=$this->get_goods_group_info_admin($rs['group_id']);

		return $rs;
	}

	/* 获取销售国家列表  */
	function get_sale_country($country_id=false) {
	    if(false !== $country_id) {
	        $this->db->where('country_id',$country_id);
	    }
		return $this->db->order_by('country_id','desc')->get('mall_goods_sale_country')->result_array();
	}

	/* 國家ID是否存在 */
	function is_exist_country($country_id){
		return $this->db->from('mall_goods_sale_country')->where('country_id',$country_id)->count_all_results();
	}

	/* 获取销售国家名称  传入商品中的id字符串，返回中文名字符串*/
	function get_sale_country_str($sale_country_ids) {
		$cur_lang=get_cookie('curLan',true);
		$cur_lang=empty($cur_lang) ? 'english' : $cur_lang;

		$link='、';
		if($cur_lang == 'english') {
			$link=' & ';
		}

		$country_arr=$this->get_sale_country();
		$country_str='';
		$sale_country_arr=explode('$',$sale_country_ids);
		foreach ($sale_country_arr as $vv) {
			foreach($country_arr as $country) {
				if($country['country_id'] == $vv) {
					$country_str.=$country['name_'.$cur_lang].$link;
				}
			}
		}
		$country_str=trim($country_str,$link);

		return $country_str;
	}

	/* 新增反馈信息  */
	function add_feedback($email,$content,$user_id) {
		return $this->db->insert('mall_feedback',array('email'=>$email,'content'=>$content,'add_time'=>time(),'user_id'=>$user_id));
	}

	/* 获取商品中的赠品列表  */
	function get_gift_list_in_goods($gift_skus) {
		$language_id=(int)$this->session->userdata('language_id');

		if(empty($gift_skus)) {
			return false;
		}

		$skus=explode(',', $gift_skus);

		$skus_str='';
		$arr_num=array();
		foreach($skus as $val) {
			$tmp_arr=explode('*',$val);
			$skus_str.="'".$tmp_arr[0]."',";

			$arr_num[$tmp_arr[0]]=$tmp_arr[1];
		}

		$skus_str=trim($skus_str,',');

		$rs=$this->db->select('goods_sn_main,goods_name,goods_img')->where("goods_sn_main IN($skus_str)",null,false)->where('language_id',$language_id)->get('mall_goods_main')->result_array();

		foreach($rs as $k=>$v) {
			$rs[$k]['num']=$arr_num[$v['goods_sn_main']];
		}
		return $rs;
	}

	/* 获取问题反馈分页列表  */
	public function get_feedback_list_page($filter, $per_page = 10) {
		$this->db->from('mall_feedback');
		$this->filter_for_feedback($filter);

		return $this->db->order_by('state','asc')->order_by('add_time','desc')->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
	}

	/* 获取问题反馈记录总数  */
	public function get_feedback_total($filter) {
		$this->db->from('mall_feedback');
		$this->filter_for_feedback($filter);

		return $this->db->get()->num_rows();
	}

	/* 问题反馈查询条件  */
	public function filter_for_feedback($filter){
		foreach ($filter as $k => $v) {
			if (!$v || $k=='page') {
				continue;
			}
			switch ($k) {

				case 'keywords':
					$v=trim($v);
					$this->db->where("(email like '%$v%' or user_id like '%$v%')", null, false);
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}

	/* 改变问题反馈的状态 */
	function chang_feedback_state($id) {
		return $this->db->where('feed_id',$id)->update('mall_feedback',array('state'=>1));
	}

	/* 获取评论分页  */
	function get_comments($goods_sn_main,$page) {
		$rs=array();

		$rs=$this->db->where('goods_sn_main',$goods_sn_main)->order_by('com_id','desc')->limit(20,$page * 20)->get('mall_goods_comments')->result_array();

		return $rs;
	}

	/* 检查该doba商品是否已经存在  */
	function check_doba_item($item_id) {
		$rs=$this->db->where('doba_item_id',$item_id)->get('mall_goods_main')->row_array();

		return empty($rs) ? false : true;
	}

	/* 检查该doba分类是否已经存在  */
	function check_doba_category($cate_name) {
		$rs=$this->db->select('cate_id')->where('cate_name',$cate_name)->get('mall_goods_category')->row_array();

		return empty($rs) ? false : $rs['cate_id'];
	}

	/* 检查该doba品牌是否已经存在  */
	function check_doba_brand($brand_name) {
		$rs=$this->db->select('brand_id')->where('brand_name',$brand_name)->get('mall_goods_brand')->row_array();

		return empty($rs) ? false : $rs['brand_id'];
	}

	/* 插入新数据 */
	function inser_db_cate($data) {
		return $this->db->insert('mall_goods_category',$data);
	}

	/* 插入doba主商品信息  */
	function add_db_goods($data) {
		$state=$this->db->insert('mall_goods_main',$data);

		return $state ? $this->db->insert_id() : false;
	}

	/* 获取商品最新运费 */
	function get_doba_ship_costs($product_id,$item_id) {
	    $URL = $this->doba_url;
	    $strRequest = '
            <dce>
              <request>
                <authentication>
                  <username>'.$this->doba_account.'</username>
                  <password>'.$this->doba_password.'</password>
                </authentication>
                <retailer_id>'.$this->doba_id.'</retailer_id>
                <action>getProductDetail</action>
                <page>1</page>
                <limit>1000</limit>
                <products>
                  <product>'.$product_id.'</product>
                </products>
                <items>
                  <item>'.$item_id.'</item>
                </items>
              </request>
            </dce>
            ';
	    $connection = curl_init();
	    curl_setopt($connection, CURLOPT_URL, $URL );
	    curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($connection, CURLOPT_POST, 1);
	    curl_setopt($connection, CURLOPT_POSTFIELDS, $strRequest);
	    curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
	    set_time_limit(50);
	    $strResponse = curl_exec($connection);
	    if(curl_errno($connection)) {
	        //print "Curl error: " . curl_error($connection);
	    } else {
	        $info = curl_getinfo($connection);
	        //print "HTTP Response Code = ".$info["http_code"]."\n";

	        $xml_arr=simplexml_load_string($strResponse);

	        $ship_arr=array();
	        if($xml_arr->response->outcome == 'success') {
	            $ship_arr['ship_cost'] = floatval($xml_arr->response->products->product->ship_cost);
	            $ship_arr['drop_ship_fee'] =floatval($xml_arr->response->products->product->supplier->drop_ship_fee);

	            return $ship_arr;
	        }

	    }

	    curl_close($connection);

	    return false;
	}



	/* 更新doba产品库存和价格  */
	/**
	*authr Baker 2017-5-16  修改了7天内图片自动从网上下载功能
	**/
	function modify_doba_inventory($product_id,$item_id,$goods_sn_main) {

		$URL = $this->doba_url;
		$strRequest = '
           <dce>
              <request>
                <authentication>
                  <username>'.$this->doba_account.'</username>
                  <password>'.$this->doba_password.'</password>
                </authentication>
                <retailer_id>'.$this->doba_id.'</retailer_id>
                <action>getProductDetail</action>
                <page>1</page>
                <limit>1000</limit>
                <products>
                  <product>'.$product_id.'</product>
                </products>
                <items>
                  <item>'.$item_id.'</item>
                </items>
              </request>
            </dce>
        ';
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, $URL );
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_POST, 1);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $strRequest);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		set_time_limit(100);
		$strResponse = curl_exec($connection);
		if(curl_errno($connection)) {
			//print "Curl error: " . curl_error($connection);
		} else {
			$info = curl_getinfo($connection);

			if($info["http_code"] == 200) {
				$new_goods_info = simplexml_load_string($strResponse);
				//print_r($new_goods_info);
				////echo 'State:',$info["http_code"],'/',$new_goods_info->response->outcome,'/';
				if($new_goods_info->response->outcome == 'success') { 
				    //如果产品获取成功，但是不在销售，下架
				    if($new_goods_info->response->products->product->items->item->stock == 'discontinued') {
				        //产品已经不存在，下架
				        $data['is_on_sale'] = 0;
				        $data['last_update']=time();
				        $data['update_user']='doba_cli_task_un_sale';
				        $upRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods_main',$data);
				        // 同步状态
				        if ($upRet) {
				            $updateDate['goods_sn_main'] = $goods_sn_main;
				            $updateDate['is_on_sale'] = $data['is_on_sale'];
				            $url = 'Api/Commodity/modifyCommodityShelfStatus';
				            $snyRet = erp_api_query($url, $updateDate);
				            if ($snyRet['code'] != 200) {
				                ////echo $snyRet['msg'];
				            }
				        }
				        ////echo 'UnSale!';
				    }else {
    					$data['daba_msrp']=$new_goods_info->response->products->product->items->item->msrp;
    					$data['doba_prepay_price']=$new_goods_info->response->products->product->items->item->prepay_price;

    					$data['purchase_price']=$new_goods_info->response->products->product->items->item->price;
    					$data['market_price']=floatval($new_goods_info->response->products->product->items->item->msrp);
    					$data['shop_price']= floatval(number_format($new_goods_info->response->products->product->items->item->price * 1.35,2,'.',''));

    					$data['last_update']=time();
    					$data['update_user']='doba_cli_task';

    					//$new_ship_price=$this->get_doba_ship_costs($product_id,$item_id);
    					$new_ship_price['ship_cost'] = floatval($new_goods_info->response->products->product->ship_cost);
	            		$new_ship_price['drop_ship_fee'] =floatval($new_goods_info->response->products->product->supplier->drop_ship_fee);

    					//if(false !== $new_ship_price) {
    					    ////echo "New ship price:".$new_ship_price['ship_cost'].'/'.$new_ship_price['drop_ship_fee']."\n";

					    $data['doba_ship_cost']=$new_ship_price['ship_cost'];
					    $data['doba_drop_ship_fee']=$new_ship_price['drop_ship_fee'];
					    $data['is_free_shipping'] = ($new_ship_price['ship_cost'] + $new_ship_price['drop_ship_fee']) > 0 ? 0 : 1;
    					//}

    					//判断加上利润后商品的销售价是否高于市场价
    					if($data['shop_price'] > $data['market_price']) {
    						$data['market_price']=number_format($new_goods_info->response->products->product->items->item->price * 1.50,2,'.','');
    					}

    					$data_goods['goods_number']=$new_goods_info->response->products->product->items->item->qty_avail;
    					$data_goods['price']=$data['shop_price'];
    					$data_goods['purchase_price']=$data['purchase_price'];

    					if($new_goods_info->response->products->product->items->item->stock == 'out-of-stock') {
    						$data_goods['goods_number']=0;
    					}
                        $this->load->model("tb_mall_goods");
                        $this->load->model("tb_mall_goods_main");
                        $this->load->model("tb_mall_goods_detail_img");
                        $one_goods_main = $this->db->where(["goods_sn_main"=>$goods_sn_main])->get('mall_goods_main')->row_array();
                        if (intval($one_goods_main['add_time']) + (3600 * 24 * 7) < time()) { 
                    		$this->load->model("m_do_img");
                        	foreach ($new_goods_info->response->products->product->images->image as $key => $value) {
                        		//下载大和小图片
                        		$max_img_resource = file_get_contents($value->url);
                        		$max_name = 'max_img_'.$goods_sn_main .'_'.time().".jpg";
                        		$max_status = $this->m_do_img->uploadImgBase64($max_name, $max_img_resource);

                        		$min_img_resource = file_get_contents($value->thumb_url);
                        		$min_name = 'min_img_'.$goods_sn_main.'_'.time().".jpg";
                        		$min_status = $this->m_do_img->uploadImgBase64($min_name, $min_img_resource);
                        	
                        		if ($min_status && $max_status) {
                        			//删除之前的图片
                        			$this->m_do_img->delete($one_goods_main['goods_img']);
                        			$detail_img_one = $this->tb_mall_goods_detail_img->get_one("image_url", ["goods_sn_main"=>$goods_sn_main]);
                        			$this->m_do_img->delete($detail_img_one['image_url']);
                        			$goods_gallery_one = $this->db->like('goods_sn', $goods_sn_main, 'after')->select(['thumb_img', 'big_img'])->get('mall_goods_gallery')->row_array();
                        			if ($goods_gallery_one['thumb_img']  && $goods_gallery_one['big_img']) {
                        				$this->m_do_img->delete($goods_gallery_one['thumb_img']);
                        				$this->m_do_img->delete($goods_gallery_one['big_img']);
                        			}
                        		
                        			//新图片更新数据库
                        			$this->tb_mall_goods_main->update_one(['goods_sn_main'=>$goods_sn_main], ['goods_img' => $min_name]);
                        			$this->tb_mall_goods_detail_img->update_one(['goods_sn_main'=>$goods_sn_main], ['image_url' => $max_name]);
									//$sql ="UPDATE mall_goods_gallery SET thumb_img = '{$min_name}', big_img ='{$max_name}' WHERE goods_sn LIKE '$goods_sn_main%' ";
									$this->db->where('goods_sn like', $goods_sn_main. "%")->update('mall_goods_gallery', ['thumb_img' =>$min_name, 'big_img' => $max_name]);
                        		}
                        		$data['add_time'] = time();
                        		break;
                        	}
                        }
                        // 原始库存
//                        $originalNum = $this->db->select('goods_number,goods_sn')->get_where('mall_goods',array('goods_sn_main'=>$goods_sn_main))->row_array();
                        $originalNum = $this->tb_mall_goods->get_one("goods_number,goods_sn",
                            ["goods_sn_main"=>$goods_sn_main]);
    					//$mainRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods_main',$data);
    					$mainRet = $this->tb_mall_goods_main->update_one(['goods_sn_main'=>$goods_sn_main], $data);
//    					$goodsRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods',$data_goods);
                        $goodsRet = $this->tb_mall_goods->update_one(['goods_sn_main'=>$goods_sn_main],$data_goods);
                        //更新独立库存
                        $mall_goods_tmp = $this->tb_mall_goods->get_one("product_id",['goods_sn_main'=>$goods_sn_main]);
                        $this->tb_mall_goods->mall_goods_redis_log($mall_goods_tmp['product_id'],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                        $this->tb_mall_goods->update_goods_number_in_redis($mall_goods_tmp['product_id'],$data_goods['goods_number']);

                        if ($mainRet && $goodsRet) {
                            // 同步库存和价格
                            $url = 'Api/Commodity/updateDobaInventoryPrice';
                            $updateDate = array(
                                'goods_sn_main' => $goods_sn_main,
//                                'goods_number' => intval($data_goods['goods_number']),
                                'daba_msrp' => floatval($new_goods_info->response->products->product->items->item->msrp),
                                'doba_prepay_price' => floatval($new_goods_info->response->products->product->items->item->prepay_price),
                                'purchase_price' => floatval($new_goods_info->response->products->product->items->item->price),
                                'shop_price' => $data['shop_price'],
                                'original_goods_number' => $originalNum['goods_number'],
                            );
                            $snyRet = erp_api_query($url, $updateDate);
                            if ($snyRet['code'] != 200) {
                                ////echo $snyRet['msg'];
                            }

							//同步库存
                            $chang_num = intval($originalNum['goods_number']) - intval($data_goods['goods_number']);

                            $this->load->model("tb_mall_goods");
                            $goods_info = $this->tb_mall_goods->get_one("goods_number",['goods_sn_main'=>$goods_sn_main]);
                            $goods_number = isset($goods_info['goods_number'])?$goods_info['goods_number']:0;

                            //取消同步库存到ERP的队列,(临时取消)
                           if($chang_num != 0){
                               $goods_num = array();
                               if($chang_num > 0){
                                   $goods_num['oper_type'] = 'dec'; //jian库存
                               }else{
                                   $goods_num['oper_type'] = 'inc'; //jia库存
                               }
                               //插入到库存推送队列表
                               $goods_num['goods_sn'] = $originalNum['goods_sn'];
                               $goods_num['quantity'] = abs($chang_num);
                               $goods_num['inventory'] = $goods_number;
                               $goods_num['order_id'] = 'doba';

                               //插入到库存队列表
                               $this->db->insert('trade_order_to_erp_inventory_queue',$goods_num);
                           }
                        }
                        ////echo 'Updated!';
				    }
				}else {
				    if($new_goods_info->response->outcome == 'failure' && $new_goods_info->response->error->code == '510') {
				        //产品已经不存在，下架
				        $data['is_on_sale'] = 0;
				        $data['last_update']=time();
				        $data['update_user']='doba_cli_task_un_sale';
				        $upRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods_main',$data);
                        // 同步状态
                        if ($upRet) {
                            $updateDate['goods_sn_main'] = $goods_sn_main;
                            $updateDate['is_on_sale'] = $data['is_on_sale'];
                            $url = 'Api/Commodity/modifyCommodityShelfStatus';
                            $snyRet = erp_api_query($url, $updateDate);
                            if ($snyRet['code'] != 200) {
                                ////echo $snyRet['msg'];
                            }
                        }
				        ////echo 'UnSale!';
				    }
				}
			}else {
			    ////echo 'State:110/Failed';
			}
		}

		curl_close($connection);
        ////sleep(1);
	}

	/* 更新doba产品库存和价格  */
	/*function modify_doba_inventory($product_id,$item_id,$goods_sn_main) {
		$URL = $this->doba_url;
		$strRequest = '
            <dce>
              <request>
                <authentication>
                  <username>'.$this->doba_account.'</username>
                  <password>'.$this->doba_password.'</password>
                </authentication>
                <retailer_id>'.$this->doba_id.'</retailer_id>
                <action>getProductInventory</action>
                <page>1</page>
                <limit>1000</limit>
                <ship_postal></ship_postal>
                <products>
                  <product>'.$product_id.'</product>
                </products>
                <list_ids/>
                <items>
                  <item>'.$item_id.'</item>
                </items>
              </request>
            </dce>
        ';
		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, $URL );
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_POST, 1);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $strRequest);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		set_time_limit(100);
		$strResponse = curl_exec($connection);
		if(curl_errno($connection)) {
			//print "Curl error: " . curl_error($connection);
		} else {
			$info = curl_getinfo($connection);

			if($info["http_code"] == 200) {
				$new_goods_info=simplexml_load_string($strResponse);
				//print_r($new_goods_info);
				////echo 'State:',$info["http_code"],'/',$new_goods_info->response->outcome,'/';
				if($new_goods_info->response->outcome == 'success') {
				    //如果产品获取成功，但是不在销售，下架
				    if($new_goods_info->response->products->item->stock == 'discontinued') {
				        //产品已经不存在，下架
				        $data['is_on_sale'] = 0;
				        $data['last_update']=time();
				        $data['update_user']='doba_cli_task_un_sale';
				        $upRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods_main',$data);
				        // 同步状态
				        if ($upRet) {
				            $updateDate['goods_sn_main'] = $goods_sn_main;
				            $updateDate['is_on_sale'] = $data['is_on_sale'];
				            $url = 'Api/Commodity/modifyCommodityShelfStatus';
				            $snyRet = erp_api_query($url, $updateDate);
				            if ($snyRet['code'] != 200) {
				                ////echo $snyRet['msg'];
				            }
				        }
				        ////echo 'UnSale!';
				    }else {
    					$data['daba_msrp']=$new_goods_info->response->products->item->msrp;
    					$data['doba_prepay_price']=$new_goods_info->response->products->item->prepay_price;

    					$data['purchase_price']=$new_goods_info->response->products->item->price;
    					$data['market_price']=floatval($new_goods_info->response->products->item->msrp);
    					$data['shop_price']= floatval(number_format($new_goods_info->response->products->item->price * 1.35,2,'.',''));

    					$data['last_update']=time();
    					$data['update_user']='doba_cli_task';

    					$new_ship_price=$this->get_doba_ship_costs($product_id,$item_id);

    					if(false !== $new_ship_price) {
    					    ////echo "New ship price:".$new_ship_price['ship_cost'].'/'.$new_ship_price['drop_ship_fee']."\n";

    					    $data['doba_ship_cost']=$new_ship_price['ship_cost'];
    					    $data['doba_drop_ship_fee']=$new_ship_price['drop_ship_fee'];
    					    $data['is_free_shipping'] = ($new_ship_price['ship_cost'] + $new_ship_price['drop_ship_fee']) > 0 ? 0 : 1;
    					}

    					//判断加上利润后商品的销售价是否高于市场价
    					if($data['shop_price'] > $data['market_price']) {
    						$data['market_price']=number_format($new_goods_info->response->products->item->price * 1.50,2,'.','');
    					}

    					$data_goods['goods_number']=$new_goods_info->response->products->item->qty_avail;
    					$data_goods['price']=$data['shop_price'];
    					$data_goods['purchase_price']=$data['purchase_price'];

    					if($new_goods_info->response->products->item->stock == 'out-of-stock') {
    						$data_goods['goods_number']=0;
    					}
                        $this->load->model("tb_mall_goods");
                        // 原始库存
//                        $originalNum = $this->db->select('goods_number,goods_sn')->get_where('mall_goods',array('goods_sn_main'=>$goods_sn_main))->row_array();
                        $originalNum = $this->tb_mall_goods->get_one("goods_number,goods_sn",
                            ["goods_sn_main"=>$goods_sn_main]);
    					$mainRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods_main',$data);
//    					$goodsRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods',$data_goods);
                        $goodsRet = $this->tb_mall_goods->update_one(['goods_sn_main'=>$goods_sn_main],$data_goods);
                        //更新独立库存
                        $mall_goods_tmp = $this->tb_mall_goods->get_one("product_id",['goods_sn_main'=>$goods_sn_main]);
                        $this->tb_mall_goods->mall_goods_redis_log($mall_goods_tmp['product_id'],"调用库存更新in:class:".__CLASS__.":function:".__FUNCTION__.":line:".__LINE__);
                        $this->tb_mall_goods->update_goods_number_in_redis($mall_goods_tmp['product_id'],$data_goods['goods_number']);

                        if ($mainRet && $goodsRet) {
                            // 同步库存和价格
                            $url = 'Api/Commodity/updateDobaInventoryPrice';
                            $updateDate = array(
                                'goods_sn_main' => $goods_sn_main,
//                                'goods_number' => intval($data_goods['goods_number']),
                                'daba_msrp' => floatval($new_goods_info->response->products->item->msrp),
                                'doba_prepay_price' => floatval($new_goods_info->response->products->item->prepay_price),
                                'purchase_price' => floatval($new_goods_info->response->products->item->price),
                                'shop_price' => $data['shop_price'],
                                'original_goods_number' => $originalNum['goods_number'],
                            );
                            $snyRet = erp_api_query($url, $updateDate);
                            if ($snyRet['code'] != 200) {
                                ////echo $snyRet['msg'];
                            }

							//同步库存
                            $chang_num = intval($originalNum['goods_number']) - intval($data_goods['goods_number']);
                            //获取当前库存,(doba产品永远一个goods_sn_main永远只有一条记录？)
//                            $goods_info = $this->db->from('mall_goods')->select('goods_number')->where('goods_sn_main',$goods_sn_main)->get()->row_array();
                            $this->load->model("tb_mall_goods");
                            $goods_info = $this->tb_mall_goods->get_one("goods_number",['goods_sn_main'=>$goods_sn_main]);
                            $goods_number = isset($goods_info['goods_number'])?$goods_info['goods_number']:0;
                           if($chang_num != 0){
                               $goods_num = array();
                               if($chang_num > 0){
                                   $goods_num['oper_type'] = 'dec'; //jian库存
                               }else{
                                   $goods_num['oper_type'] = 'inc'; //jia库存
                               }
                               //插入到库存推送队列表
                               $goods_num['goods_sn'] = $originalNum['goods_sn'];
                               $goods_num['quantity'] = abs($chang_num);
                               $goods_num['inventory'] = $goods_number;
                               $goods_num['order_id'] = 'doba';

                               //插入到库存队列表
                               $this->db->insert('trade_order_to_erp_inventory_queue',$goods_num);
                           }
                        }
                        ////echo 'Updated!';
				    }
				}else {
				    if($new_goods_info->response->outcome == 'failure' && $new_goods_info->response->error->code == '510') {
				        //产品已经不存在，下架
				        $data['is_on_sale'] = 0;
				        $data['last_update']=time();
				        $data['update_user']='doba_cli_task_un_sale';
				        $upRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods_main',$data);
                        // 同步状态
                        if ($upRet) {
                            $updateDate['goods_sn_main'] = $goods_sn_main;
                            $updateDate['is_on_sale'] = $data['is_on_sale'];
                            $url = 'Api/Commodity/modifyCommodityShelfStatus';
                            $snyRet = erp_api_query($url, $updateDate);
                            if ($snyRet['code'] != 200) {
                                ////echo $snyRet['msg'];
                            }
                        }
				        ////echo 'UnSale!';
				    }
				}
			}else {
			    ////echo 'State:110/Failed';
			}
		}

		curl_close($connection);
        ////sleep(1);
	}*/

	/* 更新doba产品库存、运费和价格CLI 每天执行一次，中午12点执行  */
	function modify_doba_inventory_cli($only_outofstock = false) {
		$last_time = (int)time() - 14400;
	    if($only_outofstock) {
	        $goods_count=$this->db->from('mall_goods_main m')->join('mall_goods g','m.goods_sn_main=g.goods_sn_main','left')->where('is_doba_goods',1)->where('last_update <',$last_time)->where('is_on_sale',1)->where('goods_number',0)->count_all_results();
	    }else {
            //只更新更新时间超过4个小时的
		    $goods_count=$this->db->from('mall_goods_main')->where('is_doba_goods',1)->where('last_update <',$last_time)->where('is_on_sale',1)->count_all_results();
//            echo $this->db->last_query();exit;
	    }
	    if(!$goods_count) {
	        exit('No results.');
	    }

		$page_size=100; //每次更新100个sku
		$current_page=1;
		do{
		    if($only_outofstock) {
		        $current_rs=$this->db->from('mall_goods_main m')->select('doba_product_id,doba_item_id,m.goods_sn_main')->join('mall_goods g','m.goods_sn_main=g.goods_sn_main','left')->where('is_doba_goods',1)->where('last_update <',$last_time)->where('is_on_sale',1)->where('goods_number',0)->limit($page_size,($current_page-1) * $page_size)->order_by('last_update','asc')->get()->result_array();

		    }else{
			    $current_rs=$this->db->select('doba_product_id,doba_item_id,goods_sn_main')->where('is_doba_goods',1)->where('is_on_sale',1)->where('last_update <',$last_time)->limit($page_size,($current_page-1) * $page_size)->order_by('last_update','asc')->get('mall_goods_main')->result_array();
		    }
			if(!empty($current_rs)) {
				foreach($current_rs as $goods_info) {
					$this->modify_doba_inventory($goods_info['doba_product_id'],$goods_info['doba_item_id'],$goods_info['goods_sn_main']);
					echo 'Updated goods:',$goods_info['goods_sn_main'],"\n";
				}
			}
			echo 'Current Page:',$current_page,'/Goods Count:',$goods_count,"\n";
			$current_page ++ ;
			$goods_count -= $page_size;
		}while($goods_count > 0);

		exit("ok\n");
	}

	/* 下单doba
	 *
	 *  $order_id:  varchar 商品表字段doba_item_id
	 *  $goods_info: array
          item_id: varchar 商品表doba_item_id
          quantity: int 数量
	 *  $ship_info：array
	        phone - varchar(10) - The phone number of the end-customer as provided by the merchant.
    	    city - varchar(25) - The city of the end-customer the shipment will be sent to as provided by the merchant.
            country - char(2) - The country of end-customer the shipment will be sent to as provided by the merchant.Only 'US' is currently allowed.
            firstname - varchar(25) - The first name of the end-customer the shipment will be sent to as provided by the merchant.
            lastname - varchar(25) - The last name of the end-customer the shipment will be sent to as provided by the merchant.
            postal - varchar(10) - The postal/zip code of the end-customer the shipment will be sent to as provided by the merchant.
            state - char(2) - The two character abbreviation state of the end-customer the shipment will be sent to as provided by the merchant.
            street - varchar(25) - The street address of the end-customer the shipment will be setnt to as provided by the merchant.

     * return 成功：返回doba订单id;失败返回false
     * eg :$this->m_goods->create_doba_order('DDGL1507310022',
     *     array(
     *           array('item_id'=>'34297354', //同一供应商的商品，请一次提交
     *           'quantity'=>1)
     *           ),
     *     array(
     *         'phone'=>'1012121221',
     *         'city'=>'shenzhen',
     *         'country'=>'US',
     *         'firstname'=>'yuan',
     *         'lastname'=>'dongdong',
     *         'postal'=>'84043',
     *         'state'=>'NY',
     *         'street'=>'tairancangsong1907'));
	 * */
	function create_doba_order($order_id,$goods_info,$ship_info) {
		$URL = $this->doba_url;

		$doba_order=$order_id.'_'.date('md');

		$strRequest = '
            <dce>
              <request>
                <authentication>
                  <username>'.$this->doba_account.'</username>
                  <password>'.$this->doba_password.'</password>
                </authentication>
                <retailer_id>'.$this->doba_id.'</retailer_id>
                <action>createOrder</action>
            	<po_number>'.$doba_order.'</po_number>
            	<shipping_phone>'.$ship_info['phone'].'</shipping_phone>
                <shipping_firstname>'.$ship_info['firstname'].'</shipping_firstname>
                <shipping_lastname>'.$ship_info['lastname'].'</shipping_lastname>
                <shipping_street>'.$ship_info['street'].'</shipping_street>
                <shipping_city>'.$ship_info['city'].'</shipping_city>
                <shipping_state>'.$ship_info['state'].'</shipping_state>
                <shipping_postal>'.$ship_info['postal'].'</shipping_postal>
                <shipping_country>'.$ship_info['country'].'</shipping_country>
                <items>';
		foreach($goods_info as $val) {
			$strRequest .='<item>
                    <item_id>'.$val['item_id'].'</item_id>
                    <quantity>'.$val['quantity'].'</quantity>
                  </item>';
		}
		$strRequest .=     '</items>
              </request>
            </dce>
            ';

		$connection = curl_init();
		curl_setopt($connection, CURLOPT_URL, $URL );
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_POST, 1);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $strRequest);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		set_time_limit(120);
		$strResponse = curl_exec($connection);
		if(curl_errno($connection)) {
			//print "Curl error: " . curl_error($connection);
			return false;
		} else {
			$info = curl_getinfo($connection);
			if($info["http_code"] == 200) {
				$new_order_info=simplexml_load_string($strResponse);
				//print_r($new_order_info);

				if($new_order_info->response->outcome == 'success') {
					$data['order_id']=$order_id;
					$data['order_id_doba']=$new_order_info->response->order_id;

					$data['order_total_doba']=$new_order_info->response->order_total;
					$data['state']=strval($new_order_info->response->outcome);
					$data['doba_order_id']=$doba_order;
					$data['goods_list_info']=serialize($goods_info);

					$state=$this->db->insert('trade_orders_doba_order_info',$data);

					curl_close($connection);
					if(!$state) {
						return false;
					}

					/*获取订单相关信息*/
					$this->load->model('tb_trade_orders');
					$orderInfo = $this->tb_trade_orders->getOrderInfo($order_id,array('order_amount_usd','shopkeeper_id','goods_amount_usd'));

                    //更新订单利润
                    $order_total_doba = $this->db->query("select order_total_doba from trade_orders_doba_order_info order_id WHERE order_id= '$order_id'")->row()->order_total_doba;
                    $order_profit_usd = tps_int_format(($orderInfo['order_amount_usd']/100 - $order_total_doba) * 100);
                    $this->db->where('order_id',$order_id)->set('order_profit_usd',$order_profit_usd)->update('trade_orders');

                    /*记录到新订单处理队列*/
                    $this->load->model('tb_new_order_trigger_queue');
                    $this->tb_new_order_trigger_queue->addNewOrderToQueue($order_id,$orderInfo['shopkeeper_id'],$orderInfo['goods_amount_usd'],$order_profit_usd);

					return $data['order_id_doba'];
				}
			}
		}

        //开始事务
        $this->db->trans_begin();

        //推送失败原因
        $this->load->model('m_validate');
        $error_msg = $this->m_validate->getTagContent('<message>','</message>',$strResponse);
        //$remark = lang('doba_order_exception').'---'.$error_msg;

        //推送失败时将订单状态改成111(异常订单)
        $this->db->where('order_id',$order_id)->set('status',Order_enum::STATUS_DOBA_EXCEPTION)->update('trade_orders');

        //记录到trade_order_remark_record
        $this->db->insert('trade_order_remark_record',array(
            'order_id'=>$order_id,
            'type'=>'2',
            'remark'=>$error_msg,
            'admin_id'=>0
        ));

        //修改订单状态为2(doba异常订单)
        $this->db->query("update trade_order_doba_log set status = '2' where order_id = '$order_id'");

		//订单同步到erp
        $this->load->model('m_erp');
		$insert_data = array();
		$insert_data['oper_type'] = 'modify';
		$insert_data['data']['order_id'] = $order_id;
		$insert_data['data']['status'] = Order_enum::STATUS_DOBA_EXCEPTION;

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

        //备注同步到erp
        $insert_data_remark = array();
        $insert_data_remark['oper_type'] = 'remark';
        $insert_data_remark['data']['order_id'] = $order_id;
        $insert_data_remark['data']['remark'] = $error_msg;
        $insert_data_remark['data']['type'] = "2"; //1 系统可见备注，2 用户可见备注
        $insert_data_remark['data']['recorder'] = 0; //操作人
        $insert_data_remark['data']['created_time'] = date('Y-m-d H:i:s',time());

        $this->m_erp->trade_order_to_erp_oper_queue($insert_data_remark);//添加到订单推送表，添加备注

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }
        else
        {
            $this->db->trans_commit();
        }

		curl_close($connection);

		return false;
	}

	/* 手动获取doba订单信息  */
	function create_doba_info_by_hands($doba_order_id,$order_id) {

	    if(empty($doba_order_id)) {
	        echo 'No doba order id yet.';
	        exit;
	    }

	    $URL = $this->doba_url;

	    $this->load->model('m_erp');

	    $connection = curl_init();

		$strRequest = '
            <dce>
              <request>
                <authentication>
                  <username>'.$this->doba_account.'</username>
                  <password>'.$this->doba_password.'</password>
                </authentication>
                <retailer_id>'.$this->doba_id.'</retailer_id>
                <action>getOrderDetail</action>
                <order_ids>
                  <order_id>'.$doba_order_id.'</order_id>
                </order_ids>
              </request>
            </dce>
            ';

		curl_setopt($connection, CURLOPT_URL, $URL );
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($connection, CURLOPT_POST, 1);
		curl_setopt($connection, CURLOPT_POSTFIELDS, $strRequest);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		set_time_limit(300);
		$strResponse = curl_exec($connection);
		if(curl_errno($connection)) {
			//print "Curl error: " . curl_error($connection);
			return false;
		} else {
			$info = curl_getinfo($connection);
			//print "HTTP Response Code = ".$info["http_code"]."\n";

			//print_r(simplexml_load_string($strResponse));exit;
			if($info["http_code"] == 200) {

				$xml_arr=simplexml_load_string($strResponse);

				$data=array();
				if($xml_arr->response->outcome == 'success') {
				    $data['order_id']=$order_id;
				    $data['order_id_doba']=$doba_order_id;

				    $data['order_total_doba']=$xml_arr->response->orders->order->order_total;
				    $data['state']=strval($xml_arr->response->orders->order->status);
				    $data['doba_order_id']=strval($xml_arr->response->orders->order->po_number);
				    $data['last_update_time']=time();
				    $data['goods_list_info']='';

				    if($data['state'] != 'Cancelled') {
				        $order_status = 3;
    				    if($data['state'] == 'Completed') {
        				    $da['shipping_info']['carrier']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->shipments->shipment->carrier);
        				    $da['shipping_info']['shipment_date']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->shipments->shipment->shipment_date);
        				    $da['shipping_info']['supplier_status']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->status);
        				    $da['shipping_info']['track_number']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->shipments->shipment->tracking);
        				    $data['doba_ship_info']=serialize($da['shipping_info']);
        				    $order_status = 4;
    				    }

    				    $state=$this->db->insert('trade_orders_doba_order_info',$data);

    				    curl_close($connection);
    				    if(!$state) {
    				        return false;
    				    }

    				    //更新订单利润
    				    $order_amount_usd = $this->db->query("select order_amount_usd from trade_orders WHERE order_id = '$order_id'")->row()->order_amount_usd;
    				    $order_total_doba = $this->db->query("select order_total_doba from trade_orders_doba_order_info order_id WHERE order_id= '$order_id'")->row()->order_total_doba;
    				    $order_profit_usd = ($order_amount_usd/100 - $order_total_doba) * 100;
    				    $this->db->where('order_id',$order_id)->set('order_profit_usd',$order_profit_usd)->update('trade_orders');

    				    //发放订单奖金
    				    $this->load->model('m_order');
    				    $order = $this->db->query("select * from trade_orders WHERE order_id = '$order_id'")->row_array();
    				    if(!empty($order))
    				    {
    				    	/*记录到新订单处理队列*/
                			$this->load->model('tb_new_order_trigger_queue');
                			$this->tb_new_order_trigger_queue->addNewOrderToQueue($order_id,$order['shopkeeper_id'],$order['goods_amount_usd'],$order['order_profit_usd']);
    				    }

                        /*事务开始*/
                        $this->db->trans_start();

    				    //更新订单状态
    				    $this->db->where('order_id',$order_id)->update('trade_orders',array('status'=>$order_status,'updated_at'=>date('Y-m-d H:i:s')));
    				    $this->db->insert('trade_order_remark_record',array('order_id'=>$order_id,'remark'=>'System: The order status changed.','admin_id'=>0,'created_at'=>date('Y-m-d H:i:s')));

    				    //同步到erp(doba订单-订单异常)

//    				    $update_attr = array('order_id' => $order_id, 'status'=>$order_status);
//    				    $this->m_erp->update_order_to_erp_log($update_attr);

						$insert_data = array();
						$insert_data['oper_type'] = 'modify';
						$insert_data['data']['order_id'] = $order_id;
						$insert_data['data']['status'] = $order_status;

						$this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                        $this->db->trans_complete();//事务结束

    				    return true;
				    }

				}

			}
		}

		curl_close($connection);
		return false;
	}

	/* doba订单状态监测任务 每10分钟执行一次 */
	function cron_doba_order_status_check() {
		set_time_limit(0);
		$URL = $this->doba_url;

        $this->load->model('m_erp');

		$order_doba_arr=$this->db->select('order_id')->where('is_doba_order',1)->where('status',3)->get('trade_orders')->result_array();

		if(empty($order_doba_arr)) {
			echo 'No order yet.';
			ob_flush();
			exit;
		}

		$connection = curl_init();

		$i=0;
		foreach($order_doba_arr as $order) {
			echo ++$i.'-'.$order['order_id'].":\n";

			$daba=$this->db->select('order_id_doba,state')->where('order_id',$order['order_id'])->get('trade_orders_doba_order_info')->row_array();

			if(!in_array($daba['state'], array('Cancelled','Completed','Returned'))) {  //未完成状态的订单，获取最新状态信息
				echo 'Doba Oreder:'.$daba['order_id_doba']."\n";
                ob_flush();

				$strRequest = '
                    <dce>
                      <request>
                        <authentication>
                          <username>'.$this->doba_account.'</username>
                          <password>'.$this->doba_password.'</password>
                        </authentication>
                        <retailer_id>'.$this->doba_id.'</retailer_id>
                        <action>getOrderDetail</action>
                        <order_ids>
                          <order_id>'.$daba['order_id_doba'].'</order_id>
                        </order_ids>
                      </request>
                    </dce>
                    ';

				curl_setopt($connection, CURLOPT_URL, $URL );
				curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($connection, CURLOPT_POST, 1);
				curl_setopt($connection, CURLOPT_POSTFIELDS, $strRequest);
				curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
				set_time_limit(300);
				$strResponse = curl_exec($connection);
				if(curl_errno($connection)) {
					//print "Curl error: " . curl_error($connection);
					echo "failed.\n";
					continue;
				} else {
					$info = curl_getinfo($connection);
					//print "HTTP Response Code = ".$info["http_code"]."\n";

					//print_r(simplexml_load_string($strResponse));
					if($info["http_code"] == 200) {

						$xml_arr=simplexml_load_string($strResponse);

						$data=array();
						if($xml_arr->response->outcome == 'success') {
							$data['order_status']=strval($xml_arr->response->orders->order->status);
							$data['po_number']=strval($xml_arr->response->orders->order->po_number);
							$data['shipping_info']['carrier']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->shipments->shipment->carrier);
							$data['shipping_info']['shipment_date']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->shipments->shipment->shipment_date);
							$data['shipping_info']['supplier_status']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->status);
							$data['shipping_info']['track_number']=strval($xml_arr->response->orders->order->supplier_orders->supplier_order->shipments->shipment->tracking);

							//保存最新处理信息
							$this->db->where('doba_order_id',$data['po_number'])->update('trade_orders_doba_order_info',array('state'=>$data['order_status'],'doba_ship_info'=>serialize($data['shipping_info']),'last_update_time'=>time()));

                            $deliver_time = date('Y-m-d H:i:s');

							//如果状态已经完成更新订单信息
							if($data['order_status'] == 'Completed') {

                                /*事务开始*/
                                $this->db->trans_start();

							    $this->db->where('order_id',$order['order_id'])->update('trade_orders',array('status'=>4,'deliver_time'=>$deliver_time));

                                //同步到erp(doba订单等待收货)
                                $doba_ship_info = $this->db->select('doba_ship_info')->where('order_id',$order['order_id'])
                                    ->get('trade_orders_doba_order_info')->row_array();

                                if(!empty($doba_ship_info))
                                {
                                    $doba_ship_info = unserialize($doba_ship_info['doba_ship_info']);

                                    $company_code_arr = $this->db->select('company_code')->where('company_shortname',$doba_ship_info['carrier'])
                                        ->get('trade_freight')->row_array();

                                    //如果没有对应的简码,则返回0，且运单号加上物流公司名
                                    if(empty($company_code_arr)){
                                        $logistics_code = 0;
                                        $doba_ship_info['track_number'] = $doba_ship_info['carrier'].'|'.$doba_ship_info['track_number'];
                                    }else{
                                        $logistics_code = $company_code_arr['company_code'];
                                    }

                                    $insert_data = array();
                                    $insert_data['oper_type'] = 'modify';
                                    $insert_data['data']['order_id'] = $order['order_id'];
                                    $insert_data['data']['status'] = Order_enum::STATUS_SHIPPED;
                                    $insert_data['data']['logistics_code'] = $logistics_code;
                                    $insert_data['data']['tracking_no'] = trim($doba_ship_info['track_number'],'|');
                                    $insert_data['data']['deliver_time'] = $deliver_time;

                                    $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                                    $this->db->trans_complete();//事务结束
                                }
							    echo $order['order_id']." status has changed.\n";
                                ob_flush();

							}elseif($data['order_status'] == 'Cancelled') { //订单被供应商取消

                                /*事务开始*/
                                $this->db->trans_start();

							    $this->db->where('order_id',$order['order_id'])->update('trade_orders',array('status'=>'111','updated_at'=>date('Y-m-d H:i:s')));
							    $this->db->insert('trade_order_remark_record',array('order_id'=>$order['order_id'],'remark'=>'System: The supplier has cancelled the order.','admin_id'=>0,'created_at'=>date('Y-m-d H:i:s')));

                                //同步到erp(doba订单-订单异常)
                                $insert_data = array();
                                $insert_data['oper_type'] = 'modify';
                                $insert_data['data']['order_id'] = $order['order_id'];
                                $insert_data['data']['status'] = 111;

                                $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                                $this->db->trans_complete();//事务结束

							    echo $order['order_id']." has Canceled.\n";
                                ob_flush();
							}

							echo "Modify success.\n---------------------------\n";
                            ob_flush();
						}

					}
				}
			}

		}

		curl_close($connection);
		echo "Finished.\n";
		exit;
	}

	/* 导入doba产品(CLI命令行导入)  */
	function import_doba_products_cli() {
	    //error_reporting(E_ALL);
		set_time_limit(0);

		$this->load->library('image_lib');

		$file_name = './upload/doba_products_csv_temp/doba_goods.csv';

		$handle = fopen($file_name,'rb');

		$i=0;
		$total_upload_num=0;
		while ($data = fgetcsv($handle,10000)) {
			if(empty($data[5])) {break;}

			echo $i++,'/Item id:',$data[15],"\n";
			//print_r($data);exit;
			if($i == 1) {
				echo "-OK,header here...\n";
				continue;
			}

			//检查该商品是否已经存在
			if($this->m_goods->check_doba_item($data[15])) {
				echo "OH,this goods is exist...\n";
				continue;
			}

			//开始处理数据
			$arr_main=array();
			$arr_main_detail=array();
			$arr_goods=array();

			//必须有库存而且数量要在10个以上
			if($data[33] == 'in-stock' && $data[32] > 10) {

				//检查图片大小是否在800 * 800像素以上
				if($data[42] < 500 || $data[43] < 500) {
					echo "OH,goods image is too small...\n";
					continue;
				}

				//检查销售价是否高于建议销售价
				$shop_price= number_format($data[27] * 1.35,2,'.','');  //销售利润为35%
				if($shop_price > $data[31]) {
					echo 'Shop price:',$shop_price,'/msp:',$data[31],"-OH,price is too high...\n";
					continue;
				}

				//检查运费是否过高
				if(($data[1] + $data[24]) > $shop_price * 0.7) {
				    echo 'Ship fee is too high.';
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
						$arr_cate['cate_name']=$cate;
						$arr_cate['parent_id']= $parent_id;
						$arr_cate['meta_title']=$cate;
						$arr_cate['language_id']=1;

						$this->m_goods->inser_db_cate($arr_cate);

						$parent_id=$this->db->insert_id();

					}else {
						$parent_id = $cate_id;
					}
				}
				echo "-OK,category checked...\n";

				$goods_sn_main = 'dbus'.random_string('numeric', 6).mt_rand(00, 99);

				//处理相册和详情图片
				$imgs_arr=array();
				//$imgs_arr[]=$data[41];
				if(!empty($data[44])) {
					$imgs_arr=explode('|', $data[44]);
					//array_unshift($imgs_arr, $data[41]);
				}else{
					$imgs_arr[]=$data[41];
				}
				//下载原图
				$dir_path='upload/products/db'.date('Ymd').'/'.$goods_sn_main.'/';
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

							chmod($main_img_path,0755);
						}

						//生成相册图 ,小图100*100,大图800*800
						$conf['width'] = 800;
						$conf['height'] = 800;
						$conf['thumb_marker'] = '_big';

						//大图800*800
						$this->image_lib->initialize($conf);
						$this->image_lib->resize();

						echo "-OK,big image created...\n";

						//小图100*100
						$conf['width'] = 100;
						$conf['height'] = 100;
						$conf['thumb_marker'] = '_thumb';
						$this->image_lib->initialize($conf); //重新调整配置文件
						$this->image_lib->resize();

						echo "-OK,small image created...\n";

						$img_path_arr=explode('.', $img_path);
						$img_path=$img_path_arr[0].'_thumb.'.$img_path_arr[1];
						$img_path_big=$img_path_arr[0].'_big.'.$img_path_arr[1];

						chmod($img_path,0755);
						chmod($img_path_big,0755);

						//保存图片路径到数据库
						$img_id=$this->m_goods->save_gall_img($goods_sn_main.'-1',$img_path,$img_path_big);
						echo "-OK,gall image info created...\n";
						//保存图片路径到数据库
						$img_id=$this->m_goods->save_detail_img($goods_sn_main,$img_path_big,1);
						echo "-OK,detail image info created...\n";
					}

					$img='';
					sleep(5); //防止速度太快，重名被覆盖
				}

				//处理品牌
				$brand_id=0;
				if(!empty($data[11])) {
					$brand_id=$this->m_goods->check_doba_brand($data[11]);
					if(!$brand_id) {
						$arr_brand['brand_name']=$data[11];
						$arr_brand['cate_id']=$parent_id;
						$arr_brand['language_id']=1;

						$this->m_goods->add_brand($arr_brand);

						$brand_id=$this->db->insert_id();

					}
				}
				echo "-OK,brand info created...\n";
				//处理主信息
				$arr_main['goods_sn_main'] = $goods_sn_main;
				$arr_main['cate_id'] = $parent_id;

				$arr_main['goods_name'] = $data[5];

				$item_name=trim($data[19]);
				if(!empty($item_name) && ($item_name != trim($data[5]))) {
					$arr_main['goods_name'] = $data[5].'-'.$data[19];
				}

				$arr_main['goods_img'] =$main_img_path;
				$arr_main['goods_weight'] =number_format($data[23] * 0.454,3,'.',''); //英镑转换成kg
				$arr_main['purchase_price'] =$data[27];
				$arr_main['market_price'] =$data[31]; //利润按35%算
				$arr_main['shop_price'] =$shop_price;
				$arr_main['is_free_shipping'] = ($data[1] + $data[24]) > 0 ? 0 : 1;
				$arr_main['add_user'] ='system';
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
//				$arr_main['supplier_id'] =100;
				$arr_main['supplier_id'] =1000;
				$arr_main['shipper_id'] =100;

				if($main_goods_id=$this->m_goods->add_db_goods($arr_main)) {
					echo "-OK,main goods info created...\n";
					//处理主信息详情
					$arr_main_detail['goods_main_id'] =$main_goods_id;
					$arr_main_detail['meta_title'] =$arr_main['goods_name'];
					$arr_main_detail['goods_desc'] =strip_tags($data[7],'<br><a><p>');
					$arr_main_detail['doba_details'] =$data[9];

					$this->db->insert('mall_goods_main_detail',$arr_main_detail);
					echo "-OK,main goods detail info created...\n";
					//处理子信息
					$arr_goods['goods_sn_main']=$goods_sn_main;
					$arr_goods['goods_sn']=$goods_sn_main.'-1';
					$arr_goods['goods_number']=$data[32];
					$arr_goods['language_id']=1;
					$arr_goods['price']=$arr_main['shop_price'];
					$arr_goods['purchase_price'] =$arr_main['purchase_price'];

					$this->db->insert('mall_goods',$arr_goods);
					echo "-OK,goods info created...\n";
					echo 'success:',++$total_upload_num,"\n-----------------------------------------\n" ;
				}

			}

		}
		fclose($handle);

		echo 'Upload new products number is ',$total_upload_num;
		exit;

	}

	/* 更新产品标题组合主标题和副标题 */
	function update_doba_goods_name() {
		set_time_limit(0);

		$this->load->library('image_lib');

		$file_name = './upload/doba_products_csv_temp/doba_goods.csv';

		$handle = fopen($file_name,'rb');

		$i=0;
		while ($data = fgetcsv($handle,10000)) {

			$rs=$this->db->select('goods_id,doba_item_id')->where('doba_item_id',$data[15])->get('mall_goods_main')->row_array();

			if(!empty($rs)) {
				$arr_main['goods_name'] = $data[5];

				$item_name=trim($data[19]);
				if(!empty($item_name) && ($item_name != trim($data[5]))) {
					$arr_main['goods_name'] = $data[5].'-'.$data[19];
				}

				$this->db->where('goods_id',$rs['goods_id'])->update('mall_goods_main',$arr_main);
				$this->db->where('goods_main_id',$rs['goods_id'])->update('mall_goods_main_detail',array('meta_title'=>$arr_main['goods_name']));
				echo ++$i,"\n:", $arr_main['goods_name'],"\n";
			}

		}
		fclose($handle);

		echo 'Modify ok.';
		exit;
	}

	/* 导入韩国产品  */
	function import_koreal_products_cli() {
		set_time_limit(0);

		$this->load->library('image_lib');

		$file_name = './upload/doba_products_csv_temp/TPS1111.xlsx';

		$f_data = readExcel($file_name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

		$i=0;
		$total_upload_num=0;
		foreach ($f_data as $data) {
			if(empty($data[1])) {break;}

			echo $i++,"\n";

			if($i == 1) {
				echo "-OK,header here...\n";
				continue;
			}

			//开始处理数据
			$arr_main=array();
			$arr_main_detail=array();
			$arr_goods=array();

			if($data[10] == 'in-stock') {

				//处理新分类
				$tmp_arr_cate=explode('||', $data[11]);
				$tmp_arr_cate=array_slice($tmp_arr_cate, 0,3); //只取3级分类

				$arr_cate=array();
				$parent_id=0;
				foreach($tmp_arr_cate as $cate) {
					//检查这个分类是否存在
					$cate_id=$this->m_goods->check_doba_category($cate);
					if(!$cate_id) {
						$arr_cate['cate_sn']=random_string('alnum', 10);
						$arr_cate['cate_name']=$cate;
						$arr_cate['parent_id']= $parent_id;
						$arr_cate['meta_title']=$cate;
						$arr_cate['language_id']=1;

						$this->m_goods->inser_db_cate($arr_cate);

						$parent_id=$this->db->insert_id();

					}else {
						$parent_id = $cate_id;
					}
				}
				echo "-OK,category checked...\n";

				$goods_sn_main = 'kl'.random_string('numeric', 8);

				//处理相册和详情图片
				$imgs_arr=array();
				if(!empty($data[12])) {
					$imgs_arr_all=explode('|', $data[12]);
					//array_unshift($imgs_arr, $data[41]);
				}
				$imgs_arr[]=$imgs_arr_all[0];
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

						echo "-OK,big image created...\n";

						//小图100*100
						$conf['width'] = 100;
						$conf['height'] = 100;
						$conf['thumb_marker'] = '_thumb';
						$this->image_lib->initialize($conf); //重新调整配置文件
						$this->image_lib->resize();

						echo "-OK,small image created...\n";

						$img_path_arr=explode('.', $img_path);
						$img_path=$img_path_arr[0].'_thumb.'.$img_path_arr[1];
						$img_path_big=$img_path_arr[0].'_big.'.$img_path_arr[1];

						//保存图片路径到数据库
						$img_id=$this->m_goods->save_gall_img($goods_sn_main.'-1',$img_path,$img_path_big);
						echo "-OK,gall image info created...\n";

					}

					$img='';
					sleep(5); //防止速度太快，重名被覆盖
				}

				//处理详情里面的图片
				$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
				preg_match_all($pattern,$data[13],$match);

				foreach($match[1] as $im) {
					$img_c=file_get_contents($im);

					if(!empty($img_c)) {
						$img_name=date('YmdHis').'_org.jpg';
						//保存原图
						file_put_contents($dir_path.$img_name, $img_c);

						//保存图片路径到数据库
						$img_id=$this->m_goods->save_detail_img($goods_sn_main,$dir_path.$img_name,1);
					}

					$img_c='';
					sleep(5);
				}
				echo "-OK,detail image info created...\n";

				//处理主信息
				$arr_main['goods_sn_main'] = $goods_sn_main;
				$arr_main['cate_id'] = $parent_id;

				$arr_main['goods_name'] = $data[1];

				$arr_main['goods_img'] =$main_img_path;
				$arr_main['goods_weight'] =$data[5]; //kg
				$arr_main['purchase_price'] =$data[7];
				$arr_main['market_price'] =$data[8];
				$arr_main['shop_price'] =$data[9];
				$arr_main['is_free_shipping'] =1;
				$arr_main['add_user'] ='kl_system';
				$arr_main['add_time'] =time();
				$arr_main['language_id'] =1;
				$arr_main['store_code'] ='KRSL';
				$arr_main['supplier_id'] =31;
				$arr_main['country_flag'] ='ko';
				$arr_main['sale_country'] ='410';
				$arr_main['is_doba_goods'] =0;

				if($main_goods_id=$this->m_goods->add_db_goods($arr_main)) {
					echo "-OK,main goods info created...\n";
					//处理主信息详情
					$arr_main_detail['goods_main_id'] =$main_goods_id;
					$arr_main_detail['meta_title'] =$arr_main['goods_name'];

					$arr_main_detail['goods_desc'] =strip_tags($data[13],'<br><a><p>');; //处理详情里面的图片

					$this->db->insert('mall_goods_main_detail',$arr_main_detail);
					echo "-OK,main goods detail info created...\n";
					//处理子信息
					$arr_goods['goods_sn_main']=$goods_sn_main;
					$arr_goods['goods_sn']=$goods_sn_main.'-1';
					$arr_goods['goods_number']='9999';
					$arr_goods['language_id']=1;
					$arr_goods['price']=$arr_main['shop_price'];

					$this->db->insert('mall_goods',$arr_goods);
					echo "-OK,goods info created...\n";
					echo 'success:',++$total_upload_num,"\n-----------------------------------------\n" ;
				}

			}

		}
		//fclose($handle);

		echo 'Upload new products number is ',$total_upload_num;
		exit;
	}

	/* 检查抓取图片失败的产品 ，并下架 */
	function check_goods_img_cli() {
	    $goods_rs=$this->db->select('goods_id')->where('is_doba_goods',1)->where('is_on_sale',1)->get('mall_goods_main')->result_array();
	    $all=$goods_count=count($goods_rs);
	    unset($goods_rs);

	    $page_size=1000; //每次更新1000个sku
	    $current_page=1;
	    $i=0;
	    do{
	        $current_rs=$this->db->select('goods_img,goods_id,goods_sn_main')->where('is_doba_goods',1)->where('is_on_sale',1)->limit($page_size,($current_page-1) * $page_size)->order_by('goods_id','asc')->get('mall_goods_main')->result_array();

	        if(!empty($current_rs)) {
	            foreach($current_rs as $goods_info) {
	                sleep(0.5);
	                //echo ++$i,":\n";
	                if(file_exists($goods_info['goods_img'])) {

	                    //echo "Check img passed...goods id:",$goods_info['goods_id'],"\n";
	                    continue;
	                }

	                //下架此商品
	                $this->db->where('goods_id',$goods_info['goods_id'])->update('mall_goods_main',array('is_on_sale'=>0));

	                echo '-----------ON Sale:',$goods_info['goods_sn_main'],"\n";
	            }
	        }

	        echo 'Current Page:',$current_page,'/Goods Count:',$goods_count,'/all:',$all,"\n";
	        $current_page ++ ;
	        $goods_count -= $page_size;
	    }while($goods_count > 0);

	    exit("ok\n");
	}

	/* 检查商品的有效状态
	 *
	 * Para : $goods_sn string 商品子sku或者主sku
	 * Return : 如果商品数量为0或者商品已下架，返回 fasle,否则返回true
	 *
	 * */
	function check_goods_status($goods_sn) {
	    $language_id=intval($this->session->userdata('language_id'));

	    $pos=strpos($goods_sn,'-');

	    if($pos === false) {
	        $goods_sn_main = $goods_sn;
	        $goods_sn_sub = $goods_sn.'-1';
	    }else {
	        $goods_arr=explode('-',$goods_sn);

	        $goods_sn_main = $goods_arr[0];
	        $goods_sn_sub = $goods_sn;
	    }

	    $rs_main_goods_info = $this->db->select('is_on_sale')->where('goods_sn_main',$goods_sn_main)->where('language_id',$language_id)->get('mall_goods_main')->row_array();

	    if($rs_main_goods_info['is_on_sale'] == 0) {
	        return false;
	    }

//	    $rs_sub_goods_info = $this->db->select('goods_number')->where('goods_sn',$goods_sn_sub)->where('language_id',$language_id)->get('mall_goods')->row_array();
        $this->load->model("tb_mall_goods");
        $rs_sub_goods_info = $this->tb_mall_goods->get_one("goods_number",
            ["goods_sn"=>$goods_sn_sub,"language_id"=>$language_id]);

	    if($rs_sub_goods_info['goods_number'] == 0) {
	        return false;
	    }

	    return true;
	}

	/* 获取供应商详细信息  */
	function get_supplier_info($supplier_id) {
	    $rs=array();
	    $rs=$this->db->where('supplier_id',$supplier_id)->get('mall_supplier')->row_array();

	    return $rs;
	}

	/* 提供api的商品列表 mall_goods表 danny  */
	public function get_api_goods($filter,$per_page = 100) {
	    $where = [];
	    $this->load->model("tb_mall_goods");
//	    $this->db->from('mall_goods');
//	    //$this->filter_for_category_pre($filter);
	    if (isset($filter['id'])){
//	        $this->db->where("product_id", $filter['id']);
            $where["product_id"] = $filter["id"];
	    }
	    return $this->tb_mall_goods->get_list("*",$where,[],$per_page,($filter['page'] - 1) * $per_page);
//	    return $this->db->limit($per_page, ($filter['page'] - 1) * $per_page)->get()->result_array();
	}

	/* 提供api的商品列表 mall_goods表 danny  */
	public function get_api_goods_by_goods_sn($goods_sn) {
	    $this->db->from('mall_goods_main');
	    if(!count($goods_sn)){
	        return array();
	    }
	    return $this->db->select("cate_id, goods_sn_main, goods_name, goods_name_cn, purchase_price, shop_price, language_id, is_on_sale")->where("goods_sn_main in (".join(',',$goods_sn).")")->get()->result_array();
	}

	/* 获取广告列表 */
	public function get_ads_lists($lang_id) {
	    return $this->db->where('language_id',$lang_id)->order_by('sort_order','asc')->get('mall_ads')->result_array();
	}

	/* 增加广告  */
	function add_ads($data) {
	    unset($data['ads_id']);

	    $this->db->insert('mall_ads',$data);

	    return $this->db->insert_id();
	}

	/* 编辑广告  */
	function update_ads($data) {
	    $ads_id=$data['ads_id'];
	    unset($data['ads_id']);

	    return $this->db->where('ad_id',$ads_id)->update('mall_ads',$data);
	}

	/* 获取广告信息  */
	function get_ads($ads_id) {
	    return $this->db->where('ad_id',$ads_id)->get('mall_ads')->row_array();
	}

	/* 获取有货商品的子sku */
	function get_valid_sub_sku($goods_sn_main) {
//	    $rs=$this->db->select('goods_sn')->where('goods_sn_main',$goods_sn_main)->where('goods_number >',0)->order_by('goods_sn asc')->get('mall_goods')->row_array();
        $this->load->model("tb_mall_goods");
        $rs = $this->tb_mall_goods->get_one("goods_sn",
            ["goods_sn_main"=>$goods_sn_main,"goods_number >"=>0],[],["goods_sn"=>"asc"]);
	    return isset($rs['goods_sn']) ? $rs['goods_sn'] : $goods_sn_main.'-1';
	}


	/**
	 * 上传到图片服务器
	 * $path 要保存到服务器中的路径文件
	 * $source ERP 临时目录物理路径
	 */
	function imgsvr_upload($path, $source) {

	    // img svr uri
	    $uri = $this->config->item('img_server_url').'/receive.php';

	    // post data, PHP >= 5.5.0
	    /*
	    $data = array(
	            'act' => 'upload',
	            'path' => $path,
	            'source' => curl_file_create($source),
	    );*/

	    // post data, PHP < 5.5.0
	    $data = array(
	            'act' => 'upload',
	            'path' => $path,
	            'source' => '@'.dirname(BASEPATH).'/'.$source,
	    );

	    $ch = curl_init ();
	    curl_setopt($ch, CURLOPT_URL, $uri);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    $return = json_decode(curl_exec($ch));
	    curl_close($ch);

	    if (isset($return->success) && $return->success === true) {
	        return 1;
	    } else {
	        return 0;
	    }
	}

	/**
	 * 删除图片服务器图片
	 */
	function imgsvr_delete($path) {

	    // img svr uri
	    $uri = $this->config->item('img_server_url').'/receive.php';

	    // post data
	    $data = array('act' => 'delete', 'path' => $path);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $uri);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    $return = json_decode(curl_exec($ch));
	    curl_close($ch);

	    if (isset($return->success) && $return->success === true) {
	        return 1;
	    } else {
	        return 0;
	    }
	}

	/** 获取商品详情介绍 */
	public function get_goods_info_by_id($goods_id,$goods_sn_main,$img_host,$location_id = 156){
		$goods_desc = $this->db->select('goods_desc,meta_title')->where('goods_main_id',$goods_id)->get('mall_goods_main_detail')->row_array();
		$cur_language_id = get_cookie('curLan_id', true);
		if (empty($cur_language_id)) {
			$this->load->model('tb_language');
            $cur_language_id = $this->tb_language->get_language_by_location($location_id);
		}
		$goods_img = $this->db->select('image_url')->where('goods_sn_main',$goods_sn_main)->where('language_id',$cur_language_id)->get('mall_goods_detail_img')->result_array();
		$img_str = '';
		if($goods_img)foreach($goods_img as $item)
		{
			$img_str .= '<img src="'.$img_host.$item['image_url'].'" alt="'.$goods_desc['meta_title'].'">';
		}
		return array('goods_desc'=>html_entity_decode($goods_desc['goods_desc']),'goods_img'=>$img_str);
	}

	/** 检测该语言下的商品是否存在  */
	public function is_goods_exist($goods_sn_main,$goods_sn,$language_id,$location){
//		$count =  $this->db->from('mall_goods')->where('language_id',$language_id)
//			->where('goods_sn_main',$goods_sn_main)->where('goods_sn',$goods_sn)->count_all_results();
        $this->load->model("tb_mall_goods");
        $count = $this->tb_mall_goods->get_counts(["language_id"=>$language_id,"goods_sn_main"=>$goods_sn_main,"goods_sn"=>$goods_sn]);
		//leon 2016-12-22 取消like的使用
		//$is_sale_country = $this->db->from('mall_goods_main')->where('language_id',$language_id)->where('goods_sn_main',$goods_sn_main)->where("sale_country like '%$location%'")->count_all_results();
		$is_sale_country = $this->db->from('mall_goods_main')->where('language_id',$language_id)->where('goods_sn_main',$goods_sn_main)->where('sale_country',$location)->count_all_results();
		return $count > 0 && $is_sale_country > 0 ? true : false;
	}

    /**
     * 修复相册和详情图片
     */
    public function erpapi_modify_img_url ($attr) {
       $retData = array(
           'code' => 200,
           'msg' => "",
       );
       $type = $attr['type'];
       $sku = $attr['sku'];
       $img_old_uri = $attr['img_old_uri'];
       $new_uri = $attr['img_new_uri'];
       if ($type == 1) { // 描述图片
         // 查询是否有这个详情数据有则更新
         $where = array('goods_sn_main' => $sku,'image_url' => $img_old_uri,'language_id' => $attr['language']);
         $exit = $this->db->get_where('mall_goods_detail_img', $where, 1);
         if ($exit) {
             $this->db->where($where);
             $update_Ret = $this->db->update('mall_goods_detail_img', array('image_url' => $new_uri));
             if (!$update_Ret) {
                 $retData['code'] = 1109;
                 $retData['msg'] = 'update detail fail';
                 return $retData;
             }
         } else {
             $retData['code'] = 1109;
             $retData['msg'] = 'TPS detail img DATA NOT EXIT';
             return $retData;
         }
       }else { // 相册
           if ($type == 2) { // 大图
               $where = array('goods_sn' => $sku,'big_img' => $img_old_uri);
               $up_data = array('big_img' => $new_uri);
           }elseif ($type == 3) { // 小图
               $where = array('goods_sn' => $sku,'thumb_img' => $img_old_uri);
               $up_data = array('thumb_img' => $new_uri);
           }
           $exit = $this->db->get_where('mall_goods_gallery', $where, 1);
           if ($exit) {
               $this->db->where($where);
               $update_Ret = $this->db->update('mall_goods_gallery', $up_data);
               if (!$update_Ret) {
                   $retData['code'] = 1109;
                   $retData['msg'] = 'update gallery fail';
                   return $retData;
               }
           } else {
               $retData['code'] = 1109;
               $retData['msg'] = 'TPS gallery img DATA NOT EXIT';
               return $retData;
           }
       }
       return $retData;
   }

    /**
    * 修复主图
    */
    public function erpapi_modify_main_img ($attr) {

        $retData = array(
           'code' => 200,
           'msg' => "",
       );
        $where = array('goods_sn_main' => $attr['goods_sn_main'],'language_id' => $attr['language_id']);
        $goods_info = $this->db->where($where)->select('sale_country')->get('mall_goods_main')->row_array();
        if (empty($goods_info)) {
        	$retData['code'] = 1120;
            $retData['msg'] = 'update main img detail fail';
            return $retData;
        }
        $update_Ret = $this->db->update('mall_goods_main', array('goods_img' => $attr['goods_img']), $where);
        if (!$update_Ret) {
            $retData['code'] = 1120;
            $retData['msg'] = 'update main img detail fail';
            return $retData;
        }
        return $retData;
    }

    /**
     * 取消中国语种的新品
     * @author james
     */
    public function cancel_goods_is_new($goodsData) {
        $retData = array(
            'code' => 200,
            'msg' => "",
        );
        $this->load->model("tb_mall_goods_main");;
        $saveDate = array('is_new' => 0);
        $where = array('goods_sn_main' => $goodsData['goods_sn_main'],'language_id' => $goodsData['language_id']);

        $one = $this->tb_mall_goods_main->get_one('sale_country', $where);
        if (empty($one)) {
        	$retData['code'] = 1120;
            $retData['msg'] = 'cancel_goods_is_new fail';
            return $retData;
        }
        $update_Ret = $this->db->update('mall_goods_main', $saveDate, $where);
        if (!$update_Ret) {
            $retData['code'] = 1120;
            $retData['msg'] = 'cancel_goods_is_new fail';
            return $retData;
        }
        return $retData;
    }

    /**
     * 添加品牌从ERp传递过来的数据
     */
    public function add_brand_from_erp($brandData) {
        $retData = array(
            'code' => 200,
            'msg' => "",
        );

        if($this->check_brand($brandData['brand_name'], $brandData['language_id'])) {
            $retData['code'] = 1001;
            $retData['msg'] = "分类名重复";
            return $retData;
        }

        $this->db->trans_begin();
        $this->db->insert('mall_goods_brand', $brandData);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $retData['code'] = 1001;
            $retData['msg'] = "添加失败";
            return $retData;
        }
        return $retData;
    }
}

