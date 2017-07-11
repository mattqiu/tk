<style>
    label{ padding: 15px 0px 0px 0px }
    #content{overflow-y:visible;}
</style>
<!--upload-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('ucenter_theme/lib/uploadify/uploadify.css')?>"/>
<script type="text/javascript" src="<?php echo base_url('ucenter_theme/lib/uploadify/jquery.uploadify-3.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('themes/admin/javascripts/tickets_base.js')?>"></script>

<div class="well">

    <form class="form-horizontal" id="add_tickets" method="post">
        <input type="hidden" id="loadingTxt" value="<?php echo lang('processing');?>">

        <label for="problem_type"><?php echo lang('tickets_type')?></label>
        <select name="type" id="problem_type" class="input-xxlarge">
            <option value=""><?php echo lang('tickets_type')?></option>
            <?php if($pro_type) {foreach($pro_type as $k=>$v){ ?>
                <option value="<?php echo $k;?>"><?php echo lang($v)?></option>
            <?php }}?>
        </select>

        <label for="language"><?php echo lang('tickets_language')?></label>
        <select name="language" id="problem_type" class="input-xxlarge">
            <?php if($language_all) {foreach($language_all as $v){ ?>
                <option value="<?php echo $v['language_id'];?>"><?php echo $v['name']?></option>
            <?php }}?>
        </select>

        <label for=""><?php echo lang('member_id')?></label>
        <div class="inline">
            <input id="uid" class="input-xxlarge tickets_input_box_trim" type="text" name="uid" value="" placeholder="<?php echo lang('pls_t_uid');?>" maxlength="10" >
        </div>

        <label for=""><?php echo lang('tickets_title')?></label>
        <div class="inline">
            <input id="title" class="input-xxlarge tickets_input_box_trim" type="text" name="title" value="" placeholder="<?php echo lang('max_limit_').'100'.lang('_words');?>" maxlength="100" >
        </div>
        <div style="margin-bottom: 2px;">
            <label for="" style="float: left;line-height: 0px;"><?php echo lang('tickets_content')?></label>
            <span style="float: left;">
                <select  class="template_type" style="width: 120px;margin-bottom: 2px;margin-left: 10px;">
                    <option value="e"><?php echo lang('tickets_type')?></option>
                    <?php if(!empty($pro_type)) {foreach($pro_type as $k=>$v){ ?>
                        <option value="<?php echo $k;?>"><?php echo lang($v)?></option>
                    <?php }}?>
                </select>
            </span>
            <span style="float: left;">
                <select  class="template_name" style="width: 150px;margin-bottom: 2px;margin-left: 10px;">
                    <option value="e"><?php echo lang('t_template_name')?></option>
                </select>
            </span>
        </div>
        <div class="inline" style="width: 532px;">
            <div>
                <textarea autoHeight="true" id="content" maxlength="1500" placeholder="<?php echo lang('max_limit_').'1500'.lang('_words');?>" name="content" rows="9" style="width: 532px; height: 201px;"></textarea>
            </div>
            <div style="float: right;clear: both;"><?php echo lang('remain_'); ?><span id='count'>600</span><?php echo lang('_words'); ?></div>
        </div>
        <div style="clear: both;"></div>
        <div class="upload_tips" style="color: red;"></div>
        <div style="margin-top: 10px;" class="upload">
            <input type="file" name="file_upload" id="file_upload" class="layui-upload-file"/>
        </div>
        <div style="margin-top:20px; ">
            <button type="button" name="submit" class="btn btn-primary"><?php echo lang('submit')?></button>
            <span class="msg" id="tickets_msg"></span>
        </div>
    </form>
</div>

<div id="confirm_submit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-body" id="confirm_submit_message" style="text-align: center;">
    </div>
    <div class="modal-footer">
        <button autocomplete="off" class="btn btn-primary" id="confirm_ok"><?php echo lang('jixu_submit'); ?></button>
        <button autocomplete="off" class="btn btn-primary" id="confirm_cancel"><?php echo lang('jiexie_previous'); ?></button>
    </div>
</div>

<script>
    $(function(){
        var t_cont  = getCookie('new_tickets_c');
        var t_title = getCookie('new_tickets_t');
        var c_obj   = $('#content');
        var t_obj   = $('#title');
        c_obj.val(t_cont);
        t_obj.val(t_title);
        /**计算字数 记录草稿 start**/
        $('#count').css('color','#FF0000').empty().append(1500-c_obj.val().length);
        c_obj.keyup(function(){
            $('#count').empty().append(1500-c_obj.val().length);
            addCookie('new_tickets_c',c_obj.val(),48);
        });
        t_obj.keyup(function(){
            addCookie('new_tickets_t',t_obj.val(),48);
        });
        /**计算字数 end**/
        autosize($('textarea'));
        //template
        $('.template_type').change(function(){
            $('.template_name').empty().append("<option value='e'><?php echo lang('t_template_name')?></option>");
            var val = $(this).find("option:selected").val();
            if( $(this).val()!='e'){
                $.ajax({
                    type:"post",
                    url:"<?php echo base_url('admin/add_tickets/get_template');?>",
                    data:{t:val},
                    dataType:"json",
                    success:function(res){
                        if(res.success==1){
                           for(var tpl in res.msg){
                              $('.template_name').append("<option value="+encodeURIComponent(res.msg[tpl].content)+">"+res.msg[tpl].name+"</option>");
                           }
                        }
                    }
                });
            }
        });
        $('.template_name').change(function(){
            var val = $(this).find("option:selected").val();
                val =decodeURIComponent(val);
            if(val!='e'){
                $('#content').val(val);
                $('#count').empty().append(1500-val.length);
            }else {
                $('#content').val('');
            }
        });

        $("button[name='submit']").click(function () {
            var title = $('#title').val();
            var cont = $('#content').val();
            var uid = $('#uid').val();
            if($.trim(title).length<=0){
                layer.msg('<?php echo lang('pls_t_title'); ?>');
                return;
            }
            if($.trim(cont).length<=0){
                layer.msg('<?php echo lang('pls_t_content'); ?>');
                return;
            }
            if(isNaN(uid) || $.trim(uid).length<10){
                layer.msg('<?php echo lang('pls_t_correct_ID'); ?>');
                return;
            }
            var curEle = $(this);
            var oldSubVal = curEle.text();
            curEle.html($('#loadingTxt').val());
            curEle.attr("disabled","disabled");

            $.ajax({
                type: "POST",
                url: "/admin/add_tickets/do_add_tickets",
                data: $('#add_tickets').serialize(),
                dataType: "json",
                success: function (data) {
                    if (data.success == 1) {
                        layer.msg(data.msg);
                        deleteCookie('new_tickets_c');
                        deleteCookie('new_tickets_t');
                        curEle.attr("disabled",false);
                        window.location.reload();
                    } else{
                        $('#tickets_msg').text('× '+ data.msg);
                        curEle.attr("disabled",false);
                        window.location.reload();
                    }
                    curEle.html(oldSubVal);
                }
            });


        });

        //upload
        var i=0;//初始化数组下标
        var uploadLimit = 10;
        $('#file_upload').uploadify({
            'auto'     : true,//自动上传
            'removeCompleted' : false,
            'isExists':'<?php echo lang('is_exists') ?>',
            "remain_upload_limit":'<?php echo lang('remain_upload_limit') ?>',
            "queue_size_limit":'<?php echo lang('queue_size_limit') ?>',
            "exceeds_size_limit":'<?php echo lang('exceeds_size_limit') ?>',
            "is_empty":'<?php echo lang('is_empty') ?>',
            "not_accepted_type":'<?php echo lang('not_accepted_type') ?>',
            "upload_limit_reached":'<?php echo lang('upload_limit_reached') ?>',
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : '<?php echo base_url('ucenter_theme/lib/uploadify/uploadify.swf'); ?>',
            'uploader' : '<?php echo base_url('admin/my_tickets/my_upload_attach');?>',
            'method'   : 'post',
            'buttonText' : '<?php echo lang('button_text'); ?>',
            'multi'    : true,
            'uploadLimit' : 10,
            'queueSizeLimit' : 10,
            'fileTypeDesc' : 'Image Files',
            'fileTypeExts' : '<?php echo config_item('tickets_upload_file_type'); ?>',
            'fileSizeLimit' : '8192KB',
            'cancelImage': '<?php echo base_url('ucenter_theme/lib/uploadify/uploadify-cancel.png')?>',
            'formData' : {'adminUserInfo' : '<?php echo get_cookie('adminUserInfo');?>'},
            'onUploadSuccess' : function(file, data, response) {
                var res=JSON.parse(data);
                var f_id= '#SWFUpload_0_'+i;
                if(res.success == 1){
                    //$(f_id).children('.cancel').children('a').attr('f_name',res.data.file_name);
                    $(f_id).children('.cancel').children('a').attr('path_name',res.data.path_name);
                    $(f_id).children('.cancel').show();
                    $(f_id).children('.uploadify-progress').hide();
                    $('.upload').append('<input name='+res.data.raw_name+' id='+res.data.raw_name+' type=hidden value='+encodeURI(file.name+'|'+ res.data.path_name+'|'+res.data.file_ext)+'>');
                }else{
                    delete this.queueData.files['SWFUpload_0_'+i];
                    $('#file_upload').uploadify('settings','uploadLimit', ++uploadLimit);
                    layer.msg(res.msg);
                    $(f_id).remove();
                }
                i++;
            },
            'onSelectError' : function(file) {
                i++;
            },
            'onCancel' : function(file) {//重复文件取消时自增id
                i++;
            },
            'onQueueComplete' : function(queueData) {
                //$(f_id).children('.uploadify-progress').hide();
                $('#file_upload-queue').children('.uploadify-queue-item').each(function(i){
                    //上传成功后的删除事件
                    $(this).children('.cancel').children('a').click(function(){
                        var id =  $(this).parent('.cancel').parent('.uploadify-queue-item').attr('id');
                        var num = $('.uploadify-queue-item').size();
                        delete queueData.files[id];
                        queueData.uploadsSuccessful = num-1;
                        $('#file_upload').uploadify('settings','uploadLimit', ++uploadLimit);
                        var path_name = $(this).attr('path_name');
                        var arr = path_name.split('/');
                        var id  = arr[arr.length-1].split('.')[0];
                        $('.upload').children('#'+id).remove();
                        $.ajax({
                            type:"post",
                            url:"<?php echo base_url('admin/my_tickets/delete_attach');?>",
                            data:{path_name:path_name},
                            dataType:"json",
                            success:function(res){
                                if(res.success){
                                    //layer.msg(res.msg);
                                }
                            }
                        });
                    });
                });
            }
        });
    });

    var system  ={
        win : false,
        mac : false,
        xll : false
    };
    var p       = navigator.platform;
    system.win  = p.indexOf("Win") == 0;
    system.mac  = p.indexOf("Mac") == 0;
    system.x11  = (p == "X11") || (p.indexOf("Linux") == 0);
    if(!system.win && !system.mac && !system.xll){
        $('.upload').hide();
        $('.upload_tips').append("<?php echo lang('not_support_mobile_upload'); ?>");
    }

	/*layui.upload({
	  url: 'admin/my_tickets/my_upload_attach',
	  success: function(res){
	  console.log(res); //上传成功返回值，必须为json格式
	  }
	});*/

	
</script>


