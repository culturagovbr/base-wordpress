<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Shortcode_Visualizar_Email
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-oficinas-visualizar-email', array($this, 'snc_email_subscription'));
        }
    }

    public function snc_email_subscription($type_email = 'snc_email_confirm_subscription')
    {
        if (!current_user_can('administrator')) {
            return false;
        }

        $subscription = current($this->get_subscription_in_workshop());
        $post_id = $subscription->ID;
        //snc_email_confirm_subscription
        // snc_email_effectiveness_subscription
        $oficinasEmail = new SNC_Oficinas_Email($subscription->ID, 'snc_email_confirm_subscription');
        return $oficinasEmail->get_email_template();

//        $response = $oficinasEmail->snc_send_mail_user();
//        if($response) {
//            return "email enviado";
//        }

//        return 'falhouu';
    }

    private function get_subscription_in_workshop()
    {
        if (is_user_logged_in()) {
            $subscription = get_posts([
                'author' => get_current_user_id(),
                'post_type' => SNC_POST_TYPE_INSCRICOES,
                'post_status' => array('confirmed', 'pending', 'canceled', 'waiting_list'),
                'posts_per_page' => 1
            ]);

            return $subscription;
        }

        return false;
    }
}
