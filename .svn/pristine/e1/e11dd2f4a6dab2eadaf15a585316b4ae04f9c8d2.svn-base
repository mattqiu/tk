delimiter //
drop PROCEDURE if exists new_order_trigger //
CREATE  PROCEDURE new_order_trigger()
BEGIN

/**用于替换原先的new_order_trigger 替换原因，新的实现分红表的数据分表**/

DECLARE t_run_cycle_num smallint(5) default -1;

DECLARE order_ids_del text;

DECLARE f_oid VARCHAR(40);

DECLARE f_uid int(10);

DECLARE f_order_amount_usd int(10);

DECLARE f_order_profit_usd int(10);

DECLARE f_order_year_month mediumint(6);

DECLARE u_status TINYINT(1) default 1; /*用户账户状态（是否休眠）*/

DECLARE u_monthfee_pool decimal(14,2) default 0.00;/*用户月费池金额*/

DECLARE u_store_qualified TINYINT(1);/*用户是否合格*/

DECLARE u_user_rank TINYINT(3);/*用户等级*/

DECLARE u_sale_rank TINYINT(3);/*用户职称*/

DECLARE cur_sale_amount int;/*用户当前月的销售额*/

DECLARE total_sale_amount int(10);/*用户总销售额*/

DECLARE comm_store_owner int;/*订单的店主提成*/

DECLARE cur_year_month MEDIUMINT(6) default DATE_FORMAT(now(),'%Y%m');/*当前的年月，如201609*/

DECLARE u_proportion decimal(5,2);/*用户的佣金自动转分红比例*/

DECLARE comm_to_point int;/*个人店铺提成中自动转分红点的金额*/

DECLARE u_parent_ids longtext;/*用户的父id集合*/

DECLARE i int(10);/*用来控制循环遍历的次数*/

DECLARE done_parent_ids int(1);/*控制父id循环的结束符*/

DECLARE p_id varchar(10);/*父id*/

DECLARE p_user_rank,p_store_qualified tinyint(1);/*父id的等级,是否合格*/

DECLARE comm_parent int;/*团队提成金额*/

DECLARE p_qualified_child_num int;/*父id直推的合格分店数*/

DECLARE p_enable_floor_num TINYINT(2);/*父id可以拿到的层数*/

DECLARE u_reward_id int(10);/*奖励分红点id*/

DECLARE u_amount_profit_sharing_comm decimal(14,2);/*用户拿的日分红统计额*/

DECLARE h_daily_bonus_elite int;/*用户历史拿过的精英日分红金额（单位：分）*/

DECLARE h_138_bonus int;/*用户历史拿过的138分红金额（单位：分）*/

DECLARE h_week_bonus int;/*用户历史拿过的周领导对等奖金额（单位：分）*/

DECLARE u_x int;/*用户的x坐标*/

DECLARE u_y int;/*用户的y坐标*/

DECLARE group_floor_num TINYINT DEFAULT 3;/*团队提成的层数*/

DECLARE t_error INT DEFAULT 0;/*定义事务参数*/

DECLARE done int default 0;/*控制主循环的结束符*/

DECLARE cur_queue CURSOR FOR SELECT oid,uid,order_amount_usd,order_profit_usd,order_year_month 

FROM new_order_trigger_queue LIMIT 500;

DECLARE CONTINUE HANDLER FOR NOT FOUND set done=1;/*定义循环handel*/

DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;/*定义事务handel*/



/*DECLARE cur_cash_tb_name varchar(60) default concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m')); */ /*资金变动表名*/



##单任务控制--检查本任务是否已经有在执行

select run_cycle_num into t_run_cycle_num from single_task_control where task_name='new_order_trigger';

if t_run_cycle_num=-1 THEN



	##单任务控制--没有正在执行的任务则插入一条任务记录

	insert into single_task_control(task_name) values('new_order_trigger');



	##-----begin业务逻辑

	set done=0;

	set autocommit=0;#定义不自动提交，为事务开启

	OPEN cur_queue;

	FETCH cur_queue INTO f_oid,f_uid,f_order_amount_usd,f_order_profit_usd,f_order_year_month;

	WHILE done<>1 do



		#----------更新用户店铺总业绩

		insert into users_store_sale_info(uid,sale_amount) values(f_uid,f_order_amount_usd) on DUPLICATE KEY 

	UPDATE sale_amount=sale_amount+f_order_amount_usd;

		

		#----------判断用户是否正在补单中，根据结果更新用户店铺月业绩

		SELECT order_year_month into f_order_year_month from withdraw_task where uid=f_uid and `status`=1 limit 1;

		if done=1 then#没有找到补单的记录

			set done=0;

		else#正在补单中，则执行补单操作

			delete from withdraw_task where uid=f_uid and `status`=1 and sale_amount_lack<=f_order_amount_usd;

			update withdraw_task set sale_amount_lack=sale_amount_lack-f_order_amount_usd where uid=f_uid and `status`=1;



			#根据订单号更新相应订单表中的业绩年月

			case substring(f_oid,1,2)

				when 'W-' THEN

					update mall_orders set score_year_month=f_order_year_month where order_id=f_oid;

				when 'O-' THEN

					update one_direct_orders set score_year_month=f_order_year_month where order_id=f_oid;

				when 'A-' THEN

					update walmart_orders set score_year_month=f_order_year_month where order_id=f_oid;

				else

					update trade_orders set score_year_month=f_order_year_month where order_id=f_oid;

			end case;

			

		end if;

		#更新月统计业绩

		insert into users_store_sale_info_monthly(uid,`year_month`,sale_amount) values(f_uid,f_order_year_month,

	f_order_amount_usd) on DUPLICATE KEY UPDATE sale_amount=sale_amount+f_order_amount_usd;



		#---------获取用户当前月销售额

		set cur_sale_amount=0;#初始化当前月销售额

		SELECT sale_amount into cur_sale_amount from users_store_sale_info_monthly where uid=f_uid and `year_month`=

	cur_year_month;

		if done=1 then set done=0;end if;



		#---------获取用户总销售额

		set total_sale_amount=0;#初始化当前月销售额

		SELECT sale_amount into total_sale_amount from users_store_sale_info where uid=f_uid;

		if done=1 then set done=0;end if;



		#---------获取用户相关信息

		select user_rank,sale_rank,`status`,month_fee_pool,left(parent_ids,109),store_qualified,amount_profit_sharing_comm 

	into u_user_rank,u_sale_rank,u_status,u_monthfee_pool,u_parent_ids,u_store_qualified,u_amount_profit_sharing_comm 

	from users where id=f_uid;



		#---------处理订单抵月费活动

		if u_status=2 THEN

			select id from users_april_plan where uid=f_uid and `type`=1;

			if done=1 then

				set done=0;

			else

				#用户休眠且参加了活动，开始订单抵月费处理

				insert into users_april_plan_order(uid,order_id) values(f_uid,f_oid);#记录活动订单信息

				insert into trade_order_remark_record(order_id,`type`,remark,admin_id) values(f_oid,1,'System: The retail orders 

	for "the Initiatives to waive past due monthly fee(s)" can\'t be cancelled or returned',0);#记录订单备注1

				insert into trade_order_remark_record(order_id,`type`,remark,admin_id) values(f_oid,2,'System: The retail orders 

	for "the Initiatives to waive past due monthly fee(s)" can\'t be cancelled or returned',0);#记录订单备注2

				if cur_sale_amount>=5000 then 

					#满了50美金，抵扣月费

					update users set `status`=1,store_qualified=1 where id=f_uid;#恢复欠月费状态为正常

					delete from users_month_fee_fail_info where uid=f_uid;#清楚扣月份失败记录

					insert into users_status_log(uid,front_status,back_status,`type`,create_time) 

	values(f_uid,2,1,2,unix_timestamp());#记录用户状态变更

					insert into month_fee_change(user_id,old_month_fee_pool,month_fee_pool,cash,create_time,`type`) values(f_uid,

	u_monthfee_pool,u_monthfee_pool,0.00,now(),8);#生成月费池变动记录

					delete from users_april_plan where uid=f_uid;#从参加月费活动表中清除

				end if;

			end if;

		end if;



		#---------发放订单对应的个人店铺销售提成（店主提成）

		set comm_store_owner =ROUND(f_order_profit_usd*0.2);

		if comm_store_owner>0 then



			#生成资金变动报表记录（个人店铺提成）

			/* 费除掉，使用下面存储过程进行添加
			set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id) 

	values(',f_uid,',5,',comm_store_owner,",'",f_oid,"')");

			PREPARE STMT FROM @STMT;

			EXECUTE STMT;
			*/
			call add_cash_account_log_tb(f_uid,comm_store_owner,5,f_oid,'',0);


			#判断是否设置了自动转分红点比例

			set comm_to_point=0;

			set u_proportion=0.00;

			select proportion/100 into u_proportion from profit_sharing_point_proportion where uid=f_uid and proportion_type=1;

			if u_proportion>0.00 THEN



				#有自动转分红比例，执行佣金自动转分红

				set comm_to_point = ROUND(comm_store_owner * u_proportion);

				if comm_to_point>0 then



					#更新用户表分红点

					update users set profit_sharing_point=profit_sharing_point+comm_to_point/100,

	profit_sharing_point_from_sale=profit_sharing_point_from_sale+comm_to_point/100 where id=f_uid;



					#生成相应资金变动报表记录（佣金转分红点）
					/* 费除掉使用存储过程进行存储
					set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount) 

	values(',f_uid,',17,',-1*comm_to_point,')');

					PREPARE STMT FROM @STMT;

					EXECUTE STMT;
					*/
					call add_cash_account_log_tb(f_uid,-1*comm_to_point,17,'','',0);

					#生成分红变动记录

					insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(f_uid,1,

	comm_to_point/100,comm_to_point/100,unix_timestamp());

				end if;

			else

				set done=0;

			end if;

			

			#更新用户现金池，个人店铺销售提成统计

			update users set amount=amount+(comm_store_owner-comm_to_point)/100,amount_store_commission=

	amount_store_commission+comm_store_owner/100 where id=f_uid;

		end if;



		#---------发放团队销售提成

		set i=0;set done_parent_ids=0;

		WHILE (i<group_floor_num and done_parent_ids=0) do

			set p_id = substring(u_parent_ids,i*11+1,10);

			if (p_id<>'1380100217' and p_id!='') then

				select user_rank,store_qualified into p_user_rank,p_store_qualified from users where id=p_id;

				if done=0 THEN#parent_id在数据库中存在



					set comm_parent=0;#初始化团队提成金额

					if i=0 THEN#第一代直接上级店铺无条件发放团队提成



						#根据等级计算提成

						if p_user_rank=1 then set comm_parent=round(f_order_profit_usd*0.2);

						elseif p_user_rank=2 then set comm_parent=round(f_order_profit_usd*0.15);

						elseif p_user_rank=3 then set comm_parent=round(f_order_profit_usd*0.1);

						elseif p_user_rank=5 then set comm_parent=round(f_order_profit_usd*0.07);

						else set comm_parent=round(f_order_profit_usd*0.05);

						end if;

					ELSE#非第一代，要判断是否有资格拿(自己是否合格，层数是否满足)

						

						if p_store_qualified=1 then#合格了才能继续



							#查询该parent_id开了几个合格分店,根据分店和等级计算可拿的层数

							select count(*) into p_qualified_child_num from users where parent_id=p_id and user_rank<>4 

		and store_qualified=1;

							case p_user_rank

								when 1 then

									if p_qualified_child_num>=3 then set p_enable_floor_num=10;

									elseif p_qualified_child_num=2 then set p_enable_floor_num=6;

									elseif p_qualified_child_num=1 then set p_enable_floor_num=3;

									else set p_enable_floor_num=1;end if;

								when 2 THEN

									if p_qualified_child_num>=2 then set p_enable_floor_num=6;

									elseif p_qualified_child_num=1 then set p_enable_floor_num=3;

									else set p_enable_floor_num=1;end if;

								when 3 THEN

									if p_qualified_child_num>=1 then set p_enable_floor_num=3;

									else set p_enable_floor_num=1;end if;

								when 5 THEN

									if p_qualified_child_num>=1 then set p_enable_floor_num=2;

									else set p_enable_floor_num=1;end if;

								else set p_enable_floor_num=1;

							end case;

							if p_enable_floor_num>=i+1 then#层数合格时，拿奖

								

								#计算团队提成比例金额。

								case p_user_rank

									when 1 THEN

										if i=1 then set comm_parent=round(f_order_profit_usd*0.1);

										elseif i=2 then set comm_parent=round(f_order_profit_usd*0.05);

										else set comm_parent=round(f_order_profit_usd*0.02);end if;

									when 2 THEN

										if i=1 then set comm_parent=round(f_order_profit_usd*0.08);

										elseif i=2 then set comm_parent=round(f_order_profit_usd*0.05);

										else set comm_parent=round(f_order_profit_usd*0.02);end if;

									when 3 THEN

										if i=1 then set comm_parent=round(f_order_profit_usd*0.05);

										else set comm_parent=round(f_order_profit_usd*0.03);end if;

									else set comm_parent=round(f_order_profit_usd*0.05);

								end case;	

							end if;

						end if;

					end if;



					#发放团队提成

					if comm_parent>0 then



						#生成资金变动报表记录
						/* 费除掉，使用存储过程进行存储
						set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id,related_uid) 

				values(',p_id,',3,',comm_parent,",'",f_oid,"','",f_uid,"')");

						PREPARE STMT FROM @STMT;

						EXECUTE STMT;
						*/

						call add_cash_account_log_tb(p_id,comm_parent,3,f_oid,'',f_uid);

						#判断是否设置了自动转分红点比例

						set comm_to_point=0;

						set u_proportion=0.00;

						select proportion/100 into u_proportion from profit_sharing_point_proportion where uid=p_id and 

	proportion_type=1;

						if u_proportion>0.00 THEN



							#有自动转分红比例，执行佣金自动转分红

							set comm_to_point = ROUND(comm_parent * u_proportion);

							if comm_to_point>0 then



								#更新用户表分红点

								update users set profit_sharing_point=profit_sharing_point+comm_to_point/100,

				profit_sharing_point_from_sale=profit_sharing_point_from_sale+comm_to_point/100 where id=p_id;



								#生成相应资金变动报表记录（佣金转分红点）
								/* 费除掉 使用存储过程进行存储
								set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount) 

				values(',p_id,',17,',-1*comm_to_point,')');

								PREPARE STMT FROM @STMT;

								EXECUTE STMT;

								*/
								call add_cash_account_log_tb(p_id,-1*comm_to_point,17,'','',0);

								#生成分红变动记录

								insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(p_id,1,

				comm_to_point/100,comm_to_point/100,unix_timestamp());

							end if;

						else

							set done=0;

						end if;

						

						#更新用户现金池，提成统计

						update users set amount=amount+(comm_parent-comm_to_point)/100,team_commission=

				team_commission+comm_parent/100 where id=p_id;

					end if;

				ELSE#parent_id在数据库中不存在(find不到时done会被置为1)

					set done=0;

				end if;

			ELSE

				set done_parent_ids=1;

			end if;

			set i=i+1;

		END WHILE;



		#---------免费店铺满50美金合格，满100美金送100分红点。

		if u_user_rank=4 and total_sale_amount>=5000 THEN	

			if u_store_qualified=0 THEN

				update users set store_qualified=1 where id=f_uid;

			end if;

			if total_sale_amount>=10000 THEN

				select id into u_reward_id from users_sharing_point_reward where uid=f_uid limit 1;

				if done=1 then

					set done=0;

					insert into users_sharing_point_reward(uid,point,end_time) values(f_uid,100,

	DATE_ADD(DATE_FORMAT(now(),'%Y-%m-%d'),INTERVAL 15 MONTH));

				end if;

			end if;

		end if;



		#----------日分红第一次满足，加入发奖列表

		if u_amount_profit_sharing_comm=0 and total_sale_amount>=2500 then

			if u_user_rank<>4 or total_sale_amount>=10000 THEN

				INSERT IGNORE INTO daily_bonus_qualified_list(uid,qualified_day) VALUES (f_uid,DATE_FORMAT(now(),'%Y%m%d'));

			end if;

		end if;



		#查询出用户历史总共拿过的精英分红、138、周奖

		set h_daily_bonus_elite=0;

		set h_138_bonus=0;

		set h_week_bonus=0;

		SELECT daily_bonus_elite,138_bonus,week_bonus into h_daily_bonus_elite,h_138_bonus,h_week_bonus from user_comm_stat 

	where uid=f_uid;

		if done=1 then set done=0;end if;



		#----------第一次满足精英日分红，加入发奖列表

		if cur_sale_amount>=25000 and h_daily_bonus_elite=0 THEN

			call add_to_daily_elite_qualified_list(f_uid);

		end if;



		#----------第一次满足138分红，加入发奖列表

		if h_138_bonus=0 and cur_sale_amount>=5000 and u_user_rank in(1,2,3,5) THEN

			set u_x=0;set u_y=0;

			select x,y into u_x,u_y from user_coordinates where user_id=f_uid;

			if done=1 then set done=0;end if;

			if u_x>0 and u_y>0 THEN

				insert IGNORE into user_qualified_for_138(user_id,user_rank,sale_amount,x,y) 

	values(f_uid,u_user_rank,cur_sale_amount,u_x,u_y);

			end if;

		end if;



		#----------收集处理完的order id,以便统一从队列删除

		if(order_ids_del is null) then

			set order_ids_del=concat('\'',f_oid,'\'');

		else

			set order_ids_del = concat(order_ids_del,',','\'',f_oid,'\'');

		end if;

		

		FETCH cur_queue INTO f_oid,f_uid,f_order_amount_usd,f_order_profit_usd,f_order_year_month;

	END WHILE;

	CLOSE cur_queue;



	#从列队表中删除已处理的订单记录

	if order_ids_del is not null then

		set @STMT :=concat('delete from new_order_trigger_queue where oid in(',order_ids_del,')');

		PREPARE STMT FROM @STMT;

		EXECUTE STMT;

	end if;



	/*事务结束*/

	IF t_error = 1 THEN

		ROLLBACK;

	ELSE

		COMMIT;

	END IF;

	SET autocommit=1;

	##-------end业务逻辑



	##单任务控制--任务结束后，删除任务记录

	delete from single_task_control where task_name='new_order_trigger';

	

ELSE



	#单任务控制--有正在执行的任务

	if t_run_cycle_num<30 then

		update single_task_control set run_cycle_num=run_cycle_num+1 where task_name='new_order_trigger';

	ELSE

		delete from single_task_control where task_name='new_order_trigger';

	end if;

end if;



END;
