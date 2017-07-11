delimiter //
drop PROCEDURE if exists new_month_group_share_list //
create PROCEDURE `new_month_group_share_list`()
BEGIN
################每月初生成新的月团队组织分红发奖列表,替换原先的new_month_group_share_list存储过程，实行4月份新政 ######################

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
truncate month_group_share_list;

insert ignore into month_group_share_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight) select a.uid,
a.sale_amount,(b.sale_rank+1)*(b.sale_rank+1),if(b.user_rank=5,1,if(b.user_rank=3,2,if(b.user_rank=2,3,4) ) ) from 
users_store_sale_info_monthly a left join users b 
on a.uid=b.id where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and b.user_rank=1 and b.sale_rank in (2,3,4,5) and a.sale_amount>=10000;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values('每月初生成新的月团队组织分红发奖列表失败.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的月团队组织分红发奖列表.');
END IF;
SET autocommit=1;

END;