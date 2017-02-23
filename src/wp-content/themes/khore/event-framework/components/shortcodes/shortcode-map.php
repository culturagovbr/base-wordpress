<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * efcb_explore shortcode function.
 *
 *
 * @package Event Framework
 * @since 1.0.0
 */
function efcb_map($atts, $content) {
    $zoom = isset($atts['zoom']) ? $atts['zoom'] : '';
    $zoom = (is_numeric($zoom) ? $zoom : 13);
    $groups = isset($atts['groups']) ? explode('##', $atts['groups']) : array();
    $pois = isset($atts['pois']) ? explode('##', $atts['pois']) : array();
    $pois_grouped = array();

    $style_items = array(
        'section' => array(
            'margin-top' => 'margin_top',
            'margin-bottom' => 'margin_bottom',
        ),
        'group' => array(
            'color' => 'group_color',
            'font-size' => 'group_font_size',
            'background-color' => 'background_color',
        ),
        'location' => array(
            'color' => 'location_color',
            'font-size' => 'location_font_size',
            'background-color' => 'background_color',
        ),
    );
    //add_filter('posts_fields', 'khore_shortcode_home_pois_fields');

    if (!empty($pois)) {
        foreach ($pois as $pois_slice) {
            $pois_arr = array();
            $pois_db = get_posts(
                    array(
                        'post_type' => 'poi',
                        'posts_per_page' => -1,
                        'suppress_filters' => false,
                        'post__in' => explode(',', $pois_slice),
                        'meta_query' => array(
                            array(
                                'key' => 'poi_address',
                                'compare' => 'EXISTS',
                            ),
                            array(
                                'key' => 'poi_latitude',
                                'compare' => 'EXISTS',
                            ),
                            array(
                                'key' => 'poi_longitude',
                                'compare' => 'EXISTS',
                            )
                        )
                    )
            );

            foreach ($pois_db as $poi_db) {
                $pois_arr[] = array(
                    'ID' => $poi_db->ID,
                    'poi_address' => sprintf('<strong>%s</strong><br/>%s', $poi_db->post_title, $poi_db->poi_address),
                    'poi_latitude' => $poi_db->poi_latitude,
                    'poi_longitude' => $poi_db->poi_longitude,
                    'poi_title' => $poi_db->post_title
                );
            }
            $pois_grouped[] = $pois_arr;
        }
        remove_filter('posts_fields', 'khore_shortcode_home_pois_fields');

        echo apply_filters('efcb_shortcode_render', '', 'efcb_map', array(
            'zoom' => $zoom,
            'styles' => EF_Framework_Helper::get_styles_from_shortcode_attributes($style_items, $atts),
            'groups' => $groups,
            'pois_grouped' => $pois_grouped
        /* 'pois_arr' => json_encode($pois_arr),
          'poi_groups' => $groups */        ));
    }
}

// Register Shortcode
add_shortcode('efcb-section-map', 'efcb_map');

function khore_shortcode_home_pois_fields($fields) {

    global $wpdb;
    return $fields . ", $wpdb->postmeta.meta_value AS khore_address, mt2.meta_value AS khore_latitude, mt1.meta_value AS khore_longitude";
}

function khore_shortcode_frontend_scripts() {
    ?>
    <script type="text/javascript">
        var poi_marker = '<?php echo get_template_directory_uri(); ?>/img/location__marker.png';
    </script>
    <?php
}

//add action for frontend scripts
add_action('wp_head', 'khore_shortcode_frontend_scripts');



