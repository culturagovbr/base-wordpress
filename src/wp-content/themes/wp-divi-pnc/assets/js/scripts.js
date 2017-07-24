jQuery(document).ready(function(jQuery) {

  //*** Função Menu collapse responsivo*** 
  jQuery(".menu-responsivo").on("click", function() {
    jQuery("#top-menu-nav").toggleClass("menu-collapse");
  });

  /*Leitor de rss script home*/
  jQuery('#divRss').FeedEk({
    FeedUrl: 'http://www.cultura.gov.br/rss-backup/-/asset_publisher/PBe5d9MJmlrW/rss?p_p_cacheability=cacheLevelPage',
    MaxCount: 5,
    DateFormat: 'DD MMMM YYYY',
    DateFormatLang: 'pt'
  });

  /*Leitor de rss pagina noticias*/
  jQuery('#rss-read').FeedEk({
    FeedUrl: 'http://www.cultura.gov.br/rss-backup/-/asset_publisher/PBe5d9MJmlrW/rss?p_p_cacheability=cacheLevelPage',
    MaxCount: 20,
    DateFormat: 'DD MMMM YYYY',
    DateFormatLang: 'pt'
  });




  /**
   * jQuery Tooltip 
   *
   * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
   * Dual licensed under the MIT and GPL licenses:
   * http://www.opensource.org/licenses/mit-license.php
   * https://osvaldas.info/elegant-css-and-jquery-tooltip-responsive-mobile-friendly
   *
   */


  var targets = jQuery('ul#menu-metas li a , .menu-desktop ul li a'),
    target = false,
    tooltip = false,
    title = false;

  targets.bind('mouseenter', function() {
    target = jQuery(this);
    tip = target.attr('title');
    tooltip = jQuery('<div id="tooltip"></div>');

    if (!tip || tip == '')
      return false;

    target.removeAttr('title');
    tooltip.css('opacity', 0)
      .html(tip)
      .appendTo('body');

    var init_tooltip = function() {
      if (jQuery(window).width() < tooltip.outerWidth() * 1.5)
        tooltip.css('max-width', jQuery(window).width() / 2);
      else
        tooltip.css('max-width', 340);

      var pos_left = target.offset().left + (target.outerWidth() / 2) - (tooltip.outerWidth() / 2),
        pos_top = target.offset().top - tooltip.outerHeight() - 20;

      if (pos_left < 0) {
        pos_left = target.offset().left + target.outerWidth() / 2 - 20;
        tooltip.addClass('left');
      } else
        tooltip.removeClass('left');

      if (pos_left + tooltip.outerWidth() > jQuery(window).width()) {
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
    jQuery(window).resize(init_tooltip);

    var remove_tooltip = function() {
      tooltip.animate({
        top: '-=10',
        opacity: 0
      }, 50, function() {
        jQuery(this).remove();
      });

      target.attr('title', tip);
    };

    target.bind('mouseleave', remove_tooltip);
    tooltip.bind('click', remove_tooltip);
  });





  /**
   * jQuery Carrousel
   *
   * Copyright (c) 2017 Moisés Rabelo 
   * https://jsfiddle.net/moisesrlima/k8xLcjbr/106/
   *
   */
  var jQuerycarousel = jQuery('#menu-metas');
  var jQueryseats = jQuery('.menu-item');

  var jQuerycontroles = "<div class='controls'> <button class='toggle' data-toggle='prev'> <i class='fa fa-angle-left' aria-hidden='true'></i> </button> <button class='toggle' data-toggle='next'>  <i class='fa fa-angle-right' aria-hidden='true'></i> </button></div>";
  jQuery("body:not(.home) .menu-metas-container").after(jQuerycontroles);


  jQuery('.toggle').on('click', function(e) {
    var jQuerynewSeat;
    var jQueryel = jQuery('.is-ref');
    var jQuerycurrSliderControl = jQuery(e.currentTarget);
    // Info: e.target is what triggers the event dispatcher to trigger and e.currentTarget is what you assigned your listener to.

    jQueryel.removeClass('is-ref');
    if (jQuerycurrSliderControl.data('toggle') === 'next') {
      jQuerynewSeat = next(jQueryel);
      jQuerycarousel.removeClass('is-reversing');
    } else {
      jQuerynewSeat = prev(jQueryel);
      jQuerycarousel.addClass('is-reversing');
    }

    jQuerynewSeat.addClass('is-ref').css('order', 1);
    for (var i = 2; i <= jQueryseats.length; i++) {
      jQuerynewSeat = next(jQuerynewSeat).css('order', i);
    }

    jQuerycarousel.removeClass('is-set');
    return setTimeout(function() {
      return jQuerycarousel.addClass('is-set');
    }, 50);

    function next(jQueryel) {
      if (jQueryel.next().length) {
        return jQueryel.next();
      } else {
        return jQueryseats.first();
      }
    }

    function prev(jQueryel) {
      if (jQueryel.prev().length) {
        return jQueryel.prev();
      } else {
        return jQueryseats.last();
      }
    }
  });

});
