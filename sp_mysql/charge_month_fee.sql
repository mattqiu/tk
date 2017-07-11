DELIMITER $$

USE `tps138_com`$$

DROP PROCEDURE IF EXISTS `charge_month_fee`$$

CREATE DEFINER=`tps138`@`%` PROCEDURE `charge_month_fee`(IN curDay INT,IN curMonthLastDay INT)
BEGIN 
	    -- 定义需执行的临时表的最小和最大id 
            DECLARE minct,maxct,_users_id,monthlyFeeCouponNum,_leftMonthlyFeeCouponNum,fee_num ,record_count,coupon_num_change,total_record INT  DEFAULT 0; 
            DECLARE _month_fee_rank,_user_rank,_first_monthly_fee_level,_country_id,monthFeeLevel,user_status,_is_auto TINYINT DEFAULT 0;
            DECLARE _month_fee_pool,monthFee,monthFeeLevel_fee DECIMAL(14,2) DEFAULT 0; 
            DECLARE now_time,_lastWeekDate,_lastWeekDateOneDayAfter,_fail_time,users_month_fee_fail_info_create_time TIMESTAMP DEFAULT NULL;
            DECLARE _fail_year,_fail_month,_fail_day,_now_year,_now_month,_now_day,_year_to_month,_coupon_num_change,_is_exception INT DEFAULT 0;
            DECLARE monthFeeOk BOOL DEFAULT FALSE; 
            DECLARE table_name,no_data CHAR(50);
            DECLARE _amount,price_diff DECIMAL(14,2) DEFAULT 0;
            DECLARE ids TEXT DEFAULT '';
            DECLARE page_count,page_size,record_num,current_page  INT DEFAULT 0;  
            -- 定义费率          
            -- 等级1  
            DECLARE month_fee_1 DECIMAL(14,2) DEFAULT 60;        
            -- 等级2  
            DECLARE month_fee_2 DECIMAL(14,2) DEFAULT 40;  
            -- 等级3 
            DECLARE month_fee_3 DECIMAL(14,2) DEFAULT 20;             
            -- 等级4 
            DECLARE month_fee_4 DECIMAL(14,2) DEFAULT 0;   
            -- 等级5        
            DECLARE month_fee_5 DECIMAL(14,2) DEFAULT 10;  
             
            DECLARE month_fee  DECIMAL(14,2) DEFAULT 0;              
            
            DECLARE month_half_price BOOL DEFAULT TRUE;
            DECLARE str_where VARCHAR(300);-- 定义查询当前需缴纳月费的会员条件  
            DECLARE t_error INT  DEFAULT 0;    
            DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;  
          --  创建临时表  TEMPORARY
            CREATE TEMPORARY  TABLE IF NOT EXISTS  tmp_sync_charge_month_fee_table(
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `uid` INT  UNSIGNED  NOT NULL,  
                `fail_time` TIMESTAMP NULL,
                PRIMARY KEY (`id`)  
            )ENGINE=MYISAM DEFAULT CHARSET=utf8; 	  
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
	    SELECT  DATE_FORMAT(NOW(),'%Y'),DATE_FORMAT(NOW(),'%m'),DATE_FORMAT(NOW(),'%d') INTO _now_year,_now_month,_now_day; 
    /*
    *  第一步 扫描需扣月费记录  
    */           
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
            -- 加上排除扣费失败的会员
            SET str_where=CONCAT(str_where,'  and  id not  IN (SELECT uid FROM users_month_fee_fail_info ) ');   
            TRUNCATE TABLE tmp_sync_charge_month_fee_table; 
            SET @sqlstr=CONCAT('insert into tmp_sync_charge_month_fee_table(uid) select id from users where `status` in(1,3)    ', str_where );  -- 执行插入临时数据 
            PREPARE stmt FROM @sqlstr;    
            EXECUTE stmt;  
            DEALLOCATE PREPARE stmt;   
            
            SET @sqlstr='insert into tmp_sync_charge_month_fee_table(uid,fail_time) SELECT uid,create_time FROM users_month_fee_fail_info ;'; 
	    PREPARE stmt FROM @sqlstr;    
            EXECUTE stmt;  
            DEALLOCATE PREPARE stmt; 
             /*
	    *  第二步 执行扣月费操作  
	    */    					
            SET page_size=2000;
            SELECT COUNT(`id`) INTO total_record FROM tmp_sync_charge_month_fee_table;  
            SET now_time=NOW();  
            IF total_record>0 THEN
							SET page_count= total_record/page_size;
							IF  page_count*page_size<total_record THEN
								SET page_count=page_count+1;
							END IF;  
							 WHILE current_page<page_count DO                 
								 SET ids=''; 
							         SET minct=maxct+1;  
                                                                 SET  maxct=page_size* (current_page+1);
                                                                 IF(maxct>total_record)	 THEN
                                                                 SET maxct=total_record;
                                                                 END IF;				 
								IF  minct>0 THEN 
									START TRANSACTION;
									WHILE minct <= maxct DO
									SET _is_auto=0;SET _users_id=0; SET _fail_time=NULL;SET user_status=0;	 SET fee_num=0;SET monthlyFeeCouponNum=0;SET _leftMonthlyFeeCouponNum=0;SET _month_fee_pool=0;
									SET _country_id=0;SET _month_fee_rank=0;SET _user_rank=0;SET _first_monthly_fee_level=0;SET monthFeeLevel=0; SET _amount=0;SET monthFee=0;SET price_diff=0;
									SET _coupon_num_change=0;
									SELECT `uid` ,`fail_time` INTO _users_id,_fail_time FROM tmp_sync_charge_month_fee_table WHERE id=minct;
								
   									
									IF _users_id>0 THEN
										SELECT u.`month_fee_rank` ,u.`month_fee_pool`,u.`user_rank`,IFNULL(u.`first_monthly_fee_level`,0),u.`country_id`, u.`amount`,u.`is_auto`
									INTO
										_month_fee_rank,_month_fee_pool,_user_rank,_first_monthly_fee_level,_country_id ,_amount,_is_auto
									FROM users  u   WHERE  u.`id` =_users_id LIMIT 1;
									-- 判断月费等级
									IF _month_fee_rank=4 THEN
										SET monthFeeLevel=_user_rank;
									ELSE
										SET monthFeeLevel=_month_fee_rank;
									END IF;	   
									SET monthFeeLevel_fee=0;
											-- 设置费率
											CASE   monthFeeLevel 
											WHEN  1 THEN  
												SET monthFeeLevel_fee=month_fee_1 ; 
											WHEN  2 THEN  
												SET monthFeeLevel_fee=month_fee_2 ; 
											WHEN  3 THEN  
												SET monthFeeLevel_fee=month_fee_3 ; 
											WHEN  4 THEN  
												SET monthFeeLevel_fee=month_fee_4 ; 
											WHEN  5 THEN  
												SET monthFeeLevel_fee=month_fee_5 ; 
											 END CASE;         
											IF month_half_price  THEN
												SET monthFeeLevel_fee=monthFeeLevel_fee/2;
											END IF;     
											-- 查询当前用户是否有可用的月费券    
										 SELECT COUNT(`id`) INTO monthlyFeeCouponNum FROM  monthly_fee_coupon WHERE  `uid`=_users_id;
										 SET _leftMonthlyFeeCouponNum=monthlyFeeCouponNum;                   
										 -- 获取用户使用月费券的信息  不等于0 为使用的月费券 
										 SET record_count=0;
										/* SET coupon_num_change=0;
										 SET @sql1=CONCAT('SELECT COUNT(1) into @recordcount FROM (SELECT `coupon_num_change` FROM month_fee_change WHERE `type`=4 AND `user_id`=',_users_id,' ORDER BY `id` DESC LIMIT 2) a WHERE a.`coupon_num_change`<>0');
										 PREPARE STMT FROM @sql1;
										 EXECUTE STMT;
										 DEALLOCATE PREPARE STMT;  
										 SET record_count = @recordcount;
										 IF record_count>0  THEN
										   SET coupon_num_change=1;	
										 END IF;	*/									   
										 IF _fail_time IS NOT  NULL THEN 
										 SET  _fail_year=0;SET _fail_month=0;SET _fail_day=0; SET users_month_fee_fail_info_create_time=NULL; 
										 SET _year_to_month=0;
										-- 欠月费的用户                        
										    -- 计算当前拖欠的月费次数                    
										   SELECT 
										   DATE_FORMAT(_fail_time,'%Y'), 
										   DATE_FORMAT(_fail_time,'%m'),
										   DATE_FORMAT(_fail_time,'%d') 
										   INTO _fail_year,_fail_month,_fail_day;
										   IF _fail_time=STR_TO_DATE('0000-00-00', '%Y-%m-%d %H') THEN
											SET fee_num=1;
										   ELSE
											SET _year_to_month=(_now_year-_fail_year)*12;
											IF _now_day>=_fail_day THEN
												SET fee_num=(_now_month-_fail_month+1)+_year_to_month;
											ELSE
												SET fee_num=(_now_month-_fail_month)+_year_to_month;
											END IF;
										   END IF; 
										      -- 判断剩余可使用的月费券数量扣除的月费数
										   IF monthlyFeeCouponNum>0 THEN 
										     -- IF coupon_num_change=0  || _country_id<>3 THEN 
											  IF fee_num<=monthlyFeeCouponNum THEN
												SET _leftMonthlyFeeCouponNum=monthlyFeeCouponNum-fee_num;
												SET fee_num=0;
											   ELSE
												SET fee_num=fee_num-monthlyFeeCouponNum;
												SET _leftMonthlyFeeCouponNum=0;
											  END IF;		      
										     -- END IF;
										   END IF;  
										    -- 当前可用的月费券数量可用，并计算需缴纳的费用金额
										   IF  fee_num>0 THEN 
											IF _first_monthly_fee_level>0 THEN
												 SET monthFee=0; -- 设置初次升级的等级对应的费率配置
												CASE  _first_monthly_fee_level 
													WHEN  1 THEN  
														SET month_fee=month_fee_1 ; 
													WHEN  2 THEN  
														SET month_fee=month_fee_2 ; 
													WHEN  3 THEN  
														SET month_fee=month_fee_3 ; 
													WHEN  4 THEN  
														SET month_fee=month_fee_4 ; 
													WHEN  5 THEN  
														SET month_fee=month_fee_5 ; 
												END CASE;      
												IF month_half_price  THEN
												     SET month_fee=month_fee/2;
												END IF;  
												SET monthFee= month_fee+monthFeeLevel_fee*(fee_num-1);			 
											ELSE
												SET monthFee =monthFeeLevel_fee * fee_num;
											END IF;
										   ELSE
											SET monthFee=0;
										   END IF; 
							    ELSE   
								-- 正常扣费                          
								SET fee_num=1;        
								-- coupon_num_change为0 首次使用月费券 && (coupon_num_change=0|| _country_id<>3 )
								IF (monthlyFeeCouponNum>0 ) THEN
									SET _leftMonthlyFeeCouponNum = monthlyFeeCouponNum-1;
									SET fee_num =  fee_num-1;
									SET monthFee = 0;
								ELSE
									IF _first_monthly_fee_level>0 THEN
										CASE  _first_monthly_fee_level 
											WHEN  1 THEN  
												SET month_fee=month_fee_1 ; 
											WHEN  2 THEN  
												SET month_fee=month_fee_2 ; 
											WHEN  3 THEN  
												SET month_fee=month_fee_3 ; 
											WHEN  4 THEN  
												SET month_fee=month_fee_4 ; 
											WHEN  5 THEN  
												SET month_fee=month_fee_5 ; 
										END CASE;        
										IF month_half_price  THEN
											SET month_fee=month_fee/2;
										END IF;   
										 SET monthFee= month_fee;
								ELSE
										SET monthFee =monthFeeLevel_fee;
								END IF;
							END IF;
				     END IF  ; 
				   SET monthFeeOk=FALSE;
										-- 月费池大于月费
										IF _month_fee_pool>=monthFee THEN 
											UPDATE  users SET `month_fee_pool`=`month_fee_pool`-monthFee WHERE `id`=_users_id;
											SET monthFeeOk =TRUE;
										ELSE
											-- 现金池自动转月费池
											IF _is_auto=1 THEN
													 -- 月费差额
													 SET price_diff=ABS(_month_fee_pool-monthFee); 
													 IF _amount>=price_diff AND _amount>0 THEN
														 UPDATE  users SET `amount`=`amount`-price_diff,`month_fee_pool`=`month_fee_pool`+price_diff WHERE `id`=_users_id;
														-- 插入资金明细
														SET @sqlstr= CONCAT('insert into ', table_name ,'(`uid`,`item_type`,`amount`)values(', CONCAT_WS(',',_users_id,14,ROUND(price_diff)*100),');');   -- 创建表                              			      
														PREPARE stmt FROM @sqlstr; 
														EXECUTE stmt;  
														DEALLOCATE PREPARE stmt;     				
																 -- 插入月费明细(现金池转月费池)
														INSERT INTO month_fee_change(`user_id`,`old_month_fee_pool`,`month_fee_pool`,`cash`,`create_time`,`type`,`old_coupon_num`,`coupon_num`,`coupon_num_change`)VALUES
																	(_users_id,_month_fee_pool,_month_fee_pool+price_diff,price_diff,NOW(),2,0,0,0); 
														SET _month_fee_pool=_month_fee_pool+price_diff;
														UPDATE  users SET  `month_fee_pool`=_month_fee_pool-monthFee WHERE `id`=_users_id;	         
														SET monthFeeOk=TRUE;                     			
													 END IF;
											 END IF;
									END IF;  
									-- 成功扣月费
									IF monthFeeOk THEN 
												SET record_count=0;
												SELECT COUNT(`uid`) INTO record_count  FROM users_month_fee_log WHERE `uid`=_users_id AND `year_and_month`=DATE_FORMAT(CURDATE(),'%Y%m') ;-- AND `amount`=monthFee; 
												IF record_count=0 THEN 
													INSERT INTO users_month_fee_log(`uid`,`year_and_month`,`amount`)VALUES(_users_id,DATE_FORMAT(CURDATE(),'%Y%m'),monthFee);
												END IF;    			
												-- 去除第一次月费等级纪录
												IF _first_monthly_fee_level>0 THEN
														UPDATE  users SET `first_monthly_fee_level`=0  WHERE `id`=_users_id;
												END IF;  
												-- 月费变动记录
												SET _coupon_num_change= _leftMonthlyFeeCouponNum-monthlyFeeCouponNum;
												INSERT INTO month_fee_change(`user_id`,`old_month_fee_pool`,`month_fee_pool`,`cash`,`create_time`,`type`,`old_coupon_num`,`coupon_num`,`coupon_num_change`)VALUES
															 (_users_id,_month_fee_pool,_month_fee_pool-monthFee,-1 * monthFee,NOW(),4,monthlyFeeCouponNum,_leftMonthlyFeeCouponNum,_coupon_num_change);  
												-- 删除月费券记录 升序排序
													-- 使用月费券		
												SET _coupon_num_change=ABS(_coupon_num_change);
												IF _coupon_num_change>0 THEN 
														  SET @STMT =CONCAT(' DELETE FROM monthly_fee_coupon WHERE `uid` = ',_users_id,' ORDER BY `create_time`  LIMIT ',_coupon_num_change);
														  PREPARE STMT FROM @STMT;
														  EXECUTE STMT; 
												END IF; 
												SET record_count=0;
                                     								SELECT COUNT(1) INTO record_count FROM users_coupon_monthfee WHERE `uid`=_users_id  AND  `status`=1;				
												IF record_count>0 THEN
													UPDATE users_coupon_monthfee SET `status`=2 ,monthly_fee_charge_time= now_time WHERE `uid`=_users_id  ;
												END IF;
												-- 判断扣取的月费是否是使用了月费抵用券的，如果是则不要发佣金 
												SET record_count=0; 
												SELECT COUNT(`id`) INTO record_count FROM users WHERE `id`=_users_id  AND  `status`=2;
												IF record_count>0 THEN
													 INSERT users_status_log(`uid`,`front_status`,`back_status`,`type`,`create_time`)VALUES(_users_id,2,1,1,UNIX_TIMESTAMP());
												END  IF; 
												SET ids=CONCAT(ids,',',_users_id);  
											 ELSE
											  SET record_count=0; 
											  SET _is_exception=0; 
													 -- 扣费失败 
												SELECT COUNT(`uid`) INTO _is_exception FROM user_email_exception_list WHERE `uid`=_users_id;
												SELECT COUNT(`uid`) INTO record_count FROM  users_month_fee_fail_info  WHERE `uid`=_users_id; 
												IF record_count=0 THEN		 
													INSERT INTO users_month_fee_fail_info(`uid`)VALUES(_users_id);  
													IF _is_exception=0 THEN
														-- 當天欠費的
														INSERT INTO sync_send_receipt_email(`uid`,`type`)VALUES(_users_id,3);
													END IF;
												 ELSE
													-- 筛选出扣取月费失败后6天仍未付费的会员id,及其扣取月费失败的时间
													-- 获取上周
													SET _lastWeekDate= DATE_SUB(CURDATE(), INTERVAL 7 DAY);
													SET _lastWeekDateOneDayAfter = DATE_SUB(CURDATE(), INTERVAL 6 DAY); 
													SET record_count=0;
													SELECT COUNT(`uid`) INTO record_count  FROM users_month_fee_fail_info  WHERE `uid`=_users_id AND `create_time`>=_lastWeekDate AND `create_time`<_lastWeekDateOneDayAfter; 
													IF (record_count>0 && _is_exception=0) THEN	 
														INSERT INTO sync_send_receipt_email(`uid`,`type`)VALUES(_users_id,4);
													END IF;   			 
													-- 筛选出7天未缴费的会员
													SET record_count=0;
													SELECT COUNT(`uid`) INTO record_count FROM  users_month_fee_fail_info WHERE   `uid`=_users_id AND `create_time`<_lastWeekDate AND `create_time`>=DATE_SUB(CURDATE(), INTERVAL 90 DAY);
													IF (record_count>0) THEN
													   SET user_status=0;	 
														 SELECT `status` INTO user_status   FROM users    WHERE  `status` IN(1,3) AND `id`=_users_id   LIMIT 1; 
															IF user_status=1 THEN 
																UPDATE users SET  `status`=2,`store_qualified`=0  WHERE `id`=_users_id AND `status`=1 ;
															ELSEIF user_status=3 THEN
																UPDATE users SET  `status`=5,`store_qualified`=0  WHERE `id`=_users_id AND `status`=3 ;                      
															END IF; 
															-- 用户从正常变成休眠状态，记录日志				
															INSERT users_status_log(`uid`,`front_status`,`back_status`,`type`,`create_time`)VALUES(_users_id,1,2,1,UNIX_TIMESTAMP());   
															-- 发送邮件
															IF (_is_exception=0) THEN	 
																INSERT INTO sync_send_receipt_email(`uid`,`type`)VALUES(_users_id,5);
															END IF;   
													END IF;  
											END IF; 
						END IF; 
				   END IF;
			     SET minct = minct + 1;   
			  END WHILE; 
				IF ids <>'' THEN
				  SET ids= SUBSTRING(ids,2);
				END IF; 
			 	IF ids <>'' THEN 
				   SET @STMT =CONCAT(' DELETE FROM  users_month_fee_fail_info WHERE `uid` IN (',ids,')');
		       PREPARE STMT FROM @STMT;
				   EXECUTE STMT; 
				   
		       -- 状态2 改为1
				   SET @STMT =CONCAT('UPDATE users SET `status`=1,`store_qualified`=1  WHERE `status`=2 and  `id` in(',ids,')');
		       PREPARE STMT FROM @STMT;
				   EXECUTE STMT;  
		       -- 状态5 改为3 
				    SET @STMT =CONCAT('UPDATE users SET `status`=3,`store_qualified`=1 WHERE  `status`=5 and `id` in(',ids,')');
		       PREPARE STMT FROM @STMT;
				   EXECUTE STMT;  
				END IF ;                    
			 IF t_error =0 THEN   
			 	COMMIT;      
			 ELSE  			 
			 	ROLLBACK;  
			        -- 记录操作失败的id批号
			        SET @STMT =CONCAT('insert into sync_month_fee_fail_info(`ids`,`create_time`)VALUES("',ids,'",now())');
		                PREPARE STMT FROM @STMT;
				EXECUTE STMT; 
			 END IF;
       END IF;     
       SET current_page=current_page+1;
     END WHILE;   
    END IF;	
    /*
    *  处理扣费失败的记录超过7天的会员状态为冻结状态
    * 
    */        
     	SET _lastWeekDate= DATE_SUB(CURDATE(), INTERVAL 7 DAY);
	SET _lastWeekDateOneDayAfter = DATE_SUB(CURDATE(), INTERVAL 6 DAY); 
	SET record_count=0;
	TRUNCATE TABLE tmp_sync_charge_month_fee_table;
	INSERT INTO  tmp_sync_charge_month_fee_table(`uid`) SELECT `uid` FROM users_month_fee_fail_info WHERE  `create_time`>=_lastWeekDate AND `create_time`<_lastWeekDateOneDayAfter; 
	SELECT COUNT(`uid`) INTO record_count  FROM tmp_sync_charge_month_fee_table ; 
	IF (record_count>0 ) THEN
		-- 借用临时表 
		SET minct=0; SET maxct=0;
		SELECT MIN(a.`id`),MAX(a.`id`) INTO minct ,maxct FROM  tmp_sync_charge_month_fee_table a; -- 获取表最小id 最大id    
		IF  minct>0 THEN
			START TRANSACTION;
			WHILE minct <= maxct DO
				SET user_status=0;SET _users_id=0;  SET _is_exception=0;  
				SELECT `uid` INTO _users_id FROM tmp_sync_charge_month_fee_table WHERE id=minct;
				SELECT COUNT(`uid`) INTO _is_exception FROM user_email_exception_list WHERE `uid`=_users_id;
				SELECT `status` INTO user_status   FROM users  WHERE  `status` IN(1,3) AND `id`=_users_id   LIMIT 1;
				IF user_status=1 THEN 
				   UPDATE users   SET  `status`=2,`store_qualified`=0  WHERE `id`=_users_id AND `status`=1 ;
				ELSEIF user_status=3 THEN
				   UPDATE users SET  `status`=5,`store_qualified`=0  WHERE `id`=_users_id AND `status`=3 ; 
				END IF; 
				 -- 用户从正常变成休眠状态，记录日志
				INSERT users_status_log(`uid`,`front_status`,`back_status`,`type`,`create_time`)VALUES(_users_id,1,2,1,UNIX_TIMESTAMP()); 
				 -- 发送邮件
				IF (_is_exception=0) THEN
				  INSERT INTO sync_send_receipt_email(`uid`,`type`)VALUES(_users_id,5);
				 END IF;   										
				SET minct = minct + 1;   
			END WHILE;	
		        IF t_error =0 THEN   
			 	COMMIT;      
			 ELSE  
			 	ROLLBACK;  
			 END IF;
		END IF;
	
	END IF;   
     IF t_error = 1 THEN   
            INSERT INTO logs_cron(content,create_time)VALUES('统计交月费，欠月费会员.[执行失败]',NOW()); 
        ELSE   
            INSERT INTO logs_cron(content,create_time)VALUES('统计交月费，欠月费会员.[执行完成]',NOW()); 
        END IF;   
    END$$

DELIMITER ;