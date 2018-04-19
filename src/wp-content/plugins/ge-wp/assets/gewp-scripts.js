(function ($) {
    $(document).ready(function () {
        app.init();
    });

    var app = {
        init: function () {
            this.initPlugins();
        },

        initPlugins: function () {
            if( $('#acoes-estrategicas').length ){
                var $grid = $('#acoes-estrategicas').masonry({
                    itemSelector: '.grid-item'
                });
            }
        }
    };
})(jQuery);