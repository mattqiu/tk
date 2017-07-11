<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title><?php echo $title; ?></title>
	<meta name="keywords" content="<?php echo $keywords?>" />
	<meta name="description" content="<?php echo $description?>">
	<meta name="author" content="tps-team">
	<link rel="stylesheet" href="../css/tps-xy.css">
	<link rel="stylesheet" href="../css/h5_base.css">
</head>

<body>
<div class="container">
	<div class="row success">
		<p class="t-c clear"><i class="iconfont">&#xe6b9;</i><span class="cg"><?php echo lang('pay_success_'); ?></span></p>
		<div class="col-xs-12"><a href="<?php echo base_url('respond/do_back');?>"><?php echo lang('back')?></a></div>
	</div>
</div>
</body>

</html>

