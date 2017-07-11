DELIMITER $$

USE `tps138.com`$$

DROP PROCEDURE IF EXISTS `count_charge_month_fee`$$

CREATE DEFINER=`root`@`%` PROCEDURE `count_charge_month_fee`(IN curDay INT,IN curMonthLastDay INT)
BEGIN
	DECLARE var_id,record_count  INT  DEFAULT 0; 
	DECLARE var_time TIMESTAMP ;
	DECLARE str_where VARCHAR(200);-- 定义查询当前需缴纳月费的会员条件  
	DECLARE t_error INTEGER DEFAULT 0;  
        DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1; 
	SET curDay=IFNULL(curDay,0);-- 判断设置默认当前日期
	IF curDay=0 THEN
		SET curDay=DAY(NOW());
	END IF;
	SET curMonthLastDay=IFNULL(curMonthLastDay,0);
	IF curMonthLastDay = 0 THEN	
		SET curMonthLastDay = DATEDIFF(DATE_ADD(CURDATE() - DAY(CURDATE()) + 1, INTERVAL 1 MONTH), DATE_ADD(CURDATE(), INTERVAL - DAY(CURDATE()) + 1 DAY));
	END IF;	 
	SET str_where=CONCAT(" and upgrade_month_fee_time<'",DATE_FORMAT(NOW(),'%Y-%m'),'-',curDay,"'  ");
	IF curMonthLastDay=curDay THEN
		SET str_where=CONCAT(str_where,' and  month_fee_date>=',curDay);
	ELSE
		SET str_where=CONCAT(str_where,' and  month_fee_date=',curDay);     
	END IF;  
	CREATE TEMPORARY TABLE tmp_user_month_fee_table(id INT);-- 创建临时表   
	SET @sqlstr=CONCAT('insert into tmp_user_month_fee_table(id) select id from users where status in(1,3) AND id NOT IN (SELECT uid FROM users_month_fee_fail_info)   ',str_where );  -- 执行插入临时数据
        PREPARE stmt FROM @sqlstr; 
	EXECUTE stmt;  
	DEALLOCATE PREPARE stmt; 	    
	-- SELECT COUNT(id) INTO record_count FROM tmp_user_month_fee_table;-- 统计临时数据表记录条数 去掉这个判断无意义
	SET var_time=NOW();
	START TRANSACTION;
        -- IF record_count>0 THEN		-- 执行插入需扣费的会员数据
	INSERT INTO sync_charge_month_fee(uid,create_time) SELECT tmp.id,var_time FROM tmp_user_month_fee_table tmp  WHERE   tmp.id  NOT  IN(SELECT id FROM sync_charge_month_fee) ;
	DROP  TABLE tmp_user_month_fee_table;   
        --  END IF;  
	-- 记录调度日志
       IF t_error = 1 THEN  
            ROLLBACK;  
            INSERT INTO logs_cron(content,create_time)VALUES('统计交月费，欠月费会员.[执行失败]',var_time); 
        ELSE  
            COMMIT;   
            INSERT INTO logs_cron(content,create_time)VALUES('统计交月费，欠月费会员.[执行完成]',var_time); 
        END IF;  
END$$

DELIMITER ;