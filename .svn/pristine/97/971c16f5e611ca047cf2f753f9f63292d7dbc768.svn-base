delimiter //
drop procedure if exists add_to_daily_elite_qualified_list //
create PROCEDURE `add_to_daily_elite_qualified_list`(in qualified_uid int)
BEGIN
##############[添加到精英日分红合格列表]##################

DECLARE u_pro_set_amount int default 0;#套餐销售额
DECLARE u_sale_amount int DEFAULT 0;#普通订单销售额
DECLARE exit_qualified_day int DEFAULT -1;#表中已存在的合格日期

/*事务开始*/
DECLARE t_error int default 0;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;
set autocommit=0;

#获取用户当前月套餐销售额
select pro_set_amount into u_pro_set_amount FROM stat_intr_mem_month where uid=qualified_uid 
and `year_month`=DATE_FORMAT(now(),'%Y%m');

#获取用户当前月普通订单销售额
select sale_amount into u_sale_amount from users_store_sale_info_monthly where uid=qualified_uid 
and `year_month`=DATE_FORMAT(now(),'%Y%m');

#插入合格列表
select qualified_day into exit_qualified_day from daily_bonus_elite_qualified_list where uid=qualified_uid;
if exit_qualified_day=-1 THEN
	#如果不存在记录，则插入
	insert into daily_bonus_elite_qualified_list(uid,bonus_shar_weight,qualified_day) 
values(qualified_uid,u_pro_set_amount+u_sale_amount,DATE_FORMAT(now(),'%Y%m%d'));
elseif exit_qualified_day=DATE_FORMAT(now(),'%Y%m%d') THEN
	#如果已经存在记录，并且是同一天的，用新记录替换旧记录
	replace into daily_bonus_elite_qualified_list(uid,bonus_shar_weight,qualified_day) 
values(qualified_uid,u_pro_set_amount+u_sale_amount,DATE_FORMAT(now(),'%Y%m%d'));
end if;

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values(concat('加入精英日分红合格列表失败，uid:',qualified_uid));
ELSE
	COMMIT;
END IF;
SET autocommit=1;

END//
delimiter ;