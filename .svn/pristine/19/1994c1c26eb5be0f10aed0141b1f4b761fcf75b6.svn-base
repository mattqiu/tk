<div class="container" style="border:0px;">

 	<div class="row-fluid">
    	<div class="tb1">
            <?php if($lists){?>
        	<div class="my_msg">
                <form class="board_msg">

                <?php foreach ($lists as $list){?>
                    <div class="one_msg" id="one_msg5">
                        <div class="left_time"><p><?php echo date('Y-m-d',strtotime($list['create_time'])) ?><br><?php echo date('H:i',strtotime($list['create_time'])) ?></p></div>
                        <div class="rig_info">
                            <div class="title">
                            <input type="checkbox" name="checkboxes[]" onclick="is_checked_msg()" value="<?php echo $list['id']?>" autocomplete="off" style="position:relative;top:-3px;left:-5px;"/>

                                <?php if($list['status']){?>
                                    <div id="speaker" style="background:url(../../../img/new/ucenter/speaker1.png) no-repeat 0 -13px;width:12px;height:13px;float:left;position:relative;left:40px;top:4px;"></div>
                                <?php }else{?>
                                    <div id="speaker" style="background:url(../../../img/new/ucenter/speaker1.png) no-repeat 0 0;width:12px;height:13px;float:left;position:relative;left:40px;top:4px;"></div>
                                <?php }?>
                                <h4><?php echo mb_substr(($list[$curLanguage]), 0, 15, 'utf-8')?></h4>
                            </div>
                            <label style="display:block;" id="info_p5"><p><?php echo $list[$curLanguage];?><!--<a href="javascript:" onclick="show_collapse('info_p5','info_p55');">查看详情>></a>--></p></label>
<!--                            <label style="display:none;" id="info_p55">-->
<!--                                <p>为避免误填推荐号码，公司强烈推荐您在注册新会员时，用您自己的二级域名会员网站（johndoe123.tps138.com), 而不让新人手动填-->
<!--                                    为避免误填推荐号码，公司强烈推荐您在注册新会员时，用您自己的二级域名会员网站（johndoe123.tps138.com), 而不让新人手动填-->
<!--                                    为避免误填推荐号码，公司强烈推荐您在注册新会员时，用您自己的二级域名会员网站（johndoe123.tps138.com), 而不让新人手动填-->
<!--                                    为避免误填推荐号码，公司强烈推荐您在注册新会员时，用您自己的二级域名会员网站（johndoe123.tps138.com), 而不让新人手动填 <a href="javascript:" onclick="show_collapse('info_p55','info_p5');"> 收起 »</a></p>-->
<!--                            </label>-->
                        </div>
                    </div>
                <?php } ?>
                </form>
                <div class="bott_msg">
				<ul>
                	<li style="padding:10px 0 0 15px;height:30px;"><label><input type="checkbox" name="msg_allcheck" autocomplete="off" style="position:relative;top:-4px;" onclick="check_all_msg(this);"/> <?php echo lang('all')?></php></label></li>
                    <li style="text-align:center;"><input type="button" value="<?php echo lang('read')?>" class="btn1_cs" disabled="1" /></li>
                    <li><input type="button" value="<?php echo lang('delete')?>" class="btn2_cs" disabled="1" /></li>
                    <?php echo $pager;?>
                </ul>
            </div>
                <?php }else{?>
                <div class="">沒有消息</div>
            <?php }?>
        </div>
        </div>
    </div>

</div>