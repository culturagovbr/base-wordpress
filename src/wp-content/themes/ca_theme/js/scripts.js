jQuery(document).ready(function($) {
    // browser window scroll (in pixels) after which the "back to top" link is shown
    var offset = 300,
        //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
        //duration of the top scrolling animation (in ms)
        scroll_top_duration = 700,
        //grab the "back to top" link
        $back_to_top = $('.cd-top');

    //hide or show the "back to top" link
    $(window).scroll(function() {
        ($(this).scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible'): $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if ($(this).scrollTop() > offset_opacity) {
            $back_to_top.addClass('cd-fade-out');
        }
    });

    //smooth scroll to top
    $back_to_top.on('click', function(event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0,
        }, scroll_top_duration);
    });

    $('[data-toggle="tooltip"]').tooltip()



     $(".fab,.backdrop").click(function(){
        if($(".backdrop").is(":visible")){
            $(".backdrop").fadeOut(125);
            $(".fab.child")
                .stop()
                .animate({
                    bottom  : $("#masterfab").css("bottom"),
                    opacity : 0
                },125,function(){
                    $(this).hide();
                });
        }else{
            $(".backdrop").fadeIn(125);
            $(".fab.child").each(function(){
                $(this)
                    .stop()
                    .show()
                    .animate({
                        bottom  : (parseInt($("#masterfab").css("bottom")) + parseInt($("#masterfab").outerHeight()) + 70 * $(this).data("subitem") - $(".fab.child").outerHeight()) + "px",
                        opacity : 1
                    },125);
            });
        }
    });


        /* Soft scroll */
        $(function() {
            $('.soft-scroll a[href^="#"], a[href^="#"].soft-scroll').bind('click', function(event) {
                var $anchor = $(this);
                var href = $anchor.attr('href');
                try {
                    var elem = $(href);
                    if(elem.length) {
                        $('html, body').stop().animate({
                            scrollTop: elem.offset().top
                        }, 1000);

                        event.preventDefault();
                    }
                }
                catch(err) {}
            });
        });

});