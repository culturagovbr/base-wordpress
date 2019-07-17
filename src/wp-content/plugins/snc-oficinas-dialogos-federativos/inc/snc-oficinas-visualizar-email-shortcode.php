<?php

class SNC_Oficinas_Visualizar_Email_Shortcode
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc-oficinas-visualizar-email', array($this, 'snc_email_subscription'));
        }
    }

    public function snc_email_subscription()
    {
        echo $this->get_email_template('admin', 'Some clever message here!!!');
    }

    private function get_email_template($user_type = 'user', $message)
    {
        $user = wp_get_current_user();
        ob_start();
        if ($user_type === false) {
            require SNC_ODF_PLUGIN_PATH . '/email-templates/user-template.php';
        } else {
            require SNC_ODF_PLUGIN_PATH . '/email-templates/admin-template.php';
        }
        return ob_get_clean();
    }
}

new SNC_Oficinas_Visualizar_Email_Shortcode();