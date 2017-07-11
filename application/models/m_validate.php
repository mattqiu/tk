<?php
/**
 * 数据验证类
 * User: jason
 * Date: 2015/12/1
 * Time: 17:46
 */
class m_validate extends CI_Model{

    function __construct() {
        parent::__construct();
    }

    /**
     * 验证收货地址有效性
     * 传入地址数据$address或传入地址数据的ID$address_id
     */
    public function validate_deliver_address($address,$address_id=0)
    {
        if(empty($address) and empty($address_id))
        {
            echo json_encode(array('error'=>true ,'code'=>'2049394','msg'=>lang('address_detail_empty'),'message'=>lang('address_detail_empty')));exit;
        }
        if($address_id)
        {
            $this->load->model("tb_trade_user_address");
            $address = $this->tb_trade_user_address->get_one("*",['id'=>$address_id]);
        }
        //如果是美国地址,新的验证规则
        if($address['country'] == 840)
        {
            $this->verify_addr_for_us($address);
            //拼接姓名
            $address['consignee'] = $address['first_name'].' '.$address['last_name'];
        }
        //如果是韩国地址,新的验证规则
        else if($address['country'] == 410)
        {
            $this->verify_addr_for_kr($address);
        }
        //如果是中国地址,新的验证规则
        else if($address['country'] == 156)
        {
            if($address['addr_lv2'] == 81)
            {
                //如果是中国香港,新的验证规则
                $this->verify_addr_for_hk($address);
            }else{
                //否则使用中国地址验证
                $this->verify_addr_for_china($address);
            }
        }
        //如果是其他地区,新的验证规则
        else
        {
            $this->verify_addr_for_other($address);
        }
        return $address;
    }

    /***获取标签内的内容***/
    public function getTagContent($leftTag,$rightTag,$content){
        preg_match("#$leftTag(.*)$rightTag#",$content,$match_content);
        if(count($match_content) == 2){
            return $match_content[1];
        }
        return "";
    }

    //验证美国下单地址
    public function validate_addr_for_us($attr){
        //验证非空
        if(trim($attr['city']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('city_empty'),'message'=>lang('city_empty')));exit;
        }
        if(trim($attr['address_detail']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('address_detail_empty'),'message'=>lang('address_detail_empty')));exit;
        }
        if(trim($attr['first_name']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('first_name_empty'),'message'=>lang('first_name_empty')));exit;
        }
        if(trim($attr['last_name']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('last_name_empty'),'message'=>lang('last_name_empty')));exit;
        }
        if(trim($attr['phone']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('phone_empty'),'message'=>lang('phone_empty')));exit;
        }
//		if(trim($attr['zip_code']) == ''){
//			echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('zip_code_empty'),'message'=>lang('zip_code_empty')));exit;
//		}

        //邮编必须为5位数字
//        $match =  is_numeric(trim($attr['zip_code']));
//        if($match == FALSE || strlen(trim($attr['zip_code']))>5){
//            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('zip_code_format'),'message'=>lang('zip_code_format')));exit;
//        }
        //m by brady 产品新要求 邮编不能超过二十个字符即可
        if(strlen(trim($attr['zip_code'])) >= 20) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('address_zip_code_check_1'),'message'=>lang('zip_code_format')));exit;
        }
        //验证长度
        if(strlen($attr['city'])>25) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('city_length'),'message'=>lang('city_length')));exit;
        }
        if(strlen($attr['address_detail'])>25) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('address_detail_length_25'),'message'=>lang('address_detail_length_25')));exit;
        }
        if(strlen($attr['first_name'])>25) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('first_name_length'),'message'=>lang('first_name_length')));exit;
        }
        if(strlen($attr['last_name'])>25) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('last_name_length'),'message'=>lang('last_name_length')));exit;
        }
        if(strlen($attr['phone'])>10) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('phone_length'),'message'=>lang('phone_length')));exit;
        }
        if(strlen($attr['zip_code'])>10) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('zip_code_length'),'message'=>lang('zip_code_length')));exit;
        }
    }

    //验证韩国下单地址
    public function validate_addr_for_korea($attr){
        if(trim($attr['zip_code']) == ''){
            echo json_encode(array('code'=>'204','msg'=>lang('zip_code_empty')));exit;
        }
        if(strlen($attr['zip_code'])>16) {
            echo json_encode(array('code'=>'204','msg'=>lang('zip_code_length')));exit;
        }
    }

    /* 验证美国下单地址（手机端） */
    public function validate_addr_for_us_mobile(){

    }


    /* 验证韩国下单地址（手机端） */
    public function validate_addr_for_kr_mobile(){

    }

    /**
     * @param $data
     * author brady
     * @description 公共验证，不能为空或过长
     */
    private function verify_addr_for_common_1($data)
    {
        //详细地址不能为空
        if(empty($data['address_detail']) || mb_strlen(trim($data['address_detail'])) == 0) {
            echo json_encode(array('error'=>true ,'code'=>'10501013','msg'=>lang('checkout_validator_address_detail'),'message'=>lang('checkout_validator_address_detail')));exit;
        }

        if (mb_strlen($data['address_detail']) > 255) {
            echo json_encode(array('error'=>true ,'code'=>'10501023','msg'=>lang('address_detail_length_25'),'message'=>lang('address_detail_length_25')));exit;
        }

        //收货人不能为空
        if(empty($data['consignee']) || strlen(trim($data['consignee'])) == 0) {
            echo json_encode(array('error'=>true ,'code'=>'10501014','msg'=>lang('checkout_validator_consignee'),'message'=>lang('checkout_validator_consignee')));exit;
        }

        //手机号不能为空
        if(empty($data['phone']) || strlen($data['phone']) == 0) {
            echo json_encode(array('error'=>true ,'code'=>'10501015','msg'=>lang('phone_not_null'),'message'=>lang('phone_not_null')));exit;
        }

    }

    /**
     * @author brady.wang
     * @description 1、中国区：手机号码不超过11位，固定电话不超过20位，邮编不超过16位。提示语："请输入正确的..."
     */
    public function verify_addr_for_china($data)
    {
        $this->verify_addr_for_common_1($data);
        //手机号码格式验证
        if(!preg_match('/^1[34578]\d{9}$/',$data['phone']))
        {
            echo json_encode(array('error'=>true ,'code'=>'10501016','msg'=>lang('check_addr_rule_phone'),'message'=>lang('check_addr_rule_phone')));
            exit;
        }
        //主要针对现有的地址，省份、城市必选
        if(empty($data['addr_lv2']))
        {
            echo json_encode(array('error'=>true ,'code'=>'10501017','msg'=>lang('checkout_validator_addr_lv2'),'message'=>lang('checkout_validator_addr_lv2')));
            exit;
        }
        if(empty($data['addr_lv3']))
        {
            echo json_encode(array('error'=>true ,'code'=>'10501018','msg'=>lang('checkout_validator_addr_lv3'),'message'=>lang('checkout_validator_addr_lv3')));
            exit;
        }
        $this->verify_addr_for_common_2($data);

    }

    /**
     * @author brady.wang
     * @description 2、美国区：手机号码不超过16位，固定电话不超过20位，邮编不超过16位。（允许输入破节号“-”） 提示语："请输入正确的..."
     */
    public function verify_addr_for_us($attr)
    {
        //验证非空
        if(trim($attr['city']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('city_empty'),'message'=>lang('city_empty')));exit;
        }
        if(trim($attr['address_detail']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('address_detail_empty'),'message'=>lang('address_detail_empty')));exit;
        }
        if(trim($attr['first_name']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('first_name_empty'),'message'=>lang('first_name_empty')));exit;
        }
        if(trim($attr['last_name']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('last_name_empty'),'message'=>lang('last_name_empty')));exit;
        }
        if(trim($attr['phone']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('phone_empty'),'message'=>lang('phone_empty')));exit;
        }

        //验证长度
        if(mb_strlen($attr['city'])>32) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('city_length'),'message'=>lang('city_length')));exit;
        }
        if(strlen($attr['address_detail'])>25) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('address_detail_length_25'),'message'=>lang('address_detail_length_25')));exit;
        }
        if(mb_strlen($attr['first_name'])>25) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('first_name_length'),'message'=>lang('first_name_length')));exit;
        }
        if(mb_strlen($attr['last_name'])>25) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('last_name_length'),'message'=>lang('last_name_length')));exit;
        }
        if(trim($attr['zip_code']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('zip_code_empty'),'message'=>lang('zip_code_empty')));exit;
        }
        //过滤掉号码里的横杠
        $phone = "".$attr['phone'];
        for($i=0;$i<3;$i++)
        {
            $phone =str_replace("-","",$phone);
        }
        //手机号码格式验证
        if(mb_strlen($phone)>11) {
            echo json_encode(array('error'=>true ,'code'=>'10501016','msg'=>lang('check_addr_rule_phone'),'message'=>lang('check_addr_rule_phone')));
            exit;
        }
        $this->verify_addr_for_common_2($attr);

    }

    /**
     * @author brady.wang
     * @description 3、香港区：手机号码不超过11位，固定电话不超过20位，邮编不超过16位。 提示语："请输入正确的..."
     */
    public function verify_addr_for_hk($data)
    {
        $this->verify_addr_for_common_1($data);

        if (strlen(trim($data['phone'])) > 11) {
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('check_addr_rule_phone'),'message'=>lang('check_addr_rule_phone')));exit;
        }

        //备用号码不超过20位
        if (!empty($data['reserve_num'])) {
            if (strlen(trim($data['reserve_num'])) > 11) {
                echo json_encode(array('error'=>true ,'code'=>'0','msg'=>lang('check_addr_rule_reserve_num'),'message'=>lang('check_addr_rule_reserve_num')));exit;
            }

        }
        //邮编不超过16位
        if (strlen($data['zip_code']) > 16) {
            echo json_encode(array('error'=>true ,'code'=>'0','msg'=>lang('check_addr_rule_zip_code'),'message'=>lang('check_addr_rule_zip_code')));exit;
        }

        //过滤掉号码里的横杠
        $phone = "".$data['phone'];
        for($i=0;$i<3;$i++)
        {
            $phone =str_replace("-","",$phone);
        }
        //手机号码格式验证
        if(mb_strlen($phone)>11) {
            echo json_encode(array('error'=>true ,'code'=>'10501016','msg'=>lang('check_addr_rule_phone'),'message'=>lang('check_addr_rule_phone')));
            exit;
        }
    }

    /**
     * @author brady.wang
     * @description 4、韩国区：手机号码不超过16位，固定电话不超过20位，邮编不超过16位。（允许输入破节号“-”）（韩国的电话类似这样的 	010-2837-3379 、 010-3000-8927，） 提示语："请输入正确的..."
     */
    public function verify_addr_for_kr($data)
    {
        $this->verify_addr_for_common_1($data);

        // 韩国必须填邮编
        if(trim($data['zip_code']) == ''){
            echo json_encode(array('error'=>true ,'code'=>'204','msg'=>lang('zip_code_empty'),'message'=>lang('zip_code_empty')));exit;
        }
        //韩国必须填写海关号
//        if (empty($data['customs_clearance'])) {
//            echo json_encode(array('error'=>true ,'code'=>'10501017','msg'=>lang('checkout_validator_customs_clearance'),'message'=>lang('checkout_validator_customs_clearance')));exit;
//        }
        //过滤掉号码里的横杠,空格
        $phone = "".$data['phone'];
        for($i=0;$i<11;$i++)
        {
            $phone =str_replace("-","",$phone);
            $phone =str_replace(" ","",$phone);
        }
        //手机号码格式验证
        if(mb_strlen($phone) > 11 or mb_strlen($phone) < 9) {
            echo json_encode(array('error'=>true ,'code'=>'10501016','msg'=>lang('check_addr_rule_phone'),'message'=>lang('check_addr_rule_phone')));
            exit;
        }
//        var_dump($data);exit($phone.",".__FILE__.",".__LINE__."<BR>");
        $this->verify_addr_for_common_2($data);
    }

    /**
     * @author brady.wang
     * @description 5、其他区：手机号码不超过16位，固定电话不超过20位，邮编不超过16位。（允许输入破节号“-”） 提示语："请输入正确的..."
     */
    public function verify_addr_for_other($data)
    {
        $this->verify_addr_for_common_1($data);
        $this->verify_addr_for_common_2($data);
    }

    public function verify_addr_for_common_2($data)
    {
        //手机号不超过11位
        //过滤掉号码里的横杠，空格
        $phone = "".$data['phone'];
        for($i=0;$i<11;$i++)
        {
            $phone =str_replace("-","",$phone);
            $phone = str_replace(" ","",$phone);
        }
        //手机号码格式验证
        if(mb_strlen($phone)>11) {
            echo json_encode(array('error'=>true ,'code'=>'0','msg'=>lang('check_addr_rule_phone'),'message'=>lang('check_addr_rule_phone')));
            exit;
        }

        if (!empty($data['reserve_num'])) {
            if (strlen($data['reserve_num']) > 20) {
                echo json_encode(array('error'=>true ,'code'=>'0','msg'=>lang('check_addr_rule_reserve_num'),'message'=>lang('check_addr_rule_reserve_num')));
                exit;
            }
        }

        if (strlen($data['zip_code']) > 16) {
            echo json_encode(array('error'=>true ,'code'=>'0','msg'=>lang('check_addr_rule_zip_code'),'message'=>lang('check_addr_rule_zip_code')));
            exit;
        }
    }

}