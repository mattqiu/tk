<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- viewport meta to reset iPhone inital scale -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.3, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="<?php echo base_url('favicon.ico?v=3');?>"/>
    <link rel="bookmark" href="<?php echo base_url('favicon.ico?v=3');?>"/>
    <title>TPS</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/globals.css?v=4'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/everypage.css?v=2'); ?>" />
    <?php if($curLanguage == 'zh' || $curLanguage == 'hk'){ ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/new/zh_hk.css?v=1'); ?>" />
    <?php }?>
    <script src="<?php echo base_url('js/new/jquery.min.js?v=2'); ?>"></script>
    <script src="<?php echo base_url('js/new/work.js?v=2'); ?>"></script>
    <script src="<?php echo base_url('js/new/slides.min.jquery.js?v=2'); ?>"></script>
    <script>
        $(function(){
            $('#slides').slides({
                preload: true,
                preloadImage: 'img/new/loading.gif',
                play: 3000,
                pause: 2500,
                hoverPause: true
            });
        });
    </script>
    <script type="text/javascript">
        $(function() {
            var sWidth = $("#focus").width(); //获取焦点图的宽度（显示面积）
            var len = $("#focus ul li").length; //获取焦点图个数
            var index = 0;
            var picTimer;
            var btn = "<div class='btnBg'></div><div class='btn'>";
            for(var i=0; i < len; i++) {
                btn += "<span></span>";
            }
            btn += "</div><div class='preNext pre'></div><div class='preNext next'></div>";
            $("#focus").append(btn);
            $("#focus .btnBg").css("opacity",0.5);
            $("#focus .btn span").mouseenter(function() {
                index = $("#focus .btn span").index(this);
                showPics(index);
            }).eq(0).trigger("mouseenter");
            $("#focus .pre").css("opacity",1).hover(function() {
                $(this).stop(true,false).addClass("hover_pre");
            },function() {
                $(this).stop(true,false).removeClass("hover_pre");
            });
            $("#focus .next").css("opacity",1).hover(function() {
                $(this).stop(true,false).addClass("hover_next");
            },function() {
                $(this).stop(true,false).removeClass("hover_next");
            });
            $("#focus .pre").click(function() {
                index -= 1;
                if(index == -1) {index = len - 1;}
                showPics(index);
            });
            $("#focus .next").click(function() {
                index += 1;
                if(index == len) {index = 0;}
                showPics(index);
            });
            $("#focus ul").css("width",sWidth * (len));
            $("#focus").hover(function() {
                clearInterval(picTimer);
            },function() {
                picTimer = setInterval(function() {
                    showPics(index);
                    index++;
                    if(index == len) {index = 0;}
                },4000);
            }).trigger("mouseleave");
            function showPics(index) {
                var nowLeft = -index*sWidth;
                $("#focus ul").stop(true,false).animate({"left":nowLeft},300);
            }
        });
    </script>

</head>

<body style="background-color:#f8f8f8;">
<div class="header_bg">
    <div class="pagewrap_top">
        <div id="header">
            <div class="logo"><a href="<?php echo base_url('/')?>"><img src="<?php echo base_url('img/new/logo.png');?>"></a></div>
            <a target="_blank" href="<?php echo base_url('new_to_tps');?>">
            <div class="text1">
                <ul>
                    <li><strong><?php echo lang('con_network');?></strong></li>
                    <li><?php echo lang('google_plus');?></li>
                </ul>
            </div>
            <div class="cenimg"><img src="<?php echo base_url('img/new/conn_img.png');?>" width="136" height="80"></div>
            </a>
            <div class="rigcont">
                <div class="tpli1">
                    <ul>
                        <li><a href="mailto:support@tps138.com">support@tps138.com</a></li>
                        <li style="width:25px;padding-top:3px;height:22px;"><img src="<?php echo base_url('img/new/info.png');?>" width="23" height="17"></li>
                        <!--<li style="text-align:left;width:20px;"></li>-->
                        <li style="width:90px;margin-left:5px; height:18px;">
                            <div class="select_box">
                                <div class="open_select_box">
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
                                    <span class="currency_icon <?php echo $class;?>" id="ensign"></span><span id="open_select" style="color:#999;"><?php echo $lan;?></span>
                                </div>
                                <ul class="select_list" style="height:75px;">
                                    <li><span class="currency_icon gbp"></span><span class="currency_type" value="english" style="position:relative;left:-3px;">English</span></li>
                                    <li style="margin-top:0px;"><span class="currency_icon cny"></span><span class="currency_type" value="zh">简体中文</span></li>
                                    <li style="margin-top:0px;"><span class="currency_icon hkd"></span><span class="currency_type" value="hk">繁體中文</span></li>
                                </ul>
                                <input type="hidden"  class="hidden_txt" />
                            </div>
                        </li>
                        <script type="text/javascript">
                            $(function(){

                                $("#open_select").click(function(event){
                                    event.stopPropagation();
                                    $(".select_list").toggle();
                                });

                                $(document).click(function(event){
                                    var eo=$(event.target);
                                    if($("#open_select").is(":visible") && eo.attr("class")!="select_list" && !eo.parent(".select_list").length)
                                        $('.select_list').hide();
                                });

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
                <div class="tpli2">
                    <ul>
                        <?php if (!$userInfo) { ?>
                            <li><a href="<?php echo base_url('login') ?>" style="color:#426ba7;"><?php echo lang('nav_login') ?></a>
                                &nbsp;|&nbsp;
                                    <a href="<?php echo base_url('register') ?>" style="color:#426ba7;"><?php echo lang('nav_register') ?></a></li>

                        <?php }else{?>
                            <li>
                                <a href="<?php echo base_url('ucenter'); ?>" style="color:#426ba7;"><?php echo lang('my_account'); ?></a>
                                |
                                <a href="<?php echo base_url('login/logout'); ?>" style="color:#426ba7;"><?php echo lang('logout'); ?></a>
                            </li>
                        <?php }?>
                        <li><a href="#"><?php echo lang('');?></a></li>
                        <li><a href="<?php echo base_url('contact');?>"><?php echo lang('nav_contact_us');?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="nav">
    <div id="nav_pagewrap">
        <ul id="navul">
            <li style="margin-left:-40px;" class="<?php echo $curControlName=='welcome'?'cur':''?>"><a href="<?php echo base_url('/'); ?>" target=""><?php echo lang('nav_index');?></a></li>
            <li class="<?php $cur = array('why_tps','advantage','plan'); if(in_array($curControlName,$cur)) echo 'cur';?>"><span style="color:#fff;cursor:pointer;"><?php echo lang('nav_opportunity');?></span>
                <ul>
                    <li style="margin-top:20px;"><a href="<?php echo base_url('why_tps'); ?>" title=""><?php echo lang('why_tps');?></a></li>
                    <li><a href="<?php echo base_url('advantage'); ?>" title=""><?php echo lang('cme_ade');?></a></li>
                    <!--<li><a href="<?php echo base_url('plan'); ?>" title=""><?php echo lang('cmn_plan');?></a></li>-->
                </ul>
            </li>
            <li class="<?php echo $curControlName=='ecosko'?'cur':''?>"><span style="color:#fff;"><a href="<?php echo base_url('mall'); ?>" title=""><?php echo lang('nav_ecosko');?></a></span></li>
            <li class="<?php $cur = array('news','news_official','video','news_detail'); if(in_array($curControlName,$cur)) echo 'cur';?>"><span style="color:#fff;cursor:pointer;" ><?php echo lang('nav_new');?></span>
                <ul>
                    <li style="margin-top:20px;"><a href="<?php echo base_url('news'); ?>" title=""><?php echo lang('nav_new_latest');?></a></li>
                    <li><a href="<?php echo base_url('news_official'); ?>" title=""><?php echo lang('official_noti');?></a></li>
                    <li><a href="<?php echo base_url('video'); ?>" title=""><?php echo lang('video');?></a></li>
                </ul>
            </li>
            <li class="<?php $cur = array('about','team','contact','vision'); if(in_array($curControlName,$cur)) echo 'cur';?>"><span style="color:#fff;cursor:pointer;"><?php echo lang('nav_abouts_us');?></span>
                <ul>
                    <li style="margin-top:20px;"><a href="<?php echo base_url('about'); ?>" target=""><?php echo lang('nav_about_tps');?></a></li>
                    <li ><a href="<?php echo base_url('vision'); ?>" target=""><?php echo lang('our_vision');?></a></li>
                    <li><a href="<?php echo base_url('team'); ?>" target=""><?php echo lang('mana_team');?></a></li>
                    <li><a href="<?php echo base_url('contact'); ?>" target=""><?php echo lang('nav_contact_us');?></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>