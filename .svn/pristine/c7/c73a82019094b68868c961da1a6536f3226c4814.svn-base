<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *　商城检索 sphinx
 * @date: 2017-03-21
 * @author: soly
 */

class Search extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /* 搜索 */
    public function index() {
  	    $this->load->helper('security');
        $keywords 	= addslashes(xss_clean(strip_tags(trim($this->input->get('keywords')))));
        $keywords   = str_replace(['%', '_'], '', $keywords);
        $str_lenght = get_str_lenght_utf8($keywords);
		if ($str_lenght > 200) { $keywords = mb_substr($keywords,0,200,'utf8'); }

		$this->load->model('tb_admin_blacklist');
		$blacklist = $this->tb_admin_blacklist->redis_get('mall:search:index:blacklist:');
		if ($blacklist) {
			$blacklist = unserialize($blacklist);
		} else {
			$blacklist = $this->tb_admin_blacklist->get_blacklist_all();
			if ($blacklist) {
				$this->tb_admin_blacklist->redis_set('mall:search:index:blacklist:', serialize($blacklist));
			};
		}
		if (in_array($keywords, $blacklist)) {
			$keywords = '';
		}
		unset($blacklist);
        /* 头部信息 */
        $this->_viewData['title']		= $keywords.' - '.lang('site_name');
        $this->_viewData['keywords']	= '';
        $this->_viewData['description']	= '';

        /* seo权重集中  */
        $this->_viewData['cate_url']	= $this->_viewData['canonical'] = base_url().'search/?keywords='.$keywords;

        /* 面包屑导航  */
        $this->_viewData['nav_title']	= $keywords;

        $this->load->library('pagination');

        $searchData = $this->input->get() ? $this->input->get() : array();

        if (!empty($keywords)) {
	        //默认的综合排序
	        $order		= 'composite';
			
	        if (isset($searchData['order'])) {
	            $order = $searchData['order'];
	        } else {
	            $searchData['order'] = $order;
	        }

	        $this->_viewData['order']	= $order;
	        $this->_viewData['arr']		= isset($searchData['arr']) ? ($searchData['arr'] == 'down' ? '' : 'down') : '';
	        $searchData['page'] 		= max((int)(isset($searchData['page']) ? $searchData['page'] : 1),1);
	        $searchData['language_id']	= intval($this->session->userdata('language_id'));
	        $searchData['keywords']		= $keywords;
			$config['per_page'] 		= 40;
			
			// 加载sphinx API
			$sphinx_config = $this->config->item('sphinx_search');
			include APPPATH.'third_party/sphinxapi.php';
			$sphinx = new SphinxClient();
			$sphinx->SetServer($sphinx_config['ip'], 9312);
			$sphinx->SetMatchMode(SPH_MATCH_EXTENDED);
			$sphinx->SetArrayResult(true);

			$sporder = 'comment_count DESC';
			switch($this->_viewData['order']) {
				case 'composite' :
					$sporder = 'comment_count desc';
					break;
				case 'sale' :
					$sporder = 'comment_star_avg desc';
					break;
				case 'comments' :
					$sporder = 'click_count desc';
					break;
				case 'price' :
					$porder  = ($searchData['arr'] == 'down') ? 'desc' : 'asc';
					$sporder = 'shop_price '.$porder;
					break;
				default :
					$sporder = 'comment_count desc';
					break;
			}
			// $offset
			$offset = (($searchData['page'] - 1) * $config['per_page']);
			$sphinx->SetLimits($offset, $config['per_page'], $sphinx_config['max_limit']);
			$sphinx->SetFilter('language_id', array($searchData['language_id']));
			$sphinx->SetFilter('is_on_sale', array(1));
			$sphinx->SetFilter('sale_country', array($this->_viewData['curLocation_id']));

			$sphinx->SetSortMode(SPH_MATCH_EXTENDED, $sporder);
			$res = $sphinx->Query ("{$keywords}", 'sp, incsp');

			$goodsData = array();
			if (isset($res['matches']) && $res['matches']) {
				$day  = date('Y-m-d H:i:s');
				foreach($res['matches'] as $rows) {
					$rows['attrs']['old_shop_price'] = $rows['attrs']['shop_price'];
					$rows['attrs']['price_off'] 	 = 0;
					$rows['attrs']['id']			 = $rows['id'];
					
					/** 商品在促销期内 **/
					if ($rows['attrs']['is_promote'] == 1) {
						$this->load->model("tb_mall_goods_promote");
					    $promote = $this->tb_mall_goods_promote->get_goods_promote_info($rows['attrs']['goods_sn_main'], $day);
						if ($promote) {
							$this->load->model("o_mall_goods_main");
					        $promote_info = $this->o_mall_goods_main->cal_price_off($promote['promote_price'], $rows['attrs'], $searchData['language_id']);

					        $rows['attrs']['shop_price'] = $promote_info['shop_price'];
					        $rows['attrs']['price_off']  = $promote_info['price_off'];
						}
						unset($promote);
					}
					
					$goodsData[] = $rows['attrs'];
				}
				unset($res['matches']);
			}
			
			$this->_viewData['goods'] = $goodsData;
			unset($goodsData);
			$this->_viewData['total_rows'] = $config['total_rows'] =  intval($res['total']);

		}

        //去掉不需要的分页参数
		$url = $this->_viewData['sphinx_search_url'];
        unset($searchData['language_id']);
        add_params_to_url($url, $searchData);
        $this->_viewData['page_link'] = $url;

        /* Pager */
        $config['base_url'] = base_url($url);
        $config['cur_page'] = $searchData['page'];
        $this->pagination->initialize_ucenter($config);
        $this->_viewData['pager'] 		= $this->pagination->create_links();
        $this->_viewData['cur_page']	= $searchData['page'];
        $this->_viewData['total_page']	= ceil($config['total_rows'] / $config['per_page']);

        /* 获取history */
        $this->_viewData['history_goods'] = $this->m_goods->get_history_list();
		unset($searchData);
        parent::index(THEME_NAME.'/','search');
    }

}







