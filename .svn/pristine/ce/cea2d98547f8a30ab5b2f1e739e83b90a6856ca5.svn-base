<?php
/**
 * User: jason
 * Date: 2016/3/22
 * Time: 15:12
 */
class m_erp extends CI_Model{

    function __construct()
    {
        parent::__construct();
    }


    /* 添加到订单推送表 */
    public function add_order_to_erp_log($order){

        $this->load->model('tb_mall_goods');
        //订单参数
        $insert_attr = array();
        $insert_attr['order_id'] = $order['order_id'];
        $insert_attr['order_prop'] = $order['order_prop'];
        $insert_attr['customer_id'] = $order['customer_id'];
        $insert_attr['consignee'] = $order['consignee'];
        $insert_attr['phone'] = $order['phone'];
        $insert_attr['reserve_num'] = $order['reserve_num'];
        $insert_attr['address'] = $order['address'];
        $insert_attr['zip_code'] = $order['zip_code'];
        $insert_attr['customs_clearance'] = $order['customs_clearance'];
        $insert_attr['deliver_time_type'] = $order['deliver_time_type'];
        $insert_attr['remark'] = $order['remark'];
        $insert_attr['created_at'] = date('Y-m-d H:i:s');
        $insert_attr['shipper_id'] = $order['shipper_id'];
        $insert_attr['status'] = $order['status'];

        //补充参数
//        $order_info = $this->db->query("select * from trade_orders where order_id = '{$order['order_id']}'")->row_array();
        $this->load->model("tb_trade_orders");
        $order_info = $this->tb_trade_orders->get_one_auto(["where"=>["order_id"=>$order['order_id']]]);
        if(!empty($order_info)){
            $insert_attr['id_no'] = $order_info['ID_no'];
            $insert_attr['id_img_front'] = $order_info['ID_front'];
            $insert_attr['id_img_reverse'] = $order_info['ID_reverse'];
            $insert_attr['currency'] = $order_info['currency'];
            $insert_attr['currency_rate'] = $order_info['currency_rate'];
            $insert_attr['payment_type'] = $order_info['payment_type'];
            $insert_attr['discount_type'] = $order_info['discount_type'];
            $insert_attr['goods_amount'] = $order_info['goods_amount'];
            $insert_attr['deliver_fee'] = $order_info['deliver_fee'];
            $insert_attr['order_amount'] = $order_info['order_amount'];
            $insert_attr['goods_amount_usd'] = $order_info['goods_amount_usd'];
            $insert_attr['deliver_fee_usd'] = $order_info['deliver_fee_usd'];
            $insert_attr['discount_amount_usd'] = $order_info['discount_amount_usd'];
            $insert_attr['order_amount_usd'] = $order_info['order_amount_usd'];
            $insert_attr['order_profit_usd'] = $order_info['order_profit_usd'];
            $insert_attr['txn_id'] = $order_info['txn_id'];
            $insert_attr['pay_time'] = $order_info['pay_time'];
            $insert_attr['receive_time'] = $order_info['receive_time'];
        }

        //如果是doba订单,订单性质为99
        if(isset($order['is_doba_order'])&& $order['is_doba_order'] == 1){
            $insert_attr['order_prop'] = 99;
        }

        //商品子sku 和 数量
        $goods = array();
        $goods_list = explode("$",$order['goods_list']);
        foreach($goods_list as $item){
            if($item == ''){
                continue;
            }
            $goods_arr = explode(":",$item);

            //获取商品价格
            $goods_price = $this->tb_mall_goods->get_goods_price($goods_arr[0]);

            //获取商品库存
            $mall_goods_info = $this->tb_mall_goods->get_goods_number($goods_arr[0],$order['area']);
            $goods_number = $mall_goods_info['goods_number'];

            //获取商品主SKU和supplier_id
//            $goods_sn_main_arr = $this->db->query("select goods_sn_main from mall_goods where goods_sn = '{$goods_arr[0]}' ")->row_array();
            $this->load->model("tb_mall_goods");
            $goods_sn_main_arr = $this->tb_mall_goods->get_one("goods_sn_main",["goods_sn"=>$goods_arr[0]]);

//            $goods_info = $this->db->select('goods_sn_main,supplier_id')
//                ->where('goods_sn_main',$goods_sn_main_arr['goods_sn_main'])
//                ->get('mall_goods_main')->row_array();
            $this->load->model("tb_mall_goods_main");
            $goods_info = $this->tb_mall_goods_main->get_one_auto([
                "select"=>"goods_sn_main,supplier_id",
                "where"=>['goods_sn_main'=>$goods_sn_main_arr['goods_sn_main']]
            ]);
            $goods[] = array(
                'goods_sn_main'=>$goods_info['goods_sn_main'],
                'supplier_id'=>$goods_info['supplier_id'],
                'goods_sn'=>trim($goods_arr[0]),
                'quantity'=>trim($goods_arr[1]),
                'original_store'=>$goods_number,
                'price'=>$goods_price * 100
            );
        }
        $insert_attr['goods'] = $goods;

        $url = 'Api/Order/addTPSOrder';

        $insert_data = array(
            'order_id'=>$order['order_id'],
            'api_url'=>$url,
            'api_param' => serialize($insert_attr)
        );
        $this->db->insert('trade_order_to_erp_logs',$insert_data);
    }


    /* 修改订单信息到推送表 */
    public function update_order_to_erp_log($update_attr){

        //如果状态为99(取消订单)、98(退货),则要增加goods数组
        if($update_attr['status'] == 99 || $update_attr['status'] == 98){
            $this->load->model('tb_mall_goods');
            $this->load->model('tb_trade_orders_goods');
//            $goods_info = $this->db->query("select goods_sn,goods_number from trade_orders_goods where order_id = '{$update_attr['order_id']}'")->result_array();
            $goods_info = $this->tb_trade_orders_goods->get_list("goods_sn,goods_number",
                ["order_id"=>$update_attr['order_id']]);
//            $area = $this->db->query("select area from trade_orders where order_id = '{$update_attr['order_id']}'")->row()->area;
            $this->load->model("tb_trade_orders");
            $area = $this->tb_trade_orders->get_one_auto([
                "select"=>"area",
                "where"=>["order_id"=>$update_attr['order_id']]
            ]);
            $area = $area['area'];
            $goods = array();
            foreach($goods_info as $item){
                //获取商品库存
                $mall_goods_info = $this->tb_mall_goods->get_goods_number($item['goods_sn'],$area);
                if($mall_goods_info == array()){
                    continue;
                }
                $goods_number = $mall_goods_info['goods_number'];
                $is_lock = $mall_goods_info['is_lock'];

                //如果没有锁定
                if($is_lock == 0) {
                    $goods[] = array('goods_sn' => $item['goods_sn'], 'alter_type' => 'inc', 'original_store' => $goods_number, 'alter_number' => $item['goods_number']);
                }
            }

            if(!empty($goods)) {
                $update_attr['goods'] = $goods;
            }
        }

        $url = "Api/Order/updateTPSOrder";
        $insert_data = array(
            'order_id'=>$update_attr['order_id'],
            'api_url'=>$url,
            'api_param' => serialize($update_attr)
        );
        $this->db->insert('trade_order_to_erp_logs',$insert_data);
    }

    /* 添加订单备注到推送表 */
    public function add_remark_to_erp_log($update_attr){
        //根据操作人id查询操作人邮箱
        $this->load->model('m_admin_user');
        $one = $this->m_admin_user->getInfo($update_attr['recorder']);
        $update_attr['recorder'] = isset($one['email'])?$one['email']:$update_attr['recorder'];
        $url = "Api/Order/addTPSOrderRemark";
        $insert_data = array(
            'order_id'=>$update_attr['order_id'],
            'api_url'=>$url,
            'api_param' => serialize($update_attr)
        );
        $this->db->insert('trade_order_to_erp_logs',$insert_data);
    }

    /* 修改商品库存到推送表 */
    public function modify_goods_qty_to_erp_log($update_attr){
        $url = "/Api/Commodity/modifyStore";
        $insert_data = array(
            'goods_sn' => $update_attr['goods_sn'],
            'api_url' =>$url,
            'api_param' => serialize($update_attr),
            'create_time'=>date('Y-m-d H:i:s',time())
        );
        $this->db->insert('mall_goods_sync_number',$insert_data);
    }


    /* 同步订单到erp */
    public function sync_order_to_erp(){

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $cron = $this->db->query("select * from cron_doing WHERE cron_name = 'sync_order_to_erp'")->row_array();
        if(!empty($cron)){
            if($cron['false_count'] > 60){
                $this->db->delete('cron_doing', array('cron_name' => 'sync_order_to_erp'));
                return false;
            }
            $this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
            return false;
        }

        //计划任务开始,插入计划任务
        $this->db->insert('cron_doing', array('cron_name' => 'sync_order_to_erp'));

        //获取log订单信息
        // $order_list = $this->db->select('*')->get('trade_order_to_erp_logs')->limit(1000)->result_array();
        $order_list = $this->db->query("select * from trade_order_to_erp_logs order by id limit 3000")->result_array();

        //循环推送订单
        foreach($order_list as $order)
        {
            $url = $order['api_url'];
            $param = unserialize($order['api_param']);
            $snyRet = erp_api_query($url, $param);
            if (isset($snyRet['code']) && $snyRet['code'] == 200) {
                $this->db->query("delete from trade_order_to_erp_logs WHERE id = {$order['id']}");
            } else {
                //同步出错,添加错误信息,终止计划任务
                $error = var_export($snyRet,1);
                $this->db->where('id',$order['id'])->set('error',$error)->update('trade_order_to_erp_logs');
                // $this->db->query("delete from cron_doing WHERE cron_name = 'sync_order_to_erp'");
                // exit;
            }
        }
        //计划任务完成,删除任务
        $this->db->query("delete from cron_doing WHERE cron_name = 'sync_order_to_erp'");
    }



    /* 同步订单到erp */
    public function mall_goods_sync_number(){
        $cron = $this->db->query("select * from cron_doing WHERE cron_name = 'mall_goods_sync_number'")->row_array();
        if(!empty($cron)){
            if($cron['false_count'] > 5){
                $this->db->delete('cron_doing', array('cron_name' => 'mall_goods_sync_number'));
                return false;
            }
            $this->db->where('id',$cron['id'])->update('cron_doing',array('false_count'=>$cron['false_count']+1));
            return false;
        }

        //计划任务开始,插入计划任务
        $this->db->insert('cron_doing', array('cron_name' => 'mall_goods_sync_number'));

        //获取log订单信息
        $goods_sn_list = $this->db->select('*')->get('mall_goods_sync_number')->result_array();

        //循环推送订单
        foreach($goods_sn_list as $item)
        {
            $url = $item['api_url'];
            $param = unserialize($item['api_param']);
            $snyRet = erp_api_query($url, $param);
            if (isset($snyRet['code']) && $snyRet['code'] == 200) {
                $this->db->query("delete from mall_goods_sync_number WHERE id = {$item['id']}");
            } else {
                //同步出错,添加错误信息,终止计划任务
                $error = var_export($snyRet,1);
                $this->db->where('id',$item['id'])->set('error',$error)->update('mall_goods_sync_number');
                $this->db->query("delete from cron_doing WHERE cron_name = 'mall_goods_sync_number'");
                exit;
            }
        }
        //计划任务完成,删除任务
        $this->db->query("delete from cron_doing WHERE cron_name = 'mall_goods_sync_number'");
    }

    /**
     * 添加到TPS订单同步ERP队列表
     * @param $insert_data 订单数据
     * User: Ckf
     * Date：2016/11/23
     */
    public function trade_order_to_erp_oper_queue($insert_data){

        //订单备注的 admin_id 换成邮箱
        $this->load->model('m_admin_user');
        if($insert_data['oper_type'] == 'remark'){
            $one = $this->m_admin_user->getInfo($insert_data['data']['recorder']);
            $insert_data['data']['recorder'] = isset($one['email'])?$one['email']:$insert_data['data']['recorder'];
        }

        $insert_data = array(
            'order_id' => $insert_data['data']['order_id'],
            'oper_data' => serialize($insert_data),
            'oper_time'=>date('Y-m-d H:i:s',time())
        );

        $res = $this->db->insert('trade_order_to_erp_oper_queue',$insert_data);
        return $res;
    }

    /**
     * 添加到TPS库存实时同步ERP队列表
     * @param $insert_data 包含:商品sn(goods_sn) 商品数量(quantity) 增加或减少(oper_type,dec 减少；inc 增加)
     * User: Ckf
     * Date：2016/11/23
     */
    public function trade_order_to_erp_inventory_queue($insert_data){

        $insert_data = array(
            'goods_sn' => $insert_data['goods_sn'],
            'oper_type' => $insert_data['oper_type'],
            'quantity' => $insert_data['quantity'],
            'inventory' => $insert_data['inventory'],
            'order_id' => $insert_data['order_id'],
        );
        $res = $this->db->insert('trade_order_to_erp_inventory_queue',$insert_data);
        $last_id = $this->db->insert_id();
        return $res;
    }

    /**
     * 订单推送到ERP定时任务,1分钟执行一次,每次推送128条
     * User:Ckf
     * Date:2016/11/26
     */
    public function trade_order_to_erp_orders(){
        $file = '/tmp/queueLog_oper_queue.txt';
        if(!file_exists($file)){ //如果文件不存在（默认为当前目录下）
            fopen($file,'w+');
        }else{
            fopen($file,'a+');
        }

        //检测脚本是否正在执行
        $this->load->model("tb_empty");
        $redis_key = "m_erp:trade_order_to_erp_orders";
        $res = $this->tb_empty->redis_get($redis_key);
        if($res)
        {
            if($res == "1")
            {
                file_put_contents($file,"...\n", FILE_APPEND);//写入日志
                return;
            }
        }
        //标识脚本正在执行
        $this->tb_empty->redis_set($redis_key,"1");
        $ttl = $this->tb_empty->redis_ttl($redis_key);
        if($ttl == -1)
        {
            //防中断异常，120秒后自动标识为脚本不在执行状态
            $this->tb_empty->redis_setTimeout($redis_key,120);
        }

        $num = config_item('push_order_numbers');
        for($i = 0; $i < 95; $i++){
            $content = '';
            $content .= "程序开始:".date('Y-m-d H:i:s',time())."\n";
            $startMicrotime1 = microtime(true);
            $order_list = $this->db->query("select id,order_id,oper_data,oper_time from trade_order_to_erp_oper_queue order by id limit $num")->result_array();
            if(empty($order_list)){
                return false;
            }

            $content .= "affected_rows:".$this->db->affected_rows()."\n";
            $sta = $order_list[0];
            $end = end($order_list);
            $content .= "staId:".$sta['id']."\n";
            $content .= "endId:".$end['id']."\n";
            //记录 $order_list 每条的id,同步成功后统一删除记录
            $ids = '';
            foreach($order_list as $v){
                $ids .= "'".$v['id']."',";
            }
            $ids = rtrim($ids,',');
            $url = '/Home/Api/orderOperQueuePush';
            $param['queue_data'] = $order_list;
            $startMicrotime = microtime(true);
            $snyRet = erp_api_query($url, $param);
            $endMicrotime = microtime(true);
            $durationTime = round($endMicrotime - $startMicrotime, 3);
            $content .= "请求时间:".$durationTime."\n";
            $content .= "code:".$snyRet['code']."\n";
            $content .= "msg:".$snyRet['msg']."\n";
            $endMicrotime1 = microtime(true);
            if (isset($snyRet['code']) && $snyRet['code'] == 200) {
                $durationTime1 = round($endMicrotime1 - $startMicrotime1, 3);
                if($durationTime1 > 8){
                    @$this->db->reconnect();
                }
                $this->db->query("delete from trade_order_to_erp_oper_queue WHERE id in ($ids)");
            }
            $content .= "程序结束:".date('Y-m-d H:i:s',time())."\n\n";
            file_put_contents($file,$content, FILE_APPEND);//写入
        }
        //标识脚本已经跑完
        $this->tb_empty->redis_del($redis_key);
    }
    
    /**
     * 推送订单到erp, 队列推送形式
     * @author: derrick
     * @date: 2017年3月24日
     * @param: @param unknown $queen_data
     * @param: @return boolean
     * @reurn: boolean
     */
    public function trade_order_to_erp_orders_from_queen($queen_data){
    
    	$file = '/tmp/queueLog_oper_queue.txt';
    	if(!file_exists($file)){ //如果文件不存在（默认为当前目录下）
    		fopen($file,'w+');
    	}else{
    		fopen($file,'a+');
    	}
    	$num = config_item('push_order_numbers');
    	for($i = 0;$i < 12;$i++){
    		$content = '';
    		$content .= "程序开始:".date('Y-m-d H:i:s',time())."\n";
    		$startMicrotime1 = microtime(true);
    		$order_list = $this->db->query("select id,order_id,oper_data,oper_time from trade_order_to_erp_oper_queue order by id limit $num")->result_array();
    		if(empty($order_list)){
    			return false;
    		}
    
    		$content .= "affected_rows:".$this->db->affected_rows()."\n";
    		$sta = $order_list[0];
    		$end = end($order_list);
    		$content .= "staId:".$sta['id']."\n";
    		$content .= "endId:".$end['id']."\n";
    		//记录 $order_list 每条的id,同步成功后统一删除记录
    		$ids = '';
    		foreach($order_list as $v){
    			$ids .= "'".$v['id']."',";
    		}
    		$ids = rtrim($ids,',');
    		$url = '/Home/Api/orderOperQueuePush';
    		$param['queue_data'] = $order_list;
    		$startMicrotime = microtime(true);
    		$snyRet = erp_api_query($url, $param);
    		$endMicrotime = microtime(true);
    		$durationTime = round($endMicrotime - $startMicrotime, 3);
    		$content .= "请求时间:".$durationTime."\n";
    		$content .= "code:".$snyRet['code']."\n";
    		$content .= "msg:".$snyRet['msg']."\n";
    		$endMicrotime1 = microtime(true);
    		if (isset($snyRet['code']) && $snyRet['code'] == 200) {
    			$durationTime1 = round($endMicrotime1 - $startMicrotime1, 3);
    			if($durationTime1 > 8){
    				@$this->db->reconnect();
    			}
    			$this->db->query("delete from trade_order_to_erp_oper_queue WHERE id in ($ids)");
    		}
    		$content .= "程序结束:".date('Y-m-d H:i:s',time())."\n\n";
    		file_put_contents($file,$content, FILE_APPEND);//写入
    		sleep(2);
    	}
    }

    /**
     * 库存推送到ERP定时任务,1秒钟执行一次，每次推送128条
     * User:Ckf
     * Date:2016/11/26
     */
    public function trade_stock_to_erp(){

        $this->db->insert('single_task_control', array('task_name' => 'trade_stock_to_erp'));
        $reg = $this->db->affected_rows();  //影响多少条记录,返回1插入成功，返回-1插入失败
        if($reg != 1) {//插入失败，则脚本还在执行，禁止重复插入
            $cron = $this->db->query("select task_name,run_cycle_num from single_task_control WHERE task_name = 'trade_stock_to_erp'")->row_array();
            if($cron['run_cycle_num'] > 1){
                $this->db->delete('single_task_control', array('task_name' => 'trade_stock_to_erp'));
                return false;
            }
            $this->db->where('task_name','trade_stock_to_erp')->update('single_task_control',array('run_cycle_num'=>$cron['run_cycle_num']+1));
            return false;
        }else{
            $file = '/tmp/queueLog_inventory_queue.txt';

            if(!file_exists($file)){ //如果文件不存在（默认为当前目录下）
                fopen($file,'w+');
            }else{
                fopen($file,'a+');
            }

            //获取log订单信息
            $num = config_item('push_stock_numbers');
            for($i = 0;$i < 16;$i++){
                $content = '';
                $content .= "程序开始:".date('Y-m-d H:i:s',time())."\n";

                $startMicrotime1 = microtime(true);
                $order_list = $this->db->query("select id,goods_sn,oper_type,quantity,inventory,order_id,oper_time from trade_order_to_erp_inventory_queue WHERE order_id != '' order by id limit $num")->result_array();
                if(empty($order_list)){
                    $this->db->query("delete from single_task_control WHERE task_name = 'trade_stock_to_erp'");
                    return false;
                }
                $content .= "affected_rows:".$this->db->affected_rows()."\n";
                $sta = $order_list[0];
                $end = end($order_list);
                $content .= "staId:".$sta['id']."\n";
                $content .= "endId:".$end['id']."\n";

                //记录 $order_list 每条的id,同步成功后统一删除记录
                $ids = '';
                foreach($order_list as $v){
                    $ids .= "'".$v['id']."',";
                }
                $ids = rtrim($ids,',');
                $url = '/Home/Api/orderInventoryQueuePush';
                $param['queue_data'] = $order_list;
                $startMicrotime = microtime(true);
                $snyRet = erp_api_query($url, $param);
                $endMicrotime = microtime(true);
                $durationTime = round($endMicrotime - $startMicrotime, 3);
                $content .= "请求时间:".$durationTime."\n";
                $content .= "code:".$snyRet['code']."\n";
                $content .= "msg:".$snyRet['msg']."\n";
                $endMicrotime1 = microtime(true);
                if (isset($snyRet['code']) && $snyRet['code'] == 200) {
                    $durationTime1 = round($endMicrotime1 - $startMicrotime1, 3);
                    if($durationTime1 > 8){
                        @$this->db->reconnect();
                    }
                    $this->db->query("delete from trade_order_to_erp_inventory_queue WHERE id in ($ids)");
                }

                //计划任务完成,删除任务
                $this->db->query("delete from single_task_control WHERE task_name = 'trade_stock_to_erp'");
                $content .= "程序结束:".date('Y-m-d H:i:s',time())."\n\n";
                file_put_contents($file,$content, FILE_APPEND);//写入
//                sleep(2);
            }
        }
    }

    public function repair_erp_order_sn($old_sku,$new_sku,$order_id)
    {
        $content = "";
        $url = '/Home/Api/repairOrderGoodsSku';
        $param['oldMainSn'] = $old_sku;
        $param['newMainSn'] = $new_sku;
        $param['orderId'] = $order_id;
        $startMicrotime = microtime(true);
        $snyRet = erp_api_query($url, $param);
        $endMicrotime = microtime(true);
        $durationTime = round($endMicrotime - $startMicrotime, 3);
        $content .= "require time:".$durationTime."|";
        $content .= "code:".$snyRet['code']."|";
        $content .= "msg:".$snyRet['msg']."|";
        return [
            "code"=>$snyRet['code'],
            "msg"=>$content,
        ];
    }
    
    /**
     * 新建订单到外代erp
     * @author: xiarong
     * @date: 2017年6月20日
     */
    public function wd_erp_new_push($insert_data) {
        $this->load->model('tb_empty');
        foreach ($insert_data as $value) {
            //修改订单为发货中，添加操作记录
            $houzui=$this->tb_empty->get_table_ext($value['order_id']);
            $this->db->where('order_id',$value['order_id'])->update('trade_orders'.$houzui,['status'=>'1']);//改变订单状态
            $this->db->insert('trade_orders_log', array(
                    'operator_id' => 0,
                    'order_id' => $value['order_id'],
                    'oper_code' => 108,//沃好
                    'statement' => '订单推送给海关接口，订单状态改为status:3发货中',
                    'update_time' => date("Y-m-d H:i:s",time()),
                ));
            //补齐订单信息
                        //            $value2 = $this->db->select('txn_id,payment_type,created_at,deliver_fee,goods_amount')->from('trade_orders')->where('order_id',$value['order_id'])->get()->row_array();
            $this->load->model("tb_trade_orders");
            $value2 =$this->tb_trade_orders->get_one_auto(["select"=>'txn_id,payment_type,created_at,deliver_fee,goods_amount',"where"=>['order_id'=>$value['order_id']]]);
            //支付方式
            $pay_name = $this->db->select('pay_name')->from('mall_payment_new')->where('pay_id',$value2['payment_type'])->get()->row_array();
            //补齐用户信息
            $userinfo = $this->db->select('name,id_card_num')->from('users')->where('id',$value['customer_id'])->get()->row_array();
            //补齐地址信息
            $songhuodizhi = $this->db->select('country,addr_lv2,addr_lv3,addr_lv4')->from('trade_user_address')->where('uid',$value['customer_id'])->where('address_detail',  end(explode(' ', $value['address'])))->get()->row_array();
            $address_info = $this->db->select('name,code')->from('trade_addr_linkage')->where('country_code',$songhuodizhi['country'])->where_in('code',array($songhuodizhi['addr_lv2'],$songhuodizhi['addr_lv3'],$songhuodizhi['addr_lv4']))->get()->result_array();
            foreach ($address_info as $key => $vae) {
                unset($address_info[$key]);
                $address_info[$vae['code']]=$vae['name'];
            }
            $saleOrderList['uuid']=$value['customer_id'];
            $saleOrderList['orderCode']=$value['order_id'];
            $saleOrderList['platFromName']="独立网店";
            $saleOrderList['shopName']='TPS商城';
            $saleOrderList['orderStatus']=$value['status'];
            $saleOrderList['type']=$value['order_type'];
            $saleOrderList['payCode']=$value2['txn_id'];
            $saleOrderList['payNo']='440316T016';
            $saleOrderList['payName']='中国工商银行股份有限公司前海分行';
            $saleOrderList['createDate']=$value2['created_at'];
            $saleOrderList['logisticsCompanyCode']="ZTO";
            $saleOrderList['logisticsCompanyName']='中通';
            $saleOrderList['actualPayment']=$value['order_amount'];
            $saleOrderList['orderTotalPrice']=$value2['goods_amount']+$value2['deliver_fee'];
            $saleOrderList['receiver']['uuid']=$value['customer_id'];
            $saleOrderList['receiver']['orderCode']=$value['order_id'];
            $saleOrderList['receiver']['name']=$userinfo['name'];
            $saleOrderList['receiver']['identityCard']=$userinfo['id_card_num'];
            $saleOrderList['receiver']['phone']=$value['phone'];
            $saleOrderList['receiver']['address']=$value['address'];
            $saleOrderList['receiver']['province']=$address_info[$songhuodizhi['addr_lv2']];
            if(in_array($songhuodizhi['addr_lv2'], [31,12,11,50])){
                $saleOrderList['receiver']['city']=$address_info[$songhuodizhi['addr_lv2']];
                $saleOrderList['receiver']['district']=$address_info[$songhuodizhi['addr_lv3']];
            }  else {
                $saleOrderList['receiver']['city']=$address_info[$songhuodizhi['addr_lv3']];
                $saleOrderList['receiver']['district']=$address_info[$songhuodizhi['addr_lv4']];
            }
            foreach ($value['goods_list'] as $key => $vae) {
                $detail['seqNo']=$key+1;
                $detail['uuid']=$value['customer_id'];
                $detail['orderCode']=$vae['order_id'];
                $detail['orderDetailCode']=$vae['order_id'];
                $detail['skuId']=$vae['goods_sn'];
                $detail['outerSkuId']=$vae['goods_sn'];
                $detail['num']=$vae['goods_number'];
                $detail['title']=$vae['goods_name'];
                $detail['price']=sprintf("%.2f",$vae['goods_price']*$value['currency_rate']);
                $saleOrderList['detail'][]=$detail;
            }
            $data['saleOrderList'][]=$saleOrderList;
        }
        /*****************************/
        $this->load->model("m_log");
        $this->m_log->dd_log($data,'发送',$value['order_id']);
        /***************************/
        $this->m_log->dd_log(wd_erp_api_query($data,'subAddSaleOrder'),'结果',$value['order_id']);//推送外代ERP接口
    }
    /**
     * 取消订单到外代erp
     * @author: xiarong
     * @date: 2017年6月20日
     */
    public function wd_erp_cancel_push($order_id) {
        $order_array['orderCode']=$order_id;
        /*****************************/
        $this->load->model("m_log");
        $this->m_log->dd_log($order_array,'发送',$order_id);
        /***************************/
        $this->m_log->dd_log(wd_erp_api_query($order_array,'cancelOrderService'),'结果',$order_id);//推送外代ERP接口
    }
}