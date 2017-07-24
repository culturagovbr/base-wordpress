jQuery(document).ready(function($) {

  //*** Função Menu collapse responsivo*** 
  $(".menu-responsivo").on("click", function() {
    $("#top-menu-nav").toggleClass("menu-collapse");
  });

  /*Leitor de rss script home*/
  $('#divRss').FeedEk({
    FeedUrl: 'http://www.cultura.gov.br/rss-backup/-/asset_publisher/PBe5d9MJmlrW/rss?p_p_cacheability=cacheLevelPage',
    MaxCount: 5,
    DateFormat: 'DD MMMM YYYY',
    DateFormatLang: 'pt'
  });

  /*Leitor de rss pagina noticias*/
  $('#rss-read').FeedEk({
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


  var targets = $('div#banner-topo-cinza ul#menu-metas li a , .menu-desktop ul li a'),
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





  /**
   * jQuery Carrousel
   *
   * Copyright (c) 2017 Moisés Rabelo 
   * https://jsfiddle.net/moisesrlima/k8xLcjbr/106/
   *
   */
  var $carousel = $('body:not(.home) #menu-metas');
  var $seats = $('body:not(.home) .menu-item');


  var $controles = "<div class='controls'> <button class='toggle' data-toggle='prev'> <i class='fa fa-angle-left' aria-hidden='true'></i> </button> <button class='toggle' data-toggle='next'>  <i class='fa fa-angle-right' aria-hidden='true'></i> </button></div>"
  $("body:not(.home) .menu-metas-container").after($controles);


  $('.toggle').on('click', function(e) {
    var $newSeat;
    var $el = $('.is-ref');
    var $currSliderControl = $(e.currentTarget);

    $el.removeClass('is-ref');
    if ($currSliderControl.data('toggle') === 'next') {
      $newSeat = next($el);
      $carousel.removeClass('is-reversing');
    } else {
      $newSeat = prev($el);
      $carousel.addClass('is-reversing');
    }

    $newSeat.addClass('is-ref').css('order', 1);
    for (var i = 2; i <= $seats.length; i++) {
      $newSeat = next($newSeat).css('order', i);
    }

    $carousel.removeClass('is-set');
    return setTimeout(function() {
      return $carousel.addClass('is-set');
    }, 50);

    function next($el) {
      if ($el.next().length) {
        return $el.next();
      } else {
        return $seats.first();
      }
    }

    function prev($el) {
      if ($el.prev().length) {
        return $el.prev();
      } else {
        return $seats.last();
      }
    }
  });

});
