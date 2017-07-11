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
    	<?php foreach($lang_list as $key=>$lan) {?>
        <button class="btn <?php if($key == 0) {?>btn-primary<?php }?> btn_tab" type="button" name="" data-id="<?php echo $lan['language_id']?>"><?php echo $lan['name']?></button>&nbsp;&nbsp;&nbsp;
    	<?php } ?>
        
        <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('supplier/goods/goods_list') ?>"><?php echo lang('goods_list') ?></a>
    </div>
    <div class="clearfix"></div>

    <input type="hidden" value="<?php echo $is_edit;?>" name="is_edit">
    
    <ul class="tab_content" style="margin:0;list-style:none;">   
    	<?php foreach($lang_list as $key=>$item) {?>
    	<li style="<?php if($key != 0) {?>display:none;<?php }?>" class="tab_li lang_<?php echo $item['language_id'];?>" data-lang="<?php echo $item['language_id'];?>">    
        
             <div style="margin-top: 20px;">				
				<div>
					<label style="display:inline"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"></label>
                     <label style="display:inline"><input class="language_id" type="checkbox" <?php if($is_edit && isset($data[$item['language_id']]['goods_name'])) {?>checked<?php }?> value="1" name="cate[<?php echo $item['language_id']?>][lang_check][<?php echo $item['language_id']?>]"><?php echo lang('label_goods_display_state');?></label>
				</div>
			</div>
			<div class="clearfix"></div>
            
        	<div style="margin-top: 20px;">
            	<input type="hidden" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['goods_id']:''?>" name="cate[<?php echo $item['language_id']?>][goods_id]">
                <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_cate');?></label>
                <div>
                	<select name="cate[<?php echo $item['language_id']?>][cate_id]">
                    	<option value="0"><?php echo lang('label_sel'); ?></option>
                        <?php foreach($category_all as $val) { ?>
							<?php 
							if($val['language_id'] == $item['language_id']) {
								if($val['parent_id'] == 0) { 
							?>
                            <option <?php if(isset($data[$item['language_id']]) && $val['cate_id'] == $data[$item['language_id']]['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['cate_name']; ?></option>
                            <?php  }else { ?>
                            <option <?php if(isset($data[$item['language_id']]) && $val['cate_id'] == $data[$item['language_id']]['cate_id']) { ?>selected<?php }?> value="<?php echo $val['cate_id']; ?>"><?php echo $val['html'],'|-',$val['cate_name']; ?></option>
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
                <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_sale_country');?></label>
                <div class="sale_country">
                        <?php 
						$sale_country_arr=array();
						if(isset($data[$item['language_id']])) {
							$sale_country_arr=explode('$',$data[$item['language_id']]['sale_country']);
						}
						
						foreach($sale_country_all as $val) { 
						?>
                        	<label style="display:inline"><input <?php if(isset($data[$item['language_id']]) && in_array($val['country_id'],$sale_country_arr)){ echo 'checked';}?> type="checkbox" name="cate[<?php echo $item['language_id']?>][sale_country][]" value="<?php echo $val['country_id']?>" /><?php echo $val['name_'.$curLanguage]?>&nbsp;&nbsp;</label>
                        	
                        <?php  } ?>
                 
                    
                </div>   
            </div>
            <div class="clearfix"></div>
            
             <div style="margin-top: 20px;">
                <label style=""><?php echo lang('label_goods_brand');?></label>
                <div>
                    <select name="cate[<?php echo $item['language_id']?>][brand_id]">
                    	<option value="0"><?php echo lang('label_sel'); ?></option>
                        <?php foreach($brand_all as $val) { 
							if($val['language_id'] == $item['language_id']) {
						?>
                        
                        	<option <?php if(isset($data[$item['language_id']]) && $val['brand_id'] == $data[$item['language_id']]['brand_id']) echo 'selected';?> value="<?php echo $val['brand_id']; ?>"><?php  echo $val['brand_name']; ?></option>
                         <?php  
						 }
						} ?>
                    </select>
                    
                </div>   
            </div>
            <div class="clearfix"></div>
            
            <div style="margin-top: 20px;">
                <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_name');?></label>
                <div>
                    <input type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['goods_name']:''?>"
                           placeholder="<?php echo lang('label_goods_name');?>" name="cate[<?php echo $item['language_id']?>][goods_name]"  class="input-xxlarge pull-left cate_name" autocomplete="off">
                    
                </div>   
            </div>
            <div class="clearfix"></div>       	
            
             <div style="margin-top: 20px;">
                <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_flag');?></label>
                <div>
                   <select name="cate[<?php echo $item['language_id']?>][country_flag]">
                    	<option value=""><?php echo lang('label_sel'); ?></option>
                       
                        	<option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'cn') echo 'selected';?> value="cn"><?php echo lang('label_cn')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'us') echo 'selected';?> value="us"><?php echo lang('label_us')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'hk') echo 'selected';?> value="hk"><?php echo lang('label_hk')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'ne') echo 'selected';?> value="ne"><?php echo lang('label_ne')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'ho') echo 'selected';?> value="ho"><?php echo lang('label_ho')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'as') echo 'selected';?> value="as"><?php echo lang('label_as')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'fr') echo 'selected';?> value="fr"><?php echo lang('label_fr')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'ko') echo 'selected';?> value="ko"><?php echo lang('label_ko')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'tw') echo 'selected';?> value="tw"><?php echo lang('label_tw')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'it') echo 'selected';?> value="it"><?php echo lang('label_it')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'en') echo 'selected';?> value="en"><?php echo lang('label_en')?></option>
                         	<option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'jp') echo 'selected';?> value="jp"><?php echo lang('label_jp')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'in') echo 'selected';?> value="in"><?php echo lang('label_in')?></option>
                            <option <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['country_flag'] == 'sp') echo 'selected';?> value="sp"><?php echo lang('label_sp')?></option>
                    </select>
                    
                </div>   
            </div>
            <div class="clearfix"></div>

			<div style="margin-top: 20px;" class="main-img">
            	<div>
                <label style="display: inline"><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_img');?>                
                </label>
                &nbsp;&nbsp;
                </div>
                <div>
                    <div class="upload_btn" style="margin-top: 20px">
                        <span><?php echo lang('label_goods_img');?></span>
                        <input class="fileupload" type="file" name="userfile" />
                        <input  class="h_main_img" type="hidden" name="cate[<?php echo $item['language_id']?>][goods_img]" value="<?php echo isset($data[$item['language_id']]) ? $data[$item['language_id']]['goods_img'] : '';?>" />
                        
                    </div>
                	<div class="img_main"  style="margin-top: 10px">
                    	<?php if(isset($data[$item['language_id']])) {?>
                        <div class="img_item" style="width:80px; margin-right:15px;"><img width="80" data-path="<?php echo $data[$item['language_id']]['goods_img']?>" height="80" src="/<?php echo $data[$item['language_id']]['goods_img']?>" /><div style="text-align:center"><a href="javascript:;">[-]</a></div>
                        <?php }?>
                    </div>
                    <div class="clearfix"></div>                   
                  
            	</div>
            </div>
            <div class="clearfix"></div>
            
            <div style="margin-top: 20px;">
                <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_main_sn');?></label>
                <div>
                    <input readonly="readonly" type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['goods_sn_main']:$goods_sn_main;?>"
                           placeholder="<?php echo lang('label_goods_main_sn');?>" name="cate[<?php echo $item['language_id']?>][goods_sn_main]"  class="input-xxlarge pull-left goods_main_sn" data-sn="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['goods_sn_main']:$goods_sn_main;?>" autocomplete="off">
                    
                </div>
            </div>
            <div class="clearfix"></div>  
            
            <div style="margin-top: 20px;">
                <label style=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_sku');?></label>
                <div>
                	 <div  style="margin-top: 10px">
                    	<table class="sub_sku" cellpadding="10" cellspacing="10" style="max-width: 98.9%;">
                        	<?php if(isset($data[$item['language_id']])) {?>
                            <?php foreach($data[$item['language_id']]['sub_sn_list'] as $key_sn=>$item_sn) {?>
                            <tr data-lang="<?php echo $item['language_id']?>">
                                <td><?php echo lang('label_sub_sn')?></td>
                                <td><input readonly="readonly" type="text" class="input-small sub_s goods_sn" data-main="<?php echo $item_sn['goods_sn_main'];?>" value="<?php echo $item_sn['goods_sn'];?>" name="cate[<?php echo $item['language_id']?>][goods_sku][<?php echo $key_sn?>][goods_sn]"></td>
                                <td><?php echo lang('label_color')?></td>
                                <td>
                                	<select class="input-small color" name="cate[<?php echo $item['language_id']?>][goods_sku][<?php echo $key_sn?>][color]"> 
                                    	<option value=""><?php echo lang('label_sel'); ?></option>
                                        <?php foreach($color_all as $val) { 
											if($val['language_id'] == $item['language_id']) {
										?>
                                            <option <?php if(isset($data[$item['language_id']]) && $val['attr_values'] == $item_sn['color']) echo 'selected';?> value="<?php echo $val['attr_values']; ?>"><?php  echo $val['attr_values']; ?></option>
                                         <?php  }} ?>
                                    </select>
                                </td>
                                <td><?php echo lang('label_size')?></td>
                                <td>
                                	<select class="input-small size" name="cate[<?php echo $item['language_id']?>][goods_sku][<?php echo $key_sn?>][size]"> 
                                    	<option value=""><?php echo lang('label_sel'); ?></option>
                                        <?php foreach($size_all as $val) { ?>
                                            <option <?php if(isset($data[$item['language_id']]) && $val['attr_values'] == $item_sn['size']) echo 'selected';?> value="<?php echo $val['attr_values']; ?>"><?php  echo $val['attr_values']; ?></option>
                                         <?php  } ?>
                                    </select>
                                </td>
                                <td><?php echo lang('label_customer')?></td>
                                <td>
                                	<input class="input-mini" value="<?php echo $item_sn['customer'];?>" name="cate[<?php echo $item['language_id']?>][goods_sku][<?php echo $key_sn?>][customer]" />
                                	
                                </td>
                                <td><?php echo lang('label_goods_stock')?></td>
                                <td><input type="text" class="input-mini goods_number" value="<?php echo $item_sn['goods_number'];?>" name="cate[<?php echo $item['language_id']?>][goods_sku][<?php echo $key_sn?>][goods_number]"></td>
                                <td style="display: none;"><?php echo lang('label_goods_shop_price')?></td>
                                <td style="display: none;"><input type="text" class="input-mini goods_s_price" value="<?php echo $item_sn['price'];?>" name="cate[<?php echo $item['language_id']?>][goods_sku][<?php echo $key_sn?>][price]"></td>
								<!--?php if($key == 0) {?-->
                                <td><?php echo lang('label_goods_img_gallery')?></td>
                                <td style="position:relative;width:auto; vertical-align:top;">
                                	
                                    <a href="javascript:;" class="add_gall_img" style="text-align:center; font-size:25px;padding:5px;display:block; float:left;">+ </a><input  style="opacity: 0; width:25px; position:absolute; z-index:99;left:10px; top:10px;" class="fileupload_gall_img" type="file" name="userfile" />
                                    <?php foreach($item_sn['imgs'] as $im) {?>
                                    <div class="img_item_gall" style="width:30px; margin:0 5px; float:left;"><img width="30" style="max-height:30px;" data-path="<?php echo $im['thumb_img']?>" height="30" src="/<?php echo $im['thumb_img']?>" /><div style="text-align:center"><a href="javascript:;" data-id="<?php echo $im['img_id']?>">[-]</a></div></div>
                                    <?php }?>
                                </td>
								<!--?php }?-->
                                <td><a href="javascript:;" class="add_sku">[+]</a>&nbsp;&nbsp;
                                <?php if(isset($key_sn) && $key_sn > 0) {?>
                                <a data-sn="<?php echo $item_sn['goods_sn'];?>" data-id="<?php echo $item_sn['product_id'];?>" href="javascript:;" class="reduce_sku">[-]</a>
                                <?php }?>
                                </td>
                            </tr>
                            <?php }?>	
                            <?php }else {?>
                        	<tr data-lang="<?php echo $item['language_id']?>">
                                <td><?php echo lang('label_sub_sn')?></td>
                                <td><input readonly="readonly" type="text" class="input-small sub_s goods_sn" data-main="<?php echo $goods_sn_main;?>" value="<?php echo $goods_sn_main,'-1';?>" name="cate[<?php echo $item['language_id']?>][goods_sku][0][goods_sn]"></td>
                                <td><?php echo lang('label_color')?></td>
                                <td>
                                	<select class="input-small color" name="cate[<?php echo $item['language_id']?>][goods_sku][0][color]"> 
                                    	<option value=""><?php echo lang('label_sel'); ?></option>
                                        <?php foreach($color_all as $val) { 
											if($val['language_id'] == $item['language_id']) {
										?>
                                        	
                                            <option <?php if(isset($data[$item['language_id']]) && $val['attr_values'] == $data[$item['language_id']]['color']) echo 'selected';?> value="<?php echo $val['attr_values']; ?>"><?php  echo $val['attr_values']; ?></option>
                                         <?php  }} ?>
                                    </select>
                                </td>
                                <td><?php echo lang('label_size')?></td>
                                <td>
                                	<select class="input-small size" name="cate[<?php echo $item['language_id']?>][goods_sku][0][size]"> 
                                    	<option value=""><?php echo lang('label_sel'); ?></option>
                                        <?php foreach($size_all as $val) { ?>
                                            <option <?php if(isset($data[$item['language_id']]) && $val['attr_values'] == $data[$item['language_id']]['size']) echo 'selected';?> value="<?php echo $val['attr_values']; ?>"><?php  echo $val['attr_values']; ?></option>
                                         <?php  } ?>
                                    </select>
                                </td>
                                <td><?php echo lang('label_customer')?></td>
                                <td>
                                	<input class="input-mini" value="" name="cate[<?php echo $item['language_id']?>][goods_sku][0][customer]" />
                                	
                                </td>
                                <td><?php echo lang('label_goods_stock')?></td>
                                <td><input type="text" class="input-mini goods_number" value="1000" name="cate[<?php echo $item['language_id']?>][goods_sku][0][goods_number]"></td>
                                <td style="display: none;"><?php echo lang('label_goods_shop_price')?></td>
                                <td style="display: none;"><input type="text" class="input-mini goods_s_price" value="" name="cate[<?php echo $item['language_id']?>][goods_sku][0][price]"></td>
								<!--?php if($key == 0) {?-->
                                <td><?php echo lang('label_goods_img_gallery')?></td>
                                <td style="position:relative;width:auto; vertical-align:top;">
                                	
                                    <a href="javascript:;" class="add_gall_img" style="text-align:center; font-size:25px;padding:5px;display:block; float:left;">+ </a><input  style="opacity: 0; width:25px; position:absolute; z-index:99;left:10px; top:10px;" class="fileupload_gall_img" type="file" name="userfile" />
                                    
                                </td>
								<!--?php }?-->
                                <td>
                                <a href="javascript:;" class="add_sku">[+]</a>&nbsp;&nbsp;
                                
                                <!--a href="javascript:;" class="reduce_sku">[-]</a-->
                                
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                    </div>
                    <div class="clearfix"></div>                   
                </div>   
            </div>
            <div class="clearfix"></div>
            
            <div style="margin-top: 20px;">
                <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_note');?></label>
                <div>
                 <textarea placeholder="<?php echo lang('label_goods_note');?>" class="input-xxlarge" name="cate[<?php echo $item['language_id']?>][seller_note]"><?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['seller_note']:''?></textarea>
                 
                	      
                </div>
            </div>
            <div class="clearfix"></div>
            
            <div style="margin-top: 20px;">
                <label for=""><?php echo lang('label_goods_desc_pic');?></label>
                <div class="detail_img_box" style=" position:relative;height:190px; border:1px solid #ddd; background:#fff;padding:5px;width:98%">
                	
                	<a href="javascript:;" class="add_detail_img" style="text-align:center; font-size:50px;padding:30px;display:block; float:left;">+ </a><input  style="opacity: 0; width:50px; position:absolute; z-index:99;left:20px; top:30px;" class="fileupload_detail_img" type="file" name="userfile1" />
                    <?php if(isset($data[$item['language_id']]) && $data[$item['language_id']]['detail_imgs']) {?>
                    	<?php foreach($data[$item['language_id']]['detail_imgs'] as $d_im) {
							if($item['language_id'] == $d_im['language_id']) {
						?>
                        	<div class="img_item_detail" style="width:80px; margin:0 15px; float:left;"><img width="80" style="max-height:60px;" data-path="<?php echo $d_im['image_url']?>" height="80" src="/<?php echo $d_im['image_url']?>" /><div style="text-align:center"><a href="javascript:;" data-id="<?php echo $d_im['img_id']?>">[-]</a></div></div>
                    	<?php }}?>
                    <?php }?>
                </div>
                <label style="margin-top:15px;" for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_desc');?></label>
                <div class="detail_img_item" style=" height:400px;width:98%;">
               <textarea style="width:100%; height:100%;resize:none;" placeholder="<?php echo lang('label_goods_desc');?>" class="input-xxlarge" name="cate[<?php echo $item['language_id']?>][goods_desc]"><?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['goods_desc']:''?></textarea>	
                
                             
                </div>
            </div>
            <div class="clearfix"></div>        
            
            <div style="margin-top: 20px;">
                <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_weight');?></label>
                <div>
                    <input  type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['goods_weight']:'';?>" placeholder="<?php echo lang('label_goods_weight');?>" name="cate[<?php echo $item['language_id']?>][goods_weight]"  class="input-large pull-left goods-weight" autocomplete="off">
                    
                </div>
            </div>
            <div class="clearfix"></div>
            
            <div style="margin-top: 20px;">
                <label for=""><img src="<?php echo base_url('img/new/reg_icon.jpg');?>"><?php echo lang('label_goods_purchase_price');?></label>
                <div>
                    <input  type="text" value="<?php echo isset($data[$item['language_id']])?$data[$item['language_id']]['purchase_price']:'';?>" placeholder="<?php echo lang('label_goods_purchase_price');?>" name="cate[<?php echo $item['language_id']?>][purchase_price]"  class="input-large pull-left goods-purchaseprice" autocomplete="off">
                    
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
			if($('.language_id:checked').length == 0) {
				$().message('<?php echo lang('info_failed');?>');
				return false;
			}
			
			
			if($('.img_item_gall').length == 0) {
				$().message('<?php echo lang('info_failed');?>');
				return false;
			}
           
			var $t = $(this);
						
			$t[0].disabled=true;
			$.ajax({
                type:'POST',
                url: '/supplier/goods/do_add',
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
		
		//自动8折计算套餐销售价
		/*$('.js-group-id').blur(function() {
			var group_id=parseInt($(this).val());
			
			if(group_id) {
				$.get('/supplier/goods/get_group_sale_price',{group_id:group_id},function(data) {
					$('.goods-shopprice,.goods_s_price').val(data);
				});
			}
		});*/
		
		$('.reduce_sku').click(function() {
			var $t=$(this),product_id=$t.attr('data-id'),goods_sn=$t.attr('data-sn');
			
			if(product_id) {
				$.get('/supplier/goods/del_sub_sn',{product_id:product_id,goods_sn:goods_sn},function(data) {
					if($.trim(data) == 'ok') {
						$t.parents('tr').remove();
					}else {
						$().message(data);
					}
				});
			}else {
				$t.parents('tr').remove();
			}
		});
		
		$('.add_sku').click(function() {
			var $t=$(this),$tr=$t.parents('tr'),$new_sku=$tr.clone(true),index=$tr.index();
			
			$tr.after($new_sku);
			$t.remove();	
			
			//同步其它语言生成子sku
			$tr.parents('.tab_li').siblings().each(function(){
				$(this).find('tr').eq(index).find('.add_sku').trigger('click');	
			});
			
			var $new_sub_s=$new_sku.find('.sub_s'),index=$new_sku.index();
			$new_sub_s.val($new_sub_s.attr('data-main') + '-' +(index+1));
			
			//清理复制出来的子sku
			$new_sku.find('.upload_form').remove();
			$new_sku.find('.img_item_gall').remove();
			
			if($new_sku.find('.fileupload_gall_img').length == 0) {
				$new_sku.find('.add_gall_img').after('<input type="file" name="userfile" class="fileupload_gall_img" style="opacity: 0; width: 25px; position: absolute; z-index: 99; left: 10px; top: 10px; display: block;">');
			}
			
			//更新复制出的tr中表单元素的name
			var lang=$new_sku.attr('data-lang');
			$new_sku.find('.goods_sn').attr('name','cate['+lang+'][goods_sku]['+index+'][goods_sn]');
			$new_sku.find('.color').attr('name','cate['+lang+'][goods_sku]['+index+'][color]');
			$new_sku.find('.size').attr('name','cate['+lang+'][goods_sku]['+index+'][size]');
			$new_sku.find('.goods_number').attr('name','cate['+lang+'][goods_sku]['+index+'][goods_number]');
			$new_sku.find('.goods_s_price').attr('name','cate['+lang+'][goods_sku]['+index+'][price]');
			
		});
		
		//上传主图片
		var $file_form=$(".fileupload"),
		//$btn_file=$('.upload_btn'),
		goods_sn=$('.goods_main_sn:eq(1)').val();
		
		$file_form.wrap('<form class="upload_form" action="" method="post" enctype="multipart/form-data"></form>');
		
		$file_form.change(function () {
			var  $t=$(this),$btn_file=$t.parents('.upload_btn'),
			$is_scale=$t.parents('.main-img').find('.no-scale'),
			is_scale=1;
			
			if($is_scale.is(':checked')) {
				is_scale=0;
			}
			
			$t.parents('.upload_form').ajaxSubmit({
				dataType: 'json', 
				beforeSend:function(){
					$btn_file.hide();
				},
				url:'/supplier/goods/new_pic?type=1&goods_sn='+ goods_sn +'&is_scale='+is_scale,
				success: function (data) { 
					if(data.success){

						$btn_file.next('.img_main').html('<div class="img_item" style="width:80px; margin-right:15px;"><img width="80" data-path="'+ data.path +'" height="80" src="/'+ data.path + '" /><div style="text-align:center"><a href="javascript:;">[-]</a></div>');
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
		
		$('.no-scale').click(function() {
			var is_checked=$(this).is(':checked');
			
			$('.no-scale').each(function(){
				$(this)[0].checked=is_checked;
			});
		});
		
		//删除主图片
		$('.img_item  a').live('click',function(){
			var $t=$(this),$item=$t.parents('.img_item'),
			img=$item.find('img').attr('data-path'),
			$tab_li=$t.parents('.tab_li');
			
			$.post("/supplier/goods/del_pic", {path: img,type:1}, function (data) {
				if ($.trim(data) == 'ok') {
					$item.remove();
					$tab_li.find('.h_main_img').val('');
					$tab_li.find('.upload_btn').show();
				} else {
					$().message(data);
				}
			});
		});
		
		//上传详情图片
		var is_requested=false;
		$('.fileupload_detail_img').wrap('<form class="upload_form" action="" method="post" enctype="multipart/form-data"></form>').click(function(){
			var $t=$(this),
			lang=$t.parents('.tab_li').attr('data-lang');
			$t.change(function () { 
				if(!is_requested) {
					$(this).parents('.upload_form').ajaxSubmit({
						dataType: 'json', 
						beforeSend:function(){
							//$t.hide();
							is_requested=true;
						},
						url:'/supplier/goods/new_pic?type=3&input_name=userfile1&goods_sn='+ goods_sn +'&lang_id='+lang,
						success: function (data) { 
							if(data.success){
		
								$t.parents('.detail_img_box').append('<div class="img_item_detail" style="width:80px; margin:0 15px; float:left;"><img width="80" style="max-height:60px;" data-path="'+ data.path +'" height="80" src="/'+ data.path + '" /><div style="text-align:center"><a href="javascript:;" data-id="'+data.img_id+'">[-]</a></div>');				
								$t.show();
								
								is_requested=false;
						   }else{
								//$t.hide();
								$().message(data.upload_data); //返回失败信息
								is_requested=false;
							}
						},
						error: function (xhr) { //上传失败
							if(xhr.responseText){
								//$t.hide();
							}
						}
					});
				}
			});
		});
		
		//删除详情图
		$('.img_item_detail  a').live('click',function(){
			var $t=$(this),
			$item=$t.parents('.img_item_detail'),
			img=$item.find('img').attr('data-path'),
			img_id=$t.attr('data-id');
			
			$.post("/supplier/goods/del_pic", {path: img,type:3,img_id:img_id}, function (data) {
				if ($.trim(data) == 'ok') {
					$item.remove();
				} else {
					$().message(data);
				}
			});
		});
		
		//上传相册图
		var is_requested1=false;		
		$('.fileupload_gall_img').live('click',function(){
			var $t=$(this);
			var goods_sn=$t.parents('tr').find('.sub_s').val();
			
			if(!$t.parent().is('form')) {
				$t.wrap('<form style="float:left;" class="upload_form" action="/supplier/goods/new_pic?type=2&input_name=userfile&goods_sn='+ goods_sn+'" method="post" enctype="multipart/form-data"></form>');
			}
			
			$t.change(function () { 
				if(!is_requested1) {
					
					var $td=$t.parents('td');
					
					$t.parents('.upload_form').ajaxSubmit({
						dataType: 'json', 
						beforeSend:function(){
							//$t.hide();
							
							is_requested1=true;
						},
						success: function (data) { 
							if(data.success){
		
								$td.append('<div class="img_item_gall" style="width:30px; margin:0 5px; float:left;"><img width="30" style="max-height:30px;" data-path="'+ data.path +'" height="30" src="/'+ data.path + '" /><div style="text-align:center"><a href="javascript:;" data-id="'+data.img_id+'">[-]</a></div>');				
								$t.show();
								
								is_requested1=false;
						   }else{
								//$t.hide();
								$().message(data.upload_data); //返回失败信息
								is_requested1=false;
							}
						},
						error: function (xhr) { //上传失败
							if(xhr.responseText){
								//$t.hide();
							}
						}
					});
				}
			});
		});
		
		//删除相册图
		$('.img_item_gall  a').live('click',function(){
			var $t=$(this),
			$item=$t.parents('.img_item_gall'),
			img_id=$t.attr('data-id');
			
			$.post("/supplier/goods/del_pic", {type:2,img_id:img_id}, function (data) {
				if ($.trim(data) == 'ok') {
					$item.remove();
				} else {
					$().message(data);
				}
			});
		});
		
		//自动填充数据
		$('.goods-marketprice').blur(function(){
			var price=$(this).val();
			$('.goods-marketprice').val(price);
		});
		$('.goods-shopprice').blur(function(){
			var price=$(this).val();
			$('.goods-shopprice').val(price);
		});
		$('.goods-weight').blur(function(){
			var weight=$(this).val();
			$('.goods-weight').val(weight);
		});
		$('.goods-purchaseprice').blur(function(){
			var price=$(this).val();
			$('.goods-purchaseprice').val(price);
		});
		$('.js-gift-skus').blur(function(){
			var skus=$(this).val();
			$('.js-gift-skus').val(skus);
		});
		$('.js-sort-order').blur(function(){
			var order=$(this).val();
			$('.js-sort-order').val(order);
		});
		$('.js-supplier').change(function(){
			var id=$(this).val();
			$('.js-supplier').val(id);
		});
		
		//seo title和名称同步
		$('.cate_name').blur(function(){
			var $t=$(this),$title=$t.val();
			
			$t.parents('.tab_li').find('.meta_title').val($title);
		});
		
		//同步状态checkbox
		$('.check_status input').click(function(){
			var $t=$(this);
			
			var is_checked=false;
			if($t.is(":checked")) {
				is_checked=true;
			}
			
			var index=$t.parent().index();
			
			$('.check_status').find('label').each(function(){
				var $this=$(this);
				if($this.index() == index) {
				   $this.find('input')[0].checked=is_checked;
				}
				
				
			});
			
		});
		
		//同步销售国家
		$('.sale_country input').click(function(){
			var $t=$(this);
			
			var is_checked=false;
			if($t.is(":checked")) {
				is_checked=true;
			}
			
			var index=$t.parent().index();
			
			$('.sale_country').find('label').each(function(){
				var $this=$(this);
				if($this.index() == index) {
				   $this.find('input')[0].checked=is_checked;
				}
				
				
			});
			
		});
		
		//同步仓库
		$('.js-store input').click(function(){
			var $t=$(this);
			
			var is_checked=false;
			if($t.is(":checked")) {
				is_checked=true;
			}
			
			var index=$t.parent().index();
			
			$('.js-store').find('label').each(function(){
				var $this=$(this);
				if($this.index() == index) {
				   $this.find('input')[0].checked=is_checked;
				}
				
				
			});
			
		});
		
    });

</script>
