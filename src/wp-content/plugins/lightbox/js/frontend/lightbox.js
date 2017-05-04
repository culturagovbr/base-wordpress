(function ($) {

    'use strict';

    jQuery.each(hugeit_gen_resp_lightbox_obj, function (index, value) {
        if (value.indexOf('true') > -1 || value.indexOf('false') > -1)
            hugeit_gen_resp_lightbox_obj[index] = value == "true";
    });

    function Lightbox(element, options) {

        this.el = element;
        this.$element = $(element);
        this.$body = $('body');
        this.objects = {};
        this.lightboxModul = {};
        this.$item = '';
        this.$cont = '';
        this.$items = this.$body.find('a.responsove_lightbox');

        this.settings = $.extend({}, this.constructor.defaults, options);

        this.init();

        return this;
    }

    Lightbox.defaults = {
        idPrefix: 'rwd-',
        classPrefix: 'rwd-',
        attrPrefix: 'data-',
        slideAnimationType: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_slideAnimationType, /*  effect_1   effect_2    effect_3
         effect_4   effect_5    effect_6
         effect_7   effect_8    effect_9   */
        lightboxView: hugeit_resp_lightbox_obj.hugeit_lightbox_lightboxView,              //  view1, view2, view3, view4, view5
        speed: hugeit_resp_lightbox_obj.hugeit_lightbox_speed_new,
        width: hugeit_resp_lightbox_obj.hugeit_lightbox_width_new + '%',
        height: hugeit_resp_lightbox_obj.hugeit_lightbox_height_new + '%',
        videoMaxWidth: hugeit_resp_lightbox_obj.hugeit_lightbox_videoMaxWidth,
        sizeFix: true, //not for option
        overlayDuration: +hugeit_gen_resp_lightbox_obj.hugeit_lightbox_overlayDuration,
        slideAnimation: true, //not for option
        overlayClose: hugeit_resp_lightbox_obj.hugeit_lightbox_overlayClose_new,
        loop: hugeit_resp_lightbox_obj.hugeit_lightbox_loop_new,
        escKey: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_escKey_new,
        keyPress: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_keyPress_new,
        arrows: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_arrows,
        mouseWheel: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_mouseWheel,
        download: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_download,
        showTitle: hugeit_resp_lightbox_obj.hugeit_lightbox_showTitle === 'true',
        showDesc: hugeit_resp_lightbox_obj.hugeit_lightbox_showDesc === 'true',
        showBorder: hugeit_resp_lightbox_obj.hugeit_lightbox_showBorder === 'true',
        showCounter: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_showCounter,
        defaultTitle: '',  //some text
        preload: 10,  //not for option
        showAfterLoad: true,  //not for option
        nextHtml: '',  //not for option
        prevHtml: '',  //not for option
        sequence_info: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_sequence_info,
        sequenceInfo: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_sequenceInfo,
        slideshow: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_slideshow_new,
        slideshowAuto: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_slideshow_auto_new,
        slideshowSpeed: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_slideshow_speed_new,
        slideshowStart: '',  //not for option
        slideshowStop: '',   //not for option
        hideControlOnEnd: false,  //not for option
        watermark: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark,
        socialSharing: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_socialSharing,
        titlePos: hugeit_resp_lightbox_obj.hugeit_lightbox_title_pos,
        fullwidth: hugeit_resp_lightbox_obj.hugeit_lightbox_fullwidth_effect === 'true',
        zoomLogo: hugeit_resp_lightbox_obj.hugeit_lightbox_zoomlogo,
        wURL: hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_link,
        watermarkURL: hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_url,
        wURLnewTab: hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_url_new_tab,
        share: {
            facebookButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_facebookButton,
            twitterButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_twitterButton,
            googleplusButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_googleplusButton,
            pinterestButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_pinterestButton,
            linkedinButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_linkedinButton,
            tumblrButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_tumblrButton,
            redditButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_redditButton,
            bufferButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_bufferButton,
            diggButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_diggButton,
            vkButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_vkButton,
            yummlyButton: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_yummlyButton
        }
    };

    Lightbox.prototype.init = function () {

        var $object = this,
            $hash;

        $hash = window.location.hash;

        ($object.settings.watermark && $('.watermark').watermark());

        if ($hash.indexOf('lightbox&') > 0) {
            $object.index = parseInt($hash.split('&slide=')[1], 10) - 1;

            $object.$body.addClass('rwd-share');
            if (!$object.$body.hasClass('rwd-on')) {
                setTimeout(function () {
                    $object.build($object.index);
                }, 900);
                $object.$body.addClass('rwd-on');
            }
        }

        (($object.settings.preload > $object.$items.length) && ($object.settings.preload = $object.$items.length));

        $object.$items.on('click.rwdcustom', function (event) {

            event = event || window.event;
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);

            $object.index = $object.$items.index(this);

            if (!$object.$body.hasClass($object.settings.classPrefix + 'on')) {
                $object.build($object.index);
                $object.$body.addClass($object.settings.classPrefix + 'on');
            }

        });

        $object.$body.on('click', function () {
            $object.$_y_ = window.pageYOffset;
        });

        switch (this.settings.zoomLogo) {
            case '1':
                $object.$body.addClass('rwd-zoomGlass');
                break;
            case '2':
                $object.$body.addClass('rwd-zoomHand');
                break;
        }

    };

    Lightbox.prototype.build = function (index) {

        var $object = this;

        $object.structure();

        $object.lightboxModul['modul'] = new $.fn.lightbox.lightboxModul['modul']($object.el);

        $object.slide(index, false, false);

        ($object.settings.keyPress && $object.addKeyEvents());

        if ($object.$items.length > 1) {

            $object.arrow();

            ($object.settings.mouseWheel && $object.mousewheel());

            ($object.settings.slideshow && $object.slideShow());

        }

        $object.counter();

        $object.closeGallery();

        $object.$cont.on('click.rwd-container', function () {

            $object.$cont.removeClass($object.settings.classPrefix + 'hide-items');

        });

        $('.shareLook').on('click.rwd-container', function () {
            $(this).css({'display': 'none'});
            $('.rwd-share-buttons').css({'display': 'block'});
            setTimeout(function(){
                $('.shareLook').css({'display' : 'block'});
                $('.rwd-share-buttons').css({'display' : 'none'});
            }, 9000);
        });

        $object.calculateDimensions();
    };

    Lightbox.prototype.structure = function () {

        var $object = this, list = '', controls = '', i,
            subHtmlCont1 = '', subHtmlCont2 = '', subHtmlCont3 = '',
            close1 = '', close2 = '', socialIcons = '',
            template, $arrows, $next, $prev,
            $_next, $_prev, $close_bg, $download_bg, $download_bg_, $contInner;

        this.$body.append(
            this.objects.overlay = $('<div class="' + this.settings.classPrefix + 'overlay"></div>')
        );
        this.objects.overlay.css('transition-duration', this.settings.overlayDuration + 'ms');

        var $wURL = '',
            $target = '';

        if($object.settings.watermark && $object.settings.wURL && hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_text){
            if($object.settings.wURLnewTab){
                $target = 'target="_blank"';
            }
            $wURL = '<a href="' + $object.settings.watermarkURL + '" class="w_url" ' + $target + '></a>';
        }

        for (i = 0; i < this.$items.length; i++) {
            list += '<div class="' + this.settings.classPrefix + 'item">' + $wURL + '</div>';
        }

        $close_bg = '<svg class="close_bg" width="16px" height="16px" fill="#999" viewBox="-341 343.4 15.6 15.6">' +
            '<path d="M-332.1,351.2l6.5-6.5c0.3-0.3,0.3-0.8,0-1.1s-0.8-0.3-1.1,0l-6.5,6.5l-6.5-6.5c-0.3-0.3-0.8-0.3-1.1,0s-0.3,0.8,0,1.1l6.5,6.5l-6.5,6.5c-0.3,0.3-0.3,0.8,0,1.1c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2l6.5-6.5l6.5,6.5c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2c0.3-0.3,0.3-0.8,0-1.1L-332.1,351.2z"/>' +
            '</svg>';

        switch (this.settings.lightboxView) {
            case 'view1':
            default:
                $_next = '<svg class="next_bg" width="22px" height="22px" fill="#999" viewBox="-333 335.5 31.5 31.5" >' +
                    '<path d="M-311.8,340.5c-0.4-0.4-1.1-0.4-1.6,0c-0.4,0.4-0.4,1.1,0,1.6l8,8h-26.6c-0.6,0-1.1,0.5-1.1,1.1s0.5,1.1,1.1,1.1h26.6l-8,8c-0.4,0.4-0.4,1.2,0,1.6c0.4,0.4,1.2,0.4,1.6,0l10-10c0.4-0.4,0.4-1.1,0-1.6L-311.8,340.5z"/>' +
                    '</svg>';
                $_prev = '<svg class="prev_bg" width="22px" height="22px" fill="#999" viewBox="-333 335.5 31.5 31.5" >' +
                    '<path d="M-322.7,340.5c0.4-0.4,1.1-0.4,1.6,0c0.4,0.4,0.4,1.1,0,1.6l-8,8h26.6c0.6,0,1.1,0.5,1.1,1.1c0,0.6-0.5,1.1-1.1,1.1h-26.6l8,8c0.4,0.4,0.4,1.2,0,1.6c-0.4,0.4-1.1,0.4-1.6,0l-10-10c-0.4-0.4-0.4-1.1,0-1.6L-322.7,340.5z"/>' +
                    '</svg>';
                if(this.settings.showTitle){
                    subHtmlCont1 = '<div class="' + this.settings.classPrefix + 'title"></div>';
                }
                close1 = '<span class="' + this.settings.classPrefix + 'close ' + $object.settings.classPrefix + 'icon">' + $close_bg + '</span>';
                break;
            case 'view2':
                $_next = '<svg class="next_bg" width="22px" height="22px" fill="#999" viewBox="-123 125.2 451.8 451.8" >' +
                    '<g><path d="M222.4,373.4L28.2,567.7c-12.4,12.4-32.4,12.4-44.8,0c-12.4-12.4-12.4-32.4,0-44.7l171.9-171.9L-16.6,179.2c-12.4-12.4-12.4-32.4,0-44.7c12.4-12.4,32.4-12.4,44.8,0l194.3,194.3c6.2,6.2,9.3,14.3,9.3,22.4C231.7,359.2,228.6,367.3,222.4,373.4z"/></g>' +
                    '</svg>';
                $_prev = '<svg class="prev_bg" width="22px" height="22px" fill="#999" viewBox="-123 125.2 451.8 451.8" >' +
                    '<g><path d="M-25.9,351.1c0-8.1,3.1-16.2,9.3-22.4l194.3-194.3c12.4-12.4,32.4-12.4,44.8,0c12.4,12.4,12.4,32.4,0,44.7L50.5,351.1L222.4,523c12.4,12.4,12.4,32.4,0,44.7c-12.4,12.4-32.4,12.4-44.7,0L-16.6,373.4C-22.8,367.3-25.9,359.2-25.9,351.1z"/></g>' +
                    '</svg>';
                if(this.settings.showTitle){
                    subHtmlCont2 = '<div class="' + this.settings.classPrefix + 'title"></div>';
                }
                close2 = '<div class="barCont"></div><span class="' + this.settings.classPrefix + 'close ' + $object.settings.classPrefix + 'icon">' + $close_bg + '</span>';
                break;
            case 'view3':
                $_next = '<svg class="next_bg" width="22px" height="22px" fill="#999" viewBox="-104 105.6 490.4 490.4" >' +
                    '<g><g><path d="M141.2,596c135.2,0,245.2-110,245.2-245.2s-110-245.2-245.2-245.2S-104,215.6-104,350.8S6,596,141.2,596z M141.2,130.1c121.7,0,220.7,99,220.7,220.7s-99,220.7-220.7,220.7s-220.7-99-220.7-220.7S19.5,130.1,141.2,130.1z"/>' +
                    '<path d="M34.7,363.1h183.4l-48,48c-4.8,4.8-4.8,12.5,0,17.3c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6l68.9-68.9c4.8-4.8,4.8-12.5,0-17.3l-68.9-68.9c-4.8-4.8-12.5-4.8-17.3,0s-4.8,12.5,0,17.3l48,48H34.7c-6.8,0-12.3,5.5-12.3,12.3C22.4,357.7,27.9,363.1,34.7,363.1z"/></g></g>' +
                    '</svg>';
                $_prev = '<svg class="prev_bg" width="22px" height="22px" fill="#999" viewBox="-104 105.6 490.4 490.4" >' +
                    '<g><g><path d="M141.2,596c135.2,0,245.2-110,245.2-245.2s-110-245.2-245.2-245.2S-104,215.6-104,350.8S6,596,141.2,596z M141.2,130.1c121.7,0,220.7,99,220.7,220.7s-99,220.7-220.7,220.7s-220.7-99-220.7-220.7S19.5,130.1,141.2,130.1z"/>' +
                    '<path d="M94.9,428.4c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6c4.8-4.8,4.8-12.5,0-17.3l-48-48h183.4c6.8,0,12.3-5.5,12.3-12.3c0-6.8-5.5-12.3-12.3-12.3H64.3l48-48c4.8-4.8,4.8-12.5,0-17.3c-4.8-4.8-12.5-4.8-17.3,0l-68.9,68.9c-4.8,4.8-4.8,12.5,0,17.3L94.9,428.4z"/></g></g>' +
                    '</svg>';
                if(this.settings.showTitle){
                    subHtmlCont1 = '<div class="' + this.settings.classPrefix + 'title"></div>';
                }
                close1 = '<span class="' + this.settings.classPrefix + 'close ' + $object.settings.classPrefix + 'icon">' + $close_bg + '</span>';
                break;
            case 'view4':
                $_next = '<svg class="next_bg" width="22px" height="22px" fill="#999" viewBox="-123 125.2 451.8 451.8" >' +
                    '<g><path d="M222.4,373.4L28.2,567.7c-12.4,12.4-32.4,12.4-44.8,0c-12.4-12.4-12.4-32.4,0-44.7l171.9-171.9L-16.6,179.2c-12.4-12.4-12.4-32.4,0-44.7c12.4-12.4,32.4-12.4,44.8,0l194.3,194.3c6.2,6.2,9.3,14.3,9.3,22.4C231.7,359.2,228.6,367.3,222.4,373.4z"/></g>' +
                    '</svg>';
                $_prev = '<svg class="prev_bg" width="22px" height="22px" fill="#999" viewBox="-123 125.2 451.8 451.8" >' +
                    '<g><path d="M-25.9,351.1c0-8.1,3.1-16.2,9.3-22.4l194.3-194.3c12.4-12.4,32.4-12.4,44.8,0c12.4,12.4,12.4,32.4,0,44.7L50.5,351.1L222.4,523c12.4,12.4,12.4,32.4,0,44.7c-12.4,12.4-32.4,12.4-44.7,0L-16.6,373.4C-22.8,367.3-25.9,359.2-25.9,351.1z"/></g>' +
                    '</svg>';
                $close_bg = '<svg class="close_bg" width="16px" height="16px" fill="#999" viewBox="-341 343.4 15.6 15.6">' +
                    '<path d="M-332.1,351.2l6.5-6.5c0.3-0.3,0.3-0.8,0-1.1s-0.8-0.3-1.1,0l-6.5,6.5l-6.5-6.5c-0.3-0.3-0.8-0.3-1.1,0s-0.3,0.8,0,1.1l6.5,6.5l-6.5,6.5c-0.3,0.3-0.3,0.8,0,1.1c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2l6.5-6.5l6.5,6.5c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2c0.3-0.3,0.3-0.8,0-1.1L-332.1,351.2z"/>' +
                    '</svg>';
                if(this.settings.showTitle){
                    subHtmlCont2 = '<div class="' + this.settings.classPrefix + 'title"></div>';
                }
                close1 = '<span class="' + this.settings.classPrefix + 'close ' + $object.settings.classPrefix + 'icon">' + $close_bg + '</span>';
                break;
            case 'view5':
                $_next = '<svg class="next_bg" width="22px" height="44px" fill="#999" x="0px" y="0px"' +
                    'viewBox="0 0 40 70" style="enable-background:new 0 0 40 70;" xml:space="preserve">' +
                    '<path id="XMLID_2_" class="st0" d="M3.3,1.5L1.8,2.9l31.8,31.8c0.5,0.5,0.5,0.9,0,1.4L1.8,67.9l1.5,1.4c0.3,0.5,0.9,0.5,1.4,0' +
                    'l33.2-33.2c0.3-0.5,0.3-0.9,0-1.4L4.7,1.5C4.3,1,3.6,1,3.3,1.5L3.3,1.5z"/>' +
                    '</svg>';
                $_prev = '<svg class="prev_bg" width="22px" height="44px" fill="#999" x="0px" y="0px"' +
                    'viewBox="0 0 40 70" style="enable-background:new 0 0 40 70;" xml:space="preserve">' +
                    '<path id="XMLID_2_" class="st0" d="M37.1,68.9l1.5-1.4L6.8,35.7c-0.3-0.5-0.3-0.9,0-1.4L38.6,2.5l-1.5-1.4c-0.3-0.5-0.9-0.5-1.2,0' +
                    'L2.5,34.3c-0.3,0.5-0.3,0.9,0,1.4l33.4,33.2C36.2,69.4,36.8,69.4,37.1,68.9L37.1,68.9z"/>' +
                    '</svg>';
                $close_bg = '<svg class="close_bg" width="16px" height="16px" fill="#999" viewBox="-341 343.4 15.6 15.6">' +
                    '<path d="M-332.1,351.2l6.5-6.5c0.3-0.3,0.3-0.8,0-1.1s-0.8-0.3-1.1,0l-6.5,6.5l-6.5-6.5c-0.3-0.3-0.8-0.3-1.1,0s-0.3,0.8,0,1.1l6.5,6.5l-6.5,6.5c-0.3,0.3-0.3,0.8,0,1.1c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2l6.5-6.5l6.5,6.5c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2c0.3-0.3,0.3-0.8,0-1.1L-332.1,351.2z"/>' +
                    '</svg>';
                if(this.settings.showTitle){
                    subHtmlCont3 += '<div class="' + this.settings.classPrefix + 'title"></div>';
                }
                if(this.settings.showDesc){
                    subHtmlCont3 += '<div class="' + this.settings.classPrefix + 'description"></div>';
                }
                close1 = '<span class="' + this.settings.classPrefix + 'close ' + $object.settings.classPrefix + 'icon">' + $close_bg + '</span>';
                break;
        }

        if (this.settings.arrows && this.$items.length > 1) {
            controls = '<div class="' + this.settings.classPrefix + 'arrows">' +
                '<div class="' + this.settings.classPrefix + 'prev ' + $object.settings.classPrefix + 'icon">' + $_prev + this.settings.prevHtml + '</div>' +
                '<div class="' + this.settings.classPrefix + 'next ' + $object.settings.classPrefix + 'icon">' + $_next + this.settings.nextHtml + '</div>' +
                '</div>';
        }

        if (this.settings.socialSharing && this.settings.lightboxView !== 'view5') {
            socialIcons = '<div class="' + this.settings.classPrefix + 'socialIcons"><button class="shareLook">share</button></div>';
        }

        $contInner = (this.settings.lightboxView === 'view5') ? '<div class="contInner">' + subHtmlCont3 + '</div>' : '';
        
        template = '<div class="' + this.settings.classPrefix + 'cont ">' +
            '<div class="rwd-container rwd-' + this.settings.lightboxView + '">' +
            '<div class="cont-inner">' + list + '</div>' +
            $contInner +
            '<div class="' + this.settings.classPrefix + 'toolbar group">' +
            close1 + subHtmlCont2 +
            '</div>' +
            controls +
            '<div class="' + this.settings.classPrefix + 'bar">' +
            close2 + subHtmlCont1 + socialIcons + '</div>' +
            '</div>' +
            '</div>';

        if ($object.settings.socialSharing) {
            setTimeout(function () {
                $object.socialShare();
            }, 50);
        }

        this.$body.append(template);
        this.$cont = $('.' + $object.settings.classPrefix + 'cont');
        this.$item = this.$cont.find('.' + $object.settings.classPrefix + 'item');

        if (!this.settings.slideAnimation) {
            this.$cont.addClass(this.settings.classPrefix + 'animation');
            this.settings.slideAnimationType = this.settings.classPrefix + 'slide';
        } else {
            this.$cont.addClass(this.settings.classPrefix + 'use');
        }

        $object.calculateDimensions();

        $(window).on('resize.rwd-container', function () {
            setTimeout(function () {
                $object.calculateDimensions();
            }, 100);
        });

        this.$item.eq(this.index).addClass(this.settings.classPrefix + 'current');

        if (this.effectsSupport()) {
            this.$cont.addClass(this.settings.classPrefix + 'support');
        } else {
            this.$cont.addClass(this.settings.classPrefix + 'noSupport');
            this.settings.speed = 0;
        }

        this.$cont.addClass(this.settings.slideAnimationType);

        ((this.settings.showAfterLoad) && (this.$cont.addClass(this.settings.classPrefix + 'show-after-load')));

        if (this.effectsSupport()) {
            var $inner = this.$cont.find('.cont-inner');
            $inner.css('transition-timing-function', 'ease');
            $inner.css('transition-duration', this.settings.speed + 'ms');
        }

        switch($object.settings.lightboxView){
            case 'view1':
            case 'view2':
            case 'view3':
                $inner.css({
                    height: 'calc(100% - 92px)',
                    top: '47px'
                });
                break;
            case 'view4':
                $inner.css({
                    height: 'calc(100% - 92px)',
                    top: '45px'
                });
                break;
        }

        $object.objects.overlay.addClass('in');

        setTimeout(function () {
            $object.$cont.addClass($object.settings.classPrefix + 'visible');
        }, this.settings.overlayDuration);

        if (this.settings.download) {
            $download_bg = '<svg class="download_bg" width="20px" height="20px" stroke="#999" fill="#999"  viewBox="-328 330.3 41.7 41.7" >' +
                '<path class="st0" d="M-296.4,352.1c0.4-0.4,0.4-1.1,0-1.6c-0.4-0.4-1.1-0.4-1.6,0l-8,8V332c0-0.6-0.5-1.1-1.1-1.1c-0.6,0-1.1,0.5-1.1,1.1v26.5l-8-8c-0.4-0.4-1.2-0.4-1.6,0c-0.4,0.4-0.4,1.1,0,1.6l10,10c0.4,0.4,1.1,0.4,1.6,0L-296.4,352.1zM-288.5,359.4c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1v10.9c0,0.6-0.5,1.1-1.1,1.1h-39.5c-0.6,0-1.1-0.5-1.1-1.1v-10.9c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1v9.8h37.2V359.4z"/>' +
                '</svg>';
            $download_bg_ = '<svg class="download_bg" width="36px" height="34px" stroke="#999" fill="#999" x="0px" y="0px"' +
                'viewBox="0 0 90 90" style="enable-background:new 0 0 90 90;" xml:space="preserve">' +
                '<path id="XMLID_2_" class="st0" d="M61.3,31.8L45.5,47.7c-0.2,0.2-0.5,0.2-0.7,0l-16-15.9c-0.2-0.2-0.2-0.5,0-0.7l2.1-2.1l12.6,12.6' +
                'V7.4c0-0.9,0.7-1.7,1.7-1.7s1.8,0.8,1.8,1.7v34l12.2-12.3l2.1,2.1C61.5,31.3,61.5,31.6,61.3,31.8L61.3,31.8z"/>' +
                '<path id="XMLID_3_" class="st0" d="M25.6,50.7L25.6,50.7h38.7c1.6,0,2.8,1.2,2.8,2.7v1.5c0,1.6-1.2,2.9-2.8,2.9H25.6' +
                'c-1.5,0-2.8-1.3-2.8-2.9v-1.5C22.9,51.9,24.1,50.7,25.6,50.7L25.6,50.7z"/>' +
                '</svg>';
            switch (this.settings.lightboxView) {
                case 'view1':
                default:
                    this.$cont.find('.' + $object.settings.classPrefix + 'toolbar').append('<a id="' + $object.settings.classPrefix + 'download" target="_blank" download class="' + this.settings.classPrefix + 'download ' + $object.settings.classPrefix + 'icon">' + $download_bg + '</a>');
                    break;
                case 'view2':
                    this.$cont.find('.' + $object.settings.classPrefix + 'bar').append('<a id="' + $object.settings.classPrefix + 'download" target="_blank" download class="' + this.settings.classPrefix + 'download ' + $object.settings.classPrefix + 'icon">' + $download_bg + '</a>');
                    break;
                case 'view4':
                    $('<a id="' + $object.settings.classPrefix + 'download" target="_blank" download class="' + this.settings.classPrefix + 'download ' + $object.settings.classPrefix + 'icon">' + $download_bg + '</a>').insertBefore($('.rwd-title'));
                    break;
                case 'view5':
                    $('.rwd-toolbar').append('<a id="' + $object.settings.classPrefix + 'download" target="_blank" download class="' + this.settings.classPrefix + 'download ' + $object.settings.classPrefix + 'icon">' + $download_bg_ + '</a>');
                    break;
            }
        }

        $arrows = $('.rwd-arrows .rwd-next, .rwd-arrows .rwd-prev');
        $next = $('.rwd-arrows .rwd-next');
        $prev = $('.rwd-arrows .rwd-prev');

        var title_text = $('.rwd-title');

        switch (this.settings.titlePos) {
            case 'left':
                title_text.css({'text-align': 'left'});
                break;
            case 'center':
                title_text.css({'text-align': 'center'});
                break;
            case 'right':
                title_text.css({'text-align': 'right'});
                break;
        }

        switch (this.settings.lightboxView) {
            case 'view1':
            default:
                $arrows.css({'top': '50%'});
                $next.css({'right': '20px'});
                $prev.css({'left': '20px'});
                break;
            case 'view2':
                $arrows.css({'bottom': '0'});
                $next.css({'right': '40%'});
                $prev.css({'left': '40%'});
                break;
            case 'view3':
                $arrows.css({'top': '14px', 'z-index': '1090'});
                $next.css({'right': '20px'});
                $prev.css({'right': '55px'});
                title_text.css({'text-align': 'left', 'border-top': '1px solid #999'});
                $('.rwd-close').css({'margin-right': '45%'});
                $('.rwd-overlay, .rwd-toolbar, .rwd-title, .rwd-next, .rwd-prev').css({'background': 'rgba(255, 255, 255, 1)'});
                $('.rwd-title, .shareLook').css({'color': '#999'});
                $('.rwd-toolbar').css({'border-bottom': '1px solid #999'});
                $('.rwd-toolbar .rwd-icon, .rwd-arrows .rwd-icon').addClass('rwd-icon0');
                break;
        }

        this.prevScrollTop = $(window).scrollTop();

        $object.objects.content = $('.rwd-container');

        $object.objects.content.css({
            'width': $object.settings.width,
            'height': $object.settings.height
        });

        var $color, $zoomTop = (document.documentElement.clientHeight - $object.objects.content.height()) / 2;
        switch (this.settings.lightboxView){
            case 'view3':
                $color = 'rgba(255,255,255,.9)';
                break;
            default:
                $color = 'rgba(0,0,0,.9)';
                break;
        }
            setTimeout(function () {
            $('.rwd-container').bind('contextmenu', function () {
                return false;
            });
        }, 50);

        if(this.settings.showBorder){
            $('.rwd-container').css({
                border: '2px solid #999'
            });
        }
    };

    Lightbox.prototype.calculateDimensions = function () {
        var $object = this, $width;

        $width = $('.' + $object.settings.classPrefix + 'current').height() * 16 / 9;

        if ($width > $object.settings.videoMaxWidth) {
            $width = $object.settings.videoMaxWidth;
        }

        $('.' + $object.settings.classPrefix + 'video-cont ').css({
            'max-width': $width + 'px'
        });
    };

    Lightbox.prototype.effectsSupport = function () {
        var transition, root, support;
        support = function () {
            transition = ['transition', 'MozTransition', 'WebkitTransition', 'OTransition', 'msTransition', 'KhtmlTransition'];
            root = document.documentElement;
            for (var i = 0; i < transition.length; i++) {
                if (transition[i] in root.style) {
                    return transition[i] in root.style;
                }
            }
        };

        return support();
    };

    Lightbox.prototype.isVideo = function (src, index) {

        var youtube, vimeo;

        youtube = src.match(/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9\-\_\%]+)/i);
        vimeo = src.match(/\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i);

        if (youtube) {
            return {
                youtube: youtube
            };
        } else if (vimeo) {
            return {
                vimeo: vimeo
            };
        }
    };

    Lightbox.prototype.counter = function () {
        if (this.settings.showCounter) {
            switch (this.settings.lightboxView) {
                case 'view1':
                default:
                    $('.' + this.settings.classPrefix + 'toolbar').append(this.objects.counter = $('<div id="' + this.settings.idPrefix + 'counter"></div>'));
                    $('#rwd-counter').css({'padding-left': '23px'});
                    break;
                case 'view2':
                case 'view4':
                    $('.' + this.settings.classPrefix + 'bar').append('<div class="barCont"></div>').append(this.objects.counter = $('<div id="' + this.settings.idPrefix + 'counter"></div>'));
                    break;
                case 'view5':
                    $('.contInner').append(this.objects.counter = $('<div id="' + this.settings.idPrefix + 'counter"></div>'));
                    break;
            }

            this.objects.counter.append(
                this.objects.current = $('<div>' + this.settings.sequence_info + ' <span id="' + this.settings.idPrefix + 'counter-current">' + (parseInt(this.index, 10) + 1) + '</span> ' +
                    this.settings.sequenceInfo + ' <span id="' + this.settings.idPrefix + 'counter-all">' + this.$items.length + '</span></div>')
            );
        }
    };

    Lightbox.prototype.setTitle = function (index) {
        var $object = this, $title, $currentElement;

        $currentElement = this.$items.eq(index);
        $title = $currentElement.find('img').attr('alt') ||
            $currentElement.find('img').attr('title') ||
            this.settings.defaultTitle || '';

        this.$cont.find('.' + this.settings.classPrefix + 'title').html('<div class="rwd-title-text">' + $title + '</div>');

        (($object.settings.lightboxView === 'view2') && $('.rwd-title-text').css({'width': '100%'}));

        if ($object.settings.lightboxView !== 'view1' && $object.settings.lightboxView !== 'view3' && $object.settings.lightboxView !== 'view4') {
            ($title === '' && $object.settings.socialSharing) ?
                this.$cont.find('.' + this.settings.classPrefix + 'title').hide() :
                this.$cont.find('.' + this.settings.classPrefix + 'title').show();
        }
    };

    Lightbox.prototype.setDescription = function (index) {
        var $object = this, $description, $currentElement;

        $currentElement = this.$items.eq(index);
        $description = $currentElement.find('img').attr('data-description') || '';

        this.$cont.find('.' + this.settings.classPrefix + 'description').html('<div class="rwd-description-text" title="' + $description + '">' + $description + '</div>');
    };

    Lightbox.prototype.preload = function (index) {
        for (var i = 1; i <= this.settings.preload; i++) {
            if (i >= this.$items.length - index) {
                break;
            }

            this.loadContent(index + i, false, 0);
        }

        for (var j = 1; j <= this.settings.preload; j++) {
            if (index - j < 0) {
                break;
            }

            this.loadContent(index - j, false, 0);
        }
    };

    Lightbox.prototype.socialShare = function () {
        var $object = this;

        var shareButtons = '<ul class="rwd-share-buttons">';
        shareButtons += $object.settings.share.facebookButton ? '<li><a title="Facebook" id="rwd-share-facebook" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.twitterButton ? '<li><a title="Twitter" id="rwd-share-twitter" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.googleplusButton ? '<li><a title="Google Plus" id="rwd-share-googleplus" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.pinterestButton ? '<li><a title="Pinterest" id="rwd-share-pinterest" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.linkedinButton ? '<li><a title="Linkedin" id="rwd-share-linkedin" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.tumblrButton ? '<li><a title="Tumblr" id="rwd-share-tumblr" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.redditButton ? '<li><a title="Reddit" id="rwd-share-reddit" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.bufferButton ? '<li><a title="Buffer" id="rwd-share-buffer" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.diggButton ? '<li><a title="Digg" id="rwd-share-digg" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.vkButton ? '<li><a title="VK" id="rwd-share-vk" target="_blank"></a></li>' : '';
        shareButtons += $object.settings.share.yummlyButton ? '<li><a title="Yummly" id="rwd-share-yummly" target="_blank"></a></li>' : '';
        shareButtons += '</ul>';

        if (this.settings.lightboxView === 'view5') {
            $('.contInner').append(shareButtons);
        } else {
            $('.' + this.settings.classPrefix + 'socialIcons').append(shareButtons);
        }

        setTimeout(function () {
            $('#rwd-share-facebook').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-twitter').attr('href', 'https://twitter.com/intent/tweet?text=&url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-googleplus').attr('href', 'https://plus.google.com/share?url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-pinterest').attr('href', 'http://www.pinterest.com/pin/create/button/?url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-linkedin').attr('href', 'http://www.linkedin.com/shareArticle?mini=true&amp;url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-tumblr').attr('href', 'http://www.tumblr.com/share/link?url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-reddit').attr('href', 'http://reddit.com/submit?url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-buffer').attr('href', 'https://bufferapp.com/add?url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-digg').attr('href', 'http://www.digg.com/submit?url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-vk').attr('href', 'http://vkontakte.ru/share.php?url=' + (encodeURIComponent(window.location.href)));
            $('#rwd-share-yummly').attr('href', 'http://www.yummly.com/urb/verify?url=' + (encodeURIComponent(window.location.href)));
        }, 200);
    };

    Lightbox.prototype.changeHash = function (index) {
        var $object = this;

        (($object.settings.socialSharing) && (window.location.hash = '/lightbox&slide=' + (index + 1)));
    };

    Lightbox.prototype.loadContent = function (index, rec, delay) {

        var $object, src, isVideo;

        $object = this;

        function isImg() {
            src = $object.$items.eq(index).attr('href');
            return src.match(/\.(jpg|png|gif)\b/);
        }

        if ($object.settings.watermark) {
            if (isImg()) {
                src = $object.$items.eq(index).find('img').attr('data-src');
            }
        } else {
            src = $object.$items.eq(index).attr('href');
        }

        isVideo = $object.isVideo(src, index);
        if (!$object.$item.eq(index).hasClass($object.settings.classPrefix + 'loaded')) {
            if (isVideo) {
                $object.$item.eq(index).prepend('<div class="' + this.settings.classPrefix + 'video-cont "><div class="' + this.settings.classPrefix + 'video"></div></div>');
                $object.$element.trigger('hasVideo.rwd-container', [index, src]);
            } else {
                $object.$item.eq(index).prepend('<div class="' + this.settings.classPrefix + 'img-wrap"><img class="' + this.settings.classPrefix + 'object ' + $object.settings.classPrefix + 'image watermark" src="' + src + '" /></div>');
            }

            $object.$element.trigger('onAferAppendSlide.rwd-container', [index]);

            $object.$item.eq(index).addClass($object.settings.classPrefix + 'loaded');
        }

        $object.$item.eq(index).find('.' + $object.settings.classPrefix + 'object').on('load.rwd-container error.rwd-container', function () {

            var speed = 0;
            if (delay) {
                speed = delay;
            }

            setTimeout(function () {
                $object.$item.eq(index).addClass($object.settings.classPrefix + 'complete');
            }, speed);

        });

        if (rec === true) {

            if (!$object.$item.eq(index).hasClass($object.settings.classPrefix + 'complete')) {
                $object.$item.eq(index).find('.' + $object.settings.classPrefix + 'object').on('load.rwd-container error.rwd-container', function () {
                    $object.preload(index);
                });
            } else {
                $object.preload(index);
            }
        }

        if(hugeit_resp_lightbox_obj.hugeit_lightbox_imageframe !== 'frame_0'){
            $('.rwd-image').css({
                borderColor: '#f4be52',
                borderStyle: 'inset',
                borderWidth: '60px'
            });
            if(hugeit_resp_lightbox_obj.hugeit_lightbox_imageframe !== 'frame_8'){
                $('.rwd-image').css({
                    borderImage: 'url("' + hugeit_resp_lightbox_plugins_url + hugeit_resp_lightbox_obj.hugeit_lightbox_imageframe + '.png") 90 95 95 90 stretch stretch'
                });
            } else {
                $('.rwd-image').css({
                    borderImage: 'url("' + hugeit_resp_lightbox_plugins_url + hugeit_resp_lightbox_obj.hugeit_lightbox_imageframe + '.png") 90 123 85 129 stretch stretch'
                });
            }
        }

    };

    Lightbox.prototype.slide = function (index, fromSlide, fromThumb) {

        var $object, prevIndex;
        $object = this;
        prevIndex = this.$cont.find('.' + $object.settings.classPrefix + 'current').index();

        var length = this.$item.length,
            time = 0,
            next = false,
            prev = false;

        if (this.settings.download) {
            var src;
            if (!this.settings.watermark) {
                src = $object.$items.eq(index).attr('data-download-url') !== 'false' && ($object.$items.eq(index).attr('data-download-url') || $object.$items.eq(index).attr('href'));
            }
            else {
                src = $object.$items.eq(index).find('img').attr('data-src');
            }
            if (src) {
                $('#' + $object.settings.classPrefix + 'download').attr('href', src);
                $object.$cont.removeClass($object.settings.classPrefix + 'hide-download');
                $object.$cont.removeClass($object.settings.classPrefix + 'hide-actual-size');
                $object.$cont.removeClass($object.settings.classPrefix + 'hide-fullwidth');
                $object.$cont.removeClass($object.settings.classPrefix + 'hide-zoom-in');
                $object.$cont.removeClass($object.settings.classPrefix + 'hide-zoom-out');
            } else {
                $object.$cont.addClass($object.settings.classPrefix + 'hide-download');
                $object.$cont.addClass($object.settings.classPrefix + 'hide-actual-size');
                $object.$cont.addClass($object.settings.classPrefix + 'hide-fullwidth');
                $object.$cont.addClass($object.settings.classPrefix + 'hide-zoom-in');
                $object.$cont.addClass($object.settings.classPrefix + 'hide-zoom-out');
            }
        }

        this.$element.trigger('onBeforeSlide.rwd-container', [prevIndex, index, fromSlide, fromThumb]);

        setTimeout(function () {
            $object.setTitle(index);
        }, time);

        if ($object.settings.lightboxView === 'view5') {
            setTimeout(function () {
                $object.setDescription(index);
            }, time);
        }

        this.arrowDisable(index);


        $object.$cont.addClass($object.settings.classPrefix + 'no-trans');

        this.$item.removeClass($object.settings.classPrefix + 'prev-slide ' + $object.settings.classPrefix + 'next-slide');
        if (!fromSlide) {

            if (index < prevIndex) {
                prev = true;
                if ((index === 0) && (prevIndex === length - 1) && !fromThumb) {
                    prev = false;
                    next = true;
                }
            } else if (index > prevIndex) {
                next = true;
                if ((index === length - 1) && (prevIndex === 0) && !fromThumb) {
                    prev = true;
                    next = false;
                }
            }

            if (prev) {

                this.$item.eq(index).addClass($object.settings.classPrefix + 'prev-slide');
                this.$item.eq(prevIndex).addClass($object.settings.classPrefix + 'next-slide');
            } else if (next) {

                this.$item.eq(index).addClass($object.settings.classPrefix + 'next-slide');
                this.$item.eq(prevIndex).addClass($object.settings.classPrefix + 'prev-slide');
            }

            setTimeout(function () {
                $object.$item.removeClass($object.settings.classPrefix + 'current');

                $object.$item.eq(index).addClass($object.settings.classPrefix + 'current');

                $object.$cont.removeClass($object.settings.classPrefix + 'no-trans');
            }, 50);
        } else {

            var slidePrev = index - 1;
            var slideNext = index + 1;

            if ((index === 0) && (prevIndex === length - 1)) {

                slideNext = 0;
                slidePrev = length - 1;
            } else if ((index === length - 1) && (prevIndex === 0)) {

                slideNext = 0;
                slidePrev = length - 1;
            }

            this.$item.removeClass($object.settings.classPrefix + 'prev-slide ' + $object.settings.classPrefix + 'current ' + $object.settings.classPrefix + 'next-slide');
            $object.$item.eq(slidePrev).addClass($object.settings.classPrefix + 'prev-slide');
            $object.$item.eq(slideNext).addClass($object.settings.classPrefix + 'next-slide');
            $object.$item.eq(index).addClass($object.settings.classPrefix + 'current');
        }

        $object.loadContent(index, true, $object.settings.overlayDuration);

        $object.$element.trigger('onAfterSlide.rwd-container', [prevIndex, index, fromSlide, fromThumb]);

        if (this.settings.showCounter) {
            $('#' + $object.settings.classPrefix + 'counter-current').text(index + 1);
        }

        if (this.settings.socialSharing) {
            $object.changeHash(index);
        }

        var $top, $left, $wWidth, $wHeight, $imgWidth, $imgHeight, $wmWidth, $wmHeight, $pos, $item;
        $item = $('.rwd-item.rwd-current');
        $pos = +hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_margin;
        $wWidth = +hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_containerWidth;
        $wHeight = +hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_textFontSize;
        $imgWidth = $object.$item.eq(index).find('img').width();
        $imgHeight = $object.$item.eq(index).find('img').height();
        $wmWidth = $item.width();
        $wmHeight = $item.height();

        switch ('pos' + hugeit_resp_lightbox_obj.hugeit_lightbox_watermark_position_new) {
            case 'pos1':
                $top = ($wmHeight - $imgHeight) / 2 + $pos;
                $left = ($wmWidth - $imgWidth) / 2 + $pos;
                break;
            case 'pos2':
                $top = ($wmHeight - $imgHeight) / 2 + $pos;
                $left = ($wmWidth - $wWidth) / 2;
                break;
            case 'pos3':
                $top = ($wmHeight - $imgHeight) / 2 + $pos;
                $left = ($wmWidth + $imgWidth) / 2 - $wWidth - $pos;
                break;
            case 'pos4':
                $top = ($wmHeight - $wHeight) / 2;
                $left = ($wmWidth - $imgWidth) / 2 + $pos;
                break;
            case 'pos5':
                $top = ($wmHeight - $wHeight) / 2;
                $left = ($wmWidth - $wWidth) / 2;
                break;
            case 'pos6':
                $top = ($wmHeight - $wHeight) / 2;
                $left = ($wmWidth + $imgWidth) / 2 - $wWidth - $pos;
                break;
            case 'pos7':
                $top = ($wmHeight + $imgHeight) / 2 - $wHeight - $pos;
                $left = ($wmWidth - $imgWidth) / 2 + $pos;
                break;
            case 'pos8':
                $top = ($wmHeight + $imgHeight) / 2 - $wHeight - $pos;
                $left = ($wmWidth - $wWidth) / 2;
                break;
            case 'pos9':
                $top = ($wmHeight + $imgHeight) / 2 - $wHeight - $pos;
                $left = ($wmWidth + $imgWidth) / 2 - $wWidth - $pos;
                break;
            default:
                $top = ($wmHeight - $wHeight) / 2;
                $left = ($wmWidth - $wWidth) / 2;
        }

        $('.w_url').css({
            position: 'absolute',
            width: $wWidth + 'px',
            height: $wHeight + 'px',
            top: $top + 'px',
            left: $left + 'px'
        });

        $object.calculateDimensions();

        $('.rwd-container .rwd-thumb-item img').css({
            opacity: 1 - 10 / 100
        });

        $('.rwd-container .rwd-thumb-item.active img').css({
            opacity: 1
        });
    };

    Lightbox.prototype.goToNextSlide = function (fromSlide) {
        var $object = this,
            $cont = $('.rwd-cont'),
            $imageObject, k;
        if (($object.index + 1) < $object.$item.length) {
            $object.index++;
            $object.slide($object.index, fromSlide, false);
        } else {
            if ($object.settings.loop === 'true') {
                $object.index = 0;
                $object.slide($object.index, fromSlide, false);
            }
        }

        if ($object.settings.fullwidth && $cont.hasClass('rwd-fullwidth-on')) {
            $imageObject = $cont.find('.rwd-image').eq($object.index);

            k = $imageObject.width() / $imageObject.height();
            if ($imageObject.width() > $imageObject.height() && k > 2) {
                $imageObject.css({
                    'min-width': '100%'
                });
            } else {
                $imageObject.css({
                    'min-height': '100%'
                });
            }
        }
    };

    Lightbox.prototype.goToPrevSlide = function (fromSlide) {
        var $object = this,
            $cont = $('.rwd-cont'),
            $imageObject, k;

        if ($object.index > 0) {
            $object.index--;
            $object.slide($object.index, fromSlide, false);
        } else {
            if ($object.settings.loop === 'true') {
                $object.index = $object.$items.length - 1;
                $object.slide($object.index, fromSlide, false);
            }
        }

        if ($object.settings.fullwidth && $cont.hasClass('rwd-fullwidth-on')) {
            $imageObject = $cont.find('.rwd-image').eq($object.index);

            k = $imageObject.width() / $imageObject.height();
            if ($imageObject.width() > $imageObject.height() && k > 2) {
                $imageObject.css({
                    'min-width': '100%'
                });
            } else {
                $imageObject.css({
                    'min-height': '100%'
                });
            }
        }
    };

    Lightbox.prototype.slideShow = function () {
        var $object = this, $toolbar, $play_bg, $pause_bg;

        $play_bg = '<svg class="play_bg" width="20px" height="20px" fill="#999" viewBox="-192 193.9 314.1 314.1">' +
            '<g><g id="_x33_56._Play"><g><path d="M101,272.5C57.6,197.4-38.4,171.6-113.5,215c-75.1,43.4-100.8,139.4-57.5,214.5c43.4,75.1,139.4,100.8,214.5,57.5C118.6,443.6,144.4,347.6,101,272.5z M27.8,459.7c-60.1,34.7-136.9,14.1-171.6-46c-34.7-60.1-14.1-136.9,46-171.6c60.1-34.7,136.9-14.1,171.6,46C108.5,348.2,87.9,425,27.8,459.7z M21.6,344.6l-82.2-47.9c-7.5-4.4-13.5-0.9-13.5,7.8l0.4,95.2c0,8.7,6.2,12.2,13.7,7.9l81.6-47.1C29,356,29,349,21.6,344.6z"/></g></g></g>' +
            '</svg>';
        $pause_bg = '<svg class="pause_bg" width="20px" height="20px" fill="#999" viewBox="-94 96 510 510" >' +
            '<g><g id="pause-circle-outline"><path d="M84.5,453h51V249h-51V453z M161,96C20.8,96-94,210.8-94,351S20.8,606,161,606s255-114.8,255-255S301.3,96,161,96zM161,555C48.8,555-43,463.2-43,351s91.8-204,204-204s204,91.8,204,204S273.2,555,161,555z M186.5,453h51V249h-51V453z"/></g></g>' +
            '</svg>';

        $toolbar = $('.' + $object.settings.classPrefix + 'toolbar');
        switch (this.settings.lightboxView) {
            case 'view1':
            default:
                $toolbar.append('<span class="' + $object.settings.classPrefix + 'autoplay-button ' + $object.settings.classPrefix + 'icon">' + $play_bg + $pause_bg + '</span>');
                break;
            case 'view2':
                $('.' + $object.settings.classPrefix + 'bar').append('<span class="' + $object.settings.classPrefix + 'autoplay-button ' + $object.settings.classPrefix + 'icon">' + $play_bg + $pause_bg + '</span>');
                break;
            case 'view3':
                $toolbar.append('<span class="' + $object.settings.classPrefix + 'autoplay-button ' + $object.settings.classPrefix + 'icon">' + $play_bg + $pause_bg + '</span>');
                $('.rwd-toolbar .rwd-icon').addClass('rwd-icon0');
                break;
            case 'view4':
                $('<span class="' + $object.settings.classPrefix + 'autoplay-button ' + $object.settings.classPrefix + 'icon">' + $play_bg + $pause_bg + '</span>').insertBefore($('.rwd-title'));
                $('.rwd-toolbar .rwd-icon').addClass('rwd-icon0');
                break;
        }
        if ($object.settings.slideshowAuto) {
            $object.slideshowAuto();
        }

        $object.$cont.find('.' + $object.settings.classPrefix + 'autoplay-button').on('click.rwd-container', function () {
            !$($object.$cont).hasClass($object.settings.classPrefix + 'show-autoplay') ? $object.startSlide() : $object.stopSlide();
        });

    };

    Lightbox.prototype.slideshowAuto = function () {
        var $object = this;

        $object.$cont.addClass('' + $object.settings.classPrefix + 'show-autoplay');
        $object.startSlide();
    };

    Lightbox.prototype.startSlide = function () {
        var $object = this;
        $object.$cont.addClass('' + $object.settings.classPrefix + 'show-autoplay');
        $('.rwd-autoplay-button > .pause_bg').css({'display': 'inline-block'});
        $('.rwd-autoplay-button > .play_bg').css({'display': 'none'});
        $object.interval = setInterval(function () {
            $object.goToNextSlide();
        }, $object.settings.slideshowSpeed);
    };

    Lightbox.prototype.stopSlide = function () {
        clearInterval(this.interval);
        this.$cont.removeClass(this.settings.classPrefix + 'show-autoplay');
        $('.rwd-thumb').removeClass('thumb_move');
        $('.rwd-autoplay-button > .pause_bg').css({'display': 'none'});
        $('.rwd-autoplay-button > .play_bg').css({'display': 'inline-block'});
    };

    Lightbox.prototype.addKeyEvents = function () {
        var $object = this;
        if (this.$items.length > 1) {
            $(window).on('keyup.rwd-container', function (e) {
                if ($object.$items.length > 1) {
                    if (e.keyCode === 37) {
                        e.preventDefault();
                        $object.goToPrevSlide();
                    }

                    if (e.keyCode === 39) {
                        e.preventDefault();
                        $object.goToNextSlide();
                    }
                }
            });
        }

        $(window).on('keydown.rwd-container', function (e) {
            if ($object.settings.escKey === true && e.keyCode === 27) {
                e.preventDefault();
                if (!$object.$cont.hasClass($object.settings.classPrefix + 'thumb-open')) {
                    $object.destroy();
                } else {
                    $object.$cont.removeClass($object.settings.classPrefix + 'thumb-open');
                }
            }
        });
    };

    Lightbox.prototype.arrow = function () {
        var $object = this;
        this.$cont.find('.' + $object.settings.classPrefix + 'prev').on('click.rwd-container', function () {
            $object.goToPrevSlide();
        });

        this.$cont.find('.' + $object.settings.classPrefix + 'next').on('click.rwd-container', function () {
            $object.goToNextSlide();
        });
    };

    Lightbox.prototype.arrowDisable = function (index) {

        if (!this.settings.loop === 'true' && this.settings.hideControlOnEnd) {
            if ((index + 1) < this.$item.length) {
                this.$cont.find('.' + this.settings.classPrefix + 'next').removeAttr('disabled').removeClass('disabled');
            } else {
                this.$cont.find('.' + this.settings.classPrefix + 'next').attr('disabled', 'disabled').addClass('disabled');
            }

            if (index > 0) {
                this.$cont.find('.' + this.settings.classPrefix + 'prev').removeAttr('disabled').removeClass('disabled');
            } else {
                this.$cont.find('.' + this.settings.classPrefix + 'prev').attr('disabled', 'disabled').addClass('disabled');
            }
        }
    };

    Lightbox.prototype.mousewheel = function () {
        var $object = this, delta;

        $object.$cont.on('mousewheel', function (e) {
            e = e || window.event;
            delta = e.deltaY || e.detail || e.wheelDelta;

            (delta > 0) ? $object.goToNextSlide() : $object.goToPrevSlide();
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);
        });

    };

    Lightbox.prototype.closeGallery = function () {

        var $object = this, mousedown = false;

        this.$cont.find('.' + $object.settings.classPrefix + 'close').on('click.rwd-container', function () {
            $object.destroy();
        });

        if ($object.settings.overlayClose === 'true') {

            $object.$cont.on('mousedown.rwd-container', function (e) {

                mousedown = ($(e.target).is('.' + $object.settings.classPrefix + 'cont') || $(e.target).is('.' + $object.settings.classPrefix + 'item ') || $(e.target).is('.' + $object.settings.classPrefix + 'img-wrap'));

            });

            $object.$cont.on('mouseup.rwd-container', function (e) {

                if ($(e.target).is('.contInner') || $(e.target).is('.' + $object.settings.classPrefix + 'cont') || $(e.target).is('.' + $object.settings.classPrefix + 'item ') || $(e.target).is('.' + $object.settings.classPrefix + 'img-wrap') && mousedown) {
                    if (!$object.$cont.hasClass($object.settings.classPrefix + 'dragEvent')) {
                        $object.destroy();
                    }
                }

            });

        }

    };

    Lightbox.prototype.destroy = function (d) {

        var $object = this;

        clearInterval($object.interval);

        $object.$body.removeClass($object.settings.classPrefix + 'on');

        $(window).scrollTop($object.prevScrollTop);

        if (d) {
            $.removeData($object.el, 'lightbox');
        }

        ($object.settings.socialSharing && (window.location.hash = ''));

        this.$element.off('.rwd-container');

        $(window).off('.rwd-container');

        if ($object.$cont) {
            $object.$cont.removeClass($object.settings.classPrefix + 'visible');
        }

        $object.objects.overlay.removeClass('in');

        setTimeout(function () {
            if ($object.$cont) {
                $object.$cont.remove();
            }

            $object.objects.overlay.remove();

        }, $object.settings.overlayDuration + 50);

        window.scrollTo(0, $object.$_y_);
    };

    $.fn.lightbox = function (options) {
        return this.each(function () {
            if (!$.data(this, 'lightbox')) {
                $.data(this, 'lightbox', new Lightbox(this, options));
            }
        });
    };

    $.fn.lightbox.lightboxModul = {};

    var Modul = function (element) {

        this.dataL = $(element).data('lightbox');
        this.$element = $(element);
        this.dataL.modulSettings = $.extend({}, this.constructor.defaultsModul);

        this.init();

        if (this.dataL.modulSettings.zoom && this.dataL.effectsSupport()) {
            this.initZoom();

            this.zoomabletimeout = false;

            this.pageX = $(window).width() / 2;
            this.pageY = ($(window).height() / 2) + $(window).scrollTop();
        }

        if (this.dataL.modulSettings.fullwidth && this.dataL.effectsSupport()) {
            this.initFullWidth();
        }

        this.$el = $(element);
        this.$thumbCont = null;
        this.thumbContWidth = 0;
        this.thumbTotalWidth = (this.dataL.$items.length * (this.dataL.modulSettings.thumbsWidth + this.dataL.modulSettings.thumbMargin));
        this.thumbIndex = this.dataL.index;
        this.left = 0;
        if(hugeit_resp_lightbox_obj.hugeit_lightbox_thumbs === 'true'){
            this.initThumbs();
        }

        return this;
    };

    Modul.defaultsModul = {
        idPrefix: 'rwd-',
        classPrefix: 'rwd-',
        attrPrefix: 'data-',
        videoMaxWidth: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_videoMaxWidth,
        fullwidth: hugeit_resp_lightbox_obj.hugeit_lightbox_fullwidth_effect === 'true',
        zoom: hugeit_resp_lightbox_obj.hugeit_lightbox_zoom,
        scale: +hugeit_resp_lightbox_obj.hugeit_lightbox_zoomsize / 10,
        thumbnail: hugeit_resp_lightbox_obj.hugeit_lightbox_thumbs,
        thumbsWidth: 100,
        thumbsHeight: 100,
        thumbMargin: 5,
        showByDefault: true,
        toogleThumb: false,
        thumbPosition: '0',
        thumbsOverlayColor: 'black',
        thumbsOverlayOpacity: 10
    };

    Modul.prototype.init = function () {
        var $object = this;

        $object.dataL.$element.on('hasVideo.rwd-container', function (event, index, src) {
            $object.dataL.$item.eq(index).find('.' + $object.dataL.modulSettings.classPrefix + 'video').append($object.loadVideo(src, '' + $object.dataL.modulSettings.classPrefix + 'object', index));
        });

        $object.dataL.$element.on('onAferAppendSlide.rwd-container', function (event, index) {
            $object.dataL.$item.eq(index).find('.' + $object.dataL.settings.classPrefix + 'video-cont').css({
                'max-width': $object.dataL.modulSettings.videoMaxWidth + 'px'
            });
        });

        $object.dataL.$element.on('onBeforeSlide.rwd-container', function (event, prevIndex, index) {

            var $videoSlide = $object.dataL.$item.eq(prevIndex),
                youtubePlayer = $videoSlide.find('.rwd-youtube').get(0),
                vimeoPlayer = $videoSlide.find('.rwd-vimeo').get(0);

            if (youtubePlayer) {
                youtubePlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            } else if (vimeoPlayer) {
                try {
                    $f(vimeoPlayer).api('pause');
                } catch (e) {
                    console.error('Make sure you have included froogaloop2 js');
                }
            }

            var src;
            src = $object.dataL.$items.eq(index).attr('href');

            var isVideo = $object.dataL.isVideo(src, index) || {};
            if (isVideo.youtube || isVideo.vimeo) {
                $object.dataL.$cont.addClass($object.dataL.modulSettings.classPrefix + 'hide-download');
                $object.dataL.$cont.addClass($object.dataL.modulSettings.classPrefix + 'hide-actual-size');
                $object.dataL.$cont.addClass($object.dataL.modulSettings.classPrefix + 'hide-fullwidth');
                $object.dataL.$cont.addClass($object.dataL.modulSettings.classPrefix + 'hide-zoom-in');
                $object.dataL.$cont.addClass($object.dataL.modulSettings.classPrefix + 'hide-zoom-out');
            }

        });

        $object.dataL.$element.on('onAfterSlide.rwd-container', function (event, prevIndex) {
            $object.dataL.$item.eq(prevIndex).removeClass($object.dataL.modulSettings.classPrefix + 'video-playing');
        });
    };

    Modul.prototype.loadVideo = function (src, addClass, index) {
        var video = '',
            autoplay = 0,
            a = '',
            isVideo = this.dataL.isVideo(src, index) || {};

        if (isVideo.youtube) {

            a = '?wmode=opaque&autoplay=' + autoplay + '&enablejsapi=1';

            video = '<iframe class="' + this.dataL.modulSettings.classPrefix + 'video-object ' + this.dataL.modulSettings.classPrefix + 'youtube ' + addClass + '" width="560" height="315" src="//www.youtube.com/embed/' + isVideo.youtube[1] + a + '" frameborder="0" allowfullscreen></iframe>';

        } else if (isVideo.vimeo) {

            a = '?autoplay=' + autoplay + '&api=1';

            video = '<iframe class="' + this.dataL.modulSettings.classPrefix + 'video-object ' + this.dataL.modulSettings.classPrefix + 'vimeo ' + addClass + '" width="560" height="315"  src="//player.vimeo.com/video/' + isVideo.vimeo[1] + a + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

        }

        return video;
    };

    Modul.prototype.initFullWidth = function () {
        var $object = this,
            $fullWidth, $fullWidthOn;

        $fullWidth = '<svg id="rwd-fullwidth" width="20px" height="20px" stroke="#999" fill="#999" x="0px" y="0px" viewBox="134 -133 357 357" style="enable-background:new 134 -133 357 357;">' +
            '<g><g id="fullscreen"><path d="M165,96.5h-31V224h127.5v-31H165V96.5z M134-5.5h31V-82h96.5v-31H134V-5.5z M440,193h-76.5v31H491V96.5h-31V192z M363.5-103v21H460v76.5h31V-113H363.5z"></path>' +
            '</g></g></svg>';

        $fullWidthOn = '<svg id="rwd-fullwidth_on" width="20px" height="20px" stroke="#999" fill="#999" x="0px" y="0px" viewBox="134 -133 357 357" style="enable-background:new 134 -133 357 357;">' +
            '<g><g id="fullscreen-exit"><path d="M134, 127.5h 96.5V 224h 31V 96.5H 114V 147.5z M210.5 -36.5H 134v 31h 127.5V -133h -31V -36.5z M363.5, 224h 31v -96.5H 491v -31H 363.5V 224z M394.5 -56.5V -133h -31V -5.5H 491v -31H 395.5z"></path>' +
            '</g></g></svg>';

        if (this.dataL.modulSettings.fullwidth) {
            var fullwidth = '<span class="rwd-fullwidth rwd-icon">' + $fullWidth + $fullWidthOn + '</span>';
            switch (hugeit_resp_lightbox_obj.hugeit_lightbox_lightboxView) {
                case 'view1':
                default:
                    this.dataL.$cont.find('.rwd-toolbar').append(fullwidth);
                    break;
                case 'view2':
                    this.dataL.$cont.find('.rwd-bar').append(fullwidth);
                    break;
                case 'view4':
                    $(fullwidth).insertBefore('.rwd-title');
                    break;
            }

        }

        if (this.dataL.modulSettings.fullwidth) {
            $('.rwd-fullwidth').on('click.rwd-container', function () {
                !$('.rwd-cont').hasClass('rwd-fullwidth-on') ? $object.onFullWidth() : $object.offFullWidth();
            });
        }
    };

    Modul.prototype.onFullWidth = function () {

        var $imageObject = this.dataL.$cont.find('.rwd-current .rwd-image');

        $('#rwd-fullwidth').css({'display': 'none'});
        $('#rwd-fullwidth_on').css({'display': 'inline-block'});

        $('.rwd-cont').addClass('rwd-fullwidth-on');

        $('.rwd-container').css({
            width: '100%',
            height: '100%'
        });

        var k = $imageObject.width() / $imageObject.height();
        if ($imageObject.width() > $imageObject.height() && k > 2) {
            $imageObject.css({
                'min-width': '100%'
            });
        } else {
            $imageObject.css({
                'min-height': '100%'
            });
        }
    };

    Modul.prototype.offFullWidth = function () {
        var $imageObject = this.dataL.$cont.find('.rwd-current .rwd-image');

        $('#rwd-fullwidth').css({'display': 'inline-block'});
        $('#rwd-fullwidth_on').css({'display': 'none'});

        $('.rwd-cont').removeClass('rwd-fullwidth-on');
        $('.rwd-container').css({
            width: hugeit_resp_lightbox_obj.hugeit_lightbox_width_new + '%',
            height: hugeit_resp_lightbox_obj.hugeit_lightbox_height_new + '%'
        });
        $imageObject.css({
            'min-width': '',
            'min-height': ''
        });
    };

    Modul.prototype.initZoom = function () {

        var $object = this, zoomIcons,
            $zoomIn, $zoomOut, scale;

        $zoomIn = '<svg id="zoom_in" width="20px" height="20px" stroke="#999" fill="#999" x="0px" y="0px" viewBox="-18 19 53 53" style="enable-background:new -18 19 53 53;">' +
            '<g><path d="M11,39H5v-6c0-0.6-0.4-1-1-1s-1,0.4-1,1v6h-6c-0.6,0-1,0.4-1,1s0.4,1,1,1h6v6c0,0.6,0.4,1,1,1s1-0.4,1-1v-6h6' +
            'c0.6,0,1-0.4,1-1S11.5,39,11,39z"/>' +
            '<path d="M33.7,70.3L18.8,54.9c3.8-3.8,6.1-9,6.1-14.8c0-11.6-9.4-21-21-21s-21,9.4-21,21s9.4,21,21,21c5.1,0,9.7-1.8,13.4-4.8' +
            'l14.9,15.5c0.2,0.2,0.5,0.3,0.7,0.3c0.3,0,0.5-0.1,0.7-0.3C34.1,71.3,34.1,70.7,33.7,70.3z M-15,40c0-10.5,8.5-19,19-19' +
            's19,8.5,19,19S14.5,59,4,59S-15,50.5-15,40z"/></g>' +
            '</svg>';

        $zoomOut = '<svg id="zoom_out" width="20px" height="20px" stroke="#999" fill="#999" x="0px" y="0px" x="0px" y="0px" viewBox="-18 19 53 53" style="enable-background:new -18 19 53 53;">' +
            '<g><path d="M11,39H-3c-0.6,0-1,0.4-1,1s0.4,1,1,1h14c0.6,0,1-0.4,1-1S11.5,39,11,39z"/>' +
            '<path d="M33.7,70.3L18.8,54.9c3.8-3.8,6.1-9,6.1-14.8c0-11.6-9.4-21-21-21s-21,9.4-21,21s9.4,21,21,21c5.1,0,9.7-1.8,13.4-4.8' +
            'l14.9,15.5c0.2,0.2,0.5,0.3,0.7,0.3c0.3,0,0.5-0.1,0.7-0.3C34.1,71.3,34.1,70.7,33.7,70.3z M-15,40c0-10.5,8.5-19,19-19' +
            's19,8.5,19,19S14.5,59,4,59S-15,50.5-15,40z"/></g>' +
            '</svg>';

        zoomIcons = '<span id="rwd-zoom-out" class="rwd-icon">' + $zoomOut + '</span><span id="rwd-zoom-in" class="rwd-icon">' + $zoomIn + '</span>';

        switch (hugeit_resp_lightbox_obj.hugeit_lightbox_lightboxView) {
            case 'view1':
            default:
                this.dataL.$cont.find('.rwd-toolbar').append(zoomIcons);
                break;
            case 'view2':
                this.dataL.$cont.find('.rwd-bar').append(zoomIcons);
                break;
            case 'view4':
                $(zoomIcons).insertBefore('.rwd-title');
                break;
        }

        scale = 1;
        function zoom(scaleVal) {
            var $imageObject, _x, _y, offsetX, offsetY, x, y;

            $imageObject = $object.dataL.$cont.find('.rwd-current .rwd-image');

            offsetX = ($(window).width() - $imageObject.width()) / 2;
            offsetY = (($(window).height() - $imageObject.height()) / 2) + $(window).scrollTop();

            _x = $object.pageX - offsetX;
            _y = $object.pageY - offsetY;

            x = _x;
            y = _y;

            $imageObject.css('transform', 'scale3d(' + scaleVal + ', ' + scaleVal + ', 1)').attr('data-scale', scaleVal);

            $imageObject.parent().css({
                transform: 'translate3d(0, ' + -y + 'px, 0)'
            }).attr('data-y', -y);
        }

        function callScale() {
            if (scale > 1) {
                $object.dataL.$cont.addClass('rwd-zoomed');
            } else {
                $object.dataL.$cont.removeClass('rwd-zoomed');
            }

            if (scale < 1) {
                scale = 1;
            }

            zoom(scale);
        }

        $(window).on('resize.rwd-container.zoom scroll.rwd-container.zoom orientationchange.rwd-container.zoom', function () {
            $object.pageX = $(window).width() / 2;
            $object.pageY = ($(window).height() / 2) + $(window).scrollTop();
            zoom(scale);
        });

        $('#rwd-zoom-out').on('click.rwd-container', function () {
            if ($object.dataL.$cont.find('.rwd-current .rwd-image').length) {
                scale -= $object.dataL.modulSettings.scale;
                callScale();
            }
        });

        $('#rwd-zoom-in').on('click.rwd-container', function () {
            if ($object.dataL.$cont.find('.rwd-current .rwd-image').length) {
                scale += $object.dataL.modulSettings.scale;
                callScale();
            }
        });

        if (hugeit_resp_lightbox_obj.hugeit_lightbox_zoomlogo !== '0') {
            $object.dataL.$cont.dblclick(function () {
                if (!$object.dataL.$cont.hasClass('dbl-zoomed')) {
                    $object.dataL.$cont.addClass('dbl-zoomed');
                    if ($object.dataL.$cont.find('.rwd-current .rwd-image').length) {
                        scale += $object.dataL.modulSettings.scale;
                        callScale();
                    }
                } else {
                    $object.dataL.$cont.removeClass('dbl-zoomed');
                    if ($object.dataL.$cont.find('.rwd-current .rwd-image').length) {
                        scale -= $object.dataL.modulSettings.scale;
                        callScale();
                    }
                }
            });
        }

        if (!('ontouchstart' in document.documentElement)) {
            $object.zoomDrag();
        }

        if (('ontouchstart' in document.documentElement)) {
            $object.zoomSwipe();
        }

    };

    Modul.prototype.touchendZoom = function (startCoords, endCoords, abscissa, ordinate) {

        var $object = this, _$el, $imageObject, distanceX, distanceY, maxX, maxY;

        _$el = $object.dataL.$item.eq($object.dataL.index).find('.rwd-img-wrap');
        $imageObject = $object.dataL.$item.eq($object.dataL.index).find('.rwd-object');
        maxX = Math.abs($imageObject.outerWidth() * Math.abs($imageObject.attr('data-scale')) - $object.dataL.$cont.find('.rwd-container').width()) / 2;
        maxY = Math.abs($imageObject.outerHeight() * Math.abs($imageObject.attr('data-scale')) - $object.dataL.$cont.find('.rwd-container').height()) / 2 + $(window).scrollTop();

        if (_$el.attr('data-x')) {
            distanceX = +_$el.attr('data-x') + (endCoords.x - startCoords.x);
        } else {
            distanceX = endCoords.x - startCoords.x;
        }

        distanceY = +_$el.attr('data-y') + (endCoords.y - startCoords.y);

        if ((Math.abs(endCoords.x - startCoords.x) > 15) || (Math.abs(endCoords.y - startCoords.y) > 15)) {

            if (abscissa) {
                if (endCoords.x - startCoords.x < 0) {
                    if(distanceX <= -maxX){
                        distanceX = -maxX;
                    }
                } else {
                    if(distanceX >= maxX) {
                        distanceX = maxX;
                    }
                }

                _$el.attr('data-x', distanceX);
            }

            if (ordinate) {
                if (endCoords.y - startCoords.y < 0) {
                    if(distanceY <= -(maxY + ($object.pageY - ($(window).height() - $imageObject.height()) / 2)) + 2 * $(window).scrollTop()) {
                        distanceY = -(maxY + ($object.pageY - ($(window).height() - $imageObject.height()) / 2)) + 2 * $(window).scrollTop();
                    }
                } else {
                    if(distanceY >= maxY - ($object.pageY - ($(window).height() - $imageObject.height()) / 2)) {
                        distanceY = maxY - ($object.pageY - ($(window).height() - $imageObject.height()) / 2);
                    }
                }

                _$el.attr('data-y', distanceY);
            }

            _$el.css({
                transform: 'translate3d(' + distanceX + 'px, ' + distanceY + 'px, 0)'
            });

        }
    };

    Modul.prototype.zoomDrag = function () {

        var $object = this,
            startCoords = {},
            endCoords = {},
            isDraging = false,
            isMoved = false,
            abscissa = false,
            ordinate = false;

        $object.dataL.$item.on('mousedown.rwd-container.zoom', function (e) {

            var $imageObject = $object.dataL.$item.eq($object.dataL.index).find('.rwd-object');

            ordinate = $imageObject.outerHeight() * $imageObject.attr('data-scale') > $object.dataL.$cont.find('.rwd-container').height();
            abscissa = $imageObject.outerWidth() * $imageObject.attr('data-scale') > $object.dataL.$cont.find('.rwd-container').width();

            if ($object.dataL.$cont.hasClass('rwd-zoomed')) {
                if ($(e.target).hasClass('rwd-object') && (abscissa || ordinate)) {
                    e.preventDefault();
                    startCoords = {
                        x: e.pageX,
                        y: e.pageY
                    };

                    isDraging = true;

                    $object.dataL.$cont.scrollLeft += 1;
                    $object.dataL.$cont.scrollLeft -= 1;

                }
            }
        });

        $(window).on('mousemove.rwd-container.zoom', function (e) {
            if (isDraging) {
                var _$el = $object.dataL.$item.eq($object.dataL.index).find('.rwd-img-wrap');
                var distanceX;
                var distanceY;

                isMoved = true;
                endCoords = {
                    x: e.pageX,
                    y: e.pageY
                };

                if (_$el.attr('data-x')) {
                    distanceX = +_$el.attr('data-x') + (endCoords.x - startCoords.x);
                } else {
                    distanceX = endCoords.x - startCoords.x;
                }

                if (ordinate) {
                    distanceY = +_$el.attr('data-y') + (endCoords.y - startCoords.y);
                }

                _$el.css({
                    transform: 'translate3d(' + distanceX + 'px, ' + distanceY + 'px, 0)'
                });
            }
        });

        $(window).on('mouseup.rwd-container.zoom', function (e) {

            if (isDraging) {
                isDraging = false;

                if (isMoved && ((startCoords.x !== endCoords.x) || (startCoords.y !== endCoords.y))) {
                    endCoords = {
                        x: e.pageX,
                        y: e.pageY
                    };
                    $object.touchendZoom(startCoords, endCoords, abscissa, ordinate);

                }

                isMoved = false;
            }

        });
    };

    Modul.prototype.zoomSwipe = function () {
        var $object = this,
            startCoords = {},
            endCoords = {},
            isMoved = false,
            abscissa = false,                   ordinate = false;

        $object.dataL.$item.on('touchstart.rwd-container', function (e) {

            if ($object.dataL.$cont.hasClass('rwd-zoomed')) {
                var $imageObject = $object.dataL.$item.eq($object.dataL.index).find('.rwd-object');

                ordinate = $imageObject.outerHeight() * $imageObject.attr('data-scale') > $object.dataL.$cont.find('.rwd-container').height();
                abscissa = $imageObject.outerWidth() * $imageObject.attr('data-scale') > $object.dataL.$cont.find('.rwd-container').width();
                if ((abscissa || ordinate)) {
                    e.preventDefault();
                    startCoords = {
                        x: e.originalEvent.targetTouches[0].pageX,
                        y: e.originalEvent.targetTouches[0].pageY
                    };
                }
            }

        });

        $object.dataL.$item.on('touchmove.rwd-container', function (e) {

            if ($object.dataL.$cont.hasClass('rwd-zoomed')) {

                var _$el = $object.dataL.$item.eq($object.dataL.index).find('.rwd-img-wrap');
                var distanceX;
                var distanceY;

                e.preventDefault();
                isMoved = true;

                endCoords = {
                    x: e.originalEvent.targetTouches[0].pageX,
                    y: e.originalEvent.targetTouches[0].pageY
                };

                if (_$el.attr('data-x')) {
                    distanceX = +_$el.attr('data-x') + (endCoords.x - startCoords.x);
                } else {
                    distanceX = endCoords.x - startCoords.x;
                }

                if (ordinate) {
                    distanceY = +_$el.attr('data-y') + (endCoords.y - startCoords.y);
                }

                if ((Math.abs(endCoords.x - startCoords.x) > 15) || (Math.abs(endCoords.y - startCoords.y) > 15)) {
                    _$el.css({
                        transform: 'translate3d(' + distanceX + 'px, ' + distanceY + 'px, 0)'
                    });
                }

            }

        });

        $object.dataL.$item.on('touchend.rwd-container', function () {
            if ($object.dataL.$cont.hasClass('rwd-zoomed')) {
                if (isMoved) {
                    isMoved = false;
                    $object.touchendZoom(startCoords, endCoords, abscissa, ordinate);

                }
            }
        });

    };

    Modul.prototype.initThumbs = function() {
        var $object = this;

        if (this.dataL.modulSettings.thumbnail === 'true' && this.dataL.$items.length > 1) {

            if (this.dataL.modulSettings.showByDefault) {
                setTimeout(function(){
                    $object.dataL.$cont.addClass('rwd-thumb-open');
                }, 100);
            }

            this.buildThumbs();

            this.dataL.effectsSupport() && this.enableThumbDrag();

            this.activatedThumbs = false;

            if ($object.dataL.modulSettings.toogleThumb) {
                $object.$thumbCont.append('<span class="rwd-toggle-thumb rwd-icon"></span>');
                $object.dataL.$cont.find('.rwd-toggle-thumb').on('click.rwd-container', function() {
                    $object.dataL.$cont.toggleClass('rwd-thumb-open');
                });
            }
        }

        $('.rwd-container .rwd-thumb-item').css({
            background: '#' + this.dataL.modulSettings.thumbsOverlayColor
        });
        $('.rwd-container .rwd-thumb-item img').css({
            opacity: 1 - +this.dataL.modulSettings.thumbsOverlayOpacity / 100
        });

        $('.rwd-thumb-cont').css({
            bottom: -$object.dataL.modulSettings.thumbsHeight + 'px'
        });

        if (this.dataL.modulSettings.showByDefault) {
            var $cont_ = $('.cont-inner'),
                $thumb_ = $('.rwd-thumb-cont'),
                $toolbar_ = $('.rwd-toolbar');
            setTimeout(function(){
                switch($object.dataL.settings.lightboxView){
                    case 'view1':
                        switch($object.dataL.modulSettings.thumbPosition){
                            case '0':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 92) + 'px)',
                                    top: '47px'
                                });
                                $thumb_.css({
                                    bottom: '0',
                                    backgroundColor: 'rgba(0,0,0,.9)'
                                });
                                $('.rwd-bar > *').css({
                                    bottom: $object.dataL.modulSettings.thumbsHeight + 'px'
                                });
                                break;
                            case '1':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 92) + 'px)',
                                    top: $object.dataL.modulSettings.thumbsHeight + 47 + 'px'
                                });
                                $thumb_.css({
                                    top: '47px',
                                    backgroundColor: 'rgba(0,0,0,.9)'
                                });
                                break;
                        }
                        break;
                    case 'view2':
                        switch($object.dataL.modulSettings.thumbPosition) {
                            case '0':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 92) + 'px)',
                                    top: '45px'
                                });
                                $thumb_.css({
                                    bottom: '45px',
                                    backgroundColor: 'rgba(0,0,0,.9)'
                                });
                                break;
                            case '1':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 92) + 'px)',
                                    top: $object.dataL.modulSettings.thumbsHeight + 45 + 'px'
                                });
                                $thumb_.css({
                                    top: '0',
                                    backgroundColor: 'rgba(0,0,0,.9)'
                                });
                                $toolbar_.css({
                                    top: $object.dataL.modulSettings.thumbsHeight + 'px'
                                });
                                break;
                        }
                        break;
                    case 'view3':
                        switch($object.dataL.modulSettings.thumbPosition) {
                            case '0':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 92) + 'px)',
                                    top: '47px'
                                });
                                $thumb_.css({
                                    bottom: '0',
                                    backgroundColor: 'white'
                                });
                                $('.rwd-title').css({
                                    bottom: $object.dataL.modulSettings.thumbsHeight + 'px'
                                });
                                break;
                            case '1':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 93) + 'px)',
                                    top: ($object.dataL.modulSettings.thumbsHeight + 48) + 'px'
                                });
                                $thumb_.css({
                                    top: '48px',
                                    backgroundColor: 'white'
                                });
                                break;
                        }
                        break;
                    case 'view4':
                        switch($object.dataL.modulSettings.thumbPosition) {
                            case '0':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 92) + 'px)'
                                });
                                $thumb_.css({
                                    bottom: '0',
                                    backgroundColor: 'none'
                                });
                                $('.rwd-socialIcons').css({
                                    bottom: ($object.dataL.modulSettings.thumbsHeight - 10) + 'px'
                                });
                                $('.barCont').css({
                                    bottom: $object.dataL.modulSettings.thumbsHeight + 'px'
                                });
                                $('#rwd-counter').css({
                                    bottom: ($object.dataL.modulSettings.thumbsHeight + 5) + 'px'
                                });
                                $('.rwd-item').css({
                                    top: '47px'
                                });
                                break;
                            case '1':
                                $cont_.css({
                                    height: 'calc(100% - ' + ($object.dataL.modulSettings.thumbsHeight + 90) + 'px)',
                                    top: $object.dataL.modulSettings.thumbsHeight + 45 + 'px'
                                });
                                $thumb_.css({
                                    top: '45px',
                                    backgroundColor: 'none'
                                });
                                break;
                        }
                        break;
                    case 'view5':
                        switch($object.dataL.modulSettings.thumbPosition) {
                            case '0':
                                $cont_.css({
                                    height: 'calc(100% - ' + $object.dataL.modulSettings.thumbsHeight + 'px)'
                                });
                                $thumb_.css({
                                    bottom: '0'
                                });
                                break;
                            case '1':
                                $cont_.css({
                                    height: 'calc(100% - ' + $object.dataL.modulSettings.thumbsHeight + 'px)',
                                    top: $object.dataL.modulSettings.thumbsHeight + 'px'
                                });
                                $thumb_.css({
                                    top: '0'
                                });
                                break;
                        }
                        break;
                }
            }, 100);
        }
    };

    Modul.prototype.buildThumbs = function() {
        var $object = this;
        var thumbList = '';
        var vimeoErrorThumbSize = '';
        var $thumb;
        var html = '<div class="rwd-thumb-cont">' +
            '<div class="rwd-thumb group">' +
            '</div>' +
            '</div>';

        vimeoErrorThumbSize = '100x75';

        $object.dataL.$cont.addClass('rwd-has-thumb');

        $object.dataL.$cont.find('.rwd-container').append(html);

        $object.$thumbCont = $object.dataL.$cont.find('.rwd-thumb-cont');
        $object.thumbContWidth = $object.$thumbCont.width();

        $object.dataL.$cont.find('.rwd-thumb').css({
            width: $object.thumbTotalWidth + 'px',
            position: 'relative'
        });

        $object.$thumbCont.css('height', $object.dataL.modulSettings.thumbsHeight + 'px');

        function getThumb(src, thumb, index) {
            var isVideo = $object.dataL.isVideo(src, index) || {};
            var thumbImg;
            var vimeoId = '';

            if (isVideo.youtube || isVideo.vimeo || isVideo.dailymotion) {
                if (isVideo.youtube) {
                    thumbImg = '//img.youtube.com/vi/' + isVideo.youtube[1] + '/1.jpg';
                } else if (isVideo.vimeo) {
                    thumbImg = '//i.vimeocdn.com/video/error_' + vimeoErrorThumbSize + '.jpg';
                    vimeoId = isVideo.vimeo[1];
                }
            } else {
                thumbImg = thumb;
            }

            thumbList += '<div data-vimeo-id="' + vimeoId + '" class="rwd-thumb-item" style="width:' + $object.dataL.modulSettings.thumbsWidth + 'px; margin-right: ' + $object.dataL.modulSettings.thumbMargin + 'px"><img src="' + thumbImg + '" /></div>';
            vimeoId = '';
        }

        $object.dataL.$items.each(function(i) {

            getThumb($(this).attr('href') || $(this).attr('data-src'), $(this).find('img').attr('src'), i);

        });

        $object.dataL.$cont.find('.rwd-thumb').html(thumbList);

        $thumb = $object.dataL.$cont.find('.rwd-thumb-item');

        $thumb.each(function() {
            var $this = $(this);
            var vimeoVideoId = $this.attr('data-vimeo-id');

            if (vimeoVideoId) {
                $.getJSON('//www.vimeo.com/api/v2/video/' + vimeoVideoId + '.json?callback=?', {
                    format: 'json'
                }, function(data) {
                    $this.find('img').attr('src', data[0]['thumbnail_small']);
                });
            }
        });

        $thumb.eq($object.dataL.index).addClass('active');
        $object.dataL.$element.on('onBeforeSlide.rwd-container', function() {
            $thumb.removeClass('active');
            $thumb.eq($object.dataL.index).addClass('active');
        });

        $thumb.on('click.rwd-container touchend.rwd-container', function() {
            var _$this = $(this);
            setTimeout(function() {
                if ($object.activatedThumbs || !$object.dataL.effectsSupport()) {
                    $object.dataL.index = _$this.index();
                    $object.dataL.slide($object.dataL.index, false, true);
                    $('.rwd-thumb').removeClass('thumb_move');
                }
            }, 50);
        });

        $object.dataL.$element.on('onBeforeSlide.rwd-container', function() {
            $object.animateThumb($object.dataL.index);
        });

        $(window).on('resize.rwd-container.thumb orientationchange.rwd-container.thumb', function() {
            setTimeout(function() {
                $object.animateThumb($object.dataL.index);
                $object.thumbContWidth = $object.$thumbCont.width();
            }, 200);
        });

    };

    Modul.prototype.animateThumb = function(index) {
        var $thumb = this.dataL.$cont.find('.rwd-thumb'),
            position = (this.thumbContWidth / 2) - (this.dataL.modulSettings.thumbsWidth / 2);

        this.left = ((this.dataL.modulSettings.thumbsWidth + this.dataL.modulSettings.thumbMargin) * index - 1) - position;
        if (this.left > (this.thumbTotalWidth - this.thumbContWidth)) {
            this.left = this.thumbTotalWidth - this.thumbContWidth;
        }

        if (this.left < 0) {
            this.left = 0;
        }

        if (this.dataL.rwdalleryOn) {
            if (!$thumb.hasClass('on')) {
                this.dataL.$cont.find('.rwd-thumb').css('transition-duration', this.dataL.modulSettings.speed + 'ms');
            }

            if (!this.dataL.effectsSupport()) {
                $thumb.animate({
                    left: -this.left + 'px'
                }, this.dataL.modulSettings.speed);
            }
        } else {
            if (!this.dataL.effectsSupport()) {
                $thumb.css('left', -this.left + 'px');
            }
        }
        if(!$('.rwd-thumb').hasClass('thumb_move')){
            this.dataL.$cont.find('.rwd-thumb').css({
                transform: 'translate3d(-' + (this.left) + 'px, 0px, 0px)'
            });
        }
    };

    Modul.prototype.enableThumbDrag = function() {

        var $object = this,
            startCoords = 0,
            endCoords = 0,
            isDraging = false,
            isMoved = false,
            tempLeft = 0,
            $left_ = ((this.dataL.modulSettings.thumbsWidth + this.dataL.modulSettings.thumbMargin) * $object.dataL.index - 1) - (this.thumbContWidth / 2) - (this.dataL.modulSettings.thumbsWidth / 2);

        $('.rwd-thumb').attr('data-left', $left_);

        $object.dataL.$cont.find('.rwd-thumb').on('mousedown.rwd-container.thumb', function(e) {
            if ($object.thumbTotalWidth > $object.thumbContWidth) {
                e.preventDefault();
                startCoords = e.pageX;
                isDraging = true;

                $object.dataL.$cont.scrollLeft += 1;
                $object.dataL.$cont.scrollLeft -= 1;

                $object.activatedThumbs = false;
            }
        });

        $(window).on('mousemove.rwd-container.thumb', function(e) {
            if (isDraging) {
                tempLeft = +$('.rwd-thumb').attr('data-left');                isMoved = true;
                endCoords = e.pageX;

                if(Math.abs(endCoords - startCoords) > 0 && $('.rwd-cont').hasClass('rwd-show-autoplay')){
                    $('.rwd-thumb').addClass('thumb_move');
                }

                tempLeft = tempLeft - (endCoords - startCoords);

                if (tempLeft > ($object.thumbTotalWidth - $object.thumbContWidth)) {
                    tempLeft = $object.thumbTotalWidth - $object.thumbContWidth;
                }

                if (tempLeft < 0) {
                    tempLeft = 0;
                }

                $object.dataL.$cont.find('.rwd-thumb').css({
                    transform: 'translate3d(-' + (tempLeft) + 'px, 0px, 0px)'
                });
            }
        });

        $(window).on('mouseup.rwd-container.thumb', function() {
            if (isMoved) {
                isMoved = false;

                $('.rwd-thumb').attr('data-left', tempLeft);

            } else {
                $object.activatedThumbs = true;
            }

            if (isDraging) {
                isDraging = false;
            }
        });

    };

    Modul.prototype.destroy = function () {
        var $object = this;

        $object.dataL.$element.off('.rwd-container.zoom');
        $(window).off('.rwd-container.zoom');
        $object.dataL.$item.off('.rwd-container.zoom');
        $object.dataL.$element.off('.rwd-container.zoom');
        $object.dataL.$cont.removeClass('rwd-zoomed');
        clearTimeout($object.zoomabletimeout);
        $object.zoomabletimeout = false;

        if (this.dataL.modulSettings.thumbnail === 'true' && this.dataL.$items.length > 1) {
            $(window).off('resize.rwd-container.thumb orientationchange.rwd-container.thumb keydown.rwd-container.thumb');
            this.$thumbCont.remove();
            this.dataL.$cont.removeClass('rwd-has-thumb');
            $('.cont-inner').css({
                height: '100%'
            });
        }
    };

    $.fn.lightbox.lightboxModul.modul = Modul;

    var WaterMark = function (element) {
        this.element = element;
        this.settings = $.extend({}, this.constructor.defaults);
        this.init();
    };

    WaterMark.defaults = {
        imgSrc: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_img_src_new,
        text: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_text,
        textColor: '#' + hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_textColor,
        textFontSize: +hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_textFontSize,
        containerBackground: hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_container_bg_color,
        containerWidth: +hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_containerWidth,
        position: 'pos' + hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_position_new,
        opacity: +hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_opacity / 100,
        margin: +hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_margin,
        done: function (imgURL) {
            this.dataset.src = imgURL;
        }
    };

    WaterMark.prototype.init = function () {
        var $object = this,
            $elem = $object.element,
            $settings = $object.settings,
            wmData = {},
            imageData = {};

        var WatermarkImage = jQuery('<img />');
        WatermarkImage.attr('src', $object.settings.imgSrc);
        WatermarkImage.css('display', 'none').attr('id', 'huge_it_watermark_img_sample');
        if (!jQuery('body').find('#huge_it_watermark_img_sample').length) {
            jQuery('body').append(WatermarkImage);
        }

        wmData = {
            imgurl: $settings.imgSrc,
            type: 'jpeg'
        };

        imageData = {
            imgurl: $elem.dataset.imgsrc
        };

        var defer = $.Deferred();

        $.when(defer).done(function (imgObj) {
            imageData.$wmObject = imgObj;

            $object.imgurltodata(imageData, function (dataURL) {
                $settings.done.call($elem, dataURL);
            });
        });

        if ($settings.text !== '') {
            wmData.imgurl = $object.textwatermark();
        }

        $object.imgurltodata(wmData, function (imgObj) {
            defer.resolve(imgObj);
        });
    };

    WaterMark.prototype.textwatermark = function () {
        var $object = this,
            $settings,
            canvas,
            context,
            $width,
            $height;

        $settings = $object.settings;
        canvas = document.createElement('canvas');
        context = canvas.getContext('2d');

        $width = $settings.containerWidth;
        $height = $settings.textFontSize;

        canvas.width = $width;
        canvas.height = $height;

        context.fillStyle = $settings.containerBackground;
        context.fillRect(0, 0, $width, $height);

        context.fillStyle = $settings.textColor;
        context.textAlign = 'center';
        context.font = '500 ' + $settings.textFontSize + 'px Sans-serif';

        context.fillText($settings.text, ($width / 2), ($height - 5));

        return canvas.toDataURL();
    };

    WaterMark.prototype.imgurltodata = function (data, callback) {
        var $object = this,
            $settings = $object.settings,
            img;

        img = new Image();
        img.setAttribute('crossOrigin', 'anonymous');
        img.onload = function () {

            var canvas = document.createElement('canvas'),
                context = canvas.getContext('2d'),

                $imgWidth = this.width,
                $imgHeight = this.height;

            if (data.$wmObject) {

                if (data.width !== 'auto' && data.height === 'auto' && data.width < $imgWidth) {
                    $imgHeight = $imgHeight / $imgWidth * data.width;
                    $imgWidth = data.width;
                } else if (data.width === 'auto' && data.height !== 'auto' && data.height < $imgHeight) {
                    $imgWidth = $imgWidth / $imgHeight * data.height;
                    $imgHeight = data.height;
                } else if (data.width !== 'auto' && data.height !== 'auto' && data.width < $imgWidth && data.height < $imgHeight) {
                    $imgWidth = data.width;
                    $imgHeight = data.height;
                }

            }


            canvas.width = $imgWidth;
            canvas.height = $imgHeight;

            /*if (data.type === 'jpeg') {
             context.fillStyle = '#ffffff';
             context.fillRect(0, 0, $imgWidth, $imgHeight);
             }*/

            context.drawImage(this, 0, 0, $imgWidth, $imgHeight);

            if (data.$wmObject) {

                var $opacity = +hugeit_gen_resp_lightbox_obj.hugeit_lightbox_watermark_containerOpacity / 100;
                if ($opacity >= 0 && $opacity <= 1) {
                    //context.globalAlpha = $settings.opacity;
                    context.globalAlpha = $opacity;
                }

                var $wmWidth,
                    $wmHeight,
                    pos = $settings.margin,
                    $x, $y;
                if ($settings.text !== '') {
                    $wmWidth = data.$wmObject.width;
                    $wmHeight = data.$wmObject.height;
                }
                else {
                    $wmWidth = $settings.containerWidth;
                    $wmHeight = (jQuery('img#huge_it_watermark_img_sample').prop('naturalHeight') * $wmWidth) / jQuery('img#huge_it_watermark_img_sample').prop('naturalWidth');
                }

                switch ($settings.position) {
                    case 'pos1':
                        $x = pos;
                        $y = pos;
                        break;
                    case 'pos2':
                        $x = $imgWidth / 2 - $wmWidth / 2;
                        $y = pos;
                        break;
                    case 'pos3':
                        $x = $imgWidth - $wmWidth - pos;
                        $y = pos;
                        break;
                    case 'pos4':
                        $x = pos;
                        $y = $imgHeight / 2 - $wmHeight / 2;
                        break;
                    case 'pos5':
                        $x = $imgWidth / 2 - $wmWidth / 2;
                        $y = $imgHeight / 2 - $wmHeight / 2;
                        break;
                    case 'pos6':
                        $x = $imgWidth - $wmWidth - pos;
                        $y = $imgHeight / 2 - $wmHeight / 2;
                        break;
                    case 'pos7':
                        $x = pos;
                        $y = $imgHeight - $wmHeight - pos;
                        break;
                    case 'pos8':
                        $x = $imgWidth / 2 - $wmWidth / 2;
                        $y = $imgHeight - $wmHeight - pos;
                        break;
                    case 'pos9':
                        $x = $imgWidth - $wmWidth - pos;
                        $y = $imgHeight - $wmHeight - pos;
                        break;
                    default:
                        $x = $imgWidth - $wmWidth - pos;
                        $y = $imgHeight - $wmHeight - pos;
                }
                context.drawImage(data.$wmObject, $x, $y, $wmWidth, $wmHeight);
            }

            var dataURL = canvas.toDataURL('image/' + data.type);

            if (typeof callback === 'function') {

                if (data.$wmObject) {
                    callback(dataURL);

                } else {
                    var $wmNew = new Image();
                    $wmNew.src = dataURL;
                    callback($wmNew);
                }
            }

            canvas = null;
        };

        img.src = data.imgurl;
    };

    $.fn['watermark'] = function () {
        return this.each(function () {
            if (!$.data(this, 'watermark')) {
                $.data(this, 'watermark', new WaterMark(this));
            }
        });
    };

})(jQuery);