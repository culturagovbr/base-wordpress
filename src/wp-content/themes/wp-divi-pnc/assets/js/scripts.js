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
 *
 * Copyright (c) 2017 Moisés Rabelo 
 * https://jsfiddle.net/moisesrlima/k8xLcjbr/106/
 *

*/

var current = jQuery("body:not(.home) .menu-metas-container #menu-metas .current-menu-item a");
var menuMetas = jQuery("body:not(.home)  .menu-metas-container  #menu-metas");
var nextButton = jQuery('.controls button.toggle[data-toggle=next]');
var prevButton = jQuery('.controls button.toggle[data-toggle=prev]');
var $controles = "<div class='controls'> </div>";

if (5 > current) {
  var $prev = "<button class='toggle' data-toggle='prev'>  <i class='fa fa-angle-left'  aria-hidden='true'></i> </button></div>";
}
if (current > 55) {
  var $next = "<button class='toggle' data-toggle='next'>  <i class='fa fa-angle-right' aria-hidden='true'></i> </button></div>";
}

jQuery(".menu-metas-container").after($controles);
jQuery(".controls").append($nex);
jQuery(".controls").prepend($prev);

current = current.text();

prevButton.on('click', function(e) {
  var link = jQuery("body:not(.home) .menu-metas-container #menu-metas .current-menu-item a").text();
  link--;
  location.href = link;
});

nextButton.on('click', function(e) {
  var link = jQuery("body:not(.home) .menu-metas-container #menu-metas .current-menu-item a").text();
  link++;
  location.href = link;
});


//-1436.19px
if (5 < current < 55) {
  var base = 5;
  var m = current - base;
  var f = -400 - (100 * m);
  alert(f);
  menuMetas.animate({
    'left': '' + f + 'px'
  }, 500);
}


nextButton.click(function(event) {
  var left = menuMetas.css("left");
  left = parseInt(left, 10);
  if (left >= -4463.19) {
    menuMetas.animate({
      'left': '-=1150px'
    }, 500);
  }
  event.preventDefault();
});

prevButton.click(function(event) {
  var left = menuMetas.css("left");
  left = parseInt(left, 10);
  if (left <= -289) {
    menuMetas.animate({
      'left': '+=1150px'
    }, 500);
  }
  event.preventDefault();
});
