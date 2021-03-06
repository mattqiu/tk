<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url('favicon.ico?v=3');?>"/>
    <link rel="bookmark" href="<?php echo base_url('favicon.ico?v=3');?>"/>
    <title><?php echo lang('tps138_admin');?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/lib/bootstrap/css/bootstrap.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('themes/admin/stylesheets/theme.css?v=6')?>">
    <link rel="stylesheet" href="<?php echo base_url('themes/admin/lib/font-awesome/css/font-awesome.css')?>">

      <script src="<?php echo base_url('js/jquery.min.2.js')?>" type="text/javascript"></script>
<!--    <script src="--><?php //echo base_url('themes/admin/lib/jquery-1.8.1.min.js')?><!--" type="text/javascript"></script>-->

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

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/dd.css?v=2'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/msdropdown/flags.css?v=2'); ?>" />
    <script src="<?php echo base_url('js/msdropdown/jquery.dd.min.js?v=2'); ?>"></script>
    <script src="<?php echo base_url('js/msdropdown/init.js?v=6'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/' . $curLanguage . '.css?v=5'); ?>">
    <script src="<?php echo base_url('themes/admin/javascripts/base.js?v=3'); ?>"></script>
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7"> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8"> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9"> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body> 
  <!--<![endif]-->
    
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container-fluid">
                <ul class="nav pull-right">
					<?php foreach($language_all as $lang) {?>
						<li><a href="Javascript:;" data-title="<?php echo $lang['name']?>" attr_id="<?php echo $lang['language_id']?>" attr_value='<?php echo $lang['code']?>' class="choose_lan <?php echo $curLanguage == $lang['code'] ? 'cur_lan' : '' ?>"><?php echo $lang['name']?></a></li>
					<?php }?>
                </ul>
                <a class="brand" href="index.html"><span class="first"><?php echo lang('tps138_admin');?></span> <span class="second"></span></a>
            </div>
        </div>
    </div>