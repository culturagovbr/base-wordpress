(function($) {
    $(document).ready(function() {
        app.init();
    });

    var app = {
        init: function() {
            var maskBehavior = function(val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            options = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(maskBehavior.apply({}, arguments), options);
                }
            };
            $('div[data-name="telefone"] input').mask(maskBehavior, options);

            $('a[href="#main-form-wrapper"]').click(function(e){
                e.preventDefault();
                // $('html, body').animate({scrollTop:0}, 'slow');
                $('html, body').animate({
                    scrollTop: $('#main-form-wrapper').offset().top + 'px'
                }, 'slow');
                return false;
            });            
        }
    };
})(jQuery);