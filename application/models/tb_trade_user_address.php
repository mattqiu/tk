<?php
/**
 * @author ckf
 * @modify tico.wong    2016.12.22
 * @modify tico.wong    2017.02.25
 */

class tb_trade_user_address extends MY_Model {

    protected $table_name = "trade_user_address";
    function __construct() {
        parent::__construct();
    }

    /**
     * 查询收货人的证件信息
     * @return array
     * @author ckf
     * @modify tico.wong 2016.12.22
     */
    public function getList($uid,$consignee) {
       return $this->get_one("*",["uid"=>$uid,"consignee"=>$consignee]);
    }

    /**
     * 检测是否已有默认收货地址
     * @author tico.wong
     * @param $data，待插入数据库的数据
     * @return int，如果有默认收货地址返回1，否则返回0
     */
    public function had_default_addr($data)
    {
        $where = ["uid"=>$data['uid'],"is_default"=>1,"country"=>$data["country"]];
        //来自m_trade的特殊处理
		if($data['country'] == 156 && $data['addr_lv2'] == 81){
            $where["addr_lv2"] = 81;
		}
        $default_row = $this->get_one("*",$where);
        if (!empty($default_row))
        {
            return 1;
        }
        return 0;
    }

    /**
     * 根据 uid 获取地址列表（不包括运费、商品金额）
     */
    public function get_deliver_address_list_by_uid($uid,$county_id)
    {
        $addr_list = array();

        $where = [];
        if($county_id == 344){
            $where["country"] = 156;
            $where["addr_lv2"] = 81;
        }else if($county_id == 156){
            $where["country"] = 156;
            $where["addr_lv2 <>"] = 81;
        }
        else if($county_id === "000")
        {
            //日本：392
            //加拿大：124
            //台湾：1560
            //其他地区
            $where["country"] = ['392','124','158','000'];
        }
        else{
            $where["country"] = $county_id;
        }

        $where["uid"] = $uid;
        $list = $this->get_list("*",$where);
        if (empty($list))
        {
            return $addr_list;
        }
        $is_defalut = FALSE;
        //批量获取拼接地址
        $this->load->model("tb_trade_addr_linkage");

        $addr_arr_list = $this->tb_trade_addr_linkage->implode_detail_address_by_attr_list($list);
        foreach ($list as $k => $v)
        {
            if($k == 0)
            {
                $first = $v['id'];
            }
            // 详细地址
            $addr_str = $addr_arr_list[$v['id']];
            if($v['is_default'] == 1 && $is_defalut ===FALSE){
                $is_defalut = TRUE;
            }
            $addr_list[$v['id']] = array(
                'id' => $v['id'],
                'is_default' => $v['is_default'],
                'consignee' => $v['consignee'],
                'phone' => $v['phone'],
                'reserve_num' => $v['reserve_num'],
                'country' => $v['country'],
                'addr_lv2' => $v['addr_lv2'],
                'addr_lv3' => $v['addr_lv3'],
                'addr_lv4' => $v['addr_lv4'],
                'address_detail' => preg_replace("/\n/i"," ",$v['address_detail']),
                'zip_code' => $v['zip_code'],
                'customs_clearance' => $v['customs_clearance'],
                'first_name'=>$v['first_name'],
                'last_name'=>$v['last_name'],
                'city'=>$v['city'],
                'address'=>$addr_str['address'],
                'country_address'=>$addr_str['country'],
                'ID_no'=>$v['ID_no'],
                'ID_front'=>$v['ID_front'],
                'ID_reverse'=>$v['ID_reverse'],
            );
            if($v['country'] == "840" or $v['country'] == "410" )
            {
                //美国跟韩国的地址，不展示国家名
                $addr_list[$v['id']]['addr_str'] = $addr_str['address'];
            }else{
                $addr_list[$v['id']]['addr_str'] = $addr_str['country'].' '.$addr_str['address'];
            }
        }

        if($is_defalut === FALSE){
            $addr_list[$first]['is_default'] = 1;
        }
        return $addr_list;
    }

    /**
     * @description  get user's address list  group by country
     * @param $user_id ,user's ID
     * @param $country_id,user's country
     * @param $sales_country,sales area
     * @return array
     */
    public function get_all_address_by_type($user_id, $country_id, $sales_country)
    {
//        $data = $this->db->from($this->table_name)
//            ->select("id, is_default, consignee, phone, reserve_num, country, addr_lv2, addr_lv3, addr_lv4, address_detail, zip_code, customs_clearance,first_name,last_name,city,ID_no,ID_front,ID_reverse")
//            ->where(['uid' => $user_id])
//            ->order_by('id desc')
//            ->get()
//            ->result_array();
        $where["uid"] = $user_id;
        $order_by["id"] = "desc";
        $data = $this->get_list("*",$where,[],100,0,$order_by);

        $output = [];
        foreach($sales_country as $v) {
            $output[$v['country_id']] = [];
        }
        // deal with the data
        if(!empty($data)) {
            $this->load->model("tb_trade_addr_linkage");
            $addr_arr_list = $this->tb_trade_addr_linkage->implode_detail_address_by_attr_list($data);
            foreach ($data as $k => $v)
            {
                // 详细地址
                $addr_str = $addr_arr_list[$v['id']];
                if ($v['country'] == '156' && $v['addr_lv2'] == "81") {
                    $output[344][] = array(
                        'id' => $v['id'],
                        'is_default' => $v['is_default'],
                        'consignee' => $v['consignee'],
                        'phone' => $v['phone'],
                        'reserve_num' => $v['reserve_num'],
                        'country' => $v['country'],
                        'addr_lv2' => $v['addr_lv2'],
                        'addr_lv3' => $v['addr_lv3'],
                        'addr_lv4' => $v['addr_lv4'],
                        'address_detail' => $v['address_detail'],
                        'addr_str' => $addr_str['country'].' '.$addr_str['address'],
                        'region'=>$addr_str['region'],
                        'zip_code' => $v['zip_code'],
                        'customs_clearance' => $v['customs_clearance'],
                        'first_name'=>$v['first_name'],
                        'last_name'=>$v['last_name'],
                        'city'=>$v['city'],
                        'address'=>$addr_str['address'],
                        'country_address'=>$addr_str['country'],
                        'ID_no'=>$v['ID_no'],
                        'ID_front'=>$v['ID_front'],
                        'ID_reverse'=>$v['ID_reverse'],
                    );
                } else {
                    $output[$v['country']][] = array(
                        'id' => $v['id'],
                        'is_default' => $v['is_default'],
                        'consignee' => $v['consignee'],
                        'phone' => $v['phone'],
                        'reserve_num' => $v['reserve_num'],
                        'country' => $v['country'],
                        'addr_lv2' => $v['addr_lv2'],
                        'addr_lv3' => $v['addr_lv3'],
                        'addr_lv4' => $v['addr_lv4'],
                        'address_detail' => $v['address_detail'],
                        'addr_str' => $addr_str['country'].' '.$addr_str['address'],
                        'region'=>$addr_str['region'],
                        'zip_code' => $v['zip_code'],
                        'customs_clearance' => $v['customs_clearance'],
                        'first_name'=>$v['first_name'],
                        'last_name'=>$v['last_name'],
                        'city'=>$v['city'],
                        'address'=>$addr_str['address'],
                        'country_address'=>$addr_str['country'],
                        'ID_no'=>$v['ID_no'],
                        'ID_front'=>$v['ID_front'],
                        'ID_reverse'=>$v['ID_reverse'],
                    );
                }
            }

            //如果是默认地址 放到第一
            foreach($output as $k=>&$v) {
                if (!empty($v)) {
                    $is_default = false;

                    foreach ($v as $key=>$value) {
                        if($value['is_default'] == 1 && $is_default === false){
                            $is_defalut = TRUE;
                        }
                        if ($value['is_default'] == 1 && $key !== 0) {
                            //交换该位置和o的位置
                            $temp = $v['0'];
                            $v["0"] = $v[$key];
                            $v[$key] = $temp;
                        }
                    }
                    if ($is_default === false) {
                        $v['0']['is_default'] = 1;
                    }
                }
            }
        }
        return $output;

    }

    /**
     * @author brady
     * @description del user address
     * @param array $id
     * @return affected_rows
     */
    public function del_address_by_id($id)
    {
        return $this->delete_one(["id"=>$id]);
//        $this->db->delete($this->table_name, ['id'=>$id]);
//        return $this->db->affected_rows();
    }

    /**
     *
     * 查询
     * @author brady.wang
     * @param array $params
     * @param bool  $get_rows 是否返回行数
     * @param bool  $get_one 是否返回一行
     * @return array
     */
    public function get_by_where(array $params, $get_rows = false, $get_one = false)
    {
        $this->db->from($this->table_name);
        if (isset($params['select'])) {
            if (isset($params['select_escape'])) {
                $this->db->select($params['select'], false);
            } else {
                $this->db->select($params['select']);
            }
        }
        if (isset($params['where']) && is_array($params['where'])) {
            $this->db->where($params['where']);
        }

        if (isset($params['where_in']) && is_array($params['where_in'])) {
            $this->db->where_in($params['where_in']['key'], $params['where_in']['value']);
        }

        if (isset($params['join'])) {
            foreach ($params['join'] as $item) {
                $this->db->join($item['table'], $item['where'], $item['type']);
            }
        }
        if (isset($params['limit'])) {
            if (is_array($params['limit']) && isset($params['limit']['page']) && isset($params['limit']['page_size'])) {
                $this->db->limit($params['limit']['page_size'],($params['limit']['page']-1)*$params['limit']['page_size']);
            } else {
                $this->db->limit($params['limit']);
            }
        }


        if (isset($params['group'])) {
            $this->db->group_by($params['group']);
        }
        if (isset($params['order'])) {
            if (is_array($params['order'])) {
                foreach ($params['order'] as $v) {
                    $this->db->order_by($v['key'], $v['value']);
                }
            } else {
                $this->db->order_by($params['order']);
            }

        }

        $result = $this->db->get();
        if (!$get_one) {
            return $get_rows ? $result->num_rows() : ($result->num_rows() > 0 ? $result->result_array() : array());
        } else {
            return $get_rows ? $result->num_rows() : ($result->num_rows() > 0 ? $result->row_array() : array());
        }
    }

    /**
     * 根据地址id获取用户当前选择的地址详情
     */
    public function get_deliver_address_by_id($addr_id)
    {
        $list = $this->get_one("*",["id"=>$addr_id]);
        return $list;
    }

    /**
     * 根据用户地址获取所在区域
     */
    public function get_area_by_addr($addr)
    {
        if (!isset($addr['country']))
        {
            return "000";
        }

        switch ($addr['country'])
        {
            // 中国
            case "156":
                if (isset($addr['addr_lv2']))
                {
                    // 香港
                    if ($addr['addr_lv2'] == "81")
                    {
                        $area = "344";
                    }
                    // 澳门
                    else if ($addr['addr_lv2'] == "82")
                    {
                        $area = "446";
                    }
                    // 台湾
                    else if ($addr['addr_lv2'] == "71")
                    {
                        $area = "158";
                    }
                    else
                    {
                        $area = "156";
                    }
                }
                else
                {
                    $area = "156";
                }
                break;

            // 美国
            case "840":
                $area = "840";
                break;

            // 韩国
            case "410":
                $area = "410";
                break;

            default:
                $area = "000";
                break;
        }
        return $area;
    }


}
