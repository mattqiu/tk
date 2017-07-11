<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo lang('tps138_admin');?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/register.css?v=2'); ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lib/bootstrap/css/bootstrap.css?v=1')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/stylesheets/theme.css?v=10')?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
</head>

<body>

<div>
    <form id="register_form">
        <div style="padding: 1.5em;">
            <table >
                <tr>
                    <td><span><?php echo lang('type'); ?>:</span><span style="color:darkred">*</span></td>
                    <td>
                        <select name="category_id" id="category_id" class="com_type input-medium" style="width: 220px;" onchange="cateSelect(this.value)">
                            <option value=""><?php lang('pls_sel'); ?></option>
                            <?php foreach ($commission_type as $key => $value) { ?>
                                <?php if(!empty($bonusData)){ ?>
                                    <?php if($bonusData['category_id'] == $key) {?>
                                        <option value="<?php echo $key; ?>" selected><?php echo lang($value); ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $key; ?>"><?php echo lang($value); ?></option>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <option value="<?php echo $key; ?>"><?php echo lang($value); ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <span class="cate_error_msg" style="display: none;color:darkred"></span>
                    </td>
                </tr>
                <tr>
                    <td><span><?php echo lang('child'); ?>:</span></td>
                    <td>
                        <select name="child_id" id="child_id" class="com_type input-medium" style="width: 220px;" onchange="verifyParant()">
                            <?php if(!empty($bonusData)){ ?>
                                <?php foreach($commission_type_child as $k=>$v) {?>
                                    <?php if($v['pid'] == $bonusData['category_id']) {?>
                                        <?php if($v['id'] == $bonusData['child_id']) {?>
                                            <option value="<?php echo $v['id']; ?>" selected><?php echo $v['name']; ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>

                </tr>
                <tbody id="table">
                    <?php if(!empty($bonusData)){ ?>
                        <?php foreach($brouns_param_numbers as $k=>$v) {?>
                             <?php if($k == $bonusData['category_id']) {?>
                               <?php foreach($brouns_param_numbers[$k] as $j=>$h) {?>
                                    <tr>
                                        <td><?php echo lang('param');?><?php echo $j+1;?>:<span style='color:darkred'>*</span></td>
                                        <td>
                                            <input type='text' class='form-control' name='param_<?php echo $j+1;?>' onblur='onblur<?php echo $j;?>()' value="<?php echo $bonusData[$sql_param[$j]]*100;?>">%
                                            <span style='color:red'>(<?php echo lang($h);?>)</span>
                                            <span class='p<?php echo $j;?>_error_msg' style='display: none;color:darkred'></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                             <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
            <div class="clearfix"></div>
            <span class="input-group">
            <button class="btn btn-primary confirm_add" type="button" name="submit" onclick="ajaxSubmit()"><?php echo lang('commission_isok') ?></button>
            <button class="btn btn-info cancel_add" type="button" style="margin-left: 25px;"><?php echo lang('cancel') ?></button>
            </span>
        </div>
        <input type="hidden" id="loop" value="">
        <input type="hidden" id="category_input" value="<?php if(!empty($bonusData)){ ?><?php echo $bonusData['category_id'];?><?php } ?>">
        <input type="hidden" name="id" value="<?php if(!empty($bonusData)){ ?><?php echo $bonusData['id'];?><?php } ?>">
    </form>
</div>
</body>
<script src="<?php echo base_url('themes/mall/js/jquery.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
<script>
    var param=<?php echo json_encode($commission_type_child);?>;
    var param_input=<?php echo json_encode($brouns_param_numbers);?>;
    var pid = 0;

    $(function() {
        $("#category_id").blur(function () {
            var caregory_id = $("#category_id").val();
            if (caregory_id != "") {
                $(".cate_error_msg").hide();
            }
        });

        $('.cancel_add').click(function(){
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        });
    })

    function onblur0() {
        var params = $("input[name='param_1']").val();
        if (params != "") {
            $(".p0_error_msg").hide();
        }
    }


    function onblur1() {
        var params = $("input[name='param_2']").val();
        if (params != "") {
            $(".p1_error_msg").hide();
        }
    }

    function onblur2() {
        var params = $("input[name='param_3']").val();
        if (params != "") {
            $(".p2_error_msg").hide();
        }
    }


    function onblur3() {
        var params = $("input[name='param_4']").val();
        if (params != "") {
            $(".p3_error_msg").hide();
        }
    }

    function onblur4() {
        var params = $("input[name='param_5']").val();
        if (params != "") {
            $(".p4_error_msg").hide();
        }
    }


    function cateSelect(id){
        var html = "";
        var inputHtml = "";
        html += "<option value=''><?php echo lang('pls_sel'); ?></option>";
        for(var i =0;i<param.length;i++){
            if(id == param[i].pid){
                html += "<option value='"+param[i].id+"'>"+param[i].name+"</option>";
            }
        }
        $("#child_id").html(html);
        $("#table").html("");
        <?php foreach($brouns_param_numbers as $k=>$v) {?>
           if(<?php echo $k;?> == id){
                $("#loop").val(<?php echo count($brouns_param_numbers[$k]);?>);
                <?php foreach($brouns_param_numbers[$k] as $j=>$v) {?>
                    inputHtml += "<tr><td>";
                    inputHtml += "<?php echo lang('param');?><?php echo $j+1;?>:<span style='color:darkred'>*</span></td>";
                    inputHtml += "<td><input type='text' class='form-control' placeholder='<?php echo lang('pl_2int');?>' name='param_<?php echo $j+1;?>' onblur='onblur<?php echo $j;?>()'>%";
                    inputHtml += "<span style='color:red'>(<?php echo lang($v);?>)</span>";
                    inputHtml += "<span class='p<?php echo $j;?>_error_msg' style='display: none;color:darkred'></span>";
                    inputHtml += "</td> </tr>";
                <?php } ?>
                $("#table").html(inputHtml);
            }
        <?php } ?>
        $("#category_input").val(id);
    }


    function verifyParant(){
        $.post("/admin/bonus_plan/verify_parent",{'pid':$("#category_input").val()},function(json){
            var json = eval('('+json+')');
            $("input[name='param_1']").val(json.data['rate_a']*100);
        })
    }


   function ajaxSubmit(){
       var category_id = $("#category_id").val();
       var child_id = $("#child_id").val();
       var param_1 = $("input[name='param_1']").val();
       var param_2 = $("input[name='param_2']").val();
       var param_3 = $("input[name='param_3']").val();
       var param_4 = $("input[name='param_4']").val();
       var param_5 = $("input[name='param_5']").val();
       var loop  = $("#loop").val();
       var id  = $("input[name='id']").val();
       var url ="";

       if(id == ""){
           url = "/admin/bonus_plan/op_bonus";
       }else{
           url = "/admin/bonus_plan/op_bonus?id="+id;
       }

       if(category_id == ""){
           $(".cate_error_msg").show();
           $(".cate_error_msg").text("<?php echo lang('type_no_empty');?>");
           return false;
       }

       if(param_1 == "" && loop >=1){
           $(".p0_error_msg").show();
           $(".p0_error_msg").text("<?php echo lang('param_no_empty');?>");
           return false;
       }

       if(param_2 == "" && loop >=2){
           $(".p1_error_msg").show();
           $(".p1_error_msg").text("<?php echo lang('param_no_empty');?>");
           return false;
       }

       if(param_3 == "" && loop >=3){
           $(".p2_error_msg").show();
           $(".p2_error_msg").text("<?php echo lang('param_no_empty');?>");
           return false;
       }

       if(param_4 == ""  && loop ==4){
           $(".p3_error_msg").show();
           $(".p3_error_msg").text("<?php echo lang('param_no_empty');?>");
           return false;
       }

       if(param_5 == ""  && loop ==5){
           $(".p4_error_msg").show();
           $(".p4_error_msg").text("<?php echo lang('param_no_empty');?>");
           return false;
       }

       $.post(url,
           {
               "category_id":category_id,
               "child_id":child_id?child_id:0,
               "rate_a":param_1,
               "rate_b":param_2,
               "rate_c":param_3,
               "rate_d":param_4,
               "rate_e":param_5,
           },function(json){
                var json = eval('('+json+')');
                if(json.success){
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    layer.msg(json.msg);
                    location.reload()
                }else{
                    layer.msg(json.msg);
                }
           }
       )
   }

</script>