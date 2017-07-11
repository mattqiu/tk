<input type="hidden" id="loadingTxt" value="<?php echo lang('processing'); ?>">
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<div class="search-well">
    <ul class="nav nav-tabs">
        <?php foreach ($tabs_map as $k => $v): ?>
            <li <?php if ($k == $tabs_type) echo " class=\"active\""; ?>>
                <a href="<?php echo base_url($v['url']); ?>">
                    <?php echo $v['desc']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<style>
    .cont_style
    {
        padding:10px;
    }

    .cont_style_left
    {
        padding:10px;
    }
</style>

<div class="block ">

    <form id="upload_form" name="upload_form"  action="<?php echo base_url('admin/trade/admin_upload_user_achievement') ?>"  method="post" class="form-inline" enctype="multipart/form-data">
        <div class="block-body">
            <div class="row-fluid cont_style">
                <input autocomplete="off" class="input-mini" type="file" id="excelfile" name="excelfile"/>
            </div>
            <div class="row-fluid cont_style">
                <input class="Wdate span2 time_input" type="text" id="t_stime" name="t_stime" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'zh'})" placeholder="开始时间">
            </div>
            <div class="row-fluid cont_style">
                <input class="Wdate span2 time_input" type="text" id="t_end" name="t_end" value="" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'zh'})" placeholder="结束时间">
            </div>
            <div class="row-fluid cont_style">
                补发奖项:
                <select name="par_set" id="par_set" onchange="user_conf(this.value)" style="width:150px;">
                    <option value="0">--请选择--</option>
                    <option value="1">每天全球利润分红</option>
                    <option value="2">138日分红</option>
                    <option value="3">每周团队销售分红</option>
                    <option value="4">每周领导对等奖</option>
                    <option value="5">每月团队组织分红</option>
                    <option value="6">每月领导销售分红</option>
                    <option value="7">新会员专享奖</option>
                    <option value="8">每月杰出店铺分红</option>
                </select>
            </div>
            <div class="row-fluid cont_style">
                修改队列中业绩:
                <select name="par_score" id="par_score" style="width:150px;">
                    <option value="0">--请选择--</option>
                    <option value="1">每天全球利润分红</option>
                    <option value="3">每周团队销售分红</option>
                    <!--
                    <option value="4">每周领导对等奖</option>
                    <option value="5">每月团队组织分红</option>
                     -->
                </select>
            </div>
            <div class="row-fluid cont_style">
                清楚队列中不满足发奖的用户:
                <select name="par_clear_list" id="par_clear_list" style="width:150px;">
                    <option value="0">--请选择--</option>
                    <option value="1">每天全球利润分红</option>
                    <option value="2">138日分红</option>
                    <option value="3">每周团队销售分红</option>
                    <option value="4">每周领导对等奖</option>
                    <option value="5">每月团队组织分红</option>

                </select>
            </div>
            <div class="row-fluid cont_style">
                奖项抽回:
                <select name="par_ledaer_list" id="par_ledaer_list" style="width:150px;">
                    <option value="0">--请选择--</option>
                    <option value="1">每月总裁奖</option>
                    <option value="3">个人销售和团队销售奖</option>
                    <option value="4">138分红</option>
                </select>
                <select name="par_ledaer_num" id="par_ledaer_num" style="width:150px;">
                    <option value="0">--请选择--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="row-fluid cont_style_left">
                <input id='submit_button' autocomplete="off"  value="<?php echo lang('submit');?>" class="btn btn-primary" type="submit">
            </div>
            <div class="row-fluid cont_style_left"><span style="color: red"><?php echo lang('user_achievement_note');?></span></div>
            <div class="row-fluid cont_style_left">
                <img src="../../img/zh/user_achievement_demo.png">
            </div>
        </div><!--/end block-body -->
    </form>


</div>

<?php if (isset($err_msg)): ?>
    <div class="well">
        <p style="color: red;"><?php echo $err_msg; ?></p>
    </div>
<?php endif; ?>

<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/lib/thinkbox/jquery.thinkbox.js?v=1'); ?>"></script>
<script>

    function user_conf(strs)
    {
        if(1==strs)
        {
            $("#user_set_conf").show();
            $("#default_conf").hide();
        }
        else
        {
            $("#user_set_conf").hide();
            $("#default_conf").show();
        }
    }

    function errboxHtml(msg) {
        return "<div class=\"thinkbox-tips thinkbox-tips-error\">" + msg + "</div>";
    }

    $('#upload_form').submit(function() {

        if ($('[name=excelfile]').val() == '') {
            layer.msg('<?php echo lang('admin_select_file')?>');
            return false;
        }

        if ($('[name=par_score]').val() != 0) {
            if ($('[name=t_stime]').val() == '') {
                layer.msg('<?php echo lang('please_select_queue_time')?>');
                return false;
            }
        }
        var li ;
        $('#submit_button').attr('disabled', true);
        $(this).ajaxSubmit({
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    layer.msg(res.msg);
                    setTimeout(function(){
                        location.reload();
                    }, 2000)
                } else {
                    $.thinkbox(errboxHtml(res.msg));
                }
            },
            error: function() {
                $.thinkbox(errboxHtml(res.msg));
                layer.msg('<?php echo lang('admin_request_failed')?>');
            },
            beforeSend: function() {
                li = layer.load();
            },
            complete: function() {
                layer.close(li);
                $('#submit_button').attr('disabled',false);
            }
        });

        return false;
    });


</script>