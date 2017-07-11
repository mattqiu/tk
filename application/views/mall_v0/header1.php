<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title;?></title>
	<link rel="shortcut icon" href="/favicon.ico">
	<meta name="keywords" content="<?php echo $keywords;?>" />
	<meta name="description" content="<?php echo $description;?>" />
	<link rel="canonical" href="<?php echo $canonical;?>" />
	<link href="<?php echo base_url(THEME.'/css/head.css?v=5')?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(THEME.'/css/all.css?v=3')?>" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/store_register.css?v=2'); ?>" />
	<?php if($curLanguage == 'english'){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/only_english.css?v=2'); ?>" />
	<?php }?>
	<script src="<?php echo base_url(THEME.'/js/jquery-1.11.1.js')?>"></script>
	<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
	<!--[if lt IE 9]>
	<style>.container { width: 1200px;}</style>
	<![endif]-->
</head>

<style>
	.language-box li { padding-left: 20%; }
	.no-bak { float: left; height: 36px; line-height: 36px; padding: 0 10px; text-align: center; }
	.blue{ color:#497fb1;}
	.float-left.currency_type{ width:60px; text-align:center}
</style>

<body>
	<div class="header">
		<div class="top-wrapper">
			<div class="container clear">
				<div class="row">
					<div class="top-left">
                    <?php foreach($language_all as $lang) {?>
							<a class="float-left currency_type <?php if($curLan == $lang['code']) echo 'yuyan';?>" href="javascript:;" data-id="<?php echo $lang['language_id'];?>" data-v="<?php echo $lang['code'];?>"><!--i class="qizhi-<?php echo $lang['code'];?>"></i--><span><?php echo $lang['name'];?></span></a>
					<?php }?>
                    <div data-toggle="arrowdown" id="arrow1" class="user-name" style="width:100px;text-align:left"><a href="javascript:;"><i class="qizhi-<?php echo $curCur?>"></i><?php if($curLan == 'english') {echo $curCur_flag,'&nbsp;',$curCur;} else {echo $curCur_flag,'&nbsp;',$curCur_name;}?><u class="down-icon"></u></a></div>
                     <!--div data-toggle="arrowdown" id="arrow2" class="user-name">

                        <a href="javascript:;"><i class="qizhi-usa"></i><?php echo lang('label_ship_to')?>：<span class="js_location"></span><u class="down-icon"></u></a>

                    </div-->
					<!--hidden-box-->
					<div data-toggle="hidden-box" id="nav-box1" class="my-yunji-box" style="left:262px">
						<ul class="js-li-currency">
							<?php foreach($currency_all as $cur) {?>
							<li style="padding-right:20%; text-align:left"><i class="qizhi-<?php echo $cur['currency']?>"></i><a href="javascript:;" class="js-currency <?php if($curCur == $cur['currency']) echo 'orange';?>" data-v="<?php echo $cur['icon'];?>" data-n="<?php echo $cur['currency'];?>" data-name="<?php echo $cur['name'];?>"><?php echo $cur['icon']?>&nbsp;<span><?php if($curLan == 'english') {echo $cur['currency'];} else {echo $cur['name'];}?></span></a></li>
							<?php }?>
						</ul>
					</div>
					<div data-toggle="hidden-box" id="nav-box2" class="stow-box js-change-location">
                        <ul>
                        	<?php 
							foreach($sale_country as $country ){								
							?>

                            <li><a class="ellipsis <?php if($country['country_id'] == $cur_location_code) {echo 'sel';}?>" title="<?php echo $country['name_'.$curLan]?>" data-code="<?php echo $country['country_id'];?>" href="javascript:;" ><?php echo $country['name_'.$curLan]?></a></li>

                            <?php 
							}
							?>
                        </ul>
                    </div>
				</div>
					<div class="top-right"> <span class="float-left"><?php echo lang('label_hello')?>,</span>
						<?php if(empty($userInfo)) {?>
						<a href="<?php echo base_url('login');?>" class="float-left c-b"><?php echo lang('label_login')?></a> <span class="float-left">|</span> <a href="<?php echo base_url('register');?>" class="float-left c-b"><?php echo lang('label_reg')?></a>
						<?php }else{?>
						<span class="float-left"><a href="<?php echo base_url('ucenter')?>" style="color:orange;"><?php echo $userInfo['email'];?></a></span>
						<?php }?>
						<div data-toggle="arrowdown" id="arrow3" class="user-name" style="width:97px;"><a href="<?php echo base_url('ucenter')?>"><s class="yhu"></s><?php echo lang('label_account')?><u class="down-icon"></u></a></div>
						<div data-toggle="arrowdown" id="arrow4" class="no-bak" style="width:98px;"><a href="<?php echo base_url('ucenter/my_collection')?>"><s class="xh"></s><?php echo sprintf(lang('label_wish'),$wish_count);?></a><!--u class="down-icon"--></div>
						<!--a	style="width:67px" href="<?php echo base_url(),'index/help'?>" class="float-left"><s class="bz"></s><?php echo lang('label_help')?></a-->
						<!--hidden-box-->
						<div data-toggle="hidden-box" id="nav-box3" class="language-box" style="right:128px;width:117px;">
							<ul>
								<li><a href="<?php echo base_url('ucenter/my_other_orders')?>"><?php echo lang('label_order')?></a></li>
								<!--li><a href="<?php echo base_url('ucenter/shipping_addr')?>"><?php echo lang('label_address')?></a></li-->
                                <!--li><a href="<?php echo base_url('index/feedback')?>"><?php echo lang('label_feedback')?></a></li-->
								<?php if($is_login) {?>
								<li><a href="<?php echo base_url('/login/logout')?>"><?php echo lang('logout')?></a></li>
								<?php }?>
							</ul>
						</div>
						<!--div data-toggle="hidden-box" id="nav-box4" class="list-box">
								<ul>
									<li><a href="">Register</a></li>
									<li><a href="">Login</a></li>
								</ul>
							</div-->
					</div>
				</div>
			</div>
		</div>
		<div class="top-main clear">
			<div class="container clear">
				<div class="row">
					<div class="logo"><a href="<?php echo base_url();?>"><img src="<?php echo $store_id == '1380141986' ? base_url(THEME.'/img/logo_1380141986.png') : base_url(THEME.'/img/logo.png');?>"></a>
                   		 <?php if($store_id == '1380141986') {?>
                         <s class="logo_bt" style="background-image:url(<?php echo base_url(THEME.'/img/logo_'.$curLan.'_'.$store_id.'.png')?>);width:182px;"></s>
                         <?php }else {?>
						<s class="logo_bt" style="background-image:url(<?php if($curLan == 'english') echo base_url(THEME.'/img/logo_'.$curLan.'.gif'); else echo base_url(THEME.'/img/logo_'.$curLan.'.jpg')?>);<?php if($curLan == 'english'){?>width:182px;<?php }?>"></s>
						<?php }?>
						<?php if($store_info){?>
							<div class="slogo">
								<strong><?php echo $store_info['store_name']?></strong>
								<p class="<?php echo $store_info['store_class']?>"><span><i></i><?php echo lang('level_'.$store_info['store_level'])?></span></p>
							</div>
						<?php }?>
					</div>
					<div class="search" >
						<form action="<?php echo base_url(),'index/search'?>" method="get">
							<div class="search_input">
								<input name="keywords" type="text" class="search_url" placeholder="<?php echo lang('label_search')?>">
								<input name="" type="submit" class="search_submit" value="">
							</div>
						</form>
					</div>
						<div class="hd_cart">
							<a href="/cart">
								<i class="ci-left"></i>
								<b class="ci-count<?php if ($cart_item_figure > 1) echo " ss"; ?>"><?php echo $cart_item_num; ?></b>
								<span><?php echo lang('label_cart')?></span>
							</a>
						</div>
				</div>
			</div>
		</div>
	</div>