<?php if($user_rank<4 || $is_authentication==true){ ?>
<style>
    .info ul li{line-height:40px;list-style: none;}
    .info ul li b{font-size:16px;margin-left:10px;color:#FF0000;}
    .info ul li span{font-size:16px;font-size:large;font-weight:bold;}
</style>
<div class="block">
    <a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo lang('forced_matrix_138_info') ?></a>
    <div id="page-stats" class="info in collapse">
        <ul>
            <li><span><?php echo lang('get_commission')?></span><b><?php echo "$".$data['commission']?></b></li>
            <li>
                <span><?php echo lang('level')?></span>
                <b>
                    <?php
                        //echo $data['user_rank'];
                        if($data['user_rank']==1){
                           echo lang('diamond');
                        }
                        if($data['user_rank']==2){
                           echo lang('gold');
                        }
                        if($data['user_rank']==3){
                           echo lang('silver');

                        }
                        if($data['user_rank']==4){
                           echo lang('free');
                        }
                    ?>
                </b>
            </li>
            <li><span><?php echo lang('qualified_customers')?></span><b><?php echo $data['QSO_count']?></b></li>
            <li><span><?php echo lang('qualified_orders')?></span><b><?php echo $data['QRC_count']?></b></li>
            <li><span><?php echo lang('coordinates')?></span><b><?php echo "X=".$data['x'].","."Y=".$data['y']?></b></li>
            <li><span><?php echo lang('childs')?></span><b><?php echo sizeof($data['childs_id']) ?></b></li>
        </ul>
    </div>
</div>
<?php }?>

<!--用户未升级 -->
<?php if($user_rank==4 && $is_authentication==false){    ?>
    <style>
        .msg {width: 750px; margin: 0 auto;}
        .msg span b {color: #954343; font-size: 18px; line-height: 200px;}
        .msg span a {color:#999 ; font-size: 14px; margin-left: 15px;}
        .msg span a:hover{color: #040404;}
    </style>
    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo lang('forced_matrix_138_info') ?></a>
        <div id="page-stats" class="msg">
        <span>
            <b><?php echo lang('msg_error')?></b>
            <a href="member_upgrade"><?php echo lang('go_to_upgrade')?></a>
        </span>
        </div>
    </div>
<?php } ?>

