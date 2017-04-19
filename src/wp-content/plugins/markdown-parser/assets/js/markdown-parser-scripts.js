jQuery(document).ready(function ($) {
    console.log('Init here');

    $('#markdown-parser-from-url').on('change', function () {
        var checked = ( $(this).prop( 'checked' ) ) ? 'on' : '';
        $.ajax({
            method: 'POST',
            url: mpObj.ajax_url,
            data: {
                action: 'change_meta_parse_markdown_from_url',
                post_id: mpObj.post_id,
                prop: checked
            },
            success: function (result) {
                console.log(result);
                if( checked ){
                    $('#markdown-parser-from-url-box').slideDown();
                } else{
                    $('#markdown-parser-from-url-box').slideUp();
                }
            }
        });
    })

});