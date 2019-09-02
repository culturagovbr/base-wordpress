(function($) {
    $(document).ready(function() {
        app.init();
    });

    var app = {
        init: function() {
            $('.micsul-map .micsul-map-item > a').click(function(e){
                e.preventDefault();
                // $(this).parent().toggleClass('active');
            });
        }
    };
})(jQuery);