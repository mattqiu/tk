<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo lang('choose_product')?></title>
    <script src="<?php echo base_url(THEME . '/js/jquery-1.11.1.js') ?>"></script>
    <script src="<?php echo base_url(THEME . '/js/SuperSlide2.1.js') ?>"></script>
</head>
<body>
<!--top-->
<div class="MyCart w1200 t-z">
    <div class="dianxx clear"><i></i>
        <!-- 用户升级选购-->
        <?php if(isset($data)&& $data!=null){?>
            <div class="col-md-2 clear">
                <dl class="clear">
                    <dt><s class="s2"></s></dt>
                    <dd><em><?php echo $data['new_level'] ?></em>
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
                        'new_level' => $data['new_level'],
                        'pay_money' => $data['pay_money']
                    );
                    ?>
                    <p><?php echo lang_attr('choose_goods_row_1', $attr); ?></p>
                    <p><?php echo lang_attr('choose_goods_row_2', $attr); ?></p>
                    <p class="ts"><?php echo lang_attr('choose_goods_row_3', $attr); ?></p>
                </div>
            </div>
        <?php }?>
    </div>
    <!----套餐区---->
    <p class="switch-cart clear"><em><?php echo lang('group_space_choose_group')?></em><span class="size"><i class="zhis_cc"></i><?php echo lang('must_least_one_to_choose')?></span></p>
    <div class="taocqu_1 clear">
        <?php if (isset($group_info) && $group_info != null) { ?>
            <?php foreach ($group_info as $k=>$group) { ?>
            <p class="goux" id="choose_group">
                <label group_id="<?php echo $k ?>">
                    <input type="checkbox" autocomplete="off" id="sel_group"/>
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
								<dt><a href="javascript:get_goods_info('<?php echo $goods["info"]["goods_id"] ?>','<?php echo $goods["info"]["goods_sn_main"] ?>');"><img src="<?php echo $img_host.$goods['info']['goods_img'] ?>"></a> </dt>
								<dd><a href="javascript:get_goods_info('<?php echo $goods["info"]["goods_id"] ?>','<?php echo $goods["info"]["goods_sn_main"] ?>');" class="tit"><?php echo $goods['info']['goods_name'] ?></a>
									<p> <b class="cse">$<?php echo $goods['info']['shop_price'] ?></b> </p>
									<p><b>×<?php echo $goods['num'] ?></b></p>
								</dd>
							</dl>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php if(count($group['list'])>4){?>
                        <span class="prev"></span> <span class="next"></span>
                    <?php }?>
                </div>
                <ul class="rig">
                    <li><?php echo lang('group_price')?><em class="c-o">$<?php echo $group['shop_price'] ?></em></li>
                    <li><?php echo lang('already_save')?> $<?php echo $group['total'] - $group['shop_price']; ?></li>
                    <li><?php echo lang('total_num')?> <?php echo $group['number']; ?></li>
                </ul>
            </div>
          <?php } ?>
        <?php } ?>
    </div>

    <!----代品券---->
    <p class="switch-cart mt-40 clear"><em><?php echo lang('three_coupons')?></em><span class="size"><i class="zhis_cc"></i><?php echo lang('can_choose_coupons')?></span> </p>
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
                    <!--<a class="Increase" href="javascript:coupons_num_add()"><i>+</i></a>--></div>
                    <p class="goux">
                        <label class="coupons_selected" coupons_id="<?php echo $k?>">
                            <input type="checkbox" id="chb_coupons" autocomplete="off">
                            <span><?php echo lang('gouxuan')?></span>
                        </label>
                    </p>
                </div>
        <?php } ?>
    </div>
</div>


<!---换购区---->
<div class="pop-compare huangouqu clear">
    <div class="head clear">
        <h3><?php echo lang('huangou_space')?></h3>
        <a href="javascript:;" class="c-b Yinchang"><?php echo lang('hidden')?></a>
    </div>
    <div class="t-c clear">
        <div class="JS-tc">
            <ul id="buy_area">
            </ul>
<!--            <span class="prev"></span><span class="next"></span>-->
        </div>
        <ul class="rig">
            <li><?php echo lang('all_choose_goods_price')?></li>
            <li><b>$</b><b id="product_total_money">0</b></li>
            <li><a href="javascript:confirm_choose() ;" class="btn_Bhui" style="background: #336699"><?php echo lang('confirm_huangou')?></a><span id="choose_goods_msg" style="color: #F00000"></span></li>
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
    jQuery(".JS-tc").slide({ mainCell:"ul", autoPlay:true, effect:"left", vis:4, scroll:2, autoPage:true, pnLoop:false, interTime:4000});
    $("body").click(function(){
        $("#fullbg").css("display","none");
        $("#BOX_nav").css("display","none");
    });
    $(".t-goods").click(function(event){
        event.stopPropagation();
    });
</script>
<script src="js/main.js?v=20170614"></script>
</body>
</html>