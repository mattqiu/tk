<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo lang('choose_product')?></title>
    <link href="<?php echo base_url(THEME . '/css/all.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(THEME . '/css/only_english.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(THEME . '/css/index.css') ?>" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url(THEME . '/js/jquery-1.11.1.js') ?>"></script>
    <script src="<?php echo base_url(THEME . '/js/SuperSlide2.1.js') ?>"></script>
</head>
<body>
<!--top-->
<div class="bj_zhuti">
    <div class="MyCart">
        <div class="container clear">
            <div class="dianxx clear"><i></i>
                <!-- 用户升级选购-->
                <?php if(isset($data)&& $data!=null){?>
                    <div class="col-md-2 clear">
                        <dl class="clear">
                            <dt><s class="s2"></s></dt>
                            <dd><em><?php echo $data['new_level'] ?></em>
                            <?php echo $data['name'] ?>
							<p><?php echo lang('choose_receiving_space')?></p>
							<select class="guojia select_country2" name="country_id" autocomplete="off" id="con_and_area2">
								<?php foreach($countrys as $country){?>
									<option value="<?php echo $country['country_id']?>" <?php echo $country_id == $country['country_id'] ? 'selected' : '';?>><?php echo $country['name_'.$curLanguage]?></option>
								<?php }?>
							</select>
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
                            <p><?php echo lang_attr('choose_package_row_1', $attr); ?></p>
                            <p><?php echo lang_attr('choose_package_row_2', $attr); ?></p>
                            <p class="ts"><?php echo lang_attr('choose_package_row_3', $attr); ?></p>
                        </div>
                    </div>
                <?php }?>
            </div>

            <!----套餐区---->
            <p class="switch-cart clear"><em><?php echo lang('group_space_choose_group')?></em><span class="size"><i class="zhis_cc"></i><?php echo lang('must_least_one_to_choose')?></span></p>
            <div class="taocqu_1 clear">
                <div class="container clear">
                    <?php if (isset($group_info) && $group_info != null) { ?>
                        <?php foreach ($group_info as $k=>$group) { ?>
                            <p class="goux" id="choose_group">
                                <label group_id="<?php echo $k ?>">
                                    <input type="checkbox" autocomplete="off" id="sel_group"/>
                                    <span><?php echo $group['goods_name'] ?></span>
                                </label>
                            </p>
                            <div class="huangouqu clear">
                                <div class="body clear">
                                    <div class="col-md-10 clear">
                                        <div class="JS_p">
                                            <ul>
                                                <?php $i=0;?>
                                                <?php foreach ($group['list'] as $goods) { ?>
                                                    <?php $i++;?>
                                                    <li class="col-md-3">
                                                        <?php if($i<count($group['list'])){?>
                                                            <b class="jiah">+</b>
                                                        <?php }?>
                                                        <dl class="marg_10 clear">
                                                            <dt><a href="<?php echo base_url('index/product?snm='.$goods['info']['goods_sn_main'])?>"><img src="<?php echo $goods['info']['goods_img'] ?>"></a> </dt>
                                                            <dd><a href="<?php echo base_url('index/product?snm='.$goods['info']['goods_sn_main'])?>" class="tit" target="_blank"><?php echo $goods['info']['goods_name'] ?></a>
                                                                <p> <b class="cse">$<?php echo $goods['info']['shop_price'] ?></b> </p>
                                                                <p><b>×<?php echo $goods['num'] ?></b></p>
                                                            </dd>
                                                        </dl>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                            <a class="prev"></a> <a class="next"></a></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="rig"><b class="dengh">=</b>
                                                <p><?php echo lang('group_price')?><em>$<?php echo $group['shop_price'] ?></em></p>
                                                <p><?php echo lang('already_save')?> $<?php echo $group['total'] - $group['shop_price']; ?></p>
                                                <p><?php echo lang('total_num')?> <?php echo $group['number']; ?></p>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>

            <!--单品区--->
            <?php if(!empty($goods_info)) { ?>
            <p class="switch-cart clear"><em><?php echo lang('alone_goods')?></em><span class="size"><i class="zhis_cc"></i><?php echo lang('can_choose_alone_goods')?></span></p>
            <div class="taocqu_2 clear">
                <div class="JS_p">
                    <ul>
                        <?php foreach ($goods_info as $goods) { ?>
                            <li class="col-md-3 clear">
                                <div class="Drive tao_z marg_10">
                                    <div class="img_box v_box">
                                        <a href="javaScript:;" class="Collect js-like" data-sn="<?php echo $goods['goods_sn_main'] ?>" data-id="<?php echo $goods['goods_id'] ?>"><s></s><?php echo lang('collect')?></a>
                                        <a href="">
                                            <img src="<?php echo $goods['goods_img'] ?>">
                                        </a>
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
                                                <p class="pink">$<?php echo $goods['shop_price'] ?></p>
                                                <s>$<?php echo $goods['market_price'] ?></s></div>
                                            <div class="huangou"><a href="javascript:;" class="Buy" goods_id="<?php echo $goods['goods_id']?>" ><i></i><?php echo lang('huangou')?></a>
                                                <div class="huangou_sl">
                                                    <input type="text" value="1" name="nums" id="goods_num" maxlength="3">
                                                    <div class="de_in"><s class="s1"></s><s class="s2"></s></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <a class="prev"></a> <a class="next"></a></div>
            </div>
            <?php } ?>

            <!----代品券---->
            <p class="switch-cart clear"><em><?php echo lang('three_coupons')?></em><span class="size"><i class="zhis_cc"></i><?php echo lang('can_choose_coupons')?></span> </p>
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
    </div>
</div>

<!---换购区---->
<div class="pop-compare clear">
    <div class="huangouqu">
        <div class="head clear">
            <h3><?php echo lang('huangou_space')?></h3>
            <a href="javascript:;" class="c-b Yinchang"><?php echo lang('hidden')?></a></div>
        <div class="body clear">
            <div class="col-md-10 clear">
                <div class="JS_p">
                    <ul id="buy_area">
                    </ul>
                    <a class="prev"></a> <a class="next"></a>
                </div>
            </div>
            <div class="col-md-2">
                <div class="rig">
                    <p><?php echo lang('all_choose_goods_price')?></p>
                    <p><b>$</b><b id="product_total_money">0</b></p>
                    <a href="javascript:confirm_choose() ;" class="btn_Bhui" style="background: #336699"><?php echo lang('confirm_huangou')?></a>
                    <span id="choose_package_msg" style="color: #F00000"></span>
                </div>
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
<script>jQuery(".JS_p").slide({
        mainCell: "ul",
        autoPlay: true,
        effect: "left",
        vis: 4,
        scroll: 1,
        autoPage: true,
        pnLoop: false,
        interTime: 10000
    });
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
						location.href = 'choose_package?param='+res.param;
					} else {
						layer.msg(res.msg);
					}
				}
			});
		}
	}
</script>
<script src="js/main.js?v=20170614"></script>
</body>
</html>