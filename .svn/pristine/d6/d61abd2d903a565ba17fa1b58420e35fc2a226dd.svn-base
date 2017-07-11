$(document).ready(function() {
                    
	// 图片上下滚动
	var count = $("#imageMenu li").length - 3; /* 显示 4 个 li标签内容 */
	var interval = $("#imageMenu li:first").height();
	var curIndex = 0;
	
	$('.scrollbutton').click(function(){
		if( $(this).hasClass('disabled') ) return false;
		
		if ($(this).hasClass('smallImgUp')) --curIndex;
		else ++curIndex;
		
		$('.scrollbutton').removeClass('disabled');
		if (curIndex == 0) $('.smallImgUp').addClass('disabled');
		if (curIndex == count-1) $('.smallImgDown').addClass('disabled');
		
		$("#imageMenu ul").stop(false, true).animate({"marginTop" : -curIndex*interval + "px"}, 600);
	});
	
	// 解决 ie6 select框 问题
	$.fn.decorateIframe = function(options) {
		if ($.browser.msie && $.browser.version < 7) {
			var opts = $.extend({}, $.fn.decorateIframe.defaults, options);
			$(this).each(function() {
				var $myThis = $(this);
				//创建一个IFRAME
				var divIframe = $("<iframe />");
				divIframe.attr("id", opts.iframeId);
				divIframe.css("position", "absolute");
				divIframe.css("display", "none");
				divIframe.css("display", "block");
				divIframe.css("z-index", opts.iframeZIndex);
				divIframe.css("border");
				divIframe.css("top", "0");
				divIframe.css("left", "0");
				if (opts.width == 0) {
					divIframe.css("width", $myThis.width() + parseInt($myThis.css("padding")) * 2 + "px");
				}
				if (opts.height == 0) {
					divIframe.css("height", $myThis.height() + parseInt($myThis.css("padding")) * 2 + "px");
				}
				divIframe.css("filter", "mask(color=#fff)");
				$myThis.append(divIframe);
			});
		}
	}
	
	 var midChangeHandler = null;
	 
	$("#imageMenu li img").bind("mouseover", function(){
			if ($(this).attr("id") != "onlickImg") {
				midChange($(this).attr("src").replace("small", "mid"));
				$("#imageMenu li").removeAttr("id");
				$(this).parent().attr("id", "onlickImg");
			}
		}).bind("mouseover", function(){
			if ($(this).attr("id") != "onlickImg") {
				window.clearTimeout(midChangeHandler);
				midChange($(this).attr("data-big").replace("small", "mid"));
				$(this).css({ "border": "1px solid #ff3674" });
			}
		}).bind("mouseout", function(){
			if($(this).attr("id") != "onlickImg"){
				$(this).removeAttr("style");
				midChangeHandler = window.setTimeout(function(){
					midChange($("#onlickImg img").attr("data-big").replace("small", "mid"));
				}, 1000);
			}
	});
	
	function midChange(src) {
		$("#midimg").attr("src", src);
		$('.magnifyingShow').find('img').attr('src',src)
	}

 });
				
//图片放大镜				
$(function(){
	$.fn.magnifying = function(){
		var that = $(this),
		 $imgCon = that.find('.con-fangDaIMg'),//正常图片容器
		 	$Img = $imgCon.find('img'),//正常图片，还有放大图片集合
		   $Drag = that.find('.magnifyingBegin'),//拖动滑动容器
		   $show = that.find('.magnifyingShow'),//放大镜显示区域
		$showIMg = $show.find('img'),//放大镜图片
		$ImgList = that.find('.con-FangDa-ImgList > li >img'),
		multiple = $show.width()/$Drag.width();

		$imgCon.mousemove(function(e){
			$Drag.css('display','block');
			$show.css('display','block');
		    //获取坐标的两种方法
		   	// var iX = e.clientX - this.offsetLeft - $Drag.width()/2,
		   	// 	iY = e.clientY - this.offsetTop - $Drag.height()/2,	
		   	var iX = e.pageX - $(this).offset().left - $Drag.width()/2,
		   		iY = e.pageY - $(this).offset().top - $Drag.height()/2,	
		   		MaxX = $imgCon.width()-$Drag.width(),
		   		MaxY = $imgCon.height()-$Drag.height();
				
  	   	    /*这一部分可代替下面部分，判断最大最小值
		   	var DX = iX < MaxX ? iX > 0 ? iX : 0 : MaxX,
		   		DY = iY < MaxY ? iY > 0 ? iY : 0 : MaxY;
		   	$Drag.css({left:DX+'px',top:DY+'px'});	   		
		   	$showIMg.css({marginLeft:-3*DX+'px',marginTop:-3*DY+'px'});*/

		   	iX = iX > 0 ? iX : 0;
		   	iX = iX < MaxX ? iX : MaxX;
		   	iY = iY > 0 ? iY : 0;
		   	iY = iY < MaxY ? iY : MaxY;	
		   	$Drag.css({left:iX+'px',top:iY+'px'});	   		
		   	$showIMg.css({marginLeft:-multiple*iX+'px',marginTop:-multiple*iY+'px'});
		   	//return false;
		});
		$imgCon.mouseout(function(){
		   	$Drag.css('display','none');
			$show.css('display','none');
		});

		$ImgList.click(function(){
			var NowSrc = $(this).data('bigimg');
			$Img.attr('src',NowSrc);
			$(this).parent().addClass('active').siblings().removeClass('active');
		});	
	}

	$("#fangdajing").magnifying();

});