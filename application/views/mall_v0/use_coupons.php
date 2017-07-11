<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo lang('use_coupons_page')?></title>
    <link href="../css/head.css" rel="stylesheet" type="text/css">
    <link href="../css/all.css" rel="stylesheet" type="text/css">
    <script src="../js/jquery-1.11.1.js"></script>
    <script src="../js/SuperSlide2.1.js"></script>
</head>
<body>
<!--top-->
<script>
    jQuery
    (".sidebar-info").slide({
        type:"menu", //效果类型
        titCell:".mainCate", // 鼠标触发对象
        targetCell:".subCate", // 效果对象，必须被titCell包含
        delayTime:400, // 效果时间
        triggerTime:150, //鼠标延迟触发时间
        defaultPlay:false,//默认执行
        returnDefault:true//返回默认
    });
</script>
<div class="bj_zhuti">
    <div class="container banner taozhuang clear">
        <div class="bd clear">
            <ul>
                <li style="height:280px" class="loading_big"><img width="1200" height="280" src="<?php echo base_url(THEME.'/img/'.$curLan.'_goods_g.jpg')?>"/></li>
            </ul>
        </div>
        <div class="hd clear">
            <ul>
                <li></li>
            </ul>
        </div>
        <div class="dengji clear">
            <dl class="clear">
                <dt><s class="s1"></s></dt>
                <dd><em>
                        <?php
                            if($user_info['user_rank']==4){
                                echo lang('member_free');
                            }
                            if($user_info['user_rank']==3){
                                echo lang('member_silver');
                            }
                            if($user_info['user_rank']==2){
                                echo lang('member_platinum');
                            }
                            if($user_info['user_rank']==1){
                                echo lang('member_diamond');
                            }
                        ?>
                    </em>
                </dd>
                <dd><?php echo $user_info['name']?></dd>
            </dl>
            <ul class="body clear">
                <li data-toggle="arrowdown" id="arrow5"><a href="" class="daipin"><span><?php echo lang_attr('coupons_total_num',$coupons_list)?><u class="down-icon run-down"></u></span></a></li>
                <li><?php echo lang('value')?> <b class="money"><?php echo "$".$coupons_list['total_money']?></b></li>
                <li><a href="/ucenter/about_exchange_coupon" class="c-b"><?php echo lang('review_coupons_rule')?></a></li>
                <ul data-toggle="hidden-box" id="nav-box5" class="daijin clear">
                    <?php foreach($coupons_info as $k=>$v){?>
                        <li><b class="daipin"><span><?php echo "$".$k ?></span></b><span class="sz"><?php echo lang('number')?>: <em><?php echo $v ?></em></span></li>
                    <?php }?>
                </ul>
            </ul>
        </div>
    </div>
    <script>jQuery(".banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold", autoPlay:true, autoPage:true, trigger:"click"}); </script>
    <div class="container clear">
        <div class="crumbs clear"> <a href="">Home</a> > <a href="">Porto Venere</a> > <a href="">Health Care Products </a> </div>
        <div class="f-line">
            <div class="col-md-8">
                <div class="left">
                    <h2><?php echo lang("group_sale_area")?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container top_15 clear">
        <?php foreach($group_list as $group) {?>
            <div class="grid_1_of_5 no_left clear" style="margin-left: 11px;">
                <div class="Drive Search tao_z clear">
                    <div class="img_box">
                        <a href="javaScript:;" class="Collect js-like" data-sn="<?php echo $group->goods_sn_main ?>" data-id="<?php echo $group->goods_id ?>"><s></s><?php echo lang('collect')?></a>
                        <a href="<?php echo base_url('index/product?snm='.$group->goods_sn_main)?>"><img src="<?php echo $group->goods_img ?>"></a>
                    </div>
                    <div class="cp_detail clear">
                        <h4><a href="<?php echo base_url('index/product?snm='.$group->goods_sn_main)?>" target="_blank"><?php echo $group->goods_name ?></a></h4>
                        <div class="xingxing clear">
                            <div id="starBg" class="star_bg">
                                <div class="star"></div>
                            </div>
                            <div class="Reviews">(<span class="pink">3</span> Reviews)</div>
                        </div>
                        <div class="jiage clear">
                            <div class="title">
                                <p class="pink">$<?php echo $group->shop_price ?></p>
                                <s>$<?php echo $group->market_price ?></s> </div>
                            <div class="huangou">
                                <a href="javascript:;" class="Buy" goods_id="<?php echo $group->goods_id ?>"><i></i><?php echo lang('buy')?></a>
                                <div class="huangou_sl">
                                    <input type="text" value="1" name="nums" class="" id="goods_num" maxlength="4">
                                    <div class="de_in"><s class="s1"></s><s class="s2"></s></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>


        <!--单品区-->
        <?php if(!empty($goods_list) && $userInfo['id'] =='1380100220') {?>
            <div class="col-md-12" style="background: #fff; text-indent: 10px; border: 1px solid #E4E7E7;margin-bottom: 15px;">
                <h3 style="color: #000; font-size: 16px;" class="title"><?php echo lang("goods_sale_area")?></h3>
            </div>
            <?php foreach($goods_list as $k=>$goods){?>
                <div class="grid_1_of_5 no_left clear" style="margin-left: 11px;">
                    <div class="Drive Search tao_z clear">
                        <div class="img_box">
                            <a href="javaScript:;" class="Collect js-like" data-sn="<?php echo $goods['goods_sn_main'] ?>" data-id="<?php echo $goods['goods_id'] ?>"><s></s><?php echo lang('collect')?></a>
                            <a href=""><img src="<?php echo $goods['goods_img']?>"></a>
                        </div>
                        <div class="cp_detail clear">
                            <h4><a href="<?php echo base_url('index/product?snm='.$goods['goods_sn_main'])?>" target="_blank"><?php echo $goods['goods_name'] ?></a></h4>
                            <div class="xingxing clear">
                                <div id="starBg" class="star_bg">
                                    <div class="star"></div>
                                </div>
                                <div class="Reviews">(<span class="pink">3</span> Reviews)</div>
                            </div>
                            <div class="jiage clear">
                                <div class="title">
                                    <p class="pink">$<?php echo $goods['shop_price']?></p>
                                    <s>$<?php echo $goods['market_price']?></s> </div>
                                <div class="huangou"> <a href="javascript:;" goods_id="<?php echo $goods['goods_id']?>" class="Buy"><i></i><?php echo lang('buy')?></a>
                                    <div class="huangou_sl">
                                        <input type="text" value="1" name="nums" class="" id="goods_num" maxlength="4">
                                        <div class="de_in"><s class="s1"></s><s class="s2"></s></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        <?php }?>
    </div>
</div>
<div class="pop-compare clear">
    <div class="huangouqu">
        <div class="head clear">
            <h3><?php echo lang('huangou_space')?></h3>
            <a href="javascript:;" class="c-b Yinchang"><?php echo lang('hidden')?></a> </div>
        <div class="body clear"><div class="JS_p">
                    <div class="col-md-10 clear">

                    <ul id="buy_area">
                    </ul>
                    <a class="prev"></a> <a class="next"></a> </div>
            </div>
            <div class="col-md-2">
                <div class="rig">
                    <p><?php echo lang('goods_total_money')?>$<b id="product_total_money"></b></p>
                    <p><?php echo lang_attr('you_coupons_total_money',$coupons_list)?></p>
                    <!--<a href="" class="btn_hong">确认换购</a>-->
                    <a href="javascript:confirm_choose_coupons();" class="btn_Bhui" style="background: #006699"><?php echo lang('confirm_huangou')?></a> </div>
            </div>
        </div>
    </div>
</div>
<?php if($is_show){?>
	<div class="backdrop-bl"></div>
	<div class="shouhuo">
		<h3><?php echo lang('choose_receiving_space')?></h3>
		<div class="neir"> <span><?php echo lang('hello_please_choose_receiving_space')?></span>
			<p>
				<select class="guojia" name="country_id" autocomplete="off" id="con_and_area">
					<option value=""><?php echo lang('country')?></option>
					<?php foreach($countrys as $country){?>
						<option value="<?php echo $country['country_id']?>"><?php echo $country['name_'.$curLanguage]?></option>
					<?php }?>
				</select>
				<input type="button" onclick="" value="<?php echo lang('confirm')?>" class="btn-Register select_country">
			</p>
		</div>
	</div>
<?php } ?>
<script>jQuery(".JS_p").slide({ mainCell:"ul", autoPlay:true, effect:"left", vis:4, scroll:1, autoPage:true, pnLoop:false, interTime:1500 });
	$(function(){
		$('.select_country').click(function(){
			select_country('con_and_area');
		});
		$('.select_country2').change(function(){
			select_country('con_and_area2')
		});
	})
	function select_country(item){

		var country_id = $( '#' + item + " option:selected").val();

		if(country_id == '' || country_id == 'undefined'){
			layer.msg('Please Select Country');
			return ;
		}else{
			$.ajax({
				type: "POST",
				url: "member_upgrade/return_to",
				data: {country_id:country_id},
				dataType: "json",
				success: function (res) {
					if (res.success) {
						location.href = 'use_coupons?param='+res.param;
					} else {
						layer.msg(res.msg);
					}
				}
			});
		}
	}
</script>
<script src="../js/main.js"></script>
</body>
</html>