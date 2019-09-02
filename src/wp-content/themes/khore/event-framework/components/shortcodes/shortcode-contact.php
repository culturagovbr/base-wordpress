<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

function efcb_contact($atts, $content) {
    $ef_options = EF_Event_Options::get_theme_options();
    $title = isset($atts['title']) ? $atts['title'] : '';
    $subtitle = isset($atts['subtitle']) ? $atts['subtitle'] : '';
    $sendtext = isset($atts['send_text']) ? $atts['send_text'] : '';

    $style_items = array(
        'section' => array(
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
            'background-color' => 'background_color',
        ),
        'title' => array(
            'color' => 'title_font_color',
            'font-size' => 'title_font_size',
        ),
        'subtitle' => array(
            'color' => 'subtitle_font_color',
            'font-size' => 'subtitle_font_size',
        ),
        'field' => array(
            'color' => 'field_font_color',
            'font-size' => 'field_font_size',
        ),
        'send_button' => array(
            'color' => 'send_button_font_color',
            'font-size' => 'send_button_font_size',
        )
    );

    echo apply_filters('efcb_shortcode_render', '', 'efcb_contact', array(
        'title' => $title,
        'subtitle' => $subtitle,
        'sendtext' => $sendtext,
        'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
        'ef_options' => $ef_options));
}

// Ajax code for send contect email
add_action('wp_ajax_nopriv_send_contact_email', 'efcb_ajax_send_contact_email');
add_action('wp_ajax_send_contact_email', 'efcb_ajax_send_contact_email');

/**
 * Ajax Code Contect Email
 *
 * Handle to send contact
 * email functionality
 *
 * @package Event Framework
 * @since 1.0.0
 */
function efcb_ajax_send_contact_email() {
    $ret = array('sent' => false, 'error' => false, 'errorField' => '', 'message' => '');
    $ef_options = EF_Event_Options::get_theme_options();
    $recaptchapublickey = isset($ef_options['efcb_contacts_recaptcha_public_key']) ? $ef_options['efcb_contacts_recaptcha_public_key'] : '';
    $recaptchaprivatekey = isset($ef_options['efcb_contacts_recaptcha_private_key']) ? $ef_options['efcb_contacts_recaptcha_private_key'] : '';
    $contactemail = isset($ef_options['efcb_contacts_email']) ? $ef_options['efcb_contacts_email'] : '';

    if (!empty($recaptchapublickey) && !empty($recaptchaprivatekey)) {

        $resp = recaptcha_check_answer($recaptchaprivatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

        // check reCaptcha
        if (!$resp || !$resp->is_valid) {
            $ret['message'] = __('The reCAPTCHA wasn\'t entered correctly. Go back and try it again.!', 'dxef');
            $ret['error'] = true;
            $ret['errorField'] = 'recaptcha_response_field';
        }
    }

    // require a name from user
    if (trim($_POST['contactName']) === '') {
        $ret['message'] = __('Forgot your name!', 'dxef');
        $ret['error'] = true;
        $ret['errorField'] = 'contactName';
    } else {

        $name = trim($_POST['contactName']);
    }

    // need valid email
    if (trim($_POST['email']) === '') {
        $ret['message'] = __('Forgot to enter in your e-mail address.', 'dxef');
        $ret['error'] = true;
        $ret['errorField'] = 'email';
    } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
        $ret['message'] = __('You entered an invalid email address.', 'dxef');
        $ret['error'] = true;
        $ret['errorField'] = 'email';
    } else {
        $email = trim($_POST['email']);
    }

    // we need at least some content
    if (trim($_POST['comments']) === '') {
        $ret['message'] = __('You forgot to enter a message!', 'dxef');
        $ret['error'] = true;
        $ret['errorField'] = 'comments';
    } else {
        if (function_exists('stripslashes')) {
            $comments = stripslashes(trim($_POST['comments']));
        } else {
            $comments = trim($_POST['comments']);
        }
    }

    // upon no failure errors let's email now!
    if (!$ret['error']) {
        $subject = __('Submitted message from ', 'dxef') . $name;
        $body = __('Name:', 'dxef') . " $name \n\n" . __('Email:', 'dxef') . " $email \n\n " . __('Comments:', 'dxef') . " $comments";
        $headers = 'From: ' . $contactemail . "\r\n" . 'Reply-To: ' . $email . "\r\n";
        try {

            wp_mail($contactemail, $subject, $body, $headers);
            $ret['sent'] = true;
            $ret['message'] = __('Your email was sent.', 'dxef');
        } catch (Exception $e) {

            $ret['message'] = __('Error submitting the form', 'dxef');
        }
    }

    echo json_encode($ret);
    die;
}

// Register Shortcode
add_shortcode('efcb-section-contact', 'efcb_contact');
