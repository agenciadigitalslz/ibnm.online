(function($) {
	"use strict";
	
	// P-scrolling (protegido contra elementos ausentes)
	if (document.querySelector('.chat-scroll')) {
		new PerfectScrollbar('.chat-scroll', { useBothWheelAxes:true, suppressScrollX:true });
	}
	if (document.querySelector('.Notification-scroll')) {
		new PerfectScrollbar('.Notification-scroll', { useBothWheelAxes:true, suppressScrollX:true });
	}
	if (document.querySelector('.cart-scroll')) {
		new PerfectScrollbar('.cart-scroll', { useBothWheelAxes:true, suppressScrollX:true });
	}
	
})(jQuery);
