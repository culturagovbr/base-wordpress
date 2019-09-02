
(function () {

    var IndexSlider = function (obj) {

        //private properties
        var _self = this,
                _obj = obj,
                _window = $(window),
                _swiper = null,
                _swiperContainer = _obj.find('.swiper-container');

        //private methods
        var _addEvents = function () {
            _window.on({
                resize: function () {
                    _updateSize();
                }
            });
            _swiperContainer.on('click', '.video-card__lnk', function () {

                clearTimeout(self.timer);

                _obj.append('<div class="slider__video">\
                                <button class="slider__close">&#215;</button>\
                                <iframe width="100%" height="100%" src="' + $(this).attr('data-video') + '" frameborder="0" allowfullscreen></iframe>\
                            </div>');

                $('.slider__close').on({
                    click: function () {
                        $('.slider__close').parent().remove();
                    }
                });


                return false;
            });
        },
                _createSwiper = function () {
                    _swiper = new Swiper(_swiperContainer, {
                        nextButton: _swiperContainer.find('.swiper-button-next'),
                        prevButton: _swiperContainer.find('.swiper-button-prev'),
                        pagination: _swiperContainer.find('.swiper-pagination'),
                        paginationClickable: true,
                        spaceBetween: 0,
                        loop: true,
                        onInit: function (swiper) {
                            if ((swiper.slides.length - swiper.loopedSlides * 2) === 1) {
                                swiper.lockSwipes();
                                $('.swiper-button-prev').hide();
                                $('.swiper-button-next').hide();
                            }
                        }
                    });
                },
                _init = function () {
                    _updateSize();
                    _createSwiper();
                    _addEvents();
                },
                _updateSize = function () {
                    if (_window.width() < 768) {
                        _obj.height(_window.height() - 36);
                    } else {
                        _obj.height(_window.height());
                    }

                };

        //public properties

        //public methods


        _init();
    };

    if (document.getElementsByClassName('page')[0].className.indexOf('page_active') >= 0) {
        $(function () {
            $.each($('.index'), function () {
                var curItem = $(this);

                new IndexSlider(curItem);
            });
        });
    } else {
        $.each($('.page .index'), function () {
            var curItem = $(this);

            new IndexSlider(curItem);
        });
    }


}());