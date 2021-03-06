<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="<?php echo base_url('favicon.ico?v=2');?>"/>
		<link rel="bookmark" href="<?php echo base_url('favicon.ico?v=2');?>"/>
		<title><?php echo lang('sys_supplier_title');?></title>
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="tps-team">

		<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lib/bootstrap/css/bootstrap.css?v=1')?>">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/stylesheets/theme.css?v=9')?>">
		<link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">

		<style type="text/css">
			#line-chart {
				height:300px;
				width:800px;
				margin: 0px auto;
				margin-top: 1em;
			}
			.brand { font-family: georgia, serif; }
			.brand .first {
				color: #ccc;
				font-style: italic;
			}
			.brand .second {
				color: #fff;
				font-weight: bold;
			}
		</style>

		<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/dd.css?v=2'); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/flags.css?v=2'); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/' . $curLanguage . '.css?v=5'); ?>">
		<script src="<?php echo base_url('js/jquery.min.2.js')?>" type="text/javascript"></script>
<!--		<script src="--><?php //echo base_url('themes/admin/lib/jquery-1.8.1.min.js')?><!--" type="text/javascript"></script>-->
		<script src="<?php echo base_url('js/msdropdown/jquery.dd.min.js?v=2'); ?>"></script>
		<script src="<?php echo base_url('js/msdropdown/init.js?v=5'); ?>"></script>
		<script src="<?php echo base_url('themes/admin/javascripts/base.js?v=13'); ?>"></script>
		<script src="<?php echo base_url('themes/admin/javascripts/jquery.message.min.js'); ?>"></script>
        <script src="<?php echo base_url('themes/admin/layer/layer.js?v=1'); ?>"></script>

	</head>

	<body>
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<ul class="nav pull-right">

					<li id="fat-menu" class="dropdown">
						<a href="#" id="drop3" role="button" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-user"></i>
							<?php echo $username?>
							<i class="icon-caret-down"></i>
						</a>

						<ul class="dropdown-menu">
							<li><a tabindex="-1" href="<?php echo base_url('supplier/index/logout')?>">Logout</a></li>
						</ul>
					</li>

					<?php foreach($language_all as $lang) {?>
						<li><a href="Javascript:;" data-title="<?php echo $lang['name']?>" attr_id="<?php echo $lang['language_id']?>" attr_value='<?php echo $lang['code']?>' class="choose_lan <?php echo $curLanguage == $lang['code'] ? 'cur_lan' : '' ?>"><?php echo $lang['name']?></a></li>
					<?php }?>

				</ul>
				<a class="brand" href="<?php echo base_url('supplier/goods/goods_list')?>"><span class="first"><?php echo lang('sys_supplier_title');?></span> <span class="second"></span></a>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="sidebar-nav">
			
					<div class="nav-header" data-toggle="collapse" data-target="#goods-menu"><i class="icon-inbox"></i><?php echo lang('goods_manage'); ?></div>
					<ul id="goods-menu" class="nav nav-list collapse <?php echo in_array($curControlName, array('goods')) ? ' in' : ''; ?>">

						<li class="<?php echo $curControlName == 'goods' ? 'active' : ''; ?>" ><a href="<?php echo base_url('supplier/goods/goods_list'); ?>"><?php echo lang('goods_list'); ?></a></li>
                  
                        
					</ul>
                    
                    
					<div class="nav-header" data-toggle="collapse" data-target="#trade-menu"><i class="icon-list"></i><?php echo lang('admin_trade_title')?></div>
					<ul id="trade-menu" class="nav nav-list collapse <?php echo in_array($curControlName, array('order')) ? ' in' : ''; ?>">

						<li class="<?php echo $curControlName == 'order' ? 'active' : ''; ?>" ><a href="<?php echo base_url('supplier/order/order_list'); ?>"><?php echo lang('admin_trade_order'); ?></a></li>
                        
					</ul>
		
			</div>
		</div>
		<div class="span9">
			<h2 class="page-title"><?php echo $title;?></h2>