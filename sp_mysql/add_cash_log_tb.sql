delimiter //
drop PROCEDURE if exists add_cash_account_log_tb //
CREATE  PROCEDURE add_cash_account_log_tb(IN user_uid int, IN user_amount int, IN item_type int,in order_id varchar(60),in cs_time VARCHAR(30),in related_uids int)
BEGIN

DECLARE cur_cash_tb_name varchar(60);
DECLARE tab_postfix int;
DECLARE index_uid int default 1382;
DECLARE table_name CHAR(60);
 DECLARE table_name_new,table_name_news CHAR(60);

IF  order_id is NULL THEN
SET order_id='';
END IF;

IF  cs_time is NULL THEN
SET cs_time='';
END IF;

IF cs_time = '' THEN
	#生成资金变动报表记录
	IF tab_postfix > 1381 THEN
		set cur_cash_tb_name = concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'),'_',tab_postfix);/*资金变动表名*/
	ELSE
		set cur_cash_tb_name = concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'));#资金变动表名
	END if;
	set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id,related_uid)
	values(',user_uid,',',item_type,',',user_amount,",'",order_id,"',",related_uids,')');
ELSE
	#生成资金变动报表记录
	IF tab_postfix > 1381 THEN
		set cur_cash_tb_name= concat('cash_account_log_',DATE_FORMAT(cs_time,'%Y%m'),'_',tab_postfix);/*资金变动表名*/
	ELSE
		set cur_cash_tb_name = concat('cash_account_log_',DATE_FORMAT(cs_time,'%Y%m'));#资金变动表名
	END if;
	SET @STMT :=CONCAT('insert into ',cur_cash_tb_name,'(uid,item_type,amount,create_time,order_id,related_uid)
	values(',user_uid,',',item_type,',',user_amount,",'",cs_time,"','",order_id,"',",related_uids,')');
END IF;
PREPARE STMT FROM @STMT;
EXECUTE STMT;
END;