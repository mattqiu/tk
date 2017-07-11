
<!--用户已升级并且已经激活 -->
<?php $pay_rank = array(1,2,3,5)?>
<?php if((in_array($month_fee_rank,$pay_rank) || in_array($store_level,$pay_rank)) && ($user_status==1 ||$user_status==2)){?>
    <?php echo $tree?>
    <div style="padding:10px;">
        <span id="level_span">
            <div style="background-color:purple"><?php echo lang('diamond');?></div>
            <div style="background-color:gold"><?php echo lang('gold');?></div>
            <div style="background-color:green"><?php echo lang('silver');?></div>
            <div  style="background-color:pink"><?php echo lang('bronze');?></div>
            <div style="background-color:white"><?php echo lang('free');?></div>
            <div style="background-color:gray"><?php echo lang('freeze');?></div>
            <div style="background-color:#c1e2b3"><?php echo lang('company_account');?></div>
        </span>
    </div>

    <form method="post" id="tree_org">
        <input type="hidden" name="uid" value="" class="input-xlarge">
    </form>

    <div id="chart" class="orgChart" style="min-width:960px;margin-left:10px;float:left;"></div>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/orgchart/css/jquery.jOrgChart.css?v=1'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/orgchart/css/custom.css?v=1'); ?>">
    <script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.jOrgChart.js?v=1'); ?>"></script>

    <script>
        jQuery(document).ready(function() {
            $("#org").jOrgChart({
                chartElement : '#chart',
                dragAndDrop  : true
            });
            $("#chart .node span").each(function(){
                var level =  parseInt($(this).attr('level'));
                var status =  parseInt($(this).attr('status'));
                var color,border_color;
                switch(level){
                    case 1:color= 'purple';border_color= 'purple';break;
                    case 2:color= 'gold';border_color= 'gold';break;
                    case 3:color= 'green';border_color= 'green';break;
                    case 4:color= 'white';border_color= 'white';break;
                    case 5:color= 'pink';border_color= 'pink';break;

                }
                if(parseInt(status)!=1){
                    color= 'gray';
                }
                if(status == 4){
                    color= '#c1e2b3';
                }
                $(this).parent().css('background-color',color)
                $(this).parent().css('border','2px solid ' + border_color)
            });

            $(".node").click(function(){
                var userID=$(this).children('span').attr('uid');
                $('#tree_org input').val(userID);
                $('#tree_org').submit();
            })

        });
    </script>

<!--用户未升级或者未激活-->
<?php }else{?>
    <style>
        .msg {width: 850px; margin: 0 auto;}
        .msg span b {color: #954343; font-size: 1.25em; line-height: 200px;}
        .msg span a {color:#999 ; font-size: 1em; margin-left: 15px;}
        .msg span a:hover{color: #040404;}
    </style>
    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo lang('forced_matrix_2x5_rules') ?></a>
        <div id="page-stats" class="msg">
        <span>
            <b><?php echo lang('you_not_have_month_fee_rank')?></b>
            <a href="member_upgrade"><?php echo lang('go_to_upgrade')?></a>
        </span>
        </div>
    </div>
<?php }?>

