(function($) {
    $(document).ready(function() {
        app.init();
        app.mainFormUtils();

        $('#snc_state').change(function() {
            if ($(this).val() != '') {
                $('#snc_county').html('<option value="">Carregando...</option>');

                $.ajax({
                    url: vars.ajaxurl,
                    type: 'post',
                    data: {
                        action: 'snc_get_cities_options',
                        uf: $('#snc_state').val(),
                        selected: $('#snc_county').val()},
                    success: function(data) {
                        $('#snc_county').html(data);
                    }
                });
            }
        })
    });

    var app = {
        init: function() {
            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('[data-toggle="tooltip"]').tooltip();
            $('#cpf').mask('000.000.000-00');
	        $('#birthday').mask('00/00/0000');
            $('#zipcode').mask('00000-000');
            $('#celphone').mask(SPMaskBehavior, spOptions);
            $('#phone').mask(SPMaskBehavior, spOptions);
        },

        mainFormUtils: function () {
            if( !$('#snc-register-form').length ){
                return;
            }

            // Masks
            $('div[data-name="mes_ano_de_finalizacao"] input').mask('00/0000');
            $('div[data-name="ano_de_estreia"] input, div[data-name="data_de_estreia"] input').mask('00/00/0000');


        },
    };
})(jQuery);