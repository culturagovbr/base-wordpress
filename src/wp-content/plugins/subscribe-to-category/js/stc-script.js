(function ($) {
    $(document).ready(function () {
        jQuery('#stc-state').chosen({
            width: '85%',
            no_results_text: 'Estado não encontrado:'
        });
        jQuery('#stc-city').chosen({
            width: '85%',
            no_results_text: 'Município não encontrado:'
        });

        function changeCityBasedOnState(state){
            data = {
                'action': 'city_based_on_state',
                'estado' : state
            };
            $.ajax({
                url : ajax_object.ajaxurl,
                data : data,
                type : 'POST',
                beforeSend: function () {
                    $('#stc-city').html('<option value="">Carregando</option>');
                    $('#stc-city').trigger('chosen:updated');
                },
                success : function( data ){
                    $('#stc-city').html('');
                    $.each(JSON.parse(data), function(index, value) {
                        $('#stc-city').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                    $('#stc-city').trigger('chosen:updated');
                },
                error: function(error){
                    console.error( error );
                }
            });
        }

        $('#stc-state').on('change', function (val) {
            if( val.target.value.length ){
                changeCityBasedOnState(val.target.value);
            } else {
                $('#stc-city').html('<option value="">Selecione seu município</option>');
            }
        })

    });
})(jQuery);

