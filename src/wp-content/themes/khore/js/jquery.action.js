(function(){

    var CTA = function (obj) {
        this.obj = obj;
        this.videoBtn = obj.find( '.play' );
        this.video = obj.find( '.action__video' );
        this.videoClose = this.video.find( '>i' );

        this.init();
    };
    CTA.prototype = {
        init: function () {
            var self = this;

            self.core = self.core();
            self.core.build();
        },
        core: function () {
            var self = this;

            return {
                addEvents: function () {
                    self.videoBtn.on({
                        click: function () {
                            self.video.addClass('active');

                            return false;
                        }
                    });
                    self.videoClose.on({
                        click: function () {
                            self.video.removeClass('active');

                            return false;
                        }
                    });
                },
                build: function () {
                    self.core.addEvents();
                }
            };
        }
    };

    if(document.getElementsByClassName('page_action4')[0].className.indexOf('page_active') >= 0){
        $( function(){
            $( '.action').each( function(){
                new CTA( $( this ) );
            } );
        } );
    } else {
        $( '.action').each( function(){
            new CTA( $( this ) );
        } );
    }


}());