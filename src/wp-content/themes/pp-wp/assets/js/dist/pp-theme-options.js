(function($) {
    $(document).ready(function() {
        app.init();
    });

    var app = {
        init: function() {
            app.toggleSocialMediaLinks();
            app.toggleMediaIco();
            app.addSocialLinkRow();
            app.deleteSocialLinkRow();
        },

        toggleSocialMediaLinks: function() {
            if( !$('#show_social_links').prop('checked') ){
                $('#show_social_links').closest('tr').next().hide();
            }

            $(document).on('change', '#show_social_links', function(){
                if ( $(this).prop('checked') ) {
                    console.log('open');
                    $(this).closest('tr').next().show();
                } else {
                    console.log('close');
                    $(this).closest('tr').next().hide();
                }
            });
        },

        toggleMediaIco: function() {
            $(document).on('change', '.fa-ico-toggler', function(){
                var selected = $(this).find('option:selected');
                $(this).closest('tr').find('.fa-ico-toggle').removeClass().addClass( 'fa-ico-toggle fa ' + selected.data('icon') );
                $(this).closest('tr').find('.fa-ico-selected').val( selected.data('icon') );
            });
        },

        addSocialLinkRow: function() {
            $('#pp-theme-options-social-media-links-add-row').on('click', function (e) {
                e.preventDefault();
                var rows  = $('#pp-theme-options-social-media-links tbody tr').length,
                    cloneIcoToggler  = $('.fa-ico-toggler:eq(0)').clone(),
                    cloneIconSelected  = '<input class="fa-ico-selected" type="hidden" name="pp_theme_options_option_name[social_links]['+ rows +'][icon]" value="fa-facebook">',
                    cloneIcon  = $('.fa-ico-toggle:eq(0)').clone(),
                    newRow  = '<tr>';
                    newRow += '<td>';
                    newRow += '<div class="actions">';
                    newRow += '<a href="#" title="Remover"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>';
                    newRow += '<span>' + (rows + 1) + '</span>';
                    newRow += '</div>';
                    newRow += '</td>';
                    newRow += '<td><input class="regular-text" type="text" name="pp_theme_options_option_name[social_links]['+ rows +'][url]" id="social_link_url_'+ rows +'" value=""></td>';
                    newRow += '<td><input class="regular-text" type="text" name="pp_theme_options_option_name[social_links]['+ rows +'][title]" id="social_link_title_'+ rows +'" value=""></td>';
                    newRow += '<td></td>';
                    newRow += '<td></td>';
                    newRow += '</tr>';

                $('#pp-theme-options-social-media-links tbody').append(newRow);
                $('#pp-theme-options-social-media-links tbody tr:last td:eq(3)').prepend(cloneIcoToggler);
                $('#pp-theme-options-social-media-links tbody tr:last td:eq(3)').append(cloneIconSelected);
                $('#pp-theme-options-social-media-links tbody tr:last td:eq(4)').prepend(cloneIcon);
                $('#pp-theme-options-social-media-links tbody tr:last .fa-ico-toggler').attr('name', 'pp_theme_options_option_name[social_links]['+ rows +'][icon]');
            })
        },

        deleteSocialLinkRow: function() {
            $(document).on('click', '.actions a', function(e){
                e.preventDefault();
                $(this).closest('tr').remove();
            });
        }
    };
})(jQuery);