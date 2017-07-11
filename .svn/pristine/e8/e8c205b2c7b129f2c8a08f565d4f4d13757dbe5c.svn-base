DELIMITER $$
USE `tps138_com`$$

DROP PROCEDURE IF EXISTS `get_supplier_recommend_commission`$$
CREATE DEFINER=`tps138`@`%`  PROCEDURE `get_supplier_recommend_commission`(min_time TIMESTAMP,max_time TIMESTAMP,istest INT)
BEGIN    
	    DECLARE minct ,maxct,_supplier_recommend,_supplier_id,_user_rank,_user_id,l_done,_country_id,re_count,_minct,_maxct,_status,_minct_s,_maxct_s ,_num INT  DEFAULT 0;   
	    DECLARE _name,_goods_sn_main,_goods_sn,_goods_name,_str,table_name VARCHAR(255); 
	    DECLARE _percentage  DECIMAL(12,6) DEFAULT 0.01;               
	    DECLARE _total_number,_order_amount_usd,_order_type,_order_profit_usd,_last_insert_id INT DEFAULT 0;
	    DECLARE _goods_price,_mall_goods_price,_mall_purchase_price,goods_profit,_amount,_amount_total,_sale_number_total DECIMAL(12,6) DEFAULT 0; 
	    DECLARE _create_time  TIMESTAMP DEFAULT NULL;
            DECLARE t_error INTEGER DEFAULT 0;  
            DECLARE _remark TEXT DEFAULT '';
	    DECLARE _supplier_recommend_commission,total_sale_goods_number VARCHAR(255) DEFAULT '';  
	    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET t_error=1;  
	     IF istest<>1 THEN 
	     CREATE TEMPORARY  TABLE IF NOT EXISTS  mall_supplier_recommend_amount_Data_Tmp(
	    `TA_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
	    `uid` INT  UNSIGNED  NOT NULL,
	    `name` VARCHAR(50) NOT NULL,
	    `amount_total` DECIMAL(12,6)  UNSIGNED  NOT NULL,
	    `sale_number_total` INT UNSIGNED  NOT NULL,
	    PRIMARY KEY (`TA_Id`)  
	    )ENGINE=MYISAM DEFAULT CHARSET=utf8;   
	    END IF;
                   IF istest=1 THEN
	    CREATE TEMPORARY  TABLE IF NOT EXISTS  mall_supplier_Data_Tmp(
	    `Tmp_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
	    `supplier_recommend` INT  UNSIGNED  NOT NULL,
	    PRIMARY KEY (`Tmp_Id`)  
	    )ENGINE=MYISAM DEFAULT CHARSET=utf8;   
	    CREATE  TEMPORARY TABLE IF NOT EXISTS  mall_goods_main_Data_Tmp(
	    `T_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
	    `goods_sn_main` VARCHAR(50) NOT NULL,
	    PRIMARY KEY (`T_Id`)  
	    )ENGINE=MYISAM DEFAULT CHARSET=utf8;  
	    CREATE TEMPORARY  TABLE IF NOT EXISTS  mall_s_supplier_Data_Tmp(
	    `Ts_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,  
	    `s_id`  SMALLINT(5)  UNSIGNED  NOT NULL,
	    PRIMARY KEY (`Ts_Id`)  
	    )ENGINE=MYISAM DEFAULT CHARSET=utf8;  
	    END IF;
	     IF istest=1 THEN	
	    IF max_time IS NULL THEN
                SET max_time= DATE_FORMAT(CURDATE(),'%Y-%m-%d');     
             END IF;    
             IF min_time IS NULL THEN
                SET min_time= DATE_SUB(max_time, INTERVAL 3 MONTH);  
             END IF; 
             SET _create_time=NOW(); 
             TRUNCATE TABLE mall_supplier_Data_Tmp; -- 
            SET @sqlstr= 'INSERT INTO  mall_supplier_Data_Tmp(`supplier_recommend`) SELECT  DISTINCT `supplier_recommend` FROM mall_supplier WHERE   `supplier_recommend` <>0;';  -- 执行插入临时数据 
            PREPARE stmt FROM @sqlstr;    
            EXECUTE stmt;  
            DEALLOCATE PREPARE stmt;           
            SELECT MIN(`Tmp_Id`),MAX(`Tmp_Id`) INTO minct ,maxct FROM mall_supplier_Data_Tmp; -- 获取表最小id 最大id  
            WHILE minct <= maxct &&minct>0 DO    
		          START TRANSACTION;  
		          SET _supplier_recommend=0;SET _supplier_id=0;SET _user_id=0;SET _user_rank=0;SET _country_id=0;SET re_count=0;SET _percentage=0;SET _name='';SET _str='';
		          SET _goods_price=0;SET _goods_sn='';SET _total_number=0;SET _goods_name='';SET _order_amount_usd=0;SET _goods_sn_main='';SET _order_type=0;SET _order_profit_usd=0;
		          SET _mall_goods_price=0;SET _mall_purchase_price=0;
		          SELECT `supplier_recommend` INTO _supplier_recommend  FROM mall_supplier_Data_Tmp WHERE `Tmp_Id`=minct  LIMIT 1;
           	          IF _supplier_recommend>0 THEN 
           	          SELECT id,`user_rank`,`name`,`country_id`,`status` INTO _user_id,_user_rank,_name,_country_id,_status FROM users WHERE `id` = _supplier_recommend  LIMIT 1;     -- 获取推荐人基本信息       	         
           	          IF _user_id>0 THEN  
           	           -- 设置对应的会员等级的提成比例
           	            CASE _user_rank 
                	     WHEN 1 THEN SET _percentage=0.02;
                	     WHEN 2 THEN SET _percentage=0.015; 
                	     ELSE SET _percentage=0.01;
                	     END CASE;  
                	     SET _minct_s=0;SET _maxct_s=0;
                	     TRUNCATE   TABLE mall_s_supplier_Data_Tmp; 
                             INSERT INTO  mall_s_supplier_Data_Tmp(`s_id`) SELECT `supplier_id` FROM mall_supplier WHERE  `supplier_recommend`=_supplier_recommend;  -- 执行插入临时数据  
                             SELECT MIN(`Ts_Id`),MAX(`Ts_Id`) INTO _minct_s ,_maxct_s FROM mall_s_supplier_Data_Tmp; -- 获取表最小id 最大id 
			     WHILE _minct_s <= _maxct_s &&_minct_s>0 DO   
                	                    SET _supplier_id=0;  
				            SELECT `s_id` INTO _supplier_id  FROM mall_s_supplier_Data_Tmp WHERE `Ts_Id`=_minct_s  LIMIT 1;  
			 		                IF _supplier_id>0     THEN  
			                             TRUNCATE   TABLE mall_goods_main_Data_Tmp;
			                             SET _minct=0;  SET _maxct=0;  
			                          	INSERT INTO  mall_goods_main_Data_Tmp(`goods_sn_main`) SELECT DISTINCT `goods_sn_main` FROM mall_goods_main WHERE  `supplier_id`=_supplier_id  ; 
			                            SELECT MIN(`T_Id`),MAX(`T_Id`) INTO _minct ,_maxct FROM mall_goods_main_Data_Tmp; -- 获取表最小id 最大id   
				                          WHILE _minct <= _maxct &&_minct>0 DO   
			                              SET _str='';
				                            SELECT `goods_sn_main` INTO _str  FROM mall_goods_main_Data_Tmp WHERE `T_Id`=_minct  LIMIT 1;  
				                            SET _str=TRIM(_str);	
			                              IF _str<>'' THEN  
			                              -- 查询当前的销售订单信息       
			                               SELECT SUM(tg.goods_number) AS total_number,tg.goods_price,tg.goods_name
		                                	INTO _total_number,_goods_price,_goods_name	
			 	                                  	FROM trade_orders t,trade_orders_goods tg  WHERE t.order_id = tg.order_id AND t.status IN (4,5,6) AND tg.goods_sn_main = _str  AND   t.pay_time <max_time AND t.pay_time>=min_time;	 
			        	  IF _total_number>0 THEN
					                          -- 商品售价
					                            SELECT  `price`,`purchase_price` INTO  _mall_goods_price,_mall_purchase_price  FROM mall_goods WHERE goods_sn_main=_str  LIMIT 1 ; 
					                          -- 商品成本价
					                           SET goods_profit=0;SET _amount=0;
					                           SET goods_profit = _mall_goods_price - _mall_purchase_price -_goods_price * 0.05;
                                     SET _amount = goods_profit * _total_number * _percentage;
                                     --  插入到推荐佣金表
                                     IF _amount>0 THEN      
							                            INSERT INTO user_recommend_commission_logs(uid,`name`,`supplier_id`,`amount`,`goods_sn_main`,`goods_name`,`sale_number`,created_time)
							                              VALUES
							                                (_supplier_recommend,_name,_supplier_id,_amount * 0.8,_str,_goods_name,_total_number,NOW()); 
                                        END IF;
				                        	END IF;    
                                    END IF;	        
			          SET _minct=_minct+1;
		                            END WHILE;  
                        END IF; 
                         SET _minct_s=_minct_s+1;
               END WHILE;    
           	         END IF;  
           	       END IF;    
           	        IF t_error = 1 THEN  
            ROLLBACK;   
        ELSE  
            COMMIT;      
        END IF;
        SET minct=minct+1;
        END WHILE;
           
        ELSE
         SET minct=0; SET maxct=0;
           IF min_time IS NOT NULL THEN  
                TRUNCATE TABLE mall_supplier_recommend_amount_Data_Tmp; 
                INSERT INTO  mall_supplier_recommend_amount_Data_Tmp (`uid`,`name`,`amount_total`,`sale_number_total`) SELECT  `uid`  ,`name`,SUM(`amount`),SUM(`sale_number`) FROM user_recommend_commission_logs WHERE   created_time>min_time GROUP BY uid  ;
		SET table_name=CONCAT('cash_account_log_', DATE_FORMAT(CURDATE(),'%Y%m'));         
		SELECT MIN(`TA_Id`),MAX(`TA_Id`) INTO minct ,maxct FROM mall_supplier_recommend_amount_Data_Tmp; -- 获取表最小id 最大id  
                START TRANSACTION;  		
		WHILE minct <= maxct &&minct>0 DO  
		          SET _user_id=0;SET _country_id=0;SET _name='';SET _amount_total=0;SET _sale_number_total=0;	 
		          SELECT `uid`,`name`,`amount_total`,`sale_number_total` INTO _user_id,_name,_amount_total,_sale_number_total  FROM mall_supplier_recommend_amount_Data_Tmp WHERE `TA_Id`=minct  LIMIT 1;
           	          IF _user_id>0 THEN 
           	           SELECT id, `name`,`country_id`,`status` INTO _user_id,_name,_country_id,_status FROM users WHERE `id` = _user_id  LIMIT 1;     -- 获取推荐人基本信息
                             BEGIN            	          
           	           IF _user_id>0 THEN 
           	            IF _status=1 || _status=3 THEN  
           	               BEGIN                                   
					SET _sale_number_total=IFNULL(_sale_number_total,0);
					SET _amount_total=IFNULL(_amount_total,0);
					SET _supplier_recommend_commission='';
					SET total_sale_goods_number='';
					 CASE   _country_id   
					       WHEN  1 THEN  
							SET _supplier_recommend_commission='供应商推荐奖' ; 
							SET total_sale_goods_number='总销售数量:sale_number件';
					 
						WHEN  4 THEN 
							SET _supplier_recommend_commission='供應商推薦獎' ; 
							SET total_sale_goods_number='總銷售數量:sale_number件'; 
						ELSE
							SET _supplier_recommend_commission='The bonuses for Recommended supplier' ; 
							SET total_sale_goods_number='Total sales of :sale_number items'; 
				         END CASE;   
					 SET _remark=CONCAT('[',_supplier_recommend_commission,']',REPLACE (total_sale_goods_number,'sale_number',FLOOR(_sale_number_total)));  
					 SET @sqlstr='';					 
					SET @sqlstr= CONCAT('insert into ', table_name ,'(`uid`,`item_type`,`amount`,`create_time`,`remark`)values(',CONCAT_WS(',',_user_id,9,FLOOR(_amount_total*100)),',"',NOW(),'","',_remark,'");');                                			      
					 PREPARE stmt FROM @sqlstr; 
		         		 EXECUTE stmt;  
					 DEALLOCATE PREPARE stmt;  -- 更新账户余额 
					 UPDATE  users SET `amount`=`amount`+_amount_total WHERE `id`=_user_id  ;  
				 END;
           	            END IF;
           	           END IF;
           	           END;
           	         END IF; 
           	   SET minct=minct+1;
                END WHILE;  
                     IF t_error = 1 THEN  
            ROLLBACK;   
        ELSE  
            COMMIT;      
        END IF;
        ELSE
         SELECT '起始日期为空';
	   END IF;
       END IF;    
    END
DELIMITER ;