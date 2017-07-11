
/***调用执行数据**/
DROP PROCEDURE IF EXISTS pro_store_statistics_totals;
CREATE PROCEDURE pro_store_statistics_totals()
BEGIN

declare err INT default 0;
declare continue handler for sqlexception set err=1;
set @time_new = date_sub(curdate(),interval 1 day);

START TRANSACTION;

/**中国区域统计**/
call pro_store_statistics_total(1,1,1,@total_p_zh);
call pro_store_statistics_total(1,1,2,@total_g_zh);
call pro_store_statistics_total(1,1,3,@total_s_zh);
call pro_store_statistics_total(1,1,5,@total_b_zh);
call pro_store_statistics_total(1,1,4,@total_f_zh);

DELETE FROM users_level_statistics_zh WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_zh SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_zh, golden=@total_g_zh,bronze = @total_b_zh, silver=@total_s_zh,diamond=@total_p_zh;

/**美国区域统计**/
call pro_store_statistics_total(1,2,1,@total_p_en);
call pro_store_statistics_total(1,2,2,@total_g_en);
call pro_store_statistics_total(1,2,3,@total_s_en);
call pro_store_statistics_total(1,2,5,@total_b_en);
call pro_store_statistics_total(1,2,4,@total_f_en);

DELETE FROM users_level_statistics_en WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_en SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_en, golden=@total_g_en,bronze = @total_b_en, silver=@total_s_en,diamond=@total_p_en;

/**韩国区域统计**/
call pro_store_statistics_total(1,3,1,@total_p_kr);
call pro_store_statistics_total(1,3,2,@total_g_kr);
call pro_store_statistics_total(1,3,3,@total_s_kr);
call pro_store_statistics_total(1,3,5,@total_b_kr);
call pro_store_statistics_total(1,3,4,@total_f_kr);

DELETE FROM users_level_statistics_kr WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_kr SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_kr, golden=@total_g_kr,bronze = @total_b_kr, silver=@total_s_kr,diamond=@total_p_kr;

/**香港区域统计**/
call pro_store_statistics_total(1,4,1,@total_p_hk);
call pro_store_statistics_total(1,4,2,@total_g_hk);
call pro_store_statistics_total(1,4,3,@total_s_hk);
call pro_store_statistics_total(1,4,5,@total_b_hk);
call pro_store_statistics_total(1,4,4,@total_f_hk);

DELETE FROM users_level_statistics_hk WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_hk SET date = DATE_FORMAT(@time_new ,'%Y%m%d'), free = @total_f_hk, golden=@total_g_hk,bronze = @total_b_hk, silver=@total_s_hk,diamond=@total_p_hk;

/**其他地区统计**/
call pro_store_statistics_total(0,5,1,@total_p_ot);
call pro_store_statistics_total(0,5,2,@total_g_ot);
call pro_store_statistics_total(0,5,3,@total_s_ot);
call pro_store_statistics_total(0,5,5,@total_b_ot);
call pro_store_statistics_total(0,5,4,@total_f_ot);

/**计算总统计数据**/
set @free_total = @total_f_zh + @total_f_en + @total_f_kr + @total_f_hk + @total_f_ot; 
set @golden_total = @total_g_zh + @total_g_en + @total_g_kr + @total_g_hk + @total_g_ot; 
set @silver_total = @total_s_zh + @total_s_en + @total_s_kr + @total_s_hk + @total_s_ot; 
set @bronze_total = @total_b_zh + @total_b_en + @total_b_kr + @total_b_hk + @total_b_ot; 
set @diamode_total = @total_p_zh + @total_p_en + @total_p_kr + @total_p_hk + @total_p_ot; 


DELETE FROM users_level_statistics_other WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_other SET date = DATE_FORMAT(@time_new ,'%Y%m%d') , free = @total_f_ot, golden=@total_g_ot,bronze = @total_b_ot, silver=@total_s_ot,diamond=@total_p_ot;

/**添加总统计数据**/
DELETE FROM users_level_statistics_total WHERE date = DATE_FORMAT(@time_new ,'%Y%m%d') ;
INSERT INTO users_level_statistics_total SET date = DATE_FORMAT(@time_new ,'%Y%m%d') , free = @free_total , golden=@golden_total ,bronze = @bronze_total , silver=@silver_total ,diamond=@diamode_total;

IF (err=0) 
THEN
commit;
ELSE
  rollback;      
END IF;

END
