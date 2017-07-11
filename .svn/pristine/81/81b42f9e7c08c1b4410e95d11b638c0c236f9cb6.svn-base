delimiter //
drop PROCEDURE if exists new_daily_bonus_elite_list //
create PROCEDURE `new_daily_bonus_elite_list`()
BEGIN
################每月初生成新的精英日分红发奖列表######################

/*事务开始*/
DECLARE t_error INT DEFAULT 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#清空上个月拿奖的人员
delete from daily_bonus_elite_qualified_list where qualified_day<DATE_FORMAT(now(),'%Y%m%d');

#-----------筛选出本上月推荐人合格的会员并插入发奖列表,同时生成相应的销售额（套餐+零售）
#筛选人，然后权重字段写入相应套餐销售额
insert ignore into daily_bonus_elite_qualified_list(uid,bonus_shar_weight) select uid,pro_set_amount 
from stat_intr_mem_month where `year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
and pro_set_amount>0 and uid<>1380100217;
#把普通订单销售额更新加入到权重字段
update daily_bonus_elite_qualified_list a,users_store_sale_info_monthly b set a.bonus_shar_weight=a.bonus_shar_weight
+b.sale_amount where b.`year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') and a.uid=b.uid 
and a.qualified_day=0;

#------------筛选出上月零售订单合格的会员并插入发奖列表，同时生成相应的销售额（只有零售）
insert ignore into daily_bonus_elite_qualified_list(uid,bonus_shar_weight) select uid,sale_amount 
from users_store_sale_info_monthly where `year_month`=DATE_FORMAT(date_add(curdate(),interval -1 month),'%Y%m') 
and sale_amount>=25000;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values('每月初生成新的精英日分红发奖列表失败.');
ELSE
	COMMIT;insert into logs_cron(content) values('[Success] 每月初生成新的精英日分红发奖列表.');
END IF;
SET autocommit=1;

END//
delimiter ;