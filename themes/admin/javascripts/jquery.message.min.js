/*
 * jQuery message plug-in 1.0
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-message/
 *
 * Copyright (c) 2009 Jörn Zaefferer
 *
 * $Id: jquery.message.js 6407 2009-06-19 09:07:26Z joern.zaefferer $
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 * Last modify by yuandd 2015/07/11
 */
(function($) {
	var helper,
		visible,
		timeout1,
		timeout2,
		style='succ';
	
	$.fn.message = function(message,type) {		
		
		message = $.trim(message || this.text());
		if (!message) {
			return;
		}
		clearTimeout(timeout1);
		clearTimeout(timeout2);
		
		initHelper();
		if(typeof(type) == 'undefined') {
			style='error';
		}else {
			style='succ';
		}

		helper.addClass(style).find("p").html(message);
		helper.show().animate({ opacity: $.message.defaults.opacity}, $.message.defaults.fadeInDuration);
		visible = true;
		active = false;
		timeout1 = setTimeout(function() {
			visible = false;
		}, $.message.defaults.minDuration + $.message.defaults.displayDurationPerCharacter * Math.sqrt(message.length));
		timeout2 = setTimeout(fadeOutHelper, $.message.defaults.totalTimeout);
	};
	
	function initHelper() {
		if (!helper) {
			helper = $($.message.defaults.template).appendTo(document.body);
			$(window).bind("mousemove click keypress", fadeOutHelper);
		}
	}
	
	function fadeOutHelper() {
		if (helper.is(":visible") && !helper.is(":animated") && !visible) {
			helper.animate({ opacity: 0 }, $.message.defaults.fadeOutDuration, function() { $(this).hide().attr('class','').addClass('jquery-message') })
		}
	}
	
	$.message = {};
	$.message.defaults = {
		opacity: 1,
		fadeOutDuration: 500,
		fadeInDuration: 200,
		displayDurationPerCharacter: 125,
		minDuration: 2500,
		totalTimeout: 6000,
		template: '<div class="jquery-message"><div class="round"></div><span class="icon-info-sign"></span>&nbsp;<p></p><div class="round"></div></div>'
	}
})(jQuery);
