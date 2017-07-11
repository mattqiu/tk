//商品详情导航,选项卡固定
   var m_tabs_big_tit_pos= $(".main_wrap").offset().top;
  $(window).scroll(function () {
	 if($(document).scrollTop()>m_tabs_big_tit_pos){
	   $("ul.Switch").addClass("fixed");
	   //$(".m_tabs_big_tit").addClass("z_web_width");
	   //$(".m_tabs_big_tit").css("width",$(".z_web_width").width()-2);
	 }
	 if($(document).scrollTop()<m_tabs_big_tit_pos){
	   $(".m_tabs_big_tit_warp").removeClass("fixed");
	   //$(".m_tabs_big_tit").removeClass("z_web_width");
	   //$(".m_tabs_big_tit").css("width","auto");
	 }
  });

//继续购买
    $('.goumai').click(function() {
        $(this).parents('.tjia').hide();
    });
    
    //点击颜色尺寸
    $('.js-color').click(function() {
        var attr_color=$.trim($(this).attr('title')),
        attr_size=$.trim($('.js-li-size.selected').find('.js-size').attr('title')),
        sn='';
        
        if(!attr_size) {
            attr_size='';
        }
        
        $.each(ColorSize,function(i,v) {            
            if(v.color == attr_color && v.size == attr_size) {
                sn=v.sn;
            }
        });

    });
    
    $('.js-size').click(function() {
        var attr_size=$.trim($(this).attr('title')),
        attr_color=$.trim($('.js-li-color.selected').find('.js-color').attr('title')),
        sn='';
        
        if(!attr_color) {
            attr_color='';
        }
        
        $.each(ColorSize,function(i,v) {            
            if(v.color == attr_color && v.size == attr_size) {
                sn=v.sn;
            }
        });
    });


//添加购物车
    $('.incart').click(function() {
        var number=$('.js-number').val(),sku=$('.js-sku').text();
        
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('cart/do_add_to_cart')?>",
          dataType: "json",
          data:{goods_sn:sku,quantity:number},
          success:function(data){
            if(data.code == 0) {
                $('.tjia').show();
                var $cart_num=$('.ci-count'),
                cart_num=parseInt($cart_num.text());
                $cart_num.text(cart_num + parseInt(number));
            }
          }
        });
   });

//增加数量
    $('.s1').click(function() {
        var $num = $('.js-number'),num=parseInt($num.val());
        $num.val(num+1);
    });
    
    //降低数量
    $('.s2').click(function() {
        var $num = $('.js-number'),num=parseInt($num.val());
        var new_num=num-1;
        
        if(new_num <= 0) {
            new_num=1
        }
        $num.val(new_num);
    });
    
    //数量检查
    $('.js-number').blur(function(){
        var $t=$(this),num=parseInt($t.val());
        if(isNaN(num)) {
            $t.val(1);
        }else {
            $t.val(num);
        }
  });

//商品规格选择
$(function(){
    $(".sys_item_specpara").each(function(){
        var i=$(this);
        var p=i.find("ul>li");
        p.click(function(){
            if(!!$(this).hasClass("selected")){
                $(this).removeClass("selected");
                i.removeAttr("data-attrval");
            }else{
                $(this).addClass("selected").siblings("li").removeClass("selected");
                i.attr("data-attrval",$(this).attr("data-aid"))
            }
        })
    })
})


//小图切换大图
$(document).ready(function(){
    $(".jqzoom").imagezoom();
    
    $(".imageMenu li a").mousemove(function(){
        $(this).parents("li").addClass("sma").siblings().removeClass("sma");
        $(".jqzoom").attr('src',$(this).find("img").attr("mid"));
        $(".jqzoom").attr('rel',$(this).find("img").attr("big"));
    });

});


(function($){

    $.fn.imagezoom = function(options){

        var settings = {
            xzoom: 420,//宽度定义
            yzoom: 406,//高度定义
            offset: 10,
            position: "BTR",
            preload: 1
        };

        if(options) {
            $.extend(settings, options);
        }

        var noalt = '';

        var self = this;

        $(this).bind("mouseenter", function(ev){

            var imageLeft = $(this).offset().left;//元素左边距
            var imageTop = $(this).offset().top;//元素顶边距


            var imageWidth = $(this).get(0).offsetWidth;//图片宽度
            var imageHeight = $(this).get(0).offsetHeight;//图片高度

            var boxLeft = $(this).parent().offset().left;//父框左边距
            var boxTop = $(this).parent().offset().top;//父框顶边距
            var boxWidth = $(this).parent().width();//父框宽度
            var boxHeight = $(this).parent().height();//父框高度

            noalt= $(this).attr("alt");//图片标题
            var bigimage = $(this).attr("rel");//大图地址
            $(this).attr("alt",'');//清空图片alt


            if($("div.zoomDiv").get().length == 0){
                $(document.body).append("<div class='zoomDiv loading_big'><img class='bigimg' src='"+bigimage+"'/></div><div class='zoomMask'>&nbsp;</div>");//放大镜框及遮罩
            }


            if(settings.position == "BTR"){
                //如果超过了屏幕宽度 将放大镜放在右边
                if(boxLeft + boxWidth + settings.offset + settings.xzoom > screen.width){
                    leftpos = boxLeft  - settings.offset - settings.xzoom;
                }else{
                    leftpos = boxLeft + boxWidth + settings.offset;
                }
            }else{
                leftpos = imageLeft - settings.xzoom - settings.offset;
                if(leftpos < 0){
                    leftpos = imageLeft + imageWidth  + settings.offset;
                }
            }

            $("div.zoomDiv").css({ top: '341px',left: leftpos });//大图定位的上边距离
            $("div.zoomDiv").width(settings.xzoom);
            $("div.zoomDiv").height(settings.yzoom);
            $("div.zoomDiv").show();

            $(this).css('cursor','crosshair');

            $(document.body).mousemove(function(e){

                mouse = new MouseEvent(e);
                if(mouse.x<imageLeft || mouse.x>imageLeft+imageWidth || mouse.y<imageTop || mouse.y>imageTop+imageHeight){
                    mouseOutImage();
                    return;
                }

                var bigwidth = $(".bigimg").get(0).offsetWidth;
                var bigheight = $(".bigimg").get(0).offsetHeight;

                var scaley ='x';
                var scalex ='y';


                //设置遮罩层尺寸
                if(isNaN(scalex)|isNaN(scaley)){
                    var scalex = (bigwidth/imageWidth);
                    var scaley = (bigheight/imageHeight);
                    $("div.zoomMask").width((settings.xzoom)/scalex );
                    $("div.zoomMask").height((settings.yzoom)/scaley);
                    $("div.zoomMask").css('visibility','visible');
                }



                xpos = mouse.x- $("div.zoomMask").width()/2 ;
                ypos = mouse.y- $("div.zoomMask").height()/2 ;
                
                xposs = mouse.x- $("div.zoomMask").width()/2 - imageLeft;
                yposs = mouse.y- $("div.zoomMask").height()/2 - imageTop ;
                
                xpos = (mouse.x - $("div.zoomMask").width()/2 < imageLeft ) ? imageLeft : (mouse.x + $("div.zoomMask").width()/2 > imageWidth + imageLeft ) ?  (imageWidth + imageLeft -$("div.zoomMask").width()): xpos;
                ypos = (mouse.y - $("div.zoomMask").height()/2 < imageTop ) ? imageTop : (mouse.y + $("div.zoomMask").height()/2  > imageHeight + imageTop ) ?  (imageHeight + imageTop - $("div.zoomMask").height()) : ypos;


                $("div.zoomMask").css({ top:ypos,left:xpos});
                $("div.zoomDiv").get(0).scrollLeft = xposs * scalex;
                $("div.zoomDiv").get(0).scrollTop  = yposs * scaley;


            });

        });


        function mouseOutImage(){
            $(self).attr("alt",noalt);
            $(document.body).unbind("mousemove");
            $("div.zoomMask").remove();
            $("div.zoomDiv").remove();
        }

        //预加载
        count = 0;
        if(settings.preload){
            $('body').append("<div style='display:none;' class='jqPreload"+count+"'></div>");

            $(this).each(function(){

                var imagetopreload= $(this).attr("rel");

                var content = jQuery('div.jqPreload'+count+'').html();

                jQuery('div.jqPreload'+count+'').html(content+'<img src=\"'+imagetopreload+'\">');

            });

        }

    }

})(jQuery);


function MouseEvent(e) {
    this.x = e.pageX;
    this.y = e.pageY;
}
