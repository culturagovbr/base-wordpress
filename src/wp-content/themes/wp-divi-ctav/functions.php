<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function divi_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

// Add Shortcode
function num_inscricao() {
  $num_inscricao = get_option('ctav_inscricoes', 500);
  return $num_inscricao;
}

add_shortcode( 'num_inscricao', 'num_inscricao');

function set_nova_inscricao() {
  $num_inscricao = get_option('ctav_inscricoes') + 1;
  update_option('ctav_inscricoes', $num_inscricao);
}

// funcao para executar antes do envio de emails
function gera_nova_inscricao($cf7) {
    set_nova_inscricao();
    $num_inscricao = num_inscricao();
    
    $wpcf = WPCF7_ContactForm::get_current();
    $wpcf->posted_data['numero_inscricao'] = $num_inscricao;
    return $wpcf;
}
add_action('wpcf7_before_send_mail', 'gera_nova_inscricao');

?>
