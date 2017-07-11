delimiter //
drop PROCEDURE if exists new_cash_log_tb //
create PROCEDURE `new_cash_log_tb`()
BEGIN
################每月初自动创建下个月的佣金记录分表######################
/**用于替换原先的new_cash_log_tb 替换原因，新的实现分红表的数据分表**/

DECLARE new_tb_name VARCHAR(40);
DECLARE users_m_id,users_id  INT  DEFAULT 0;
DECLARE uid_new INT;
DECLARE index_uid int default 1382;
DECLARE table_name_new CHAR(60);
SELECT   left(id,4)  into uid_new FROM users  order by id desc limit 0,1; /**查询最大用户id**/

set new_tb_name = CONCAT('cash_account_log_',DATE_FORMAT(date_add(now(),interval 1 month),'%Y%m'));
set @STMT =concat('CREATE TABLE if not exists ',new_tb_name," (  
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` INT(10) UNSIGNED  not NULL,
  `item_type` tinyint(1) UNSIGNED not NULL,
  `amount`   DECIMAL(14,2) UNSIGNED DEFAULT 0 not NULL ,
  `create_time`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP  NOT NULL ,
  `order_id` varchar(25)  DEFAULT '0' not NULL,
  `related_uid` INT(10) UNSIGNED DEFAULT 0   not NULL,
  `related_id` varchar(35)  DEFAULT 0  not NULL,
  `remark`  text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `uid` (`uid`) USING HASH,
  KEY `item_type` (`item_type`) USING HASH,
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
PREPARE STMT FROM @STMT;
EXECUTE STMT;

/**创建cash_account_log_xx的分表**/
	 IF uid_new > 1381 THEN
		while index_uid <= (uid_new+1)   do
			set table_name_new =   concat(new_tb_name,"_",index_uid );
			set @tab_sql_news = concat('create table if not exists ',  table_name_new ,'  like  ',  new_tb_name) ;
			PREPARE stmt1 FROM @tab_sql_news;
	 	  	EXECUTE stmt1;
			DEALLOCATE PREPARE stmt1;
			set index_uid = index_uid +1;
		end while;
	 END IF;


END//
