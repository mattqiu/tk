<?php
Class M_upgrade_level extends CI_Model{

    function __construct()
    {
        parent::__construct();
    }

    public function getLevel(){
        return  $this->db->from('user_rank')->where('rank_id <=',LEVEL_SILVER)->order_by('rank_id','desc')->get()->result_array();
    }

    /**用户升级等级
     * @param $user_id  用户ID
     * @param $rank_id 升级的等级
     * @param $currency
     * @return array
     */
    public function userUpgrade($user_id,$rank_id,$currency){

        $user = $this->db->from('users')->where('id', $user_id)->get()->row_array();

        //用户的等级比升级的等级高 或相等 （不必升级）
        if($user['user_rank'] <= $rank_id){
            return array('success' => FALSE,'msg'=> lang('upgrade_level_low'));
        }

        $user_rank = $this->db->from('user_rank')->where('rank_id', $user['user_rank'])->get()->row_array();

        $upgrade = $this->db->from('user_rank')->where('rank_id', $rank_id)->get()->row_array();

        //计算升级所需的费用
        $annual_fee = $upgrade['annual_fee'] - $user_rank['annual_fee']; //年费
        $manage_fee = $upgrade['manage_fee']; //管理费需要重新交
        $total_fee = $annual_fee + $manage_fee;

        //账户余额不足够升级
        if($user['amount'] < $total_fee){
           // return array('success' => FALSE,'msg'=> lang('amount_not_enough'));
        }

        //扣除升级所需余额
        //$this->db->update('users', array('amount'=>$user['amount']-$total_fee), array('id' => $user_id));

        return array('success' => TRUE,'msg'=> lang('upgrade_success'),
            'total_fee'=>$this->m_currency->price_format($total_fee,$currency),'annual_fee'=>$this->m_currency->price_format($user_rank['annual_fee'],$currency),
            'manage_fee'=>$this->m_currency->price_format($manage_fee,$currency),'upgrade_annual_fee'=>$this->m_currency->price_format($upgrade['annual_fee'],$currency)
        );
    }
}