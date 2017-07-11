delimiter //
drop PROCEDURE if exists ly_debug //
create PROCEDURE `ly_debug`(in de_content text)
BEGIN
################调试用的存储过程######################

insert into debug_logs(content) values(de_content);

END//
delimiter ;