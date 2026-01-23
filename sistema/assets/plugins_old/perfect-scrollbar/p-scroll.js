(function($) {
	"use strict";
	
	//P-scrolling
	
	if (document.querySelector('.chat-scroll')) {
		const ps2 = new PerfectScrollbar('.chat-scroll', {
		  useBothWheelAxes:true,
		  suppressScrollX:true,
		});
	}
	if (document.querySelector('.Notification-scroll')) {
		const ps3 = new PerfectScrollbar('.Notification-scroll', {
		  useBothWheelAxes:true,
		  suppressScrollX:true,
		});
	}

	if (document.querySelector('.cart-scroll')) {
		const ps4 = new PerfectScrollbar('.cart-scroll', {
			useBothWheelAxes:true,
			suppressScrollX:true,
		});
	}
	
	
	
})(jQuery);