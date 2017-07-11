<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo lang('tps138_admin');?></title>
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/css/bootstrap.css')?>">
		<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">
        <style type="text/css">
        .txt{ margin:20px 0 0 150px; font-size:20px;}
		.txt .input-xxlarge{ width:400px; margin:0 15px;}		
        </style>
	</head>

	<body>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-offset-1 col-xs-10">
                	<form class="f_order_id">
                    <div class="txt"><?php echo lang('shipping_com'); ?>:
                    <select class="shipping_com">
                    	<?php foreach($freight_map as $k=>$map) {?>
                    	<option value="<?php echo $k;?>"><?php echo $map;?></option>
                        <?php } ?>
                    </select>
                    </div>
                    
                	<div class="txt"><?php echo lang('admin_scan_order_id'); ?>:<input class="input-xxlarge order_id"  type="text"  placeholder="<?php echo lang('admin_scan_order_id'); ?>" /><input type="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" name="doc_submit"></div>
                    
                    </form>
                    <form class="f_track_id">
                    <div class="txt"><?php echo lang('admin_scan_track_id'); ?>:<input class="input-xxlarge track_id"  type="text"  placeholder="<?php echo lang('admin_scan_track_id'); ?>" /><input type="submit" value="<?php echo lang('submit');?>" class="btn btn-primary" name="doc_submit"></div>
                   
                    </form>
					<div>
                    <iframe width="100%" height="600" frameborder="0" scrolling="yes" src="" style="display: inline;"></iframe>
                    </div>
				</div>
			</div>
		</div>

		<script src="<?php echo base_url('themes/mall/js/jquery-1.11.1.js')?>"></script>
		<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
        <script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
        <script>
        $(function(){
			$('.order_id').focus();
			
			var url='<?php echo base_url('supplier/order/get_order_info'); ?>';
			$('.f_order_id').submit(function() {
				//检测订单是否已经存在物流单号				
				var data = {},

				$order_id=$('.order_id');
			
				data.order_id = $order_id.val();		
				
				$.ajax({
					url: '/supplier/order/check_order_shipping_status',
					type: "POST",
					data: data,
					dataType: "json",
					success: function(data) {
						if (data.code == 0) {
							$('iframe').attr('src',url + '/' + $('.order_id').val());
							$('.track_id').focus();
						} else {
							$('iframe').attr('src','');
							layer.msg("This Order has been shipped.");
						}
					},
					error: function(data) {
						//console.log(data.responseText);
					}
				});				
				
				return false;
			});
			
			$('.f_track_id').submit(function(){
				submit_deliver();
				
				return false;
			});
			
			function submit_deliver()
			{
				var data = {},
				$ifram=$('iframe'),
				$order_id=$('.order_id'),
				$track_id=$('.track_id');
			
				data.order_id = $order_id.val();
				data.company_code = $('.shipping_com').val(); //物流编号
				data.express_id = $track_id.val();
			
				//console.log(data);
				if($.trim(data.order_id) == '' &&　$.trim(data.express_id) == '') {
					layer.msg("Error");
					return ;
				}
				
				$.ajax({
					url: '/supplier/order/do_order_deliver',
					type: "POST",
					data: data,
					dataType: "json",
					success: function(data) {
						if (data.code == 0) {
							layer.msg("Status changed.");
							
							$ifram.attr('src','');
							$order_id.val('').focus();
							$track_id.val('');
						} else {
							layer.msg("system error");
						}
					},
					error: function(data) {
						//console.log(data.responseText);
					}
				});
			}
		});
        </script>
	</body>
</html>
