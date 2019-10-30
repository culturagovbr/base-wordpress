jQuery(function() {
    jQuery( "#sortable1_1, #sortable1_2" ).sortable({
        connectWith: ".sortable1",
        update: function(event, ui) {
            jQuery('#speakers_full_order_1').val(jQuery("#sortable1_1").sortable('toArray', {
                attribute: "data-id"
            }).toString());
        }
    }).disableSelection();
    
    jQuery( "#sortable2_1, #sortable2_2" ).sortable({
        connectWith: ".sortable2",
        update: function(event, ui) {
            jQuery('#speakers_full_order_2').val(jQuery("#sortable2_1").sortable('toArray', {
                attribute: "data-id"
            }).toString());
        }
    }).disableSelection();
    
    jQuery( "#sortable3_1, #sortable3_2" ).sortable({
        connectWith: ".sortable3",
        update: function(event, ui) {
            jQuery('#speakers_full_order_3').val(jQuery("#sortable3_1").sortable('toArray', {
                attribute: "data-id"
            }).toString());
        }
    }).disableSelection();
    
    jQuery('.upload_image_delete').click(function(){
        jQuery(this).parent().find('.upload_image').attr('src', '');
        jQuery(this).parent().find('.upload_image_id').val('');
    });
});