(function ($) {
    $(document).ready(function () {
        app.init();
    });

    var app = {
        init: function () {
            console.log('app started');
            this.uploadFnc();
        },

        uploadFnc: function () {
            $('#upload-doc-button').click(function(e) {
                e.preventDefault();
                var button = $(this);
                wp.media.editor.send.attachment = function(props, attachment) {
                    var defaultIcon = $('.document-url-meta').data('default-icon');
                    $('#easy-docs-metabox .document-url-wrapper').removeClass('no-doc');
                    $('#easy-docs-metabox #document-url').val(attachment.url);
                    $('#easy-docs-metabox .doc-name b').text(attachment.title);
                    $('#easy-docs-metabox .doc-size').text(attachment.filesizeHumanReadable);
                    $('#easy-docs-metabox .document-url-meta .thumbnail img').attr('src', ( typeof(attachment.sizes) !== 'undefined' ) ? attachment.sizes.thumbnail.url : defaultIcon);
                };
                wp.media.editor.open(button);
            });

            $('#remove-document-url').click(function (e) {
                e.preventDefault();
                $('#easy-docs-metabox #document-url').val('');
                $('#easy-docs-metabox .document-url-wrapper').addClass('no-doc');
            })
        },
    };
})(jQuery);