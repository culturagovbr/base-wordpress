(function($) {
    $(document).ready(function() {
        app.init();
    });

    var app = {
        init: function() {
            // this.accessibility();
            // this.utils();
            console.log('App here!');
        },

        /**
         * Accessibility functions
         *
         */
        accessibility: function() {
            // High contrast
            $('#high-contrast').click(function (e) {
                e.preventDefault();
                var highContrast = cookie('high-contrast');

                if (highContrast === 'on') {
                    cookie('high-contrast', 'off');
                    $('body').removeClass('high-contrast');
                } else {
                    cookie('high-contrast', 'on');
                    $('body').addClass('high-contrast');
                }
            })
        },

        /**
         * Utility functions, used on all sites
         *
         */
        utils: function() {
            // Enable bootstrap tooltip
            // $('[data-toggle="tooltip"]').tooltip();


        }
    };
})(jQuery);