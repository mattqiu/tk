<?php

class m_user_helper extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //修改或刪除
    function userIdCard($data){

        if($this->userIdCardExist($data['uid'])){
            $this->db->where('uid', $data['uid'])->update('user_id_card_info', $data);
        }
    }

    function updateIdCardStatus($uid){
        $data = array('check_status'=>1,'create_time'=>time());
        $this->db->where('uid', $uid)->update('user_id_card_info', $data);
        return $this->db->affected_rows();
    }

    //刪除圖片 如果已經上傳了圖片信息的
    function delImg($uid,$back){
        if($this->userIdCardExist($uid)){
            if($back){
                $data = array('id_card_scan_back'=>'0');
            }else{
                $data = array('id_card_scan'=>'0');
            }
            $this->db->where('uid', $uid)->update('user_id_card_info',$data);
            return $this->db->affected_rows();
        }
    }
    
    //身份证审核通过 清空保存的图片路径
    function delCardImg($uid) { 
      
           $data = array('id_card_scan'=>'0','id_card_scan_back'=>'0');
           $this->db->where('uid', $uid)->update('user_id_card_info',$data);
           return $this->db->affected_rows();
    }
    

    function getUserIdCard($uid){
        return $this->db->where('uid', $uid)->get('user_id_card_info')->row_array();
    }
    function userIdCardExist($uid){

        $exist =  $this->db->where('uid', $uid)->get('user_id_card_info')->row_array();
        return $exist ? true : false;
    }
    function findIdCardExist($uid,$num){

        $exist =  $this->db->where('id_card_num', $num)->where('uid !=',$uid)->get('user_id_card_info')->row_array();
        return $exist ? true : false;
    }

    function getOneTakeCashLog($id){

        return $this->db->from('cash_take_out_logs')->where('id',$id)->get()->row_array();
    }

    function deleteOneTakeCashLog($id){
        if($this->getOneTakeCashLog($id)){
            return $this->db->where('id',$id)->update('cash_take_out_logs',array('status'=>4));
        }else{
            return false;
        }
    }

    function addUserCash($log){
       return $this->db->where('id', $log['uid'])->set('amount', 'amount+' . $log['amount'], FALSE)->update('users');
    }

	/** 得到物流公司 */
	function getFreightName($code){
		$row =  $this->db->where('company_code',$code)->get('trade_freight')->row_array();
		if($row){
			return $row['company_name'];
		}else{
			return "";
		}
	}

	/** 確認收貨 */
	function confirm_deliver($id,$uid){
//		$count =  $this->db->from('trade_orders')->where('order_id',$id)->where('customer_id',$uid)->count_all_results();
        $this->load->model("tb_trade_orders");
        $count =  $this->tb_trade_orders->get_counts(['order_id'=>$id,'customer_id'=>$uid]);
		if($count){
//			$this->db->where('order_id',$id)->update('trade_orders',array('status'=>'5'));
//			return $this->db->affected_rows();
			return $this->tb_trade_orders->update_one(["order_id"=>$id],['status'=>'5']);
        }else{
			return 0;
		}
	}

	/** 取消订单 */
	function confirm_cancel($id,$uid){
        $this->load->model('m_erp');
        $this->load->model('m_group');
        $this->load->model("tb_trade_orders");
//		$order =  $this->db->from('trade_orders')->where('order_id',$id)->where('customer_id',$uid)->get()->row_array();
		$order = $this->tb_trade_orders->get_one("order_id,status,order_prop",["order_id"=>$id,"customer_id"=>$uid]);
		if(!$order){
			return 0;
		}
		if($order['status']!=100 && $order['status']!=2){
			return 0;
		}
		if($order['order_prop'] == 2){
//			$sub_orders = $this->db->select('order_id,shopkeeper_id,customer_id,goods_amount_usd,order_profit_usd,goods_list')->where('attach_id',$order['order_id'])->where('order_prop','1')->get('trade_orders')->result_array();
			$sub_orders = $this->tb_trade_orders->get_list_auto([
			   "select"=> 'order_id,shopkeeper_id,customer_id,goods_amount_usd,order_profit_usd,goods_list',
                "where"=>[
                    'attach_id'=>$order['order_id'],
                    'order_prop'=>'1',
                ]
            ]);
			$this->db->trans_start();//事务开始
			if($sub_orders)foreach($sub_orders as $sub_order){
				if(empty($sub_order['order_id'])){
                    continue;
                }

				//批量修改子訂單狀態
//				$this->db->where('order_id',$sub_order['order_id'])->update('trade_orders',array('status'=> Order_enum::STATUS_CANCEL));
				$this->tb_trade_orders->update_one(['order_id'=>$sub_order['order_id']],['status'=> Order_enum::STATUS_CANCEL]);

                $insert_data = array();
                $insert_data['oper_type'] = 'modify';
                $insert_data['data']['order_id'] = $sub_order['order_id'];
                $insert_data['data']['status'] = Order_enum::STATUS_CANCEL;
                $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

				//TPS库存修改(用户取消加库存)
//                $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$sub_order['order_id'])->from('trade_orders_goods')->get()->result_array();
                $this->load->model("tb_trade_orders_goods");
                $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",
                    ['order_id'=>$sub_order['order_id']]);
                $this->m_group->add_goods_number($goods, $sub_order['order_id']);
			}
			$this->db->trans_complete();
			return true;
		}else{

            $this->db->trans_start();//事务开始

//			$this->db->where('order_id',$id)->update('trade_orders',array('status'=> Order_enum::STATUS_CANCEL));
//            $res = $this->db->affected_rows();
            $res = $this->tb_trade_orders->update_one(['order_id'=>$id],['status'=> Order_enum::STATUS_CANCEL]);
            if($res > 0){
                //取消订单插入到trade_orders_log
                $this->load->model('m_trade');
                $ret = $this->m_trade->add_order_log($id,105,"用户取消",$uid);
               
                //同步到erp(用户取消订单)
                $this->load->model('m_erp');
                $insert_data = array();
                $insert_data['oper_type'] = 'modify';
                $insert_data['data']['order_id'] = $id;
                $insert_data['data']['status'] = Order_enum::STATUS_CANCEL;
                $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                //TPS库存修改(用户取消加库存)
//                $goods = $this->db->select("goods_sn,goods_number as quantity")->where('order_id',$id)->from('trade_orders_goods')->get()->result_array();
                $this->load->model("tb_trade_orders_goods");
                $goods = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number as quantity",['order_id'=>$id]);
                $this->m_group->add_goods_number($goods, $id);
            }
            $this->db->trans_complete();//事务结束
			return true;
		}
	}

	/** 得到 关注列表 */
	function getCollection($filter,$uid,$perPage=10){

		$cur_language_id = get_cookie('curLan_id', true);
		if (empty($cur_language_id)) {
		    if($filter['lan_id']) {
		        $cur_language_id=$filter['lan_id'];
		    }else {
			    $cur_language_id = 1;
		    }
		}

		$end_count = ($filter['page'] - 1) * $perPage;

		$sql = "select mgm.goods_id,mgm.is_new,mgm.goods_sn_main,mgm.goods_name,mgm.goods_img,mgm.shop_price,mgm.market_price,mgm.country_flag from mall_goods_main mgm
		where goods_id in (select goods_id from mall_wish where `user_id` = $uid order by add_time asc) and language_id=$cur_language_id limit $end_count,$perPage;";
		$data = $this->db->query($sql)->result_array();

		return $data;
	}

	/** 得到 关注列表 数量 */
	function getCollectionRows($uid){
	    $cur_language_id = get_cookie('curLan_id', true);
	    if (empty($cur_language_id)) {
	        $cur_language_id = 1;
	    }

		$res = $this->db->from('mall_wish')
						->select('goods_id')
						->where(['user_id'=>$uid])
						->get()
						->result_array();

		if (empty($res)) {
			return 0;
		} else {
			$goods_ids = [];
			foreach ($res as $v) {
				$goods_ids[] = $v['goods_id'];
			}
			$count_res = $this->db->from('mall_goods_main')
					->select('count(*) as nums')
					->where_in('goods_id',$goods_ids)
					->where(['language_id'=>$cur_language_id])
					->get()
					->row_array();

			if (!empty($count_res)) {
				return $count_res['nums'];
			} else {
				return 0;
			}
		}
	}

	/** 取消关注 */
	function cancel_collection($goods_id,$uid){
		$count = $this->db->from('mall_wish')->where('goods_id',$goods_id)->where('user_id',$uid)->count_all_results();
		if($count){
			$this->db->where('goods_id',$goods_id)->where('user_id',$uid)->delete('mall_wish');
			return $this->db->affected_rows();
		}else{
			return FALSE;
		}
	}

	/** 添加评论 */
	function add_goods_comments($com_user,$com_contents,$goods_id,$add_time,$com_score,$goods_sn_main){
		$count = $this->db->from('mall_goods_main')->where('goods_id',$goods_id)->where('goods_sn_main',$goods_sn_main)->count_all_results();
		if($count){
			$comments['com_user'] = $com_user;
			$comments['com_contents'] = $com_contents;
			$comments['goods_id'] = $goods_id;
			$comments['com_score'] = $com_score/2;
			$comments['add_time'] = $add_time;
			$comments['goods_sn_main'] = $goods_sn_main;
			$this->db->insert('mall_goods_comments',$comments);

			return $this->db->affected_rows();
		}else{
			return FALSE;
		}

	}

	/** 产品综合分数 */
	function average_score($goods_id,$goods_sn_main){
		$avg_score =  $this->db->select_avg('com_score')->where('goods_id',$goods_id)->get('mall_goods_comments')->row_array();
		$this->db->where('goods_sn_main',$goods_sn_main)->set('comment_count','comment_count + 1',FALSE)->set('comment_star_avg',$avg_score['com_score'],FALSE)->update('mall_goods_main');
		return $this->db->affected_rows();
	}

	/** 訂單狀態已完成 */
	function complete_order($order_id,$uid){
//		$count =  $this->db->from('trade_orders')->where('order_id',$order_id)->where('customer_id',$uid)->count_all_results();
		$this->load->model("tb_trade_orders");
        $count =  $this->tb_trade_orders->get_counts(["order_id"=>$order_id,"customer_id"=>$uid]);
		if($count){
//			$this->db->where('order_id',$order_id)->update('trade_orders',array('status'=>6));
//			return $this->db->affected_rows();
			return $this->tb_trade_orders->update_one(["order_id"=>$order_id],["status"=>6]);
		}else{
			return 0;
		}
	}

	/** 商品评价操作 */
	function do_comments($data){

//		$row =  $this->db->from('trade_orders')->where('order_id',$data['order_id'])->where('customer_id',$data['uid'])->where('status',5)->get()->row_array();
		$this->load->model("tb_trade_orders");
		$row = $this->tb_trade_orders->get_one("shopkeeper_id",["order_id"=>$data['order_id'],"customer_id"=>$data['uid'],"status"=>5]);
		if(empty($row)){
			return array('success'=>0,'msg'=>lang('try_again'));
		}
		if($row['shopkeeper_id'] == $data['uid']){
			$url = base_url('ucenter/my_orders_new');
		}else{
			$url = base_url('ucenter/my_other_orders');
		}
		foreach($data['goods_start'] as $goods_id=>$goods_score){
			$service_score = $data['goods_service'][$goods_id];
			$goods_sn_main = $data['goods_sn_main'][$goods_id];
			$total = $service_score + $goods_score;
			$is_add = $this->add_goods_comments($data['com_user'],'nice',$goods_id,$data['time'],$total,$goods_sn_main);

			//统计产品的综合分数 并改变修改產品的comment_count ，平均分數
			$is_average = $this->average_score($goods_id,$goods_sn_main);

		}

		/** 訂單狀態為已完成 */
		$is_success = $this->complete_order($data['order_id'],$data['uid']);
		if($is_success > 0){
			return array('success'=>1,'msg'=>lang('update_success'),'url'=>$url);
		}else{
			return array('success'=>0,'msg'=>lang('try_again'));
		}
	}

}
