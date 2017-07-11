delimiter //
drop PROCEDURE if exists 138_shar_prepare //
create PROCEDURE `138_shar_prepare`(out totalNum int,out totalSharNum int)
BEGIN
/*Desc:为138分红准备数据*/

select count(*) into totalNum from user_qualified_for_138;/*统计138分红总人数*/
select x,y into @lastX,@lastY from user_coordinates order by id desc limit 1;/*获取138矩阵的最后一个坐标*/
insert into 138_grant_tmp(uid,num_share) select user_id,(@lastY-y)-if(x>@lastX,1,0) from user_qualified_for_138;/*根据
矩阵最后一个坐标计算每个id矩阵底下的人数，并将数据存入临时表*/
select sum(num_share) into totalSharNum from 138_grant_tmp;/*统计发奖临时表中所有人底下人数的总和*/

END//
delimiter ;