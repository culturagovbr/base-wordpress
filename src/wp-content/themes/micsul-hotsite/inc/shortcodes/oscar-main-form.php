<?php
function oscar_main_form( $atts ){
    $atts = shortcode_atts( array(
        'field_group' => '',
    ), $atts, 'oscar-main-form' );

    $current_user = wp_get_current_user();
    $inscricao_id = get_user_meta( $current_user->ID, '_inscricao_id', true ); 

    ob_start();
    $options = array(
        'field_groups' => array( $atts['field_group'] ),
        'id' => 'acf_inscricoes-oscar-2018',
        'post_id'       => $inscricao_id ? $inscricao_id : 'new_inscricao',
        'new_post'      => array(
            'post_type'     => 'inscricao',
            'post_status'   => 'publish'
        ),
        'uploader' => 'basic',
        'updated_message' => __('Formulário atualizado.', 'acf'),
        // 'return'        => $inscricao_id ? '' : home_url('/enviar-video'),
        'submit_value'  => $inscricao_id ? 'Atualizar dados' : 'Enviar dados',
    );

    if( !$inscricao_id ){
        $options['return'] = home_url('/enviar-video');
    }

    acf_form( $options );
    return ob_get_clean();
}
add_shortcode( 'oscar-main-form', 'oscar_main_form' );