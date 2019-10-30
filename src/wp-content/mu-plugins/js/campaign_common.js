(function(jQuery){
    jQuery(document).ready(function() {
        // customize strings from my sites admin menu        
        a = jQuery('#wp-admin-bar-my-sites a').first();
        if (a.text() == 'Meus Sites') {
            a.text(campaign_common.value.MeusProjetos);
            a.removeAttr('href');
        }      
        
        li = jQuery('#wp-admin-bar-blog-1');
        a = li.find('a').first();
        if (a.text() == 'Redelivre') {
            a.text(campaign_common.value.AdministrarProjetos);
            a.css('background-image', 'url()');
            li.find('.ab-sub-wrapper').remove();
        }
    });
})(jQuery);
