window.requestAnimFrame = (function () {
    return window.requestAnimationFrame ||
            function (callback) {
                // for ie
                window.setTimeout(callback, 1000 / 60);
            };
})();

document.addEventListener('touchmove', function (e) {
    e.preventDefault();
}, false);

window.onpopstate = function (event) {

    if (event.state && event.state.page_id && event.state.path) {
        var newPage = new Page();
        newPage.core.setSection(event.state.page_id, event.state.path);
        newPage.core.openPage('page_' + event.state.path);
        newPageMenuItem = $('nav.menu a[data-page=' + event.state.page_id + ']');
        if (newPageMenuItem.length) {
            $('nav.menu a').removeClass('active');
            newPageMenuItem.addClass('active');
        }
    }
    if (event.state && event.state.title) {
        document.title = event.state.title;
    }
};

var aresSelects = [];

var myScrollChecker = false;

function getQueryString(url, name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(url);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

(function () {
    var instagram;
    var currentMap;

    $(function () {
        new Page();
    });

}());

var Page = function () {
    this.start = true;
    this.scrolls = [];
    this.request = new XMLHttpRequest();
    this.loc = null;
    this.init();
};
Page.prototype = {
    init: function () {
        var self = this;
        self.core = self.core();
        self.core.build();
        self.core.buildStructure(document);
    },
    core: function () {
        var self = this;
        return {
            addCountDown: function () {
                if (self.window.width() >= 970 && !self.countDounLoaded) {
                    if ($('.countdown').length > 0) {
                        var dateParts = $('.countdown').data('date').split(/[- :]/);
                        $('.countdown').countdown({until: new Date(dateParts[0], dateParts[1] - 1, dateParts[2], dateParts[3], dateParts[4], dateParts[5])});
                    }
                }
            },
            addEvents: function () {
                self.btnMenu.on({
                    click: function () {
                        var status = parseInt(localStorage.getItem('menu_status'));
                        if (status) {
                            localStorage['menu_status'] = 0;
                       } else {
                            localStorage['menu_status'] = 1;
                        }
                        self.page.toggleClass('site_opened');

                        if ($('.logo img').css('height') == '48px') {
                            $('.logo img').animate({
                                'height':'96px',
                                'width': '42px',
                                'margin-left': '0px',
                                'margin-top': '0px'
                            }, 300);
                        } else {
                           $('.logo img').animate({
                                'height': '48px',
                                'width': '21px',
                                'margin-left': '10px',
                                'margin-top': '-20px'
                            }, 300);
                        }

                        return false;
                    }
                });
                self.window.on({
                    resize: function () {

                        self.core.checkMenu();
                        self.core.addCountDown();
                    },
                    load: function () {
                        self.core.loadPages();
                    }
                });
                window.addEventListener('popstate', function (e) {
                    //if (device.ios()) {
                    //
                    //    if (!self.start) {
                    //        if (!e.state) {
                    //            console.log(2);
                    //            self.core.openPage('page_index');
                    //        } else {
                    //            self.core.openPage('page_' + e.state.path);
                    //        }
                    //    }
                    //
                    //    self.start = false;
                    //
                    //} /*else {
                    //    if (!e.state) {
                    //        self.core.openPage('page_index');
                    //    } else {
                    //        self.core.openPage('page_' + e.state.path);
                    //    }
                    //}*/


                });
                self.pageLnk.on({
                    click: function () {
                        var curItem = $(this);
                        self.core.setSection($(this).data('page'), $(this).attr('href'));

                        if (!curItem.hasClass('active') && !self.action) {
                            self.pageLnk.removeClass('active');
                            curItem.addClass('active');
                            if (self.window.width() < 970) {
                                self.page.removeClass('site_opened');

                                //for css animation
                                setTimeout(function () {
                                    self.core.checkHeaderScroll();
                                }, 300);
                            }
                            self.core.setToHistory($(this));
                            self.core.openPage('page_' + $(this).attr('href'));

                            // subitem opening on reduced menu
                            if (!jQuery('.site').hasClass('site_opened')) {
                                if (!curItem.parents('.menu__item_opened').length) {
                                    var opened = self.menuItem.filter('.menu__item_opened'),
                                            curMenu;
                                    $.each(opened, function () {
                                        curMenu = $(this);

                                        curMenu.find('>div')
                                                .stop(false, true)
                                                .slideUp({
                                                    duration: 300,
                                                    easing: 'easeInQuad',
                                                    complete: function () {
                                                        self.core.checkHeaderScroll();
                                                    }
                                                });
                                        curMenu.removeClass('menu__item_opened');

                                    });
                                }
                            }

                        } else if (!self.action) {
                            self.core.reloadPage('page_' + $(this).attr('href'));
                            if (self.window.width() < 970) {
                                self.page.removeClass('site_opened');

                                //for css animation
                                setTimeout(function () {
                                    self.core.checkHeaderScroll();
                                }, 300);
                            }
                        }

                        return false;
                    }
                });
                self.menuItemToggle.on({
                    click: function () {
                        var curItem = $(this).closest('.menu__item'),
                                tempItem;

                        curItem.toggleClass('menu__item_opened');

                        if (curItem.hasClass('menu__item_opened')) {
                            curItem.find('>div')
                                    .stop(false, true)
                                    .slideDown({
                                        duration: 300,
                                        easing: 'easeInQuad',
                                        complete: function () {
                                            self.core.checkHeaderScroll();
                                        }
                                    });
                        } else {
                            curItem.find('>div')
                                    .stop(false, true)
                                    .slideUp({
                                        duration: 300,
                                        easing: 'easeInQuad',
                                        complete: function () {
                                            self.core.checkHeaderScroll();
                                        }
                                    });
                        }

                        $.each(self.menuItem, function () {
                            tempItem = $(this);

                            if (this != curItem[0] && $) {
                                tempItem.removeClass('menu__item_opened');
                                tempItem.find('>div')
                                        .stop(false, true)
                                        .slideUp({
                                            duration: 300,
                                            easing: 'easeInQuad',
                                            complete: function () {
                                                self.core.checkHeaderScroll();
                                            }
                                        });
                            }
                        });

                        return false;
                    }
                });
                self.arrow.on({
                    click: function () {
                        self.headerWrap.animate({
                            scrollTop: self.headerWrap[0].scrollHeight
                        }, 300);

                        return false;
                    }
                });
                self.headerWrap.on({
                    scroll: function () {
                        self.core.checkHeaderScroll();
                    }
                });
            },
            addHeaderScroll: function () {
                self.headerScroll = self.headerWrap.niceScroll({
                    cursorwidth: "0px",
                    horizrailenabled: false,
                    cursorborder: false,
                    railpadding: {
                        top: 0,
                        right: 1,
                        left: 0,
                        bottom: 0
                    }
                });
            },
            checkNiceScroll: function () {
                var pages = $('.page');

                $.each(self.scrolls, function () {
                    this.remove();

                });
                self.scrolls = [];
                if (self.scrolls.length < pages.length) {

                    self.scrolls.push(
                            pages.eq(-1).find('.page__scroll').niceScroll({
                        horizrailenabled: false,
                        zindex: 5,
                        railpadding: {
                            top: 0,
                            right: 1,
                            left: 0,
                            bottom: 0
                        }
                    })
                            );
                }
            },
            build: function () {
                self.core.getElements();
                self.core.checkMenuOnLoad();
                self.core.addHeaderScroll();

                self.core.addCountDown();

                self.core.renderLoop();

                self.core.addEvents();
                self.core.checkNiceScroll();
            },
            checkHeaderScroll: function () {

                if (self.headerWrap.find('>div').height() <= self.headerWrap.scrollTop() + self.headerWrap.height()) {
                    self.header.addClass('site__header_scrolled');
                } else {
                    self.header.removeClass('site__header_scrolled');
                }
            },
            checkMenu: function () {
                if (self.window.width() < 970) {
                    self.page.addClass('site_noanimate');
                    self.page.removeClass('site_opened');

                    //for css animation
                    setTimeout(function () {
                        self.page.removeClass('site_noanimate');
                        self.core.checkHeaderScroll();

                    }, 100);
                }
                self.core.checkHeaderScroll();
            },
            checkMenuOnLoad: function () {
                if (self.window.width() >= 970) {

                    self.page.addClass('site_noanimate');

                    //if(!localStorage.getItem('menu_status')){
                    //    self.page.addClass('site_opened');
                    //    localStorage['menu_status'] = 1;
                    //} else {
                    //    var status = parseInt(localStorage.getItem('menu_status'));
                    //
                    //    if(status){
                    //
                    //        self.page.addClass('site_opened');
                    //    }
                    //}

                    // for css animation
                    setTimeout(function () {
                        self.page.removeClass('site_noanimate');
                        self.core.checkHeaderScroll();
                    }, 1);

                } else {
                    self.page.removeClass('site_opened');
                }
            },
            getElements: function () {
                self.page = $('.site');
                self.header = self.page.find('.site__header');
                self.headerWrap = self.header.find('.site__header-wrap');
                self.content = self.page.find('.site__content');
                self.btnMenu = self.page.find('.site__header-btn');
                self.menuItem = self.page.find('div.menu__item');
                self.pages = self.page.find('.page');
                self.pageLnk = self.page.find('.page-lnk');
                self.arrow = self.page.find('.site__header-arrow');
                self.window = $(window);
                self.loadingPages = [];
                self.countDounLoaded = false;
                self.action = false;
                self.menuItemToggle = self.page.find('div.menu__item .fa-plus, div.menu__item .fa-minus');
            },
            setSection: function (page_id, section) {

                self.pages = self.page.find('.page');

                if (!self.pages.filter('.page_' + section).length) {
                    self.content.append('<div class="page page_' + section + ' light" data-id="' + page_id + '"><div class="page__scroll"><div></div></div></div>');
                }
                self.pages = self.page.find('.page');

            },
            loadPage: function (page, path) {
                var page_id = page.data('id'),
                        t = path[0];
                page.addClass('page_loading');
                self.request.abort();
                self.request = $.ajax({
                    url: ajaxurl,
                    data: {
                        page_id: page_id,
                        action: 'get_section'
                    },
                    dataType: 'html',
                    timeout: 5000,
                    type: 'POST',
                    success: function (msg) {
                        page.find('.page__scroll > div').html(msg);
                        page.append('<script>$(".page_' + t + '").addClass("page_loaded");  $(".page_' + t + '").removeClass("page_loading");</script>');
                        self.core.checkNiceScroll();
                        self.core.buildStructure(page);
                    },
                    error: function (XMLHttpRequest) {
                        self.content.addClass('site__content_load');
                        if (XMLHttpRequest.statusText != "abort") {
                            page.removeClass('page_loading');
                        }
                    }
                });
            },
            reloadPage: function (pageClass) {
                var page = self.pages.filter('.' + pageClass),
                        path = [pageClass.replace('page_', '')],
                        page_id = page.data('id'),
                        t = path[0];

                self.action = true;
                self.content.addClass('site__content_load');

                self.request.abort();
                self.request = $.ajax({
                    url: ajaxurl,
                    data: {
                        page_id: page_id,
                        action: 'get_section'
                    },
                    dataType: 'html',
                    timeout: 5000,
                    type: 'POST',
                    success: function (msg) {
                        page.find('.page__scroll > div').html(msg);
                        page.append('<script>$(".page_' + t + '").addClass("page_loaded");  $(".page_' + t + '").removeClass("page_loading");</script>');
                        self.core.checkNiceScroll();
                        self.core.buildStructure(page);
                        setTimeout(function () {
                            self.content.removeClass('site__content_load');
                            self.action = false;
                        }, 300);

                    },
                    error: function (XMLHttpRequest) {
                        self.content.addClass('site__content_load');
                        if (XMLHttpRequest.statusText != "abort") {
                            page.removeClass('page_loading');
                        }
                    }
                });

                $('.ares-select__popup').remove();
            },
            loadPages: function () {
                if (self.page.hasClass('autoload')) {

                    var curItem = null,
                            path = [], classArr = [];


                    $.each(self.pages, function () {
                        curItem = $(this);

                        if (!curItem.hasClass('page_loaded') && !curItem.hasClass('page_loading')) {
                            classArr = curItem.attr('class').split(' ');

                            $.each(classArr, function () {

                                if (this != 'page') {
                                    path[0] = this.replace('page_', '');
                                }
                            });

                            self.core.loadPage(curItem, path);
                        }

                    });
                    $('.ares-select__popup').remove();
                }
            },
            openPage: function (pageClass) {
                $('.ares-select__popup').remove();
                var page = self.pages.filter('.' + pageClass);
                if (self.pages.filter('.page_active')[0] != page[0]) {
                    self.pageClass = pageClass;
                    if (page.hasClass('page_loaded')) {
                        if (!self.action) {
                            self.action = true;

                            self.content.removeClass('site__content_load');

                            //for css animation
                            setTimeout(function () {
                                if (myScrollChecker) {
                                    myScrollChecker = false;
                                    self.pages.filter('.page_active').removeClass('page_active');
                                    page.addClass('page_active');
                                    self.loadingPages = [];
                                    self.action = false;
                                } else {
                                    $('.news__popup, .schedule__popup, .gallery__video').removeClass('active');
                                    self.pages.filter('.page_move-bottom').removeClass('page_move-bottom');
                                    self.loadingPages = [];
                                    self.pages.filter('.page_active').addClass('page_move-bottom');
                                    page.addClass('page_move-top');

                                    //for css animation
                                    setTimeout(function () {
                                        self.pages.filter('.page_active').remove();
                                        page.removeClass('page_move-top');
                                        page.addClass('page_active');
                                        self.action = false;
                                        self.core.refreshScrolls();
                                        self.pages = self.page.find('.page');
                                    }, 700);
                                }

                            }, 300);
                        }
                    } else {

                        self.content.addClass('site__content_load');
                        if (!page.hasClass('page_loading')) {
                            self.core.loadPage(page, [pageClass.replace('page_', '')]);
                        }
                        self.loadingPages[0] = page;
                    }
                }

            },
            refreshScrolls: function () {
                $.each(self.scrolls, function (i) {
                    this.resize();
                });
            },
            renderLoop: function () {
                requestAnimFrame(self.core.renderLoop);

                $.each(self.loadingPages, function () {
                    if (this.hasClass('page_loaded')) {

                        self.core.openPage(self.pageClass);
                    }

                });
            },
            setToHistory: function (lnk) {
                /*url = lnk.data('url'),
                 //url = 'http://www.google.com',
                 path = url;
                 if (title != undefined) {
                 title = 'Khore - ' + title;
                 if (url == 'index')
                 url = '';
                 url = base_url + url;
                 } else {
                 title = 'Khore';
                 url = base_url;
                 }*/
                base_url = $('#base_url').val();
                url = lnk.data('url');
                path = lnk.data('url');
                if (url === 'index') {
                    url = base_url;
                } else {
                    url = base_url + url;
                }
                //title = lnk.find('span').text();
                title = lnk.data('title');
                window.history.pushState({
                    title: title,
                    url: url,
                    path: path,
                    page_id: lnk.attr('data-page')
                }, title, url);
                document.title = title;
            },
            buildStructure: function (container) {
                //var section = $(container).find('[data-section]:first');
                var sections = $(container).find('[data-section]');
                //if (section && section.length > 0 && section.attr('data-section').length) {
                if (sections && sections.length > 0) {
                    sections.each(function (i, el) {
                        section = jQuery(el);
                        if (section.attr('data-section')) {
                            switch (section.attr('data-section')) {
                                case 'news':
                                    new News($(section), $(section).closest('.page').attr('data-id'));
                                    break;
                                case 'schedule':
                                    new Schedule($(section), self.scrolls, true);
                                    break;
                                case 'twittering':
                                    if ($('#twitter_update_list').length) {
                                        $('#twitter_update_list').tweetMachine(
                                                '#' + khore_hash, {
                                                    backendScript: ajaxurl,
                                                    tweetFormat: false,
                                                    limit: khore_count,
                                                    rate: 30000
                                                },
                                        function (tweets, tweetsDisplayed) {
                                            tweets_html = '';
                                            if (tweets && tweets.length) {
                                                for (var i = tweets.length - 1; i >= 0; i--) {
                                                    tweets_html += '<div class="twittering__item col-md-4 col-xs-12">';
                                                    tweets_html += '<div>\
                                                                <p>' + this.parseText(tweets[i].text) + '</p>\
                                                                <a href="http://twitter.com/' + tweets[i].user.screen_name + '" target="_blank"> <img class="tweet-image avatar" src="' + tweets[i].user.profile_image_url.replace("normal", "reasonably_small") + '" alt="" width="50" height="50" /></a>\
                                                                <span>' + this.relativeTime(tweets[i].created_at) + '\
                                                                <a href="http://twitter.com/' + tweets[i].user.screen_name + '" target="_blank">@' + tweets[i].user.screen_name + '</a>\
                                                                </span>\
                                                                </div>';
                                                    tweets_html += '</div>';
                                                }
                                            }
                                            $('#twitter_update_list').html(tweets_html);
                                        });
                                    }
                                    break;
                                case 'inst':
                                    instagram = new Inst($(section), null, true);
                                    break;
                                case 'location':
                                    self.loc = null;
                                    if (device.mobile()) {
                                        self.loc = new Location($(section), true);
                                    } else {
                                        self.loc = new Location($(section));
                                    }
                                    break;
                                case 'contact':
                                    Recaptcha.create(recaptchaPublicKey,
                                            "recaptcha_widget", {
                                                theme: "blackglass",
                                                callback: Recaptcha.focus_response_field
                                            }
                                    );
                                    $('.contact__feedback').submit(function (e) {
                                        var hasError = false;
                                        $('.text-field', this).removeClass('text-field_error');
                                        if (!hasError) {
                                            $('.contact__feedback .alert, .contact__feedback .info').remove();
                                            $.ajax({
                                                url: ajaxurl,
                                                data: $(this).serialize(),
                                                dataType: 'json',
                                                type: 'POST',
                                                success: function (data) {

                                                    if (data.sent === true) {
                                                        $('.contact__feedback').slideUp("fast", function () {
                                                            $('.contact__feedback').before('<p class="info">' + data.message + '</p>');
                                                        });
                                                    }
                                                    else {
                                                        if (data.errorField != '')
                                                            $('[name=' + data.errorField + ']').closest('.text-field').addClass('text-field_error');
                                                        $('.contact__feedback').before('<p class="alert">' + data.message + '</p>');
                                                    }
                                                },
                                                error: function (data) {
                                                    $('.contact__feedback').before('<p class="alert">' + data.message + '</p>');
                                                }
                                            });
                                        }
                                        return false;
                                    });
                                    break;
                                case 'sponsors':
                                    new Sponsors(section.parents('.page__scroll'));
                                    $('.sponsors__item').on({
                                        click: function () {

                                            $(this).toggleClass('sponsors__item_flipped');

                                        }
                                    });
                                    var curItem;
                                    $('.sponsors__item').each(function () {
                                        curItem = $(this);
                                        curItem.removeClass('small');

                                        if (curItem.width() < 210) {
                                            curItem.addClass('small');
                                        }
                                        if (curItem.parents('.sponsors__content_bronze').length) {
                                            curItem.addClass('small');
                                        }
                                    });
                                    $(window).on({
                                        resize: function () {
                                            // enclose in function
                                            // same as previous lines
                                            var curItem;

                                            $('.sponsors__item').each(function () {
                                                curItem = $(this);
                                                curItem.removeClass('small');

                                                if (curItem.width() < 210) {
                                                    curItem.addClass('small');
                                                }
                                                if (curItem.parents('.sponsors__content_bronze').length) {
                                                    curItem.addClass('small');
                                                }
                                            });
                                        }
                                    });
                                    break;
                                case 'gallery':
                                    new Gallery($(section));
                                    break;
                            }
                        }
                    });
                }

                $('.text-field').each(function () {
                    new TextField($(this));
                });

                new IndexSlider($('.page .index'));
                // new IndexSlider($('.index'));

                //scroll for single pages
                jQuery('.page.single, .page.single-speaker, .page.single-session, .page.page-category, .page.page-archive').niceScroll();
            }
        };
    }
};

var Sponsors = function (obj) {

    //private properties
    var _self = this,
            _obj = obj,
            _window = $(window),
            _containers = _obj.find('.sponsors__content'),
            _titles = _obj.find('.sponsors___content-title');

    //private methods
    var _addEvents = function () {
        _obj.on({
            scroll: function () {
                _checkScroll();
            }
        });
        _window.on({
            resize: function () {
                _checkTitleWidth();
            }
        });
    },
            _checkScroll = function () {
                var curScrollPos = _obj.scrollTop(),
                        curTitle = null,
                        container = null, newTitleTop = null;

                _titles.removeClass('sponsors__content_fixed');
                _titles.removeClass('sponsors__content_absolute');
                if (_msieversion()) {
                    _titles.css({
                        left: 0
                    });
                }

                if (_window.width() >= 1025 && _containers.length) {

                    for (i = _containers.length - 1; i >= 0; i--) {

                        var curContainer = _containers.eq(i),
                                curContainerPos = curContainer.position().top - curScrollPos;

                        if (curContainerPos < 0) {

                            container = curContainer;
                            curTitle = curContainer.find('.sponsors___content-title');
                            newTitleTop = -curContainerPos;

                            if (((container.height() - curTitle.height()) + curContainerPos) < 0) {
                                curTitle.addClass('sponsors__content_absolute');

                            } else {
                                curTitle.addClass('sponsors__content_fixed');
                                if (_msieversion()) {
                                    curTitle.css({
                                        left: $('.site__header').width()
                                    });
                                }
                            }


                        }
                    }
                }
            },
            _checkTitleWidth = function () {
                $.each(_titles, function () {
                    var curItem = $(this);

                    curItem.outerWidth(curItem.parent().width());
                });
            },
            _checkSponsorsHeight = function () {
                $(_obj).find('.sponsors__item').each(function (i, el) {
                    $(el).outerHeight($(el).outerWidth() / 1.5);
                });
            },
            _msieversion = function () {

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer, return version number
                    return true;
                else                 // If another browser, return 0
                    return false;


            },
            _init = function () {
                _checkTitleWidth();
                _addEvents();
                _checkScroll();
            };

    //public properties

    //public methods


    _init();
};

var AresSelect = function (params) {
    this.obj = params.obj;
    this.optionType = params.optionType || 0;
    this.showType = params.showType || 0;
    this.visible = params.visible || 5;
    this.selects = params.selects || [];

    this.init();
};
AresSelect.prototype = {
    init: function () {
        var self = this;

        self.core = self.core();
        self.core.build();
    },
    core: function () {
        var self = this;

        return {
            build: function () {
                self.core.start();
                self.core.controls();
            },
            start: function () {
                self.device = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                self.text = $('<span class="ares-select__item"></span>');
                self.wrap = $('<div class="ares-select"></div>');
                self.window = $(window);
                self.opened = false;


                self.core.addWraper();
                if (!self.optionType || self.device) {
                    self.core.setMobileView();
                } else if (self.optionType == 1) {
                    self.core.setCustom1();
                }

                self.obj[0].customSelect = this;
            },
            setMobileView: function () {
                self.wrap.addClass('ares-select_mobile');
            },
            setCustom1: function () {
                self.wrap.addClass('ares-select_custom');
            },
            destroy: function () {
                self.text.remove();
                self.wrap.unwrap();
            },
            addWraper: function () {
                var curText = '';

                self.obj.css({
                    opacity: 0
                });

                self.obj.wrap(self.wrap);
                self.wrap = self.obj.parent();
                self.obj.before(self.text);
                self.obj.find('option').each(function () {
                    var curItem = $(this);
                    if (curItem.attr('selected') == 'selected') {
                        curText = curItem.text();
                    }
                });

                if (curText == '') {

                    curText = self.obj.find('option').eq(0).text();
                }

                self.text.html(curText);
            },
            showPopup: function () {
                var list = $('<ul></ul>'),
                        curScroll = self.window.scrollTop(),
                        offset = self.wrap.offset(),
                        maxHeight = 0,
                        curIndex = self.obj.find('option:selected').index(),
                        id = Math.round(Math.random() * 1000);



                if (self.opened) {
                    self.popup.remove();
                }
                self.opened = true;

                self.popup = $('<div class="ares-select__popup" id="ares-select__popup' + id + '"></div>');

                $.each(self.selects, function (i) {
                    if (this.popup) {
                        if (this.popup[0] != self.popup[0] && this.opened) {
                            this.core.hidePopup();
                        }
                    }

                });

                self.obj.find('option').each(function (i) {
                    var curItem = $(this);

                    if (curItem.attr('disabled') != 'disabled')
                        if (i == curIndex) {
                            list.append('<li class="active" data-value="' + curItem.val() + '">' + curItem.text() + '</li>');
                        } else {
                            list.append('<li data-value="' + curItem.val() + '">' + curItem.text() + '</li>');
                        }

                });

                self.popup.append(list);
                $('body').append(self.popup);

                self.popup.css({
                    width: self.wrap.outerWidth() - 1,
                    left: offset.left,
                    top: offset.top + self.wrap.outerHeight()
                });

                maxHeight = self.popup.outerHeight();
                if (maxHeight > ((self.popup.find('li').eq(0).outerHeight() + 2) * self.visible) - 2) {
                    self.popup.height(((self.popup.find('li').eq(0).outerHeight() + 2) * self.visible) - 2);
                    self.scroll = self.popup.niceScroll({
                        horizrailenabled: false,
                        zindex: 10,
                        autohidemode: false,
                        railpadding: {
                            top: 0,
                            right: 1,
                            left: 0,
                            bottom: 0
                        }
                    });
                }

                if (self.showType == 1) {
                    self.popup.css({
                        display: 'none'
                    });
                    self.popup.slideDown(300);
                } else if (self.showType == 2) {
                    self.popup.css({
                        opacity: 0.1
                    });
                    self.popup.animate({opacity: 1}, 300);
                }

                self.popup.find('li').on({
                    'click': function (event) {
                        var event = event || window.event,
                                value = $(this).data('value');

                        if (event.stopPropagation) {
                            event.stopPropagation()
                        } else {
                            event.cancelBubble = true
                        }

                        self.obj.val(self.obj.find('option[value="' + value + '"]').attr('value'));
                        self.obj.trigger('change');
                        self.core.hidePopup();

                    }
                });

            },
            hidePopup: function () {
                self.opened = false;
                if (!self.showType) {
                    self.popup.remove();
                } else if (self.showType == 1) {
                    self.popup.stop(true, false).slideUp(300, function () {
                        self.popup.remove();
                    });
                } else if (self.showType == 2) {
                    self.popup.fadeOut(300, function () {
                        self.popup.remove();
                    });
                }
            },
            controls: function () {
                self.obj.on('change', function () {
                    self.text.text($(this).find('option:selected').text());
                });

                if (self.optionType == 1 && !self.device) {
                    self.wrap.on({
                        'click': function (event) {
                            var event = event || window.event;

                            if (event.stopPropagation) {
                                event.stopPropagation()
                            } else {
                                event.cancelBubble = true
                            }

                            if (self.opened) {
                                self.core.hidePopup();
                            } else {
                                self.core.showPopup();
                            }

                        }
                    });
                    $('body').on({
                        'click': function () {
                            if (self.opened) {
                                self.core.hidePopup();
                            }
                        }
                    });

                }
                self.window.on({
                    resize: function () {
                        if (self.opened) {
                            self.core.hidePopup();
                        }
                    }
                });
            }
        };
    }
};

var Schedule = function (obj, scrolls) {
    this.obj = obj;
    this.content = this.obj.find('.schedule__content');
    this.wrap = this.obj.find('.schedule__wraper');
    this.title = this.wrap.find('.schedule__title-wrap');
    this.titleWrapp = this.wrap.find('> header');
    this.nextLnk = this.obj.find('.schedule__card-title a.next');
    this.prevLnk = this.obj.find('.schedule__card-title a.prev');
    this.scrollWrap = this.obj.find('.schedule__scroll');
    this.scrollPopup = this.obj.find('schedule__popup');
    this.card = this.obj.find('.schedule__card');
    this.filter = this.obj.find('.schedule__filter');
    this.popup = this.obj.find('.schedule__popup');
    this.popupWrap = this.popup.find('.schedule__popup-wrap');
    this.closePopupBtn = this.obj.find('.schedule__popup-close');
    this.request = new XMLHttpRequest();
    this.window = $(window);
    this.scrollPos = 0;
    this.scrollGlobal = scrolls[$('.page__scroll').index(this.obj.parents('.page__scroll'))];

    this.init();
};
Schedule.prototype = {
    init: function () {
        var self = this;

        self.core = self.core();
        self.core.build();
    },
    core: function () {
        var self = this;

        return {
            addCardEvent: function () {
                self.card.on({
                    click: function () {
                        $(this).toggleClass('schedule__card_flip');
                    }
                });
                self.core.addInfoEvents();
            },
            addContentScroll: function () {
                self.scroll = self.scrollWrap.niceScroll({
                    horizrailenabled: false,
                    zindex: 5,
                    hidecursordelay: 100,
                    railpadding: {
                        top: 0,
                        right: 1,
                        left: 0,
                        bottom: 0
                    }
                });
            },
            addEvents: function () {
                self.core.addCardEvent();
                self.core.addNextLinksEvent();

                $('.schedule__filter .session_dates, .schedule__filter .schedule_tracks, .schedule__filter .session_locations').on({
                    change: function () {
                        self.core.getContent()

                        return false;
                    }
                });
                self.closePopupBtn.on({
                    click: function () {
                        self.core.hidePopup();
                        window.history.back();
                        return false;
                    }
                });

                self.window.on({
                    resize: function () {
                        self.core.chekRowPosition();
                        self.core.setSize();
                    }
                });

                self.scrollWrap.on({
                    scroll: function () {
                        self.core.chekRowPosition();
                        self.core.checkTitle();
                        $.each(self.selects, function () {
                            if (this.popup) {
                                this.opened = false;
                                this.popup.remove()
                            }
                        });
                    }
                });
                self.obj.parents('.page__scroll').on({
                    scroll: function () {
                        self.core.chekRowPosition();
                        self.core.checkTitle();
                        $.each(self.selects, function () {
                            if (this.popup) {
                                this.opened = false;
                                this.popup.remove()
                            }

                        });
                    }
                });
            },
            addNextLinksEvent: function () {
                self.nextLnk.on({
                    click: function () {
                        var totalScrollPosition = self.obj.parents('.page__scroll').scrollTop(),
                                titleRows = $('.schedule__card-title'),
                                currentTitleRow = $(this).parents('.schedule__card-title'),
                                scheduleContents = $('.schedule__content'),
                                curIndex = titleRows.index(currentTitleRow);

                        if (self.window.width() >= 1025) {
                            if (curIndex < titleRows.length) {

                                var newScrollPosition = (scheduleContents.eq(curIndex + 1).offset().top + self.scrollWrap.scrollTop() - self.titleWrapp.outerHeight(true));

                                self.scrollWrap.animate({
                                    scrollTop: newScrollPosition
                                }, {
                                    duration: 300,
                                    easing: 'easeInOutQuad'
                                });
                                self.obj.parents('.page__scroll').animate({
                                    scrollTop: 1
                                }, {
                                    duration: 300,
                                    easing: 'easeInOutQuad'
                                });
                            }
                        } else {
                            if (curIndex < titleRows.length) {

                                var newScrollPosition = (scheduleContents.eq(curIndex + 1).position().top + self.titleWrapp.outerHeight(true));

                                if (self.window.width() < 768) {
                                    newScrollPosition -= 36;
                                }
                                self.obj.parents('.page__scroll').animate({
                                    scrollTop: newScrollPosition
                                }, {
                                    duration: 300,
                                    easing: 'easeInOutQuad'
                                });
                            }
                        }

                        return false;
                    }
                });
                self.prevLnk.on({
                    click: function () {
                        var totalScrollPosition = self.obj.parents('.page__scroll').scrollTop(),
                                titleRows = $('.schedule__card-title'),
                                currentTitleRow = $(this).parents('.schedule__card-title'),
                                scheduleContents = $('.schedule__content'),
                                curIndex = titleRows.index(currentTitleRow);

                        if (self.window.width() >= 1025) {
                            if (curIndex > 0) {

                                var newScrollPosition = (scheduleContents.eq(curIndex - 1).offset().top + self.scrollWrap.scrollTop() + totalScrollPosition) - self.titleWrapp.outerHeight(true);

                                self.scrollWrap.animate({
                                    scrollTop: newScrollPosition
                                }, {
                                    duration: 300,
                                    easing: 'easeInOutQuad'
                                });
                                self.obj.parents('.page__scroll').animate({
                                    scrollTop: 1
                                }, {
                                    duration: 300,
                                    easing: 'easeInOutQuad'
                                });
                            }
                        } else {
                            if (curIndex > 0) {

                                var newScrollPosition = (scheduleContents.eq(curIndex - 1).offset().top + self.scrollWrap.scrollTop() + totalScrollPosition);

                                if (self.window.width() < 768) {
                                    newScrollPosition -= 36;
                                }

                                self.obj.parents('.page__scroll').animate({
                                    scrollTop: newScrollPosition
                                }, {
                                    duration: 300,
                                    easing: 'easeInOutQuad'
                                });
                            }
                        }

                        return false;
                    }
                });
            },
            addInfoEvents: function () {
                self.card.find('a').on({
                    click: function () {

                        self.core.openPopup($(this).attr('data-id'), $(this).attr('href'));

                        return false;
                    }
                });
            },
            addPopupEvents: function () {
                self.pagination2Lnk.on({
                    click: function () {
                        var curLnk = $(this);
                        //alert(curLnk.attr('href'));
                        if (!curLnk.hasClass('active')) {
                            self.core.loadPopup(curLnk);
                        }

                        return false;
                    }
                });
                self.pagination2Prev.on({
                    click: function () {
                        var curLnk = $(this);


                        self.pagination2Lnk.filter('.active').parent().prev().find('a').trigger('click');


                        return false;
                    }
                });
                self.pagination2Next.on({
                    click: function () {
                        var curLnk = $(this);

                        self.pagination2Lnk.filter('.active').parent().next().find('a').trigger('click');


                        return false;
                    }
                });
            },
            addPopupScroll: function () {
                self.scrollPopup = self.popup.niceScroll({
                    horizrailenabled: false,
                    zindex: 2,
                    railpadding: {
                        top: 0,
                        right: 1,
                        left: 0,
                        bottom: 0
                    }
                });
            },
            build: function () {
                self.core.intSelects();
                self.core.setSize();
                self.core.getContent()
                self.core.addContentScroll();
                self.core.addPopupScroll();
                self.core.addEvents();
                self.core.checkTitle();
            },
            checkPopupBtn: function () {
                self.pagination2Next.css({display: 'block'});
                self.pagination2Prev.css({display: 'block'});
                if (!self.pagination2Lnk.filter('.active').parent().next().length) {
                    self.pagination2Next.css({display: 'none'});
                }
                if (!self.pagination2Lnk.filter('.active').parent().prev().length) {
                    self.pagination2Prev.css({display: 'none'});
                }
            },
            chekRowPosition: function () {
                var totalScrollPosition = self.obj.parents('.page__scroll').scrollTop(),
                        currentScrollTop = self.scrollWrap.scrollTop(),
                        scheduleContents = $('.schedule__content'),
                        currentSchedule = null,
                        prevPosition = 1000000,
                        i = null;

                scheduleContents.removeClass('schedule__content_fixed');
                $('.schedule__card-title').removeAttr('style');

                if (self.window.width() >= 1025 && scheduleContents.length) {

                    for (i = scheduleContents.length - 1; i >= 0; i--) {

                        var currentScheduleContents = scheduleContents.eq(i),
                                currentSchedulePosition = (currentScheduleContents.offset().top + currentScrollTop + totalScrollPosition) - self.titleWrapp.outerHeight(true);

                        if (currentScrollTop < prevPosition) {
                            currentSchedule = currentScheduleContents;
                        }
                        prevPosition = currentSchedulePosition;
                    }

                    currentSchedule.addClass('schedule__content_fixed');
                    currentSchedule.find('.schedule__card-title').css({
                        top: self.titleWrapp.outerHeight(true) - totalScrollPosition
                    });
                }
            },
            checkTitle: function () {
                var totalScroll = self.obj.parents('.page__scroll').scrollTop(),
                        contentScroll = self.scrollWrap.scrollTop();

                if (self.window.width() > 1024) {
                    if (totalScroll != 0 || contentScroll != 0) {
                        self.title.slideUp({
                            duration: 300,
                            progress: function () {
                                self.core.chekRowPosition();
                            }
                        });
                    } else {
                        self.title.slideDown({
                            duration: 300,
                            progress: function () {
                                self.core.chekRowPosition();
                            }
                        });
                    }
                }

            },
            changePopupContent: function (content) {
                self.popupWrap.find('.schedule__popup-layout').html(content);
                self.popup.removeClass('schedule__popup_loading');
                self.popupWrap.css('display', 'block');
                self.core.checkPopupBtn();
            },
            getContent: function () {
                var session_date = $('.schedule__filter .session_dates option:selected').val();
                var schedule_track = $('.schedule__filter .schedule_tracks option:selected').val();
                var session_location = $('.schedule__filter .session_locations option:selected').val();
                self.core.updateSchedule(session_date, session_location, schedule_track);
            },
            hidePopup: function () {
                self.request.abort();
                self.popup.removeClass('active');
                self.obj.parents('.page__scroll').find('> div ').removeAttr('style');
                self.obj.parents('.page__scroll').scrollTop(self.scrollPos);

                // for css animation
                setTimeout(function () {
                    self.popupWrap.html('');
                }, 500);

            },
            intSelects: function () {
                self.selects = [];

                self.obj.find('select').each(function (i) {
                    self.selects[i] = new AresSelect({
                        obj: $(this),
                        optionType: 1,
                        showType: 2,
                        visible: 5,
                        selects: self.selects
                    });
                });

                /*self.scrollwrap.getNiceScroll().scrollstart(function (info) {
                 console.log('close');
                 });*/
            },
            openPopup: function (id, url) {
                self.popup.addClass('active');
                self.popup.addClass('schedule__popup_loading');
                self.scrollPos = self.obj.parents('.page__scroll').scrollTop();
                self.obj.parents('.page__scroll').find('> div ').css({
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    bottom: 0,
                    right: 0,
                    overflow: 'hidden'
                });

                title = '';
                path = '';

                window.history.pushState({
                    title: title,
                    url: url,
                    path: path,
                    page_id: null
                }, title, url);

                document.title = title;

                // for css animation
                setTimeout(function () {
                    self.request.abort();
                    self.request = $.ajax({
                        //url: self.popup.attr('data-action')+id,
                        //url: url + id,
                        //data: {scheduleId: id},
                        url: ajaxurl,
                        data: {
                            post_id: id,
                            post_type: 'session',
                            action: 'get_template_part'
                        },
                        dataType: 'html',
                        timeout: 20000,
                        type: 'GET',
                        success: function (msg) {
                            self.popupWrap.html(msg);
                            self.popup.removeClass('schedule__popup_loading');
                            self.pagination2 = self.popup.find('.pagination');
                            self.pagination2Prev = self.pagination2.find('.pagination-lnk_prev');
                            self.pagination2Next = self.pagination2.find('.pagination-lnk_next');
                            self.pagination2Lnk = self.pagination2.find('ul a');

                            self.core.addPopupEvents();
                            self.core.checkPopupBtn();
                        },
                        error: function (XMLHttpRequest) {
                            if (XMLHttpRequest.statusText != "abort") {
                                self.core.hidePopup();
                            }
                        }

                    });
                }, 500);


            },
            loadPopup: function (lnk) {
                var index = self.pagination2Lnk.index(lnk);

                self.popup.addClass('schedule__popup_loading');
                self.popupWrap.css('display', 'none');

                self.request.abort();
                self.request = $.ajax({
                    url: self.pagination2.attr('data-action'),
                    data: {
                        pageIndex: index
                    },
                    dataType: 'html',
                    timeout: 20000,
                    type: "GET",
                    success: function (msg) {
                        self.pagination2Lnk.removeClass('active');
                        self.pagination2Lnk.eq(index).addClass('active');

                        self.core.changePopupContent(msg);
                    },
                    error: function (XMLHttpRequest) {
                        if (XMLHttpRequest.statusText != "abort") {

                            self.popup.removeClass('speakers__popup_loading');
                            self.popupWrap.css('display', 'block');
                        }
                    }
                });
            },
            setSize: function () {
                self.scrollWrap.height(self.window.height() - self.filter.outerHeight(true));
            },
            update: function () {
                self.nextLnk = self.obj.find('.schedule__card-title a.next');
                self.prevLnk = self.obj.find('.schedule__card-title a.prev');
                self.card = self.obj.find('.schedule__card');
                self.core.addCardEvent();
                self.core.addNextLinksEvent();
            },
            updateSchedule: function (timestamp, location, track) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: ajaxurl,
                    data: {
                        'action': 'get_schedule',
                        'data-timestamp': timestamp,
                        'data-location': location,
                        'data-track': track
                    },
                    success: function (data) {
                        var html = '';
                        if (data.sessions && data.sessions.length > 0) {
                            var cur_date = 0;
                            html = '';
                            var closing_tags = '',
                            langs = ['pb', 'es', 'en'],
                            current_lang = '';

                            for (var i in langs) {
                                var search_lang = window.location.href.indexOf('/' + langs[i] + '/');
                                if (search_lang > 0) {
                                    current_lang = langs[i];
                                }
                            }
                            if (current_lang == '') {
                                current_lang = 'pb';
                            }
                            $.each(data.sessions, function (index, session) {
                                var speakers = '';

                                var color = (session.color != '' ? ' style="color:' + session.color + '"' : '');
                                if (cur_date != session.date) {

                                    if (cur_date != 0) {
                                        if (closing_tags != '')
                                            html += closing_tags;
                                        closing_tags = '';
                                    }
                                    var prev_day = [];
                                    prev_day['pb'] = 'DIA ANTERIOR';
                                    prev_day['es'] = 'DÍA ANTERIOR';
                                    prev_day['en'] = 'PREVIOUS DAY';

                                    var next_day = [];
                                    next_day['pb'] = 'PRÓXIMO DIA';
                                    next_day['es'] = 'PRÓXIMO DÍA';
                                    next_day['en'] = 'NEXT DAY';

                                    html += '<div class="schedule__content container">\
                                        <div class="schedule__wrap row">\
                                            <div class="schedule__card-title col-sm-12">\
                                                <time datetime="' + session.date + '">' + session.date + '</time>\
                                                <a href="#" class="prev"><span>' + prev_day[current_lang] + '</span></a>\
                                                <a href="#" class="next"><span>' + next_day[current_lang] + '</span></a>\
                                            </div>';


                                    cur_date = session.date;
                                    closing_tags = '</div> \
                                </div>';
                                }//cur_date != session.date

                                var more_info = [];
                                more_info['pb'] = 'SAIBA MAIS';
                                more_info['es'] = 'VEA MÁS';
                                more_info['en'] = 'MORE INFO';

                                if (session.speakers) {
                                    var speakers_len = session.speakers.length;
                                    var type_grid = '';
                                    if (speakers_len > 3) {
                                        type_grid = 'schedule__photo_type3';
                                    } else if (speakers_len > 1) {
                                        type_grid = 'schedule__photo_type2';
                                    }
                                    speakers = '<div class="schedule__photo ' + type_grid + '">';
                                    $.each(session.speakers, function (index, speaker) {
                                        featured = speaker.featured ? ' featured' : '';
                                        if (speaker.post_image !== null) {
                                            if (speaker.post_image) {
                                                speakers += '<div style="background-image: url(' + speaker.post_image + ')"></div>';

                                            } else {
                                                speakers += '<div style="background-image: none"></div>';

                                            }
                                        }
                                    });
                                    speakers += '</div>';
                                }
                                var thumbnail = '';
                                if (session.thumbnail) {
                                    thumbnail = ' style="background-image:url(' + session.thumbnail + '); background-size: cover;"';
                                }

                                html += '<div class="col-sm-6 col-xs-12 col-md-4">\
                                    <div class="schedule__card">\
                                        <div class="schedule__face1"' + thumbnail + '>\
                                            <div class="vertical-center">\
                                                <div>\
                                                    <div>' + session.post_title + '</div>\
                                                </div>\
                                            </div>\
                                            <time>' + session.time + ' - ' + session.end_time + '</time>\
                                        </div>\
                                        <div class="schedule__face2">\
                                            ' + speakers + '\
                                            <div class="schedule__info">\
                                                <p>' + session.location + '</p>\
                                                    <div>\
                                                        <a href="' + session.url + '" data-id="' + session.id + '">' + more_info[current_lang] + '</a>\
                                                    </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>';

                                if (index === data.sessions.length - 1)
                                    html += '</div></div>';
                            })//each data.sessions

                        }
                        self.obj.find('.schedule_update_content').html(html);

                        self.core.update();

                    }
                });
            }
        };
    }
};

var TextField = function (obj) {
    this.obj = obj;
    this.input = obj.find('input');

    this.init();
};
TextField.prototype = {
    init: function () {
        var self = this;

        self.core = self.core();
        self.core.build();
    },
    core: function () {
        var self = this;

        return {
            addEvents: function () {
                self.input.on({
                    focus: function () {
                        self.obj.removeClass('text-field_error');
                    },
                    blur: function () {
                        if (self.input.val().length) {
                            self.obj.addClass('text-field_filled');
                        } else {
                            self.obj.removeClass('text-field_filled');
                        }

                    }
                });
            },
            build: function () {
                if (!self.input.length) {
                    self.input = self.obj.find('textarea');
                }
                self.core.addEvents();
            }
        };
    }
};

var Gallery = function (obj) {
    this.obj = obj;
    this.topGallery = obj.find('.gallery-top');
    this.topItems = this.topGallery.find('.swiper-slide');
    this.thumbsGallery = obj.find('.gallery-thumbs');
    this.thumbsItems = this.thumbsGallery.find('.swiper-slide');
    this.topSwiper = null;
    this.btnPrev = this.topGallery.find('.swiper-button-prev');
    this.btnNext = this.topGallery.find('.swiper-button-next');
    this.videoBtn = this.topGallery.filter('.gallery__btn-video');
    this.video = obj.find('.gallery__video');
    this.closeVideo = this.video.find('i');
    this.title = obj.find('.site__title');
    this.window = $(window);

    this.init();
};
Gallery.prototype = {
    init: function () {
        var self = this;

        self.core = self.core();
        self.core.build();
    },
    core: function () {
        var self = this;

        return {
            addEventListeners: function () {
                self.window.on({
                    resize: function () {
                        self.core.setSize();
                    }
                });
                self.topGallery.on('click', '.gallery__btn-video', function () {
                    self.video.find('>div').html($(this).attr('data-video'));

                    if (self.window.width() < 768) {
                        self.video.css({
                            top: -self.scroll.scroll.y,
                            height: self.window.height() - 36
                        });
                    }

                    self.video.addClass('active');

                    return false;
                });

                self.closeVideo.on({
                    click: function () {

                        self.video.removeClass('active');

                        //for css animation
                        setTimeout(function () {
                            self.video.find('>div').html('');
                        }, 500);

                        return false;
                    }
                });
            },
            addSwipers: function () {
                self.topSwiper = new Swiper(self.topGallery, {
                    nextButton: self.topGallery.find('.swiper-button-next'),
                    prevButton: self.topGallery.find('.swiper-button-prev'),
                    spaceBetween: 0
                });
                self.thumbsSwiper = new Swiper(self.thumbsGallery, {
                    spaceBetween: 0,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    touchRatio: 0.2,
                    slideToClickedSlide: true
                });
                self.topSwiper.params.control = self.thumbsSwiper;
                self.thumbsSwiper.params.control = self.topSwiper;
            },
            build: function () {
                self.core.setSize();
                self.core.addSwipers();
                self.core.addEventListeners();
            },
            setSize: function () {
                var btnTop = null,
                        newW = self.obj.width() * .156435643,
                        topGalleryHeight = Math.floor((self.window.height() - (self.title.outerHeight(true))) * .81),
                        thumbsGalleryHeight = (self.window.height() - (self.title.outerHeight(true))) - topGalleryHeight;

                self.thumbsItems.width(newW);

                self.topGallery.height(topGalleryHeight);
                self.topItems.height(topGalleryHeight);
                self.thumbsGallery.height(thumbsGalleryHeight);

                btnTop = (self.topGallery.height() / 2 - 22.5);

                self.btnNext.css({
                    top: btnTop
                });
                self.btnPrev.css({
                    top: btnTop
                });
            }
        };
    }
};

var News = function (obj, page_id) {
    this.obj = obj;
    this.page_id = page_id;
    this.pagination = this.obj.find('.news__wrap > .pagination');
    this.searchFormText = this.obj.find('.search-form #s');
    this.searchFormBtn = this.obj.find('.search-form #searchsubmit');
    this.paginationPrev = this.pagination.find('.pagination-lnk_prev');
    this.paginationNext = this.pagination.find('.pagination-lnk_next');
    this.paginationLnk = this.pagination.find('ul a');
    this.news = this.obj.find('.news__item');
    this.popup = this.obj.find('.news__popup');
    this.popupWrap = this.popup.find('.news__popup-wrap');
    this.gallery = this.obj.find('.news__gallery .row');
    this.closePopupBtn = this.obj.find('.news__popup-close');
    this.request = new XMLHttpRequest();
    this.scrollPos = 0;

    this.init();
};
News.prototype = {
    init: function () {
        var self = this;

        self.core = self.core();
        self.core.build();
    },
    core: function () {
        var self = this;

        return {
            addEvents: function () {
                self.paginationLnk.on({
                    click: function () {
                        var curLnk = $(this);
                        var myTestVarforUrl = curLnk.attr('href');
                        //alert(myTestVarforUrl);
                        if (!curLnk.hasClass('active')) {
                            self.core.load(curLnk.html());
                        }
                        return false;
                    }
                });
                self.paginationPrev.on({
                    click: function () {
                        var curLnk = $(this);
                        self.paginationLnk.filter('.active').parent().prev().find('a').trigger('click');
                        return false;
                    }
                });
                self.paginationNext.on({
                    click: function () {
                        var curLnk = $(this);
                        self.paginationLnk.filter('.active').parent().next().find('a').trigger('click');
                        return false;
                    }
                });
                self.core.addSpeakerEvents();
                self.closePopupBtn.on({
                    click: function () {
                        var path = location.href;

                        for (i = path.length - 2; i >= 0; i--) {
                            if (path[i] == '/') {
                                path = path.substr(0, i + 1);
                                break;
                            }
                        }

                        self.core.hidePopup();

                        window.history.pushState({
                            title: '',
                            url: path,
                            path: path
                        }, '', path);

                        return false;
                    }
                });
                self.searchFormBtn.on('click', function () {
                    self.core.loadSearch();
                });
                self.searchFormText.on('keypress', function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        $(self.searchFormBtn).trigger('click');
                    }
                });

            },
            addPopupEvents: function () {
                self.pagination2Lnk.on({
                    click: function () {
                        var curLnk = $(this);
                        if (!curLnk.hasClass('active')) {
                            self.core.loadPopup(curLnk);
                        }
                        return false;
                    }
                });
                self.pagination2Prev.on({
                    click: function () {
                        var curLnk = $(this);
                        self.pagination2Lnk.filter('.active').parent().prev().find('a').trigger('click');
                        return false;
                    }
                });
                self.pagination2Next.on({
                    click: function () {
                        var curLnk = $(this);
                        self.pagination2Lnk.filter('.active').parent().next().find('a').trigger('click');
                        return false;
                    }
                });
            },
            addPopupScroll: function () {

                self.scrollPopup = self.popup.niceScroll({
                    horizrailenabled: false,
                    zindex: 2,
                    railpadding: {
                        top: 0,
                        right: 1,
                        left: 0,
                        bottom: 0
                    }
                });
            },
            addSpeakerEvents: function () {
                self.news.on({
                    click: function () {
                        self.core.openPopup($(this).attr('data-id'), $(this).attr('data-type'), $(this).attr('href'));
                        return false;
                    }
                });
            },
            build: function () {
                self.core.checkBtn();
                self.core.addPopupScroll();
                self.core.addEvents();
            },
            changeContent: function (content) {
                var oldH = self.obj.height(),
                        newH = null;

                //self.obj.html(content);
                var objParent = self.obj.parent();
                objParent.html(content);
                self.obj = objParent.find('[data-section]');

                newH = self.obj.height();
                if (newH != oldH) {
                    self.gallery.height(oldH);
                    self.gallery.addClass('news__animated');
                    self.gallery.height(newH);

                    //for css animation
                    setTimeout(function () {
                        self.gallery.removeClass('news__animated');
                        showContent();
                    }, 500);
                } else {
                    showContent();
                }

                function showContent() {
                    new News($(self.obj), $(self.obj).closest('.page').attr('data-id'));
                    /*self.obj.removeClass('news__loading');
                     self.core.checkBtn();
                     self.news = self.obj.find('.news__item');
                     self.core.addSpeakerEvents();*/
                }
            },
            changePopupContent: function (content) {
                self.popupWrap.find('.news__layout').html(content);
                self.popup.removeClass('news__popup_loading');
                self.popupWrap.css('display', 'block');
                self.core.checkPopupBtn();
                self.scrollPopup.scrollTo(0, 0, 0);
                self.scrollPopup.refresh();
            },
            checkBtn: function () {
                var index = self.paginationLnk.index(self.paginationLnk.filter('.active'));

                self.paginationNext.css({display: 'block'});
                self.paginationPrev.css({display: 'block'});

                if (index == self.paginationLnk.length - 1) {
                    self.paginationNext.css({display: 'none'});
                }
                if (!index) {
                    self.paginationPrev.css({display: 'none'});
                }
            },
            checkPopupBtn: function () {
                var index = self.pagination2Lnk.index(self.pagination2Lnk.filter('.active'));

                self.pagination2Next.css({display: 'block'});
                self.pagination2Prev.css({display: 'block'});

                if (index == self.pagination2Lnk.length - 1) {
                    self.pagination2Next.css({display: 'none'});
                }
                if (!index) {
                    self.pagination2Prev.css({display: 'none'});
                }
            },
            hidePopup: function () {
                self.request.abort();
                self.popup.removeClass('active');
                self.obj.parents('.page__scroll').find('> div ').removeAttr('style');
                self.obj.parents('.page__scroll').scrollTop(self.scrollPos);

                // for css animation
                setTimeout(function () {
                    self.popupWrap.html('');

                    self.scrollPopup.resize();
                }, 500)
            },
            load: function (index) {
                self.obj.addClass('news__loading');
                self.request.abort();
                var curUrl = self.paginationLnk[2]['href'].split("?");

                self.request = $.ajax({
                    url: ajaxurl,
                    data: {
                        paged: index,
                        page_id: self.page_id,
                        action: 'get_section'
                    },
                    dataType: 'html',
                    timeout: 20000,
                    type: 'POST',
                    success: function (msg) {
                        self.paginationLnk.removeClass('active');
                        self.paginationLnk.eq(index).addClass('active');
                        self.core.changeContent(msg);
                    },
                    error: function (XMLHttpRequest) {
                        if (XMLHttpRequest.statusText != "abort") {
                            self.obj.removeClass('news__loading');
                        }
                    }
                });
            },
            loadSearch: function () {
                self.obj.addClass('news__loading');
                self.request.abort();
                self.request = $.ajax({
                    url: ajaxurl,
                    data: {
                        text: self.searchFormText.val(),
                        page_id: self.page_id,
                        action: 'get_section'
                    },
                    dataType: 'html',
                    timeout: 20000,
                    type: 'POST',
                    success: function (msg) {
                        self.core.changeContent(msg);
                    },
                    error: function (XMLHttpRequest) {
                        if (XMLHttpRequest.statusText != "abort") {
                            self.obj.removeClass('news__loading');
                        }
                    }
                });
            },
            loadPopup: function (lnk) {
                var index = self.pagination2Lnk.index(lnk);
                self.popup.addClass('news__popup_loading');
                self.popupWrap.css('display', 'none');

                self.request.abort();
                self.request = $.ajax({
                    url: self.pagination2.attr('data-action'),
                    data: {
                        pageIndex: index
                    },
                    dataType: 'html',
                    timeout: 20000,
                    type: "GET",
                    success: function (msg) {
                        self.pagination2Lnk.removeClass('active');
                        self.pagination2Lnk.eq(index).addClass('active');

                        self.core.changePopupContent(msg);

                    },
                    error: function (XMLHttpRequest) {
                        if (XMLHttpRequest.statusText != "abort") {

                            self.popup.removeClass('news__popup_loading');
                            self.popupWrap.css('display', 'block');
                        }
                    }
                });
            },
            openPopup: function (id, type, url) {
                self.popup.addClass('active');
                self.popup.addClass('news__popup_loading');

                window.history.pushState({
                    title: '',
                    url: url,
                    path: url
                }, '', url);

                //for css animation
                setTimeout(function () {
                    self.request.abort();
                    self.request = $.ajax({
                        url: ajaxurl,
                        data: {
                            post_id: id,
                            post_type: type,
                            action: 'get_template_part'
                        },
                        dataType: 'html',
                        timeout: 20000,
                        type: 'POST',
                        success: function (msg) {
                            self.popupWrap.html(msg);
                            self.popup.removeClass('news__popup_loading');
                            self.scrollPos = self.obj.parents('.page__scroll').scrollTop();
                            self.obj.parents('.page__scroll').find('> div ').css({
                                position: 'absolute',
                                top: 0,
                                left: 0,
                                bottom: 0,
                                right: 0,
                                overflow: 'hidden'
                            });


                            self.pagination2 = self.popup.find('.pagination');
                            self.pagination2Prev = self.pagination2.find('.pagination-lnk_prev');
                            self.pagination2Next = self.pagination2.find('.pagination-lnk_next');
                            self.pagination2Lnk = self.pagination2.find('ul a');

                            $.each(self.popup.find('.text-field'), function (i) {
                                new TextField($(this));

                            });
                            $.each(self.popup.find('.text-area'), function (i) {
                                new TextField($(this));

                            });

                            self.core.addPopupEvents();
                            self.core.checkPopupBtn();

                            // for css animation
                            setTimeout(function () {
                                self.scrollPopup.resize();
                            }, 1000);
                        },
                        error: function (XMLHttpRequest) {
                            if (XMLHttpRequest.statusText != "abort") {
                                self.core.hidePopup();
                            }
                        }
                    });
                }, 500);

            }
        };
    }
};

var Inst = function (obj, scroll) {
    this.obj = obj;
    this.items = obj.find('.inst__content a');
    this.wrap = obj.find('.inst__wrap');
    this.window = $(window);
    this.scroll = scroll;
    this.scrollHeight = this.wrap.height();

    this.init();
};
Inst.prototype = {
    init: function () {
        var self = this;

        self.core = self.core();
        self.core.build();
    },
    core: function () {
        var self = this;

        return {
            addEvents: function () {
                self.window.on({
                    resize: function () {

                        self.core.fillGallery();
                    }
                });
            },
            addGallery: function () {
                self.gallery = $('<div class="inst__gallery"/></div>');

                self.obj.find('>div').append(self.gallery);
            },
            build: function () {
                self.core.addGallery();
                self.core.fillGallery();
                self.core.renderLoop();
                self.core.addEvents();
                self.core.refreshInstagrams();
            },
            fillGallery: function () {
                var rowsMap = [],
                        curBlock,
                        curItemIndex = 0,
                        countInBlock = 0,
                        start = 0,
                        finish = 0,
                        j = 0,
                        i = 0;

                self.gallery.html('');

                if (self.window.width() >= 1200) {
                    rowsMap = [2, 1, 4, 4, 1, 2];
                } else if (self.window.width() >= 768) {
                    rowsMap = [4, 1, 1, 4];
                }

                for (i = 0; i < rowsMap.length; i++) {

                    countInBlock = rowsMap[i];

                    curBlock = $('<div><div></div></div>');

                    if (countInBlock == 1) {
                        curBlock.addClass('inst__big')
                    } else if (countInBlock == 2) {
                        curBlock.addClass('inst__small')
                    } else if (countInBlock == 4) {
                        curBlock.addClass('inst__medium')
                    }

                    start = curItemIndex;
                    finish = ((start + countInBlock) > self.items.length) ? self.items.length : (start + countInBlock);

                    for (j = start; j < finish; j++) {
                        curBlock.find('div').append(self.items.eq(j).clone());
                    }

                    if (finish == self.items.length) {
                        break;
                    }

                    curItemIndex = finish;

                    if (i == rowsMap.length - 1) {
                        i = -1;
                    }

                    self.gallery.append(curBlock);

                }


            },
            refreshInstagrams: function () {
                $.post(
                        ajaxurl,
                        {
                            action: 'get_instagrams',
                            limit: khore_count,
                            hashtag: khore_hash
                        },
                function (instagrams, textStatus, jqXHR) {
                    instagrams_html = new Array();
                    if (instagrams)
                        for (var i = 0; i < instagrams.length; i++) {
                            instagram_html = '';

                            instagram_html += '<a href="' + instagrams[i].link + '"><img src="' + instagrams[i].images.standard_resolution.url + '" alt="' + (instagrams[i].caption ? instagrams[i].caption.text : '') + '" /></a>';

                            instagrams_html.push(instagram_html);
                        }

                    $('#instagram_update_list').html(instagrams_html);
                    self.items = self.obj.find('.inst__content a');
                    self.core.fillGallery();

                    //for update instagram
                    setTimeout(self.core.refreshInstagrams, 30000);

                },
                        'json');
            },
            renderLoop: function () {
                requestAnimFrame(self.core.renderLoop);

                if (self.scrollHeight != self.wrap.height()) {
                    self.scrollHeight = self.wrap.height();
                }
            }
        };
    }
};

var Location = function (obj) {
    this.obj = obj;
    this.wrap = obj.find('.location__wrap');
    this.mapWrap = obj.find('.location__map');
    this.titles = obj.find('.location__titles');
    this.titlesWrap = this.titles.find('ul');
    this.titlesItems = this.titles.find('li');
    this.form = obj.find('form');
    this.select = obj.find('select');
    this.window = $(window);
    this.action = false;
    this.selects = null;
    this.init();
};
Location.prototype = {
    init: function () {
        var self = this;

        self.core = self.core();
        self.core.build();
    },
    core: function () {
        var self = this;

        return {
            addEvents: function () {
                self.window.on({
                    resize: function () {
                        self.core.setTitleSize();
                    }
                });

                self.btnPrev.on({
                    click: function () {
                        var curIndex = Math.round(-self.titlesScroll.x / self.obj.width()) - 1;

                        if (!self.action) {
                            self.action = true;
                            self.titlesScroll.scrollToElement(self.titlesItems[ curIndex ], 400);
                        }

                        return false;
                    }
                });

                self.btnNext.on({
                    click: function () {
                        var curIndex = Math.round(-self.titlesScroll.x / self.obj.width()) + 1;


                        if (!self.action) {
                            self.action = true;
                            self.titlesScroll.scrollToElement(self.titlesItems[ curIndex ], 400);
                        }

                        return false;
                    }
                });

                $('.ares-select.ares-select_custom select').on('change', function (e) {
                    var lat = $('.ares-select.ares-select_custom select option:selected').data('lat') || null;
                    var lng = $('.ares-select.ares-select_custom select option:selected').data('lng') || null;
                    if (lat !== null && lng !== null)
                        self.map.panTo(new google.maps.LatLng(lat, lng));
                    return false;
                });

                self.titlesScroll.on('scrollEnd', function () {
                    self.action = false;
                    self.core.checkArrows();

                });

                self.select.on({
                    change: function () {
                        //self.form.trigger( 'submit' );
                    }
                });

                $('.page__scroll').on({
                    scroll: function () {

                        if (self.selects.popup) {
                            self.selects.opened = false;
                            self.selects.popup.remove()
                        }
                    }
                });


            },
            addTitlesBtns: function () {

                self.btnPrev = $('<div class="location__btn location__btn_prev"><i class="fa fa-arrow-left"></i></div>')
                self.btnNext = $('<div class="location__btn location__btn_next"><i class="fa fa-arrow-right"></i></div>')

                self.wrap.prepend(self.btnPrev);
                self.wrap.prepend(self.btnNext);
            },
            build: function () {
                if (typeof google === 'object' && typeof google.maps === 'object') {
                    self.core.initMap();
                } else {
                    google.maps.event.addDomListener(window, 'load', self.core.initMap);
                }

                self.core.initTitlesScroll();
                self.core.addTitlesBtns();
                self.core.checkArrows();
                self.core.initSelect();
                self.core.addEvents();
                self.core.setTitleSize();
            },
            checkArrows: function () {
                var curIndex = -self.titlesScroll.x / self.obj.width();
                self.core.setGroup(Math.floor(Math.abs(curIndex)));
                self.btnPrev.css({display: 'block'});
                self.btnNext.css({display: 'block'});
                if (!curIndex) {
                    self.btnPrev.css({display: 'none'});
                }
                if (curIndex == self.titlesItems.length - 1) {
                    self.btnNext.css({display: 'none'});
                }
            },
            initMap: function () {
                var mapOptions = {
                    zoom: poi_gmap_zoom,
                    scrollwheel: false,
                    disableDefaultUI: true,
                    center: new google.maps.LatLng(51.516465, -0.128378)
                },
                newId = 'map_' + Math.round(100 * Math.random());
                self.mapWrap.attr('id', newId);
                if (device.mobile()) {
                    mapOptions.draggable = false;
                }
                self.map = new google.maps.Map(document.getElementById(newId),
                        mapOptions);
                currentMap = self.map;

                self.core.setMarkers();
            },
            initSelect: function () {
                self.obj.find('select').each(function (i) {
                    self.selects = new AresSelect({
                        obj: $(this),
                        optionType: 1,
                        showType: 2,
                        visible: 5,
                        selects: self.selects
                    });
                });
            },
            setGroup: function (index) {
                var group_id = self.titles.find('.vertical-center').eq(index).data('group');
                self.core.updateOptionsSelect(group_id);
            },
            updateOptionsSelect: function (group) {
                $('#location_select option').each(function (i, el) {
                    if ($(el).data('group') != group && $(el).val() != 0) {
                        $(el).attr('disabled', true);
                    } else {
                        $(el).attr('disabled', false);
                    }
                })
                if (self.selects !== null) {
                    if (self.selects.opened) {
                        self.selects.core.showPopup();
                    }
                }

            },
            initTitlesScroll: function () {
                self.id = 'scroll' + new Date().getTime();

                self.titles.attr('id', self.id);

                self.titlesScroll = new IScroll('#' + self.id, {
                    scrollX: true,
                    scrollY: false,
                    momentum: false,
                    click: true,
                    snap: true,
                    snapSpeed: 400,
                    keyBindings: true
                });
            },
            setMarkers: function () {
                var image = {
                    url: poi_marker,
                    size: new google.maps.Size(50, 59),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(25, 59)
                },
                data = JSON.parse(self.mapWrap.attr('data-map')).marks;
                self.bounds = new google.maps.LatLngBounds();

                self.markers = [];
                self.info = [];

                $.each(data, function (i) {
                    var curLatLng = new google.maps.LatLng(this.poi_latitude, this.poi_longitude);

                    self.bounds.extend(curLatLng);

                    self.markers[ i ] = new google.maps.Marker({
                        position: curLatLng,
                        map: self.map,
                        icon: image,
                        title: this.poi_title
                    });
                    self.info[i] = new google.maps.InfoWindow({
                        content: this.poi_address
                    });

                    self.core.setInfoWindow(i);
                });

                self.map.fitBounds(self.bounds);

                // zoom after fitBounds
                zoomChangeBoundsListener =
                        google.maps.event.addListenerOnce(self.map, 'bounds_changed', function (event) {
                            if (this.getZoom()) {
                                this.setZoom(poi_gmap_zoom);
                            }
                        });
                setTimeout(function () {
                    google.maps.event.removeListener(zoomChangeBoundsListener)
                }, 2000);
                
            },
            setInfoWindow: function (index) {

                google.maps.event.addListener(self.markers[ index ], 'click', function () {
                    self.info[index].open(self.map, self.markers[ index ]);
                    return false
                });
            },
            setTitleSize: function () {
                self.titlesItems.width(self.obj.width());
                self.titlesWrap.width(self.obj.width() * self.titlesItems.length);

                self.titlesScroll.refresh();

                self.mapWrap.height(self.window.height() - self.titles.height())

            }
        };
    }
};
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
                            $('.swiper-pagination').hide();
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
