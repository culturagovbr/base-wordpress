<?php
// load campaign base files
foreach (glob(WPMU_PLUGIN_DIR . '/campaign_base/*.php') as $file) {
    require_once($file);
}

$campaign = null;

// load code used only for campaign sites (exclude main site)
if (!is_main_site()) {
    // must wait for wordpress to finish loading before loading campaign code
    add_action('init', function() {
        global $blog_id, $campaign;
        
        require_once(__DIR__ . '/includes/payment.php');
        require_once(__DIR__ . '/includes/EasyAjax.php');
        
        $campaign = Campaign::getByBlogId($blog_id);
        GraphicMaterial::setUp();
        
        if (is_admin()) {
            require_once(__DIR__ . '/includes/load_menu_options.php');
        }
        
        add_action('template_redirect', 'campanha_check_payment_status');
        add_filter('query_vars', 'campaign_base_custom_query_vars');
        add_filter('rewrite_rules_array', 'campaign_base_custom_url_rewrites', 10, 1);
        add_action('template_redirect', 'campaign_base_template_redirect_intercept');
        add_filter('login_message', 'campanha_login_payment_message');
        add_action('admin_notices', 'campanha_admin_payment_message');
        add_filter('site_option_upload_space_check_disabled', 'campanha_unlimited_upload');
    });
}

/**
 * Check the payment status and mark the blog
 * as private in case payment is pending.
 */
function campanha_check_payment_status() {
    global $campaign;

    $user_id = get_current_user_id();

    if (!$campaign->isPaid() && $campaign->campaignOwner->ID !== $user_id
        && !is_super_admin())
    {
        wp_redirect(wp_login_url());
    }
}

function campaign_base_custom_query_vars($public_query_vars) {
    $public_query_vars[] = "tpl";

    return $public_query_vars;
}

// REDIRECIONAMENTOS
function campaign_base_custom_url_rewrites($rules) {
    $new_rules = array(
        "materialgrafico/?$" => "index.php?tpl=materialgrafico",
        "mobilizacao/?$" => "index.php?tpl=mobilizacao"
    );

    return $new_rules + $rules;
}

function campaign_base_template_redirect_intercept() {
    global $wp_query;
    
    switch ($wp_query->get('tpl')) {
        case 'materialgrafico':
            require(WPMU_PLUGIN_DIR . '/includes/graphic_material_list_links.php');
            die;
        case 'mobilizacao':
            require(WPMU_PLUGIN_DIR . '/includes/tpl-mobilize.php');
            die;
        default:
            break;
    }
}

/**
 * Display a message in the login page about the
 * payment.
 * 
 * @param string $message
 * @return string
 */
function campanha_login_payment_message($message) {
    global $campaign;
    
    if (!$campaign->isPaid()) {
        $message .= '<p class="message">Está campanha está visível somente para o criador pois o pagamento está pendente.</p>';
    }
    
    return $message;
}


/**
 * Display a message in the admin panel about
 * the payment.
 */
function campanha_admin_payment_message() {
    global $campaign;
    
    if (!$campaign->isPaid()) {
        $link = admin_url('admin.php?page=payments');
        echo "<div class='error'><p>Está campanha está visível somente para o criador pois o pagamento está pendente. <a href='$link'>Pague agora!</a></p></div>";
    }
}

/**
 * Hack to remove upload limit for premium campaings.
 * 
 * By default in Wordpress or you disable upload limit 
 * or you set a limit for all sites. This functions is a
 * workaround to allow some sites with upload limit and 
 * others without.
 * 
 * @param bool $value the value for the site option 'upload_space_check_disabled'
 * @return bool
 */
function campanha_unlimited_upload($value) {
    if (get_option('blog_upload_space') == -1) {
        $value = true;
    }

    return $value;
}

function print_msgs($msg, $extra_class='', $id='') {
    if (!is_array($msg)) {
        return false;
    }

    foreach ($msg as $type => $msgs) {
        if (!$msgs) {
            continue;
        }
        
        echo "<div class='$type $extra_class' id='$id'><ul>";
        
        if (!is_array($msgs)) {
            echo "<li>$msgs</li>";
        } else {
            foreach ($msgs as $m) {
                echo "<li>$m</li>";
            }
        }
        
        echo "</ul></div>";
    }
}

add_action('wp_print_scripts', 'campanha_add_common_js');
/**
 * Add JS files shared by all themes.
 */
function campanha_add_common_js() {
    if (is_user_logged_in()) {
        wp_enqueue_script('uservoice', site_url() . '/wp-content/mu-plugins/js/uservoice.js', 'jquery');
    }
}