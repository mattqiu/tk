<?php
/**
 * @author Terry
 */
class tb_profit_sharing_point_proportion extends CI_Model {


    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取用户的“佣金自动转分红点比例”
     * @param $uid
     * @return 比例
     * @author Terry
     */
    public function getUserCommToSharingpointProportion($uid){
        $res = $this->db->select('proportion')->from('profit_sharing_point_proportion')->where('uid',$uid)->where('proportion_type',1)->get();
        if ($res) {
        	$res = $res->row_array();
	        return $res?$res['proportion']:0;
        } else {
        	return 0;
        }
    }

    public function saveUserProportion($uid,$proportion_type,$proportion){

        if(!$uid || !$proportion_type || $proportion==''){

            return 101;
        }

        if (!is_numeric($proportion) || $proportion > 100 || $proportion < 0 ||  get_decimal_places($proportion)>2) {
            
            return 1046;
        }

        $this->db->replace('profit_sharing_point_proportion',array(
            'uid'=>$uid,
            'proportion_type'=>$proportion_type,
            'proportion'=>$proportion,
        ));

        return 0;
    }
    
}
