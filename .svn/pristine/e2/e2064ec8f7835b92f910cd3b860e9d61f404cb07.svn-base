<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title;?></title>
    <link rel="shortcut icon" href="/favicon.ico">
	<meta name="keywords" content="<?php echo $keywords;?>">
	<meta name="description" content="<?php echo $description;?>">
	<link rel="canonical" href="<?php echo $canonical;?>" />
    
	<link rel="stylesheet" href="<?php echo base_url(THEME.'/css/base.css?v=1.0')?>">
	<script src="<?php echo base_url(THEME.'/js/jquery.min.js')?>"></script>
    
    <style type="text/css">
	a.orange{ color:#e65050;}
	.header-bar .nav-top li .li-b{ width:125px;}
	.catalog,.menu-cate-all-out{ width:250px;z-index:99999}
	.menu-cate-all-out dd.submenu{ left:250px; }
	.menu-cate-all-out dd.submenu li.b-t{ width:auto;}
	.menu-normal a{ padding:0 30px}
	.menu-cate-all-out dt, .menu-cate-all-out dt a{width:80%; display:inline-block;}
	.menu-cate-all-out dl.on dt{ padding-left:30px;}
	</style>
</head>
<body>
	<header class="main-header">
		<div class="header-bar">
			<div class="w1200">
				<ul class="fl nav-top">
					<li class="fl mfr-5">
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
					</li>
					<li class="fl mfr-5">
						<a class="down" href="javascript:;"><s class="qizhi-<?php echo $curCur?>"></s><?php if($curLan == 'english') {echo $curCur_flag,'&nbsp;',$curCur;} else {echo $curCur_flag,'&nbsp;',$curCur_name;}?><s class="minicart_arrow"></s></a>
						<ol class="li-b xs">
                        	<?php foreach($currency_all as $cur) {?>
							<li><a href="javascript:;" class="js-currency <?php if($curCur == $cur['currency']) echo 'orange';?>" data-v="<?php echo $cur['icon'];?>" data-n="<?php echo $cur['currency'];?>" data-name="<?php echo $cur['name'];?>"><s class="qizhi-<?php echo $cur['currency']?>"></s><?php echo $cur['icon']?>&nbsp;<span><?php if($curLan == 'english') {echo $cur['currency'];} else {echo $cur['name'];}?></span></a></li>
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
                    <li class="fl mfr-5"><?php echo lang('label_hello')?>,<a href="<?php echo base_url('ucenter')?>"	style="color:orange;"><?php echo $userInfo['email'];?></a></li>
                    <?php }?>
					<li class="fl mfr-5"><a href="<?php echo base_url('ucenter/my_collection')?>"><i class="fa fa-star-o"></i><?php echo sprintf(lang('label_wish'),$wish_count);?></a></li>
					<li class="fl mfr-5">
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
                    <?php }?>
				</div>
                <form action="<?php echo $web_host,'index/search'?>" method="get">
				<div class="tps-search fl clear">
					<input class="search-text" placeholder="<?php echo lang('label_search')?>">
					<button type="submit"><i class="fa fa-search"></i></button>
				</div>
                </form>
				<div class="header-cart">
					<a class="down" href="<?php echo $web_host,'cart'?>"><i></i><?php echo lang('label_cart')?><b class="ci-count"><?php echo $cart_item_num; ?></b><s class="minicart_arrow"></s></a>
					
				</div>
			</div>
		</div>		
	</header>
    <main>	
	<div class="nav-wp">
			<div class="w1200">
				<div class="catalog fl">
					<h3><i class="fa fa-list"></i><?php echo lang('label_category')?><i class="fa fa-angle-down"></i></h3>
					<div class="menu-cate-all-out">
                    	<?php
							$i=1;
							foreach($category_all as $cat) {
								if($cat['level'] == 0) {
									++$i;
						?>
						<dl class="dl-1">
                        	
							<dt class="clear"><a href="<?php echo $web_host,'index/category?sn=',$cat['cate_sn'];?>" class="category"><?php echo $cat['cate_name']?></a><i class="fa fa-angle-right"></i></dt>
							<dd class="submenu clear">
                            	<?php
										foreach($category_all as $sub) {
											if($sub['parent_id'] == $cat['cate_id']) {
									?>
								<ul class="clear">
									<li class="b-t"><a href="<?php echo $web_host,'index/category?sn=',$sub['cate_sn'];?>"><?php echo $sub['cate_name']?><i class="fa fa-angle-right"></i></a></li>
									<li class="n-r">
                                    <?php
												foreach($category_all as $ss) {
													if($ss['parent_id'] == $sub['cate_id']) {
											?>
                                    		<a href="<?php echo $web_host,'index/category?sn=',$ss['cate_sn'];?>"><?php echo $ss['cate_name']?></a>
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
				<div class="menu-normal fl">
 				<?php switch($curLan){
					case 'english':
				?>
                <a href="<?php echo $web_host,'index/category?sn=MRUya5BVJ5'?>"><?php echo lang('label_cate_kl_special')?></a>
                
                <a href="<?php echo $web_host,'index/category?sn=u9f0KhcZci'?>"><?php echo lang('label_cate_mom')?></a>
                <a href="<?php echo $web_host,'index/category?sn=VjCumOUrIi'?>"><?php echo lang('label_cate_beauty')?></a>
				<a href="<?php echo $web_host,'index/category?sn=zhJoOMFhRV'?>"><?php echo lang('label_cate_home')?></a>
				<a href="<?php echo $web_host,'index/category?sn=AcrsRgyBJo'?>"><?php echo lang('label_cate_outdoor')?></a>
                <?php break;
					case 'hk':
				?>
                <a href="<?php echo $web_host,'index/category?sn=MRUya5BVJ5'?>"><?php echo lang('label_cate_health')?></a>
                
                <a href="<?php echo $web_host,'index/category?sn=u9f0KhcZci'?>"><?php echo lang('label_cate_mom')?></a>
                <a href="<?php echo $web_host,'index/category?sn=VjCumOUrIi'?>"><?php echo lang('label_cate_beauty')?></a>
				<a href="<?php echo $web_host,'index/category?sn=zhJoOMFhRV'?>"><?php echo lang('label_cate_home')?></a>
				<a href="<?php echo $web_host,'index/category?sn=AcrsRgyBJo'?>"><?php echo lang('label_cate_outdoor')?></a>
                <?php break;
					case 'zh':
				?>
               <a href="<?php echo $web_host,'index/category?sn=MRUya5BVJ5'?>"><?php echo lang('label_cate_health')?></a>
                
               <a href="<?php echo $web_host,'index/category?sn=u9f0KhcZci'?>"><?php echo lang('label_cate_mom')?></a>
                <a href="<?php echo $web_host,'index/category?sn=VjCumOUrIi'?>"><?php echo lang('label_cate_beauty')?></a>
				<a href="<?php echo $web_host,'index/category?sn=zhJoOMFhRV'?>"><?php echo lang('label_cate_home')?></a>
				<a href="<?php echo $web_host,'index/category?sn=AcrsRgyBJo'?>"><?php echo lang('label_cate_outdoor')?></a>
                <?php break;
				}
				 ?>                
               </div>
			</div>
		</div>
    </main>