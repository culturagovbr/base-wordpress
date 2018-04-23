(function ($) {
    $(document).ready(function () {
        app.init();
    });

    var app = {
        init: function () {
            this.initPlugins();
        },

        initPlugins: function () {
            if( $('#acoes-estrategicas.subacoes').length ){
                var $grid = $('#acoes-estrategicas.subacoes').masonry({
                    itemSelector: '.grid-item'
                });
            }
        }
    };
})(jQuery);