<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo lang('use_coupons_page')?></title>
</head>
<body>
<!-- top -->
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
<div class="main-content t-z">   
    <div class="banner clear">
        <div class="bd clear">
            <ul>
                <li class="loading_big"><img src="<?php echo base_url(THEME.'/img/'.$curLan.'_goods_g.jpg')?>"/></li>
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
                <dd>
                    <em>
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
                <li>
                    <a href="" class="daipin"><span><?php echo lang_attr('coupons_total_num',$coupons_list)?></span></a>
                    <ol class="daijin xs clear">
                        <?php foreach($coupons_info as $k=>$v){?>
                        <li><b class="daipin"><span><?php echo "$".$k ?></span></b><span class="sz"><?php echo lang('number')?>: <em><?php echo $v ?></em></span></li>
                        <?php }?>
                    </ol>
                </li>
                <li><?php echo lang('value')?> <b class="money"><?php echo "$".$coupons_list['total_money']?></b></li>
                <li><a href="/ucenter/about_exchange_coupon" class="c-b"><?php echo lang('review_coupons_rule')?></a></li>
            </ul>
        </div>
    </div>
    

    <script>jQuery(".banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold", autoPlay:true, autoPage:true, trigger:"click",}); </script>
    <div class="w1200">
        
        <!-- leon 搜索内容 -->
        <form method="GET">
            <div class="update clear">
                <span><?php echo lang('commodity_search');?></span>
                <div class="tps-search">
                    <?php if($group_search_hint == 1){ ?>
                    <input name="search" class="search-text" maxlength="200px" value="<?php echo $group_search; ?>" placeholder="<?php echo lang('search_prompt')?>" autocomplete="off">
                    <?php }elseif ($group_search_hint == 2){ ?>
                    <input name="search" class="search-text" value="" placeholder="<?php echo $group_search; ?>" autocomplete="off">
                    <?php } ?>
                    <button type="submit" target="_blank"><i class="pc-tps">&#xe647;</i></button>
                </div>
            </div>
        </form>

        <!-- 套装 -->
        <div class="tps-stars search clear">
            <div class="hd clear">
                <h2 class="title"><?php echo lang("group_sale_area")?></h2>
            </div>
            <?php if(isset($group_list) && $group_list != null){ ?>
            <ul class="mt-n clear">
                    <?php $group_list = array_values($group_list); ?>
                    <?php foreach($group_list as $k=>$group) {?>
                    <?php if (($k+1)%5 == 0) { ?>
                        <li class="mr-n">
                    <?php } else{ ?>
                        <li>
                    <?php }?>
                    <div class="img-xg">
                        <p class="b-q">
                            <?php if($group['is_promote']) {?>
                                <s class="salle" title="<?php echo lang('label_nav_promote')?>"></s>
                            <?php }elseif($group['is_hot']){?>
                                <s class="hot" title="<?php echo lang('label_comment')?>"></s>
                            <?php }elseif($group['is_new']){?>
                                <s class="new" title="<?php echo lang('label_single_sale')?>"></s>
                            <?php }?>

                            <?php if($group['is_free_shipping']) {?>
                                <s class="free" title="<?php echo lang('label_nav_free_ship')?>"></s>
                            <?php }?>
                        </p>

                        <i class="top"></i> <i class="right"></i> <i class="bottom"></i> <i class="left"></i>
                        <a class="img" href="javascript:get_goods_info('<?php echo $group['goods_id']; ?>','<?php echo $group['goods_sn_main']; ?>');">
                            <img src="<?php echo $img_host.$group['goods_img']; ?>">
                        </a>
                        <dl class="tit">
                            <dd class="fl">
                                <p>
                                    <a href="javascript:get_goods_info('<?php echo $group['goods_id']; ?>','<?php echo $group['goods_sn_main']; ?>');">
                                        <?php echo $group['goods_name'] ?>
                                    </a>
                                </p>
                                <p class="c-o fs-14">$<?php echo $group['shop_price'] ?> <span class="c-bb">/ <s>$<?php echo $group['market_price'] ?></s></span></p>
                            </dd>
                            <dt class="fr">
                                <img src="<?php  echo $this->config->item('country_flag_path').$group['country_flag'].'.jpg'?>" />
                                    <p class="c-bb">
                                    <?php echo $origin_array[strtolower($group['country_flag'])];?>
                                    </p>
                            </dt>
                        </dl>
                        <div class="huangou"> 
                            <a href="javascript:;" class="Buy" is_coupons = "1" goods_id="<?php echo $group['goods_id'] ?>"><em class="pc-tps">&#xe60f;</em><?php echo lang('buy')?></a>
                            <div class="huangou_sl">
                                <div class="sl c-99"><?php echo lang('num')?></div>
                                <input type="text" value="1" name="nums" class="" id="goods_num" maxlength="4">
                            </div>
                        </div>
                    </div>    
                </li>
                <?php }?>
            </ul>
            <?php }else{ ?>
                <p class="update clear" style="text-align:center; height:50px;color: #444; font-size: 16px; font-weight: 700; line-height: 36px;"><?php echo lang(label_cate_no_records); ?></p>
            <?php } ?>
        </div>
        
        <!-- 单品 -->
        <div class="tps-stars search clear">
            <div class="hd clear">
                <h2 class="title"><?php echo lang("goods_sale_area")?></h2>
            </div>
            <?php if(isset($goods_list) && $goods_list != null){ ?>
            <ul class="mt-n clear">
                <?php foreach($goods_list as $k=>$goods){?>
                    <?php if (($k+1)%5 == 0) { ?>
                        <li class="mr-n">
                    <?php } else{ ?>
                        <li>
                    <?php }?>
                    <div class="img-xg">
                        <p class="b-q">
                            <?php if($goods['is_promote']) {?>
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
                        <a class="img" href="javascript:get_goods_info('<?php echo $goods["goods_id"] ?>','<?php echo $goods["goods_sn_main"] ?>');"><img src="<?php echo $img_host.$goods['goods_img']?>"></a>
                        <dl class="tit">
                            <dd class="fl">
                                <p><a href="javascript:get_goods_info('<?php echo $goods["goods_id"] ?>','<?php echo $goods["goods_sn_main"] ?>');"><?php echo $goods['goods_name'] ?></a></p>
                                <p class="c-o fs-14">$<?php echo $goods['shop_price']?> <span class="c-bb">/ <s>$<?php echo $goods['market_price']?></s></span></p>
                            </dd>
                            <dt class="fr">
                                <img src="<?php echo $this->config->item('country_flag_path').$goods['country_flag'].'.jpg'?>" />
                                    <p class="c-bb">
                                    <?php echo $origin_array[strtolower($goods['country_flag'])];?>
                                    </p>
                            </dt>
                        </dl>
                        <div class="huangou"> 
                            <a href="javascript:;" goods_id="<?php echo $goods['goods_id']?>" is_coupons = "1" class="Buy"><em class="pc-tps">&#xe60f;</em><?php echo lang('buy')?></a>
                            <div class="huangou_sl">
                                <div class="sl c-99"><?php echo lang('num')?></div>
                                <input type="text" value="1" name="nums" class="" id="goods_num" maxlength="4">
                            </div>
                        </div>
                    </div>
                </li>
                <?php }?>
            </ul>
            <?php }else{ ?>
                <p class="update clear" style="text-align:center; height:50px;color: #444; font-size: 16px; font-weight: 700; line-height: 36px;"><?php echo lang(label_cate_no_records); ?></p>
            <?php } ?>
        </div>

    </div>
</div>

<div class="pop-compare huangouqu clear">
    <div class="head clear">
        <h3><?php echo lang('huangou_space')?></h3>
        <a href="javascript:;" class="c-b Yinchang"><?php echo lang('hidden')?></a>
    </div>

    <div class="t-c clear">
        <div class="JS-tc">
            <ul id="buy_area">
            </ul>
            <span class="prev"></span> <span class="next"></span>
        </div>
        <ul class="rig">
            <li><?php echo lang('goods_total_money')?><br>$<b class="c-o" id="product_total_money"></b></li>
            <li class="fs-14"><?php echo lang_attr('you_coupons_total_money',$coupons_list)?></li>
            <li><a href="javascript:confirm_choose_coupons();" class="btn_hong"><?php echo lang('confirm_huangou')?></a></li>
        </ul>
    </div>
</div>

<!--弹出层-->
<div class="wodl t-goods img100 clear" id="BOX_nav">
	<span class="close" onclick="closeBg();">×</span>
	<h3><?php echo lang('label_desc') ?></h3>
	<p class="tit goods_desc"></p>
	<div class="goods_img">

	</div>
</div>
<div class="xm-backdrop" id="fullbg"></div>
<script>
    $("body").click(function(){
        $("#fullbg").css("display","none");
        $("#BOX_nav").css("display","none");
    });
    $(".t-goods").click(function(event){
        event.stopPropagation();
    });
</script>
<script src="/js/main.js?v=20170614"></script>
</body>
</html>