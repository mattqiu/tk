<style>
    .block-body div {
        margin-top: 30px;
    }
</style>

<div class="block">

    <div class="block-heading">
            <span class="block-icon pull-right">
                <a rel="tooltip" href="<?php echo base_url('ucenter/rewards_introduced#r7')?>" data-original-title="<?php echo lang('learn_more_rule'); ?>"><i class="icon-hand-right"></i></a>
            </span>
        <a data-toggle="collapse" class=""><?php echo lang('infinity_title'); ?></a>
    </div>
    <div class="block-body">
        <div class="row-fluid countdown_div">
            <?php echo lang('infinity_countdown');?>:<span class="countdown" id='remainSeconds' leftSeconds='<?php echo $leftSeconds?>'></span>
        </div>

        <div class="row-fluid condition">
            <?php echo lang('infinity_enable')?><span class="<?php if($con1&&$con2&&$con3){ ?>text-success<?php }else{ ?>error<?php }?>"><?php if($con1&&$con2&&$con3){ ?> √ <?php echo lang('yes')?> <?php }else{ ?> × <?php echo lang('no') ?> <?php } ?></span>

            <span class="error_msg" style="display: block; padding-top: 20px;">
                <ul>
                    <li <?php if($con1){ ?>class="text-success"<?php } ?> > <?php if($con1){ ?>√<?php }else{ ?> ×<?php } ?> <?php echo lang('infinity_con1');?> </li>
                    <li <?php if($con2){ ?>class="text-success"<?php } ?> > <?php if($con2){ ?>√<?php }else{ ?> ×<?php } ?> <?php echo lang('infinity_con2');?>  </li>
                    <?php if(!use_temporary_rule()){?>
                    <li <?php if($con3){ ?>class="text-success"<?php } ?> > <?php if($con3){ ?>√<?php }else{ ?> ×<?php } ?> <?php echo lang('infinity_con3');?>  </li>
                    <?php }?>
                </ul>

            </span>
        </div>

        <div class="row-fluid">
            <p><span class="title"><?php echo lang('infinity_date_title');?>：</span><?php echo lang('infinity_date_content');?></p>
            <p><span class="title"><?php echo lang('infinity_qualifications_title');?>：</span>
                <ol>
                    <li><?php echo lang('infinity_con1');?></li>
                    <li><?php echo lang('infinity_con2');?></li>
                    <?php if(!use_temporary_rule()){?>
                    <li><?php echo lang('infinity_con3');?></li>
                    <?php }?>
                </ol>
            <p><span class="title"><?php echo lang('infinity_formula_title');?>：</span><?php echo lang('infinity_formula_content');?></p>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<script>
    var timerc=$("#remainSeconds").attr("leftSeconds");
    function countdown(){
        if(timerc > 0){
            --timerc;
            var days= addZeroforNumLessTen(parseInt(timerc/(3600*24)));
            var daysRemainSeconds = timerc%(3600*24);
            var hours= addZeroforNumLessTen(parseInt(daysRemainSeconds/3600));
            var hoursRemainSeconds = daysRemainSeconds%3600;
            var mins = addZeroforNumLessTen(parseInt(hoursRemainSeconds/60));
            var seconds = addZeroforNumLessTen(hoursRemainSeconds%60);
            $('#remainSeconds').text(days+' <?php echo lang('days')?> '+ hours+' <?php echo lang('hours')?> '+mins+' <?php echo lang('minutes')?> '+seconds+' <?php echo lang('seconds')?>');
            setTimeout("countdown()", 1000);
        };
    };
    countdown();
</script>