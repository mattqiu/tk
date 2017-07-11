<?php
/**
 * Author:Soly
 * Date:2017-05-02
 */

class M_trade_invoice extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	// 映射系统分类
    private $category_maps = array(
        20      => '母婴用品',
        35      => '美妆个护用品',
        65      => '运动户外用品',
        80      => '营养保健品',
        92      => '食品酒水',
        122     => '家居日用品',
        158     => '钟表首饰',
        497     => '电子产品',
        1036    => '礼品箱包',
        1066    => '汽车用品',
        1187    => '服饰',
        1120    => '宠物用品',
        1323    => '生活费',
		999999	=> '农产品',
    );
	
	/**
	 * @params  $data  array.
	 * @return  int  100 会员id为空
	 */
	public function ucTradeMoneyRecord($data, $symbol = '-') {
		$ucid    = (isset($data['ucid']) && $data['ucid']) ? trim($data['ucid']) : 0;
		$amount  = (isset($data['money']) && $data['money']) ? $data['money'] : 0;
		$desc	 = (isset($data['desc']) && $data['desc']) ? trim($data['desc']) : '';
		$adminId = (isset($data['adminid']) && $data['adminid']) ? intval($data['adminid']) : 0;
		
		// 金额如果小于等于0 直接返回
		if ($amount <= 0) return 200;
		
		// 会员id为空
		if (!$ucid) return 100;
		
		// 操作者ID
		if (!$adminId) return 110;
		
		// 会员不存在
		$ucinfo = $this->db->select('id,amount')->where('id', $ucid)
						   ->where('status', 1)->get('users')->row_array();
		if (empty($ucinfo) || !isset($ucinfo['id'])) return 404;
		
		//
		$code      = 300;
		$mon       = date('Ym');
		$tb_name   = 'cash_account_log_'.$mon;
		$operation = $symbol.$amount;
		$this->load->model('m_commission');
		$this->load->model('m_admin_user');
		try {
			$this->db->trans_begin();
            $this->db->where('id', $ucid)
					 ->where('amount >=', $amount)
					 ->set('amount', 'amount'.$symbol.$amount, FALSE)
					 ->update('users');
			$affectRows = $this->db->affected_rows();

			if ($affectRows) {
				$this->m_commission->commissionLogs($ucid, 9, $operation, '0', '', '', $desc);
				
				$gpstr = $tb_name.'|'.$this->db->insert_id();

				$this->m_admin_user->addCommissionManageLog($adminId, $ucid, $operation, $desc, 1, $gpstr);
				$this->m_log->adminActionLog($adminId, 'admin_commission_manage', 'users', $ucid, 'amount', $ucinfo['amount'],$ucinfo['amount'].$symbol.$amount, $operation);
			}
			
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $code = 200;
            } else {
                throw new Exception('Error:trade fail.');
            }
		} catch (Exception $e) {
            $this->db->trans_rollback();
        }
		
		return $code;
	}
	
	/**
	 * 列表信息
	 */
	public function getInvoiceList($filter, $perPage = 10) {
		$this->db->from('trade_invoice');
		$this->filterWhere($filter);
		return $this->db->order_by("id", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
	}
	
	public function getInvoiceListRows($filter) {
		$this->db->from('trade_invoice');
		$this->filterWhere($filter);
		return $this->db->count_all_results();
	}
	
	public function filterWhere($filter) {
		foreach ($filter as $k => $v) {
			if ($v == '' || $k == 'page') continue;
			switch ($k) {
				case 'start':
					$this->db->where('created_at >=', ($v));
					break;
				case 'end':
					$this->db->where('created_at <=', ($v));
					break;
				default:
					$this->db->where($k, $v);
					break;
			}
		}
	}
	
	public function getCategory($language_id = 2) {
		if (isset($language_id) && $language_id) $this->db->where('language_id', $language_id);
		return $this->db->get('mall_goods_category')->result_array();
	}
	
	public function getFirstParents($cateid) {
		$cate = $this->getCategory();

		if (!isset($cate) || empty($cate)) return array();
		static $categoryArray = array();
		foreach ($cate as $key => $items) {

			if ($items['cate_id'] == $cateid) {

				//农产品发票类型
				if(in_array($items['cate_id'],array(98,194,260,985,1119,1128, 1012,897,227,1273,994,1260,1204,224,1278,1613,919,1612,1611, 1121,1235,1156,1153)))
				{
					$categoryArray = array(
							'cate_id' 	=> 999999,
							'cate_name'	=> trim($this->category_maps[999999])
					);
					break;
				}

				if (empty($items['parent_id'])) {
					$cate_map = $this->category_maps;
					$items['cate_name'] = isset($cate_map[$items['cate_id']]) ? trim($cate_map[$items['cate_id']]) : $items['cate_name'];
					$categoryArray = $items;
					break;
				} else {
					$this->getFirstParents($items['parent_id']);
				}
			} else { continue; }
		}
		unset($cate);
		return $categoryArray;
	}
	
	/**
	 * 重置数组键
	 */
	public function restChildrenArrayKey(&$array) {
		if (!isset($array) && !is_array($array)) return;
		
		foreach ($array as $key => &$value) {
			if ($value && is_array($value)) $this->restChildrenArrayKey($value);
			if ($key && $key == 'children') $value = array_values($value);
		}
	}
}
