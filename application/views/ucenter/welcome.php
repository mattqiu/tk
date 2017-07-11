<style>
    .alert.alert-error{
        background: rgba(0, 0, 0, 0) -moz-linear-gradient(center bottom , #e35f40 0%, #e35f40 100%) repeat scroll 0 0;
        border:0;
    }
</style>

<?php if($user['month_fee_rank'] == 4){?>
<div class="alert alert-error alert-block">
    <h4><?php echo lang('alert');?>!</h4>
    <span><?php echo lang('welcome_notice1');?></span>
</div>
<?php }?>
<?php if($user['month_fee_rank'] != 4 && $user['user_rank'] == 4){?>
    <div class="alert alert-error alert-block">
        <h4><?php echo lang('alert');?>!</h4>
        <span><?php echo lang('welcome_notice2');?></span>
    </div>
<?php }?>


    <h4><?php echo lang('welcome_msg').',<span style="color:green;margin:0px 10px;">'.($userInfo['name']?$userInfo['name']:$userInfo['email']).'</span>!';?></h4>
<div class="row-fluid">
    <table class="tb">
        <tr>
            <td><span class="title"><?php echo lang('id'); ?>：</span><strong class="text-info"><?php echo $userInfo['id']; ?></strong></td>
            <td><span class="title"><?php echo lang('regi_parent_id'); ?>：</span><strong class="text-info"><?php echo $userInfo['parent_id']; ?></strong></td>
            <?php if($userInfo['month_fee_date']){?>
            <td><span class="title"><?php echo lang('month_fee_date'); ?>：</span><strong class="text-info"><?php echo $userInfo['month_fee_date'].lang('day_th'); ?></strong></td>
            <?php }?>
            <td><span class="title"><?php echo lang('cur_monthly_fee_level'); ?></span><strong class="text-info"><?php echo lang(config_item('monthly_fee_ranks')[$userInfo['month_fee_rank']]); ?></strong></td>
            <td><span class="title"><?php echo lang('current_level'); ?>：</span><strong class="text-info"><?php echo lang(config_item('user_ranks')[$userInfo['user_rank']]); ?></strong></td>
        </tr>
    </table>
    <div class="clearfix"></div>
</div>

<div class="block">
    <p class="block-heading"><?php echo lang('coupon') ?></p>
    <div class="block-body<?php echo !$monthlyFeeCoupon?' hide':'';?>" id='monthly_fee_coupon_block'>
        <?php echo lang('monthly_fee_coupon_notice');?>
        <p>
            <?php if($monthlyFeeCoupon){ ?>
            <a href='javascript:useMonthlyFeeCoupon();'>
                <img src='<?php echo base_url('img/'.$curLanguage.'/'.$monthlyFeeCoupon['img_name'].'.png');?>' />
            </a>
            <?php }?>
        </p>
    </div>
    <div class="block-body<?php echo $monthlyFeeCoupon?' hide':'';?>" id='no_monthly_fee_coupon_block'><?php echo $noMonthlyFeeCouponMsg?></div>
    <div id='monthlyFeeCouponMsg' class='msg success'></div>
</div>

<div class="block ">
    <p class="block-heading"><?php echo lang('up_level') ?></p>
    <div class="block-body">
        <?php echo lang('review_account_info').'<span class="welcome_notice_a"><a href="'.  base_url('ucenter/member_upgrade').'">['.lang('member_upgrade').']</a></span>'.lang('up_level_notice_2');?>
    </div>
</div>

<div class="block">
	<p class="block-heading" style="color:#e16e22;font-size: 16px;"><?php echo lang('Bulletin_title')?></p>
	<div class="block-body">

        <?php if ($boards){ ?>
            <?php foreach ($boards as $key=>$board) { ?>
                <div class="container bulletin" style="margin-top: 20px;">
                    <div class="row-fluid bulletin-row">
                        <div class="col-md-8 bulletin-left">
                            <span ><?php echo 1+$key.'.'; if($board['important']){ echo lang('important');} echo $board[$curLanguage];?></span>
                        </div>
                        <div class="col-md-4 bulletin-right" <?php if(($curLanguage == 'zh')||($curLanguage == 'hk')){echo(" style='margin-top: 15px;'>");}else{echo(" style='margin-top: 25px;'>");}?>>
                        <span><i class="icon-time"></i><?php echo $board['create_time']?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div style="float: right;"><?php echo $pager;?></div>
            <script>
                $(function(){
                    $('.total_num').css('display','none')
                });
            </script>
            <div class="clearfix"></div>
        <?php }?>
    </div>
</div>


<div class="block ">
    <p class="block-heading"><?php echo lang('view_complete_your_info') ?></p>
    <div class="block-body">
        <?php echo lang('review_account_info').'<span class="welcome_notice_a"><a href="'.  base_url('ucenter/account_info').'">['.lang('my_account').'->'.lang('account_info').']</a></span>'.lang('review_account_info_2');?>
    </div>
</div>
<div id="freeze_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?php echo lang('freeze_tip_title')?></h3>
    </div>
    <div class="modal-body">
        <div>
            <?php echo $alert_tip?>
        </div>
    </div>

</div>
<?php if($user['status'] == 2){?>
    <script>
        $(function(){
            $('#freeze_modal').modal();
        });
    </script>
    <style>
        .modal p{
            margin: 10px;
        }
    </style>
<?php }?>
