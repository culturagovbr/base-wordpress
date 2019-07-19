(function ($) {
    $(document).ready(function () {
        app.init();
        app.mainFormUtils();

        $form = $("#snc-register-form");

        $form.find('input.form-control, select.form-control').blur(app.verifyRegisterUserField)
            .keydown(function (e) {
                if (e.keyCode === 13) { //enter
                    $(this).trigger('blur').focus();
                }
            });

        $('#snc_state').change(function () {
            if ($(this).val() != '') {
                $('#snc_county').html('<option value="">Carregando...</option>');

                $.ajax({
                    url: vars.ajaxurl,
                    type: 'post',
                    data: {
                        action: 'snc_get_cities_options',
                        uf: $('#snc_state').val(),
                        selected: $('#snc_county').val()
                    },
                    success: function (data) {
                        $('#snc_county').html(data);
                    }
                });
            }
        })
    });

    var app = {
            init: function () {
                var SPMaskBehavior = function (val) {
                        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                    },
                    spOptions = {
                        onKeyPress: function (val, e, field, options) {
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
                if (!$('#snc-register-form').length) {
                    return;
                }

                // Masks
                $('div[data-name="mes_ano_de_finalizacao"] input').mask('00/0000');
                $('div[data-name="ano_de_estreia"] input, div[data-name="data_de_estreia"] input').mask('00/00/0000');


            },
            verifyRegisterUserField: function (e) {

                var $me = $(this);

                var values = { 'action': 'snc_register_verify_field' };
                // values['user_type'] = $('input[name="user_tipo"]:checked').val();
                // //
                // // // no checkbox o ajax pega o valor mesmo sem estar selecionado
                // console.log('avvvvv', this.name, $me.val());
                // if ($me.is('input[type="checkbox"]') && $me.prop("checked")) {
                //     values[this.name] = $me.val();
                // }
                values[this.name] = $me.val();

                $.post(vars.ajaxurl, values,
                    function (data) {
                        if (data.length === 0 && $me.val().length > 0) {
                            $form.find("[name='" + $me.attr('name') + "']").removeClass('is-invalid').addClass('is-valid');
                        }

                        for (var field in data) {
                            if (data[field] === true && $me.val().length > 0) {
                                $form.find("[name='"+ field + "']").removeClass('is-invalid').addClass('is-valid');
                            } else {
                                $form.find("[name='"+ field + "']").removeClass('is-valid').addClass('is-invalid');
                                $form.find("[name='"+ field + "']").parent().find('.invalid-feedback').html(data[field])
                                // $form.find('#' + field + '-error').html(data[field]).show();
                            }
                        }
                    },
                    'json'
                );
            }
        }
    ;
})(jQuery);