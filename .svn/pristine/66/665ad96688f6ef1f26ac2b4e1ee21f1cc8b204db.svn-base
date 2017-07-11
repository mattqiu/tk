<?php
/**
 * 韩国激励计划类
 * @author terry
 */
class o_korea_inspiration extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*根据国家id判断会员是否符合韩国4月份激励计划*/
    public function inspirationIn201604Condition($userContryId){


    	$return = false;
    	if( $userContryId==3 && time()<strtotime('2016-04-04') ){
    		$return = true;
    	}

    	return $return;
    }

}
