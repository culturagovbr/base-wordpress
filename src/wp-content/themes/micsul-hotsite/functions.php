<?php
if ( ! function_exists( 'micsul_setup' ) ) :
    function micsul_setup() {
        load_theme_textdomain( 'micsul', get_template_directory() . '/languages' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        // add_image_size('portfolio-thumb', 332, 200, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'primary-menu' => esc_html__( 'Menu Principal', 'micsul' ),
        ) );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

    }
endif;
add_action( 'after_setup_theme', 'micsul_setup' );


/**
 * Register Custom Navigation Walker
 */
require_once get_template_directory() . '/inc/wp-bootstrap-navwalker.php';

/**
 * Enqueue scripts and styles.
 */
function micsul_scripts() {
    wp_enqueue_style( 'google-fonts--open-sans', 'https://fonts.googleapis.com/css?family=Lato:300,400,900' );
    wp_enqueue_style( 'micsul-styles', get_template_directory_uri() . '/assets/css/dist/main.min.css' );

    wp_enqueue_script( 'micsul-scripts', get_template_directory_uri() . '/assets/js/dist/main.min.js', array('jquery') );

}
add_action( 'wp_enqueue_scripts', 'micsul_scripts' );

/**
* Disable WordPress Admin Bar for all users but admins.
* 
*/
if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
}

/**
 * Adds a config options page to the theme
 * 
 */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(
        array(
            'page_title' => 'Configurações do tema',
            'menu_title' => 'Configurações do tema',
            'menu_slug' => 'micsul-general-settings',
            'capability' => 'edit_posts',
            'redirect' => true
        )
    );

    acf_add_options_sub_page(array(
        'page_title'    => 'Configurações do tema',
        'menu_title'    => 'Configurações do tema',
        'parent_slug'   => 'micsul-general-settings',
    ));
};

/**
 * Disable support for comments and trackbacks in post types
 * 
 */
function df_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if(post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'df_disable_comments_post_types_support');
// Close comments on the front-end
function df_disable_comments_status() {
    return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);
// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);
// Remove comments page in menu
function df_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');
// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url()); exit;
    }
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');
// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');
// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'df_disable_comments_admin_bar');

/**
 * Get page theme option
 */
function get_page_theme($page_id){
    $page_theme = get_field('page_theme', $page_id );
    return ($page_theme) ? 'page-theme-' . $page_theme : 'page-theme-default';
}

/**
 * Collapse field groups by default
 */
add_action('acf/input/admin_head', 'my_acf_input_admin_head');
function my_acf_input_admin_head() {
    if( !is_admin() ) 
        return false;

    $cur_page = get_current_screen();
    if( $cur_page->id !== 'toplevel_page_acf-options-configuracoes-do-tema' ) {
        return;
    } ?>
    <script type="text/javascript">
    jQuery(function(){
        jQuery('.acf-postbox').addClass('closed');
        jQuery('.acf-postbox').first().removeClass('closed');
    });
    </script>
<?php }