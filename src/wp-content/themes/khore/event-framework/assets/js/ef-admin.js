(function ($) {
    var EF_Admin = {};
    EF_Admin.image_upload_cb = function (e) {
        var custom_uploader;
        var that = this;
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        // Add the uploader as a wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        // Use the upload image as the text field value
        custom_uploader.on('select', jQuery.proxy(function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery(this).parent().find(':text').val(attachment.url);
            jQuery(this).parent().find(':text').trigger('change');
        }, that));

        //Open the uploader dialog
        custom_uploader.open();
    };
    window.EF_Admin = EF_Admin;
}(jQuery));