<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title;?></title>
	<link rel="shortcut icon" href="/favicon.ico?v=2">
	<meta name="keywords" content="<?php echo $keywords;?>" />
	<meta name="description" content="<?php echo $description;?>" />
	<link rel="canonical" href="<?php echo $canonical;?>" />
	<link rel="stylesheet" href="<?php echo base_url(THEME.'/css/head_food_1.css?v=1.0')?>">
	<link rel="stylesheet" href="<?php echo base_url(THEME.'/css/base.css?v=1.0')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/store_register.css?v=1'); ?>" />
	<?php if($curLan == 'hk'){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/hk.css?v=3'); ?>" />
	<?php }?>
	<?php if($curLan == 'english'){ ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/only_english.css?v=3'); ?>" />
	<?php }?>
	<?php if($curLan == 'kr'){ ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/mall/css/kr.css?v=3'); ?>" />
	<?php }?>
	<script src="<?php echo base_url(THEME.'/js/jquery.min.js')?>"></script>    
	<script src="<?php echo base_url(THEME.'/js/SuperSlide2.1.js')?>"></script>
	<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
    <script>
		$(function(){ //ie9.0以下提示升级ie
			if($.browser.msie && $.browser.version < 9.0) {
				$('.this-ts').show();
			}
		});
		var _hmt = _hmt || [];
		(function() {
			var hm = document.createElement("script");
			hm.src = "//hm.baidu.com/hm.js?578f5b60e0b8aa397afd3ca1332c296d";
			var s = document.getElementsByTagName("script")[0];
			s.parentNode.insertBefore(hm, s);
		})();
	</script>
</head>
<body>
	<p class="this-ts"><?php echo lang('msg_tips')?></p>
	
	<?php if(isset($curLoc_name)) {?>
		<div class="backdrop location"></div>
		<div class="g-jia location">
			<i class="close_location">×</i>
			<s></s>
			<span>
			<?php echo sprintf(lang('label_location_tps'),$curLoc_name);?>
			</span>
		</div>
	<?php }?>
	<?php if($curLan == 'zh') {?>
		<div class="w-100"><s class="ggao tps_fenghui" style=" background:url('<?php echo base_url(THEME.'/img/tpsfenghui.jpg')?>') no-repeat center"></s></div>
		<?php }elseif($curLan == 'hk') {?>
		<div class="w-100 d-n"></div>
		<?php }else {?>
		<div class="w-100 d-n"></div>
	<?php }?>

	<div class="main-header">
		<div class="header-bar">
			<div class="w1200">
				<ul class="fl nav-top">
                	<li class="fl mfr-5 w88">
                    	<?php foreach($sale_country as $country) {?>
                        	<?php if ($curLocation_id == $country['country_id']) {?>
							<a class="down" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag']?>"></s><?php echo $country['name_'.$curLan]?><s class="minicart_arrow"></s></a>
                            <?php }?>
                        <?php }?>
						<ol class="xs li-b">
                        	<?php foreach($sale_country as $country) {?>
							<li><a data-id="<?php echo $country['country_id']?>" data-lang="<?php echo $country['default_language']?>" data-cur="<?php echo $country['default_flag']?>"
								   data-goods-sn="<?php echo isset($goods_sn)?$goods_sn:''?>" data-jump="<?php echo isset($jump)?TRUE:''?>" data-goods-sn-main="<?php echo isset($goods_sn_main)?$goods_sn_main:''?>" class="<?php if ($curLocation_id == $country['country_id']) echo 'orange'; ?>
								   change_location" href="javascript:;"><s class="qizhi-<?php echo $country['default_flag']?>"></s><?php echo $country['name_'.$curLan]?></a></li>
							<?php }?>
						</ol>
					</li>
					<li class="fl mfr-5">
						<a class="down" href="javascript:;"><!--s class="qizhi-<?php echo $curCur?>"></s--><?php if(in_array($curLan,array('english','kr'))) {echo $curCur_flag,$curCur;} else {echo $curCur_flag,$curCur_name;}?><s class="minicart_arrow"></s></a>
						<ol class="xs li-b">
                        	<?php foreach($currency_all as $cur) {?>
							<li><a href="javascript:;" class="js-currency <?php if($curCur == $cur['currency']) echo 'orange';?>" data-v="<?php echo $cur['icon'];?>" data-n="<?php echo $cur['currency'];?>" data-name="<?php echo $cur['name'];?>"><!--s class="qizhi-<?php echo $cur['currency']?>"></s--><?php echo $cur['icon']?>&nbsp;<span><?php if(in_array($curLan,array('english','kr'))) {echo $cur['currency'];} else {echo $cur['name'];}?></span></a></li>
							<?php }?>
						</ol>
					</li>	
				</ul>
				<?php if($this->uri->segment(1)!='enroll'){?>
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
						<ol class="xs li-b">
							<li><a href="<?php echo base_url('ucenter/my_other_orders')?>"><?php echo lang('label_order')?></a></li>
							<?php if($is_login) {?>
							<li><a href="<?php echo base_url('/login/logout')?>"><?php echo lang('logout')?></a></li>
							<?php }?>
						</ol>
					</li>
				</ul>
				<?php }?>
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
                <form action="<?php echo $web_host,'index/search'?>" method="get" onsubmit="if ($.trim($('#search_keywords').val()) == '') return false">
                    <div class="tps-search fr clear">
                        <input name="keywords" id="search_keywords" maxlength="200px" class="search-text" placeholder="<?php echo lang('label_search')?>">
                        <button type="submit" target="_blank"><s></s></button>
                    </div>
                </form>
			</div>
		</div>		
	</header>
    <main>	
	<div class="nav-wp">
			<div class="w1200">
            	<?php if($curLocation_id != '000') {?>
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
                <?php }?>
				<ul class="menu-normal fl">
				 <?php foreach($nav_list as $nav) {?>  
                 	<?php if((empty($store_info['store_level']) || $store_info['store_level'] == 4) && $nav['name'] == 'nav_affiliate'){continue;}?>              	
					<li>
                    	<?php if($nav['name'] == 'nav_affiliate') { ?>
                            <a class="down" href="javascript:;"><?php echo lang('label_'.$nav['name'])?> <i class="fa fa-angle-down"></i></a>
                            <ol class="xs">
                            	<?php foreach($nav['list'] as $sub_nav) {?>
                                <li><a target="_blank" href="<?php echo str_replace('STOREID',$store_id,$sub_nav['link']);?>"><?php echo lang('label_'.$sub_nav['name'])?></a></li>
                                <?php } ?>
                            </ol>
                        <?php } else {?>
						<a <?php if(strpos($nav['link'],'http') !== false)echo 'target="_blank"';?> href="<?php if(strpos($nav['link'],'javascript') !== false ){echo $nav['link'];}elseif(strpos($nav['link'],'http') === false){echo $web_host,$nav['link'];}else{ echo str_replace('STOREID',$store_id,$nav['link']);}?>">
						<?php if($nav['name'] == 'nav_hot'){?>
						<i class="hot"></i>
						<?php }?>
						<?php if($nav['name'] == 'nav_new'){?>
						<i class="new"></i>
						<?php }?>                    
						<?php echo lang('label_'.$nav['name'])?>
						</a>
                        <?php }?>
                    </li>
                 <?php }?>
               </ul>
			</div>
		</div>
    </div>
    
    
<script>
$(document).ready(function(){
	//登录按钮
	$('.tps_fenghui').click(function(){
        $.ajax({
            type: "POST",
            url: "/index/tps_summit_meeting_login",
            data: {},
            dataType: "json",
            success: function (data) {
                if (data.success == 0) {
                    window.location.href = "<?php echo base_url().'login/index?fp=fp'?>";
                }else if(data.success == 1){
                	window.location.href = '/index/tps_summit_meeting';
                }
            }
        });
	})
})
</script>


