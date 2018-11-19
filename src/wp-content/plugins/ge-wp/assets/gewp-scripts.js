(function ($) {
    $(document).ready(function () {
        app.init();
    });

    var app = {
        init: function () {
            this.initPlugins();
            this.counterAnim();
            this.barAnim();
            this.pdfViewer();
        },

        initPlugins: function () {
            if( $('#acoes-estrategicas.subacoes').length ){
                var $grid = $('#acoes-estrategicas.subacoes').masonry({
                    itemSelector: '.grid-item'
                });
            }
        },

        counterAnim: function () {
            function startCounterAnim() {
                $('.count').each(function () {
                    $(this).addClass('active');
                    $(this).prop('Counter',0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 1000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                });
            }

            if( $('.acoes-estrategicas-resultados').length ){
                startCounterAnim();
            }
        },

        barAnim: function () {
            function startBarAnim() {
                $('.bar-holder .bar').each(function () {
                    var width = $(this).css('width');
                    $(this).css('width', 0).addClass('active');
                    $(this).animate({
                        width: width
                    }, {
                        duration: 1000,
                        easing: 'swing'
                    });
                });
            }

            if( $('.acoes-estrategicas-resultados-table').length ){
                startBarAnim();
            }
        },

	    pdfViewer: function () {
	        if( $('.pdf-viewer').length ){
		        $('a.pdf-viewer').media({
			        width: 1100,
			        height: 680
		        });
            }
        }
    };
})(jQuery);