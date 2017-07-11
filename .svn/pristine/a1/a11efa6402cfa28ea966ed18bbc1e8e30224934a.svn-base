/**创建店铺统计的存储过程**/
DROP PROCEDURE IF EXISTS pro_store_statistics_total;
CREATE PROCEDURE pro_store_statistics_total(in country_type int,in countryid int , in user_level int, out totals int)
BEGIN

 /**当区域country_type  0为其他地区统计， 1为，中国，美国等具体地区统计   country_id 等于0，或大于4时，为其他地区，其他正常*****/
 IF country_type = 0
 THEN
 	IF user_level =4
 	THEN
 		select count(*) into @counts from users  WHERE user_rank = 4 AND (country_id =0 || country_id >4)  AND DATE_FORMAT(FROM_UNIXTIME(create_time) ,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = date_sub(curdate(),interval 1 day)  and new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id > 4 or userall.country_id =0 ;
 	END IF;
   
 ELSE 
 	IF user_level=4
 	THEN
 		select count(*) into @counts  from users WHERE FROM_UNIXTIME(create_time,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) AND country_id=countryid  AND user_rank = user_level;
 	ELSE
 		select count(*) into @counts  from users userall right join (select uid from users_level_change_log WHERE level_type = '2' AND DATE_FORMAT(create_time,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) AND new_level = user_level  group by uid  order by create_time desc) as users_level on users_level.uid = userall.id  WHERE  userall.country_id=countryid ;
 	END IF;
    
 END IF;

 SET totals = @counts;

END;
