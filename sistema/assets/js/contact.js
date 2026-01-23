$(function() {
	'use strict'
	if (document.querySelector('#mainContactList')) {
		new PerfectScrollbar('#mainContactList', {
			suppressScrollX: true
		});
	}
	if (document.querySelector('.main-contact-info-body')) {
		new PerfectScrollbar('.main-contact-info-body', {
			suppressScrollX: true
		});
	}
	$('.main-contact-item').on('click touch', function() {
		$(this).addClass('selected');
		$(this).siblings().removeClass('selected');
		$('body').addClass('main-content-body-show');
	})
});