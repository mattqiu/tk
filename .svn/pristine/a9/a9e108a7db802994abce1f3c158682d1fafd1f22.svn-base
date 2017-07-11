<?php

/**
 * 公告model
 * Created by PhpStorm.
 * User: brady
 * Date: 2016/12/28
 * Time: 10:36
 */
class tb_bulletin_board extends MY_Model
{
    protected $table = "bulletin_board";

    public function __construct()
    {
        parent::__construct();
        $this->load->model("m_bulletin_read");
        $this->load->model("m_bulletin_unread");
    }


    /**
     * @param $params
     * @param $parent_id 父类id
     * @param $country 国家id
     * @param bool|false $display 是否查询显示的
     * @param bool|false $get_rows 是否查询行数
     * @return array
     */
    public function get_list($params, $parent_id, $country, $display = false, $get_rows = false)
    {
        //参数过滤
        $params['page'] = intval($params['page']);
        $params['page_size'] = intval($params['page_size']);
        $params['page'] = empty($params['page']) ? 1 : $params['page'];
        $params['page_size'] = empty($params['page_size']) ? 10 : $params['page_size'];

        //根据国家查询一种语言的即可
        $search = [
            "select" => "id,title_" . $country . ", permission," . $country . ", display,sort,create_time,important",
            'where' => [
                'title_' . $country . " !=" => '0',
                'title_' . $country . " != " => ''
            ]
        ];

        //是否只查询显示状态的的
        if ($display) {
            $search['where']['display'] = 1;
        }

        $search['where_in']['key'] = "permission";
        if ($this->m_global->isStore($parent_id)) {
            $search['where_in']['value'] = [1, 2];
        } else {
            $search['where_in']['value'] = [1, 3];
        }

        //是返回行数还是返回记录
        if ($get_rows) {
            $result = $this->get($search, true);
        } else {
            $search['order'] = [
                [
                    'key' => "sort",
                    'value' => 'desc',
                ],
                [
                    'key' => 'important',
                    'value' => 'desc'
                ]
            ];
            $start = ($params['page'] - 1) * $params['page_size'];
            $search['limit'] = ['page' => $params['page'], 'page_size' => $params['page_size']];
            $result = $this->get($search);

        }
        return $result;
    }

    /**
     * @description 对公告数据最后组装
     * @param $data 公告数据
     * @param $uid 用户id
     * @param $country 国家
     * @return mixed
     */
    public function get_read_not($data, $uid, $country,$parent_id)
    {
        $ids_read = [];
        $ids_unread = [];
        $time_till = strtotime('2016/12/26');
        foreach ($data as &$item) {
            if (strtotime($item['create_time']) > $time_till) {
                $ids_read[] = $item['id'];
            } else {
                $ids_unread[] = $item['id'];
            }

        }
        //未读处理
        if (!empty($ids_unread)) {
            $unread = $this->m_bulletin_unread->get([
                'select' => 'bulletin_id',
                'where'=>[
                        'uid'=>$uid
                ],
                'where_in' => [
                    'key' => 'bulletin_id',
                    'value' => $ids_unread
                ]
            ]);
            if (!empty($unread)) {
                $unreads = [];
                foreach ($unread as $v) {
                    $unreads[] = $v['bulletin_id'];
                }
                foreach ($data as &$item) {
                    if (in_array($item['id'],$ids_unread)) {
                        if (in_array($item['id'], $unreads)) {
                            $item['is_read'] = FALSE;
                            $item[$country . '_short'] = $this->cutstr_html($item["$country"], $length = 100, $ellipsis = '…');
                            //$item[$country . '_short'] = mb_substr(($item["$country"]), 0, 100, 'utf-8') . '...';
                        } else {
                            $item['is_read'] = TRUE;
                        }
                    }


                }
            } else {
                //全部都已读
                foreach ($data as &$item) {
                    if (in_array($item['id'],$ids_unread)) {
                        if (in_array($item['id'], $ids_unread)) {
                            $item['is_read'] = TRUE;
                        }
                    }
                }
            }

        }
        //已读表处理
        if (!empty($ids_read)) {
            $read = $this->m_bulletin_read->get([
                'select' => 'bulletin_id',
                'where' =>[
                        'uid'=>$uid
                ],
                'where_in' => [
                    'key' => 'bulletin_id',
                    'value' => $ids_read
                ]
            ]);

            if (!empty($read)) {
                $reads = [];
                foreach ($read as $v) {
                    $reads[] = $v['bulletin_id'];
                }

                foreach ($data as &$item) {
                    if (in_array($item['id'],$ids_read)) {
                        if (in_array($item['id'], $reads)) {
                            $item['is_read'] = TRUE;
                        } else {
                            $item['is_read'] = FALSE;
                            $item[$country . '_short'] = $this->cutstr_html($item["$country"], $length = 100, $ellipsis = '…');
                            //$item[$country . '_short'] = mb_substr(($item["$country"]), 0, 100, 'utf-8') . '...';
                        }
                    }

                }
            } else {
                foreach ($data as &$item) {
                    if (in_array($item['id'],$ids_read)) {
                            $item['is_read'] = FALSE;
                            $item[$country . '_short'] = $this->cutstr_html($item["$country"], $length = 100, $ellipsis = '…');
                            //$item[$country . '_short'] = mb_substr(($item["$country"]), 0, 100, 'utf-8') . '...';
                    }

                }
            }
        }

        return $data;
    }

    /**
     * @param $uid 获取用户未读消息数目  区分两个时间段 2016/12/26 之前用unread 之后查询 read表
     */
    public function get_unread_counts($uid,$country,$parent_id)
    {
        $time_till = '2016-12-26';
        //找到2016-11-26日以前的未读消息数目
        if ($this->m_global->isStore($parent_id)) {
            $search['where_in']['value'] = [1, 2];
            $sql = "select count(0) as number from bulletin_board as a join bulletin_unread as b on a.id = b.bulletin_id where b.uid = ".$uid." and a.title_".$country." !='0' and a.title_".$country." !='' and a.create_time<'2016/12/26' and a.display=1 and a.permission in(1,2)";
            $count1_arr =  $this->db->query($sql)->row_array();
            $count1 = $count1_arr['number'];
            //找到2016-12-26后面的未读消息数目
            $sql = "select count(0) as number from bulletin_board where create_time > '".$time_till."'  and permission in(1,2)  and display=1 and title_".$country." !='0' and title_".$country." !='' and   id not in(SELECT bulletin_id from bulletin_read where uid = ".$uid." )";
            $count2_arr =  $this->db->query($sql)->row_array();
            $count2 = $count2_arr['number'];

        } else {
            $search['where_in']['value'] = [1, 3];
            $sql = "select count(0) as number  from bulletin_board as a join bulletin_unread as b on a.id = b.bulletin_id where b.uid = ".$uid." and title_".$country." !='0' and title_".$country." !='' and create_time<'2016/12/26' and display=1 and a.display=1 and a.permission in(1,3)";
            $count1_arr =  $this->db->query($sql)->row_array();
            $count1 = $count1_arr['number'];
            $sql = "select count(0) as number from bulletin_board where create_time > '".$time_till."'  and display=1 and title_".$country." !='0' and title_".$country." !=''   and permission in(1,3) and    id not in(SELECT bulletin_id from bulletin_read where uid = ".$uid." )";
            $count2_arr =  $this->db->query($sql)->row_array();
            $count2 = $count2_arr['number'];
        }

        return $count1 + $count2;

    }

    public function get_row($id)
    {
        $key = config_item("redis_key")['bulletin_board_list'].$id;
        if (REDIS_STOP == 0) {
            $res = $this->redis_get($key);
            if ($res) {
                //echo "走缓存";
                $data =  json_decode($res,true);
            } else {
                $data = $this->db->from($this->table)->where(array('id'=>$id))->get()->row_array();
                $this->redis_set($key,json_encode($data),config_item("redis_expire")['bulletin_board_list']);
            }
        } else {
            $data = $this->db->from($this->table)->where(array('id'=>$id))->get()->row_array();
            $this->redis_set($key,json_encode($data),config_item("redis_expire")['bulletin_board_list']);
        }
        return $data;
    }

    function cutstr_html($string, $length = 0, $ellipsis = '…')
    {
        $string = strip_tags($string);
        $string = preg_replace('/\n/is', '', $string);
        $string = preg_replace('/ |　/is', '', $string);
        $string = preg_replace('/&nbsp;/is', '', $string);
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $string);
        if(is_array($string) && !empty($string[0])) {
            if(is_numeric($length) && $length) {
                $string = join('', array_slice($string[0], 0, $length)) . $ellipsis;
            } else {
                $string = implode('', $string[0]);
            }
        } else {
            $string = '';
        }
        return $string;
    }
}