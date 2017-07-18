<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="<?php echo base_url('favicon.ico?v=3');?>"/>
	<link rel="bookmark" href="<?php echo base_url('favicon.ico?v=3');?>"/>
	<title><?php echo lang('tps138_admin');?></title>
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="tps-team">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lib/bootstrap/css/bootstrap.css?v=1')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/stylesheets/theme.css?v=10')?>">
	<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">

	<style type="text/css">
		#line-chart {
			height:300px;
			width:800px;
			margin: 0px auto;
			margin-top: 1em;
		}
		.brand { font-family: georgia, serif; }
		.brand .first {
			color: #ccc;
			font-style: italic;
		}
		.brand .second {
			color: #fff;
			font-weight: bold;
		}
		.unassigned_count{float: right;}
	</style>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/dd.css?v=2'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/flags.css?v=2'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/' . $curLanguage . '.css?v=5'); ?>">
	<script src="<?php echo base_url('js/jquery.min.2.js')?>" type="text/javascript"></script>
<!--	<script src="--><?php //echo base_url('themes/admin/lib/jquery-1.8.1.min.js')?><!--" type="text/javascript"></script>-->
	<script src="<?php echo base_url('js/msdropdown/jquery.dd.min.js?v=2'); ?>"></script>
	<script src="<?php echo base_url('js/msdropdown/init.js?v=5'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/javascripts/base.js?v=20'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/javascripts/jquery.message.min.js'); ?>"></script>
	<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>

</head>

<body>
<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<ul class="nav pull-right">

				<li id="fat-menu" class="dropdown">
					<a href="#" id="drop3" role="button" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-user"></i>
						<?php echo $adminInfo['email']?>
						<i class="icon-caret-down"></i>
					</a>

					<ul class="dropdown-menu">
						<li><a tabindex="-1" href="<?php echo base_url('admin/sign_in/logout')?>">Logout</a></li>
					</ul>
				</li>

				<?php foreach($language_all as $lang) {?>
					<li><a href="Javascript:;" data-title="<?php echo $lang['name']?>" attr_id="<?php echo $lang['language_id']?>" attr_value='<?php echo $lang['code']?>' class="choose_lan <?php echo $curLanguage == $lang['code'] ? 'cur_lan' : '' ?>"><?php echo $lang['name']?></a></li>
				<?php }?>

			</ul>
			<a class="brand" href="<?php echo base_url('admin/')?>"><span class="first"><?php echo lang('tps138_admin');?></span> <span class="second"></span></a>
		</div>
	</div>
</div>

<div class="container-fluid">
<div class="row-fluid">
<div class="span3">
<div class="sidebar-nav">
<?php if( (in_array($adminInfo['role'],array(0,1,2,3,4,5,8)) && !check_right('hk_customer_1v1_role_5')) || check_right('hk_export_order_role_7') ){?>
	<div class="nav-header" data-toggle="collapse" data-target="#dashboard-menu"><i class="icon-user"></i><?php echo lang('user_management') ?></div>
	<ul id="dashboard-menu" class="nav nav-list collapse<?php echo in_array($curControlName, array('repair_users_point', 'repair_users_amount','mvp_apply_list','blacklist','user_list','synchronize_wo_hao','users_status_log','join_plan','upgrade_user_manually','check_card','check_prepaid_card','upgrade_order_list','clear_member_account_info','monthly_fee_report','reset_user_pwd','delete_users','set_except_group','user_email_exception_list','repair_abnormality','order_achievement_repair', 'group_stat','repair_abnormality_sale',"unfrost", 'fix_user_rank')) ? ' in' : '' ?>">
		<?php if(!in_array($adminInfo['role'],array(8))){?>
                <li class="<?php echo $curControlName == 'user_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/user_list'); ?>"><?php echo lang('user_list') ?></a></li>
                <li class="<?php echo $curControlName == 'users_status_log' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/users_status_log'); ?>"><?php echo lang('users_status_log') ?></a></li>
                <?php }?>
                    <?php if(in_array($adminInfo['role'],array(0,1,2,8)) || $adminInfo['id'] == 47){?>
			<?php if($adminInfo['id'] != 47||in_array($adminInfo['role'],array(8))){ ?>
				<li class="<?php echo $curControlName == 'check_card' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/check_card'); ?>"><?php echo lang('check_card') ?></a></li>
			<?php }?>
                <?php if(!in_array($adminInfo['role'],array(8))){?>
			<li class="<?php echo $curControlName == 'check_prepaid_card' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/check_prepaid_card'); ?>"><?php echo lang('check_prepaid_card') ?></a></li>
		<?php }?>
                <?php }?>
                <?php if(!in_array($adminInfo['role'],array(8))){?>
		<li class="<?php echo $curControlName == 'upgrade_order_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/upgrade_order_list'); ?>"><?php echo lang('order_search') ?></a></li>
		<li class="<?php echo $curControlName == 'monthly_fee_report' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/monthly_fee_report'); ?>"><?php echo lang('monthly_fee_detail') ?></a></li>
                <?php }?>
		<?php if(in_array($adminInfo['id'],array(1,3,5,145))){?>
			<li class="<?php echo $curControlName == 'upgrade_user_manually' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/upgrade_user_manually'); ?>"><?php echo lang('upgrade_user_manually') ?></a></li>
		<?php }?>

		<?php if(in_array($adminInfo['role'],array(0,1,2))){?>
			<li class="<?php echo $curControlName == 'clear_member_account_info' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/clear_member_account_info'); ?>"><?php echo lang('clear_member_account_info') ?></a></li>
		<?php }?>

		<?php if(in_array($adminInfo['role'],array(0,2,3,4))){?>
			<li class="<?php echo $curControlName == 'reset_user_pwd' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/reset_user_pwd'); ?>"><?php echo lang('reset_user_pwd') ?></a></li>
		<?php }?>

		<?php if(in_array($adminInfo['role'],array(0,1,2))){?>
			<li class="<?php echo $curControlName == 'delete_users' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/delete_users'); ?>"><?php echo lang('delete_free_user') ?></a></li>
			<li class="<?php echo $curControlName == 'join_plan' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/join_plan'); ?>"><?php echo lang('action_charge_month') ?></a></li>
                <?php }?>
                <?php if(!in_array($adminInfo['role'],array(8))){?>
                <li class="<?php echo $curControlName == 'user_email_exception_list' ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin/user_email_exception_list'); ?>"><?php echo lang('user_email_exception_list'); ?></a>
		</li>
		<li class="<?php echo $curControlName == 'blacklist' ? 'active' : ''; ?>">
			<a href="<?php echo base_url('admin/blacklist'); ?>"><?php echo lang('blacklist'); ?></a>
		</li>
                <?php }?>
		<?php if(in_array($adminInfo['role'],array(0,1,2,5)) || check_right('hk_export_order_role_7')){?>
			<li class="<?php echo $curControlName == 'synchronize_wo_hao' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/synchronize_wo_hao'); ?>"><?php echo lang('synchronize_wo_hao') ?></a></li>
		<?php }?>
		<?php if(in_array($adminInfo['role'],array(0)) || in_array($adminInfo['id'],array(145))){?>
<!--			<li class="--><?php //echo $curControlName == 'repair_abnormality' ? 'active' : '' ?><!--"><a href="--><?php //echo base_url('admin/repair_abnormality/repair'); ?><!--">--><?php //echo lang('repair_abnormality') ?><!--</a></li>-->
		<?php }?>

		<?php if(!check_right('hk_export_order_role_7')){ ?>
		<li class="<?php echo $curControlName == 'mvp_apply_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/mvp_apply_list'); ?>"><?php echo lang('mvp_apply_list') ?></a></li>
        <li class="<?php echo $curControlName == 'repair_users_amount' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/repair_users_amount'); ?>"><?php echo lang('repair_users_amount') ?></a></li>
        <li class="<?php echo $curControlName == 'repair_users_point' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/repair_users_point'); ?>"><?php echo lang('repair_users_point') ?></a></li>


		<?php if($adminInfo['email']=="surchow.zhou@shoptps.com"){ ?>
            <li class="<?php echo $curControlName == 'order_achievement_repair' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/order_achievement_repair'); ?>"><?php echo lang('user_order_achievement_repair') ?></a></li>
        <?php } ?>
        <?php if($adminInfo['email']=="ckf.chen@shoptps.com" || $adminInfo['email']=="brady.wang@shoptps.com"){ ?>
            <li class="<?php echo $curControlName == 'repair_abnormality_sale' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/repair_abnormality_sale/repair?tabs_type=2'); ?>"><?php echo lang('repair_abnormality') ?></a></li>
        <?php } ?>
        <li class="<?php echo $curControlName == 'group_stat' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/user_list/group_stat'); ?>"><?php echo lang('group_stat') ?></a></li>
		<li class="<?php echo $curControlName == 'unfrost' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/user_list/unfrost'); ?>"><?php echo lang('unfrost') ?></a></li>
		<?php } ?>
		<li class="<?php echo $curControlName == 'fix_user_rank' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/user_list/fix_user_rank'); ?>"><?php echo lang('fix_user_rank') ?></a></li>
	</ul>
<?php }?>

<?php if(in_array($adminInfo['role'],array(0,1,2,4,5,122,153))){?>
	<div class="nav-header" data-toggle="collapse" data-target="#team"><i class="icon-money"></i><?php echo lang('commission') ?></div>


	<ul id="team" class="nav nav-list collapse<?php echo in_array($curControlName, array('commission_report','commission_admin','commission_month','monthfee_pool_admin','sharing_point_to_cash','cash_withdrawal_list','month_fee_to_amount','demote_levels','withdraw_table','withdraw_table_batch','withdraw_table_batch_detail','bank_withdraw','return_back','cash_to_sharing_point','order_repair_of_comm','commission_special_do','paypal_withdrawal_list','incentive_system_management','users_amount_check','bonus_plan_control','users_bonus_list_check','bank_withdraw','commission_special_check')) ? ' in' : '' ?>">

		<li class="<?php echo $curControlName == 'commission_report' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/commission_report'); ?>"><?php echo lang('funds_change_report') ?></a></li>
		<?php if(in_array($adminInfo['role'],array(0,4)) || in_array($adminInfo['id'],array(1,18,68,99,210,60,188,277,291,173,3,8,9,212,64,294,293,295,144,198,280,160,464))){?>
			<li class="<?php echo in_array($curControlName,array('withdraw_table','withdraw_table_batch','withdraw_table_batch_detail'))? 'active' : '' ?>"><a href="<?php echo base_url('admin/withdraw_table'); ?>"><?php echo lang('alipay_withdraw') ?></a></li>
                        <li class="<?php echo in_array($curControlName,array('bank_withdraw','bank_withdraw_batch','bank_withdraw_batch_detail'))? 'active' : '' ?>"><a href="<?php echo base_url('admin/bank_withdraw'); ?>"><?php echo lang('bank_withdraw') ?></a></li>
                <?php }?>
                <?php if(in_array($adminInfo['id'],array(1,3,18,61,173,99,210,60,188,277,291,8,9,212,64,294,293,295,144,198,280,160)) || in_array($adminInfo['role'], array(0)) ){?>
			<li class="<?php echo $curControlName == 'cash_withdrawal_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/cash_withdrawal_list'); ?>"><?php echo lang('cash_withdrawal_list') ?></a></li>
                        <li class="<?php echo $curControlName == 'paypal_withdrawal_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/paypal_withdrawal_list'); ?>"><?php echo lang('paypal_withdrawal_list') ?></a></li>
		<?php }?>
		<?php if( in_array($adminInfo['role'],array(0)) || check_right('commission_admin_model_right') ){?>
			<li class="<?php echo $curControlName == 'commission_admin' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/commission_admin'); ?>"><?php echo lang('commission_admin') ?></a></li>
		<?php }?>

                <?php if(in_array($adminInfo['id'],array(5))){?>
                <li class="<?php echo $curControlName == 'demote_levels' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/demote_levels'); ?>"><?php echo lang('demote_level') ?></a></li>
                <?php }?>
                
                

		<?php if(in_array($adminInfo['id'], array(1,68))){?>
			<li class="<?php echo $curControlName == 'return_back' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/return_back'); ?>"><?php echo lang('return_back') ?></a></li>
		<?php }?>
		<?php if(in_array($adminInfo['role'],array(0)) || in_array($adminInfo['id'], array(145))){?>
			<li class="<?php echo $curControlName == 'monthfee_pool_admin' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/monthfee_pool_admin'); ?>"><?php echo lang('monthfee_pool_admin') ?></a></li>
		<?php }?>

		<?php if(in_array($adminInfo['role'],array(0,1,2))){?>
			<li class="<?php echo $curControlName == 'month_fee_to_amount' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/month_fee_to_amount'); ?>"><?php echo lang('month_fee_to_amount') ?></a></li>
		<?php }?>
		<?php if(in_array($adminInfo['id'],array(1,3,5))){?>
			<li class="<?php echo $curControlName == 'cash_to_sharing_point' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/cash_to_sharing_point'); ?>"><?php echo lang('manually_sharing_point') ?></a></li>
		<?php }?>   
		<?php if(in_array($adminInfo['id'],array(1,3,5))){?>
			<li class="<?php echo $curControlName == 'sharing_point_to_cash' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/sharing_point_to_cash'); ?>"><?php echo lang('sharing_point_to_money') ?></a></li>
		<?php }?>

		<?php if(in_array($adminInfo['role'],array(0,2))){?>
			<li class="<?php echo $curControlName == 'order_repair_of_comm' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/order_repair_of_comm'); ?>"><?php echo lang('commission_order_repair') ?></a></li>
		<?php }?>

		<?php if(check_right('comm_special_admin_ids')){?>
			<li class="<?php echo $curControlName == 'commission_special_do' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/commission_special_do'); ?>"><?php echo lang('commission_special_do') ?></a></li>
		<?php }?>
        <?php if(in_array($adminInfo['role'],array(0,1,2))){?>
			<li class="<?php echo $curControlName == 'commission_month' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/commission_month'); ?>"><?php echo lang('commission_month') ?></a></li>
		<?php }?>
        <?php if(in_array($adminInfo['role'],array(0,1,2))){?>
            <li class="<?php echo $curControlName == 'incentive_system_management' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/incentive_system_management'); ?>"><?php echo lang('incentive_system_management') ?></a></li>
        <?php }?>
        <li class="<?php echo $curControlName == 'users_amount_check' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/users_amount_check'); ?>"><?php echo lang('transfer_contr') ?></a></li>
      
       <?php if(in_array($adminInfo['id'],array(1,145,280))){?>       
            <li class="<?php echo $curControlName == 'users_bonus_list_check' ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/users_bonus_list_check'); ?>"><?php echo lang("users_bonus_list_check"); ?></a>
            </li>
       <?php } ?>
       
        <li class="<?php echo $curControlName == 'bonus_plan_control' ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin/bonus_plan_control'); ?>">奖金发放监控</a>
        </li>
        <li class="<?php echo $curControlName == 'commission_special_check' ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin/commission_special_check'); ?>"><?php echo lang("commission_special_check");?></a>
        </li>
	</ul>
<?php }?>

<?php if(in_array($adminInfo['role'],array(0,1,2,5))){?>
	<div class="nav-header" data-toggle="collapse" data-target="#commssion"><i class="icon-sitemap"></i><?php echo lang('team') ?></div>
	<ul id="commssion" class="nav nav-list collapse<?php echo in_array($curControlName, array('generation_list','forced_matrix_2x5','forced_matrix_138','user_move_position')) ? ' in' : '' ?>">
		<li class="<?php echo $curControlName == 'generation_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/generation_list'); ?>"><?php echo lang('gene_tree_list') ?></a></li>
<!--		<li class="--><?php //echo $curControlName == 'forced_matrix_2x5' ? 'active' : '' ?><!--"><a href="--><?php //echo base_url('admin/forced_matrix_2x5'); ?><!--">--><?php //echo lang('forced_matrix_2x5') ?><!--</a></li>-->
		<li class="<?php echo $curControlName == 'forced_matrix_138' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/forced_matrix_138'); ?>"><?php echo lang('forced_matrix_138') ?></a></li>
		<?php if(in_array($adminInfo['role'],array(0)) || in_array($adminInfo['email'], config_item('assistant_persident_root'))){?>
		  <li class="<?php echo $curControlName == 'user_move_position' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/user_move_position'); ?>"><?php echo lang('user_move_position') ?></a></li>
	    <?php } ?>
	</ul>
<?php }?>
<div class="nav-header" data-toggle="collapse" data-target="#admin-manage-menu"><i class="icon-briefcase"></i>
	<?php echo lang('admin_account_manage'); ?>
</div>
<ul id="admin-manage-menu" class="nav nav-list collapse<?php echo in_array($curControlName, array('admin_account_list','reset_pwd','add_admin','admin_right')) ? ' in' : '' ?>">
	<?php if(in_array($adminInfo['role'],array(0)) || in_array($adminInfo['id'],array(171,8,343))){?>
		<li class="<?php echo $curControlName == 'admin_account_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/admin_account_list'); ?>"><?php echo lang('admin_account_list');?></a></li>
	<?php }?>
	<li class="<?php echo $curControlName == 'reset_pwd' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/reset_pwd') ?>"><?php echo lang('reset_pwd'); ?></a></li>
	<?php if(in_array($adminInfo['id'],array(1,5,171,8,343))){?>
		<li class="<?php echo $curControlName == 'add_admin' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/add_admin') ?>"><?php echo lang('add_admin'); ?></a></li>
	<?php }?>

	<?php if(check_right('admin_right_manage')){?>
		<li class="<?php echo $curControlName == 'admin_right' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/admin_right') ?>"><?php echo '权限管理'; ?></a></li>
	<?php }?>

</ul>
<?php if(in_array($adminInfo['role'],array(0,1,2))){?>
	<div class="nav-header" data-toggle="collapse" data-target="#news-menu"><i class="icon-file"></i><?php echo lang('news_manage'); ?></div>
	<ul id="news-menu" class="nav nav-list collapse <?php echo in_array($curControlName, array('add_news','news_list','add_bulletin_board','bulletin_board_list','admin_ads_file_manage')) ? ' in' : '' ?>">
		<li class="<?php echo $curControlName == 'news_list' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/news_list') ?>"><?php echo lang('news_list'); ?></a></li>
		<li class="<?php echo $curControlName == 'bulletin_board_list' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/bulletin_board_list') ?>"><?php echo lang('bulletin_board_list'); ?></a></li>

		<?php if(in_array($adminInfo['role'],array(0,2)) || check_right('admin_ads_file_manage')){ ?>
		<li class="<?php echo $curControlName == 'admin_ads_file_manage' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/admin_ads_file_manage'); ?>"><?php echo lang('admin_ads_file_manage'); ?></a></li>
		<?php } ?>
	</ul>
<?php }?>

<?php if(in_array($adminInfo['role'],array(0,2,3)) || in_array($adminInfo['id'],array(8,9,18,83,88,43,68,70,77)) ){?>
	<div class="nav-header" data-toggle="collapse" data-target="#goods-menu"><i class="icon-inbox"></i><?php echo lang('goods_manage'); ?></div>

	<ul id="goods-menu" class="nav nav-list collapse <?php echo in_array($curControlName, array('add_weight_fee_us','add_weight_fee','category','goods','brand','goods_group','supplier','ads','storehouse_to_supplier','international_freight')) ? ' in' : ''; ?>">
		<li class="<?php echo $curControlName == 'category' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/category/category_list'); ?>"><?php echo lang('category_list'); ?></a></li>
		<li class="<?php echo $curControlName == 'brand' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/brand/brand_list'); ?>"><?php echo lang('label_brand_list'); ?></a></li>
		<li class="<?php echo $curControlName == 'goods_group' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/goods_group/goods_group_list'); ?>"><?php echo lang('goods_group_list'); ?></a></li>
		<!--li class="<?php echo $curControlName == 'supplier' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/supplier/supplier_list'); ?>"><?php echo lang('label_supplier'); ?></a></li-->
		<li class="<?php echo $curControlName == 'add_weight_fee' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/add_weight_fee'); ?>"><?php echo lang('china_weight_fee'); ?></a></li>
		<li class="<?php echo $curControlName == 'add_weight_fee_us' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/add_weight_fee_us'); ?>"><?php echo lang('usa_weight_fee'); ?></a></li>
		<li class="<?php echo $curControlName == 'international_freight' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/international_freight'); ?>"><?php echo lang('international_freight'); ?></a></li>
		<!-- 广告管理已经迁移到 ERP，请删除TPS广告管理的相关代码 carter
		<li class="<?php echo $curControlName == 'ads' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/ads/ads_list'); ?>"><?php echo lang('ads_list'); ?></a></li>
		-->
		<!--li class="<?php echo $curControlName == 'storehouse_to_supplier' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/storehouse_to_supplier'); ?>"><?php echo lang('admin_supplier_store_code'); ?></a></li-->
	</ul>
<?php }?>

<?php if(in_array($adminInfo['role'],array(0,1,2,3,4,5,6,7))){?>
	<div class="nav-header" data-toggle="collapse" data-target="#trade-menu"><i class="icon-list"></i><?php echo lang('admin_trade_title')?></div>

	<ul id="trade-menu" class="nav nav-list collapse <?php echo in_array($curControlName, array('store_report','order_report','export_orders','trade','mvp_live_order','payment_list','edit_payment','reset_group','reset_choose_group','import_third_part_orders','coupons_manage','split_order','trade_order_logs','paypal_pending_log','export_bank_orders')) ? ' in' : ''; ?>">
		<?php if(in_array($adminInfo['role'],array(0,1,2,3,6,7)) || check_right('hk_customer_1v1_role_5')){?>
			<!-- 订单管理 -->
			<li class="<?php echo uri_string() == 'admin/trade' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/trade'); ?>"><?php echo lang('admin_trade_order'); ?></a></li>

			<?php if(!check_right('hk_customer_1v1_role_5')){ ?>
			<li class="<?php echo uri_string() == 'admin/mvp_live_order' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/mvp_live_order'); ?>"><?php echo lang('mvp_live_list'); ?></a></li>
			<?php }?>

			<?php if(!in_array($adminInfo['role'],array(6,7))){?>
				<!-- 订单修复 -->
				<li class="<?php echo uri_string() == 'admin/trade/order_repair' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/trade/order_repair'); ?>"><?php echo lang('admin_trade_repair'); ?></a></li>
			<?php }?>
		<?php }?>
		<?php if(in_array($adminInfo['role'],array(0,1,2,3,6,7)) || check_right('hk_customer_1v1_role_5')){?>
			<!-- 导出订单 -->
			<li class="<?php echo uri_string() == 'admin/export_orders' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/export_orders'); ?>"><?php echo lang('admin_export_orders'); ?></a></li>

			<!-- 导入订单 -->
			<li class="<?php echo uri_string() == 'admin/trade/order_import' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/trade/order_import'); ?>"><?php echo lang('admin_trade_order_import'); ?></a></li>

			<?php if(in_array($adminInfo['role'],array(0,1,2,3)) || check_right('hk_customer_1v1_role_5')){?>


				<?php if(in_array($adminInfo['id'],array(1))){?>

				<?php if(in_array($adminInfo['id'],array(1)) || check_right('import_third_part_orders')){?>

				<?php if(in_array($adminInfo['id'],array(1,9,131,420,464,144)) || check_right('import_third_part_orders')){?>
					<li class="<?php echo $curControlName == 'import_third_part_orders' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/import_third_part_orders'); ?>"><?php echo lang('import_third_part_orders'); ?></a></li>
				<?php }?>

				<?php if(in_array($adminInfo['role'],array(0)) || $adminInfo['id'] == 68){?>
					<li class="<?php echo $curControlName == 'payment_list' ? 'active' : '' ?>"><a href="<?php echo base_url('admin/payment_list'); ?>"><?php echo lang('payment_list');?></a></li>
				<?php }?>
				<!--						<li class="--><?php //echo uri_string() == 'admin/reset_group' ? 'active' : ''; ?><!--" ><a href="--><?php //echo base_url('admin/reset_group'); ?><!--">--><?php //echo lang('reset_group'); ?><!--</a></li>-->
				<?php if(in_array($adminInfo['id'],array(1,5,129,173)) || $adminInfo['role'] == 2){?>
					<li class="<?php echo uri_string() == 'admin/coupons_manage' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/coupons_manage'); ?>"><?php echo lang('coupons_manage'); ?></a></li>
				<?php }?>

				<?php if(!check_right('hk_customer_1v1_role_5')){ ?>
				<!--   <li class="<?php echo $curControlName == 'order_report' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/order_report'); ?>"><?php echo lang('order_report'); ?></a></li> -->
				<li class="<?php echo $curControlName == 'store_report' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/store_report'); ?>"><?php echo lang('store_report'); ?></a></li>
				<?php }?>
				<!------拆分订单-------->
				<!--						--><?php //if(in_array($adminInfo['id'],array(70))){?>
				<!--							<li class="--><?php //echo $curControlName == 'split_order' ? 'active' : ''; ?><!--" ><a href="--><?php //echo base_url('admin/split_order'); ?><!--">--><?php //echo lang('split_order'); ?><!--</a></li>-->
				<!--						--><?php //}?>

				<!-- 订单流水明细 -->
				<li class="<?php echo $curControlName == 'trade_order_logs' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/trade_order_logs'); ?>"><?php echo lang('trade_order_logs'); ?></a></li>
				<li class="<?php echo $curControlName == 'paypal_pending_log' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/paypal_pending_log'); ?>"><?php echo lang('paypal_pending_log'); ?></a></li>
				<li class="<?php echo $curControlName == 'export_customs_orders' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/export_customs_orders'); ?>"><?php echo lang('export_customs_orders'); ?></a></li>
                                <li class="<?php echo $curControlName == 'export_bank_orders' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/export_bank_orders'); ?>"><?php echo lang('export_bank_orders'); ?></a></li>
			<?php }?>
		<?php }?>
		<?php }?>
		<?php }?>
	</ul>
	<?php if(in_array($adminInfo['role'],array(0,1,2,3,4))){?>
		<div class="nav-header" data-toggle="collapse" data-target="#service"><i class="icon-star"></i><?php echo lang('label_server'); ?></div>
		<ul id="service" class="nav nav-list collapse <?php echo in_array($curControlName, array('feedback','paypal_failure_list','paypal_search','admin_after_sale_batch','admin_demo_level','after_sale_order_list','add_after_sale_order','admin_as_refund_list',"three_month_days_order", 'invoice','logistics_manage')) ? ' in' : ''; ?>">
			<?php if(in_array($adminInfo['role'],array(0,1,2,3))){?>
                                <li class="<?php echo $curControlName == 'three_month_days_order' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/three_month_days_order'); ?>"><?php echo "取消升级订单"; ?></a></li>
								
							
				<li class="<?php echo $curControlName == 'invoice' ? 'active' : ''; ?>"><a href="<?php echo base_url('admin/invoice'); ?>"><?php echo lang('invoice_manage'); ?></a></li>

				
				<li class="<?php echo $curControlName == 'add_after_sale_order' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/add_after_sale_order'); ?>"><?php echo lang('admin_add_after_sale'); ?></a></li>
				<li class="<?php echo $curControlName == 'after_sale_order_list' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/after_sale_order_list'); ?>"><?php echo lang('admin_add_after_sale_list'); ?></a></li>
			<?php }?>
                                <?php if(in_array($adminInfo['id'],array(62,198,129))){ ?>
                                <li class="<?php echo $curControlName == 'admin_demo_level' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/admin_demo_level') ?>"><?php echo '售后订单特别处理'; ?></a></li>       
                                <?php } ?>
			<?php if(in_array($adminInfo['role'],array(0,2,4)) || $adminInfo['id'] == 18 ){?>
				<li class="<?php echo $curControlName == 'admin_as_refund_list' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/admin_as_refund_list'); ?>"><?php echo lang('admin_as_refund'); ?></a></li>
				<li class="<?php echo $curControlName == 'admin_after_sale_batch' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/admin_after_sale_batch'); ?>"><?php echo lang('view_batch'); ?></a></li>
			<?php }?>
			<?php if(in_array($adminInfo['role'],array(0,1,2,))){?>
				<li class="<?php echo $curControlName == 'paypal_failure_list' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/paypal_failure_list'); ?>"><?php echo lang('admin_paypal_failure_list'); ?></a></li>
				<li class="<?php echo $curControlName == 'feedback' ? 'active' : ''; ?>" ><a href="<?php echo base_url('admin/feedback/feedback_list'); ?>"><?php echo lang('label_feedback'); ?></a></li>
			<?php }?>

			<?php if($adminInfo['id']==144){ ?>
				<li class="<?php echo $curControlName == 'logistics_manage' ? 'active' : '' ?> <?php echo $curControlName == 'logistics_manage' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/logistics_manage') ?>"><?php echo '物流接口测试'; ?></a></li>
			<?php } ?>

		</ul>
	<?php }?>

	<!--售后中心-->
	<?php if(in_array($adminInfo['role'],array(0,1,2,3))){?>
		<div class="nav-header" data-toggle="collapse" data-target="#tickets-menu"><i class="icon-file"></i><?php echo lang('tickets_center'); ?>
			<div class="unprocessed_count_box" style="float: right"><span class="unprocessed_count label label-info"></span></div>
		</div>
		<ul id="tickets-menu" class="nav nav-list collapse <?php echo in_array($curControlName, array('add_tickets','my_tickets','unassigned_tickets','all_tickets','tickets_statistics','tickets_template','tickets_black_list','tickets_customer_role','admin_knowledge','admin_knowledge_cate')) ? ' in' : '' ?>">

			<?php if($adminInfo['role'] != 3){ ?>
			<li class="<?php echo $curControlName == 'unassigned_tickets' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/unassigned_tickets') ?>"><?php echo lang('unassigned_tickets'); ?>
					<span class="unassigned_count my_badge_news label label-info"></span>
				</a></li>
			<li class="<?php echo $curControlName == 'add_tickets' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/add_tickets') ?>"><?php echo lang('add_tickets'); ?></a></li>
			<li class="<?php echo $curControlName == 'my_tickets' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/my_tickets') ?>"><?php echo lang('my_tickets'); ?></a></li>

			<?php } ?>

			<li class="<?php echo $curControlName == 'all_tickets' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/all_tickets') ?>"><?php echo lang('all_tickets'); ?></a></li>

			<?php if($adminInfo['role'] != 3){ ?>

			<?php if(check_right('tickets_statistics_right') && $curLanguage=='zh'){ ?>
			<li class="<?php echo $curControlName == 'tickets_statistics' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/tickets_statistics') ?>"><?php echo lang('tickets_statistics'); ?></a></li>
			<?php }?>

			<li class="<?php echo $curControlName == 'tickets_template' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/tickets_template') ?>"><?php echo lang('tickets_template'); ?></a></li>
			<?php if(check_right('tickets_customer_role_right')) {?>
			<li class="<?php echo $curControlName == 'tickets_customer_role' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/tickets_customer_role') ?>"><?php echo lang('tickets_customer_role'); ?></a></li>
			<?php } ?>
			<!--                            <li class="--><?php //echo $curControlName == 'tickets_black_list' ? 'active' : '' ?><!--" ><a href="--><?php //echo base_url('admin/tickets_black_list') ?><!--">--><?php //echo lang('tickets_black_list'); ?><!--</a></li>-->
            <li class="<?php echo $curControlName == 'admin_knowledge' ? 'active' : '' ?> <?php echo $curControlName == 'admin_knowledge_cate' ? 'active' : '' ?>" ><a href="<?php echo base_url('admin/admin_knowledge') ?>"><?php echo lang('admin_knowledge'); ?></a></li>

			<?php } ?>

		</ul>
	<?php }?>
    
	
    <?php if(in_array($adminInfo['id'],array(1,5,39,43,66,68,70,87,89,198,192,160,122,280,145))){?>
		<div class="nav-header" data-toggle="collapse" data-target="#system_setting"><i class="icon-wrench"></i><?php echo lang('system_setting'); ?></div>
		<ul id="system_setting" class="nav nav-list collapse<?php echo in_array($curControlName, array('execute_sql','goods_number_exception','cron_doing','user_qualified','bonus_plan','grant_user_bonus_option')) ? ' in' : '' ?>">

			<!--执行sql语句-->
			<?php if (in_array($adminInfo['id'], array(1))) { ?>
				<li class="<?php echo $curControlName == 'execute_sql' ? 'active' : ''; ?>">
					<a href="<?php echo base_url('admin/execute_sql'); ?>"><?php echo lang('execute_sql'); ?></a>
				</li>
			<?php } ?>
			<li class="<?php echo $curControlName == 'goods_number_exception' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('admin/goods_number_exception'); ?>"><?php echo lang('goods_number_exception'); ?></a>
			</li>
			<li class="<?php echo $curControlName == 'cron_doing' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('admin/cron_doing'); ?>"><?php echo lang('cron_doing'); ?></a>
			</li>
<!--			隐藏138分红入口 m by brady -->
<!--			<li class="--><?php //echo $curControlName == 'user_qualified' ? 'active' : ''; ?><!--">-->
<!--				<a href="--><?php //echo base_url('admin/user_qualified'); ?><!--">--><?php //echo lang('user_qualified'); ?><!--</a>-->
<!--			</li>-->
			<!-- 
			<li class="<?php echo $curControlName == 'grant_user_bonus_option' ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin/grant_user_bonus_option'); ?>"><?php echo lang('grant_user_hand_bonus_option'); ?></a>
            </li> 
             -->
            <?php if(in_array($adminInfo['id'],array(1,3,122,145,280,464))){?>
                <li class="<?php echo $curControlName == 'bonus_plan' ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('admin/bonus_plan'); ?>"><?php echo lang('system_bonus_ratio'); ?></a>
                </li>
            <?php } ?>
             			
		</ul>
	<?php }?>
	<!-- 开发者管理 -->    
    <?php if(in_array($adminInfo['id'],array(1,145,280,464))){?>
		<div class="nav-header" data-toggle="collapse" data-target="#develop"><i class="icon-cog"></i><?php echo lang('develop_msg'); ?></div>
		<ul id="develop" class="nav nav-list collapse<?php echo in_array($curControlName, array('pre_week_team_bonus','pre_month_team_bonus','pre_month_leader_bonus','daily_bonus_pre','new_member_bonus_pre','week_leader_preview')) ? ' in' : '' ?>">
		    
			<li class="<?php echo $curControlName == 'daily_bonus_pre' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('admin/daily_bonus_pre'); ?>"><?php echo lang('pre_day_bonus'); ?></a>
			</li>  
			<li class="<?php echo $curControlName == 'new_member_bonus_pre' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('admin/new_member_bonus_pre'); ?>"><?php echo lang('pre_new_user_bonus'); ?></a>
			</li>
			 <li class="<?php echo $curControlName == 'week_leader_preview' ? 'active' : ''; ?>">
                <a href="<?php echo base_url('admin/week_leader_preview'); ?>"><?php echo lang('week_leader_preview'); ?></a>
            </li> 
		    <li class="<?php echo $curControlName == 'pre_week_team_bonus' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('admin/pre_week_team_bonus'); ?>"><?php echo lang('pre_week_team_bonus'); ?></a>
			</li>
			<li class="<?php echo $curControlName == 'pre_month_team_bonus' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('admin/pre_month_team_bonus'); ?>"><?php echo lang('pre_month_team_bonus'); ?></a>
			</li>
			<li class="<?php echo $curControlName == 'pre_month_leader_bonus' ? 'active' : ''; ?>">
				<a href="<?php echo base_url('admin/pre_month_leader_bonus'); ?>"><?php echo lang('pre_month_leader_bonus'); ?></a>
			</li>
		</ul>
	<?php } ?>
    <!--/end 开发者管理 -->
<?php }?>
</div>
</div>
<div class="span9">
	<h2 class="page-title"><?php echo $title;?></h2>