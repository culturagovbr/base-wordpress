jQuery(document).ready(function($) {
	$(function() {
		$('.box-events.carousel').cycle({
			fx: 'fadeout',
			timeout: 0,
			log: false,
			slides: '.mcwp-item',
			pager: '.mcwp-carousel-pager'
		});
	});
});