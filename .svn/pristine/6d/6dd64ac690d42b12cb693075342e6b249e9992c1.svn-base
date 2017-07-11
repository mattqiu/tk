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
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lightbox/css/lightbox.css?v=1'); ?>">
        <style type="text/css">
			.example-image{
				width:50rem;
			}
			body,table {
				background: #444 none repeat scroll 0 0;
				padding: 20px 20px 20px 0;

			}
        </style>
	</head>

	<body>
		<?php if(isset($no_data)){?>
			<div style="color: #ff0000;font-weight: bold;font-size: 16px;"><?php echo $no_data?></div>
		<?php }else{?>
		<table>
			<tr>
				<td>
					<div style="color: #ffffff;font-weight: bold;font-size: 16px;">
						身份證明
					</div>
				</td>
			</tr>
			<tr>

				<td>
				<?php if ($card_info['ID_front']){ ?>
					<a data-lightbox="pic-<?php echo $card_info['uid']?>" href="<?php echo config_item('img_server_url').'/'.$card_info['ID_front'] ?>" class="example-image-link" rel="<?php echo htmlspecialchars($card_info['ID_no']); ?>" fullname="<?php echo htmlspecialchars($card_info['name']); ?>">
						<img alt="not exist" src="<?php echo config_item('img_server_url').'/'.$card_info['ID_front'] ?>" class="example-image"></a>
				<?php }?>
				</td>
				<td>
					<?php if ($card_info['ID_reverse']){ ?>
						<a data-lightbox="pic-<?php echo $card_info['uid']?>" href="<?php echo config_item('img_server_url').'/'.$card_info['ID_reverse'] ?>" class="example-image-link" rel="<?php echo htmlspecialchars($card_info['ID_no']); ?>"  fullname="<?php echo htmlspecialchars($card_info['name']); ?>">
							<img alt="not exist" src="<?php echo config_item('img_server_url').'/'.$card_info['ID_reverse'] ?>" class="example-image"></a>
					<?php }?>
				</td>
			</tr>

			<tr>
				<td>
					<div style="color: #ffffff;font-weight: bold;font-size: 16px;">地址證明</div>
				</td>
			</tr>

			<tr>
				<td><?php if ($card_info['address_prove']){ ?>
						<a data-lightbox="pic-<?php echo $card_info['uid']?>" href="<?php echo config_item('img_server_url').'/'.$card_info['address_prove'] ?>" class="example-image-link" rel="<?php //echo $card_info['ID_no']; ?>"  fullname="<?php //echo $card_info['name']; ?>">
							<img alt="not exist" src="<?php echo config_item('img_server_url').'/'.$card_info['address_prove'] ?>" class="example-image"></a>
					<?php }?></td>
			</tr>
		</table>
		<?php }?>
		<script src="<?php echo base_url('themes/mall/js/jquery-1.11.1.js')?>"></script>
		<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
        <script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>
		<script src="<?php echo base_url('themes/admin/lightbox/js/jQueryRotateCompressed.js?v=2'); ?>"></script>
		<script src="<?php echo base_url('themes/admin/lightbox/js/lightbox.js?v=2'); ?>"></script>
        <script>
        $(function(){
			var value2 = 0
			$(".lb-nav").rotate({

				bind:

				{

					click : function() {
						value2 +=90;
						if(value2 > 360){
							value2 = 90;
						}
						//$('#lightbox').css({overflow:"hidden"});
						$(this).prev().rotate({angle:45,animateTo:value2})
						//$(this).parent().parent().rotate({angle: 0,animateTo:value2})
						$('.lb-dataContainer').css({width:'70%'});
					}
				}
			});
		});
        </script>
	</body>
</html>
