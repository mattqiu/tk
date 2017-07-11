<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>

<form action="" method="post"  class="form-horizontal">
 	<div style="margin-top: 20px;">
    	<?php foreach($lang_list as $key=>$lan) {?>
        <button class="btn <?php if($key == 0) {?>btn-primary<?php }?> btn_tab" type="button" name="" data-id="<?php echo $lan['language_id']?>"><?php echo $lan['name']?></button>&nbsp;&nbsp;&nbsp;
    	<?php } ?>
        
        <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/category/category_list') ?>"><?php echo lang('category_list') ?></a>
    </div>
    <div class="clearfix"></div>

    <input type="hidden" value="<?php echo $is_edit;?>" name="is_edit">
    
    <ul class="tab_content" style="margin:0;list-style:none;">   
    	<?php foreach($lang_list as $key=>$item) {?>
    	<li style="<?php if($key != 0) {?>display:none;<?php }?>" class="lang_<?php echo $item['language_id'];?>"> 
        	<div style="margin-top: 20px;">
                <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_cate_parent');?></label>
                <div>
                	<select name="cate[<?php echo $item['language_id']?>][parent_id]" <?php if(isset($data[$item['language_id']]) &&  $data[$item['language_id']]['parent_id'] ==0) { echo 'disabled';}?>>
                    	<option value="0"><?php echo lang('label_cate_top'); ?></option>
                        <?php foreach($category_all as $val) { ?>
							<?php 
							if($val['language_id'] == $item['language_id']) {
								if($val['parent_id'] == 0) { 
							?>
                            <option <?php if(isset($data[$item['language_id']]) && $val['cate_id'] == $data[$item['language_id']]['parent_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['cate_name'],'/',$val['cate_desc']; ?></option>
                            <?php  }else { ?>
                            <option <?php if(isset($data[$item['language_id']]) && $val['cate_id'] == $data[$item['language_id']]['parent_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['html'],'|-',$val['cate_name'],'/',$val['cate_desc']; ?></option>
                            <?php  
								}
							} 
							?>
                        <?php  } ?>
                    </select>           
                </div>   
            </div>
            <div class="clearfix"></div>
            
         	<div style="margin-top: 20px;">
                <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_cate_name');?></label>
                <div>
                    <input type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['cate_name']:''?>"
                           placeholder="<?php echo lang('label_cate_name');?>" name="cate[<?php echo $item['language_id']?>][cate_name]"  class="input-xxlarge pull-left cate_name" autocomplete="off">
                    
                </div>   
            </div>
            <div class="clearfix"></div>
            
             <div style="margin-top: 20px;">
                <label for=""><?php echo lang('label_cate_desc');?></label>
                <div class="">
                    <textarea placeholder="<?php echo lang('label_cate_desc');?>" class="input-xxlarge" name="cate[<?php echo $item['language_id']?>][cate_desc]"><?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['cate_desc']:''?></textarea>
                </div>
            </div>
            <div class="clearfix"></div>             
     
            <div style="margin-top: 20px;">
                <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_cate_sn');?></label>
                <div>
                    <input readonly="readonly" type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['cate_sn']:$cate_sn;?>"
                           placeholder="<?php echo lang('label_cate_sn');?>" name="cate[<?php echo $item['language_id']?>][cate_sn]"  class="input-xxlarge pull-left" autocomplete="off">
                    
                </div>
            </div>
            <div class="clearfix"></div>  
            
            <div style="margin-top: 20px;">
                <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_cate_meta_title');?></label>
                <div>
                    <input type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['meta_title']:''?>"
                           placeholder="<?php echo lang('label_cate_meta_title');?>" name="cate[<?php echo $item['language_id']?>][meta_title]"  class="input-xxlarge pull-left meta_title" autocomplete="off">
                    
                </div>
            </div>
            <div class="clearfix"></div> 
            
            <div style="margin-top: 20px;">
                <label for=""><?php echo lang('label_cate_meta_keywords');?></label>
                <div>
                    <input type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['meta_keywords']:''?>"
                           placeholder="<?php echo lang('label_cate_meta_keywords');?>" name="cate[<?php echo $item['language_id']?>][meta_keywords]"  class="input-xxlarge pull-left" autocomplete="off">
                   
                </div>
            </div>
            <div class="clearfix"></div> 
            
            <div style="margin-top: 20px;">
                <label for=""><?php echo lang('label_cate_meta_desc');?></label>
                <div class="">
                    <textarea placeholder="<?php echo lang('label_cate_meta_desc');?>" class="input-xxlarge" name="cate[<?php echo $item['language_id']?>][meta_desc]"><?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['meta_desc']:''?></textarea>
                </div>
            </div>
            <div class="clearfix"></div>        
            
            <div style="margin-top: 20px;">
				<label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_ads_status');?></label>
				<label for="" style="display:inline"><input value="1" <?php if(!isset($data[$item['language_id']]['status'])) echo 'checked="checked"'; ?> <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['status']) {?> checked="checked" <?php }?> type="radio" name="cate[<?php echo $item['language_id']?>][status]" />Show</label>
                <label for="" style="display:inline"><input value="0" <?php if(isset($data[$item['language_id']]['status']) && !$data[$item['language_id']]['status']) {?> checked="checked" <?php }?> type="radio" name="cate[<?php echo $item['language_id']?>][status]" />Hidden</label>
			</div>
			<div class="clearfix"></div>    
            
            <div style="margin-top: 20px;">
                <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_cate_sort');?></label>
                <input autocomplete="off" class="input-mini sort_order" type="text" name="cate[<?php echo $item['language_id']?>][sort_order]"  placeholder="" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['sort_order']:'99'?>">
                
            </div>
            <div class="clearfix"></div>        
            
            <!--
            <div style="margin-top: 20px;">
                <label for=""><?php echo lang('label_cate_icon');?></label>
                <div>
                    <input type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['cate_img']:''?>"
                           placeholder="<?php echo lang('label_cate_icon');?>" name="cate[<?php echo $item['language_id']?>][cate_img]"  class="input-xxlarge pull-left" autocomplete="off">
                    
                </div>
            </div>
            <div class="clearfix"></div> 
            -->
            
            <div>
					<div class="upload_btn" style="margin-top: 20px">
						<span><?php echo lang('label_cate_icon');?></span>
						<input class="fileupload" type="file" name="userfile" />
						<input  class="h_main_img" type="hidden" name="cate[<?php echo $item['language_id']?>][cate_img]" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['cate_img']:''?>" />

					</div>
					<div class="img_main"  style="margin-top: 10px">
						<?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['cate_img']) {?>
						<div class="img_item" style="width:80px; margin-right:15px;"><img width="50" data-path="<?php echo $data[$item['language_id']]['cate_img']?>" height="50" src="<?php echo $img_host.$data[$item['language_id']]['cate_img']?>" /><div style="text-align:center"></div>
						<?php }?>
					</div>
					<div class="clearfix"></div>
			</div>
            <div class="clearfix"></div>          
            
        </li>
        <?php }?>
    </ul>
    
    <div class="clearfix"></div>
    <div style="margin-top: 30px;">
    	<input name="doc_submit" type="button" class="btn btn-primary" value="<?php echo lang('submit');?>" />
    </div>
</form>


<script>
    $(function(){
        $('input[name=doc_submit]').click(function(){
           
			var $t = $(this);
						
			$t[0].disabled=true;
			$.ajax({
                type:'POST',
                url: '/admin/category/do_add',
                data: $('form').serialize(),
                dataType: "json",
                success: function (data) {
                    if(data.error) {
						$().message(data.msg);						
					}else {
						$().message(data.msg,1);
						$('form')[0].reset();
						
						window.location.reload();
					}
					$t[0].disabled=false;
                }
            });
			
        });
		
		//上传主图片
		var $file_form=$(".fileupload");

		$file_form.wrap('<form class="upload_form" action="" method="post" enctype="multipart/form-data"></form>');

		$file_form.change(function () {
			var  $t=$(this),$btn_file=$t.parents('.upload_btn');

			$t.parents('.upload_form').ajaxSubmit({
				dataType: 'json',
				beforeSend:function(){
					$btn_file.hide();
				},
				url:'/admin/category/new_pic',
				success: function (data) {
					if(data.success){

						$btn_file.next('.img_main').html('<div class="img_item" style="width:50px; margin-right:15px;"><img width="50" data-path="'+ data.path +'" height="80" src="/'+ data.path + '" /><div style="text-align:center"></div>');
						$btn_file.find('.h_main_img').val(data.path);
					}else{
						$btn_file.show();
						$().message(data.upload_data); //返回失败信息
					}
				},
				error: function (xhr) {
					if(xhr.responseText){
						$btn_file.show();
					}
				}
			});
		});
		
    });

</script>
