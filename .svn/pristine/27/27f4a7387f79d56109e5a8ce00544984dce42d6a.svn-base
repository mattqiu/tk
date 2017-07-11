delimiter //
drop PROCEDURE if exists new_138_bonus_list //
create PROCEDURE `new_138_bonus_list`()
BEGIN
#[每月初生成新的138发奖列表]

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
DELETE from user_qualified_for_138 where create_time<DATE_FORMAT(now(),'%Y-%m-%d');

#筛选出本月拿奖人员并插入发奖列表（上月订单合格的会员）
insert ignore into user_qualified_for_138(user_id,user_rank,sale_amount) select a.uid,b.user_rank,a.sale_amount from 
users_store_sale_info_monthly a left join users b on a.uid=b.id where a.`year_month`=
DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.sale_amount>=5000 and b.user_rank in(1,2,3,5);

#更新合格表中的x,y
update user_qualified_for_138 a,user_coordinates b set a.x=b.x,a.y=b.y where a.user_id=b.user_id and a.x=0 and a.y=0;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into logs_cron(content) values('[Fail] 每月初生成新的138发奖列表.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的138发奖列表.');
END IF;
SET autocommit=1;

END//
delimiter ;