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
	</head>

	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-offset-1 col-xs-10">
					<?php if (isset($err_msg)): ?>
					<p class="text-danger text-center"><?php echo $err_msg; ?></p>
					<?php else: ?>
					<p><br><br></p>
					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-2 control-label text-success"><strong>寄件信息</strong></label>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-success">寄件人姓名</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="sender_name" value="<?php echo $sender_name; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-success">单位名称</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="send_company" value="<?php echo $send_company; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-success">寄件人地址</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="send_address" value="<?php echo $send_address; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-success">邮政编码</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="send_postal" value="<?php echo $send_postal; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-success">联系电话</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="send_phone" value="<?php echo $send_phone; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger"><strong>收件人信息</strong></label>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger">收件人姓名</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="recipient_name" value="<?php echo $recipient_name; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger">目的地城市</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="recipient_city" value="<?php echo $recipient_city; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger">单位名称</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="recipient_company" value="<?php echo $recipient_company; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger">收件人地址</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="recipient_address" value="<?php echo $recipient_address; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger">邮政编码</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="recipient_postal" value="<?php echo $recipient_postal; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger">联系电话</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="recipient_phone" value="<?php echo $recipient_phone; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-danger">备用号码</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="recipient_reserve_num" value="<?php echo $recipient_reserve_num; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-info">品名</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="description" value="<?php echo $description; ?>" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-4">
								<button class="btn btn-success" type="button" onclick="do_print();">打印</button>
							</div>
						</div>
					</form>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php
			// 第一行
			$line1_top = 62;
			$line2_top = 86;
			$line3_top = 114;
			$line4_top = 141;
			$line5_top = 165;

			$l1_left = 30;
			$l2_left = 142;
			$r1_left = 332;
			$r2_left = 475;
		?>
		<div id="print_cont" hidden>
			<!-- 寄件人姓名 -->
			<div id="print_sender_name" style="position: absolute; left: <?php echo $l1_left;?>px; top: <?php echo $line1_top;?>px;"></div>
			<!-- 单位名称 -->
			<div id="print_send_company" style="position: absolute; left: <?php echo $l1_left;?>px; top: <?php echo $line2_top;?>px;"></div>
			<!-- 寄件人地址 -->
			<div id="print_send_address" style="position: absolute; left: <?php echo $l1_left;?>px; top: <?php echo $line3_top;?>px;"></div>
			<!-- 寄件附加语句 -->
			<div style="position: absolute; left: <?php echo $l1_left;?>px; top: <?php echo $line4_top;?>px;">(越区勿转) (请检查包装完好签收)</div>
			<!-- 邮政编码 -->
			<div id="print_send_postal" style="position: absolute; left: <?php echo $l1_left;?>px; top: <?php echo $line5_top;?>px;"></div>
			<!-- 联系电话 -->
			<div id="print_send_phone" style="position: absolute; left: <?php echo $l2_left;?>px; top: <?php echo $line5_top;?>px;"></div>

			<!-- 收件人姓名 -->
			<div id="print_recipient_name" style="position: absolute; left: <?php echo $r1_left;?>px; top: <?php echo $line1_top;?>px;"></div>
			<!-- 目的城市 -->
			<div id="print_recipient_city" style="position: absolute; left: <?php echo $r2_left;?>px; top: <?php echo $line1_top;?>px;"></div>
			<!-- 单位名称-->
			<div id="print_recipient_company" style="position: absolute; left: <?php echo $r1_left;?>px; top: <?php echo $line2_top;?>px; font-size: 75%; width: 280px;"></div>
			<!-- 收件人地址 -->
			<div id="print_recipient_address" style="position: absolute; left: <?php echo $r1_left;?>px; top: <?php echo $line3_top;?>px; width: 280px;"></div>
			<!-- 邮政编码 -->
			<div id="print_recipient_postal" style="position: absolute; left: <?php echo $r1_left;?>px; top: <?php echo $line5_top;?>px; font-size: 5px;"></div>
			<!-- 联系电话 -->
			<div id="print_recipient_phone" style="position: absolute; left: <?php echo $r2_left;?>px; top: <?php echo $line5_top;?>px;"></div>
			<!-- 订单号条形码 -->
			<div  style="position: absolute; left: 280px; top: 185px;">
				<div class="barcodeImg"></div>
			</div>

			<!-- 品名 -->
			<div id="print_description" style="position: absolute; left: 15px; top: 280px;"></div>
			<!-- 寄件日期 -->
			<div id="print_sender_date" style="position: absolute; left: 20px; top: 335px;"><?php echo date("Y-m-d");?></div>
		</div>

		<script src="<?php echo base_url('themes/mall/js/jquery-1.11.1.js')?>"></script>
		<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
		<script src="<?php echo base_url('themes/admin/javascripts/jquery-barcode.js'); ?>"></script>
		<script>
			//生成订单号条形码
			var order_id='<?php echo $order_id;?>';

			$('.barcodeImg').barcode(order_id, "code128",{barWidth:2, barHeight:33,showHRI:false});

			function do_print()
			{
				var sender_name = $('#sender_name').val();
				var send_company = $('#send_company').val();
				var send_address = $('#send_address').val();
				var send_postal = $('#send_postal').val();
				var send_phone = $('#send_phone').val();
				var recipient_name = $('#recipient_name').val();
				var recipient_city = $('#recipient_city').val();
				var recipient_company = $('#recipient_company').val();
				var recipient_address = $('#recipient_address').val();
				var recipient_postal = $('#recipient_reserve_num').val();
				var recipient_phone = $('#recipient_phone').val();
				var description = $('#description').val();

				$('#print_sender_name').text(sender_name);
				$('#print_send_company').text(send_company);
				$('#print_send_address').text(send_address);
				$('#print_send_postal').text(send_postal);
				$('#print_send_phone').text(send_phone);
				$('#print_recipient_name').text(recipient_name);
				$('#print_recipient_city').text(recipient_city);
				$('#print_recipient_company').text(recipient_company);
				$('#print_recipient_address').text(recipient_address);
				$('#print_recipient_postal').text(recipient_postal);
				$('#print_recipient_phone').text(recipient_phone);
				$('#print_description').text(description);

				var headstr = "<html><head><title></title></head><body style=\"font-family: SimHei;\">";
				var footstr = "</body>";
				var newstr = document.all.item('print_cont').innerHTML;
				var oldstr = document.body.innerHTML;
				document.body.innerHTML = headstr + newstr + footstr;
				window.print();
				//console.log(document.body.innerHTML);
				document.body.innerHTML = oldstr;
				return false;
			}
		</script>
	</body>
</html>
