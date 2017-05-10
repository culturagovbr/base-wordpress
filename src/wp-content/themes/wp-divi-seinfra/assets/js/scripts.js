(function ($) {
	var app = {
		init: function () {
			// console.info('Initializing application');
			app.galleryInit();
		},

		galleryInit: function () {
			if( $('article .entry-content .gallery').length ){
				$('.gallery-item a').each(function(){
					$(this).attr('data-caption', $(this).parent().next('.gallery-caption').text() );
				})
				$('.gallery-item a').attr('data-fancybox', 'group');
			}
		}
	};

	$(document).ready(function () {
		app.init();
	});
})(jQuery);