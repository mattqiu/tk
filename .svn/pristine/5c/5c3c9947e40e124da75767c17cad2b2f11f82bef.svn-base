	<main class="main-content TICOWONG.hyk2-test0">
		<div class="banner">
			<div class="bd">
				<ul>
					<?php foreach($banner_ads as $ad) {?>
                   <li><a href="<?php echo $web_host,$ad['ad_url'];?>"><img width="1920" height="620" src="<?php echo $img_host,$ad['ad_img'];?>" /></a></li>
                   <?php }?>    
				</ul>
			</div>
			<div class="hd">
				<ul>
				</ul>
			</div>
			<span class="prev"><a class="fa fa-angle-left" href="javascript:;"></a></span>
			<span class="next"><a class="fa fa-angle-right" href="javascript:;"></a></span>
		</div>
		<div class="w1200">
			<div class="list-service clear">
				<ul>
					<li>
						<s></s>
						<span>
							<?php echo lang('label_brand1');?>
						</span>
					</li>
					<li class="l1">
						<s class="s1"></s>
						<span>
							<?php echo lang('label_brand2');?>
						</span>
					</li>
					<li class="l2">
						<s class="s2"></s>
						<span>
							<?php echo lang('label_brand3');?>
						</span>
					</li>
					<li class="l3">
						<s class="s3"></s>
						<span>
							<?php echo lang('label_brand4');?>
						</span>
					</li>
				</ul>
			</div>
			<div class="tps-stars clear">
				<div class="hd clear">
					<h2 class="title fl"><?php echo lang('label_comment')?></h2>
					<ul></ul>
				</div>
				<div class="bd">
					<ul>
                    	<?php foreach($best_goods as $k=>$goods) { ?>
						<li>
							<div class="img-xg">
                            	<?php if($goods['is_new']) {?>
								<s class="new"></s>
                                <?php }elseif($goods['is_hot']) {?>
                                <s class="hot"></s>
                                <?php }?>
								<i class="top"></i> <i class="right"></i> <i class="bottom"></i> <i class="left"></i>
                                <div class="v_box">
								<a title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><img style="height:200px;"  data-original="<?php echo $img_host,$goods['goods_img']?>" src="<?php echo $web_host,'/themes/mall/img/loading250_250.gif'?>" /></a>
                                </div>
								<dl class="tit">
									<dd class="fl">
										<p><a title="<?php echo $goods['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>"><?php echo $goods['goods_name'];?></a></p>
										<p class="c-o fs-14"><?php echo $curCur_flag,format_price($goods['shop_price'],$cur_rate);?><span class="c-bb">/ <s> <?php echo $curCur_flag,format_price($goods['market_price'],$cur_rate);?></s></span></p>
									</dd>
									<dt class="fr">
										<s class="qizhi"><img style="width:16px;height:11px;" src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" /></s>
										<p class="c-bb"><?php  echo $origin_array[strtolower($goods['country_flag'])]; ?></p>
									</dt>
								</dl>
							</div>
						</li>
						<?php }?>
					</ul>
				</div>
			</div>
			<?php 
				$k=0;
				foreach($level_goods as $cate_id=>$goods) {
					$k++;
			?>
			<div class="popular" id="float0<?php echo $k?>">
				<div class="hd clear">
                	<?php foreach($category_all as $cat) {
								if($cat['level'] == 0 && $cat['cate_id'] == $cate_id) {
					?>
					<h2 class="title fl"><?php echo $cat['cate_name']?></h2>
                    <?php }
					}?>
					<a class="fr" href="<?php echo base_url(),'index/category?sn=',$goods['cate_sn'];?>"><?php echo lang('label_more')?> &gt;</a>
				</div>
				<div class="content clear">
					<div class="l-side clear">
						<a href=""><img src="<?php echo base_url(THEME.'/img/cp_5.jpg')?>" alt=""></a>
						<ul class="b-list">
                        	<?php
										$i=0;
										foreach($category_all as $sub) {
											if($i<10 && $sub['parent_id'] == $cate_id) {
												$i++;
									?>
							<li class="ellipsis" title="<?php echo $sub['cate_name']?>"><a href="<?php echo base_url(),'index/category?sn=',$goods['cate_sn'];?>"><?php echo $sub['cate_name']?></a></li>
							<?php
											}
										}
									?>
						</ul>
					</div>
					<div class="activity">
						<ul class="bd">
							<li><a href=""><img src="<?php echo base_url(THEME.'/img/cp_7.jpg')?>" alt=""></a></li>

						</ul>
						<!--ul class="hd">
							<li><p>保暖内衣</p><p>全场包邮</p></li>
						</ul-->
					</div>
					<div class="cp">
						<ul>
                        	<?php foreach($goods['goods_list'] as $goods_list) { ?>
							<li class="img-xg1">
								<?php if($goods_list['is_new']) {?>
								<s class="new"></s>
                                <?php }elseif($goods_list['is_hot']) {?>
                                <s class="hot"></s>
                                <?php }?>
								<a title="<?php echo $goods_list['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods_list['goods_sn_main'];?>"><img  data-original="<?php echo $img_host,$goods_list['goods_img']?>" src="<?php echo $web_host,'/themes/mall/img/loading250_250.gif'?>" /></a>
								<dl class="tit">
									<dd class="fl">
										<p><a title="<?php echo $goods_list['goods_name'];?>" href="<?php echo base_url(),'index/product?snm=',$goods_list['goods_sn_main'];?>"><?php echo $goods_list['goods_name'];?></a></p>
										<p class="c-o fs-14"><?php echo $curCur_flag,format_price($goods_list['shop_price'],$cur_rate);?><span class="c-bb">/ <s> <?php echo $curCur_flag,format_price($goods_list['market_price'],$cur_rate);?></s></span></p>
									</dd>
									<dt class="fr">
										<s class="qizhi"><img style="width:16px;height:11px;" src="<?php echo $this->config->item('country_flag_path') .$goods_list['country_flag'].'.jpg'?>" /></s>
										<p class="c-bb"><?php  echo $origin_array[strtolower($goods_list['country_flag'])];?></p>
									</dt>
								</dl>
							</li>
							<?php }?>
						</ul>
					</div>
				</div>
			</div>
			<?php 
			}
			?>
		</div>
		
	</main>

	<!--楼层导航-->		
	<?php if($curLan != 'english') {?><div class="floatCtro">
    <p class="cur"><?php echo lang('floor_cate_1');?></p><p><?php echo lang('floor_cate_2');?></p><p><?php echo lang('floor_cate_3');?></p><p><?php echo lang('floor_cate_4');?></p><p><?php echo lang('floor_cate_5');?></p><p><?php echo lang('floor_cate_6');?></p><p><?php echo lang('floor_cate_7');?></p>
    <a style="display: block;"><span title="Top">TOP</span></a></div>
    <?php }else {?>
    <div class="floatCtro" style="top:90%"><a style="display: block;"><span title="Top">TOP</span></a></div>
    <?php }?>

	
	<!--<div class="backToTop-up"><i class="fa fa-angle-up" title="Top"></i></div>-->

    <script src="<?php echo base_url(THEME.'/js/jquery.SuperSlide.2.1.2.js')?>"></script>
    <script src="<?php echo base_url(THEME.'/js/jquery.lazyload.min.js')?>"></script> 
	<script>
		//固定导航
		$(function(){
			var nav = $('.site-header'),
				doc = $(document),
				win = $(window);
			win.scroll(function() {
				if (doc.scrollTop() > 900) {
					nav.addClass('fixed-top');
				} 
				else {
					nav.removeClass('fixed-top');
				}
			});
			win.scroll();
		});

		//竖直导航
		$(function(){
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
		});

		// 楼层导航JS
		$(function(){
		   var AllHet = $(window).height();
		   var mainHet= $('.floatCtro').height();
		   var fixedTop = (AllHet - mainHet)/2
		   $('.floatCtro p').click(function(){
				var ind = $('.floatCtro p').index(this)+1;
				var topVal = $('#float0'+ind).offset().top;
				$('body,html').animate({scrollTop:topVal},500)
			})
			$('.floatCtro a').click(function(){
				$('body,html').animate({scrollTop:0},500)
				})
		   $(window).scroll(scrolls)
		   scrolls()
		   function scrolls(){
			   var f1,f2,f3,f4,f5,f6,f7,bck;
			   var fixRight = $('.floatCtro p');
			   var blackTop = $('.floatCtro a')
			   var sTop = $(window).scrollTop();
			   fl = $('#float01').offset().top;
			   f2 = $('#float02').offset().top;
			   f3 = $('#float03').offset().top;
			   f4 = $('#float04').offset().top;
			   f5 = $('#float05').offset().top;
			   f6 = $('#float06').offset().top;
			   f7 = $('#float07').offset().top;	   

				if(sTop<=$('#float01').get(0).offsetHeight){
					$('.floatCtro').hide();
				}
				else{
					$('.floatCtro').show();
				}

			   if(sTop>=fl){
				   fixRight.eq(0).addClass('cur').siblings().removeClass('cur');
				   }
			   if(sTop>=f2-100){
				   fixRight.eq(1).addClass('cur').siblings().removeClass('cur');
				   }
			   if(sTop>=f3-100){
				   fixRight.eq(2).addClass('cur').siblings().removeClass('cur');
				   }
			   if(sTop>=f4-100){
				   fixRight.eq(3).addClass('cur').siblings().removeClass('cur');
				   }
			   if(sTop>=f5-100){
				   fixRight.eq(4).addClass('cur').siblings().removeClass('cur');
				   }
			   if(sTop>=f6-100){
				   fixRight.eq(5).addClass('cur').siblings().removeClass('cur');
				   }
			   if(sTop>=f7-100){
				   fixRight.eq(6).addClass('cur').siblings().removeClass('cur');
				   } 
		     }
		   });
		   jQuery(".banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"leftLoop", vis:"auto", autoPlay:true, autoPage:true, interTime:8000,});
		   jQuery(".tps-stars").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"left", vis:5, scroll:5, delayTime:500, autoPlay:true, autoPage:true, pnLoop:false, interTime:14000,});
		   jQuery(".activity").slide({ titCell:".hd li", mainCell:".bd", effect:"left",delayTime:500,easing:"easeInOutQuad",});
	</script>

