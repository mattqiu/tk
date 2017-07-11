<meta charset="utf-8"/>

<script src="<?php echo base_url('themes/admin/lib/jquery-1.8.1.min.js')?>" type="text/javascript"></script>

UID:<input  type="text" id="txt_uid" width="100px"><input type="button" value="查询" onclick="doquery()"><br/>

---现数据-<br/>
现金池:<label id="lbl_amount"   name="amount"   value="0"></label><br/>
用户总分红点:<label id="lbl_profit_sharing_point"  name="profit_sharing_point"  value="0"></label><br/>
分红佣金自动转入分红点:<label id="lbl_profit_sharing_point_from_sharing" name="profit_sharing_point_from_sharing"  value="0"></label><br/>
周领导对等奖总金额:<label id="lbl_amount_weekly_Leader_comm"  name="amount_weekly_Leader_comm"  value="0"></label> <br/>
会员数量:<label id="lbl_menber_num"  name="menber_num"  value="0"></label> <br/>
佣金点:<label id="lbl_proportion"   value="0"></label> <br/>
子级id:<label id="lbl_childs"   value="0"></label> <br/>
<!-------用户奖金统计---<br/>-->
<!--现金池:<label id="lbl_week_bonus" name="week_bonus"   value="0"></label><br/>-->
<br/>
----周领导对等奖---<br/>
总额:<label id="lbl_total_amount"   value="0"></label><br/>
<br/>
--修复数据<br/>
现金池:<input type="text" id="txt_amount"   value="0">:amount<br/><br/>
用户总分红点:<input type="text" id="txt_profit_sharing_point"   value="0"></label>:profit_sharing_point<br/><br/>
分红佣金自动转入分红点:<input type="text" id="txt_profit_sharing_point_from_sharing"   value="0"></label>:profit_sharing_point_from_sharing<br/><br/>
周领导对等奖总金额:<input type="text" id="txt_amount_weekly_Leader_comm"  value="0"></label>:amount_weekly_Leader_comm <br/><br/>
周领导对等奖总额:<input type="text" id="txt_week_amount" value="0">：week_amount<br/><br/>
<input type="button" value="提交修复现金池" onclick="repairUser()">
<br/>
-----用户奖金统计---<br/>

金额:<input type="text" id="txt_week_bonus"   value="0"></label>：week_bonus<br/>
<input type="button" value="提交用户奖金统计" onclick="repairuserStat()">
<br/>
--周领导对等奖记录
<input type="hidden" value="7" id="hd_item_type_week"><br/><br/>
删除ID：<input type="text" id="txt_log_week_id" value="0"><input type="button" value="删除" onclick="doweekleader('del')"><br/><br/>
修改ID：<input type="text" id="txt_log_id" value="0">金额：<input type="text" id="txt_log_amount_update" value="0"><input type="button" value="修改" onclick="doweekleader('up')"><br/><br/>
金额：<input type="text" id="txt_log_amount_add" value="0"><input type="button" value="新增"  onclick="doweekleader('add')"><br/><br/><br/>
<br/>--佣金分红记录<br/>
<input type="text" value="201704" id="txt_year_month"><br/>
<input type="hidden" value="17" id="hd_item_type_point">
ID:<input type="text" id="txt_log_point_id_del" value="0"><input type="button" value="删除" onclick="doweekleader17('del')"><br/><br/>
ID:<input type="text" id="txt_log_point_id_up" value="0">金额：<input type="text" id="txt_log_point_amount_up" value="0"><input type="button" value="修改" onclick="doweekleader17('up')"><br/><br/>
金额：<input type="text" id="txt_log_point_amount_add" value="0"><input type="button" value="新增" onclick="doweekleader17('add')"><br/><br/>


--佣金分红明细记录<br/>
ID:<input type="text" id="txt_log_point_id_detail_del" value="0"><input type="button" value="删除" onclick="dopoint('del')"><br/><br/>
ID:<input type="text" id="txt_log_point_detail_up" value="0"> 点：<input type="text" id="txt_log_point_detail_amount" value="0"><input type="button" value="修改" onclick="dopoint('up')"><br/><br/>
点：<input type="text" id="txt_log_point_detail_add" value="0">
佣金记录ID:<input type="text" value="" id="sharing_point_log_id"><input type="button" value="新增" onclick="dopoint('add')"><br/>

----单值修复 <br/>

<select id="drop_filed">
    <option>----</option>
    <option value="amount">现金池</option>
    <option value="profit_sharing_point">用户总分红点</option>
    <option value="profit_sharing_point_from_sharing">分红佣金自动转入分红点</option>
    <option value="amount_weekly_Leader_comm">周领导对等奖总金额</option>
</select>
数值:<input type="text" id="txt_number"   value="0"><br/><br/>


<input type="button" value="提交修复单值" onclick="repairone()">




<script type="text/javascript">
function doquery() {
    var uid=$("#txt_uid").val();
    if(uid!='') {
        $.ajax({
            url:'<?php echo base_url('admin/RepairWeekLeader/doquery') ?>',
            type:'POST', //GET
            async:true,    //或false,是否异步
            data:{
                uid:uid
            },
            timeout:5000,    //超时时间
            dataType:'json',
            beforeSend:function(xhr){
                $("#lbl_total_amount").text(0);
                $("#lbl_amount").text(0);
                $("#lbl_menber_num").text(0);
                $("#lbl_profit_sharing_point_from_sharing").text(0);
                $("#lbl_profit_sharing_point").text(0);
                $("#lbl_amount_weekly_Leader_comm").text(0);
                $("#lbl_proportion").text(0);
                $("#txt_amount").val(0);
                $("#txt_profit_sharing_point").val(0);
                $("#txt_profit_sharing_point_from_sharing").val(0);
                $("#txt_amount_weekly_Leader_comm").val(0);
                $("#txt_week_amount").val(0);
                $("#lbl_childs").text('');
            },
            success:function(data,textStatus,jqXHR){
                 if(data.status==1){
                     $("#lbl_total_amount").text(data.total_amount);
                     $("#lbl_amount").text(data.amount);
                     $("#lbl_menber_num").text(data.menber_num);
                     $("#lbl_profit_sharing_point_from_sharing").text(data.profit_sharing_point_from_sharing);
                     $("#lbl_profit_sharing_point").text(data.profit_sharing_point);
                     $("#lbl_amount_weekly_Leader_comm").text(data.amount_weekly_Leader_comm);
                     $("#lbl_proportion").text(data.proportion);
                     $("#lbl_childs").text(data.childIds);
                 }else {
                     alert('参数异常');
                 }
            }
        });
    }else {
        alert('请输入uid');
        $("#txt_uid").focus();
    }
}
function  repairUser() {
    var uid=$("#txt_uid").val();
    if(uid!='') {
        $.ajax({
            url:'<?php echo base_url('admin/RepairWeekLeader/repairuser') ?>',
            type:'POST', //GET
            async:true,    //或false,是否异步
            data:{
                uid:uid,
                amount:$("#txt_amount").val(),
                profit_sharing_point:$("#txt_profit_sharing_point").val(),
                profit_sharing_point_from_sharing:$("#txt_profit_sharing_point_from_sharing").val(),
                amount_weekly_Leader_comm:$("#txt_amount_weekly_Leader_comm").val(),
                week_amount:$("#txt_week_amount").val()
            },
            timeout:5000,    //超时时间
            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
            success:function(data,textStatus,jqXHR){
                if(data.status==1){
                    $("#lbl_total_amount").text(data.total_amount);
                    $("#lbl_amount").text(data.amount);
                    $("#lbl_menber_num").text(data.menber_num);
                    $("#lbl_profit_sharing_point_from_sharing").text(data.profit_sharing_point_from_sharing);
                    $("#lbl_profit_sharing_point").text(data.profit_sharing_point);
                    $("#lbl_amount_weekly_Leader_comm").text(data.amount_weekly_Leader_comm);
                    $("#lbl_proportion").text(data.proportion);
                }else {
                    alert('参数异常');
                }
            }
        });
    }else {
        alert('清输入uid');
        $("#txt_uid").focus();
    }
}
function  repairuserStat() {
    var uid=$("#txt_uid").val();
    if(uid!='') {
        $.ajax({
            url:'<?php echo base_url('admin/RepairWeekLeader/repairuserStat') ?>',
            type:'POST', //GET
            async:true,    //或false,是否异步
            data:{
                uid:uid,
                amount:$("#txt_week_bonus").val(),
            },
            timeout:5000,    //超时时间
            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
            success:function(data,textStatus,jqXHR){
                if(data.status==1){
                  alert('更新成功');
                }else {
                    alert('参数异常');
                }
            }
        });
    }else {
        alert('清输入uid');
        $("#txt_uid").focus();
    }

}
function  doweekleader(dowhat) {
    var uid=$("#txt_uid").val();
    if(uid!='') {
        switch(dowhat){
            case "del":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/doweekleader') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        type:7,
                        amt:0,
                        id:$("#txt_log_week_id").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                           alert('删除周领导奖成功');
                        }else {
                            alert('删除周领导奖失败');
                        }
                    }
                });
                break;
            case "up":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/doweekleader') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        type:7,
                        id:$("#txt_log_id").val(),
                        amt:$("#txt_log_amount_update").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('修改周领导奖成功');
                        }else {
                            alert('修改周领导奖失败');
                        }
                    }
                });
                break;

            case "add":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/doweekleader') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        type:7,
                        id:0,
                        amt:$("#txt_log_amount_add").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('新增周领导奖成功');
                        }else {
                            alert('新增周领导奖失败');
                        }
                    }
                });
                break;
        }
    }else {
        alert('清输入uid');
        $("#txt_uid").focus();
    }
}
function  doweekleader17(dowhat) {
    var uid=$("#txt_uid").val();
    if(uid!='') {
        switch(dowhat){
            case "del":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/doweekleader') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        type:17,amt:0,
                        month:$("#txt_year_month").val(),
                        id:$("#txt_log_point_id_del").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('删除分红佣金成功');
                        }else {
                            alert('删除分红佣金失败');
                        }
                    }
                });
                break;
            case "up":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/doweekleader') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        type:17,
                        id:0,
                        id:$("#txt_log_point_id_up").val(),
                        month:$("#txt_year_month").val(),
                        amt:$("#txt_log_point_amount_up").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('修改分红佣金成功');
                        }else {
                            alert('修改分红佣金失败');
                        }
                    }
                });
                break;

            case "add":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/doweekleader') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        type:17,
                        id:0,
                        month:$("#txt_year_month").val(),
                        amt:$("#txt_log_point_amount_add").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('新增分红佣金成功');
                        }else {
                            alert('新增分红佣金失败');
                        }
                    }
                });
                break;
        }
    }else {
        alert('请输入uid');
        $("#txt_uid").focus();
    }
}
function  dopoint(dowhat) {
    var uid=$("#txt_uid").val();
    if(uid!='') {
        switch(dowhat){
            case "del":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/dopoint') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,amt:0,
                        id:$("#txt_log_point_id_detail_del").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('删除分红明细成功');
                        }else {
                            alert('删除分红明细失败');
                        }
                    }
                });
                break;
            case "up":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/dopoint') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        id:$("#txt_log_point_detail_up").val(),
                        amt:$("#txt_log_point_detail_amount").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('修改分红明细成功');
                        }else {
                            alert('修改分红明细失败');
                        }
                    }
                });
                break;

            case "add":
                $.ajax({
                    url:'<?php echo base_url('admin/RepairWeekLeader/dopoint') ?>',
                    type:'POST', //GET
                    async:true,    //或false,是否异步
                    data:{
                        uid:uid,
                        act:dowhat,
                        id:0,
                        amt:$("#txt_log_point_detail_add").val(),
                        logid:$("#sharing_point_log_id").val()
                    },
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.status==1){
                            alert('新增分红明细成功');
                        }else {
                            alert('新增分红明细失败');
                        }
                    }
                });
                break;
        }
    }else {
        alert('请输入uid');
        $("#txt_uid").focus();
    }
}
function repairone() {
    var uid=$("#txt_uid").val();
    if(uid!='') {
        $.ajax({
            url:'<?php echo base_url('admin/RepairWeekLeader/repairone') ?>',
            type:'POST', //GET
            async:true,    //或false,是否异步
            data:{
                uid:uid,
                filed:$("#drop_filed").val(),
                num:$("#txt_number").val(),
              //  profit_sharing_point:$("#txt_profit_sharing_point_1").val(),
               // profit_sharing_point_from_sharing:$("#txt_profit_sharing_point_from_sharing_1").val(),
              ////  amount_weekly_Leader_comm:$("#txt_amount_weekly_Leader_comm_1").val(),
               // week_amount:$("#txt_week_amount").val()
            },
            timeout:5000,    //超时时间
            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
            success:function(data,textStatus,jqXHR){
                if(data.status==1){
                    alert('更新成功');
                }else {
                    alert('参数异常');
                }
            }
        });
    }else {
        alert('清输入uid');
        $("#txt_uid").focus();
    }

}
</script>


