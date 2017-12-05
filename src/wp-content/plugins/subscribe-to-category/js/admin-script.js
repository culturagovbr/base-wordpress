(function ($) {

    $(document).ready(function () {

        $('#stc-resend').change(function () {

            if ($(this).is(':checked')) {
                $('#stc-resend-info').show();
            } else {
                $('#stc-resend-info').hide();
            }

        });

        $('button#stc-force-run').live('click', function () {
            var trigger_btn = $(this);

            trigger_btn.attr('disabled', 'disabled'); // disable button during ajax call
            trigger_btn.before('<span id="stc-spinner" class="spinner" style="display:inline"></span>'); // adding spinner element
            $('#message').remove(); // remove any previous message

            var data = {
                action: 'force_run',
                nonce: ajax_object.ajax_nonce
            };

            $.post(ajax_object.ajaxurl, data, function (response) {

                setTimeout(function () {
                    $('#stc-posts-in-que').text('0'); // clear posts in que
                    $('.wrap h2').after('<div id="message"></div>'); // add message element
                    $('#message').addClass('updated').html('<p><strong>' + response + '</strong></p>'); // get text from ajax call
                    $('#stc-spinner').remove(); // remove spinner
                    trigger_btn.attr('disabled', false); // enable button
                }, 1500);

            }).error(function () {
                alert("Problem calling: " + action + "\nCode: " + this.status + "\nException: " + this.statusText);
            });

            return false;

        });

        $('.select-all-categories-to-include, .select-all-categories-to-exclude').on('click', function (e) {
            e.preventDefault();
            $(this).parent().prev('div').find('input:checkbox').trigger('click');
        });

        $('.select-all-categories-to-export').on('click', function (e) {
            e.preventDefault();
            $('.export-table').find('input:checkbox').trigger('click');
        });

        $('#link-posts-to-send').on('click', function (e) {
            e.preventDefault();
            $('.posts-to-send').slideToggle();
        });

        $('.posts-to-send .stc-remove-from-sending').on('click', function (e) {
            e.preventDefault();
            var listItem = $(this).closest('li');

            console.log($(this).attr('data-post-id'), listItem);
            var data = {
                action: 'remove_post_from_sending',
                post_id: $(this).attr('data-post-id'),
                nonce: ajax_object.ajax_nonce
            };

            $.post(ajax_object.ajaxurl, data, function (response) {
                if (response.length) {
                    console.log(response);
                    $('#stc-posts-in-que').text(parseInt($('#stc-posts-in-que').text()) - 1);
                    $(listItem).hide('slow');
                }
            }).error(function () {
                alert("Problem calling: " + action + "\nCode: " + this.status + "\nException: " + this.statusText);
            });
        });

    });

})(jQuery);

