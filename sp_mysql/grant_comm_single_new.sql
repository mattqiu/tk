delimiter// 
drop procedure if exists grant_comm_single_new//
create procedure grant_comm_single_new(in comm_uid int,in comm_amount int,in comm_item_type TINYINT,in order_id VARCHAR(35),in cs_time VARCHAR(30))
BEGIN
/**********新的发放佣金（单个）2017-2-9 *********/

DECLARE cur_cash_tb_name varchar(30); 
DECLARE f_comm_stat VARCHAR(35);
DECLARE comm_to_point int default 0;/*佣金自动转分红点的金额（单位：分）*/
DECLARE u_proportion int default 0;/*佣金自动转分红点比例(百分比的分子，整型)*/

IF  order_id is NULL THEN
SET order_id='';
END IF;

IF cs_time = '' THEN
	set cur_cash_tb_name = concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'));#资金变动表名
	#生成资金变动报表记录
	set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id) 
	values(',comm_uid,',',comm_item_type,',',comm_amount,",'",order_id,"')");
ELSE
	#生成资金变动报表记录
	set cur_cash_tb_name = concat('cash_account_log_',DATE_FORMAT(cs_time,'%Y%m'));#资金变动表名
	SET @STMT :=CONCAT('insert into ',cur_cash_tb_name,'(uid,item_type,amount,create_time,order_id) 
	values(',comm_uid,',',comm_item_type,',',comm_amount,",'",cs_time,"','",order_id,"')");
END IF;

PREPARE STMT FROM @STMT;
EXECUTE STMT;

#用户奖金统计
case comm_item_type
	when 24 then
		set f_comm_stat='daily_bonus_elite';
	when 26 then # m by brady 新用户分红
		set f_comm_stat='new_member_bonus';
	when 2 THEN
		set f_comm_stat='138_bonus';
	when 7 THEN
		set f_comm_stat='week_bonus';
	when 25 THEN
		set f_comm_stat='week_share_bonus';
	when 1 THEN
		set f_comm_stat='month_group_share';
	else set f_comm_stat='';
end case;
if f_comm_stat<>'' THEN
	set @STMT :=concat('insert into user_comm_stat(uid,',f_comm_stat,') values(',comm_uid,',',comm_amount,') on DUPLICATE KEY 
UPDATE ',f_comm_stat,'=',f_comm_stat,'+',comm_amount);
	PREPARE STMT FROM @STMT;
	EXECUTE STMT;
end if;

#判断是否设置了自动转分红点比例
select round(proportion) into u_proportion from profit_sharing_point_proportion where uid=comm_uid and proportion_type=1;
if u_proportion>0 THEN#有自动转分红比例

	set comm_to_point = ROUND(comm_amount * u_proportion/100);
	if comm_to_point>0 then

		#更新用户表分红点
		update users set profit_sharing_point=profit_sharing_point+comm_to_point/100 where id=comm_uid;

		#生成相应资金变动报表记录（佣金转分红点）
		set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount) 
values(',comm_uid,',17,',-1*comm_to_point,')');
		PREPARE STMT FROM @STMT;
		EXECUTE STMT;

		#生成分红变动记录
		insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(comm_uid,1,
comm_to_point/100,comm_to_point/100,unix_timestamp());
	end if;
end if;
		
#更新用户现金池
update users set amount=amount+(comm_amount-comm_to_point)/100 where id=comm_uid;

END;
//
