<?php
    class m_news extends MY_Model{

        /** 得到一條新聞 */
        public function getOneNews($id){
           return $this->db->from('news')->where('id',$id)->get()->row_array();
		   //var_dump($this->db->from('news')->where('id',$id)->get()->row_array());
        }

        /** create news */
        public function createNews($data){
            unset($data['news_id']);
            $this->db->insert('news',$data);
        }

        /** update news */
        public function updateNews($data){
           $new_id =  $data['news_id'];
           unset($data['news_id']);
           $this->db->update('news',$data,array('id'=>$new_id));
        }

        public function filterForNews($filter){
            foreach ($filter as $k => $v) {
                if (!$v || $k=='page') {
                    continue;
                }
                switch ($k) {
                    case 'start':
                        $this->db->where('create_time >=', ($v));
                        break;
                    case 'end':
                        $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                        break;
                    case 'idEmail':
                        if(is_numeric($v)){
                            $this->db->where('id', $v);
                        }else{
                            $this->db->where('email', $v);
                        }
                        break;
                    default:
                        $this->db->where($k, $v);
                        break;
                }
            }
        }

        public function getNewsList($filter, $perPage = 10 ,$type = 'all') {
        $this->db->from('news n');
        $this->db->join('news_type t','t.type_id=n.cate_id','left');
		$lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
		$this->db->where('n.language_id',$lang_id);
        $this->filterForNews($filter);
        return $this->db->order_by("id", "desc")->order_by("cate_id", "asc")->order_by("sort", "asc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        }

        public function getHotNews($filter, $perPage = 2 ,$type = 'all') {
            $this->db->from('news');
            $this->db->where('hot',1);
            $this->db->where('display',1);
            if($type != 'all'){
                if($type == 'english'){
                    $this->db->where('type',1);
                }else{
                    $this->db->where('type',0);
                }
            }
            return $this->db->order_by("sort", "asc")->order_by("id", "desc")->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        }

        public function getNewsListRows($filter) {
            $this->db->from('news');
            $this->filterForNews($filter);
			$lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;
			$this->db->where('language_id',$lang_id);
            return $this->db->count_all_results();
        }

        public function filterForBoard($filter){
            foreach ($filter as $k => $v) {
                if (!$v || $k=='page' || $k =='page_size') {
                    continue;
                }
                switch ($k) {
                    case 'start':
                        $this->db->where('create_time >=', ($v));
                        break;
                    case 'end':
                        $this->db->where('create_time <=', date('Y-m-d H:i:s',strtotime($v)+86400-1));
                        break;
                    default:
                        $this->db->where($k, $v);
                        break;
                }
            }
        }

        public function getBoardList($filter,$parent_id, $perPage = 10 ,$display = 'all') {
            $this->db->from('bulletin_board');

            if($display != 'all'){
                $this->db->where('display', 1);
				if($this->m_global->isStore($parent_id)){
					$this->db->where('permission !=',3);
				}else{
					$this->db->where('permission !=',2);
				}
            }
            $this->filterForBoard($filter);
            return $this->db->order_by("sort", "desc")->order_by('important','desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        }

        public function getBoardListRows($filter,$parent_id,$country = false,$is_display = false) {
            $this->db->from('bulletin_board');


			if($country){
				$fields = 'title_'.$country;
				$this->db->where("$fields !=","0");
			}

            if($is_display){
                $this->db->where('display', 1);
				if($this->m_global->isStore($parent_id)){
					$this->db->where('permission !=',3);
				}else{
					$this->db->where('permission !=',2);
				}
            }
            $this->filterForBoard($filter);
            return $this->db->count_all_results();
        }

        /** 用戶未讀的信息數量 */
        public function getBoardRowsFromUsers($uid) {
            //return $this->db->from('bulletin_board b')->join('bulletin_board_status bs','b.id=bs.board_id and bs.uid='.$uid,'left')->where('(bs.status is null) and display=1')->count_all_results();
        	return $this->db->from('bulletin_unread')->where('uid',$uid)->count_all_results();
		}

        /** 用戶显示的的信息數量 */
        public function getBoardRowsShow($uid) {
            return $this->db->from('bulletin_board b')->join('bulletin_board_status bs','b.id=bs.board_id and bs.uid='.$uid,'left')->where('(bs.status is null or bs.status=1) and display=1')->count_all_results();

        }

        /** 用戶可讀的信息列表  未删除的 */
        public function getBoardListFromUsers($filter,$uid,$perPage = 10) {
            $this->db->from('bulletin_board b')->join('bulletin_board_status bs','b.id=bs.board_id and bs.uid='.$uid,'left')->where('(bs.status is null or bs.status=1) and display=1');
            return $this->db->order_by("sort", "desc")->order_by('important','desc')->limit($perPage, ($filter['page'] - 1) * $perPage)->get()->result_array();
        }

        /** 得到一條新聞 */
        public function getOneBoard($id){
            return $this->db->from('bulletin_board')->where('id',$id)->get()->row_array();
        }

		/** 得到5條新聞 */
        public function getFiveBoard($parent_id,$uid){
            return $data = $this->getFiveBoardByDb($parent_id,$uid);
            if (REDIS_STOP == 0) {
                $key = config_item("redis_key")['getFiveBoard'].$uid;
                $res = $this->redis_get($key);
                $res = json_decode($res,true);
                if ($res && is_array($res) ) {
                    //echo "走缓存";
                    $data = $res;
                } else {
                    $data = $this->getFiveBoardByDb($parent_id,$uid);
                }

            } else {
                $data = $this->getFiveBoardByDb($parent_id,$uid);
            }

			return $data;
		}

        public function getFiveBoardByDb($parent_id,$uid)
        {
            if($this->m_global->isStore($parent_id)){
                $this->db->where('permission !=',3);
            }else{
                $this->db->where('permission !=',2);
            }
            $this->db->where('display',1);
            $data = $this->db->select('id,title_english,title_zh,title_kr,title_hk,english,zh,hk,kr')->from('bulletin_board')->order_by('sort','DESC')->limit(5)->get()->result_array();
            foreach($data as &$item){
                $count = $this->db->from('bulletin_unread')->where('uid',$uid)->where('bulletin_id',$item['id'])->count_all_results();
                if($count){
                    $item['is_read'] = FALSE;
                }else{
                    $item['is_read'] = TRUE;
                }
            }
//            if (REDIS_STOP == 0) {
//                $key = config_item("redis_key")['getFiveBoard'] . $uid;
//                $res = $this->redis_set($key,json_encode($data),7200);
//            }
            return $data;
        }

        /** create Board */
        public function createBoard($data){
            unset($data['board_id']);
            //$this->db->insert('bulletin_board',$data);
            $sql = "INSERT INTO bulletin_board SET title_english = '".$data['title_english'].
            "', title_hk='".$data['title_hk'].
            "', title_zh = '".$data['title_zh'].
            "', title_kr = '".$data['title_kr'].
            "', english = '".$data['english'].
            "', zh = '".$data['zh'].
            "', hk = '".$data['hk'].
            "', kr = '".$data['kr'].
            "', permission = '".$data['permission'].
            "', sort = '".$data['sort'].            
            "', important = '".$data['important'].
            "', display = '".$data['display'].
            "', create_time = Now()";
            $this->db->query($sql);

            //删除缓存 m by brady 2017 05 24 start
            $id= $this->db->insert_id();
            $data['create_time'] = date("Y-m-d H:i:s");
            $this->update_bulletin_redis($id,$data,true);
			return $this->db->insert_id();
        }

        /**
         * 更新某个个id的缓存
         * @param $id
         * @param $data
         */
        public function update_bulletin_redis($id,$data,$add=false)
        {

            $this->load->model("tb_bulletin_board");
            $data['id'] = $id;
            $key = config_item("redis_key")['bulletin_board_list'].$id;
            $redis_data = $this->redis_get($key);
            //$this->redis_set($key,json_encode($data),config_item("redis_expire")['bulletin_board_list']);//是否显示没变，直接存
            if ($redis_data) {
                $redis_data = json_decode($redis_data,true);
            } else {
                $redis_data = $this->tb_bulletin_board->get_row($id);
            }

            if (is_array($redis_data)) {

                if($redis_data['display'] != $data['display'] || $redis_data['permission'] != $data['permission']) {
                    //显示有变化，会影响所有分页，删除所有缓存
                    //删除缓存 m by brady 2017 05 24 start
                    $key_prefix = config_item("redis_key")['bulletin_board_index'];
                    $keys = $this->redis_keys($key_prefix."*");
                    foreach($keys as $v) {
                        $this->redis_del($v);
                    }
                } else {
                    $keys = config_item("redis_key")['bulletin_board_list'].$id;
                    $this->redis_del($keys);
                }


            }
            if ($add == true) { //新增的要删除所有的key
                $key_prefix = config_item("redis_key")['bulletin_board_index'];
                $keys = $this->redis_keys($key_prefix."*");
                foreach($keys as $v) {
                    $this->redis_del($v);
                }
            }

        }


        /** update Board */
        public function updateBoard($data){

            $board_id =  $data['board_id'];
            unset($data['board_id']);
            //$this->db->update('bulletin_board',$data,array('id'=>$board_id));
            $sql = "UPDATE  bulletin_board SET title_english = '".$data['title_english'].
            "', title_hk='".$data['title_hk'].
            "', title_zh = '".$data['title_zh'].
            "', title_kr = '".$data['title_kr'].
            "', english = '".$data['english'].
            "', zh = '".$data['zh'].
            "', hk = '".$data['hk'].
            "', kr = '".$data['kr'].
            "', permission = '".$data['permission'].
            "', sort = '".$data['sort'].
            "', important = '".$data['important'].
            "', display = '".$data['display']. "'  where id = '".$board_id."'";

            $this->db->query($sql);
            //删除缓存 m by brady 2017 05 24 start
            $this->update_bulletin_redis($board_id,$data);
        }


        
        /**
         * 获取公告最大排序编号
         */
        public function getMaxSort()
        {
            $sql = "SELECT sort FROM bulletin_board ORDER BY sort DESC limit 0,1";
            $query = $this->db->query($sql);
            return $query->row()->sort;
        }
        
        
        /* 获取首页最新公告  */
        public function get_home_news($language){
        	return $this->db->select('title,id')->where('language_id',$language)->where('display',1)->where('cate_id',0)->order_by('sort asc,id desc')->limit(5)->get('news')->result_array();
        	
        }
        
        /* 获取新闻详情  */
        public function get_news_detail($id){
            //return $this->db->select('content')->where('id',$id)->get('news')->row_array()['content'];
            return $this->db->where('id',$id)->get('news')->row_array();
        }
        
        /* 获取新闻分类  */
        public function get_news_type() {
			$lang_id = get_cookie('admin_curLan_id',true) ? get_cookie('admin_curLan_id',true) : 1;

        	return $this->db->where('language_id',$lang_id)->get('news_type')->result_array();
        }
        
        /* 新增新闻分类  */
        public function add_type($name,$lang_id=1) {
        	$this->db->insert('news_type',array('type_name'=>$name,'language_id'=>$lang_id));

        	return $this->db->insert_id();
        }

    }