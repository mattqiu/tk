delimiter //
drop PROCEDURE if exists grant_team_sale_comm //
CREATE PROCEDURE grant_team_sale_comm(
IN p_uid int(10),IN p_oid char(19),IN p_order_profit int(11),IN grant_parent_ids char(32))
    SQL SECURITY INVOKER
BEGIN

/**用于替换原先的grant_team_sale_comm 替换原因，新的实现分红表的数据分表**/
################发放团队销售提成######################

#参数说明

#p_order_profit 用于发放团队提成的订单利润（分）

#grant_parent_ids 要发放团队提成的3代父id



DECLARE cur_cash_tb_name varchar(30) default concat('cash_account_log_',DATE_FORMAT(now(),'%Y%m'));#资金变动表名

DECLARE i int(10);#用来控制循环遍历的次数

DECLARE u_proportion decimal(5,2);#用户的佣金自动转分红比例

DECLARE comm_to_point int;#佣金自动转分红点的金额

DECLARE done_parent_ids int(1);#控制父id循环的结束符

DECLARE p_id varchar(10);#父id

DECLARE p_user_rank,p_store_qualified tinyint(1);#父id的等级,是否合格

DECLARE comm_parent int default 0;#团队提成金额(分)

DECLARE p_qualified_child_num int;#父id直推的合格分店数

DECLARE p_enable_floor_num TINYINT(2);#父id可以拿到的层数





set i=0;set done_parent_ids=0;

WHILE (i<3 and done_parent_ids=0) do

	set p_id = substring(grant_parent_ids,i*11+1,10);

	if (p_id<>'1380100217' and p_id!='') then

		select user_rank,store_qualified into p_user_rank,p_store_qualified from users where id=p_id;

		if p_user_rank is not null THEN#parent_id在数据库中存在



			set comm_parent=0;#初始化团队提成金额

			if i=0 THEN#第一代直接上级店铺无条件发放团队提成



				#根据等级计算提成

				if p_user_rank=1 then set comm_parent=round(p_order_profit*0.2);

				elseif p_user_rank=2 then set comm_parent=round(p_order_profit*0.15);

				elseif p_user_rank=3 then set comm_parent=round(p_order_profit*0.1);

				elseif p_user_rank=5 then set comm_parent=round(p_order_profit*0.07);

				else set comm_parent=round(p_order_profit*0.05);

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

								if i=1 then set comm_parent=round(p_order_profit*0.1);

								elseif i=2 then set comm_parent=round(p_order_profit*0.05);

								else set comm_parent=round(p_order_profit*0.02);end if;

							when 2 THEN

								if i=1 then set comm_parent=round(p_order_profit*0.08);

								elseif i=2 then set comm_parent=round(p_order_profit*0.05);

								else set comm_parent=round(p_order_profit*0.02);end if;

							when 3 THEN

								if i=1 then set comm_parent=round(p_order_profit*0.05);

								else set comm_parent=round(p_order_profit*0.03);end if;

							else set comm_parent=round(p_order_profit*0.05);

						end case;	

					end if;

				end if;

			end if;



			#发放团队提成

			if comm_parent>0 then



				#生成资金变动报表记录
				/* 屏蔽掉， 使用存储过程
				set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount,order_id,related_uid) 

		values(',p_id,',3,',comm_parent,",'",p_oid,"','",p_uid,"')");

				PREPARE STMT FROM @STMT;

				EXECUTE STMT;
				*/
				CALL add_cash_account_log_tb(p_id,comm_parent,3,p_oid,'',p_uid);

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

						/* 疲敝掉使用存错过程
						set @STMT :=concat('insert into ',cur_cash_tb_name,'(uid,item_type,amount) 

		values(',p_id,',17,',-1*comm_to_point,')');

						PREPARE STMT FROM @STMT;

						EXECUTE STMT;
						*/
						CALL add_cash_account_log_tb(p_id,-1*comm_to_point,17,'','',0);

						#生成分红变动记录

						insert into profit_sharing_point_add_log(uid,add_source,money,point,create_time) values(p_id,1,

		comm_to_point/100,comm_to_point/100,unix_timestamp());

					end if;

				end if;

				

				#更新用户现金池，提成统计

				update users set amount=amount+(comm_parent-comm_to_point)/100,team_commission=

		team_commission+comm_parent/100 where id=p_id;

			end if;

		end if;

	ELSE

		set done_parent_ids=1;

	end if;

	set i=i+1;

END WHILE;



END;

