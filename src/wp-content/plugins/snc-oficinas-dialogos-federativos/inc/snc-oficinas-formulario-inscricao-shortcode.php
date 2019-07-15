<?php

class SNC_Oficinas_Formulario_Inscricao_Shortcode
{
	public function __construct()
	{
		if( !is_admin() ){
            add_shortcode('snc-subscription-form', array($this, 'snc_minc_subscription_form_shortcode')); // Inscrição
		}
	}

    /**
     * Shortcode to show ACF form
     *
     * @param $atts
     * @return string
     */
    public function snc_minc_subscription_form_shortcode($atts)
    {

        $atts = shortcode_atts(array(
            'form-group-id' => '',
            'return' => home_url('/inscricao/?sent=true#message')
        ), $atts);


        ob_start();

        $settings = array(
            'field_groups' => array($atts['form-group-id']),
            'id' => 'snc-main-form',
            'post_id' => 'inscricao-oficina',
            'new_post' => array(
                'post_type' => 'inscricao-oficina',
                'post_status' => 'publish'
            ),
            'updated_message' => 'Inscrição enviada com sucesso.',
			'return' =>  home_url('/visualizar/?updated=true'),
            'uploader' => 'basic',
            'submit_value' => 'Finalizar inscrição'
        );

        $subscription = $this->is_user_registered_in_workshop();
        if (count($subscription) > 0) {
            $settings['post_id'] = current($subscription)->ID;
        }

        acf_form($settings);

        return ob_get_clean();
    }

    private function is_user_registered_in_workshop()
    {
        if ( is_user_logged_in() ) {
            $current_user = get_current_user_id();
            $post = get_posts([
                'author'        =>  $current_user,
                'orderby'       =>  'post_date',
                'order'         =>  'ASC',
                'post_type'     => 'inscricao-oficina',
                'posts_per_page' => 1
            ]);

            return $post;
        }

        return false;
    }

}