(function($) {
    $(document).ready(function() {
        sncOficinas.init();
    })

    var sncOficinas = {
        init: function() {
            $('input.hasDatepicker').attr('maxlength','10');
            $('input.hasDatepicker').keydown(function(event) {
                return false;
            });
        }
    };
})(jQuery);