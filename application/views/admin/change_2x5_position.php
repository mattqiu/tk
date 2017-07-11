
<!-- User ID 挂靠到 Parent ID 树下-->
<label><?php echo lang("matrix_alert1")?></label>
<form method="post" action="change_2x5_position">
    <input class="proportion_input" type="text"  name="user_id" id="cashForMonthFee" value=""  placeholder="User ID">
    <input class="proportion_input" type="text"  name="pay_parent_id" id="cashForMonthFee" value="" placeholder="Parent ID" >
    <input type="submit" id="submit_id1" class="btn btn-primary" value=<?php echo ('提交') ?> >
</form>

<!--UserID 1 与 UserID 2 交换位置 -->
<label><?php echo lang("matrix_alert2")?></label>
<form method="post" action="change_2x5_position">
    <input class="proportion_input" type="text"  name="userId1" id="cashForMonthFee" value="" placeholder="UserID 1">
    <input class="proportion_input" type="text"  name="userId2" id="cashForMonthFee" value="" placeholder="UserID 2">
    <input type="submit" id="submit_id2" class="btn btn-primary" value=<?php echo ('提交') ?> >
</form>

<?php echo $tree?>
<div style=" padding: 10px;">
    <span id="level_span">
    <div style="background-color:purple"><?php echo lang('diamond');?></div>
    <div style="background-color:gold"><?php echo lang('gold');?></div>
    <div  style="background-color:green"><?php echo lang('silver');?></div>
    <div  style="background-color:white"><?php echo lang('free');?></div>
    <div  style="background-color:gray"><?php echo lang('freeze');?></div>
    </span>
</div>

<form method="post" id="tree_org">
    <input type="hidden" name="uid" value="" class="input-xlarge">
</form>

<div id="chart" class="orgChart"></div>

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
            var color,border_color;
            switch(level){
                case 1:color= 'purple';border_color= 'purple';break;
                case 2:color= 'gold';border_color= 'gold';break;
                case 3:color= 'green';border_color= 'green';break;
                case 4:color= 'white';border_color= 'white';break;
                case -1:color= 'gray';border_color= 'gray';break;
            }
            $(this).parent().css('background-color',color)
        });

        $(".node").click(function(){
            var userID=$(this).children('span').attr('uid');
            $('#tree_org input').val(userID);
            $('#tree_org').submit();
        })
    });
</script>