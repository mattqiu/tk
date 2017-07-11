<style>
    .bulletin{
        width: 48%;
        height: 100px;
    }
    #add_bulletin div{
        margin: 10px;
    }
    .div_margin{
        margin:10px;
    }
    
    .div_content_sty
    {
	   margin-left:20px;
    }
</style>

<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>


<form action="" method="post" id="add_bulletins">
    <input type="hidden" value="Processing..." id="loadingTxt">
    
   
    <div class="div_margin">
        <img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
        <input name="title_english" id="title_english" class="span6" value="<?php echo isset($data)?htmlspecialchars($data['title_english']):""?>" placeholder="Title English">
		<span class="msg" id="title_english_msg"></span>
    </div>
	<div class="div_margin">
		<img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
		<input name="title_zh" id="title_zh" class="span6" value="<?php echo isset($data)?htmlspecialchars($data['title_zh']):""?>" placeholder="Title 中文">
		<span class="msg" id="title_zh_msg"></span>
    </div>
	<div class="div_margin">
		<img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
		<input name="title_hk" id="title_hk" class="span6" value="<?php echo isset($data)?htmlspecialchars($data['title_hk']):""?>" placeholder="Title 繁体">
		<span class="msg" id="title_hk_msg"></span>
    </div>
	<div class="div_margin">
		<img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
		<input name="title_kr" id="title_kr" class="span6" value="<?php echo isset($data)?htmlspecialchars($data['title_kr']):""?>" placeholder="Title 한국어">
		<span class="msg" id="title_hk_msg"></span>
    </div>
    
    
    <div class="div_margin div_content_sty">	     
        <span>english</span>
        <script type="text/plain" id="myEditor" name="english" style="width:1000px;height:240px;">
            
        </script>
    </div>
    <div class="div_margin div_content_sty">	
        <span>中文</span>     
        <script type="text/plain" id="myEditor1" name="zh" style="width:1000px;height:240px;">
            <?php echo isset($data)?($data['zh']):""?>
        </script>
    </div>
    <div class="div_margin div_content_sty">	  
        <span>繁体</span>
        <script type="text/plain" id="myEditor2" name="hk" style="width:1000px;height:240px;">
            <?php echo isset($data)?($data['hk']):""?>
        </script>
    </div>
    <div class="div_margin div_content_sty">	     
        <span>한국어</span>        
        <script type="text/plain" id="myEditor3" name="kr" style="width:1000px;height:240px;">
            <?php echo isset($data)?($data['kr']):""?>
        </script>
    </div>
    
     <!--  
    	<div class="div_margin">	       
            <img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
            <textarea name="english" id="english" placeholder="english" class="bulletin"><?php echo isset($data)?htmlspecialchars($data['english']):""?></textarea>
            <span class="msg" id="english_msg"></span>        
        </div>
       
        <div class="div_margin">
            <img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
            <textarea name="zh" id="zh" placeholder="中文" class="bulletin"><?php echo isset($data)?htmlspecialchars($data['zh']):''?></textarea>
            <span class="msg" id="zh_msg"></span>
        </div>
        
        <div class="div_margin">
            <img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
            <textarea name="hk" id="hk" placeholder="繁体" class="bulletin"><?php echo isset($data)?htmlspecialchars($data['hk']):""?></textarea>
            <span class="msg" id="hk_msg"></span>
        </div>
        
    	<div class="div_margin">
    		<img src="<?php echo base_url('img/new/reg_icon.jpg');?>">
    		<textarea name="kr" id="kr" placeholder="한국어" class="bulletin"><?php echo isset($data)?htmlspecialchars($data['kr']):""?></textarea>
    		<span class="msg" id="hk_msg"></span>
    	</div>
	-->
	
    <div style="margin-left: 19px;">
        <input autocomplete="off" type="text" name="sort" id="sort" placeholder="Sort" value="<?php echo isset($data)?$data['sort']:$sort_nb;  ?>" >
        <span class="msg" id="sort_msg"></span>
    </div>
    
	<div style="margin-left: 19px;" class="modal_main">
		<input autocomplete="off" type="radio" name="permission" id="sort" placeholder="Sort" value="1" <?php echo !isset($data)? 'checked':''?> <?php echo isset($data) && $data['permission']==1 ? 'checked':''?>>
		All
		<input autocomplete="off" type="radio" name="permission" id="sort" placeholder="Sort" value="2" <?php echo isset($data) && $data['permission']==2 ? 'checked':''?>>
		Store Owner
		<input autocomplete="off" type="radio" name="permission" id="sort" placeholder="Sort" value="3" <?php echo isset($data) && $data['permission']==3 ? 'checked':''?>>
		customer
		<span class="msg" id="permission_msg"></span>
	</div>
	
    <div style="margin-left: 19px;">
        <label>
            <input type="checkbox" name="display" id="add_board" <?php echo isset($data)&&$data['display'] ==1 ?'checked':''?>><?php echo lang('display')?>
        </label>
        <label>
            <input type="checkbox" name="important" id="add_board" <?php echo isset($data)&&$data['important'] ==1 ?'checked':''?>><?php echo lang('important_title')?>?
        </label>
    </div>
    
    <input type="hidden" value="<?php echo isset($data)?$data['id']:''?>" id="board_id" name="board_id">
    <button name="add_bulletin_board" type="button" class="btn btn-primary" style="margin-left: 19px;"><?php echo lang('submit');?></button>
    
    </form>





<script>
    $(function(){
        $('button[name="add_bulletin_board"]').click(function(){
            curEle = $(this);

			var tit_english = $("#title_english").val();
			var tit_zh = $("#title_zh").val();
			var tit_hk = $("#title_hk").val();
			var tit_kr = $("#title_kr").val();
			
			var en_content =($.trim(UM.getEditor('myEditor').getPlainTxt()));
			var zh_content =($.trim(UM.getEditor('myEditor1').getPlainTxt()));
			var hk_content =($.trim(UM.getEditor('myEditor2').getPlainTxt()));
			var kr_content =($.trim(UM.getEditor('myEditor3').getPlainTxt()));
						
			if(0 == tit_english.trim().length && 0==tit_zh.trim().length && 0 == tit_hk.trim().length && 0 == tit_kr.trim().length)
			{
				layer.alert('<?php echo $admin_board_title_not_null; ?>', {
		      		  icon: 3,
		      		  skin: 'layer-ext-moon' 
		      	})
				return;
			}
			
			if(0 == en_content.trim().length && 0 == zh_content.trim().length && 0 == hk_content.trim().length && 0 == kr_content.trim().length)
			{
				layer.alert('<?php echo $admin_board_conteng_not_null; ?>', {
		      		  icon: 3,
		      		  skin: 'layer-ext-moon' 
		      	})
				return;
			}
			
            oldSubVal = $('#loadingTxt').val();
            curEle.attr("disabled", true);            
            $.ajax({
                type:'POST',
                url: '/admin/add_bulletin_board/do_add',
                data: $('#add_bulletins').serialize(),
                dataType: "json",
                success: function (data) {
                    
                    if (data.success) {
                      if($('#board_id').val() == ''){
                          window.location.href = '/admin/bulletin_board_list';
                      }else{
                          //window.location.reload();
                    	  window.location.href = '/admin/bulletin_board_list';
                      }
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
</script>

<script>  

	var um_en = UM.getEditor('myEditor').setContent('<?php echo str_replace(array("\n","\r"),"",isset($data)?($data['english']):"");?>');
	var um_zh = UM.getEditor('myEditor1').setContent('<?php echo str_replace(array("\n","\r"),"",isset($data)?($data['zh']):"");?>');
	var um_hk = UM.getEditor('myEditor2').setContent('<?php echo str_replace(array("\n","\r"),"",isset($data)?($data['hk']):"");?>');
	var um1_kr = UM.getEditor('myEditor3').setContent('<?php echo str_replace(array("\n","\r"),"",isset($data)?($data['kr']):"");?>');

</script>

