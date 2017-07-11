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

    <input type="hidden" value="<?php echo $is_edit;?>" name="is_edit" class="is_edit">	
    <input type="hidden" value="<?php echo isset($data['group_id']) ? $data['group_id'] : 0;?>"  class="group_id">	        
    <div style="margin-top: 20px;">
        <label style=""><?php echo lang('label_goods_group_search');?></label>
        <div>
            <input type="text" value="" placeholder="<?php echo lang('label_goods_name'),'/',lang('label_goods_name_cn'),'/',lang('label_goods_main_sn');?>"  class="input-xxlarge pull-left goods_name" autocomplete="off">&nbsp;&nbsp;
            <input name="doc_submit" type="button" class="btn btn-primary ok" value="<?php echo lang('label_goods_group_ok');?>" />
             <a style="float:right;" class="btn" target="_self" href="<?php echo base_url('admin/goods_group/goods_group_list') ?>"><?php echo lang('goods_group_list') ?></a>
        </div>   
    </div>
    <div class="clearfix"></div>   
    
    <div  style="margin-top: 20px; background:#fff;  padding:10px; min-height:196px;" class="goods_list clearfix">
         
    </div>
    <div class="clearfix"></div>

     <div  style="margin-top: 20px; background:#fff;  padding:10px; min-height:196px;" class="goods_list_1 clearfix">
       <?php if(isset($data['goods_list'])) {?>
       <?php foreach($data['goods_list']['list'] as $g) {?>
       	<div style="padding:5px; border:1px solid #ddd; float:left; margin-right:10px;" class="goods_l"><a target="_blank" href="<?php echo base_url(),"index/product?snm=",$g['info']['goods_sn_main']?>"><img width="92" height="82" src="<?php echo $img_host.$g['info']['goods_img']?>" title="<?php echo $g['info']['goods_name']?>"></a><p><?php echo $g['info']['goods_sn_main']?><br><?php echo '$',$g['info']['shop_price']?><br><input checked="checked" type="checkbox" value="1" data-id="<?php echo $g['info']['goods_sn_main']?>" class="ck_good"></p><p>总件数:<br><input type="text" style="width:50px;" value="<?php echo $g['num']?>" class="ck_num"></p></div>
       <?php }}?>
    </div>
    <div class="clearfix"></div>
    
    <div style="margin-top: 30px;">
    	<input name="doc_submit" type="button" class="btn btn-primary doc_submit" value="<?php echo lang('submit');?>" />
    </div>
</form>

<script>
    $(function(){
		$('.ok').click(function() {
			var keyword=$('.goods_name').val(),$target=$('.goods_list');
			if($.trim(keyword) == '') {
				return;
			}
			$target.html('');
			$.getJSON('/admin/goods_group/search_goods',{keyword:keyword},function(data){
				if(data.length > 0) {
					$.each(data,function(i,v) {
						var html='<div class="goods_l" style="padding:5px; border:1px solid #ddd; float:left; margin-right:10px;">';
						html +='<a href="<?php echo $img_host,"index/product?snm="?>' + v.goods_sn_main+ '" target="_blank"><img width="92" height="82" title="'+v.goods_name+'" src="<?php echo $img_host?>' + v.goods_img+ '" /></a>';
                    	html +='<p>'+v.goods_sn_main+'<br />$'+v.shop_price+'<br/><input class="ck_goods" data-id="'+v.goods_sn_main+'" type="checkbox" value="1" /></p></div>';
						$target.append(html);
					});
				}else {
					$().message('No Result.');
				}
			});
		});
		
		$('.ck_goods').live('click',function() {
			var $t=$(this),html=$t.parents('.goods_l').clone(false);
			html.find('.ck_goods').attr('class','ck_good');
			html.append('<p><?php echo lang('label_goods_group_num')?>:<br /><input class="ck_num" type="text" value="1" style="width:50px;" /></p>');
			$('.goods_list_1').append(html);
		});
		
		$('.doc_submit').click(function(){
			var id=$('.group_id').val(),
				is_edit=$('.is_edit').val(),
				$box=$('.goods_list_1'),
				$list=$box.find('.goods_l'),
				goods_str='';
				
			$list.each(function() {
				var $t=$(this),$check_box=$t.find('.ck_good'),id=$check_box.attr('data-id'),num=$t.find('.ck_num').val();
				
				if($check_box.is(':checked')) {
					goods_str+=id+'*'+num+'|';
				}
				
			});
			$.get('/admin/goods_group/do_add',{is_edit:is_edit,id:id,ids:goods_str},function(data){
				if($.trim(data) == 'ok') {
					$().message('<?php echo lang('info_success')?>',1);
					window.setTimeout(function(){
							window.location.reload();
					},3000);
				}else {
					$().message('<?php echo lang('info_error')?>');
				}
			});
		});
	});
</script>