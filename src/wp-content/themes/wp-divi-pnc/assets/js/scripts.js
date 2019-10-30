jQuery(document).ready(function($) {

    //*** Função Menu collapse responsivo*** 
    $(".menu-responsivo").on("click", function() {
        $("#top-menu-nav").toggleClass("menu-collapse");
    });

    /*Leitor de rss script home*/
    if ($("#divRss")[0]) {
        $('#divRss').FeedEk({
            FeedUrl: 'http://www.cultura.gov.br/rss-backup/-/asset_publisher/PBe5d9MJmlrW/rss?p_p_cacheability=cacheLevelPage',
            MaxCount: 5,
            DateFormat: 'DD MMMM YYYY',
            DateFormatLang: 'pt'
        });
    }
    if ($("#rss-read")[0]) {
        /*Leitor de rss pagina noticias*/
        $('#rss-read').FeedEk({
            FeedUrl: 'http://www.cultura.gov.br/rss-backup/-/asset_publisher/PBe5d9MJmlrW/rss?p_p_cacheability=cacheLevelPage',
            MaxCount: 20,
            DateFormat: 'DD MMMM YYYY',
            DateFormatLang: 'pt'
        });
    }

    /**
     * jQuery Tooltip 
     *
     * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
     * Dual licensed under the MIT and GPL licenses:
     * http://www.opensource.org/licenses/mit-license.php
     * https://osvaldas.info/elegant-css-and-jquery-tooltip-responsive-mobile-friendly
     *
     */

     var targets = $('ul#menu-metas li a , .menu-desktop ul li a'),
     target = false,
     tooltip = false,
     title = false;

     targets.bind('mouseenter', function() {
        target = $(this);
        tip = target.attr('title');
        tooltip = $('<div id="tooltip"></div>');

        if (!tip || tip == '')
            return false;

        target.removeAttr('title');
        tooltip.css('opacity', 0)
        .html(tip)
        .appendTo('body');

        var init_tooltip = function() {
            if ($(window).width() < tooltip.outerWidth() * 1.5)
                tooltip.css('max-width', $(window).width() / 2);
            else
                tooltip.css('max-width', 340);

            var pos_left = target.offset().left + (target.outerWidth() / 2) - (tooltip.outerWidth() / 2),
            pos_top = target.offset().top - tooltip.outerHeight() - 20;

            if (pos_left < 0) {
                pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                tooltip.addClass('left');
            } else
            tooltip.removeClass('left');

            if (pos_left + tooltip.outerWidth() > $(window).width()) {
                pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                tooltip.addClass('right');
            } else
            tooltip.removeClass('right');

            if (pos_top < 0) {
                var pos_top = target.offset().top + target.outerHeight();
                tooltip.addClass('top');
            } else
            tooltip.removeClass('top');

            tooltip.css({
                left: pos_left,
                top: pos_top
            })
            .animate({
                top: '+=10',
                opacity: 1
            }, 50);
        };

        init_tooltip();
        $(window).resize(init_tooltip);

        var remove_tooltip = function() {
            tooltip.animate({
                top: '-=10',
                opacity: 0
            }, 50, function() {
                $(this).remove();
            });

            target.attr('title', tip);
        };

        target.bind('mouseleave', remove_tooltip);
        tooltip.bind('click', remove_tooltip);
    });

 });

/**
 * jQuery Carrousel
 * Copyright (c) 2017 Moisés Rabelo 
 * Navegação das metas
 *
 */

 function navegacaoMetas() {

    var menuMetas = jQuery("body:not(.home)  .menu-metas-container  #menu-metas");
    var controles = "<div class='controls'> </div>";
    var corrent = jQuery("body:not(.home) .menu-metas-container #menu-metas .current-menu-item a");
    corrent = corrent.text();
    jQuery("body:not(.home) .menu-metas-container").after(controles);


    var prev = "<button class='toggle' data-toggle='prev'>  <i class='fa fa-angle-left'  aria-hidden='true'></i> </button></div>";
    var next = "<button class='toggle' data-toggle='next'>  <i class='fa fa-angle-right' aria-hidden='true'></i> </button></div>";

    if (4 < corrent < 50) {
        var base = 5;
        var m = corrent - base;
        var f = -33 - (11 * m);
        menuMetas.animate({
            'left': '' + f + '%'
        }, 500);
    }

    if (corrent < 5) {
        var f = -33;
        menuMetas.animate({
            'left': '' + f + '%'
        }, 500);
    }

    if (corrent > 49) {
        var f = -517;
        menuMetas.animate({
            'left': '' + f + '%'
        }, 500);
    }


    jQuery(".controls").append(next);
    jQuery(".controls").prepend(prev);

    var left = menuMetas.css("left");

    var nextButton = jQuery('button.toggle[data-toggle=next]');
    nextButton.click(function(event) {
        left = menuMetas.css("left");
        left = parseInt(left, 10);
        if (left >= -5000) {
            menuMetas.animate({
                'left': '-=530px'
            }, 500);
            event.preventDefault();
        }
    });

    var prevButton = jQuery('button.toggle[data-toggle=prev]');
    prevButton.click(function(event) {
        left = menuMetas.css("left");
        left = parseInt(left, 10);
        if (left <= -505) {
            menuMetas.animate({
                'left': '+=530px'
            }, 500);
            event.preventDefault();
        }
    });

}

navegacaoMetas(jQuery("#menu-metas"));

/**
 * jQuery Modal
 * Copyright (c) 2017 Moisés Rabelo 
 * Modal Newsletter
 *
 */

 function openModal(botao, url) {

    jQuery("#stc_widget-2").parent().addClass("modal");
    var botao = botao;
    var url = url.parent();
    var fechar = jQuery("div.modal .et_pb_widget");
    fechar.append('<button title="Close (Esc)" type="button" class="mfp-close">×</button>');
    jQuery("div.modal input[name=stc-unsubscribe]").parent().hide()
    botao.click(function(e) {
        url.toggleClass("modal-oppen");
        e.preventDefault();
        jQuery('body').addClass('modal-is-open');
    });
    jQuery("button.mfp-close").click(function(e) {
        url.toggleClass("modal-oppen");
        jQuery('body').removeClass('modal-is-open');
    });

    jQuery("div.modal input#stc-email").attr("placeholder", "Digite seu email");
};



openModal(jQuery("#top-menu-nav a.et_pb_button"), jQuery("div#stc_widget-2"));


//Função para a carregar os cards de metas sob demanda
jQuery(document).ready(function() {
    jQuery(window).scroll(function() {
        jQuery('ul#menu-lista-de-metas li').each(function(i) {
            if (i > 10) {
                jQuery('ul#menu-lista-de-metas li').addClass("hideme");
            }
            var bottom_of_object = jQuery(this).offset().top + jQuery(this).outerHeight();
            var bottom_of_window = jQuery(window).scrollTop() + jQuery(window).height();
            if (bottom_of_window > bottom_of_object) {
                jQuery(this).animate({
                    'opacity': '1'
                }, 500);
            }
        });
    });

    jQuery('.terms-link.handler').click(function(e){
        e.preventDefault();
        jQuery('.acceptance-terms').slideToggle();
    });

    jQuery('#terms-acceptance-option').on('change', function(){
        if (jQuery(this).is(':checked')) {
            jQuery('#stc-subscribe-btn').removeAttr('disabled');
        } else {
            jQuery('#stc-subscribe-btn').attr('disabled', true);
        }
    })

    jQuery('#stc-subscribe-btn').on('click', function (e) {

        if( !jQuery('#stc-email').val().length ){
            e.preventDefault();

            jQuery('.validation_alert').remove();
            jQuery('#stc-subscribe-btn').before('<span class="validation_alert">Você precisa inserir um endereço de email válido</span>')
        }

        if( !jQuery('#stc-state').val().length || !jQuery('#stc-city').val().length ){
            e.preventDefault();

            jQuery('.validation_alert').remove();
            jQuery('#stc-subscribe-btn').before('<span class="validation_alert">Selecione um Estado e Município</span>')
        }

    })

    // Scripts referentes ao importador de usuários
    if( jQuery('#pnc-form-users-import').length ){
        console.log( 'Scripts referentes ao importador de usuários' );

        function isValidJson(json) {
            try {
                JSON.parse(json);
                return true;
            } catch (e) {
                return false;
            }
        }

        function formatJSON() {
            if( isValidJson( jQuery(this).val() ) ){
                var obj = jQuery.parseJSON( jQuery(this).val() );
                jQuery(this).val( JSON.stringify(obj) );
                jQuery('#pnc-form-users-import input[type="submit"]').removeAttr('disabled');
                console.log(obj);
            } else {
                console.error('Ops...wrong JSON');
                jQuery('#users-json').after('<p style="color: red;">O JSON inserido parece não ser válido</p>')
            }
        }

        var jsonInput = jQuery('#users-json');

        jsonInput.blur(formatJSON);
        jQuery('.validate-json-link').click(function (e) {
            e.preventDefault();
        });

    }
});