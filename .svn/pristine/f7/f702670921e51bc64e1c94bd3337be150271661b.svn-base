DELIMITER $$ 
USE `tps138_com`$$

DROP PROCEDURE IF EXISTS `weekLeadershipMatchingBonus`$$

CREATE DEFINER=`tps138`@`%` PROCEDURE `weekLeadershipMatchingBonus`()
BEGIN  
            DECLARE l_done,minct,maxct,_user_id ,_record_count,_f_uid,_t_uid,_lastWeekStartTimestamp,_lastWeekEndTimestamp,record_count,_insert_id,_commission_amount_int INTEGER DEFAULT 0;   
            DECLARE _ids,_strsql TEXT DEFAULT ''; 
            DECLARE table_name,str_field,_current_table_name VARCHAR(50) DEFAULT '';
            DECLARE startYearMonth,endYearMonth,sub_month,sub_year,old_date,current_year_month,_now_year_month INT DEFAULT 0;
            DECLARE _sum_amount,_proportion,_commissionToPoint ,_rate,_total_amount DECIMAL(12,2)  DEFAULT 0;
            DECLARE start_time,end_time,_now_time TIMESTAMP;
            DECLARE page_size,total_record,page_count,current_page INT DEFAULT 0;
            DECLARE t_error INT  DEFAULT 0;    
            DECLARE rs CURSOR FOR  SELECT id FROM users WHERE parent_id=_user_id  ;  
            DECLARE t_rs CURSOR FOR  SELECT id FROM users WHERE parent_id=_f_uid ;
            DECLARE yearmonth_rs CURSOR FOR  SELECT yearMonth FROM tmp_week_leader_members_yearmonth_table ORDER  BY yearMonth ASC ;
            DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET l_done = 1;
            DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;  
       --  创建临时表  
            CREATE TEMPORARY  TABLE IF NOT EXISTS  tmp_week_leader_members_queue_table(
                `sq_no` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `uid` INT  UNSIGNED  NOT NULL, 
                PRIMARY KEY (`sq_no`)  
            )ENGINE=MYISAM DEFAULT CHARSET=utf8; 
           CREATE TEMPORARY TABLE IF NOT EXISTS  tmp_week_leader_members_yearmonth_table(
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
                `yearMonth` INT  UNSIGNED  NOT NULL, 
                PRIMARY KEY (`id`)  
            )ENGINE=MYISAM DEFAULT CHARSET=utf8; 	 
         --  set old_date=201606;    -- 分表操作
	-- 设置日期
	     SET _now_year_month=DATE_FORMAT(CURDATE(),'%Y%m');
	     SET _current_table_name=CONCAT('cash_account_log_', _now_year_month);
	     SET _lastWeekStartTimestamp=UNIX_TIMESTAMP(SUBDATE(CURDATE(),DATE_FORMAT(CURDATE(),'%w')-1)) ; 
             SET _lastWeekEndTimestamp=_lastWeekStartTimestamp+3600*24*7*5;
             SELECT FROM_UNIXTIME(_lastWeekStartTimestamp,'%Y%m'),FROM_UNIXTIME(_lastWeekEndTimestamp,'%Y%m') INTO startYearMonth,endYearMonth; 
             SELECT FROM_UNIXTIME(_lastWeekStartTimestamp,'%Y-%m-%d'),FROM_UNIXTIME(_lastWeekEndTimestamp,'%Y-%m-%d') INTO start_time,end_time;    
             TRUNCATE TABLE tmp_week_leader_members_yearmonth_table;  
              WHILE  startYearMonth<=endYearMonth DO     
               SET _strsql=CONCAT(_strsql,',(',startYearMonth,')');
                -- 截取月份
                    SET sub_year= LEFT(startYearMonth,4); 
                    SET sub_month= RIGHT(startYearMonth,2);      
                     IF sub_month<>12 THEN
                        SET  sub_month=sub_month+1;
                        ELSE
                         SET sub_year=sub_year+1;
                         SET sub_month=1;
                        END IF;
                        IF sub_month<10 THEN 
                        SET startYearMonth=CONCAT(sub_year,'0',sub_month);
                        ELSE 
                         SET startYearMonth=CONCAT(sub_year,sub_month);
                        END IF; 
             END WHILE;    
             -- 插入临时表
                    IF _strsql <>'' AND  LEFT(_strsql,1)=',' THEN
				  SET _strsql= SUBSTRING(_strsql,2);
			END IF;       
               IF _strsql <>'' THEN
                      SET @STMT =CONCAT('insert into tmp_week_leader_members_yearmonth_table(`yearMonth`) values ',_strsql);
		       PREPARE STMT FROM @STMT;
		      EXECUTE STMT; 
               END IF;
	     TRUNCATE TABLE tmp_week_leader_members_queue_table;
	   INSERT INTO  tmp_week_leader_members_queue_table(uid) SELECT uid FROM week_leader_members;	   
           SET page_size=2000;
            SELECT COUNT(`sq_no`) INTO total_record FROM tmp_week_leader_members_queue_table;   
            IF total_record>0 THEN	    
	    SET page_count= total_record/page_size;
							IF  page_count*page_size<total_record THEN
								SET page_count=page_count+1;
							END IF;  
			 WHILE current_page<page_count DO 				 
          
                                                                SET minct=maxct+1;  
                                                                 SET  maxct=page_size* (current_page+1);
                                                                 IF(maxct>total_record)	 THEN
                                                                 SET maxct=total_record;
                                                                 END IF;	
                                                                 	IF  minct>0 THEN 		  
            START TRANSACTION;
          WHILE minct <= maxct DO  
            SET _user_id=0;SET _record_count=0; SET _ids='';
                 SELECT `uid` INTO _user_id  FROM tmp_week_leader_members_queue_table WHERE `sq_no`=minct  LIMIT 1;
                 IF _user_id>0  THEN
                   -- 判断店铺是否有效
                   SELECT COUNT(`id`)INTO _record_count  FROM users WHERE `id`=_user_id AND `store_qualified`=1 LIMIT 1;    
                   IF _record_count>0 THEN 
                      -- 查询一级
                       OPEN rs; 
			SET _f_uid=0; 
                       out_loop:LOOP 
                        FETCH    rs INTO _f_uid;   
			IF l_done=1 THEN  
				LEAVE out_loop;     
			END IF;    
                        IF _f_uid>0 THEN 
                         SET _ids=CONCAT(_ids,',',_f_uid); 
                        -- 第二次查询当前下级
                              OPEN t_rs;       
                              inner_loop:LOOP
                                    SET _t_uid=0;      
                                      FETCH    t_rs INTO _t_uid;   
                                      IF l_done = 1 THEN
					LEAVE inner_loop;
					END IF;
					SET _ids=CONCAT(_ids,',',_t_uid); 
				END LOOP;    
                             CLOSE t_rs;
                             SET l_done=0;
                        END IF; 
                        END LOOP;                          
                       CLOSE rs;
                       SET l_done=0; 
                   --    set  _ids=',1380100249,1380102482,1380108181,1380111337,1380114995,1380121157,1380126254,1380126650,1380136879,1380138137,1380139974,1380140595,1380148760,1380151484,1380158250,1380172676,1380101150,1380102487,1380109803,1380114490,1380481187,1380551852,1380101576,1380101913,1380104115,1380118838,1380355201,1380101914,1380101916,1380157358';
                    -- 计算金额 
                      IF _ids <>'' AND  LEFT(_ids,1)=',' THEN
				  SET _ids= SUBSTRING(_ids,2);
			END IF;    
			 IF _ids <>'' THEN
                         OPEN yearmonth_rs;
                         SET _sum_amount=0;
                          year_month_loop:LOOP
                             SET current_year_month=0;
                                   FETCH    yearmonth_rs INTO current_year_month;   
                                      IF l_done = 1 THEN
					LEAVE year_month_loop;
				  END IF;     
				   SET table_name=CONCAT('cash_account_log_', current_year_month);   
				   SET @STMT =CONCAT(' select sum(amount) into @sum_amount  from ',table_name,' where create_time>="', start_time,'" and  create_time<"',end_time,'" and `item_type` in (1,2,3,4,5,6,7,8,23,16,21,24) and  uid in (', _ids ,')');
                                   PREPARE STMT FROM @STMT;
				   EXECUTE STMT;
				   DEALLOCATE PREPARE STMT;  
				   SET _sum_amount = _sum_amount+IFNULL(@sum_amount,0);  
                          END LOOP;  
                          CLOSE yearmonth_rs;                   
                          SET l_done=0;  
                         END IF; 
                      --   金额
                      SET _total_amount=_sum_amount/100;
                      -- 设置手续费
                        SET _total_amount=ROUND(_total_amount*0.05,2);
                        SET _commission_amount_int=_total_amount*100;
                        -- 单个会员发奖
                        IF   _total_amount<=0 THEN  
                                SET @sqlstr= CONCAT('insert into ', _current_table_name ,'(`uid`,`item_type`,`amount`)values(',CONCAT_WS(',',_user_id,7,0),')');                                			      
				PREPARE stmt FROM @sqlstr; 
				EXECUTE stmt;  
				DEALLOCATE PREPARE stmt; 
                          ELSE 
                                SET @sqlstr= CONCAT('insert into ', _current_table_name ,'(`uid`,`item_type`,`amount`)values(', CONCAT_WS(',',_user_id,7,_commission_amount_int),')');                                           			      
				PREPARE stmt FROM @sqlstr; 
				EXECUTE stmt;  
				DEALLOCATE PREPARE stmt;  
                           -- 统计用户奖金 
                           SET record_count=0;
                           SET _proportion=0;
                               SELECT COUNT(`uid`) INTO record_count  FROM user_comm_stat WHERE `uid`=_user_id ;
				 IF record_count=0 THEN                                  
                                     INSERT INTO user_comm_stat(`uid`,`week_bonus`) VALUES(_user_id,_commission_amount_int) ;
                                 ELSE           
                                     UPDATE  user_comm_stat SET   `week_bonus`=`week_bonus`+_commission_amount_int  WHERE  `uid`=_user_id;    
                                 END IF;   
                                 -- 佣金自动转分红点 
                                 SELECT proportion INTO _proportion FROM profit_sharing_point_proportion WHERE `uid`=_user_id  AND  `proportion_type`=1;     
                                 SET _rate=0;        
                                 SET _rate=IFNULL(_proportion,0)/100;        
                                 SET _commissionToPoint=0;       
                                 IF _rate>0 THEN 
                                    SET _commissionToPoint=ROUND(_total_amount*_rate,2);
                                    IF _commissionToPoint>0.01 THEN 
                                         SET _insert_id=0;
                                         UPDATE users SET `profit_sharing_point`=`profit_sharing_point`+_commissionToPoint,`profit_sharing_point_from_sharing`=`profit_sharing_point_from_sharing`+_commissionToPoint WHERE `id`=_user_id;
                                         SET @sqlstr= CONCAT('insert into ', _current_table_name ,'(`uid`,`item_type`,`amount`)values(', CONCAT_WS(',',_user_id,17,ROUND(_commissionToPoint*100)*-1),');');       
                                         PREPARE stmt FROM @sqlstr; 
					 EXECUTE stmt;  
					 DEALLOCATE PREPARE stmt; 
					 -- 查询这个id		 
                                         SET @sqlstr =CONCAT(' select  `id` INTO @_insert_id  from ',_current_table_name,' where `uid`=',_user_id,' and  `item_type`=17  and `amount`=',ROUND(_commissionToPoint*100)*-1,' order by create_time desc limit 1');
                                         PREPARE stmt FROM @sqlstr;
				         EXECUTE stmt;
				         DEALLOCATE PREPARE stmt;  					 
				         SET _insert_id =@_insert_id; 
				         INSERT INTO profit_sharing_point_add_log(`uid`,`commission_id`,`add_source`,`money`,`point`,`create_time`)VALUES(_user_id,_insert_id,3,_commissionToPoint,_commissionToPoint,UNIX_TIMESTAMP());
				ELSE
					 SET _commissionToPoint= 0;                                   
                                    END IF;
                                 END IF;
                                 UPDATE users SET  `amount`=`amount`+_total_amount-_commissionToPoint,	`amount_weekly_Leader_comm`=`amount_weekly_Leader_comm`+_total_amount WHERE id=_user_id;		  
                        END IF;  
                   END IF;
                   END IF;
                     SET minct = minct + 1;   
            END WHILE;  
             IF t_error =0 THEN   
			 	COMMIT;      
			 ELSE  			 
			 	ROLLBACK;  
           END IF;
        END IF;
          SET current_page=current_page+1;
     END WHILE; 
       -- 清理表数据
        TRUNCATE TABLE tmp_week_leader_members_queue_table;
        TRUNCATE TABLE tmp_week_leader_members_yearmonth_table;  
        IF t_error = 1 THEN   
            INSERT INTO logs_cron(content,create_time)VALUES('执行发放周领导对等奖.[执行失败]',NOW()); 
        ELSE   
            INSERT INTO logs_cron(content,create_time)VALUES('执行发放周领导对等奖.[执行完成]',NOW()); 
            END IF;
     END IF;
    END$$

DELIMITER ;