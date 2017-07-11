	<main class="main-content">
		<div class="banner clear">
			<div class="bd">
				<ul>
				<?php 
				$mall_ads = $mall_ads_list[$mall_ads_title[$curLocation_id]['free']];
					if ($mall_ads[0]) {
						foreach($mall_ads as $ad) {
				?>
					<li _src="url(<?php echo  $img_host.'/'.$ad['ad_img'];?>)" style="background: <?php echo $ad['img_subhead']?> no-repeat center;"><a target="_blank" href="<?php echo $ad['action_val'];?>" ></a></li>

				<?php  }} ?>	
				</ul>
			</div>
			<?php if(count($mall_ads) > 1) {?>
			<div class="hd">
				<ul></ul>
			</div>
			<span class="prev"><i class="pc-tps">&#xe63f;</i></span>
			<span class="next"><i class="pc-tps">&#xe648;</i></span>
			<?php }?>
		</div>
		<div class="New-hot-nav">
			<div class="w1200">
				<div class="logo fl"><a href="<?php echo $web_host;?>"><i class="pc-tps">&#xe63a;</i></a></div>
				<div class="huiy fl">
					<?php if($store_info){?>
						<b><?php echo $store_info['store_name']?></b>
						<p class="p <?php echo $store_info['store_class']?>"><span><i></i><?php echo lang('level_'.$store_info['store_level'])?></span></p>
					<?php }else {?>
						<i class="pc-tps">&#xe649;</i>
					<?php }?>
				</div>
				<div class="s-s">
					<h3 class="fl"><?php echo lang('label_cat')?></h3>
					<dl class="select">
						<dt><span>
                        <?php
						$cate_name=lang('label_all');
						foreach($category_all as $cat) {
                            if($cat['level'] == 0 && $cat['cate_id'] == $cate_id) {                            	
								$cate_name = $cat['cate_name'];
								break;
							}
						}
						echo $cate_name;
						?>
                        </span><i class="fa fa-angle-down fr"></i></dt>
						<dd>
							<ul>
								<li><span class="cate-sel"  data-href="<?php echo $web_host,'index/goods_free_ship';?>"><?php echo lang('label_all')?></span><i class="fa fa-angle-down fr"></i></li>
                                <?php
                                foreach($category_all as $cat) {
                                    if($cat['level'] == 0) {
                            	?>
								<li><span class="cate-sel"  data-href="<?php echo $web_host,'index/goods_free_ship?cate_id=',$cat['cate_id']?>"><?php echo ucwords($cat['cate_name'])?></span><i class="fa fa-angle-down fr"></i></li>
								<?php
                                    }
                                }
                            	?>
							</ul>
						</dd>
					</dl>
				</div>
				
			</div>
		</div>
        
		<div class="w1200"><!--
        	<?php if($best_goods) {?>
			<div class="tps-stars clear">
				<div class="hd clear">
					<h2 class="title fl"><?php echo lang('label_recommend');$best_count=count($best_goods);?></h2>
					<?php if($best_count > 5) {?>
					<ul></ul>
                    <?php }?>
				</div>
				<div class="bd free">
					<ul>
						<?php foreach($best_goods as $k=>$goods) { ?>
						<li>
							<div class="img-xg">
                            	<?php if($goods['is_direc_goods']) {?>
                                <p class="left-zg"><?php echo lang('label_flag_direc')?></p>
                                <?php }?>
                                <p class="b-q">
                                    <?php if($goods['price_off']) {?>
                                        <s class="salle" title="<?php echo lang('label_nav_promote')?>"></s>
                                    <?php }elseif($goods['is_hot']){?>
                                        <s class="hot" title="<?php echo lang('label_comment')?>"></s>
                                    <?php }elseif($goods['is_new']){?>
                                        <s class="new" title="<?php echo lang('label_single_sale')?>"></s>
                                    <?php }?>

                                    <?php if($goods['is_free_shipping']) {?>
                                        <s class="free" title="<?php echo lang('label_nav_free_ship')?>"></s>
                                    <?php }?>
                                </p>
                                
								<i class="top"></i> <i class="right"></i> <i class="bottom"></i> <i class="left"></i>
										<a target="_blank" class="img"  title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><img src="<?php echo $img_host,$goods['goods_img']?>"  /></a>
								<dl class="tit">
									<dd class="fl">
										<p><a target="_blank"  title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><?php echo $goods['goods_name'];?></a></p>
										<p class="c-o fs-14"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?><span class="c-bb">/ <s> <?php echo $curCur_flag,number_format(format_price($goods['market_price'],$cur_rate),2);?></s></span></p>
									</dd>
									<dt class="fr">
                                    	<?php if($goods['country_flag'] != 'tw') {?>
										<img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" />
                                        <?php }?>
										<p class="c-bb"><?php echo $origin_array[strtolower($goods['country_flag'])]; ?></p>
									</dt>
								</dl>
							</div>
						</li>
						<?php }?>

					</ul>
					 <?php if($best_count > 5) {?>
					<span class="prev"><s></s></span>
					<span class="next"><s></s></span>
                    <?php }?>
				</div>
			</div>
            <?php }?>-->
			<div class="search New-hot clear">
				<div class="hd clear"><h2 class="title"><?php echo lang('label_nav_free_ship')?></h2></div>
				<ul class="clear lazy">
					<?php if($goods_list) {?>
					<?php foreach($goods_list as $k=>$item) {?>
					<?php if (($k+1)%4 == 0) { ?>
						<li class="img-xg mr-n">
					<?php } else{ ?>
						<li class = "img-xg">
					<?php }?>
                    	<?php if($item['is_direc_goods']) {?>
                        <p class="left-zg"><?php echo lang('label_flag_direc')?></p>
                        <?php }?>
                        <p class="b-q">
                            <?php if($item['price_off']) {?>
                                <s class="salle" title="<?php echo lang('label_nav_promote')?>"></s>
                            <?php }elseif($item['is_hot']){?>
                                <s class="hot" title="<?php echo lang('label_comment')?>"></s>
                            <?php }elseif($item['is_new']){?>
                                <s class="new" title="<?php echo lang('label_single_sale')?>"></s>
                            <?php }?>

                            <?php if($item['is_free_shipping']) {?>
                                <s class="free" title="<?php echo lang('label_nav_free_ship')?>"></s>
                            <?php }?>
                        </p>
						<i class="top"></i> <i class="right"></i> <i class="bottom"></i> <i class="left"></i>
						<a target="_blank" class="img" title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>"><img  data-original="<?php echo $img_host,$item['goods_img']?>" src="<?php echo $web_host,'/themes/mall/img/loading250_250.gif'?>" /></a>
						<dl class="tit">
							<dd class="fl">
								<p><a target="_blank" title="<?php echo $item['goods_name']?>" href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>"><?php echo $item['goods_name']?></a></p>
								<p class="c-o fs-14"><?php echo $curCur_flag,number_format(format_price($item['shop_price'],$cur_rate),2);?> <span class="c-bb">
<!--										/ <s> --><?php //echo $curCur_flag,number_format(format_price($item['market_price'],$cur_rate),2);?><!--</s>-->
									</span></p>
							</dd>
							<dt class="fr">
                            	<?php if($item['country_flag'] != 'tw') {?>
								<s class="qizhi"><img src="<?php echo $this->config->item('country_flag_path').$item['country_flag'].'.jpg'?>" /></s>
                                 <?php }?>
								<p class="c-bb"><?php  echo $origin_array[strtolower($item['country_flag'])];?></p>
							</dt>
						</dl>
					</li>
					<?php }?>
					<?php }else{?>
                    <p style="text-align:center; height:300px;"> <?php echo lang('label_cate_no_records')?></p>
                    <?php }?>
				</ul>
                
                <?php echo $pager;?>
			</div>
		</div>
		
	</main>
    <div class="backToTop-up" ><s></s></div>
<script src="<?php echo base_url(THEME.'/js/jquery.lazyload.min.js')?>"></script> 
<script>

$(function(){
	jQuery(".tps-stars").hover(function(){ jQuery(this).find(".prev,.next").stop(true,true).fadeTo("show",.85) },function(){ jQuery(this).find(".prev,.next").fadeOut() });
	jQuery(".tps-stars").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"left", vis:5, scroll:2, delayTime:500, autoPlay:true, autoPage:true, pnLoop:false, interTime:6000,});	
	
	/* 控制左右按钮显示 */
	jQuery(".banner").hover(function(){ jQuery(this).find(".prev,.next").stop(true,true).fadeTo("show",0.5) },function(){ jQuery(this).find(".prev,.next").fadeOut() });
	
	/* 调用SuperSlide */
	jQuery(".banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold",  autoPlay:true, autoPage:true, trigger:"click", interTime:8000,
		startFun:function(i){
			var curLi = jQuery(".banner .bd li").eq(i); /* 当前大图的li */
			if( !!curLi.attr("_src") ){
				curLi.css("background-image",curLi.attr("_src")).removeAttr("_src") /* 将_src地址赋予li背景，然后删除_src */
			}
		}
	});

	//固定导航
	$(function(){
		var nav = $('.New-hot-nav'),
			doc = $(document),
			win = $(window);
		win.scroll(function() {
			if (doc.scrollTop() > 900) {
				nav.addClass('fixed');
			}
			else {
				nav.removeClass('fixed');
			}
		});
		win.scroll();
	});
	
	//竖直导航
	$('.catalog').hover(function() {
		$(this).addClass('Hover');
	},function() {
		$(this).removeClass('Hover');
		$('.menu-cate-all-out dl').removeClass('on');
	})	
	$('.menu-cate-all-out dt').mouseover(function(){
		$('.menu-cate-all-out dl').removeClass('on');
		$(this).parent().addClass('on');
	})

	$(".select").each(function(){
		var s=$(this);
		var z=parseInt(s.css("z-index"));
		var dt=$(this).children("dt");
		var dd=$(this).children("dd");
		var _show=function(){dd.slideDown(200);dt.addClass("cur");s.css("z-index",z+1);};   //展开效果
		var _hide=function(){dd.slideUp(200);dt.removeClass("cur");s.css("z-index",z);};    //关闭效果
		dt.click(function(){dd.is(":hidden")?_show():_hide();});
		dd.find("li").click(function(){dt.html($(this).html());_hide();});  //选择效果（如需要传值，可自定义参数，在此处返回对应的“value”值 ）
		$("body").click(function(i){ !$(i.target).parents(".select").first().is(s) ? _hide():"";});
	})
	
	$('.date-sel,.cate-sel').click(function() {
		var href=$(this).attr('data-href');console.log(href);
		if(typeof(href) != 'undefind') {
			window.location.href=href;
		}
	});
});
</script>
