<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<!-- viewport meta to reset iPhone inital scale -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo base_url('favicon.ico?v=2'); ?>"/>
<link rel="bookmark" href="<?php echo base_url('favicon.ico?v=2'); ?>"/>
<title>TPS</title>
<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/globals.css?v=4'); ?>" />
<link rel="stylesheet" href="<?php echo base_url('css/new/login.css?v=6'); ?>">
<?php if($curLanguage == 'zh' || $curLanguage == 'hk'){ ?>
    <!--针对中文的css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/zh_hk.css?v=1'); ?>" />
<?php }?>
<script type="text/javascript" src="<?php echo base_url('js/new/jquery.min.js?v=2'); ?>"></script>
</head>

<body style="background-color:#f8f8f8;">
<div class="header_bg" style="background:#fff;">
	<div class="pagewrap_top">
    	<div id="header">
            <div class="logo"><a href="<?php echo base_url('/');?>"><img src="<?php echo base_url('img/new/logo.png');?>"></a></div>
            <div class="rigcont">
                <div class="tpli1">
                    <ul>
                        <li><a href="mailto:support@tps138.com">support@tps138.com</a></li>
                        <li style="width:25px;padding-top:3px;height:22px;"><img src="<?php echo base_url('img/new/info.png');?>" width="23" height="17"></li>
                        <!--<li style="text-align:left;width:40px;"></li>-->
                        <li style="width:90px;margin-left:5px; height:18px; border-bottom:0px; background:url(<?php echo base_url('img/new/flag_bg2.png');?>) repeat;">
                            <div class="select_box" style="border:0px;">
                                <div class="open_select_box" style="background:#fff;border:0px;">
                                    <?php if($curLanguage == 'zh'){
                                        $class = 'cny';
                                        $lan = '简体中文';
                                    }elseif($curLanguage == 'hk'){
                                        $class = 'hkd';
                                        $lan = '繁體中文';
                                    }else{
                                        $class = 'gbp';
                                        $lan = 'English';
                                    }
                                    ?>
                                    <span class="currency_icon <?php echo $class;?>" id="ensign"></span><span id="open_select"><?php echo $lan;?></span>
                                </div>
                                <ul class="select_list" style="border:1px solid #004a80;height:75px;background:#D7F2FF;">
                                    <li><span class="currency_icon gbp"></span><span class="currency_type" value="english" style="position:relative;left:-3px;">English</span></li>
                                    <li style="margin-top:0px;"><span class="currency_icon cny"></span><span class="currency_type" value="zh">简体中文</span></li>
                                    <li style="margin-top:0px;"><span class="currency_icon hkd"></span><span class="currency_type" value="hk">繁體中文</span></li>
                                </ul>
                                <input type="hidden"  class="hidden_txt" />
                            </div>
                        </li>
                        <script type="text/javascript">
                            $(function(){
                                
                            //展开或关闭下拉菜单	
                            $("#open_select").click(function(event){	
                                event.stopPropagation();
                                $(".select_list").toggle();	
                            });
                            
                            //离开选择区域后，展开的下拉列表关闭
                            $(document).click(function(event){
                                var eo=$(event.target);
                            if($("#open_select").is(":visible") && eo.attr("class")!="select_list" && !eo.parent(".select_list").length)
                            $('.select_list').hide();
                            
                            });
                            
                            /*获取选中的值*/
                            var $dss=$(".currency_type");
                            $dss.click(function(){
                                $.ajax({
                                    type: "POST",
                                    url: "/common/changeLanguage",
                                    data: {lan: $(this).attr('value')},
                                    dataType: "json",
                                    success: function (res) {
                                        if(res.success){
                                                location.reload();
                                        }
                                    }
                                });
                            });
                            
                            //下拉列表滑过的背景
                            $(".select_list li").hover(function(){
                                    $(this).addClass("h_bg");
                                },function(){
                                    $(this).removeClass("h_bg");		
                                });
                            });
                        </script>
                        <li><img src="<?php echo base_url('img/new/map.png');?>" width="45" height="24"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>