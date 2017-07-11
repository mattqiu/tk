<?php
/**
 * mall_goods 功能类
 * @author ticowong
 */
class o_mall_goods extends MY_Model {

    private $doba_url='https://www.doba.com/api/20110301/xml_retailer_api.php';
    private $doba_account='richard@goventura.com';
    private $doba_password='2587Yef831';
    private $doba_id='5076889';

    function __construct() {
        parent::__construct();
    }

    /**
     * 获取浏览历史
     * @return array
     */
    public function get_history_list() {
        $goods_his=get_cookie('goods_history');

        $history_goods=array();
        if(!empty($goods_his)) {
            $history_goods=$this->get_history_goods(trim($goods_his,','));
        }

        return $history_goods;
    }

    /**
     * 获取浏览历史
     * @param $goods_ids
     * @return mixed
     */
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
                    $this->load->model("tb_mall_goods_category");
                    $cate_id=$this->tb_mall_goods_category->check_doba_category($cate);
                    if(!$cate_id) {
                        $arr_cate['cate_sn']=random_string('alnum', 10);
                        $arr_cate['cate_name']=$cate;
                        $arr_cate['parent_id']= $parent_id;
                        $arr_cate['meta_title']=$cate;
                        $arr_cate['language_id']=1;

                        $this->tb_mall_goods_category->inser_db_cate($arr_cate);

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
                        $this->load->model("tb_mall_goods_gallery");
                        $img_id=$this->tb_mall_goods_gallery->save_gall_img($goods_sn_main.'-1',$img_path,$img_path_big);
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
                        $this->load->model("tb_mall_goods_detail_img");
                        $img_id=$this->tb_mall_goods_detail_img->save_detail_img($goods_sn_main,$dir_path.$img_name,1);
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

                $this->load->model("tb_mall_goods_main");
                if($main_goods_id=$this->tb_mall_goods_main->add_db_goods($arr_main)) {
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

//                    $this->db->insert('mall_goods',$arr_goods);
                    $this->load->model("tb_mall_goods");
                    $this->tb_mall_goods->insert_one($arr_goods);
                    echo "-OK,goods info created...\n";
                    echo 'success:',++$total_upload_num,"\n-----------------------------------------\n" ;
                }

            }

        }
        //fclose($handle);

        echo 'Upload new products number is ',$total_upload_num;
        exit;
    }

    /* doba订单状态监测任务 每10分钟执行一次 */
    function cron_doba_order_status_check() {
        set_time_limit(0);
        $URL = $this->doba_url;

        $this->load->model('m_erp');
        $this->load->model("tb_trade_orders");

//        $order_doba_arr=$this->db->select('order_id')->where('is_doba_order',1)->where('status',3)->get('trade_orders')->result_array();
        $order_doba_arr = $this->tb_trade_orders->get_list("order_id",[
            "is_doba_order"=>1,
            "status"=>3,
        ]);

        if(empty($order_doba_arr)) {
            echo 'No order yet.';
            exit;
        }

        $connection = curl_init();

        $i=0;
        foreach($order_doba_arr as $order) {
            echo ++$i.'-'.$order['order_id'].":\n";

            $daba=$this->db->select('order_id_doba,state')->where('order_id',$order['order_id'])->get('trade_orders_doba_order_info')->row_array();

            if(!in_array($daba['state'], array('Cancelled','Completed','Returned'))) {  //未完成状态的订单，获取最新状态信息
                echo 'Doba Oreder:'.$daba['order_id_doba']."\n";

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

//                                $this->db->where('order_id',$order['order_id'])->update('trade_orders',array('status'=>4,'deliver_time'=>$deliver_time));
                                $this->tb_trade_orders->update_one(["order_id"=>$order['order_id']],
                                    array('status'=>4,'deliver_time'=>$deliver_time));
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

                            }elseif($data['order_status'] == 'Cancelled') { //订单被供应商取消

                                /*事务开始*/
                                $this->db->trans_start();

//                                $this->db->where('order_id',$order['order_id'])->update('trade_orders',array('status'=>'111','updated_at'=>date('Y-m-d H:i:s')));
                                $this->tb_trade_orders->update_one(["order_id"=>$order['order_id']],
                                    array('status'=>'111','updated_at'=>date('Y-m-d H:i:s')));

                                $this->db->insert('trade_order_remark_record',array('order_id'=>$order['order_id'],'remark'=>'System: The supplier has cancelled the order.','admin_id'=>0,'created_at'=>date('Y-m-d H:i:s')));

                                //同步到erp(doba订单-订单异常)
                                $insert_data = array();
                                $insert_data['oper_type'] = 'modify';
                                $insert_data['data']['order_id'] = $order['order_id'];
                                $insert_data['data']['status'] = 111;

                                $this->m_erp->trade_order_to_erp_oper_queue($insert_data);//添加到订单推送表，修改订单

                                $this->db->trans_complete();//事务结束

                                echo $order['order_id']." has Canceled.\n";
                            }

                            echo "Modify success.\n---------------------------\n";
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

            $this->load->model("tb_mall_goods_main");
            //检查该商品是否已经存在
            if($this->tb_mall_goods_main->check_doba_item($data[15])) {
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
                    $this->load->model("tb_mall_goods_category");
                    $cate_id=$this->tb_mall_goods_category->check_doba_category($cate);
                    if(!$cate_id) {
                        $arr_cate['cate_sn']=random_string('alnum', 10);
                        $arr_cate['is_doba_cate']=1;
                        $arr_cate['cate_name']=$cate;
                        $arr_cate['parent_id']= $parent_id;
                        $arr_cate['meta_title']=$cate;
                        $arr_cate['language_id']=1;

                        $this->tb_mall_goods_category->inser_db_cate($arr_cate);

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
                        $this->load->model("tb_mall_goods_gallery");
                        $img_id=$this->tb_mall_goods_gallery->save_gall_img($goods_sn_main.'-1',$img_path,$img_path_big);
                        echo "-OK,gall image info created...\n";
                        //保存图片路径到数据库
                        $this->load->model("tb_mall_goods_detail_img");
                        $img_id=$this->tb_mall_goods_detail_img->save_detail_img($goods_sn_main,$img_path_big,1);
                        echo "-OK,detail image info created...\n";
                    }

                    $img='';
                    sleep(5); //防止速度太快，重名被覆盖
                }

                //处理品牌
                $brand_id=0;
                if(!empty($data[11])) {
                    $this->load->model("tb_mall_goods_brand");
                    $brand_id=$this->tb_mall_goods_brand->check_doba_brand($data[11]);
                    if(!$brand_id) {
                        $arr_brand['brand_name']=$data[11];
                        $arr_brand['cate_id']=$parent_id;
                        $arr_brand['language_id']=1;

                        $this->tb_mall_goods_brand->add_brand($arr_brand);

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

                $this->load->model("tb_mall_goods_main");
                if($main_goods_id=$this->tb_mall_goods_main->add_db_goods($arr_main)) {
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

//                    $this->db->insert('mall_goods',$arr_goods);
                    $this->load->model("tb_mall_goods");
                    $this->tb_mall_goods->insert_one($arr_goods);
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
                        // 原始库存
//                        $originalNum = $this->db->select('goods_number,goods_sn')->get_where('mall_goods',array('goods_sn_main'=>$goods_sn_main))->row_array();
                        $this->load->model("tb_mall_goods");
                        $originalNum = $this->tb_mall_goods->get_one('goods_number,goods_sn',['goods_sn_main'=>$goods_sn_main]);
                        $mainRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods_main',$data);
//                        $goodsRet = $this->db->where('goods_sn_main',$goods_sn_main)->update('mall_goods',$data_goods);
                        $goodsRet = $this->tb_mall_goods->update_batch(['goods_sn_main'=>$goods_sn_main],$data_goods);
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

                            //同步库存,取消同步库存到ERP的队列
//                            $chang_num = intval($originalNum['goods_number']) - intval($data_goods['goods_number']);
//                            //获取当前库存
////                            $goods_info = $this->db->from('mall_goods')->select('goods_number')->where('goods_sn_main',$goods_sn_main)->get()->row_array();
//                            $this->load->model("tb_mall_goods");
//                            $goods_info = $this->tb_mall_goods->get_one('goods_number',['goods_sn_main'=>$goods_sn_main]);
//
//                            $goods_number = isset($goods_info['goods_number'])?$goods_info['goods_number']:0;
//                            if($chang_num != 0){
//                                $goods_num = array();
//                                if($chang_num > 0){
//                                    $goods_num['oper_type'] = 'dec'; //jian库存
//                                }else{
//                                    $goods_num['oper_type'] = 'inc'; //jia库存
//                                }
//                                //插入到库存推送队列表
//                                $goods_num['goods_sn'] = $originalNum['goods_sn'];
//                                $goods_num['quantity'] = abs($chang_num);
//                                $goods_num['inventory'] = $goods_number;
//                                $goods_num['order_id'] = 'doba';
//
//                                //插入到库存队列表
//                                $this->db->insert('trade_order_to_erp_inventory_queue',$goods_num);
//                            }
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
//                    $this->db->where('order_id',$order_id)->set('order_profit_usd',$order_profit_usd)->update('trade_orders');
                    $this->tb_trade_orders->update_one(["order_id"=>$order_id],['order_profit_usd'=>$order_profit_usd]);

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
        $this->load->model('tb_trade_orders');
        $error_msg = $this->m_validate->getTagContent('<message>','</message>',$strResponse);
        //$remark = lang('doba_order_exception').'---'.$error_msg;

        //推送失败时将订单状态改成111(异常订单)
//        $this->db->where('order_id',$order_id)->set('status',Order_enum::STATUS_DOBA_EXCEPTION)->update('trade_orders');
        $this->tb_trade_orders->update_one(["order_id"=>$order_id],['status'=>Order_enum::STATUS_DOBA_EXCEPTION]);
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
                    $this->load->model("tb_trade_orders");
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
//                        $order_amount_usd = $this->db->query("select order_amount_usd from trade_orders WHERE order_id = '$order_id'")->row()->order_amount_usd;
                        $order_amount_usd = $this->tb_trade_orders->get_one("order_amount_usd",["order_id"=>$order_id]);
                        $order_amount_usd = isset($order_amount_usd['order_amount_usd'])?$order_amount_usd['order_amount_usd']:0;

                        $order_total_doba = $this->db->query("select order_total_doba from trade_orders_doba_order_info order_id WHERE order_id= '$order_id'")->row()->order_total_doba;
                        $order_profit_usd = ($order_amount_usd/100 - $order_total_doba) * 100;
//                        $this->db->where('order_id',$order_id)->set('order_profit_usd',$order_profit_usd)->update('trade_orders');
                        $this->tb_trade_orders->update_one(["order_id"=>$order_id],['order_profit_usd'=>$order_profit_usd]);

                        //发放订单奖金
                        $this->load->model('m_order');
//                        $order = $this->db->query("select * from trade_orders WHERE order_id = '$order_id'")->row_array();
                        $order = $this->tb_trade_orders->get_one("shopkeeper_id,goods_amount_usd,order_profit_usd",["order_id"=>$order_id]);
                        if(!empty($order))
                        {
                            /*记录到新订单处理队列*/
                            $this->load->model('tb_new_order_trigger_queue');
                            $this->tb_new_order_trigger_queue->addNewOrderToQueue($order_id,$order['shopkeeper_id'],$order['goods_amount_usd'],$order['order_profit_usd']);
                        }

                        /*事务开始*/
                        $this->db->trans_start();

                        //更新订单状态
//                        $this->db->where('order_id',$order_id)->update('trade_orders',array('status'=>$order_status,'updated_at'=>date('Y-m-d H:i:s')));
                        $this->tb_trade_orders->update_one(["order_id"=>$order_id],
                            array('status'=>$order_status,'updated_at'=>date('Y-m-d H:i:s')));
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

}