delimiter //
drop PROCEDURE if exists new_week_share_list;
create PROCEDURE `new_week_share_list`()
BEGIN
################每月初生成新的周团队分红发奖列表######################

/*事务开始*/
DECLARE stime varchar(30);
DECLARE autocommit int default 0;
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
set stime = date_sub(date_sub(date_format(now(),'%y-%m-%d'),interval extract(day from now())-1 day),interval 0 month);
/*truncate week_share_qualified_list;*/
delete from week_share_qualified_list where create_time <stime;


insert ignore into week_share_qualified_list(uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight
) select a.uid,a.sale_amount,b.sale_rank*b.sale_rank,if(b.user_rank=3,1,if(b.user_rank=1,3,2)),round(b.profit_sharing_point*100) from users_store_sale_info_monthly a left join users b 
on a.uid=b.id where a.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and b.user_rank =1 
and b.sale_rank in(1,2,3,4,5) and a.sale_amount>=10000;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values('每月初生成新的周团队分红发奖列表失败.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的周团队分红发奖列表.');
END IF;
SET autocommit=1;

END