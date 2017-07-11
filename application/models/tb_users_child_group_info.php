<?php
/**
 * 表数据已经移至redis, 请勿继承MY_Model类
 * @author derrick
 * @date 2017年6月28日
 */
class tb_users_child_group_info extends CI_Model{
    protected $table_name = "users_child_group_info";

    /**
     * 源数据key
     */
    CONST GROUP_KEY = 'users_child_group_info_for_group_';
    /**
     * 上下级关系映射key
     */
    CONST LIST_KEY = 'users_child_group_info_for_list_';
    
    public function __construct(){
        parent::__construct();
        
        $this->load->library('RedisCache', array($this->table_name), 'redis');
    }
    
        /**
     * 替换一条数据
     * @param $data
     */
    public function replace($data)
    {
        return $this->db->replace($this->table_name,$data);
    }
    
    public function modify_user_level_num($uid,$level,$u_num) {
        $rel_value = $this->find_by_group_id($uid);
        $field = $this->_get_sale_rank_field_by_index($level);
        if (isset($rel_value[$field])) {
	        if($rel_value) {
	        		$rel_value[$field] = $u_num;
	        		$this->add_one($uid, $rel_value);
	        } else {
	        	$user_sql = "select parent_id from users where id = ".$uid;
	        	$user_query = $this->db->query($user_sql);
	        	$user_value = $user_query->row_array();
	            if($user_value) {
	            	$add_data = $this->_generate_object_data();
	            	$add_data['uid'] = $user_value['parent_id'];
	            	$add_data['group_id'] = $user_value;
	                $add_data[$field] = $u_num;
	                $this->add_one($uid, $add_data);
	            }
	        }
        }
       
    }
    
    /**
     * 修改用户职称
     * @param 用户id $uid
     * @param 职称 $sale_rank
     */
    public function edit_user_sale_rank($uid,$sale_rank)
    {
        $sql = "update users set sale_rank=".$sale_rank." where id = ".$uid;
        $this->db->query($sql);
    }

    /**
     * @author: derrick 获取用户的某个等级之下的所有用户数
     * @date: 2017年6月28日
     * @param: @param int $user_id 用户ID
     * @param: @param int $level_id 等级ID
     * @reurn: return_type
     */
    public function get_branch_user_total_num_before_level($user_id, $level_id) {
    	$objects = $this->find_by_user_id($user_id);
    	
    	$total = 0;
    	$keys = $this->_get_before_level_key($level_id);
    	
    	foreach ($objects as $ob) {
    		foreach ($keys as $k) {
    			if (isset($ob[$k]) && $ob[$k] > 0) {
    				$total++;
    				break;
    			}
    		}
    	}
    	return $total;
    }
    
    /**
     * @author: derrick 获取用户所有下级的某个级别数量总数
     * @date: 2017年6月29日
     * @param: @param int $user_id 父用户ID
     * @param: @param String $level_field 职称字段, mso_num, sm_num, sd_num, vp_num
     * @reurn: return_type
     */
    public function get_user_total_num_before_level($user_id, $level_field) {
    	$objects = $this->find_by_user_id($user_id);
    	 
    	$total = 0;
    	foreach ($objects as $ob) {
    		if (isset($ob[$level_field])) {
    			$total += $ob[$level_field];
    		}
    	}
    	return $total;
    }
    
    /**
     * @author: derrick 获取下级各个职称等级人数总数
     * @date: 2017年7月5日
     * @param: @param int $user_id
     * @reurn: return_type
     */
    public function get_all_childrens_total($user_id){ 
    	$childrens = $this->find_by_user_id($user_id);
    	$total = $this->_generate_object_data();
    	foreach ($childrens as $c) {
    		$total['mso_num'] += $c['mso_num'];
    		$total['sm_num'] += $c['sm_num'];
    		$total['sd_num'] += $c['sd_num'];
    		$total['vp_num'] += $c['vp_num'];
    	}
    	return $total;
    }
    
    /**
     * @author: derrick 根据父ID查询
     * @date: 2017年6月28日
     * @param: @param int $group_id
     * @reurn: unknown|multitype:
     */
    public function find_by_group_id($group_id){
		$group = $this->redis->get_hash_object($this->_get_groupid_redis_key($group_id));
		if ($group) {
			return $group;
		}
    	$group = $this->db->get_where($this->table_name, array('group_id' => $group_id), 1)->result_array();
    	if (isset($group[0])) {
    		//设置对象
    		$this->add_one($group_id, $group[0]);
    		
    		//添加映射关系
    		$this->redis->add_set_list($this->_get_listid_redis_key($group[0]['uid']), $group[0]['group_id']);
    		return $group[0];
    	}
    	return array();
    }
    
    /**
     * @author: derrick 根据用户ID查询列表
     * @date: 2017年6月29日
     * @param: @param int $user_id
     * @reurn: return_type
     */
    public function find_by_user_id($user_id) {
    	$redis_list = $this->get_childrens_ids($user_id);
    	$objects = array();
    	foreach ($redis_list as $rl) {
    		$objects[] = $this->redis->get_hash_object($this->_get_groupid_redis_key($rl));
    	}
    	return $objects;
    }
    
    /**
     * @author: derrick 更新上级用户的子用户数量
     * @date: 2017年6月28日
     * @param: @param int $group_id 上级用户ID
     * @param: @param string $level_field 更新字段
     * @param: @param string $operate 更新操作 (+, -)
     * @param: @param number $level_num 更新数量
     * @reurn: return_type
     */
    public function update_user_level_num($group_id, $level_field, $operate = '+', $level_num = 1) {
    	if ($operate == '+' || $operate == '-') {
    		$data = $this->redis->get_hash_object($this->_get_groupid_redis_key($group_id));
    		if (empty($data)) {
    			$data = $this->find_by_group_id($group_id);
    			if (empty($data)) {
    				return false;
    			}
    		}
    		if (isset($data[$level_field])) {
    			return $this->redis->incr_hash_object_field($this->_get_groupid_redis_key($group_id), $level_field, $operate == '-' ? $operate.$level_num : $level_num);
    		}
    	}
    	return false;
    }

    /**
     * @author: derrick 覆盖更新子用户数量
     * @date: 2017年6月29日
     * @param: @param int $group_id 用户ID
     * @param: @param string $level_field 更新字段
     * @param: @param int $nums 更新后数量
     * @param: @return boolean
     * @reurn: boolean
     */
    public function update_user_level_field_num($group_id, $level_field, $nums) {
    	$data = $this->redis->get_hash_object($this->_get_groupid_redis_key($group_id));
    	if (empty($data)) {
    		$data = $this->find_by_group_id($group_id);
    		if (empty($data)) {
    			return false;
    		}
    	}
    	if (isset($data[$level_field])) {
    		$data[$level_field] = $nums;
    		$this->add_one($group_id, $data);
    	}
    	return $data;
    }
    
    /**
     * @author: derrick 新增数据
     * @date: 2017年6月28日
     * @param: @param int $group_id 上级用户ID
     * @param: @param array $data 新增对象
     * @reurn: return_type
     */
    public function add_one($group_id, $data) {
    	if (empty($data)) {
    		return false;
    	}
    	$this->redis->set_hash_object($this->_get_groupid_redis_key($group_id), $data);
    }
    
    /**
     * @author: derrick 修复用户职称数据
     * @date: 2017年6月29日
     * @param: @param int $user_id 需要修复的会员ID
     * @reurn: return_type
     */
    public function fix_user_rank($user_id) {
    	//更新下级
    	$this->_fix_down_user_rank($user_id);
    	
    	//更新自身数量
    	$this->load->model('tb_users');
    	$user = $this->tb_users->getUserInfo($user_id, array('id, parent_id, sale_rank'));
    	$self = $this->_generate_object_data();
    	$self['user_id'] = $user['parent_id'];
    	$self['group_id'] = $user_id;
    	
    	$childrens = $this->find_by_user_id($user_id);
    	foreach ($childrens as $c) {
    		$self['mso_num'] += $c['mso_num'];
    		$self['sm_num'] += $c['sm_num'];
    		$self['sd_num'] += $c['sd_num'];
    		$self['vp_num'] += $c['vp_num'];
    	}
    	$idx = $this->_get_sale_rank_field_by_index($user['sale_rank']);
    	if (isset($self[$idx])) {
    		$self[$idx]++;
    	} elseif ($idx == 'gvp_num') {
    		$self['mso_num']++;$self['sm_num']++;$self['sd_num']++;$self['vp_num']++;
    	}
    	$this->add_one($user_id, $self);
		
		//更新上级
    	$this->_fix_up_user_rank($user['parent_id']);
    }
    
    /**
     * @author: derrick 单独修复用户职称变动时间
     * @date: 2017年7月4日
     * @param: @param unknown $user_id
     * @reurn: return_type
     */
    public function fix_user_sale_rank_up_time($user_id) {
    	$this->load->model('tb_users');
    	$user = $this->tb_users->getUserInfo($user_id, array('id, sale_rank, sale_rank_up_time'));
    	if (empty($user)) {
    		return false;
    	}
    	$sale_rank_up_time = $this->_get_real_sale_rank_time($user_id, $user['sale_rank']);
    	if (empty($sale_rank_up_time)) {
    		return array();
    	}
    	
    	if ($user['sale_rank_up_time'] != $sale_rank_up_time) {
    		$this->db->where('id', $user_id)->update('users', array('sale_rank_up_time' => $sale_rank_up_time));
    	}
    	return true;
    }
    
    /**
     * @author: derrick 递归获取所有子级的ID
     * @date: 2017年7月3日
     * @param: @param int $parent_id
     * @reurn: return_type
     */
    public function get_all_childrens_ids($parent_id, &$childrens = array()) {
    	$childrens = $this->get_childrens_ids($parent_id);
    	if ($childrens) {
    		foreach ($childrens as $c) {
    			$tmp_ids = $this->get_all_childrens_ids($c);
    			$childrens = array_merge($childrens, $tmp_ids);
    		}
    	}
    	return $childrens;
    }
    
    /**
     * @author: derrick 获取下一级用户IDS
     * @date: 2017年7月3日
     * @param: @param int $user_id 父级ID
     * @reurn: return_type
     */
    public function get_childrens_ids($user_id) {
    	//如果redis中的数据和db中的数据不一致, 则刷新redis的数据
    	$lists = $this->db->get_where($this->table_name, array('uid' => $user_id))->result_array();
    	$redis_list = $this->redis->get_set_length($this->_get_listid_redis_key($user_id));
    	if ($redis_list != count($lists)) {
    		foreach ($lists as $l) {
    			$this->redis->add_set_list($this->_get_listid_redis_key($user_id), $l['group_id']);
    			 
    			if (!$this->redis->check_hash_exists($this->_get_groupid_redis_key($l['group_id']))) {
    				$this->add_one($l['group_id'], $l);
    			};
    		}
    	}
    	return $this->redis->get_set_list($this->_get_listid_redis_key($user_id));
    }
    
    /**
     * 修复so
     * @param 用户id $uid
     * @param so数量 $num
     */
    public function edit_users_sale_rank_info_num($uid,$num) {
    	$ck_sql = "select * from users_sale_rank_info where uid = ".$uid;
    	$query = $this->db->query($ck_sql)->row_array();
    	if(!empty($query))
    	{
    		$sql = "update users_sale_rank_info set above_silver_num =".$num." where uid = ".$uid;
    	}
    	else
    	{
    		$sql = "insert into users_sale_rank_info set  above_silver_num = ".$num.",uid = ".$uid;
    	}
    	$this->db->query($sql);
    }
    
    
    // ------------------------------------------以下是私有方法------------------------------------------
    
    /**
     * @author: derrick 修复下级职称
     * @date: 2017年6月29日
     * @reurn: return_type
     */
    private function _fix_down_user_rank($user_id) {
    	$this->load->model('tb_users');
    	$childrens = $this->tb_users->find_by_parent_id('id', $user_id);
    	if ($childrens) {
    		$children_ranks = array();
    		foreach ($childrens as $c) {
    			//递归每个子分支
    			$children_ranks[] = $this->_fix_down_user_rank($c['id']);
    		}
    		$user = $this->tb_users->getUserInfo($user_id, array('id, parent_id, sale_rank'));
    		if (empty($user)) {
    			return false;
    		}
    		if ($children_ranks) {
    			$data = $this->_generate_object_data();
    			$data['user_id'] = $user['parent_id'];
    			$data['group_id'] = $user_id;
    			foreach ($children_ranks as $cr) {
    				$data['mso_num'] += isset($cr['mso_num']) ? $cr['mso_num'] : 0;
    				$data['sm_num'] += isset($cr['sm_num']) ? $cr['sm_num'] : 0;
    				$data['sd_num'] += isset($cr['sd_num']) ? $cr['sd_num'] : 0;
    				$data['vp_num'] += isset($cr['vp_num']) ? $cr['vp_num'] : 0;
    			}
    			
    			$idx = $this->_get_sale_rank_field_by_index($user['sale_rank']);
    			if (isset($data[$idx])) {
    				$data[$idx]++;
    			} elseif ($idx == 'gvp_num') {
		    		$data['mso_num']++;$data['sm_num']++;$data['sd_num']++;$data['vp_num']++;
		    	}
    			$this->add_one($user_id, $data);
    		}
    	}
    	 
    	//统计直系付费用户数
    	$user = $this->tb_users->getUserInfo($user_id, array('id,parent_id, user_rank, sale_rank'));
    	if (empty($user)) {
    		return false;
    	}
    	
    	if ($user['sale_rank'] < 5 && $user['user_rank'] != 4) {
    		//修复职称
    		return $this->_fix($user_id, $user['parent_id'], $user['sale_rank']);
    	}
    	
    	return $this->find_by_group_id($user_id);
    }
    
    /**
     * @author: derrick 向上修复用户职称数据
     * @date: 2017年6月29日
     * @param: @param int $user_id
     * @reurn: return_type
     */
    private function _fix_up_user_rank($user_id) {
    	$this->load->model('tb_users');
    	$user = $this->tb_users->getUserInfo($user_id, array('id, parent_id, user_rank, sale_rank'));
    	if ($user && $user['parent_id'] != 0 && $user['parent_id'] != config_item('mem_root_id') && $user['user_rank'] != 4) {
    		$this->_fix($user_id, $user['parent_id'], $user['sale_rank']);
    		
    		//更新自身数量
    		$parent = $this->_generate_object_data();
    		$parent['group_id'] = $user_id; 
    		$parent['user_id'] = $user['parent_id']; 
    		
    		$childrens = $this->find_by_user_id($user_id);
    		foreach ($childrens as $c) {
    			$parent['mso_num'] += $c['mso_num'];
    			$parent['sm_num'] += $c['sm_num'];
    			$parent['sd_num'] += $c['sd_num'];
    			$parent['vp_num'] += $c['vp_num'];
    		}
    		$user = $this->tb_users->getUserInfo($user_id, array('id, parent_id, sale_rank'));
    		$idx = $this->_get_sale_rank_field_by_index($user['sale_rank']);
    		if (isset($parent[$idx])) {
    			$parent[$idx]++;
    		} elseif ($idx == 'gvp_num') {
	    		$parent['mso_num']++;$parent['sm_num']++;$parent['sd_num']++;$parent['vp_num']++;
	    	}
	    	
    		$this->add_one($user_id, $parent);
    		
    		//继续往上更新
    		$this->_fix_up_user_rank($user['parent_id']);
    	}
    	//修复完毕
    	return true;
    }
    
    /**
     * @author: derrick 修复职称数据
     * @date: 2017年6月30日
	 * @param: @param int $user_id 修复用户ID
     * @param: @param int $parent_id 修复用户上级ID
     * @param: @param int $current_sale_rank 修复会员当前职称
     */
    private function _fix($user_id, $parent_id, $current_sale_rank) {
    	//从销售总监开始统计, 如果用户已经是副总裁, 则没有统计的必要了.
    	$rank_level = 4;

		$this->load->model(array('tb_users', 'tb_user_sale_rank_repair_log'));
        $user_info = $this->tb_users->getUserInfo($user_id,array('sale_rank_up_time','sale_rank'));
        $data =$this->find_by_group_id($user_id);
        
    	//只修复职称少加的情况
    	while ($rank_level >= $current_sale_rank) {
    		
    		$num = $this->get_branch_user_total_num_before_level($user_id, $rank_level);
    		
    		if ($num >= 3 || $rank_level == 0) {
    			//触发职称改变
    			if ($rank_level == 0) {
    				$num = $this->tb_users->count_pay_user_by_parent_id($user_id);
    				$sale_rank_info = $this->db->from('users_sale_rank_info')->where('uid', $user_id)->get()->row_array();
    				if ($sale_rank_info) {
    					$this->db->set('above_silver_num', $num)->where('uid', $user_id)->update('users_sale_rank_info');
    				} else {
    					$this->db->replace('users_sale_rank_info', array(
    							'uid' => $user_id,
    							'above_silver_num' => 0
    					));
    				}
    			}
    			
				$n_user_rank = $num >= 3 ? $rank_level+1 : $rank_level;
				$sale_rank_up_time = $this->_get_real_sale_rank_time($user_id, $n_user_rank);
				if (empty($sale_rank_up_time)) {
					return $data;
				}
				
				if ($user_info['sale_rank_up_time'] != $sale_rank_up_time || $n_user_rank != $user_info['sale_rank']) {
					//升级时间和职称有一项不同时, 则进行更新
	    			$this->db->where('id', $user_id)->update('users', array('sale_rank' => $n_user_rank, 'sale_rank_up_time' => $sale_rank_up_time));
	
	                //职称变动的会员做个日志
	                $this->tb_user_sale_rank_repair_log->add_log($user_id, $user_info['sale_rank'], $n_user_rank);
	
	    			$this->load->model('tb_users_store_sale_info_monthly');
	    			$this->tb_users_store_sale_info_monthly->user_rank_change_week_comm($user_id, $current_sale_rank, $rank_level, 0);
				}
    			
    			//更新统计
				$data = $this->get_all_childrens_total($user_id);
				$data['user_id'] = $parent_id;
				$data['group_id'] = $user_id;
    			$sql = 'SELECT sale_rank, COUNT(id) AS num FROM users WHERE parent_id = '.$user_id.' AND sale_rank = 5 GROUP BY sale_rank';
				$ranks = $this->db->query($sql)->result_array();
				foreach ($ranks as $r) {
					$data['mso_num'] += $r['num'];
					$data['sm_num'] += $r['num'];
					$data['sd_num'] += $r['num'];
					$data['vp_num'] += $r['num'];
				}
				
				if (isset($data[$this->_get_sale_rank_field_by_index($num >= 3 ? $rank_level+1 : $rank_level)])) {
					$data[$this->_get_sale_rank_field_by_index($num >= 3 ? $rank_level+1 : $rank_level)]++;
				}
				$this->add_one($user_id, $data);
				return $data;
    		}
    		$rank_level--;
    	}
    	return $data;
    }
    
    /**
     * @author: derrick 获取用户实际职称变动时间
     * @date: 2017年7月3日
     * @param: @param int $user_id 用户ID
     * @param: @param int $user_sale_rank 职称ID
     * @reurn: return_type
     */
    private function _get_real_sale_rank_time($user_id, $user_sale_rank) {
    	$sale_rank_up_time = '';
    	if ($user_sale_rank == 0) {
    		//直接升付费用户
    		$this->load->model('tb_users_level_change_log');
    		$rank_data = $this->tb_users_level_change_log->find_user_last_change($user_id);
    		if (empty($rank_data)) {
    			return $sale_rank_up_time;
    		}
    		$sale_rank_up_time = $rank_data['create_time'];
    	} else {
    		$childrens = $this->get_all_childrens_ids($user_id);
    		if ($user_sale_rank == 1) {
    			//升mso, 从users_level_change_log表里获取最新升级时间
    			$this->load->model('tb_users_level_change_log');
    			$rank_data = $this->tb_users_level_change_log->find_last_no_users_up_time($childrens);
    			if ($rank_data) {
    				$sale_rank_up_time = $rank_data['create_time'];
    			}
    		} else {
    			$this->load->model('tb_users');
    			//升msb+, 直接拿用户的sale_rank_up_time
    			$rank_data = $this->tb_users->find_special_one_from_users($childrens, 3, 'sale_rank_up_time');
    			$sale_rank_up_time = $rank_data['sale_rank_up_time'];
    		}
    	}
    	return $sale_rank_up_time;
    }
    
    /**
     * @author: derrick 获取group_id对应的redis key
     * @date: 2017年6月28日
     * @param: @param int $group_id
     * @reurn: return_type
     */
    private function _get_groupid_redis_key($group_id) {
    	return self::GROUP_KEY.$group_id;
    }
    
    /**
     * @author: derrick 获取上下级关系映射列表redis key
     * @date: 2017年6月28日
     * @param: @param unknown $uid
     * @reurn: return_type
     */
    private function _get_listid_redis_key($uid) {
    	if (is_array($uid)) {
    		$return = array();
    		foreach ($uid as $u) {
    			$return[] = self::LIST_KEY.$u;
    		}
    		return $return;
    	}
    	return self::LIST_KEY.$uid;
    }

    /**
     * @author: derrick 根据某个等级ID获取小于这个等级的所有key
     * @date: 2017年6月28日
     * @param: @param unknown $level_id
     * @reurn: return_type
     */
	private function _get_before_level_key($level_id) {
		$keys = array();
		switch ($level_id) {
			case 1:
				$keys[] = 'mso_num';$keys[] = 'sm_num';$keys[] = 'sd_num';$keys[] = 'vp_num';
				break;
			case 2:
				$keys[] = 'sm_num';$keys[] = 'sd_num';$keys[] = 'vp_num';
				break;
			case 3:
				$keys[] = 'sd_num';$keys[] = 'vp_num';
				break;
			case 4:
				$keys[] = 'vp_num';
				break;
			default:
				break;
		}
		return $keys;
	}

	/**
	 * @author: derrick 获取object格式
	 * @date: 2017年6月29日
	 * @param: @return multitype:string number 
	 * @reurn: multitype:string number
	 */
	private function _generate_object_data() {
		return array(
            'user_id' => '',
            'group_id' => '',
            'mso_num' => 0,
            'sm_num' => 0,
            'sd_num' => 0,
        	'vp_num' => 0
		);
	}

	/**
	 * @author: derrick 根据职称索引获取职称字段名
	 * @date: 2017年6月30日
	 * @param: @param int $sale_rank
	 * @reurn: return_type
	 */
	private function _get_sale_rank_field_by_index($sale_rank) {
		switch ($sale_rank) {
			case 1:
				return 'mso_num';
				break;
			case 2:
				return 'sm_num';
				break;
			case 3:
				return 'sd_num';
				break;
			case 4:
				return 'vp_num';
				break;
			case 5:
				return 'gvp_num';
		}
	}
}