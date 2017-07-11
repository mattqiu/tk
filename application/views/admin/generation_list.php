<div class="main">

    <div style=" padding: 10px;">
        <span id="level_span">
        <div style="background-color:purple"><?php echo lang('level_1');?></div>
        <div style="background-color:gold"><?php echo lang('level_2');?></div>
        <div  style="background-color:green"><?php echo lang('level_3');?></div>
        <div  style="background-color:pink"><?php echo lang('level_5');?></div>
        <div  style="background-color:white"><?php echo lang('level_4');?></div>
        <div  style="background-color:gray"><?php echo lang('freeze');?></div>
        <div  style="background-color:#c1e2b3"><?php echo lang('company_account');?></div>
        </span>

        <div class="pull-right" class="span12">
            <div>
                <div class="alert alert-error hidden pull-left">
                    <strong></strong>
                </div>
                <input id="custom_uid" type="text" placeholder="<?php echo lang('enter');?>">
            </div>
        </div>
    </div>
    <?php echo $tree_str?>

    <form method="post" id="tree_org">
        <input type="hidden" type="text" name="uid" value="" class="input-xlarge" autocomplete="on">
        <input id="type" type="hidden" type="text" name="type" value="child" class="input-xlarge" autocomplete="on">
    </form>

    <div id="chart" class="orgChart"></div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/orgchart/css/jquery.jOrgChart.css?v=1'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/orgchart/css/custom.css?v=1'); ?>">
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.jOrgChart.js?v=1'); ?>"></script>

<script>
    $("#custom_uid").keydown(function(e) {
        var a = e||window.event
        if (a.keyCode == '13') {//keyCode=13是回车键
            $uid = $(this).val()
            if($uid){
            $.post("/admin/generation_list/checkUid", {uid : $uid},
                function(data){
                    if(data['success']){
                        $('#tree_org input').val($uid);
                        $("#tree_org").submit();
                    }else{
                        $('.alert-error strong').html(data.msg);
                        $('.alert-error').removeClass('hidden');
                    }
                },'json');
            }
        }
    });
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
                case 5:color= 'pink';border_color= 'pink';break;
                case 4:color= 'white';border_color= 'white';break;
            }
            if(status != 1){
                color= 'gray';
            }
            if(status == 4){
                color= '#c1e2b3';
            }
           $(this).parent().css('background-color',color)
           $(this).parent().css('border','2px solid ' + border_color)
        });
    });
</script>
<style>
    #level_span div{
        width: 80px;
    }
</style>