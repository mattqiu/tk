delimiter //
drop PROCEDURE if exists new_daily_bonus_list //
create PROCEDURE `new_daily_bonus_list`()
BEGIN
#[每月初生成新的日分红发奖列表]

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
DELETE from daily_bonus_qualified_list where qualified_day<DATE_FORMAT(now(),'%Y%m%d');

#筛选出本月拿奖人员并插入发奖列表（上月订单合格的会员）
insert ignore into daily_bonus_qualified_list(uid) select a.uid from users_store_sale_info_monthly a left join users b 
on a.uid=b.id where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=2500 
and (a.sale_amount>=10000 or b.user_rank<>4);
#筛选出本月拿奖人员并插入发奖列表（上月推荐人合格的会员）
insert ignore into daily_bonus_qualified_list(uid) select uid from stat_intr_mem_month where `year_month`=
DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
and (member_bronze_num>0 or member_silver_num>0 or member_platinum_num>0 or member_diamond_num>0) and uid<>1380100217;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into logs_cron(content) values('[Fail] 每月初生成新的日分红发奖列表.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的日分红发奖列表.');
END IF;
SET autocommit=1;

END//
delimiter ;