<style>
    .mytable {
        width: 100%;
        margin-bottom: 20px;
    }
    .mytable{
        max-width: 100%;
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
    }
    .mytable tr td {
        line-height: 20px;
    }
    .mytable th, .mytable td {
        padding: 10px 0;
        line-height: 22px;
        text-align: center;
    }
    .well{
        padding:5px;
    }
    .block-heading{
        line-height: 2em;
    }
    .cron_msg{
        color:darkgreen;
    }
    .btn{
        padding:2px 6px;
        margin-bottom: 5px;
    }
</style>

<?php if ($list){?>
    <?php foreach ($bonus_type as $i=>$items) { ?>
        <div class="block ">
            <p class="block-heading"><?php echo $items; ?></p>
            <div class="block-body">
                <?php foreach ($list[$i] as $j=>$item) { ?>
                    <div class="well" style="margin-right:20px;position: relative;">
                        <table class="mytable">
                            <tr><span align="left" style="width:180px;float: left;float: left;margin: 10px 0 10px 40px" ><h4><?php echo lang($item['title']); ?></h4></span><span style="float: left;margin: 22px 10px 0 0" class="cron_msg" id="cron_msg_<?php echo $j;?>"></span></td></tr>
                            <tbody id="well_<?php echo $j;?>">
                            <tr>
                                <!--<td align="left" style="width:180px;float: left"><h4><?php /*echo lang($item['title']); */?></h4></td>-->
                                <?php if(!empty($item['param'])) {?>
                                    <?php if($item['ischild']>0) {?>
                                        <?php if($item['category_id']==23) {?>
                                            <?php foreach ($item['data'] as $a=>$b) { ?>
                                                <tr>
                                                    <td align="left" width="15%" style="float: left"><?php echo lang($item['param_name']['rate_a']); ?>:</td>
                                                    <td align="left" width="10%" style="float: left"><span class="td_child"><?php echo $b['rate_a']*100; ?></span>%</td>
                                                    <td align="left" width="15%" style="float: left"><?php echo $b['name']; ?>:</td>
                                                    <td align="left" width="10%" style="float: left"><span class="td_child"><?php echo $b['rate_b']*100; ?></span>%</td>
                                                </tr>
                                                <input type='hidden' class='_child'  value="<?php echo $b['id'];?>">
                                            <?php } ?>
                                        <?php }else{ ?>
                                            <td align="left" width="15%" style="float: left"><?php echo lang($item['param_name']['rate_a']); ?>:</td>
                                            <td align="left" width="10%" style="float: left"><span class="td_child"><?php echo $item['data'][0]['rate_a']*100; ?></span>%</td>
                                            <?php foreach ($item['data'] as $a=>$b) { ?>
                                                <td align="left" width="15%" style="float: left"><?php echo $b['name']; ?>:</td>
                                                <td align="left" width="10%" style="float: left"><span class="td_child"><?php echo $b['rate_b']*100; ?></span>%</td>
                                                <input type='hidden' class='_child'  value="<?php echo $b['id'];?>">
                                            <?php } ?>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <?php foreach ($item['data'] as $a=>$b) { ?>
                                            <input type='hidden' class='_child'  value="<?php echo $b['id'];?>">
                                            <?php foreach ($item['param'] as $k=>$v) { ?>
                                                <td align="left" width="15%" style="float: left"><?php echo lang($item['param_name'][$v]); ?>:</td>
                                                <td align="left" width="10%" style="float: left"><span class="td_child"><?php echo $b[$v]*100; ?></span>%</td>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                            </tbody>
                        </table>
                        <div class="input-group" style="height:20px;">
                            <?php if($j == 26 && $showhide == 0) {?>
                                   <!-- <button class="btn btn-primary"  type="button" name="submit" onclick="preExpansion('<?php /*echo $j;*/?>')" style="position:absolute;bottom:0;right:166px">手动发奖</button>-->
                            <button class="btn btn-primary "  type="button" name="submit" onclick="preCalculate(<?php echo $j;?>)" style="position:absolute;bottom:0;right:116px">模拟</button>
                            <?php } ?>
                            <button class="btn btn-primary confirm_add" id="well_<?php echo $j;?>_edit" type="button" name="submit" onclick="changeTab('well_<?php echo $j;?>',true)" style="position:absolute;bottom:0;right:65px">修改</button>
                            <button class="btn btn-success" id="well_<?php echo $j;?>_add" type="button" style="display:none;position:absolute;bottom:0;right:65px" name="submit" onclick="submitFunc('well_<?php echo $j;?>',<?php echo $item['category_id'];?>,<?php echo $item['category_id'];?>,<?php echo $item['ischild'];?>)">保存</button>
                            <button class="btn btn-info cancel_add" type="button" onclick="changeTab('well_<?php echo $j;?>',false)" style="position:absolute;bottom:0;right:15px">取消</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script>
    function preCalculate(id){
        var url = '<?php echo base_url('admin/bonus_plan/pre_calculate?id=');?>'+id;
        layer.open({
            type: 2,
            area: ['600px', '500px'],
            fix: true,
            shadeClose:true,
            skin: 'layui-layer-rim',
            maxmin:true,
            title:"分红模拟",
            content: [url, 'no'],
            end:function(){
                //window.location.reload();
            }
        });
    }

    function editBonus(id){
        layer.open({
            type: 2,
            area: ['600px', '450px'],
            fix: true,
            shadeClose:true,
            maxmin:true,
            title:"<?php echo lang('add_honus_ratio')?>",
            content: '<?php echo base_url('admin/bonus_plan/bronus_plan_html?method=edit'); ?>&id='+id,
            end:function(){
                window.location.reload();
            }
        });
    }

    function deleteBonus(id){
        var url = "/admin/bonus_plan/delete_bonus";
        $.post(url,{'id':id},function(json){
            var json = eval('('+json+')');
            layer.msg(json.msg);
            setTimeout(location.reload(), 5000);
        })
    }

    function changeTab(id,flg){
        if(flg){
            $("#"+id).find(".td_child").each(function(i){
                $(this).html("<input type='text' class='input' name='td_"+i+"' value='"+$(this).text()+"' style='width:40px;'>");
            })
            $("#"+id+"_edit").hide();
            $("#"+id+"_add").show();
        }else{
            $("#"+id).find(".td_child").each(function(i){
                $(this).text($(this).find(".input").val());
            })
            $("#"+id+"_add").hide();
            $("#"+id+"_edit").show();
        }
    }

    /**
     * @param obj 元素ID
     * @param id  数据ID
     * @param categoryId 类型ID
     * @param childId 子级ID
     */
    function submitFunc(obj,id,categoryId,childId){
        var url = "/admin/bonus_plan/op_bonus?id="+id;
        var str = "";
        var htm = "";
        $("#"+obj).find(".td_child").each(function(i){
            var v = $(this).find(".input").val();
            if(v != ""){
                str += v+",";
            }
        })
        if(childId != 0){
            $("#"+obj).find("._child").each(function(i){
                var c = $(this).val();
                if(c != ""){
                    htm += c+",";
                }
            })
            childId = htm.substr(0,htm.length-1);
        }else{
            var wid = $("#"+obj).find("._child").val();
            url += "&wid="+wid;
        }
        $.post(url,{'postData':str.substr(0,str.length-1),"category_id":categoryId,"child_id":childId},function(json){
            var json = eval('('+json+')');
            if(json.success){
                layer.msg(json.msg);
                location.reload();
            }else{
                layer.msg(json.msg);
            }
        })
    }
    var t;
    var sid;
    var loading;
    /*//预发
    function preExpansion(id){
        loading = layer.load(2,{
            time: 0,
            shade: 0.5
        });
        var url = "/admin/bonus_plan/pre_expansion";
        $.post(url,{'id':id},function(json){
            var json = eval('('+json+')');
            if(json.success){
                sid = id;
                t = setInterval("pretestStatusQuery()",5000);
            }else{
                layer.msg(json.msg);
            }
        })
    }

     //定时请求预发状态
     function pretestStatusQuery(){
         var url = "/admin/bonus_plan/pretest_status_query?id="+sid;
             $.post(url,function(json){
                 var json = eval('('+json+')');
                 if(json.success){
                 if(json.data['state'] == 3){
                 layer.closeAll('loading');
                 $("#cron_msg_"+sid).html("(手动发奖已完成)");
                     window.clearInterval(t);
                 }
             }
         })
     }



     */

   /* //手动发会员奖
    function preExpansion(id){
        loading = layer.load(2,{
            time: 0,
            shade: 0.5
        });
        var url = "/admin/bonus_plan/manualAwards";
        $.post(url,{'categroy_id':id},function(json){
            var json = eval('('+json+')');
            if(json.success){
                sid = id;
                t = setInterval("pretestStatusQuery()",5000);
            }else{
                layer.closeAll('loading');
                $("#cron_msg_"+id).html("(手动发奖已完成)");
            }
        })
    }

    //定时请求手动发会员奖状态
    function pretestStatusQuery(){
        var url = "/admin/bonus_plan/manual_status_query?id="+sid;
        $.post(url,function(json){
            var json = eval('('+json+')');
            if(json.success){
                if(json.data['status'] == 2){
                    layer.closeAll('loading');
                    $("#cron_msg_"+sid).html("(手动发奖已完成)");
                    window.clearInterval(t);
                }
               /!* else if(json.data['ishanding'] == 0){
                    $.post("/admin/bonus_plan/manualAwardsIng?id="+sid,function(json){
                        var json = eval('('+json+')');
                        if(json.success){
                            $.post("/cron/set_new_member_control",function(){});
                        }
                    });
                }*!/
            }
        })
    }*/


</script>