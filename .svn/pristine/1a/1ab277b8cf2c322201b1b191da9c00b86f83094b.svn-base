
<form method="post" id="tree_org">
    <input type="hidden" name="uid" value="" class="input-xlarge">
</form>

<!--已升级且非冻结状态的用户-->
<?php if (in_array($month_fee_rank,config_item('pay_rank')) || in_array($store_level,config_item('pay_rank')) || $is_authentication==true){?>
    <?php if(isset($is_sorting) && $is_sorting==1){?>
        <style>
            .msg {width: 750px; margin: 0 auto;}
            .msg span b {color: #954343; font-size: 18px; line-height: 200px;}
            .msg span a {color:#999 ; font-size: 14px; margin-left: 15px;}
            .msg span a:hover{color: #040404;}
        </style>
        <div class="block">
            <a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo lang('forced_matrix_138_rules') ?></a>
            <div id="page-stats" class="msg">
                <span>
                    <b><?php echo lang('is_sorting')?></b>
                </span>
            </div>
        </div>
    <?php }else{?>

        <style type="text/css">
            #level_span div {float: left; font-weight: bold; padding: 5px; text-align: center; width: 150px; color: #f38630;}
            #tab{margin-top: 50px; margin-left: 170px; margin-bottom: 50px;}
            #tab tr td{text-align:center;height: 80px; width: 315px; font-size:15px; color: #000;font-weight:bold; border: 1px solid #c0c0c0; cursor:pointer;}
            .main{background: #C0C0C0; border: solid 1px #C0C0C0;}
        </style>

        <div style=" padding: 10px;">
            <span id="level_span">
            <div style="background-color:purple;"><?php echo lang('diamond');?></div>
            <div style="background-color:gold;"><?php echo lang('gold');?></div>
            <div style="background-color:green;"><?php echo lang('silver');?></div>
            <div style="background-color:pink"><?php echo lang('bronze');?></div>
            <div style="background-color:white;"><?php echo lang('free');?></div>
            </span>
        </div>
        <?php echo($tree)?>
        <script type="application/javascript">
            $("#tab tr td span").each(function(){
                var level =  parseInt($(this).attr('level'));
                var color,border_color;
                switch(level){
                    case 1:color='purple';border_color='purple';break;
                    case 2:color='gold';border_color='gold';break;
                    case 3:color='green';border_color='green';break;
                    case 4:color='white';border_color='white';break;
                    case 5:color='pink';border_color='pink';break;
                }
                $(this).parent().css('background-color',color)
            });
            $("#tab tr td").click(function(){
                var userID=$(this).children('span').attr('uid');
                $('#tree_org input').val(userID);
                $('#tree_org').submit();
            })
        </script>
        <?php }?>

<!--未升级的用户-->
<?php }else{?>

    <style>
        .msg {width: 750px; margin: 0 auto;}
        .msg span b {color: #954343; font-size: 18px; line-height: 200px;}
        .msg span a {color:#999 ; font-size: 14px; margin-left: 15px;}
        .msg span a:hover{color: #040404;}
    </style>
    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo lang('forced_matrix_138_rules') ?></a>
        <div id="page-stats" class="msg">
        <span>
            <b><?php echo lang('you_not_have_authentication')?></b>
            <a href="account_info"><?php echo lang('go_to_authentication')?></a>
        </span>
        </div>
    </div>
<?php }?>

