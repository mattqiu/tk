/**用户等级升级时，如果满足每周团队组织分红奖， 则发奖**/
/***********************************************
user_id 用户id，
old_sale_rank,老级别,
new_sale_rank，新级别,
option_m，操作类型，0为职称变更，1为级别变更，2为订单变更
注：当option_m 为0时，old_sale_rank,new_sale_rank分别为
老的职称和新的职称
************************************************/
DROP PROCEDURE IF EXISTS user_rank_change_week_comm;
CREATE PROCEDURE user_rank_change_week_comm(IN user_id int , IN old_sale_rank int, IN new_sale_rank int,IN option_m int)
BEGIN

	DECLARE week_count int default 0;
	DECLARE comt,user_comt int default 0;
	DECLARE new_rank_change_point int default 20170401;
	DECLARE new_rank_change_point_time int default 0;
	DECLARE new_rank_change_tag int default 0;
	DECLARE err int default 0;	
	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET err =1;
	
		set new_rank_change_point_time = date_format(now(),'%Y%m%d');
		IF new_rank_change_point_time < 20170401 THEN		
			/*使用老的方法 >=$75 */
			set new_rank_change_tag = 0;		
		ELSE		
			/*使用新的方法 >=$100 */
			set new_rank_change_tag = 1;
		END IF;
		
		SELECT week_share_bonus INTO week_count from user_comm_stat WHERE uid = user_id;
		
		IF week_count = 0 THEN
			set user_comt = 1;
		ELSE
			set user_comt = 0; 
		END IF;		
		
		IF option_m = 0 THEN
			/*判断用户职称是否变更*/
			IF old_sale_rank = 0 and new_sale_rank >=1 THEN
				set comt = 1;
			END IF;
		ELSEIF option_m = 1 THEN
			/*判断用户是否升级*/
			IF old_sale_rank > 3 and new_sale_rank <=3 THEN	
				set comt = 1;
			END IF;
		ELSE 
			set comt = 1;
		END IF;	
	
		IF comt = 1 and user_comt =1 THEN
		
			START TRANSACTION;
			
			IF new_rank_change_tag = 1 THEN
			
				/*2017-4-1起执行的新规定*/
				set @sql_str = CONCAT("insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight
				) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b
				on a.uid=b.id where a.uid = ",user_id," and a.year_month = DATE_FORMAT(curdate(),'%Y%m') and b.user_rank = 1 and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000");
			
			ELSE
			
				/*老的发奖机制*/
				set @sql_str = CONCAT("insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight
				) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b
				on a.uid=b.id where a.uid = ",user_id," and a.year_month = DATE_FORMAT(curdate(),'%Y%m') and b.user_rank in(3,2,1)
				and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=7500");
			END IF;
			
						
			PREPARE stmt FROM @sql_str;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;									
						
			IF err = 1 THEN
				ROLLBACK;insert into error_log(content) values('每月周团队分红发奖列表失败.');
			ELSE		
				COMMIT;
			END IF;
			
		END IF;


END;
