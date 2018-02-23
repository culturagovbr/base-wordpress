(function ($) {
    $(document).ready(function () {
        app.init();
    });

    var app = {
        init: function () {
            // console.log('App init', ecwp);
            this.initPlugins();
        },

        initPlugins: function () {
            // Masonry
            var $grid = $('#editais-culturais').masonry({
                // options
                itemSelector: '.card-wrapper'
            });

            // Infinite scroll
            var controller = new ScrollMagic.Controller();

            var pageNumber = '';
            var scene = new ScrollMagic.Scene({triggerElement: '#loader', triggerHook: 'onEnter'})
                .addTo(controller)
                .on("enter", function (e) {
                    if (!$('#loader').hasClass('active')) {
                        $('#loader').addClass('active');

                        pageNumber = parseInt( $('#editais-culturais').attr('data-page') ) + 1;
                        $.ajax({
                            url: ecwp.api_url + pageNumber,
                            beforeSend: function () {
                                console.log("loading new items");
                            },
                            success: function (data) {
                                if( data.length ){

                                    $.each(data, function (index, card) {
                                        var image = ( typeof card["@files:avatar.avatarMedium"] !== 'undefined' ? card["@files:avatar.avatarMedium"].url : '' );
                                        var desc = ( card.shortDescription !== null ? card.shortDescription.substring(0, 120) : '' );
                                        if( card.shortDescription !== null ) desc =  desc.substr(0, Math.min(desc.length, desc.lastIndexOf(" "))) + '...';

                                        var $template = '<div class="card-wrapper">' +
                                            '<div id="card-'+ card.id +'" class="edital-card">' +
                                            '<a href="'+ card.singleUrl +'" target="_blank">' +
                                            '<div class="card-header">' +
                                            '<div class="card-header-text">' +
                                            '<span class="headline">'+ card.name +'</span>' +
                                            '<span class="subhead">'+ desc +'</span>' +
                                            '</div>' +
                                            '<div class="card-header-media">' +
                                            '<img src="'+ image +'">' +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="card-actions">' +
                                            '<!--<span>Tags:</span>-->';

                                            if( card.terms.tag.length ){
                                                $template += '<ul>';
                                                $.each(card.terms.tag, function (i, el) {
                                                    $template += '<li>'+ el +'</li>';
                                                });
                                                $template += '</ul>';
                                            }

                                        $template += '</div>' +
                                            '</a>' +
                                            '</div>' +
                                            '</div>';

                                        $template = $.parseHTML($template);
                                        $grid.append( $template ).masonry( 'appended', $template );;
                                    });

                                    $('#editais-culturais').attr('data-page', pageNumber);
                                } else {
                                    // console.log( 'No more!' );
                                    setTimeout(function () {
                                        $('#loader').removeClass('active');
                                    }, 3000)
                                }
                            },
                            complete: function () {
                                // console.log( 'Loaded!' );
                                setTimeout(function () {
                                    $('#loader').removeClass('active');
                                }, 3000)
                            },
                            error: function (error) {
                                console.error(error);
                            }
                        });
                    }
                });

            // pseudo function to add new content. In real life it would be done through an ajax request.
            function addBoxes (amount) {



            }

        }
    };
})(jQuery);