<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title;?></title>
	<link rel="shortcut icon" href="/favicon.ico">
	<meta name="keywords" content="<?php echo $keywords;?>" />
	<meta name="description" content="<?php echo $description;?>" />
	<link rel="canonical" href="<?php echo $canonical;?>" />
	<link href="<?php echo base_url(THEME.'/css/head.css?v=7')?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url(THEME.'/css/all.css?v=5')?>" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/store_register.css?v=1'); ?>" />
	<?php if($curLanguage == 'english'|| $curLanguage == 'kr'){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/only_english.css?v=3'); ?>" />
	<?php }?>
	<script src="<?php echo base_url(THEME.'/js/jquery-1.11.1.js')?>"></script>
	<script src="<?php echo base_url(THEME.'/js/SuperSlide2.1.js')?>"></script>
	<!--[if lt IE 9]>
	<style>.container { width: 1200px;}</style>
	<![endif]-->
</head>

<style type="text/css">
	.cp_detail h4 span { color: red; }
	.cp_detail .btn_box a.action { background-color: #fe425b; background-position: -20px -56px; border: 1px solid #fff; display: block; }
	.cp_detail .btn_box { position: relative; }
	.add_one { display: block; padding: 8px; font-size: 0; display: block; position: absolute; z-index: -2; color: #ff6600;}
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
                	<!--
                    <?php foreach($language_all as $lang) {?>
							<a class="float-left currency_type <?php if($curLan == $lang['code']) echo 'yuyan';?>" href="javascript:;" data-id="<?php echo $lang['language_id'];?>" data-v="<?php echo $lang['code'];?>"><!--i class="qizhi-<?php echo $lang['code'];?>"></i><span><?php echo $lang['name'];?></span></a>
					<?php }?>
                    -->
                    <div data-toggle="arrowdown" id="arrow1" class="user-name" style="width:100px;text-align:left"><a href="javascript:;"><i class="qizhi-<?php echo $curCur?>"></i><?php if(in_array($curLan,array('english','kr'))) {echo $curCur_flag,'&nbsp;',$curCur;} else {echo $curCur_flag,'&nbsp;',$curCur_name;}?><u class="down-icon"></u></a></div>
   
					<!--hidden-box-->
					<div data-toggle="hidden-box" id="nav-box1" class="my-yunji-box" style="left:10px">
						<ul class="js-li-currency">
							<?php foreach($currency_all as $cur) {?>
							<li style="padding-right:20%; text-align:left"><i class="qizhi-<?php echo $cur['currency']?>"></i><a href="javascript:;" class="js-currency <?php if($curCur == $cur['currency']) echo 'orange';?>" data-v="<?php echo $cur['icon'];?>" data-n="<?php echo $cur['currency'];?>" data-name="<?php echo $cur['name'];?>"><?php echo $cur['icon']?>&nbsp;<span><?php if(in_array($curLan,array('english','kr'))) {echo $cur['currency'];} else {echo $cur['name'];}?></span></a></li>
							<?php }?>
						</ul>
					</div>

				</div>
				<div class="top-right"> <span class="float-left"><?php echo lang('label_hello')?>,</span>
					<?php if(empty($userInfo)) {?>
					<a href="<?php echo base_url('login');?>" class="float-left c-b"><?php echo lang('label_login')?></a> <span class="float-left">|</span> <a href="<?php echo base_url('register');?>" class="float-left c-b"><?php echo lang('label_reg')?></a>
					<?php }else{?>
					<span class="float-left"><a href="<?php echo base_url('ucenter')?>"	style="color:orange;"><?php echo $userInfo['email'];?></a></span>
					<?php }?>
					<div data-toggle="arrowdown" id="arrow3" class="user-name" style="width:97px;"><a href="<?php echo base_url('ucenter')?>" ><s class="yhu"></s><?php echo lang('label_account')?><u class="down-icon"></u></a></div>
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
                    <s class="logo_bt" style="background-image:url(<?php if($curLan == 'english' || $curLan=='kr') echo base_url(THEME.'/img/logo_'.$curLan.'.gif'); else echo base_url(THEME.'/img/logo_'.$curLan.'.jpg')?>);<?php if($curLan == 'english'){?>width:182px;<?php }?>"></s>
                     <?php }?>
					<?php if($store_info){?>
					<div class="slogo">
						<strong><?php echo $store_info['store_name']?></strong>
						<p class="<?php echo $store_info['store_class']?>"><span><i></i><?php echo lang('level_'.$store_info['store_level'])?></span></p>
					</div>
					<?php }?>
				</div>
				<div class="search" >
					<form action="<?php echo $web_host, $sphinx_search_url?>" method="get">
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
	<div class="container clear">
		<div class="row clear">
			<div class="nav">
				<div class="sidebar">
					<h3 data-toggle="arrowdown" id="arrow6"><i class="ht"></i><?php echo lang('label_category')?><s class="jt"></s></h3>
					<!--sidebar-info-->
					<ul class="sidebar-info" data-toggle="hidden-box" id="nav-box6">
						<?php
							$i=1;
							foreach($category_all as $cat) {
								if($cat['level'] == 0) {
									++$i;
						?>
						<li class="mainCate <?php if($i%2 == 0){ ?>evenLeval<?php }?>">
							<div class="titt">
								<h3><a style="display:inline-block;width:80%;" href="<?php echo base_url(),'index/category?sn=',$cat['cate_sn'];?>"><?php echo $cat['cate_name']?></a></h3>
								<i class="menu-nav-arrow"></i>
							</div>
							<div class="subCate clear">
								<div class="subitems clear">
									<?php
										foreach($category_all as $sub) {
											if($sub['parent_id'] == $cat['cate_id']) {
									?>
									<dl class="fore1">
										<dt><a href="<?php echo base_url(),'index/category?sn=',$sub['cate_sn'];?>"><?php echo $sub['cate_name']?></a></dt>
										<dd class="t_none">
											<?php
												foreach($category_all as $ss) {
													if($ss['parent_id'] == $sub['cate_id']) {
											?>
											<a href="<?php echo base_url(),'index/category?sn=',$ss['cate_sn'];?>"><?php echo $ss['cate_name']?></a>
											<?php
													}
												}
											?>
										</dd>
									</dl>
									<?php
											}
										}
									?>
								</div>
							</div>
						</li>
						<?php
								}
							}
						?>
					</ul>
				</div>
				<div class="nav_l">
				<!--a href="<?php echo base_url(),'index/product_g'?>"><?php echo lang('label_cate_group')?></a-->
                <?php switch($curLan){
					case 'english':
				?>
                <a href="<?php echo base_url(),'index/category?sn=MRUya5BVJ5'?>"><?php echo lang('label_cate_kl_special')?></a>
                
                <a href="<?php echo base_url(),'index/category?sn=u9f0KhcZci'?>"><?php echo lang('label_cate_mom')?></a>
                <a href="<?php echo base_url(),'index/category?sn=VjCumOUrIi'?>"><?php echo lang('label_cate_beauty')?></a>
				<a href="<?php echo base_url(),'index/category?sn=zhJoOMFhRV'?>"><?php echo lang('label_cate_home')?></a>
				<a href="<?php echo base_url(),'index/category?sn=AcrsRgyBJo'?>"><?php echo lang('label_cate_outdoor')?></a>
                <?php break;
					case 'hk':
				?>
                <a href="<?php echo base_url(),'index/category?sn=MRUya5BVJ5'?>"><?php echo lang('label_cate_health')?></a>
                
                <a href="<?php echo base_url(),'index/category?sn=u9f0KhcZci'?>"><?php echo lang('label_cate_mom')?></a>
                <a href="<?php echo base_url(),'index/category?sn=VjCumOUrIi'?>"><?php echo lang('label_cate_beauty')?></a>
				<a href="<?php echo base_url(),'index/category?sn=zhJoOMFhRV'?>"><?php echo lang('label_cate_home')?></a>
				<a href="<?php echo base_url(),'index/category?sn=AcrsRgyBJo'?>"><?php echo lang('label_cate_outdoor')?></a>
                <?php break;
					case 'zh':
				?>
               <a href="<?php echo base_url(),'index/category?sn=MRUya5BVJ5'?>"><?php echo lang('label_cate_health')?></a>
                
               <a href="<?php echo base_url(),'index/category?sn=u9f0KhcZci'?>"><?php echo lang('label_cate_mom')?></a>
                <a href="<?php echo base_url(),'index/category?sn=VjCumOUrIi'?>"><?php echo lang('label_cate_beauty')?></a>
				<a href="<?php echo base_url(),'index/category?sn=zhJoOMFhRV'?>"><?php echo lang('label_cate_home')?></a>
				<a href="<?php echo base_url(),'index/category?sn=AcrsRgyBJo'?>"><?php echo lang('label_cate_outdoor')?></a>
                <?php break;
				}
				 ?>

		</div>
	</div>
</div>