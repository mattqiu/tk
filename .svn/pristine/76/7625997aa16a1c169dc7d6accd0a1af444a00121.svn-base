delimiter //
drop PROCEDURE if exists new_cash_log_tb //
create PROCEDURE `new_cash_log_tb`()
BEGIN
################每月初自动创建下个月的佣金记录分表######################

DECLARE new_tb_name VARCHAR(40);

set new_tb_name = CONCAT('cash_account_log_',DATE_FORMAT(date_add(now(),interval 1 month),'%Y%m'));
set @STMT :=concat('CREATE TABLE ',new_tb_name," (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `item_type` tinyint(1) unsigned NOT NULL COMMENT '资金变动项类型。',
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_id` varchar(25) NOT NULL DEFAULT '0' COMMENT '订单号',
  `related_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关uid',
  `related_id` varchar(35) NOT NULL DEFAULT '0' COMMENT '关联id',
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `uid` (`uid`) USING HASH,
  KEY `item_type` (`item_type`) USING HASH,
  KEY `order_id` (`order_id`) USING HASH,
  KEY `related_uid` (`related_uid`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
PREPARE STMT FROM @STMT;
EXECUTE STMT;

END//
delimiter ;