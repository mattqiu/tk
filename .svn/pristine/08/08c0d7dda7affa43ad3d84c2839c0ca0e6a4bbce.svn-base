<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $title;?></title>
		<link rel="shortcut icon" href="/favicon.ico?v=2">
		<meta name="keywords" content="<?php echo $keywords;?>">
		<meta name="description" content="<?php echo $description;?>">
		<link rel="canonical" href="<?php echo $canonical;?>" />  
		<link rel="stylesheet" href="<?php echo base_url(THEME.'/css/base.css?v=1.0')?>">
		<?php if($curLan == 'english'){ ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/only_english.css?v=3'); ?>" />
		<?php }?>
		<script src="<?php echo base_url(THEME.'/js/jquery.min.js')?>"></script>    
	</head>
	<body>
		<p class="this-ts">温馨提示：为了保障购物安全，享受更棒的购物体验，建议您使用手机浏览，或者使用新版本IE浏览器或UC浏览器</p>
		<?php if($curLan == 'zh') {?>
		<div class="w-100"><s class="ggao" style=" background:url('<?php echo base_url(THEME.'/img/tz.jpg')?>') no-repeat center"></s></div>
		<?php }elseif($curLan == 'hk') {?>
		<div class="w-100"><s class="ggao" style=" background:url('<?php echo base_url(THEME.'/img/tz1.jpg')?>') no-repeat center"></s></div>
		<?php }else {?>
		<div class="w-100"><s class="ggao" style=" background:url('<?php echo base_url(THEME.'/img/tz2.jpg')?>') no-repeat center"></s></div>
		<?php }?>
		<header class="main-header">
			<div class="header-bar">
				<div class="w1200">
					<ul class="fl nav-top">
						<li class="fl mfr-5 w88">
							<?php foreach($sale_country as $country) {?>
							<?php if ($curLocation_id == $country['country_id']) {?>
							<a class="down" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag']?>"></s><?php echo $country['name_'.$curLan]?><s class="minicart_arrow"></s></a>
							<?php }?>
							<?php }?>
							<ol class="li-b xs">
								<?php foreach($sale_country as $country) {?>
								<li><a data-id="<?php echo $country['country_id']?>" data-lang="<?php echo $country['default_language']?>" data-cur="<?php echo $country['default_flag']?>" class="<?php if ($curLocation_id == $country['country_id']) echo 'orange'; ?> change_location" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag']?>"></s><?php echo $country['name_'.$curLan]?></a></li>
								<?php }?>
							</ol>
						</li>
						<!--li class="fl mfr-5 w90">
							<?php foreach($language_all as $lang) {?>
							<?php if($curLan == $lang['code']) {?>

							<a class="down" href="javascript:;"><?php echo $lang['name'];?><s class="minicart_arrow"></s></a>
							<?php }?>
							<?php }?>
							<ol class="li-b xs">
								<?php foreach($language_all as $lang) {?>
								<li class="fl"><a class="currency_type <?php if($curLan == $lang['code']) echo 'orange';?>" href="javascript:;" data-id="<?php echo $lang['language_id'];?>" data-v="<?php echo $lang['code'];?>"><?php echo $lang['name'];?></a></li>	
								<?php }?>
							</ol>
						</li-->
						<li class="fl mfr-5">
							<a class="down" href="javascript:;"><!--s class="qizhi-<?php echo $curCur?>"></s--><?php if(in_array($curLan,array('english','kr'))) {echo $curCur_flag,$curCur;} else {echo $curCur_flag,$curCur_name;}?><s class="minicart_arrow"></s></a>
							<ol class="li-b xs">
								<?php foreach($currency_all as $cur) {?>
								<li><a href="javascript:;" class="js-currency <?php if($curCur == $cur['currency']) echo 'orange';?>" data-v="<?php echo $cur['icon'];?>" data-n="<?php echo $cur['currency'];?>" data-name="<?php echo $cur['name'];?>"><!--s class="qizhi-<?php echo $cur['currency']?>"></s--><?php echo $cur['icon']?>&nbsp;<span><?php if(in_array($curLan,array('english','kr'))) {echo $cur['currency'];} else {echo $cur['name'];}?></span></a></li>
								<?php }?>
							</ol>
						</li>	
					</ul>
					<ul class="fr nav-top">
						<?php if(empty($userInfo)) {?>
						<li class="fl mfr-5"><a class="login" href="<?php echo base_url('login');?>"><?php echo lang('label_login')?></a></li>
						<li class="fl">|</li>
						<li class="fl mfr-5"><a class="login" href="<?php echo base_url('register');?>"><?php echo lang('label_reg')?></a></li>
						<?php }else{?>
						<li class="fl mfr-5"><?php echo lang('label_hello')?>,<a class="login" href="<?php echo base_url('ucenter')?>"><?php echo $userInfo['email'];?></a></li>
						<?php }?>
						<li class="fl mfr-5"><a href="<?php echo base_url('ucenter/my_collection')?>"><i class="fa fa-star"></i><?php echo sprintf(lang('label_wish'),$wish_count);?></a></li>
						<li class="fl mfr-5 w90">
							<a class="down" href="<?php echo base_url('ucenter')?>"><?php echo lang('label_account')?><s class="minicart_arrow"></s></a>
							<ol class="li-b xs">
								<li><a href="<?php echo base_url('ucenter/my_other_orders')?>"><?php echo lang('label_order')?></a></li>
								<?php if($is_login) {?>
								<li><a href="<?php echo base_url('/login/logout')?>"><?php echo lang('logout')?></a></li>
								<?php }?>
							</ol>
						</li>
					</ul>
				</div>
			</div>
			<div class="site-header">
				<div class="w1200">
					<div class="logo fl"><a href="<?php echo $web_host;?>"><img src="<?php echo $store_id == '1380141986' ? base_url(THEME.'/img/logo_1380141986.png') : base_url(THEME.'/img/tps.png');?>"></a></div>
					<div class="huiy">
						<?php if($store_info){?>
						<b><?php echo $store_info['store_name']?></b>
						<p class="p <?php echo $store_info['store_class']?>"><span><i></i><?php echo lang('level_'.$store_info['store_level'])?></span></p>
						<?php }else {?>
						<img src="<?php echo base_url(THEME.'/img/138com.png')?>">
						<?php }?>
					</div>
					<div class="header-cart">
						<a class="down" href="<?php echo $web_host,'cart'?>"><i></i><?php echo lang('label_cart')?><b class="ci-count"><?php echo $cart_item_num; ?></b><s class="minicart_arrow"></s></a>
					</div>
					<form action="<?php echo $web_host, $sphinx_search_url?>" method="get">
						<div class="tps-search fr clear">
							<input name="keywords" class="search-text" placeholder="<?php echo lang('label_search')?>">
							<button type="submit" target="_blank"><s></s></button>
						</div>
					</form>
				</div>
			</div>		
		</header>
		<main>	
			<div class="nav-wp">
				<div class="w1200">
					<div class="catalog fl">
						<h3><?php echo lang('label_category')?><i class="fa fa-angle-down"></i></h3>
						<div class="menu-cate-all-out">
							<?php
	$i=1;
foreach($category_all as $cat) {
	if($cat['level'] == 0) {

							?>
							<dl class="dl-<?php echo $i;?>">
								<dt class="clear"><a href="<?php echo $web_host,'index/category?sn=',$cat['cate_sn'];?>" class="category"><?php echo ucwords($cat['cate_name'])?></a><i class="fa fa-angle-right"></i></dt>
								<dd class="submenu clear">
									<?php
								++$i;
		foreach($category_all as $sub) {
			if($sub['parent_id'] == $cat['cate_id']) {
									?>
									<ul class="clear">
										<li class="b-t"><a href="<?php echo $web_host,'index/category?sn=',$sub['cate_sn'];?>"><?php echo ucwords($sub['cate_name'])?><i class="fa fa-angle-right"></i></a></li>
										<li class="n-r">                              
											<?php
										foreach($category_all as $ss) {
										if($ss['parent_id'] == $sub['cate_id']) {
											?>
											<a href="<?php echo $web_host,'index/category?sn=',$ss['cate_sn'];?>"><?php echo ucwords($ss['cate_name'])?></a>
											<?php
										}
									}
											?>

										</li>
									</ul>							
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
					<ul class="menu-normal fl">
						<?php if($curLan == 'english') {?>
						<li><a href="<?php echo $web_host,'index/category?sn=VjCumOUrIi';?>"><?php echo lang('label_cate_1')?></a></li>
						<li><a href="<?php echo $web_host,'index/category?sn=dc8hfvl17J';?>"><?php echo lang('label_cate_2')?></a></li>
						<li><a href="<?php echo $web_host,'index/category?sn=zhJoOMFhRV';?>"><?php echo lang('label_cate_3')?></a></li>
						<li><a href="<?php echo $web_host,'index/category?sn=d3uxgyBPpI';?>"><?php echo lang('label_cate_4')?></a></li>
						<li><a href="<?php echo $web_host,'index/category?sn=AcrsRgyBJo';?>"><?php echo lang('label_cate_5')?></a></li>
						<li><a href="<?php echo $web_host,'index/category?sn=wk1BC6QKrZ';?>"><?php echo lang('label_cate_8')?></a></li>
						<li><a href="<?php echo $web_host,'index/category?sn=8hQ3rPF5bM';?>"><?php echo lang('label_cate_9')?></a></li>
						<li><a href="<?php echo $web_host,'index/category?sn=MRUya5BVJ5';?>"><?php echo lang('label_cate_10')?></a></li>
						<?php }else {?>
						<?php
								$i=1;
								foreach($category_all as $cat) {
									if($cat['level'] == 0) {						

										if($curLan == 'english' && $i >= 8) {break;}
										++$i;	
						?>
						<li><a target="_blank" href="<?php echo $web_host,'index/category?sn=',$cat['cate_sn']?>"><?php echo $cat['cate_name']?></a></li>
						<?php }}}?>
					</ul>
				</div>
			</div>
		</main>