delimiter //
drop PROCEDURE if exists do_daily_elite_shar_page //
create PROCEDURE do_daily_elite_shar_page(in curpage int,in pagesize int,in totalSharAmount BIGINT,
in totalWeight BIGINT)
BEGIN
################分页发放精英日分红######################

DECLARE t_error INT DEFAULT 0;#定义事务相关参数
DECLARE comm_amount int;#需发放的精英分红奖金，单位：分。
DECLARE li_uid int;#拿奖人id
DECLARE li_bonus_shar_weight int;#拿奖人权重点
DECLARE done int default 0;#控制主循环的结束符
DECLARE itemStart int default (curpage-1)*pagesize;
DECLARE cur_list CURSOR FOR select uid,bonus_shar_weight from daily_bonus_elite_qualified_list where qualified_day<
DATE_FORMAT(now(),'%Y%m%d') ORDER BY uid limit itemStart,pagesize;#按分页取出要发奖的人员列表（游标）
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;#定义循环hander
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;#定义事务hander

set autocommit=0;#定义不自动提交，为事务开启
OPEN cur_list;
FETCH cur_list INTO li_uid,li_bonus_shar_weight;
WHILE done=0 do

	select id into li_uid from users where id=li_uid and store_qualified=1;
	if done=0 THEN
		set comm_amount = round(totalSharAmount/totalWeight*li_bonus_shar_weight);
		call grant_comm_single(li_uid,comm_amount,24,'');
	ELSE
		set done=0;
	end if;

	FETCH cur_list INTO li_uid,li_bonus_shar_weight;
END WHILE;
CLOSE cur_list;#关闭游标
#------------循环结束--------------

/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;insert into error_log(content) values(concat('分页发放精英日分红失败。 curpage:',curpage,'; pagesize:',
pagesize,'; totalSharAmount:',totalSharAmount,'; totalWeight:',totalWeight));
ELSE
	COMMIT;
END IF;
SET autocommit=1;

END//
delimiter ;