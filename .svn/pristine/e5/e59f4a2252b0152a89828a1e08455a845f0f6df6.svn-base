<?php if($is_store === TRUE && $user_rank != '4'){ ?>
<div class="container" style="border:0px;">
    <?php if(isset($data)&& $data!=null){?>
	<div class="choose_alert">
            <form id="hidden_data" method="post" action="/choose_goods">
                <input type="hidden" name="user_id" value="<?php echo $data['user_id']?>" />
                <input type="hidden" name="name" value="<?php echo ucwords($data['name'])?>" />
                <input type="hidden" name="old_level" value="<?php echo $data['old_level']?>" />
                <input type="hidden" name="now_level" value="<?php echo $data['new_level']?>" />
                <input type="hidden" name="create_time" value="<?php echo $data['create_time']?>" />
            </form>
            <div class="tb1">
                <div class="ltxt">
                    <p style="margin-top:-5px;"><span style="color:#ff4400;font-weight:bold;"><?php echo lang('important_notice')?></span></p>
                    <p>
                        <?php echo sprintf(lang('choose_alert_row_1'),$data['name']) ?>
                        <?php echo sprintf(lang('choose_alert_row_2'),$data['create_time'],$data['new_level'],$data['pay_money'])?>
                    </p>
                    <p style="margin-bottom:10px;"><?php echo sprintf(lang('choose_alert_row_3'),$data['user_id'])?></p>
                </div>
                <div class="rbtn"><input type="button" value="<?php echo lang('go_to_choose')?>" class="btn_choose_cn" onclick="JavaScript:choose_goods();"></div>
            </div>
        <?php }?>
    </div>
</div>
<?php }?>
<div class="alert alert-error hidden payment_note" >
	<button type="button" class="close" data-dismiss="alert">×</button>
	<strong><?php echo lang('payment_note'); ?></strong>
</div>

<script>
	var index = location.href.indexOf('#');
	var lc = location.href.substr(index+1,7);
	if(index != -1 && lc == 'upgrade'){
		$('.payment_note').removeClass('hidden');
	}
</script>
<div class="container" style="border:1px dashed #ccc;background:#f8f8f8;padding-bottom:10px;position:relative;top:-6px;">
	<div class="top_txt"><span><?php echo $lastLoginInfo;?></span></div>

    <div class="tb1">
        <div class="hello">
        	<span>
            <?php
            $hour=(int)date("H");
            if($hour<=11){echo lang('good_morning');}
            else if($hour<=13){echo lang('good_noon');}
            else if($hour<=17){echo lang('good_afternoon');}
            else {echo lang('good_evening');}
            ?>
            <?php echo $user['name'] == '' ? $user['id'] : $user['name']?>
                <?php if($language_id == 4){?>
                    <span style="margin-left: 5px;"><?php echo "님"?></span>
                <?php }?>
            </span>
        </div><?php if($is_store === TRUE){ ?>

            <div class="id_line">
                <ul>
                    <li style="width:auto;min-width:15%;margin-left:15px;">
                        <span><?php echo lang('join_time'); ?>: </span>
                        <span style="color:#f57403;"><?php echo $join_time; ?></span>
                    </li>

                    <li style="width:auto;min-width:20%;margin-left:15px;">
                        <span><?php echo lang('cur_title').":"?></span>
                        <span><?php echo $cur_title_text?></span>
                        <?php $attr=array('sale_rank_up_time'=>$sale_rank_up_time); ?>
                        <span><?php echo lang_attr('sale_rank_up_time',$attr)?></span>
                    </li>
                </ul>
            </div>

            <div class="id_line">
            <ul>

                <li style="width:155px;"><span style="color:#333;padding-left:15px;"><?php echo lang('id')?>:</span> <span style="color:#6a6666;font-family:Arial, Helvetica, sans-serif;"><?php echo $user['id']?></span></li>

				<li style="width:auto;min-width:20%;margin-left:20px;">
                    <span>
                        <?php
						echo lang('store_level').' : ';
						?>
						<strong style="color:#f57403;">
							<?php
							if($user['user_rank']==1)echo lang('member_diamond');
							if($user['user_rank']==2)echo lang('member_platinum');
							if($user['user_rank']==3)echo lang('member_silver');
							if($user['user_rank']==4)echo lang('member_free');
							if($user['user_rank']==5)echo lang('member_bronze');
							?>
						</strong>
                    </span>

					<?php if($user['user_rank']!=1){?>
                        <a href="<?php echo base_url('ucenter/member_upgrade')?>" style="text-decoration:none;" > <input type="button" value="<?php echo lang('upgrade')?>" class="wel_upgrade"></a>
					<?php }?>

				</li>

            </ul>
        </div>
		<div class="id_line">
            <ul>
				<?php if($user['month_fee_rank'] < 4 || $user['user_rank'] != 4 ){?>
            	<li style="width:auto;min-width:15%;margin-left:15px;">
                    <span><?php echo lang('mothlyFeeCoupon'); ?>: </span>
                    <span style="color:#f57403;"><?php echo $monthly_fee_coupon_num.lang("zhang") ?></span>
                </li>
				<?php }?>
                <li style="width:auto;min-width:20%;margin-left:17px;">
                    <span><?php echo lang('dai_coupon')?><a href="<?php echo base_url('ucenter/my_coupons')?>" style="color:#08c;"> <?php echo $coupon_total_num?></a> <?php echo lang('zhang')?>   </span>
                </li>
                <li style="width:auto;min-width:20%;">
                    <span><?php echo lang('account_status')?></span>
                    <span><?php echo $account_status?></span>
                </li>

                <?php if($monthlyFeeCoupon){?>
                <li style="width:auto;min-width:20%;margin-left:12px;" id='monthlyFeeCouponText'>
                    <span>
                        <?php echo lang('mothlyFeeCoupon')?>: 1 <?php echo lang('zhang')?> 
                        (<?php echo $monthlyFeeCoupon['levelName']?>)  
                        <a href="javascript:useMonthlyFeeCoupon();" style="color:#08c;"><?php echo lang('clickToUse')?></a>
                    </span>
                </li>
                <?php }?>
            </ul>
        </div>

		<?php }?>
		<?php if($is_store === TRUE){ ?>
        <div class="ac_safe">
            <ul>
				<!--
                <li style="width:auto;min-width:40%;">
                    <ul>
                        <li style="color:#333;width:auto;min-width:18%;padding-left:15px;"><?php echo lang('account_security')?>: </li>
                        <li style="margin-left:10px;width:18px;height:18px; position:relative;top:3px; background:url(../../../img/new/ucenter/safe_info.png) no-repeat -4px -5px;"></li>
                        <li style="margin-left:10px;width:18px;height:18px; position:relative;top:3px; background:url(../../../img/new/ucenter/safe_info.png) no-repeat -29px -5px;"></li>
                        <li style="margin-left:10px;width:18px;height:18px; position:relative;top:3px; background:url(../../../img/new/ucenter/safe_info.png) no-repeat -53px -5px;"></li>
                        <li style="margin-left:10px;width:18px;height:18px; position:relative;top:3px; background:url(../../../img/new/ucenter/safe_info.png) no-repeat -76px -25px;"></li>
                        <li style="width:auto;min-width:20%;margin-left:10px;"><a href="/ucenter/account_safe" style="margin-left:5px;"><?php echo lang('go_to_perfect')?></a></li>
                    </ul>
                </li>
                -->
				<?php if($check === FALSE){?>
                <li style="width:auto;min-width:35%;margin-left:15px;">
                    <ul>
                        <li style="color:#333;width:auto;max-width:35%; text-decoration:none;"><?php echo lang('perfect_account')?>: </li>
						<li style="width:41px;min-height:7px;position:relative;top:6px;left:5px; background:url(../../../img/new/ucenter/safe_info.png) no-repeat -5px -70px;"></li>
						<li style="width:auto;max-width:45%;margin-left:15px;"><a href="ucenter/account_info"><?php echo lang('go_to_perfect')?></a></li>
                    </ul>
                </li>
				<?php }?>
				<?php if($is_join_plan){?>
					<li style="width:auto;min-width:45%;margin-left:15px;">
						<ul>
							<li style="color:#333;width:auto;max-width:100%; text-decoration:none;color: red;font-weight: bold"><?php echo lang('join_action_charge_month').$is_join_plan['create_time']?> </li>
						</ul>
					</li>
				<?php }?>
            </ul>
        </div>
		<?php } ?>
		<?php if($is_store === TRUE){ ?>

		<div class="sh_store_bg" style="clear:both;">
            <div class="sh_store" style="margin-top:5px;">
                <ul>
                    <li style="width:auto;min-width:50%;">
                        <span style="width:30%;margin-left:7px;"><?php echo lang('member_url').":"?></span>
                        <span>
                            <?php $modify_member_url_counts=config_item('member_url_modify_counts_limit')-$user['member_url_modify_counts']; ?>
                            <a style="width:180px;" target="_blank" href="<?php echo 'http://'.$user['member_url_prefix'].".".get_public_domain()?>" class="member_url">
                                <?php echo 'http://'.$user['member_url_prefix'].".".get_public_domain()?>
                            </a>
                        </span>
                    </li>
                    <li style="width:auto;min-width:45%;margin-left:10px;">
                        <span style="border-right:1px solid #ccc;padding-right:5px;"><a href="Javascript:;" id="copy_member_url"><?php echo lang('copy')?></a></span>
                        <span style="border-right:1px solid #ccc;margin-left:2px;padding-right:5px;"><a href="javaScript:modify_member_url()"><?php echo lang('modify')?></a></span>
                        <span style=" color: #666;"><?php echo sprintf(lang('modify_the_opportunity'),$modify_member_url_counts)?></span>
                    </li>
                </ul>
            </div>
			<div class="sh_store" style="margin-top:5px;">
				<ul>
					<li style="width:auto;min-width:50%;">
						<span style="width:30%;margin-left:7px;"><?php echo lang('member_name').":"?></span>
                        <span>
							<a style="width:180px;" target="_blank" href="<?php echo 'http://'.$user['member_url_prefix'].".".get_public_domain()?>" class="member_url member_store_name">
								<?php echo $user['store_name']?$user['store_name']:$user['member_url_prefix'].".".get_public_domain()?>
							</a>
                        </span>
					</li>
					<li style="width:auto;min-width:45%;margin-left:10px;">
						<span><a href="javaScript:modify_store_name()"><?php echo lang('modify')?></a></span>
					</li>
				</ul>
			</div>
			<?php if($user['sync_walhao']){?>
            <div class="sh_store" style="margin-top:5px;">
                <ul>
                    <li style="width:auto;min-width:50%;">
                        <span style="width:30%;margin-left:7px;"><?php echo lang('store_url').":"?></span>
                        <span>
                            <?php $modify_store_url_counts=config_item('store_url_modify_counts_limit')-$user['store_url_modify_counts']; ?>
                            <a style="width:180px;" target="_blank" href="<?php echo 'http://'.$user['store_url'].".walhao.com"?>" class="walhao_url">
                                <?php echo 'http://'.$user['store_url'].".walhao.com"?>
                            </a>
                        </span>
                    </li>
                    <li style="width:auto;min-width:45%;margin-left:10px;">
                        <span style="border-right:1px solid #ccc;padding-right:5px;"><a href="Javascript:;" id="copy_walhao_url"><?php echo lang('copy')?></a></span>
                        <span style="border-right:1px solid #ccc;margin-left:2px;padding-right:5px;"><a href="javaScript:modify_store_url()"><?php echo lang('modify')?></a></span>
                        <span style="color:#666;"><?php echo sprintf(lang('modify_the_opportunity'),$modify_store_url_counts)?></span>
                        <span class="copy_store_url_msg" style="display:none; color: #666;"><?php echo lang('copy_success')?></span>
                        <span class="copy_store_url_fail" style="display:none; color: #f41100;"><?php echo lang('the_browser_not_support')?></span>
                    </li>
                </ul>
            </div>
			<?php } ?>
            <div class="sh_store" style="margin-bottom:5px;margin-top:5px;">
                <ul>
                    <li style="width:auto;min-width:50%;">
                        <span style="width:30%;margin-left:7px;"><?php echo lang('enroll').":"?></span>
                        <span>
                        <a target="_blank" href="<?php echo 'http://'.$user['member_url_prefix'].'.'.get_public_domain().'/enroll'?>" class="enroll_url">
							<?php echo 'http://'.$user['member_url_prefix'].".".get_public_domain().'/enroll'?>
                        </a></span>
                    </li>
					<li style="width:auto;min-width:45%;margin-left:10px;">
						<span style="padding-right:5px;"><a href="Javascript:;" id="copy_enroll_url"><?php echo lang('copy')?></a></span>
					</li>
                </ul>
            </div>
        </div>
		<?php }?>
    </div>
</div>
<div class="container" style="margin-top:8px;border:1px solid #ccc;">
	<div class="title">
    	<div class="tb_tit">
        	<div class="ltxt"><span><?php echo lang('tps_bulletin')?></span></div>
            <div class="rtxt"><a href="<?php echo base_url('ucenter/bulletin_board')?>"><?php echo lang('view_more')?></a></div>
        </div>
    </div>
    <div class="tb1">
    	<div class="notice">
			<?php if($bulletins)foreach($bulletins as $item){ if(!$item['title_'.$curLanguage]) continue;?>

				<p>
					<a class="getBoard" href="Javascript:void(0);" attr_id="<?php echo $item['id']?>"><?php echo	$item['title_'.$curLanguage] ? $item['title_'.$curLanguage] : mb_substr(($item[$curLanguage]), 0, 15, 'utf-8')?></a>
					<?php if(!$item['is_read']){?>
					<img src="<?php echo base_url("../../img/$curLanguage/unread.png") ?>">
					<?php }?>
				</p>
			<?php }?>
			<?php if($is_alert === TRUE){ ?>
				<script>
					var timerc = 30; //全局时间变量（秒数）
					function add() { //加时函数
						if (timerc > 0) { //如果不到5分钟
							--timerc; //时间变量自增1
							$("#board_news .close").text(timerc).attr('data-dismiss',false); //写入秒数（两位）
							setTimeout("add()", 1000); //设置1000毫秒以后执行一次本函数
						} else {
							$("#board_news .close").text('×').attr('data-dismiss','modal').addClass('alert_count_close');
						}
					}
					$(function(){
						$.ajax({
							type: "POST",
							url: "/ucenter/welcome_new/get_board_msg",
							data:{id:79},
							dataType: "json",
							success: function (res) {
								if(res.success){
									$('#board_news .board_news_title').html(res.title);
									$('#board_news .board_news_time').html(res.time);
									$('#board_news .board_news_content').html(res.msg);
									$('#board_news').modal({backdrop: 'static', keyboard: false});
									add(); //首次调用add函数
								}else{
									layer.msg(res.msg);
								}
							}
						});
					})
				</script>
			<?php }?>
        </div>
    </div>
</div>

<!-- 修改店鋪url弹层 -->
<div id="modify_store_url_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo lang('modify_store_url')?></h3>
    </div>

    <div class="modal-body">
        <form action="" method="post" class="form-horizontal" id="modify_store_url_form">
            <table class="enable_level_tb">
                <tr>
                    <td class="title" style="width: 130px;"><?php echo lang('store_url'); ?>:</td>
                    <td class="main">
                        <input id="store_url_prefix" autocomplete="off" style="width:120px" type="text" value="<?php echo $userInfo['store_url']?>" name="store_url_prefix">.<?php echo config_item('wohao_host')?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><span id="modify_store_url_msg" class="msg error" style="margin-left:0px"></span></td>
                </tr>
            </table>
        </form>
        <span class="msg" style="margin-left: 0px;"><?php echo lang('url_show')?></span>
    </div>
    <div class="modal-footer">
        <button autocomplete="off" class="btn btn-primary" id="modify_store_url_submit"><?php echo lang('submit'); ?></button>
    </div>
</div>


<!-- 修改会员url弹层 -->
<div id="modify_member_url_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo lang('modify_member_url')?></h3>
    </div>

    <div class="modal-body">
        <form action="" method="post" class="form-horizontal" id="modify_member_url_form">
            <table class="enable_level_tb">
                <tr>
                    <td class="title" style="width: 130px;"><?php echo lang('member_url'); ?>:</td>
                    <td class="main">
                        <input id="member_url_prefix" autocomplete="off" style="width:120px" type="text" value="<?php echo $user['member_url_prefix']?>" name="member_url_prefix"><?php echo '.'.get_public_domain();?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><span id="modify_member_url_msg" class="msg error" style="margin-left:0px"></span></td>
                </tr>
            </table>
        </form>
        <span class="msg" style="margin-left: 0px;"><?php echo lang('url_show')?></span>
    </div>
    <div class="modal-footer">
        <button autocomplete="off" class="btn btn-primary" id="modify_member_url_submit"><?php echo lang('submit'); ?></button>
    </div>
</div>

	<!-- 修改店铺名称弹层 -->
	<div id="modify_store_name_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3><?php echo lang('modify').lang('member_name')?></h3>
		</div>

		<div class="modal-body">
			<form action="" method="post" class="form-horizontal" id="modify_store_name_form">
				<table class="enable_level_tb">
					<tr>
						<td class=""><?php echo lang('member_name'); ?>:</td>
						<td class="main">
							<input id="store_name" autocomplete="off" type="text" value="<?php echo $user['store_name']?$user['store_name']:$user['member_url_prefix'].".".get_public_domain()?>" name="store_name">
						</td>
					</tr>
					<tr>
						<td colspan="2"><span id="modify_store_name_msg" class="msg error" style="margin-left:0px"></span></td>
					</tr>
				</table>
			</form>
			<?php echo lang('input_store_name_tip')?>
		</div>
		<div class="modal-footer">
			<button autocomplete="off" class="btn btn-primary" id="modify_store_name_submit"><?php echo lang('submit'); ?></button>
		</div>
	</div>

<!--新聞列表-->
<div class="modal hide fade" id="board_news" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 class="board_news_title"></h3>
	</div>
	<div class="modal-body">
		<div class="board_news_time" style="text-align: right;margin-top:-20px;color:#666666"></div>
		<p class="board_news_content" style="margin-top:15px;color:#333333""></p>
	</div>
</div>

<!-- 弹层提醒用户自动转月费功能 -->
<!--<div id="notice_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">-->
<!--    <div class="modal-header">-->
<!--        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
<!--        <h3>--><?php //echo lang('new_func')?><!--</h3>-->
<!--    </div>-->
<!---->
<!--    <div class="modal-body">-->
<!--        <p>--><?php //echo lang('auto_to_month_fee_pool_notice')?><!--</p>-->
<!--    </div>-->
<!--    <div class="modal-footer">-->
<!--        <button autocomplete="off" class="btn btn-primary" id="notice_modal_submit">--><?php //echo lang('confirm'); ?><!--</button>-->
<!--    </div>-->
<!--</div>-->
<?php if($is_notice == true){?>
<script>
//    $(function(){
//        $('#notice_modal').modal({backdrop: 'static', keyboard: false});
//    })

    $('#notice_modal_submit').click(function(){
        $.ajax({
            type: "POST",
            url: "/ucenter/welcome_new/is_auto_notice",
            data: {confirm:1},
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    $('#notice_modal').modal('hide');
                }
            }
        });
    })
</script>
<?php }?>



