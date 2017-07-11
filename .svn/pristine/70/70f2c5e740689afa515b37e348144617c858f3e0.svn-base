delimiter //
drop PROCEDURE if exists new_member_bonus //
CREATE PROCEDURE new_member_bonus(in page int,in pageSize int,in totalMoney BIGINT,
in totalWeight BIGINT)
BEGIN
/*局部变量的定义*/

#DECLARE totalMoney  BIGINT default 37996;
#DECLARE totalWeight BIGINT DEFAULT 625720;
#DECLARE page int ;
#DECLARE pageSize int DEFAULT 1000;

DECLARE t_money BIGINT DEFAULT 0 ;#每个用户获取到的金额 单位美分
DECLARE itemStart int default (page-1)*pagesize;

DECLARE done TINYINT default 0 ;
DECLARE t_uid BIGINT ;
DECLARE t_qualified_day int ;
DECLARE t_end_day int;
DECLARE now_time int;

DECLARE t_error INT DEFAULT 0;#定义事务相关参数
DECLARE t_weight BIGINT DEFAULT 0;


declare users CURSOR FOR SELECT uid,bonus_weight FROM `tps138.com`.new_member_bonus WHERE end_day >= DATE_FORMAT(now(),'%Y%m%d') and qualified_day <DATE_FORMAT(now(),'%Y%m%d') ORDER BY uid  limit itemStart,pageSize;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;#定义事务hander
set autocommit=0;#定义不自动提交，为事务开启
OPEN users;
call ly_debug(concat('分页发放新用户分红成功。 curpage:',page,'; pagesize:',pageSize,'; totalSharAmount:',totalMoney,'; totalWeight:',totalWeight));
START TRANSACTION;
FETCH users INTO t_uid,t_weight;
    WHILE done = 0 DO
				if done = 0 THEN
					set t_money = floor(totalMoney * t_weight/totalWeight);
					SELECT t_money;
					call grant_comm_single(t_uid,t_money,26,'');
				END IF;
			FETCH users INTO t_uid,t_weight;
END WHILE;

CLOSE users;
SELECT t_error;
/*事务结束*/
IF t_error = 1 THEN
	ROLLBACK;
	insert into error_log(content) values(concat('分页发放新用户分红失败。 curpage:',page,'; pagesize:',pageSize,'; totalSharAmount:',totalMoney,'; totalWeight:',totalWeight));
  call ly_debug(concat('分页发放新用户分红失败。 curpage:',page,'; pagesize:',pageSize,'; totalSharAmount:',totalMoney,'; totalWeight:',totalWeight));
ELSE

	COMMIT;
END IF;
SET autocommit=1;



END//
delimiter ;




