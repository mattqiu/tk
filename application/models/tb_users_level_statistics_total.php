<?php
/**
 * 店铺统计
 *  
 */
class tb_users_level_statistics_total extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    /***
     * 获取当天店铺统计
     * @param 查询数据 $search_data
     */
    public function get_store_statistics_total($search_data,$add_today_data,$total_name)
    {
        //执行当天统计的存储过程
        ///$this->db->query('/*TDDL:MASTER*/call pro_store_nowday_totals()');     
        
        $this->getTodayUserStortTotal(date('Y-m-d'));
        $store_total_data = array();
        $store_statistics_all_total = array();
        $sql = "SELECT *  FROM users_level_statistics_total WHERE DATE_FORMAT(date,'%Y%m')='".$search_data."' ORDER BY date DESC";      
        $query = $this->db->query($sql);     
        $search_datas = $query->result_array();      
        
        if(!empty($search_datas))
        {
            
            foreach($search_datas as $sult)
            {
                $account_t = $sult['bronze']+$sult['silver']+$sult['golden']+$sult['diamond'];
                $store_total_data[$sult['date']] = array
                (
                    'date'      => date('Y-m-d',strtotime($sult['date'])),
                    'free'      => $sult['free'],
                    'bronze'    => $sult['bronze'],
                    'silver'    => $sult['silver'],
                    'golden'    => $sult['golden'],
                    'diamond'   => $sult['diamond'],
                    'total_x'   => $sult['free']+$account_t,
                    'total_m'   => $account_t
                );
            }
        }     
        
        $free_total = 0;
        $bronze_total = 0;
        $silver_total = 0;
        $golden_total = 0;
        $diamond_total = 0;
        $total_x_total = 0;
        $total_m_total = 0;
        foreach($store_total_data as $sult)
        {
            $free_total = $free_total + $sult["free"];
            $bronze_total = $bronze_total + $sult["bronze"];
            $silver_total = $silver_total + $sult["silver"];
            $golden_total = $golden_total + $sult["golden"];
            $diamond_total = $diamond_total + $sult["diamond"];
            $total_x_total = $total_x_total + $sult["total_x"];
            $total_m_total = $total_m_total + $sult["total_m"];
        }       
        
        
        $total_date[] = array(
            'date' => $total_name."(".substr($search_data,0,4)."-".substr($search_data,4).")",
            'free' => $free_total,
            'bronze' => $bronze_total,
            'silver' => $silver_total,
            'golden' => $golden_total,
            'diamond' => $diamond_total,
            'total_x' => $total_x_total,
            'total_m' => $total_m_total
        );
      
        return array_merge($total_date, $store_total_data);   
    }
   
    /**
     * 获取不同区域当天的总店铺统计
     * @param unknown $type
     * @param unknown $search_data
     */
    public function get_store_area_total_all($type,$search_data,$add_today_data,$total_name)
    {
        $optype = 0;
        $store_today_tatal = array();
        $store_statistics_all_total = array();
        switch ($type) {
            case 1:
                //中国
                $sql = "SELECT *  FROM users_level_statistics_zh WHERE DATE_FORMAT(date,'%Y%m')='" . $search_data . "' ORDER BY date DESC";
                $optype = 1;
                break;
            case 2:
                //美国
                $sql = "SELECT *  FROM users_level_statistics_en WHERE DATE_FORMAT(date,'%Y%m')='" . $search_data . "' ORDER BY date DESC";
                $optype = 1;
                break;
            case 3:
                //韩国
                $sql = "SELECT *  FROM users_level_statistics_kr WHERE DATE_FORMAT(date,'%Y%m')='" . $search_data . "' ORDER BY date DESC";
                $optype = 1;
                break;
            case 4:
                //香港
                $sql = "SELECT *  FROM users_level_statistics_hk WHERE DATE_FORMAT(date,'%Y%m')='" . $search_data . "' ORDER BY date DESC";
                $optype = 1;
                break;
            case 5:
                //其他
                $sql = "SELECT *  FROM users_level_statistics_other WHERE DATE_FORMAT(date,'%Y%m')='" . $search_data . "' ORDER BY date DESC";
                $optype = 0;
                break;
            default:
                //其他
                $sql = "SELECT *  FROM users_level_statistics_other WHERE DATE_FORMAT(date,'%Y%m')='" . $search_data . "' ORDER BY date ASC";
                $optype = 0;
                break;
        }
        
        $query = $this->db->query($sql);
        $search_datas = $query->result_array();        
        if(!empty($search_datas))
        {
            foreach($search_datas as $sult)
            {
                $t_count_p = $sult['bronze']+$sult['silver']+$sult['golden']+$sult['diamond'];
                $store_total_data[$sult['date']] = array
                (
                    'date'      => date('Y-m-d',strtotime($sult['date'])),
                    'free'      => $sult['free'],
                    'bronze'    => $sult['bronze'],
                    'silver'    => $sult['silver'],
                    'golden'    => $sult['golden'],
                    'diamond'   => $sult['diamond'],
                    'total_x'   => $sult['free']+$t_count_p,
                    'total_m'   => $t_count_p,
                );
            }
        }
        
        $free_total = 0;
        $bronze_total = 0;
        $silver_total = 0;
        $golden_total = 0;
        $diamond_total = 0;
        $total_x_total = 0;
        $total_m_total = 0;
        foreach($store_total_data as $sult)
        {
            $free_total = $free_total + $sult["free"];
            $bronze_total = $bronze_total + $sult["bronze"];
            $silver_total = $silver_total + $sult["silver"];
            $golden_total = $golden_total + $sult["golden"];
            $diamond_total = $diamond_total + $sult["diamond"];
            $total_x_total = $total_x_total + $sult["total_x"];
            $total_m_total = $total_m_total + $sult["total_m"];
        }
        $total_date[] = array(
            'date' => $total_name."(".substr($search_data,0,4)."-".substr($search_data,4).")",
            'free' => $free_total,
            'bronze' => $bronze_total,
            'silver' => $silver_total,
            'golden' => $golden_total,
            'diamond' => $diamond_total,
            'total_x' => $total_x_total,
            'total_m' => $total_m_total
        );
        
        return array_merge($total_date,$store_total_data);
    }
    
    
    /**
     * 获取不同区域当天的店铺统计
     * @param 操作类型 0为其他 $opt_type
     * @param 区域 $country
     * @param 用户等级 $userlevel
     */
    public function get_store_dif_country_total($opt_type, $country,$userlevel)
    {
        if(0 == $opt_type)
        {
            $sql = "select count(*) as total from users  WHERE  (country_id =0 || country_id >4)  AND create_time >= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')) AND create_time <= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 23:59:59')) AND user_rank = ".$userlevel;
        }
        else
        {
            $sql = "select count(*) as total  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND create_time >= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')) AND create_time <= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 23:59:59'))  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id='".$country."'  AND userall.user_rank = ".$userlevel;
        }
        $query = $this->db->query($sql);
        return $query->row()->total;       
    }
    
        
    /**
     * 当天总统计
     */
    public function get_store_country_total_all()
    {
        //免费用户
        $free_total = $this->get_store_dif_country_total(0, 5,4);
        $free_total_zh = $this->get_store_dif_country_total(1, 1,4);
        $free_total_en = $this->get_store_dif_country_total(1, 2,4);
        $free_total_kr = $this->get_store_dif_country_total(1, 3,4);
        $free_total_hk = $this->get_store_dif_country_total(1, 4,4);
        
        //铜级会员
        $bron_total = $this->get_store_dif_country_total(0, 5,5);
        $bron_total_zh = $this->get_store_dif_country_total(1, 1,5);
        $bron_total_en = $this->get_store_dif_country_total(1, 2,5);
        $bron_total_kr = $this->get_store_dif_country_total(1, 3,5);
        $bron_total_hk = $this->get_store_dif_country_total(1, 4,5);
        
        //银级会员
        $silver_total = $this->get_store_dif_country_total(0, 5,3);
        $silver_total_zh = $this->get_store_dif_country_total(1, 1,3);
        $silver_total_en = $this->get_store_dif_country_total(1, 2,3);
        $silver_total_kr = $this->get_store_dif_country_total(1, 3,3);
        $silver_total_hk = $this->get_store_dif_country_total(1, 4,3);
        
        //金级会员
        $golden_total = $this->get_store_dif_country_total(0, 5,2);
        $golden_total_zh = $this->get_store_dif_country_total(1, 1,2);
        $golden_total_en = $this->get_store_dif_country_total(1, 2,2);
        $golden_total_kr = $this->get_store_dif_country_total(1, 3,2);
        $golden_total_hk = $this->get_store_dif_country_total(1, 4,2);
        
        //钻石级会员
        $dinmond_total = $this->get_store_dif_country_total(0, 5,1);
        $dinmond_total_zh = $this->get_store_dif_country_total(1, 1,1);
        $dinmond_total_en = $this->get_store_dif_country_total(1, 2,1);
        $dinmond_total_kr = $this->get_store_dif_country_total(1, 3,1);
        $dinmond_total_hk = $this->get_store_dif_country_total(1, 4,1);
        
        //免费
        $free_total_today = $free_total + $free_total_zh + $free_total_en + $free_total_kr + $free_total_hk;
        //铜级
        $bron_total_today = $bron_total +  $bron_total_zh + $bron_total_en + $bron_total_kr + $bron_total_hk;
        //银级
        $silver_total_today = $silver_total + $silver_total_zh + $silver_total_en + $silver_total_kr + $silver_total_hk;
        //金级
        $gol_total_today = $golden_total + $golden_total_zh + $golden_total_en + $golden_total_kr + $golden_total_hk;
        //钻石级
        $dina_total_today = $dinmond_total + $dinmond_total_zh + $dinmond_total_en + $dinmond_total_kr + $dinmond_total_hk;        
        //总统计
        $total_all = $free_total_today + $bron_total_today + $silver_total_today + $gol_total_today + $dina_total_today;
        $data[] = array
        (
            'date'      => date("Ymd"),
            'free'      => $free_total_today,
            'bronze'    => $bron_total_today,
            'silver'    => $silver_total_today,
            'golden'    => $gol_total_today,
            'diamond'   => $dina_total_today,
            'total_x'   => $total_all,
            'total_m'   => ($total_all-$free_total_today)
        );        
        return $data;
    }
    
    /**
     * 获取区域用户统计
     * @param 0其他 1. 正常 $optype
     * @param 国家区域 1.中国2.美国;3.韩国 4.香港 $countryid
     */
    public function getAreaUsersTotal($optype,$countryid)
    {
        
        $total_f = $this->get_store_dif_country_total($optype, $countryid,4);
        $total_p = $this->get_store_dif_country_total($optype, $countryid,1);
        $total_g = $this->get_store_dif_country_total($optype, $countryid,2);
        $total_s = $this->get_store_dif_country_total($optype, $countryid,3);        
        $total_b = $this->get_store_dif_country_total($optype, $countryid,5);
        $data[] = array
        (
            'date'      => date("Ymd"),
            'free'      => $total_f,
            'bronze'    => $total_b,
            'silver'    => $total_s,
            'golden'    => $total_g,
            'diamond'   => $total_p,
            'total_x'   => ($total_f + $total_b + $total_s + $total_g + $total_p),
            'total_m'   => ($total_b + $total_s + $total_g + $total_p)
        );
        return $data;
    }
    
    
    /**
     * 根据不同日期获取不同区域当天的店铺统计
     * @param 操作类型 0为其他 $opt_type
     * @param 区域 $country
     * @param 用户等级 $userlevel
     * @param 日期 $day
     */
    public function get_store_dif_country_total_by_day($opt_type, $country,$userlevel,$day)
    {
        $sql = "";
        if(0 == $opt_type)
        {
            if(4==$userlevel)
            {
                $sql = "select count(*) as total from users  WHERE  (country_id =0 || country_id >4)  AND DATE_FORMAT(FROM_UNIXTIME(create_time) ,'%Y-%m-%d') = '".$day."' AND user_rank = ".$userlevel;
            }
            else 
            {
                $sql = "select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = '".$day."'  and new_level = ".$userlevel."  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id > 4 or userall.country_id =0";
            }            
        }
        else
        {
            if(4==$userlevel)
            {
                $sql = "select count(*) as total  from users  WHERE  create_time >= UNIX_TIMESTAMP('".$day." 00:00:00') AND  create_time <= UNIX_TIMESTAMP( '".$day." 23:59:59') AND  country_id='".$country."'  AND user_rank = ".$userlevel;                   
            }
            else 
            {
                $sql = "select count(*) as total  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = '".$day."'   AND new_level = ".$userlevel."  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id='".$country."'";
            }
            
        }
        $query = $this->db->query($sql);
        return $query->row_array()->total;
    }
    
    
    public function get_store_country_total_all_by_day($day)
    {
      
        //免费用户
        $free_total = $this->get_store_dif_country_total_by_day(0, 5,4,$day);
       
        $free_total_zh = $this->get_store_dif_country_total_by_day(1, 1,4,$day);
        $free_total_en = $this->get_store_dif_country_total_by_day(1, 2,4,$day);
        $free_total_kr = $this->get_store_dif_country_total_by_day(1, 3,4,$day);
        $free_total_hk = $this->get_store_dif_country_total_by_day(1, 4,4,$day);
    
        //铜级会员
        $bron_total = $this->get_store_dif_country_total_by_day(0, 5,5,$day);
        $bron_total_zh = $this->get_store_dif_country_total_by_day(1, 1,5,$day);
        $bron_total_en = $this->get_store_dif_country_total_by_day(1, 2,5,$day);
        $bron_total_kr = $this->get_store_dif_country_total_by_day(1, 3,5,$day);
        $bron_total_hk = $this->get_store_dif_country_total_by_day(1, 4,5,$day);
    
        //银级会员
        $silver_total = $this->get_store_dif_country_total_by_day(0, 5,3,$day);
        $silver_total_zh = $this->get_store_dif_country_total_by_day(1, 1,3,$day);
        $silver_total_en = $this->get_store_dif_country_total_by_day(1, 2,3,$day);
        $silver_total_kr = $this->get_store_dif_country_total_by_day(1, 3,3,$day);
        $silver_total_hk = $this->get_store_dif_country_total_by_day(1, 4,3,$day);
    
        //金级会员
        $golden_total = $this->get_store_dif_country_total_by_day(0, 5,2,$day);
        $golden_total_zh = $this->get_store_dif_country_total_by_day(1, 1,2,$day);
        $golden_total_en = $this->get_store_dif_country_total_by_day(1, 2,2,$day);
        $golden_total_kr = $this->get_store_dif_country_total_by_day(1, 3,2,$day);
        $golden_total_hk = $this->get_store_dif_country_total_by_day(1, 4,2,$day);
    
        //钻石级会员
        $dinmond_total = $this->get_store_dif_country_total_by_day(0, 5,1,$day);
        $dinmond_total_zh = $this->get_store_dif_country_total_by_day(1, 1,1,$day);
        $dinmond_total_en = $this->get_store_dif_country_total_by_day(1, 2,1,$day);
        $dinmond_total_kr = $this->get_store_dif_country_total_by_day(1, 3,1,$day);
        $dinmond_total_hk = $this->get_store_dif_country_total_by_day(1, 4,1,$day);
    
        //免费
        $free_total_today = $free_total + $free_total_zh + $free_total_en + $free_total_kr + $free_total_hk;
        //铜级
        $bron_total_today = $bron_total +  $bron_total_zh + $bron_total_en + $bron_total_kr + $bron_total_hk;
        //银级
        $silver_total_today = $silver_total + $silver_total_zh + $silver_total_en + $silver_total_kr + $silver_total_hk;
        //金级
        $gol_total_today = $golden_total + $golden_total_zh + $golden_total_en + $golden_total_kr + $golden_total_hk;
        //钻石级
        $dina_total_today = $dinmond_total + $dinmond_total_zh + $dinmond_total_en + $dinmond_total_kr + $dinmond_total_hk;
        //总统计
        $total_all = $free_total_today + $bron_total_today + $silver_total_today + $gol_total_today + $dina_total_today;
        
        //中国 地区当天统计
        $sql_zh = "DELETE FROM users_level_statistics_zh WHERE date = DATE_FORMAT('".$day."','%Y%m%d')";
        $this->db->query($sql_zh);       
        $query_zh_add = "INSERT INTO users_level_statistics_zh SET date=DATE_FORMAT('".$day."','%Y%m%d'), free='".($free_total_zh+$dinmond_total_zh).
            "', bronze='".$bron_total_zh."', silver='".$silver_total_zh."', golden = '".$golden_total_zh."', diamond='".$dinmond_total_zh."'";        
        $this->db->query($query_zh_add);
        //-->end(中国 地区当天统计)
        
        //美国 地区当天统计
        $sql_en = "DELETE FROM users_level_statistics_en WHERE date = DATE_FORMAT('".$day."','%Y%m%d')";       
        $this->db->query($sql_en);        
        $sql_en_add = "INSERT INTO users_level_statistics_en SET date=DATE_FORMAT('".$day."','%Y%m%d'), free='".($free_total_en + $dinmond_total_en).
            "', bronze='".$bron_total_en."', silver='".$silver_total_en."', golden = '".$golden_total_en."', diamond='".$dinmond_total_en."'";         
             
        $this->db->query($sql_en_add);
        //-->end(美国 地区当天统计)
        
        //韩国 地区当天统计
        $sql_kr = "DELETE FROM users_level_statistics_kr WHERE date = DATE_FORMAT('".$day."','%Y%m%d')";
        $this->db->query($sql_kr);        
        $sql_kr_add = "INSERT INTO users_level_statistics_kr SET date=DATE_FORMAT('".$day."','%Y%m%d'), free='".($free_total_kr + $dinmond_total_kr).
            "', bronze='".$bron_total_kr."', silver='".$silver_total_kr."', golden = '".$golden_total_kr."', diamond='".$dinmond_total_kr."'";
        
        $this->db->query($sql_kr_add);
        //-->end(韩国 地区当天统计)
        
        //香港地区当天统计
        $sql_hk = "DELETE FROM users_level_statistics_hk WHERE date = DATE_FORMAT('".$day."','%Y%m%d')";
        $this->db->query($sql_hk);        
        $sql_hk_add = "INSERT INTO users_level_statistics_hk SET date=DATE_FORMAT('".$day."','%Y%m%d'), free='".($free_total_hk + $dinmond_total_hk).
            "', bronze='".$bron_total_hk."', silver='".$silver_total_hk."', golden = '".$golden_total_hk."', diamond='".$dinmond_total_hk."'";
       
        $this->db->query($sql_hk_add);
        //-->end(香港 地区当天统计)
        
        //香港地区当天统计
        $sql_other = "DELETE FROM users_level_statistics_other WHERE date = DATE_FORMAT('".$day."','%Y%m%d')";
        $this->db->query($sql_other);        
        $sql_other_add = "INSERT INTO users_level_statistics_other SET date=DATE_FORMAT('".$day."','%Y%m%d'), free='".($free_total + $dinmond_total).
            "', bronze='".$bron_total."', silver='".$silver_total."', golden = '".$golden_total."', diamond='".$dinmond_total."'";
        
        $this->db->query($sql_other_add);
        //-->end(香港 地区当天统计)
        
        //当天总统计
        $sql_total = "DELETE FROM users_level_statistics_total WHERE date = DATE_FORMAT('".$day."','%Y%m%d')";
        $this->db->query($sql_total);        
        $sql_total_add = "INSERT INTO users_level_statistics_total SET date=DATE_FORMAT('".$day."','%Y%m%d'), free='".($free_total_today+$dina_total_today).
            "', bronze='".$bron_total_today."', silver='".$silver_total_today."', golden = '".$gol_total_today."', diamond='".$dina_total_today."'";        
        $this->db->query($sql_total_add);
        //-->end(当天总统计)
    }
    
    //时间段查询并导入数据
    public function getDataShow($begin,$end)
    {        
        $begintime = strtotime($begin);
        $endtime = strtotime($end);
        for ($start = $begintime; $start <= $endtime; $start += 24 * 3600) 
        {               
            $this->get_store_country_total_all_by_day(date("Y-m-d", $start));
        }        
    }
    
    /**
     * 统计当天店铺
     * @param unknown $user_level
     */
    public function getUserStoreTotalToday($user_level,$time_t)
    {
        $data = array();
        if(!empty($time_t))
        {
            $s_time = $time_t." 00:00:00";
            $e_time = $time_t." 23:59:59";
        }
        else 
        {
            $s_time =  'DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00")';
            $e_time = 'DATE_FORMAT(NOW(),"%Y-%m-%d 23:59:59")';
        }
        if(4==$user_level)
        {
            
            $sql = "select country_id as country, count(*) as total from users WHERE create_time >= UNIX_TIMESTAMP('".$s_time."') AND
                create_time <= UNIX_TIMESTAMP('".$e_time."')  AND user_rank = ".$user_level." group by country_id";
        }
        else
        {
            $sql = "select userall.country_id  as country,count(*) as total from users userall right join
                (select uid from users_level_change_log WHERE level_type = 2 AND create_time >= '".$s_time."' AND create_time <='".$e_time."' AND new_level = ".$user_level."  group by uid ) as users_level on users_level.uid = userall.id  GROUP BY  userall.country_id";
        }
        
        $query = $this->db->query($sql);
        $query_value = $query->result_array();
        if(!empty($query_value))
        {
            $china = 0;
            $hk = 0;
            $usa = 0;
            $kz = 0;
            $other = 0;
            foreach($query_value as $sult)
            {                
                switch($sult['country'])
                {
                    case 1:
                        //中国
                        $china = $china + $sult['total'];
                        break;
                    case 2:
                        //美国
                        $usa = $usa + $sult['total'];
                        break;
                    case 3:
                        //韩国
                        $kz = $kz + $sult['total'];
                        break;
                    case 4:
                        //香港
                        $hk = $hk + $sult['total'];
                        break;
                    default:
                        $other = $other + $sult['total'];
                        break;
                }                
            }
            $data[] = array
            (
                'china' => $china,
                'usa'   => $usa,
                'kz'    => $kz,
                'hk'    => $hk,
                'other' => $other
            );
        }
        
        return $data;
    }
    
   /**
    * 获取当天店铺统计
    * @param 时间 （时间格式date(Y-m-d)） $isToday
    */
    public function getTodayUserStortTotal($isToday)
    {
        
        if(!empty($isToday))
        {
            $d_s_time = $isToday;
            
            $s_times = strtotime($d_s_time);
            $d_time = date("Ymd", $s_times);
        }
        else
        {
            $d_s_time = date('Y-m-d');            
            $s_times = strtotime($d_s_time);
            $d_time = date("Ymd", $s_times);
        }
        
        $diamond=$this->getUserStoreTotalToday(1,$d_s_time); //钻石级        
        $gold=$this->getUserStoreTotalToday(2,$d_s_time); //白银级        
        $silver=$this->getUserStoreTotalToday(3,$d_s_time); //银级        
        $free=$this->getUserStoreTotalToday(4,$d_s_time); //免费        
        $bronze=$this->getUserStoreTotalToday(5,$d_s_time); //统计
        
        if(empty($diamond))
        {
            $diamond[] = array(
                'china' => 0,
                'usa'   => 0,
                'kz'    => 0,
                'hk'    => 0,
                'other' => 0
            );
        }
        
        if(empty($gold))
        {
            $gold[] = array(
                'china' => 0,
                'usa'   => 0,
                'kz'    => 0,
                'hk'    => 0,
                'other' => 0
            );
        }
        
        if(empty($silver))
        {
            $silver[] = array(
                'china' => 0,
                'usa'   => 0,
                'kz'    => 0,
                'hk'    => 0,
                'other' => 0
            );
        }
        
        if(empty($free))
        {
            $free[] = array(
                'china' => 0,
                'usa'   => 0,
                'kz'    => 0,
                'hk'    => 0,
                'other' => 0
            );
        }
        
        if(empty($bronze))
        {
            $bronze[] = array(
                'china' => 0,
                'usa'   => 0,
                'kz'    => 0,
                'hk'    => 0,
                'other' => 0
            );
        }
        
        $diamond_total = !empty($diamond[0]['china'])?$diamond[0]['china']:0 + $diamond[0]['usa'] + $diamond[0]['kz'] + $diamond[0]['hk'] + $diamond[0]['other'];
        $gold_total = $gold[0]['china'] + $gold[0]['usa'] + $gold[0]['kz'] + $gold[0]['hk'] + $gold[0]['other'];
        $silver_total = $silver[0]['china'] + $silver[0]['usa'] + $silver[0]['kz'] + $silver[0]['hk'] + $silver[0]['other'];
        $free_total = $free[0]['china'] + $free[0]['usa'] + $free[0]['kz'] + $free[0]['hk'] + $free[0]['other'];
        $bronze_total = $bronze[0]['china'] + $bronze[0]['usa'] + $bronze[0]['kz'] + $bronze[0]['hk'] + $bronze[0]['other'];
        
        //中国 地区当天统计
        $sql_zh = "DELETE FROM users_level_statistics_zh WHERE date = '".$d_time."'";
        $this->db->query($sql_zh);        
        $query_zh_add = "INSERT INTO users_level_statistics_zh SET date='".$d_time."', free='".$free[0]['china'].
        "', bronze='".$bronze[0]['china']."', silver='".$silver[0]['china']."', golden = '".$gold[0]['china']."', diamond='".$diamond[0]['china']."'";        
        $this->db->query($query_zh_add);
        
        //美国 地区当天统计
        $sql_en = "DELETE FROM users_level_statistics_en WHERE date = '".$d_time."'";
        $this->db->query($sql_en);
        $query_usa_add = "INSERT INTO users_level_statistics_en SET date='".$d_time."', free='".$free[0]['usa'].
        "', bronze='".$bronze[0]['usa']."', silver='".$silver[0]['usa']."', golden = '".$gold[0]['usa']."', diamond='".$diamond[0]['usa']."'";        
        $this->db->query($query_usa_add);
        
        //韩国地区
        $sql_kr = "DELETE FROM users_level_statistics_kr WHERE date =  '".$d_time."'";
        $this->db->query($sql_kr);
         $query_kr_add = "INSERT INTO users_level_statistics_kr SET date='".$d_time."', free='".$free[0]['kz'].
        "', bronze='".$bronze[0]['kz']."', silver='".$silver[0]['kz']."', golden = '".$gold[0]['kz']."', diamond='".$diamond[0]['kz']."'";        
        $this->db->query($query_kr_add);
        
        //香港地区当天统计
        $sql_hk = "DELETE FROM users_level_statistics_hk WHERE date = '".$d_time."'";
        $this->db->query($sql_hk);
        $query_hk_add = "INSERT INTO users_level_statistics_hk SET date='".$d_time."', free='".$free[0]['hk'].
        "', bronze='".$bronze[0]['hk']."', silver='".$silver[0]['hk']."', golden = '".$gold[0]['hk']."', diamond='".$diamond[0]['hk']."'";        
        $this->db->query($query_hk_add);
        
        //其他地区当天统计
        $sql_other = "DELETE FROM users_level_statistics_other WHERE date = '".$d_time."'";
        $this->db->query($sql_other);
         $query_other_add = "INSERT INTO users_level_statistics_other SET date='".$d_time."', free='".$free[0]['other'].
        "', bronze='".$bronze[0]['other']."', silver='".$silver[0]['other']."', golden = '".$gold[0]['other']."', diamond='".$diamond[0]['other']."'";        
        $this->db->query($query_other_add);
        
        //当天总统计
        $sql_total = "DELETE FROM users_level_statistics_total WHERE date = '".$d_time."'";
        $this->db->query($sql_total);
        
        $sql_total_add = "INSERT INTO users_level_statistics_total SET date= '".$d_time."', free='".$free_total.
        "', bronze='".$bronze_total."', silver='".$silver_total."', golden = '".$gold_total."', diamond='".$diamond_total."'";
        $this->db->query($sql_total_add);
        
    }
    
    
    

}