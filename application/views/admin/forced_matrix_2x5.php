
<b class="please_input_id"><?php echo lang('please_input_id'); ?></b><br>
<form method="post" action="forced_matrix_2x5">
    <input class="proportion_input" type="text"  name="submit_uid" id="cashForMonthFee" value="" ><br>
    <input type="submit" id="submit_id" class="btn btn-primary" value=<?php echo lang('find') ?> >
</form>

<!--用户已升级并且已经激活 -->

<?php if($status==1){ ?>
    <div class="main">
        <?php echo $tree?>
        <div style=" padding: 10px;">
        <span id="level_span">
        <div style="background-color:purple"><?php echo lang('diamond');?></div>
        <div style="background-color:gold"><?php echo lang('gold');?></div>
        <div  style="background-color:green"><?php echo lang('silver');?></div>
        <div  style="background-color:pink"><?php echo lang('bronze');?></div>
        <div  style="background-color:white"><?php echo lang('free');?></div>
        <div  style="background-color:gray"><?php echo lang('freeze');?></div>
        <div  style="background-color:#c1e2b3"><?php echo lang('company_account');?></div>
        </span>
        </div>

        <!--显示此ID的下面三层-->
        <form method="post" id="tree_org">
            <input type="hidden" name="uid" value="" class="input-xlarge">
        </form>
        <!--向上回溯三层-->
        <form method="post" id="back">
            <input type="hidden" name="back_uid" value="" class="input-xlarge">
        </form>

        <div id="chart" class="orgChart"></div>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/orgchart/css/jquery.jOrgChart.css?v=1'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/orgchart/css/custom.css?v=1'); ?>">
        <script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.jOrgChart_2x5.js?v=1'); ?>"></script>

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

                /***单击事件***/
                var TimeFn = null;
                $(".node").click(function() {
                    var $this = $(this);
                    clearTimeout(TimeFn);
                    TimeFn = setTimeout(function(){
                        var uid = $this.children('span').attr('uid');
                        $('#tree_org input').val(uid);
                        $('#tree_org').submit();
                    },300);
                });

                /***双击事件***/
                $('.node').dblclick(function() {
                    // 取消上次延时未执行的方法
                    clearTimeout(TimeFn);
                    var $this=$(this);
                    var back_uid = $this.children('span').attr('uid');
                    $('#back input').val(back_uid);
                    $('#back').submit();
                })
            });
        </script>

        <!--用户未升级或者未激活-->

        <style>
            #level_span div {float: left; font-weight: bold; padding: 5px; text-align: center; width: 150px; color: #f38630;}
            .main{background: #C0C0C0;}
            .msg_2x5_alert{margin: 0 auto;
                background: -webkit-gradient(linear, left bottom, left top, color-stop(0, #afd9ee), color-stop(1, #d9edf7));
                border:none;
                border: 1px solid #85c5e5;
            }
            .msg {width: 750px; margin: 0 auto;}
            .msg span b {color: #954343; font-size: 18px; line-height: 200px;}
            .msg span a {color:#999 ; font-size: 14px; margin-left: 15px;}
            .msg span a:hover{color: #040404;}
            .please_input_id{color:#954343;font-size: 14px;
            .btn{ position: absolute; top: -20px;}
            }
            .msg_2x5_alert h5{
                text-align: center; color:#942a25;
            }
        </style>
    </div>

<?php }else{?>
        <div class="msg_2x5_alert">
            <h5><?php echo lang('user_not_exist') ?></h5>
        </div>
<?php }?>
