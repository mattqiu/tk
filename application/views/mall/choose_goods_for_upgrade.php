<html>
<head>
    <meta charset="utf-8">
    <title><?php echo lang('choose_product')?></title>
</head>
<body>

<!-- top -->
<div class="MyCart w1200 t-z">
    <div class="dianxx clear">
        <i><img src="<?php echo base_url("/img/$curLanguage/product.png")?>"/></i>
        <!-- 用户升级选购-->
        <?php if(isset($data)&& $data!=null){?>
            <div class="col-md-2 clear">
				<dl class="clear">
					<dt><s class="s2"></s></dt>
					<dd>
                        <em><?php echo $data['old_level'] ?></em>
                        <br>
						<?php echo $data['name'] ?>
					</dd>
				</dl>
            </div>
            <div class="col-md-10 clear">
                <div class="right clear">
                    <?php
                    $attr = array(
                        'name' => $data['name'],
                        'create_time' => $data['create_time'],
                        'old_level' => $data['old_level'],
                        'now_level' => $data['now_level'],
                        'pay_money' => $data['pay_money']
                    );
                    ?>
                    <p><?php echo lang_attr('choose_goods_row_1_upgrade', $attr); ?></p>
                    <p><?php echo lang_attr('choose_goods_row_2_upgrade', $attr); ?></p>
                    <p class="ts"><?php echo lang_attr('choose_goods_row_3_upgrade', $attr); ?></p>
                </div>
            </div>
        <?php }?>
    </div>

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
                <button type="submit" target="_blank"><i class="pc-tps">&#xe647;</i>
            </div>
        </div>
    </form>

    <!-- 套餐区 -->
    <p class="switch-cart mt-40 clear"><em><?php echo lang('group_space_choose_group')?></em><span class="size"><?php echo lang('must_least_one_to_choose'); ?></span></p>
    <div class="taocqu_1 clear">
        <?php if (isset($group_info) && $group_info != null) { ?>
            <?php foreach ($group_info as $k=>$group) { ?>
           <!--      <p class="goux" id="choose_group"> -->
                <p class="goux choose_group" >
                    <label group_id="<?php echo $k ?>">
                        <input type="checkbox" id="sel_group<?=$k?>" autocomplete="off" />
                        <span><?php echo $group['goods_name'] ?></span>
                    </label>
                </p>
                <div class="t-c clear">
                    <div class="JS-tc">
                        <ul>
                            <?php $i=0;?>
                            <?php foreach ($group['list'] as $goods) { ?>
                                <?php $i++;?>
                                <li>
                                    <?php if($i<count($group['list'])){?>
                                    <?php }?>
                                    <dl class="clear">
                                        <dt id='imgInfo'><a href="javascript:get_goods_info('<?php echo $goods["info"]["goods_id"] ?>','<?php echo $goods["info"]["goods_sn_main"] ?>');"><img src="<?php echo $img_host.$goods['info']['goods_img'] ?>"></a> </dt>
                                        <dd>
                                            <a class="tit" href="javascript:get_goods_info('<?php echo $goods["info"]["goods_id"] ?>','<?php echo $goods["info"]["goods_sn_main"] ?>');" class="tit" ><?php echo $goods['info']['goods_name'] ?></a>
                                            <p> <b class="cse">$<?php echo $goods['info']['shop_price'] ?></b> ×<?php echo $goods['num'] ?></p>
                                        </dd>
                                    </dl>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if(count($group['list'])>4){?>
                            <span class="prev"><i class="pc-tps">&#xe63f;</i></span>
                            <span class="next"><i class="pc-tps">&#xe648;</i></span>
                        <?php }?>
                    </div>
                    <ul class="rig">
                        <li><?php echo lang('group_price')?><em class="c-o">$<?php echo $group['shop_price'] ?></em></li>
                        <li><?php echo lang('already_save')?> $<?php echo $group['total'] - $group['shop_price']; ?></li>
                        <li><?php echo lang('total_num')?> <?php echo $group['number']; ?></li>
                    </ul>
                </div>
            <?php } ?>
        <?php }else{ ?>
        <p class="update clear" style="text-align:center; height:50px;color: #444; font-size: 16px; font-weight: 700; line-height: 36px;"><?php echo lang('label_cate_no_records'); ?></p>
        <?php } ?>
    </div>

    <!-- 单品区 -->
    <p class="switch-cart mt-40 clear"><em><?php echo lang('alone_goods')?></em><span class="size"><?php echo lang('can_choose_alone_goods')?></span></p>
    <div class="tps-stars search clear">
        <?php if(isset($goods_info) && $group_info != null){ ?>
        <ul class="mt-n clear">
            <?php foreach ($goods_info as $goods) { ?>
                <li>
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
                        <a href="javaScript:void(0);" class="Collect js-like" data-sn="<?php echo $goods['goods_sn_main'] ?>" data-id="<?php echo $goods['goods_id'] ?>"></a>
                        <a class="img" href="javascript:get_goods_info('<?php echo $goods["goods_id"] ?>','<?php echo $goods["goods_sn_main"] ?>');"><img src="<?php echo $img_host.$goods['goods_img'] ?>"></a>
                        <dl class="tit">
                            <dd class="fl">
                                <p><?php echo $goods['goods_name']; ?></p>
                                <p class="c-o fs-14">$<?php echo $goods['shop_price'] ?></p>
                            </dd>
                            <dt class="fr">
                                <img src="<?php  echo $this->config->item('country_flag_path').$group['country_flag'].'.jpg'?>" />
                                <p class="c-bb"><?php echo $origin_array[strtolower($group['country_flag'])];?></p>
                            </dt>
                        </dl>
                        <div class="huangou"> 
                            <a href="javascript:;" class="Buy" goods_id="<?php echo $goods['goods_id']?>" ><em class="pc-tps">&#xe60f;</em><?php echo lang('huangou')?></a>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <?php }else{ ?>
            <p class="update clear" style="text-align:center; height:50px;color: #444; font-size: 16px; font-weight: 700; line-height: 36px;"><?php echo lang('label_cate_no_records'); ?></p>
        <?php } ?>
    </div>

    <!-- 代品券 -->
    <?php if(date('Y-m-d H:i:s',time()) < config_item('upgrade_not_coupon')){?>
    <p class="switch-cart mt-40 clear"><em><?php echo lang('three_coupons')?></em><span class="size"><i class="zhis_cc"></i><?php echo lang('can_choose_coupons')?></span></p>
    <div class="taocqu_3 clear">
        <?php foreach (config_item('coupons_money') as $k => $v) { ?>
            <div class="col-md-2">
                <div class="dapj clear">
                	<span>
                        <p><?php echo lang('d_p_coupons')?></p>
                        <p>$<?php echo $v ?></p>
                    </span>
                </div>
                <div class="Spinner clear"><b><?php echo lang('number')?></b><!--<a class="DisDe" href="javascript:coupons_num_reduction()"><i>-</i></a>-->
                    <input class="coupons_num" value="1" autocomplete="off" maxlength="4" id="<?php echo $k ?>">
                    <!--<a class="Increase" href="javascript:coupons_num_add()"><i>+</i></a>-->
                </div>
                <p class="goux">
                    <label class="coupons_selected" coupons_id="<?php echo $k?>">
                        <input type="checkbox" id="chb_coupons" autocomplete="off">
                        <span><?php echo lang('gouxuan')?></span>
                    </label>
                </p>
            </div>
        <?php } ?>
    </div>
    <?php } ?>

</div>

<!-- 换购区 -->
<div class="pop-compare huangouqu clear">
    <div class="head clear">
        <h3><?php echo lang('huangou_space')?></h3>
        <a href="javascript:;" class="c-b Yinchang"><?php echo lang('hidden')?></a>
    </div>
    <div class="t-c clear">
        <div class="JS-tc">
            <ul id="buy_area">
            </ul>
        </div>
        <ul class="rig">
            <li><?php echo lang('all_choose_goods_price')?></li>
            <li>$<b class="c-o" id="product_total_money">--</b></li>
            <li><a href="javascript:confirm_choose_upgrade_1() ;" class="btn_Bhui"><?php echo lang('confirm_huangou')?></a>
            <span id="choose_goods_msg" style="color: #F00000"></span></li>
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
    /**
     * 语言获取方法
     * @param  {[type]} l [description]
     * @return {[type]}   [description]
     */
    var lang = function(l)
    {
        if(l == "delete")
        {
            return "<?php echo lang('delete')?>";
        }
    }
</script>

<script>
    jQuery(".JS-tc").slide({ mainCell:"ul", autoPlay:true, effect:"left", vis:4, scroll:2, autoPage:true, pnLoop:false, interTime:4000,});
    $("body").click(function(){
        $("#fullbg").css("display","none");
        $("#BOX_nav").css("display","none");
    });
    $(".t-goods").click(function(event){
        event.stopPropagation();
    });
</script>

<script>
    /***确认换购(升级)***/
    function confirm_choose_upgrade_1(){
        <?php if (date('Y-m-d H:i:s', time()) >= config_item('upgrade_not_coupon')) {?>
            $.ajax({
                type: "POST",
                url: "/choose_goods_for_upgrade/confirm_choose",
                data: {},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        if (data.code == '101') {
                            layer.confirm("<?php echo lang('user_upgrade_not_coupon_3')?>", {
                                icon: 3,
                                title: "<?php echo lang('user_upgrade_not_coupon_is')?>",
                                closeBtn: 2,
                                btn: ['<?php echo lang('yes')?>', '<?php echo lang('no')?>']
                            }, function (index) {
                                window.location.href = data.redirect_url;
                            });
                    }else {
                            window.location.href = data.redirect_url;
                        }
                    }else{
                        layer.msg(data.msg);
                        //setTimeout("$('#choose_package_msg').text('')", 3000);
                    }
                }
            });
        <?php }else{?>
            $.ajax({
                type: "POST",
                url: "/choose_goods_for_upgrade/confirm_choose",
                data: {},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        window.location.href=data.redirect_url;
                    }else{
                        layer.msg(data.msg);
                        //setTimeout("$('#choose_package_msg').text('')", 3000);
                    }
                }
            });
        <?php }?>
    }

</script>

</body>
</html>