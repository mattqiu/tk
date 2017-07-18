<!DOCTYPE html>
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">-->
<html>
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="<?php echo base_url('favicon.ico?v=3'); ?>"/>
        <link rel="bookmark" href="<?php echo base_url('favicon.ico?v=3'); ?>"/>
        <title><?php echo $title . '-' . lang('tps_ucenter') ?></title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <meta name="viewport" content="width=device-width, initial-scale=0.25 minimum-scale=0.25, maximum-scale=1.0, user-scalable=yes" />
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/bootstrap/css/bootstrap.css?v=1'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/stylesheets/theme.css?v=30'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('ucenter_theme/lib/font-awesome/css/font-awesome.css?v=1'); ?>">
        <script src="<?php echo base_url('js/jquery-1.11.2.min.js'); ?>" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo base_url('ucenter_theme/js/jquery.zclip.min.js?v=1')?>"></script>
		
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <script src="<?php echo base_url('js/base.js?v=18'); ?>"></script>
        <script src="<?php echo base_url('ucenter_theme/js/base.js?v=45'); ?>"></script>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/dd.css?v=2'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/flags.css?v=2'); ?>" />
        <?php if($curLanguage == 'english'){ ?>
        	<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/en.css?v=4'); ?>" />
    	<?php }else if($curLanguage == 'kr'){?>
			<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/kr.css?v=4'); ?>" />
    	<?php }?>
        <script src="<?php echo base_url('js/msdropdown/jquery.dd.min.js?v=2'); ?>"></script>
        <script src="<?php echo base_url('js/msdropdown/init.js?v=6'); ?>"></script>

		<script>
			var _hmt = _hmt || [];
			(function() {
				var hm = document.createElement("script");
				hm.src = "//hm.baidu.com/hm.js?578f5b60e0b8aa397afd3ca1332c296d";
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(hm, s);
			})();
		</script>
    </head>

    <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
    <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
    <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
    <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
    <!--[if (gt IE 9)|!(IE)]><!--> 
    <body style="background:#dfdfdf;"> 
        <!--<![endif]-->
    <div class="top_bg">
        <div class="navbar">
           	 <div class="navbar-inner">
            	<ul class="nav pull-left" style="padding-top:0px;">
                	<li><a href="<?php echo base_url('/'); ?>">
					<?php if($store_id == '1380141986') {?>
                    <img src="<?php echo base_url(THEME.'/img/logo_1380141986.png');?>">
                    <?php }else {?>
                    <img src="../../../img/new/ucenter/logo.png">
                    <?php }?>
                    </a></li>
                    <li style="margin:30px 0 0 0px;"><span style="color:#fff;font-size:1.25em;"><?php echo lang('tps_ucenter') ?></span></li>
                </ul>
            
                <ul class="nav pull-right" style="padding-top:8px;margin-right:1%">

                    <li><a href="<?php echo base_url('cart');?>" style="text-decoration:none;"><div class="car_bg"><span style="color:#f57403;font-weight:bold;position:relative;top:-5px;<?php echo $cart_item_num > 9 ? 'left:13px;': 'left:17px;'; ?>"><?php echo $cart_item_num?></span><span style="color:#fff;margin-left:30px;position:relative;top:10px;"><?php echo lang('label_cart')?></span></div></a></li>
                	<!--<li class="h_mycart"><a href="<?php echo base_url('cart');?>"><?php echo lang('label_cart')?></a></li>-->
					<?php if($is_store === TRUE){ ?>
                    <li><a href="<?php echo 'http://'.$userInfo['member_url_prefix'].'.'.get_public_domain()?>" style="color:#fff;" target="_blank"><?php echo lang('tps_home'); ?></a></li>
                    <?php }?>

                    <li id="fat-menu" class="dropdown">
                        <a href="##" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i>
                            <?php echo $userInfo['name']?ucwords($userInfo['name']):$userInfo['email']; ?>
                            <?php if($is_store === TRUE){?>
                            <li><a tabindex="-1" href="<?php echo base_url('ucenter/account_info')?>"><?php echo lang('my_account'); ?></a></li>
							<?php }?>
                        </a>
                       
                    </li>
                    <li><a id="login_logout" tabindex="-1" href="<?php echo base_url('login/logout'); ?>"><?php echo lang('logout') ?></a></li>
                   
                    

                    <li>
                        <div class="language">
                        	<ul>
								<?php foreach($sale_country as $country) {?>
									<li><a data-id="<?php echo $country['country_id']?>" data-lang="<?php echo $country['default_language']?>" data-cur="<?php echo $country['default_flag']?>"
										   data-goods-sn="<?php echo isset($goods_sn)?$goods_sn:''?>" data-jump="TRUE" data-goods-sn-main="<?php echo isset($goods_sn_main)?$goods_sn_main:''?>" class="<?php if ($curLocation_id == $country['country_id']) echo 'cur_lan'; ?>
								   change_location" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag']?>"></s><?php echo $country['name_'.$curLan]?></a></li>
								<?php }?>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        </div>


        <?php if(isset($visitor)&& $visitor =='admin'){?>
        <script>
            $(document).ready(function(){
                $('#UserCenterMaxContainer input').attr("readonly", "readonly");
                $(".process").attr("disabled", "disabled");
                $('#monthly_fee_coupon_block a').attr("href","###");
                $("#UserCenterMaxContainer input[type='button']").attr("disabled","disabled");
            });
        </script>
            <div style="text-align:center;color:#ff0000" class="admin_login_alert"><?php echo lang('admin_login_alert')?></div>
        <?php }?>
      </div>
		<!--<div class="tb1024">-->
        	<div class="sidebar-nav" status="">

            <!-- Welcome-->
            <!--<a href="<?php echo base_url('ucenter'); ?>" class="nav-header<?php echo $curControlName == 'welcome' ? ' active' : '' ?>" >
                <i class="icon-home"></i><?php echo lang('welcome_page'); ?>
            </a>-->
            <a href="<?php echo base_url('ucenter'); ?>" class="nav-header<?php echo $curControlName == 'welcome_new' ? ' active' : '' ?>" >
                <i class="icon-home"></i><?php echo lang('welcome_page'); ?>
            </a>

			<?php if($is_store === TRUE){ ?>
			<!--佣金-->
			<a href="#commission" class="nav-header" data-toggle="collapse">
				<i class="icon-money"></i><?php echo lang('commission') ?>
			</a>
			<ul id="commission" class="nav nav-list collapse <?php echo in_array($curControlName,array('alipay_binding_unbundling','commission','commission_report','commission_order_repair','profit_sharing_point_log','profit_sharing_point_to_money_log','user_monthly_fee_report','take_out_cash','infinite_generation_info','cash_take_out_logs','generation_sales','paypal_binding'))? 'in' : '' ?>">
				<li class="<?php echo $curControlName == 'commission' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/commission'); ?>">
						<?php echo lang('dashboard'); ?>
					</a>
				</li>
				<li class="<?php echo $curControlName == 'commission_report' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/commission_report'); ?>">
						<?php echo lang('commission_report'); ?>
					</a>
				</li>

				<li class="<?php echo $curControlName == 'commission_order_repair' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/commission_order_repair'); ?>">
					<?php echo lang('commission_order_repair'); ?>
					<span class="my_badge_news <?php echo isset($order_repair_count)&&$order_repair_count>0?'label label-info':''?>" style="float:right;margin-right:60px;background-color:red;"><?php echo $order_repair_count>0?'+'.$order_repair_count:''?></span>
					</a>
				</li>

				<li class="<?php echo in_array($curControlName,array('alipay_binding_unbundling','take_out_cash','paypal_binding')) ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/take_out_cash'); ?>">
						<?php echo lang('take_out_cash'); ?>
					</a>
				</li>
				<li class="<?php echo $curControlName == 'profit_sharing_point_log' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/profit_sharing_point_log'); ?>">
						<div style="width:80%;"><?php echo lang('profit_sharing_point_log'); ?></div>
					</a>
				</li>
				<li class="<?php echo $curControlName == 'profit_sharing_point_to_money_log' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/profit_sharing_point_to_money_log'); ?>">
						<div style="width:75%;"><?php echo lang('profit_sharing_point_to_money_log'); ?></div>
					</a>
				</li>
<!--				<li class="--><?php //echo $curControlName == 'user_monthly_fee_report' ? 'active' : '' ?><!--">-->
<!--					<a href="--><?php //echo base_url('ucenter/user_monthly_fee_report'); ?><!--">-->
<!--						<div style="width:75%;">--><?php //echo lang('monthly_fee_detail'); ?><!--</div>-->
<!--					</a>-->
<!--				</li>-->
				<li class="<?php echo $curControlName == 'cash_take_out_logs' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/cash_take_out_logs'); ?>">
						<div style="width:75%;"><?php echo lang('cash_take_out_logs'); ?></div>
					</a>
				</li>
				<!--                <li class="--><?php //echo $curControlName == 'generation_sales' ? 'active' : '' ?><!--">-->
				<!--                    <a href="--><?php //echo base_url('ucenter/generation_sales');?><!--">-->
				<!--                        --><?php //echo lang('generation_sales'); ?>
				<!--                    </a>-->
				<!--                </li>-->
				<!--                <li class="--><?php //echo $curControlName == 'infinite_generation_info' ? 'active' : '' ?><!--">-->
				<!--                    <a href="--><?php //echo base_url('ucenter/infinite_generation_info'); ?><!--">-->
				<!--                        --><?php //echo lang('infinity_info'); ?>
				<!--                    </a>-->
				<!--                </li>-->

			</ul>
			<!--/佣金-->
			<?php }?>

			<?php if($is_store === TRUE){ ?>
			<!-- 团队树形结构 -->
			<?php $arr = array('genealogy_tree','forced_matrix_2x5','forced_matrix_138','sponsorship')?>
			<a href="#team-menu" class="nav-header" data-toggle="collapse"><i class="icon-glass"></i><?php echo lang('team'); ?></a>
			<ul id="team-menu" class="nav nav-list collapse <?php echo in_array($curControlName,$arr)? 'in' : '' ?>">
				<li class="<?php echo $curControlName == 'genealogy_tree' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/genealogy_tree'); ?>"><?php echo lang('gene_tree_list'); ?></a></li>
				<!-- <li class="<?php echo $curControlName == 'forced_matrix_2x5' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/forced_matrix_2x5'); ?>">
						<?php echo lang('forced_matrix_2x5'); ?>
					</a>
				</li> -->
				<li class="<?php echo $curControlName == 'forced_matrix_138' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/forced_matrix_138'); ?>">
						<?php echo lang('forced_matrix_138'); ?>
					</a>
				</li>
				<li class="<?php echo $curControlName == 'sponsorship' ? 'active' : '' ?>">
					<a href="<?php echo base_url('ucenter/sponsorship'); ?>">
						<?php echo lang('directly'); ?>
					</a>
				</li>
			</ul>
			<?php }?>

			<?php if($is_store === TRUE){ ?>
			<!--會員升級-->
			<a href="<?php echo base_url('ucenter/member_upgrade');?>" class="nav-header <?php echo $curControlName == 'member_upgrade' ? ' active' : '' ?>">
				<i class="icon-hand-up"></i>
				<?php echo lang('member_upgrade'); ?>
			</a>
			<!--/會員升級-->
			<?php }?>

			<!-- 订单中心 -->
			<?php $arr = array('my_orders_new','refund_and_replace_list','my_orders','my_other_orders','my_orders_action','my_one_direct_orders','my_walmart_orders')?>
			<a href="#order_center-menu" class="nav-header" data-toggle="collapse"><i class="icon-briefcase"></i><?php echo lang('order_center'); ?></a>
				<ul id="order_center-menu" class="nav nav-list collapse <?php echo in_array($curControlName,$arr)? 'in' : '' ?>">
					<?php if($is_store === TRUE){ ?>
				<li class="<?php echo $curControlName == 'my_orders_new' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_orders_new'); ?>"><?php echo lang('my_tps_orders'); ?></a></li>
					<?php }?>
				<li class="<?php echo $curControlName == 'my_other_orders' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_other_orders'); ?>"><?php echo lang('my_orders'); ?></a></li>

				<!--我的沃好店铺订单-->
				<?php if($userInfo['sync_walhao']){?>
					<li class="<?php echo $curControlName == 'my_orders'? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_orders'); ?>"><?php echo lang('my_walhao_store_orders'); ?></a></li>
				<?php }?>
				<!--/我的沃好店铺订单-->

				<li class="<?php echo $curControlName == 'my_one_direct_orders' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_one_direct_orders'); ?>"><?php echo lang('my_one_direct_orders'); ?></a></li>
				<li class="<?php echo $curControlName == 'my_walmart_orders' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_walmart_orders'); ?>"><?php echo lang('my_affiliate'); ?></a></li>

				<!--<li class="<?php /*echo $curControlName == 'refund_and_replace_list' ? 'active' : '' */?>"><a href="<?php /*echo base_url('ucenter/refund_and_replace_list'); */?>"><?php /*echo lang('refund_and_replace_list'); */?></a></li>-->
			</ul>
			<!-- /订单中心 -->

			<?php if($is_store === TRUE){ ?>
			<!--领导排行榜  -->
			<?php /*?><a href="<?php echo base_url('ucenter/leadership_bulletin');?>" class="nav-header <?php echo $curControlName == 'leadership_bulletin' ? ' active' : '' ?>">
				<i class="icon-sitemap"></i>
				<?php echo lang('leadership_bulletin'); ?>
			</a><?php */?>
			<?php }?>

			<?php if($is_store === TRUE){ ?>
			<!-- 獎勵制度介紹-->
			<a href="<?php echo base_url('ucenter/rewards_introduced'); ?>" class="nav-header<?php echo $curControlName == 'rewards_introduced' ? ' active' : '' ?>" >
				<i class="icon-trophy"></i><?php echo lang('rewards_introduced'); ?>
			</a>
			<?php }?>

			<?php if($is_store === TRUE){ ?>
			<!--职称晋升的介绍 -->
			<a href="<?php echo base_url('ucenter/rank_advancement');?>" class="nav-header <?php echo $curControlName == 'rank_advancement' ? ' active' : '' ?>">
				<i class="icon-bookmark-empty"></i>
				<?php echo lang('rank_advancement'); ?>
			</a>
			<?php } ?>

			<?php if($is_store === TRUE){ ?>
			<!--资源库  -->
			<?php $arr = array('video_and_call','faq','global_payment_system','policy_procedures','file_download','ba_agreements','about_exchange_coupon')?>
			<a href="#resources" class="nav-header" data-toggle="collapse"><i class="icon-cog"></i><?php echo lang('resources'); ?></a>
			<ul id="resources" class="nav nav-list collapse <?php echo in_array($curControlName,$arr)? 'in' : '' ?>">
				<li class="<?php echo $curControlName == 'policy_procedures' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/policy_procedures'); ?>"><div style="width:75%;"><?php echo lang('policy_procedures'); ?></div></a></li>
				<li class="<?php echo $curControlName == 'faq' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/faq'); ?>"><?php echo lang('faq'); ?></a></li>
				<li class="<?php echo $curControlName == 'about_exchange_coupon' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/about_exchange_coupon'); ?>"><?php echo lang('about_exchange_coupon'); ?></a></li>
                <li class="<?php echo $curControlName == 'file_download' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/file_download'); ?>"><?php echo lang('file_download'); ?></a></li>
			</ul>
			<?php } ?>

            <!--我的消息-->
<!--            <a href="--><?php //echo base_url('ucenter/my_msg'); ?><!--" class="nav-header--><?php //echo $curControlName == 'my_msg' ? ' active' : '' ?><!--" >-->
<!--                <i class="icon-home"></i>--><?php //echo lang('my_msg'); ?><!--<span class="my_badge_news"></span>-->
<!--            </a>-->

            <!-- 我的账户信息 -->
            <?php $arr = array('account_info','shipping_addr','change_pwd','my_addresses','account_safe','change_funds_pwd','change_email','funds_change_report','my_coupons','my_collection','change_mobile')?>
            <a href="#accounts-menu" class="nav-header" data-toggle="collapse"><i class="icon-user"></i><?php echo lang('my_account'); ?></a>
            <ul id="accounts-menu" class="nav nav-list collapse <?php echo in_array($curControlName,$arr)? 'in' : '' ?>">
				<?php if($is_store === TRUE){ ?>
					<li class="<?php echo $curControlName == 'funds_change_report' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/funds_change_report'); ?>"><?php echo lang('funds_change_report'); ?></a></li>
					<li class="<?php echo $curControlName == 'account_info' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/account_info'); ?>"><?php echo lang('account_info'); ?></a></li>
					<?php if($userInfo['country_id'] == '1'){ ?>
					<li class="<?php echo $curControlName == 'change_mobile' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/change_mobile'); ?>"><?php echo lang('change_mobile'); ?></a></li>
					<?php }?>
					<li style="border-bottom:0px" class="<?php echo $curControlName == 'my_addresses' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_addresses'); ?>""><?php echo lang('my_addresses'); ?></a></li>
					<li class="<?php echo $curControlName == 'my_coupons' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_coupons'); ?>"><?php echo lang('my_coupons'); ?></a></li>
				<?php } ?>
					<li class="<?php echo $curControlName == 'change_email' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/change_email'); ?>""><?php echo lang('email_reset'); ?></a></li>
					<li class="<?php echo $curControlName == 'change_pwd' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/change_pwd'); ?>"><?php echo lang('reset_pwd'); ?></a></li>
				<?php if($is_store === TRUE){ ?>
					<li class="<?php echo $curControlName == 'change_funds_pwd' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/change_funds_pwd'); ?>""><?php echo lang('funds_pwd_reset'); ?></a></li>
				<?php } ?>
					<li class="<?php echo $curControlName == 'my_collection' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_collection'); ?>"><?php echo lang('my_collection'); ?></a></li>

				<!--
				<li class="<?php echo $curControlName == 'shipping_addr' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/shipping_addr'); ?>"><?php echo lang('shipping_addr'); ?></a></li>
				<li class="<?php echo $curControlName == 'account_safe' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/account_safe'); ?>"><?php echo lang('account_safe'); ?></a></li>
				-->
            </ul>

				<!-- 售后服务 -->
				<?php $arr = array('add_tickets','my_tickets')?>
				<a href="#accounts-tickets" class="nav-header" data-toggle="collapse"><i class="icon-thumbs-up"></i><?php echo lang('tickets_center'); ?><?php if(!empty($new_msg_num)){?> <span style="margin-top: 1.5em;width: 0px;height: 5px;border-radius: 8px;" class="my_badge_news label label-info"></span><?php } ?></a>
				<ul id="accounts-tickets" class="nav nav-list collapse <?php echo in_array($curControlName,$arr)? 'in' : '' ?>">
					<li class="<?php echo $curControlName == 'add_tickets' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/add_tickets'); ?>" ><?php echo lang('add_tickets'); ?></a></li>
					<li class="<?php echo $curControlName == 'my_tickets' ? 'active' : '' ?>"><a href="<?php echo base_url('ucenter/my_tickets'); ?> "><?php echo lang('my_tickets'); ?></a></li>
				</ul>

			<a href="<?php echo base_url('ucenter/bulletin_board'); ?>" class="nav-header<?php echo $curControlName == 'bulletin_board' ? ' active' : '' ?>" >
				<span  id = "my_badge_news_bulletin" class="my_badge_news <?php echo (isset($unread_count) && !empty($unread_count) && $unread_count !=='0')?'label label-info':''?>"><?php echo (isset($unread_count) && !empty($unread_count) && $unread_count !=='0')?'+'.$unread_count:''?></span><i class="icon-bell"></i><?php echo lang('Bulletin_title'); ?>
			</a>
        </div>
        <div class="content">
            <div class="header">
				<h1 class="page-title"><?php echo $title;?></h1>
			</div>

            <div class="container-fluid" id="UserCenterMaxContainer">

                <div class="row-fluid">
                    <div class="main">



<?php
if(isset($_COOKIE['login_wohao_url']) && !empty($_COOKIE['login_wohao_url']))
{
	$login_wohao_url = $_COOKIE['login_wohao_url'];
	delete_cookie("login_wohao_url");
} else {
	$login_wohao_url = '';
}
?>

<script>
	$(function(){
		var login_wohao_url = "<?php echo $login_wohao_url ?>";
		if (login_wohao_url.length >0) {
			$.ajax({
				type: "get",
				url: login_wohao_url,
				xhrFields: {
					withCredentials: true
				},
				crossDomain: true,
				dataType: "jsonp",
				jsonp:"jsoncallback",
				success: function (res) {

				}
			});
		}
	})
</script>
