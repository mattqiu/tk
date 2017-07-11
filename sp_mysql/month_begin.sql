delimiter //
drop PROCEDURE if exists month_begin //
create PROCEDURE `month_begin`()
BEGIN
################每月初执行的任务######################

call stat_user_point_monthly();#统计用户分红点
call new_cash_log_tb();#创建资金log分表

END//
delimiter ;