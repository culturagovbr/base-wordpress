<?php
require_once 'event-framework/event-framework.php';

require_once 'inscricoes/db.updates.php';
require_once 'inscricoes/inscricoes.functions.php';
require_once 'includes/relatorios.php';
require_once 'includes/relatorios.functions.php';


add_filter('ef_content_builder_templates', 'khore_ef_content_builder_templates');

function khore_ef_content_builder_templates($templates) {
    $templates['list'] = array();
    return $templates;
}

add_filter('ef_content_builder_sections', 'khore_ef_content_builder_sections');

function khore_ef_content_builder_sections($sections) {
    unset($sections['speakers']);
    unset($sections['exhibitors']);
    unset($sections['fullexhibitors']);
    unset($sections['schedule']);
    unset($sections['calltoaction']);
    unset($sections['sponsors']);
    unset($sections['news']);
    array_values($sections);

    return $sections;
}

add_filter('ef_theme_options_logo', 'khore_set_theme_options_logo');

function khore_set_theme_options_logo() {
    return get_template_directory_uri() . '/img/khore_logo.png';
}

function khore_setup_social_networks() {
    global $twitter, $facebook, $instagram;

    $ef_options     = EF_Event_Options::get_theme_options();
    $facebookAppID  = $ef_options['efcb_facebook_rsvp_app_id'];
    $facebookSecret = $ef_options['efcb_facebook_rsvp_secret'];

    if (!empty($facebookAppID) && !empty($facebookSecret))
        $facebook = new Facebook(array(
            'appId'  => $facebookAppID,
            'secret' => $facebookSecret,
        ));


    $twitterAccessToken       = $ef_options['efcb_twitter_access_token'];
    $twitterAccessTokenSecret = $ef_options['efcb_twitter_access_token_secret'];
    $twitterConsumerKey       = $ef_options['efcb_twitter_consumer_key'];
    $twitterConsumerSecret    = $ef_options['efcb_twitter_consumer_secret'];

    if (!empty($twitterAccessToken) && !empty($twitterAccessTokenSecret) && !empty($twitterConsumerKey) && !empty($twitterConsumerSecret)) {
        $twitter = new TwitterAPIExchange(array(
            'oauth_access_token'        => $twitterAccessToken,
            'oauth_access_token_secret' => $twitterAccessTokenSecret,
            'consumer_key'              => $twitterConsumerKey,
            'consumer_secret'           => $twitterConsumerSecret
        ));
    }

    $instagramClientID     = $ef_options['efcb_instagram_client_id'];
    $instagramClientSecret = $ef_options['efcb_instagram_client_secret'];

    if (!empty($instagramClientID) && !empty($instagramClientSecret)) {
        $instagram = new InstagramAPI($instagramClientID);
    }
}

add_action('init', 'khore_setup_social_networks');
// ******************* Scripts and Styles ****************** //
add_action('wp_enqueue_scripts', 'khore_enqueue_scripts');

add_action('after_setup_theme', 'khore_after_theme_setup');

function khore_after_theme_setup() {

// ******************* Localizations ****************** //
    load_theme_textdomain('khore', get_template_directory() . '/languages/');

// ******************* Add Custom Menus ****************** //
    add_theme_support('menus');

// ******************* Add Post Thumbnails ****************** //
    add_theme_support('post-thumbnails');
    add_image_size('khore-speaker', 531, 424, true);
    add_image_size('khore-news', 531, 424, true);
    add_image_size('khore-session', 549, 549, true);
    add_image_size( 'new-image-size', 640, 480, true );

// ******************* Add Navigation Menu ****************** //
    register_nav_menu('primary', __('Navigation Menu', 'khore'));
}

function my_image_sizes($sizes) {
    $addsizes = array(
        "new-image-size" => __( "Tamanho médio", 'khore')
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}
add_filter('image_size_names_choose', 'my_image_sizes');


add_action('admin_enqueue_scripts', 'khore_admin_enqueue_scripts');

function khore_admin_enqueue_scripts($hook) {
    global $post_type;

    if (in_array($hook, array('post.php', 'post-new.php'))) {
        if ($post_type == 'session') {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/css/admin/jquery-ui-smoothness/jquery-ui-1.10.3.custom.min.css');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_style('khore-sortable', get_template_directory_uri() . '/css/admin/sortable.css');
        }
    } else if ($hook == 'toplevel_page_ef-options') {
        wp_enqueue_style('khore-theme-options', get_template_directory_uri() . '/css/admin/themeoptions.css');
        wp_enqueue_script('khore-theme-options', get_template_directory_uri() . '/js/admin/themeoptions.js', array('jquery'), false, true);
    }
}

function khore_enqueue_scripts() {
    $ef_options = EF_Event_Options::get_theme_options();

    wp_enqueue_style('khore-font-noto', 'http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic');
    wp_enqueue_style('khore-font-Dosis', 'http://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800');
    wp_enqueue_style('khore-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style('khore-style-reset', get_template_directory_uri() . '/css/reset.css');
    wp_enqueue_style('khore-style-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('khore-style-main', get_template_directory_uri() . '/css/main.css');

    wp_enqueue_style('swiper-css', get_template_directory_uri() . '/css/swiper.min.css');
    wp_enqueue_style('index-css', get_template_directory_uri() . '/css/index.css');
    wp_enqueue_style('schedule-css', get_template_directory_uri() . '/css/schedule.css');
    wp_enqueue_style('speakers-css', get_template_directory_uri() . '/css/news.css');
    wp_enqueue_style('tickets-css', get_template_directory_uri() . '/css/tickets.css');
    wp_enqueue_style('twiiter-sc-css', get_template_directory_uri() . '/css/twittering.css');
    wp_enqueue_style('latest-news-css', get_template_directory_uri() . '/css/news.css');
    wp_enqueue_style('instagram-css', get_template_directory_uri() . '/css/inst.css');
    wp_enqueue_style('fb-rsvp-css', get_template_directory_uri() . '/css/fbook.css');
    wp_enqueue_style('explore-css', get_template_directory_uri() . '/css/location.css');
    wp_enqueue_style('contact-css', get_template_directory_uri() . '/css/contact.css');
    wp_enqueue_style('sponsors-css', get_template_directory_uri() . '/css/sponsors.css');
    wp_enqueue_style('media-grid-css', get_template_directory_uri() . '/css/gallery.css');
    wp_enqueue_style('text-blocks-css', get_template_directory_uri() . '/css/samples.css');
    wp_enqueue_style('registration-css', get_template_directory_uri() . '/css/registration.css');
    wp_enqueue_style('samplepage-css', get_template_directory_uri() . '/css/samplepage.css');
    wp_enqueue_style('khore-dynamic-css', admin_url('admin-ajax.php') . '?action=dynamic-css');
    wp_enqueue_style('khore-style', get_stylesheet_uri());

    if (!empty($ef_options['ef_font'])) {
        wp_enqueue_style('khore-font-custom', 'http://fonts.googleapis.com/css?family=' . $ef_options['ef_font']);
    }

// Scripts
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery-2.1.1.min.js', false, false, false);
    wp_enqueue_script('jquery-migrate', 'http://code.jquery.com/jquery-migrate-1.2.1.js', false, false, false);
    wp_enqueue_script('jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.js', false, false, false);
    wp_enqueue_script('khore-script-googlemaps', 'http://maps.google.com/maps/api/js?sensor=true', false, false, false);
    wp_enqueue_script('khore-script-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), false, false);
    wp_enqueue_script('khore-script-device', get_template_directory_uri() . '/js/device.js', array('jquery'), false, false);
    wp_enqueue_script('khore-script-countdown', get_template_directory_uri() . '/js/jquery.countdown.min.js', array('jquery'), false, true);
    wp_enqueue_script('khore-script-iscroll-probe', get_template_directory_uri() . '/js/iscroll-probe.js', array('jquery'), false, false);
    wp_enqueue_script('khore-script-nicescroll', get_template_directory_uri() . '/js/jquery.nicescroll.min.js', array('jquery'), false, false);
    wp_enqueue_script('khore-script-swiper', get_template_directory_uri() . '/js/swiper.jquery.min.js', array('jquery'), false, false);
    wp_enqueue_script('khore-script-main', get_template_directory_uri() . '/js/jquery.main.js', array('jquery'), false, false);
    wp_enqueue_script('khore-tweetmachine', get_template_directory_uri() . '/js/tweetMachine.min.js');
    wp_enqueue_script('khore-recaptcha', 'http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
    wp_enqueue_script('khore-woocommerce', get_template_directory_uri() . '/js/woocommerce.js', array('jquery'), false, false);
    wp_enqueue_script('khore-form-inscricoes', get_template_directory_uri() . '/js/form.inscricoes.js');
    wp_enqueue_script('khore-geral', get_template_directory_uri() . '/js/geral.js');
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // language para countdown
    $lang = $GLOBALS['q_config']['language'];
    if ($lang == 'pb') {
       $lang = 'pt-BR';
    } else if ($lang == 'en') {
       $lang = '';
    }
    if ($lang != '') {
        wp_enqueue_script("khore-script-countdown-$lang", get_template_directory_uri() . "/js/jquery.countdown-$lang.js", array('jquery'), false, true);
    }
}

function khore_countdown_set_lang() {
    $lang = $GLOBALS['_COOKIE']['qtrans_front_language'];
    if ($lang == 'pb') {
       $lang = 'pt-BR';
    } else if ($lang == 'en') {
       $lang = '';
    }
?>
    <script type="text/javascript">
        jQuery.countdown.setDefaults(jQuery.countdown.regionalOptions['<?php echo $lang; ?>']);
    </script>
<?php
}
add_action('wp_print_footer_scripts', khore_countdown_set_lang);

function khore_template_hash($hashtag, $count = 9) {
    ?>
    <script type="text/javascript">
        var khore_hash = '<?php echo $hashtag; ?>';
        var khore_count = '<?php echo $count; ?>';
    </script>
    <?php
}

add_action('set_ajax_params', 'khore_template_hash', 10, 2);

// ******************* Ajax ****************** //

add_action('wp_ajax_nopriv_get_tweets', 'khore_ajax_get_tweets');
add_action('wp_ajax_get_tweets', 'khore_ajax_get_tweets');

function khore_ajax_get_tweets() {
    global $twitter;
    $tweets = array();
    if (!empty($twitter)) {
        $url           = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield      = "?q={$_GET['queryParams']['q']}&count={$_GET['queryParams']['count']}";
        $requestMethod = 'GET';
        $store         = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();
        $tweets        = json_decode($store);
        echo json_encode($tweets->statuses);
    }
    die;
}

add_action('wp_ajax_nopriv_get_instagrams', 'khore_ajax_get_instagrams');
add_action('wp_ajax_get_instagrams', 'khore_ajax_get_instagrams');

function khore_ajax_get_instagrams() {
    global $instagram;
    $instagramhash = !empty($_POST['hashtag']) ? $_POST['hashtag'] : '';

    $limit = !empty($_POST['limit']) ? $_POST['limit'] : '9';

    $ret = array();
    if (isset($instagram) && !empty($instagramhash)) {
        $instagrams = $instagram->getTagMedia($instagramhash, $limit);
        $ret        = $instagrams->data;
    }

    echo json_encode($ret);
    die;
}

function khore_parse_tweet_text($text) {
    $text = preg_replace('/(https?:\/\/[^\s"<>]+)/', '<a href="$1">$1</a>', $text);
    $text = preg_replace('/(^|[\n\s])@([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/$2">@$2</a>', $text);
    $text = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/search?q=%23$2">#$2</a>', $text);

    return $text;
}

// widgets

add_filter('ef_widget_render', 'khore_ef_widget_render', 10, 3);

function khore_ef_widget_render($content, $id_base, $args) {
    ob_start();
    include(locate_template("components/templates/widgets/$id_base.php"));
    return ob_get_clean();
}

// shortcodes

add_filter('efcb_shortcode_render', 'khore_efcb_shortcode_render', 10, 3);

function khore_efcb_shortcode_render($content, $id_base, $args) {
    ob_start();
    include(locate_template("components/templates/shortcodes/$id_base.php"));
    return ob_get_clean();
}

add_filter('efcb_shortcode_render', 'khore_efcb_shortcode_render', 10, 3);

add_action('wp_ajax_nopriv_get_section', 'khore_efcb_section_render');
add_action('wp_ajax_get_section', 'khore_efcb_section_render');

function khore_efcb_section_render() {
    $page_id      = !empty($_REQUEST["page_id"]) ? $_REQUEST["page_id"] : null;
    $frontpage_id = get_option('page_on_front');
    if ($page_id) {
        /* comentário: fernao
         * previne que pagina setada como home não seja exibida
         
        if (!empty($frontpage_id) && $frontpage_id == $page_id) {
            $pageQuery = new WP_Query(array(
                'post__in'  => array($page_id),
                'post_type' => 'page'
            ));
            if ($pageQuery->have_posts()) :
                while ($pageQuery->have_posts()) :
                    $pageQuery->the_post();

                    ob_start();
                    get_template_part('front-page-content');
                    $ret = ob_get_contents();
                    ob_end_clean();

                endwhile;
            endif;

            echo $ret;
        } else {
        */
            $pageQuery = new WP_Query(array(
                'post__in'  => array($page_id),
                'post_type' => 'page'
            ));
            if ($pageQuery->have_posts()) :
                while ($pageQuery->have_posts()) :
                    $pageQuery->the_post();
                    echo do_shortcode(the_content());
                endwhile;
            endif;
        //}
    } else {
        echo 'Page not found';
    }
    die();
}

add_action('wp_ajax_nopriv_get_template_part', 'khore_efcb_get_template_part');
add_action('wp_ajax_get_template_part', 'khore_efcb_get_template_part');

function khore_efcb_get_template_part() {
    $post_id   = !empty($_REQUEST['post_id']) ? $_REQUEST['post_id'] : null;
    $post_type = !empty($_REQUEST['post_type']) ? $_REQUEST['post_type'] : null;
    $ret       = '';
    if ($post_id && $post_type) {
        global $post;
        $post = get_post($post_id);
        setup_postdata($post);
        ob_start();
        get_template_part("$post_type-content");
        $ret  = ob_get_contents();
        ob_end_clean();
    }
    echo $ret;
    die();
}

add_action('wp_ajax_nopriv_get_schedule', array('EF_Session_Helper', 'ef_ajax_get_schedule'));
add_action('wp_ajax_get_schedule', array('EF_Session_Helper', 'ef_ajax_get_schedule'));

add_action('wp_ajax_nopriv_get_video_thumbnail', 'khore_ajax_get_video_thumbnail');
add_action('wp_ajax_get_video_thumbnail', 'khore_ajax_get_video_thumbnail');

function khore_ajax_get_video_thumbnail() {
    $ret = '';
    $url = filter_input(INPUT_POST, 'url');
    if (!empty($url)) {
        $ret = khore_get_video_thumbnail($url, array('youtube' => 'default', 'vimeo' => 'thumbnail_small'));
    }

    echo json_encode($ret);
    die;
}

// ******************* Misc ****************** //

add_filter('manage_edit-speaker_columns', 'edit_speaker_columns');

function edit_speaker_columns($columns) {
    $new_columns = array(
        'cb'         => $columns['cb'],
        'title'      => $columns['title'],
        'menu_order' => __('Order', 'khore'),
        'date'       => $columns['date'],
    );
    return $new_columns;
}

add_action('manage_posts_custom_column', 'edit_post_columns', 10, 2);

function edit_post_columns($column_name) {
    global $post;

    switch ($column_name) {
        case 'menu_order' :
            echo $post->menu_order;
            break;

        default:
    }
}

function getRelativeTime($date) {
    $diff = time() - strtotime($date);
    if ($diff < 60)
        return $diff . _n(' second', ' seconds', $diff, 'khore') . __(' ago', 'khore');
    $diff = round($diff / 60);
    if ($diff < 60)
        return $diff . _n(' minute', ' minutes', $diff, 'khore') . __(' ago', 'khore');
    $diff = round($diff / 60);
    if ($diff < 24)
        return $diff . _n(' hour', ' hours', $diff, 'khore') . __(' ago', 'khore');
    $diff = round($diff / 24);
    if ($diff < 7)
        return $diff . _n(' day', ' days', $diff, 'khore') . __(' ago', 'khore');
    $diff = round($diff / 7);
    if ($diff < 4)
        return $diff . _n(' week', ' weeks', $diff, 'khore') . __(' ago', 'khore');
    return __('on ', 'khore') . date("F j, Y", strtotime($date));
}

function khore_get_video_embedded_url($url) {
    $ret = $url;
    try {
        if (!empty($url)) {
            $image_url = parse_url($url);
            if (!empty($image_url['host'])) {
                if ($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com') {
                    $query_params = explode('&', $image_url['query']);
                    if (!empty($query_params)) {
                        foreach ($query_params as $query_param) {
                            $parts = explode('=', $query_param);
                            if ($parts[0] == 'v') {
                                $ret = "https://www.youtube.com/embed/{$parts[1]}";
                                break;
                            }
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        
    }

    return $ret;
}

function khore_get_video_thumbnail($url, $sizes) {
    $ret = '';
    try {
        if (!empty($url)) {
            $image_url = parse_url($url);

            if (!empty($image_url['host'])) {
                if ($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com') {
                    $query_params = explode('&', $image_url['query']);
                    if (!empty($query_params)) {
                        foreach ($query_params as $query_param) {
                            $parts = explode('=', $query_param);
                            if ($parts[0] == 'v') {
                                $ret = "http://img.youtube.com/vi/" . $parts[1] . "/{$sizes['youtube']}.jpg";
                                break;
                            }
                        }
                    }
                } else if ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com') {
                    $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . substr($image_url['path'], 1) . ".php"));
                    $ret  = $hash[0][$sizes['vimeo']];
                }
            }
        }
    } catch (Exception $e) {
        
    }

    return $ret;
}

################################################################
/**
 * Retrieve adjacent post link.
 *
 * Can either be next or previous post link.
 *
 * Based on get_adjacent_post() from wp-includes/link-template.php
 *
 * @param array $r Arguments.
 * @param bool $previous Optional. Whether to retrieve previous post.
 * @return array of post objects.
 */

function khore_get_adjacent_post_plus($r, $previous = true) {
    global $post, $wpdb;

    extract($r, EXTR_SKIP);

    if (empty($post))
        return null;

//  Sanitize $order_by, since we are going to use it in the SQL query. Default to 'post_date'.
    if (in_array($order_by, array('post_date', 'post_title', 'post_excerpt', 'post_name', 'post_modified'))) {
        $order_format = '%s';
    } elseif (in_array($order_by, array('ID', 'post_author', 'post_parent', 'menu_order', 'comment_count'))) {
        $order_format = '%d';
    } elseif ($order_by == 'custom' && !empty($meta_key)) { // Don't allow a custom sort if meta_key is empty.
        $order_format = '%s';
    } elseif ($order_by == 'numeric' && !empty($meta_key)) {
        $order_format = '%d';
    } else {
        $order_by     = 'post_date';
        $order_format = '%s';
    }

//  Sanitize $order_2nd. Only columns containing unique values are allowed here. Default to 'post_date'.
    if (in_array($order_2nd, array('post_date', 'post_title', 'post_modified'))) {
        $order_format2 = '%s';
    } elseif (in_array($order_2nd, array('ID'))) {
        $order_format2 = '%d';
    } else {
        $order_2nd     = 'post_date';
        $order_format2 = '%s';
    }

//  Sanitize num_results (non-integer or negative values trigger SQL errors)
    $num_results = intval($num_results) < 2 ? 1 : intval($num_results);

//  Queries involving custom fields require an extra table join
    if ($order_by == 'custom' || $order_by == 'numeric') {
        $current_post = get_post_meta($post->ID, $meta_key, TRUE);
        $order_by     = ($order_by === 'numeric') ? 'm.meta_value+0' : 'm.meta_value';
        $meta_join    = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $meta_key);
    } elseif ($in_same_meta) {
        $current_post = $post->$order_by;
        $order_by     = 'p.' . $order_by;
        $meta_join    = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $in_same_meta);
    } else {
        $current_post = $post->$order_by;
        $order_by     = 'p.' . $order_by;
        $meta_join    = '';
    }

//  Get the current post value for the second sort column
    $current_post2 = $post->$order_2nd;
    $order_2nd     = 'p.' . $order_2nd;

//  Get the list of post types. Default to current post type
    if (empty($post_type))
        $post_type = "'$post->post_type'";

//  Put this section in a do-while loop to enable the loop-to-first-post option
    do {
        $join                = $meta_join;
        $excluded_categories = $ex_cats;
        $included_categories = $in_cats;
        $excluded_posts      = $ex_posts;
        $included_posts      = $in_posts;
        $in_same_term_sql    = $in_same_author_sql  = $in_same_meta_sql    = $ex_cats_sql         = $in_cats_sql         = $ex_posts_sql        = $in_posts_sql        = '';

//      Get the list of hierarchical taxonomies, including customs (don't assume taxonomy = 'category')
        $taxonomies = array_filter(get_post_taxonomies($post->ID), "is_taxonomy_hierarchical");

        if (($in_same_cat || $in_same_tax || $in_same_format || !empty($excluded_categories) || !empty($included_categories)) && !empty($taxonomies)) {
            $cat_array    = $tax_array    = $format_array = array();

            if ($in_same_cat) {
                $cat_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
            }
            if ($in_same_tax && !$in_same_cat) {
                if ($in_same_tax === true) {
                    if ($taxonomies != array('category'))
                        $taxonomies = array_diff($taxonomies, array('category'));
                } else
                    $taxonomies = (array) $in_same_tax;
                $tax_array  = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
            }
            if ($in_same_format) {
                $taxonomies[] = 'post_format';
                $format_array = wp_get_object_terms($post->ID, 'post_format', array('fields' => 'ids'));
            }

            $join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy IN (\"" . implode('", "', $taxonomies) . "\")";

            $term_array       = array_unique(array_merge($cat_array, $tax_array, $format_array));
            if (!empty($term_array))
                $in_same_term_sql = "AND tt.term_id IN (" . implode(',', $term_array) . ")";

            if (!empty($excluded_categories)) {
//              Support for both (1 and 5 and 15) and (1, 5, 15) delimiter styles
                $delimiter           = (strpos($excluded_categories, ',') !== false) ? ',' : 'and';
                $excluded_categories = array_map('intval', explode($delimiter, $excluded_categories));
//              Three category exclusion methods are supported: 'strong', 'diff', and 'weak'.
//              Default is 'weak'. See the plugin documentation for more information.
                if ($ex_cats_method === 'strong') {
                    $taxonomies    = array_filter(get_post_taxonomies($post->ID), "is_taxonomy_hierarchical");
                    if (function_exists('get_post_format'))
                        $taxonomies[]  = 'post_format';
                    $ex_cats_posts = get_objects_in_term($excluded_categories, $taxonomies);
                    if (!empty($ex_cats_posts))
                        $ex_cats_sql   = "AND p.ID NOT IN (" . implode($ex_cats_posts, ',') . ")";
                } else {
                    if (!empty($term_array) && !in_array($ex_cats_method, array('diff', 'differential')))
                        $excluded_categories = array_diff($excluded_categories, $term_array);
                    if (!empty($excluded_categories))
                        $ex_cats_sql         = "AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
                }
            }

            if (!empty($included_categories)) {
                $in_same_term_sql    = ''; // in_cats overrides in_same_cat
                $delimiter           = (strpos($included_categories, ',') !== false) ? ',' : 'and';
                $included_categories = array_map('intval', explode($delimiter, $included_categories));
                $in_cats_sql         = "AND tt.term_id IN (" . implode(',', $included_categories) . ")";
            }
        }

//      Optionally restrict next/previous links to same author
        if ($in_same_author)
            $in_same_author_sql = $wpdb->prepare("AND p.post_author = %d", $post->post_author);

//      Optionally restrict next/previous links to same meta value
        if ($in_same_meta && $r['order_by'] != 'custom' && $r['order_by'] != 'numeric')
            $in_same_meta_sql = $wpdb->prepare("AND m.meta_value = %s", get_post_meta($post->ID, $in_same_meta, TRUE));

//      Optionally exclude individual post IDs
        if (!empty($excluded_posts)) {
            $excluded_posts = array_map('intval', explode(',', $excluded_posts));
            $ex_posts_sql   = " AND p.ID NOT IN (" . implode(',', $excluded_posts) . ")";
        }

//      Optionally include individual post IDs
        if (!empty($included_posts)) {
            $included_posts = array_map('intval', explode(',', $included_posts));
            $in_posts_sql   = " AND p.ID IN (" . implode(',', $included_posts) . ")";
        }

        $adjacent = $previous ? 'previous' : 'next';
        $order    = $previous ? 'DESC' : 'ASC';
        $op       = $previous ? '<' : '>';

//      Optionally get the first/last post. Disable looping and return only one result.
        if ($end_post) {
            $order       = $previous ? 'ASC' : 'DESC';
            $num_results = 1;
            $loop        = false;
            if ($end_post === 'fixed') // display the end post link even when it is the current post
                $op          = $previous ? '<=' : '>=';
        }

//      If there is no next/previous post, loop back around to the first/last post.
        if ($loop && isset($result)) {
            $op   = $previous ? '>=' : '<=';
            $loop = false; // prevent an infinite loop if no first/last post is found
        }

        $join = apply_filters("get_{$adjacent}_post_plus_join", $join, $r);

//      In case the value in the $order_by column is not unique, select posts based on the $order_2nd column as well.
//      This prevents posts from being skipped when they have, for example, the same menu_order.
        $where = apply_filters("get_{$adjacent}_post_plus_where", $wpdb->prepare("WHERE ( $order_by $op $order_format OR $order_2nd $op $order_format2 AND $order_by = $order_format ) AND p.post_type IN ($post_type) AND p.post_status = 'publish' $in_same_term_sql $in_same_author_sql $in_same_meta_sql $ex_cats_sql $in_cats_sql $ex_posts_sql $in_posts_sql", $current_post, $current_post2, $current_post), $r);

        $sort = apply_filters("get_{$adjacent}_post_plus_sort", "ORDER BY $order_by $order, $order_2nd $order LIMIT $num_results", $r);

        $query     = "SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort";
        $query_key = 'adjacent_post_' . md5($query);
        $result    = wp_cache_get($query_key);
        if (false !== $result)
            return $result;

//      echo $query . '<br />';
//      Use get_results instead of get_row, in order to retrieve multiple adjacent posts (when $num_results > 1)
//      Add DISTINCT keyword to prevent posts in multiple categories from appearing more than once
        $result = $wpdb->get_results("SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort");
        if (null === $result)
            $result = '';
    } while (!$result && $loop);

    wp_cache_set($query_key, $result);
    return $result;
}

//Event Framwork Session Order By Session Date

/**
 * Display previous post link that is adjacent to the current post.
 *
 * Based on previous_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if previous post link is found, otherwise false.
 */
function khore_previous_post_link_plus($args = '') {

    return khore_adjacent_post_link_plus($args, '&laquo; %link', true);
}

/**
 * Display next post link that is adjacent to the current post.
 *
 * Based on next_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if next post link is found, otherwise false.
 */
function khore_next_post_link_plus($args = '') {

    return khore_adjacent_post_link_plus($args, '%link &raquo;', false);
}

/**
 * Display adjacent post link.
 *
 * Can be either next post link or previous.
 *
 * Based on adjacent_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @param bool $previous Optional, default is true. Whether display link to previous post.
 * @return bool True if next/previous post is found, otherwise false.
 */
function khore_adjacent_post_link_plus($args = '', $format = '%link &raquo;', $previous = true) {

    $defaults = array(
        'order_by'       => 'post_date', 'order_2nd'      => 'post_date', 'meta_key'       => '', 'post_type'      => '',
        'loop'           => false, 'end_post'       => false, 'thumb'          => false, 'max_length'     => 0,
        'format'         => '', 'link'           => '%title', 'date_format'    => '', 'tooltip'        => '%title',
        'in_same_cat'    => false, 'in_same_tax'    => false, 'in_same_format' => false,
        'in_same_author' => false, 'in_same_meta'   => false,
        'ex_cats'        => '', 'ex_cats_method' => 'weak', 'in_cats'        => '', 'ex_posts'       => '', 'in_posts'       => '',
        'before'         => '', 'after'          => '', 'num_results'    => 1, 'return'         => false, 'echo'           => true
    );

//If Post Types Order plugin is installed, default to sorting on menu_order
    if (function_exists('CPTOrderPosts')) {

        $defaults['order_by'] = 'menu_order';
    }

    $r = wp_parse_args($args, $defaults);
    if (empty($r['format'])) {
        $r['format'] = $format;
    }
    if (empty($r['date_format'])) {
        $r['date_format'] = get_option('date_format');
    }
    if (!function_exists('get_post_format')) {
        $r['in_same_format'] = false;
    }

    if ($previous && is_attachment()) {

        $posts   = array();
        $posts[] = &get_post($GLOBALS['post']->post_parent);
    } else {
        $posts = khore_get_adjacent_post_plus($r, $previous);
    }

//If there is no next/previous post, return false so themes may conditionally display inactive link text.
    if (!$posts) {
        return false;
    }

//If sorting by date, display posts in reverse chronological order. Otherwise display in alpha/numeric order.
    if (($previous && $r['order_by'] != 'post_date') || (!$previous && $r['order_by'] == 'post_date')) {
        $posts = array_reverse($posts, true);
    }

//Option to return something other than the formatted link
    if ($r['return']) {

        if ($r['num_results'] == 1) {

            reset($posts);
            $post = current($posts);
            if ($r['return'] === 'id')
                return $post->ID;
            if ($r['return'] === 'href')
                return get_permalink($post);
            if ($r['return'] === 'object')
                return $post;
            if ($r['return'] === 'title')
                return $post->post_title;
            if ($r['return'] === 'date')
                return mysql2date($r['date_format'], $post->post_date);
        } elseif ($r['return'] === 'object') {

            return $posts;
        }
    }

    $output = $r['before'];

//When num_results > 1, multiple adjacent posts may be returned. Use foreach to display each adjacent post.
    foreach ($posts as $post) {

        $title = $post->post_title;
        if (empty($post->post_title)) {

            $title = $previous ? __('Previous Post', 'khore') : __('Next Post', 'khore');
        }

        $title  = apply_filters('the_title', $title, $post->ID);
        $date   = mysql2date($r['date_format'], $post->post_date);
        $author = get_the_author_meta('display_name', $post->post_author);

//Set anchor title attribute to long post title or custom tooltip text. Supports variable replacement in custom tooltip.
        if ($r['tooltip']) {
            $tooltip = str_replace('%title', $title, $r['tooltip']);
            $tooltip = str_replace('%date', $date, $tooltip);
            $tooltip = str_replace('%author', $author, $tooltip);
            $tooltip = ' title="' . esc_attr($tooltip) . '"';
        } else
            $tooltip = '';

//Truncate the link title to nearest whole word under the length specified.
        $max_length = intval($r['max_length']) < 1 ? 9999 : intval($r['max_length']);
        if (strlen($title) > $max_length)
            $title      = substr($title, 0, strrpos(substr($title, 0, $max_length), ' ')) . '...';

        $rel = $previous ? 'prev' : 'next';

        $anchor = '<a href="' . get_permalink($post) . '" rel="' . $rel . '"' . $tooltip . '>';
        $link   = str_replace('%title', $title, $r['link']);
        $link   = str_replace('%date', $date, $link);
        $link   = $anchor . $link . '</a>';

        $format = str_replace('%link', $link, $r['format']);
        $format = str_replace('%title', $title, $format);
        $format = str_replace('%date', $date, $format);
        $format = str_replace('%author', $author, $format);
        if (($r['order_by'] == 'custom' || $r['order_by'] == 'numeric') && !empty($r['meta_key'])) {
            $meta   = get_post_meta($post->ID, $r['meta_key'], true);
            $format = str_replace('%meta', $meta, $format);
        } elseif ($r['in_same_meta']) {
            $meta   = get_post_meta($post->ID, $r['in_same_meta'], true);
            $format = str_replace('%meta', $meta, $format);
        }

//Get the category list, including custom taxonomies (only if the %category variable has been used).
        if ((strpos($format, '%category') !== false) && version_compare(PHP_VERSION, '5.0.0', '>=')) {
            $term_list    = '';
            $taxonomies   = array_filter(get_post_taxonomies($post->ID), "is_taxonomy_hierarchical");
            if ($r['in_same_format'] && get_post_format($post->ID))
                $taxonomies[] = 'post_format';
            foreach ($taxonomies as &$taxonomy) {
//No, this is not a mistake. Yes, we are testing the result of the assignment ( = ).
//We are doing it this way to stop it from appending a comma when there is no next term.
                if ($next_term = get_the_term_list($post->ID, $taxonomy, '', ', ', '')) {
                    $term_list .= $next_term;
                    if (current($taxonomies))
                        $term_list .= ', ';
                }
            }
            $format = str_replace('%category', $term_list, $format);
        }

//Optionally add the post thumbnail to the link. Wrap the link in a span to aid CSS styling.
        if ($r['thumb'] && has_post_thumbnail($post->ID)) {
            if ($r['thumb'] === true) // use 'post-thumbnail' as the default size
                $r['thumb'] = 'post-thumbnail';
            $thumbnail  = '<a class="post-thumbnail" href="' . get_permalink($post) . '" rel="' . $rel . '"' . $tooltip . '>' . get_the_post_thumbnail($post->ID, $r['thumb']) . '</a>';
            $format     = $thumbnail . '<span class="post-link">' . $format . '</span>';
        }

//If more than one link is returned, wrap them in <li> tags
        if (intval($r['num_results']) > 1)
            $format = '<li>' . $format . '</li>';

        $output .= $format;
    }

    $output .= $r['after'];

//If echo is false, don't display anything. Return the link as a PHP string.
    if (!$r['echo'] || $r['return'] === 'output')
        return $output;

    $adjacent = $previous ? 'previous' : 'next';
    echo apply_filters("{$adjacent}_post_link_plus", $output, $r);

    return true;
}

/**
 *
 * Woocommerce Integration
 *
 */
add_action('after_setup_theme', 'khore_woocommerce_setup_theme');

function khore_woocommerce_setup_theme() {
    add_theme_support('woocommerce');
}

add_action('wp_head', 'khore_wp_head');

function khore_wp_head() {
    global $post;
    if (isset($post) && isset($post->post_content) && in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) && (has_shortcode($post->post_content, 'efcb-section-registration') || has_shortcode($post->post_content, 'efcb-section-samplepage'))) {
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 10);
        add_action('woocommerce_before_shop_loop_item', 'khore_woocommerce_before_shop_loop_item', 10);
        add_filter('woocommerce_locate_template', 'khore_woocommerce_locate_template', 10, 3);
    }
}

function khore_woocommerce_before_shop_loop_item() {
    global $post;

    echo '<td class="title">';
    do_action('woocommerce_before_shop_loop_item_title');
    echo '<h3>' . get_the_title() . '</h3>';
    do_action('woocommerce_after_shop_loop_item_title');
    echo '</td>';
    echo '<td class="description">';
    echo '<span class="short-description">' . $post->post_excerpt . '</span>';
    echo '</td>';
    echo '<td class="price">';
    woocommerce_template_loop_price();
    echo '</td>';
    echo '<td class="quantity">';
    woocommerce_quantity_input();
    echo '<input type="hidden" name="product_id" value="' . $post->ID . '" />';
    echo '</td>';
}

function khore_woocommerce_locate_template($template, $template_name, $template_path) {
    return $template;
}

/* ----------------------------- */

add_filter('walker_nav_menu_start_el', 'khore_walker_nav_menu_start_el', 10, 4);

function khore_walker_nav_menu_start_el($item_output, $item, $depth, $args) {
    if (in_array('menu-item-has-children', $item->classes)) {
        $item_output = "<a href=\"$item->url\" class=\"menu-item-header\">$item->title</a> <i class=\"fa fa-chevron-down\"></i>";
    }

    return $item_output;
}

add_filter('ef_schedule_speakers_thumbnail_size', 'khore_ef_schedule_speakers_thumbnail_size');

function khore_ef_schedule_speakers_thumbnail_size($size) {
    return 'khore-speaker';
}

add_filter('ef_schedule_speakers_thumbnail_class', 'khore_ef_schedule_speakers_thumbnail_class');

function khore_ef_schedule_speakers_thumbnail_class($class) {
    return 'image scalable-image';
}

add_filter('next_post_link', 'khore_next_post_link');
add_filter('next_post_link_plus', 'khore_next_post_link');

function khore_next_post_link($html) {
    $html = str_replace('<a', '<a class="next pull-right"', $html);
    return $html;
}

add_filter('previous_post_link', 'khore_previous_post_link');
add_filter('previous_post_link_plus', 'khore_previous_post_link');

function khore_previous_post_link($html) {
    $html = str_replace('<a', '<a class="prev pull-left"', $html);
    return $html;
}

function khore_exhibitor_letter_posts_where($where) {
    return $where . " AND `post_title` LIKE '" . trim($_REQUEST['letter']) . "%' ";
}

function khore_exhibitor_text_posts_where($where) {
    return $where . " AND `post_title` LIKE '%" . trim($_REQUEST['text']) . "%' ";
}

function khore_main_pagination($paged, $total, $styles = null) {
    global $wp;

    $primary_style   = !empty($styles) && !empty($styles[0]) ? $styles[0] : '';
    $secondary_style = !empty($styles) && !empty($styles[1]) ? $styles[1] : '';
    $current_url     = remove_query_arg('paged', add_query_arg($wp->query_string, '', home_url($wp->request)));
    $pag_links       = paginate_links(array(
        'base'      => $current_url . '%_%',
        'format'    => strstr($current_url, '?') === false ? '?paged=%#%' : '&paged=%#%',
        'current'   => $paged,
        'total'     => $total,
        'show_all'  => true,
        'prev_text' => __('PREV', 'khore'),
        'next_text' => __('NEXT', 'khore'),
        'type'      => 'array'
    ));
    //$pag_links = str_replace('<a ', '<a id="53" ', $pag_links);
    if ($pag_links && count($pag_links) > 0) {
        ?>
        <nav class="pagination" <?php echo $secondary_style; ?>>
            <?php
            echo '<ul>';
            foreach ($pag_links as $pag_link) {
                if (strstr($pag_link, 'prev ') !== false) {
                    echo '<li>' . str_replace('<a class="', "<a $primary_style class=\"pagination-lnk pagination-lnk_prev ", $pag_link) . '</li>';
                }
                if (strstr($pag_link, 'next ') === false && strstr($pag_link, 'prev ') === false) {
                    ?>
                    <li>
                        <?php
                        $replace  = str_replace("<span class='", "<a $primary_style class='active ", $pag_link);
                        $pag_link = str_replace('</span>', '</a>', $replace);
                        echo $pag_link;
                        ?>
                    </li>
                    <?php
                }
                if (strstr($pag_link, 'next ') !== false) {
                    echo '<li>' . str_replace('<a class="', "<a $primary_style class=\"pagination-lnk pagination-lnk_next ", $pag_link) . '</li>';
                }
            }
            echo '</ul>';
            ?>
        </nav>
        <?php
    }
}

add_filter('ef_content_builder_items', 'ef_content_builder_items');
add_filter('ef_content_builder_sections', 'ef_content_builder_sections');
add_filter('ef_content_builder_options', 'ef_content_builder_options');
add_filter('ef_content_builder_templates', 'ef_content_builder_templates');

function ef_content_builder_items($items) {
    //array_push($items, 'gigilatrottola.php');
    return $items;
}

function ef_content_builder_sections($items) {
    //array_push($items, 'gigilatrottola.php');
    return $items;
}

function ef_content_builder_options($items) {
    //array_push($items, 'gigilatrottola.php');
    return $items;
}

function ef_content_builder_templates($items) {
    //array_push($items, 'gigilatrottola.php');
    return $items;
}

function get_id_for_section($name_page) {
    
}

add_action('wp_update_nav_menu_item', 'custom_nav_update', 10, 3);

function custom_nav_update($menu_id, $menu_item_db_id, $args) {
    if (!isset($_REQUEST['khore-menu-item-custom-icon']))
        $_REQUEST['khore-menu-item-custom-icon']                   = array();
    if (!isset($_REQUEST['khore-menu-item-custom-icon'][$menu_item_db_id]))
        $_REQUEST['khore-menu-item-custom-icon'][$menu_item_db_id] = false;
    if (is_array($_REQUEST['khore-menu-item-custom-icon'])) {
        $custom_value = $_REQUEST['khore-menu-item-custom-icon'][$menu_item_db_id];
        update_post_meta($menu_item_db_id, '_khore_menu_item_custom_icon', $custom_value);
    }
}

add_filter('wp_setup_nav_menu_item', 'custom_nav_item');

function custom_nav_item($menu_item) {
    $menu_item->custom_icon = get_post_meta($menu_item->ID, '_khore_menu_item_custom_icon', true);
    return $menu_item;
}

add_filter('wp_edit_nav_menu_walker', 'custom_nav_edit_walker', 10, 2);

function custom_nav_edit_walker($walker, $menu_id) {
    return 'Walker_Nav_Menu_Edit_Custom';
}

class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu {

    /**
     * @see Walker_Nav_Menu::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function start_lvl(&$output, $depth = 0, $args = array()) {
        
    }

    /**
     * @see Walker_Nav_Menu::end_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function end_lvl(&$output, $depth = 0, $args = array()) {
        
    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $_wp_nav_menu_max_depth;
        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        ob_start();
        $item_id      = esc_attr($item->ID);
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );

        $original_title = '';
        if ('taxonomy' == $item->type) {
            $original_title = get_term_field('name', $item->object_id, $item->object, 'raw');
            if (is_wp_error($original_title))
                $original_title = false;
        } elseif ('post_type' == $item->type) {
            $original_object = get_post($item->object_id);
            $original_title  = get_the_title($original_object->ID);
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr($item->object),
            'menu-item-edit-' . ( ( isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if (!empty($item->_invalid)) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title     = sprintf(__('%s (Invalid)'), $item->title);
        } elseif (isset($item->post_status) && 'draft' == $item->post_status) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title     = sprintf(__('%s (Pending)'), $item->title);
        }

        $title = (!isset($item->label) || '' == $item->label ) ? $title : $item->label;

        $submenu_text = '';
        if (0 == $depth)
            $submenu_text = 'style="display: none;"';
        ?>
        <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes); ?>">
            <dl class="menu-item-bar">
                <dt class="menu-item-handle">
                <span class="item-title"><span class="menu-item-title"><?php echo esc_html($title); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e('sub item'); ?></span></span>
                <span class="item-controls">
                    <span class="item-type"><?php echo esc_html($item->type_label); ?></span>
                    <span class="item-order hide-if-js">
                        <a href="<?php
                        echo wp_nonce_url(
                                add_query_arg(
                                        array(
                            'action'    => 'move-up-menu-item',
                            'menu-item' => $item_id,
                                        ), remove_query_arg($removed_args, admin_url('nav-menus.php'))
                                ), 'move-menu_item'
                        );
                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
                        |
                        <a href="<?php
                        echo wp_nonce_url(
                                add_query_arg(
                                        array(
                            'action'    => 'move-down-menu-item',
                            'menu-item' => $item_id,
                                        ), remove_query_arg($removed_args, admin_url('nav-menus.php'))
                                ), 'move-menu_item'
                        );
                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
                    </span>
                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
                    echo ( isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item'] ) ? admin_url('nav-menus.php') : add_query_arg('edit-menu-item', $item_id, remove_query_arg($removed_args, admin_url('nav-menus.php#menu-item-settings-' . $item_id)));
                    ?>"><?php _e('Edit Menu Item'); ?></a>
                </span>
                </dt>
            </dl>

            <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
                <?php if ('custom' == $item->type) : ?>
                    <p class="field-url description description-wide">
                        <label for="edit-menu-item-url-<?php echo $item_id; ?>">
                            <?php _e('URL'); ?><br />
                            <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->url); ?>" />
                        </label>
                    </p>
                <?php endif; ?>
                <p class="description description-thin">
                    <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                        <?php _e('Navigation Label'); ?><br />
                        <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->title); ?>" />
                    </label>
                </p>
                <p class="description description-thin">
                    <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
                        <?php _e('Title Attribute'); ?><br />
                        <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->post_excerpt); ?>" />
                    </label>
                </p>
                <p class="description description-wide">
                    <label for="edit-menu-item-custom-<?php echo $item_id; ?>">
                        <?php _e('Icon'); ?><br />
                        <input type="text" id="edit-menu-item-custom-icon-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="khore-menu-item-custom-icon[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->custom_icon); ?>" />
                        <span class="description"><?php _e('Type full name of Font Awesome icon. You can find all icons on <a href="http://fontawesome.io/icons" target="_blank">FontAwesome</a>', 'khore'); ?></span>
                    </label>
                </p>
                <p class="field-link-target description">
                    <label for="edit-menu-item-target-<?php echo $item_id; ?>">
                        <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked($item->target, '_blank'); ?> />
                        <?php _e('Open link in a new window/tab'); ?>
                    </label>
                </p>
                <p class="field-css-classes description description-thin">
                    <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
                        <?php _e('CSS Classes (optional)'); ?><br />
                        <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr(implode(' ', $item->classes)); ?>" />
                    </label>
                </p>
                <p class="field-xfn description description-thin">
                    <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
                        <?php _e('Link Relationship (XFN)'); ?><br />
                        <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->xfn); ?>" />
                    </label>
                </p>
                <p class="field-description description description-wide">
                    <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                        <?php _e('Description'); ?><br />
                        <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html($item->description); // textarea_escaped                                                                                   ?></textarea>
                        <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
                    </label>
                </p>

                <p class="field-move hide-if-no-js description description-wide">
                    <label>
                        <span><?php _e('Move'); ?></span>
                        <a href="#" class="menus-move-up"><?php _e('Up one'); ?></a>
                        <a href="#" class="menus-move-down"><?php _e('Down one'); ?></a>
                        <a href="#" class="menus-move-left"></a>
                        <a href="#" class="menus-move-right"></a>
                        <a href="#" class="menus-move-top"><?php _e('To the top'); ?></a>
                    </label>
                </p>

                <div class="menu-item-actions description-wide submitbox">
                    <?php if ('custom' != $item->type && $original_title !== false) : ?>
                        <p class="link-to-original">
                            <?php printf(__('Original: %s'), '<a href="' . esc_attr($item->url) . '">' . esc_html($original_title) . '</a>'); ?>
                        </p>
                    <?php endif; ?>
                    <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
                    echo wp_nonce_url(
                            add_query_arg(
                                    array(
                        'action'    => 'delete-menu-item',
                        'menu-item' => $item_id,
                                    ), admin_url('nav-menus.php')
                            ), 'delete-menu_item_' . $item_id
                    );
                    ?>"><?php _e('Remove'); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url(add_query_arg(array('edit-menu-item' => $item_id, 'cancel' => time()), admin_url('nav-menus.php')));
                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
                </div>

                <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
                <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->object_id); ?>" />
                <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->object); ?>" />
                <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->menu_item_parent); ?>" />
                <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->menu_order); ?>" />
                <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->type); ?>" />
            </div><!-- .menu-item-settings-->
            <ul class="menu-item-transport"></ul>
            <?php
            $output .= ob_get_clean();
        }

    }

    /* Return image URL from text content */

    function catch_that_image($contnent) {
        $first_img = '';
        ob_start();
        ob_end_clean();
        $output    = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $contnent, $matches);
        $first_img = $matches [1] [0];
        // No Image found. Total fail :-D
        if (empty($first_img)) {
            $first_img = false;
        }
        return $first_img;
    }

    function get_home_page_slider() {
        $ef_options           = EF_Event_Options::get_theme_options();
        $header_logo          = $ef_options['ef_header_logo'];
        $image_ids            = explode(',', $ef_options['ef_header_gallery']);
        $image_url            = '';
        $video_markup         = '';
        $videocontainer_color = !empty($ef_options['ef_videobutton_color']) ? "style=\"border-color: {$ef_options['ef_videobutton_color']};\"" : '';
        $videoicon_size       = !empty($ef_options['ef_videobutton_color']) ? "style=\"color: {$ef_options['ef_videobutton_color']};\"" : '';

        if (strlen($ef_options['ef_header_video']) > 0) {
            $video_url    = parse_url($ef_options['ef_header_video']);
            $video_id     = explode("=", $video_url['query']);
            $video_markup = '<a class="video-card__lnk" href="#" data-video="http://www.youtube.com/embed/' . $video_id[1] . '" ' . $videocontainer_color . '>
                                            <i class="fa fa-play fa-5x" ' . $videoicon_size . '></i>
                                            </a>';
        }
        $event_title      = $ef_options['ef_herotitle'];
        $event_s_title    = $ef_options['ef_herosubtitle'];
        $date_n_time      = $ef_options['ef_datetimeplace'];
        $registration_btn = '';
        $html             = '';
        if (isset($ef_options['ef_home_show_register_btn'])) {
            $button_font_size    = !empty($ef_options['ef_home_registerbutton_font_size']) ? "font-size: {$ef_options['ef_home_registerbutton_font_size']};" : '';
            $button_text_color   = !empty($ef_options['ef_home_registerbutton_color']) ? "color: {$ef_options['ef_home_registerbutton_color']};" : '';
            $button_border_color = !empty($ef_options['ef_home_registerbutton_color']) ? "border-color: {$ef_options['ef_home_registerbutton_color']};" : '';
            $button_style        = '';

            if (!empty($button_font_size) || !empty($button_text_color)) {
                $button_style = "style=\"$button_font_size $button_text_color $button_border_color\"";
            }

            $registration_btn = '<a href="' . $ef_options['ef_home_registerbtnurl'] . '" title="' . stripslashes($ef_options['ef_home_registerbtntext']) . '" class="video-card__reg" ' . $button_style . '>' . $ef_options['ef_home_registerbtntext'] . '</a>';
        }
        $header_logo_item = '';
        if (!empty($header_logo)) {
            if (isset($ef_options['ef_logo_remove_size'])) {
                $header_logo_size = '';
            } else {
                $header_logo_size = 'width="300" height="101"';
            }
            $header_logo_item = '<img src="' . $header_logo . '"' . $header_logo_size . ' alt="' . get_bloginfo('name') . '">';
        }
        $event_title_color    = !empty($ef_options['ef_herotitle_color']) ? "color:{$ef_options['ef_herotitle_color']};" : '';
        $event_subtitle_color = !empty($ef_options['ef_herosubtitle_color']) ? "color:{$ef_options['ef_herosubtitle_color']};" : '';
        $event_datetime_color = !empty($ef_options['ef_datetimeplace_color']) ? "color:{$ef_options['ef_datetimeplace_color']};" : '';
        $event_title_size     = !empty($ef_options['ef_herotitle_font_size']) ? "font-size:{$ef_options['ef_herotitle_font_size']};" : '';
        $event_subtitle_size  = !empty($ef_options['ef_herosubtitle_font_size']) ? "font-size:{$ef_options['ef_herosubtitle_font_size']};" : '';
        $event_datetime_size  = !empty($ef_options['ef_datetimeplace_font_size']) ? "font-size:{$ef_options['ef_datetimeplace_font_size']};" : '';

        $title_style    = '';
        $subtitle_style = '';
        $date_style     = '';

        if (!empty($event_title_color) || !empty($event_title_size)) {
            $title_style = "style=\"$event_title_color $event_title_size\"";
        }
        if (!empty($event_subtitle_color) || !empty($event_subtitle_size)) {
            $subtitle_style = "style=\"$event_subtitle_color $event_subtitle_size\"";
        }
        if (!empty($event_datetime_color) || !empty($event_datetime_size)) {
            $date_style = "style=\"$event_datetime_color $event_datetime_size\"";
        }

        foreach ($image_ids as $image) {
            $image_url = wp_get_attachment_image_src($image, 'full');
            // Hack para imagem da home traduzivel
            $lang = $GLOBALS['q_config']['language'];
            $images = ['pb' => 'http://emergencias.cultura.gov.br/wp-content/uploads/2015/11/Capa-site-1600x900px-POR-01.jpg', 
                       'en' => 'http://emergencias.cultura.gov.br/wp-content/uploads/2015/11/Capa-site-1600x900px-ENG-01.jpg',
                       'es' => 'http://emergencias.cultura.gov.br/wp-content/uploads/2015/11/Capa-site-1600x900px-ESP-01.jpg'];
            $image_url[0] = $images[$lang];
            // fim do hack
            
            $html      = $html . '<div class="swiper-slide">
                                <div class="video-card" style="background-image:url(' . $image_url[0] . '); background-repeat: no-repeat">
                                    <div>
                                        <div>
                                            <h2 class="video-card__title">' .
                    $header_logo_item .
                    "<span $title_style>" . stripslashes($event_title) . '</span>
                                            </h2>
                                            <p ' . $subtitle_style . '>' . stripslashes($event_s_title) . '</p>
                                            ' . $video_markup . '
                                            <div class="video-card__place"' . $date_style . '>
                                                ' . stripslashes($date_n_time) . '
                                            </div>
                                            ' . $registration_btn . '
                                        </div>
                                    </div>
                                </div>
                            </div>';
        }
        return '<div class="swiper-wrapper">' . $html . '</div>';
    }

    function khore_comment_callback($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);
        ?>
        <!-- comments__item -->
        <div class="comments__item">
            <!-- comments__pic -->
            <div class="comments__pic">
                <?php
                if ($args['avatar_size'] != 0) {
                    echo get_avatar($comment, $args['avatar_size']);
                }
                ?>
            </div>
            <!-- /comments__pic -->
            <!-- comments__text -->
            <div class="comments__text">
                <cite><?php comment_author(); ?></cite>
                <time><?php printf(__('%1$s at %2$s', 'khore'), get_comment_date(), get_comment_time()); ?></time>
                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'khore'); ?></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
                <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('add_below' => 'comments__text', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>
            </div>
            <!-- /comments__text -->
        </div>
        <!-- /comments__item -->
        <?php
    }

    add_filter('comment_form_submit_button', 'khore_comment_form_submit_button', 10, 2);

    function khore_comment_form_submit_button($button, $args) {
        return sprintf('<button name="%s" type="submit" class="%s" id="%s">%s</button>', $args['name_submit'], $args['class_submit'], $args['id_submit'], $args['title_reply']);
    }

    add_action('wp_footer', 'khore_wp_footer', 100);

    function khore_wp_footer() {
        global $khore_footer_scripts;

        if (isset($khore_footer_scripts)) {
            echo '<style type="text/css">';
            foreach ($khore_footer_scripts as $script) {
                echo $script;
            }
            echo '</style>';
        }
    }

    add_filter('admin_post_thumbnail_html', 'khore_admin_post_thumbnail_html');

    function khore_admin_post_thumbnail_html($content) {
        global $post_type;
        $size_text = '';

        switch ($post_type) {
            case 'post':
                $size_text = '531x424';
                break;
            case 'speaker':
                $size_text = '531x424';
                break;
            case 'sponsor':
                $size_text = sprintf('<br/>%s: 213x143<br/>%s: 426x285<br/>%s: 853x570', __('Small', 'khore'), __('Medium', 'khore'), __('Large', 'khore'));
                break;
            case 'session':
                $size_text = '549x549';
                break;
        }

        if (!empty($size_text)) {
            $content .= sprintf('<p>%s: %s</p>', __('Recommended size', 'khore'), $size_text);
        }
        return $content;
    }

    add_filter('ef_post_type_label', 'khore_ef_post_type_label', 10, 3);

    function khore_ef_post_type_label($label, $postType, $count) {
        $ef_options = EF_Event_Options::get_theme_options();
        $label_key  = "ef_{$postType}_label_";
        if ($count > 1) {
            $label_key .= 'plural';
        } else {
            $label_key .= 'singular';
        }
        return !empty($ef_options[$label_key]) ? $ef_options[$label_key] : $label;
    }

    add_action('wp_ajax_dynamic-css', 'khore_wp_ajax_dynamic_css');
    add_action('wp_ajax_nopriv_dynamic-css', 'khore_wp_ajax_dynamic_css');

    function khore_wp_ajax_dynamic_css() {
        require(get_template_directory() . '/css/dynamic.css.php');
        die;
    }

    add_filter('wp_import_nav_menu_item_args', 'khore_wp_import_nav_menu_item_args', 10, 2);

    function khore_wp_import_nav_menu_item_args($args, $metas) {
        if (!empty($metas)) {
            foreach ($metas as $meta) {
                if ($meta['key'] == '_khore_menu_item_custom_icon') {
                    $args['_khore_menu_item_custom_icon'] = $meta['value'];
                    break;
                }
            }
        }

        return $args;
    }

    add_action('wp_update_nav_menu_item', 'khore_wp_update_nav_menu_item', 10, 3);

    function khore_wp_update_nav_menu_item($menu_id, $menu_item_db_id, $args) {
        if (isset($args['_khore_menu_item_custom_icon'])) {
            update_post_meta($menu_item_db_id, '_khore_menu_item_custom_icon', $args['_khore_menu_item_custom_icon']);
        }
    }

    add_filter('ef_schedule_sessions_thumbnail_size', 'khore_ef_schedule_sessions_thumbnail_size');

    function khore_ef_schedule_sessions_thumbnail_size($size) {
        return 'khore-session';
    }
