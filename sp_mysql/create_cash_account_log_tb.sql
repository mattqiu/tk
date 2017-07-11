delimiter //
drop PROCEDURE if exists create_cash_account_log_tb //
CREATE  PROCEDURE create_cash_account_log_tb()
BEGIN

DECLARE cur_cash_tb_name varchar(60);
DECLARE tab_postfix int;
DECLARE index_uid int default 1382;
DECLARE table_name CHAR(60);
DECLARE user_uid int;
DECLARE table_name_new,table_name_news CHAR(60);
SELECT   left(id,4)  into user_uid FROM users  order by id desc limit 0,1; /**查询最大用户id**/

SET table_name=CONCAT('cash_account_log_', DATE_FORMAT(CURDATE(),'%Y%m'));

            SET @sqlstr= CONCAT('create TABLE IF NOT EXISTS ', table_name ,'(

            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,

            `uid` INT UNSIGNED  not NULL,

            `item_type` tinyint UNSIGNED not NULL,

            `amount`   DECIMAL(14,2) UNSIGNED DEFAULT 0 not NULL ,

            `create_time`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP  NOT NULL ,

            `order_id` varchar(25) ,

            `related_uid` INT UNSIGNED DEFAULT 0   not NULL,

            `related_id` varchar(35)  DEFAULT 0  not NULL,

            `remark`  text,

            PRIMARY KEY (`id`)

            )ENGINE=MYISAM DEFAULT CHARSET=utf8;');   -- 创建表

            PREPARE stmt FROM @sqlstr;

            EXECUTE stmt;

            DEALLOCATE PREPARE stmt;

			/**创建cash_account_log_xx的分表**/
	
			SET tab_postfix = left(user_uid,4);
		 	IF tab_postfix > 1381 THEN
				while index_uid <= (tab_postfix+1)   do
					set table_name_new =   concat(table_name,"_",index_uid );
					set @tab_sql_news = concat('create table if not exists ',  table_name_new ,'  like  ',  table_name) ;
					PREPARE stmt1 FROM @tab_sql_news;
			 	  	EXECUTE stmt1;
					DEALLOCATE PREPARE stmt1;
					set index_uid = index_uid +1;
				end while;
			 END IF;

END;