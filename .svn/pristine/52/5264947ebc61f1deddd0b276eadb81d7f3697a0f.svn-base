<div class="alert alert-info">
    <?php echo lang('reward_tip');?>
    <?php echo lang('reward_tip2');?>
    <?php if(use_temporary_rule()){ ?>
    <span class="label label-important"><?php echo lang('notice');?>!</span>
    &nbsp;<strong style="color: #b94a48;"><?php echo lang('new_rule');?></strong>
    <?php }?>
</div>

<div class="well">

<div class="tabbable tabs-left">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#r1"><?php echo lang('plan_1');?></a></li>
        <li><a data-toggle="tab" href="#r2"><?php echo lang('plan_2');?></a></li>
        <li><a data-toggle="tab" href="#r12"><?php echo lang('plan_12');?></a></li>
        <li><a data-toggle="tab" href="#r3"><?php echo lang('plan_3');?></a></li>
        <li><a data-toggle="tab" href="#r11"><?php echo lang('plan_11');?></a></li>
        <li><a data-toggle="tab" href="#r4"><?php echo lang('plan_4');?></a></li>
        <li><a data-toggle="tab" href="#r6"><?php echo lang('plan_5');?></a></li>
        <li><a data-toggle="tab" href="#r9"><?php echo lang('plan_9');?></a></li>
        <li><a data-toggle="tab" href="#r_week_share"><?php echo lang('plan_week_share');?></a></li>
        <li><a data-toggle="tab" href="#r5"><?php echo lang('plan_6');?></a></li>
        <li><a data-toggle="tab" href="#r10"><?php echo lang('plan_10');?></a></li>
        <!-- <li><a data-toggle="tab" href="#r8"><?php echo lang('plan_7');?></a></li> -->
        <li><a data-toggle="tab" href="#r7"><?php echo lang('plan_8');?></a></li>
    </ul>
    <div class="tab-content">
            <div id="r1" class="tab-pane active">
                <p><?php echo lang('r1_content');?></p>
                <p><?php echo lang('r1_content_notice');?></p>
            </div>
            <div id="r2" class="tab-pane">
                <ol style="list-style:none;">
                <li>
                    <p>
                        <?php echo lang('r2_content1');?>
                        <?php echo lang('r2_content1_1');?>
                    </p>
                </li>
					<li>
                    <p>
                        <?php echo lang('r2_content_5');?>
                        <?php echo lang('r2_content_5_1');?>
                    </p>
                </li>
                <li>
                    <p>
                        <?php echo lang('r2_content_1');?>
                        <?php echo lang('r2_content_1_1');?>
                    </p>
                </li>
                <li>
                    <p>
                    <?php echo lang('r2_content_2');?>
                    <?php echo lang('r2_content_2_1');?>
                    </p>
                </li>
                <li>
                    <p>
                    <?php echo lang('r2_content_3');?>
                    <?php echo lang('r2_content_3_1');?>
                    </p>
                </li>
                </ol>
            </div>
            <div id="r4" class="tab-pane">
                <p> <?php echo lang('r3_content_1');?></p>
                <p> <?php echo lang('r3_content_2');?></p>
                <p> <strong><?php echo lang('r3_content_4');?></strong></p>
                <p><?php echo lang('r3_content_5');?></p>
                <p> <?php echo lang('r3_content_3');?></p>
            </div>

            <div id="r11" class="tab-pane">
                <ol style="list-style:none;">
                <li>
                    <p>
                        <?php echo lang('r11_content');?>
                    </p>
                </li>
                </ol>
            </div>
         <div id="r12" class="tab-pane">
                <p> <?php echo lang('r12_content_1');?></p>
          </div>

        <div id="r3" class="tab-pane">
           <p> <?php echo lang('r4_content_1');?></p>
           <p> <?php echo lang('r4_content_2');?></p>
           <p> <?php echo lang('r4_content_3');?></p>
           <p> <?php echo lang('r4_content_4');?></p>
        </div>
            <div id="r5" class="tab-pane">
                <p> <?php echo lang('r5_content_1');?></p>
                <p> <?php echo lang('r5_content_2');?></p>
                <p> <?php echo lang('r5_content_3');?></p>
                <p> <?php echo lang('r5_content_4');?></p>
            </div>
            <div id="r6" class="tab-pane">
                <p> <?php echo lang('r6_content_1');?></p>
                <p> <?php echo lang('r6_content_2');?></p>
                <p> <?php echo lang('r6_content_3');?></p>
            </div>
            <div id="r7" class="tab-pane">
                <p> <?php echo lang('r7_content_1');?></p>
                <!-- <ul><li><?php echo lang('infinity_con1');?></li><li><?php echo lang('infinity_con2');?></li>
                    <li><?php echo lang('infinity_con3');?></li>
                </ul> -->
                <p> <?php echo lang('r7_content_2');?></p>
            </div>
            <div id="r10" class="tab-pane">
                <p> <?php echo lang('r10_content_1');?></p>
                <p> <?php echo lang('r10_content_2');?></p>
            </div>
            <div id="r_week_share" class="tab-pane">
                <p> <?php echo lang('r_week_share_content');?></p>
            </div>
        <div id="r9" class="tab-pane reward_138 " >
                <p> <?php echo lang('r9_content_1');?></p>
                <p> <?php echo lang('r9_content_3');?></p>
                <p> <?php echo lang('r9_content_2');?></p>
                <p> <?php echo lang('r9_content_4');?></p>
                <p> <?php echo lang('r9_content_5');?></p>
            </div>
    </div>
    </div>
</div>
<style>
    .tab-pane ul li{
        font-weight: bold;
    }
</style>
<script>
    $(function(){
        var index = location.href.indexOf('#');
        var lc = location.href.substr(index+1,2);
        if(index != -1){
            $('.active').each(function(){
                $(this).removeClass('active');
            });
            $('.nav a[href="#'+lc+'"]').parent().addClass('active');
            $('#'+lc).addClass('active');
        }
        $('#r2 ul').css({"list-style":"none","margin-left":"0"});
    });
</script>