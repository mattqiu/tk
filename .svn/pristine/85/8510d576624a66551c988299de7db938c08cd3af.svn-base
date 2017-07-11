<div class="main-content">
		<!--banner start-->
		<div class="banner clear">
			<div class="bd">
				<ul>
				<?php 
				$mall_ads = $mall_ads_list[$mall_ads_title[$curLocation_id]['ads']];
					//print_r($mall_ads);exit;
					if ($mall_ads[0]) {
						foreach($mall_ads as $ad) {
							//2017-07-07 leon  修改图片地址为当前登陆人的店铺ID
							// if($store_id != 0){
							// 	$action_val = $ad['action_val'];
							// 	$action_val_array = explode('www',$action_val);
							// 	$action_val_url = $action_val_array[0].$store_id.$action_val_array[1];
							// }else{
							// 	$action_val_url = $ad['action_val'];
							// }
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
		<!--end banner-->

		<div class="w1200">
			<!--x-banner start-->
			<?php
				$mall_ads_activity = $mall_ads_list[$mall_ads_title[$curLocation_id]['activity']];
				$mall_ads_activity_count = count($mall_ads_activity);
			?>
			<div class="x-banner clear">
				<?php
				if ($mall_ads_activity && $mall_ads_activity_count > 1) {
					foreach ($mall_ads_activity as $k => $v) {
						if (4 <= $k || (3 == $mall_ads_activity_count  && $k == 2)) {
							break;
						}
				?>
				<a href="<?=$v['action_val']?>"><img src="<?php echo $img_host.$v['ad_img']?>" alt=""></a>
				<?php  } } ?>
			</div>
			<!--end x-banner-->

			<!--新品上架 start-->
			<?php  if ('000' != $curLocation_id) { ?>
			<div class="tps-line">
				<div class="tc mb-10">
					<b class="d-ib"><?='344' != $curLocation_id ? lang('label_single_sale') : lang('label_single_sale_main');?></b>
					<?php  if ('344' != $curLocation_id ) { ?>
					<em class="d-ib fs-18 mfr-10">/</em>
					<span class="d-ib c-99 fs-14"><?=lang('label_nav_new_info')?></span>
					<?php } ?>
					<a class="fs-14 c-66 fr" href="<?php echo base_url(),'index/goods_new';?>"><?php echo lang('more');?> ></a>
				</div>
				<div class="bd clear">
					<ul>
					<?php foreach($new_goods as $k=>$goods) { ?>
						<?php if ($k % 5 == 4) { ?>
						<li class="detail-box w-a">
						<?php } else{ ?>
						<li class="detail-box">
						<?php }?>
							<a href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" target="_blank">
								<div class="img-box"><img src="<?php echo $img_host,$goods['goods_img']?>" alt=""></div>
								<div class="offer-detail">
									<h3 title="<?php echo $goods['goods_name'];?>"> <?php echo $goods['goods_name'];?></h3>
									<p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
									<p class="gps"><?= !empty($origin_array[strtolower($goods['country_flag'])])  ? $origin_array[strtolower($goods['country_flag'])] : '';?>
									<?php if($goods['country_flag'] != 'tw'  && $goods['country_flag']) {?>
									<img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" />
                                    <?php }?>
									</p>
								</div>
							</a>
						</li>
						<?php if (($k % 15 == 14) && ($k < count($new_goods)-1)) { ?>
					</ul>
					<ul>
					<?php } ?>
					<?php } ?>
					</ul>
				</div>
				<span class="prev"><i class="pc-tps">&#xe63f;</i></span>
				<span class="next"><i class="pc-tps">&#xe648;</i></span>
			</div>
			<?php } ?>
			<!--end 新品上架-->

			<!--特惠促销 start-->
			<?php
				$promote_goods_count = count($promote_goods);
				if ($promote_goods_count >= 5) {
			?>
			<div class="tps-line">
				<div class="tc mb-10">
					<b class="d-ib"><?='344' != $curLocation_id ? lang('label_nav_promote') : lang('menu_label_nav_promote');?></b>
					<?php  if ('344' != $curLocation_id ) { ?>
					<em class="d-ib fs-18 mfr-10">/</em>
					<span class="d-ib c-99 fs-14"><?php echo lang('label_nav_promote_info');?></span>
					<?php }?>
					<a class="fs-14 c-66 fr" href="<?php echo base_url(),'index/promote';?>"><?php echo lang('more');?>></a>
				</div>
				<div class="bd clear">
					<ul>
						<?php foreach($promote_goods as $k=>$goods) { ?>
						<?php if ($k % 5 == 4) { ?>
						<li class="detail-box w-a">
						<?php } else{ ?>
						<li class="detail-box">
						<?php }?>
							<a href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" target="_blank">
							<div class="img-box"><img src="<?php echo $img_host,$goods['goods_img']?>" alt="" ></div>
							<div class="offer-detail">
								<h3 title="<?php echo $goods['goods_name'];?>"> <?php echo $goods['goods_name'];?></h3>
								<p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
								<p class="gps"><?= !empty($origin_array[strtolower($goods['country_flag'])])  ? $origin_array[strtolower($goods['country_flag'])] : '';?>
								<?php if($goods['country_flag'] != 'tw'  && $goods['country_flag']) { ?>
								<img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" />
                                <?php } ?>
								</p>
							</div>
							</a>
						</li>
						<?php if (($k % 15 == 14) && ($k < count($promote_goods)-1)) { ?>
					</ul>
					<ul>
						<?php } ?>
						<?php } ?>
					</ul>
				</div>
				<span class="prev"><i class="pc-tps">&#xe63f;</i></span>
				<span class="next"><i class="pc-tps">&#xe648;</i></span>
			</div>
            <?php } ?>
			<!--end 特惠促销-->


			<!--热门推荐 start-->
			<div class="tps-line">
				<div class="tc mb-10">
					<b class="d-ib">
					<?='344' != $curLocation_id ? lang('label_comment') : lang('label_comment_main'); ?>
					</b>
					<?php  if ('344' != $curLocation_id ) { ?>
					<em class="d-ib fs-18 mfr-10">/</em>
					<span class="d-ib c-99 fs-14"><?php echo lang('label_comment_info');?></span>
					<?php }?>
					<a class="fs-14 c-66 fr" href="<?php echo base_url(),'index/goods_hot';?>"><?php echo lang('more');?>></a>
				</div>
				<div class="titles clear">
					<ul>
					<?php
						$n = 1;
						foreach($hot_goods as $k=>$goods) {
							$line = $n % 3 == 0 ? 'line' : '';
							$n++;
					?>
						<li class="detail-box">
							<a class="<?php echo $line ?>" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" target="_blank">
								<!-- <i class="pc-tps c-y">&#xe645;</i> -->
								<div class="img-box"><img src="<?php echo $img_host,$goods['goods_img']?>" alt=""></div>
								<div class="offer-detail">
									<h3><?php echo $goods['goods_name'];?></h3>
									<p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
									<p><?= !empty($origin_array[strtolower($goods['country_flag'])])  ? $origin_array[strtolower($goods['country_flag'])] : '';?>
									<?php if($goods['country_flag'] != 'tw'  && $goods['country_flag']) {?>
									<img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" />
                                    <?php }?>
									</p>
								</div>
							</a>
						</li>
					<?php }?>
					</ul>
				</div>
			</div>
			<!--end 热门推荐-->

			<!--全球购 start-->
			<?php if ('156' == $curLocation_id)  { ?>
			<div class="tps-line pr">
				<div class="tc"><b class="d-ib"><?php echo lang('tps_global')?></b><em class="d-ib fs-18 mfr-10">/</em><span class="d-ib c-99 fs-14"><?php echo lang('tps_global_info')?></span></div>
				<i class="pc-tps qqg">&#xe64a;</i>
				<ul class="tps-wrap clear">
					<li><a href="<?php echo base_url(),'index/global_shopping?elevent=korea'?>"></a></li>
					<!-- <li class="btn-2"><a href="<?php echo base_url(),'index/global_shopping?elevent=america'?>"></a></li> -->
					<li class="btn-3"><a href="<?php echo base_url(),'index/global_shopping?elevent=gat'?>"></a></li>
					<li class="btn-4"><a href="<?php echo base_url(),'index/global_shopping?elevent=canada'?>"></a></li> 
					<!-- <li class="btn-5"><a href="<?php echo base_url(),'index/global_shopping?elevent=japan'?>"></a></li>  -->
					<li class="btn-6"><a href="<?php echo base_url(),'index/global_shopping?elevent=europe'?>"></a></li>
					<li class="btn-7"><a href="<?php echo base_url(),'index/global_shopping?elevent=australia'?>"></a></li>
					<li class="btn-8"><a href="<?php echo base_url(),'index/global_shopping?elevent=southeast-asia'?>"></a></li> 
				</ul>
			</div>
			<?php } ?>
			<!--end 全球购-->


			<!--首页楼层内容 start-->
			<?php foreach ($level_goods as $key => $value) { ?>
			<div class="popular lazy">
				<!-- 楼层的名字（一级类目的名字）  start -->
				<div class="title-tps">
					<h2><?php echo $value['cate']['cate_name']?></h2>
					<p>
						<?php
							if (!empty($mall_keyword_list[$keyword_key_all[$curLocation_id][$value['cate']['cate_cn']]])) {
								$mall_keyword_cate = $mall_keyword_list[$keyword_key_all[$curLocation_id][$value['cate']['cate_cn']]];
							}
							if (!empty($mall_keyword_cate)) {
								//$keyword_list_count = count($mall_keyword_cate) - 1;
								foreach ($mall_keyword_cate as $k => $v) {
									if (8 <= $k) {
										break;
									}
									$priority = $v['priority'] == 'emphasize' ? 'class="c-o"': '';
									echo '<a href="'.$web_host .$sphinx_search_url.'?keywords='.$v['keyword'].'"'. $priority.'">'.$v['keyword'].'</a>';
								}
							}
							unset($mall_keyword_cate);
						?>
					</p>
					<!-- leon 添加更多商品跳转内容 -->
					<a class="more" href="<?php echo base_url(),'index/category?sn=',$value['cate']['cate_cn'];?>"><?php echo lang('more')?> &gt;</a>
				</div>
				<!--end   楼层的名字（一级类目的名字） -->

				<div class="tab clear">
					<!-- 左侧的图片和楼层 -->
					<div class="l-side">
						<img src="<?php echo $web_host.'themes/mall/img/cate_'. $key .'.jpg'?>" alt="">    <!-- 楼层左侧上角的图片（暂时隐藏） -->
						<!-- 楼层中的 二级分类内容 菜单 -->
						<ul>
							<?php
							$i=0;
							foreach($category_all as $sub) {
								if($i<10 && $sub['parent_id'] == $key) {
									$i++;
							?>
								<li title="<?php echo $sub['cate_name']?>">
									<a  href="<?php echo base_url(),'index/category?sn=',$sub['cate_sn'];?>"><?php echo ucwords($sub['cate_name'])?></a>
								</li>
							<?php
								}
							}
							?>
						</ul>
						<!-- 楼层中的 二级分类内容 菜单 -->
					</div>

					<div class="activity">
						<ul class="bd">
						<?php
						$mall_ads_cate = $mall_ads_list[$mall_ads_title[$curLocation_id][$value['cate']['cate_cn']]];
						$mall_ads_cate_count = count($mall_ads_cate);
						if ($mall_ads_cate) {
							foreach ($mall_ads_cate as $k => $v) {
								//2017-07-07 leon  修改图片地址为当前登陆人的店铺ID
								// if($store_id != 0){
								// 	$action_val = $ad['action_val'];
								// 	$action_val_array = explode('www',$action_val);
								// 	$action_val_url = $action_val_array[0].$store_id.$action_val_array[1];
								// }else{
								// 	$action_val_url = $ad['action_val'];
								// }
						?>
							<li>
							<a href="<?=$v['action_val']?>"><img src="<?php echo $img_host.$v['ad_img']?>" alt=""></a>
							</li>
						<?php }}?>
						</ul>
						<ul class="hd">
						<?php if ($mall_ads_cate_count > 1) {
							echo str_repeat("<li></li>", $mall_ads_cate_count);
						}?>
						</ul>
						<?php if ($mall_ads_cate_count > 1) { ?>
						<span class="prev"><i class="pc-tps">&#xe63f;</i></span>
						<span class="next"><i class="pc-tps">&#xe648;</i></span>
						<?php } ?>
					</div>

					<div class="activity brands">
						<?php
							if (156 == $curLocation_id) {
								$mall_ads_brand = $mall_ads_list[$mall_ads_title[$curLocation_id][$value['cate']['cate_cn']."_brand"]];
								$mall_brand_count = count($mall_ads_brand);
								$mall_page_nums = ceil($mall_brand_count/4);
								if ($mall_brand_count) {
						?>
						<ul class="bd">
							<?php
									$n = 0;
									foreach ($mall_ads_brand as $k => $brand) {

										//2017-07-07 leon  修改图片地址为当前登陆人的店铺ID
										// if($store_id != 0){
										// 	$action_val = $ad['action_val'];
										// 	$action_val_array = explode('www',$action_val);
										// 	$action_val_url = $action_val_array[0].$store_id.$action_val_array[1];
										// }else{
										// 	$action_val_url = $ad['action_val'];
										// }

										if ($k >= 12) break;
										$brand_a .= '<a class="img" href="'.$brand['action_val'].'"><img src="'. $img_host.$brand['ad_img'].'" alt=""></a>';
										$n++;
										if ($k % 4 == 0) {
											echo "<li>";
										}
										if (4 == $n || ($k+1) == $mall_brand_count) {
											$n = 0;
											echo "{$brand_a}</li>";
											$brand_a = '';
										}
									}
								}
							?>
						</ul>
						<?php } else { ?>
						<h3><?php echo lang('label_goods_hot');?></h3>
						<ul class="bd">
							<?php
								$goods_list_hot_count = count($value['goods_list_hot']);
								$mall_page_nums   = ceil($goods_list_hot_count / 3);
								if ($value['goods_list_hot']) {
									$n = 0;
									foreach ($value['goods_list_hot'] as $k => $hval) {
										if ($k >= 9) break;
										$hot_content .= '<li>
										<a href="'.base_url().'index/product?snm='.$hval['goods_sn_main'].'">
												<dl>
													<dt>
														<img src="'. $img_host.$hval['goods_img'].'">
													</dt>
													<dd>
														<h5>'.$hval['goods_name'].'</h5>';
														$hot_content .= '<p class="price">'.$curCur_flag.number_format(format_price($hval['shop_price'],$cur_rate),2).'</p>
													</dd>
												</dl>
											</a>
										</li>';
										$n++;
										if ($k % 3 == 0) {
											echo "<li><ul>";
										}
										if (3 == $n || ($k+1) == $goods_list_hot_count) {
											$n = 0;
											echo "{$hot_content}</li></ul>";
											$hot_content = '';
										}
									}
								}
							?>
						</ul>
						<?php  } ?>
						<ul class="hd">
							<?php
								$mall_page_nums = $mall_page_nums > 3 ? 3 : $mall_page_nums;
								if ($mall_page_nums > 1 ) {
									echo str_repeat("<li></li>", $mall_page_nums);
								}
							?>
						</ul>
					</div>
				</div>

				<!-- 楼层底部的商品 start -->
				<div class="list clear">
					<ul>
					<?php
						if ($value['goods_list']) {
							foreach ($value['goods_list'] as $gkey => $goods) {
					?>
						<?php if ($gkey % 5 == 4) { ?>
							<li class="detail-box w-a">
						<?php }else{ ?>
							<li class="detail-box">
						<?php } ?>
							<a target="_blank" href="<?php echo base_url(),'index/product?snm=',$goods['goods_sn_main'];?>" >
								<div class="img-box"><img src="<?php echo $img_host,$goods['goods_img']?>" alt=""></div>
								<div class="offer-detail">
									<h3><?php echo $goods['goods_name'];?></h3>
									<p class="price"><?php echo $curCur_flag,number_format(format_price($goods['shop_price'],$cur_rate),2);?></p>
									<p><?= !empty($origin_array[strtolower($goods['country_flag'])])  ? $origin_array[strtolower($goods['country_flag'])] : '';?>
									<?php if($goods['country_flag'] != 'tw'  && $goods['country_flag']) {?>
										<img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" />
                                    <?php }?>
									</p>
								</div>
							</a>
						</li>
					<?php }}  ?>
					</ul>
				</div>
				<!--end 楼层底部的商品 -->

			</div>
			<?php }?>
			<!--end 首页楼层内容-->

			<!--浏览历史 start-->
			<?php
				if ($history_goods) {
			?>
			<div class="tps-line ls">
				<div class="tc mb-10"><b class="d-ib"><?php echo lang('label_goods_history');?></b><em class="d-ib fs-18 mfr-10"></em><span class="d-ib c-99 fs-14"></span><!-- <a class="fs-14 c-66 fr" href="">更多></a> --></div>
				<div class="bd clear">
					<ul>
						<?php foreach($history_goods as $k => $item){ ?>
						<?php if ($k % 5 == 4) { ?>
							<li class="detail-box w-a">
						<?php } else{ ?>
							<li class="detail-box">
						<?php }?>
								<a href="<?php echo base_url(),'index/product?snm=',$item['goods_sn_main'];?>" target="_blank">
									<div class="img-box"><img src="<?php echo $img_host,$item['goods_img']?>" alt=""  width="240" height="180"></div>
									<div class="offer-detail">
										<h3><?php echo $item['goods_name']?></h3>
										<p class="price"><?php echo $curCur_flag,number_format(format_price($item['shop_price'],$cur_rate),2);?></p>
										<p class="gps">
										<?= !empty($origin_array[strtolower($item['country_flag'])])  ? $origin_array[strtolower($item['country_flag'])] : '';?>
										<?php if($item['country_flag'] != 'tw'  && $item['country_flag']) {?>
											<img src="<?php echo $this->config->item('country_flag_path').$item['country_flag'].'.jpg'?>" />
	                                    <?php }?>
										</p>
									</div>
								</a>
							</li>
						<?php if ((++$k % 5 == 0) && ($k < count($history_goods)-1)) { ?>
					</ul>
					<ul>
					<?php } ?>
					<?php } ?>
					</ul>
				</div>
				<?php if (count($history_goods) > 5 ) { ?>
				<span class="prev"><i class="pc-tps">&#xe63f;</i></span>
				<span class="next"><i class="pc-tps">&#xe648;</i></span>
				<?php }?>
			</div>
			<?php } ?>
			<!--end 浏览历史-->
		</div>
	</div>

	<!-- 楼层导航 -->
	<?php if(!empty($level_goods_floor)) {?>
		<div class="floatCtro">
			<?php
				$a = 1;
				foreach($level_goods as $k=>$name) {
			?>
			<p>
				
				<?php if($curLocation_id == 156 || $curLocation_id == 344){ ?>
				<i class="pc-tps lc-<?php echo $a; ?>"></i>
				<span><?php echo preg_replace ('/[\w&]+/', '', $name['cate']['cate_name']); ?></span>
				<?php }else{ ?>
				<i title="<?php echo $name['cate']['cate_name']; ?>" class="pc-tps lc-<?php echo $a; ?>"></i>
				<?php }?>
			</p>
			<?php
				$a++;
				}
			?>
			<em class="top" title="TOP"><i class="pc-tps">&#xe672;</i></em>
		</div>
	<?php }else{?>
		<div class="backToTop-up"><i class="pc-tps">&#xe672;</i></div>
	<?php } ?>




    <script>
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

		/**
		 * tps 顶部大图
		 * 调用image背景显示
		 */
		jQuery(".banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold", autoPlay:true, autoPage:true, interTime: 3000,
			startFun:function(i){
				var curLi = jQuery(".banner .bd li").eq(i); /* 当前大图的li */
				if( !!curLi.attr("_src") ){
					curLi.css("background-image",curLi.attr("_src")).removeAttr("_src") /* 将_src地址赋予li背景，然后删除_src */
				}
			}
		});
		jQuery(".tps-line").slide({ mainCell:".bd", effect:"left", autoPage:true, autoPlay:true, delayTime:500, interTime: 3000, trigger:"click"});
		jQuery(".img-gg").slide({ titCell:".hd ul", mainCell:"ul.pic", effect:"fold", autoPage:true, autoPlay:true, interTime:5000, });
		jQuery(".activity").slide({ titCell:".hd li", mainCell:".bd", effect:"left", delayTime:500, autoPlay:true, interTime:3000,});

		//固定导航
		$(function(){
			var nav = $('.site-header'),
				doc = $(document),
				win = $(window);
			win.scroll(function() {
				if (doc.scrollTop() > 500) {
					nav.addClass('fixed-top');
				}
				else {
					nav.removeClass('fixed-top');
				}
			});
			win.scroll();
		});

		
		// $(".floatCtro p").click(function(){
		// 	var index = $(this).index();
		// 	//让滚动条移动到对应页面位置
		// 	var topslide = $(".popular").eq(index).offset().top;
		// 	$("html,body").animate({"scrollTop":topslide},500);
		// });
		// //滚动条事件
		// $(window).scroll(function(){
		// 	var topslide = $(window).scrollTop();
		// 	$(".popular").each(function(i){
		// 		if(topslide>=$(this).offset().top){
		// 			$(".floatCtro p").eq($(this)).addClass("cur").siblings().removeClass("cur");
		// 		}
		// 	});
		// 	//显示楼梯导航
		// 	if(topslide>=1900){
		// 		$(".floatCtro").addClass("show");
		// 	}else{
		// 		$(".floatCtro").removeClass("show");
		// 	}
		// });
		// //顶部
		// $(".floatCtro .top").click(function(){
		// 	$('body,html').animate({scrollTop:0},500);
		// })


	//*****************右侧侧楼梯导航*******************//
		$(function(){
		  //1.楼梯什么时候显示，800px scroll--->scrollTop
		  $(window).on('scroll',function(){
		      var $scroll=$(this).scrollTop();
		      if($scroll>=1900){
		          $('.floatCtro').addClass("show");
		      }else{
		          $('.floatCtro').removeClass("show");
		      }

		      //4.拖动滚轮，对应的楼梯样式进行匹配
		      $('.popular').each(function(){
		          var $populartop=$('.popular').eq($(this).index()).offset().top+400;
		          //console.log($populartop);
		          if($populartop>$scroll){//楼层的top大于滚动条的距离
		              $('.popular p').removeClass('cur');
		              $('.popular p').eq($(this).index()).addClass('cur');
		              return false;//中断循环
		          }
		      });
		  });
		  //2.获取每个楼梯的offset().top,点击楼梯让对应的内容模块移动到对应的位置  offset().left
		  
		  var $popularli=$('.floatCtro p').not('.last');
		  $popularli.on('click',function(){
		      $(this).addClass('cur').siblings('p').removeClass('cur');
		      var $populartop=$('.popular').eq($(this).index()).offset().top;
		      //获取每个楼梯的offsetTop值
		      $('html,body').animate({//$('html,body')兼容问题body属于chrome
		          scrollTop:$populartop
		      })
		  });

			//3.回到顶部
		  	$('.floatCtro .top').on('click',function(){
		      $('html,body').animate({//$('html,body')兼容问题body属于chrome
		          scrollTop:0
		      })
		  });


		})

	</script>


<?php
if(isset($_COOKIE['logout_wohao_url']) && !empty($_COOKIE['logout_wohao_url']))
{
	$logout_wohao_url = $_COOKIE['logout_wohao_url'];
	delete_cookie("logout_wohao_url");
} else {
	$logout_wohao_url = '';
}
echo $logout_wohao_url;
?>
<script>
	$(function(){
		var logout_wohao_url = "<?php echo $logout_wohao_url ?>";
		if (logout_wohao_url.length >0) {
			$.ajax({
				type: "get",
				url: logout_wohao_url,
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

