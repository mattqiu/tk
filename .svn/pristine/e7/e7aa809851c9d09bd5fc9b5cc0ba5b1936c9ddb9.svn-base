<?php
/**
 * 用户冻结解封备注
 *
 */
class tb_users_frozen_remark extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /***
     * 坚持用户是否存在备注信息
     * @param unknown $uid
     */
    public function check_users_frozen_remark($uid)
    {
        $sql = "select * from users_frozen_remark WHERE uid = '.$uid.'   limit 0,1";       
        $query = $this->db->query($sql);       
        return $query->result();
    }
    
    /***
     * 根据用户编号查询用户备注信息
     * @param unknown $uid
     */
	public function get_users_frozen_remark($uid)
	{	    
		$query = $this->db->query('select * from users_frozen_remark WHERE uid = '.$uid.' order by dates desc');
		return $query->result_array();		
	}

	/**
	 * 用户冻结解封备注信息
	 * @param 用户编号 $uid
	 * @param 操作用户 $option
	 * @param 备注信息 $content
	 */
	public function add_user_frozen_remark($uid,$option,$content,$optiontype){

	    $sql = "INSERT INTO users_frozen_remark SET uid = '".$uid.
	    "', options = '".$option.
	    "', content = '".$content.
	    "', optiontype = '".$optiontype.
	    "', dates = Now()";
		$this->db->query($sql);		
		if(1==$optiontype)
		{
		    /**不合格店铺**/
		    $edit_sql = "UPDATE users SET  store_qualified = 0 WHERE id=".$uid;
		    $this->db->query($edit_sql);
		}
		else if(2==$optiontype)
		{
		    /**合格用户**/
		    $edit_sql = "UPDATE users SET  store_qualified = 1 WHERE id=".$uid;
		    $this->db->query($edit_sql);
		}
		else if(3==$optiontype)
		{
		    /**合格用户**/
		    $edit_sql = "UPDATE users SET  store_qualified = 1 WHERE id=".$uid;
		    $this->db->query($edit_sql);
		}
		
		
	}

}