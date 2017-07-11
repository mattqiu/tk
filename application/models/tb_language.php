<?php
/**
 * User: jason
 */
class tb_language extends CI_Model
{

    function __construct(){
        parent::__construct();
    }


    /**
     * @param $location_id 区域ID
     * @return int  返回language id
     */
    public function get_language_by_location($location_id){
        $language_id = 1;
        $res = $this->db->select('default_language')->where('country_id',$location_id)
            ->get('mall_goods_sale_country')->row_array();

        if(!empty($res)){
            $language_id_arr = $this->db->select('language_id')->where('code',$res['default_language'])->get('language')->row_array();
            $language_id = $language_id_arr['language_id'];
        }

        return $language_id;
    }

    /**
     * @param $language_id 区域ID
     * @return int  返回language id
     */
    public function get_location_id_by_language($language_id){

        $location_id = 840;

        $res = $this->db->select('code')->where('language_id',$language_id)
            ->get('language')->row_array();

        if(!empty($res)){
            $location_id_arr = $this->db->select('country_id')->where('default_language',$res['code'])->get('mall_goods_sale_country')->row_array();
            $location_id = $location_id_arr['country_id'];
        }

        return $location_id;
    }
}