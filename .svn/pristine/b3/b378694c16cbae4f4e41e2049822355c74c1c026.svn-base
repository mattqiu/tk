<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/My97DatePicker/WdatePicker.js?v=1'); ?>"></script>
<style>
.sub_sku { border:1px solid #ddd; background:#fff; }
.sub_sku td{ margin:10px; vertical-align:top;}
</style>
<form action="" method="post"  class="form-horizontal">
	<div style="margin-top: 20px;">

		<a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/ads/ads_list') ?>"><?php echo lang('ads_list') ?></a>
	</div>
	<div class="clearfix"></div>

    <input type="hidden" value="<?php echo $is_edit;?>" name="is_edit">

    <div style="margin-top: 20px;">
        <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_ads_lang');?></label>
        <div>
            <select class="js-supplier" name="language_id">						
                <?php foreach($lang_all as $val) { ?>

                    <option <?php if(isset($data['language_id']) && $val['language_id'] == $data['language_id']) { ?>selected<?php }?> value="<?php echo $val['language_id']; ?>"><?php echo $val['name']; ?></option>

                <?php  } ?>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>

    <div style="margin-top: 20px;" class="main-img">
        <div>
        <label style="display: inline"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_ads_img');?>
        <select class="js-size" style="width:200px">
        	<option value="1920*620">1920*450(pc home)</option>
            <option value="354*500" <?php if(isset($data['location']) && is_numeric($data['location'])) echo 'selected'?>>354*500(pc category)</option>
            <option value="1440*672" <?php if(isset($data['location']) && strpos($data['location'],'ios_index') !== false) echo 'selected'?>>1440*672(ios home)</option>
            <option value="1440*348" <?php if(isset($data['location']) && strpos($data['location'],'ios_cate') !== false) echo 'selected'?>>1440*348(ios category)</option>
            <option value="1440*672" <?php if(isset($data['location']) && strpos($data['location'],'and_index') !== false) echo 'selected'?>>1440*672(andriod home)</option>
             <option value="1440*348" <?php if(isset($data['location']) && strpos($data['location'],'and_cate') !== false) echo 'selected'?>>1440*348(andriod category)</option>
        </select>
        </label>
        
        </div>
        <div>
            <div class="upload_btn" style="margin-top: 20px">
                <span><?php echo lang('label_ads_img');?></span>
                <input class="fileupload" type="file" name="userfile" />
                <input  class="h_main_img" type="hidden" name="ad_img" value="<?php if(isset($data['ad_img'])) echo $data['ad_img'];?>" />

            </div>
            <div class="img_main"  style="margin-top: 10px">
				<?php if(isset($data['ad_img'])) {?>
                <div class="img_item" style="width:80px; margin-right:15px;"><img width="120" data-path="<?php echo $data['ad_img']?>" height="42" src="<?php echo $img_host.$data['ad_img']?>" /><div style="text-align:center"><a href="javascript:;">[-]</a></div>
                </div>
				<?php }?>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
    <div class="clearfix"></div>
    
    <div style="margin-top: 20px;">
        <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_ads_url');?></label>
        <div>
            <input type="text" value="<?php if(isset($data['ad_url'])) echo $data['ad_url']?>"
                placeholder="<?php echo lang('label_ads_url');?>" name="ad_url"  class="input-xxlarge pull-left cate_name" autocomplete="off">

        </div>
    </div>
    <div class="clearfix"></div>
    
    <div style="margin-top: 20px;">
        <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_ads_location');?></label>
        <div>
            <input style="width:200px;" type="text" value="<?php if(isset($data['location'])) echo $data['location']?>"
                placeholder="<?php echo lang('label_ads_location');?>" name="location"  class="input-xxlarge pull-left cate_name" autocomplete="off"><div class="clearfix"></div>
                
                <span style="color:red;">PC</span> // China:index_156 / USA:index_840 / Korea:index_410 / hk:index_344 / other:index_000 // XX
                <br/>
                <span style="color:red;">IOS</span> // China:ios_index_156 / USA:ios_index_840 / Korea:ios_index_410 / hk:ios_index_344 / other:ios_index_000 //ios_cate_000_hot(promote、new、free)
                <br/>
                <span style="color:red;">ANDRIOD</span> //China:and_index_156 / USA:and_index_840 / Korea:and_index_410 / hk:and_index_344 / other:and_index_000 //and_cate_000_hot(promote、new、free)

        </div>
    </div>
    <div class="clearfix"></div>
    
    <div style="margin-top: 20px;">
				<label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_ads_status');?></label>
				<label for="" style="display:inline"><input value="1" <?php if(!isset($data['status'])) echo 'checked="checked"'; ?> <?php if(isset($data['status']) && $data['status']) {?> checked="checked" <?php }?> type="radio" name="status" />Show</label>
                <label for="" style="display:inline"><input value="0" <?php if(isset($data['status']) && !$data['status']) {?> checked="checked" <?php }?> type="radio" name="status" />Hidden</label>
			</div>
	<div class="clearfix"></div>
    
    <div style="margin-top: 20px;">
				<label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_sort');?></label>
				<input autocomplete="off" class="input-mini sort_order js-sort-order" type="text" name="sort_order"  placeholder="" value="<?php echo isset($data['sort_order'])?$data['sort_order']:'99'?>">
			</div>
	<div class="clearfix"></div>


	<div style="margin-top: 30px;">
    	<input type="hidden" value="<?php echo isset($data['ad_id'])?$data['ad_id']:''?>" name="ads_id">
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
				url: '/admin/ads/do_add',
				data: $('form').serialize(),
				dataType: "json",
				success: function (data) {
					if(data.error) {
						$().message(data.msg);
					}else {
						$().message(data.msg,1);
						$('form')[0].reset();

						window.setTimeout(function(){
							window.location.reload();
						},3000);
					}
					$t[0].disabled=false;
				}
			});

		});

		//上传主图片
		var $file_form=$(".fileupload");
		//$btn_file=$('.upload_btn'),
	

		$file_form.wrap('<form class="upload_form" action="" method="post" enctype="multipart/form-data"></form>');

		$file_form.change(function () {
			var  $t=$(this),$btn_file=$t.parents('.upload_btn'),size=$('.js-size').val();

			$t.parents('.upload_form').ajaxSubmit({
				dataType: 'json',
				beforeSend:function(){
					$btn_file.hide();
				},
				url:'/admin/ads/new_pic?size=' + size,
				success: function (data) {
					if(data.success){

						$btn_file.next('.img_main').html('<div class="img_item" style="width:80px; margin-right:15px;"><img width="120" data-path="'+ data.path +'" height="42" src="/'+ data.path + '" /><div style="text-align:center"><a href="javascript:;">[-]</a></div>');
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



		//删除主图片
		$('.img_item  a').live('click',function(){
			var $t=$(this),$item=$t.parents('.img_item'),
			img=$item.find('img').attr('data-path');
			
			$.post("/admin/ads/del_pic",{path:img},function (data) {
				if ($.trim(data) == 'ok') {
					$item.remove();
					$('.h_main_img').val('');
					$('.upload_btn').show();
				} else {
					$().message(data);
				}
			});
		});
		
	});
</script>
