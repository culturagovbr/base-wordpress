<?php

global $ef_speaker_label_singular, $ef_speaker_label_plural;

$ef_speaker_label_singular = apply_filters('ef_post_type_label', __('Speaker', 'dxef'), 'speaker', 1);
$ef_speaker_label_plural = apply_filters('ef_post_type_label', __('Speakers', 'dxef'), 'speaker', 2);

register_post_type('speaker', array(
    'labels' => array(
        'name' => $ef_speaker_label_plural,
        'singular_name' => $ef_speaker_label_singular,
        'add_new' => __('Add New', 'dxef'),
        'add_new_item' => sprintf(__('Add New %s', 'dxef'), $ef_speaker_label_singular),
        'edit_item' => sprintf(__('Edit %s', 'dxef'), $ef_speaker_label_singular),
        'new_item' => sprintf(__('New %s', 'dxef'), $ef_speaker_label_singular),
        'all_items' => sprintf(__('All %s', 'dxef'), $ef_speaker_label_plural),
        'view_item' => sprintf(__('View %s', 'dxef'), $ef_speaker_label_singular),
        'search_items' => sprintf(__('Search %s', 'dxef'), $ef_speaker_label_plural),
        'not_found' => sprintf(__('No %s found', 'dxef'), $ef_speaker_label_plural),
        'not_found_in_trash' => sprintf(__('No %s found in trash', 'dxef'), $ef_speaker_label_plural),
        'menu_name' => $ef_speaker_label_plural
    ),
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array(
        'slug' => strtolower($ef_speaker_label_plural)
    ),
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array(
        'title',
        'editor',
        'thumbnail'
    )
));

/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 */
function khore_speaker_updated_messages($messages) {

    global $post, $post_ID, $ef_speaker_label_singular;

    $messages['speaker'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf(__('%s updated. <a href="%s">View %s</a>', 'dxef'), $ef_speaker_label_singular, esc_url(get_permalink($post_ID)), $ef_speaker_label_singular),
        2 => __('Custom field updated.', 'dxef'),
        3 => __('Custom field deleted.', 'dxef'),
        4 => sprintf(__('%s updated.', 'dxef'), $ef_speaker_label_singular),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf(__('%s restored to revision from %s', 'dxef'), $ef_speaker_label_singular, wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6 => sprintf(__('%s published. <a href="%s">View %s</a>', 'dxef'), $ef_speaker_label_singular, esc_url(get_permalink($post_ID)), $ef_speaker_label_singular),
        7 => sprintf(__('%s saved.', 'dxef'), $ef_speaker_label_singular),
        8 => sprintf(__('%s submitted. <a target="_blank" href="%s">Preview %s</a>', 'dxef'), $ef_speaker_label_singular, esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))), $ef_speaker_label_singular),
        9 => sprintf(__('%s scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview %s</a>', 'dxef'),
                // translators: Publish box date format, see http://php.net/date
                $ef_speaker_label_singular, date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID)), $ef_speaker_label_singular),
        10 => sprintf(__('%s draft updated. <a target="_blank" href="%s">Preview %s</a>', 'dxef'), $ef_speaker_label_singular, esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))), $ef_speaker_label_singular),
    );

    return $messages;
}

add_filter('post_updated_messages', 'khore_speaker_updated_messages');
