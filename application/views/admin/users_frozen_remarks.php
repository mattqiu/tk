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
	</head>

	<body>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-offset-1 col-xs-10">					
					<p><br><br></p>
					<table class="table table-hover table-condensed">
					   <tbody>
						<tr>
						  <td><?php echo $job_number; ?></td>
						  <td><?php echo $admin_order_remark; ?></td>
						  <td><?php echo $action; ?></td>
						  <td><?php echo $operator_email; ?></td>
						  <td><?php echo $admin_order_info_create_time; ?></td>
						</tr>
					   <tbody>
						<?php $i=1; foreach($remark_date as $sult) { ?>
						<tr>
						  <td style="width:10%;"><?php echo $i; ?></td>
						  <td ><?php echo $sult['content']; ?></td>
						  <td >
						      <?php if(1==$sult['optiontype']){ ?>
						          <?php echo $account_disable; ?>
						      <?php } else if (2==$sult['optiontype']){ ?>
						          <?php echo $account_reenable; ?>
						      <?php } else { ?>
						          <?php echo $resert_user_status; ?>
						      <?php } ?>
						  </td>
						  <td style="width:20%;"><?php echo $sult['options']; ?></td>
						  <td style="width:20%;"><?php echo $sult['dates']; ?></td>
						</tr>						
						<?php $i++; } ?>
					</table>
					
				</div>
			</div>
		</div>

		<script src="<?php echo base_url('themes/mall/js/jquery.min.js')?>"></script>
		<script src="<?php echo base_url('themes/admin/lib/bootstrap-3.3.5/js/bootstrap.js')?>"></script>
	</body>
</html>
