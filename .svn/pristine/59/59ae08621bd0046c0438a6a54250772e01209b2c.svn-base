<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>

<form action="" method="post" id="add_bonus_system" class="form-horizontal">
    <input type="hidden" value="Processing..." id="loadingTxt">

    <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('reward_name');?></label>
    <div class="" style="">
        <input type="text" value="<?php echo isset($data)?$data['title']:''?>"
               placeholder="<?php echo lang('reward_name');?>" name="title" id="title" class="input-xlarge pull-left" autocomplete="off">
        <span class="msg" id="title_msg"></span>
    </div>
    <div class="clearfix"></div>

    <div style="margin-top: 20px;">
        <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('news_lang');?></label>
        <div class="">
            <select name="language_id">
                <?php foreach($lang_all as $lang) {?>
                    <option <?php if(isset($language_id) && $language_id == $lang['language_id']) echo 'selected'; ?> value="<?php echo $lang['language_id']?>"><?php echo $lang['name']?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>

    <input type="hidden" value="<?php echo isset($data)?$data['id']:0 ?>" id="bouns_id">
    <div class="clearfix"></div>
</form>
<div class="clearfix"></div>
<script type="text/plain" id="myEditor" style="width:1000px;height:240px;">
    <?php echo isset($data)?$data['html_content']:''?>
</script>
<div class="clearfix"></div>
<div style="margin-top: 20px;">
    <label style="display: inline">
     <?php echo lang("is_show_hide");?> :
    <input type="checkbox" name="display" value="0" id="add_board" <?php echo isset($data)&&$data['status'] ==1 ?'checked':''?>><?php echo lang('add_show');?>
    </label>

    </div>
    <div style="margin-top: 19px;">
    <img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('reward_sort');?> :
    <input autocomplete="off" type="text" name="sort" id="sort" placeholder="Sort" value="<?php echo isset($max_sort)?isset($data)?$max_sort:$max_sort+1:'1'?>">
    <span class="msg" id="sort_msg"></span>
    </div>
<span class="msg" id="content_msg"></span>
    <div class="clearfix"></div>

    <table style="margin-top: 30px;">

    <tr>
    <td><button name="add_data" type="button" class="btn btn-primary"><?php echo lang('submit');?></button></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><button type="button" class="btn btn-primary" onclick="location.href='/admin/incentive_system_management?language_id=<?php echo $http_language_id;?>'"><?php echo lang('back');?></button></td>
    </tr>
    </table>
    <script>
    $(function(){
        $('button[name="add_data"]').click(function(){
            curEle = $(this);
            oldSubVal = $('#loadingTxt').val();
            curEle.attr("disabled", true);
            var title = $('#title').val();
            var source = $('#source').val();
            var sort = $('#sort').val();
            var display = $('input[name="display"]:checked').val();
            var language_id=$('select[name=language_id]').val();

            if(display == undefined){
                display = 0;
            }else{
                display = 1 ;
            }

            //实例化编辑器
            var html_content = $.trim(UM.getEditor('myEditor').getContent());
            var content =($.trim(UM.getEditor('myEditor').getPlainTxt()));

            $.ajax({
                type:'POST',
                url: '/admin/add_incentive_system_management/do_add?id=<?php if(isset($data))echo $data['id'];?>',
                data: {title: title,sort:sort,status:display,content:content,html_content:html_content,lang:language_id},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                           window.location.href = '/admin/incentive_system_management?language_id=<?php echo $http_language_id;?>';
                    } else {
                        $.each(data.error,function(index,value){
                            if(value){
                                $('#'+index+'_msg').text('× '+value).addClass('error');
                            }else{
                                $('#'+index+'_msg').text('');
                            }
                        });

                    }
                    curEle.attr("value", oldSubVal);
                    curEle.attr("disabled", false);
                }
            });
        });
        $("#sort").keyup(function () {
            //如果输入非数字，则替换为''，如果输入数字，则在每4位之后添加一个空格分隔
            this.value = this.value.replace(/[^\d]/g, '')/*.replace(/(\d{4})(?=\d)/g, "$1 ")*/;
        })
    });
$(function () {
    files = $('.files');
    showimg = $('#showimg');
    $("#fileupload").wrap("<form id='myupload' action='/admin/add_news/news_pic' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function () { //选择文件
        $("#myupload").ajaxSubmit({
            dataType: 'json', //数据格式为json
            beforeSend:function(){
                $('.upload_btn').hide();
            },
            success: function (data) { //成功
                if(data.success){
                    //获得后台返回的json数据，显示文件名，大小，以及删除按钮
                    files.html("<a href='##' id='delimg' onclick='delimg()' class='delimg' rel='" + data.path + "'>Delete</a>");
                    $('#news_img').val(data.path);

                    //显示上传后的图片
                    var img = data.path;
                    showimg.html("<img src='/"+img+"'>");

                }else{
                    $('.upload_btn').show();
                    layer.msg(data.upload_data); //返回失败信息
                }
            },
            error: function (xhr) { //上传失败
                if(xhr.responseText){
                    $('.upload_btn').show();
                }
            }
        });
    });

});
function delimg() {
    var pic = $('#delimg').attr("rel");
    $.post("/admin/add_news/news_pic?act=del", {path: pic}, function (msg) {
        if (msg === '1') {
            $('.upload_btn').show();
            $('#news_img').val('');
            $('#showimg').empty(); //清空图片
            $('.files').hide(); //delete
        } else {
            layer.msg(msg);
        }
    });
}

</script>
<script type="text/javascript">
    //实例化编辑器
    var um = UM.getEditor('myEditor');

</script>