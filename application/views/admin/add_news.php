<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>

<form action="" method="post" id="add_news" class="form-horizontal">
    <input type="hidden" value="Processing..." id="loadingTxt">

    <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('title');?></label>
    <div class="" style="">
        <input type="text" value="<?php echo isset($data)?$data['title']:''?>"
               placeholder="<?php echo lang('title');?>" name="title" id="title" class="input-xlarge pull-left" autocomplete="off">
        <span class="msg" id="title_msg"></span>
    </div>
    <div class="clearfix"></div>
    
    <div style="margin-top: 20px;">
        <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('news_lang');?></label>
        <div class="">
            <select name="language_id">
            	
            	<?php foreach($lang_all as $lang) {?>
            	<option <?php if(isset($data) && $data['language_id'] == $lang['language_id']) echo 'selected'; ?> value="<?php echo $lang['language_id']?>"><?php echo $lang['name']?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    
    <div style="margin-top: 20px;">
        <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('news_cate');?></label>
        <div class="">
            <select name="cate_id">
           		<option value="0"><?php echo lang('news_cate_name')?></option>
            	<?php foreach($type_all as $type) {?>
            	<option <?php if(isset($data) && $data['cate_id'] == $type['type_id']) echo 'selected'; ?> value="<?php echo $type['type_id']?>"><?php echo $type['type_name']?></option>
                <?php }?>
                
            </select>
            <input class="js-add-type" value="" type="text" /><input class="js-add-cate" type="button" value="<?php echo lang('news_ok')?>" />
        </div>
    </div>
    <div class="clearfix"></div>

	<!--
    <div style="margin-top: 20px;">
        <label for=""><?php echo lang('source');?></label>
        <div class="">
            <input type="text" value="<?php echo isset($data)?$data['source']:''?>"
                   placeholder="<?php echo lang('source');?>" name="source" id="source" class="input-xlarge pull-left" autocomplete="off">
            <span class="msg" id="source_msg"></span>
        </div>
    </div>
    <div class="clearfix"></div>
    

    <?php if(!isset($data)){?>
    <div class="upload_btn" style="margin-top: 20px">
        <span><?php echo lang('news_img');?></span>
        <input id="fileupload" type="file" name="userfile">
    </div>

    <div class="clearfix"></div>
    <div id="showimg"></div><div class="files"></div> <span class="txt"style="color: #ff0000;"></span>
    <?php }else{?>
        <div id="showimg"><img src="/<?php echo isset($data)?$data['img']:''?>"/></div>
    <?php }?>
    <span class="msg" id="img_msg"></span>
    <div class="clearfix"></div>
    <div>
        <img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
    <label style="display: inline" class="modal_main">
        <input autocomplete="off" type="radio" name="type" value="0" <?php if(isset($data)){ echo $data['type'] == 0 ? 'checked':'';  } else { echo $curLanguage != 'english' ? 'checked':'';} ?>/>
        中文
    </label>
    <label style="margin-left: 30px;display: inline" class="modal_main">
        <input autocomplete="off" type="radio"  name="type" value="1" <?php if(isset($data)){ echo $data['type'] == 1 ? 'checked':'';  } else { echo $curLanguage == 'english' ? 'checked':'';} ?> />
        English
    </label>
    </div>
	-->

    <div style="margin-top: 20px;">
        <label style="display: inline">
            <input type="checkbox" name="hot" value="1" id="add_board" <?php echo isset($data)&&$data['hot'] ==1 ?'checked':''?>><?php echo lang('hot_news');?>
        </label>
        <label style="display: inline;margin-left: 20px;">
            <input type="checkbox" name="display" value="1" id="add_board" <?php echo isset($data)&&$data['display'] ==1 ?'checked':''?>><?php echo lang('display');?>
        </label>
    </div>
    <div style="margin-top: 19px;">
        <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('sort');?></label>
        <input autocomplete="off" type="text" name="sort" id="sort" placeholder="Sort" value="<?php echo isset($data)?$data['sort']:'100'?>">
        <span class="msg" id="sort_msg"></span>
    </div>
    <input type="hidden" value="<?php echo isset($data)?$data['id']:''?>" id="news_id">
    <input type="hidden" value="<?php echo isset($data)?$data['img']:''?>" id="news_img" name="img" autocomplete="off">
    <div class="clearfix"></div>
</form>
<div class="clearfix"></div>
<script type="text/plain" id="myEditor" style="width:1000px;height:240px;">
    <?php echo isset($data)?$data['html_content']:''?>
</script>
<span class="msg" id="content_msg"></span>
<div class="clearfix"></div>

<table style="margin-top: 30px;">

    <tr>
        <td colspan="2"><button name="add_news" type="button" class="btn btn-primary"><?php echo lang('submit');?></button></td>
    </tr>
</table>
<script>
    $(function(){
        $('button[name="add_news"]').click(function(){
            curEle = $(this);
            oldSubVal = $('#loadingTxt').val();
            curEle.attr("disabled", true);
            var title = $('#title').val();
            var source = $('#source').val();
            var sort = $('#sort').val();
            var type = $('input:radio:checked').val();
            var hot = $('input[name="hot"]:checked').val();
            var display = $('input[name="display"]:checked').val();
			
			var language_id=$('select[name=language_id]').val();
			var cate_id=$('select[name=cate_id]').val();

            if(hot == undefined){
                hot = 0;
            }else{
                hot = 1 ;
            }
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
                url: '/admin/add_news/do_add',
                data: {title: title,source:source,sort:sort,hot:hot,display:display,content:content,html_content:html_content,type:type,news_id:$('#news_id').val(),img:$('#news_img').val(),language_id:language_id,cate_id:cate_id},
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                      if($('#news_id').val() == ''){
                          window.location.href = '/admin/news_list';
                      }
                      window.location.reload();
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
		
		//增加分类
		$('.js-add-cate').click(function() {
			var $text=$('.js-add-type'),text=$text.val(),
			lang_id=$('select[name=language_id]').val();
			
			if($.trim(text) == '') {
				$().message('<?php echo lang('news_cate_need')?>');
				return;
			}
			
			$.get('/admin/add_news/add_news_type',{type_name:text,lang_id:lang_id},function(data){
				if(data > 0) {
					$('select[name=cate_id]').append('<option value="'+data+'">'+text+'</option>');
					$text.val('');
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