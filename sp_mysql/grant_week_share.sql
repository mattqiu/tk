delimiter //
drop PROCEDURE if exists grant_week_share //
create PROCEDURE grant_week_share(in istest tinyint(1))
BEGIN
################发放每周团队销售分红######################

DECLARE t_error INT DEFAULT 0;#定义事务相关参数
DECLARE totalProfit_wh BIGINT DEFAULT 0;#沃好利润，单位：分。
DECLARE totalProfit_tps BIGINT DEFAULT 0;#tps利润，单位：分。
DECLARE totalProfit_1di BIGINT DEFAULT 0;#1direct利润，单位：分。
DECLARE totalProfit BIGINT DEFAULT 0;#用于发奖的总利润，单位：分。
DECLARE tp_sale_amount_weight BIGINT DEFAULT 0;#用于销售额权重的利润，单位：分。
DECLARE tp_sale_rank_weight BIGINT DEFAULT 0;#用于职称权重的利润，单位：分。
DECLARE tp_store_rank_weight BIGINT DEFAULT 0;#用于店铺权重的利润，单位：分。
DECLARE tp_share_point_weight BIGINT DEFAULT 0;#用于分红点权重的利润，单位：分。
DECLARE tn_sale_amount_weight BIGINT DEFAULT 0;#销售额总权重。
DECLARE tn_sale_rank_weight BIGINT DEFAULT 0;#职称总权重。
DECLARE tn_store_rank_weight BIGINT DEFAULT 0;#店铺总权重。
DECLARE tn_share_point_weight BIGINT DEFAULT 0;#分红点总权重。
DECLARE comm_amount int DEFAULT 0;#需发放的奖金，单位：分。
DECLARE n_week_time varchar(30);
DECLARE s_week_time varchar(30);
DECLARE li_uid int;#拿奖人id
DECLARE li_sale_amount_weight int;#拿奖人销售额权重点
DECLARE li_sale_rank_weight tinyint;#拿奖人职称权重点
DECLARE li_store_rank_weight tinyint;#拿奖人店铺等级权重点
DECLARE li_share_point_weight int;#拿奖人分红点权重点
DECLARE done int default 0;#控制主循环的结束符
DECLARE cur_list CURSOR FOR select uid,sale_amount_weight,sale_rank_weight,store_rank_weight,share_point_weight from
week_share_qualified_list WHERE create_time < CURDATE();#取出发奖人员信息列表（游标）
DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;#定义循环hander
DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;#定义事务hander

#统计上一周的总利润额，每总权重的金额
set n_week_time = subdate(curdate(),date_format(curdate(),'%w')-1); /**本周*/
set s_week_time = subdate(date_add( n_week_time, interval -2 day) ,date_format( date_add(n_week_time, interval -2 day),'%w' )-1); /**上周*/


select round(if(sum(order_profit_usd) is not null,sum(order_profit_usd),0)*100) into totalProfit_wh from mall_orders 
where create_time>=s_week_time and create_time<n_week_time;

select round(if(sum(order_profit_usd) is not null,sum(order_profit_usd),0)*100) into totalProfit_1di from 
one_direct_orders where create_time>=s_week_time and create_time<n_week_time;

select if(sum(order_profit_usd) is not null,sum(order_profit_usd),0) into totalProfit_tps from trade_orders 
where pay_time>=s_week_time and pay_time<n_week_time and order_prop in('0','2') and `status` in('3','4','5','6');


set totalProfit = round((totalProfit_wh+totalProfit_1di+totalProfit_tps)*0.20);
set tp_sale_amount_weight = round(totalProfit*0.1);
set tp_sale_rank_weight = round(totalProfit*0.78);
set tp_store_rank_weight = round(totalProfit*0.1);
set tp_share_point_weight = round(totalProfit*0.02);

#统计每种权重下总权重点
select sum(sale_amount_weight) into tn_sale_amount_weight from week_share_qualified_list;
select sum(sale_rank_weight) into tn_sale_rank_weight from week_share_qualified_list;
select sum(store_rank_weight) into tn_store_rank_weight from week_share_qualified_list;
select sum(share_point_weight) into tn_share_point_weight from week_share_qualified_list;

set autocommit=0;#定义不自动提交，为事务开启
set done=0;
OPEN cur_list;
FETCH cur_list INTO li_uid,li_sale_amount_weight,li_sale_rank_weight,li_store_rank_weight,li_share_point_weight;
WHILE done=0 do

	select id into li_uid from users where id=li_uid and store_qualified=1;
	if done=0 THEN
		set comm_amount = round(tp_sale_amount_weight/tn_sale_amount_weight*li_sale_amount_weight + 
tp_sale_rank_weight/tn_sale_rank_weight*li_sale_rank_weight + 
tp_store_rank_weight/tn_store_rank_weight*li_store_rank_weight +
tp_share_point_weight/tn_share_point_weight*li_share_point_weight);
		if istest=1 THEN
			call ly_debug(concat(li_uid,':',comm_amount/100));
		ELSE
			call grant_comm_single(li_uid,comm_amount,25,'');
		end if;
	ELSE
		set done=0;
	end if;

	FETCH cur_list INTO li_uid,li_sale_amount_weight,li_sale_rank_weight,li_store_rank_weight,li_share_point_weight;
END WHILE;
CLOSE cur_list;#关闭游标
#------------循环结束--------------

/*事务结束*/
IF t_error = 1 THEN
 	ROLLBACK;insert into error_log(content) values('发放周团队销售分红失败.');
ELSE
 	COMMIT;insert into logs_cron(content) values('[Success] 发放周团队销售分红.');
END IF;
SET autocommit=1;

END;