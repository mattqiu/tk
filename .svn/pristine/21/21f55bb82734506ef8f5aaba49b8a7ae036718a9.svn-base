delimiter //
drop PROCEDURE if exists stat_user_point_monthly //
create PROCEDURE `stat_user_point_monthly`()
BEGIN
#[每月初统计用户分红点]

/*事务开始*/
DECLARE t_error int default 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月的数据
TRUNCATE table users_profit_sharing_point_last_month;

#统计生成本月的数据
insert into users_profit_sharing_point_last_month(uid,profit_sharing_point) select id,round(profit_sharing_point*100) 
from users where profit_sharing_point!=0;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into logs_cron(content) values('[Fail] 每月初统计用户分红点.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初统计用户分红点.');
END IF;
SET autocommit=1;

END//
delimiter ;