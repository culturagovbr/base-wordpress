jQuery.cookie = function (key, value, options) {

    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            '; path=/',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

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
            // $('div[data-name="telefone"] input').mask(maskBehavior, options);
            $('div[data-name="custo"] input').mask('000.000.000.000.000.000.000,00', {reverse: true});

            $('a[href="#main-form-wrapper"]').click(function(e){
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('#main-form-wrapper').offset().top + 'px'
                }, 'slow');
                return false;
            });

            $('#toggle-high-contrast').on('click', function(e){
                e.preventDefault();
                var a = jQuery.cookie("contraste_site_class");
                if (a === "contraste_on") {
                    jQuery.cookie("contraste_site_class", "contraste_off");

                    jQuery("body").removeClass('alto-contraste-ativo');
                } else {
                    jQuery.cookie("contraste_site_class", "contraste_on");
                    jQuery("body").addClass('alto-contraste-ativo');
                }
            });

            if (jQuery.cookie("contraste_site_class") === "contraste_on") {
                jQuery("body").addClass('alto-contraste-ativo');
            }
        }
    };
})(jQuery);