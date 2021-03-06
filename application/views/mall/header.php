<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title;?></title>
	<link rel="shortcut icon" href="/favicon.ico?v=5">
	<meta name="keywords" content="<?php echo $keywords;?>" />
	<meta name="description" content="<?php echo $description;?>" />
	<link rel="canonical" href="<?php echo $canonical;?>" />
	<link rel="stylesheet" href="<?php echo base_url(THEME.'/css/style.css?v=5.0')?>">
	<link rel="stylesheet" href="<?php echo base_url(THEME.'/css/base.css?v=5.0')?>">
	<?php if($curLan == 'hk'){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/hk.css?v=5'); ?>" />
	<?php }?>
	<?php if($curLan == 'english'){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/only_english.css?v=5'); ?>" />
	<?php }?>
	<?php if($curLan == 'kr'){ ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/kr.css?v=5'); ?>" />
	<?php }?>
	<script src="<?php echo base_url(THEME.'/js/jquery.min.js?v=5')?>"></script>
	<script src="<?php echo base_url(THEME.'/js/SuperSlide2.1.js?v=5')?>"></script>
	<script src="<?php echo base_url('themes/admin/layer/layer.js?v=5'); ?>"></script>
    <script>
		$(function(){ //ie9.0以下提示升级ie
			if($.browser && $.browser.msie && $.browser.version < 9.0) {
				$('.this-ts').show();
			}
		});

		//百度统计
		window.onload = function(){
			var _hmt = _hmt || [];
			(function() {
				var hm = document.createElement("script");
				hm.src = "/themes/mall/js/baidu_statistics.js?v=5";
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(hm, s);
			})();
		}

		$(function(){
			$('#tog').click(function(){
				// $('.w-100').slideDown(260);
			},function(){
				$('.w-100').slideUp(260);
			})
		});
	</script>
</head>

<body>

	<?php 
		if (@$mall_ads_list[$mall_ads_title[$curLocation_id]['top']][0]) {
	?>
	<div class="w-100">
		<a href="<?php echo $mall_ads_list[$mall_ads_title[$curLocation_id]["top"]][0]['action_val'] ?>" target="_blank">
			<s class="ggao" style=" background:url('<?php echo $img_host.'/'.$mall_ads_list[$mall_ads_title[$curLocation_id]["top"]][0]['ad_img']?>') no-repeat center"></s>
		</a>
		<span id="tog">×</span>
	</div>
    <?php } ?>

	<div class="main-header">
		<!--main-header start-->
		<div class="header-bar">
			<div class="w1200">
				<ul class="fl nav-top">
					<li class="fl mfr-5 w90">
                    	<?php foreach($sale_country as $country) {?>
                        	<?php if ($curLocation_id == $country['country_id']) {?>
							<a class="down" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag']?>"></s><?php echo $country['name_'.$curLan]?><s class="minicart_arrow"></s></a>
                            <?php }?>
                        <?php }?>
						<ol class="xs li-b">
                        	<?php foreach($sale_country as $country) {?>
							<li><a data-id="<?php echo $country['country_id']?>" data-lang="<?php echo $country['default_language']?>" data-cur="<?php echo $country['default_flag']?>"
								   data-goods-sn="<?php echo isset($goods_sn)?$goods_sn:''?>" data-jump="<?php echo isset($jump)?TRUE:''?>" data-goods-sn-main="<?php echo isset($goods_sn_main)?$goods_sn_main:''?>" class="<?php if ($curLocation_id == $country['country_id']) echo 'c-o'; ?>
								   change_location" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag']?>"></s><?php echo $country['name_'.$curLan]?></a></li>
							<?php }?>
						</ol>
					</li>
					<li class="fl mfr-5">
						<a class="down" href="javascript:;"><!--s class="qizhi-<?php echo $curCur?>"></s--><?php if(in_array($curLan,array('english','kr'))) {echo $curCur_flag,$curCur;} else {echo $curCur_flag,$curCur_name;}?><s class="minicart_arrow"></s></a>
						<ol class="xs li-b">
                        	<?php foreach($currency_all as $cur) {?>
							<li><a href="javascript:;" class="js-currency <?php if($curCur == $cur['currency']) echo 'c-o';?>" data-v="<?php echo $cur['icon'];?>" data-n="<?php echo $cur['currency'];?>" data-name="<?php echo $cur['name'];?>"><!--s class="qizhi-<?php echo $cur['currency']?>"></s--><?php echo $cur['icon']?>&nbsp;<span><?php if(in_array($curLan,array('english','kr'))) {echo $cur['currency'];} else {echo $cur['name'];}?></span></a></li>
							<?php }?>
						</ol>
					</li>
				</ul>
				<ul class="fr nav-top">
					<?php if(empty($userInfo)) {?>
						<li class="fl mfr-5"><a class="c-o" href="<?php echo base_url('login');?>"><?php echo lang('label_login')?></a></li>
						<li class="fl">|</li>
						<li class="fl mfr-5"><a class="c-o" href="<?php echo base_url('register');?>"><?php echo lang('label_reg')?></a></li>
                    <?php }else{?>
                    	<li class="fl mfr-5"><?php echo lang('label_hello')?>,<a class="c-o" href="<?php echo base_url('ucenter')?>"><?php echo $userInfo['email'];?></a></li>
                    <?php }?>
					<li class="fl mfr-5"><a href="<?php echo base_url('ucenter/my_collection')?>"><i class="pc-tps">&#xe603;</i><?php echo sprintf(lang('label_wish'),$wish_count);?></a></li>
					<li class="fl w92">
						<a class="down" href="<?php echo base_url('ucenter')?>"><?php echo lang('label_account')?><s class="minicart_arrow"></s></a>
						<ol class="xs li-b">
							<li><a href="<?php echo base_url('ucenter/my_other_orders')?>"><?php echo lang('label_order')?></a></li>
							<?php if($is_login) {?>
							<li><a id = "login_logout" href="<?php echo base_url('/login/logout')?>"><?php echo lang('logout')?></a></li>
							<?php }?>
						</ol>
					</li>

				</ul>
			</div>
		</div>
		<!-- end main-header-->

		<!--site-header start-->
		<div class="site-header">
			<div class="w1200">
				<div class="logo fl">
					<a href="<?php echo $web_host;?>"><i class="pc-tps">&#xe63a;</i></a>
					<dl class="huiy">
						<?php if($store_info){?>
							<dt><?php echo $store_info['store_name']?></dt>
							<dd class="<?php echo $store_info['store_class']?>"><span><?php echo lang('level_'.$store_info['store_level'])?></span></dd>
						<?php }else {?>
							<i class="pc-tps">&#xe649;</i>
						<?php }?>
					</dl>
				</div>
				<div class="header-cart"> 	
					<a class="down" href="<?php echo $web_host,'cart'?>">
						<i class="pc-tps">&#xe64c;</i>
						<em><?php echo lang('label_cart')?></em>
						<b class="ci-count"><?php echo $cart_item_num; ?></b>
					</a>
					<!-- <p class="no">购物袋中还没有商品，赶快选购吧！</p> -->
				</div>

                <form action="<?php echo $web_host,$sphinx_search_url?>" method="get" onsubmit="if ($.trim($('#search_keywords').val()) == '') return false">
					<div class="search-box fr clear">
						<div class="tps-search">
							<input name="keywords" id="search_keywords" class="search-text" type="search" placeholder="<?php echo lang('label_search')?>" maxlength="200px">
							<button type="submit" target="_blank"><i class="pc-tps">&#xe647;</i></button>
						</div>
						<div class="search-hots t-o-h">
							<?php 
								if (!empty($mall_keyword_list[$keyword_key_all[$curLocation_id]['input']])) {
									$mall_keyword_input = $mall_keyword_list[$keyword_key_all[$curLocation_id]['input']];
								}
								if (!empty($mall_keyword_input)) {
									$keyword_list_count = count($mall_keyword_input)-1;
									foreach ($mall_keyword_input as $key => $value) {
										$priority = $value['priority'] == 'emphasize' ? 'c-o' : '';
										echo '<a class="c-66 '.$priority.'" href="'.$web_host .$sphinx_search_url.'?keywords='.$value['keyword'].'"'. $priority.'>'.$value['keyword'].'</a>';
										if ($key < $keyword_list_count) {
											echo "<b>|</b>";
										}
									}
								}
							?>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- end site-header-->

		<!--nav-wp start-->
		<div class="nav-wp">
			<div class="w1200">
            <?php if($curLocation_id != '000') {?>
				<?php if($curLocation_id == '840') {?>
					<div class="catalog fl">
						<h3><i class="pc-tps mr-10">&#xe600;</i><?php echo lang('label_category')?></h3>
						<div class="menu-cate-all-out">
							<?php
								$i=1;
								foreach($category_all as $cat) {
									if($cat['level'] == 0) {
							?>
							<dl>
								<?php if(isset($cat['is_show']) && $cat['is_show'] == 1) { ?>
								<dt class="clear"><a href="<?php echo $web_host,'index/category?sn=',$cat['cate_sn'];?>" class="category"><?php echo ucwords($cat['cate_name'])?></a></dt>
								<?php } ?>
								<dd class="submenu clear">
	                            	<?php
	                                    ++$i;
	                                    foreach($category_all as $sub) {
	                                        if($sub['parent_id'] == $cat['cate_id']) {
									?>
									<ul class="clear">
										<li class="b-t">
										<a href="<?php echo $web_host,'index/category?sn=',$sub['cate_sn'];?>"><?php echo ucwords($sub['cate_name'])?></a></li>
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
				<?php }else{?>
					<div class="catalog fl">
						<h3><i class="pc-tps mr-10">&#xe600;</i><?php echo lang('label_category')?></h3>
						<div class="menu-cate-all-out">
							<?php
								$i=1;
								foreach($category_all as $cat) {
									if($cat['level'] == 0) {
							?>
							<dl>
								<dt class="clear"><a href="<?php echo $web_host,'index/category?sn=',$cat['cate_sn'];?>" class="category"><?php echo ucwords($cat['cate_name'])?></a></dt>
								<dd class="submenu clear">
	                            	<?php
	                                    ++$i;
	                                    foreach($category_all as $sub) {
	                                        if($sub['parent_id'] == $cat['cate_id']) {
									?>
									<ul class="clear">
										<li class="b-t">
										<a href="<?php echo $web_host,'index/category?sn=',$sub['cate_sn'];?>"><?php echo ucwords($sub['cate_name'])?></a></li>
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
				<?php }?>
            <?php }?>

			<ul class="menu-normal fl">
				 <?php foreach($nav_list as $nav) { ?>
                 	<?php if((empty($store_info['store_level']) || $store_info['store_level'] == 4) && $nav['name'] == 'nav_affiliate'){continue;}?>
					<li>
                    	<?php if($nav['name'] == 'nav_affiliate') { ?>
                            <a class="down" href="javascript:;"><?php echo lang('label_'.$nav['name'])?><i class="pc-tps">&#xe609;</i></a>
                            <ol class="ff xs">
                            	<?php foreach($nav['list'] as $sub_nav) {?>
                                <li><a href="<?php echo str_replace('STOREID',$store_id,$sub_nav['link']);?>"><?php echo lang('label_'.$sub_nav['name'])?></a></li>
                                <?php } ?>
                            </ol>
                        <?php } else if ('zh' == $curLanguage &&  $nav['name'] == 'nav_country'){?>
                        	<a class="down" href="javascript:;"><?php echo lang('tps_global');?><i class="pc-tps">&#xe609;</i><em class="new"></em></a>
							<ol class="xs">
								<?php foreach($nav['list'] as $sub_nav) {?>
                                <li><a href="<?php echo $web_host,$sub_nav['link'];?>"><?php echo $sub_nav['name']?></a></li>
                                <?php } ?>
							</ol>
                        <?php } else if ( 'zh' == $curLanguage &&  $nav['name'] == 'nav_Life'){ ?>
                        	<a class="down" href="javascript:;"><?php echo lang('label_'.$nav['name']);?><i class="pc-tps">&#xe609;</i></a>
							<ol class="xs w120">
								<?php foreach($nav['list'] as $sub_nav) {
								?>
                                <li><a href="<?php echo $web_host.$sub_nav['link'];?>"><?php echo lang('label_' .$sub_nav['name'])?></a></li>
                                <?php } ?>
							</ol>

                        <?php } else  {?>
						<a <?php if(strpos($nav['link'],'http') !== false)echo 'target="_blank"';?> href="<?php if(strpos($nav['link'],'javascript') !== false ){echo $nav['link'];}elseif(strpos($nav['link'],'http') === false){echo $web_host,$nav['link'];}else{ echo str_replace('STOREID',$store_id,$nav['link']);}?>">
						<?php if($nav['name'] == 'nav_hot'){?>
						<em class="hot"></em>
						<?php }?>
						<?php if($nav['name'] == 'nav_new'){?>
						<?php }?>
						<?php echo lang('label_'.$nav['name'])?>
						</a>
                        <?php }?>
                    </li>
                 <?php } ?>
			</ul>

			</div>
		</div>
		<!--end nav-wp-->
	</div>

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