<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lib/bootstrap/css/bootstrap.css?v=1')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/stylesheets/theme.css?v=10')?>">
<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
<style>
    span{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    table{
        font-size: 14px;
    }
    .mdifiable{
        background-color: #d0cbcb;
        padding: 2px;
        min-height: 20px;
    }

</style>
<div class="well" style="height:500px;width: 93%;">
    <input type="hidden" name="categroy_id" value="<?php echo $category_id;?>">
    <input type="hidden" name="bonus_shar_weight" value="<?php echo $amount['bonus_shar_weight'];?>">
    <input type="hidden" name="totalWeight" value="<?php echo $amount['totalWeight'];?>">
    <input type="hidden" name="yesterdayProfit" value="<?php echo $amount['yesterdayProfit'];?>">
    <div style="width:100%;height:30px;margin-top: 10px">
        <span style="float:left;font-size: 15px;margin-left: 5px;">用户ID：<?php echo $logs[0]['uid'];?></span>

    </div>
    <table class="table" style="margin-top: 30px;">
        <thead>
        <tr>
            <th>日期</th>
            <th>金额</th>
            <th>模拟状态</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="30%"><?php echo date("Y-m-d"); ?></td>
            <td>$<span class="mdifiable amount"><?php echo $amount['amount'];?></span>(模拟)</td>
            <td><?php if($isusd>0){?>已发<?php }else{ ?>未发<?php } ?></td>
        </tr>
        <?php foreach($logs as $v) {?>
            <tr>
                <td width="30%"><?php echo date("Y-m-d",strtotime($v['create_time'])); ?></td>
                <td>$&nbsp;<?php echo $v['amount']/100; ?></td>
                <td>已发</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="<?php echo base_url('themes/admin/lib/jquery-1.8.1.min.js')?>" type="text/javascript"></script>
<script>
    $(".amount").dblclick(function () {
        var curVal = $(this).text()?$(this).text():$(this).children().first().val();
        if(!curVal){
            curVal = '';
        }
        var mem_id = $(this).parent().prev().prev().text();
        $(this).html('<input type="text" attr-uid="'+mem_id+'" attr-value="'+curVal+'" class="amount_input" value="'+curVal+'" name="moni" style="width: 55px;height: 25px;padding: 2px 5px;">');
        $(this).children().first().focus();
    });


    $(".amount_input").live('blur',function(){
        var curEle = $(this);
        var source_value = curEle.attr('attr-value');
        var amount=$("input[name='moni']").val()*100;
        var id=$("input[name='categroy_id']").val();
        var bonus_shar_weight=$("input[name='bonus_shar_weight']").val();
        var totalWeight=$("input[name='totalWeight']").val();
        var yesterdayProfit=$("input[name='yesterdayProfit']").val();
        var ratio = (amount*totalWeight)/bonus_shar_weight/yesterdayProfit;
        if(ratio.toFixed(4)<=0){
            ratio = 0.0001;
        }
        if(ratio>=1){
            parent.layer.msg("设置的百分比过大，请修改！");
            return;
        }

        parent.layer.confirm("你确定要修改吗？", {
                icon: 3,
                title: "数据修改",
                closeBtn: 2,
                btn: ["确定", "取消"]
            }, function(index){
                parent.layer.close(index);
                var url = "/admin/bonus_plan/calculate_bonus_save";
                $.post(url,{'uid':id,"ratio":ratio},function(json){
                    var json = eval('('+json+')');
                    if(json.success){
                        parent.window.location.reload();
                    }else{
                        parent.layer.msg("已超过修改时间！");
                    }
                })
            }
            ,function()
            {
                curEle.parent().text(source_value);
            }
        );
    });
</script>