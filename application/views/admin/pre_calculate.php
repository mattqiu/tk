<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lib/bootstrap/css/bootstrap.css?v=1')?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/stylesheets/theme.css?v=10')?>">
<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
<style>
    #inputs{
        height: 30px;
        border-radius: 4px;
        padding: 4px 5px;
    }
    .font-style{
        font-size:14px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        line-height: 20px;
    }
    .message{
        margin-bottom: 10px;
    }
</style>
<div class="well text-danger" style="height:500px;width:300px;float:left;">
    <input type="hidden" name="categroyId" value="<?php echo $result['data'][0]['category_id'];?>">
    <div class="message">今天分红为：$&nbsp;<span id="bonus_num">0</span></div>
    <table>
        <tr>
            <td>
                <span class="font-style">用户ID</span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <input class="inputs" type="text" id="inputs" name="uid" value="<?php echo $logs[0]['uid']; ?>">
            </td>
        </tr>
        <tbody id="tbody">
            <?php if($result['ischild'] == 1){?>
                <tr>
                    <td>
                        <span class="font-style"><?php echo $v['name'];?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="inputs" type="text" id="inputs" value="">
                    </td>
                </tr>
                <?php foreach($result['data'] as $k => $v) {?>
                    <tr>
                        <td>
                            <span class="font-style"><?php echo $v['name'];?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="inputs" type="text" id="inputs" value="">
                        </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <?php foreach($result['rule'] as $k => $v) {?>
                    <tr>
                        <td>
                            <span class="font-style"><?php echo lang($result['param_name'][$v]);?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="inputs" type="text" id="inputs" name="input_rate" value="<?php echo $result['data'][0][$v]*100; ?>">
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td>
                    <button class="btn btn-primary"  type="button" name="submit" onclick="calculateNum()">计算</button>
                    <button class="btn btn-success"  type="button" name="submit" onclick="submit()">保存比例</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="well text-danger" style="height:500px;width:210px;float:left;">
    <table>
        <tbody>
        <tr>
            <td>
                <span class="font-style" style="color: darkgreen">分红记录</span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <?php foreach($logs as $v) {?>
            <tr height="30">
                <td>
                    <span class="font-style"><?php echo date("Y-m-d",strtotime($v['create_time'])); ?></span><span class="font-style" style="margin-left: 20px;">$&nbsp;<?php echo $v['amount']/100; ?></span>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script src="<?php echo base_url('themes/admin/lib/jquery-1.8.1.min.js')?>" type="text/javascript"></script>
<script>
    function calculateNum(){
        var id=$("input[name='uid']").val();
        var rate_a = $("input[name='input_rate']").val();
        var url = "/admin/bonus_plan/calculate_bonus";
        $.post(url,{'uid':id,"rate_a":rate_a},function(json){
            var json = eval('('+json+')');
            if(!json.success){
                parent.layer.alert(json.data.message);
            }else{
                $("#bonus_num").text(json.data.amount);
            }
        })
    }

    function submit(){
        var id=$("input[name='categroyId']").val();
        var input_rate = new Array();
        $("#tbody").find("input").each(function(i){
            input_rate.push($(this).val()/100);
        })
        var url = "/admin/bonus_plan/calculate_bonus_save";
        $.post(url,{'uid':id,"input_rate":input_rate},function(json){
            parent.window.location.reload();
        })
    }

</script>