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
        });

        $("button.cancel-subscription").click(function (e) {
            app.userCancelSubscription(this);
        });

        $("button.confirm-presence").click(function (e) {
            app.userConfirmPresence(this);
        });
    });

    var app = {
            newDialog: function (field) {
                return $(field).dialog({
                    autoOpen: false,
                    height: 480,
                    width: 600,
                    title: "Confirmação de Presença",
                    buttons: {
                        "Confirmar": function () {
                            if ($('.checkValidatorSnc:checked').length != $('.checkValidatorSnc').length) {
                                alert("Marque todos os 'Check List'!");
                                return;
                            }

                            var pid = $("#sncIdOficina").val();

                            if (confirm("Você realmente deseja confirmar sua(s) presença(s) na oficina?")) {
                                $.post(vars.ajaxurl, {'action': 'snc_confirm_presence', 'pid': pid}, function (data) {
                                    if (data) {
                                        window.location.href = window.location.href + "?status=confirm";
                                        $(field).dialog("close");
                                    }
                                }).fail(function (e) {
                                    alert(e.responseJSON.data);
                                });
                            }
                        },
                        "Cancelar": function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function () {
                        $(this).dialog("destroy");
                    }
                });
            },

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
                var values = {'action': 'snc_register_verify_field'};
                values[this.name] = $me.val();

                $.post(vars.ajaxurl, values,
                    function (data) {
                        if (data.length === 0 && $me.val().length > 0) {
                            $form.find("[name='" + $me.attr('name') + "']").removeClass('is-invalid').addClass('is-valid');
                        }
                        for (var field in data) {
                            if (data[field] === true && $me.val().length > 0) {
                                $form.find("[name='" + field + "']").removeClass('is-invalid').addClass('is-valid');
                            } else {
                                $form.find("[name='" + field + "']").removeClass('is-valid').addClass('is-invalid');
                                $form.find("[name='" + field + "']").parent().find('.invalid-feedback').html(data[field])
                                // $form.find('#' + field + '-error').html(data[field]).show();
                            }
                        }
                    },
                    'json'
                );
            },

            daysBetween: function (date1, date2) {
                // The number of milliseconds in one day
                var ONE_DAY = 1000 * 60 * 60 * 24;

                // Convert both dates to milliseconds
                var date1_ms = date1.getTime();
                var date2_ms = date2.getTime();

                // Calculate the difference in milliseconds
                var difference_ms = Math.abs(date1_ms - date2_ms);

                // Convert back to days and return
                return Math.round(difference_ms / ONE_DAY);
            },

            dateFullFormat: function (data) {
                var dia = data.getDate().toString().padStart(2, '0'),
                    mes = (data.getMonth() + 1).toString().padStart(2, '0'),
                    ano = data.getFullYear();
                return dia + "/" + mes + "/" + ano;
            },

            userConfirmPresence: function (el) {
                var dataInicio = $(el).attr("data-dt-inicio");
                var horaInicio = $(el).attr("data-hr-inicio");

                var arDataInicio = dataInicio.split('/');
                var arHoraInicio = horaInicio.split(':');

                var objDataInicio = new Date(parseInt(arDataInicio[2]), parseInt(arDataInicio[1]) - 1, parseInt(arDataInicio[0]));

                var dataFim = $(el).attr("data-dt-fim");
                var horaFim = $(el).attr("data-hr-fim");

                var arDataFim = dataFim.split('/');
                var arHorafim = horaFim.split(':');

                var objDataFim = new Date(parseInt(arDataFim[2]), parseInt(arDataFim[1]) - 1, parseInt(arDataFim[0]));

                var rangeDays = app.daysBetween(objDataFim, objDataInicio);

                var table = '<table style="font-size: 10px;">';
                var thDatas = '<tr style="font-size: 12px;"><th>Dias da Oficina</th>';

                var tdMat = '<th>Matutino</th>';
                var tdVesp = '<th>Vespertino</th>';
                var tdNot = '<th>Noturno</th>';

                var turnoInicio = arHoraInicio[0] >= 4 && arHoraInicio[0] < 12;
                var turnoMeio = arHorafim[0] > 12 || (arHorafim[0] == 12 && arHorafim[1] > 0);
                var turnoFim = arHorafim[0] > 18 || (arHorafim[0] == 18 && arHorafim[1] > 0);

                thDatas += (turnoInicio ? tdMat : '')
                    + (turnoMeio ? tdVesp : '')
                    + (turnoFim ? tdNot : '')
                    + '</tr>';

                for (var i = 0; i <= rangeDays; i++) {
                    objDataInicio.setDate(objDataInicio.getDate() + (i > 0 ? 1 : 0));

                    thDatas += '<tr><th>' + app.dateFullFormat(objDataInicio) + '</th>';

                    if (turnoInicio) {
                        thDatas += '<th><input name="matutino[' + i + ']" class="checkValidatorSnc" type="checkbox" /></th>';
                    }

                    if (turnoMeio) {
                        thDatas += '<th><input name="vespertino[' + i + ']" class="checkValidatorSnc" type="checkbox" /></th>';
                    }

                    if (turnoFim) {
                        thDatas += '<th><input name="noturno[' + i + ']" class="checkValidatorSnc" type="checkbox" /></th>';
                    }

                    thDatas += '</tr>';
                }

                table += thDatas + '</table>';
                table += '<input id="sncIdOficina" type="hidden" value="' + $(el).attr("data-id") + '" />';

                $(".checkValidatorSnc").attr("checked", false);

                $("#dialog-snc-label-table").html(table);

                app.newDialog("#dialog-snc").dialog("open");

                $(".ui-dialog-buttonset button").addClass('btn btn-secondary btn-sm');
            },

            userCancelSubscription: function (el) {
                var pid = el.id.replace(/^[^0-9]+/, '');
                if (confirm("Você realmente deseja cancelar a sua inscrição na oficina?")) {
                    $.post(vars.ajaxurl, {'action': 'snc_cancel_subscription', 'pid': pid}, function (data) {
                        if (data) {
                            window.location.href = window.location.href + "?status=canceled";
                        }
                    }).fail(function (e) {
                        alert(e.responseJSON.data);
                    });
                }

                return false;
            }
        }
    ;
})(jQuery);