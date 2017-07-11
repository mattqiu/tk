<link href="<?php echo base_url('themes/admin/umeditor/themes/default/css/umeditor.css?v=1'); ?>" type="text/css" rel="stylesheet">
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.config.js?v=1'); ?>"></script>
<script src="<?php echo base_url('themes/admin/umeditor/umeditor.min.js?v=1'); ?>"></script>
<script src="<?php echo base_url("themes/admin/umeditor/lang/$curLanguage.js?v=1"); ?>"></script>
<script src="<?php echo base_url('ucenter_theme/lib/orgchart/js/jquery.form.js?v=1'); ?>"></script>
<style>
.sub_sku { border:1px solid #ddd; background:#fff; }
.sub_sku td{ margin:10px; vertical-align:top;}
</style>
<form action="" method="post"  class="form-horizontal">
 	<div style="margin-top: 20px;">
        
        <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/brand/brand_list') ?>"><?php echo lang('label_brand_list') ?></a>
    </div>
    <div class="clearfix"></div>

    <input type="hidden" value="<?php echo $is_edit;?>" name="is_edit">
    
    <div style="margin-top: 20px;">
        <input type="hidden" value="<?php echo isset($data['brand_id'])?$data['brand_id']:''?>" name="brand_id">
        <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_cate');?></label>
        <div>
            <select name="cate_id" >     
       		<option value=""><?php echo lang('label_sel_cate');?></option>      
            <?php foreach($category_all as $val) { ?>
                <?php 
                    if($val['parent_id'] == 0) { 
                ?>
                <option <?php if(isset($data['cate_id']) && $val['cate_id'] == $data['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['cate_name']; ?></option>
                <?php  }else { ?>
                <option <?php if(isset($data['cate_id']) && $val['cate_id'] == $data['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['html'],'|-',$val['cate_name']; ?></option>
                <?php  
                    }
                ?>
            <?php  } ?>
        </select> 
        </div>   
    </div>
    <div class="clearfix"></div>      	
    
    <div style="margin-top: 20px;">
        <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_language_all');?></label>
        <div>
            <select name="language_id" > 
        	<option value=""><?php echo lang('label_sel_cate');?></option>
        	<?php 
				foreach($lang_all as $v) {
			?>
						
						<option <?php if(isset($data['language_id']) && $v['language_id'] == $data['language_id']) { ?>selected<?php }?> value="<?php echo $v['language_id']; ?>"><?php echo $v['name']; ?></option>
            <?php 
				}
			?>
        </select>
            
        </div>   
    </div>
    <div class="clearfix"></div>
    
    <div style="margin-top: 20px;">
        <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_brand_name');?></label>
        <div>
            <input type="text" value="<?php echo isset($data['brand_name'])?$data['brand_name']:''?>" placeholder="<?php echo lang('label_brand_name');?>" name="brand_name"  class="input-xxlarge pull-left cate_name" autocomplete="off">
            
        </div>   
    </div>
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
                url: '/admin/brand/do_add',
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
	});
</script>