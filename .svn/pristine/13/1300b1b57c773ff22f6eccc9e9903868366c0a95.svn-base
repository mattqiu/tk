<?php

/** 系统配置权限
 * Class admin_right
 */
class tb_admin_right extends MY_Model
{

    protected $table_name = 'admin_right';

    public function __construct()
    {
        parent::__construct();
    }

    /** 添加权限
     * @param $data
     * @return mixed
     */
    public function addRight($data)
    {
        $this->redis_del('admin:tb_admin_right:getAllRight');
        $this->db->insert('admin_right', $data);
        return $this->db->insert_id();

    }

    /** 自动检测key,若没有，则自动添加此权限
     * @param $rightKey
     * @return mixed
     */
    public function autoAddRight($rightKey){

        $arr = array(
            'admin_id'  => 0,
            'right_name'=> 'null',
            'right_key' => $rightKey,
            'remark'    => 'null',
            'right'     => serialize(array(1)),
        );

        return $this->addRight($arr);
    }

    //更新
    public function updateRightForArr($id,$updateData){

        $this->db->where('id',$id);
        $this->db->update('admin_right',$updateData);
        return $this->db->affected_rows();
    }

    /** 更新权限
     * @param $rightId
     * @param $adminId
     * @author andy
     */
    public function updateRightForId($rightId, $adminId)
    {

        $select = '*';
        $where['id'] = $rightId;

        $oldData = $this->get_one($select, $where);

        $rightArray = unserialize($oldData['right']);

        $newRight = array_push($rightArray, $adminId);

        $data['right'] = serialize($newRight);

        return $this->update_one($where, $data);

    }


    public function getOneRightById($id){
        $this->db->from('admin_right');
        return $this->db->where('id',$id)->get()->row_array();
    }


    /** 通过键获取数组，用于分配工单的名单过滤
     * @param $key
     */
    public function getOneRightByKeyForTickets($key){

        $this->db->from('admin_right');
        $rows = $this->db->where('right_key',$key)->where('type',2)->get()->row_array();

        $right = array();

        if(empty($rows)){

            switch($key)
            {
                //韩国客服过滤
                case 'deny_assign_tickets_for_kr_customers':{
                    $right = array(975,806,890,817,834,835,976,840,869);
                    $arr = array(
                        'type'      =>2,
                        'admin_id'  => 0,
                        'right_name'=> '不分配的韩国客服工号',
                        'right_key' => $key,
                        'remark'    => '不分配的韩国客服工号',
                        'right'     => serialize($right),
                    );
                    $id = $this->addRight($arr);

                    $log = array(
                        'type'  =>2,
                        'right_id'=>$id,
                        'admin_id'=>0,
                        'old_data'=>'',
                        'new_data'=>serialize($arr),
                    );
                    $this->db->insert('admin_right_log',$log);
                    break;
                }

                //香港客服过滤
                case 'deny_assign_tickets_for_hk_customers':{
                    $right = array(975,806,890,817,834,835,976,840,869);
                    $arr = array(
                        'type'      =>2,
                        'admin_id'  => 0,
                        'right_name'=> '不分配的香港客服工号',
                        'right_key' => $key,
                        'remark'    => '不分配的香港客服工号',
                        'right'     => serialize($right),
                    );
                    $id = $this->addRight($arr);

                    $log = array(
                        'type'  =>2,
                        'right_id'=>$id,
                        'admin_id'=>0,
                        'old_data'=>'',
                        'new_data'=>serialize($arr),
                    );
                    $this->db->insert('admin_right_log',$log);
                    break;
                }

                //中文客服过滤
                case 'deny_assign_tickets_for_zh_customers':{
                    $right = array(975,806,890,817,834,835,976,981,840,869);
                    $arr = array(
                        'type'      =>2,
                        'admin_id'  => 0,
                        'right_name'=> '不分配的中国客服工号',
                        'right_key' => $key,
                        'remark'    => '不分配的中国客服工号',
                        'right'     => serialize($right),
                    );
                    $id = $this->addRight($arr);

                    $log = array(
                        'type'  =>2,
                        'right_id'=>$id,
                        'admin_id'=>0,
                        'old_data'=>'',
                        'new_data'=>serialize($arr),
                    );
                    $this->db->insert('admin_right_log',$log);
                    break;
                }

                default : break;
            }

            return $right;
        }
        return $rows ? unserialize($rows['right']) : array();
    }


    /**
     * 获取全部权限列表
     * @author andy
     */
    public function getAllRight($searchData=[])
    {

        $all_right = $this->redis_get('admin:tb_admin_right:getAllRight');

        if(!$all_right){
            $this->db->from('admin_right');
            $this->filter($searchData);
            $res =  $this->db->get()->result_array();

            $this->redis_set('admin:tb_admin_right:getAllRight',serialize($res),60*60*24);

            return $res;
        }else{

            return unserialize($all_right);

        }
    }

    public function getAllRightList($searchData=[],$perPage=10)
    {

        $this->db->from('admin_right');
        $this->filter($searchData);
        return $this->db->limit($perPage, ($searchData['page'] - 1) * $perPage)->get()->result_array();

    }


    public function getAllRightCount($searchData=[])
    {
        $this->db->from('admin_right');
        $this->filter($searchData);
        return $this->db->count_all_results();
    }

    private function filter($searchData){

        if($searchData)
        {

            foreach($searchData as $k=>$v){
                if($k=='page' || $v===''){
                    continue;
                }

                switch($k){

                    case 'right_name':{
                        $this->db->like('right_name',$v);
                        break;
                    }

                    case 'type':{
                        $this->db->where('type',$v);
                        break;
                    }

                    default:break;

                }
            }

        }

    }
}