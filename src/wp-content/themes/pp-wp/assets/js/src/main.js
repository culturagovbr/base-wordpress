(function($) {
    $(document).ready(function() {
        app.init();
    });

    var app = {
        init: function() {
            console.log('Ok here! 123...');
            /*app.utils();
            app.navigation();
            app.aboutSection();
            app.animsInit();
            app.formHandlers();
            app.votingHandlers();*/
        },

        /**
         * Navigation and menus in general
         * 
         */
        navigation: function() {
            // Toggle menu sidebar
            $('.menu-toggle').on('click', function(){
                $('body').addClass('menu-active');
                $('body').append('<div id="sidenav-overlay"></div>');
            });

            // Hide menu sidebar
            $('body').on('click', '#sidenav-overlay, .main-navigation .menu-close', function(){
                $('body').removeClass('menu-active');
                $('#sidenav-overlay').remove();
            });
        },

        /**
         * Specific section on homepage
         * 
         */
        aboutSection: function() {
            $('.about-nav li').mouseenter(function(){
                var target = $(this).find('a').attr('href');
                $('.about-nav li, .about-content').removeClass('active');
                $(target).addClass('active');
                $(this).addClass('active');
            });
        },

        /**
         * Handle animation and particles on homepage
         * 
         */
        animsInit: function() {
            if( $('#intro').length ){
                particlesJS.load('intro', ppmSettings.templatePath + '/assets/data/particlesjs-config.json', function() {});
            }

            $('.ppm-animated').waypoint(function(e) {
                var el = $( $(this)[0].element );
                var anim = el.attr('data-anim');
                var delay = el.attr('data-delay');

                if( delay !== '0' ){
                    setTimeout(function(){
                        $('.ppm-animated').addClass( anim );
                    }, delay);
                } else {
                    el.addClass( anim );
                }
                
            }, { offset: '100%' });
        },

        /**
         * Utility functions, used on all sites
         * 
         */
        utils: function() {
            // Enable bootstrap tooltip
            $('[data-toggle="tooltip"]').tooltip();
            
            // 
            $('.checkbox input[type="checkbox"], div[data-type=true_false] .acf-input input[type=checkbox]').on('change', function(e) {
                if ($(this).is(':checked')) {
                    $('.acf-form-submit input[type=submit]').removeAttr('disabled');
                    $(this).parent().parent().addClass('selected');
                } else {
                    $('.acf-form-submit input[type=submit]').attr('disabled', 'disabled');
                    $(this).parent().parent().removeClass('selected');
                }
            });

            // Enable galley plugin
            if( $('.gallery').length ){
                $('.gallery-item a').attr( 'data-fancybox', 'group' );
                $('.gallery-item a').fancybox({});
            }

            $('.carousel-indicators-custom li').click(function(){
                $('.carousel-indicators-custom li').removeClass('active');
                $(this).addClass('active');
            });

            Raven.config('https://ec6112b1028046e28753f2688e5dfdb6@sentry.io/220799').install();
        },

        /**
         * Handle different forms
         * 
         */
        formHandlers: function() {
            // Register form manipulation
            if( $('#ppm-register-form').length ){

                function togglePersonTypeInput () {
                    if( $('input[name="reg_person_type"]:checked').val() === 'pessoa-fisica' ){
                        $('.pessoa-fisica-input').removeClass('hidden');
                        $('.pessoa-juridica-input').addClass('hidden');
                        $('#reg-cpf').attr('required', true);
                        $('#reg-cnpj').removeAttr('required');
                    } else {
                        $('.pessoa-juridica-input').removeClass('hidden');
                        $('.pessoa-fisica-input').addClass('hidden');
                        $('#reg-cnpj').attr('required', true);
                        $('#reg-cpf').removeAttr('required');
                    }
                }
                togglePersonTypeInput();
                $('input[name="reg_person_type"]').on( 'click', togglePersonTypeInput );
            }

        },

        /**
         * Main voting form and functionalities
         * 
         */
        votingHandlers: function() {
            if( $('#main.ppm-voting-form-page').length ){
                // When click modality columns, open modal of categories
                $('.voting-col').on('click', function(){
                    var modality = $(this).attr('data-modality'),
                        modalityTitle = $(this).find('h2').text();
                    $('body').addClass('modal-open modal-ppm-form');
                    $('div[data-name="'+ modality +'"]').addClass('overlayed');
                    $('div[data-name="'+ modality +'"] > .acf-input > .acf-repeater').css('height', $(window).height() - 100 + 'px');
                    $('div[data-name="'+ modality +'"] > .acf-input > .acf-repeater .modality-title').remove();
                    $('div[data-name="'+ modality +'"] > .acf-input > .acf-repeater').prepend('<div class="acf-label modality-title"><label>'+ modalityTitle +'</label></div>');
                    $('div[data-name="'+ modality +'"]').prepend('<a href="#" class="close-modality"><i class="fa fa-times" aria-hidden="true"></i></a>');

                    var modalityTableRows = $('#ppm-voting-form div.overlayed > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row');
                    // Ignore initial rows
                    if( modalityTableRows.length > 1 ){
                        $('#ppm-voting-form div.overlayed').removeClass('no-rows');
                        $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions li:last a').text('Nova inscrição');
                        $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions').removeClass('no-rows-btn');
                    } else {
                        $('#ppm-voting-form div.overlayed').addClass('no-rows');
                        $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions li:last a').text('Fazer inscrição');
                        $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions').addClass('no-rows-btn');
                    }
                });

                // Close modal for modality categories
                $('body').on('click', '.close-modality, .save-voting-row a', function(e){
                    e.preventDefault();
                    var modality = $('#ppm-voting-form div.overlayed').attr('data-name');
                    $('.voting-col[data-modality="'+ modality +'"]').find('.list-group').html('');
                    $.each( $('#ppm-voting-form div.overlayed > .acf-input > .acf-repeater > .acf-table > tbody > .acf-row'), function(i, e){
                        if( !$(e).hasClass('acf-clone') ){
                            var subscriptionCat = $(e).find('div[data-name="categorias"] select').val();
                            var subscriptionName = $(e).find('div[data-name="nome_do_concorrente"] input').val();
                            var li = '<li class="list-group-item">'+ subscriptionName +'<br><small>'+ subscriptionCat +'</small></li>';

                            $('.voting-col[data-modality="'+ modality +'"]').find('.list-group').append(li);
                        }
                    });

                    $('#ppm-voting-form div').removeClass('overlayed');
                    $('body').removeClass('modal-open modal-ppm-form');

                });

                // New subscription ['New row'] button click
                $('body').on('click', '#ppm-voting-form div.overlayed > .acf-input > .acf-repeater > .acf-actions li a', function(event){
                    // validateFields();
                    // Wait for ACF append row
                    setTimeout(function(){
                        var modality = $('#ppm-voting-form div.overlayed').attr('data-name');
                        var modalityTableRows = $('#ppm-voting-form div.overlayed > .acf-input > .acf-repeater > .acf-table > tbody > tr.acf-row');
                        // Ignore initial rows
                        if( modalityTableRows.length > 2 ){
                            $.each( modalityTableRows, function(i, e){
                                if( !$(e).hasClass('acf-clone') ){
                                    $(e).addClass('voting-row-defined').removeClass('current-row');
                                    $(e).parent().find('.acf-clone').prev('.acf-row').removeClass('voting-row-defined');
                                   // $(e).find('.acf-fields').removeAttr('colspan');
                                }

                                if( !$(e).hasClass('acf-clone') && !$(e).hasClass('voting-row-defined') ){
                                    $(e).addClass('current-row');
                                    // $(e).find('.acf-fields').attr('colspan', '2');
                                }
                            });
                        }
                        updateColWithSubscriptions();
                    }, 500);

                    $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions .save-voting-row').remove();
                    $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions').prepend('<li class="save-voting-row"><a class="" href="#">Salvar inscrição</a></li>');
                    $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions li:last a').text('Nova inscrição');
                    $('#ppm-voting-form div.overlayed>.acf-input>.acf-repeater>.acf-actions').removeClass('no-rows-btn');
                    $('#confimation-modal-btn').removeClass('hidden');
                });

                function validateFields () {
                    $.each($('#ppm-voting-form .acf-row .acf-fields .acf-field'), function(i, e){
                        if( $(this).attr('data-required') ){
                            $(this).find('.acf-input').prepend('<div class="acf-error-message"><p>É necessário preencher o campo Nome do concorrente</p></div>');
                            return false;
                        }
                    })
                }

                // Function responsible for update HTML for modality column list, with subscriptions
                function updateColWithSubscriptions() {
                    var modality = $('#ppm-voting-form div.overlayed').attr('data-name');
                    $('.voting-col[data-modality="'+ modality +'"]').find('.list-group').html('');

                    // For each row subscription defined (.voting-row-defined), collapse and put some utils
                    $.each( $('#ppm-voting-form div.overlayed .acf-row.voting-row-defined'), function(i, e){
                        // console.log(i, e);
                        // var removeIcon = '<i class="fa fa-trash" aria-hidden="true"></i>';
                        // $(e).find('div[data-name="nome_do_concorrente"] .acf-label label').text('Inscrição #' + (i + 1));
                        // $(e).find('div[data-name="nome_do_concorrente"] .acf-label label').html('Inscrição pronta <a href="#" class="expand-row">Expandir <i class="fa fa-expand" aria-hidden="true"></i></a>');
                        // $(e).find('div[data-name="nome_do_concorrente"] input').attr('disabled', true);
                        // $(e).find('div[data-name="nome_do_concorrente"]').parent().parent().find('.acf-row-handle.remove > a.acf-icon.-minus').html(removeIcon);
                        var subscriptionCat = $(e).find('div[data-name="categorias"] select').val();
                        var subscriptionName = $(e).find('div[data-name="nome_do_concorrente"] input').val();
                        var li = '<li class="list-group-item">'+ subscriptionName +'<br><small>'+ subscriptionCat +'</small></li>';

                        $('.voting-col[data-modality="'+ modality +'"]').find('.list-group').append(li);
                    });
                }

                $('body').on('click', '#ppm-voting-form .expand-row', function(e){
                    e.preventDefault();
                    $(this).closest('tr').removeClass('voting-row-defined');
                    // $('#ppm-voting-form div.overlayed').find('div[data-name="nome_do_concorrente"] .acf-label label').html('Nome do concorrente');
                    // $('#ppm-voting-form div.overlayed').find('div[data-name="nome_do_concorrente"] .acf-input input').removeAttr('disabled')
                });

                $('body').on('click', '.acf-row-handle.remove a.acf-icon.-minus', function(e){
                    setTimeout(function(){
                        $('.acf-tooltip').html('Tem certeza? <a href="#" class="acf-confirm-y -red">Remover</a> <a href="#" class="acf-confirm-n">Cancelar</a>');
                    }, 0)
                });

                // Send form to subscription
                $('body').on('click', '#ppm-voting-form-send-btn', function(){
                    $(this).text('Enviando...');
                    $('#ppm-voting-form .acf-form-submit input[type="submit"]').trigger('click');
                    setTimeout(function(){
                        // console.log('Checking errors...');
                        if( $('#ppm-voting-form .acf-error-message').length ){
                            $('.validation-form-check').removeClass('hidden');
                            $('#ppm-voting-form-send-btn').text('Finalizar inscrição')
                            if( $('#ppm-voting-form div[data-name="inscricoes-criacao"] .acf-error-message').length ) {
                                $('.voting-col.creation .validation-error').remove();
                                $('.voting-col.creation h2').after('<p class="validation-error">Falha na validação. Alguns campos requerem sua atenção</p>')
                            }

                            if( $('#ppm-voting-form div[data-name="inscricoes-producao"] .acf-error-message').length ) {
                                $('.voting-col.production .validation-error').remove();
                                $('.voting-col.production h2').after('<p class="validation-error">Falha na validação. Alguns campos requerem sua atenção</p>')
                            }

                            if( $('#ppm-voting-form div[data-name="inscricoes-convergencia"] .acf-error-message').length ) {
                                $('.voting-col.distribution .validation-error').remove();
                                $('.voting-col.distribution h2').after('<p class="validation-error">Falha na validação. Alguns campos requerem sua atenção</p>')
                            }

                            $('#confimation-modal').modal('hide');
                        }
                    }, 1000)
                });

            }
        }
    };
})(jQuery);