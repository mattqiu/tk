<?php

/**
 * @author Tico.wong
 */
class tb_trade_addr_linkage extends MY_Model {
    protected $table_name = "trade_addr_linkage";
    function __construct() {
        parent::__construct();
    }

    /**
     * 批量根据参数拼写出详细地址,新方法
     * @param $list
     * @return array list
     */
    public function implode_detail_address_by_attr_list($list)
    {
        $stime = microtime(true);
        $address_list = [];
        //取所有条件
        $where_arr = [];
        foreach($list as $k=>$v)
        {
            $where_tmp = $this->get_where_by_attr($v);
            foreach($where_tmp as $kt=>$vt)
            {
                if(!in_array($vt,$where_arr))
                {
                    $where_arr[] = $vt;
                }
            }
        }
        //一次性取所有addr_info_list
        $str_select = "name,country_code,code,level";
        $str_where = implode(" ",$where_arr);
        $sql = "select $str_select from ".$this->table_name. " where 1=2 ".$str_where;
        $addr_info_list = $this->db_slave->query($sql)->result_array();
        //组装address_list

        foreach($list as $k=>$v)
        {
            $address_list[$v['id']] = $this->get_addr_arr_by_list($addr_info_list,$v);
        }
//        $etime = microtime(true);
//        $execute_time = $etime-$stime;
//        $this->m_debug->log("implode_detail_address_by_attr_list->sql:".$sql);
//        $this->m_debug->log("implode_detail_address_by_attr_list->time:".$execute_time);
        return $address_list;
    }

    /**
     * 根据参数取where字符串，不含where关键字
     * @param $attr
     * @return array
     */
    public function get_where_by_attr($attr)
    {
        $str_where[] = "or (country_code = {$attr['country']} and code = '0')";
        for($i=2;$i<=5;$i++)
        {
            if (!empty($attr['addr_lv'.$i])) {
                $str_where[] = " or (country_code = '{$attr['country']}' and code = '{$attr['addr_lv'.$i]}' and level = $i)";
            }
        }
        return $str_where;
    }

    /**
     * @param $addr_info_list
     * @param $attr
     * @return array addr_arr
     */
    public function get_addr_arr_by_list($addr_info_list,$attr)
    {
        $addr_arr['id'] = $attr['id'];
        $addr_str_arr = [];
        $country_code = "000";
        //取国家
        foreach($addr_info_list as $k=>$v)
        {
            if($v['country_code'] == $attr['country'] and $v['code'] == "0")
            {
                //取得国家code
                $country_code = $v['country_code'];
                $addr_arr['country'] = $v['name'];
                $addr_arr['country_code'] = $country_code;
                break;
            }else{
                // 如果为空，国家 code 为 000
                $addr_arr['country'] = "";
            }
        }

        //取2至5级地址名
        for($i=2;$i<=5;$i++){
            if($i == 3 && $attr['country'] == 840 && $attr['city'])
            {
                // $addr_str .= " ".$v['name'];
                $addr_str_arr[] = $attr['city'];
                continue;
            }
            if (!empty($attr['addr_lv'.$i])) {
                foreach($addr_info_list as $k=>$v) {
                    // M BY brady 修复不同国家如果下级id相同时候，会数据错乱
                    if($v['code'] == $attr['addr_lv'.$i] && $v['level'] == $i && $v['country_code'] == $attr['country'])
                    {
                        //过滤other段的地址
                        if(strtolower($v['name']) != "other"){
                            $addr_str_arr[] = $v['name'];
                        }
                        break;
                    }
                }
            }
        }
        $addr_arr['region'] = implode(" ",$addr_str_arr);
        $addr_str_arr[] = $attr['address_detail'];
        if($country_code == 840)
        {
            //如果是美国的地址，反转顺序
            $addr_arr['address'] = implode(" ",array_reverse($addr_str_arr));
        }else{
            $addr_arr['address'] = implode(" ",$addr_str_arr);
        }

        return $addr_arr;
    }


    /**
     * 根据参数拼写出详细地址,新方法
     */
    public function implode_detail_address_by_attr($attr)
    {
        $str_select = "name,country_code,code,level";
        $str_where = implode(" ",$this->get_where_by_attr($attr));

        $sql = "select $str_select from ".$this->table_name. " where 1=2 ".$str_where;
        $addr_info_list = $this->db_slave->query($sql)->result_array();

        $addr_arr = $this->get_addr_arr_by_list($addr_info_list,$attr);

        return $addr_arr;
    }

    /**
     * 根据参数拼写出详细地址,旧方法，不使用
     */
    public function implode_detail_address_by_attr_old($attr)
    {
        $addr_str="";
        // 拼详细地址
        $country_info = $this->db
            ->select('name')
            ->where('country_code', $attr['country'])
            ->where('code', "0")
            ->get('trade_addr_linkage')
            ->row_array();
        if (empty($country_info))
        {
            // 如果为空，国家 code 为 000
            $addr_arr['country'] = "";
        }
        else
        {
            $addr_arr['country'] = $country_info['name'];
        }

        if (!empty($attr['addr_lv2'])) {
            $addr_info = $this->db
                ->select('name')
                ->where('country_code', $attr['country'])
                ->where('code', $attr['addr_lv2'])
                ->where('level', "2")
                ->get('trade_addr_linkage')
                ->row_array();
            if (!empty($addr_info))
            {
                $addr_str .= " {$addr_info['name']}";
            }
        }
        if (!empty($attr['addr_lv3'])) {
            $addr_info = $this->db
                ->select('name')
                ->where('country_code', $attr['country'])
                ->where('code', $attr['addr_lv3'])
                ->where('level', "3")
                ->get('trade_addr_linkage')
                ->row_array();
            if (!empty($addr_info))
            {
                $addr_str .= " {$addr_info['name']}";
            }
        }
        if (!empty($attr['addr_lv4'])) {
            $addr_info = $this->db
                ->select('name')
                ->where('country_code', $attr['country'])
                ->where('code', $attr['addr_lv4'])
                ->where('level', "4")
                ->get('trade_addr_linkage')
                ->row_array();
            if (!empty($addr_info))
            {
                $addr_str .= " {$addr_info['name']}";
            }
        }
        if (!empty($attr['addr_lv5'])) {
            $addr_info = $this->db
                ->select('name')
                ->where('country_code', $attr['country'])
                ->where('code', $attr['addr_lv5'])
                ->where('level', "5")
                ->get('trade_addr_linkage')
                ->row_array();
            if (empty($addr_info))
            {
                $addr_str .= " {$addr_info['name']}";
            }
        }
        $addr_str .= " {$attr['address_detail']}";
        $addr_str = trim($addr_str);
        $addr_arr['address'] = $addr_str;

        return $addr_arr;
    }

    /**
     * @author brady.wang 根据区域id,组装订单详细地址
     * @param $attr
     * @return mixed
     */
    public function implode_address_info_by_attr($attr)
    {
        $res = '';
        for ($i=1;$i<=5;$i++) {
            if ($i == 1) {
//                $country_arr = $this->db->from($this->table_name)
//                                    ->select('id,name')
//                                    ->where(array('country_code'=>$attr['country'],'code'=>'0','level'=>'1'))
//                                    ->get()
//                                ->row_array();
                    $country_arr = $this->get_one('id,name',
                        array('country_code'=>$attr['country'],'code'=>'0','level'=>'1'));
                if (!empty($country_arr)) {
                    $res = $country_arr['name'];
                }
            }
            if (!empty($attr['addr_lv'.$i])) {
                if ($i == 2) {
                    $parent_code = 0;
                } else {
                    $parent_code = $attr['addr_lv'.($i-1)];
                }
//                $res_arr = $this->db->from($this->table_name)
//                    ->select('id,name')
//                    ->where(array('country_code'=>$attr['country'],'code'=>$attr['addr_lv'.$i],'parent_code'=>$parent_code, 'level'=>$i))
//                    ->get()
//                    ->row_array();
                $res_arr = $this->get_one('id,name',
                    array('country_code'=>$attr['country'],'code'=>$attr['addr_lv'.$i],'parent_code'=>$parent_code, 'level'=>$i));
                if (!empty($res_arr)) {
                    //过滤other的信息
                    if(strtolower($res_arr['name']) != "other")
                    {
                        $res .= ' '.$res_arr['name'];
                    }
                }
            }
        }
        if (empty($res)) {
            return false;
        } else {
            return $res.' '.$attr['address_detail'].' ';
        }
    }
}
