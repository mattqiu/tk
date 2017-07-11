/**当天统计**/
DROP PROCEDURE IF EXISTS pro_store_nowday_total;
CREATE PROCEDURE pro_store_nowday_total (in country_type int,in countryid int , in user_level int, out totals int)
BEGIN
 
 IF country_type = 0
 THEN
 	IF user_level = 4
 	THEN
 		select count(*) into @counts from users  WHERE  (country_id =0 || country_id >4)  AND create_time >=DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00") AND create_time <=DATE_FORMAT(NOW(),"%Y-%m-%d 23:59:59")  AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')  and new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id > 4 or userall.country_id =0 ;
 	END IF;   
 ELSE 
 	IF user_level = 4
 	THEN
 		select count(*) into @counts  from users WHERE create_time >= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00')) AND create_time <= UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y-%m-%d 23:59:59'))  AND country_id=countryid  AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND create_time >=DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00") AND create_time <=DATE_FORMAT(NOW(),"%Y-%m-%d 23:59:59") AND new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id=countryid;
 	END IF;
    
 END IF;

 SET totals = @counts;

END;